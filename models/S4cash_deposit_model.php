<?php
require_once(APPPATH.'libraries/requestparam.php');
class S4cash_deposit_model extends CI_Model {

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
        
        public function update_notification($payment_gateway,$notification,$psp_request,$url_trans)
        {
        	$data = array(      
        			 'psp'=>$payment_gateway,
        			 'notification' =>$notification,
        			 'psp_request'=>$psp_request,
        			 'transaction_id'=>$url_trans,
        	
        			);
        	$this->db->insert('notifications', $data);
        }
        
        public function update_notification1($payment_gateway,$notification,$transaction)
        {
        	$data = array(
        			'psp'=>$payment_gateway,
        			'notification' =>$notification,
        			 
        	);
        	$this->db->where('transaction_id', $transaction);
        	$this->db->update('notifications', $data);
        }
        
        public function update_payment_status($status,$transaction)
        {
        
        	$data = array(
        			 
        			'status' => $status,
        	);
        
        	$this->db->where('transaction_id', $transaction);
        	$this->db->update('payment_status', $data);
        
        }
        
        public function get_details_payment_status($transaction_id)
        {
        	$this->db->select('*');
        	$this->db->from('payment_status');
        	$this->db->where('transaction_id', $transaction_id);
        	return $this->db->get()->row();
        
        }
//add_payment_details($firstname,$lastname,$email,$country,$phone_country_code,$phone,$currency,$address,$city,$zip,$currency,$trading_platform_accountid,$transaction,$amount);
        			        
        public function add_payment_details($firstname,$lastname,$email,$country,$phone,$currency,$address,$city,$zip,$trading_platform_accountid,$transaction,$amount)
        {
        
        	$data = array(
        						'firstname' => $firstname,
        						'lastname' => $lastname,
        						'tp_account_id'=> $trading_platform_accountid,
        						'provider'=> "S4cash",
        						'status'=> "Processing",
        						'transaction_id'=> $transaction,
        						'amount'=> $amount,
        						'currency'=> $currency,
        						'email'=> $email,
        					//	'ipaddress'=> $ip,
			        			'country' => $country,
			        			'telephone' => $phone,
			        			'address' => $address,
			        			'postcode' => $zip,
			        			'city' => $city,
        		
			 
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
