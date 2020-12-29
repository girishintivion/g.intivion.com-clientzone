<?php
class Cashier6 extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('url_helper');
		
		$this->load->model('withdrawal_request_model');
		$this->load->model('deposite_model');
		
		$this->load->helper(array('url'));
		$this->load->helper('prodconfig');
		
		$uri_1 = $this->uri->segment(1);
		all_lang($uri_1);
		
	}
	
	
        public function index()
        {
        	if(!isset($_SESSION['logged_in']))
        	{       		
        		redirect($this->uri->segment(1).'/dashboard');
        	}
        	exit;

        	
        	if(!isset($_SESSION['logged_in']))
        	{       		
        		redirect($this->uri->segment(1).'/login');
        	}
			
        	

        		$data = array();
        		$data['title'] = 'Deposit Fund';
        		$data['language'] = $this->uri->segment(1);
        		
        		$this->load->helper('form');
        		$this->load->library('form_validation');
        		
        		$this->form_validation->set_rules('acc', 'lang:Trading Account No', 'trim|required');
        
        	   if($this->form_validation->run() === FALSE)
        		{
        		
				$user_email = $this->withdrawal_request_model->get_current_user_email($_SESSION['username'] );
        			$data   = array();
        			$data['result'] = $this->withdrawal_request_model->get_current_user_data($user_email);
					
        		
        		$data['gateway']='Deposit';
        		$this->load->view('templates/header', $data);
        		$this->load->view('templates/left-sidebar');
        		$this->load->view('traders_room/fund_account/fake_deposit', $data);
        		$this->load->view('templates/footer');
        	
        
        	}
        	
        		else
				{

						$_SESSION['pop_mes'] = "Transaction is declined";
        				//popup();
						
						redirect ( $this->uri->segment ( 1 ) . '/cashier6');
				/*		
				$this->load->view('templates/header');
        		$this->load->view('templates/left-sidebar');
        		$this->load->view('traders_room/fund_account/fake_deposit');
        		$this->load->view('templates/footer');
				*/
							
				}
        	

        
        }
        
        
        

        
        
        
     
}