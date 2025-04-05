<?php
/**
 * Template Name: Blog Page
 *
 * @package Comic_Theme
 */

get_header();

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
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$args = array(
    'category__not_in' => $exclude_categories,
    'paged' => $paged
);
$blog_query = new WP_Query($args);
?>

<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <main id="primary" class="site-main">
                <header class="page-header mb-4">
                    <h1 class="page-title">Blog</h1>
                </header>

                <?php if ($blog_query->have_posts()) : ?>
                    <div class="row row-cols-1 g-4">
                        <?php while ($blog_query->have_posts()) : $blog_query->the_post(); ?>
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
                                                
                                                <h2 class="card-title h5">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h2>
                                                
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
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="pagination-container mt-4">
                        <?php
                        echo paginate_links(array(
                            'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $blog_query->max_num_pages,
                            'prev_text' => '<i class="fas fa-chevron-left"></i>',
                            'next_text' => '<i class="fas fa-chevron-right"></i>',
                            'type' => 'list'
                        ));
                        ?>
                    </div>
                    
                    <?php
                    wp_reset_postdata();
                    
                else :
                ?>
                    <div class="alert alert-info">
                        <p>Chưa có bài viết nào.</p>
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
