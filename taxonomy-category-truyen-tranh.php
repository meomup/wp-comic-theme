<?php
/**
 * The template for displaying Truyện Tranh category
 *
 * @package Comic_Theme
 */

get_header();
$category = get_queried_object();
?>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <main id="primary" class="site-main">
                <header class="category-header mb-4">
                    <h1 class="category-title"><?php echo esc_html($category->name); ?></h1>
                    <?php if (!empty($category->description)) : ?>
                        <div class="category-description">
                            <?php echo wpautop(esc_html($category->description)); ?>
                        </div>
                    <?php endif; ?>
                </header>

                <div class="comic-directory">
                    <?php
                    // Get all subcategories (comics)
                    $comics = get_categories(array(
                        'parent' => $category->term_id,
                        'hide_empty' => 0,
                    ));

                    if (!empty($comics)) :
                        foreach ($comics as $comic) :
                            $image_url = comic_theme_get_category_image_url($comic->term_id);
                            $default_image = get_template_directory_uri() . '/img/default-comic.jpg';
                            
                            // Get chapter count
                            $chapter_count = $comic->count;
                            
                            // Get latest chapter
                            $latest_post = get_posts(array(
                                'numberposts' => 1,
                                'category' => $comic->term_id,
                                'orderby' => 'date',
                                'order' => 'DESC',
                            ));
                            
                            $latest_date = !empty($latest_post) ? get_the_date('d/m/Y', $latest_post[0]->ID) : '';
                            $latest_title = !empty($latest_post) ? $latest_post[0]->post_title : '';
                            $latest_link = !empty($latest_post) ? get_permalink($latest_post[0]->ID) : '';
                    ?>
                        <div class="comic-card">
                            <a href="<?php echo esc_url(get_category_link($comic->term_id)); ?>" class="card-img-link">
                                <img src="<?php echo esc_url($image_url ? $image_url : $default_image); ?>" alt="<?php echo esc_attr($comic->name); ?>" class="card-img-top comic-cover">
                            </a>
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="<?php echo esc_url(get_category_link($comic->term_id)); ?>"><?php echo esc_html($comic->name); ?></a>
                                </h3>
                                <?php if (!empty($latest_post)) : ?>
                                    <p class="card-text">
                                        <a href="<?php echo esc_url($latest_link); ?>"><?php echo esc_html($latest_title); ?></a>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> <?php echo esc_html($latest_date); ?>
                                        </small>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer text-center">
                                <a href="<?php echo esc_url(get_category_link($comic->term_id)); ?>" class="btn btn-sm btn-primary">Xem truyện</a>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    else :
                    ?>
                        <div class="alert alert-info">
                            <p>Chưa có truyện nào được đăng.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main><!-- #main -->
        </div>
        
        <div class="col-lg-4">
            <?php include(get_template_directory() . '/sidebar-comic.php'); ?>
        </div>
    </div>
</div>

<?php
get_footer();
