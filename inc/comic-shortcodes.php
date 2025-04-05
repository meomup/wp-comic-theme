<?php
/**
 * Comic Shortcodes for Comic Theme
 *
 * @package Comic_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Shortcode to display comic images
 * 
 * Usage: [comic_images]image1.jpg|image2.jpg|image3.jpg[/comic_images]
 * or: [comic_images ids="123,124,125"]
 */
function comic_theme_images_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'ids' => '',
    ), $atts, 'comic_images');
    
    // Initialize output
    $output = '<div class="comic-container">';
    
    // Check if we have attachment IDs
    if (!empty($atts['ids'])) {
        $attachment_ids = explode(',', $atts['ids']);
        
        foreach ($attachment_ids as $attachment_id) {
            $attachment_id = trim($attachment_id);
            $image_url = wp_get_attachment_url($attachment_id);
            $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
            
            if ($image_url) {
                $output .= '<div class="comic-image">';
                $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($image_alt) . '" class="img-fluid comic-page" loading="lazy" data-id="' . esc_attr($attachment_id) . '">';
                $output .= '</div>';
            }
        }
    } 
    // Otherwise, parse content for image URLs
    elseif ($content) {
        $images = explode('|', $content);
        
        foreach ($images as $image) {
            $image = trim($image);
            
            if (!empty($image)) {
                $output .= '<div class="comic-image">';
                $output .= '<img src="' . esc_url($image) . '" alt="Comic Page" class="img-fluid comic-page" loading="lazy">';
                $output .= '</div>';
            }
        }
    }
    
    $output .= '</div>';
    $output .= '<div class="comic-navigation">
                  <button class="btn btn-primary comic-prev" disabled><i class="fas fa-chevron-left"></i> Trang trước</button>
                  <span class="comic-pagination">Trang <span class="current-page">1</span> / <span class="total-pages">1</span></span>
                  <button class="btn btn-primary comic-next">Trang sau <i class="fas fa-chevron-right"></i></button>
                </div>';
    
    // Return the output
    return $output;
}
add_shortcode('comic_images', 'comic_theme_images_shortcode');

/**
 * Shortcode to display comic info
 * 
 * Usage: [comic_info title="Tên truyện" author="Tác giả" status="Đang cập nhật"]
 */
function comic_theme_info_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => '',
        'author' => '',
        'artist' => '',
        'status' => '',
        'genres' => '',
        'summary' => ''
    ), $atts, 'comic_info');
    
    $output = '<div class="comic-info">';
    
    if (!empty($atts['title'])) {
        $output .= '<h3 class="comic-title">' . esc_html($atts['title']) . '</h3>';
    }
    
    $output .= '<div class="comic-details">';
    
    if (!empty($atts['author'])) {
        $output .= '<div class="comic-author"><strong>Tác giả:</strong> ' . esc_html($atts['author']) . '</div>';
    }
    
    if (!empty($atts['artist'])) {
        $output .= '<div class="comic-artist"><strong>Họa sĩ:</strong> ' . esc_html($atts['artist']) . '</div>';
    }
    
    if (!empty($atts['status'])) {
        $output .= '<div class="comic-status"><strong>Trạng thái:</strong> ' . esc_html($atts['status']) . '</div>';
    }
    
    if (!empty($atts['genres'])) {
        $output .= '<div class="comic-genres"><strong>Thể loại:</strong> ' . esc_html($atts['genres']) . '</div>';
    }
    
    $output .= '</div>';
    
    if (!empty($atts['summary'])) {
        $output .= '<div class="comic-summary"><strong>Tóm tắt:</strong><p>' . esc_html($atts['summary']) . '</p></div>';
    }
    
    $output .= '</div>';
    
    return $output;
}
add_shortcode('comic_info', 'comic_theme_info_shortcode');

/**
 * Shortcode to display chapter list
 * 
 * Usage: [comic_chapters category_id="123"]
 */
