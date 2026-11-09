<?php
/**
 * IAHS Modern Child Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// =========================================================================
// FIX: Remove unwanted output between nav and hero section
// =========================================================================

/**
 * Fix Yoast SEO schema output issues
 */
add_action('wp', 'iahs_fix_yoast_output');
function iahs_fix_yoast_output()
{
    // Remove Yoast schema if it's causing layout issues
    add_filter('wpseo_json_ld_output', '__return_false');

    // Alternative: Remove specific Yoast filters that might cause output
    remove_action('wpseo_head', array(WPSEO_Frontend::get_instance(), 'debug_marker'), 2);
}

/**
 * Clean up wp_head output
 */
add_action('after_setup_theme', 'iahs_cleanup_head_output');
function iahs_cleanup_head_output()
{
    // Remove unnecessary emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Remove WP version
    remove_action('wp_head', 'wp_generator');

    // Remove extra RSS feed links
    remove_action('wp_head', 'feed_links_extra', 3);
}

/**
 * Ensure proper template loading
 */
add_action('template_redirect', 'iahs_check_template_output');
function iahs_check_template_output()
{
    // Check if we're on the modern home template
    if (is_page_template('templates/home-modern.php')) {
        // Ensure no output before header
        ob_start();
    }
}

/**
 * Fix for Divi theme builder compatibility
 */
add_action('wp_enqueue_scripts', 'iahs_fix_divi_compatibility', 15);
function iahs_fix_divi_compatibility()
{
    // Ensure Divi styles load properly
    if (function_exists('et_setup_theme')) {
        wp_dequeue_style('divi-style');
        wp_enqueue_style('divi-style', get_template_directory_uri() . '/style.css', array(), ET_CORE_VERSION);
    }
}

add_action('wp_enqueue_scripts', 'iahs_child_enqueue_styles', 20);
function iahs_child_enqueue_styles()
{
    // First dequeue any existing Divi styles that might conflict
    wp_dequeue_style('divi-style');
    wp_dequeue_style('divi-style-parent');

    // Enqueue parent style
    wp_enqueue_style('divi-parent', get_template_directory_uri() . '/style.css');

    // Enqueue child style
    wp_enqueue_style('divi-child', get_stylesheet_directory_uri() . '/style.css', array('divi-parent'));

    // Enqueue custom modern styles
    wp_enqueue_style('iahs-modern-style', get_stylesheet_directory_uri() . '/css/custom.css', array('divi-parent'), '1.0.0');

    // Enqueue custom JavaScript
    wp_enqueue_script('iahs-modern-js', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '1.0.0', true);

    // Localize script for AJAX
    wp_localize_script('iahs-modern-js', 'iahs_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('iahs_nonce')
    ));
}

// Add custom body classes
add_filter('body_class', 'iahs_custom_body_class');
function iahs_custom_body_class($classes)
{
    if (is_front_page() || is_page_template('templates/home-modern.php')) {
        $classes[] = 'iahs-modern-home';
    }
    return $classes;
}

// Register custom navigation
add_action('init', 'iahs_register_menus');
function iahs_register_menus()
{
    register_nav_menus(array(
        'mobile-menu' => __('Mobile Menu', 'iahs'),
    ));
}

// AJAX handler for contact form
add_action('wp_ajax_iahs_contact_form', 'iahs_handle_contact_form');
add_action('wp_ajax_nopriv_iahs_contact_form', 'iahs_handle_contact_form');
function iahs_handle_contact_form()
{
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'iahs_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Process form data
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $message = sanitize_textarea_field($_POST['message']);
    $program = sanitize_text_field($_POST['program']);

    // Here you would typically:
    // 1. Send email to admin
    // 2. Save to database
    // 3. etc.

    // For now, just return success
    wp_send_json_success(array(
        'message' => 'Thank you for your message! We will get back to you soon.'
    ));
}

// Add custom image sizes
add_action('after_setup_theme', 'iahs_custom_image_sizes');
function iahs_custom_image_sizes()
{
    add_image_size('iahs-hero', 1920, 1080, true);
    add_image_size('iahs-card', 400, 300, true);
    add_image_size('iahs-program', 600, 400, true);
}

// Custom shortcodes
add_shortcode('iahs_stats', 'iahs_stats_shortcode');
function iahs_stats_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'number' => '10,000+',
        'label' => 'Students',
    ), $atts);

    return '<div class="stat-item">
        <span class="stat-number">' . esc_html($atts['number']) . '</span>
        <span class="stat-label">' . esc_html($atts['label']) . '</span>
    </div>';
}

// Ensure template is recognized
add_filter('theme_page_templates', 'iahs_add_page_template');
function iahs_add_page_template($templates)
{
    $templates['templates/home-modern.php'] = 'IAHS Modern Home';
    return $templates;
}

/**
 * Debug output to identify what's being displayed
 */
add_action('wp_body_open', 'iahs_debug_output', 1);
function iahs_debug_output()
{
    if (is_page_template('templates/home-modern.php')) {
        echo "<!-- WP_BODY_OPEN reached -->\n";
    }
}

add_action('wp_footer', 'iahs_debug_footer', 1);
function iahs_debug_footer()
{
    if (is_page_template('templates/home-modern.php')) {
        echo "<!-- WP_FOOTER reached -->\n";
    }
}
?>