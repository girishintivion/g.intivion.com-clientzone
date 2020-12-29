<?php
class Calculator extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('forgot_password_model');
                $this->load->model('user_model');
                $this->load->model('dashboard_model');
                $this->load->helper('url_helper');
                $this->load->helper(array('url'));
                $this->load->library('javascript');
                //$this->load->library('CrmRealRequestModel');
             
                $this->load->helper('prodconfig');
                
                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
   
        }

       
        
        public function index()
        {

        	
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	}
        	
        	
        	    $data['title'] = 'Calculator';
        	    $this->load->view('templates/header4', $data);
        	    $this->load->view('templates/sidebar');
        		$this->load->view('traders_room/reports/calculator');
        		$this->load->view('templates/footer');
        	
        	
        }
        
       
        
}