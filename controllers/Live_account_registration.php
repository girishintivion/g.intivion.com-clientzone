<?php
class Live_account_registration extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Common_model');
		$this->load->model('real_model');
		$this->load->model('user_model');
		$this->load->helper('url_helper');
		$this->load->helper(array('url'));
		$this->load->library('CrmRealRequestModel');
		
		
		$this->load->helper('prodconfig');
		
		$uri_1 = $this->uri->segment(1);
		all_lang($uri_1);
		
	}

	
	public function countrychange()
	{
		$country_code = $_GET['q'];
		
		$pco_code = $this->Common_model->get_phone_code($country_code);
		
		echo $data['pco'] =$pco_code['dialing_code'];
		
	}
	

	public function checkspecialchar($field_value)
	{
		if (preg_match('/[\'^Â£$%&*()}{@#~?><>,|=_+Â¬-]/', $field_value)){
			$this->form_validation->set_message('checkspecialchar', "Please provide valid details");
			return FALSE;
		}
		else{
			
			return TRUE;
		}
		
		
	}
	public function testing1()
	{
		echo api_url;
}
	public function index()
	{
	
		if(isset($_SESSION['logged_in']))
		{
			redirect($this->uri->segment(1).'/real-exist'); 
		}
		
		global $blocked_IPs;
		if (in_array(get_client_ip(), $blocked_IPs)){
			$_SESSION['error_pop_mes'] = "This IP has been blocked for registration.";
			redirect($this->uri->segment(1).'/live-account-registration');
			exit();
		}
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->helper('url');
		$this->load->helper('prodconfig');
		
		$country_code = get_country_details();
		$country_name=get_country_full_name($country_code);
		
		$pco_code = $this->real_model->get_phone_code($country_code);
		
		$data['country_name'] = $country_name;
		$data['country_code'] = $country_code;
		$data['pco'] =$pco_code['dialing_code'];
		$data['country'] = $this->real_model->get_countries();
		

		
		$data['title'] = 'Real Account Step 1';
		
		$this->form_validation->set_rules('firstname', 'lang:Full Name', 'trim|required|callback_checkspecialchar');
	//	$this->form_validation->set_rules('lastname', 'lang:Last Name', 'trim|required|callback_checkspecialchar');
		$this->form_validation->set_rules('email', 'lang:Email ID', 'trim|required|valid_email');
		$this->form_validation->set_rules('password_confirmation', 'lang:Password', 'trim|required|min_length[6]|max_length[20]|alpha_numeric|matches[password]');
		$this->form_validation->set_rules('password', 'lang:Confirm Password', 'trim|required|min_length[6]|max_length[20]|alpha_numeric');
		$this->form_validation->set_rules('country', 'lang:Country', 'trim|required');
		$this->form_validation->set_rules('country_code', 'lang:DialingCode', 'trim|required');
		$this->form_validation->set_rules('phone', 'lang:Phone Number', 'trim|required|numeric');
		$this->form_validation->set_rules('dob', 'lang:DOB', 'trim|required');
		$this->form_validation->set_rules('currency', 'lang:Currency', 'trim|required');
		$this->form_validation->set_rules('check1', 'lang:Terms', 'trim|required');
		
		
		if($this->form_validation->run() === FALSE)
		{

			$data['language'] = $this->uri->segment(1);
			
			$this->load->view('templates/before-login-header', $data );
			$this->load->view('traders_room/real_account/real-account', $data);
			$this->load->view('templates/before-login-footer');
			
		}
		else
		{
			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
			"Step 1 and 2 POST: ".json_encode($_POST).PHP_EOL
			."-------------------------".PHP_EOL."\n";
			log_message('custom',$log);

			$token = $this->input->post('my_token_real_step1');
			
			$session_token=null;
			
			$session_token = $_SESSION['form_token_real_step1'];
			unset($_SESSION['form_token_real_step1']);
			
			if(!empty($token) != $session_token)
			{
				$_SESSION['error_pop_mes'] = "Invalid Session.";
				redirect($this->uri->segment(1).'/live-account-registration');		
			}
			
			$firstname=trim($this->input->post('firstname')," ");
			$lastname= trim($this->input->post('lastname')," ");
			
			/*
			$name=$this->input->post('firstname');
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
			$account_type =$this->input->post('acc_type');
			
			$date=date_format(date_create_from_format('d/m/Y', $this->input->post('dob')), 'Y-m-d\TH:i:s\Z');
			//getBUDetailsCreateRealAcc($language,$currency);
			
			$count = get_acc_count($email);
			if($count >= 1)
			{
			    $_SESSION['pop_mes'] = lang("Account already exists with this Email");
			    //popup();
			    redirect($this->uri->segment(1)."/login");
			}
			
			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
			getBUDetailsCreateRealAcc($language,$currency,'1',strtolower($platform_type));
		
			
			$request = new CrmRealRequestModel();
			$request->tradingPlatformId =$REAL_PLATFORM_ID;
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
			$request->groupName =$REAL_PLATFORM_GROUP;
			$request->loggedInAccountId ="";
			$request->dateOfBirth= date_format(date_create_from_format('d/m/Y', $this->input->post('dob')), 'd-m-Y');
			$request->password=$password;
			$request->city = "";
			$request->address = "";
			$request->accountTypeValue= $account_type;
			//	$request->tag1=$this->input->post('promocode');
			
			$request->tag1= $promocode;
			$date = $this->input->post('dob');
			
			//print"<pre>"; print_r($request); print"</pre>";
			//exit();
			
			//	date_default_timezone_set("America/New_York");
			$current_time=  date("h:i:sa");		
			//$request->additionalinfo1 = $current_time;
			/*
			$ip = get_client_ip();
			$ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
			$ipInfo = json_decode($ipInfo);
			$timezone = $ipInfo->timezone;
			date_default_timezone_set($timezone);
			//echo date_default_timezone_get();
			// echo date('Y/m/d H:i:s');
			$current_time = date('H:i:s a');
			*/
			$request->new_clientlocaltime = $current_time;
			
			if (isset($_COOKIE['affid']))
			{
				if($_COOKIE['affid'] != "null")
				{
					$request->affiliate=$_COOKIE['affid'];
				}
			}
			
			
			if (isset($_COOKIE['cxd']))
			{
				if($_COOKIE['cxd'] != "null")
				{
					$request->affiliateTransactionId=$_COOKIE['cxd'];
				}
			}
			
			
			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
			"REAL REQUEST: ".json_encode($request).PHP_EOL
			."-------------------------".PHP_EOL."\n";
			log_message('custom',$log);
			
			$method = "Live Account Registration";
			$crmurl = api_url."/registerrealaccount";
			$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
												 
			$json_result = $update_mcrm['json_result'];
			$http_code = $update_mcrm['http_code'];
								 
			$main_transaction_id = main_transaction_id();

			$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
			"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
			"HTTP CODE:".$http_code."REAL RESPONSE: ".json_encode($json_result).PHP_EOL
			."-------------------------".PHP_EOL."\n";
			log_message('custom',$log);
			
			if($http_code == "400" || $http_code == "404")
			{
				
				$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
				redirect ( $this->uri->segment ( 1 ) . '/live-account-registration');
				
			}
			if($http_code == "201" || $http_code == "200") //if operation successful
			{
				
				//////// service //////
				
				/*$cURLConnection = curl_init();
				
				curl_setopt($cURLConnection, CURLOPT_URL, 'https://doc-uploader.azurewebsites.net/api/auth/EmailWebhook/ACC015B9C0F1B6A831C399E269772661/'.$email);
				curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
				
				$docuploaderresponse = curl_exec($cURLConnection);
				$curl_infodoc = curl_getinfo ( $cURLConnection);
				curl_close($cURLConnection);
				
				$http_codedoc = $curl_infodoc['http_code'];
				
				$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
				"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"HTTP CODE:".$http_codedoc."doc-uploader response: ".$docuploaderresponse.PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
				*/
				
				/// service end ///////
				
				
				
				try{
					$user=$this->real_model->create_users($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$email);
					
					$cm_user=$this->real_model->create_crmuser($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$json_result->currency_id,$json_result->currency_code,$json_result->accountId,$json_result->tradingPlatformAccountId,$date,$this->input->post('country'),$city,$address,$platform_type,$firstname,$lastname);
					
					
				}
				catch(Exception $e)
				{
					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
					"REAL DB EXCEPTION: ".json_encode($e).PHP_EOL
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
						'display_name' => 'real'
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
				
				
				if($this->user_model->resolve_user_login($json_result->tradingPlatformAccountName, $json_result->tradingPlatformAccountPassword))
				{
					try{
						$user_id = $this->user_model->get_user_id_from_username($json_result->tradingPlatformAccountName);
						$user= $this->user_model->get_user($user_id);
						
					}
					catch(Exception $e)
					{
						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
						"REAL DB EXCEPTION: ".json_encode($e).PHP_EOL
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

						redirect($this->uri->segment(1).'/live-account-registration/thankyou');
					//redirect($this->uri->segment(1).'/cashier6');
					
					}
					else
					{
						$_SESSION['error_pop_mes'] = lang("User Not Saved");
						redirect ( $this->uri->segment ( 1 ) . '/live-account-registration');
						
						
					}
				}
			}
			
		}
		
		
		
	}
	
	public function captcha_contact()
	{
		if(isset ($_SESSION['vercode011']))
		{
			unset($_SESSION['vercode011']);
		}
		
		$ranStr = md5(uniqid(rand(), TRUE));
		$_SESSION["vercode011"] = $ranStr;
		echo $ranStr;
		
	}
	
	public function captcha_lp()
	{
		if(isset ($_SESSION['vercode011']))
		{
			unset($_SESSION['vercode011']);
		}
		
		$ranStr = md5(uniqid(rand(), TRUE));
		$_SESSION["vercode011"] = $ranStr;
		echo $ranStr;
		
	}
	

	public function quickreal()
	{
				
		if(isset($_SESSION['logged_in']))
		{
			redirect($this->uri->segment(1).'/real-exist');
		}
		
		global $blocked_IPs;
		if (in_array(get_client_ip(), $blocked_IPs)){
			$_SESSION['error_pop_mes'] = "This IP has been blocked for registration.";
			redirect($this->uri->segment(1).'/live-account-registration');
			exit();
		}
	//		print $_SESSION["vercode011"]."=". $_POST["captchacode011"];exit();
		if($_SESSION["vercode011"] != "")
		{
			
			if($_POST["captchacode011"] != "")
			{
				if($_SESSION["vercode011"] == $_POST["captchacode011"])
				{
		
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
		
		
		
		$data['title'] = 'Real Account Step 1';
		
		$this->form_validation->set_rules('firstname', 'lang:First Name', 'trim|required|callback_checkspecialchar');
		$this->form_validation->set_rules('email', 'lang:Email ID', 'trim|required|valid_email');
		$this->form_validation->set_rules('country', 'lang:Country', 'trim|required');
		$this->form_validation->set_rules('country_code', 'lang:DialingCode', 'trim|required');
		$this->form_validation->set_rules('phone', 'lang:Phone Number', 'trim|required|numeric');
		$this->form_validation->set_rules('currency', 'lang:Currency', 'trim|required');
		$this->form_validation->set_rules('check1', 'lang:Terms', 'trim|required');
		
		
		if($this->form_validation->run() === FALSE)
		{
			
			$data['language'] = $this->uri->segment(1);
			
			$this->load->view('templates/before-login-header', $data );
			$this->load->view('traders_room/real_account/real-account', $data);
			$this->load->view('templates/before-login-footer');
			
		}
		else
		{
			
	//		$token = $this->input->post('my_token_real_step1');			
	//		$session_token=null;			
	//		$session_token = $_SESSION['form_token_real_step1'];
	//		unset($_SESSION['form_token_real_step1']);
			
	//		if(!empty($token) != $session_token)
	//		{
	//			$_SESSION['error_pop_mes'] = "Invalid Session.";
	//			redirect($this->uri->segment(1).'/live-account-registration');
	//		}
			
            /*
			$name=$this->input->post('firstname');
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
	
	        $firstname =$this->input->post('firstname');
	        $lastname =$this->input->post('lastname');
	
			$email=$this->input->post('email');
			
			$language = $this->uri->segment(1);
			$password = $this->input->post('password');
			$phone_country_code = $this->input->post('country_code');
			$phone = $this->input->post('phone');
			$country=$this->input->post('country');
		///	$account_type =$this->input->post('acc_type');
			$account_type ='1';
			
			/// $currency='USD';
			$currency=$this->input->post('currency');
			$platform_type='mt4';
			
			
			$count = get_acc_count($email);
			if($count >= 1)
			{
			    $_SESSION['pop_mes'] = lang("Account already exists with this Email");
			    //popup();
			    redirect($this->uri->segment(1)."/login");
			}
			
			
			/*
			$date=date_format(date_create_from_format('d/m/Y', $this->input->post('dob')), 'd-m-Y');
			//getBUDetailsCreateRealAcc($language,$currency);
			*/
			
			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
			getBUDetailsCreateRealAcc($language,$currency,'1',strtolower($platform_type));
			
			
			$request = new CrmRealRequestModel();
			$request->tradingPlatformId =$REAL_PLATFORM_ID;
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
			$request->groupName =$REAL_PLATFORM_GROUP;
			$request->loggedInAccountId ="";
	//		$request->dateOfBirth= date_format(date_create_from_format('d/m/Y', $this->input->post('dob')), 'd-m-Y');
			$request->password=$password;
			$request->city = "";
			$request->address = "";
			$request->accountTypeValue= $account_type;
			//	$request->tag1=$this->input->post('promocode');
			
			
			 
			
		//	date_default_timezone_set("America/New_York");
			$current_time=  date("h:i:sa");		
		//	$request->additionalinfo1 = $current_time;
		/*
			$ip = get_client_ip();
			$ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
			$ipInfo = json_decode($ipInfo);
			$timezone = $ipInfo->timezone;
			date_default_timezone_set($timezone);
			//echo date_default_timezone_get();
			// echo date('Y/m/d H:i:s');
			$current_time = date('H:i:s a');
			*/
		
			$request->new_clientlocaltime = $current_time;
			
			$request->tag1= $promocode;
			$date = $this->input->post('dob');
			
			if (isset($_COOKIE['affid']))
			{
				if($_COOKIE['affid'] != "null")
				{
					$request->affiliate=$_COOKIE['affid'];
				}
			}
			
			
			if (isset($_COOKIE['cxd']))
			{
				if($_COOKIE['cxd'] != "null")
				{
					$request->affiliateTransactionId=$_COOKIE['cxd'];
				}
			}
			
			$method = "Live Account Registration";
			$crmurl = api_url."/registerrealaccount";
			$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
												 
			$json_result = $update_mcrm['json_result'];
			$http_code = $update_mcrm['http_code'];
								 
			$main_transaction_id = main_transaction_id();

			if($http_code == "400" || $http_code == "404")
			{
				
				$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
				redirect ( $this->uri->segment ( 1 ) . '/live-account-registration');
				
			}
			if($http_code == "201" || $http_code == "200") //if operation successful
			{
				
				
				//////// service //////
				/*
				$cURLConnection = curl_init();
				
				curl_setopt($cURLConnection, CURLOPT_URL, 'https://doc-uploader.azurewebsites.net/api/auth/EmailWebhook/ACC015B9C0F1B6A831C399E269772661/'.$email);
				curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
				
				$docuploaderresponse = curl_exec($cURLConnection);
				$curl_infodoc = curl_getinfo ( $cURLConnection);
				curl_close($cURLConnection);
				
				$http_codedoc = $curl_infodoc['http_code'];
				
				$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
				"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"HTTP CODE:".$http_codedoc."doc-uploader response: ".$docuploaderresponse.PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
				*/
				
				/// service end ///////
				
				
				
				
				try{
					$user=$this->real_model->create_users($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$email);
					
					$cm_user=$this->real_model->create_crmuser($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$json_result->currency_id,$json_result->currency_code,$json_result->accountId,$json_result->tradingPlatformAccountId,$date,$this->input->post('country'),$city,$address,$platform_type,$firstname,$lastname);
					
					
				}
				catch(Exception $e)
				{
					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
					"REAL DB EXCEPTION: ".json_encode($e).PHP_EOL
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
						'display_name' => 'real'
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
				
				
				if($this->user_model->resolve_user_login($json_result->tradingPlatformAccountName, $json_result->tradingPlatformAccountPassword))
				{
					try{
						$user_id = $this->user_model->get_user_id_from_username($json_result->tradingPlatformAccountName);
						$user= $this->user_model->get_user($user_id);
						
					}
					catch(Exception $e)
					{
						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
						"REAL DB EXCEPTION: ".json_encode($e).PHP_EOL
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
						
						 redirect($this->uri->segment(1).'/live-account-registration/thankyou');
						
						//redirect($this->uri->segment(1).'/cashier6');
						
					}
					else
					{
						$_SESSION['error_pop_mes'] = lang("User Not Saved");
						redirect ( $this->uri->segment ( 1 ) . '/live-account-registration');
						
						
					}
				}
			}
			
		}
		
				}else{
					redirect(wp_site_url);
				}
			}else{
				redirect(wp_site_url);
			}
		}else{
			redirect(wp_site_url);
			
		}
		
		
	}

	
	
	public function thankyou()
	{
		
		if(!isset($_SESSION['logged_in']))
		{
			redirect($this->uri->segment(1).'/Login');
		}

		$get_crm_details= $this->real_model->get_crm_details($_SESSION['username']);
		
		$data['platform']= $get_crm_details[0]->platform;
		$data['login']= $get_crm_details[0]->name;
		$data['password']= $get_crm_details[0]->secret_data;
	
		
						$this->load->view('templates/before-login-header', $data );
						$this->load->view('traders_room/real_account/thankyou-real', $data);
						$this->load->view('templates/before-login-footer');

	}
	
	
	public function lead_creation()
	{
		// 	$myfile = fopen(logger_url_txt."lead_reg.txt", "a") or die("Unable to open file!");
		
		// 	fwrite($myfile,"values recieved[".date('Y-m-d H:i:s')."] = ".json_encode($_POST)."\n\n");
		
		
		$firstname=$_POST['first_name'];
		$lastname=$_POST['last_name'];
		$email=$_POST['email'];
		$country=$_POST['country'];
		$pco=$_POST['prefix'];
		$phone_no=$_POST['phone'];
		$aid_lp=$_POST['aid_lp'];
		$cxd_lp=$_POST['cxd_lp'];
		
		$referrer=$_POST['referrer'];
		
		
		
		
		//$myfile = fopen("lead_reg.txt", "a") or die("Unable to open file!");
		
		getBUDetailsCreateRealAcc("en","USD","1","sirix");
		
		global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
		
		
		$request = new CrmLeadUserModel();
		//$request->TradingPlatformId =$REAL_PLATFORM_ID;
		$request->firstName =$firstname;
		$request->lastName =$lastname;
		$request->email = $email;
		$request->phoneCountryCode = $pco;
		$request->phoneNumber =$phone_no;
		$request->countryCode = $country;
		$request->phoneAreaCode ="0";
		$request->ipAddress = get_client_ip();               /* Fix implemented by Ramakant on 08-08-2017 for the getting two ips problem  */
		$request->organizationName = ORGANIZATION_NAME;
		$request->ownerUserId =$OWNER_USER_ID;
		$request->businessUnitName =$BUSINESS_UNIT_NAME;
		
		if($_POST['lp_name']=="testimonial")
		{
			$request->tag="eBay campaign";
			$tag = "eBay campaign";
		}
		else
		{
			$request->affiliate=$aid_lp;
			$tag = $aid_lp;
		}
		
		
		$request->affiliateTransactionId=$cxd_lp;
		
		$request->linkId=$referrer;
		
		$request->referrer=$referrer;
		
		//print"<pre>"; print_r($request); print"</pre>";
		
		
		//	date_default_timezone_set("America/New_York");
		$current_time=  date("h:i:sa");
	//	$request->additionalinfo1 = $current_time;
	/*
		$ip = get_client_ip();
		$ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
		$ipInfo = json_decode($ipInfo);
		$timezone = $ipInfo->timezone;
		date_default_timezone_set($timezone);
		//echo date_default_timezone_get();
		// echo date('Y/m/d H:i:s');
		$current_time = date('H:i:s a');
		*/
		$request->new_clientlocaltime = $current_time;
		
		fwrite($myfile,"create lead req[".date('Y-m-d H:i:s')."] = ".json_encode($request)."\n\n");
		
		$method = "Live Account Registration";
		$crmurl = api_url."/RegisterLeadAccount";
		$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
												 
		$json_result = $update_mcrm['json_result'];
		$curl_error = $update_mcrm['curl_error'];
		$curl_info = $update_mcrm['curl_info'];

		fwrite($myfile,"create lead res[".date('Y-m-d H:i:s')."] = ".json_encode($json_result)."\n\n");
		fwrite($myfile,"create lead err[".date('Y-m-d H:i:s')."] = ".json_encode($curl_error)."\n\n");
		fwrite($myfile,"create lead info[".date('Y-m-d H:i:s')."] = ".json_encode($curl_info)."\n\n");
		fclose($myfile);
		
		$http_code = $update_mcrm['http_code'];
		
		if($http_code == "400" || $http_code == "404")
		{
			redirect($this->uri->segment(1).'/errors');
		}
		
		if($http_code == "201" || $http_code == "200") //if operation successful
		{
			
			
			$_SESSION['fname']=$firstname;
			$_SESSION['lname']=$lastname;
			$_SESSION['email']=$email;
			$_SESSION['phone']=$phone_no;
			
			redirect($this->uri->segment(1).'/live-account-registration');
			
			
			
		}
		
		
		
	}
	
	
	function lp_submit()
	{
		/*
		if(isset($_SESSION['logged_in']))
		{
			redirect($this->uri->segment(1).'/real-exist');
		}
		*/
		
		global $blocked_IPs;
		if (in_array(get_client_ip(), $blocked_IPs)){
			$_SESSION['error_pop_mes'] = "This IP has been blocked for registration.";
			redirect($this->uri->segment(1).'/live-account-registration');
			exit();
		}
	
		if($_SESSION["vercode011"] != "")
		{
			
			if($_POST["captchacode011"] != "")
			{
				if($_SESSION["vercode011"] == $_POST["captchacode011"])
				{
					
					$this->load->helper('form');
					$this->load->library('form_validation');
					$this->load->helper('url');
					$this->load->helper('prodconfig');
					
					$country_code = get_country_details();
					$country_name=get_country_full_name($country_code);
					
					$pco_code = $this->real_model->get_phone_code($country_code);
					
					$data['country_name'] = $country_name;
					$data['country_code'] = $country_code;
					$data['pco'] =$pco_code['dialing_code'];
					$data['country'] = $this->real_model->get_countries();
					
					
					
					$data['title'] = 'Real Account Step 1';
					
					$this->form_validation->set_rules('firstname', 'lang:First Name', 'trim|required|callback_checkspecialchar');
					$this->form_validation->set_rules('email', 'lang:Email ID', 'trim|required|valid_email');
					$this->form_validation->set_rules('country', 'lang:Country', 'trim|required');
					$this->form_validation->set_rules('country_code', 'lang:DialingCode', 'trim|required');
					$this->form_validation->set_rules('phone', 'lang:Phone Number', 'trim|required|numeric');
					$this->form_validation->set_rules('currency', 'lang:Currency', 'trim|required');
					$this->form_validation->set_rules('check1', 'lang:Terms', 'trim|required');
					
					
					if($this->form_validation->run() === FALSE)
					{
						
						$data['language'] = $this->uri->segment(1);
						
						$this->load->view('templates/before-login-header', $data );
						$this->load->view('traders_room/real_account/real-account', $data);
						$this->load->view('templates/before-login-footer');
						
					}
					else
					{
						
						$firstname =$this->input->post('firstname');
						$lastname =$this->input->post('lastname');
						
						$email=$this->input->post('email');
						
						$language = $this->uri->segment(1);
						$password = $this->input->post('password');
						$phone_country_code = $this->input->post('country_code');
						$phone = $this->input->post('phone');
						$country=$this->input->post('country');
						///	$account_type =$this->input->post('acc_type');
						$account_type ='1';
						
						/// $currency='USD';
						$currency=$this->input->post('currency');
						$platform_type='mt4';
						
						
						
						$count = get_acc_count($email);
						if($count >= 1)
						{
						    $_SESSION['pop_mes'] = lang("Account already exists with this Email");
						    //popup();
						//    redirect($this->uri->segment(1)."/login");
						    redirect("b-trade.io/lp/lp1/?status=email_exist");
						}
						
						/*
						 $date=date_format(date_create_from_format('d/m/Y', $this->input->post('dob')), 'd-m-Y');
						 //getBUDetailsCreateRealAcc($language,$currency);
						 */
						
						global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
						getBUDetailsCreateRealAcc($language,$currency,'1',strtolower($platform_type));
						
						
						$request = new CrmRealRequestModel();
						$request->tradingPlatformId =$REAL_PLATFORM_ID;
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
						$request->groupName =$REAL_PLATFORM_GROUP;
						$request->loggedInAccountId ="";
				//		$request->dateOfBirth= date_format(date_create_from_format('d/m/Y', $this->input->post('dob')), 'd-m-Y');
						$request->password=$password;
						$request->city = "";
						$request->address = "";
						$request->accountTypeValue= $account_type;
						$request->tag=$this->input->post('affiliate');
						$request->language=$this->input->post('language');
						
					//	$request->tag1=$this->input->post('promocode');
						
						
						
						//	date_default_timezone_set("America/New_York");
						$current_time=  date("h:i:sa");
					//	$request->additionalinfo1 = $current_time;
                    
                    /*
						$ip = get_client_ip();
						$ipInfo = file_get_contents('http://ip-api.com/json/' . $ip);
						$ipInfo = json_decode($ipInfo);
						$timezone = $ipInfo->timezone;
						date_default_timezone_set($timezone);
						//echo date_default_timezone_get();
						// echo date('Y/m/d H:i:s');
						$current_time = date('H:i:s a');
						*/
						$request->new_clientlocaltime = $current_time;
						
					//	$request->tag1= $promocode;
						$date = $this->input->post('dob');
						
						if (isset($_COOKIE['affid']))
						{
							if($_COOKIE['affid'] != "null")
							{
								$request->affiliate=$_COOKIE['affid'];
							}
						}
						
						
						if (isset($_COOKIE['cxd']))
						{
							if($_COOKIE['cxd'] != "null")
							{
								$request->affiliateTransactionId=$_COOKIE['cxd'];
							}
						}
						
						$method = "Live Account Registration";
						$crmurl = api_url."/registerrealaccount";
						$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
															
						$json_result = $update_mcrm['json_result'];
						$http_code = $update_mcrm['http_code'];
											
						$main_transaction_id = main_transaction_id();

						if($http_code == "400" || $http_code == "404")
						{
							
							$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
							redirect ( $this->uri->segment ( 1 ) . '/live-account-registration');
							
						}
						if($http_code == "201" || $http_code == "200") //if operation successful
						{
							
							try{
								$user=$this->real_model->create_users($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$email);
								
								$cm_user=$this->real_model->create_crmuser($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword,$BUSINESS_UNIT_NAME,$json_result->currency_id,$json_result->currency_code,$json_result->accountId,$json_result->tradingPlatformAccountId,$date,$this->input->post('country'),$city,$address,$platform_type,$firstname,$lastname);
									
							}
							catch(Exception $e)
							{
								$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
								"LP1 DB EXCEPTION: ".json_encode($e).PHP_EOL
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
									'display_name' => 'real'
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
							
							
							if($this->user_model->resolve_user_login($json_result->tradingPlatformAccountName, $json_result->tradingPlatformAccountPassword))
							{
								try{
									$user_id = $this->user_model->get_user_id_from_username($json_result->tradingPlatformAccountName);
									$user= $this->user_model->get_user($user_id);
									
								}
								catch(Exception $e)
								{
									$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
									"LP1 DB EXCEPTION: ".json_encode($e).PHP_EOL
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
									
									redirect($this->uri->segment(1).'/live-account-registration/thankyou');
								}
								else
								{
									$_SESSION['error_pop_mes'] = lang("User Not Saved");
									redirect ( $this->uri->segment ( 1 ) . '/live-account-registration');
									
									
								}
							}
						}
						
					}
					
				}else{
					redirect(wp_site_url);
				}
			}else{
				redirect(wp_site_url);
			}
		}else{
			redirect(wp_site_url);
			
		}
		
		
		
	}

		   
        
}
