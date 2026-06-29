<?php
/**
 * Plugin Name: Payment Methods by Product & Country for WooCommerce
 * Plugin URI: https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/
 * Description: Show WooCommerce gateway only if there is selected product, product category or product tag in cart.
 * Version: 2.0.0
 * Author: WPFactory
 * Author URI: https://wpfactory.com
 * Requires at least: 4.4
 * Text Domain: payment-gateways-per-product-categories-for-woocommerce
 * Domain Path: /langs
 * WC tested up to: 10.9
 * Requires Plugins: woocommerce
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WPFactory\WC_Payment_Gateways_Per_Product
 */

defined( 'ABSPATH' ) || exit;

if ( 'payment-gateways-per-product-for-woocommerce.php' === basename( __FILE__ ) ) {
	if ( ! function_exists( 'wpfactory_wc_pgpp_is_pro_activated' ) ) {
		/**
		 * Check if Pro plugin version is activated.
		 *
		 * @version 2.0.0
		 */
		function wpfactory_wc_pgpp_is_pro_activated() {
			$plugin = 'payment-gateways-per-product-for-woocommerce-pro/payment-gateways-per-product-for-woocommerce-pro.php';
			return (
				in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
				(
					is_multisite() &&
					array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) )
				)
			);
		}
	}
	if ( wpfactory_wc_pgpp_is_pro_activated() ) {
		defined( 'WPFACTORY_WC_PGPP_FILE_FREE' ) || define( 'WPFACTORY_WC_PGPP_FILE_FREE', __FILE__ );
		return;
	}
}

/**
 * WPFACTORY_WC_PGPP_VERSION.
 *
 * @version 2.0.0
 * @since   2.0.0
 */
defined( 'WPFACTORY_WC_PGPP_VERSION' ) || define( 'WPFACTORY_WC_PGPP_VERSION', '2.0.0' );

/**
 * WPFACTORY_WC_PGPP_FILE.
 *
 * @version 2.0.0
 * @since   2.0.0
 */
defined( 'WPFACTORY_WC_PGPP_FILE' ) || define( 'WPFACTORY_WC_PGPP_FILE', __FILE__ );

/**
 * Main class.
 *
 * @version 2.0.0
 * @since   2.0.0
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpfactory-wc-pgpp.php';

if ( ! function_exists( 'wpfactory_wc_pgpp' ) ) {
	/**
	 * Returns the main instance of WPFactory_WC_PGPP to prevent the need to use globals.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @return  WPFactory_WC_PGPP
	 */
	function wpfactory_wc_pgpp() {
		return WPFactory_WC_PGPP::instance();
	}
}

/**
 * Inits the plugin.
 *
 * @version 2.0.0
 * @since   1.0.0
 */
add_action( 'plugins_loaded', 'wpfactory_wc_pgpp' );
