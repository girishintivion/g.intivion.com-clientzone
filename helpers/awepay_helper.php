<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
abstract class Awepay_ApiElementBase
{
	
	protected $_errors = array();
	
	protected function addError($msg)
	{
		$this->_errors[] = $msg;
	}
	
	protected function addErrors($prefix, $msgs)
	{
		foreach ($msgs as $msg) {
			$this->addError('(' . $prefix . ') ' . $msg);
		}
	}
	
	public function getErrors()
	{
		return $this->_errors;
	}
	
	protected function validate()
	{
		return true;
	}
	
	protected function toArray()
	{
		$return = array();
		foreach (get_class_vars(get_class($this)) as $var => $defVal) {
			if (substr($var, 0, 1) == '_') {
				continue;
			}
			if (is_object($this->$var)) {
				$return[$var] = $this->$var->toArray();
			} elseif (is_array($this->$var)) {
				foreach ($this->$var as $index => $val) {
					if (is_object($val)) {
						$return[$var][$index] = $val->toArray();
					} else {
						$return[$var][$index] = $val;
					}
				}
			} else {
				$return[$var] = $this->$var;
			}
		}
		return $return;
	}
	
}

abstract class Awepay_ApiBase extends Awepay_ApiElementBase
{
	
	protected $_url = null;
	
	const VISA = 'visa';
	const MASTERCARD = 'mastercard';
	const AMERICAN_EXPRESS = 'amex';
	const JCB = 'jcb';
	const DISCOVER = 'discover';
	const DINERS = 'diners';
	const UNION_PAY = 'union_pay';
	const ACH = 'ach';
	const CHECK21 = 'check21';
	
	const TYPE_CHECKING = 1;
	const TYPE_SAVINGS = 2;
	
	const STATUS_APPROVED = 'OK';
	const STATUS_DECLINED = 'EXC';
	const STATUS_ERROR = 'PAYG_ERROR';
	const STATUS_PENDING = 'PENDING';
	const STATUS_3D = 'REQ';
	
	const CLASS_INTERNET_INITIATED = 'WEB';
	const CLASS_ACCOUNT_RECEIVABLE = 'ARC';
	const CLASS_CUSTOMER_INITIATED = 'CIE';
	const CLASS_PREARRANGED_PAYMENT_DEPOSIT = 'PPD';
	const CLASS_TELEPHONE_INITIATED = 'TEL';
	const CLASS_CASH_CONCENTRATION_DISBURSEMENT = 'CCD';
	const CLASS_CORPORATE_TRADE_EXCHANGE = 'CTX';
	
	protected $_transport = null;
	protected $_action = null;
	protected $_status = null;
	protected $_txid = null;
	protected $_error = null;
	protected $_errorcode = null;
	protected $_3dform = null;
	
	public $sid;
	public $rcode;
	
	public function __construct()
	{
		$this->_transport = new SoapClient($this->_url, array('trace' => true, 'exceptions' => true));
	}
	
	public function call()
	{
		if (!$this->validate()) {
			return false;
		}
		$response = $this->_transport->__soapCall($this->_action, $this->getRequestParams());
		$myfile = fopen("responseofawepay.txt", "w") or die("Unable to open file!");
		fwrite($myfile,json_encode($response));
		fclose($myfile);
		$this->parseResponse($response);
		return true;
	}
	
	protected function getRequestParams()
	{
		$wsdlFunctions = $this->_transport->__getFunctions();
		foreach ($wsdlFunctions as $func) {
			if (stripos($func, $this->_action . '(') !== false) {
				$wsdlFunction = $func;
				break;
			}
		}

		$params = $this->toArray();
		foreach ($params as $k => $v) {
			$match = strpos($wsdlFunction, "\${$k}");
			if ($match !== false) {
				$requestParams[$k] = $match;
			}
		}

		if (is_array($requestParams)) {
			asort($requestParams);
		} else {
			die('The requested service method or parameter names was not found on the web-service. Please check the method name and parameters.');
		}

		foreach ($requestParams as $k => $paramName) {
			$requestParams[$k] = $params[$k];
		}
		return $requestParams;
	}
	
	protected function parseResponse($response)
	{
		$response = $this->object2array($response);
		$this->_status = $response['status'];
		$this->_txid = $response['txid'];
		$this->_error = $response['error']['msg'];
		$this->_errorcode = $response['error']['code'];
		if (isset($response['required']['embedhtml'])) {
			$this->_3dform = $response['required']['embedhtml'];
		}
	}
	
