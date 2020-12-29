<?php
require_once(APPPATH.'libraries/requestparam.php');
class Real_exist_model extends CI_Model {

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
		
		 public function get_current_result_data($username)
        {
        	$query = $this->db->get_where('crm_user', array('name' => $username));
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
        			'user_role'  => "REAL",
        	);
        	$this->db->insert('users_ci', $userinfo);
        
        }
        
        public function create_crmuser($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$date,$firstname,$lastname,$email,$country,$phone_country_code,$phone,$account_type,$platformtype)
        {
        
        	$data = array(
        						//'title' => $this->input->post('title'),
        						//'slug' => $slug,
        						'firstname' => $firstname,
        						'lastname' => $lastname,
        						'country'=> $country,
        						'trading_platform'=> "REAL",
        						'account_type'=> $account_type,
        						'currency'=> $currency_code,
        						'platform'=>$platformtype,
        						'secret_data'=> $tradingPlatformAccountPassword,
        						'phone_country_code'=> $phone_country_code,
        						'phone'=> $phone,
        						'birth_date'=>$date,
        						'email' => $email,
        						'trading_platform_accountid'=>$tradingPlatformAccountId,
        						'account_id'=>$accountId,
        						'business_unit'=>$BUSINESS_UNIT_NAME,
        						'currency_id'=>$currency_id,
        						'currency_code'=>$currency_code,
        						'name'=>$tradingPlatformAccountName,
        						 
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
