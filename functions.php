<?php
/**
 * Comic Theme functions and definitions
 *
 * @package Comic_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define theme constants
define('COMIC_THEME_DIR', get_template_directory());
define('COMIC_THEME_URI', get_template_directory_uri());

/**
 * Enqueue scripts and styles
 */
function comic_theme_scripts() {
    // Enqueue Bootstrap CSS
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', array(), '5.3.0');
    
    // Enqueue custom styles
    wp_enqueue_style('comic-style', get_template_directory_uri() . '/comic-style.css', array(), '1.0.0');
    
    // Enqueue jQuery (WordPress already includes jQuery)
    wp_enqueue_script('jquery');
    
    // Enqueue Bootstrap JS
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);
    
    // Enqueue custom scripts
    wp_enqueue_script('comic-script', get_template_directory_uri() . '/js/comic-script.js', array('jquery'), '1.0.0', true);
    
    // Localize script for AJAX
    wp_localize_script('comic-script', 'comic_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('comic_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'comic_theme_scripts');

/**
 * Register navigation menus
 */
function comic_theme_menus() {
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'comic-theme'),
        'footer' => esc_html__('Footer Menu', 'comic-theme'),
    ));
}
add_action('after_setup_theme', 'comic_theme_menus');

/**
 * Theme setup
 */
function comic_theme_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');
    
    // Let WordPress manage the document title
    add_theme_support('title-tag');
    
    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');
    
    // Add theme support for Custom Logo
    add_theme_support('custom-logo', array(
        'height' => 250,
        'width' => 250,
        'flex-width' => true,
        'flex-height' => true,
    ));
    
    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Add image size for comic covers
    add_image_size('comic-cover', 300, 450, true);
    add_image_size('comic-thumbnail', 150, 225, true);
}
add_action('after_setup_theme', 'comic_theme_setup');

/**
 * Add support for subcategory featured images
 */
function comic_theme_add_category_image_support() {
    // Include custom taxonomy functions
    require_once COMIC_THEME_DIR . '/inc/custom-post-types.php';
}
add_action('after_setup_theme', 'comic_theme_add_category_image_support');

/**
 * Include comic shortcodes
 */
function comic_theme_include_shortcodes() {
    require_once COMIC_THEME_DIR . '/inc/comic-shortcodes.php';
}
add_action('init', 'comic_theme_include_shortcodes');

/**
 * Register widget area
 */
function comic_theme_widgets_init() {
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'comic-theme'),
        'id' => 'sidebar-1',
        'description' => esc_html__('Add widgets here.', 'comic-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'comic_theme_widgets_init');

/**
 * Include Bootstrap Nav Walker
 */
require_once COMIC_THEME_DIR . '/inc/class-wp-bootstrap-navwalker.php';
/**
 * Include Theme Options
 */
require_once COMIC_THEME_DIR . '/theme-options.php';

/**
 * Add theme mode script to head
 */
function comic_theme_add_theme_mode_script() {
    ?>
    <script>
        // Immediately set theme to avoid flash of wrong theme
        (function() {
            const currentTheme = localStorage.getItem('theme');
            if (currentTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
    <?php
}
add_action('wp_head', 'comic_theme_add_theme_mode_script', 1);

/**
 * Register homepage sidebar
 */
function comic_theme_homepage_sidebar() {
    register_sidebar(array(
        'name' => esc_html__('Homepage Sidebar', 'comic-theme'),
        'id' => 'sidebar-home',
        'description' => esc_html__('Add widgets here for the homepage.', 'comic-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'comic_theme_homepage_sidebar');

/**
 * Register comic sidebar
 */
function comic_theme_comic_sidebar() {
    register_sidebar(array(
        'name' => esc_html__('Comic Sidebar', 'comic-theme'),
        'id' => 'sidebar-comic',
        'description' => esc_html__('Add widgets here for comic pages.', 'comic-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'comic_theme_comic_sidebar');

