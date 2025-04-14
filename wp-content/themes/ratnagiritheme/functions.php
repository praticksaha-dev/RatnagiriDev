<?php
/*****************************************
 * Weaver's Web Functions & Definitions *
 *****************************************/
$functions_path = get_template_directory() . '/functions/';
$post_type_path = get_template_directory() . '/inc/post-types/';
$theme_function_path = get_template_directory() . '/inc/theme-functions/';
/*--------------------------------------*/
/* Multipost Thumbnail Functions
/*--------------------------------------*/
require_once($functions_path . 'multipost-thumbnail/multi-post-thumbnails.php');
if (class_exists('MultiPostThumbnails')) {
	$types = array('page');
	foreach ($types as $type) {
		new MultiPostThumbnails(array(
			'label' => 'Top Banner Image',
			'id' => 'top-banner-image',
			'post_type' => $type
		));
	}
}
add_image_size('top-banner-size-image', 1920, 700, true);
/*--------------------------------------*/
/* Optional Panel Helper Functions
/*--------------------------------------*/
require_once($functions_path . 'admin-functions.php');
require_once($functions_path . 'admin-interface.php');
require_once($functions_path . 'theme-options.php');
function ratnaweb_ftn_wp_enqueue_scripts()
{
	if (!is_admin()) {
		wp_enqueue_script('jquery');
		if (is_singular() and get_site_option('thread_comments')) {
			wp_print_scripts('comment-reply');
		}
	}
}
add_action('wp_enqueue_scripts', 'ratnaweb_ftn_wp_enqueue_scripts');
function ratnaweb_ftn_get_option($name)
{
	$options = get_option('ratnaweb_ftn_options');
	if (isset($options[$name]))
		return $options[$name];
}
function ratnaweb_ftn_update_option($name, $value)
{
	$options = get_option('ratnaweb_ftn_options');
	$options[$name] = $value;
	return update_option('ratnaweb_ftn_options', $options);
}
function ratnaweb_ftn_delete_option($name)
{
	$options = get_option('ratnaweb_ftn_options');
	unset($options[$name]);
	return update_option('ratnaweb_ftn_options', $options);
}
function get_theme_value($field)
{
	$field1 = ratnaweb_ftn_get_option($field);
	if (!empty($field1)) {
		$field_val = $field1;
		return $field_val;
	}
}
/*--------------------------------------*/
/* Post Type Helper Functions
/*--------------------------------------*/
require_once($post_type_path . 'clients.php');
require_once($post_type_path . 'astrologers.php');
require_once($post_type_path . 'services.php');
require_once($post_type_path . 'horoscopes.php');
/*--------------------------------------*/
/* Theme Helper Functions
/*--------------------------------------*/
if (!function_exists('ratnaweb_theme_setup')):
	function ratnaweb_theme_setup()
	{
		add_theme_support('title-tag');
		add_theme_support('post-thumbnails');
		register_nav_menus(array(
			'primary' => __('Primary Menu', 'ratnaweb'),
			'secondary' => __('Secondary Menu', 'ratnaweb'),
		));
		add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
	}
endif;
add_action('after_setup_theme', 'ratnaweb_theme_setup');
function ratnaweb_widgets_init()
{
	register_sidebar(array(
		'name' => __('Widget Area', 'ratnaweb'),
		'id' => 'sidebar-1',
		'description' => __('Add widgets here to appear in your sidebar.', 'ratnaweb'),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '</h2>',
	));
}
add_action('widgets_init', 'ratnaweb_widgets_init');
function ratnaweb_scripts()
{
	// css -----------------------------
	wp_enqueue_style('animate', get_template_directory_uri() . '/assets/css/animate.css', array(), null);
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), null);
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/css/font-awesome.css', array(), null);
	wp_enqueue_style('fonts', get_template_directory_uri() . '/assets/css/fonts.css', array(), null);

	wp_enqueue_style('flaticon', get_template_directory_uri() . '/assets/css/flaticon.css', array(), null);

	wp_enqueue_style('owl.carousel', get_template_directory_uri() . '/assets/css/owl.carousel.css', array(), null);
	wp_enqueue_style('owl.theme.default', get_template_directory_uri() . '/assets/css/owl.theme.default.css', array(), null);

	wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), null);
	wp_enqueue_style('reset', get_template_directory_uri() . '/assets/css/reset.css', array(), null);
	wp_enqueue_style('datepicker', get_template_directory_uri() . '/assets/css/datepicker.css', array(), null);
	wp_enqueue_style('responsive', get_template_directory_uri() . '/assets/css/responsive.css', array(), null);

	// External Stylesheets
	wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), null);

	// Google Fonts
	wp_enqueue_style('google-fonts-marcellus', 'https://fonts.googleapis.com/css2?family=Marcellus&display=swap', array(), null);
	wp_enqueue_style('google-fonts-bellefair-marcellus', 'https://fonts.googleapis.com/css2?family=Bellefair&family=Marcellus&display=swap', array(), null);

	// Main Style
	wp_enqueue_style('style', get_template_directory_uri() . '/assets/css/style.css', array(), null);


	// js --------------------------------------

	wp_enqueue_script('jquery_min', get_template_directory_uri() . '/assets/js/jquery_min.js', array('jquery'), time(), true);
	wp_enqueue_script('bootstrap.min', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), time(), true);
	wp_enqueue_script('modernizr', get_template_directory_uri() . '/assets/js/modernizr.js', array('jquery'), time(), true);
	wp_enqueue_script('jquery.menu-aim', get_template_directory_uri() . '/assets/js/jquery.menu-aim.js', array('jquery'), time(), true);
	wp_enqueue_script('parallax.min', get_template_directory_uri() . '/assets/js/parallax.min.js', array('jquery'), time(), true);

	// wp_enqueue_script('owl.carousel', get_template_directory_uri() . '/assets/js/owl.carousel.js', array('jquery'), time(), true);
	wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/assets/js/owl.carousel.js', array('jquery'), time(), true);
	wp_enqueue_script('jquery.shuffle.min', get_template_directory_uri() . '/assets/js/jquery.shuffle.min.js', array('jquery'), time(), true);
	wp_enqueue_script('jquery.countTo', get_template_directory_uri() . '/assets/js/jquery.countTo.js', array('jquery'), time(), true);
	wp_enqueue_script('jquery.inview.min', get_template_directory_uri() . '/assets/js/jquery.inview.min.js', array('jquery'), time(), true);
	wp_enqueue_script('jquery.magnific-popup', get_template_directory_uri() . '/assets/js/jquery.magnific-popup.js', array('jquery'), time(), true);
	wp_enqueue_script('datepicker', get_template_directory_uri() . '/assets/js/datepicker.js', array('jquery'), time(), true);

	wp_enqueue_script('swiper-bundle.min', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), time(), true);


	wp_enqueue_script('custom', get_template_directory_uri() . '/assets/js/custom.js', array('jquery'), time(), true);




}
add_action('wp_enqueue_scripts', 'ratnaweb_scripts');
add_filter('comments_template', 'legacy_comments');
function legacy_comments($file)
{
	if (!function_exists('wp_list_comments'))
		$file = TEMPLATEPATH . '/legacy.comments.php';
	return $file;
}

function cc_mime_types($mimes) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
  }
  add_filter('upload_mimes', 'cc_mime_types');