<?php
if( !defined( 'ABSPATH' ) )
    exit;


$settings = array(

    'general-settings'  =>  array(

        'watermark_section_start'   =>  array(
            'name'  => __('General Settings', 'yith-woocommerce-watermark'),
            'type' =>   'title',
            'id' => 'ywcwat_sectionstart'
        ),
        'watermark_gen_backup' => array(
            'name'	=> '',
            'type'	=> 'custom-button',
            'id'	=> 'ywcwat_gen_backup'
        ),
        'watermark_custom_field'    =>  array(
            'name' => '',
            'type'  =>  'watermark-select',
            'id'    =>  'ywcwat_watermark_select',
            'default'=> array( ),
        ),

          'watermark_section_end' =>  array(
            'type'  =>  'sectionend',
            'id'    =>  'ywcwat_sectionend'
        )

    )
);

return apply_filters( 'ywcwat_free_options', $settings );