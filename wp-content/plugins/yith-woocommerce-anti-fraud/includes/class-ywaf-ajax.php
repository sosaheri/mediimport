<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( !class_exists( 'YWAF_Ajax' ) ) {

    /**
     * Implements AJAX for YWAF plugin
     *
     * @class   YWAF_Ajax
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     *
     */
    class YWAF_Ajax {

        /**
         * Single instance of the class
         *
         * @var \YWAF_Ajax
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         *
         * @return \YWAF_Ajax
         * @since 1.0.0
         */
        public static function get_instance() {

            if ( is_null( self::$instance ) ) {

                self::$instance = new self( $_REQUEST );

            }

            return self::$instance;
        }

        /**
         * Constructor
         *
         * @since   1.0.0
         * @return  mixed
         * @author  Alberto Ruggiero
         */
        public function __construct() {

            add_action( 'wp_ajax_ywaf_fraud_risk_check', array( $this, 'ywaf_fraud_risk_check' ) );

        }

        /**
         * Start Fraud risk check from admin pages
         *
         * @since   1.0.0
         * @return  void
         * @author  Alberto Ruggiero
         */
        public function ywaf_fraud_risk_check() {

            if ( check_admin_referer( 'ywaf-check-fraud-risk' ) ) {

                try {

                    $repeat   = false;
                    $response = array();

                    if ( ( isset( $_POST['repeat'] ) && $_POST['repeat'] == 'true' ) || ( isset( $_GET['repeat'] ) && $_GET['repeat'] == 'true' ) ) {
                        $repeat = true;
                    }

                    $order_id = absint( $_GET['order_id'] );
                    YITH_WAF()->set_fraud_check( $order_id, $repeat );

                    $redirect = wp_get_referer() ? wp_get_referer() : admin_url( 'edit.php?post_type=shop_order' );

                    if ( isset( $_GET['single'] ) ) {

                        $response['status']   = 'success';
                        $response['redirect'] = $redirect;

                    }
                    else {
                        wp_safe_redirect( $redirect );
                    }


                } catch ( Exception $e ) {

                    $response['status'] = 'fail';
                    $response['error']  = $e->getMessage();

                }

                wp_send_json( $response );

            }

            exit;

        }

    }

    /**
     * Unique access to instance of YWAF_Ajax class
     *
     * @return \YWAF_Ajax
     */
    function YWAF_Ajax() {

        return YWAF_Ajax::get_instance();

    }

    new YWAF_Ajax();

}