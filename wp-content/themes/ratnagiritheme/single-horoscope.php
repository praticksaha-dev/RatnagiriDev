<?php
get_header();
if (have_posts()):
	while (have_posts()):
		the_post();
		get_sidebar('banner');
		?>


		<!-- hs sidebar Start -->
		<div class="hs_kd_sidebar_main_wrapper hs_num_sidebar_main_wrapper">
			<div class="container">
				<div class="row">
					<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
						<div class="hs_kd_left_sidebar_main_wrapper">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="hs_sign_left_tabs_wrapper hs_sign_left_tabs_border_wrapper1">
										<div class="hs_slider_tabs_icon_wrapper">
											<i class="flaticon-aries-sign"></i>
										</div>
										<div class="hs_slider_tabs_icon_cont_wrapper hs_ar_tabs_heading_wrapper">
											<ul>
												<li><a href="#" class="hs_tabs_btn"><?php the_title(); ?></a></li>
												<li><?php echo get_field('horoscope_formdate_to_todate'); ?></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="hs_ar_first_sec_wrapper">
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
												<?php $image_url = get_the_post_thumbnail_url($post->ID, 'full'); ?>
												<?php if ($image_url) { ?>
													<div class="hs_ar_first_sec_img_wrapper">
														<img src="<?php echo $image_url ?>" alt="arlies_img" />
													</div>
												<?php } ?>
											</div>
											<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
												<div class="hs_ar_first_sec_img_cont_wrapper">
													<?php the_content(); ?>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="hs_ar_second_sec_cont_wrapper">
													<?php echo get_field('horoscope_second_desc'); ?>
												</div>
											</div>
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<div class="hs_pr_second_cont_wrapper hs_ar_second_sec_cont_list_wrapper">
													

													<ul>
														<?php

														if (have_rows('horoscope_lucky')):
															while (have_rows('horoscope_lucky')):
																the_row();

																?>
																<li>
																	<div class="hs_pr_icon_wrapper">
																		<i class="fa fa-circle"></i>
																	</div>
																	<div class="hs_pr_icon_cont_wrapper hs_ar_icon_cont_wrapper">
																		<p><?php echo get_sub_field('lucky_text'); ?></p>
																	</div>
																</li>
															<?php
															endwhile;
														endif;
														?>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
						<div class="hs_kd_right_sidebar_main_wrapper">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="hs_kd_right_first_sec_wrapper">
										<div class="hs_kd_right_first_sec_heading">
											<h2>Zodiac Sign</h2>
										</div>
										<div class="hs_blog_right_cate_list_cont_wrapper">
											<ul>
												<?php
												$horoscope_posts = get_posts(array(
													'post_type' => 'horoscope',
													'posts_per_page' => -1,
													'orderby' => 'menu_order',
													'order' => 'ASC'
												));

												foreach ($horoscope_posts as $post) {
													setup_postdata($post);
													?>
													<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
													<?php
												}
												wp_reset_postdata();
												?>
											</ul>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- hs sidebar End -->

		<?php
	endwhile;
endif;
get_footer(); ?>