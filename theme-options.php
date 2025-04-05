<?php
/**
 * Theme Options for Comic Theme
 *
 * @package Comic_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add theme options page to the admin menu
function comic_theme_add_options_page() {
    add_theme_page(
        __('Comic Theme Options', 'comic-theme'),
        __('Comic Options', 'comic-theme'),
        'manage_options',
        'comic-theme-options',
        'comic_theme_options_page'
    );
}
add_action('admin_menu', 'comic_theme_add_options_page');

// Register settings
function comic_theme_register_settings() {
    register_setting('comic_theme_options', 'comic_theme_options', 'comic_theme_validate_options');
    
    add_settings_section(
        'comic_theme_general',
        __('General Settings', 'comic-theme'),
        'comic_theme_section_general',
        'comic-theme-options'
    );
    
    add_settings_field(
        'comic_home_layout',
        __('Home Page Layout', 'comic-theme'),
        'comic_theme_field_layout',
        'comic-theme-options',
        'comic_theme_general'
    );
    
    add_settings_field(
        'comic_primary_color',
        __('Primary Color', 'comic-theme'),
        'comic_theme_field_color',
        'comic-theme-options',
        'comic_theme_general'
    );
    
    add_settings_field(
        'comic_footer_text',
        __('Footer Text', 'comic-theme'),
        'comic_theme_field_footer_text',
        'comic-theme-options',
        'comic_theme_general'
    );
}
add_action('admin_init', 'comic_theme_register_settings');

// Render the options page
function comic_theme_options_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'comic-theme'));
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('comic_theme_options');
            do_settings_sections('comic-theme-options');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Render general section description
function comic_theme_section_general() {
    echo '<p>' . __('Configure general settings for the Comic Theme.', 'comic-theme') . '</p>';
}

// Render layout field
function comic_theme_field_layout() {
    $options = get_option('comic_theme_options');
    $layout = isset($options['comic_home_layout']) ? $options['comic_home_layout'] : 'grid';
    ?>
    <select name="comic_theme_options[comic_home_layout]" id="comic_home_layout">
        <option value="grid" <?php selected($layout, 'grid'); ?>><?php _e('Grid Layout', 'comic-theme'); ?></option>
        <option value="list" <?php selected($layout, 'list'); ?>><?php _e('List Layout', 'comic-theme'); ?></option>
        <option value="masonry" <?php selected($layout, 'masonry'); ?>><?php _e('Masonry Layout', 'comic-theme'); ?></option>
    </select>
    <p class="description"><?php _e('Select the layout style for the home page comics list.', 'comic-theme'); ?></p>
    <?php
}

// Render color field
function comic_theme_field_color() {
    $options = get_option('comic_theme_options');
    $color = isset($options['comic_primary_color']) ? $options['comic_primary_color'] : '#007bff';
    ?>
    <input type="color" name="comic_theme_options[comic_primary_color]" id="comic_primary_color" value="<?php echo esc_attr($color); ?>">
    <p class="description"><?php _e('Select the primary color for buttons, links, and accents.', 'comic-theme'); ?></p>
    <?php
}

// Render footer text field
function comic_theme_field_footer_text() {
    $options = get_option('comic_theme_options');
    $footer_text = isset($options['comic_footer_text']) ? $options['comic_footer_text'] : '';
    ?>
    <textarea name="comic_theme_options[comic_footer_text]" id="comic_footer_text" rows="3" cols="50"><?php echo esc_textarea($footer_text); ?></textarea>
    <p class="description"><?php _e('Custom text to display in the footer. Leave blank to use the default text.', 'comic-theme'); ?></p>
    <?php
}

// Validate options
function comic_theme_validate_options($input) {
    $output = array();
    
    // Validate layout
    if (isset($input['comic_home_layout']) && in_array($input['comic_home_layout'], array('grid', 'list', 'masonry'))) {
        $output['comic_home_layout'] = $input['comic_home_layout'];
    } else {
        $output['comic_home_layout'] = 'grid';
    }
    
    // Validate color
    if (isset($input['comic_primary_color']) && preg_match('/^#[a-f0-9]{6}$/i', $input['comic_primary_color'])) {
        $output['comic_primary_color'] = $input['comic_primary_color'];
    } else {
        $output['comic_primary_color'] = '#007bff';
    }
    
    // Sanitize footer text
    if (isset($input['comic_footer_text'])) {
        $output['comic_footer_text'] = wp_kses_post($input['comic_footer_text']);
    }
    
    return $output;
}

// Apply theme options
function comic_theme_apply_options() {
    $options = get_option('comic_theme_options');
    
    if (isset($options['comic_primary_color'])) {
        $primary_color = $options['comic_primary_color'];
        $custom_css = "
            :root {
                --primary-color: {$primary_color};
            }
            
            .btn-primary, 
            .bg-primary,
            .pagination .current,
            .navbar-dark .navbar-nav .nav-link:after,
            .footer-heading:after,
            .comic-info .comic-title,
            .comic-chapters h3 {
                background-color: var(--primary-color) !important;
                border-color: var(--primary-color) !important;
            }
            
            a, 
            .navbar-dark .navbar-nav .active .nav-link,
            .category-list li i,
            .chapter-table a:hover {
                color: var(--primary-color) !important;
            }
            
            .btn-outline-primary {
                color: var(--primary-color) !important;
                border-color: var(--primary-color) !important;
            }
            
            .btn-outline-primary:hover {
                background-color: var(--primary-color) !important;
                color: #fff !important;
            }
        ";
        wp_add_inline_style('comic-style', $custom_css);
    }
}
add_action('wp_enqueue_scripts', 'comic_theme_apply_options', 20);
