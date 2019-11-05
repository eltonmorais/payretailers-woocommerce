<?php
/*
Plugin Name: WooCommerce PayRetailers Gateway
Plugin URI: https://autopilottools.com
Description: Extends WooCommerce with PayRetailers gateway.
Version: 0.1
Author: Elton Morais
Author URI: https://autopilottools.com
*/

add_action('plugins_loaded', 'woocommerce_payretailers_init', 0);

function woocommerce_payretailers_init() {

	if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

	/**
 	 * Localisation
	 */
    load_plugin_textdomain( 'payretailers-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	require_once 'class-wc-gateway-payretailers.php';

	/**
 	* Add the Gateway to WooCommerce
 	**/
	function woocommerce_add_payretailers_gateway($methods) {
		$methods[] = 'WC_Gateway_PayRetailers';
		return $methods;
	}
	
	add_filter('woocommerce_payment_gateways', 'woocommerce_add_payretailers_gateway' );
}
