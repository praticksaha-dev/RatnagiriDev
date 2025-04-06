<?php

function ab_add_admin_menu() {
    add_menu_page('Astro Booking Settings', 'Astro Booking', 'manage_options', 'astro-booking-settings', 'ab_settings_page');
}
add_action('admin_menu', 'ab_add_admin_menu');

function ab_settings_page() {
    if (isset($_POST['ab_save_availability'])) {
        update_option('ab_location_availability', $_POST['ab_location_availability']);
        echo '<div class="updated"><p>Settings saved.</p></div>';
    }

    $availabilities = get_option('ab_location_availability', []);
    ?>
    <div class="wrap">
        <h2>Location Availability Settings</h2>
        <form method="post">
            <table class="widefat fixed" style="margin-top:20px;">
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Days</th>
                        <th>From Time</th>
                        <th>To Time</th>
                    </tr>
                </thead>
                <tbody id="availability-rows">
                    <?php
                    if (!empty($availabilities)) {
                        foreach ($availabilities as $i => $availability) {
                            ab_render_availability_row($availability, $i);
                        }
                    } else {
                        ab_render_availability_row([], 0);
                    }
                    ?>
                </tbody>
            </table>
            <p><button type="button" class="button" onclick="ab_add_row()">+ Add Availability</button></p>
            <p><input type="submit" class="button-primary" name="ab_save_availability" value="Save Settings"></p>
        </form>
    </div>

    <script>
        function ab_add_row() {
            const wrapper = document.getElementById('availability-rows');
            const index = wrapper.children.length;
            const html = `<?php ob_start(); ab_render_availability_row([], '__INDEX__'); echo str_replace("\n", '', addslashes(ob_get_clean())); ?>`.replace(/__INDEX__/g, index);
            wrapper.insertAdjacentHTML('beforeend', html);
        }
    </script>
    <?php
}


function ab_render_availability_row($data, $index) {
    $location = isset($data['location']) ? esc_attr($data['location']) : '';
    $days = isset($data['days']) ? $data['days'] : [];
    $start = isset($data['start']) ? esc_attr($data['start']) : '';
    $end = isset($data['end']) ? esc_attr($data['end']) : '';

    $all_days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
    ?>
    <div class="availability-row" style="padding:10px; border:1px solid #ccc; margin-bottom:10px;">
        <p><label>Location: <input type="text" name="ab_location_availability[<?= $index ?>][location]" value="<?= $location ?>" /></label></p>
        <p>Days:
            <?php foreach ($all_days as $day): ?>
                <label style="margin-right: 10px;">
                    <input type="checkbox" name="ab_location_availability[<?= $index ?>][days][]" value="<?= $day ?>" <?= in_array($day, $days) ? 'checked' : '' ?> /> <?= $day ?>
                </label>
            <?php endforeach; ?>
        </p>
        <p>
            <label>From: <input type="time" name="ab_location_availability[<?= $index ?>][start]" value="<?= $start ?>" /></label>
            <label>To: <input type="time" name="ab_location_availability[<?= $index ?>][end]" value="<?= $end ?>" /></label>
        </p>
    </div>
    <?php
}
