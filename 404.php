<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Comic_Theme
 */

get_header();
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <main id="primary" class="site-main">
                <section class="error-404 not-found text-center">
                    <div class="error-image mb-4">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/404.svg" alt="404 Error" class="img-fluid" style="max-height: 300px;">
                    </div>
                    
                    <header class="page-header">
                        <h1 class="page-title"><?php esc_html_e('Oops! Trang không tồn tại', 'comic-theme'); ?></h1>
                    </header><!-- .page-header -->

                    <div class="page-content">
                        <p><?php esc_html_e('Có vẻ như trang bạn đang tìm kiếm không tồn tại hoặc đã bị di chuyển đi nơi khác.', 'comic-theme'); ?></p>
                        
                        <div class="error-actions mt-4">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                <i class="fas fa-home me-2"></i><?php esc_html_e('Về trang chủ', 'comic-theme'); ?>
                            </a>
                            
                            <button class="btn btn-outline-secondary ms-2" onclick="window.history.back();">
                                <i class="fas fa-arrow-left me-2"></i><?php esc_html_e('Quay lại trang trước', 'comic-theme'); ?>
                            </button>
                        </div>
                        
                        <div class="mt-5">
                            <h3><?php esc_html_e('Có lẽ bạn muốn tìm kiếm?', 'comic-theme'); ?></h3>
                            <div class="search-form-404 w-75 mx-auto mt-3">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                        
                        <div class="error-suggestions mt-5">
                            <h3><?php esc_html_e('Hoặc khám phá một số truyện hay', 'comic-theme'); ?></h3>
                            
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
                                    echo '<div class="row row-cols-1 row-cols-md-3 g-4 mt-3">';
                                    
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
                    </div><!-- .page-content -->
                </section><!-- .error-404 -->
            </main><!-- #main -->
        </div>
    </div>
</div>

<?php
get_footer();
