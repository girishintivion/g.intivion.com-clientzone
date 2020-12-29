<?php
require_once(APPPATH.'libraries/requestparam.php');
class Admin_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function get_password($username)
        {
        			$this->db->select('secret_data');
		$this->db->from('crm_user');
		$this->db->where('name', $username);
		return $this->db->get()->row('secret_data');
     
        }
        public function create_user($username,$password)
        {
        	$userinfo = array(
        			'username'   => $this->input->post('username'),
        			'email'      => $this->input->post('email'),
        			'password'   => $this->hash_password($this->input->post('password')),
        			'created_at' => date('Y-m-j H:i:s'),
        			'user_role'  => "Admin",
        	);
        	$this->db->insert('users_ci', $userinfo);

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
