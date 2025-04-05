<?php
/**
 * The template for displaying category archive pages
 *
 * @package Comic_Theme
 */

get_header();

// Get current category
$category = get_queried_object();
$is_comic = is_comic_category($category->term_id);
$category_image = comic_theme_get_category_image_url($category->term_id);
$default_image = get_template_directory_uri() . '/img/default-comic.jpg';

// Find parent if it's a comic subcategory
$parent_category = null;
if ($category->parent) {
    $parent_category = get_term($category->parent, 'category');
}
?>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <main id="primary" class="site-main">
                <?php if ($is_comic && $category->slug !== 'truyen-tranh') : ?>
                    <!-- This is a specific comic category, show chapters -->
                    <div class="comic-detail">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <?php if ($category_image) : ?>
                                            <img src="<?php echo esc_url($category_image); ?>" alt="<?php echo esc_attr($category->name); ?>" class="img-fluid comic-cover">
                                        <?php else : ?>
                                            <img src="<?php echo esc_url($default_image); ?>" alt="<?php echo esc_attr($category->name); ?>" class="img-fluid comic-cover">
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-8">
                                        <nav aria-label="breadcrumb" class="mb-3">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a></li>
                                                <?php if ($parent_category) : ?>
                                                    <li class="breadcrumb-item"><a href="<?php echo esc_url(get_category_link($parent_category->term_id)); ?>"><?php echo esc_html($parent_category->name); ?></a></li>
                                                <?php endif; ?>
                                                <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html($category->name); ?></li>
                                            </ol>
                                        </nav>
                                        
                                        <h1 class="card-title"><?php echo esc_html($category->name); ?></h1>
                                        
                                        <?php if (!empty($category->description)) : ?>
                                            <div class="comic-description mb-3">
                                                <?php echo wpautop(esc_html($category->description)); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="comic-stats mb-3">
                                            <span class="badge bg-primary"><i class="fas fa-bookmark"></i> <?php echo esc_html($category->count); ?> Chapter</span>
                                            <span class="badge bg-success"><i class="fas fa-clock"></i> Cập nhật: <?php echo esc_html(get_latest_post_date($category->term_id)); ?></span>
                                        </div>
                                        
                                        <div class="d-grid gap-2 d-md-block">
                                            <?php 
                                            // Get first and last chapter
                                            $first_chapter = get_posts(array(
                                                'numberposts' => 1,
                                                'category' => $category->term_id,
                                                'orderby' => 'date',
                                                'order' => 'ASC',
                                            ));
                                            
                                            $last_chapter = get_posts(array(
                                                'numberposts' => 1,
                                                'category' => $category->term_id,
                                                'orderby' => 'date',
                                                'order' => 'DESC',
                                            ));
                                            
                                            if (!empty($first_chapter)) : 
                                            ?>
                                                <a href="<?php echo esc_url(get_permalink($first_chapter[0]->ID)); ?>" class="btn btn-outline-primary">
                                                    <i class="fas fa-fast-backward"></i> Chapter đầu
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($last_chapter)) : ?>
                                                <a href="<?php echo esc_url(get_permalink($last_chapter[0]->ID)); ?>" class="btn btn-primary">
                                                    Chapter mới nhất <i class="fas fa-fast-forward"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h3 class="card-title h5 m-0">Danh sách Chapter</h3>
                            </div>
                            <div class="card-body p-0">
                                <?php
                                if (have_posts()) :
                                    echo '<div class="table-responsive">';
                                    echo '<table class="table table-hover chapter-table mb-0">';
                                    echo '<thead>
                                            <tr>
                                                <th>Chapter</th>
                                                <th>Ngày đăng</th>
                                                <th>Lượt xem</th>
                                            </tr>
                                          </thead>';
                                    echo '<tbody>';
                                    
                                    // List all posts in this category
                                    $chapter_args = array(
                                        'posts_per_page' => -1,
                                        'category' => $category->term_id,
                                        'orderby' => 'date',
                                        'order' => 'DESC',
                                    );
                                    
                                    $chapters = get_posts($chapter_args);
                                    
                                    foreach ($chapters as $chapter) :
                                        $views = get_post_meta($chapter->ID, 'post_views_count', true) ?: '0';
                                    ?>
                                        <tr>
                                            <td><a href="<?php echo esc_url(get_permalink($chapter->ID)); ?>"><?php echo esc_html($chapter->post_title); ?></a></td>
                                            <td><i class="fas fa-calendar-alt"></i> <?php echo get_the_date('', $chapter->ID); ?></td>
                                            <td><i class="fas fa-eye"></i> <?php echo esc_html($views); ?></td>
                                        </tr>
                                    <?php
                                    endforeach;
                                    wp_reset_postdata();
                                    
                                    echo '</tbody>';
                                    echo '</table>';
                                    echo '</div>';
                                    
                                else :
                                    echo '<div class="alert alert-info m-3">Chưa có chapter nào được đăng.</div>';
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                <?php elseif ($category->slug === 'truyen-tranh') : ?>
                    <!-- This is the main "Truyện Tranh" category, show list of comics -->
                    <header class="page-header mb-4">
                        <h1 class="page-title"><?php echo esc_html($category->name); ?></h1>
                        <?php if (!empty($category->description)) : ?>
                            <div class="category-description">
                                <?php echo wpautop(esc_html($category->description)); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        <?php
                        // Get all subcategories
                        $comics = get_categories(array(
                            'parent' => $category->term_id,
                            'hide_empty' => 0,
                        ));

                        foreach ($comics as $comic) :
                            $image_url = comic_theme_get_category_image_url($comic->term_id);
                            ?>
                            
                            <div class="col">
                                <div class="card h-100 comic-card">
                                    <a href="<?php echo esc_url(get_category_link($comic->term_id)); ?>" class="card-img-link">
                                        <?php if ($image_url) : ?>
                                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($comic->name); ?>" class="card-img-top comic-cover">
                                        <?php else : ?>
                                            <img src="<?php echo esc_url($default_image); ?>" alt="<?php echo esc_attr($comic->name); ?>" class="card-img-top comic-cover">
                                        <?php endif; ?>
                                    </a>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="<?php echo esc_url(get_category_link($comic->term_id)); ?>"><?php echo esc_html($comic->name); ?></a>
                                        </h5>
                                        <?php
