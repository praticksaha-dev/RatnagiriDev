<?php
add_action('init', 'ratnaweb_ftn_options');
if (!function_exists('ratnaweb_ftn_options')) {
	function ratnaweb_ftn_options()
	{
		// If using image radio buttons,define a directory path
		$imagepath = get_stylesheet_directory_uri() . '/images/';
		$options = array(
			/* ---------------------------------------------------------------------------- */
			/* Header Setting */
			/* ---------------------------------------------------------------------------- */
			array(
				"name" => "Header Section",
				"type" => "heading"
			),
			array(
				"name" => "Choose Site Logo",
				"desc" => "Please choose your header logo",
				"id" => "ohs_header_logo",
				"std" => "",
				"type" => "upload"
			),
			/* ---------------------------------------------------------------------------- */
			/* Footer Setting */
			/* ---------------------------------------------------------------------------- */
			array(
				"name" => "Footer Section",
				"type" => "heading"
			),
			array(
				"name" => "Choose Site Logo",
				"desc" => "Please choose your footer logo",
				"id" => "ohs_footer_logo",
				"std" => "",
				"type" => "upload"
			),
			array(
				"name" => "Footer Short Description",
				"desc" => "Enter Short Description",
				"id" => "ohs_footer_shortcode",
				"std" => "",
				"type" => "textarea"
			),
			array(
				"name" => "Bottom Copyright",
				"desc" => "Enter Copyright Text Content",
				"id" => "ohs_footer_copyright",
				"std" => "",
				"type" => "textarea"
			),
			/* ---------------------------------------------------------------------------- */
		);
		ratnaweb_ftn_update_option('of_template', $options);
	}
}
?>