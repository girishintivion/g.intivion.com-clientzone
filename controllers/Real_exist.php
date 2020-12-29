<?php
class Real_exist extends CI_Controller {

        public function __construct()
        {
				parent::__construct();
				$this->load->model('Common_model');
                $this->load->model('real_exist_model');
                $this->load->model('user_model');
                $this->load->model('personal_details_model');
                $this->load->helper('url_helper');
                $this->load->helper(array('url'));
                //$this->load->library('CrmRealRequestModel');
             
                
                $this->load->helper('prodconfig');
                
                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
   
        }

        
        
        public function check()
        {
        	$result123=get_acc_details_forgot_password('131035615655');
        	echo $code=$result123->result->code;
        	print"<pre>"; print_r($result123); print"</pre>";
        	/*
        	$this->db->select('*');
        	$this->db->from('crm_user');
        //	$this->db->where('name', '539076');
        	$query = $this->db->get();
        	$result = $query->result();
        	print"<pre>"; print_r($result); print"</pre>";
        	*/
        } 
        
   		public function update()
   		{}
   		
        public function thankyou()
        {}
        
        
        
        public function index()
        {
        	if (! isset ( $_SESSION ['logged_in'] )) {
        		redirect ( $this->uri->segment ( 1 ) . '/login' );
        	}
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	
        	$country_code = $this->personal_details_model->get_ccode($_SESSION['username'] );
        	$country_name = $this->personal_details_model->get_country_name($country_code);
        	$data['country_name'] = $country_name;
        	$data['country_code'] = $country_code;
        	$data['country'] = $this->Common_model->get_countries();
        	
        	$pco_code = $this->Common_model->get_phone_code($country_code);
        	$data['pco'] =$pco_code['dialing_code'];
        	
        	try{
        		$user_data = $this->Common_model->get_current_user_data($_SESSION['username']);
        	}
        	catch(Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"ADDITIONAL DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL;
        		file_put_contents(logger_url, $log. "\n", FILE_APPEND);
        	}
        	$tparr =array();
        	if(is_object($user_data)) {
        		$tparr[] =  $user_data;
        	}
        	else
        	{
        		$tparr = $user_data;
        	}
        	
        	$data['result'] = $tparr;
        	
        	$this->form_validation->set_rules('password', 'lang:password', 'trim|required');
        	
        	if($this->form_validation->run() === FALSE)
        	{
 
        		$this->load->view('templates/header', $data );
        		$this->load->view('templates/left-sidebar', $data );
        		$this->load->view('traders_room/real_exist_account/real_exist', $data);
        		$this->load->view('templates/footer', $data );
        	}
        	else{

				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"Step 1 and 2 POST: ".json_encode($_POST).PHP_EOL
				."-------------------------".PHP_EOL."\n";
				log_message('custom',$log);

        		 $token = $this->input->post('my_token_demotoreal');
        		if (isset($_COOKIE['form_token_demotoreal']))
        		{
        			if($_COOKIE['form_token_demotoreal'] != "null")
        			{
        				$cookie_token=$_COOKIE['form_token_demotoreal'];
        				unset($_COOKIE['form_token_demotoreal']);
        			}
        		}
        		if(!empty($token) == $cookie_token){
        			try{
				$result_data = $this->Common_model->get_current_user_data($_SESSION['username']);
        		}
        		catch(Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        			"REAL EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        		}
        	//	print"<pre>";print_r($result_data);print"</pre>";
        		
				$firstname = $result_data->firstname; 
				$lastname = $result_data->lastname; 
				
				/*
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
				*/
				 
				$email = $result_data->email;
				
				$_SESSION['fname'] = $firstname;
				$_SESSION['lname'] = $lastname;
				$_SESSION['email'] = $email;
				
				$date = $result_data->birth_date;
				$country = $result_data->country;	
				$phone_country_code = $result_data->phone_country_code;
				$phone = $result_data->phone;
				$account_id = $result_data->account_id;
				$account_type= $result_data->account_type;
				$platform_type= $this->input->post('platform');//$result_data->platform;
				
	
        		try
        		{
        			
        			 
        			$this->load->helper('url');
        			$this->load->helper('prodconfig');
        			 
        			 /*
        			print_r($_REQUEST);
        			echo  "currency".	$currency = $this->input->post('currency');
        			echo "BU".$_SESSION['BUSINESS_UNIT'];
        			 */ 
        			 
        			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
        		//	getBUDetailsExe($_SESSION['BUSINESS_UNIT'], $this->input->post('currency'),strtolower($platform_type));
        			
        			getBUDetailsCreateRealAcc($this->uri->segment(1),$this->input->post('currency'),'1',strtolower($platform_type));
        			
        			
        			
        		//	echo  "group". $REAL_PLATFORM_GROUP;
        		//	exit();
        			$request = new CrmRealRequestModel();
        			$request->TradingPlatformId =$REAL_PLATFORM_ID;
        			$request->FirstName =$firstname;
        			$request->LastName =$lastname;
        			$request->Email =$email;
        			$request->PhoneCountryCode =$phone_country_code;
        			$request->PhoneNumber =$phone;
        			$request->CountryCode = $country;
        			$request->PhoneAreaCode ="0";
        			$request->IpAddress = get_client_ip();          /* Fix implemented by Ramakant on 08-08-2017 for the getting two ips problem  */
        			$request->organizationName = ORGANIZATION_NAME;
        			$request->ownerUserId = $OWNER_USER_ID;
        			$request->businessUnitName = $_SESSION['BUSINESS_UNIT'];
        			$request->GroupName =$REAL_PLATFORM_GROUP;
        			$request->LoggedInAccountId =$account_id;
        			$request->dateOfBirth=$date;
        			$request->password=$this->input->post('password');
					$request->new_accountleverage='3';
					 
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
        			"REAL EXIST REQUEST: ".json_encode($request).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('custom',$log);
        		
				$method = "Real Exist";
				$crmurl = api_url."/registerrealaccount";
				$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
										
				$json_result = $update_mcrm['json_result'];
				$http_code = $update_mcrm['http_code'];
						
				$main_transaction_id = main_transaction_id();	

        		$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
				"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
        		 "HTTP CODE:".$http_code."REAL EXIST RESPONSE: ".json_encode($json_result).PHP_EOL
        		 ."-------------------------".PHP_EOL."\n";
        		 log_message('custom',$log);
        			 
        			if($http_code == "400" || $http_code == "404")
        			{
        		        $_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
        		        redirect ( $this->uri->segment ( 1 ) . '/real-exist');
        		//		redirect($this->uri->segment(1)."/errors");
        			//	echo "<p id='pop_error_mes'>Main Transaction Id : ".$main_transaction_id."</p>";
        			}
					
										
					
        			if($http_code == "201" || $http_code == "200") //if operation successful
        			{
        					
        				
        		try{
        				$user=$this->real_exist_model->create_user($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$email);
        				
        				$cm_user=$this->real_exist_model->create_crmuser($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$json_result->currency_id,$json_result->currency_code,$json_result->accountId,$json_result->tradingPlatformAccountId,$date,$firstname,$lastname,$email,$country,$phone_country_code,$phone,$account_type,$platform_type);
        		}
        		catch(Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        			"REAL EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        		}
        		
        		$fake_data=fake_data();
        		
        				$userinfo = array (
        				
        						'user_login' => $json_result->tradingPlatformAccountName,
        						'user_nicename' => $fake_data['fname'],
        						'user_email' =>$fake_data['email'],
        						'user_pass' => $json_result->tradingPlatformAccountPassword,
        						'display_name' => 'real',
        				);
        				
        				$user_id = wp_insert_user ( $userinfo );
        				$wp_user = new WP_User ( $user_id );
        				$wp_user->set_role ( 'real' );
        				
        				$users = get_user_by ( 'login', $json_result->tradingPlatformAccountName );
        				
        				if (! is_wp_error ( $users )) {
        					wp_clear_auth_cookie ();
        					wp_set_current_user ( $users->ID );
        					wp_set_auth_cookie ( $users->ID );
        				
        				
        				
        				
        				}
        				
        		
        				
        		
        				$_SESSION['BUSINESS_UNIT'] = $BUSINESS_UNIT_NAME;
        				$_SESSION['platform_type'] = $platform_type;
        		
        				if($this->user_model->resolve_user_login($json_result->tradingPlatformAccountName, $json_result->tradingPlatformAccountPassword))
        				{
        					try{
        					$user_id = $this->user_model->get_user_id_from_username($json_result->tradingPlatformAccountName);
        					$user= $this->user_model->get_user($user_id);
        					}
        					catch(Exception $e)
        					{
        						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        						"REAL EXIST DB EXCEPTION: ".json_encode($e).PHP_EOL
        						."-------------------------".PHP_EOL."\n";
        						log_message('error',$log);
        					}
        				
        		if(is_object($user))
        		{
        			// set session user datas
        			$_SESSION['user_id']      = (int)$user->id;
        			$_SESSION['username']     = (string)$user->username;
        			$_SESSION['logged_in']    = (bool)true;
        			$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
        			//$_SESSION['is_admin']     = (bool)$user->is_admin;
        			$_SESSION['user_role']     = (string)$user->user_role;
        		
        			$_SESSION['fullname']     = $firstname." ".$lastname;
        		//	redirect($this->uri->segment(1).'/real_exist/thankyou');
        		//	echo "<p id='pop_mes'>".lang("Real Account Registered Successfully")."</p><p>Username: ".$json_result->tradingPlatformAccountName."</p><p>Password: ".$json_result->tradingPlatformAccountPassword."</p>";
        		//	$_SESSION['pop_mes'] = "<p>".lang("Real Account Registered Successfully")."</p><p>Username: ".$json_result->tradingPlatformAccountName."</p><p>Password: ".$json_result->tradingPlatformAccountPassword."</p>";
        		//	redirect ( $this->uri->segment ( 1 ) . '/Dashboard');
        		redirect($this->uri->segment(1).'/live-account-registration/thankyou');
        		}
        		else 
        		{       			
        			
        		//	$_SESSION['pop_mes'] = "User Not Saved";
        		//	popup();
        		//	echo "<p id='pop_error_mes'>User Not Saved</p>";
        			$_SESSION['error_pop_mes'] = "User Not Saved";
        			redirect ( $this->uri->segment ( 1 ) . '/real-exist');
        		
        		}
        				}
        				
        		
        			}
        		}
        		catch (Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        			"REAL EXIST EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        			//redirect("errors");
        		//	echo "<p id='pop_error_mes'>".$e."</p>";
        			$_SESSION['error_pop_mes'] = $e;
        			redirect ( $this->uri->segment ( 1 ) . '/real-exist');
        		}
      		
        		}
        		else
        		{
        			//redirect($this->uri->segment(1).'/real_exist');
        			redirect ( $this->uri->segment ( 1 ) . '/real-exist');
        		}
        
        }	
        }
        
        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
        
        
        
}