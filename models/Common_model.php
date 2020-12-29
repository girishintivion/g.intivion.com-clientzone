<?php
require_once(APPPATH.'libraries/requestparam.php');

class Common_model extends CI_Model {

    public function __construct()
    {
            $this->load->database();
    }
 
    public function get_current_user_data($username)
    {
        $this->db->select('*');
        $this->db->from('crm_user');
        $this->db->where('name', $username);
        return $this->db->get()->row();
        
    }
   
    public function get_current_user_email($username)
    {
        $this->db->select('email');
        $this->db->from('crm_user');
        $this->db->where('name', $username);
        return $this->db->get()->row('email');
        	 
    }

    public function get_current_user_acc_id($username)
        {
        	$this->db->select('account_id');
        	$this->db->from('crm_user');
        	$this->db->where('name', $username);
        	return $this->db->get()->row('account_id');
        	 
        }
        public function get_current_user_email_data($user_email)
        {
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('email', $user_email);
        
        	$query = $this->db->get();
        	return $result = $query->result();
        
        }
    public function get_current_user_group($username)
    {
        $this->db->select('trading_platform');
        $this->db->from('crm_user');
        $this->db->where('name', $username);
        return $this->db->get()->row('trading_platform');
    
    }

    public function get_password($username)
    {
        $this->db->select('secret_data');
        $this->db->from('crm_user');
        $this->db->where('name', $username);
        return $this->db->get()->row('secret_data');
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


    public function create_user_demo($username,$password,$email)
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


    public function create_user_real($username,$password,$email)
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

    private function hash_password($password) {
        
        return password_hash($password, PASSWORD_BCRYPT);
    
    }
}

?>