<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly


/**
 * Get available post types
 * @return array
 */
function awv_get_post_types() {
	$post_types = get_post_types( array( 'show_ui'	=> true	), 'objects' );
	$exclude 	= array( 'acf-field', 'attachment', 'awv_box', 'acf-field-group', 'shop_order', 'shop_coupon' );

	return array_diff_key( (array)$post_types, array_flip( $exclude ) );
}


/**
 * Ajax function. View string with field buttons for the admin box editor.
 * @return string
 */
function awv_get_field_buttons() {
	check_ajax_referer( 'awv_nonce', 'nonce' );

	if ( ! isset( $_POST['box_post_type'] ) || empty( $_POST['box_post_type'] ) || ! post_type_exists( $_POST['box_post_type'] ) ) {
		die();
	}

	echo awv_post_type_field_buttons( sanitize_key( $_POST['box_post_type'] ) );
	die();
}
add_action( 'wp_ajax_awv_get_field_buttons', 'awv_get_field_buttons' );


/**
 * Ajax function. Prepare field options fields
 * @return string
 */
function awv_get_field_row() {
	check_ajax_referer( 'awv_nonce', 'nonce' );

	if ( empty( $_POST['field_type'] ) || 
		   empty( $_POST['count_items'] ) || 
		   empty( $_POST['field_name'] ) || 
		 ! is_numeric( $_POST['count_items'] ) ) {
		die();
	}

	echo awv_get_field_row_template( array( 
		'key' 		 => sanitize_text_field( $_POST['count_items'] ),
		'field_type' => sanitize_text_field( $_POST['field_type'] ),
		'field_name' => sanitize_text_field( $_POST['field_name'] ),
		'field' 	 => sanitize_text_field( $_POST['field'] ),
		'editor' 	 => sanitize_text_field( $_POST['editor'] ),
	) );
	
	die();
}
add_action( 'wp_ajax_awv_get_field_row', 'awv_get_field_row' );

/**
 * Return field options fields.
 * @param array $args 
 * @return string
 */
function awv_get_field_row_template( $args ) {

	$field_data = wp_parse_args( $args, array(
		'key' 		  => '0',
		'field' 	  => '',
		'field_type'  => 'text',
		'field_name'  => 'Text',
		'label' 	  => '',
		'separator'   => '',
		'prefix' 	  => '',
		'sufix' 	  => '',
		'placeholder' => '',
		'multy_value_separator' => '',
		'style'   	  => '',
		'css_class'   => '',
		'css_id' 	  => '',
		'link_text'   => '',
		'active'      => 'active',
		'editor'      => 'line',
	) );

	ob_start();
	if ( $field_data['editor'] == 'line' ) {
		?>
			<div class="awv-feild-item <?php echo esc_attr( $field_data['active'] ); ?>" data-key="<?php echo esc_attr( $field_data['key'] ); ?>">
				<div class="awv-field-item-head">
					<span class="awv-cpacicon-move"></span>
					<div class="awv-item-title"><?php echo esc_html( $field_data['field_name'] ); ?></div>
					<div class="awv-item-actions">
						<a href="#" class="awv-edit-button"><?php esc_html_e( 'Edit', 'awv-plugin' ); ?></a>
						<a href="#" class="awv-close-button"><?php esc_html_e( 'Close', 'awv-plugin' ); ?></a>
						<a href="#" class="awv-remove-button"><?php esc_html_e( 'Remove', 'awv-plugin' ); ?></a>
					</div>
				</div>
				<div class="awv-field-item-body">
					<?php echo awv_get_admin_field_template( $field_data ); ?>
				</div>
				<input type="hidden" name="awv_feild_item[<?php echo esc_attr( $field_data['key'] ); ?>][field_type]" value="<?php echo esc_html( $field_data['field_type'] ); ?>">
				<input type="hidden" name="awv_feild_item[<?php echo esc_attr( $field_data['key'] ); ?>][field_name]" value="<?php echo esc_html( $field_data['field_name'] ); ?>">
				<input type="hidden" name="awv_feild_item[<?php echo esc_attr( $field_data['key'] ); ?>][field]" value="<?php echo esc_html( $field_data['field'] ); ?>">
			</div>
		<?php
	}
	if ( $field_data['editor'] == 'html' ) {
		echo awv_get_admin_field_template( $field_data ); ?>
		<input type="hidden" name="awv_feild_item[<?php echo esc_attr( $field_data['key'] ); ?>][field_type]" value="<?php echo esc_html( $field_data['field_type'] ); ?>">
		<input type="hidden" name="awv_feild_item[<?php echo esc_attr( $field_data['key'] ); ?>][field_name]" value="<?php echo esc_html( $field_data['field_name'] ); ?>">
		<input type="hidden" class="acfv-field" data-type="text" data-field="field" name="awv_feild_item[<?php echo esc_attr( $field_data['key'] ); ?>][field]" value="<?php echo esc_html( $field_data['field'] ); ?>">
		<?php
	}

	return ob_get_clean();
}


