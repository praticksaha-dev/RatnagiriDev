<?php
/***
 * Astrologers Post Type
 ***/

if (!class_exists('Progressive_Astrologers_Post_Type')):
	class Progressive_Astrologers_Post_Type
	{

		function __construct()
		{
			// Adds the astrologers post type 
			add_action('init', array(&$this, 'astrologers_init'), 0);
			// Thumbnail support for astrologers posts
			add_theme_support('post-thumbnails', array('astrologers'));
		}


		function astrologers_init()
		{
			/**
			 * Enable the Astrologers custom post type
			 * http://codex.wordpress.org/Function_Reference/register_post_type
			 */
			$labels = array(
				'name' => __('Astrologers', 'Progressive'),
				'singular_name' => __('Astrologer', 'Progressive'),
				'add_new' => __('Add New', 'Progressive'),
				'add_new_item' => __('Add New Astrologer', 'Progressive'),
				'edit_item' => __('Edit Astrologer', 'Progressive'),
				'new_item' => __('Add New Astrologer', 'Progressive'),
				'view_item' => __('View Astrologer', 'Progressive'),
				'search_items' => __('Search Astrologers', 'Progressive'),
				'not_found' => __('No Astrologers found', 'Progressive'),
				'not_found_in_trash' => __('No Astrologers found in trash', 'Progressive')
			);

			$args = array(
				'labels' => $labels,
				'public' => true,
				'publicly_queryable' => true,
				'show_ui' => true,
				'query_var' => true,
				'menu_icon' => 'dashicons-star-filled',
				'map_meta_cap' => true,
				'hierarchical' => false,
				'menu_position' => 5,
				'supports' => array('title', 'thumbnail', 'editor', 'page-attributes')
			);

			$args = apply_filters('Progressive_astrologers_args', $args);

			register_post_type('astrologers', $args);

			// Add new taxonomy, NOT hierarchical (like tags)
			$labels_one = array(
				'name' => _x('Astrologer Types', 'taxonomy general name'),
				'singular_name' => _x('Astrologer Type', 'taxonomy singular name'),
				'search_items' => __('Search Astrologer Types'),
				'popular_items' => __('Popular Astrologer Types'),
				'all_items' => __('All Astrologer Types'),
				'parent_item' => null,
				'parent_item_colon' => null,
				'edit_item' => __('Edit Astrologer Type'),
				'update_item' => __('Update Astrologer Type'),
				'add_new_item' => __('Add New Astrologer Type'),
				'new_item_name' => __('New Astrologer Type Name'),
				'separate_items_with_commas' => __('Separate astrologer types with commas'),
				'add_or_remove_items' => __('Add or remove astrologer types'),
				'choose_from_most_used' => __('Choose from the most used astrologer types'),
				'not_found' => __('No astrologer types found.'),
				'menu_name' => __('Astrologer Types'),
			);

			$args_one = array(
				'hierarchical' => true,
				'labels' => $labels_one,
				'show_ui' => true,
				'show_admin_column' => true,
				'update_count_callback' => '_update_post_term_count',
				'query_var' => true,
				'rewrite' => array('slug' => 'astrologer_type'),
			);

			register_taxonomy('astrologer_type', 'astrologers', $args_one);
		}
	}

	new Progressive_Astrologers_Post_Type;
endif;
