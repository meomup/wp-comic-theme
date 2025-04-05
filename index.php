<?php
/**
 * The main template file
 *
 * @package Comic_Theme
 */

get_header();
?>

<!-- Hero Section -->
<section class="site-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto text-center">
                <h1><?php bloginfo('name'); ?></h1>
                <p class="lead"><?php bloginfo('description'); ?></p>
                <div class="mt-4">
                    <a href="<?php echo esc_url(get_category_link(get_category_by_slug('truyen-tranh')->term_id)); ?>" class="btn btn-light btn-lg me-2">
                        <i class="fas fa-book-open me-2"></i>Đọc truyện
                    </a>
                    <a href="#latest-posts" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-newspaper me-2"></i>Bài viết mới
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <main id="primary" class="site-main">
                <section id="latest-posts" class="mb-5">
                    <header class="section-header">
                        <h2 class="text-center mb-4">Bài viết mới nhất</h2>
                    </header>

                    <div class="row row-cols-1 g-4">
                        <?php
                        // Exclude comics category and its subcategories
                        $comics_category = get_term_by('slug', 'truyen-tranh', 'category');
                        $exclude_categories = array();
                        
                        if ($comics_category) {
                            $exclude_categories[] = $comics_category->term_id;
                            
                            $subcategories = get_categories(array(
                                'parent' => $comics_category->term_id,
                                'hide_empty' => 0,
                            ));
                            
                            foreach ($subcategories as $subcategory) {
                                $exclude_categories[] = $subcategory->term_id;
                            }
                        }
                        
                        // Create a query that excludes comics
                        $args = array(
                            'category__not_in' => $exclude_categories,
                            'posts_per_page' => 5
                        );
                        
                        $latest_posts = new WP_Query($args);
                        
                        if ($latest_posts->have_posts()) :
                            while ($latest_posts->have_posts()) :
                                $latest_posts->the_post();
                        ?>
                            <div class="col">
                                <article id="post-<?php the_ID(); ?>" <?php post_class('card h-100 blog-card'); ?>>
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <a href="<?php the_permalink(); ?>" class="card-img-link h-100">
                                                    <?php the_post_thumbnail('medium', array('class' => 'img-fluid rounded-start h-100 object-fit-cover')); ?>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php the_permalink(); ?>" class="card-img-link h-100">
                                                    <div class="bg-light d-flex align-items-center justify-content-center h-100 rounded-start">
                                                        <i class="fas fa-file-alt fa-3x text-muted"></i>
                                                    </div>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body d-flex flex-column h-100">
                                                <div class="mb-2">
                                                    <?php
                                                    $categories = get_the_category();
                                                    foreach ($categories as $category) {
                                                        if (!in_array($category->term_id, $exclude_categories)) {
                                                            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="badge bg-primary text-decoration-none me-1">' . 
                                                                esc_html($category->name) . '</a>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                
                                                <h3 class="card-title h5">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h3>
                                                
                                                <div class="card-text mb-2">
                                                    <?php the_excerpt(); ?>
                                                </div>
                                                
                                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                                    <div class="post-meta small text-muted">
                                                        <i class="fas fa-calendar-alt"></i> <?php echo get_the_date(); ?>
                                                    </div>
                                                    <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary">Đọc tiếp</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php 
                            endwhile;
                            wp_reset_postdata();
                        else :
                        ?>
                            <div class="col">
                                <div class="alert alert-info">
                                    <p>Chưa có bài viết nào.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="btn btn-primary">
                            Xem tất cả bài viết <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </section>
                
                <section class="featured-posts mb-5">
                    <header class="section-header">
                        <h2 class="text-center mb-4">Danh mục nổi bật</h2>
                    </header>
                    
                    <div class="row g-4">
                        <?php 
                        // Get categories excluding comics
                        $args = array(
                            'exclude' => $exclude_categories,
                            'hide_empty' => 0,
                            'parent' => 0, // Only top-level categories
                            'number' => 4
                        );
                        
                        $categories = get_categories($args);
                        
                        foreach ($categories as $category) :
                        ?>
                            <div class="col-md-6">
                                <div class="card category-card h-100">
                                    <div class="card-body">
                                        <h3 class="category-title">
                                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                                                <i class="fas fa-folder me-2"></i><?php echo esc_html($category->name); ?>
                                            </a>
                                        </h3>
                                        <p class="category-description">
                                            <?php echo category_description($category->term_id) ?: 'Xem tất cả bài viết trong danh mục ' . $category->name; ?>
                                        </p>
                                        <p class="category-count">
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-file-alt me-1"></i><?php echo esc_html($category->count); ?> bài viết
                                            </span>
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="btn btn-outline-primary btn-sm">
                                            Xem danh mục <i class="fas fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </main><!-- #main -->
        </div>
        
        <div class="col-lg-4">
            <?php 
            // Custom sidebar for home page without comic info
            include(get_template_directory() . '/sidebar-home.php'); 
            ?>
        </div>
    </div>
</div>

<?php
get_footer();
