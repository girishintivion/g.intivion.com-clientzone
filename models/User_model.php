<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}
	public function get_phone_code($country_code)
	{
		$this->db->select('dialing_code');
		$this->db->from('countries_country');
		$this->db->where('iso2', $country_code);
		return $this->db->get()->row('dialing_code');
		
	}
	
	
	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
	public function resolve_user_login($username, $password) {
		
		$this->db->select('password');
		$this->db->from('users_ci');
		$this->db->where('username', $username);
		$hash = $this->db->get()->row('password');
		
		return $this->verify_password_hash($password, $hash);
		
	}
	
	/**
	 * get_user_id_from_username function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @return int the user id
	 */
	public function get_user_id_from_username($username) {
		
		$this->db->select('id');
		$this->db->from('users_ci');
		$this->db->where('username', $username);

		return $this->db->get()->row('id');
		
	}
	
	/**
	 * get_user function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function get_user($user_id) {
		
		$this->db->from('users_ci');
		$this->db->where('id', $user_id);
		return $this->db->get()->row();
		
	}
	
	
	/**
	 * update_user function.
	 *
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function update_user($user_id,$data) 
	{	

		$password=password_hash($data['password'], PASSWORD_BCRYPT);

		$data = array(
				'password' => $password,
		);
		$this->db->where('id', $user_id);
		$this->db->update('users_ci', $data);
	}
	
	/**
	 * delete_crmuser function.
	 *
	 * @access public
	 * @param mixed $email
	 * @return object the user object
	 */
	public function delete_crmuser($email)
	{
		$this->db->where('email', $email);
		$this->db->delete('crm_user');
	}
	
	/**
	 * get_iso2 function.
	 *
	 * @access public
	 * @param mixed $country
	 * @return object the user object
	 */
	public function get_iso2($country)
	{
		$this->db->from('countries_country');
		$this->db->where('name', $country);
		return $this->db->get()->row()->iso2;
	}
	
	/**
	 * insert_crmuser function.
	 *
	 * @access public
	 * @param mixed $country
	 * @return object the user object
	 */
	public function insert_crmuser($data)
	{
		$this->db->insert('crm_user', $data);
	}
	
	/**
	 * create_user function.
	 *
	 * @access public
	 * @param mixed $country
	 * @return object the user object
	 */
	public function create_user($username,$email,$password,$type)
	{
		
			$userinfo = array(
					'username'   => $username,
					'email'      => $email,
					'password'   => $this->hash_password($password),
					'created_at' => date('Y-m-j H:i:s'),
					'user_role'  => $type,
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
	private function hash_password($password) 
	{
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}
	
	/**
	 * verify_password_hash function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) {
		
		return password_verify($password, $hash);
		
	}
	
}
