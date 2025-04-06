<?php
/***
* Clients Post Type
***/

if(! class_exists('Progressive_Clients_Post_Type')):
class Progressive_Clients_Post_Type{

	function __construct(){
		// Adds the clients post type and taxonomies
		add_action('init',array(&$this,'clients_init'),0);
		// Thumbnail support for clients posts
		add_theme_support('post-thumbnails',array('clients'));
	}

	function clients_init(){
		/**
		 * Enable the Clients_init custom post type
		 * http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		$labels = array(
			'name'					=> __('Clients','Progressive'),
			'singular_name'		=> __('Client Name','Progressive'),
			'add_new'				=> __('Add New','Progressive'),
			'add_new_item'			=> __('Add New Client','Progressive'),
			'edit_item'			=> __('Edit Client','Progressive'),
			'new_item'				=> __('Add New Client','Progressive'),
			'view_item'			=> __('View Client','Progressive'),
			'search_items'			=> __('Search Clients','Progressive'),
			'not_found'			=> __('No clients items found','Progressive'),
			'not_found_in_trash'	=> __('No clients found in trash','Progressive')
		);
		
		$args = array(
		    'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'query_var' => true,
			'menu_icon' => 'dashicons-businessman',
			'rewrite' => true,			
			'map_meta_cap' => true,
			'hierarchical' => false,
			'menu_position' => 5,
			'supports' => array('title','thumbnail','page-attributes')
		); 
		
		$args = apply_filters('Progressive_clients_args',$args);
		register_post_type('clients',$args);
	}
}
new Progressive_Clients_Post_Type;
endif;