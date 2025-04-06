<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>
        <?php wp_title(); ?>
    </title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <?php wp_head(); ?>
</head>
<!--/head-->
<!-- <link rel="stylesheet" href="/assets/css/owl.carousel.css" />
<link rel="stylesheet" href="/assets/css/owl.carousel.css" /> -->

<body <?php body_class(); ?>>
    <?php //bloginfo('template_directory'); ?>
	<div class="main-body">

		<!-- main_header_wrapper Start -->
		<div class="main_header_wrapper" id="down">
			<!-- hs Navigation Start -->
			<div class="hs_navigation_header_wrapper">
				<div class="">
					<div class="row">
						<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
							<div class="hs_header_add_wrapper border_icon hidden-sm hidden-xs">
								<div class="hs_header_add_icon">
									<i class="fa fa-home"></i>
								</div>
								<div class="hs_header_add_icon_cont">
									<p><?php echo get_field('address','option') ?></p>
								</div>
							</div>
							<div class="hs_header_add_wrapper">
								<div class="hs_header_add_icon last">
									<i class="fa fa-phone"></i>
								</div>
								<div class="hs_header_add_icon_cont">
									<p class="topbar-text">
									<?php echo get_field('contect_numbers','option') ?>
										</p>
								</div>
							</div>
						</div>
						<div class="col-md-5">
							<div class="top-social-media">
								<p>Follow us on: </p>
								<ul>
									<li><a href="<?php echo get_field('facebook_link','option') ?>"><i class="fa fa-facebook"></i></a></li>
									<li><a href="<?php echo get_field('twitter_link','option') ?>"><i class="fa fa-twitter"></i></a></li>
									<li><a href="<?php echo get_field('youtube_link','option') ?>"><i class="fa fa-youtube-play"></i></a></li>
									<li><a href="<?php echo get_field('linkedin_link','option') ?>"><i class="fa fa-linkedin"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- hs Navigation End -->
			<!-- hs top header Start -->
			<div class="hs_header_Wrapper hidden-sm hidden-xs">

				<!-- hs top header Start -->
				<div class="hs_top_header_main_Wrapper">
					<div class="hs_header_logo_left">
						<div class="hs_logo_wrapper">
							<a href="<?php echo home_url(); ?>"><img src="<?php echo get_field('header_logo','option') ?>" class="img-responsive" alt="logo"
									title="Logo" /></a>
						</div>
					</div>
					<div class="hs_header_logo_right">
						<!-- <nav class="hs_main_menu">
							<ul>
								<li class="menu-button">
									<a class="menu-button" href="index.html">Home</a>
								</li>
								<li>
									<a class="menu-button" href="about.html">Astrologers</a>
								</li>
								<li>
									<a class="menu-button" href="aries.html">Gemstones </a>
								</li>
								<li>
									<a class="menu-button" href="aries.html">Horoscope </a>
								</li>
								<li>
									<a class="menu-button" href="aries.html">Jewellery </a>
								</li>
								<li>
									<a class="menu-button" href="aries.html">About us </a>
								</li>
							</ul>
						</nav> -->
					
						
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container' => 'nav',
                            'menu_class' => 'hs_main_menu',
                            'menu_id' => 'top_menu'
                        ))

                            ?>
						<div class="hs_btn_wrapper hidden-md">
							<ul class="d-flex">
								<li><a href="#!" data-toggle="modal" data-target=".bd-example-modal-lg"
										class="hs_btn_hover"><?php echo get_field('header_first_button_text','option'); ?></a></li>
								<li><a href="#!" data-toggle="modal" data-target=".bd-example-modal-lg"
										class="hs_btn_hover ml-2 bg-green"><?php echo get_field('header_second_button_text','option'); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
				<!-- hs top header End -->

			</div>
			<header class="mobail_menu visible-sm visible-xs">
				<div class="container">
					<div class="row">
						<div class="col-xs-6 col-sm-6">
							<div class="hs_logo">
								<a href="index.html"><img src="images/logo1.png" class="img-responsive m-logo" alt="Logo" title="Logo"></a>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6">
							<div class="cd-dropdown-wrapper">
								<a class="house_toggle" href="#0">
									<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
										xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="511.63px"
										height="511.631px" viewBox="0 0 511.63 511.631"
										style="enable-background:new 0 0 511.63 511.631;" xml:space="preserve">
										<g>
											<g>
												<path
													d="M493.356,274.088H18.274c-4.952,0-9.233,1.811-12.851,5.428C1.809,283.129,0,287.417,0,292.362v36.545
											c0,4.948,1.809,9.236,5.424,12.847c3.621,3.617,7.904,5.432,12.851,5.432h475.082c4.944,0,9.232-1.814,12.85-5.432
											c3.614-3.61,5.425-7.898,5.425-12.847v-36.545c0-4.945-1.811-9.233-5.425-12.847C502.588,275.895,498.3,274.088,493.356,274.088z" />
												<path
													d="M493.356,383.721H18.274c-4.952,0-9.233,1.81-12.851,5.427C1.809,392.762,0,397.046,0,401.994v36.546
											c0,4.948,1.809,9.232,5.424,12.854c3.621,3.61,7.904,5.421,12.851,5.421h475.082c4.944,0,9.232-1.811,12.85-5.421
											c3.614-3.621,5.425-7.905,5.425-12.854v-36.546c0-4.948-1.811-9.232-5.425-12.847C502.588,385.53,498.3,383.721,493.356,383.721z" />
												<path
													d="M506.206,60.241c-3.617-3.612-7.905-5.424-12.85-5.424H18.274c-4.952,0-9.233,1.812-12.851,5.424
											C1.809,63.858,0,68.143,0,73.091v36.547c0,4.948,1.809,9.229,5.424,12.847c3.621,3.616,7.904,5.424,12.851,5.424h475.082
											c4.944,0,9.232-1.809,12.85-5.424c3.614-3.617,5.425-7.898,5.425-12.847V73.091C511.63,68.143,509.82,63.861,506.206,60.241z" />
												<path
													d="M493.356,164.456H18.274c-4.952,0-9.233,1.807-12.851,5.424C1.809,173.495,0,177.778,0,182.727v36.547
											c0,4.947,1.809,9.233,5.424,12.845c3.621,3.617,7.904,5.429,12.851,5.429h475.082c4.944,0,9.232-1.812,12.85-5.429
											c3.614-3.612,5.425-7.898,5.425-12.845v-36.547c0-4.952-1.811-9.231-5.425-12.847C502.588,166.263,498.3,164.456,493.356,164.456z" />
											</g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
										<g>
										</g>
									</svg>
								</a>
							
								<?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container' => 'nav',
                            'menu_class' => 'hs_main_menu',
                            'menu_id' => 'top_menu'
                        ))

                            ?>
								<!-- .cd-dropdown -->
							</div>
						</div>
					</div>
				</div>
				<!-- .cd-dropdown-wrapper -->
			</header>
		</div>


		<div class="modal fade bd-example-modal-lg appointment-book-modal" tabindex="-1" role="dialog"
			aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
		<!-- main_header_wrapper end -->