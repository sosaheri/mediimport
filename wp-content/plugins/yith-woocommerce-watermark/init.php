<?php
/**
 * Plugin Name: YITH WooCommerce Watermark
 * Plugin URI: http://yithemes.com/themes/plugins/yith-woocommerce-watermark/
 * Description: YITH WooCommerce Watermark allows you to set a watermark in your product image.
 * Version: 1.1.1
 * Author: YITHEMES
 * Author URI: http://yithemes.com/
 * Text Domain: yith-woocommerce-watermark
 * Domain Path: /languages/
 * WC requires at least: 2.6.0
 * WC tested up to: 3.2.0-RC1
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Watermark
 * @version 1.1.1
 *
 */

/*  Copyright 2013  Your Inspiration Themes  (email : plugins@yithemes.com)

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

if ( !defined( 'ABSPATH' ) ){
    exit;
}

if( !function_exists( 'is_plugin_active' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}
    function yith_ywcwat_install_woocommerce_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'YITH WooCommerce Watermark is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-watermark' ); ?></p>
        </div>
    <?php

}

    function yith_ywcwat_install_free_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'You can\'t activate the free version of YITH WooCommerce Watermark while you are using the premium one.', 'yith-woocommerce-watermark' ); ?></p>
        </div>
    <?php
}


if ( !defined( 'YWCWAT_VERSION' ) ) {
    define( 'YWCWAT_VERSION', '1.1.1' );
}

if ( !defined( 'YWCWAT_FREE_INIT' ) ) {
    define( 'YWCWAT_FREE_INIT', plugin_basename( __FILE__ ) );
}

if ( !defined( 'YWCWAT_FILE' ) ) {
    define( 'YWCWAT_FILE', __FILE__ );
}

if ( !defined( 'YWCWAT_DIR' ) ) {
    define( 'YWCWAT_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'YWCWAT_URL' ) ) {
    define( 'YWCWAT_URL', plugins_url( '/', __FILE__ ) );
}

if ( !defined( 'YWCWAT_ASSETS_URL' ) ) {
    define( 'YWCWAT_ASSETS_URL', YWCWAT_URL . 'assets/' );
}

if ( !defined( 'YWCWAT_TEMPLATE_PATH' ) ) {
    define( 'YWCWAT_TEMPLATE_PATH', YWCWAT_DIR . 'templates/' );
}

if ( !defined( 'YWCWAT_INC' ) ) {
    define( 'YWCWAT_INC', YWCWAT_DIR . 'includes/' );
}

if( !defined('YWCWAT_SLUG' ) ){
    define( 'YWCWAT_SLUG', 'yith-woocommerce-watermark' );
}

if( !defined( 'YWCWAT_BACKUP_FILE'))
    define( 'YWCWAT_BACKUP_FILE', '_ywcwat_original_');

if( !defined('YWCWAT_PRIVATE_DIR'))
    define('YWCWAT_PRIVATE_DIR','yith_watermark_backup');

if ( !function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );





/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YWCWAT_DIR . 'plugin-fw/init.php' ) ) {
    require_once( YWCWAT_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader(YWCWAT_DIR);

if( !function_exists( 'YITH_Watermark_Init' ) ) {

    function YITH_Watermark_Init()
    {
        load_plugin_textdomain( 'yith-woocommerce-watermark', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
        require_once( YWCWAT_INC . 'functions.yith-wc-watermark.php' );
        require_once( YWCWAT_INC .'classes/class.yith-woocommerce-watermark.php' );

        global $YWC_Watermark_Instance;
        $YWC_Watermark_Instance = YITH_WC_Watermark::get_instance();
    }
}

add_action( 'ywcwat_init', 'YITH_Watermark_Init' );

if( !function_exists( 'yith_watermark_install' ) ){

    function yith_watermark_install(){

        if( !function_exists( 'WC' ) ){
            add_action( 'admin_notices', 'yith_ywcwat_install_woocommerce_admin_notice' );
        }elseif( defined( 'YWCWAT_PREMIUM' ) ){
            add_action( 'admin_notices', 'yith_ywcwat_install_free_admin_notice' );
            deactivate_plugins( plugin_basename( __FILE__ ) );
        }else
            do_action( 'ywcwat_init' );
    }
}

add_action( 'plugins_loaded', 'yith_watermark_install' ,11 );