/**
 * View table with possible shortcodes.
 * @return string
 */
function awv_get_fields_table() {

	$groups = acf_get_field_groups( array('post_type' => 'product' ) );
	if ( ! empty( $groups ) ) {
		$output = '';
		foreach ( $groups as $key => $group ) {
			$fields = awv_filter_fields( acf_get_fields( $group['key'] ) );
			$output .= '<h3>' . $group['title'] . '</h3>';
			if ( ! empty( $fields ) ) {
				$output .= '
					<table class="wp-list-table widefat fixed striped">
						<tr>
							<th><b>' . esc_html__( 'Field name', 'awv-plugin' ) . '</b></th>
							<th><b>' . esc_html__( 'Shortcode', 'awv-plugin' ) . '</b></th>
						</tr>';
				foreach ( $fields as $key => $field ) {
					$output .= '
						<tr>
							<td>' . esc_html( $field['label'] ) . '</td>
							<td>[awv_field field="' . esc_html( $field['name'] ) . '"]</td>
						</tr>';
				}
				$output .= '</table>';
			} else {
				$output .= esc_html__( 'No Fields Found', 'awv-plugin' );
			}
		}
		echo $output;
	} else {
		esc_html_e( 'No Grops Found', 'awv-plugin' );
	}
}


/**
 * Return box editor buttons.
 * @param string $post_type 
 * @return string
 */
function awv_post_type_field_buttons( $post_type ) {
	if ( ! empty( $post_type ) ) {
		$groups = acf_get_field_groups( array('post_type' => $post_type ) );
		ob_start();
		if ( ! empty( $groups ) ) {
			foreach ( $groups as $key => $group ) {
				$fields = awv_filter_fields( acf_get_fields( $group['key'] ) );
				?>
				<div class="afl-buttons-group">
					<h3><?php echo esc_html( $group['title'] ); ?></h3><?php

					if ( ! empty( $fields ) ) {
						foreach ( $fields as $key => $field ) { ?>
							<div class="awv-add-field button awv-add-field" data-name="<?php echo $field['name']; ?>" data-type="<?php echo $field['type']; ?>"><?php echo esc_html( $field['label'] ); ?></div>
						<?php }
					} else {
						esc_html_e( 'No Fields Found', 'awv-plugin' );
					} ?>

				</div>
				<?php
			}
		} else {
			esc_html_e( 'No Grops Found', 'awv-plugin' );
		}
		return ob_get_clean();
	}
}


/**
 * Filter fields array. Allow only possible fields to use.
 * @param array $fields 
 * @return array
 */
function awv_filter_fields( $fields ) {
	$list = array();
	foreach ( $fields as $field ) {
		if ( awv_validate_field_type( $field['type'] ) ) {
			$list[] = $field;
		}
	}
	return $list;
}


/**
 * View possible positions for required post type.
 * @return string
 */
function awv_get_box_positions($selected) {

	$positions = awv_box_positions( 'product' );

	if ( ! empty( $positions ) ) {
		foreach ( $positions as $position_key => $position ) {
			$priority_ui = $position['priority_ui'] ? 'show' : 'hide';
			echo '<option 
				data-priority="' . esc_attr( $position['priority'] ) . '"
				data-priority_ui="' . esc_attr( $priority_ui ) . '"
				value="' . esc_attr( $position_key ) . '" ' . selected( $position_key, $selected, false ) . '>' . esc_html( $position['title'] ) . '</option>';
		}
	} else {
		echo '<option value="0">' . esc_html__( 'No Positions Found', 'awv-plugin' ) . '</option>';
	}
}


/**
 * Validate field type
 * @param string $field_type 
 * @return bool
 */
