<?php
/**
 * IAHS Modern Child Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
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

add_action('wp_enqueue_scripts', 'iahs_child_enqueue_styles', 20);
function iahs_child_enqueue_styles()
{
    $parent_version = defined('ET_CORE_VERSION') ? ET_CORE_VERSION : wp_get_theme(get_template())->get('Version');
    $child_version = wp_get_theme()->get('Version');

    // Divi registers this handle; enqueue it once with a safe version fallback.
    wp_enqueue_style('divi-style', get_template_directory_uri() . '/style.css', array(), $parent_version);

    wp_enqueue_style('divi-child', get_stylesheet_uri(), array('divi-style'), $child_version);

    wp_enqueue_style('iahs-modern-style', get_stylesheet_directory_uri() . '/css/custom.css', array('divi-child'), $child_version);

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
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (!wp_verify_nonce($nonce, 'iahs_nonce')) {
        wp_send_json_error('Security check failed');
    }

    // Read optional fields without emitting notices on malformed requests.
    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field(wp_unslash($_POST['phone'])) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
    $program = isset($_POST['program']) ? sanitize_text_field(wp_unslash($_POST['program'])) : '';

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
