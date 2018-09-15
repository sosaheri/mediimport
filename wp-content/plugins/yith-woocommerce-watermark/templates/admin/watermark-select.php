<?php
if( !defined('ABSPATH' ) )
    exit;

if( !class_exists( 'YITH_Watermark_Select' ) ){

    class YITH_Watermark_Select{


        public static function output( $option ){


            $value = get_option( $option['id'], array() );
            $defaults = array();

            global $YWC_Watermark_Instance;
            if( isset( $option['default'] ) )
                $defaults    =   $option['default'];

            $value = wp_parse_args( $value, $defaults );
            $watermark_id = empty( $value[0]['ywcwat_watermark_id'] ) ? '' : $value[0]['ywcwat_watermark_id'] ;
            $watermark_url = empty( $value[0]['ywcwat_watermark_id'] ) ? '' : wp_get_attachment_image_src( $value[0]['ywcwat_watermark_id'] );
            $watermark_url = is_array( $watermark_url )? $watermark_url[0] : $watermark_url;
            $text_button = __( 'Select Watermark', 'yith-woocommerce-watermark' );
            ?>

            <tr valign="top">
                <th scope="row" class="titledesc"><?php _e( 'Watermark Image', 'yith-woocommerce-watermark' );?>
                    <?php

                    $perc_size = $YWC_Watermark_Instance->get_perc_size();
                    $size = wc_get_image_size('shop_single');
                    $max_w = isset( $size['width'] ) && !empty( $size['width'] )  ? round( ( $size['width'] * $perc_size )/100 ) :125;
                    $max_h = isset( $size['height']) && !empty( $size['height'] ) ? round( ($size['height']* $perc_size)/100 ) : 125;

                    $desc_tip= sprintf('%s <br/> %s %spx <br/> %s %spx', __('Maximum size allowed:','yith-woocommerce-watermark'), __('Width:','yith-woocommerce-watermark'), $max_w, __('Height:','yith-woocommerce-watermark'), $max_h  );
                    ?>
                    <img class="help_tip" data-tip="<?php echo $desc_tip; ?>" src="<?php echo esc_url( WC()->plugin_url() ) . '/assets/images/help.png'?>" height="16" width="16" />

                </th>
                <td class="forminp forminp-button">
                    <input type="text" class="ywcwat_url" value="<?php echo $watermark_url;?>">
                    <input type="button" class="button button-secondary" id="ywcwat_add_watermark" value="<?php echo $text_button;?>" data-choose="<?php echo $text_button;?>">
                    <input type="button" class="button button-secondary" id="ywcwat_remove_watermark" value="<?php _e('Remove Watermark', 'yith-woocommerce-watermark');?>">
                    <input type="hidden" name="<?php echo esc_attr( $option['id'] );?>[0][ywcwat_watermark_id]" id="<?php echo esc_attr( $option['id'] );?>" value="<?php echo $watermark_id;?>">
                    <input type="hidden" name="<?php echo esc_attr( $option['id'] );?>[0][ywcwat_id]" id="<?php echo esc_attr( 'ywcwat_id' ).'-0';?>" value="id-ywcwat_free_wat">
                    <input type="hidden" name="<?php echo esc_attr( $option['id'] );?>[0][ywcwat_watermark_position]" value="bottom_right">
                    <input type="hidden" name="<?php echo esc_attr( $option['id'] );?>[0][ywcwat_watermark_margin_x]" value="20" >
                    <input type="hidden" name="<?php echo esc_attr( $option['id'] );?>[0][ywcwat_watermark_margin_y]" value="20" >
                    <input type="hidden" name="<?php echo esc_attr( $option['id'] );?>[0][ywcwat_watermark_sizes]" value="shop_single" >
                    <input type="hidden" name="<?php echo esc_attr( $option['id'] );?>[0][ywcwat_watermark_category]" value="" >

                </td>
            </tr>
            <tr valign="top">
                <th scope="row" class="titledesc"><?php _e( 'Apply Watermark', 'yith-woocommerce-watermark' );?>
                    <?php $item_row =$YWC_Watermark_Instance->get_max_item_task();
                          $desc_tip = sprintf('%s %s %s', __('This procedure will apply the watermark to','yith-woocommerce-watermark'), $item_row, __('images at a time','yith-woocommerce-watermark' ) );
                            ?>
                   <img class="help_tip" data-tip="<?php echo $desc_tip; ?>" src="<?php echo esc_url( WC()->plugin_url() ) . '/assets/images/help.png'?>" height="16" width="16" />

                </th>
                <td class="forminp forminp-button">

                    <input type="button" class="button button-secondary ywcwat_apply_all_watermark" value="<?php _e( 'Apply Watermark', 'yith-woocommerce-watermark' );?>">
                    <span class="description"> <?php _e( 'All "Shop Single" images of your products will be regenerated. (Save Changes before apply watermark)','yith-woocommerce-watermark');?></span>
                </td>
            </tr>
           <tr valign="top">
                <th scope="row" class="titledesc"><?php _e('Reset','yith-woocommerce-watermark');?></th>
                <td class="forminp forminp-button">
                    <input type="button" class="button button-secondary" id="ywcwat_reset_watermark" value="<?php _e('Reset', 'yith-woocommerce-watermark');?>">
                    <span class="description"> <?php _e( 'Delete all product images with watermark (once completed, you have to deactivate the plugin and regenerate image thumbnails).','yith-woocommerce-watermark');?></span>
                </td>
            </tr>
            <tr valign="top">
                <td colspan="2">
                    <div class="ywcwat_messages">
                        <span class="ywcwat_icon"></span>
                        <span class="ywcwat_text"></span>
                    </div>
                </td>
            </tr>
           <tr valign="top">
              <td class="forminp forminp-progressbar_all" colspan="2">
                   <div class="ywcwat-progressbar" id="ywcwat-progressbar_all">
                       <div class="ywcwat-progressbar-percent" id="ywcwat-progressbar-percent_all"></div>
                   </div>
              </td>
           </tr>
            <tr valign="top" class="ywcwat_log_row">
                <td></td>
                <td colspan="2" class="ywcwat_log_content">
                    <?php
                        $show_log = __( 'Show Log', 'yith-woocommerce-watermark' );
                        $hide_log = __( 'Hide Log', 'yith-woocommerce-watermark' );
                    ?>
                    <input type="button" class="button button-secondary" id="ywcwat_show_log" value="<?php echo $show_log;?>" data-hide_log="<?php echo $hide_log;?>"/>
                    <div id="ywcwat_log_container" style="display: none;"></div>
                </td>
            </tr>
<?php
        }
    }
}