<?php
/**
 * The template for displaying archive pages
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
                        <?php
                        the_archive_title('<h1 class="page-title">', '</h1>');
                        the_archive_description('<div class="archive-description">', '</div>');
                        ?>
                    </header><!-- .page-header -->
                    
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <?php
                        /* Start the Loop */
                        while (have_posts()) :
                            the_post();
                            ?>
                            
                            <div class="col">
                                <div class="card h-100 comic-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="card-img-link">
                                        <?php the_post_thumbnail('medium', array('class' => 'card-img-top')); ?>
                                    </a>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h5>
                                        <p class="card-text small text-muted">
                                            <i class="fas fa-calendar-alt"></i> <?php echo get_the_date(); ?>
                                            <?php if (get_post_meta(get_the_ID(), 'post_views_count', true)) : ?>
                                            <span class="ms-3">
                                                <i class="fas fa-eye"></i> <?php echo get_post_meta(get_the_ID(), 'post_views_count', true); ?> lượt xem
                                            </span>
                                            <?php endif; ?>
                                        </p>
                                        <div class="card-text">
                                            <?php the_excerpt(); ?>
                                        </div>
                                    </div>
                                    <div class="card-footer text-center">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary">Đọc tiếp</a>
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

                    <div class="alert alert-info">
                        <?php esc_html_e('Không có bài viết nào.', 'comic-theme'); ?>
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
