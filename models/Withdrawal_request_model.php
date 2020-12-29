<?php
require_once(APPPATH.'libraries/requestparam.php');
class Withdrawal_request_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
       
        
        public function get_current_user_email($username)
        {
        	$this->db->select('email');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('email');
        	 
        }
        
        
         public function get_current_user_data($user_email)
        {
         $this->db->select('*');
         $this->db->from('crm_user');
         $data = array(
           'email' =>$user_email,
           'trading_platform' =>'REAL',
            
         
         
         );
         
         
         $this->db->where($data);
        
         $query = $this->db->get();
         return $result = $query->result();
        
        }
        
        
        public function get_user_account_id($acc)
        {
        	$this->db->select('account_id');
        	$this->db->from('crm_user');
        	$this->db->where('name', $acc);
        	return $this->db->get()->row('account_id');
        
        }
		
		public function get_user_tradingplatform_id($acc)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $acc);
        	return $this->db->get()->row();
        
        }
        
        public function get_user_emailid($acc)
        {
        	$this->db->select('email');
        	$this->db->from('crm_user');
        	$this->db->where('name', $acc);
        	return $this->db->get()->row('email');
        
        }
        
        public function get_user_currency_id($acc)
        {
        	$this->db->select('currency_id');
        	$this->db->from('crm_user');
        	$this->db->where('name', $acc);
        	return $this->db->get()->row('currency_id');
        
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
