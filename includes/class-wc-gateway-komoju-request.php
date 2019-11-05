<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Generates requests to send to PayRetailers
 */
class WC_Gateway_PayRetailers_Request {

	/**
	 * Stores line items to send to PayRetailers
	 * @var array
	 */
	protected $line_items = array();

	/**
	 * Pointer to gateway making the request
	 * @var WC_Gateway_PayRetailers
	 */
	protected $gateway;

	/**
	 * Endpoint for requests from PayRetailers
	 * @var string
	 */
	protected $notify_url;

	/**
	 * Constructor
	 * @param WC_Gateway_PayRetailers $gateway
	 */
	public function __construct( $gateway ) {
		$this->gateway    = $gateway;
		$this->notify_url = $this->gateway->notify_url;
 		$this->request_id = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
		$this->PayRetailers_endpoint = '/en/api/'.$this->gateway->accountID. '/transactions/';
	}

	/**
	 * Get the PayRetailers request URL for an order
	 * @param  WC_Order  $order
	 * @param  boolean $sandbox
	 * @return string
	 */
	public function get_request_url( $order, $sandbox = false, $method = 'credit_card' ) {
		$payretailers_args = $this->get_payretailers_args( $order, $method );

		if ( $sandbox ) {
			return 'https://sandbox.payretailers.com' . $this->PayRetailers_endpoint.$method.'/new'.'?' .$payretailers_args;
		} else {
			return 'https://payretailers.com' . $this->PayRetailers_endpoint.$method.'/new'.'?' .$payretailers_args;
		}
	}

	/**
	 * Get PayRetailers Args for passing to PayRetailers hosted page
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	protected function get_payretailers_args( $order, $method ) {
		WC_Gateway_PayRetailers::log( 'Generating payment form for order ' . $order->get_order_number() . '. Notify URL: ' . $this->notify_url );

		$params = array(
				"transaction[amount]"						=> $order->get_subtotal()+$order->get_total_shipping(),
				"transaction[currency]"						=> get_woocommerce_currency(),
				"transaction[customer][email]"			    => $order->billing_email,
				"transaction[customer][phone]"			    => $order->billing_phone,
				"transaction[customer][given_name]"			=> $order->billing_first_name,
				"transaction[customer][family_name]"		=> $order->billing_last_name,
				"transaction[external_order_num]"			=> $this->gateway->get_option( 'invoice_prefix' ) . $order->get_order_number() . '-' . $this->request_id,
				"transaction[return_url]"					=> $this->gateway->get_return_url( $order ),
				"transaction[cancel_url]"					=> $order->get_cancel_order_url_raw(),
				"transaction[callback_url]"					=> $this->notify_url,
				"transaction[tax]"							=> strlen($order->get_total_tax())==0 ? 0 : $order->get_total_tax(),
				"timestamp"									=> time(),
		);
		WC_Gateway_PayRetailers::log( 'Raw parametres: ' .print_r( $params, true) );

		$qs_params = array();
		foreach ($params as $key => $val) {
			$qs_params[] = urlencode($key) . '=' . urlencode($val);
		}
		sort($qs_params);
		$query_string = implode('&', $qs_params);

		$url = $this->PayRetailers_endpoint.$method.'/new'. '?' .$query_string;
		$hmac = hash_hmac('sha256', $url, $this->gateway->secretKey);
		$query_string .= '&hmac='.$hmac;

		return $query_string;
	}

	/**
	 * Check if currency has decimals
	 *
	 * @param  string $currency
	 *
	 * @return bool
	 */
	protected function currency_has_decimals( $currency ) {
		if ( in_array( $currency, array( 'HUF', 'JPY', 'TWD' ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Round prices
	 *
	 * @param  float|int $price
	 * @param  WC_Order $order
	 *
	 * @return float|int
	 */
	protected function round( $price, $order ) {
		$precision = 2;

		if ( ! $this->currency_has_decimals( $order->get_order_currency() ) ) {
			$precision = 0;
		}

		return round( $price, $precision );
	}

	/**
	 * Format prices
	 *
	 * @param  float|int $price
	 * @param  WC_Order $order
	 *
	 * @return float|int
	 */
	protected function number_format( $price, $order ) {
		$decimals = 2;

		if ( ! $this->currency_has_decimals( $order->get_order_currency() ) ) {
			$decimals = 0;
		}

		return number_format( $price, $decimals, '.', '' );
	}
}
