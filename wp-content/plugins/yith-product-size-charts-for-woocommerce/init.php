<?php
/**
 * Plugin Name: YITH Product Size Charts for WooCommerce
 * Plugin URI: https://yithemes.com/themes/plugins/yith-product-size-charts-for-woocommerce/
 * Description: YITH Product Size Charts for WooCommerce allows you to create custom Size Charts for products in your shop.
 * Version: 1.1.2
 * Author: YITHEMES
 * Author URI: http://yithemes.com/
 * Text Domain: yith-product-size-charts-for-woocommerce
 * Domain Path: /languages/
 * WC requires at least: 3.0.0
 * WC tested up to: 3.3.x
 *
 * @author yithemes
 * @package YITH Product Size Charts for WooCommerce
 * @version 1.1.2
 */
/*  Copyright 2015  Your Inspiration Themes  (email : plugins@yithemes.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* == COMMENT == */

if ( !defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( !function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

function yith_wcpsc_install_woocommerce_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'YITH Product Size Charts for WooCommerce is enabled but not effective. It requires WooCommerce in order to work.', 'yith-product-size-charts-for-woocommerce' ); ?></p>
    </div>
    <?php
}


function yith_wcpsc_install_free_admin_notice() {
    ?>
    <div class="error">
        <p><?php _e( 'You can\'t activate the free version of YITH Product Size Charts for WooCommerce while you are using the premium one.', 'yith-product-size-charts-for-woocommerce' ); ?></p>
    </div>
    <?php
}

if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );


if ( !defined( 'YITH_WCPSC_VERSION' ) ) {
    define( 'YITH_WCPSC_VERSION', '1.1.2' );
}

if ( !defined( 'YITH_WCPSC_FREE_INIT' ) ) {
    define( 'YITH_WCPSC_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( !defined( 'YITH_WCPSC' ) ) {
    define( 'YITH_WCPSC', true );
}

if ( !defined( 'YITH_WCPSC_FILE' ) ) {
    define( 'YITH_WCPSC_FILE', __FILE__ );
}

if ( !defined( 'YITH_WCPSC_URL' ) ) {
    define( 'YITH_WCPSC_URL', plugin_dir_url( __FILE__ ) );
}

if ( !defined( 'YITH_WCPSC_DIR' ) ) {
    define( 'YITH_WCPSC_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'YITH_WCPSC_TEMPLATE_PATH' ) ) {
    define( 'YITH_WCPSC_TEMPLATE_PATH', YITH_WCPSC_DIR . 'templates' );
}

if ( !defined( 'YITH_WCPSC_ASSETS_URL' ) ) {
    define( 'YITH_WCPSC_ASSETS_URL', YITH_WCPSC_URL . 'assets' );
}

if ( !defined( 'YITH_WCPSC_ASSETS_PATH' ) ) {
    define( 'YITH_WCPSC_ASSETS_PATH', YITH_WCPSC_DIR . 'assets' );
}


function yith_wcpsc_init() {

    load_plugin_textdomain( 'yith-product-size-charts-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    // Load required classes and functions
    require_once( 'class.yith-wcpsc-frontend.php' );
    require_once( 'class.yith-wcpsc-admin.php' );
    require_once( 'class.yith-wcpsc.php' );

    // Let's start the game!
    YITH_WCPSC();
}

add_action( 'yith_wcpsc_init', 'yith_wcpsc_init' );


function yith_wcpsc_install() {

    if ( !function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'yith_wcpsc_install_woocommerce_admin_notice' );
    } elseif ( defined( 'YITH_WCPSC_PREMIUM' ) ) {
        add_action( 'admin_notices', 'yith_wcpsc_install_free_admin_notice' );
        deactivate_plugins( plugin_basename( __FILE__ ) );
    } else {
        do_action( 'yith_wcpsc_init' );
    }
}

add_action( 'plugins_loaded', 'yith_wcpsc_install', 11 );

/* Plugin Framework Version Check */
if ( !function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( plugin_dir_path( __FILE__ ) . 'plugin-fw/init.php' ) ) {
    require_once( plugin_dir_path( __FILE__ ) . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( plugin_dir_path( __FILE__ ) );