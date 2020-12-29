<?php
class Forgot_password extends CI_Controller {

        public function __construct()
        {
				parent::__construct();
				$this->load->model('Common_model');
                //$this->load->model('forgot_password_model');
                //$this->load->model('user_model');
                //$this->load->model('dashboard_model');
                $this->load->helper('url_helper');
                $this->load->helper(array('url'));
                $this->load->library('javascript');
                //$this->load->library('CrmRealRequestModel');
               
                $this->load->helper('prodconfig');
                
                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
   
        }

       
        public function send($username)
        {
        	 
        	$data['language'] = $this->uri->segment(1);
        
        	try
        	{
        		global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        
        		$this->load->helper('url');
        		$this->load->helper('prodconfig');
        
        		//print $accname;
        		try{
        			$user_email = $this->Common_model->get_current_user_email($username);
        		}
        		catch(Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        			"FORGOT PASSWORD DB EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        		}
        		
        		getParentBUDetails();
        
        		$request = new CrmForgotRequestPasswordModel();
        		$request->tpName = $username;
        		$request->email = $user_email;
        		$request->organizationName = ORGANIZATION_NAME;
        		$request->ownerUserId = $OWNER_USER_ID;
        		$request->businessUnitName = $BUSINESS_UNIT_NAME;
        
				$method = "Forgot Password";
				$crmurl = api_url."/forgotyourpassword";
				$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
											
				$json_result = $update_mcrm['json_result'];
				$http_code = $update_mcrm['http_code'];
							
				$main_transaction_id = main_transaction_id();

        		if($http_code == "400" || $http_code == "404")
        		{
        			$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
        			redirect ( $this->uri->segment ( 1 ) . '/dashboard');
        			
        			
        		}
        		if($http_code == "201" || $http_code == "200") //if operation successful
        		{
        			if($json_result->resultInfo =="0")
        			{
        				$_SESSION['pop_mes'] = "Please check your email: We have sent you the password.";
        				redirect($this->uri->segment(1)."/dashboard");
        
        			}
        
        
        		}
        
        		try{
        			$user_email = $this->Common_model->get_current_user_email($_SESSION['username'] );
        
        
        			$user_group = $this->Common_model->get_current_user_group($_SESSION['username'] );
        			$_SESSION['user_group'] = $user_group;
        
        			$data   = array();
        
        			$data['result'] = $this->Common_model->get_current_user_email_data($user_email);
        
        			//$usrrole = $this->dashboard_model->get_current_user_trplatform($_SESSION['username'] );
        		}
        		catch(Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        			"DASHBOARD DB EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        		}
        
        		 
        		
        		echo'
				<script>
        				setTimeout(function () {
				window.location.href = "'.base_url($this->uri->segment(1).'/dashboard').'"},2000);
				</script>
				';        
        		
        
        			$data['title'] = 'Dashboard';
        			$this->load->view('templates/header', $data);
        			$this->load->view('templates/sidebar');
        			$this->load->view('user/dashboard', $data);
        			$this->load->view('templates/footer');
                		
        	}
                
                		catch (Exception $e)
                		{
                			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
                			"FORGOT PASSWORD EXCEPTION: ".json_encode($e).PHP_EOL
                			."-------------------------".PHP_EOL."\n";
                			log_message('error',$log);
                			redirect ( $this->uri->segment ( 1 ) . '/login');
                		}
                		//$this->real_model->user();
                }
        

        
        public function index()
        {
			global $blocked_IPs;
			if (in_array(get_client_ip(), $blocked_IPs)){
				echo "This IP has been blocked further";
				exit;
			}
        	
        	 
        	$data['title'] = 'Forgot Password';
        	
        	
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        
        	
        	
        	$data['language'] = $this->uri->segment(1);
        	 

        		$this->form_validation->set_rules('accname', 'lang:Enter Your Account Number', 'trim|required');
        		
        		
        	if($this->form_validation->run() === FALSE)
        	{
        		redirect($this->uri->segment(1).'/Login');
 			/*	
        		$data['title'] = 'Forgot Password';
        		
        		$this->load->view('templates/before-login-header', $data);
        		$this->load->view('traders_room/forgot_password/forgot_password');
        	//	$this->load->view('user/login/login');
        		$this->load->view('templates/before-login-footer');
        */
        	}
        	else
        	{
        		$token = $this->input->post('my_token_forgot_pass');
        		
        		$session_token=null;
        		
        		$session_token = $_SESSION['form_token_forgot_pass'];
        		unset($_SESSION['form_token_forgot_pass']);
        		
        		if(!empty($token)== $session_token)
        		{
        		try
        		{
        			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        			 
        			$this->load->helper('url');
        			$this->load->helper('prodconfig');
        			 
        			 
        			$accname = $this->input->post('accname');
        			try{
        	
        				$get_acc_details_forgot_password = get_acc_details_forgot_password($accname);
        				
        				$result = $get_acc_details_forgot_password->result;
        				
        				$code = $result->code; 
        			
        				if($code == "0" || $code == "15")
        				{
        					$user_email = $get_acc_details_forgot_password->accountsInfo[0]->email;
        					
        				}
        				else {
        				
        					$main_transaction_id = main_transaction_id();
        					
        					$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id."</p><p>Reason : Incorrect Account Number.";
        					
        					redirect ( $this->uri->segment ( 1 ) . '/login');
        					
        				}
        				
        				
        				
        			}
        			catch(Exception $e)
        			{
        				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        				"FORGOT PASSWORD DB EXCEPTION: ".json_encode($e).PHP_EOL
        				."-------------------------".PHP_EOL."\n";
        				log_message('error',$log);
        			}

        			// print $user_email;
        		
        	
        			getParentBUDetails();
        			
        			$request = new CrmForgotRequestPasswordModel();
        			$request->tpName = $accname;
        			$request->email = $user_email;
        			$request->organizationName = ORGANIZATION_NAME;
        			$request->ownerUserId = $OWNER_USER_ID;
        			$request->businessUnitName = $BUSINESS_UNIT_NAME;
        		
					$method = "Forgot Password";
					$crmurl = api_url."/forgotyourpassword";
					$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
												
					$json_result = $update_mcrm['json_result'];
					$http_code = $update_mcrm['http_code'];
								
					$main_transaction_id = main_transaction_id();

        			if($http_code == "400" || $http_code == "404")
        			{
						$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
						
						redirect ( $this->uri->segment ( 1 ) . '/login');
        			}
        			if($http_code == "201" || $http_code == "200") //if operation successful
        			{
        				if($json_result->resultInfo =="0")
        				{
        					$_SESSION['pop_mes'] = lang("Password has been emailed successfully.");
        					//popup();
        					redirect ( $this->uri->segment ( 1 ) . '/login');
        						
        				}
        				
        		
        			}       			
        
        			
        			
        		}
        		 
        		catch (Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        			"FORGOT PASSWORD EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        			redirect ( $this->uri->segment ( 1 ) . '/login');
        		
        		}

        		
        		}else
        		{
        			redirect($this->uri->segment(1).'/login');
        		}
        		
        		
        	}
        }
        
        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
        
        
        
}