	protected function object2array($object)
	{
		if (is_object($object)) {
			$object = get_object_vars($object);
		}
		
		if (is_array($object)) {
			foreach ($object as $var => $val){
				if (is_object($object[$var]) || is_array($object[$var])) {
					$object[$var] = $this->object2array($object[$var]);
				}
			}
		}
		
		return $object;
	}
	
	public function setSid($sid)
	{
		$this->sid = $sid;
	}
	
	public function setRCode($rcode)
	{
		$this->rcode = $rcode;
	}

	public function getStatus()
	{
		return $this->_status;
	}
	
	public function getResponseErrorCode()
	{
		return $this->_errorcode;
	}
	
	public function getResponseErrorMessage()
	{
		return $this->_error;
	}
	
	public function getTransactionId()
	{
		return $this->_txid;
	}
	
	public function get3dForm()
	{
		return $this->_3dform;
	}
	
}

abstract class Awepay_PrimaryRequest extends Awepay_ApiBase
{
	
	
	public $udetails;
	public $paydetails;
	public $cart;
	public $txparams;
	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->udetails = new Awepay_UserDetails();
		$this->paydetails = new Awepay_PaymentDetails();
		$this->cart = new Awepay_Cart();
		$this->txparams = new Awepay_TransactionParameters();
	}
	
	protected function validate()
	{
		if (!$this->sid) {
			$this->addError('`sid` is required.');
		}
		
		if (!$this->rcode) {
			$this->addError('`rcode` is required.');
		}
		
		if (!$this->udetails->validate()) {
			$this->addErrors('Udetails', $this->udetails->getErrors());
		}
		
		if (!$this->paydetails->validate()) {
			$this->addErrors('Paydetails', $this->paydetails->getErrors());
		}
		
		if (!$this->cart->validate()) {
			$this->addErrors('Cart', $this->cart->getErrors());
		}
		
		if (!$this->txparams->validate()) {
			$this->addErrors('Txparams', $this->txparams->getErrors());
		}
		
		if (!empty($this->_errors)) {
			return false;
		}
		
		return true;
	}
	
	public function setUsername($username)
	{
		$this->udetails->username = $username;
	}
	
	public function setPassword($password)
	{
		$this->udetails->password = $password;
	}
	
	public function setFirstName($name)
	{
		$this->udetails->firstname = $name;
	}
	
	public function setLastName($name)
	{
		$this->udetails->lastname = $name;
	}
	
	public function setEmail($email)
	{
		$this->udetails->email = $email;
	}
	
	public function setPhoneNumber($phone)
	{
		$phone = preg_replace('![^0-9\+#]!', '', $phone);
		$this->udetails->phone = $phone;
	}
	
	public function setMobileNumber($phone)
	{
		$phone = preg_replace('![^0-9\+#]!', '', $phone);
		$this->udetails->mobile = $phone;
	}
	
	public function setBillingAddress($address)
	{
		$address = preg_replace('![^0-9a-z /\-]!i', '', $address);
		$this->udetails->address = $address;
	}
	
	public function setBillingCity($city)
	{
		$this->udetails->suburb_city = $city;
	}
	
	public function setBillingState($state)
	{
		if (strlen($state) > 3) {
			throw new Exception('The `state` field only accepts 2 and 3 character state codes as per the ISO standard');
		}
		$this->udetails->state = strtoupper($state);
	}
	
	public function setBillingCountry($country)
	{
		if (strlen($country) != 2) {
			throw new Exception('The `country` field only accepts the 2 character international country codes');
		}
		$this->udetails->country = strtoupper($country);
	}
	
	public function setBillingPostCode($postcode)
	{
		$this->udetails->postcode = $postcode;
	}
	
	public function setShippingFirstName($name)
	{
		$this->udetails->ship_firstname = $name;
	}
	
	public function setShippingLastName($name)
	{
		$this->udetails->ship_lastname = $name;
	}
	
	public function setShippingAddress($address)
	{
		$address = preg_replace('![^0-9a-z /\-\\]!i', '', $address);
		$this->udetails->ship_address = $address;
	}
	
	public function setShippingCity($city)
	{
		$this->udetails->ship_suburb_city = $city;
	}
	
	public function setShippingState($state)
	{
		if (strlen($state) > 3) {
			throw new Exception('The `shipping state` field only accepts 2 and 3 character state codes as per the ISO standard');
		}
		$this->udetails->ship_state = strtoupper($state);
	}
	
	public function setShippingCountry($country)
	{
		if (strlen($country) != 2) {
			throw new Exception('The `shipping country` field only accepts the 2 character international country codes');
		}
		$this->udetails->ship_country = strtoupper($country);
	}
	
	public function setShippingPostCode($postcode)
	{
		$this->udetails->ship_postcode = $postcode;
	}
	
	public function setBankName($name)
	{
		$this->udetails->bank_name = $name;
	}
	
	public function setBankPhoneNumber($phone)
	{
		$phone = preg_replace('![^0-9\+#]!', '', $phone);
		$this->udetails->bank_phone = $phone;
	}
	
	public function setSocialSecurityNumber($ssn)
	{
		$this->udetails->ssn = $ssn;
	}
	
	public function setDriversLicenceNumber($dl)
	{
		$this->udetails->dl = $dl;
	}
	
	public function setDateOfBirth($dob)
	{
		$dob = strtotime($dob);
		$this->udetails->dob = date('Y-m-d', $dob);
	}
	
	public function setCustomerIPAddress($ip)
	{
		if (!preg_match('!^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$!i', $ip)) {
			throw new Exception('A valid IP address is required for the `customer ip address` field');
		}
		$this->udetails->uip = $ip;
	}
	
	public function setPaymentType($type)
	{
		$this->paydetails->payby = strtolower($type);
	}
	
	public function setCardName($name)
	{
		$this->paydetails->card_name = $name;
	}
	
	public function setCardNumber($number)
	{
		$this->paydetails->card_no = preg_replace('![^0-9]!', '', $number);
		switch (substr($number, 0, 1)) {
			case '4':
				$this->setPaymentType(self::VISA);
				break;
			case '5':
				$this->setPaymentType(self::MASTERCARD);
				break;
			default:
				break;
		}
	}
	
	public function setCardCVV($cvv)
	{
		$this->paydetails->card_ccv = $cvv;
	}
	
	public function setCardExpiryMonth($month)
	{
		if ($month < 1 || $month > 12) {
			throw new Exception('Card expiry month was set outside the acceptible limits');
		}
		$this->paydetails->card_exp_month = str_pad($month, 2, '0', STR_PAD_LEFT);
	}
	
	public function setCardExpiryYear($year)
	{
		if (strlen($year) == 2) {
			$year = '20' . $year;
		}
		
		if ($year < date('Y')) {
			throw new Exception('Card expiry year has already expired');
		}
		$this->paydetails->card_exp_year = $year;
	}
	
	public function setMD($md)
	{
		$this->paydetails->md = $md;
	}
	
	public function setRedirectUrl($url)
	{
		$this->paydetails->redirecturl = $url;
	}
	
	public function setUserAgent($agent)
	{
		$this->paydetails->useragent = $agent;
	}
	
	public function setBrowserAccepts($accepts)
	{
		$this->paydetails->browseragent = $accepts;
	}
	
	public function setRoutingNumber($routing)
	{
		$this->paydetails->routing_no = preg_replace('![^0-9]!', '', $routing);
	}
	
	public function setAccountNumber($account)
	{
		$this->paydetails->account_no = preg_replace('![^0-9]!', '', $account);
	}
	
	public function setChequeType($type)
	{
		$this->paydetails->type = $type;
	}
	
	public function setRegulationE($e)
	{
		$this->paydetails->regulation_e = (int) $e;
	}
	
	public function setClass($class)
	{
		$this->paydetails->class = $class;
	}
	
	public function setReceiverName($name)
	{
		$this->paydetails->receive_name = $name;
	}
	
	public function setIssueNumber($number)
	{
		$this->paydetails->issue_no = $number;
	}
	
	public function setStartDateMonth($month)
	{
		if ($month < 1 || $month > 12) {
			throw new Exception('Start date month was set outside the acceptible limits');
		}
		$this->paydetails->start_date_month = str_pad($month, 2, '0', STR_PAD_LEFT);
	}
	
	public function setStartDateYear($year)
	{
		if (strlen($year) == 2) {
			$year = '20' . $year;
		}
		
		if ($year < date('Y')) {
			throw new Exception('Start date year has already expired');
		}
		$this->paydetails->start_date_year = $year;
	}
	
	public function setScheduleId($id)
	{
		$this->paydetails->schedule_id = $id;
	}
	
	public function addItem($name, $quantity, $amount, $sku = null, $description = null)
	{
		$this->cart->addItem($name, $quantity, $amount, $sku, $description);
	}
	
	public function setWID($wid)
	{
		$this->txparams->wid = $wid;
	}
	
	public function setTID($tid)
	{
		$this->txparams->tid = $tid;
	}
	
	public function setReference1($ref)
	{
		$this->txparams->ref1 = $ref;
	}
	
	public function setReference2($ref)
	{
		$this->txparams->ref2 = $ref;
	}
	
	public function setReference3($ref)
	{
		$this->txparams->ref3 = $ref;
	}
	
	public function setReference4($ref)
	{
		$this->txparams->ref4 = $ref;
	}
	
	public function setPostbackUrl($url)
	{
		$this->txparams->postbackurl = $url;
	}
	
	
}

