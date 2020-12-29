<?php
class Change_password extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

                $this->load->helper('url_helper');
                $this->load->model('user_model');
                $this->load->model('demo_model');
                $this->load->model('change_password_model');
                $this->load->helper(array('url'));
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
        	
        	$user_pass = $this->demo_model->get_password($_SESSION['username'] );
        	$data['password']=$user_pass;
        	
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	$this->form_validation->set_rules('oldpass', 'oldpass', 'trim|required');
        	$this->form_validation->set_rules('password_confirmation','password_confirmation', 'trim|required');
        	$this->form_validation->set_rules('password', 'password', 'trim|required');
        	
        	if($this->form_validation->run() === FALSE)
        	{
        		$this->load->view('templates/header', $data );
        		$this->load->view('templates/left-sidebar', $data );
        		$this->load->view('traders_room/change_password_account/change_pass', $data);
        		$this->load->view('templates/footer', $data );
        	}
        	else 
        	{
        	$token = $this->input->post('my_token_change_password');
        	if (isset($_COOKIE['form_token_change_password']))
        	{
        		if($_COOKIE['form_token_change_password'] != "null")
        		{
        			$cookie_token=$_COOKIE['form_token_change_password'];
        			unset($_COOKIE['form_token_change_password']);
        		}
        	}
        	if(!empty($token) == $cookie_token){
        		try
        		{
        			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
        			
        			$this->load->helper('url');
        			$this->load->helper('prodconfig');
        			
        			getBUDetails($_SESSION['BUSINESS_UNIT']);
        			
        			
        			$request = new CrmChangePasswordRequestModel();
        			$request->organizationName =ORGANIZATION_NAME;
        			$request->ownerUserId =$OWNER_USER_ID;
        			$request->businessUnitName =$BUSINESS_UNIT_NAME;
        			$request->tpName =$this->input->post('accnum');
        			$request->oldPassword =$this->input->post('oldpass');
        			$request->newPassword =$this->input->post('password');
        	
					$method = "Change Password";
					$crmurl = api_url."/changepassword";
					$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
											
					$json_result = $update_mcrm['json_result'];
					$http_code = $update_mcrm['http_code'];
							
					$main_transaction_id = main_transaction_id();

        			if($http_code == "400" || $http_code == "404")
        			{
						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
						"CHANGE PASSWORD REQUEST: ".json_encode($request).PHP_EOL
						."-------------------------".PHP_EOL."\n";
						log_message('custom',$log);
						
						$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
						"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
						"HTTP CODE:".$http_code."CHANGE PASSWORD RESPONSE: ".json_encode($json_result).PHP_EOL
						."-------------------------".PHP_EOL."\n";
						log_message('custom',$log);
					
        				$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id."</p><p>Reason. : Incorrect password";
        			//	echo "<p id='pop_error_mes'>Main Transaction Id : ".$main_transaction_id."</p><p>Reason. : Incorrect password</p>";
        				redirect ( $this->uri->segment ( 1 ) . '/change-password');
        				
        			}
        			if($http_code == "201" || $http_code == "200") //if operation successful
        			{
        				if($json_result->resultInfo =="0")
        				{
        					
        					$data = array(
        							'password' => $this->input->post('password'),
        					);
        					try{
        						$user_id = $this->user_model->get_user_id_from_username($_SESSION['username']);
        						
        						$user_update=$this->user_model->update_user($user_id,$data);
        						$cm_user=$this->change_password_model->update_crmuser($_SESSION['username'],$this->input->post('password'));
        					}
        					catch(Exception $e)
        					{
        						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        						"CHANGE PASSWORD DB EXCEPTION: ".json_encode($e).PHP_EOL
        						."-------------------------".PHP_EOL."\n";
        						log_message('error',$log);
        					}
        					
        				//	echo "<p id='pop_mes'>".lang("Password Successfully Updated")."</p>";
        						$_SESSION['pop_mes'] = "Password Successfully Updated";
        					//	popup();
        						redirect ( $this->uri->segment ( 1 ) . '/change-password');
        					
        				}
        				
        			}
        			//redirect('dashboard')
        			
        			
        			
        		}
        		
        		catch (Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        			"CHANGE PASSWORD EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        			//	redirect($this->uri->segment(1)."/errors");
        			//echo "<p id='pop_error_mes'>".$e."</p>";
        			$_SESSION['error_pop_mes'] = $e;
        			redirect ( $this->uri->segment ( 1 ) . '/change-password');
        		}
        		
        		
        		
        		// 		redirect ( $this->uri->segment ( 1 ) . '/Dashboard?module=change_pass');
        		
        	}else
        	{
        		redirect ( $this->uri->segment ( 1 ) . '/change-password');
        	
        	}
        	//$this->real_model->user();
        	
        	
        }
        }
        
        
}