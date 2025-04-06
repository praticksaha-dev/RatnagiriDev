<?php
/*Template Name: Layout: Gemstone*/
get_header();
if (have_posts()):
    while (have_posts()): 
		the_post();
get_sidebar('banner');

?>


<!-- hs sidebar Start -->
<div class="hs_blog_categories_main_wrapper shop_wrapper">
	<div class="container">
		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="hs_blog_left_sidebar_main_wrapper">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="hs_shop_tabs_cont_sec_wrapper">
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="row">

										<?php
										$args = array(
											'post_type' => 'product',
											'posts_per_page' => 8, // Adjust the number of products to show
											'orderby' => 'date',
											'order' => 'DESC',
										);

										$loop = new WP_Query($args);

										if ($loop->have_posts()):
											while ($loop->have_posts()):
												$loop->the_post();
												global $product;
												?>

												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
													<div class="hs_shop_prodt_main_box">
														<a href="<?php the_permalink(); ?>">
															<div class="hs_shop_prodt_img_wrapper">
																<?php if (has_post_thumbnail()): ?>
																	<img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>"
																		alt="img">
																<?php else: ?>
																	<img src="http://localhost/ratnagiriproject/wp-content/uploads/woocommerce-placeholder.png" alt="Default Product Image">
																<?php endif; ?>
															</div>
														</a>
														<div class="hs_shop_prodt_img_cont_wrapper">
															<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
															</h2>
															<p class="gemestone-para-text">
																<?php echo wp_trim_words(get_the_excerpt(), 15, '...'); ?></p>
															<div class="hs_shop_prodt_cart_btn">
																<a href="<?php the_permalink(); ?>">View More</a>
															</div>
														</div>
													</div>
												</div>

												<?php
											endwhile;
											wp_reset_postdata();
										else:
											echo '<p>No products found.</p>';
										endif;
										?>

									</div>
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