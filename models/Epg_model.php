<?php
require_once(APPPATH.'libraries/requestparam.php');
class Epg_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        
       /* public function get_current_user_email($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row();
        
        }*/
        
        public function get_details_from_crm_user($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row();
        
        }
        
        /*
        public function update_payment_status_details($serial_number,$paynet_order_id,$transaction)
        {
        
        	$data = array(
        			'pay_net_order' => $paynet_order_id,
        			'serial_number' => $serial_number,
        			 
        	);
        	 
        	$this->db->where('transaction_id', $transaction);
        	$this->db->update('payment_status', $data);
        
        }
        */
        
        
        public function add_payment_details($firstname,$lastname,$email,$checksum,$amount,$currency,$phone,$address1,$country,$zip,$language,$trans_refNum,$ip,$city)
       
        {
        
        	$data = array(
        						'tp_account_id' =>  $_SESSION['username'],
			'firstname' => $firstname,
			'lastname' => $lastname,
			'email' => $email,
			'provider' => "easy_payments",
			'status' => "Processing",
			'checksum' => $checksum,
			'amount' => $amount,
			'currency' => $currency,
			'time_stamp' => $time_stamp,
			'trans_date' => $datetime,
			'telephone' => $phone,
			'address' => $address1,
			'country' => $country,
			'postcode' => $zip,
			'lang_code' => $language,
			'transaction_id' => $trans_refNum,
			'ipaddress' => $ip,
			'type' => "Credit Card Deposit",
			'city' => $city
			 
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
