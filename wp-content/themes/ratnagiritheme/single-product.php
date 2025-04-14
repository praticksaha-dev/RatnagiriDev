<?php
get_header();
if (have_posts()):
	while (have_posts()):
		the_post();
		get_sidebar('banner');
		?>
  <!-- hs shop single prod slider Start -->
  <div class="hs_shop_single_prod_slider_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="video_img_section_wrapper">
                    <div class="cc_ps_top_slider_section">
    <div class="owl-carousel owl-theme">
        <?php
        global $product;
        $attachment_ids = $product->get_gallery_image_ids();
        if ($attachment_ids && is_array($attachment_ids)) :
            $index = 0;
            foreach ($attachment_ids as $attachment_id) :
                $hash = 'img' . $index;
                $image_url = wp_get_attachment_image_url($attachment_id, 'large');
                ?>
                <div class="item" data-hash="<?php echo esc_attr($hash); ?>">
                    <img class="small img-responsive" src="<?php echo esc_url($image_url); ?>" alt="product image" />
                </div>
                <?php
                $index++;
            endforeach;
        endif;
        ?>
    </div>

    <!-- Thumbnails Navigation -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="video_nav_img">
                <div class="row">
                    <?php
                    if ($attachment_ids && is_array($attachment_ids)) :
                        $index = 0;
                        foreach ($attachment_ids as $attachment_id) :
                            $hash = 'img' . $index;
                            $thumb_url = wp_get_attachment_image_url($attachment_id, 'thumbnail');
                            ?>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 cc_ps_tabs">
                                <a class="button secondary url owl_nav" href="#<?php echo esc_attr($hash); ?>">
                                    <img src="<?php echo esc_url($thumb_url); ?>" class="img-responsive" alt="nav_img">
                                </a>
                            </div>
                            <?php
                            $index++;
                        endforeach;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="hs_shop_single_border_Wrapper">
						<div class="hs_shop_single_right_heading_wrapper">
							<h2><?php echo get_the_title(); ?>
                            
                           <?php echo get_field('color_details'); ?></h2>
						</div>
					</div>
				<?php  echo get_the_content(); ?>
					<div class="hs_btn_wrapper hidden-md">
						<ul class="d-flex">
							<li><a href="#!" data-toggle="modal" data-target=".bd-example-modal-lg" class="hs_btn_hover">Make Enquiry</a></li>
							<li><a href="#!" data-toggle="modal" data-target=".bd-example-modal-lg" class="hs_btn_hover ml-2 bg-green">Request a Call</a></li>
						</ul>
					</div>
                </div>
            </div>
        </div>
    </div>
    <!-- hs shop single prod slider End -->




<?php
endwhile;
endif;
get_footer(); ?>