<?php
get_header();
if (have_posts()):
    while (have_posts()):
        the_post();

        get_sidebar('banner');

        ?>



        <div class="hs_astrology_team_main_wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="hs_about_heading_main_wrapper">
                            <div class="hs_about_heading_wrapper">
                                <h2><?php echo get_field('meet_our_cosmic_experts_heading'); ?></h2>
                                <h4><span>&nbsp;</span></h4>
                                <?php echo get_field('meet_our_cosmic_experts_text'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="hs_team_slider_wrapper">
                            <div class="owl-carousel owl-theme">

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
                                        <div class="item">
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
                                                        <h2><a href="#"><?php the_title(); ?></a></h2>
                                                        <p>Magic ball reader</p>
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
                        </div>
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


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 d-flex-center">
                        <div class="hs_effect_btn">
                            <ul>
                                <li><a href="<?php echo get_field('experts_section_button_link'); ?>"
                                        class="hs_btn_hover"><?php echo get_field('experts_section_button_text'); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- hs title wrapper Start -->


        <section class="products-section">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="hs_about_heading_main_wrapper">
                    <div class="hs_about_heading_wrapper">
                        <h2><?php echo get_field('product_section_heading'); ?></h2>
                        <h4><span>&nbsp;</span></h4>
                        <?php echo get_field('product_section_text'); ?>
                    </div>
                </div>
            </div>
            <div class="hs_counter_main_wrapper">



                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 4, // Adjust as needed
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_visibility',
                            'field' => 'name',
                            'terms' => 'featured',
                        ),
                    ),
                );

                $loop = new WP_Query($args);

                if ($loop->have_posts()):
                    ?>
                    <!-- <h2>Featured Products</h2> -->
                    <div class="featured-products">
                        <?php while ($loop->have_posts()):
                            $loop->the_post();
                            global $product;
                            ?>

                            <div class="hs_counter_cont_wrapper">
                                <a href="<?php the_permalink(); ?>" class="products-link">
                                    <?php if (has_post_thumbnail()) { ?>
                                        <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>" class="img-responsive"
                                            alt="product">
                                    <?php } else { ?>
                                        <img src="http://localhost/ratnagiriproject/wp-content/uploads/woocommerce-placeholder.png"
                                            class="img-responsive" alt="product">

                                    <?php } ?>
                                    <div class="item">
                                        <p><?php the_title(); ?></p>
                                    </div>
                                </a>
                            </div>

                        <?php endwhile; ?>
                    </div>
                    <?php
                    wp_reset_postdata();
                else:
                    echo '<p>No featured products found.</p>';
                endif;
                ?>


            </div>
        </section>




        <div class="hs_service_main_wrapper service-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="hs_about_heading_main_wrapper">
                            <div class="hs_about_heading_wrapper">
                                <h2><?php echo get_field('services_section_heading'); ?></h2>
                                <h4><span>&nbsp;</span></h4>
                                <?php echo get_field('services_section_text'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-area ptb-100">
                        <div class="container">
                            <div class="row">


                                <?php
                                $args = array(
                                    'post_type' => 'services', // Change this to your CPT slug
                                    'posts_per_page' => -1, // Adjust the number of services to show
                                    'orderby' => 'date',
                                    'order' => 'DESC',
                                );

                                $services = get_posts($args);

                                if (!empty($services)):
                                    foreach ($services as $post):
                                        setup_postdata($post);
                                        ?>

                                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                                            <div class="hs_service_main_box_wrapper">
                                                <div class="hs_service_icon_wrapper">
                                                    <?php echo get_field('logo_icon', $post->ID); ?>
                                                    <div class="btc_step_overlay"></div>
                                                </div>
                                                <div class="hs_service_icon_cont_wrapper">
                                                    <h2><?php the_title(); ?></h2>
                                                    <p><?php the_excerpt(); ?></p>
                                                    <h5><a href="<?php the_permalink(); ?>">Read More <i
                                                                class="fa fa-long-arrow-right"></i></a></h5>
                                                </div>
                                            </div>
                                        </div>

                                        <?php
                                    endforeach;
                                    wp_reset_postdata();
                                else:
                                    echo '<p>No services found.</p>';
                                endif;
                                ?>


                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container -->
                    </div>
                    <!--/.portfolio-area-->
                </div>
            </div>
        </div>

        <!-- hs title wrapper End -->

        <div class="hs_sign_wrapper hs_sign_main_wrapper zodiac-sign-section">
            <div class="container">
                <div class="hs_sign_heading_wrapper">
                    <div class="hs_about_heading_main_wrapper">
                        <div class="hs_about_heading_wrapper">
                            <h2><i class="fa fa-clock"></i><?php echo get_field('choose_your_zodiac_sign_heading'); ?></h2>
                            <h4><span>&nbsp;</span></h4>
                            </i><?php echo get_field('choose_your_zodiac_sign_text'); ?>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <?php
                    if (have_rows('zodiac_sign_lists')):
                        while (have_rows('zodiac_sign_lists')):
                            the_row();

                            ?>
                            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-6">
                                <div class="hs_sign_box">
                                    <div class="sign_box_img">
                                        <img src="<?php echo get_sub_field('zodiac_sign_logo_icon') ?>" alt="icon">
                                    </div>
                                    <div class="sign_box_cont">
                                        <h2><?php echo get_sub_field('zodiac_sign_text') ?></h2>
                                        <p><?php echo get_sub_field('zodiac_sign_date') ?></p>
                                    </div>
                                </div>
                            </div>

                            <?php

                        endwhile;
                    endif;
                    ?>

                </div>
            </div>
        </div>


        <div class="container">
            <div class="row">

                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false, // Show categories even if empty
                ));

                if (!empty($categories)):
                    foreach ($categories as $category):
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image_url = ($thumbnail_id) ? wp_get_attachment_url($thumbnail_id) : 'images/default.jpg'; // Fallback image
                        ?>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="hs_blog_single_second_main_wrapper">
                                <div class="hs_blog_single_second_client_img_wrapper">
                                    <div
                                        class="hs_testi_client_main_wrapper hs_testi_client_main_right_wrapper hs_testi_client_blog_single_main_right_wrapper">
                                        <div class="hs_testi_client_cont_img_sec">
                                            <img src="<?php echo esc_url($image_url); ?>" class="img-responsive"
                                                alt="<?php echo esc_attr($category->name); ?>">
                                        </div>
                                        <div class="hs_testi_client_cont_sec">
                                            <p>Today Price</p>
                                            <p><?php echo $category->description; ?></p> <!-- Category description as price -->
                                        </div>
                                    </div>
                                </div>
                                <div class="hs_blog_single_second_client_img_cont_wrapper">
                                    <h2><?php echo esc_html($category->name); ?></h2>
                                </div>
                            </div>
                        </div>

                        <?php
                    endforeach;
                else:
                    echo '<p>No product categories found.</p>';
                endif;
                ?>


            </div>
        </div>


        <!-- hs testi slider wrapper Start -->
        <div class="hs_testi_slider_main_wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="hs_about_heading_main_wrapper">
                            <div class="hs_about_heading_wrapper">
                                <h2><?php echo get_field('clients_are_saying_heading'); ?></h2>
                                <h4><span>&nbsp;</span></h4>
                                <?php echo get_field('clients_are_saying_text'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="hs_testi_slider_wrapper">
                            <div class="owl-carousel owl-theme">

                                <?php
                                if (have_rows('clients_feedback_lists')):
                                    $count = 0;

                                    while (have_rows('clients_feedback_lists')):
                                        the_row();

                                        if ($count % 2 == 0) { // Start a new item for every 2 testimonials
                                            echo '<div class="item"><div class="row">';
                                        }
                                        ?>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <div class="testimonial_slider_content">
                                                <img src="<?php echo get_sub_field('client_image'); ?>" class="img-responsive"
                                                    alt="Client Image">
                                                <h5><?php echo get_sub_field('client_name'); ?></h5>
                                                <small><?php echo get_sub_field('client_position'); ?></small>
                                                <h4><span>&nbsp;</span></h4>
                                                <p><?php echo get_sub_field('client_saying_text'); ?></p>
                                            </div>
                                        </div>

                                        <?php
                                        if ($count % 2 == 1 || $count == count(get_field('clients_feedback_lists')) - 1) {
                                            echo '</div></div>'; // Close row and item div if 2 items per slide
                                        }

                                        $count++;
                                    endwhile;

                                endif;
                                ?>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- hs testi slider wrapper End -->
        <!-- <script src="/assets/js/owl.carousel.js"></script> -->
        <!-- Initialize Swiper -->
        <script>
            var swiper = new Swiper(".mySwiper", {

                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                speed: 2000,
            });
        </script>
        <?php
    endwhile;
endif;

get_footer(); ?>