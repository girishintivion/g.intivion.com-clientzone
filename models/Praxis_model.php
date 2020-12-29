<?php
require_once(APPPATH.'libraries/requestparam.php');
class Praxis_model extends CI_Model 
{

        public function __construct()
        {
                $this->load->database();
        }
        

        public function get_details_from_praxis_account($username)
        {
        	$this->db->select('*');
        	$this->db->from('praxis_account');
        	$this->db->where('PIN', $username);
        	return $this->db->get()->row_array();
        	
        }
        
        public function get_details_from_praxis_withdraw($ExtPayoutID)
        {
        	$this->db->select('*');
        	$this->db->from('praxis_withdraw');
        	$this->db->where('extPayoutID', $ExtPayoutID);
        	return $this->db->get()->row_array();
        	
        }
        
        public function get_details_from_crm_user($username)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row();
        	
        }
        
        public function insert_into_wp_praxis_withdraw($my_arr)
        {
        	
        	$this->db->insert('praxis_withdraw', $my_arr);
        	
        }
        
        public function get_details_from_countries_country($country)
        {
        	$this->db->select('*');
        	$this->db->from('countries_country');
        	$this->db->where('name', $country);
        	return $this->db->get()->row_array();
        	
        }
        
        public function update_wp_praxis_account($update_arr,$PIN)
        {

        	$this->db->where('PIN', $PIN);
        	$this->db->update('praxis_account', $update_arr);

        }
        
        public function insert_into_wp_praxis_account($my_arr)
        {
        	
        	$this->db->insert('praxis_account', $my_arr);
        	
        }
}
