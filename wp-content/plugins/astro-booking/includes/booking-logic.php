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
    // $name = sanitize_text_field($_POST['name']);
    // $email = sanitize_email($_POST['email']);
    // $phone = sanitize_text_field($_POST['phone']);

    $product_id = 374;
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
    // Check if the cart already contains a product with 'astrologer_name' meta data
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        if (isset($cart_item['astrologer_id'])) {
            // Remove the item with this specific meta data
            WC()->cart->remove_cart_item($cart_item_key);
        }
    }


    $cart_item_key = WC()->cart->add_to_cart($product_id, 1, 0, [], [
        'booking_date' => $date,
        'booking_slot' => $slot,
        'booking_location' => $location,
        'astrologer_id' => $post_id,
        'astrologer_name' => get_the_title($post_id),
        // 'ab_user_name' => $name,
        // 'ab_user_email' => $email,
        // 'ab_user_phone' => $phone,
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

add_action('woocommerce_checkout_create_order_line_item', 'ab_add_booking_meta_to_order_items', 10, 4);

function ab_add_booking_meta_to_order_items($item, $cart_item_key, $values, $order) {
    if (isset($values['booking_date'])) {
        $item->add_meta_data('booking_date', $values['booking_date'], true);
    }

    if (isset($values['booking_slot'])) {
        $item->add_meta_data('booking_slot', $values['booking_slot'], true);
    }

    if (isset($values['booking_location'])) {
        $item->add_meta_data('booking_location', $values['booking_location'], true);
    }

    if (isset($values['astrologer_id'])) {
        $item->add_meta_data('astrologer_id', $values['astrologer_id'], true);
    }

    if (isset($values['astrologer_name'])) {
        $item->add_meta_data('astrologer_name', $values['astrologer_name'], true);
    }

    // if (isset($values['ab_user_name'])) {
    //     $item->add_meta_data('ab_user_name', $values['ab_user_name'], true);
    // }

    // if (isset($values['ab_user_email'])) {
    //     $item->add_meta_data('ab_user_email', $values['ab_user_email'], true);
    // }

    // if (isset($values['ab_user_phone'])) {
    //     $item->add_meta_data('ab_user_phone', $values['ab_user_phone'], true);
    // }
}
add_action('woocommerce_thankyou', 'ab_insert_appointment_after_payment', 20, 1);

function ab_insert_appointment_after_payment($order_id) {
    $order = wc_get_order($order_id);

    if (!$order) return;

    // Check if the order has items with appointment booking data
    $has_appointment = false;
    $appointment_id = null; // Initialize the appointment ID

    foreach ($order->get_items() as $item_id => $item) {
        $post_id   = $item->get_meta('astrologer_id');
        $date      = $item->get_meta('booking_date');
        $slot      = $item->get_meta('booking_slot');
        $location  = $item->get_meta('booking_location');
        
        // Check if the item contains appointment-related data
        if ($post_id && $date && $slot && $location) {
            $has_appointment = true;
            break;  // Stop looping once we find an appointment
        }
    }

    // If the order has an appointment, show the message
    if ($has_appointment && is_order_received_page()) {
        echo '<p><strong>Note:</strong> You can cancel your appointment bookings only within 30 minutes.</p>';
    }

    // Prevent duplicate insertions of appointments
    if ($order->get_meta('_ab_appointment_inserted')) {
        return;
    }

    global $wpdb;
    $table = $wpdb->prefix . 'ab_appointments';

    foreach ($order->get_items() as $item_id => $item) {
        $post_id   = $item->get_meta('astrologer_id');
        $date      = $item->get_meta('booking_date');
        $slot      = $item->get_meta('booking_slot');
        $location  = $item->get_meta('booking_location');

        // Check if all necessary appointment data is present
        if ($post_id && $date && $slot && $location) {
            // Prevent duplicate appointment entries
            $already = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE post_id = %d AND date = %s AND time_slot = %s AND location = %s",
                $post_id, $date, $slot, $location
            ));

            // Insert the appointment if it doesn't already exist
            if (!$already) {
                $created_at = current_time('mysql'); // Current time when the appointment is created
                $wpdb->insert($table, [
                    'post_id'     => $post_id,
                    'date'        => $date,
                    'time_slot'   => $slot,
                    'location'    => $location,
                    'user_name'   => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    'user_email'  => $order->get_billing_email(),
                    'user_phone'  => $order->get_billing_phone(),
                    'created_at'  => $created_at, // Store creation time
                ]);

                // Get the appointment ID for later use in order meta
                $appointment_id = $wpdb->insert_id;
            }

            // Update order item meta with appointment details
            wc_update_order_item_meta($item_id, 'astrologer_id', $post_id);
            wc_update_order_item_meta($item_id, 'booking_date', $date);
            wc_update_order_item_meta($item_id, 'booking_slot', $slot);
            wc_update_order_item_meta($item_id, 'booking_location', $location);
        }
    }

    // If appointment ID was generated, add it to order meta
    if ($appointment_id) {
        $order->update_meta_data('appointment_id', $appointment_id);
    }

    // Check if user exists, if not, create the user without sending a reset password link
    $user_email = $order->get_billing_email();
    $user = get_user_by('email', $user_email);

    if (!$user) {
        // Create new user
        $username = sanitize_user($order->get_billing_first_name() . '.' . $order->get_billing_last_name());
        $password = wp_generate_password(); // Generate a password but do not send a reset email

        $user_id = wp_create_user($username, $password, $user_email);
        if (!is_wp_error($user_id)) {
            // Assign role (optional, you can adjust this as needed)
            $user = get_user_by('id', $user_id);
            $user->set_role('customer'); // Default user role

            // Avoid sending reset password email by modifying user meta
            update_user_meta($user_id, 'wp_reset_password', true);
        }
    }

    // Link this order to the user account (if user exists)
    if ($user && is_object($user)) {
        $order->set_customer_id($user->ID);
    }

    // Mark the order as processed for appointment
    $order->update_meta_data('_ab_appointment_inserted', true);
    $order->update_meta_data('appointment_created_at', current_time('mysql'));

    $order->save();
}