function awv_validate_field_type( $field_type ) {
	return awv_field_options( $field_type ) ? true : false;
}


/**
 * Return array with possible box positions
 * @param string $post_type 
 * @return array
 */
function awv_box_positions( $post_type = 'post' ) {
	$positions = apply_filters( 'awv_box_positions', array( 
		'product' => array(
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
				'position'    => 'after',
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
				'position'    => 'after',
				'priority'    => 5,
				'priority_ui' => true,
			),
			'woocommerce_after_single_product' => array(
				'title'       => esc_html__( 'After single product', 'awv-plugin' ),
				'type'        => 'action',
				'hook'        => 'woocommerce_after_single_product',
				'position'    => 'after',
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
				'position'    => 'after',
				'priority'    => 5,
				'priority_ui' => true,
			),
		)
	) );

	return isset( $positions[ $post_type ] ) ? $positions[ $post_type ] : array();
}


/**
 * Return array with possible to view box front-end positions
 * @return array
 */
function awv_box_styles() {
	return apply_filters( 'awv_box_styles', array(
		'paragraph' => array(
			'title' 	=> esc_html__( 'Paragraphs', 'awv-plugin' ),
			'open_tag' 	=> '',
			'close_tag' => '',
		),
		'table' => array(
			'title' 	=> esc_html__( 'Table(two columns)', 'awv-plugin' ),
			'open_tag' 	=> '<table class="awv-table">',
			'close_tag' => '</table>',
		),
		'list' => array(
			'title' 	=> esc_html__( 'List', 'awv-plugin' ),
			'open_tag' 	=> '<ul class="awv-list">',
			'close_tag' => '</ul>',
		),
		'list_two_columns' => array(
			'title' 	=> esc_html__( 'List two columns', 'awv-plugin' ),
			'open_tag' 	=> '<ul class="awv-list two-columns">',
			'close_tag' => '</ul>',
		),
		'list_three_columns' => array(
			'title' 	=> esc_html__( 'List three columns', 'awv-plugin' ),
			'open_tag'	=> '<ul class="awv-list three-columns">',
			'close_tag' => '</ul>',
		),
		'list_four_columns' => array(
			'title' 	=> esc_html__( 'List four columns', 'awv-plugin' ),
			'open_tag' 	=> '<ul class="awv-list four-columns">',
			'close_tag' => '</ul>',
		),
	) );
}


/**
 * View line editor fields rows. 
 * @param int $post_id 
 * @return string
 */
function awv_view_field_rows( $post_id ) {
	$awv_feilds = get_post_meta( $post_id, 'awv_feild_item', true );
	if ( ! empty( $awv_feilds ) ) {
		foreach ( $awv_feilds as $key => $awv_feild ) {
			$awv_feild['active'] = 'none';
			$awv_feild['key'] = $key;
			echo awv_get_field_row_template( $awv_feild );
		}
	}
}


/**
 * Prepare Advanced Custom Fields field value to output
 * @param string|array $field_value 
 * @param array $atts 
 * @return string
 */
function awv_prepare_field_value( $field_value, $atts ) {
	$value = '';
	$field_data = get_field_object( $atts['field'] );

	if ( ! $field_data || ! is_array( $field_data ) && awv_validate_field_type( $field_data['type'] ) ) {
		return '';
	}

	switch ( $field_data['type'] ) {
		case 'date_picker':
		case 'number':
		case 'text':
		case 'textarea':
			$value = $field_value;
			break;
		case 'email':
			$value = '<a href="' . esc_url( 'mailto:' . $field_value ) . '">' . sanitize_email( $field_value ) . '</a>';
			break;
		case 'file':
			$url = $field_value;
			if ( is_array( $field_value ) ) {
				$url = $field_value['url'];
			}
			if ( is_numeric( $field_value ) ) {
				$url = wp_get_attachment_url( $field_value );
			}
			$link_text = ! empty( $atts['link_text'] ) ? $atts['link_text'] : $field_value;
			$value = '<a href="' . esc_url( $url ) . '">' . esc_html( $link_text ) . '</a>';
			break;
		case 'page_link':
			$link_text = ! empty( $atts['link_text'] ) ? $atts['link_text'] : $field_value;
			$value = '<a href="' . esc_url( $field_value ) . '">' . esc_html( $link_text ) . '</a>';
			break;
		case 'image':
			$url = $field_value;
			if ( is_array( $field_value ) ) {
				$url = $field_value['url'];
			}
			if ( is_numeric( $field_value ) ) {
				$url = wp_get_attachment_url( $field_value );
			}
			$value = '<img src="' . esc_url( $url ) . '" alt="" />';
			break;
	}

	$value = apply_filters( 'awv_prepare_field_value', $value, $field_value, $atts );

	return $value;
}


