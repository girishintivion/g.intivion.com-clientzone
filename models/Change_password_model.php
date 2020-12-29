<?php
require_once(APPPATH.'libraries/requestparam.php');
class Change_password_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
       
        
       
        public function update_crmuser($username,$password)
        {
        
        	$data = array(
        			'secret_data' =>$password,
        			 
        	);
        	 
        	$this->db->where('name', $username);
        	$this->db->update('crm_user', $data);
        
        }
        
        public function get_password($username)
        {
        	$this->db->select('secret_data');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('secret_data');
        	
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
