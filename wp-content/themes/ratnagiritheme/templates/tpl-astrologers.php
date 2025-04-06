<?php
/*Template Name: Layout: Astrologers*/
get_header();
if (have_posts()):
    while (have_posts()):
        the_post();
        get_sidebar('banner');

        ?>




        <section class="astrologers-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="hs_kd_five_heading_sec_wrapper">
                            <h2><?php echo get_field('astrologers_listing_section_heading'); ?></h2>
                            <h4><span>&nbsp;</span></h4>
                        </div>
                    </div>

                    <?php
                    // Get all astrologer post IDs (you can adjust this query as needed)
                    $args = array(
                        'post_type' => 'astrologers', // Replace with your custom post type
                        'posts_per_page' => -1, // Retrieve all posts (adjust as necessary)
                        'post_status' => 'publish',
                    );
                    $posts = get_posts($args); // Fetch the posts
            
                    // Start the loop to display astrologers
                    if ($posts):
                        foreach ($posts as $post):
                            setup_postdata($post);

                            // Get custom field values
                            $language = get_field('language');  // Language custom field
                            $charges = get_field('charges');  // Charges custom field
                            $image_url = get_the_post_thumbnail_url($post->ID, 'full'); // Get the post thumbnail (featured image)
                            ?>

                            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                                <div class="hs_astro_team_img_main_wrapper">
                                    <div class="hs_astro_img_wrapper">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="team-img">
                                        <ul>
                                            <li><a data-toggle="modal" data-target=".bd-example-modal-lg1"><i
                                                        class="fa fa-phone"></i>&nbsp; Book Now</a></li>
                                        </ul>
                                    </div>
                                    <div class="hs_astro_img_cont_wrapper">
                                        <div class="hs_astro_img_inner_wrapper">
                                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                            <p><i class="fa fa-language"></i>
                                                <?php
                                                if ($language) {
                                                    echo esc_html($language);
                                                } else {
                                                    echo 'N/A'; // Fallback if no language is set
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <div class="hs_astro_img_bottom_wrapper">
                                            <ul>
                                                <li>Min Charges :</li>
                                                <li>
                                                    <?php
                                                    if ($charges) {
                                                        echo '$' . esc_html($charges) . ' / Min.';
                                                    } else {
                                                        echo '$0 / Min.'; // Fallback if no charges are set
                                                    }
                                                    ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                        endforeach;
                        wp_reset_postdata(); // Reset the post data after the loop
                    else:
                        echo '<p>No astrologers found.</p>';
                    endif;
                    ?>


                </div>



                <!-- Book Astrologers Modal -->
                <div class="modal fade bd-example-modal-lg1 appointment-book-modal" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel1" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <div class="modal-body">
                                <p class="modal-head-text">Book your Appointment</p>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <form>
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="email" placeholder="Enter Name">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Enter Email">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Select Date">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Select Time">
                                    </div>
                                    <div class="form-group">
                                        <select class="form-select" name="" id="">
                                            <option value="">Location 1</option>
                                            <option value="">Location 2</option>
                                            <option value="">Location 3</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-default">Book Appointment</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Book Astrologers Modal -->
            </div>
                </section>


        <?php

    endwhile;
endif;
get_footer(); ?>