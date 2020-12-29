<?php
require_once(APPPATH.'libraries/requestparam.php');
class Monetary_statement_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
       
        
        public function get_current_user_email($username)
        {
        	$this->db->select('account_id');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('account_id');
        	 
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