abstract class Awepay_SecondaryRequest extends Awepay_ApiBase
{
	public $txid;
	
	public function setTransactionId($txid)
	{
		$this->txid = $txid;
	}
}

/* Request Containers */
class Awepay_Payment extends Awepay_PrimaryRequest
{
	protected $_url = 'https://admin.awepay.com/soap/tx3.php?wsdl';
	protected $_action = 'processPayment';
}

class Awepay_Preauth extends Awepay_PrimaryRequest
{
	protected $_url = 'https://admin.awepay.com/soap/tx3.php?wsdl';
	protected $_action = 'processPreAuth';
}

class Awepay_Settlement extends Awepay_SecondaryRequest
{
	protected $_url = 'https://admin.awepay.com/soap/tx3.php?wsdl';
	protected $_action = 'processSettlement';
}

class Awepay_Refund extends Awepay_SecondaryRequest
{
	protected $_url = 'https://admin.awepay.com/soap/tx3.php?wsdl';
	protected $_action = 'processRefund';
	
	public $txid;
	public $reason;
	public $amount;
	public $postbackurl;
	public $sendNotification = 0;
	
	public function setTransactionId($txid)
	{
		$this->txid = $txid;
	}
	
	public function setReason($reason)
	{
		$this->reason = $reason;
	}
	
