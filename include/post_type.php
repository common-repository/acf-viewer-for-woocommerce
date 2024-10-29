<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly

/**
 * Register custom post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */

if ( ! function_exists( 'awv_register_post_type' ) ) {
	function awv_register_post_type() {

		$args = array(
			'labels'             => array(
				'name'               => _x( 'AWV box', 'post type general name', 'awv-plugin' ),
				'singular_name'      => _x( 'AWV box', 'post type singular name', 'awv-plugin' ),
				'menu_name'          => _x( 'AWV boxes', 'admin menu', 'awv-plugin' ),
				'name_admin_bar'     => _x( 'AWV box', 'add new on admin bar', 'awv-plugin' ),
				'add_new'            => _x( 'Add New', 'acfv results', 'awv-plugin' ),
				'add_new_item'       => esc_html__( 'Add AWV box', 'awv-plugin' ),
				'new_item'           => esc_html__( 'New AWV box', 'awv-plugin' ),
				'edit_item'          => esc_html__( 'Edit AWV box', 'awv-plugin' ),
				'view_item'          => esc_html__( 'View AWV box', 'awv-plugin' ),
				'all_items'          => esc_html__( 'All AWV boxes', 'awv-plugin' ),
				'search_items'       => esc_html__( 'Search AWV box', 'awv-plugin' ),
				'parent_item_colon'  => esc_html__( 'Parent AWV box:', 'awv-plugin' ),
				'not_found'          => esc_html__( 'No AWV box found.', 'awv-plugin' ),
				'not_found_in_trash' => esc_html__( 'No AWV box found in Trash.', 'awv-plugin' )
			),
			'menu_icon'			  => 'dashicons-media-code',
			'public'              => true,
			'publicly_queryable'  => false,
			'query_var'           => true,
			'capability_type'     => 'post',
			'exclude_from_search' => true, 
			'has_archive'         => false,
			'hierarchical'        => false,
			'supports'            => array( 'title' ),
		);

		if ( ! file_exists( AWV_DIR_PATH . '/pro/awv-pro.php' ) ) {
			$posts = new WP_Query( array( 'post_type' => 'awv_box' ) );
			if ( $posts->found_posts >= 1 ) {
				$args['capabilities'] = array( 'create_posts' => false );
				$args['map_meta_cap'] = true;
			}
		}

		register_post_type( 'awv_box', $args );
	}
}
add_action( 'init', 'awv_register_post_type' );