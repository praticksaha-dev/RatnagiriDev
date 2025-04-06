<?php /*Template Name: Layout: About Us*/
get_header();
if (have_posts()):
    while (have_posts()):
        the_post();
        get_sidebar('banner');
        ?>

        <!-- hs about ind wrapper Start -->
        <div class="hs_about_indx_main_wrapper about_page_wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="hs_about_left_img_wrapper">
                            <?php
                            // Get the about_ratnagiri_image field (assuming it's an image field)
                            $about_image = get_field('about_ratnagiri_image');
                            if ($about_image): ?>
                                <img src="<?php echo $about_image; ?>" class="rotating" alt="image" />
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="hs_about_heading_main_wrapper">
                            <div class="hs_about_heading_wrapper">
                                <?php
                                // Get the about_ratnagiri_heading field
                                $heading = get_field('about_ratnagiri_heading');
                                if ($heading): ?>
                                    <h2><?php echo $heading; ?></h2>
                                <?php endif; ?>
                                <h4><span>&nbsp;</span></h4>
                            </div>
                        </div>
                        <div class="hs_about_right_cont_wrapper">
                            <?php
                            // Get the about_ratnagiri_phone_no field
                            $phone_no = get_field('about_ratnagiri_phone_no');
                            if ($phone_no): ?>
                                <h1><?php echo esc_html($phone_no); ?></h1>
                            <?php endif; ?>

                            <?php
                            // Get the about_ratnagiri_sub_heading field
                            $sub_heading = get_field('about_ratnagiri_sub_heading');
                            if ($sub_heading): ?>
                                <h2><?php echo esc_html($sub_heading); ?></h2>
                            <?php endif; ?>

                            <?php
                            // Get the about_ratnagiri_text field
                            $about_text = get_field('about_ratnagiri_text');
                            if ($about_text): ?>
                                <?php echo $about_text; ?>
                            <?php endif; ?>

                            <div class="hs_effect_btn hs_about_btn">
                                <ul>
                                    <?php
                                    // Get the about_ratnagiri_button_link field
                                    $button_link = get_field('about_ratnagiri_button_link');
                                    if ($button_link): ?>
                                        <li><a href="<?php echo $button_link; ?>" class="hs_btn_hover">Read more</a></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- hs about ind wrapper End -->


        <!-- hs about progress wrapper Start -->
        <div class="hs_about_progress_main_wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="hs_about_progress_img_left">
                            <h2><?php the_field('our_horoscope_progress_haeding'); ?></h2>
                            <h4><span>&nbsp;</span></h4>
                            <?php
                            $image = get_field('our_horoscope_progress_image');
                            if ($image):
                                ?>
                                <img src="<?php echo $image; ?>" class="img-responsive" alt="image" />
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="hs_about_progress_cont_left">
                            <h2><?php the_field('questions_faq_haeding'); ?></h2>
                            <h4><span>&nbsp;</span></h4>
                            <div class="accordionFifteen">
                                <div class="panel-group" id="accordionFifteenLeft" role="tablist">
                                    <?php if (have_rows('faq_questions_and_answerer')):
                                        $i = 1; ?>
                                        <?php while (have_rows('faq_questions_and_answerer')):
                                            the_row(); ?>
                                            <div class="panel panel-default truck_pannel">
                                                <div class="panel-heading" role="tab">
                                                    <h4 class="panel-title">
                                                        <a class="<?php echo ($i == 1) ? '' : 'collapsed'; ?>" data-toggle="collapse"
                                                            data-parent="#accordionFifteenLeft"
                                                            href="#collapseFifteenLeft<?php echo $i; ?>"
                                                            aria-expanded="<?php echo ($i == 1) ? 'true' : 'false'; ?>">
                                                            <?php the_sub_field('question'); ?>
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="collapseFifteenLeft<?php echo $i; ?>"
                                                    class="panel-collapse collapse <?php echo ($i == 1) ? 'in' : ''; ?>"
                                                    role="tabpanel">
                                                    <div class="panel-body">
                                                        <div class="panel_cont">
                                                            <?php the_sub_field('answere'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php $i++; endwhile; ?>
                                    <?php endif; ?>
                                </div>
                                <!-- /.panel-group -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- hs about progress wrapper End -->
        <?php
    endwhile;
endif;
get_footer(); ?>