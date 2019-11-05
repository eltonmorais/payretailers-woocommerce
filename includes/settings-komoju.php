<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings for PayRetailers Gateway (hosted page)
 */

return array(
	'enabled' => array(
		'title'   => __( 'Enable/Disable', 'payretailers-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Enable PayRetailers', 'payretailers-woocommerce' ),
		'default' => 'no'
	),
	'title' => array(
		'title'       => __( 'Title', 'payretailers-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'This controls the title which the user sees during checkout.', 'payretailers-woocommerce' ),
		'default'     => __( 'PayRetailers', 'payretailers-woocommerce' ),
		'desc_tip'    => true,
	),
	'description' => array(
		'title'       => __( 'Description', 'payretailers-woocommerce' ),
		'type'        => 'textarea',
		'description' => __( 'Payment method description that the customer will see on your checkout.', 'payretailers-woocommerce' ),
		'default'     => __( 'Make your payment through PayRetailers: offline and online Japanese payments like Konbini, credit cards, WebMoney, ...', 'payretailers-woocommerce' ),
		'desc_tip'    => true,
	),
	'supported_methods' => array(
		'title'	=> __('Supported methods set in PayRetailers', 'payretailers-woocommerce'),
		'type'	=> 'title',
		'id'	=> 'supported-methods-in-payretailers'
	),
	'credit_card' => array(
		'title'   => __( 'Credit Card', 'payretailers-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow credit card', 'payretailers-woocommerce' ),
		'default' => 'yes'
	),
	'web_money' => array(
		'title'   => __( 'Web Money', 'payretailers-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow Web Money', 'payretailers-woocommerce' ),
		'default' => 'yes'
	),
	'konbini' => array(
		'title'   => __( 'Konbini', 'payretailers-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow delayed payment in convenience store', 'payretailers-woocommerce' ),
		'description' => __( 'Lawson, Family Mart, Sunkus, Circle-K, Ministop, Daily Yamazaki, 7-Eleven', 'payretailers-woocommerce' ),
		'desc_tip'    => true,
		'default' => 'yes'
	),
	'bank_transfer' => array(
		'title'   => __( 'Bank Transfer', 'payretailers-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow bank transfer', 'payretailers-woocommerce' ),
		'default' => 'yes'
	),
	'pay_easy' => array(
		'title'   => __( 'Pay Easy', 'payretailers-woocommerce' ),
		'type'    => 'checkbox',
		'label'   => __( 'Allow delayed payment through Pay Easy', 'payretailers-woocommerce' ),
		'default' => 'yes'
	),
	'API_settings' => array(
		'title'	=> 'API Settings',
		'type'	=> 'title',
		'id'	=> 'api-seetings-in-payretailers'
	),
	'accountID' => array(
		'title'       => __( 'PayRetailers merchant ID', 'payretailers-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter your PayRetailers account ID.', 'payretailers-woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
	),
	'secretKey' => array(
		'title'       => __( 'Secret Key from PayRetailers', 'payretailers-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter your PayRetailers secret key.', 'payretailers-woocommerce' ),
		'default'     => '',
		'desc_tip'    => true,
	),
	'callbackURL' => array(
		'title'       => __( 'Callback Url', 'payretailers-woocommerce' ),
		'type'        => 'text',
		'description' => sprintf( __( 'Specify a special callback url (or leave this field empty if you don\'t know what it is). Default url is %s', 'payretailers-woocommerce' ), $this->get_mydefault_api_url() ),
		'default'     => '',
	),
	'testmode' => array(
		'title'       => __( 'PayRetailers Sandbox', 'payretailers-woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable PayRetailers sandbox', 'payretailers-woocommerce' ),
		'default'     => 'yes',
		'description' => sprintf( __( 'When checked, your PayRetailers sandbox will be used in order to test payments. Sign up for a developer account <a href="%s">here</a>.', 'payretailers-woocommerce' ), 'https://sandbox.payretailers.com/sign_up' ),
	),
	'invoice_prefix' => array(
		'title'       => __( 'Invoice Prefix', 'payretailers-woocommerce' ),
		'type'        => 'text',
		'description' => __( 'Please enter a prefix for your invoice numbers. If you use your PayRetailers account for multiple stores ensure this prefix is unique.', 'payretailers-woocommerce' ),
		'default'     => 'WC-',
		'desc_tip'    => true,
	),
	'debug' => array(
		'title'       => __( 'Debug Log', 'payretailers-woocommerce' ),
		'type'        => 'checkbox',
		'label'       => __( 'Enable logging', 'payretailers-woocommerce' ),
		'default'     => 'no',
		'description' => sprintf( __( 'Log PayRetailers events inside <code>%s</code>', 'payretailers-woocommerce' ), wc_get_log_file_path( 'payretailers' ) )
	),
);
?>