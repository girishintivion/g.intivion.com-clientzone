<?php
require_once(APPPATH.'libraries/requestparam.php');
class Personal_details_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        public function get_countries()
        { 	
        	$query = $this->db->get('countries_country');
        	return $query->result_array();
        }
        public function get_phone_code($country_code)
        {
        	$query = $this->db->get_where('countries_country', array('iso2' => $country_code));
        	return $query->row_array();
        }
        
        public function get_current_user_email($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row();
        
        }
        
        public function get_ccode($username)
        {
        	$this->db->select('country');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('country');
        
        }
        
        public function get_country_name($country_code)
        {
        	$this->db->select('name');
        	$this->db->from('countries_country');
        	$this->db->where('iso2', $country_code);
        	return $this->db->get()->row('name');
        
        }
        
        public function get_account_id($username)
        {
        	$query = $this->db->get_where('crm_user', array('name' => $username));
        	return $query->row_array();
        }
       
        
       
        
        
        public function update_crmuser($username)
        {
         
        	$data = array(
        			'firstname' => $this->input->post('firstname'),
        			'lastname' => $this->input->post('lastname'),
        			'country' => $this->input->post('country'),
        			'phone_country_code' => $this->input->post('country_code'),
        			'phone' => $this->input->post('phone'),
        			'email' => $this->input->post('email'),
        			'address1' => $this->input->post('address'),
        			'city' => $this->input->post('city'),
        		    'mobile' => $this->input->post('mobile'),
        			
        			
        			
        	);
        	
        	$this->db->where('name', $username);
        	$this->db->update('crm_user', $data);
        
        }
        
        public function update_crmuser2($username,$date)
        {
        	
        	$data = array(
        			'firstname' => trim($this->input->post('firstname')," "),
        			'lastname' => trim($this->input->post('lastname')," "),
        			'country' => $this->input->post('country'),
        			'phone_country_code' => $this->input->post('country_code'),
        			'phone' => trim($this->input->post('phone')," "),
        			'email' => trim($this->input->post('email')," "),
        			'address1' => $this->input->post('address'),
        			'city' => $this->input->post('city'),
        			'mobile' => trim($this->input->post('mobile')," "),
        			'birth_date'=> $date,
        			
        			
        			
        	);
        	
        	$this->db->where('name', $username);
        	$this->db->update('crm_user', $data);
        	
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
