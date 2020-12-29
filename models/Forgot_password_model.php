<?php
require_once(APPPATH.'libraries/requestparam.php');
class Forgot_password_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
       
        
       
        public function get_account_email($accname)
        {
        	$this->db->select('email');
        	$this->db->from('crm_user');
        	$this->db->where('name', $accname);
        	return $this->db->get()->row('email');
        
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
