<?php
/*
Plugin Name: ACF viewer for Woocommerce
Description: Easy way to view ACF fields for Woocommerce products. Advanced custom fields addon.
Author: Oleh Odeshchak
Version: 1.0.1
Author URI: http://thewpdev.org/
*/

if ( ! defined( 'ABSPATH' ) ) { return; } // Exit if accessed directly

/**
 * Define common constants
 */
if ( ! defined( 'AWV_DIR_URL' ) )  		 define( 'AWV_DIR_URL',  plugins_url( '', __FILE__ ) );
if ( ! defined( 'AWV_DIR_PATH' ) ) 		 define( 'AWV_DIR_PATH', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'AWV_VERSION' ) )  		 define( 'AWV_VERSION', '1.0.0' );
if ( ! defined( 'AWV_DOCUMENTATION' ) )  define( 'AWV_DOCUMENTATION', 'SITE_URL' );

class AWV_Init {

	public function __construct() {
		
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Check if ACF plugin is active
		if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) || is_plugin_active( 'advanced-custom-fields/acf.php' ) ) {
			require_once AWV_DIR_PATH . '/include/post_type.php';
			require_once AWV_DIR_PATH . '/include/fields.php';
			require_once AWV_DIR_PATH . '/include/helper-functions.php';
			require_once AWV_DIR_PATH . '/include/admin.php';
			require_once AWV_DIR_PATH . '/include/shortcodes.php';
			require_once AWV_DIR_PATH . '/include/widget.php';
			require_once AWV_DIR_PATH . '/include/settings.php';
			require_once AWV_DIR_PATH . '/tester/tester.php';

			// Include Pro version
			if ( file_exists( AWV_DIR_PATH . '/pro/awv-pro.php' ) ) {
				require_once AWV_DIR_PATH . '/pro/awv-pro.php';
			} else {
				add_filter('views_edit-awv_box', array($this, 'free_version_notice'));
			}
		} else {
			// Admin notice
			add_action( 'admin_notices', array($this, 'admin_notices') );
		}
	}

	public function admin_notices() {
		?>
		<div class="error">
			<p>
			<?php 
				echo wp_kses_post( sprintf( 
					__( 'ACF Woocommerce Viewer require <a href="%s">Advanced Custom Fields</a> or <a href="%s">Advanced Custom Fields</a> plugin. It is impossible to use ACF Woocommerce Viewer without one of these plugins. <a href="%s">Learn more here</a>.', 'awv-plugin' ),
					'https://wordpress.org/plugins/advanced-custom-fields/',
					'https://www.advancedcustomfields.com/',
					AWV_DOCUMENTATION
				) ); 
			?>
			</p>
		</div>
		<?php
	}

	public function free_version_notice() {
		wp_enqueue_style( 'awv-admin-style' );
		?>
		<div class="awv-free-notice">
			<?php esc_html_e( 'You are using the free version with one AWV Box limitation', 'awv-plugin' ); ?>
		</div>
		<?php
	}
}
new AWV_Init();

