<?php
/**
 * Payment Gateways per Products for WooCommerce - Section Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  WPFactory
 *
 * @package WPFactory\WC_Payment_Gateways_Per_Product\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFactory_WC_PGPP_Settings_Section' ) ) :

	/**
	 * WPFactory_WC_PGPP_Settings_Section class.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	class WPFactory_WC_PGPP_Settings_Section {

		/**
		 * ID.
		 *
		 * @version 1.8.0
		 * @since   1.8.0
		 *
		 * @var     string
		 */
		public $id;

		/**
		 * Description.
		 *
		 * @version 1.8.0
		 * @since   1.8.0
		 *
		 * @var     string
		 */
		public $desc;

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 */
		public function __construct() {
			$id = 'wpfactory_wc_pgpp';
			add_filter(
				'woocommerce_get_sections_' . $id,
				array( $this, 'settings_section' )
			);
			add_filter(
				'woocommerce_get_settings_' . $id . '_' . $this->id,
				array( $this, 'get_settings' ),
				PHP_INT_MAX
			);
		}

		/**
		 * Settings section.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param   array $sections Sections.
		 *
		 * @return  array
		 */
		public function settings_section( $sections ) {
			$sections[ $this->id ] = $this->desc;
			return $sections;
		}

		/**
		 * Get terms.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 *
		 * @param   array|string $args Arguments.
		 *
		 * @return  array
		 */
		public function get_terms( $args ) {
			global $wp_version, $sitepress;

			if ( ! is_array( $args ) ) {
				$_taxonomy = $args;
				$args      = array(
					'taxonomy'   => $_taxonomy,
					'orderby'    => 'name',
					'hide_empty' => false,
				);
			}

			if ( isset( $sitepress ) ) {
				$admin_current_lang = apply_filters( 'wpml_current_language', null ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				$sitepress->switch_lang( 'all' );
			}

			if ( version_compare( $wp_version, '4.5.0', '>=' ) ) {
				$_terms = get_terms( $args );
			} else {
				$_taxonomy = $args['taxonomy'];
				unset( $args['taxonomy'] );
				$_terms = get_terms( $_taxonomy, $args ); // phpcs:ignore WordPress.WP.DeprecatedParameters.Get_termsParam2Found
			}

			if ( isset( $sitepress ) ) {
				$sitepress->switch_lang( $admin_current_lang );
			}

			$_terms_options = array();
			if ( ! empty( $_terms ) && ! is_wp_error( $_terms ) ) {
				foreach ( $_terms as $_term ) {
					if ( isset( $sitepress ) ) {
						$term_name = $_term->name . ' (' . $this->get_language_by_term_id( $_term->term_id, $_taxonomy ) . ')';
					} else {
						$term_name = $_term->name;
					}
					$_terms_options[ $_term->term_id ] = $term_name;
				}
			}
			return $_terms_options;
		}

		/**
		 * Get language by term ID.
		 *
		 * @version 2.0.0
		 *
		 * @param   int    $term_id   Term ID.
		 * @param   string $term_type Term type.
		 *
		 * @return  string
		 */
		public function get_language_by_term_id( $term_id, $term_type ) {
			global $wpdb;

			$term_type  = 'tax_' . $term_type;
			$table      = $wpdb->prefix . 'icl_translations';

			// phpcs:disable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter
			$language_code = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT language_code
					FROM {$table}
					WHERE element_id = %d
					AND element_type = %s",
					$term_id,
					$term_type
				)
			);
			// phpcs:enable WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, PluginCheck.Security.DirectDB.UnescapedDBParameter

			return $language_code;
		}

		/**
		 * Get gateways settings.
		 *
		 * @version 2.0.0
		 * @since   1.1.0
		 *
		 * @param   array $args Arguments.
		 *
		 * @todo    Add "Chosen select / Standard multiselect" option.
		 * @todo    Add "Select all" button.
		 * @todo    Add "Set as IDs" option (i.e., enter categories / tags / products by ID (i.e., as comma separated text)).
		 * @todo    Add (i.e., duplicate) settings to "WooCommerce > Settings > Payments > Direct bank transfer" etc.?
		 */
		public function get_gateways_settings( $args ) {
			$gateways = wpfactory_wc_pgpp()->core->get_all_gateways();

			$gateways_settings = array();
			foreach ( $gateways as $gateway_id => $gateway_title ) {

				$options_in = apply_filters(
					'wpfactory_wc_pgpp_gateway_settings_options',
					$args['options'],
					$gateway_id,
					$args['options_id'],
					'include'
				);
				$options_ex = apply_filters(
					'wpfactory_wc_pgpp_gateway_settings_options',
					$args['options'],
					$gateway_id,
					$args['options_id'],
					'exclude'
				);

				$class = 'wc-enhanced-select ' . $args['options_id'] . '_select_pgpp';
				$class = apply_filters(
					'wpfactory_wc_pgpp_gateway_settings_class',
					$class,
					$gateway_id,
					$args['options_id']
				);

				$custom_attributes = (
					'products' === $args['options_id'] ?
					apply_filters(
						'wpfactory_wc_pgpp_gateway_settings_custom_attributes',
						array( 'disabled' => 'disabled' ),
						$gateway_id,
						$args['options_id']
					) :
					''
				);

				$gateways_settings = array_merge(
					$gateways_settings,
					array(
						array(
							'title' => $gateway_title,
							'type'  => 'title',
							'id'    => 'alg_wc_pgpp_' . $args['options_id'] . '_gateway_' . $gateway_id . '_options',
						),
						array(
							'title'             => __( 'Include', 'payment-gateways-per-product-categories-for-woocommerce' ),
							'desc_tip'          => (
								$args['desc_tips']['include'] . ' ' .
								__( 'Ignored if empty.', 'payment-gateways-per-product-categories-for-woocommerce' )
							),
							'id'                => 'alg_wc_pgpp_' . $args['options_id'] . '_include_' . $gateway_id,
							'default'           => '',
							'type'              => 'multiselect',
							'class'             => $class,
							'options'           => $options_in,
							'custom_attributes' => $custom_attributes,
						),
						array(
							'title'             => __( 'Exclude', 'payment-gateways-per-product-categories-for-woocommerce' ),
							'desc_tip'          => (
								$args['desc_tips']['exclude'] . ' ' .
								__( 'Ignored if empty.', 'payment-gateways-per-product-categories-for-woocommerce' )
							),
							'id'                => 'alg_wc_pgpp_' . $args['options_id'] . '_exclude_' . $gateway_id,
							'default'           => '',
							'type'              => 'multiselect',
							'class'             => $class,
							'options'           => $options_ex,
							'custom_attributes' => $custom_attributes,
						),
						array(
							'type' => 'sectionend',
							'id'   => 'alg_wc_pgpp_' . $args['options_id'] . '_gateway_' . $gateway_id . '_options',
						),
					)
				);
			}
			return $gateways_settings;
		}
	}

endif;
