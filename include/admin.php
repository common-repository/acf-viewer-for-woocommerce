<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly

class AWV_Admin {

	public function __construct() {
		add_action( 'add_meta_boxes_awv_box', array($this, 'awv_add_meta_boxes') );
		add_action( 'save_post_awv_box', array($this, 'awv_save_meta_box_data') );
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_scripts') );
	}

	// AWV box metabox
	public function awv_add_meta_boxes( $post ) {
		add_meta_box( 'awv_meta_box',  esc_html__( 'Box options', 'awv-plugin' ), array($this, 'awv_build_main_meta_box'), 'awv_box', 'normal', 'default' );
		add_meta_box( 'awv_fields', esc_html__( 'Available fields', 'awv-plugin' ), array($this, 'awv_build_meta_fields'), 'awv_box', 'normal', 'default' );
		add_meta_box( 'awv_shortcode', esc_html__( 'Box shortcode', 'awv-plugin' ), array($this, 'awv_build_meta_box_shortcode'), 'awv_box', 'side', 'default' );
		add_meta_box( 'awv_meta_box_additional',  esc_html__( 'Additional options', 'awv-plugin' ), array($this, 'awv_build_additional_meta_box'), 'awv_box', 'side', 'default' );
	}

	// View fields int the backend
	public function awv_build_main_meta_box( $post ) {

		$box_title  		= get_post_meta( $post->ID, 'box_title', true );
		$box_style  		= get_post_meta( $post->ID, 'box_style', true );
		$view_automatically = get_post_meta( $post->ID, 'view_automatically', true );
		$box_position 		= get_post_meta( $post->ID, 'box_position', true );
		$position_priority 	= get_post_meta( $post->ID, 'position_priority', true );
		$box_post_type 		= 'product';
		$html_data 			= get_post_meta( $post->ID, 'html_data', true );
		$box_editor 		= get_post_meta( $post->ID, 'box_editor', true );
		
		wp_enqueue_code_editor( array( 'type' => 'text/html' ) );

		wp_enqueue_style( 'awv-admin-style' );
		wp_enqueue_script( 'awv-admin-script' );

		wp_localize_script( 'awv-admin-script', 'awv_data', array( 
			'ajax_url' 	    => admin_url( 'admin-ajax.php' ),
			'ajax_nonce'    => wp_create_nonce( 'awv_nonce' ),
			'loading_text'  => esc_html__( 'Loading...', 'awv-plugin' ),
			'box_post_type' => $box_post_type
		) );


		$box_styles  = awv_box_styles();
		$pro_version = file_exists( AWV_DIR_PATH . '/pro/awv-pro.php' );


		$line_elmns = $box_editor == 'html' ? 'hidden-row' : '';
		$html_elmns = $box_editor != 'html' ? 'hidden-row' : '';

		// make sure the form request comes from WordPress
		wp_nonce_field( basename( __FILE__ ), 'awv_box_metabox' );

		?>

		<!-- Main box fields -->
		<div class="acfv-fields-wrapper">
			<table class="form-table js-awv-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="box_editor"><?php esc_html_e( 'Box editor', 'awv-plugin' ); ?></label>
						</th>
						<td>
							<select name="box_editor" id="box_editor">
								<option value="line" <?php selected( $box_editor, 'line' ); ?>><?php esc_html_e( 'Line items', 'awv-plugin' ); ?></option>
								<?php if ( $pro_version ): ?>
									<option value="html" <?php selected( $box_editor, 'html' ); ?>><?php esc_html_e( 'HTML editor', 'awv-plugin' ); ?></option>
								<?php endif ?>
							</select>
						</td>
					</tr>
					<tr class="<?php echo esc_attr( $line_elmns ); ?> line-elmns" id="box_title_row">
						<th scope="row">
							<label for="box_title"><?php esc_html_e( 'Box title', 'awv-plugin' ); ?></label>
						</th>
						<td>
							<input name="box_title" type="text" id="box_title" value="<?php echo esc_html( $box_title ); ?>">
						</td>
					</tr>
					<tr class="<?php echo esc_attr( $line_elmns ); ?> line-elmns" id="box_style_row">
						<th scope="row">
							<label for="box_style"><?php esc_html_e( 'Box style', 'awv-plugin' ); ?></label>
						</th>
						<td>
							<select name="box_style" id="box_style">
								<?php foreach ( $box_styles as $box_style_key => $box_item_style ) {
									echo '<option value="' . esc_attr( $box_style_key ) . '" ' . selected( $box_style_key, $box_style, false ) . '>' . esc_html( $box_item_style['title'] ) . '</option>';
								} ?>
							</select>
						</td>
					</tr>
					<tr id="view_automatically_row">
						<th scope="row">
							<label for="view_automatically"><?php esc_html_e( 'View Automatically', 'awv-plugin' ); ?></label>
						</th>
						<td>
							<select name="view_automatically" id="view_automatically">
								<option value="no" <?php selected( $view_automatically, 'no' ); ?>><?php esc_html_e( 'No', 'awv-plugin' ); ?></option>
								<option value="yes" <?php selected( $view_automatically, 'yes' ); ?>><?php esc_html_e( 'Yes', 'awv-plugin' ); ?></option>
							</select>
						</td>
					</tr>
					<tr <?php if ( $view_automatically == 'no' ): ?>class="hidden-row"<?php endif ?> id="box_position_row">
						<th scope="row">
							<label for="box_position"><?php esc_html_e( 'Choose position', 'awv-plugin' ); ?></label>
						</th>
						<td>
							<select name="box_position" id="box_position" data-selected="<?php echo esc_attr( $box_position ); ?>">
								<?php awv_get_box_positions( $box_position ); ?>
							</select>
						</td>
					</tr>
					<tr <?php if ( $view_automatically == 'no' ): ?>class="hidden-row"<?php endif ?> id="box_position_priority_row">
						<th scope="row">
							<label for="position_priority"><?php esc_html_e( 'Priority', 'awv-plugin' ); ?></label>
						</th>
						<td>
							<input name="position_priority" min="0" type="number" id="position_priority" value="<?php echo esc_attr( $position_priority ); ?>">
						</td>
					</tr>
				</tbody>
			</table>
			
			<div class="awv-field-buttons">
				<?php echo awv_post_type_field_buttons( $box_post_type ); ?>
			</div>

			<div class="awv-html-editor <?php echo esc_attr( $html_elmns ); ?>">
				<textarea name="html_data" id="html_data" placeholder="<?php esc_html_e( 'Box code...', 'awv-plugin' ); ?>"><?php echo $html_data; ?></textarea>
			</div>

			<div class="awv-fields awv-line-editor line-elmns <?php echo esc_attr( $line_elmns ); ?>">
				<div id="awv-fields-container"><?php awv_view_field_rows( $post->ID ); ?></div>
				<div class="awv-column-footer">
					<div class="awv-order-message"><?php esc_html_e( 'Drag and drop to reorder', 'awv-plugin' ); ?></div>
				</div>
			</div>

			<div class="awv-popup-overlay"></div>
			<div class="awv-popup">
				<div class="awv-popup-heading">
					<div class="awv-popup-title"></div>
					<div class="awv-popup-close"></div>
				</div>
				<div class="awv-popup-content"></div>
				<div class="awv-popup-buttons">
					<div class="button awv-button-cancel"><?php esc_html_e( 'Cancel', 'awv-plugin' ); ?></div>
					<div class="button button-primary awv-button-insert"><?php esc_html_e( 'Insert', 'awv-plugin' ); ?></div>
				</div>
			</div>
		</div>
		<?php
	}


