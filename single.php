<?php
/**
 * The template for displaying all single posts
 *
 * @package Comic_Theme
 */

get_header();

// Increase post views count
$post_views = get_post_meta(get_the_ID(), 'post_views_count', true);
$post_views = ($post_views) ? $post_views + 1 : 1;
update_post_meta(get_the_ID(), 'post_views_count', $post_views);

// Get current post category
$categories = get_the_category();
$current_category = !empty($categories) ? $categories[0] : null;

// Check if this is a comic chapter (belongs to a comic category)
$is_comic_chapter = false;
if ($current_category && is_comic_category($current_category->term_id)) {
    $is_comic_chapter = true;
}
?>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <main id="primary" class="site-main">
                <?php
                while (have_posts()) :
                    the_post();
                
                    if ($is_comic_chapter) :
                        // Comic chapter template
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('comic-chapter'); ?>>
                    <div class="chapter-header mb-4">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>">Trang chủ</a></li>
                                <?php if ($current_category) : ?>
                                    <?php if ($current_category->parent) : ?>
                                        <?php $parent_cat = get_term($current_category->parent, 'category'); ?>
                                        <li class="breadcrumb-item"><a href="<?php echo esc_url(get_category_link($parent_cat->term_id)); ?>"><?php echo esc_html($parent_cat->name); ?></a></li>
                                    <?php endif; ?>
                                    <li class="breadcrumb-item"><a href="<?php echo esc_url(get_category_link($current_category->term_id)); ?>"><?php echo esc_html($current_category->name); ?></a></li>
                                <?php endif; ?>
                                <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
                            </ol>
                        </nav>
                        
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                        
                        <div class="entry-meta mb-3">
                            <span class="posted-on">
                                <i class="fas fa-calendar-alt"></i> <?php the_date(); ?>
                            </span>
                            <span class="views-count ms-3">
                                <i class="fas fa-eye"></i> <?php echo esc_html($post_views); ?> lượt xem
                            </span>
                        </div>
                        
                        <div class="chapter-navigation mb-3">
                            <?php
                            // Get adjacent posts in same category
                            if ($current_category) {
                                $prev_chapter = get_previous_post(true, '', 'category');
                                $next_chapter = get_next_post(true, '', 'category');
                                
                                // Get first and last chapters
                                $first_chapter = get_posts(array(
                                    'numberposts' => 1,
                                    'category' => $current_category->term_id,
                                    'orderby' => 'date',
                                    'order' => 'ASC',
                                ));
                                
                                $last_chapter = get_posts(array(
                                    'numberposts' => 1,
                                    'category' => $current_category->term_id,
                                    'orderby' => 'date',
                                    'order' => 'DESC',
                                ));
                                
                                $first_chapter_id = !empty($first_chapter) ? $first_chapter[0]->ID : 0;
                                $last_chapter_id = !empty($last_chapter) ? $last_chapter[0]->ID : 0;
                            }
                            ?>
                            
                            <div class="btn-group" role="group" aria-label="Chapter navigation">
                                <?php if (!empty($first_chapter) && $first_chapter_id != get_the_ID()) : ?>
                                    <a href="<?php echo esc_url(get_permalink($first_chapter_id)); ?>" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Chapter đầu tiên">
                                        <i class="fas fa-fast-backward"></i>
                                    </a>
                                <?php else : ?>
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="fas fa-fast-backward"></i>
                                    </button>
                                <?php endif; ?>
                                
                                <?php if (!empty($prev_chapter)) : ?>
                                    <a href="<?php echo esc_url(get_permalink($prev_chapter->ID)); ?>" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Chapter trước">
                                        <i class="fas fa-step-backward"></i> Chapter trước
                                    </a>
                                <?php else : ?>
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="fas fa-step-backward"></i> Chapter trước
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($current_category) : ?>
                                    <a href="<?php echo esc_url(get_category_link($current_category->term_id)); ?>" class="btn btn-primary">
                                        <i class="fas fa-list"></i> Danh sách chapter
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($next_chapter)) : ?>
                                    <a href="<?php echo esc_url(get_permalink($next_chapter->ID)); ?>" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Chapter sau">
                                        Chapter sau <i class="fas fa-step-forward"></i>
                                    </a>
                                <?php else : ?>
                                    <button class="btn btn-outline-secondary" disabled>
                                        Chapter sau <i class="fas fa-step-forward"></i>
                                    </button>
                                <?php endif; ?>
                                
                                <?php if (!empty($last_chapter) && $last_chapter_id != get_the_ID()) : ?>
                                    <a href="<?php echo esc_url(get_permalink($last_chapter_id)); ?>" class="btn btn-outline-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Chapter mới nhất">
                                        <i class="fas fa-fast-forward"></i>
                                    </a>
                                <?php else : ?>
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="fas fa-fast-forward"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="entry-content comic-content">
                        <?php the_content(); ?>
                    </div><!-- .entry-content -->
                    
                    <div class="chapter-footer mt-4">
                        <div class="chapter-navigation mb-3">
                            <div class="btn-group w-100" role="group" aria-label="Chapter navigation">
                                <?php if (!empty($prev_chapter)) : ?>
                                    <a href="<?php echo esc_url(get_permalink($prev_chapter->ID)); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-step-backward"></i> Chapter trước
                                    </a>
                                <?php else : ?>
                                    <button class="btn btn-outline-secondary" disabled>
                                        <i class="fas fa-step-backward"></i> Chapter trước
                                    </button>
                                <?php endif; ?>
                                
                                <?php if ($current_category) : ?>
                                    <a href="<?php echo esc_url(get_category_link($current_category->term_id)); ?>" class="btn btn-primary">
                                        <i class="fas fa-list"></i> Danh sách chapter
                                    </a>
                                <?php endif; ?>
                                
                                <?php if (!empty($next_chapter)) : ?>
                                    <a href="<?php echo esc_url(get_permalink($next_chapter->ID)); ?>" class="btn btn-outline-primary">
                                        Chapter sau <i class="fas fa-step-forward"></i>
                                    </a>
                                <?php else : ?>
                                    <button class="btn btn-outline-secondary" disabled>
                                        Chapter sau <i class="fas fa-step-forward"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="chapter-comments mt-4">
                            <?php
                            // If comments are open or we have at least one comment, load up the comment template.
                            if (comments_open() || get_comments_number()) :
                                comments_template();
                            endif;
                            ?>
                        </div>
                    </div>
                </article><!-- #post-<?php the_ID(); ?> -->
                
                <?php else : ?>
                    <!-- Regular post template -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header mb-4">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <i class="fas fa-calendar-alt"></i> <?php the_date(); ?>
                                </span>
                                <span class="views-count ms-3">
                                    <i class="fas fa-eye"></i> <?php echo esc_html($post_views); ?> lượt xem
                                </span>
                            </div>
                        </header>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="entry-thumbnail mb-4">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid rounded')); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                        
                        <footer class="entry-footer mt-4">
                            <?php
                            // Display categories and tags
                            $categories_list = get_the_category_list(', ');
                            if ($categories_list) {
                                echo '<div class="cat-links mb-2"><i class="fas fa-folder-open"></i> ' . $categories_list . '</div>';
                            }
                            
                            $tags_list = get_the_tag_list('', ', ');
                            if ($tags_list) {
                                echo '<div class="tags-links"><i class="fas fa-tags"></i> ' . $tags_list . '</div>';
                            }
                            ?>
                        </footer>
                        
                        <div class="post-navigation mt-4">
                            <?php
                            the_post_navigation(array(
                                'prev_text' => '<i class="fas fa-chevron-left"></i> %title',
                                'next_text' => '%title <i class="fas fa-chevron-right"></i>',
                            ));
                            ?>
                        </div>
                        
                        <?php
                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) :
                            comments_template();
                        endif;
                        ?>
                    </article>
                <?php endif; ?>
                
                <?php endwhile; // End of the loop. ?>
            </main><!-- #main -->
        </div>
    </div>
</div>

<?php
get_footer();
