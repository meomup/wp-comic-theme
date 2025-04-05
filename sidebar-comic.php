<?php
/**
 * The sidebar for comic pages
 *
 * @package Comic_Theme
 */

?>

<aside id="secondary" class="widget-area">
    <div class="sidebar-card">
        <div class="card-header">
            <i class="fas fa-star me-2"></i><?php esc_html_e('Truyện đọc nhiều', 'comic-theme'); ?>
        </div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush popular-comics">
                <?php
                // Find the "Truyện Tranh" category
                $comics_category = get_term_by('slug', 'truyen-tranh', 'category');
                $comics_category_id = $comics_category ? $comics_category->term_id : 0;
                
                if ($comics_category_id) {
                    // Get popular comics based on view count
                    $popular_comics = get_categories(array(
                        'parent' => $comics_category_id,
                        'orderby' => 'count',
                        'order' => 'DESC',
                        'number' => 5,
                        'hide_empty' => 0,
                    ));
                    
                    if (!empty($popular_comics)) :
                        foreach ($popular_comics as $comic) :
                            $image_url = comic_theme_get_category_image_url($comic->term_id);
                            $default_image = get_template_directory_uri() . '/img/default-comic.jpg';
                            
                            // Get chapter count
                            $chapter_count = $comic->count;
                        ?>
                            <li class="list-group-item">
                                <div class="popular-comic-thumb">
                                    <a href="<?php echo esc_url(get_category_link($comic->term_id)); ?>">
                                        <img src="<?php echo esc_url($image_url ? $image_url : $default_image); ?>" alt="<?php echo esc_attr($comic->name); ?>" class="img-fluid">
                                    </a>
                                </div>
                                <div class="popular-comic-details">
                                    <h4><a href="<?php echo esc_url(get_category_link($comic->term_id)); ?>"><?php echo esc_html($comic->name); ?></a></h4>
                                    <div class="stats">
                                        <span><i class="fas fa-book"></i> <?php echo esc_html($chapter_count); ?> chapter</span>
                                    </div>
                                </div>
                            </li>
                        <?php
                        endforeach;
                    else :
                        ?>
                        <li class="list-group-item">Chưa có truyện nào.</li>
                        <?php
                    endif;
                } else {
                    ?>
                    <li class="list-group-item">Vui lòng tạo danh mục "Truyện Tranh".</li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
    
    <div class="sidebar-card">
        <div class="card-header">
            <i class="fas fa-search me-2"></i><?php esc_html_e('Tìm truyện', 'comic-theme'); ?>
        </div>
        <div class="card-body">
            <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <div class="input-group">
                    <input type="search" class="form-control" placeholder="Tìm truyện..." value="<?php echo get_search_query(); ?>" name="s">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
                <?php
                // Only search in comics category and its subcategories
                if ($comics_category_id) {
                    echo '<input type="hidden" name="cat" value="' . $comics_category_id . '">';
                }
                ?>
            </form>
        </div>
    </div>
    
    <?php if (is_active_sidebar('sidebar-comic')) : ?>
        <div class="sidebar-card">
            <div class="card-header">
                <i class="fas fa-th me-2"></i><?php esc_html_e('Widgets', 'comic-theme'); ?>
            </div>
            <div class="card-body">
                <?php dynamic_sidebar('sidebar-comic'); ?>
            </div>
        </div>
    <?php endif; ?>
</aside><!-- #secondary -->
