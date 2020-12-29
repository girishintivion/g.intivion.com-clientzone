<?php
require_once(APPPATH.'libraries/requestparam.php');
class Demo_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        
        public function get_crm_details($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	
        	$query = $this->db->get();
        	return $result = $query->result();
        	
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
        public function get_password($username)
        {
        			$this->db->select('secret_data');
		$this->db->from('crm_user');
		$this->db->where('name', $username);
		return $this->db->get()->row('secret_data');
     
        }
        public function create_user($username,$password,$email)
        {
        	$userinfo = array(
        			'username'   => $username,
        			'email'      => $email,
        			'password'   => $this->hash_password($password),
        			'created_at' => date('Y-m-j H:i:s'),
        			'user_role'  => "DEMO",
        	);
        	$this->db->insert('users_ci', $userinfo);

        }
        public function create_crmuser($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$acctype,$platformtype,$city,$address,$firstname,$lastname)
        {
        
        	$data = array(
        			//'title' => $this->input->post('title'),
        			//'slug' => $slug,
        			'firstname' => $firstname,
        			'lastname' => $lastname,
        			'email' => $this->input->post('email'),
        			'phone'=> $this->input->post('phone'),
        			'name'=>$tradingPlatformAccountName,
        			'business_unit'=>$BUSINESS_UNIT_NAME,
        			'currency_id'=>$currency_id,
        			'currency'=> $currency_code,
        			'secret_data'=> $tradingPlatformAccountPassword,
        			'country'=> $this->input->post('country'),
        			'phone_country_code'=> $this->input->post('country_code'),
        			'trading_platform'=> "DEMO",
        			'currency_code'=>$currency_code,
        			'account_id'=>$accountId,
        			'trading_platform_accountid'=>$tradingPlatformAccountId,
        			'account_type'=>$acctype,
        			'platform'=> $platformtype,
        			'city'=>$city,
        			'address1'=> $address,
        	//		 'birth_date'=>$date,
        			
        	);
        	
        	$this->db->insert('crm_user', $data);
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