add_filter('woocommerce_get_item_data', 'ab_display_custom_cart_item', 10, 2);
function ab_display_custom_cart_item($item_data, $cart_item) {
    if (isset($cart_item['booking_date'])) {
        $item_data[] = ['name' => 'Date', 'value' =>  date('d M Y', strtotime($cart_item['booking_date']))];
    }

    if (isset($cart_item['booking_slot'])) {
        $item_data[] = ['name' => 'Time Slot', 'value' => $cart_item['booking_slot']];
    }

    if (isset($cart_item['booking_location'])) {
        $item_data[] = ['name' => 'Location', 'value' => $cart_item['booking_location']];
    }

    if (isset($cart_item['astrologer_name'])) {
        $item_data[] = ['name' => 'Astrologer', 'value' => $cart_item['astrologer_name']];
    }

    // if (isset($cart_item['ab_user_name'])) {
    //     $item_data[] = ['name' => 'Your Name', 'value' => $cart_item['ab_user_name']];
    // }

    // if (isset($cart_item['ab_user_email'])) {
    //     $item_data[] = ['name' => 'Email', 'value' => $cart_item['ab_user_email']];
    // }

    // if (isset($cart_item['ab_user_phone'])) {
    //     $item_data[] = ['name' => 'Phone', 'value' => $cart_item['ab_user_phone']];
    // }

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
add_filter('woocommerce_email_order_meta_fields', 'ab_add_cancel_message_and_password_reset_to_email', 10, 3);

function ab_add_cancel_message_and_password_reset_to_email($fields, $sent_to_admin, $order) {
    // Default cancellation policy message
    $cancel_message = 'You can cancel your appointment bookings only within 30 minutes.';
    
    // Check if the order is an appointment booking and if the customer is a new user
    $is_appointment_order = false;
    $user_email = $order->get_billing_email();
    
    // Loop through order items to check for appointment details
    foreach ($order->get_items() as $item_id => $item) {
        if ($item->get_meta('astrologer_id')) {
            $is_appointment_order = true;
            break;  // If it's an appointment order, we stop checking further
        }
    }

    if ($is_appointment_order) {
        // Check if the user is a new customer and does not have an account
        $user = get_user_by('email', $user_email);
        if (!$user) {
            // Generate the login URL for new customers
            $my_account_url = site_url('/my-account/');
            $login_message = sprintf(
                'To login, click <a href="%s">here</a>.',
                esc_url($my_account_url)
            );
    
            // Combine cancellation message and login link
            $cancel_message .= ' ' . $login_message;
        }
    }
    

    // Add combined cancellation message and password reset to the email
    $fields['cancel_message'] = array(
        'label' => __('Cancellation Policy'),
        'value' => $cancel_message
    );

    return $fields;
}



add_filter('woocommerce_order_can_be_canceled', 'ab_check_appointment_cancellation', 10, 2);

function ab_check_appointment_cancellation($can_cancel, $order) {
    // Check if the order has an appointment
    $appointment_id = $order->get_meta('appointment_id');
    if ($appointment_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'ab_appointments';
        
        // Fetch the appointment creation time
        $created_at = $wpdb->get_var($wpdb->prepare(
            "SELECT created_at FROM $table WHERE id = %d",
            $appointment_id
        ));

        if ($created_at) {
            $created_time = strtotime($created_at);
            $current_time = current_time('timestamp');
            $time_difference = $current_time - $created_time;

            // Allow cancellation only within 30 minutes
            if ($time_difference > 30 * 60) {
                return false; // Deny cancellation if more than 30 minutes have passed
            }
        }
    }
    return $can_cancel;
}
add_action('woocommerce_order_details_after_order_table', 'ab_display_cancel_button_on_order_view', 10, 1);

function ab_display_cancel_button_on_order_view($order) {
    $created_at = $order->get_meta('appointment_created_at'); // Ensure this is set correctly in the order meta

    if ($created_at) {
        $appointment_time = strtotime($created_at);
        $current_time = time(); // Get the current time
        $time_diff = $current_time - $appointment_time; // Calculate the time difference in seconds
        
        // Only show the cancel button if it's within 30 minutes (1800 seconds)
        if ($time_diff <= 1800) {
            // Manually construct the URL for the "View Order" page with the cancel_appointment query parameter
            $cancel_url = site_url('/my-account/view-order/' . $order->get_id() . '/?cancel_appointment=true');
            
            echo '<a href="' . esc_url($cancel_url) . '" class="button cancel-appointment">Cancel Appointment</a>';
        } else {
            // Display a message that cancellation is no longer available
            echo '<p>Your appointment cannot be canceled as more than 30 minutes have passed since the booking.</p>';
        }
    }
}

add_action('template_redirect', 'ab_cancel_appointment_if_requested');

function ab_cancel_appointment_if_requested() {
    if (isset($_GET['cancel_appointment']) && $_GET['cancel_appointment'] === 'true' && is_account_page()) {
        $order_id = get_query_var('view-order'); // Get the order ID from the URL

        // Check if the order exists
        $order = wc_get_order($order_id);

        if ($order && $order->get_status() == 'processing') {
            // Get the appointment ID from the order meta
            $appointment_id = $order->get_meta('appointment_id');

            if ($appointment_id) {
                // Get the appointment creation time
                $created_at = $order->get_meta('appointment_created_at');
                if ($created_at) {
                    $appointment_time = strtotime($created_at);
                    $current_time = time();
                    $time_diff = $current_time - $appointment_time;

                    if ($time_diff <= 1800) {
                        // Your code to cancel the appointment (e.g., remove the appointment from the database)
                        global $wpdb;
                        $table = $wpdb->prefix . 'ab_appointments';

                        // Remove the appointment from the custom table
                        $wpdb->delete($table, ['id' => $appointment_id]);

                        // Optionally, update the WooCommerce order status to "Cancelled"
                        $order->update_status('cancelled'); // Mark the order as canceled
                        $order->save();

                        // Add a notice to inform the user
                        wc_add_notice('Your appointment and order have been successfully canceled.', 'success');
                        wp_redirect(wc_get_account_endpoint_url('orders'));
                        exit;
                    } else {
                        wc_add_notice('Your appointment cannot be canceled as more than 30 minutes have passed since the booking.', 'error');
                    }
                }
            }
        }
    }
}

add_action( 'woocommerce_checkout_process', 'ab_check_slot_availability_before_place_order' );

function ab_check_slot_availability_before_place_order() {
    // Loop through all the cart items
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $astrologer_id = $cart_item['booking_data']['astrologer_id'];
        $booking_date = $cart_item['booking_data']['booking_date'];
        $booking_slot = $cart_item['booking_data']['booking_slot'];
        $location = $cart_item['booking_data']['location'];

        // Query to check if the slot is already taken
        global $wpdb;
        $table = $wpdb->prefix . 'ab_appointments'; // Ensure this table is correct
        $already_booked = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE post_id = %d AND date = %s AND time_slot = %s AND location = %s",
            $astrologer_id, $booking_date, $booking_slot, $location
        ));

        if ($already_booked) {
            // Slot is already booked, add an error
            wc_add_notice( 'This time slot is no longer available. Please choose another one.', 'error' );
            return; // Prevent further order processing
        }
    }
}

