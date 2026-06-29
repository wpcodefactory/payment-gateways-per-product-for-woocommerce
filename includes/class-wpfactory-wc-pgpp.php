<?php
/**
 * Payment Gateways per Products for WooCommerce - Main Class
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  WPFactory
 *
 * @package WPFactory\WC_Payment_Gateways_Per_Product
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFactory_WC_PGPP' ) ) :

	/**
	 * WPFactory_WC_PGPP class.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	final class WPFactory_WC_PGPP {

		/**
		 * Plugin version.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @var     string
		 */
		public $version = WPFACTORY_WC_PGPP_VERSION;

		/**
		 * The single instance of the class.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @var     WPFactory_WC_PGPP The single instance of the class
		 */
		protected static $instance = null;

		/**
		 * Core.
		 *
		 * @version 1.7.13
		 * @since   1.7.13
		 *
		 * @var     WPFactory_WC_PGPP_Core
		 */
		public $core = null;

		/**
		 * Main WPFactory_WC_PGPP Instance.
		 *
		 * Ensures only one instance of WPFactory_WC_PGPP is loaded or can be loaded.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @static
		 *
		 * @return  WPFactory_WC_PGPP - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * WPFactory_WC_PGPP Constructor.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @access  public
		 */
		public function __construct() {

			// Check for active WooCommerce plugin.
			if ( ! function_exists( 'WC' ) ) {
				return;
			}

			// Load libs.
			if ( is_admin() ) {
				require_once plugin_dir_path( WPFACTORY_WC_PGPP_FILE ) . 'vendor/autoload.php';
			}

			// Declare compatibility with custom order tables for WooCommerce.
			add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

			// Pro.
			if ( 'payment-gateways-per-product-for-woocommerce-pro.php' === basename( WPFACTORY_WC_PGPP_FILE ) ) {
				require_once plugin_dir_path( WPFACTORY_WC_PGPP_FILE ) . 'includes/pro/class-wpfactory-wc-pgpp-pro.php';
			}

			// Include required files.
			$this->includes();

			// Admin.
			if ( is_admin() ) {
				$this->admin();
			}
		}

		/**
		 * Declare compatibility with custom order tables for WooCommerce.
		 *
		 * @version 2.0.0
		 * @since   1.7.8
		 *
		 * @see     https://developer.woocommerce.com/docs/features/high-performance-order-storage/recipe-book/
		 */
		public function wc_declare_compatibility() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				$files = (
					defined( 'WPFACTORY_WC_PGPP_FILE_FREE' ) ?
					array( WPFACTORY_WC_PGPP_FILE, WPFACTORY_WC_PGPP_FILE_FREE ) :
					array( WPFACTORY_WC_PGPP_FILE )
				);
				foreach ( $files as $file ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
						'custom_order_tables',
						$file,
						true
					);
				}
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 */
		public function includes() {
			// Core.
			$this->core = require_once plugin_dir_path( WPFACTORY_WC_PGPP_FILE ) . 'includes/class-wpfactory-wc-pgpp-core.php';
		}

		/**
		 * Admin.
		 *
		 * @version 2.0.0
		 * @since   1.1.0
		 */
		public function admin() {

			// Action links.
			add_filter(
				'plugin_action_links_' . plugin_basename( WPFACTORY_WC_PGPP_FILE ),
				array( $this, 'action_links' )
			);

			// "Recommendations" page.
			add_action(
				'init',
				array( $this, 'add_cross_selling_library' )
			);

			// WC Settings tab as WPFactory submenu item.
			add_action(
				'init',
				array( $this, 'move_wc_settings_tab_to_wpfactory_menu' )
			);

			// Settings.
			add_filter(
				'woocommerce_get_settings_pages',
				array( $this, 'add_woocommerce_settings_tab' )
			);

			// Version updated.
			if ( get_option( 'alg_wc_pgpp_version', '' ) !== $this->version ) {
				add_action(
					'admin_init',
					array( $this, 'version_updated' )
				);
			}
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @param   mixed $links Plugin action links.
		 *
		 * @return  array
		 */
		public function action_links( $links ) {
			$custom_links = array();

			$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=wpfactory_wc_pgpp' ) . '">' .
				__( 'Settings', 'payment-gateways-per-product-categories-for-woocommerce' ) .
			'</a>';

			if ( 'payment-gateways-per-product-for-woocommerce.php' === basename( WPFACTORY_WC_PGPP_FILE ) ) {
				$custom_links[] = '<a' .
					' target="_blank"' .
					' style="font-weight: bold; color: green;"' .
					' href="https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/"' .
				'>' .
					__( 'Go Pro', 'payment-gateways-per-product-categories-for-woocommerce' ) .
				'</a>';
			}

			return array_merge( $custom_links, $links );
		}

		/**
		 * Add cross selling library.
		 *
		 * @version 2.0.0
		 * @since   1.8.0
		 */
		public function add_cross_selling_library() {

			if ( ! class_exists( '\WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling' ) ) {
				return;
			}

			$cross_selling = new \WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling();
			$cross_selling->setup( array( 'plugin_file_path' => WPFACTORY_WC_PGPP_FILE ) );
			$cross_selling->init();
		}

		/**
		 * Move WC settings tab to WPFactory menu.
		 *
		 * @version 2.0.0
		 * @since   1.8.0
		 */
		public function move_wc_settings_tab_to_wpfactory_menu() {

			if ( ! class_exists( '\WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu' ) ) {
				return;
			}

			$wpfactory_admin_menu = \WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();

			if ( ! method_exists( $wpfactory_admin_menu, 'move_wc_settings_tab_to_wpfactory_menu' ) ) {
				return;
			}

			$wpfactory_admin_menu->move_wc_settings_tab_to_wpfactory_menu(
				array(
					'wc_settings_tab_id' => 'wpfactory_wc_pgpp',
					'menu_title'         => __( 'Payment Gateways per Products', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'page_title'         => __( 'Payment Methods by Product & Country for WooCommerce', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'plugin_icon'        => array(
						'get_url_method'    => 'wporg_plugins_api',
						'wporg_plugin_slug' => 'payment-gateways-per-product-categories-for-woocommerce',
					),
				)
			);
		}

		/**
		 * Add Payment Gateways per Products settings tab to WooCommerce settings.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @param   array $settings WooCommerce settings tabs.
		 */
		public function add_woocommerce_settings_tab( $settings ) {
			$settings[] = require_once plugin_dir_path( WPFACTORY_WC_PGPP_FILE ) . 'includes/settings/class-wpfactory-wc-pgpp-settings.php';
			return $settings;
		}

		/**
		 * Version updated.
		 *
		 * @version 1.1.0
		 * @since   1.1.0
		 *
		 * @todo    (v2.0.0) Clean up `alg_wc_pgpp_pay_titles` option?
		 */
		public function version_updated() {
			update_option( 'alg_wc_pgpp_version', $this->version );
		}

		/**
		 * Get the plugin url.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @return  string
		 */
		public function plugin_url() {
			return untrailingslashit( plugin_dir_url( WPFACTORY_WC_PGPP_FILE ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @return  string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( WPFACTORY_WC_PGPP_FILE ) );
		}
	}

endif;
