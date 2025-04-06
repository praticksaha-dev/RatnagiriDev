<?php
add_action('wp_ajax_ab_get_available_dates', 'ab_get_available_dates');
add_action('wp_ajax_nopriv_ab_get_available_dates', 'ab_get_available_dates');

function ab_get_available_dates() {
    $post_id = intval($_POST['post_id']);
    $availability = get_post_meta($post_id, 'ab_multi_availability', true);
    $booked = get_booked_slots_for_post($post_id); // your function to get booked slots
    $enabled = [];

    $today = strtotime('today');
    $maxDays = 14;

    for ($i = 1; $i <= $maxDays; $i++) {
        $timestamp = strtotime("+{$i} days", $today);
        $dayName = date('l', $timestamp);
        $dateStr = date('Y-m-d', $timestamp);

        foreach ($availability as $entry) {
            if (!in_array($dayName, $entry['days'])) continue;

            $slots = ab_generate_slots($entry['from'], $entry['to']);
            $used = $booked[$dateStr][$entry['location']] ?? [];

            if (count($used) < count($slots)) {
                $enabled[] = $dateStr;
                break;
            }
        }
    }

    wp_send_json(['enabled' => array_values(array_unique($enabled))]);
}

add_action('wp_ajax_ab_get_slots_for_date_location', 'ab_get_slots_for_date_location');
add_action('wp_ajax_nopriv_ab_get_slots_for_date_location', 'ab_get_slots_for_date_location');

function ab_get_slots_for_date_location() {
    $post_id = intval($_POST['post_id']);
    $date =  date('Y-m-d', strtotime(sanitize_text_field($_POST['date']))); 
    
    $location = sanitize_text_field($_POST['location']);
    $timestamp = strtotime($date);
    $day = date('l', $timestamp);

    $availability = get_post_meta($post_id, 'ab_multi_availability', true);
    $slots = [];

    foreach ($availability as $entry) {
        if ($entry['location'] === $location && in_array($day, $entry['days'])) {
            $from = strtotime($date . ' ' . $entry['from']);
            $to   = strtotime($date . ' ' . $entry['to']);

            while ($from + 1800 <= $to) {
                $end = $from + 1800; // 30 mins session
                $slots[] = date('H:i', $from) . ' - ' . date('H:i', $end);
                $from = $end + 1800; // 30 mins gap
            }

            break; // Found matching location
        }
    }

    // Remove booked slots
    global $wpdb;
    $booked = $wpdb->get_col($wpdb->prepare("
        SELECT time_slot FROM {$wpdb->prefix}ab_appointments 
        WHERE post_id = %d AND location = %s AND date = %s
    ", $post_id, $location, $date));
    // print_r($wpdb->last_query);
    $available_slots = array_diff($slots, $booked);
    wp_send_json(array_values($available_slots));
}


function get_booked_slots_for_post($post_id) {
    global $wpdb;

    // Assuming you have a custom table like wp_ab_appointments with columns:
    // post_id, date, location, time_slot

    $results = $wpdb->get_results($wpdb->prepare(
        "SELECT date, location, time_slot FROM {$wpdb->prefix}ab_appointments WHERE post_id = %d",
        $post_id
    ));

    $booked = [];

    foreach ($results as $row) {
        $date = $row->date;
        $location = $row->location;
        $slot = $row->time_slot;

        if (!isset($booked[$date])) $booked[$date] = [];
        if (!isset($booked[$date][$location])) $booked[$date][$location] = [];

        $booked[$date][$location][] = $slot;
    }

    return $booked;
}

add_action('wp_ajax_ab_get_locations_for_date', 'ab_get_locations_for_date');
add_action('wp_ajax_nopriv_ab_get_locations_for_date', 'ab_get_locations_for_date');

function ab_get_locations_for_date() {
    $post_id = intval($_POST['post_id']);
    $availability = get_post_meta($post_id, 'ab_multi_availability', true);
    $locations = [];
    foreach ($availability as $entry) {
        array_push($locations,$entry['location']);
    }

    wp_send_json(array_unique($locations));
}

add_action('wp_ajax_ab_get_days_for_location', 'ab_get_days_for_location');
add_action('wp_ajax_nopriv_ab_get_days_for_location', 'ab_get_days_for_location');

function ab_get_days_for_location() {
    $post_id = intval($_POST['post_id']);
    $location = sanitize_text_field($_POST['location']);

    $availability = get_post_meta($post_id, 'ab_multi_availability', true);
    $days = [];

    if (!empty($availability)) {
        foreach ($availability as $entry) {
            if ($entry['location'] === $location && !empty($entry['days'])) {
                $days = array_merge($days, $entry['days']);
            }
        }
    }

    wp_send_json(['days' => array_unique($days)]);
}

add_action('wp_ajax_ab_submit_booking', 'ab_submit_booking');
add_action('wp_ajax_nopriv_ab_submit_booking', 'ab_submit_booking');

// function ab_submit_booking() {
//     $post_id = intval($_POST['post_id']);
//     $date = sanitize_text_field($_POST['date']);
//     $slot = sanitize_text_field($_POST['slot']);
//     $location = sanitize_text_field($_POST['location']);
//     $name = sanitize_text_field($_POST['name']);
//     $email = sanitize_email($_POST['email']);
//     $phone = sanitize_text_field($_POST['phone']);

//     global $wpdb;
//     $table = $wpdb->prefix . 'ab_appointments';

//     // Double-check if slot is still available
//     $already_booked = $wpdb->get_var($wpdb->prepare(
//         "SELECT COUNT(*) FROM $table WHERE post_id = %d AND date = %s AND time_slot = %s AND location = %s",
//         $post_id, $date, $slot, $location
//     ));

//     if ($already_booked) {
//         wp_send_json_error();
//     }

//     // Insert new booking
//     $wpdb->insert($table, [
//         'post_id' => $post_id,
//         'date' => $date,
//         'time_slot' => $slot,
//         'location' => $location,
//         'user_name' => $name,
//         'user_email' => $email,
//         'user_phone' => $phone,
//         'created_at' => current_time('mysql'),
//     ]);

//     wp_send_json_success();
// }
function ab_submit_booking() {
    $post_id = intval($_POST['post_id']);
    $date = sanitize_text_field($_POST['date']);
    $slot = sanitize_text_field($_POST['slot']);
    $location = sanitize_text_field($_POST['location']);
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);

    $product_id = 379;
    $ab_price = get_post_meta($post_id, 'ab_price', true);

    // Start WooCommerce session if needed
    if (null === WC()->session) {
        WC()->initialize_session();
    }

    global $wpdb;
    $table = $wpdb->prefix . 'ab_appointments';
    $already_booked = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table WHERE post_id = %d AND date = %s AND time_slot = %s AND location = %s",
        $post_id, $date, $slot, $location
    ));

    if ($already_booked) {
        wp_send_json_error(['message' => 'This slot was just taken. Please choose another.']);
    }
    $cart_item_key = WC()->cart->add_to_cart($product_id, 1, 0, [], [
        'booking_date' => $date,
        'booking_slot' => $slot,
        'booking_location' => $location,
        'astrologer_id' => $post_id,
        'astrologer_name' => get_the_title($post_id),
        'ab_user_name' => $name,
        'ab_user_email' => $email,
        'ab_user_phone' => $phone,
        'custom_price' => $ab_price
    ]);
    
    if (!$cart_item_key) {
        wp_send_json_error(['message' => 'Could not add to cart. Product might be disabled or invalid.']);
    }
    else
    {

    wp_send_json_success(['redirect_url' => wc_get_checkout_url()]);
    }
}


