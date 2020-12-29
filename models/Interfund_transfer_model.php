<?php
require_once(APPPATH.'libraries/requestparam.php');
class Interfund_transfer_model extends CI_Model {

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
        	$array = array('email' => $user_email, 'trading_platform' => 'REAL');
        	
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	
        	
        	$this->db->where($array);
        
        	$query = $this->db->get();
        	return $result = $query->result();
        
        }
        
        
        public function get_user_tradingPlatformAccountId($accno)
        {
        	$this->db->select('trading_platform_accountid');
        	$this->db->from('crm_user');
        	$this->db->where('name', $accno);
        	return $this->db->get()->row('trading_platform_accountid');
        
        }
        
        public function get_user_oppositeAccountId($accno_two)
        {
        	$this->db->select('trading_platform_accountid');
        	$this->db->from('crm_user');
        	$this->db->where('name', $accno_two);
        	return $this->db->get()->row('trading_platform_accountid');
        
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
