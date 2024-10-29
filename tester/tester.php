<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly

// Include required files
require_once AWV_DIR_PATH . 'tester/plugins.php';


class AWV_Tester_Init {

	private $option_key = 'awv_tester';

	public function __construct() {
		add_action( 'wp_footer', array($this, 'view_form') );
		add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts') );
		add_action( 'wp', array($this, 'update_options'), 15 );
		add_action( 'wp', array($this, 'view_box_positions') );
		add_action( 'woocommerce_product_tabs', array($this, 'tab_preview') );
	}

	/**
	 * View frond-end form
	 * @return void
	 */
	public function view_form() {
		$awv_options = get_option( 'awv_options' );
		$enable_tester = isset( $awv_options['tester'] ) && $awv_options['tester'] == 1;
		
		if ( is_singular('product') && is_user_logged_in() && $enable_tester ) {
			$post_type  = get_post_type();
			$priorities = get_option( $this->option_key );
			$positions  = $this->box_positions( $post_type );

			wp_enqueue_style( 'awv_tester-style' );
			wp_enqueue_script( 'awv_tester-script' );

			?>
			<div class="awv_tester-form-wrapper">
				<div class="awv_tester-button"></div>
				<div class="awv_tester-form">
					<h3><?php esc_html_e( 'Positions', 'awv-plugin' ); ?></h3>
					<form method="post">
						<div class="awv_tester-fields-wrapper">
							<?php 
								foreach ( $positions as $position_key => $position ) { 
									$priority = isset( $priorities[ $post_type ][ $position_key ] ) ? $priorities[ $post_type ][ $position_key ]  : $position['priority'];
									?>
									<label>
										<?php echo esc_html( $position['title'] ) . ': '; ?>
										<?php if ( $position['priority_ui'] ) { ?>
											<input type="number" name="<?php echo esc_attr( $this->option_key ); ?>[<?php echo esc_attr( $post_type ); ?>][<?php echo esc_attr( $position_key ); ?>]" value="<?php echo esc_attr( $priority ); ?>">
										<?php } ?>
									</label>
								<?php }
							?>
						</div>
						<?php wp_nonce_field('awv_tester', 'awv_tester_nonce_fields' ); ?>
						<input type="submit" value="<?php esc_html_e( 'Update', 'awv-plugin' ); ?>">
					</form>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Enqueue scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_register_style( 'awv_tester-style', 	AWV_DIR_URL . '/tester/assets/css/styles.css', array(), AWV_VERSION );
		wp_register_script( 'awv_tester-script', AWV_DIR_URL . '/tester/assets/js/scripts.js', array( 'jquery' ), AWV_VERSION, true );
	}

	/**
	 * Default box positions
	 *
	 * @param string $post_type 
	 * @return array
	 */
	public function box_positions( $post_type = 'post' ) {

		$positions = apply_filters( 'sacfvt_box_positions', array( 
			'default' => array(
				'before_content' => array(
					'title'       => esc_html__( 'Before post content', 'awv-plugin' ),
					'type'        => 'filter',
					'hook'        => 'the_content',
					'position'    => 'before',
					'priority'    => '10',
					'priority_ui' => false,
				),
				'after_content'  => array(
					'title'       => esc_html__( 'After post content', 'awv-plugin' ),
					'type'        => 'filter',
					'hook'        => 'the_content',
					'position'    => 'after',
					'priority'    => '10',
					'priority_ui' => false,
				),
			)
		) );

		return isset( $positions[ $post_type ] ) ? $positions[ $post_type ] : array();
	}


	/**
	 * Save form options
	 * @return viod
	 */
	public function update_options() {
		if ( isset( $_POST[ $this->option_key ] ) && wp_verify_nonce( $_POST['awv_tester_nonce_fields'], 'awv_tester' ) ) {
			$post_type  = get_post_type();
			$priorities = get_option( $this->option_key, array() );
			$values = array_map( 'sanitize_text_field', $_POST[ $this->option_key ][ $post_type ] );
			$priorities[ $post_type ] = $values;
			update_option( $this->option_key, $priorities, false );
			wp_redirect( get_the_permalink() );
		}
	}


	/**
	 * Show possible box positions
	 * @return viod
	 */
	public function view_box_positions() {
		$awv_options = get_option( 'awv_options' );
		$enable_tester = isset( $awv_options['tester'] ) && $awv_options['tester'] == 1;

		if ( is_singular('product') && is_user_logged_in() && $enable_tester ) {
			$post_type  = get_post_type();
			$positions  = $this->box_positions( $post_type );
			$priorities = get_option( $this->option_key );

			foreach ( $positions as $position_key => $position ) {

				$priority = isset( $priorities[ $post_type ][ $position_key ] ) ? $priorities[ $post_type ][ $position_key ]  : $position['priority'];

				$title = esc_html( $position['title'] );
				$title .= $position['priority_ui'] ? '<small>(priority ' . esc_html( $priority ) . ')</small>' : '';

				$output = '<div class="awv_tester-postition">' . $title . '</div>';

				if ( $position['type'] == 'filter' ) {
					add_filter( $position['hook'], function( $content ) use ( $output, $position ) {
						return $this->combine_strings( $output, $position['position'], $content );
					});
				}

				if ( $position['type'] == 'action' ) {
					add_action( $position['hook'], function( $content ) use ( $output ) {
						echo $output;
					}, $priority );
				}
			}
		}	
	}


	/**
	 * Combine strings
	 * @param string $str1 
	 * @param string $position 
	 * @param string $str2 
	 * @return string
	 */
	public function combine_strings( $str1, $position, $str2 ) {
		if ( $position == 'before' ) {
			return $str1 . $str2;
		}
		if ( $position == 'after' ) {
			return $str2 . $str1;
		}
	}


	public function tab_preview( $tabs ) {
		$awv_options = get_option( 'awv_options' );

		if ( isset( $awv_options['tester'] ) && $awv_options['tester'] == 1 ) {
			$priorities = get_option( $this->option_key );
			$priority 	= isset( $priorities['product']['woocommerce_product_tab'] ) ? $priorities['product']['woocommerce_product_tab'] : '10';
			
			// Adds the new tab
			$tabs['awv_tab'] = array(
				'title'     => esc_html__( 'Custom tab', 'awv-plugin' ),
				'priority'  => $priority,
				'callback'  => array($this, 'tab_preview_callback')
			);
		}
		return $tabs;
	}


	public function tab_preview_callback() {
		esc_html_e( 'Example of the tab content', 'awv-plugin' );
	}
}
new AWV_Tester_Init();

