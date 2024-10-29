<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly


function awv_field_options( $field_type ) {
	$fields = apply_filters( 'awv_field_options', array(
		'date_picker' => array(
			array(
				'field_key' => 'label',
				'type' => 'text',
				'label' => esc_html__( 'Field label', 'awv-plugin' )
			),
			array(
				'field_key' => 'separator',
				'type' => 'text',
				'label' => esc_html__( 'Separator between label and value', 'awv-plugin' )
			),
			array(
				'field_key' => 'prefix',
				'type' => 'text',
				'label' => esc_html__( 'Field value prefix', 'awv-plugin' )
			),
			array(
				'field_key' => 'sufix',
				'type' => 'text',
				'label' => esc_html__( 'Field value sufix', 'awv-plugin' )
			),
			array(
				'field_key' => 'placeholder',
				'type' => 'text',
				'label' => esc_html__( 'Default value', 'awv-plugin' )
			),
			array(
				'field_key' => 'empty',
				'type' => 'empty_cell'
			),
			array(
				'field_key' => 'css_class',
				'type' => 'text',
				'label' => esc_html__( 'CSS class', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_id',
				'type' => 'text',
				'label' => esc_html__( 'CSS id', 'awv-plugin' )
			),
		),
		'email' => array(
			array(
				'field_key' => 'label',
				'type' => 'text',
				'label' => esc_html__( 'Field label', 'awv-plugin' )
			),
			array(
				'field_key' => 'separator',
				'type' => 'text',
				'label' => esc_html__( 'Separator between label and value', 'awv-plugin' )
			),
			array(
				'field_key' => 'prefix',
				'type' => 'text',
				'label' => esc_html__( 'Field value prefix', 'awv-plugin' )
			),
			array(
				'field_key' => 'sufix',
				'type' => 'text',
				'label' => esc_html__( 'Field value sufix', 'awv-plugin' )
			),
			array(
				'field_key' => 'placeholder',
				'type' => 'text',
				'label' => esc_html__( 'Default value', 'awv-plugin' )
			),
			array(
				'field_key' => 'empty',
				'type' => 'empty_cell'
			),
			array(
				'field_key' => 'css_class',
				'type' => 'text',
				'label' => esc_html__( 'CSS class', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_id',
				'type' => 'text',
				'label' => esc_html__( 'CSS id', 'awv-plugin' )
			),
		),
		'file' => array(
			array(
				'field_key' => 'label',
				'type' => 'text',
				'label' => esc_html__( 'Field label', 'awv-plugin' )
			),
			array(
				'field_key' => 'separator',
				'type' => 'text',
				'label' => esc_html__( 'Separator between label and value', 'awv-plugin' )
			),
			array(
				'field_key' => 'prefix',
				'type' => 'text',
				'label' => esc_html__( 'Field value prefix', 'awv-plugin' )
			),
			array(
				'field_key' => 'sufix',
				'type' => 'text',
				'label' => esc_html__( 'Field value sufix', 'awv-plugin' )
			),
			array(
				'field_key' => 'empty',
				'type' => 'empty_cell'
			),
			array(
				'field_key' => 'link_text',
				'type' => 'text',
				'label' => esc_html__( 'File link text', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_class',
				'type' => 'text',
				'label' => esc_html__( 'CSS class', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_id',
				'type' => 'text',
				'label' => esc_html__( 'CSS id', 'awv-plugin' )
			),
		),
		'image' => array(
			array(
				'field_key' => 'label',
				'type' => 'text',
				'label' => esc_html__( 'Field label', 'awv-plugin' )
			),
			array(
				'field_key' => 'separator',
				'type' => 'text',
				'label' => esc_html__( 'Separator between label and value', 'awv-plugin' )
			),
			array(
				'field_key' => 'prefix',
				'type' => 'text',
				'label' => esc_html__( 'Field value prefix', 'awv-plugin' )
			),
			array(
				'field_key' => 'sufix',
				'type' => 'text',
				'label' => esc_html__( 'Field value sufix', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_class',
				'type' => 'text',
				'label' => esc_html__( 'CSS class', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_id',
				'type' => 'text',
				'label' => esc_html__( 'CSS id', 'awv-plugin' )
			),
		),
		'number' => array(
			array(
				'field_key' => 'label',
				'type' => 'text',
				'label' => esc_html__( 'Field label', 'awv-plugin' )
			),
			array(
				'field_key' => 'separator',
				'type' => 'text',
				'label' => esc_html__( 'Separator between label and value', 'awv-plugin' )
			),
			array(
				'field_key' => 'prefix',
				'type' => 'text',
				'label' => esc_html__( 'Field value prefix', 'awv-plugin' )
			),
			array(
				'field_key' => 'sufix',
				'type' => 'text',
				'label' => esc_html__( 'Field value sufix', 'awv-plugin' )
			),
			array(
				'field_key' => 'placeholder',
				'type' => 'text',
				'label' => esc_html__( 'Default value', 'awv-plugin' )
			),
			array(
				'field_key' => 'empty',
				'type' => 'empty_cell'
			),
			array(
				'field_key' => 'css_class',
				'type' => 'text',
				'label' => esc_html__( 'CSS class', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_id',
				'type' => 'text',
				'label' => esc_html__( 'CSS id', 'awv-plugin' )
			),
		),
		'page_link' => array(
			array(
				'field_key' => 'label',
				'type' => 'text',
				'label' => esc_html__( 'Field label', 'awv-plugin' )
			),
			array(
				'field_key' => 'separator',
				'type' => 'text',
				'label' => esc_html__( 'Separator between label and value', 'awv-plugin' )
			),
			array(
				'field_key' => 'prefix',
				'type' => 'text',
				'label' => esc_html__( 'Field value prefix', 'awv-plugin' )
			),
			array(
				'field_key' => 'sufix',
				'type' => 'text',
				'label' => esc_html__( 'Field value sufix', 'awv-plugin' )
			),
			array(
				'field_key' => 'placeholder',
				'type' => 'text',
				'label' => esc_html__( 'Default value', 'awv-plugin' )
			),
			array(
				'field_key' => 'link_text',
				'type' => 'text',
				'label' => esc_html__( 'File link text', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_class',
				'type' => 'text',
				'label' => esc_html__( 'CSS class', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_id',
				'type' => 'text',
				'label' => esc_html__( 'CSS id', 'awv-plugin' )
			),
		),
		'text' => array(
			array(
				'field_key' => 'label',
				'type' => 'text',
				'label' => esc_html__( 'Field label', 'awv-plugin' )
			),
			array(
				'field_key' => 'separator',
				'type' => 'text',
				'label' => esc_html__( 'Separator between label and value', 'awv-plugin' )
			),
			array(
				'field_key' => 'prefix',
				'type' => 'text',
				'label' => esc_html__( 'Field value prefix', 'awv-plugin' )
			),
			array(
				'field_key' => 'sufix',
				'type' => 'text',
				'label' => esc_html__( 'Field value sufix', 'awv-plugin' )
			),
			array(
				'field_key' => 'placeholder',
				'type' => 'text',
				'label' => esc_html__( 'Default value', 'awv-plugin' )
			),
			array(
				'field_key' => 'empty',
				'type' => 'empty_cell'
			),
			array(
				'field_key' => 'css_class',
				'type' => 'text',
				'label' => esc_html__( 'CSS class', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_id',
				'type' => 'text',
				'label' => esc_html__( 'CSS id', 'awv-plugin' )
			),
		),
		'textarea' => array(
			array(
				'field_key' => 'label',
				'type' => 'text',
				'label' => esc_html__( 'Field label', 'awv-plugin' )
			),
			array(
				'field_key' => 'separator',
				'type' => 'text',
				'label' => esc_html__( 'Separator between label and value', 'awv-plugin' )
			),
			array(
				'field_key' => 'prefix',
				'type' => 'text',
				'label' => esc_html__( 'Field value prefix', 'awv-plugin' )
			),
			array(
				'field_key' => 'sufix',
				'type' => 'text',
				'label' => esc_html__( 'Field value sufix', 'awv-plugin' )
			),
			array(
				'field_key' => 'placeholder',
				'type' => 'text',
				'label' => esc_html__( 'Default value', 'awv-plugin' )
			),
			array(
				'field_key' => 'empty',
				'type' => 'empty_cell'
			),
			array(
				'field_key' => 'css_class',
				'type' => 'text',
				'label' => esc_html__( 'CSS class', 'awv-plugin' )
			),
			array(
				'field_key' => 'css_id',
				'type' => 'text',
				'label' => esc_html__( 'CSS id', 'awv-plugin' )
			),
		),
	) );

	if ( ! isset( $fields[ $field_type ] ) ) {
		return false;
	}
	
	return $fields[ $field_type ];
}



/**
 * Prepare field option output
 * @param array $field_data 
 * @return string
 */
function awv_get_admin_field_template( $field_data ) {

	if ( awv_validate_field_type( $field_data['field_type'] ) ) {
		$field_options = awv_field_options( $field_data['field_type'] );
		$field_groups  = array_chunk( $field_options, 2 );
		
		$output = '<table class="wp-list-table widefat fixed striped">';

		foreach ( $field_groups as $field_group ) {
			$output .= '<tr>';
			foreach ( $field_group as $field_key => $field_fields ) {
				$output .= awv_get_admin_field_option( $field_fields, $field_data );
			}
			$output .= '</tr>';
		}
		
		$output .= '</table>';

		return $output;
	}
}


function awv_get_admin_field_option( $field_fields, $field_data ) {
	$output = '';

	switch ( $field_fields['type'] ) {
		case 'text':
			$output .= '<td>' . esc_html( $field_fields['label'] ) . '</td>';
			$output .= '<td><input type="text" class="acfv-field" data-type="text" data-field="' . esc_attr( $field_fields['field_key'] ) . '" name="awv_feild_item[' . esc_attr( $field_data['key'] ) . '][' . esc_attr( $field_fields['field_key'] ) . ']" value="' . esc_html(  $field_data[ $field_fields['field_key'] ] ) . '" /></td>';
			break;
		case 'empty_cell':
			$output .= '<td>&nbsp;</td>';
			$output .= '<td>&nbsp;</td>';
			break;
		case 'select':
			$output .= '<td>' . esc_html( $field_fields['label'] ) . '</td>';
			$output .= '<td>';
			$output .= '<select class="acfv-field" data-type="select" data-field="' . esc_attr( $field_fields['field_key'] ) . '" name="awv_feild_item[' . esc_attr( $field_data['key'] ) . '][' . esc_attr( $field_fields['field_key'] ) . ']">';
			foreach ( $field_fields['values'] as $key => $value ) {
				$output .= '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $field_data[ $field_fields['field_key'] ], false ) . '>' . esc_html( $value ) . '</option>';
			}
			$output .= '</select>';
			$output .= '</td>';
			break;
	}
	return $output;
}