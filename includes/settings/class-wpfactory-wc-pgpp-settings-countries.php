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

if ( ! class_exists( 'WPFactory_WC_PGPP_Settings_Countries' ) ) :

	/**
	 * WPFactory_WC_PGPP_Settings_Countries class.
	 *
	 * @version 2.0.0
	 * @since   1.1.0
	 */
	class WPFactory_WC_PGPP_Settings_Countries extends WPFactory_WC_PGPP_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   1.1.0
		 */
		public function __construct() {
			$this->id   = 'countries';
			$this->desc = __( 'Countries', 'payment-gateways-per-product-categories-for-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get restriction settings.
		 *
		 * @version 2.0.0
		 * @since   1.7.9
		 *
		 * @todo    Get restriction setting based on saved numbers.
		 */
		public function get_restriction_settings() {

			$numbers = (int) apply_filters( 'wpfactory_wc_pgpp_countries_restriction_number', 1 );

			$return = array();

			if ( $numbers > 0 ) {
				for ( $i = 1; $i <= $numbers; $i++ ) {

					if ( 1 === $i ) {
						$country_id = 'alg_wc_pgpp_countries_remove_countries';
						$gateway_id = 'alg_wc_pgpp_countries_remove_include_gateway';
					} else {
						$country_id = 'alg_wc_pgpp_countries_remove_countries_' . $i;
						$gateway_id = 'alg_wc_pgpp_countries_remove_include_gateway_' . $i;
					}

					$return[] = array(
						'title' => sprintf(
							/* Translators: %d: Restriction number. */
							__( 'Restrict gateway for selected countries (#%d)', 'payment-gateways-per-product-categories-for-woocommerce' ),
							$i
						),
						'type'  => 'title',
						'id'    => 'alg_wc_pgpp_countries_condition_' . $i,
					);

					$return[] = array(
						'title'             => __( 'Choose countries', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'desc_tip'          => __( 'If countries are chosen, the following payment gateways will be included.', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'id'                => $country_id,
						'default'           => '',
						'type'              => 'multiselect',
						'class'             => 'wc-enhanced-select',
						'options'           => WC()->countries->get_countries(),
						'custom_attributes' => apply_filters(
							'wpfactory_wc_pgpp_settings',
							array( 'disabled' => 'disabled' )
						),
					);

					$return[] = array(
						'title'             => __( 'Choose gateways to appear only on selected countries above', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'desc_tip'          => __( 'Gateways will appear with the above-chosen countries.', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'id'                => $gateway_id,
						'default'           => '',
						'type'              => 'multiselect',
						'class'             => 'wc-enhanced-select',
						'options'           => wpfactory_wc_pgpp()->core->get_all_gateways(),
						'custom_attributes' => apply_filters(
							'wpfactory_wc_pgpp_settings',
							array( 'disabled' => 'disabled' )
						),
					);

					$return[] = array(
						'type' => 'sectionend',
						'id'   => 'alg_wc_pgpp_countries_condition_' . $i,
					);

				}
			}
			return $return;
		}

		/**
		 * Get settings.
		 *
		 * @version 2.0.0
		 * @since   1.1.0
		 */
		public function get_settings() {

			$restrictions = $this->get_restriction_settings();
			$settings     = array();

			$settings[] = array(
				'title' => __( 'Remove from countries', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc'  => __( 'By default, gateways will appear in all countries. To restrict a specific gateway to a specific country, enter it here, all others will remain untouched.', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'type'  => 'title',
				'id'    => 'alg_wc_pgpp_countries_remove',
			);

			$settings[] = array(
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
				'id'                => 'alg_wc_pgpp_countries_remove_enabled',
				'default'           => 'no',
				'type'              => 'checkbox',
				'custom_attributes' => apply_filters(
					'wpfactory_wc_pgpp_settings',
					array( 'disabled' => 'disabled' )
				),
			);

			$settings[] = array(
				'title'             => __( 'Combine conditions from other sections', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc'              => __( 'Enable', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc_tip'          => __( 'It will result in the combination of payment gateways with other conditions from other sections (Product Category, Product Tags, Per Product) as well.', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'id'                => 'alg_wc_pgpp_countries_combine_condition',
				'default'           => 'no',
				'type'              => 'checkbox',
				'custom_attributes' => apply_filters(
					'wpfactory_wc_pgpp_settings',
					array( 'disabled' => 'disabled' )
				),
			);

			$settings[] = array(
				'title'             => __( 'Number of payment by country restrictions', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'desc_tip'          => __( 'It will behave the same as the default Country-Gateway combination mentioned above, with the added flexibility of being able to include multiple conditions if needed.', 'payment-gateways-per-product-categories-for-woocommerce' ),
				'id'                => 'alg_wc_pgpp_countries_restriction_number',
				'default'           => 1,
				'type'              => 'number',
				'custom_attributes' => apply_filters(
					'wpfactory_wc_pgpp_settings_countries_restriction_number',
					array( 'disabled' => 'disabled' )
				),
			);

			$settings[] = array(
				'type' => 'sectionend',
				'id'   => 'alg_wc_pgpp_products_options',
			);

			return array_merge( $settings, $restrictions );
		}
	}

endif;

return new WPFactory_WC_PGPP_Settings_Countries();
