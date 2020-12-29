<?php
require_once(APPPATH.'libraries/requestparam.php');
class Solid_deposit_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        
        public function get_current_user_email($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row();
        
        }
        
        public function get_details_from_payment_status($ourTransactionID)
        {
        	$this->db->select('*');
        	$this->db->from('payment_status');
        	$this->db->where('transaction_id', $ourTransactionID);
        	return $this->db->get()->row();
        
        }
        
        public function get_details_from_crm_user($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row();
        
        }
        
        public function update_payment_status_details($serial_number,$paynet_order_id,$transaction)
        {
        
        	$data = array(
        			'pay_net_order' => $paynet_order_id,
        			'serial_number' => $serial_number,
        			
        			
        			 
        			 
        			 
        	);
        	 
        	$this->db->where('transaction_id', $transaction);
        	$this->db->update('payment_status', $data);
        
        }
        
        
        
        public function add_payment_details($firstname,$lastname,$email,$country,$phone,$transaction_id,$currency,$ip,$amount)
        {
        
        	$data = array(
        						'firstname' => $firstname,
        						'lastname' => $lastname,
        						'tp_account_id'=> $_SESSION['username'],
        						'provider'=> "solid payment",
        						'status'=> "Processing",
        						'transaction_id'=> $transaction_id,
        						'amount'=> $amount,
        						'currency'=> $currency,
        						'email'=> $email,
        						'ipaddress'=> $ip,
        			'country' => $country,
        			'telephone' => $phone,
        			'address' => $street,
        			'postcode' => $zip,
        			'city' => $city,
			 
        				);
        		
        				$this->db->insert('payment_status', $data);
        }
        
        
        public function add_dmn_in_notifications($psp,$newstring,$ourTransactionID)
        {
        
        	$data = array(
        			'psp' => $psp,
        			'notification' => $newstring,
        			'transaction_id'=> $ourTransactionID,
      
        	);
        
        	$this->db->insert('notifications', $data);
        }
        
        
        public function update_payment_status($status,$ourTransactionID)
        {
        
        	$data = array(
        			'status' => $status 
        	);
        	 
        	$this->db->where('transaction_id', $ourTransactionID);
        	$this->db->update('payment_status', $data);
        
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
