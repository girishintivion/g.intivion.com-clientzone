<?php
require_once(APPPATH.'libraries/requestparam.php');
class Deposite_response_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        
        public function payment_result($seller_ref)
        {
        	$this->db->select('*');
        	$this->db->from('payment_status');
        	$this->db->where('transaction_id', $seller_ref);
        	return $this->db->get()->row();
        
        }
        
        
        public function update_payment_result($tnx_amount,$seller_ref)
        {
        
        	$data = array(
        			'status' =>"Success",
        			'amount' => $tnx_amount,
        			 
        
        
        	);
        
        	$this->db->where('transaction_id', $seller_ref);
        	$this->db->update('payment_status', $data);
        
        }
        
        
        public function tnx_status_declined($tnx_amount,$seller_ref)
        {
        
        	$data = array(
        			'status' =>"Fail",
        			'amount' => $tnx_amount,
        			
        	);
        
        	$this->db->where('transaction_id', $seller_ref);
        	$this->db->update('payment_status', $data);
        
        }
        
        
        public function tnx_status_pending($tnx_amount,$seller_ref)
        {
        
        	$data = array(
        			'status' =>"Pending",
        			'amount' => $tnx_amount,
        			 
        	);
        
        	$this->db->where('transaction_id', $seller_ref);
        	$this->db->update('payment_status', $data);
        
        }
        
        
        public function user_payment_result($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row();
        
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
