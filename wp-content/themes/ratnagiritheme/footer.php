	<!-- hs footer wrapper Start -->
    <div class="hs_footer_main_wrapper">
			<!-- hs footer wrapper Start -->
			<div class="hs_footer_top_wrapper">
				<div class="container">
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="hs_footer_list_wrapper">
								<h2>About us</h2>
								<p><?php echo get_field('footer_text','option'); ?></p><br />
								<h4><a href="<?php echo get_field('button_link','option'); ?>" class="text-white read-more-text-footer">Read More <i class="fa fa-long-arrow-right"></i></a>
								</h4>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="hs_footer_list_wrapper">
								<h2>Our <span>Services</span></h2>
								<ul class="hs_footer_list">
									<li><a href="<?php echo get_field('address_link','option') ?>"><img src="http://localhost/ratnagiriproject/wp-content/uploads/2025/04/map.png" class="img-responsive footer-contact-img"
												alt=""> <?php echo get_field('address','option') ?></a>
									</li>
									<li><a href="#"><img src="http://localhost/ratnagiriproject/wp-content/uploads/2025/04/phone.png"
												class="img-responsive footer-contact-img" alt=""><?php echo get_field('contect_numbers','option') ?></a>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="hs_footer_list_wrapper">
								<h2>Usefull <span>Links</span></h2>
							

								<?php
                        wp_nav_menu(array(
                            'theme_location' => 'secondary',
                            'menu_class' => 'hs_footer_list',
                            'menu_id' => 'usefull_links'
                        ))

                            ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- hs footer wrapper End -->
		<!-- hs bottom footer wrapper Start -->
		<div class="hs_bottom_footer_main_wrapper">
			<a href="javascript:" id="return-to-top"><i class="fa fa-angle-up"></i></a>
			<div class="container">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="footer_bottom_cont_wrapper">
							<p><?php echo get_field('copyright_text'); ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
<!-- hs bottom footer wrapper End -->
<?php //bloginfo('template_directory'); ?>
<?php wp_footer(); ?>
</body>

</html>