<?php
/*
 * This file belongs to the YIT Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */


return array(

    'settings' => apply_filters( 'yith_ctpw_settings_options', array(

            //general
            'settings_options_start'    => array(
                'type' => 'sectionstart',
            ),

            'settings_options_title'    => array(
                'title' => _x( 'General settings', 'Panel: page title', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'  => 'title',
                'desc'  => '',
            ),

            'settings_enable_custom_thankyou_page' => array(
                'title'   => _x( 'Enable Custom ThankYou Page', 'Admin option: Enable plugin', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'checkbox',
                'desc'    => _x( 'Check this option to enable plugin features', 'Admin option description: Enable plugin', 'yith-custom-thankyou-page-for-woocommerce' ),
                'id'      => 'yith_ctpw_enable',
                'default' => 'yes'
            ),


            'settings_select_custom_thankyou_page' => array(
                'title'   => _x( 'Select the General Page', 'Admin option: Select General Page', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type'    => 'single_select_page',
                'id'      => 'yith_ctpw_general_page',
                'sort_column' => 'title',
                'class' => 'wc-enhanced-select-nostd',
                'css' => 'min-width:300px;',
                'desc_tip' => __('Select the General Thank You Page for all products'),
            ),


            'setting_custom_thankyou_page_custom_style' => array(
                'title'   => _x( 'Custom CSS', 'Admin option: Custom Style', 'yith-custom-thankyou-page-for-woocommerce' ),
                'type' => 'textarea',
                'id'      => 'yith_ctpw_custom_style',
            ),

            'settings_options_end'      => array(
                'type' => 'sectionend',
            ),


        )
    ) //end settings array
);

