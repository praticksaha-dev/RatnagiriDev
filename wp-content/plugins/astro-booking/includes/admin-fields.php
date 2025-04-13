<?php

add_action('add_meta_boxes', 'ab_register_astrologer_metabox');
function ab_register_astrologer_metabox() {
    add_meta_box(
        'ab_astrologer_details',
        'Astrologer Details & Availability',
        'ab_astrologer_details_callback',
        'astrologers', // <- your CPT slug
        'normal',
        'default'
    );
}


function ab_astrologer_details_callback($post) {
    $price = get_post_meta($post->ID, 'ab_price', true);
    $hide = get_post_meta($post->ID, 'ab_hide', true);

    $all_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    ?>
    <p><label>Price:</label> <input type="number" name="ab_price" value="<?= esc_attr($price) ?>" /></p>
    <p><label><input type="checkbox" name="ab_hide" <?= checked($hide, 'on', false) ?> /> Hide from listing</label></p>

    <hr>
    <h4>Availability by Location</h4>
<div id="location-availability-wrapper">
    <?php
    $multi_availability = get_post_meta($post->ID, 'ab_multi_availability', true) ?: [];
    if (!empty($multi_availability)) {
        foreach ($multi_availability as $loc_index => $location_group) {
            ab_render_location_block($location_group, $loc_index);
        }
    } else {
        ab_render_location_block([], 0);
    }
    ?>
</div>
<p><button type="button" class="button" onclick="ab_add_location_block()">+ Add Location</button></p>

<script>
    function ab_add_location_block() {
        const wrapper = document.getElementById('location-availability-wrapper');
        const index = wrapper.children.length;
        const html = `<?php ob_start(); ab_render_location_block([], '__INDEX__'); echo str_replace("\n", '', addslashes(ob_get_clean())); ?>`.replace(/__INDEX__/g, index);
        wrapper.insertAdjacentHTML('beforeend', html);
    }

    // Event delegation for remove buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('ab-remove-location-button')) {
            const block = e.target.closest('.ab-location-block');
            if (block) block.remove();
        }
    });
</script>



    <?php
}

function ab_render_location_block($location_group, $loc_index) {
    $location_name = isset($location_group['location']) ? esc_attr($location_group['location']) : '';
    $days = $location_group['days'] ?? [];
    $from = $location_group['from'] ?? '';
    $to = $location_group['to'] ?? '';
    $all_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

    $slots=$location_group['slots'] ?? [];
    // print_r($slots);
    echo "<div class='ab-location-block' style='border:1px solid #ccc; padding:10px; margin-bottom:15px; position:relative;'>";

    echo "<p><strong>Location:</strong> <input type='text' name='ab_multi_availability[{$loc_index}][location]' value='{$location_name}' /></p>";

    echo "<p><strong>Available Days:</strong><br>";
    foreach ($all_days as $day) {
        $checked = in_array($day, $days) ? 'checked' : '';
        echo "<label style='margin-right:8px; display:inline-block;'>";
        echo "<input type='checkbox' name='ab_multi_availability[{$loc_index}][days][]' value='{$day}' {$checked} /> {$day}";
        echo "</label>";
    }
    echo "</p>";

    echo "<p><label>From Time: <input type='time' name='ab_multi_availability[{$loc_index}][from]' value='{$from}' /></label></p>";
    echo "<p><label>To Time: <input type='time' name='ab_multi_availability[{$loc_index}][to]' value='{$to}' /></label></p>";
    echo "<p><label>Slots are:</label></p>";
    if(!empty($slots))
    {
        foreach ($slots as $slot) {
            echo "<br><label>$slot</label>";
        }
    }
    else
    {
        echo "<br><label style='color:red;'>Invalid Time Chosen</label>";
        
    }
    echo "<p><button type='button' class='button ab-remove-location-button' style='background:#dc3545; color:white;'>Remove Location</button></p>";

    echo "</div>";
}


