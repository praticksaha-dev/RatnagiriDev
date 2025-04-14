<?php /*Template Name: Layout: Horoscope */
 get_header();
 if (have_posts()):
    while (have_posts()):
        the_post();
 get_sidebar('banner');
?>

<div class="hs_service_main_wrapper service-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="hs_about_heading_main_wrapper">
						<div class="hs_about_heading_wrapper">
							<h2><?php  echo get_field('horoscope_heading_title') ?></h2>
							<h4><span>&nbsp;</span></h4>
							<?php  echo get_field('horoscope_heading_text') ?>
						</div>
					</div>
				</div>
				<div class="portfolio-area ptb-100">
					<div class="container">
						<div class="row">

                        <?php
$horoscope_posts = get_posts(array(
	'post_type' => 'horoscope',
	'posts_per_page' => -1,
	'orderby' => 'menu_order',
	'order' => 'ASC'
));

foreach ($horoscope_posts as $post) {
	setup_postdata($post);

	$logo_icon_class = get_post_meta($post->ID, 'horoscope_logo_image', true);
	$date_range = get_post_meta($post->ID, 'horoscope_formdate_to_todate', true);
	?>

	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
		<div class="hs_service_main_box_wrapper">
			<div class="hs_service_icon_wrapper">
				<?php echo $logo_icon_class; ?>
				<div class="btc_step_overlay"></div>
			</div>
			<div class="hs_service_icon_cont_wrapper">
				<h2><?php echo get_the_title(); ?></h2>
				<p><?php echo esc_html($date_range); ?></p>
				<h5><a href="<?php echo get_permalink($post->ID); ?>">Read More <i class="fa fa-long-arrow-right"></i></a></h5>
			</div>
		</div>
	</div>

	<?php
}
wp_reset_postdata();
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


<?php
endwhile;
endif;
get_footer(); ?>