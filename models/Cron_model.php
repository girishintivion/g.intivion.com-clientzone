<?php
require_once(APPPATH.'libraries/requestparam.php');
class Cron_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
       
        public function get_all_crm_account()
        {
        	$this->db->select('*');
        	$this->db->from('crm_account');
        	$this->db->where('status', '0');
        	$query = $this->db->get();
        	 
        	return $result = $query->result();
        	
        }
       
        
        public function get_all_traders_files()
        {
        	$this->db->select('*');
        	$this->db->from('traders_files');
        	$this->db->where('status', '0');
        	$query = $this->db->get();
        
        	return $result = $query->result();
        	 
        }
        
        public function get_all_corporate_account()
        {
        	$this->db->select('*');
        	$this->db->from('corporate_account');
        	$this->db->where('status', '0');
        	$query = $this->db->get();
        
        	return $result = $query->result();
        
        }
        
        public function update_status($tp_account_id,$status)
        {
        
        	$data = array(
        			'status' =>$status,
        			 
        
        
        	);
        
        	$this->db->where('tp_account_id', $tp_account_id);
        	$this->db->update('crm_account', $data);
        
        }
        
        
        public function update_corporate_status($tp_account_id,$status)
        {
        
        	$data = array(
        			'status' =>$status,
        
        
        
        	);
        
        	$this->db->where('tp_account_id', $tp_account_id);
        	$this->db->update('corporate_account', $data);
        
        }
        
        public function update_traders_files_status($file_name,$status)
        {
        
        	$data = array(
        			'status' =>$status,
        
        
        
        	);
        
        	$this->db->where('file_name', $file_name);
        	$this->db->update('traders_files', $data);
        
        }
        
        
        public function create_crmuser($tradingPlatformAccountName,$tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$currency_id,$currency_code,$accountId,$tradingPlatformAccountId,$date,$account_type,$platform_type)
        {
        
        	$data = array(
        			//'title' => $this->input->post('title'),
        			//'slug' => $slug,
        			'firstname' => $this->input->post('firstname'),
        			'lastname' => $this->input->post('lastname'),
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
        			 'account_type'=> $account_type,
        			 'platform'=> $this->$platform_type,
        			 'phone'=> $this->input->post('phone'),
        			 'birth_date'=>$date,
        			
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
