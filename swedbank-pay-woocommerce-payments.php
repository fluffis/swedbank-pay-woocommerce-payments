<?php
/*
 * Plugin Name: Swedbank Pay Payments
 * Plugin URI: https://www.swedbankpay.com/
 * Description: (Preview). Provides a Credit Card Payment Gateway through Swedbank Pay for WooCommerce.
 * Author: Swedbank Pay
 * Author URI: https://profiles.wordpress.org/swedbankpay/
 * License: Apache License 2.0
 * License URI: http://www.apache.org/licenses/LICENSE-2.0
 * Version: 2.0.0-beta.1
 * Text Domain: swedbank-pay-woocommerce-payments
 * Domain Path: /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 3.9.1
 */

use SwedbankPay\Payments\WooCommerce\WC_Swedbank_Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( '\SwedbankPay\Payments\WooCommerce\WC_Swedbank_Plugin', false ) ) {
	include_once( dirname( __FILE__ ) . '/includes/class-wc-swedbank-plugin.php' );
}

class WC_Swedbank_Pay extends WC_Swedbank_Plugin {
	const TEXT_DOMAIN = 'swedbank-pay-woocommerce-payments';

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();

		// Activation
		register_activation_hook( __FILE__, [ $this, 'install' ] );

		// Actions
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ], 0 );
		add_action( 'woocommerce_loaded', [ $this, 'woocommerce_loaded' ], 20 );
	}

	/**
	 * Install
	 */
	public function install() {
		parent::install();
	}

	/**
	 * Add relevant links to plugins page
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$plugin_links = [
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=payex_psp_cc' ) . '">' . __( 'Settings',
				WC_Swedbank_Pay::TEXT_DOMAIN ) . '</a>'
		];

		return array_merge( $plugin_links, $links );
	}

	/**
	 * Init localisations and files
	 */
	public function init() {
		// Localization
		load_plugin_textdomain( WC_Swedbank_Pay::TEXT_DOMAIN, false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * WooCommerce Loaded: load classes
	 */
	public function woocommerce_loaded() {
		include_once( dirname( __FILE__ ) . '/includes/class-wc-adapter.php' );
		include_once( dirname( __FILE__ ) . '/includes/class-wc-gateway-swedbank-pay-cc.php' );
		include_once( dirname( __FILE__ ) . '/includes/class-wc-gateway-swedbank-pay-invoice.php' );
		include_once( dirname( __FILE__ ) . '/includes/class-wc-gateway-swedbank-pay-vipps.php' );
		include_once( dirname( __FILE__ ) . '/includes/class-wc-gateway-swedbank-pay-swish.php' );

		// Register Gateways
		WC_Swedbank_Pay::register_gateway( 'WC_Gateway_Swedbank_Pay_Cc' );
		WC_Swedbank_Pay::register_gateway( 'WC_Gateway_Swedbank_Pay_Invoice' );
		WC_Swedbank_Pay::register_gateway( 'WC_Gateway_Swedbank_Pay_Vipps' );
		WC_Swedbank_Pay::register_gateway( 'WC_Gateway_Swedbank_Pay_Swish' );
	}
}

new WC_Swedbank_Pay();