	public function setAmount($amount)
	{
		$this->amount = sprintf('%01.2f', $amount);
	}
	
	public function setPostbackUrl($url)
	{
		$this->postbackurl = $url;
	}
	
	public function setSendNotification($send)
	{
		$this->sendNotification = $send;
	}
}

class Awepay_Query extends Awepay_ApiBase
{
	const ACTION_PAYMENT = 'PAYMENT';
	const ACTION_PREAUTH = 'PREAUTH';
	const ACTION_SETTLEMENT = 'SETTLEMENT';
	const ACTION_VOID = 'VOID';
	const ACTION_REFUND = 'REFUND';
	const ACTION_CHARGEBACK = 'CHARGEBACK';
	
	const STATUS_APPROVED = 'APPROVED';
	const STATUS_DECLINED = 'DECLINED';
	const STATUS_PENDING = 'PENDING';
	const STATUS_ERROR = 'PAYG_ERROR';
	
	protected $_url = 'https://admin.awepay.com/soap/tx3.php?wsdl';
	protected $_action = 'getTransactionsBySID';
	protected $_status;
	protected $_response = array();
	public $from_timestamp;
	public $to_timestamp;
	public $tx_actions;
	public $responses;
	public $card_types;
	public $filter_type;
	public $filter_search;
	public $limit;

	public function setFromDate($date)
	{
		$this->from_timestamp = date('YmdHis', strtotime($date));
	}
	
	public function setToDate($date)
	{
		$this->to_timestamp = date('YmdHis', strtotime($date));
	}
	
	public function setQueryActions($types)
	{
		$this->tx_actions = (array)$types;
	}
	
	public function setQueryStatuses($statuses)
	{
		$this->responses = (array)$statuses;
	}
	
	public function setSearchFilters($field, $value)
	{
		$this->filter_type = $field;
		$this->filter_search = $value;
	}
	
	public function setLimit($limit)
	{
		$this->limit = $limit;
	}
	
	public function parseResponse($response)
	{
		$response = $this->object2array($response);
		$this->_status = $response['status'];
		if ($response['status'] != 'OK') {
			return;
		}
		$this->_response['total'] = str_replace(' transactions returned', '', $response['error']['msg']);
		$this->_response['transactions'] = array();
		foreach ($response['txs'] as $tx) {
			$entry = new Awepay_QueryResponseElement();
			$entry->fromArray($tx);
			$this->_response['transactions'][] = $entry;
		}
	}
	
