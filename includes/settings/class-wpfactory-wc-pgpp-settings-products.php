<?php
/**
 * Payment Gateways per Products for WooCommerce - Products Section Settings
 *
 * @version 2.0.0
 * @since   1.1.0
 *
 * @author  WPFactory
 *
 * @package WPFactory\WC_Payment_Gateways_Per_Product\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFactory_WC_PGPP_Settings_Products' ) ) :

	/**
	 * WPFactory_WC_PGPP_Settings_Products class.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	class WPFactory_WC_PGPP_Settings_Products extends WPFactory_WC_PGPP_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   1.1.0
		 */
		public function __construct() {
			$this->id   = 'products';
			$this->desc = __( 'Products', 'payment-gateways-per-product-categories-for-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get settings.
		 *
		 * @version 2.0.0
		 * @since   1.1.0
		 *
		 * @todo    "Add variations": add option to use main product and variations *simultaneously*?
		 */
		public function get_settings() {
			return array_merge(
				array(
					array(
						'title' => __( 'Per Products', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'type'  => 'title',
						'id'    => 'alg_wc_pgpp_products_options',
					),
					array(
						'title'             => __( 'Enable/Disable', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'desc'              => '<strong>' .
							__( 'Enable section', 'payment-gateways-per-product-categories-for-woocommerce' ) .
						'</strong>',
						'desc_tip'          => apply_filters(
							'wpfactory_wc_pgpp_settings',
							sprintf(
								'To enable this section you need <a href="%s" target="_blank">Payment Gateways per Products for WooCommerce Pro</a> plugin.',
								'https://wpfactory.com/item/payment-gateways-per-product-for-woocommerce/'
							)
						),
						'id'                => 'alg_wc_pgpp_products_section_enabled',
						'default'           => 'no',
						'type'              => 'checkbox',
						'custom_attributes' => apply_filters(
							'wpfactory_wc_pgpp_settings',
							array( 'disabled' => 'disabled' )
						),
					),
					array(
						'title'             => __( 'Add variations', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'desc'              => __( 'Add', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'desc_tip'          => __( 'Will use variations instead of main product for variable products.', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'id'                => 'alg_wc_pgpp_products_add_variations',
						'default'           => 'no',
						'type'              => 'checkbox',
						'custom_attributes' => apply_filters(
							'wpfactory_wc_pgpp_settings',
							array( 'disabled' => 'disabled' )
						),
					),
					array(
						'type' => 'sectionend',
						'id'   => 'alg_wc_pgpp_products_options',
					),
				),
				parent::get_gateways_settings(
					array(
						'options'    => '',
						'options_id' => 'products',
						'desc_tips'  => array(
							'include' => __( 'Show gateway only if there are selected products in cart.', 'payment-gateways-per-product-categories-for-woocommerce' ),
							'exclude' => __( 'Hide gateway if there are selected products in cart.', 'payment-gateways-per-product-categories-for-woocommerce' ), // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.PostNotIn_exclude
						),
					)
				)
			);
		}
	}

endif;

return new WPFactory_WC_PGPP_Settings_Products();
