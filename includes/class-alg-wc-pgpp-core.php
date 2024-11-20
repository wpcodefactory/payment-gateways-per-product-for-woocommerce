<?php
/**
 * Payment Gateways per Products for WooCommerce - Core Class
 *
 * @version 1.8.0
 * @since   1.0.0
 *
 * @author  WPFactory
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_PGPP_Core' ) ) :

class Alg_WC_PGPP_Core {

	/**
	 * Constructor.
	 *
	 * @version 1.8.0
	 * @since   1.0.0
	 */
	function __construct() {

		if ( 'yes' === get_option( 'alg_wc_pgpp_enabled', 'yes' ) ) {
			$hook = get_option( 'alg_wc_pgpp_advanced_add_hook', 'init' );
			if ( 'constructor' === $hook ) {
				$this->add_hook();
			} elseif ( 'wp_loaded' === $hook ) {
				add_action( 'wp_loaded', array( $this, 'add_hook' ) );
			} else { // 'init'
				add_action( 'init', array( $this, 'add_hook' ) );
			}
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'custom_plugin_scripts' ), PHP_INT_MAX );

		add_action( 'admin_footer', array( $this, 'custom_admin_js_add_order' ) );

		add_action( 'wp_ajax_noprev_alg_wc_pgpp_get_products', array( $this, 'get_products_ajax_callback' ) );
		add_action( 'wp_ajax_alg_wc_pgpp_get_products',        array( $this, 'get_products_ajax_callback' ) );

	}

	/**
	 * custom_admin_js_add_order.
	 *
	 * @version 1.8.0
	 */
	function custom_admin_js_add_order() {
		?>
		<script>
		jQuery(document).ready(function(){
			is_checkedalg_wc_pqpp();
			if (jQuery.isFunction(jQuery('.products_select_pgpp').select2)){
				jQuery('.products_select_pgpp').select2({
					ajax: {
							url: ajaxurl,
							dataType: 'json',
							delay: 250,
							data: function (params) {
								return {
									q: params.term,
									action: 'alg_wc_pgpp_get_products'
								};
							},
							processResults: function( data ) {
							var options = [];
							if ( data ) {
								jQuery.each( data, function( index, text ) {
									options.push( { id: text[0], text: text[1]  } );
								});
							}
							return {
								results: options
							};
						},
						cache: true
					},
					minimumInputLength: 3
				});
			}
		});
		jQuery("#alg_wc_pgpp_advanced_fallback_gateway_enabled").on("click", function(){
			is_checkedalg_wc_pqpp();
		});
		function is_checkedalg_wc_pqpp(){
			if(jQuery("#alg_wc_pgpp_advanced_fallback_gateway_enabled").length > 0){
				var check = jQuery("#alg_wc_pgpp_advanced_fallback_gateway_enabled").prop("checked");
				if(check) {
					 jQuery('#alg_wc_pgpp_advanced_fallback_gateway').removeAttr('disabled');
				} else {
					 jQuery('#alg_wc_pgpp_advanced_fallback_gateway').attr('disabled','disabled');
					  if (jQuery.isFunction(jQuery('#alg_wc_pgpp_advanced_fallback_gateway').select2)){
						jQuery( '#alg_wc_pgpp_advanced_fallback_gateway' ).select2();
					  }
				}
			}
		}
		</script>
		<?php
	}

	/**
	 * get_products_ajax_callback.
	 *
	 * @version 1.8.0
	 */
	function get_products_ajax_callback(){
		// We will pass post IDs and titles to this array
		$return = array();
		$add_variations = false;
		if('yes' === get_option( 'alg_wc_pgpp_products_add_variations', 'no' )){
			$add_variations = true;
		}
		// You can use WP_Query, query_posts() or get_posts() here - it doesn't matter
		$loop = new WP_Query( array(
			's'=> $_GET['q'], // the search query
			'post_status' => 'publish',
			'posts_per_page' => 50,
			'post_type' => array('product'),
			'orderby'        => 'title',
			'order'          => 'ASC',
			'fields'         => 'ids',
		) );
		foreach ( $loop->posts as $post_id ) {
			$maintitle = get_the_title( $post_id ) . ' (#' . $post_id . ')';
			$title = ( mb_strlen( $maintitle ) > 100 ) ? mb_substr( $maintitle, 0, 99 ) . '...' : $maintitle;
			$return[ $post_id ] = array( $post_id, $title );
			if ( $add_variations ) {
				$_product = wc_get_product( $post_id );
				if ( $_product->is_type( 'variable' ) ) {
					unset( $return[ $post_id ] );
					foreach ( $_product->get_children() as $child_id ) {
						$childmaintitle = get_the_title( $child_id ) . ' (#' . $child_id . ')';
						$chtitle = ( mb_strlen( $childmaintitle ) > 100 ) ? mb_substr( $childmaintitle, 0, 99 ) . '...' : $childmaintitle;
						$return[ $child_id ] = array( $child_id, $chtitle );
					}
				}
			}
		}
		echo json_encode( $return );
		die;
	}

	/**
	 * custom_plugin_scripts.
	 *
	 * @version 1.8.0
	 */
	function custom_plugin_scripts( $hook ) {
		if ( 'woocommerce_page_wc-settings' != $hook ) {
			return;
		}
		wp_enqueue_script( 'select2' );
	}

	/**
	 * add_hook.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function add_hook() {
		add_filter( 'woocommerce_available_payment_gateways', array( $this, 'filter_available_payment_gateways_per_category' ), PHP_INT_MAX );
	}

	/**
	 * do_disable_gateway_by_terms.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function do_disable_gateway_by_terms( $terms, $taxonomy, $is_include ) {
		global $wp;
		if ( empty( $terms ) || 'no' === get_option( 'alg_wc_pgpp_' . $taxonomy . '_section_enabled', 'yes' ) ) {
			return false;
		}
		if(is_wc_endpoint_url('order-pay')){
			if ( isset($wp->query_vars['order-pay']) && absint($wp->query_vars['order-pay']) > 0 ) {
				$order_id = absint($wp->query_vars['order-pay']);
				$order    = wc_get_order( $order_id );
				foreach ( $order->get_items() as  $item_key => $item_values ) {
					$item_data = $item_values->get_data();
					$product_id = $item_data['product_id'];

					$product_terms = get_the_terms( $product_id, $taxonomy );
					if ( $product_terms && ! is_wp_error( $product_terms ) ) {
						foreach( $product_terms as $product_term ) {
							if ( in_array( $product_term->term_id, $terms ) ) {
								return ( ! $is_include );
							}
						}
					}
				}
			}
		}else{
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item_values ) {
				$product_terms = get_the_terms( $cart_item_values['product_id'], $taxonomy );
				if ( $product_terms && ! is_wp_error( $product_terms ) ) {
					foreach( $product_terms as $product_term ) {
						if ( in_array( $product_term->term_id, $terms ) ) {
							return ( ! $is_include );
						}
					}
				}
			}
		}
		return $is_include;
	}

	/**
	 * get_cart_product_id.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function get_cart_product_id( $cart_item_values ) {
		return ( 'yes' === get_option( 'alg_wc_pgpp_products_add_variations', 'no' ) ?
			( ! empty( $cart_item_values['variation_id'] ) ? $cart_item_values['variation_id'] : $cart_item_values['product_id'] ) :
			$cart_item_values['product_id']
		);
	}

	/**
	 * do_disable_gateway_by_products.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function do_disable_gateway_by_products( $products, $is_include ) {
		global $wp;
		if ( empty( $products ) || 'no' === apply_filters( 'alg_wc_pgpp', 'no', 'products_section' ) ) {
			return false;
		}
		if(is_wc_endpoint_url('order-pay')){
			if ( isset($wp->query_vars['order-pay']) && absint($wp->query_vars['order-pay']) > 0 ) {
				$order_id = absint($wp->query_vars['order-pay']);
				$order    = wc_get_order( $order_id );
				foreach ( $order->get_items() as  $item_key => $item_values ) {
					$item_data = $item_values->get_data();
					$product_id = $item_data['product_id'];

					if(isset($item_data['variation_id'])){
						$variation_id = $item_data['variation_id'];
					}else{
						$variation_id = 0;
					}

					if($variation_id > 0){
						if ( in_array( $variation_id, $products ) ) {
							return ( ! $is_include );
						}
					}else{
						if ( in_array( $product_id, $products ) ) {
							return ( ! $is_include );
						}
					}
				}
			}
		}else{
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item_values ) {
				if ( in_array( $this->get_cart_product_id( $cart_item_values ), $products ) ) {
					return ( ! $is_include );
				}
			}
		}
		return $is_include;
	}

	/**
	 * get_occupied_payment_gateway.
	 *
	 * @version 1.7.9
	 * @since   1.7.9
	 */
	function get_occupied_payment_gateway() {

		$occupied_gateway = array();
		$restriction_number = (int) get_option( 'alg_wc_pgpp_countries_restriction_number', 1 );

		if ( $restriction_number > 0 ) {

			for ( $i = 1; $i <= $restriction_number; $i ++ ) {

				if ( $i == 1 ) {
					$country_id = 'alg_wc_pgpp_countries_remove_countries';
					$gateway_id = 'alg_wc_pgpp_countries_remove_include_gateway';
				} else {
					$country_id = 'alg_wc_pgpp_countries_remove_countries_' . $i;
					$gateway_id = 'alg_wc_pgpp_countries_remove_include_gateway_' . $i;
				}

				$alg_wc_pgpp_countries_remove_countries = get_option( $country_id, array() );
				$alg_wc_pgpp_countries_remove_include_gateway = get_option( $gateway_id, array() );

				if ( !empty( $alg_wc_pgpp_countries_remove_countries ) && !empty( $alg_wc_pgpp_countries_remove_include_gateway ) ) {
					foreach ( $alg_wc_pgpp_countries_remove_include_gateway as $gateway ) {

						if ( isset( $occupied_gateway[$gateway] ) ) {
							$occupied_gateway[$gateway] = array_unique( array_merge($alg_wc_pgpp_countries_remove_countries,  $occupied_gateway[$gateway] ) );
						} else {
							$occupied_gateway[$gateway] = array_unique( $alg_wc_pgpp_countries_remove_countries );
						}
					}
				}

			}
		}
		return $occupied_gateway;
	}

	/**
	 * filter_available_payment_gateways_per_category.
	 *
	 * @version 1.7.17
	 * @since   1.0.0
	 * @todo    [dev] (maybe) `if ( ! isset( WC()->cart ) || '' === WC()->cart ) { WC()->cart = new WC_Cart(); }`
	 */
	function filter_available_payment_gateways_per_category( $available_gateways ) {

		$occupied_gateways = array_keys( $this->get_occupied_payment_gateway() ) ;
		if(function_exists( 'WC' )){
			if(!is_null(WC()->checkout()) && isset($_REQUEST['country']) && !empty($_REQUEST['country'])){

				$alg_wc_pgpp_countries_remove_enabled = get_option( 'alg_wc_pgpp_countries_remove_enabled', 'no' );
				$alg_wc_pgpp_countries_combine_condition = get_option( 'alg_wc_pgpp_countries_combine_condition', 'no' );

				if ( $alg_wc_pgpp_countries_remove_enabled == 'yes' ) {
					$restriction_number = (int) get_option( 'alg_wc_pgpp_countries_restriction_number', 1 );

					/*$selected_country = WC()->checkout->get_value( 'billing_country' );*/
					$selected_country = $_REQUEST['country'];

					if ( $restriction_number > 0 ) {
						for( $i = 1; $i <= $restriction_number; $i ++ ) {

							if ($i == 1) {
								$country_ids = 'alg_wc_pgpp_countries_remove_countries';
								$gateway_ids = 'alg_wc_pgpp_countries_remove_include_gateway';
							} else {
								$country_ids = 'alg_wc_pgpp_countries_remove_countries_' . $i;
								$gateway_ids = 'alg_wc_pgpp_countries_remove_include_gateway_' . $i;
							}

							$alg_wc_pgpp_countries_remove_countries = get_option( $country_ids, array() );
							$alg_wc_pgpp_countries_remove_include_gateway = get_option( $gateway_ids, array() );


							if ( $alg_wc_pgpp_countries_remove_enabled == 'yes' && in_array( $selected_country, $alg_wc_pgpp_countries_remove_countries ) ) {
								if ( !empty($alg_wc_pgpp_countries_remove_include_gateway) && !empty($available_gateways) ) {
									$gateways = $available_gateways;
									foreach ( $gateways as $gateway_id => $gateway ) {
										if ( !in_array( $gateway_id, $alg_wc_pgpp_countries_remove_include_gateway ) ) {
											unset( $gateways[ $gateway_id ] );
										}
									}
									$available_gateways = $gateways;
									if( $alg_wc_pgpp_countries_combine_condition == 'no' ) {
										return $available_gateways;
									}
								}
							}
						}
					}

					// remove occupied gateways by other countries
					if ( !empty( $occupied_gateways ) && $alg_wc_pgpp_countries_combine_condition == 'no' ) {
						$gateways = $available_gateways;
						foreach ( $gateways as $gateway_id => $gateway ) {
							if ( in_array( $gateway_id, $occupied_gateways ) ) {
								unset( $gateways[ $gateway_id ] );
							}
						}
						$available_gateways = $gateways;
						return $available_gateways;
					}

				}

			}
		}

		if(function_exists( 'WC' ) && is_wc_endpoint_url('order-pay')){

		}
		else if(is_wc_endpoint_url('add-payment-method')){
			return $available_gateways;
		}
		else if ( ! function_exists( 'WC' ) || ! isset( WC()->cart ) || WC()->cart->is_empty() || empty( $available_gateways ) ) {
			return $available_gateways;
		}
		$gateways = $available_gateways;
		foreach ( $available_gateways as $gateway_id => $gateway ) {
			if (
				$this->do_disable_gateway_by_terms( get_option( 'alg_wc_pgpp_categories_include_' . $gateway_id, '' ), 'product_cat', true ) ||
				$this->do_disable_gateway_by_terms( get_option( 'alg_wc_pgpp_categories_exclude_' . $gateway_id, '' ), 'product_cat', false ) ||
				$this->do_disable_gateway_by_terms( get_option( 'alg_wc_pgpp_tags_include_'       . $gateway_id, '' ), 'product_tag', true ) ||
				$this->do_disable_gateway_by_terms( get_option( 'alg_wc_pgpp_tags_exclude_'       . $gateway_id, '' ), 'product_tag', false ) ||
				$this->do_disable_gateway_by_products( apply_filters( 'alg_wc_pgpp', '', 'products_include', array( 'gateway_id' => $gateway_id ) ), true ) ||
				$this->do_disable_gateway_by_products( apply_filters( 'alg_wc_pgpp', '', 'products_exclude', array( 'gateway_id' => $gateway_id ) ), false )
			) {
				if(!$this->do_disable_gateway_by_products( apply_filters( 'alg_wc_pgpp', '', 'products_include', array( 'gateway_id' => $gateway_id ) ), false )){
					unset( $available_gateways[ $gateway_id ] );
				}
			}
		}



		$alg_wc_pgpp_advanced_fallback_gateway = get_option( 'alg_wc_pgpp_advanced_fallback_gateway', '' );
		$alg_wc_pgpp_advanced_fallback_gateway_enabled = get_option( 'alg_wc_pgpp_advanced_fallback_gateway_enabled', 'no' );

		if($alg_wc_pgpp_advanced_fallback_gateway_enabled == 'yes'){
			if ( !empty($alg_wc_pgpp_advanced_fallback_gateway) && empty($available_gateways) ) {
				foreach ( $gateways as $gateway_id => $gateway ) {
					if($alg_wc_pgpp_advanced_fallback_gateway != $gateway_id){
						unset( $gateways[ $gateway_id ] );
					}
				}
				$available_gateways = $gateways;
			}
		}



		return $available_gateways;
	}

}

endif;

return new Alg_WC_PGPP_Core();
