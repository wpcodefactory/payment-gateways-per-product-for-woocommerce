<?php
/*
Plugin Name: Payment Methods by Product & Country for WooCommerce
Plugin URI: https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/
Description: Show WooCommerce gateway only if there is selected product, product category or product tag in cart.
Version: 1.8.4
Author: WPFactory
Author URI: https://wpfactory.com
Requires at least: 4.4
Text Domain: payment-gateways-per-product-categories-for-woocommerce
Domain Path: /langs
WC tested up to: 10.4
Requires Plugins: woocommerce
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

defined( 'ABSPATH' ) || exit;

/**
 * Check if WooCommerce is active.
 */
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! (
		is_multisite() &&
		array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) )
	)
) {
	return;
}

/**
 * before_woocommerce_init.
 *
 * @version 1.8.0
 * @since   1.7.8
 */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
			'custom_order_tables',
			dirname(__FILE__),
			true
		);
	}
} );

/**
 * Check if Pro is active.
 */
if ( 'payment-gateways-per-product-for-woocommerce.php' === basename( __FILE__ ) ) {
	// Check if Pro is active, if so then return
	$plugin = 'payment-gateways-per-product-for-woocommerce-pro/payment-gateways-per-product-for-woocommerce-pro.php';
	if (
		in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) ||
		(
			is_multisite() &&
			array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) )
		)
	) {
		return;
	}
}

if ( ! class_exists( 'Alg_WC_PGPP' ) ) :

/**
 * Main Alg_WC_PGPP Class
 *
 * @version 1.8.2
 * @since   1.0.0
 *
 * @class   Alg_WC_PGPP
 */
final class Alg_WC_PGPP {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.0.0
	 */
	public $version = '1.8.4';

	/**
	 * @var   Alg_WC_PGPP The single instance of the class
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * core.
	 *
	 * @version 1.7.13
	 * @since   1.7.13
	 */
	public $core = null;

	/**
	 * settings.
	 *
	 * @version 1.8.0
	 * @since   1.8.0
	 */
	public $settings;

	/**
	 * Main Alg_WC_PGPP Instance.
	 *
	 * Ensures only one instance of Alg_WC_PGPP is loaded or can be loaded.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @static
	 * @return  Alg_WC_PGPP - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Alg_WC_PGPP Constructor.
	 *
	 * @version 1.8.2
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Load libs
		if ( is_admin() ) {
			require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Pro
		if ( 'payment-gateways-per-product-for-woocommerce-pro.php' === basename( __FILE__ ) ) {
			require_once plugin_dir_path( __FILE__ ) . 'includes/pro/class-alg-wc-pgpp-pro.php';
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}

	}

	/**
	 * localize.
	 *
	 * @version 1.8.1
	 * @since   1.8.1
	 */
	function localize() {
		load_plugin_textdomain(
			'payment-gateways-per-product-categories-for-woocommerce',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/langs/'
		);
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.8.2
	 * @since   1.0.0
	 */
	function includes() {
		// Core
		$this->core = require_once plugin_dir_path( __FILE__ ) . 'includes/class-alg-wc-pgpp-core.php';
	}

	/**
	 * admin.
	 *
	 * @version 1.8.2
	 * @since   1.1.0
	 */
	function admin() {

		// Action links
		add_filter(
			'plugin_action_links_' . plugin_basename( __FILE__ ),
			array( $this, 'action_links' )
		);

		// "Recommendations" page
		add_action( 'init', array( $this, 'add_cross_selling_library' ) );

		// WC Settings tab as WPFactory submenu item
		add_action( 'init', array( $this, 'move_wc_settings_tab_to_wpfactory_menu' ) );

		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );

		// Version updated
		if ( get_option( 'alg_wc_pgpp_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}

	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.8.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();

		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_pgpp' ) . '">' .
			__( 'Settings', 'payment-gateways-per-product-categories-for-woocommerce' ) .
		'</a>';

		if ( 'payment-gateways-per-product-for-woocommerce.php' === basename( __FILE__ ) ) {
			$custom_links[] = '<a href="https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/">' .
				__( 'Unlock All', 'payment-gateways-per-product-categories-for-woocommerce' ) .
			'</a>';
		}

		return array_merge( $custom_links, $links );
	}

	/**
	 * add_cross_selling_library.
	 *
	 * @version 1.8.0
	 * @since   1.8.0
	 */
	function add_cross_selling_library() {

		if ( ! class_exists( '\WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling' ) ) {
			return;
		}

		$cross_selling = new \WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling();
		$cross_selling->setup( array( 'plugin_file_path' => __FILE__ ) );
		$cross_selling->init();

	}

	/**
	 * move_wc_settings_tab_to_wpfactory_menu.
	 *
	 * @version 1.8.2
	 * @since   1.8.0
	 */
	function move_wc_settings_tab_to_wpfactory_menu() {

		if ( ! class_exists( '\WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu' ) ) {
			return;
		}

		$wpfactory_admin_menu = \WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();

		if ( ! method_exists( $wpfactory_admin_menu, 'move_wc_settings_tab_to_wpfactory_menu' ) ) {
			return;
		}

		$wpfactory_admin_menu->move_wc_settings_tab_to_wpfactory_menu( array(
			'wc_settings_tab_id' => 'alg_wc_pgpp',
			'menu_title'         => __( 'Payment Gateways per Products', 'payment-gateways-per-product-categories-for-woocommerce' ),
			'page_title'         => __( 'Payment Gateways per Products', 'payment-gateways-per-product-categories-for-woocommerce' ),
			'plugin_icon'        => array(
				'get_url_method'    => 'wporg_plugins_api',
				'wporg_plugin_slug' => 'payment-gateways-per-product-categories-for-woocommerce',
			),
		) );

	}

	/**
	 * Add Payment Gateways per Products settings tab to WooCommerce settings.
	 *
	 * @version 1.8.2
	 * @since   1.0.0
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once plugin_dir_path( __FILE__ ) . 'includes/settings/class-alg-wc-settings-pgpp.php';
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function version_updated() {
		update_option( 'alg_wc_pgpp_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

}

endif;

if ( ! function_exists( 'alg_wc_pgpp' ) ) {
	/**
	 * Returns the main instance of Alg_WC_PGPP to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 *
	 * @return  Alg_WC_PGPP
	 */
	function alg_wc_pgpp() {
		return Alg_WC_PGPP::instance();
	}
}

/**
 * Inits the plugin.
 *
 * @version 1.8.0
 * @since   1.0.0
 */
add_action( 'plugins_loaded', 'alg_wc_pgpp' );