/**
 * Return prepared box HTML for required post
 * @param int $box_id 
 * @param int $post_id 
 * @return string
 */
function awv_box_output( $box_id, $post_id, $view_box_title = true ) {
	$box_options = get_post_meta( $box_id );
	if ( ! empty( $box_options ) && ! empty( $box_options['awv_feild_item'][0] ) ) {
		if ( $box_options['box_visibility'][0] == 'public' || is_user_logged_in() ) {
			$awv_feild_items = unserialize( $box_options['awv_feild_item'][0] );
			$css_id 		 = ! empty( $box_options['css_id'][0] ) ? 'id="' . esc_attr( $box_options['css_id'][0] ) . '"' : '';
			
			$box_style 			= $box_options['box_style'][0];
			$box_styles 		= awv_box_styles();
			$box_style_options  = $box_styles[ $box_style ];

			$box_classes   = array();
			$box_classes[] = 'awv-box';
			$box_classes[] = 'box-' . esc_attr( $box_id );
			$box_classes[] = 'box-style-' . $box_style;
			$box_classes[] = $box_options['css_class'][0];

			$style  = '';
			$style .= ! empty( $box_options['padding_top'][0] ) && is_numeric( $box_options['padding_top'][0] ) ? 'padding-top:' . $box_options['padding_top'][0] . 'px;' : '';
			$style .= ! empty( $box_options['padding_bottom'][0] ) && is_numeric( $box_options['padding_bottom'][0] ) ? 'padding-bottom:' . $box_options['padding_bottom'][0] . 'px;' : '';
			$style  = ! empty( $style ) ? 'style=' . $style . '' : '';

			$output = '<div class="' . implode( ' ', $box_classes ) . '" ' . $css_id . ' ' . esc_attr( $style ) . '>';

			// Line items output
			if ( $box_options['box_editor'][0] == 'line' ) {
				if ( ! empty( $box_options['box_title'][0] ) && $view_box_title ) {
					$output .= '<h3 class="afl-box-title">' . esc_html( $box_options['box_title'][0] ) . '</h3>';
				}
				
				$output .= $box_style_options['open_tag'];

				foreach ( $awv_feild_items as $awv_feild_item ) {
					$output .= awv_box_item_html( $awv_feild_item, $box_style, $post_id );
				} 

				$output .= $box_style_options['close_tag'];
			} 


			// HTML output
			if ( $box_options['box_editor'][0] == 'html' && ! empty( $box_options['html_data'][0] ) ) {
			 	$output .= do_shortcode( $box_options['html_data'][0] );
			}

			$output .= '</div>';

			return $output;
		} else {
			return '';
		}
	}
}


/**
 * Add HTML wrapper for the field output. Line items style.
 * @param array $item 
 * @param string $box_style 
 * @param int $post_id 
 * @return string
 */
