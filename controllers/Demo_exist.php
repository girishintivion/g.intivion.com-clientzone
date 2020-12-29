<?php
class Demo_exist extends CI_Controller {

        public function __construct()
        {
				parent::__construct();
				$this->load->model('Common_model');
                $this->load->model('demo_exist_model');
                $this->load->model('user_model');
                $this->load->helper('url_helper');
                $this->load->helper(array('url'));
                
             
                
                $this->load->helper('prodconfig');
                
                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
   
        }
        
        public function update()
        {
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	}
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	
        	$this->form_validation->set_rules('password_confirmation', 'lang:password_confirmation', 'trim|required');
        	$this->form_validation->set_rules('password', 'lang:password', 'trim|required');
        	
        	
        	if($this->form_validation->run() === FALSE)
        	{
        		redirect($this->uri->segment(1).'/Dashboard?module=additional_acc');
        	}
        	else{
		   
				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"Step 1 and 2 POST: ".json_encode($_POST).PHP_EOL
				."-------------------------".PHP_EOL."\n";
				log_message('custom',$log);

        	$token = $this->input->post('my_token_demo_exist');
        	if (isset($_COOKIE['form_token_demo_exist']))
        	{
        		if($_COOKIE['form_token_demo_exist'] != "null")
        		{
        			$cookie_token=$_COOKIE['form_token_demo_exist'];
        			unset($_COOKIE['form_token_demo_exist']);
        		}
        	}
        	if(!empty($token) == $cookie_token){
        		try
        		{
        			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$DEMO_PLATFORM_ID,$DEMO_PLATFORM_GROUP;
        			
        			$this->load->helper('url');
        			$this->load->helper('prodconfig');
        			
        			
        			$dob_month = $this->input->post('date_month');
        			$dob_year =$this->input->post('date_year');
        			$dob_day = $this->input->post('date_day');
        			$date = gmDate ( "Y-m-d\TH:i:s\Z", mktime ( 0, 0, 0, $dob_month, $dob_day, $dob_year ) );
        			
        			try{
        				$user_data=$this->Common_model->get_current_user_data($_SESSION['username']);
        				$account_type=$user_data->account_type;
        				//$platform_type=$user_data->platform;
        				$phone=$user_data->phone;
        				$firstname = $user_data->firstname;
        			//	$lastname = $user_data->lastname;
        				$name= explode(' ', $firstname);
        				if($name[1] !="")
        				{
        					$lastname=$name[1];
        				}
        				else
        				{
        					$lastname="NA";
        				}
        				$firstname=	$name[0];
        				$currency = $this->input->post('currency');
        				$platform_type=$this->input->post('platform');
        			}
        			catch(Exception $e )
        			{
        				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        				"DEMO EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        				."-------------------------".PHP_EOL."\n";
        				log_message('error',$log);
        			}
        			getBUDetailsExe($_SESSION['BUSINESS_UNIT'],$currency,strtolower($platform_type));
        			
        			$request = new CrmDemoRequestModel();
        			$request->organizationName = ORGANIZATION_NAME;
        			$request->ownerUserId =$OWNER_USER_ID;
        			$request->businessUnitName =$BUSINESS_UNIT_NAME;
        			$request->TradingPlatformId =$DEMO_PLATFORM_ID;
        			$request->IpAddress = get_client_ip();                      /* Fix implemented by Ramakant on 08-08-2017 for the getting two ips problem  */
        			$request->FirstName =$firstname;
        			$request->LastName =$lastname;
        			$request->Email =$user_data->email;
        			$request->GroupName =$DEMO_PLATFORM_GROUP;
        			$request->PhoneAreaCode ="0";
        			$request->PhoneNumber =$phone;
        			$request->PhoneCountryCode =$user_data->phone_country_code;
        			$request->CountryCode = $user_data->country;
        			$request->password=$this->input->post('password');
        			$request->LoggedInAccountId =$user_data->account_id;
        			
        			
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
        			"DEMO EXIST REQUEST: ".json_encode($request).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('custom',$log);
        		
        			$method = "Demo Exist";
					$crmurl = api_url."/registerdemoaccount";
					$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
											
					$json_result = $update_mcrm['json_result'];
					$http_code = $update_mcrm['http_code'];
							
					$main_transaction_id = main_transaction_id();
        			
        			$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
        			"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
        			"HTTP CODE:".$http_code."DEMO EXIST RESPONSE: ".json_encode($json_result).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('custom',$log);
        			
        			if($http_code == "400" || $http_code == "404")
        			{
        			//	$_SESSION['error_pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
        			//	redirect ( $this->uri->segment ( 1 ) . '/Dashboard?module=additional_acc');
        				echo "<p id='pop_error_mes'>Main Transaction Id : ".$main_transaction_id."</p>";
        			}
        			if($http_code == "201" || $http_code == "200") //if operation successful
        			{
        				try{
        					$user=$this->demo_exist_model->create_user($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$user_data->email);
        					
        					$cm_user=$this->demo_exist_model->create_crmuser($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$json_result->currency_id,$json_result->currency_code,$json_result->accountId,$json_result->tradingPlatformAccountId,$date,$firstname,$lastname,$account_type,$user_data->platform,$user_data->phone,$user_data->email,$user_data->phone_country_code,$user_data->country);
        				}
        				catch(Exception $e )
        				{
        					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        					"DEMO EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        					."-------------------------".PHP_EOL."\n";
							log_message('error',$log);
        				}
        				$_SESSION['BUSINESS_UNIT'] = $BUSINESS_UNIT_NAME;
        			/*	
        				$userinfo = array (
        						
        						'user_login' => $json_result->tradingPlatformAccountName,
        						'user_nicename' => $this->input->post('firstname'),
        						'user_email' =>$user_data->email,
        						'user_pass' => $json_result->tradingPlatformAccountPassword,
        						'display_name' => 'demo'
        				);
        				
        				$user_id = wp_insert_user ( $userinfo );
        				$wp_user = new WP_User ( $user_id );
        				$wp_user->set_role ( 'demo' );
        				
        				$users = get_user_by ( 'login', $json_result->tradingPlatformAccountName );
        				
        				if (! is_wp_error ( $users )) {
        					wp_clear_auth_cookie ();
        					wp_set_current_user ( $users->ID );
        					wp_set_auth_cookie ( $users->ID );
        					
        					
        				}
        	*/			
        				
        				$_SESSION['platform_type'] = $platform_type;
        				
        				if($this->user_model->resolve_user_login($json_result->tradingPlatformAccountName, $json_result->tradingPlatformAccountPassword))
        				{
        					try{
        						$user_id = $this->user_model->get_user_id_from_username($json_result->tradingPlatformAccountName);
        						$user= $this->user_model->get_user($user_id);
        					}
        					catch(Exception $e )
        					{
        						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        						"DEMO EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        						."-------------------------".PHP_EOL."\n";
        						log_message('error',$log);
        					}
        					if($user)
        					{
        						
        						
        						// set session user datas
        						$_SESSION['user_id']      = (int)$user->id;
        						$_SESSION['username']     = (string)$user->username;
        						$_SESSION['logged_in']    = (bool)true;
        						$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
        						//$_SESSION['is_admin']     = (bool)$user->is_admin;
        						$_SESSION['user_role']     = (string)$user->user_role;
        		       				
        				//		redirect($this->uri->segment(1).'/demo_exist/thankyou');
        						echo "<p id='pop_mes'>".lang("Additional Demo Account Registered Successfully")."</p><p>Username: ".$json_result->tradingPlatformAccountName."</p><p>Password: ".$json_result->tradingPlatformAccountPassword."</p>";
        						}
        					else
        					{
        					//	$_SESSION['pop_mes'] = "User Not Saved";
        					//	popup();
        					//	redirect ( $this->uri->segment ( 1 ) . '/Dashboard?module=additional_acc');
        						echo "<p id='pop_error_mes'>".lang("User Not Saved")."</p>";
        					}
        					
        					
        				}
        				
        			}
        		}
        		catch (Exception $e)
        		{
        			
        			//	redirect('error?error='.$e);
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        			"DEMO EXIST EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        			redirect($this->uri->segment(1)."/errors");
        			
        		}
       		
        	}
        	else
        	{
        		redirect ( $this->uri->segment ( 1 ) . '/Dashboard?module=additional_acc');
        	//	redirect($this->uri->segment(1).'/demo_exist');
        	}
        	
        	}	
        }

        public function thankyou()
        {
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	}
        	

        	try{
        		$user_pass = $this->Common_model->get_current_user_data($_SESSION['username'] );
        	}
        	catch(Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"REAL EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('error',$log);
        	}
        	$data['username']=$user_pass->name;
        	$data['platform']="";
        	$data['password']=$user_pass->secret_data;
        	$data['currency']=$user_pass->currency_code;
        	
        	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$DEMO_PLATFORM_GROUP;
        	getBUDetailsExe($_SESSION['BUSINESS_UNIT'],$user_pass->currency_code,"");
        	
        	$data['groupname']=$DEMO_PLATFORM_GROUP;
        	
        	$this->load->view('templates/before-login-header', $data);
        	$this->load->view('traders_room/demo_account/thankyou-demo', $data);
        	$this->load->view('templates/before-login-footer');

        	
        	/*
        	try{
        	$user_pass = $this->demo_exist_model->get_password($_SESSION['username'] );
        	}
        	catch(Exception $e )
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"DEMO EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL;
        		file_put_contents(logger_url, $log. "\n", FILE_APPEND);
        	}
        
        	$data['title'] = 'Thank You Demo';
        	$data['password']=$user_pass;
        	$this->load->view('templates/header4', $data);
        	$this->load->view('traders_room/demo_account/thankyou-demo', $data);
        	$this->load->view('templates/footer');
        	
        	*/
        }
        
        public function index()
        {
        	
        	
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	} 
        	
        
			$country_code = get_country_details();
            $country_name=get_country_full_name($country_code);
 
        	$data['title'] = 'Open Demo Account';
        	$data['country_name'] = $country_name;
        	$data['country_code'] = $country_code;
        	 
        	$pco_code = $this->Common_model->get_phone_code($country_code);
        	 
        	$data['pco'] =$pco_code['dialing_code'];
        	
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        
        	
        	$data['country'] = $this->Common_model->get_countries();
        	
        	$data['language'] = $this->uri->segment(1);
        	 

        	//	$this->form_validation->set_rules('firstname', 'firstname', 'trim|required');
        	//	$this->form_validation->set_rules('lastname', 'lastname', 'trim|required');
        		$this->form_validation->set_rules('password_confirmation', 'password_confirmation', 'required|min_length[6]');
        		$this->form_validation->set_rules('password', 'password', 'trim|required|matches[password_confirmation]');
        		
        	if($this->form_validation->run() === FALSE)
        	{
 				try{
        		$user_data = $this->Common_model->get_current_user_data($_SESSION['username']);
 				}
 				catch(Exception $e )
 				{
 					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
 					"DEMO EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
 					."-------------------------".PHP_EOL."\n";
 					log_message('error',$log);
 				}
        		$tparr =array(); //creating an array
        		//is_object () function is used to find whether a variable is an object or not.
        		if(is_object($user_data)) {
        			$tparr[] =  $user_data;
        		}
        		else
        		{
        			$tparr = $user_data;
        		}
        		
        		//print_r($user_data); 
        		$data['result'] = $tparr;
        		$data['title'] = 'Open Demo Account';
        		
        		
        		$this->load->view('templates/header4', $data );
        		$this->load->view('templates/sidebar');
        		$this->load->view('traders_room/demo_exist_account/demo_exist',$data);
        		$this->load->view('templates/footer');
        
        	}
        	else
        	{
        		
        		$token = $this->input->post('my_token_demo_exist');
        		
        		$session_token=null;
        		
        		$session_token = $_SESSION['form_token_demo_exist'];
        		unset($_SESSION['form_token_demo_exist']);
        		
        		if($token == $session_token)
        		{
        		
        		try
        		{
        			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$DEMO_PLATFORM_ID,$DEMO_PLATFORM_GROUP;
        			 
        			$this->load->helper('url');
        			$this->load->helper('prodconfig');
        			 
        			 
        			$dob_month = $this->input->post('date_month');
        			$dob_year =$this->input->post('date_year');
        			$dob_day = $this->input->post('date_day');
        			$date = gmDate ( "Y-m-d\TH:i:s\Z", mktime ( 0, 0, 0, $dob_month, $dob_day, $dob_year ) );
        			 
        			try{
        			$user_data=$this->Common_model->get_current_user_data($_SESSION['username']);
        			$account_type=$user_data->account_type;
        			$platform_type=$this->input->post('platform');//$user_data->platform;
        			$phone=$user_data->phone;
        			$firstname = $user_data->firstname;
        			$lastname = $user_data->lastname;
        			$currency = $this->input->post('currency');
        			}
        			catch(Exception $e )
        			{
        				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        				"DEMO EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        				."-------------------------".PHP_EOL."\n";
        				log_message('error',$log);
        			}
        			getBUDetailsExe($_SESSION['BUSINESS_UNIT'],$currency,strtolower($platform_type));
        			 
        			$request = new CrmDemoRequestModel();
        			$request->organizationName = ORGANIZATION_NAME;
        			$request->ownerUserId =$OWNER_USER_ID;
        			$request->businessUnitName =$BUSINESS_UNIT_NAME;
        			$request->TradingPlatformId =$DEMO_PLATFORM_ID;
        			$request->IpAddress = get_client_ip();                      /* Fix implemented by Ramakant on 08-08-2017 for the getting two ips problem  */
        			$request->FirstName =$firstname;
        			$request->LastName =$lastname;
        			$request->Email =$user_data->email;
        			$request->GroupName =$DEMO_PLATFORM_GROUP;
        			$request->PhoneAreaCode ="0";
        			$request->PhoneNumber =$phone;
        			$request->PhoneCountryCode =$user_data->phone_country_code;
        			$request->CountryCode = $user_data->country;
        			$request->password=$this->input->post('password');
        			$request->LoggedInAccountId =$user_data->account_id;
        			
					$method = "Demo Exist";
					$crmurl = api_url."/registerdemoaccount";
					$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
											
					$json_result = $update_mcrm['json_result'];
					$http_code = $update_mcrm['http_code'];
							
					$main_transaction_id = main_transaction_id();

        			if($http_code == "400" || $http_code == "404")
        			{
						$_SESSION['pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
        				redirect($this->uri->segment(1)."/errors");
        			}
        			if($http_code == "201" || $http_code == "200") //if operation successful
        			{
        				
        					
        				
        				
        		try{
        				$user=$this->demo_exist_model->create_user($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$user_data->email);
        		
        				$cm_user=$this->demo_exist_model->create_crmuser($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$json_result->currency_id,$json_result->currency_code,$json_result->accountId,$json_result->tradingPlatformAccountId,$date,$firstname,$lastname,$account_type,$user_data->platform,$user_data->phone,$user_data->email,$user_data->phone_country_code,$user_data->country);
        		}
        				catch(Exception $e )
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        			"DEMO EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        		}
        				$_SESSION['BUSINESS_UNIT'] = $BUSINESS_UNIT_NAME;
        				
        				$fake_data=fake_data();
        				
        				$userinfo = array (
        				
        						'user_login' => $json_result->tradingPlatformAccountName,
        						'user_nicename' => $fake_data['fname'],
        						'user_email' =>$fake_data['email'],
        						'user_pass' => $json_result->tradingPlatformAccountPassword,
        						'display_name' => 'demo'
        				);
        				
        				$user_id = wp_insert_user ( $userinfo );
        				$wp_user = new WP_User ( $user_id );
        				$wp_user->set_role ( 'demo' );
        				
        				$users = get_user_by ( 'login', $json_result->tradingPlatformAccountName );
        				
        				if (! is_wp_error ( $users )) {
        					wp_clear_auth_cookie ();
        					wp_set_current_user ( $users->ID );
        					wp_set_auth_cookie ( $users->ID );
        				
        				
        				}
        				
        				
        				
        		
        				if($this->user_model->resolve_user_login($json_result->tradingPlatformAccountName, $json_result->tradingPlatformAccountPassword))
        				{
        					try{
        					$user_id = $this->user_model->get_user_id_from_username($json_result->tradingPlatformAccountName);
        					$user= $this->user_model->get_user($user_id);
        					}
        					catch(Exception $e )
        					{
        						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        						"DEMO EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        						."-------------------------".PHP_EOL."\n";
        						log_message('error',$log);
        					}
        					if($user)
        					{
        						
        						
        						// set session user datas
        						$_SESSION['user_id']      = (int)$user->id;
        						$_SESSION['username']     = (string)$user->username;
        						$_SESSION['logged_in']    = (bool)true;
        						$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
        						//$_SESSION['is_admin']     = (bool)$user->is_admin;
        						$_SESSION['user_role']     = (string)$user->user_role;
								
								global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        			getParentBUDetails();
        			$request = new CrmGetAccountBalanceModel();
        			
        			$request->tradingPlatformAccountName =$_SESSION['username'];
        			$request->organizationName =ORGANIZATION_NAME;
        			$request->ownerUserId =$OWNER_USER_ID;
        			$request->businessUnitName =$BUSINESS_UNIT_NAME;
				
			        $method = "Demo Exist";
					$crmurl = api_url."/GetAccountBalance";
					$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
											
					$mgtapibalance_result = $update_mcrm['json_result'];
					$http_code = $update_mcrm['http_code'];
							
					$main_transaction_id = main_transaction_id();
        		
					$_SESSION['equity'] = $mgtapibalance_result->equity;
					$_SESSION['currency'] = $json_result->currency_code;
        					
        						 
        					redirect($this->uri->segment(1).'/demo-account-registration/thankyou');
        					}
        					else
        					{
        						$_SESSION['pop_mes'] = "User Not Saved";
        						popup();
        						
        						
        					}
        					 
        					
        				}
        				
        			}
        		}
        		catch (Exception $e)
        		{
        			 
        		//	redirect('error?error='.$e);
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        			"DEMO EXIST EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        			redirect($this->uri->segment(1)."/errors");

        		}
        		
        		 }
        		else
        		{
        			redirect($this->uri->segment(1).'/demo_exist');
        		}	 

        	}
        	
        	
        }
          
}