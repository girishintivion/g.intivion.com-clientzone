<?php
class Demo_account_registration extends CI_Controller {

        public function __construct()
        {
				parent::__construct();
				$this->load->model('Common_model');
                $this->load->model('demo_model');
                //$this->load->model('real_model');
                $this->load->model('user_model');
                $this->load->helper('url_helper');
                $this->load->helper(array('url'));
                
             
                
                $this->load->helper('prodconfig');
                
                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
   
        }

        public function test()
        {
        	$this->load->view('templates/header', $data );
        	$this->load->view('templates/left-sidebar', $data );
        	$this->load->view('traders_room/real-account-index', $data);
        	$this->load->view('templates/footer');
        }

        public function thankyou()
        {
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/Login');
        	}
        	
        	$get_crm_details= $this->demo_model->get_crm_details($_SESSION['username']);
        	
        	$data['platform']= $get_crm_details[0]->platform;
        	$data['login']= $get_crm_details[0]->name;
        	$data['password']= $get_crm_details[0]->secret_data;
        	
        	
        	$this->load->view('templates/before-login-header', $data );
        	$this->load->view('traders_room/demo_account/thankyou-demo', $data);
        	$this->load->view('templates/before-login-footer');
        	
        }
        
        public function checkspecialchar($field_value)
        {
        	if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $field_value)){
        		$this->form_validation->set_message('checkspecialchar', "Please provide valid details");
        		return FALSE;
        	}
        	else{
        		
        		return TRUE;
        	}
        	
        	
        }

        public function index()
        {
        	
        	
        	if(isset($_SESSION['logged_in']))
        	{
        		//redirect($this->uri->segment(1).'/dashboard'); //05July2019
        		redirect($this->uri->segment(1).'/real-exist'); 
        	}
        	
        	global $blocked_IPs;
        	if (in_array(get_client_ip(), $blocked_IPs)){     
        		$_SESSION['error_pop_mes'] = "This IP has been blocked for registration.";
        		redirect($this->uri->segment(1).'/demo-account-registration');
        		exit();
        	}
        	
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	$this->load->helper('url');
        	$this->load->helper('prodconfig');
        	
        	$country_code = get_country_details();
        	$country_name=get_country_full_name($country_code);
        	
        	$pco_code = $this->Common_model->get_phone_code($country_code);
        	
        	$data['country_name'] = $country_name;
        	$data['country_code'] = $country_code;
        	$data['pco'] =$pco_code['dialing_code'];
        	$data['country'] = $this->Common_model->get_countries();
              
        	$this->form_validation->set_rules('firstname', 'lang:Full Name', 'trim|required|callback_checkspecialchar');
//        	$this->form_validation->set_rules('lastname', 'lang:Last Name', 'trim|required|callback_checkspecialchar');
        	$this->form_validation->set_rules('email', 'lang:Email ID', 'trim|required|valid_email');
        	$this->form_validation->set_rules('password_confirmation', 'lang:Password', 'trim|required|min_length[6]|max_length[20]|alpha_numeric|matches[password]');
        	$this->form_validation->set_rules('password', 'lang:Confirm Password', 'trim|required|min_length[6]|max_length[20]|alpha_numeric');
        	$this->form_validation->set_rules('country', 'lang:Country', 'trim|required');
        	$this->form_validation->set_rules('country_code', 'lang:DialingCode', 'trim|required');
        	$this->form_validation->set_rules('phone', 'lang:Phone Number', 'trim|required|numeric');        	
        	$this->form_validation->set_rules('currency', 'lang:Currency', 'trim|required');
        	$this->form_validation->set_rules('check1', 'lang:Terms', 'trim|required');
        	
        	
        	if($this->form_validation->run() === FALSE)
        	{
        		
        		$data['language'] = $this->uri->segment(1);
        		
        		$this->load->view('templates/before-login-header', $data );
        		$this->load->view('traders_room/demo_account/demo-account', $data);
        		$this->load->view('templates/before-login-footer');
        		
        	}
        	else
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"Step 1 and 2 POST: ".json_encode($_POST).PHP_EOL
				."-------------------------".PHP_EOL."\n";
				log_message('custom',$log);

        		$token = $this->input->post('my_token_demo_step1');
        		
        		$session_token=null;
        		
        		$session_token = $_SESSION['form_token_demo_step1'];
        		unset($_SESSION['form_token_demo_step1']);
        		
        		if(!empty($token) != $session_token)
        		{
        			$_SESSION['error_pop_mes'] = "Invalid Session.";
        			redirect($this->uri->segment(1).'/demo-account-registration');
        		}
        		//echo trim($str, "Hell!"); trim(''," ")
        		$firstname=trim($this->input->post('firstname')," ");
        		$lastname=trim($this->input->post('lastname')," ");        
        	/*	$name=$this->input->post('firstname');
        		$name= explode(' ', $name);
        		if($name[1] !="")
        		{
        			$lastname=$name[1];
        		}
        		else
        		{
        			$lastname="NA";
        		}
        		$firstname=	$name[0];
        		*/
        		$email=trim($this->input->post('email')," ");      		
        		$language = $this->uri->segment(1);
        		$password = $this->input->post('password');
        		$phone_country_code = $this->input->post('country_code');
        		$phone = trim($this->input->post('phone')," ");
        		$country=$this->input->post('country');
        		$currency=$this->input->post('currency');
        		$platform_type=$this->input->post('platform');
        		
        		
        	
        		global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$DEMO_PLATFORM_ID,$DEMO_PLATFORM_GROUP;
        		getBUDetailsCreateRealAcc($language,$currency,'1',strtolower($platform_type));
        		
        		
        		
        		$request = new CrmDemoRequestModel();
        		$request->tradingPlatformId =$DEMO_PLATFORM_ID;
        		$request->firstName = $firstname;
        		$request->lastName = $lastname;
        		$request->email = $email;
        		$request->phoneCountryCode =$phone_country_code;
        		$request->phoneNumber =$phone;
        		$request->countryCode = $country;
        		$request->phoneAreaCode ="0";
        		$request->ipAddress = get_client_ip();
        		$request->organizationName = ORGANIZATION_NAME;
        		$request->ownerUserId =$OWNER_USER_ID;
        		$request->businessUnitName =$BUSINESS_UNIT_NAME;
        		$request->groupName =$DEMO_PLATFORM_GROUP;
        		$request->loggedInAccountId ="";
        		//	$request->dateOfBirth= date_format(date_create_from_format('d/m/Y', $this->input->post('dob')), 'd-m-Y');
        		$request->password=$password;
        	
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
        		"demo REQUEST: ".json_encode($request).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('custom',$log);
        		
        		$method = "Demo Account Registration";
				$crmurl = api_url."/registerdemoaccount";
				$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
												
				$json_result = $update_mcrm['json_result'];
				$http_code = $update_mcrm['http_code'];
							
				$main_transaction_id = main_transaction_id();

        		$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
        		"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
        		"HTTP CODE:".$http_code."demo RESPONSE: ".json_encode($json_result).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('custom',$log);
        		
        		if($http_code == "400" || $http_code == "404")
        		{
        			
        			$_SESSION['error_pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
        			redirect ( $this->uri->segment ( 1 ) . '/demo-account-registration');
        			
        		}
        		if($http_code == "201" || $http_code == "200") //if operation successful
        		{
        			
        			try{
        				$user=$this->demo_model->create_user($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$email);
        				
        				$cm_user=$this->demo_model->create_crmuser($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$json_result->currency_id,$json_result->currency_code,$json_result->accountId,$json_result->tradingPlatformAccountId,"Demo",$platform_type,$city,$address,$firstname,$lastname);
        				
        			}
        			catch(Exception $e)
        			{
        				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        				"demo DB EXCEPTION: ".json_encode($e).PHP_EOL
        				."-------------------------".PHP_EOL."\n";
        				log_message('error',$log);
        			}
        			
        			
        			$_SESSION['BUSINESS_UNIT'] = $BUSINESS_UNIT_NAME;
        			
        			// $_SESSION['platform_type'] = $platform_type;
        			
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
        				catch(Exception $e)
        				{
        					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        					"demo DB EXCEPTION: ".json_encode($e).PHP_EOL
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
        					
        					$_SESSION['fullname']     = $firstname." ".$lastname;
      
        					redirect($this->uri->segment(1).'/demo-account-registration/thankyou');
        				}
        				else
        				{
        					$_SESSION['error_pop_mes'] = lang("User Not Saved");
        					redirect ( $this->uri->segment ( 1 ) . '/demo_account_registration');
        					
        					
        				}
        			}
        		}
        	
        	}
        	
        }
        
     
        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
        
        
        
}