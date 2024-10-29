<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly

/*
 * Woocommerce single product support
 */
function sacfvt_wc_positions( $positions ) {
	$positions['product'] = array(
		'woocommerce_before_main_content' => array(
			'title'       => esc_html__( 'Before main content', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_before_main_content',
			'position'    => 'before',
			'priority'    => 5,
			'priority_ui' => true,
		),
		'woocommerce_after_main_content' => array(
			'title'       => esc_html__( 'After main content', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_after_main_content',
			'position'    => 'before',
			'priority'    => 15,
			'priority_ui' => true,
		),
		'woocommerce_before_single_product' => array(
			'title'       => esc_html__( 'Before single product', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_before_single_product',
			'position'    => 'before',
			'priority'    => 5,
			'priority_ui' => true,
		),
		'woocommerce_before_single_product_summary' => array(
			'title'       => esc_html__( 'Before single product summary', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_before_single_product_summary',
			'position'    => 'before',
			'priority'    => 5,
			'priority_ui' => true,
		),
		'woocommerce_single_product_summary' => array(
			'title'       => esc_html__( 'Single product summary', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_single_product_summary',
			'position'    => 'before',
			'priority'    => 5,
			'priority_ui' => true,
		),
		'woocommerce_after_single_product_summary' => array(
			'title'       => esc_html__( 'After single product summary', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_after_single_product_summary',
			'position'    => 'before',
			'priority'    => 5,
			'priority_ui' => true,
		),
		'woocommerce_after_single_product' => array(
			'title'       => esc_html__( 'After single product', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_after_single_product',
			'position'    => 'before',
			'priority'    => 5,
			'priority_ui' => true,
		),
		'woocommerce_product_additional_information' => array(
			'title'       => esc_html__( 'Product additional information', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_product_additional_information',
			'position'    => 'before',
			'priority'    => 10,
			'priority_ui' => true,
		),
		'woocommerce_product_meta_start' => array(
			'title'       => esc_html__( 'Before product meta', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_product_meta_start',
			'position'    => 'before',
			'priority'    => 10,
			'priority_ui' => true,
		),
		'woocommerce_product_meta_end' => array(
			'title'       => esc_html__( 'After product meta', 'awv-plugin' ),
			'type'        => 'action',
			'hook'        => 'woocommerce_product_meta_end',
			'position'    => 'before',
			'priority'    => 5,
			'priority_ui' => true,
		),
		'woocommerce_product_tab' => array(
			'title'       => esc_html__( 'Tab', 'awv-plugin' ),
			'type'        => 'tab',
			'position'    => 'before',
			'priority'    => 10,
			'priority_ui' => true,
		),
	);

	return $positions;
}
add_filter( 'sacfvt_box_positions', 'sacfvt_wc_positions' );