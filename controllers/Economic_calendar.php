<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Economic_calendar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url_helper');
		$this->load->model('dashboard_model');
		$this->load->helper(array('url'));
		$this->load->helper('prodconfig');
		
		$uri_1 = $this->uri->segment(1);
		all_lang($uri_1);
		
	}
	
	public function index()
	{
		$this->load->view('templates/header', $data );
		$this->load->view('templates/left-sidebar', $data );
		$this->load->view('traders_room/calendar/calendar', $data);
		$this->load->view('templates/footer', $data );
	}
}
