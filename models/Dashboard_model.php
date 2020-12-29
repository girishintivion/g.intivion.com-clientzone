<?php
require_once(APPPATH.'libraries/requestparam.php');
class Dashboard_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function get_current_user_accid($username)
        {
        	$this->db->select('account_id');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('account_id');
        
        }
        
        
        public function get_current_user_email($username)
        {
        	$this->db->select('email');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('email');
        	 
        }
        
        public function get_current_user_group($username)
        {
        	$this->db->select('trading_platform');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('trading_platform');
        
        }
        
        public function get_current_user_trid($username)
        {
        	$this->db->select('trading_platform_accountid');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('trading_platform_accountid');
        
        }
        
        public function get_current_user_trplatform($username)
        {
        	$this->db->select('trading_platform');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('trading_platform');
        
        }
        
        
        public function get_current_user_data($user_email)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('email', $user_email);
        
        	$query = $this->db->get();
        	return $result = $query->result();
        
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
