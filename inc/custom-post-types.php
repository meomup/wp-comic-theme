<?php
/**
 * Custom taxonomies and post types for Comic Theme
 *
 * @package Comic_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue media scripts for admin
 */
function comic_theme_admin_enqueue_scripts() {
    if (is_admin()) {
        wp_enqueue_media();
    }
}
add_action('admin_enqueue_scripts', 'comic_theme_admin_enqueue_scripts');

/**
 * Add category image field
 */
function comic_theme_category_add_form_fields($taxonomy) {
    ?>
    <div class="form-field term-group">
        <label for="category-image-id"><?php _e('Category Image', 'comic-theme'); ?></label>
        <input type="hidden" id="category-image-id" name="category-image-id" class="custom_media_url" value="">
        <div id="category-image-wrapper"></div>
        <p>
            <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e('Add Image', 'comic-theme'); ?>" />
            <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e('Remove Image', 'comic-theme'); ?>" />
        </p>
    </div>
    <?php
    // Add admin script inline
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Media uploader
        var mediaUploader;
        
        $('.ct_tax_media_button').click(function(e) {
            e.preventDefault();
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: '<?php _e('Choose Image', 'comic-theme'); ?>',
                button: {
                    text: '<?php _e('Choose Image', 'comic-theme'); ?>'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#category-image-id').val(attachment.id);
                $('#category-image-wrapper').html('<img class="custom_media_image" src="' + attachment.url + '" style="max-width:100px;height:auto;border:1px solid #e9e9e9;" />');
                $('.ct_tax_media_remove').show();
            });
            
            mediaUploader.open();
        });
        
        $('.ct_tax_media_remove').click(function(e) {
            $('#category-image-id').val('');
            $('#category-image-wrapper').html('');
            $('.ct_tax_media_remove').hide();
        });
    });
    </script>
    <?php
}
add_action('category_add_form_fields', 'comic_theme_category_add_form_fields', 10, 2);

/**
 * Edit category image field
 */
function comic_theme_category_edit_form_fields($term, $taxonomy) {
    $image_id = get_term_meta($term->term_id, 'category-image-id', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row">
            <label for="category-image-id"><?php _e('Category Image', 'comic-theme'); ?></label>
        </th>
        <td>
            <input type="hidden" id="category-image-id" name="category-image-id" value="<?php echo $image_id; ?>">
            <div id="category-image-wrapper">
                <?php if ($image_id) { ?>
                    <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                <?php } ?>
            </div>
            <p>
                <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php _e('Add Image', 'comic-theme'); ?>" />
                <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php _e('Remove Image', 'comic-theme'); ?>" />
            </p>
        </td>
    </tr>
    <?php
    // Add admin script inline
    ?>
    <script>
    jQuery(document).ready(function($) {
        // Media uploader
        var mediaUploader;
        
        $('.ct_tax_media_button').click(function(e) {
            e.preventDefault();
            
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: '<?php _e('Choose Image', 'comic-theme'); ?>',
                button: {
                    text: '<?php _e('Choose Image', 'comic-theme'); ?>'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                attachment = mediaUploader.state().get('selection').first().toJSON();
                $('#category-image-id').val(attachment.id);
                $('#category-image-wrapper').html('<img src="' + attachment.url + '" style="max-width:100px;height:auto;border:1px solid #e9e9e9;" />');
                $('.ct_tax_media_remove').show();
            });
            
            mediaUploader.open();
        });
        
        $('.ct_tax_media_remove').click(function(e) {
            $('#category-image-id').val('');
            $('#category-image-wrapper').html('');
            $('.ct_tax_media_remove').hide();
        });
    });
    </script>
    <?php
}
add_action('category_edit_form_fields', 'comic_theme_category_edit_form_fields', 10, 2);

/**
 * Save category image field
 */
function comic_theme_save_category_image($term_id, $tt_id) {
    if (isset($_POST['category-image-id']) && '' !== $_POST['category-image-id']) {
        $image = $_POST['category-image-id'];
        update_term_meta($term_id, 'category-image-id', $image);
    } else {
        update_term_meta($term_id, 'category-image-id', '');
    }
}
add_action('edited_category', 'comic_theme_save_category_image', 10, 2);
add_action('create_category', 'comic_theme_save_category_image', 10, 2);

/**
 * Add category image column
 */
function comic_theme_manage_category_columns($columns) {
    $columns['image'] = __('Image', 'comic-theme');
    return $columns;
}
add_filter('manage_edit-category_columns', 'comic_theme_manage_category_columns');

/**
 * Add category image column content
 */
function comic_theme_manage_category_column($content, $column_name, $term_id) {
    if ($column_name !== 'image') {
        return $content;
    }
    
    $image_id = get_term_meta($term_id, 'category-image-id', true);
    
    if ($image_id) {
        $content = wp_get_attachment_image($image_id, array(50, 50));
    }
    
    return $content;
}
add_filter('manage_category_custom_column', 'comic_theme_manage_category_column', 10, 3);

/**
 * Get category image URL
 */
function comic_theme_get_category_image_url($term_id) {
    $image_id = get_term_meta($term_id, 'category-image-id', true);
    
    if ($image_id) {
        $image_url = wp_get_attachment_image_url($image_id, 'comic-cover');
        return $image_url;
    }
    
    return false;
}

/**
 * Check if category is a comic category (has parent = "Truyện Tranh")
 */
function is_comic_category($category_id) {
    // Get category
    $category = get_term($category_id, 'category');
    
    if (!$category || is_wp_error($category)) {
        return false;
    }
    
    // If it has a parent, check if the parent is "Truyện Tranh"
    if ($category->parent) {
        $parent = get_term($category->parent, 'category');
        if (!is_wp_error($parent) && $parent->slug === 'truyen-tranh') {
            return true;
        }
    }
    
    // Check if this is the "Truyện Tranh" category itself
    if ($category->slug === 'truyen-tranh') {
        return true;
    }
    
    return false;
}

/**
 * Get all comics (categories with parent = "Truyện Tranh")
 */
function get_all_comics() {
    // Find the "Truyện Tranh" category
    $comics_category = get_term_by('slug', 'truyen-tranh', 'category');
    
    if (!$comics_category) {
        return array();
    }
    
    // Get all subcategories
    $comics = get_categories(array(
        'parent' => $comics_category->term_id,
        'hide_empty' => 0,
    ));
    
    return $comics;
}

/**
 * Get latest chapters for a comic
 */
function get_comic_chapters($comic_id, $limit = 5) {
    $args = array(
        'posts_per_page' => $limit,
        'category' => $comic_id,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    return get_posts($args);
}
