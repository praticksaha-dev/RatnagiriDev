<?php
/*
Plugin Name: Astro Booking Scheduler
Description: Booking system for astrologers based on location, availability, and time slots.
Version: 1.0
Author: Pratick Saha
*/

if (!defined('ABSPATH')) exit;

define('ASTRO_BOOKING_DIR', plugin_dir_path(__FILE__));
define('ASTRO_BOOKING_URL', plugin_dir_url(__FILE__));

add_action('wp_enqueue_scripts', function() {
    if (!is_singular('astrologers')) return;

    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-css', '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');
    // Load jQuery (if not already)
    wp_enqueue_script('jquery');

    wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array('jquery'), null, true);  
    
    // Register a custom JS for your calendar logic
    wp_enqueue_script('ab-calendar-script', plugin_dir_url(__FILE__) . 'assets/js/calendar.js', ['jquery', 'jquery-ui-datepicker'], null, true);

    // Localize ajaxurl
    wp_localize_script('ab-calendar-script', 'ab_ajax', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
});



// Includes
include_once ASTRO_BOOKING_DIR . 'includes/shortcodes.php';
include_once ASTRO_BOOKING_DIR . 'includes/booking-logic.php';

include_once ASTRO_BOOKING_DIR . 'includes/admin-fields.php';

register_activation_hook(__FILE__, 'ab_create_appointments_table');

function ab_create_appointments_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ab_appointments';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        post_id BIGINT(20) NOT NULL,
        date DATE NOT NULL,
        location VARCHAR(255) NOT NULL,
        time_slot VARCHAR(50) NOT NULL,
        user_name VARCHAR(255),
        user_email VARCHAR(255),
        user_phone VARCHAR(50),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}


// Activation hook
register_activation_hook(__FILE__, function () {
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function () {
    flush_rewrite_rules();
});
