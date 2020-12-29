<?php
require_once(APPPATH.'libraries/requestparam.php');
class Document_upload_model extends CI_Model 
{
	public function __construct()
	{
		$this->load->database();
	}
	public function get_account_id($username)
	{
		$this->db->select('account_id');
		$this->db->from('crm_user');
		$this->db->where('name', $username);
		return $this->db->get()->row('account_id');
	
	}
	public function save_file($accountid,$original_name,$doc_type,$modi_name,$filecontents,$buname)
	{
		$file = array(
				        'file_name' => $modi_name,
						'original_filename' => $original_name,
						'account_id' => $accountid,
						'doc_type' => $doc_type,
						'file_url' => $filecontents,
						'business_unit' => $buname,
				'status' => '0',
						'uploaded_date' => date('Y-m-j H:i:s'),
		);
		$this->db->insert('traders_files', $file);
	
	}
	public function get_upload_details($account_id)
	{
		$this->db->select('*');
		$this->db->from('traders_files');
		$this->db->where('account_id', $account_id);
	
		$query = $this->db->get();
		
		return $result = $query->result();

	}
}