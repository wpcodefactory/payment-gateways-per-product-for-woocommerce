<?php
/**
 * Payment Gateways per Products for WooCommerce - Core Class
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  WPFactory
 *
 * @package WPFactory\WC_Payment_Gateways_Per_Product
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFactory_WC_PGPP_Core' ) ) :

	/**
	 * WPFactory_WC_PGPP_Core class.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	class WPFactory_WC_PGPP_Core {

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 */
		public function __construct() {
			$hook = get_option( 'alg_wc_pgpp_advanced_add_hook', 'init' );
			switch ( $hook ) {

				case 'constructor':
					$this->add_hook();
					break;

				case 'wp_loaded':
				case 'init':
					add_action( $hook, array( $this, 'add_hook' ) );
					break;

			}
		}

		/**
		 * Add hook.
		 *
		 * @version 2.0.0
		 * @since   1.1.0
		 */
		public function add_hook() {
			add_filter(
				'woocommerce_available_payment_gateways',
				array( $this, 'filter_available_payment_gateways' ),
				PHP_INT_MAX
			);
		}

		/**
		 * Get all gateways.
		 *
		 * @version 2.0.0
		 */
		public function get_all_gateways() {
			$gateways = WC()->payment_gateways->payment_gateways();
			$result   = array();
			foreach ( $gateways as $gateway_id => $gateway ) {
				$result[ $gateway_id ] = (
					! empty( $gateway->method_title ) ?
					$gateway->method_title . ' - ' . $gateway->title :
					$gateway->title
				);
			}
			return $result;
		}

		/**
		 * Do disable gateway by terms.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @param   array  $terms      Terms.
		 * @param   string $taxonomy   Taxonomy.
		 * @param   bool   $is_include Is include.
		 *
		 * @return  bool
		 */
		public function do_disable_gateway_by_terms( $terms, $taxonomy, $is_include ) {
			global $wp;
			if (
				empty( $terms ) ||
				'no' === get_option( 'alg_wc_pgpp_' . $taxonomy . '_section_enabled', 'yes' )
			) {
				return false;
			}
			$terms = array_map( 'intval', $terms );
			if ( is_wc_endpoint_url( 'order-pay' ) ) {
				if (
					isset( $wp->query_vars['order-pay'] ) &&
					absint( $wp->query_vars['order-pay'] ) > 0
				) {
					$order_id = absint( $wp->query_vars['order-pay'] );
					$order    = wc_get_order( $order_id );
					foreach ( $order->get_items() as  $item_key => $item_values ) {
						$item_data     = $item_values->get_data();
						$product_id    = $item_data['product_id'];
						$product_terms = get_the_terms( $product_id, $taxonomy );
						if ( $product_terms && ! is_wp_error( $product_terms ) ) {
							foreach ( $product_terms as $product_term ) {
								if ( in_array( $product_term->term_id, $terms, true ) ) {
									return ( ! $is_include );
								}
							}
						}
					}
				}
			} else {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item_values ) {
					$product_terms = get_the_terms( $cart_item_values['product_id'], $taxonomy );
					if ( $product_terms && ! is_wp_error( $product_terms ) ) {
						foreach ( $product_terms as $product_term ) {
							if ( in_array( $product_term->term_id, $terms, true ) ) {
								return ( ! $is_include );
							}
						}
					}
				}
			}
			return $is_include;
		}

		/**
		 * Filter available payment gateways.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @param   array $available_gateways Available gateways.
		 */
		public function filter_available_payment_gateways( $available_gateways ) {

			$pre_available_gateways = apply_filters( 'wpfactory_wc_pgpp_pre_filter_gateways', false, $available_gateways );
			if ( false !== $pre_available_gateways ) {
				return $pre_available_gateways;
			}

			if ( is_wc_endpoint_url( 'add-payment-method' ) ) {
				return $available_gateways;
			}

			if ( ! isset( WC()->cart ) || WC()->cart->is_empty() ) {
				return $available_gateways;
			}

			if ( empty( $available_gateways ) ) {
				return $available_gateways;
			}

			$gateways = $available_gateways;
			foreach ( $available_gateways as $gateway_id => $gateway ) {
				if (
					$this->do_disable_gateway_by_terms(
						get_option( 'alg_wc_pgpp_categories_include_' . $gateway_id, '' ),
						'product_cat',
						true
					) ||
					$this->do_disable_gateway_by_terms(
						get_option( 'alg_wc_pgpp_categories_exclude_' . $gateway_id, '' ),
						'product_cat',
						false
					) ||
					$this->do_disable_gateway_by_terms(
						get_option( 'alg_wc_pgpp_tags_include_' . $gateway_id, '' ),
						'product_tag',
						true
					) ||
					$this->do_disable_gateway_by_terms(
						get_option( 'alg_wc_pgpp_tags_exclude_' . $gateway_id, '' ),
						'product_tag',
						false
					) ||
					apply_filters( 'wpfactory_wc_pgpp_disable_gateway', false, $gateway_id )
				) {
					if ( apply_filters( 'wpfactory_wc_pgpp_skip_disable_gateway', false, $gateway_id ) ) {
						continue;
					}
					unset( $available_gateways[ $gateway_id ] );
				}
			}

			$available_gateways = apply_filters(
				'wpfactory_wc_pgpp_after_filter_gateways',
				$available_gateways,
				$gateways
			);

			return $available_gateways;
		}
	}

endif;

return new WPFactory_WC_PGPP_Core();
