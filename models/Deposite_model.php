<?php
require_once(APPPATH.'libraries/requestparam.php');
class Deposite_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        
        public function insert_to_praxis_account($pin,$full_name,$address,$city,$Zip,$countryCode,$phone,$email,$bd,$gender,$ip_address,$trans_refNum,$state)
        {
        
        	$data = array(
        			'PIN' =>  $pin,
        			'CustName' => $full_name,
        			'Street' => $address,
        			'City' => $city,
        			'PCode' => $Zip,
        			'Country' => $countryCode,
        			'Phone' => $phone,
        			'Email' => $email,
        			'DOB' => $bd,
        			'Gender' => $gender,
        			'ip_address' => $ip_address,
        			'session_id' => $trans_refNum,
        			'province' => $state,
        	);
        
        	$this->db->insert('praxis_account', $data);
        }
        
        
        public function get_phone_code_depo($country_code)
        {
        	$query = $this->db->get_where('countries_country', array('iso2' => $country_code));
        	return $query->row_array();
        }
        
        
        public function get_current_user_email($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row();
        
        }
        public function update_status_in_payment($TransactionID,$ErrCode,$ExErrCode,$Message,$ourTransactionID)
        {
        
        	$data = array(
        			'status' =>"Failure",
        			'gateway_transaction_id' => $TransactionID,
        			'ErrCode' => $ErrCode,
        			'ExErrCode' => $ExErrCode,
        			'Reason' => $Message,
        			 
        
        	);
        
        	$this->db->where('transaction_id', $ourTransactionID);
        	$this->db->update('payment_status', $data);
        
        }
        public function update_notification($dmn,$ourTransactionID)
        {
        
        	$data = array(
        			'psp'=>"Safecharge",
        			'notification' => $dmn,
        			'transaction_id'=>$ourTransactionID,
        
        
        	);
        
        	$this->db->insert('notifications', $data);
        }
        
        public function insert_notification_inatech($dmn,$ourTransactionID)
        {
        
        	$data = array(
        			'psp'=>"inatech",
        			'notification' => $dmn,
        			'transaction_id'=>$ourTransactionID,
        
        
        	);
        
        	$this->db->insert('notifications', $data);
        }
        
        public function get_details_from_crm_user($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row();
        
        }
        
        public function get_details_from_praxis_account($username)
        {
        	$this->db->select('*');
        	$this->db->from('praxis_account');
        	$this->db->where('PIN', $username);
        	return $this->db->get()->row();
        
        }
        
        public function update_success($ourTransactionID)
        {
        
        	$data = array(
        			'status' =>"Success",
        			//'card_num' => $card_Number,
        
        
        	);
        
        	$this->db->where('transaction_id', $ourTransactionID);
        	$this->db->update('payment_status', $data);
        
        }
        
        public function update_fail($ourTransactionID)
        {
        
        	$data = array(
        			'status' =>"fail",
        
        
        	);
        
        	$this->db->where('transaction_id', $ourTransactionID);
        	$this->db->update('payment_status', $data);
        
        }
        
        public function update_payment_status_details($serial_number,$paynet_order_id,$transaction)
        {
        
        	$data = array(
        			'paynet_order' => $paynet_order_id,
        			'serial_number' => $serial_number,
        			
        			
        			 
        			 
        			 
        	);
        	 
        	$this->db->where('transaction_id', $transaction);
        	$this->db->update('payment_status', $data);
        
        }
        
        public function get_records($ourTransactionID)
        {
        	$this->db->select('*');
        	$this->db->from('payment_status');
        	$this->db->where('transaction_id', $ourTransactionID);
        	return $this->db->get()->row();
        
        }
        public function get_records_from_crm_user($tp_account_id)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $tp_account_id);
        	return $this->db->get()->row();
        
        }
        
        public function check_status_notification($ourTransactionID)
        {
        	$this->db->select('status');
        	$this->db->from('payment_status');
        	$this->db->where('transaction_id', $ourTransactionID);
        	return $this->db->get()->row('status');
        
        }
        
 public function get_lang_code($Gateway_TransactionID)
        {
        	$this->db->select('*');
        	$this->db->from('payment_status');
        	$this->db->where('transaction_id', $Gateway_TransactionID);
        	return $this->db->get()->row('lang_code');
        	 
        }
        
        public function add_payment_details($firstname,$lastname,$email,$checksum,$amount,$currency,$datetime,$phone,$address,$country,$zip,$language,$invoice_id,$ip,$city)
        {
        
        	$data = array(
								'tp_account_id' =>  $_SESSION['username'],
								'firstname' => $firstname,
								'lastname' => $lastname,
								'email' => $email,
								'provider' => "safecharge",
								'status' => "Processing",
								'checksum' => $checksum,
								'amount' => $amount,
								'currency' => $currency,
								'time_stamp' => $datetime,
							//	'trans_date' => $datetime,
								'telephone' => $phone,
								'address' => $address,
								'country' => $country,
								'postcode' => $zip,
								'lang_code' => $language,
								'transaction_id' => $invoice_id,
								'ipaddress' => $ip,
								'type' => "Credit Card Deposit",
								'city' => $city,
								
			 
        				);
        		
        				$this->db->insert('payment_status', $data);
        }
        
        
        
        
        
        public function add_payment_details_inatech($acc,$firstname,$lastname,$email,$amount,$currency,$datetime,$address,$country,$zip,$invoice_id,$ip,$city,$card_num,$date_month,$date_year)
        {
        
        	$data = array(
        			'tp_account_id' =>  $acc,
        			'firstname' => $firstname,
        			'lastname' => $lastname,
        			'email' => $email,
        			'provider' => "inatech",
        			'status' => "Processing",
        			//'checksum' => $checksum,
        			'amount' => $amount,
        			'currency' => $currency,
        			'time_stamp' => $datetime,
        			//	'trans_date' => $datetime,
        			//'telephone' => $phone,
        			'address' => $address,
        			'country' => $country,
        			'postcode' => $zip,
        			//'lang_code' => $language,
        			'transaction_id' => $invoice_id,
        			'ipaddress' => $ip,
        			//'type' => "Credit Card Deposit",
        			'city' => $city,
        			
        			
        			'card_num' => $card_num,
        			'expire_month' => $date_month,
        		
        			'expire_year' => $date_year,
        
        
        	);
        
        	$this->db->insert('payment_status', $data);
        }
        
        
       
        /**
         * hash_password function.
         *
         * @access private
         * @param mixed $password
         * @return string|bool could be a string on success, or bool false on failure
         */
        private function hash_password($password) {
        
        	return password_hash($password, PASSWORD_BCRYPT);
        
        }
        
}