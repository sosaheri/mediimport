<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'ylc_sanitize_text' ) ) {

	/**
	 * Sanitize strings
	 *
	 * @since   1.0.0
	 *
	 * @param   $string
	 * @param   $html
	 *
	 * @return  string
	 * @author  Alberto ruggiero
	 */
	function ylc_sanitize_text( $string, $html = false ) {
		if ( $html ) {
			return html_entity_decode( $string );
		} else {
			return esc_html( $string );
		}
	}

}

if ( ! function_exists( 'ylc_get_plugin_options' ) ) {

	/**
	 * Get plugin options
	 *
	 * @since   1.0.0
	 * @return  array
	 * @author  Alberto ruggiero
	 */
	function ylc_get_plugin_options() {

		$user_prefix = '';
		$user_type   = 'visitor';

		if ( ( defined( 'YLC_OPERATOR' ) || current_user_can( 'answer_chat' ) ) && ( is_admin() || ylc_frontend_manager() ) ) {
			$user_prefix = 'ylc-op-';
			$user_type   = 'operator';

		} elseif ( is_user_logged_in() ) {

			$user_prefix = 'usr-';

		}

		$options = array(
			'app_id'    => esc_html( YITH_Live_Chat()->options['firebase-appurl'] ),
			'user_info' => array(
				'user_id'      => $user_prefix . YITH_Live_Chat()->user->ID,
				'user_name'    => apply_filters( 'ylc_nickname', YITH_Live_Chat()->user->display_name ),
				'user_email'   => YITH_Live_Chat()->user->user_email,
				'gravatar'     => md5( YITH_Live_Chat()->user->user_email ),
				'user_type'    => $user_type,
				'avatar_type'  => apply_filters( 'ylc_avatar_type', 'default' ),
				'avatar_image' => apply_filters( 'ylc_avatar_image', '' ),
				'current_page' => YITH_Live_Chat()->user->current_page,
				'user_ip'      => YITH_Live_Chat()->user->user_ip
			),
		);

		if ( ! is_admin() && ylc_check_premium() ) {
			$options['styles'] = apply_filters( 'ylc_plugin_opts_premium', array() );
		}

		return $options;

	}

}

if ( ! function_exists( 'ylc_get_current_page' ) ) {

	/**
	 * Get the current page name
	 *
	 * @since   1.0.0
	 * @return  string
	 * @author  Alberto ruggiero
	 */
	function ylc_get_current_page() {

		global $pagenow;

		if ( ! empty( $_GET['page'] ) ) {

			return $_GET['page'];
		} else {
			return $pagenow;
		}

	}

}

if ( ! function_exists( 'ylc_set_firebase_security' ) ) {

	/**
	 * Sets firebase security rules
	 *
	 * @since   1.0.0
	 * @return  void
	 * @author  Alberto ruggiero
	 */
	function ylc_set_firebase_security() {

		$options = YITH_Live_Chat()->options;

		if ( ! empty( $options['firebase-appurl'] ) && ! empty( $options['firebase-appsecret'] ) ) {

			$ylc_security_version = get_option( 'ylc_security_version' );

			if ( empty( $ylc_security_version ) || version_compare( $ylc_security_version, YLC_VERSION, '<' ) ) {

				require_once YLC_DIR . '/includes/firebase/firebaseInterface.php';
				require_once YLC_DIR . '/includes/firebase/firebaseLib.php';

				$rules_json = file_get_contents( YLC_DIR . 'assets/ylc-rules.json' );
				$path       = 'https://' . esc_html( $options['firebase-appurl'] ) . '.firebaseio.com/';
				$firebase   = new FirebaseLib( $path, esc_html( $options['firebase-appsecret'] ) );

				$resp = json_decode( $firebase->set( '/.settings/rules', $rules_json ) );

				if ( ! empty( $resp->status ) ) {

					if ( $resp->status == 'ok' ) {
						update_option( 'ylc_security_version', YLC_VERSION );
					}

				}

			}

		}

	}

	add_action( 'admin_init', 'ylc_set_firebase_security' );

}

if ( ! function_exists( 'ylc_set_defaults' ) ) {

	/**
	 * Sets default options on database
	 *
	 * @since   1.1.0
	 * @return  void
	 * @author  Alberto ruggiero
	 */
	function ylc_set_defaults() {

		$ylc_options_version = get_option( 'ylc_options_version' );

		if ( empty( $ylc_options_version ) || version_compare( $ylc_options_version['number'], YLC_VERSION, '<' ) || ( $ylc_options_version['version'] == 'free' && ylc_check_premium() ) ) {

			$existing_options = get_option( 'yit_' . YITH_Live_Chat()->_options_name . '_options' );
			$default_options  = YITH_Live_Chat()->defaults;

			$options = empty( $existing_options ) ? $default_options : array_merge( $default_options, $existing_options );

			update_option( 'yit_' . YITH_Live_Chat()->_options_name . '_options', $options );

			$ylc_options_version = array(
				'number'  => YLC_VERSION,
				'version' => ylc_check_premium() ? 'premium' : 'free'
			);

			update_option( 'ylc_options_version', $ylc_options_version );

		}

	}

	add_action( 'init', 'ylc_set_defaults' );


}

if ( ! function_exists( 'ylc_check_premium' ) ) {

	/**
	 * Check if premium version
	 *
	 * @since   1.2.1
	 * @return  bool
	 * @author  Alberto ruggiero
	 */
	function ylc_check_premium() {

		return ( defined( 'YLC_PREMIUM' ) && YLC_PREMIUM );

	}

}

if ( ! function_exists( 'ylc_frontend_manager' ) ) {

	/**
	 * Check if is frontend dashboard
	 *
	 * @since   1.2.5
	 * @return  bool
	 * @author  Alberto ruggiero
	 */
	function ylc_frontend_manager() {

		if ( function_exists( 'YITH_Frontend_Manager' ) ) {
			return YITH_Frontend_Manager()->gui->is_main_page();
		}

		return false;

	}

}

if ( ! function_exists( 'ylc_multivendor_check' ) ) {
	/**
	 * Check if YITH Multi Vendor Premium is active and current vendor has Live Chat enabled
	 *
	 * @since   1.1.4
	 * @return  boolean
	 * @author  Alberto Ruggiero
	 */
	function ylc_multivendor_check() {

		if ( defined( 'YITH_WPV_PREMIUM' ) && YITH_WPV_PREMIUM ) {

			$vendor = yith_get_vendor( 'current', 'user' );

			if ( get_option( 'yith_wpv_vendors_option_live_chat_management' ) != 'yes' && $vendor->is_valid() && $vendor->has_limited_access() && ! current_user_can( 'answer_chat' ) ) {
				return false;
			}

		}

		return true;

	}
}

if ( ! function_exists( 'ylc_get_template' ) ) {

	/**
	 * Get template
	 *
	 * @since   1.2.3
	 *
	 * @param   $template_name
	 * @param   $args
	 * @param   $template_path
	 * @param   $default_path
	 *
	 * @return  string
	 * @author  Alberto ruggiero
	 */
	function ylc_get_template( $template_name, $args, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = 'yith-live-chat/';
		}

		if ( ! $default_path ) {
			$default_path = trailingslashit( YLC_TEMPLATE_PATH ) . '/';
		}

		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args );
		}

		include( $template );

	}

}