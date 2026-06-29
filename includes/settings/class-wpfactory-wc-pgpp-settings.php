<?php
/**
 * Payment Gateways per Products for WooCommerce - Settings
 *
 * @version 2.0.0
 * @since   1.0.0
 *
 * @author  WPFactory
 *
 * @package WPFactory\WC_Payment_Gateways_Per_Product\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPFactory_WC_PGPP_Settings' ) ) :

	/**
	 * WPFactory_WC_PGPP_Settings class.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	class WPFactory_WC_PGPP_Settings extends WC_Settings_Page {

		/**
		 * Constructor.
		 *
		 * @version 2.0.0
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->id    = 'wpfactory_wc_pgpp';
			$this->label = __( 'Payment Gateways per Products', 'payment-gateways-per-product-categories-for-woocommerce' );

			parent::__construct();

			require_once plugin_dir_path( __FILE__ ) . 'class-wpfactory-wc-pgpp-settings-section.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-wpfactory-wc-pgpp-settings-general.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-wpfactory-wc-pgpp-settings-cats.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-wpfactory-wc-pgpp-settings-tags.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-wpfactory-wc-pgpp-settings-products.php';
			require_once plugin_dir_path( __FILE__ ) . 'class-wpfactory-wc-pgpp-settings-countries.php';
		}

		/**
		 * Get settings.
		 *
		 * @version 1.1.0
		 * @since   1.0.0
		 */
		public function get_settings() {
			global $current_section;
			return array_merge(
				apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
				array(
					array(
						'title' => __( 'Reset Section', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'type'  => 'title',
						'id'    => $this->id . '_' . $current_section . '_reset_options',
					),
					array(
						'title'   => __( 'Reset settings', 'payment-gateways-per-product-categories-for-woocommerce' ),
						'desc'    => '<strong>' . __( 'Reset', 'payment-gateways-per-product-categories-for-woocommerce' ) . '</strong>',
						'id'      => $this->id . '_' . $current_section . '_reset',
						'default' => 'no',
						'type'    => 'checkbox',
					),
					array(
						'type' => 'sectionend',
						'id'   => $this->id . '_' . $current_section . '_reset_options',
					),
				)
			);
		}

		/**
		 * Maybe reset settings.
		 *
		 * @version 1.1.1
		 * @since   1.0.0
		 */
		public function maybe_reset_settings() {
			global $current_section;
			if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
				foreach ( $this->get_settings() as $value ) {
					if ( isset( $value['id'] ) ) {
						$id = explode( '[', $value['id'] );
						delete_option( $id[0] );
					}
				}
				add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
			}
		}

		/**
		 * Admin notice settings reset.
		 *
		 * @version 2.0.0
		 * @since   1.1.1
		 */
		public function admin_notice_settings_reset() {
			echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
				esc_html__( 'Your settings have been reset.', 'payment-gateways-per-product-categories-for-woocommerce' ) .
			'</strong></p></div>';
		}

		/**
		 * Save settings.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function save() {
			parent::save();
			$this->maybe_reset_settings();
		}
	}

endif;

return new WPFactory_WC_PGPP_Settings();
