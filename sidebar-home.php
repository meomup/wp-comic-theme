<?php
/**
 * The sidebar for homepage (without comic content)
 *
 * @package Comic_Theme
 */

?>

<aside id="secondary" class="widget-area">
    <div class="sidebar-card">
        <div class="card-header">
            <i class="fas fa-tags me-2"></i><?php esc_html_e('Danh mục', 'comic-theme'); ?>
        </div>
        <div class="card-body">
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
            
            // Get categories excluding comics
            $args = array(
                'exclude' => $exclude_categories,
                'hide_empty' => 0,
            );
            
            $categories = get_categories($args);
            
            if (!empty($categories)) {
                echo '<ul class="category-list">';
                
                foreach ($categories as $category) {
                    echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '"><i class="fas fa-folder"></i> <span>' . esc_html($category->name) . '</span> <span class="badge bg-light text-dark">' . $category->count . '</span></a></li>';
                }
                
                echo '</ul>';
            } else {
                echo '<div class="alert alert-info">Chưa có danh mục nào.</div>';
            }
            ?>
        </div>
    </div>
    
    <div class="sidebar-card">
        <div class="card-header">
            <i class="fas fa-search me-2"></i><?php esc_html_e('Tìm kiếm', 'comic-theme'); ?>
        </div>
        <div class="card-body">
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Tìm kiếm..." value="<?php echo get_search_query(); ?>" name="s">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
                <input type="hidden" name="cat" value="-<?php echo implode(',-', $exclude_categories); ?>">
            </form>
        </div>
    </div>
    
    <div class="sidebar-card">
        <div class="card-header">
            <i class="fas fa-calendar me-2"></i><?php esc_html_e('Bài viết gần đây', 'comic-theme'); ?>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush recent-posts">
                <?php
                $recent_posts = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 5,
                    'category__not_in' => $exclude_categories,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
                
                if ($recent_posts->have_posts()) :
                    while ($recent_posts->have_posts()) : $recent_posts->the_post();
                ?>
                    <li class="list-group-item">
                        <div class="d-flex align-items-center">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="recent-post-thumb me-3">
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid rounded')); ?>
                                </a>
                            <?php endif; ?>
                            <div>
                                <h4 class="h6 mb-1"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <div class="small text-muted">
                                    <i class="fas fa-calendar-alt"></i> <?php echo get_the_date(); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <li class="list-group-item">Chưa có bài viết nào.</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <?php if (is_active_sidebar('sidebar-home')) : ?>
        <div class="sidebar-card">
            <div class="card-header">
                <i class="fas fa-th me-2"></i><?php esc_html_e('Widgets', 'comic-theme'); ?>
            </div>
            <div class="card-body">
                <?php dynamic_sidebar('sidebar-home'); ?>
            </div>
        </div>
    <?php endif; ?>
</aside><!-- #secondary -->