	public function awv_build_additional_meta_box( $post ) {
		$box_visibility = get_post_meta( $post->ID, 'box_visibility', true );
		$box_weight 	= get_post_meta( $post->ID, 'box_weight', true );
		$css_class 		= get_post_meta( $post->ID, 'css_class', true );
		$css_id 		= get_post_meta( $post->ID, 'css_id', true );
		$padding_top 	= get_post_meta( $post->ID, 'padding_top', true );
		$padding_bottom = get_post_meta( $post->ID, 'padding_bottom', true );

		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="box_visibility"><?php esc_html_e( 'Visibility', 'awv-plugin' ); ?></label>
					</th>
					<td>
						<select name="box_visibility" id="box_visibility">
							<option value="public" <?php selected( $box_visibility, 'public' ); ?>><?php esc_html_e( 'public', 'awv-plugin' ); ?></option>
							<option value="private" <?php selected( $box_visibility, 'private' ); ?>><?php esc_html_e( 'logged in users only', 'awv-plugin' ); ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="box_weight"><?php esc_html_e( 'Weight', 'awv-plugin' ); ?></label>
					</th>
					<td>
						<input name="box_weight" type="number" id="box_weight" min="0" value="<?php echo esc_attr( $box_weight ); ?>" />
						<p class="awv-tip"><?php esc_html_e( 'Zero has the lowest priority.', 'awv-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="css_class"><?php esc_html_e( 'CSS class', 'awv-plugin' ); ?></label>
					</th>
					<td>
						<input name="css_class" type="text" id="css_class" value="<?php echo esc_attr( $css_class ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="css_id"><?php esc_html_e( 'CSS id', 'awv-plugin' ); ?></label>
					</th>
					<td>
						<input name="css_id" type="text" id="css_id" value="<?php echo esc_attr( $css_id ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="padding_top"><?php esc_html_e( 'Padding top(px)', 'awv-plugin' ); ?></label>
					</th>
					<td>
						<input name="padding_top" type="number" min="0" id="padding_top" value="<?php echo esc_attr( $padding_top ); ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="padding_bottom"><?php esc_html_e( 'Padding bottom(px)', 'awv-plugin' ); ?></label>
					</th>
					<td>
						<input name="padding_bottom" type="number" min="0" id="padding_bottom" value="<?php echo esc_attr( $padding_bottom ); ?>" />
					</td>
				</tr>
			</tbody>
		</table>

		<?php
	}


	public function awv_build_meta_box_shortcode( $post ) {
		echo '<p><input style="width:100%;" type="text" name="awv_address" value="[awv_box box_id=&quot;' . $post->ID . '&quot;]" readonly /></p>';
	}


	public function awv_build_meta_fields( $post ) {
		awv_get_fields_table();
	}


	public function awv_save_meta_box_data( $post_id ) {
		// verify meta box nonce
		if ( ! isset( $_POST['awv_box_metabox'] ) || ! wp_verify_nonce( $_POST['awv_box_metabox'], basename( __FILE__ ) ) ){
			return;
		}

		// return if autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Check if feilds exist and save
		if ( isset( $_POST['awv_feild_item'] ) ) {
			$feild_item = $this->sanitize_array( $_POST['awv_feild_item'] );
			update_post_meta( $post_id, 'awv_feild_item', $feild_item );
		}

		if ( isset( $_POST['box_title'] ) ) {
			update_post_meta( $post_id, 'box_title', sanitize_text_field( $_POST['box_title'] ) );
		}

		update_post_meta( $post_id, 'box_style', sanitize_text_field( $_POST['box_style'] ) );
		update_post_meta( $post_id, 'box_editor', sanitize_text_field( $_POST['box_editor'] ) );
		update_post_meta( $post_id, 'view_automatically', sanitize_text_field( $_POST['view_automatically'] ) );
		update_post_meta( $post_id, 'box_post_type', 'product' );
		update_post_meta( $post_id, 'position_priority', sanitize_text_field( $_POST['position_priority'] ) );
		update_post_meta( $post_id, 'html_data', sanitize_text_field( $_POST['html_data'] ) );

		if ( isset( $_POST['box_position'] ) ) {
			update_post_meta( $post_id, 'box_position', sanitize_text_field( $_POST['box_position'] ) );
		}

		update_post_meta( $post_id, 'box_visibility', sanitize_text_field( $_POST['box_visibility'] ) );
		update_post_meta( $post_id, 'box_weight', sanitize_text_field( $_POST['box_weight'] ) );
		update_post_meta( $post_id, 'css_class', sanitize_text_field( $_POST['css_class'] ) );
		update_post_meta( $post_id, 'css_id', sanitize_text_field( $_POST['css_id'] ) );
		update_post_meta( $post_id, 'padding_top', sanitize_text_field( $_POST['padding_top'] ) );
		update_post_meta( $post_id, 'padding_bottom', sanitize_text_field( $_POST['padding_bottom'] ) );
	}


	public function enqueue_admin_scripts() {
		wp_register_style( 'awv-admin-style',  AWV_DIR_URL . '/assets/css/admin.css', AWV_VERSION );
		wp_register_script( 'awv-admin-script', AWV_DIR_URL . '/assets/js/admin.js', array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), AWV_VERSION, true );
	}


	public function sanitize_array( $data = array() ) {
		if (!is_array( $data ) || ! count( $data ) ) {
			return array();
		}

		foreach ($data as $k => $v) {
			if (!is_array($v) && !is_object($v)) {
				$data[$k] = sanitize_text_field($v);
			}
			if (is_array($v)) {
				$data[$k] = $this->sanitize_array($v);
			}
		}
		return $data;
	}

}
new AWV_Admin();
