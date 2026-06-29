<?php
/**
 * Payment Gateways per Products for WooCommerce - General Section Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  WPFactory
 *
 * @package WPFactory\WC_Payment_Gateways_Per_Product\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFactory_WC_PGPP_Settings_General' ) ) :

	/**
	 * WPFactory_WC_PGPP_Settings_General class.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	class WPFactory_WC_PGPP_Settings_General extends WPFactory_WC_PGPP_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->id   = '';
			$this->desc = __( 'General', 'payment-gateways-per-product-categories-for-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get settings.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @todo    Better description for the "Add filter" option.
		 */
		public function get_settings() {
			return array(
				array(
					'title' => __( 'Advanced Options', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_pgpp_advanced_options',
				),
				array(
					'title'             => __( 'Fallback gateway', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'desc'              => __( 'Enable', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'desc_tip'          => apply_filters(
						'wpfactory_wc_pgpp_settings',
						sprintf(
							'To enable this section you need <a href="%s" target="_blank">Payment Gateways per Products for WooCommerce Pro</a> plugin.',
							'https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/'
						)
					),
					'id'                => 'alg_wc_pgpp_advanced_fallback_gateway_enabled',
					'default'           => 'no',
					'type'              => 'checkbox',
					'custom_attributes' => apply_filters(
						'wpfactory_wc_pgpp_settings',
						array( 'disabled' => 'disabled' )
					),
				),
				array(
					'desc'              => __( 'Choose fallback gateway', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'desc_tip'          => __( 'If products in cart are in mixing payment gateway rules, show this gateway.', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'id'                => 'alg_wc_pgpp_advanced_fallback_gateway',
					'default'           => '',
					'type'              => 'select',
					'class'             => 'wc-enhanced-select',
					'options'           => wpfactory_wc_pgpp()->core->get_all_gateways(),
					'custom_attributes' => apply_filters(
						'wpfactory_wc_pgpp_settings',
						array( 'disabled' => 'disabled' )
					),
				),
				array(
					'title'    => __( 'Add filter', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'desc_tip' => __( 'Change this if you are having issues with plugin not working correctly.', 'payment-gateways-per-product-categories-for-woocommerce' ),
					'id'       => 'alg_wc_pgpp_advanced_add_hook',
					'default'  => 'init',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'options'  => array(
						'constructor' => __( 'In constructor', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'init'        => __( 'On "init" action', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'wp_loaded'   => __( 'On "wp_loaded" action', 'payment-gateways-per-product-categories-for-woocommerce' ),
					),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_pgpp_advanced_options',
				),
			);
		}
	}

endif;

return new WPFactory_WC_PGPP_Settings_General();
