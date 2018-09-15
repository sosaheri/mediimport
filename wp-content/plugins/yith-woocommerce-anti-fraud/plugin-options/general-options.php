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
    exit;
} // Exit if accessed directly

return array(

    'general' => array(

        'ywaf_main_section_title'               => array(
            'name' => __( 'Anti-Fraud settings', 'yith-woocommerce-anti-fraud' ),
            'type' => 'title',
        ),
        'ywaf_enable_plugin'                    => array(
            'name'    => __( 'Enable YITH WooCommerce Anti-Fraud', 'yith-woocommerce-anti-fraud' ),
            'type'    => 'checkbox',
            'id'      => 'ywaf_enable_plugin',
            'default' => 'yes',
        ),
        'ywaf_main_section_end'                 => array(
            'type' => 'sectionend',
        ),

        'ywaf_rules_title'                      => array(
            'name' => __( 'Rule settings', 'yith-woocommerce-anti-fraud' ),
            'type' => 'title',
        ),
        'ywaf_rules_first_order_enable'         => array(
            'name'    => __( 'Enable first order check', 'yith-woocommerce-anti-fraud' ),
            'type'    => 'checkbox',
            'id'      => 'ywaf_rules_first_order_enable',
            'default' => 'yes',
        ),
        'ywaf_rules_international_order_enable' => array(
            'name'    => __( 'Enable international order check', 'yith-woocommerce-anti-fraud' ),
            'type'    => 'checkbox',
            'id'      => 'ywaf_rules_international_order_enable',
            'default' => 'yes',
        ),
        'ywaf_rules_ip_country_enable'          => array(
            'name'    => __( 'Enable IP geolocation check', 'yith-woocommerce-anti-fraud' ),
            'type'    => 'checkbox',
            'id'      => 'ywaf_rules_ip_country_enable',
            'default' => 'yes',
        ),
        'ywaf_rules_addresses_matching_enable'  => array(
            'name'    => __( 'Enable Billing and Shipping address check', 'yith-woocommerce-anti-fraud' ),
            'type'    => 'checkbox',
            'id'      => 'ywaf_rules_addresses_matching_enable',
            'default' => 'yes',
        ),
        'ywaf_rules_end'                        => array(
            'type' => 'sectionend',
        ),

    )

);