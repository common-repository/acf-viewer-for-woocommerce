<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly

if ( ! function_exists( 'awv_single_field_shortcoder' ) ) {
	function awv_single_field_shortcoder( $atts, $content = null ) {
		extract( wp_parse_args( $atts, array(
			'post_id' 	 	 => 0,
			'field' 	 	 => '',
			'label' 	 	 => '',
			'separator'  	 => '',
			'prefix' 	 	 => '',
			'sufix' 	 	 => '',
			'placeholder'	 => '',
			'multy_value_separator' => '',
			'style'   	 	 => '',
			'css_class'   	 => '',
			'css_id' 	  	 => '',
			'padding_top' 	 => '',
			'padding_bottom' => '',
			'link_text'   	 => '',
			'visibility'  	 => 'public'
		) ) );


		if ( $visibility == 'public' || is_user_logged_in() ) {

			if ( ( ! is_singular() && $post_id === 0 ) || empty( $field ) ) {
				return '';
			}

			if ( $post_id === 0 ) {
				$post_id = get_the_ID();
			}

			$field_value = get_field( $field, $post_id ) ?: $placeholder;
			if ( ! empty( $field_value ) ) {
				
				$field_output = awv_prepare_field_value( $field_value, $atts );
				if ( ! empty( $field_output ) ) {
					
					$css_id = ! empty( $css_id ) ? 'id="' . $css_id . '"' : '';
					$label  = ! empty( $label ) ? '<span>' . $label . '</span>' : '';
					
					$style  = '';
					$style  .= ! empty( $padding_top ) && is_numeric( $padding_top ) ? 'padding-top:' . $padding_top . 'px;' : '';
					$style  .= ! empty( $padding_bottom ) && is_numeric( $padding_bottom ) ? 'padding-bottom:' . $padding_bottom . 'px;' : '';
					$style  = ! empty( $style ) ? 'style=' . $style . '' : '';

					return '<div class="afl-single-field ' . $css_class . '" ' . $css_id . ' ' . $style . '>' . $label . $separator . $prefix . $field_output . $sufix . '</div>';
				}
			}
		}
	}
}
add_shortcode( 'awv_field', 'awv_single_field_shortcoder' );

if ( ! function_exists( 'awv_box_shortcoder' ) ) {
	function awv_box_shortcoder( $atts, $content = null ) {
		extract( wp_parse_args( $atts, array(
			'post_id' => 0,
			'box_id'  => ''
		) ) );

		if ( ( ! is_singular() && $post_id === 0 ) || empty( $box_id ) ) {
			return '';
		}

		if ( $post_id == 0 ) {
			$post_id = get_the_ID();
		}

		$post_type     = get_post_type();
		$box_post_type = get_post_meta( $box_id, 'box_post_type', true );

		if ( $post_type != $box_post_type ) {
			return '';
		}

		return awv_box_output( $box_id, $post_id );
	}
}
add_shortcode( 'awv_box', 'awv_box_shortcoder' );