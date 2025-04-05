<?php
/**
 * The template for displaying search results pages
 *
 * @package Comic_Theme
 */

get_header();
?>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <main id="primary" class="site-main">
                <?php if (have_posts()) : ?>
                    <header class="page-header mb-4">
                        <h1 class="page-title">
                            <?php
                            /* translators: %s: search query. */
                            printf(esc_html__('Kết quả tìm kiếm cho: %s', 'comic-theme'), '<span>' . get_search_query() . '</span>');
                            ?>
                        </h1>
                        <div class="search-meta">
                            <?php printf(esc_html(_n('Tìm thấy %s kết quả', 'Tìm thấy %s kết quả', $wp_query->found_posts, 'comic-theme')), number_format_i18n($wp_query->found_posts)); ?>
                        </div>
                    </header><!-- .page-header -->
                    
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <?php
                        /* Start the Loop */
                        while (have_posts()) :
                            the_post();
                            
                            // Lấy thông tin về category
                            $categories = get_the_category();
                            $is_comic_chapter = false;
                            
                            if (!empty($categories)) {
                                foreach ($categories as $category) {
                                    if (is_comic_category($category->term_id)) {
                                        $is_comic_chapter = true;
                                        break;
                                    }
                                }
                            }
                            ?>
                            
                            <div class="col">
                                <div class="card h-100 comic-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="card-img-link">
                                        <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>
                                    </a>
                                    <?php else: ?>
                                        <div class="card-img-top text-center py-5 bg-light">
                                            <i class="fas <?php echo $is_comic_chapter ? 'fa-book-open' : 'fa-file-alt'; ?> fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h5>
                                        
                                        <div class="card-text small text-muted mb-2">
                                            <i class="fas fa-calendar-alt"></i> <?php echo get_the_date(); ?>
                                            
                                            <?php if (!empty($categories)) : ?>
                                                <span class="ms-2">
                                                    <i class="fas fa-folder"></i> 
                                                    <?php 
                                                    $cat_names = array();
                                                    foreach ($categories as $category) {
                                                        $cat_names[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                                                    }
                                                    echo implode(', ', $cat_names);
                                                    ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if (get_post_meta(get_the_ID(), 'post_views_count', true)) : ?>
                                                <span class="ms-2">
                                                    <i class="fas fa-eye"></i> <?php echo get_post_meta(get_the_ID(), 'post_views_count', true); ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="card-text">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <?php if ($is_comic_chapter) : ?>
                                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary">Đọc Chapter</a>
                                        <?php else : ?>
                                            <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary">Đọc tiếp</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="mt-4">
                        <?php the_posts_pagination(array(
                            'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Previous', 'comic-theme'),
                            'next_text' => __('Next', 'comic-theme') . ' <i class="fas fa-chevron-right"></i>',
                            'class' => 'pagination justify-content-center',
                        )); ?>
                    </div>

                <?php else : ?>
                    <div class="search-no-results">
                        <div class="alert alert-info">
                            <h3><i class="fas fa-search me-2"></i><?php esc_html_e('Không tìm thấy kết quả', 'comic-theme'); ?></h3>
                            <p><?php esc_html_e('Không tìm thấy kết quả nào phù hợp với từ khóa tìm kiếm. Vui lòng thử lại với từ khóa khác.', 'comic-theme'); ?></p>
                        </div>
                        
                        <div class="search-suggestions mt-4">
                            <h4><?php esc_html_e('Gợi ý', 'comic-theme'); ?></h4>
                            <ul>
                                <li><?php esc_html_e('Kiểm tra lỗi chính tả của từ khóa tìm kiếm.', 'comic-theme'); ?></li>
                                <li><?php esc_html_e('Thử sử dụng các từ khóa đơn giản hơn.', 'comic-theme'); ?></li>
                                <li><?php esc_html_e('Thử sử dụng từ khóa khác với ý nghĩa tương tự.', 'comic-theme'); ?></li>
                            </ul>
                        </div>
                        
                        <div class="search-form mt-4">
                            <h4><?php esc_html_e('Tìm kiếm lại', 'comic-theme'); ?></h4>
                            <?php get_search_form(); ?>
                        </div>
                        
                        <div class="search-explore mt-5">
                            <h4><?php esc_html_e('Khám phá truyện tranh', 'comic-theme'); ?></h4>
                            <?php 
                            // Hiển thị 3 truyện ngẫu nhiên
                            $comics_category = get_term_by('slug', 'truyen-tranh', 'category');
                            
                            if ($comics_category) {
                                $comics = get_categories(array(
                                    'parent' => $comics_category->term_id,
                                    'hide_empty' => 0,
                                    'number' => 3,
                                    'orderby' => 'rand'
                                ));
                                
                                if (!empty($comics)) {
                                    echo '<div class="row row-cols-1 row-cols-md-3 g-4">';
                                    
                                    foreach ($comics as $comic) {
                                        $image_url = comic_theme_get_category_image_url($comic->term_id);
                                        $default_image = get_template_directory_uri() . '/img/default-comic.jpg';
                                        
                                        echo '<div class="col">';
                                        echo '<div class="card h-100 comic-card">';
                                        
                                        echo '<a href="' . esc_url(get_category_link($comic->term_id)) . '" class="card-img-link">';
                                        if ($image_url) {
                                            echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($comic->name) . '" class="card-img-top comic-cover">';
                                        } else {
                                            echo '<img src="' . esc_url($default_image) . '" alt="' . esc_attr($comic->name) . '" class="card-img-top comic-cover">';
                                        }
                                        echo '</a>';
                                        
                                        echo '<div class="card-body">';
                                        echo '<h5 class="card-title"><a href="' . esc_url(get_category_link($comic->term_id)) . '">' . esc_html($comic->name) . '</a></h5>';
                                        echo '</div>';
                                        
                                        echo '<div class="card-footer text-center">';
                                        echo '<a href="' . esc_url(get_category_link($comic->term_id)) . '" class="btn btn-sm btn-primary">Xem truyện</a>';
                                        echo '</div>';
                                        
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                    
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </main><!-- #main -->
        </div>
        
        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php
get_footer();
