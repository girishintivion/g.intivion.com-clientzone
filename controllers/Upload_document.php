<?php
class Upload_document extends CI_Controller 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper(array('url'));
		$this->load->model('document_upload_model');
		$this->load->helper('prodconfig');
		$uri_1 = $this->uri->segment(1);
		all_lang($uri_1);
	}
	
	//if index is loaded
	public function index() 
	{
		if(!isset($_SESSION['logged_in']))
		{
			redirect('user/login');
		}
		//load the helper library
		$this->load->helper('form');
		$this->load->helper('url');
		//Set the message for the first time
		
		$data['title'] = 'Upload a Document';
		$accountid=$this->document_upload_model->get_account_id($_SESSION['username']);
		$data['result']=$this->document_upload_model->get_upload_details($accountid);
	
		

		$this->load->view('templates/header4', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('traders_room/document_upload/multiple_upload', $data);
		$this->load->view('templates/footer');
	}
}