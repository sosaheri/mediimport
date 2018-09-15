<?php
/**
 * YITH WooCommerce Recover Abandoned Cart Content metabox template
 *
 * @package YITH WooCommerce Recover Abandoned Cart
 * @since   1.0.0
 * @author  Yithemes
 */
$seconds_offset = get_option( 'gmt_offset' ) * 3600;
$last_update = date('Y-m-d h:i:sa', strtotime( $last_update ) + $seconds_offset );
?>
<table class="yith-ywrac-info-cart" cellspacing="20">
    <tbody>
        <tr>
            <th><?php _e('Cart Status:','yith-woocommerce-recover-abandoned-cart') ?></th>
            <td><span class="<?php echo $status ?>"><?php echo $status ?></span></td>
        </tr>

        <tr>
            <th><?php _e('Cart Last Update:','yith-woocommerce-recover-abandoned-cart') ?></th>
            <td><?php echo $last_update ?></td>
        </tr>

        <tr>
            <th><?php _e('User:','yith-woocommerce-recover-abandoned-cart') ?></th>
            <td><?php echo $user->display_name ?></td>
        </tr>

        <tr>
            <th><?php _e('User email:','yith-woocommerce-recover-abandoned-cart') ?></th>
            <td><?php echo '<a href="mailto:'.$user->user_email.'">'.$user->user_email.'</a>' ?></td>
        </tr>



    </tbody>
</table>