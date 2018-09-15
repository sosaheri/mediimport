<?php
/**
 * Plugin Name: YITH WooCommerce Anti-Fraud
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-anti-fraud/
 * Description: YITH WooCommerce Anti-Fraud is the best way to understand and recognize all suspicious purchases made in your e-commerce site.
 * Author: YITHEMES
 * Text Domain: yith-woocommerce-anti-fraud
 * Version: 1.1.4
 * Author URI: http://yithemes.com/
 * WC requires at least: 3.0.0
 * WC tested up to: 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function ywaf_install_free_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'You can\'t activate the free version of YITH WooCommerce Anti-Fraud while you are using the premium one.', 'yith-woocommerce-anti-fraud' ); ?></p>
	</div>
	<?php
}

function ywaf_install_woocommerce_admin_notice() {
	?>
	<div class="error">
		<p><?php _e( 'YITH WooCommerce Anti-Fraud is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-anti-fraud' ); ?></p>
	</div>
	<?php
}

if ( ! defined( 'YWAF_VERSION' ) ) {
	define( 'YWAF_VERSION', '1.1.4' );
}

if ( ! defined( 'YWAF_FREE_INIT' ) ) {
	define( 'YWAF_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'YWAF_FILE' ) ) {
	define( 'YWAF_FILE', __FILE__ );
}

if ( ! defined( 'YWAF_DIR' ) ) {
	define( 'YWAF_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YWAF_URL' ) ) {
	define( 'YWAF_URL', plugins_url( '/', __FILE__ ) );
}

if ( ! defined( 'YWAF_ASSETS_URL' ) ) {
	define( 'YWAF_ASSETS_URL', YWAF_URL . 'assets' );
}

if ( ! defined( 'YWAF_TEMPLATE_PATH' ) ) {
	define( 'YWAF_TEMPLATE_PATH', YWAF_DIR . 'templates' );
}

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YWAF_DIR . 'plugin-fw/init.php' ) ) {
	require_once( YWAF_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YWAF_DIR );

function ywaf_free_init() {

	/* Load text domain */
	load_plugin_textdomain( 'yith-woocommerce-anti-fraud', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


	/* === Global YITH WooCommerce Anti-Fraud  === */
	YITH_WAF();

}

add_action( 'ywaf_init', 'ywaf_free_init' );

function ywaf_free_install() {

	if ( ! function_exists( 'WC' ) ) {
		add_action( 'admin_notices', 'ywaf_install_woocommerce_admin_notice' );
	} elseif ( defined( 'YWAF_PREMIUM' ) ) {
		add_action( 'admin_notices', 'ywaf_install_free_admin_notice' );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	} else {
		do_action( 'ywaf_init' );
	}

}

add_action( 'plugins_loaded', 'ywaf_free_install', 11 );

/**
 * Init default plugin settings
 */
if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
	require_once 'plugin-fw/yit-plugin-registration-hook.php';
}

register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

if ( ! function_exists( 'YITH_WAF' ) ) {

	/**
	 * Unique access to instance of YITH_WC_Anti_Fraud
	 *
	 * @since   1.0.0
	 * @return  YITH_WC_Anti_Fraud|YITH_WC_Anti_Fraud_Premium
	 * @author  Alberto Ruggiero
	 */
	function YITH_WAF() {

		// Load required classes and functions
		require_once( YWAF_DIR . 'class.yith-wc-anti-fraud.php' );

		if ( defined( 'YWAF_PREMIUM' ) && file_exists( YWAF_DIR . 'class.yith-wc-anti-fraud-premium.php' ) ) {


			require_once( YWAF_DIR . 'class.yith-wc-anti-fraud-premium.php' );

			return YITH_WC_Anti_Fraud_Premium::get_instance();
		}

		return YITH_WC_Anti_Fraud::get_instance();

	}

}