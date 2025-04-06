<?php
add_shortcode('astrologer_calendar', 'ab_render_astrologer_calendar');
function ab_render_astrologer_calendar($atts) {
    global $post;
    if (!$post || get_post_type($post) !== 'astrologers') return '';
    $ab_price= get_post_meta( $post->ID, 'ab_price' , true);
    
   global  $woocommerce;
    ob_start();
    ?>
    <div id="ab-location-wrapper" style="margin-top: 20px; display:none;">
        <label>Select Location:</label>
        <select id="ab-location-select"></select>
    </div>
    <div id="astrologer-calendar"></div>
    <div id="ab-slots-wrapper" style="margin-top: 20px; display:none;">
        <label>Available Slots:</label>
        <div id="ab-slots"></div>
    </div>

    <!-- Modal for Booking -->
    <div class="modal fade" id="ab-booking-modal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <form id="ab-booking-form">
                <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="bookingModalLabel">Confirm Booking</h4>
                </div>
                <div class="modal-body">
                <div class="form-group">
                    <label>Date</label>
                    <input type="text" id="ab-modal-date" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label>Time Slot</label>
                    <input type="text" id="ab-modal-slot" class="form-control" disabled>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="ab-user-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="ab-user-email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" id="ab-user-phone" class="form-control" required>
                </div>
                <div class="form-group error-msg" id="booking_error" style="display:none;"></div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="ab-pay-btn" class="btn btn-success">Pay <?php echo get_woocommerce_currency_symbol().$ab_price;?> & Book</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        const postID = <?php echo $post->ID; ?>;

        // Initialize calendar but hide initially
        $("#astrologer-calendar").hide();

        // Datepicker will show only after a location is selected
        $("#ab-location-select").on("change", function() {
            const selectedLocation = $(this).val();
            if (!selectedLocation) return;

            // Get available days for the selected location
            $.post(ab_ajax.ajaxurl, {
                action: 'ab_get_days_for_location',
                post_id: postID,
                location: selectedLocation
            }, function(response) {
                if (!response || !Array.isArray(response.days)) return;

                const allowedDays = response.days.map(day => {
                    return ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'].indexOf(day);
                });

                const tomorrow = new Date('<?php echo date('Y-m-d', strtotime('+1 day')); ?>');

                $("#astrologer-calendar").datepicker("destroy").datepicker({
                    minDate: tomorrow,
                    beforeShowDay: function(date) {
                        return [allowedDays.includes(date.getDay()), ''];
                    },
                    onSelect: function(dateText) {
                        $.post(ab_ajax.ajaxurl, {
                            action: 'ab_get_slots_for_date_location',
                            post_id: postID,
                            date: dateText,
                            location: selectedLocation
                        }, function(slots) {
                            const wrapper = $("#ab-slots");
                            wrapper.empty();
                            $("#ab-slots-wrapper").show();

                            if (slots.length > 0) {
                                const select = $('<select id="ab-slot-select" class="form-select mb-2"></select>');
                                select.append('<option value="">Select a time slot</option>');

                                slots.forEach(slot => {
                                    select.append(`<option value="${slot}">${slot}</option>`);
                                });

                                wrapper.append(select);
                                wrapper.append('<button class="btn btn-primary mt-2" id="ab-book-btn">Book Now</button>');                            
                            } else {
                                wrapper.html('<p>No slots available for this date and location.</p>');
                            }
                        });

                    }
                }).show();
            });
        });

        // Populate location dropdown on page load
        $.post(ab_ajax.ajaxurl, {
            action: 'ab_get_locations_for_date',
            post_id: postID,
        }, function(locations) {
            const select = $("#ab-location-select");
            select.empty();
            select.append('<option value="">Select Location</option>');

            if (locations && locations.length > 0) {
                locations.forEach(loc => {
                    select.append(`<option value="${loc}">${loc}</option>`);
                });

                $("#ab-location-wrapper").show();
            } else {
                select.append('<option disabled>No locations available</option>');
            }
        });

        $(document).on("click", "#ab-book-btn", function() {
            const selectedSlot = $("#ab-slot-select").val();
            const selectedDate = $("#astrologer-calendar").datepicker("getDate");
            const formattedDate = $.datepicker.formatDate('dd-M-yy', selectedDate);

            if (!selectedSlot) return;

            $("#ab-modal-date").val(formattedDate);
            $("#ab-modal-slot").val(selectedSlot);
            $("#booking_error").hide();
            $("#ab-booking-modal").modal("show");
        });

        $(document).on("click", "#ab-pay-btn", function() {
            const selectedLocation = $("#ab-location-select").val();
            const selectedDate = $("#astrologer-calendar").datepicker("getDate");
            const date = $.datepicker.formatDate('yy-mm-dd', selectedDate);

            const slot = $("#ab-modal-slot").val();
            const name = $("#ab-user-name").val();
            const email = $("#ab-user-email").val();
            const phone = $("#ab-user-phone").val();

            if (!name || !email || !phone) {
                $("#booking_error").html("All fields are required.").show();
                return;
            }

            $.post(ab_ajax.ajaxurl, {
                action: 'ab_submit_booking',
                post_id: postID,
                date: date,
                location: selectedLocation,
                slot: slot,
                name: name,
                email: email,
                phone: phone
            }, function(response) {
                if (response.success) {
                    if(response.data.redirect_url){
                        window.location.href = response.data.redirect_url;
                    }
                }
                else {    
                    $("#booking_error").html(response.data.message).show();
                    setTimeout(() => {
                    $("#ab-booking-modal").modal("hide");                        
                    }, 3000);
                }
            });
        });

    });
    </script>


    <style>
        .ui-datepicker { font-size: 14px; }
    </style>
    <?php
    return ob_get_clean();
}

