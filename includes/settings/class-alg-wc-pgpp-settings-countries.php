<?php
/**
 * Payment Gateways per Products for WooCommerce - Products Section Settings
 *
 * @version 1.1.0
 * @since   1.1.0
 * @author  WPFactory
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_PGPP_Settings_Countries' ) ) :

class Alg_WC_PGPP_Settings_Countries extends Alg_WC_PGPP_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 */
	function __construct() {
		$this->id   = 'countries';
		$this->desc = __( 'Countries', 'payment-gateways-per-product-categories-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.1.0
	 * @since   1.1.0
	 * @todo    [dev] "Add variations": maybe add option to use main product and variations *simultaneously*
	 */
	function get_settings() {
		
		return array(
			array(
				'title'    => __( 'Remove from countries', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc'     => __( 'By default, gateways will appear in all countries. To restrict a specific gateway to a specific country, enter them here, all others will remain untouched', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'type'     => 'title',
				'id'       => 'alg_wc_pgpp_countries_remove',
			),
			array(
				'title'    => __( 'Enable/Disable', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc'     => '<strong>' . __( 'Enable section', 'payment-gateways-per-product-categories-for-woocommerce' ) . '</strong>',
				'desc_tip' => apply_filters( 'alg_wc_pgpp', sprintf(
					'To enable this section you need <a href="%s" target="_blank">Payment Gateways per Products for WooCommerce Pro</a> plugin.',
					'https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/' ), 'settings' ),
				'id'       => 'alg_wc_pgpp_countries_remove_enabled',
				'default'  => 'no',
				'type'     => 'checkbox',
				'custom_attributes' => apply_filters( 'alg_wc_pgpp', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			
			array(
				'title'    => __( 'Choose Countries', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc_tip' => __( 'If countries chosen following payment gateways will be excluded', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'id'       => 'alg_wc_pgpp_countries_remove_countries',
				'default'  => '',
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'options'  => $this->allCountries(),
				'custom_attributes' => apply_filters( 'alg_wc_pgpp', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			
			array(
				'title'    => __( 'Choose gateway to appear with above countries', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc_tip' => __( 'Gateways will appeared with above chosen countries.', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'id'       => 'alg_wc_pgpp_countries_remove_include_gateway',
				'default'  => '',
				'type'     => 'multiselect',
				'class'    => 'wc-enhanced-select',
				'options'  => $this->allGateways(),
				'custom_attributes' => apply_filters( 'alg_wc_pgpp', array( 'disabled' => 'disabled' ), 'settings' ),
			),
			array(
				'type'     => 'sectionend',
				'id'       => 'alg_wc_pgpp_products_options',
			),
		);
	}
	
	public function allCountries(){
		$wc_countries = new WC_Countries();
		$countries = $wc_countries->get_countries();
		return $countries;
	}
	
	public function allGateways(){
		$available_gateways = WC()->payment_gateways->payment_gateways();
		$gateways_settings  = array();
		foreach ( $available_gateways as $gateway_id => $gateway ) {
			if(isset($gateway->method_title) && !empty($gateway->method_title)){
				$gateways_settings[$gateway_id] = $gateway->method_title . ' - ' . $gateway->title;
			}else{
				$gateways_settings[$gateway_id] = $gateway->title;
			}
		}
		return $gateways_settings;
	}

}

endif;

return new Alg_WC_PGPP_Settings_Countries();
