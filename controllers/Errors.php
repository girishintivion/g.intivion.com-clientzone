<?php
class Errors extends CI_Controller {

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

        public function index()
        {
        	   
                $data['title'] = 'Error';

                $this->load->view('templates/before-login-header', $data);
                $this->load->view('errors/pagenotfound');
                $this->load->view('templates/before-login-footer');
        }

        public function test()
        {
        	?>
        	<form action="" method="post">
        	<input type="submit" value="click me">
        	</form>
        	<?php 
        }
        
}