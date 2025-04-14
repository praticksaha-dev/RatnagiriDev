<?php
/***
 * Horoscopes Post Type
 ***/

if (!class_exists('Progressive_Horoscope_Post_Type')):
	class Progressive_Horoscope_Post_Type
	{

		function __construct()
		{
			// Adds the horoscope post type 
			add_action('init', array(&$this, 'horoscope_init'), 0);
			// Thumbnail support for horoscope posts
			add_theme_support('post-thumbnails', array('horoscope'));
		}

		function horoscope_init()
		{
			$labels = array(
				'name' => __('Horoscopes', 'Progressive'),
				'singular_name' => __('Horoscope', 'Progressive'),
				'add_new' => __('Add New', 'Progressive'),
				'add_new_item' => __('Add New Horoscope', 'Progressive'),
				'edit_item' => __('Edit Horoscope', 'Progressive'),
				'new_item' => __('Add New Horoscope', 'Progressive'),
				'view_item' => __('View Horoscope', 'Progressive'),
				'search_items' => __('Search Horoscopes', 'Progressive'),
				'not_found' => __('No Horoscopes found', 'Progressive'),
				'not_found_in_trash' => __('No Horoscopes found in trash', 'Progressive')
			);

			$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'query_var' => true,
				'menu_icon' => 'dashicons-calendar-alt',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => 6,
				'supports' => array('title', 'thumbnail', 'editor', 'page-attributes')
			);

			$args = apply_filters('Progressive_horoscope_args', $args);

			register_post_type('horoscope', $args);

			// Custom taxonomy for horoscope types
			$labels_tax = array(
				'name' => _x('Horoscope Types', 'taxonomy general name'),
				'singular_name' => _x('Horoscope Type', 'taxonomy singular name'),
				'search_items' => __('Search Horoscope Types'),
				'popular_items' => __('Popular Horoscope Types'),
				'all_items' => __('All Horoscope Types'),
				'edit_item' => __('Edit Horoscope Type'),
				'update_item' => __('Update Horoscope Type'),
				'add_new_item' => __('Add New Horoscope Type'),
				'new_item_name' => __('New Horoscope Type Name'),
				'separate_items_with_commas' => __('Separate horoscope types with commas'),
				'add_or_remove_items' => __('Add or remove horoscope types'),
				'choose_from_most_used' => __('Choose from the most used horoscope types'),
				'not_found' => __('No horoscope types found.'),
				'menu_name' => __('Horoscope Types'),
			);

			$args_tax = array(
				'hierarchical' => true,
				'labels' => $labels_tax,
				'show_ui' => true,
				'show_admin_column' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array('slug' => 'horoscope_type'),
			);

			register_taxonomy('horoscope_type', 'horoscope', $args_tax);
		}
	}

	new Progressive_Horoscope_Post_Type;
endif;
