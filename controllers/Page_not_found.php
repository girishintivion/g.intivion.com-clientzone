<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Page_not_found extends CI_Controller {
     public function __construct()
        {
                parent::__construct();
                $this->load->model('demo_model');
                $this->load->model('user_model');
                $this->load->helper('url_helper');
                $this->load->helper(array('url'));
                $this->load->helper('prodconfig');
                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
   
        }
 
    public function index() { 
	           
    	        $data['title'] = 'Page Not Found';
				$this->load->view('templates/before-login-header', $data);
                $this->load->view('errors/pagenotfound');
                $this->load->view('templates/before-login-footer');
        
    } 
} 
