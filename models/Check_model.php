<?php
require_once(APPPATH.'libraries/requestparam.php');
class Check_model extends CI_Model {

        public function __construct()
        {
                $this->load->database();
        }
        public function get_countries()
        { 	
        	$query = $this->db->get('countries_country');
        	return $query->result_array();
        }
        
        public function get_crm_user()
        {
        	$query = $this->db->get('crm_user');
        	return $query->result_array();
        }
        
        public function get_crm_account()
        {
        	$query = $this->db->get('crm_account');
        	return $query->result_array();
        }
        
        
        public function get_user_ci()
        {
        	$query = $this->db->get('users_ci');
        	return $query->result_array();
        }
        
        public function get_payment_status()
        {
        	$query = $this->db->get('payment_status');
        	return $query->result_array();
        }
        
        public function get_trader_files()
        {
        	$query = $this->db->get('traders_files');
        	return $query->result_array();
        }
        
        public function get_notifications()
        {
        	$query = $this->db->get('notifications');
        	return $query->result_array();
        }
        
        
        
        public function get_user_tpname($username)
        {
        $this->db->select('*');
		$this->db->from('crm_user');
		$this->db->where('name', $username);
		return $this->db->get()->row();
     
        }
        
        public function get_user_email($email)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('email', $email);
           	$query = $this->db->get();
        	
        	return $result = $query->result();
        	 
        }
        
        
        public function get_transaction_by_id($transaction_id)
        {
        	$this->db->select('*');
        	$this->db->from('payment_status');
        	$this->db->where('transaction_id', $transaction_id);
        	return $this->db->get()->row();
        
        }
        
        
        public function get_transaction_by_tpname($tp_account_id)
        {
        	$this->db->select('*');
        	$this->db->from('payment_status');
        	$this->db->where('tp_account_id', $tp_account_id);
        	return $this->db->get()->row();
        
        }
        
        
        
        
}
