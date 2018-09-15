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
	exit;
} // Exit if accessed directly

/**
 * Main class
 *
 * @class   YITH_WC_Anti_Fraud
 * @package Yithemes
 * @since   1.0.0
 * @author  Your Inspiration Themes
 */

if ( ! class_exists( 'YITH_WC_Anti_Fraud' ) ) {

	class YITH_WC_Anti_Fraud {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WC_Anti_Fraud
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Panel object
		 *
		 * @var     /Yit_Plugin_Panel object
		 * @since   1.0.0
		 * @see     plugin-fw/lib/yit-plugin-panel.php
		 */
		protected $_panel = null;

		/**
		 * @var $_premium string Premium tab template file name
		 */
		protected $_premium = 'premium.php';

		/**
		 * @var string Premium version landing link
		 */
		protected $_premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-anti-fraud/';

		/**
		 * @var string Plugin official documentation
		 */
		protected $_official_documentation = 'http://yithemes.com/docs-plugins/yith-woocommerce-anti-fraud/';

		/**
		 * @var string YITH WooCommerce Anti-Fraud panel page
		 */
		protected $_panel_page = 'yith-wc-anti-fraud';

		/**
		 * @var array Active Anti-Fraud Rules
		 */
		protected $rules = array();

		/**
		 * @var array Active risk thresholds
		 */
		protected $risk_thresholds = array();

		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WC_Anti_Fraud
		 * @since 1.0.0
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {

				self::$instance = new self;

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

			if ( ! function_exists( 'WC' ) ) {
				return;
			}

			//Load plugin framework
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 12 );
			add_filter( 'plugin_action_links_' . plugin_basename( YWAF_DIR . '/' . basename( YWAF_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 4 );
			add_action( 'admin_menu', array( $this, 'add_menu_page' ), 5 );
			add_action( 'yith_anti_fraud_premium', array( $this, 'premium_tab' ) );

			if ( get_option( 'ywaf_enable_plugin' ) == 'yes' ) {

				$this->includes();

				$this->rules           = $this->get_ywaf_rules();
				$this->risk_thresholds = $this->get_ywaf_thresholds();

				add_action( 'woocommerce_order_status_changed', array( $this, 'ywaf_order_status' ), 99, 3 );

				if ( is_admin() ) {

					add_action( 'admin_notices', array( $this, 'ywaf_admin_notices' ) );
					add_filter( 'manage_edit-shop_order_columns', array( $this, 'add_ywaf_column' ), 11 );
					add_action( 'manage_shop_order_posts_custom_column', array( $this, 'render_ywaf_column' ), 3 );
					add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_admin' ) );

				}

			}

		}

		/**
		 * Files inclusion
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		private function includes() {

			if ( is_admin() ) {

				include_once( 'includes/class-ywaf-metabox.php' );
				include_once( 'includes/class-ywaf-ajax.php' );

			}

			include_once( 'includes/class-ywaf-rules.php' );
			include_once( 'includes/rules/class-ywaf-first-order.php' );
			include_once( 'includes/rules/class-ywaf-international-order.php' );
			include_once( 'includes/rules/class-ywaf-ip-country.php' );
			include_once( 'includes/rules/class-ywaf-addresses-matching.php' );

		}

		/**
		 * Get Anti-Fraud rules
		 *
		 * @since   1.0.0
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function get_ywaf_rules() {

			$rules = array(
				'YWAF_First_Order'         => array(
					'rule'   => new YWAF_First_Order(),
					'active' => get_option( 'ywaf_rules_first_order_enable' ),
				),
				'YWAF_International_Order' => array(
					'rule'   => new YWAF_International_Order(),
					'active' => get_option( 'ywaf_rules_international_order_enable' ),
				),
				'YWAF_IP_Country'          => array(
					'rule'   => new YWAF_IP_Country(),
					'active' => get_option( 'ywaf_rules_ip_country_enable' ),
				),
				'YWAF_Addresses_Matching'  => array(
					'rule'   => new YWAF_Addresses_Matching(),
					'active' => get_option( 'ywaf_rules_addresses_matching_enable' ),
				),
			);

			return $rules;

		}

		/**
		 * Get Anti-Fraud thresholds
		 *
		 * @since   1.0.0
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function get_ywaf_thresholds() {

			$thresholds = array(
				'medium' => get_option( 'ywaf_medium_risk_threshold', 25 ),
				'high'   => get_option( 'ywaf_high_risk_threshold', 75 ),
			);

			return $thresholds;

		}

		/**
		 * On change order status perform a fraud check
		 *
		 * @since   1.0.0
		 *
		 * @param   $id
		 * @param   $old_status
		 * @param   $new_status
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function ywaf_order_status( $id, $old_status, $new_status ) {

			if ( 'completed' == $new_status || 'processing' == $new_status || 'on-hold' == $new_status ) {

				$this->set_fraud_check( $id );

			}

		}

		/**
		 * Set fraud check
		 *
		 * @since   1.0.0
		 *
		 * @param   $order_id
		 * @param   $repeat
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function set_fraud_check( $order_id, $repeat = false ) {

			$order      = wc_get_order( $order_id );
			$is_deposit = ( yit_get_prop( $order, '_created_via', true ) == 'yith_wcdp_balance_order' );

			if ( $is_deposit ) {
				return;
			}

			if ( $repeat ) {
				yit_delete_prop( $order, 'ywaf_risk_factor' );

			}

			//$in_progress = yit_get_prop( $order, 'ywaf_check_progress' );
			$can_check = apply_filters( 'ywaf_paypal_check', true, $order );

			if ( $can_check ) {

				$risk_factor = yit_get_prop( $order, 'ywaf_risk_factor' );

				if ( $risk_factor && ! $repeat ) {
					return;
				}

				$order->add_order_note( __( 'Fraud risk check in progress.', 'yith-woocommerce-anti-fraud' ) );

				yit_save_prop( $order, 'ywaf_check_status', 'process' );

				$this->process_fraud_check( $order );

			}

		}

		/**
		 * Process fraud check
		 *
		 * @since   1.0.0
		 *
		 * @param   $order
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function process_fraud_check( WC_Order $order = null ) {

			if ( $order == null ) {
				return;
			}

			$blacklisted  = apply_filters( 'ywaf_check_blacklist', false, $order );
			$risk         = $this->calculate_risk_score( $order );
			$risk_score   = $risk['risk_score'];
			$failed_rules = $risk['failed_rules'];

			yit_save_prop( $order, 'ywaf_risk_factor', array(
				'score'        => ( $risk_score > 100 ) ? 100 : $risk_score,
				'failed_rules' => $failed_rules
			) );

			$order_status = apply_filters( 'ywaf_after_check_status', array(
				'status' => yit_get_prop( $order, 'post_status' ),
				'note'   => __( 'Fraud risk check completed.', 'yith-woocommerce-anti-fraud' ),
			), $risk_score, $order );

			$order->add_order_note( $order_status['note'] );
			$order->set_status( $order_status['status'] );

			$risk_level = $this->get_risk_level( ( $risk_score > 100 ) ? 100 : $risk_score );

			$check_status = ( $risk_level['class'] == 'high' || $risk_level['class'] == 'medium' ) ? $risk_level['class'] . '_risk' : 'success';

			yit_save_prop( $order, 'ywaf_check_status', $check_status, true );

			do_action( 'ywaf_after_fraud_check', $order, $blacklisted, $risk_score );

		}

		/**
		 * Calculate risk score for the order
		 *
		 * @return array Map with risk_score and failed_rules params
		 * @since 1.1.2
		 */
		public function calculate_risk_score( $order ) {
			$total_risk_points = 0;
			$max_risk_points   = 0;
			$failed_rules      = array();
			$blacklisted       = apply_filters( 'ywaf_check_blacklist', false, $order );

			if ( $order == null ) {
				return array( 'risk_score' => 0, 'failed_rules' => $failed_rules );
			}

			if ( $blacklisted ) {

				$failed_rules[] = 'YWAF_Blacklist';
				$risk_score     = 100;

			} else {

				foreach ( $this->rules as $key => $rule ) {

					if ( $rule['active'] == 'yes' ) {

						if ( $rule['rule']->get_fraud_risk( $order ) === true ) {
							$total_risk_points += $rule['rule']->get_points();
							$failed_rules[] = $key;
						}
						$max_risk_points += 10;

					}

				}

				$risk_score = round( ( $total_risk_points / $max_risk_points ) * 100, 1 );

			}

			return array( 'risk_score' => $risk_score, 'failed_rules' => $failed_rules );
		}

		/**
		 * ADMIN FUNCTIONS
		 */

		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 * @use     /Yit_Plugin_Panel class
		 * @see     plugin-fw/lib/yit-plugin-panel.php
		 */
		public function add_menu_page() {

			if ( ! empty( $this->_panel ) ) {
				return;
			}

			$admin_tabs = array();

			if ( defined( 'YWAF_PREMIUM' ) ) {
				$admin_tabs['premium-general'] = __( 'General Settings', 'yith-woocommerce-anti-fraud' );
				$admin_tabs['blacklist']       = __( 'Blacklist Settings', 'yith-woocommerce-anti-fraud' );
				$admin_tabs['paypal']          = __( 'PayPal Settings', 'yith-woocommerce-anti-fraud' );

			} else {
				$admin_tabs['general']         = __( 'General Settings', 'yith-woocommerce-anti-fraud' );
				$admin_tabs['premium-landing'] = __( 'Premium Version', 'yith-woocommerce-anti-fraud' );
			}

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'page_title'       => _x( 'Anti-Fraud', 'plugin name in admin page title', 'yith-woocommerce-anti-fraud' ),
				'menu_title'       => _x( 'Anti-Fraud', 'plugin name in admin WP menu', 'yith-woocommerce-anti-fraud' ),
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->_panel_page,
				'admin-tabs'       => $admin_tabs,
				'options-path'     => YWAF_DIR . 'plugin-options'
			);

			$this->_panel = new YIT_Plugin_Panel_WooCommerce( $args );

		}

		/**
		 * Enqueue script file
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function enqueue_scripts_admin() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'ywaf-admin', YWAF_ASSETS_URL . '/css/ywaf-admin' . $suffix . '.css' );
			wp_enqueue_style( 'ywaf-google-fonts', '//fonts.googleapis.com/css?family=Lato:400,700,900', array(), null );

			wp_enqueue_script( 'ywaf-admin', YWAF_ASSETS_URL . '/js/ywaf-admin' . $suffix . '.js', array( 'jquery' ) );

			if ( isset( $_GET['post'] ) ) {

				$query_args = array(
					'action'   => 'ywaf_fraud_risk_check',
					'order_id' => $_GET['post'],
					'single'   => '1',
					'_wpnonce' => wp_create_nonce( 'ywaf-check-fraud-risk' )
				);

				$ajax_url = add_query_arg( $query_args, str_replace( array( 'https:', 'http:' ), '', admin_url( 'admin-ajax.php' ) ) );

				wp_localize_script( 'ywaf-admin', 'ywaf_ajax_url', $ajax_url );

			}

		}

		/**
		 * Advise if no rule is active
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function ywaf_admin_notices() {

			$active_rules = 0;

			foreach ( $this->rules as $rule ) {

				if ( $rule['active'] == 'yes' ) {

					$active_rules ++;

				}

			}

			if ( $active_rules === 0 ): ?>
				<div class="error">
					<p>
						<?php _e( 'You must activate at least one rule to monitor your orders against fraud.', 'yith-woocommerce-anti-fraud' ); ?>
					</p>
				</div>
			<?php endif;

		}

		/**
		 * Add the order fraud risk column
		 *
		 * @since   1.0.0
		 *
		 * @param   $columns
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function add_ywaf_column( $columns ) {

			$label = __( 'Fraud Risk Level', 'yith-woocommerce-anti-fraud' );

			$columns = array_merge( array_slice( $columns, 0, 1 ), array( 'ywaf_status' => '<span class="ywaf_status tips" data-tip="' . $label . '">' . $label . '</span>' ), array_slice( $columns, 1 ) );

			return $columns;

		}

		/**
		 * Render the order fraud risk column
		 *
		 * @since   1.0.0
		 *
		 * @param   $column
		 *
		 * @return  void
		 * @author  Alberto Ruggiero
		 */
		public function render_ywaf_column( $column ) {

			if ( 'ywaf_status' == $column ) {

				global $post;

				$order        = wc_get_order( $post->ID );
				$risk_factor  = yit_get_prop( $order, 'ywaf_risk_factor' );
				$risk_data    = $this->get_risk_level( isset ( $risk_factor['score'] ) ? $risk_factor['score'] : '' );
				$failed_rules = '<br />';

				if ( isset( $risk_factor['failed_rules'] ) ) {

					foreach ( $risk_factor['failed_rules'] as $failed_rule ) {

						if ( class_exists( $failed_rule ) ) {

							$rule = new $failed_rule;

							$failed_rules .= esc_html( $rule->get_message() ) . '<br />';

						}

					}
				}

				echo apply_filters( 'ywaf_paypal_status', sprintf( '<mark class="%s tips" data-tip="%s">%s</mark>', $risk_data['class'], $risk_data['tip'] . $failed_rules, $risk_data['tip'] ) );

			}

		}

		/**
		 * Set risk level
		 *
		 * @since   1.0.0
		 *
		 * @param   $risk_points
		 *
		 * @return  array
		 * @author  Alberto Ruggiero
		 */
		public function get_risk_level( $risk_points ) {

			$data = array(
				'class' => '',
				'tip'   => ''
			);

			if ( $risk_points === '' ) {

				$data['class'] = '';
				$data['color'] = '#cacaca';
				$data['tip']   = __( 'No check performed', 'yith-woocommerce-anti-fraud' );

			} else {

				switch ( true ) {

					case $risk_points >= $this->risk_thresholds['high']:

						$data['class'] = 'high';
						$data['color'] = '#c83c3d';
						$data['tip']   = __( 'High Risk', 'yith-woocommerce-anti-fraud' );

						break;

					case $risk_points >= $this->risk_thresholds['medium'] && $risk_points < $this->risk_thresholds['high']:

						$data['class'] = 'medium';
						$data['color'] = '#ffa200';
						$data['tip']   = __( 'Medium Risk', 'yith-woocommerce-anti-fraud' );

						break;

					default:

						$data['class'] = 'low';
						$data['color'] = '#00a208';
						$data['tip']   = __( 'Low Risk', 'yith-woocommerce-anti-fraud' );

				}

			}

			return $data;

		}

		/**
		 * YITH FRAMEWORK
		 */

		/**
		 * Load plugin framework
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Andrea Grillo
		 * <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once( $plugin_fw_file );
				}
			}
		}

		/**
		 * Premium Tab Template
		 *
		 * Load the premium tab template on admin page
		 *
		 * @since   1.0.0
		 * @return  void
		 * @author  Andrea Grillo
		 * <andrea.grillo@yithemes.com>
		 */
		public function premium_tab() {
			$premium_tab_template = YWAF_TEMPLATE_PATH . '/admin/' . $this->_premium;
			if ( file_exists( $premium_tab_template ) ) {
				include_once( $premium_tab_template );
			}
		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @return  string The premium landing link
		 * @author  Andrea Grillo
		 * <andrea.grillo@yithemes.com>
		 */
		public function get_premium_landing_uri() {
			return defined( 'YITH_REFER_ID' ) ? $this->_premium_landing . '?refer_id=' . YITH_REFER_ID : $this->_premium_landing;
		}

		/**
		 * Action Links
		 *
		 * add the action links to plugin admin page
		 * @since   1.0.0
		 *
		 * @param   $links | links plugin array
		 *
		 * @return  mixed
		 * @author  Andrea Grillo
		 * <andrea.grillo@yithemes.com>
		 * @use     plugin_action_links_{$plugin_file_name}
		 */
		public function action_links( $links ) {

			$links[] = '<a href="' . admin_url( "admin.php?page={$this->_panel_page}" ) . '">' . __( 'Settings', 'yith-woocommerce-anti-fraud' ) . '</a>';

			if ( defined( 'YWAF_FREE_INIT' ) ) {
				$links[] = '<a href="' . $this->get_premium_landing_uri() . '" target="_blank">' . __( 'Premium Version', 'yith-woocommerce-anti-fraud' ) . '</a>';
			}

			return $links;
		}

		/**
		 * Plugin row meta
		 *
		 * add the action links to plugin admin page
		 *
		 * @since   1.0.0
		 *
		 * @param   $plugin_meta
		 * @param   $plugin_file
		 * @param   $plugin_data
		 * @param   $status
		 *
		 * @return  array
		 * @author  Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use     plugin_row_meta
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file, $plugin_data, $status ) {
			if ( ( defined( 'YWAF_INIT' ) && ( YWAF_INIT == $plugin_file ) ) ||
			     ( defined( 'YWAF_FREE_INIT' ) && ( YWAF_FREE_INIT == $plugin_file ) )
			) {

				$plugin_meta[] = '<a href="' . $this->_official_documentation . '" target="_blank">' . __( 'Plugin Documentation', 'yith-woocommerce-anti-fraud' ) . '</a>';
			}

			return $plugin_meta;
		}

	}

}