function awv_box_item_html( $item, $box_style, $post_id ) {
	$field = '';
			
	$placeholder = isset( $item['placeholder'] ) ? $item['placeholder'] : '';
	$field_value = get_field( $item['field'], $post_id ) ?: $placeholder;
	if ( ! empty( $field_value ) ) {
		
		$atts = array(
			'field' 	=> $item['field'],
			'link_text' => isset( $item['link_text'] ) ? $item['link_text'] : '',
			'style' 	=> isset( $item['style'] ) ? $item['style'] : '',
			'multy_value_separator' => isset( $item['multy_value_separator'] ) ? $item['multy_value_separator'] : '',
		);

		$css_id    = ! empty( $item['css_id'] ) ? 'id="' . $item['css_id'] . '"' : '';
		$css_class = ! empty( $item['css_class'] ) ? 'id="' . $item['css_class'] . '"' : '';

		$item['label'] 	   = isset( $item['label'] ) ? $item['label'] : '';
		$item['sufix']     = isset( $item['sufix'] ) ? $item['sufix'] : '';
		$item['prefix']    = isset( $item['prefix'] ) ? $item['prefix'] : '';
		$item['separator'] = isset( $item['separator'] ) ? $item['separator'] : '';
		
		$prepared_field = awv_prepare_field_value( $field_value, $atts );
		switch ( $box_style ) {
			case 'paragraph':
				$field = '<p ' . $css_id . ' ' . $css_class . '>' . $item['label'] . $item['separator'] . $item['prefix'] . $prepared_field . $item['sufix'] . '</p>';
				break;
			case 'table':
				$field = '<tr ' . $css_id . ' ' . $css_class . '><td>' . $item['label'] . '</td><td>' . $item['prefix'] . $prepared_field . $item['sufix'] . '</td></tr>';
				break;
			case 'list':
			case 'list_two_columns':
			case 'list_three_columns':
			case 'list_four_columns':
				$field = '<li ' . $css_id . ' ' . $css_class . '>' . $item['label'] . $item['separator'] . $item['prefix'] . $prepared_field . $item['sufix'] . '</li>';
				break;
		}
	}

	return $field;
}


/**
 * Add custom columns to the Box post type
 * @param array $columns 
 * @return array
 */
function awv_set_custom_admin_columns( $columns ) {
	unset( $columns['date'] );

	$columns['box_shortcode'] = esc_html__( 'Box shortcode', 'awv-plugin' );

	return $columns;
}
add_filter( 'manage_awv_box_posts_columns', 'awv_set_custom_admin_columns' );


/**
 * View custom admin columns values
 * @param string $column 
 * @param int $post_id 
 * @return string
 */
function awv_custom_admin_column_values( $column, $post_id ) {
	if ( $column == 'box_shortcode' ) {
		echo '<input type="text" value="[awv_box box_id=&quot;' . $post_id . '&quot;]" readonly />';
	}
}
add_action( 'manage_awv_box_posts_custom_column' , 'awv_custom_admin_column_values', 10, 2 );


/**
 * Combine strings for the box output
 * @param string $str1 
 * @param string $position 
 * @param string $str2 
 * @return string
 */
function awv_combine_strings( $str1, $position, $str2 ) {
	if ( $position == 'before' ) {
		return $str1 . $str2;
	}
	if ( $position == 'after' ) {
		return $str2 . $str1;
	}
}


/**
 * Main Box output function
 * @return mixed
 */
function awv_view_content_box() {
	if ( is_singular('product') ) {
		$args = array(
			'post_type' 	 => 'awv_box',
			'posts_per_page' => -1,
			'fields' 		 => 'ids',
			'meta_query' 	 => array(
				array(
					'key' 	=> 'box_post_type',
					'value' => 'product'
				),
				array(
					'key' 	=> 'view_automatically',
					'value' => 'yes'
				),
				'box_weight' => array(
					'key' 	=> 'box_weight',
					'type'  => 'numeric'
				)
			),
			'orderby' => 'box_weight',
			'order'	  => 'DESC'
		);

		$boxes = new WP_Query( $args );
		if ( ! empty( $boxes->posts ) ) {
			foreach ( $boxes->posts as $box_id ) {
				$box_position  = get_post_meta( $box_id, 'box_position', true );
				$box_positions = awv_box_positions( 'product' );
				$position 	   = $box_positions[ $box_position ];
				$output 	   = awv_box_output( $box_id, get_the_ID() );

				if ( $position['type'] == 'filter' ) {
					add_filter( $position['hook'], function( $content ) use ( $output, $position ) {
						return awv_combine_strings( $output, $position['position'], $content );
					});
				}

				if ( $position['type'] == 'action' ) {
					$position_priority = get_post_meta( $box_id, 'position_priority', true );

					add_action( $position['hook'], function( $content ) use ( $output ) {
						echo $output;
					}, $position_priority);
				}
			}
		}
	}
}
add_action( 'wp', 'awv_view_content_box' );


/**
 * Register scripts and styles
 * @return void
 */
function awv_enqueue_scripts() {
	if ( is_singular( 'product' ) ) {
		wp_enqueue_style( 'acfv-styles', AWV_DIR_URL . '/assets/css/public.css', array(), AWV_VERSION );
	}
}
add_action( 'wp_enqueue_scripts', 'awv_enqueue_scripts' );

