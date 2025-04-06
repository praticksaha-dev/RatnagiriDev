<?php
/***
* Services Post Type
***/

if(! class_exists('Progressive_Services_Post_Type')):
class Progressive_Services_Post_Type{

	function __construct(){
		// Adds the services post type 
		add_action('init',array(&$this,'services_init'),0);
		// Thumbnail support for services posts
		add_theme_support('post-thumbnails',array('services'));
	}
	

	function services_init(){
		/**
		 * Enable the Services custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		$labels = array(
			'name'					=> __('Services','Progressive'),
			'singular_name'		=> __('Services','Progressive'),
			'add_new'				=> __('Add New','Progressive'),
			'add_new_item'			=> __('Add New Service','Progressive'),
			'edit_item'			=> __('Edit Service','Progressive'),
			'new_item'				=> __('Add New Service','Progressive'),
			'view_item'			=> __('View Service','Progressive'),
			'search_items'			=> __('Search Services','Progressive'),
			'not_found'			=> __('No Services items found','Progressive'),
			'not_found_in_trash'	=> __('No Services found in trash','Progressive')
		);
		
		$args = array(
		    'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-clipboard',			
			'map_meta_cap' => true,
			'hierarchical' => false,
			'menu_position' => 5,
			'supports' => array('title','thumbnail','editor','page-attributes')
		); 
				
		$args = apply_filters('Progressive_services_args',$args);
		
		register_post_type('services',$args);
		
		// Add new taxonomy,NOT hierarchical(like tags)
		$labels_one = array(
			'name'                       => _x('Service Types','taxonomy general name'),
			'singular_name'              => _x('Service Type','taxonomy singular name'),
			'search_items'               => __('Search Service Types'),
			'popular_items'              => __('Popular Service Types'),
			'all_items'                  => __('All Service Types'),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __('Edit Service Type'),
			'update_item'                => __('Update Service Type'),
			'add_new_item'               => __('Add New Service Type'),
			'new_item_name'              => __('New Service Type Name'),
			'separate_items_with_commas' => __('Separate service types with commas'),
			'add_or_remove_items'        => __('Add or remove service types'),
			'choose_from_most_used'      => __('Choose from the most used service types'),
			'not_found'                  => __('No service types found.'),
			'menu_name'                  => __('Service Types'),
		);
	
		$args_one = array(
			'hierarchical'          => true,
			'labels'                => $labels_one,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array('slug' => 'service_type'),
		);
	
		register_taxonomy('service_type','services',$args_one);		
	}
}

new Progressive_Services_Post_Type;
endif;