add_action('woocommerce_checkout_order_processed', 'ab_insert_appointment_after_payment', 20, 1);

function ab_insert_appointment_after_payment($order_id) {
    $order = wc_get_order($order_id);
    global $wpdb;
    $table = $wpdb->prefix . 'ab_appointments';

    foreach ($order->get_items() as $item) {
        $meta = $item->get_meta_data();

        // Pull meta cleanly
        $post_id   = $item->get_meta('astrologer_id');
        $date      = $item->get_meta('booking_date');
        $slot      = $item->get_meta('booking_slot');
        $location  = $item->get_meta('booking_location');
        $name      = $item->get_meta('ab_user_name');
        $email     = $item->get_meta('ab_user_email');
        $phone     = $item->get_meta('ab_user_phone');

        if ($post_id && $date && $slot && $location) {
            // Check if already exists
            $already = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE post_id = %d AND date = %s AND time_slot = %s AND location = %s",
                $post_id, $date, $slot, $location
            ));

            if (!$already) {
                $wpdb->insert($table, [
                    'post_id'     => $post_id,
                    'date'        => $date,
                    'time_slot'   => $slot,
                    'location'    => $location,
                    'user_name'   => $name,
                    'user_email'  => $email,
                    'user_phone'  => $phone,
                    'created_at'  => current_time('mysql'),
                ]);
            }
        }
    }
}

add_filter('woocommerce_get_item_data', 'ab_display_custom_cart_item', 10, 2);
function ab_display_custom_cart_item($item_data, $cart_item) {
    if (isset($cart_item['booking_date'])) {
        $item_data[] = ['name' => 'Date', 'value' => $cart_item['booking_date']];
        $item_data[] = ['name' => 'Slot', 'value' => $cart_item['booking_slot']];
        $item_data[] = ['name' => 'Location', 'value' => $cart_item['booking_location']];
        $item_data[] = ['name' => 'Astrologer', 'value' => $cart_item['astrologer_name']];
    }
    return $item_data;
}

add_filter('woocommerce_before_calculate_totals', 'ab_set_custom_cart_price', 20, 1);
function ab_set_custom_cart_price($cart) {
    if (is_admin() && !defined('DOING_AJAX')) return;

    foreach ($cart->get_cart() as $cart_item) {
        if (isset($cart_item['custom_price'])) {
            $cart_item['data']->set_price($cart_item['custom_price']);
        }
    }
}
