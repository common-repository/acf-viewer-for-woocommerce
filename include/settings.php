<?php

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly

class AWV_Settings {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}


	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=awv_box', esc_html__( 'Settings', 'awv-plugin' ), esc_html__( 'Settings', 'awv-plugin' ), 'manage_options', 'awv-settings', array( $this, 'plguin_admin_page' ) ); 
		add_action( 'admin_init', array( $this, 'plugin_settings' ) );
	}


	public function plugin_settings() {
		register_setting( 'awv_options', 'awv_options' );
	}


	public function plguin_admin_page(){
		$awv_options = get_option( 'awv_options' );
		$tester = isset( $awv_options['tester'] ) && $awv_options['tester'] == 1 ? 'checked' : '';
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Settings', 'awv-plugin' ); ?></h2>
		</div>
		<form method="post" action="options.php" novalidate="novalidate">
			<?php settings_fields( 'awv_options' ); ?>
			<?php do_settings_sections( 'awv_options' ); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label for="tester"><?php esc_html_e( 'Enable tester', 'awv-plugin' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="tester" name="awv_options[tester]" value="1" <?php echo esc_attr( $tester ); ?>>
							<p class="description"><?php esc_html_e( 'Allows to test plugin compability and preview possible box positions.', 'awv-plugin' ); ?></p>
						</td>
					</tr>

					<?php do_action( 'awv_settings' ); ?>

				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
		<?php
	}
}
new AWV_Settings();