	public function getTotalRecords()
	{
		return $this->_response['total'];
	}
	
	public function getTransactions()
	{
		if ($this->_status != 'OK') {
			return false;
		}
		return $this->_response['transactions'];
	}
	
	public function validate()
	{
		if (!$this->from_timestamp) {
			$this->addError('From date is required');
		}
		if (!$this->to_timestamp) {
			$this->addError('To date is required');
		}
		if (!$this->limit) {
			$this->addError('Limit is required');
		}
		if (!empty($this->_errors)) {
			return false;
		}
		return true;
	}

}

/* Complex Types */
class Awepay_UserDetails extends Awepay_ApiElementBase
{
	
	public $username;
	public $password;
	public $firstname;
	public $lastname;
	public $email;
	public $phone;
	public $mobile;
	public $address;
	public $suburb_city;
	public $state;
	public $country;
	public $postcode;
	public $ship_firstname;
	public $ship_lastname;
	public $ship_address;
	public $ship_suburb_city;
	public $ship_state;
	public $ship_country;
	public $ship_postcode;
	public $bank_name;
	public $bank_phone;
	public $ssn;
	public $dl;
	public $dob;
	public $uip;
	
	public function __construct()
	{
		$this->uip = $_SERVER['REMOTE_ADDR'];
	}
	
	protected function validate()
	{
		$required_fields = array('firstname', 'lastname', 'email', 'phone', 'address', 'suburb_city', 'state', 'country', 'postcode', 'uip');
		foreach ($required_fields as $field) {
			if (!$this->$field) {
				$this->addError('`' . $field . '` is required.');
			}
		}
		if (!empty($this->_errors)) {
			return false;
		}
		return true;
	}
	
	public function toArray()
	{
		if (!$this->ship_address || !$this->ship_suburb_city || !$this->ship_state || !$this->ship_country || !$this->ship_postcode || !$this->ship_firstname || !$this->ship_lastname) {
			$this->ship_firstname = $this->firstname;
			$this->ship_lastname = $this->lastname;
			$this->ship_address = $this->address;
			$this->ship_suburb_city = $this->suburb_city;
			$this->ship_state = $this->state;
			$this->ship_country = $this->country;
			$this->ship_postcode = $this->postcode;
		}
		return parent::toArray();
	}
	
}

class Awepay_PaymentDetails extends Awepay_ApiElementBase
{
	
	public $payby;
	public $card_name;
	public $card_no;
	public $card_ccv;
	public $card_exp_month;
	public $card_exp_year;
	public $md;
	public $redirecturl;
	public $useragent;
	public $browseragent;
	public $routing_no;
	public $account_no;
	public $type;
	public $regulation_e;
	public $class;
	public $receive_name;
	public $issue_no;
	public $start_date_month;
	public $start_date_year;
	public $schedule_id;
	
	public function validate()
	{
		if ($this->payby == 'ach' || $this->payby == 'check21') {
			$required_fields = array('routing_no', 'account_no', 'type', 'regulation_e', 'class', 'receive_name');
		} else {
			$required_fields = array('card_name', 'card_no', 'card_ccv', 'card_exp_month', 'card_exp_year');
		}
		foreach ($required_fields as $field) {
			if (!$this->$field) {
				$this->addError('`' . $field . '` is required.');
			}
		}
		if (!empty($this->_errors)) {
			return false;
		}
		return true;
	}
	
}

class Awepay_Cart extends Awepay_ApiElementBase
{
	
	public $summary;
	public $items = array();
	
	public function __construct()
	{
		$this->summary = new Awepay_CartSummary();
	}
	
	protected function validate()
	{
		if (empty($this->items)) {
			$this->addError('Must have at least 1 cart item.');
			return false;
		}
		foreach ($this->items as $idx => $item) {
			if (!$item->validate()) {
				$this->addErrors('CartItem[' . $idx . ']', $item->getErrors());
			}
		}
		if (!empty($this->_errors)) {
			return false;
		}
		return true;
	}
	
	public function addItem($name, $quantity, $amount, $sku = null, $description = null)
	{
		$item = new Awepay_CartItem();
		$item->name = $name;
		$item->quantity = $quantity;
		$item->amount_unit = $amount;
		$item->item_no = $sku;
		$item->item_desc = $description;
		$this->items[] = $item;
		
		$this->summary->amount_purchase += ($quantity * $amount);
		$this->summary->quantity += $quantity;
	}
	
}

class Awepay_CartSummary extends Awepay_ApiElementBase
{
	
