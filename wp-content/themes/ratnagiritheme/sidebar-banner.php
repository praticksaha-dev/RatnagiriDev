<?php if (is_front_page()) { ?>

 <!-- hs Slider End -->
 <div class="hs_sign_main_wrapper">

<div class="swiper mySwiper mt-4">
    <div class="swiper-wrapper">
        <?php
        if (have_rows('banner_slider')):
            while (have_rows('banner_slider')):
                the_row();
                if (get_sub_field('banner_image')) {
                ?>
                <div class="swiper-slide"><img src="<?php echo get_sub_field('banner_image'); ?>"
                        class="img-responsive banner-img" alt="image"></div>
            <?php
                }
            endwhile;
        endif;
        ?>
    </div>
    <div class="swiper-pagination"></div>
</div>
</div>

<?php } else { ?>
    <!-- hs About Title Start -->
    <div class="hs_indx_title_main_wrapper">
        <div class="hs_title_img_overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 full_width">
                    <div class="hs_indx_title_left_wrapper">
                        <h2><?php the_title(); ?></h2>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12  full_width">
                    <div class="hs_indx_title_right_wrapper">
                        <ul>
                            <li><a href="<?php echo home_url(); ?>">Home</a> &nbsp;&nbsp;&nbsp;> </li>
                            <li><?php the_title(); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>