function ab_render_time_row($slot, $loc_index, $time_index) {
    $days = $slot['days'] ?? [];
    $from = $slot['from'] ?? '';
    $to = $slot['to'] ?? '';
    $all_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

    echo "<tr>";
    echo "<td>";
    foreach ($all_days as $day) {
        $checked = in_array($day, $days) ? 'checked' : '';
        echo "<label style='margin-right:5px; display:inline-block;'>";
        echo "<input type='checkbox' name='ab_multi_availability[{$loc_index}][slots][{$time_index}][days][]' value='{$day}' {$checked} /> {$day}";
        echo "</label><br>";
    }
    echo "</td>";
    echo "<td><input type='time' name='ab_multi_availability[{$loc_index}][slots][{$time_index}][from]' value='{$from}' /></td>";
    echo "<td><input type='time' name='ab_multi_availability[{$loc_index}][slots][{$time_index}][to]' value='{$to}' /></td>";
    echo "</tr>";
}

function ab_generate_slots($from, $to) {
    $slots = [];

    $start = strtotime($from);
    $end = strtotime($to);

    while ($start + 1800 <= $end) { // 30 min session
        $sessionStart = date('H:i', $start);
        $sessionEnd = date('H:i', $start + 1800);
        $slots[] = "$sessionStart-$sessionEnd";

        $start += 3600; // 30 min session + 30 min gap = 1 hour
    }

    return $slots;
}


add_action('save_post', 'ab_save_astrologer_meta');
function ab_save_astrologer_meta($post_id) {
    if (array_key_exists('ab_price', $_POST)) {
        update_post_meta($post_id, 'ab_price', sanitize_text_field($_POST['ab_price']));
        update_post_meta($post_id, 'ab_hide', isset($_POST['ab_hide']) ? 'on' : 'off');

        $multi_availability = $_POST['ab_multi_availability'] ?? [];
        $cleaned = [];

        foreach ($multi_availability as $loc_index => $location_group) {
            $location = sanitize_text_field($location_group['location']);
            $days = array_map('sanitize_text_field', $location_group['days'] ?? []);
            $from = sanitize_text_field($location_group['from'] ?? '');
            $to = sanitize_text_field($location_group['to'] ?? '');
        
            // Check if from and to are set
            if (!empty($from) && !empty($to)) {
                $slots = ab_generate_slots($from, $to);
            } else {
                $slots = [];
            }
        
            $cleaned[] = [
                'location' => $location,
                'days' => $days,
                'from' => $from,
                'to' => $to,
                'slots' => $slots
            ];
        }
        

        update_post_meta($post_id, 'ab_multi_availability', $cleaned);
    }
}

add_filter('woocommerce_order_item_display_meta_value', 'ab_format_booking_meta_display', 10, 2);

function ab_format_booking_meta_display($display_value, $meta) {
    $key = $meta->key;

    if ($key === 'booking_date') {
        $display_value = date('d M Y', strtotime($meta->value));
    } elseif (in_array($key, ['booking_slot', 'booking_location', 'ab_user_name', 'ab_user_email', 'ab_user_phone'])) {
        $display_value = esc_html($meta->value);
    }

    return $display_value;
}

add_filter('woocommerce_order_item_display_meta_key', 'ab_format_admin_meta_key', 10, 3);
function ab_format_admin_meta_key($display_key, $meta, $item) {
    if ($display_key === 'astrologer_id' && !current_user_can('manage_woocommerce')) {
        return ''; // Hide label for non-admins
    }

    switch ($display_key) {
        case 'booking_date': return 'Date';
        case 'booking_slot': return 'Time Slot';
        case 'booking_location': return 'Location';
        case 'astrologer_name': return 'Astrologer';
        case 'astrologer_id': return 'Astrologer ID';
        case 'ab_user_name': return 'Customer Name';
        case 'ab_user_email': return 'Email';
        case 'ab_user_phone': return 'Phone';
        default: return $display_key;
    }
}

add_filter('woocommerce_order_item_get_formatted_meta_data', 'ab_hide_astrologer_id_for_non_admins', 10, 2);
function ab_hide_astrologer_id_for_non_admins($formatted_meta, $order_item) {
    // Only modify for non-admins
    if (!current_user_can('manage_woocommerce')) {
        foreach ($formatted_meta as $key => $meta) {
            if (in_array($meta->key, ['astrologer_id','ab_user_name','ab_user_email','ab_user_phone'])) {
                unset($formatted_meta[$key]);
            }
        }
    }
    return $formatted_meta;
}