	public $quantity = 0;
	public $amount_purchase = 0.00;
	public $amount_shipping = 0.00;
	public $currency_code = 'USD';
	
}

class Awepay_CartItem extends Awepay_ApiElementBase
{
	
	public $name;
	public $quantity;
	public $amount_unit;
	public $item_no;
	public $item_desc;
	
	protected function validate()
	{
		$required_fields = array('name', 'quantity', 'amount_unit');
		foreach ($required_fields as $field) {
			if (!$this->$field) {
				$this->addError('`' . $field . '` is required.');
			}
		}
		if (!empty($this->_errors)) {
			return false;
		}
		return true;
	}
	
}

class Awepay_TransactionParameters extends Awepay_ApiElementBase
{
	
	public $wid;
	public $tid;
	public $ref1;
	public $ref2;
	public $ref3;
	public $ref4;
	public $addinfo;
	public $ipayinfo;
	public $cmd;
	public $postbackurl;
	
}

class Awepay_QueryResponseBase
{
	
	public function fromArray($array)
	{
		foreach ($array as $k => $item) {
			if (is_array($item)) {
				foreach ($item as $key => $value) {
					if (property_exists($this, $key)) {
						$this->{$key} = $value;
					}
				}
			} else {
				if (property_exists($this, $k)) {
					$this->{$k} = $item;
				}
			}
		}
	}
	
}

class Awepay_QueryResponseElement extends Awepay_QueryResponseBase
{
	public $txid;
	public $sid;
	public $card_type;
	public $amount_purchase;
	public $amount_shipping;
	public $currency_code;
	public $card_name;
	public $card_no;
	public $email;
	public $firstname;
	public $lastname;
	public $uip;
	public $ref1;
	public $ref2;
	public $ref3;
	public $ref4;
	public $site_name;
	public $tx_history = array();
	
	public function fromArray($array)
	{
		parent::fromArray($array);
		foreach ($array['txhistory'] as $item) {
			$entry = new Awepay_QueryResponseHistoryElement();
			$entry->fromArray($item);
			$this->tx_history[] = $entry;
		}
	}
	
	public function getTransactionId()
	{
		return $this->txid;
	}
	
	public function getSid()
	{
		return $this->sid;
	}
	
	public function getCardType()
	{
		return $this->card_type;
	}
	
	public function getTotalAmount()
	{
		return sprintf('%01.2f', $this->amount_purchase + $this->amount_shipping);
	}
	
	public function getPurchaseAmount()
	{
		return sprintf('%01.2f', $this->amount_purchase);
	}
	
	public function getShippingAmount()
	{
		return sprintf('%01.2f', $this->amount_shipping);
	}
	
	public function getCurrencyCode()
	{
		return $this->currency_code;
	}
	
	public function getCardName()
	{
		return $this->card_name;
	}
	
	public function getCardNumber()
	{
		return $this->card_no;
	}
	
	public function getCustomerEmail()
	{
		return $this->email;
	}
	
	public function getCustomerName()
	{
		return $this->firstname . ' ' . $this->lastname;
	}
	
	public function getCustomerFirstName()
	{
		return $this->firstname;
	}
	
	public function getCustomerLastName()
	{
		return $this->lastname;
	}
	
	public function getCustomerIp()
	{
		return $this->uip;
	}
	
	public function getReference1()
	{
		return $this->ref1;
	}
	
	public function getReference2()
	{
		return $this->ref2;
	}
	
	public function getReference3()
	{
		return $this->ref3;
	}
	
	public function getReference4()
	{
		return $this->ref4;
	}
	
	public function getSiteName()
	{
		return $this->site_name;
	}
	
	public function getTransactionActions()
	{
		return $this->tx_history;
	}
	
}

class Awepay_QueryResponseHistoryElement extends Awepay_QueryResponseBase
{
	
	public $txid;
	public $amount;
	public $tx_action;
	public $timestamp;
	public $response;
	public $comment;

	public function getTransactionId()
	{
		return $this->txid;
	}
	
	public function getAmount()
	{
		return sprintf('%01.2f', $this->amount);
	}
	
	public function getAction()
	{
		return $this->tx_action;
	}
	
	public function getTimestamp()
	{
		return $this->timestamp;
	}
	
	public function getStatus()
	{
		return $this->response;
	}
	
	public function getErrorMessage()
	{
		if (!$this->comment || empty($this->comment) || $this->comment == '-') {
			return null;
		}
		return $this->comment;
	}
	
}