// Get latest post of this category
$latest_post = get_posts(array(
    'numberposts' => 1,
    'category' => $comic->term_id,
    'orderby' => 'date',
    'order' => 'DESC',
));

if (!empty($latest_post)) :
    $latest = $latest_post[0];
?>
    <p class="card-text">
        <a href="<?php echo esc_url(get_permalink($latest->ID)); ?>"><?php echo esc_html($latest->post_title); ?></a>
        <br>
        <small class="text-muted">
            <i class="fas fa-clock"></i> <?php echo get_the_date('d/m/Y', $latest->ID); ?>
        </small>
    </p>
<?php endif; ?>

                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="<?php echo esc_url(get_category_link($comic->term_id)); ?>" class="btn btn-sm btn-primary">Xem truyện</a>
                                    </div>
                                </div>
                            </div>
                            
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <!-- Regular category -->
                    <header class="page-header mb-4">
                        <h1 class="page-title"><?php single_cat_title(); ?></h1>
                        <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
                    </header>

                    <?php if (have_posts()) : ?>

                        <div class="row">
                            <?php
                            while (have_posts()) :
                                the_post();
                            ?>
                                <div class="col-md-6 mb-4">
                                    <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100'); ?>>
                                        <?php if (has_post_thumbnail()) : ?>
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <div class="card-body">
                                            <h2 class="card-title h5">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h2>
                                            
                                            <div class="card-text">
                                                <?php the_excerpt(); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="card-footer text-muted">
                                            <small>
                                                <i class="fas fa-calendar-alt"></i> <?php echo get_the_date(); ?>
                                            </small>
                                        </div>
                                    </article>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <?php the_posts_pagination(); ?>

                    <?php else : ?>

                        <p><?php esc_html_e('Không có bài viết nào.', 'comic-theme'); ?></p>

                    <?php endif; ?>
                <?php endif; ?>
            </main><!-- #main -->
        </div>
        
        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div>
    </div>
</div>

<?php
// Helper function to get the latest post date in a category
function get_latest_post_date($cat_id) {
    $latest_post = get_posts(array(
        'numberposts' => 1,
        'category' => $cat_id,
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    
    if (!empty($latest_post)) {
        return get_the_date('d/m/Y', $latest_post[0]->ID);
    }
    
    return 'N/A';
}

get_footer();
