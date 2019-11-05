<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 *PayRetailers Payment Gateway
 *
 * Provides access to Japanese local payment methods.
 *
 * @class       WC_Gateway_PayRetailers
 * @extends     WC_Payment_Gateway
 * @version     0.1
 * @package     WooCommerce/Classes/Payment
 * @author      WooThemes
 */
class WC_Gateway_PayRetailers extends WC_Payment_Gateway
{

	/** @var array Array of locales */
	public $locale;

	/** @var boolean Whether or not logging is enabled */
	public static $log_enabled = false;

	/** @var WC_Logger Logger instance */
	public static $log = false;

	/**
	 * Constructor for the gateway.
	 */
	public function __construct()
	{

		$this->id                	= 'payretailers';
		$this->icon              	= apply_filters('woocommerce_payretailers_icon', plugins_url('assets/images/payretailers-logo.png', __FILE__));
		$this->has_fields         	= true;
		$this->method_title       	= __('PayRetailers', 'payretailers-woocommerce');
		$this->method_description 	= __('Allows payments by PayRetailers, global payment that fits well for Latin America.', 'payretailers-woocommerce');
		$this->testmode       		= 'yes' === $this->get_option('testmode', 'yes');
		$this->debug          		= 'yes' === $this->get_option('debug', 'yes');
		$this->invoice_prefix		= $this->get_option('invoice_prefix');
		$this->accountID     		= $this->get_option('accountID');
		$this->secretKey     		= $this->get_option('secretKey');
		$this->callbackURL     		= $this->get_option('callbackURL');
		// supported payment gateways chosen by the merchant (among the ones PayRetailers is providing)
		$this->credit_card			= $this->get_option('credit_card');
		$this->web_money			= $this->get_option('web_money');
		$this->konbini				= $this->get_option('konbini');
		$this->bank_transfer		= $this->get_option('bank_transfer');
		$this->pay_easy				= $this->get_option('pay_easy');
		self::$log_enabled    		= $this->debug;
		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();
		// Define user set variables
		$this->title        = $this->get_option('title');
		$this->description  = $this->get_option('description');
		$this->instructions = $this->get_option('instructions', $this->description);
		$this->notify_url = $this->callbackURL == '' ? WC()->api_request_url('WC_Gateway_PayRetailers') : $this->callbackURL;
		// Filters
		// Actions

		include_once('includes/class-wc-gateway-payretailers-ipn-handler.php');
		new WC_Gateway_PayRetailers_IPN_Handler($this->testmode, $this->notify_url, $this->secretKey, $this->invoice_prefix);
	}

	/**
	 * Logging method
	 * @param  string $message
	 */
	public static function log($message)
	{
		if (self::$log_enabled) {
			if (empty(self::$log)) {
				self::$log = new WC_Logger();
			}
			self::$log->add('payretailers', $message);
		}
	}

	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields()
	{
		$this->form_fields = include('includes/settings-payretailers.php');
	}

	/**
	 * Process the payment and return the result
	 *
	 * @param int $order_id
	 * @return array
	 */
	public function process_payment($order_id)
	{
		include_once('includes/class-wc-gateway-payretailers-request.php');
		$order          = wc_get_order($order_id);
		$payretailers_request = new WC_Gateway_PayRetailers_Request($this);
		return array(
			'result'   => 'success',
			'redirect' => $payretailers_request->get_request_url($order, $this->testmode, $_POST['payretailers-method'])
		);
	}


	/**
	 * Payment form on checkout page
	 */
	public function payment_fields()
	{

		if ($description = $this->get_description()) {
			echo wpautop(wptexturize($description));
		}

		$this->payretailers_method_form();
	}

	/**
	 * Form to choose the payment method within PayRetailers optional gateways
	 */
	private function payretailers_method_form($args = array(), $fields = array())
	{

		$default_args = array(
			'fields_have_names' => true,
		);

		$args = wp_parse_args($args, apply_filters('woocommerce_payretailers_method_form_args', $default_args, $this->id));

		$str = '<p class="form-row form-row-wide validate-required woocommerce-validated"><label for="' . esc_attr($this->id) . '-method">' . __('Method of payment:', 'payretailers-woocommerce') . ' <abbr class="required" title="required">*</abbr></label>';
		if ('yes' == $this->credit_card)
			$str .= '<input id="' . esc_attr($this->id) . '-method" class="input-radio" type="radio" value="credit_card" name="' . ($args['fields_have_names'] ? $this->id . '-method' : '') . '" /> ' . __('Credit Card', 'payretailers-woocommerce') . '<img src="' . plugins_url('assets/images/cards.png', __FILE__) . '" /><br/>';
		if ('yes' == $this->konbini)
			$str .= '<input id="' . esc_attr($this->id) . '-method" class="input-radio" type="radio" value="konbini" name="' . ($args['fields_have_names'] ? $this->id . '-method' : '') . '" /> ' . __('Konbini', 'payretailers-woocommerce') . '<img src="' . plugins_url('assets/images/konbini.png', __FILE__) . '" /><br/>';
		if ('yes' == $this->web_money)
			$str .= '<input id="' . esc_attr($this->id) . '-method" class="input-radio" type="radio" value="web_money" name="' . ($args['fields_have_names'] ? $this->id . '-method' : '') . '" /> ' . __('WebMoney', 'payretailers-woocommerce') . '<img src="' . plugins_url('assets/images/webmoney.png', __FILE__) . '" /><br/>';
		if ('yes' == $this->bank_transfer)
			$str .= '<input id="' . esc_attr($this->id) . '-method" class="input-radio" type="radio" value="bank_transfer" name="' . ($args['fields_have_names'] ? $this->id . '-method' : '') . '" /> ' . __('Bank Transfer', 'payretailers-woocommerce') . '<br/>';
		if ('yes' == $this->pay_easy)
			$str .= '<input id="' . esc_attr($this->id) . '-method" class="input-radio" type="radio" value="pay_easy" name="' . ($args['fields_have_names'] ? $this->id . '-method' : '') . '" /> ' . __('Pay Easy', 'payretailers-woocommerce') . '<img src="' . plugins_url('assets/images/payeasy.png', __FILE__) . '" /><br/>';
		$str .= '</p>';
		$default_fields = array('method-field' => $str);
		$fields = wp_parse_args($fields, apply_filters('woocommerce_payretailers_method_form_fields', $default_fields, $this->id));
		?>
		<fieldset id="<?php echo $this->id; ?>-cc-form">
			<?php do_action('woocommerce_payretailers_method_form_start', $this->id); ?>
			<?php
					foreach ($fields as $field) {
						echo $field;
					}
					?>
			<?php do_action('woocommerce_payretailers_method_form_end', $this->id); ?>
			<div class="clear"></div>
		</fieldset>
<?php
	}

	private function get_mydefault_api_url()
	{
		return WC()->api_request_url('WC_Gateway_PayRetailers');
	}

	/**
	 * Validate the payment form (for custom fields added)
	 */
	function validate_fields()
	{
		if (!isset($_POST['payretailers-method'])) {
			wc_add_notice(__('Please select a payment method (how you want to pay)', 'payretailers-woocommerce'), 'error');
			return false;
		}
		return true;
	}
}