function comic_theme_chapters_shortcode($atts) {
    $atts = shortcode_atts(array(
        'category_id' => '',
        'limit' => -1,
    ), $atts, 'comic_chapters');
    
    if (empty($atts['category_id'])) {
        return '<p>Vui lòng chỉ định ID danh mục truyện.</p>';
    }
    
    $args = array(
        'cat' => $atts['category_id'],
        'posts_per_page' => $atts['limit'],
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $query = new WP_Query($args);
    
    if (!$query->have_posts()) {
        return '<p>Chưa có chapter nào.</p>';
    }
    
    $output = '<div class="comic-chapters">';
    $output .= '<h3>Danh sách chapter</h3>';
    $output .= '<div class="list-group">';
    
    while ($query->have_posts()) {
        $query->the_post();
        
        $output .= '<a href="' . get_permalink() . '" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">';
        $output .= get_the_title();
        $output .= '<span class="badge bg-primary rounded-pill">' . get_the_date() . '</span>';
        $output .= '</a>';
    }
    
    $output .= '</div>';
    $output .= '</div>';
    
    wp_reset_postdata();
    
    return $output;
}
add_shortcode('comic_chapters', 'comic_theme_chapters_shortcode');

/**
 * Shortcode to display all available comics
 * 
 * Usage: [comic_list]
 */
function comic_theme_list_shortcode($atts) {
    $atts = shortcode_atts(array(
        'columns' => 3,
        'limit' => -1
    ), $atts, 'comic_list');
    
    // Find the "Truyện Tranh" category
    $comics_category = get_term_by('slug', 'truyen-tranh', 'category');
    
    if (!$comics_category) {
        return '<p>Vui lòng tạo danh mục "Truyện Tranh" trước.</p>';
    }
    
    // Get comics
    $comics = get_categories(array(
        'parent' => $comics_category->term_id,
        'hide_empty' => 0,
        'number' => $atts['limit']
    ));
    
    if (empty($comics)) {
        return '<p>Chưa có truyện nào.</p>';
    }
    
    $default_image = get_template_directory_uri() . '/img/default-comic.jpg';
    
    $output = '<div class="comic-list">';
    $output .= '<div class="row row-cols-1 row-cols-md-2 row-cols-lg-' . esc_attr($atts['columns']) . ' g-4">';
    
    foreach ($comics as $comic) {
        $image_url = comic_theme_get_category_image_url($comic->term_id);
        
        $output .= '<div class="col">';
        $output .= '<div class="card h-100 comic-card">';
        
        $output .= '<a href="' . esc_url(get_category_link($comic->term_id)) . '" class="card-img-link">';
        if ($image_url) {
            $output .= '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($comic->name) . '" class="card-img-top comic-cover">';
        } else {
            $output .= '<img src="' . esc_url($default_image) . '" alt="' . esc_attr($comic->name) . '" class="card-img-top comic-cover">';
        }
        $output .= '</a>';
        
        $output .= '<div class="card-body">';
        $output .= '<h5 class="card-title"><a href="' . esc_url(get_category_link($comic->term_id)) . '">' . esc_html($comic->name) . '</a></h5>';
        
        // Get latest chapter
        $latest_post = get_posts(array(
            'numberposts' => 1,
            'category' => $comic->term_id,
            'orderby' => 'date',
            'order' => 'DESC',
        ));
        
        if (!empty($latest_post)) {
            $latest = $latest_post[0];
            $output .= '<p class="card-text small text-muted">';
            $output .= '<span class="latest-update"><i class="fas fa-clock"></i> Mới nhất: ';
            $output .= '<a href="' . esc_url(get_permalink($latest->ID)) . '">' . esc_html($latest->post_title) . '</a></span><br>';
            $output .= '<i class="fas fa-calendar-alt"></i> ' . get_the_date('', $latest->ID);
            $output .= '</p>';
        }
        
        $output .= '</div>'; // End card-body
        
        $output .= '<div class="card-footer text-center">';
        $output .= '<a href="' . esc_url(get_category_link($comic->term_id)) . '" class="btn btn-sm btn-primary">Xem truyện</a>';
        $output .= '</div>'; // End card-footer
        
        $output .= '</div>'; // End card
        $output .= '</div>'; // End col
    }
    
    $output .= '</div>'; // End row
    $output .= '</div>'; // End comic-list
    
    return $output;
}
add_shortcode('comic_list', 'comic_theme_list_shortcode');
