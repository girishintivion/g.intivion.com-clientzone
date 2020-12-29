<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/requestparam.php');
/**
 * User class.
 * 
 * @extends CI_Controller
 */
class Login extends CI_Controller {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		parent::__construct();
		$this->load->library(array('session'));
		$this->load->helper(array('url'));
		$this->load->model('user_model');
		$this->load->helper('prodconfig');
                
        $uri_1 = $this->uri->segment(1);
        all_lang($uri_1);

	}
	
	

	
	
	/**
	 * login function.
	 *
	 * @access public
	 * @return void
	 */
	public function index() {
		
		global $blocked_IPs;
		if (in_array(get_client_ip(), $blocked_IPs)){
			echo lang("This IP has been blocked further");
			exit;
		}
		
		if(isset($_SESSION['acc_bu_not_exist']))
		{
			
			$_SESSION['error_pop_mes'] = "Account does not exist";
			//	popup();
			unset($_SESSION['acc_bu_not_exist']);
		}
		
		
		
		//global $logger;
		$data['title'] = 'Login';
		
		if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
		{
			redirect($this->uri->segment(1).'/dashboard');
		}
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == false) {
			
			///	$this->load->view('templates/header', $data );
			$this->load->view('templates/before-login-header', $data );
			$this->load->view('user/login/login');
			///	$this->load->view('templates/footer2');
			$this->load->view('templates/before-login-footer');
			
			
			
		} else {		
			if(!empty($_POST['remember_me']))
			{
				setcookie("acc_user",$_POST['username'],time() + (86400 * 180),"/");
				setcookie("acc_pwd",$_POST['password'],time() + (86400 * 180),"/");
			}
			
			$token = $this->input->post('my_token_login');
			
			$session_token=null;
			
			$session_token = $_SESSION['form_token_login'];
			unset($_SESSION['form_token_login']);
			
			if(!empty($token)== $session_token)
			{

				$username = $this->input->post('username');
				
				$owning_bu=get_owning_bu($username);
//print_r($owning_bu);
				global $mybu;
				
				if (!in_array($owning_bu, $mybu))
				{
					$_SESSION['error_pop_mes'] = "Account does not exist";
					redirect($this->uri->segment(1).'/login');
					exit(0);
					
				}
				
				
				
				
				
				
				try
				{
					global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
					
					$this->load->helper('url');
					$this->load->helper('prodconfig');
					
					getParentBUDetails();
					
					//Login Account Request parameters
					$LoginAccount = new CrmLoginAccountModel();
					$LoginAccount->ownerUserId = $OWNER_USER_ID;
					$LoginAccount->tradingPlatformAccountName = $this->input->post('username');
					$LoginAccount->tradingPlatformAccountPassword = $this->input->post('password');
					$LoginAccount->organizationName = ORGANIZATION_NAME;
					$LoginAccount->businessUnitName = $BUSINESS_UNIT_NAME;
					
					
					/* send request to */
					$url = api_url."/customloginaccount";
					$ch = curl_init ();
					curl_setopt ( $ch, CURLOPT_URL, $url );
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
					curl_setopt ( $ch, CURLOPT_POST, TRUE );
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($LoginAccount));
					// WPF expect UTF-8 encoding
					curl_setopt ( $ch, CURLINFO_HEADER_OUT, TRUE );
					curl_setopt ( $ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8" ));
					$curl_result = curl_exec ( $ch ); // Call WPF
					$curl_error = curl_error ( $ch ); // Collect errors
					$curl_info = curl_getinfo ( $ch );
					curl_close ( $ch );
					
					$json_result = json_decode ($curl_result);
					
					$http_code = $curl_info ['http_code'];
					
					$main_transaction_id = main_transaction_id();
					
					if ($http_code == "400" || $http_code == "404")
					{
							
						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
						"LOGIN REQUEST: ".json_encode($LoginAccount).PHP_EOL
						."-------------------------".PHP_EOL."\n";
						log_message('custom',$log);
						
						$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
						"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
						"HTTP CODE:".$http_code."LOGIN RESPONSE: ".json_encode($json_result).PHP_EOL
						."-------------------------".PHP_EOL."\n";
						log_message('custom',$log);

						//$_SESSION['pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p><p>Sub Transaction Id : ".$json_result->requestId."</p><p>Reason : ".$json_result->message."</p>";
						
						//redirect($this->uri->segment(1).'/errors');
						$_SESSION['error_pop_mes'] = 'Incorrect Password';
						redirect($this->uri->segment(1).'/login');
						
						
					}
					if ($http_code == "201" || $http_code == "200")
					{
						/*
						 if(!empty($_POST['check_default']))
						 {
						 setcookie("acc_user",$this->input->post('username'),time() + (86400 * 180),"/");
						 setcookie("acc_pwd",$this->input->post('password'),time() + (86400 * 180),"/");
						 }
						 */
						
						$OwningBusinessUnit =$json_result->owningBusinessUnit;
						$email=$json_result->email;
						$_SESSION['BUSINESS_UNIT'] = $OwningBusinessUnit;
						
						$tparr =array(); //creating an array
						if(is_object($json_result))
						{
							$tparr[] =  $json_result;
						}
						else
						{
							$tparr = $json_result;
						}
						
						
						try{
							$user_id = $this->user_model->get_user_id_from_username($this->input->post('username'));
							$user    = $this->user_model->get_user($user_id);
						}
						catch(Exception $e)
						{
							$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
							"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
							."-------------------------".PHP_EOL."\n";
							log_message('error',$log);
						}
						
						
						
						global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
						getParentBUDetails();
						$request = new CrmGetAccountBalanceModel();
						
						$request->tradingPlatformAccountName =$_SESSION['username'];
						$request->organizationName =ORGANIZATION_NAME;
						$request->ownerUserId =$OWNER_USER_ID;
						$request->businessUnitName =$BUSINESS_UNIT_NAME;
						
						$method = "Login";
						$crmurl = api_url."/GetAccountBalance";
						$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
															
						$mgtapibalance_result = $update_mcrm['json_result'];
						$http_code = $update_mcrm['http_code'];
											
						$main_transaction_id = main_transaction_id();
						
						$_SESSION['equity'] = $mgtapibalance_result->equity;
						$_SESSION['currency'] = $ccode;
						
						if($user)
						{
							
							$data = array(
									'password' => $this->input->post('password'),
							);
							try{
								$user_update=$this->user_model->update_user($user_id,$data); // update user password
								
								$delete_records=$this->user_model->delete_crmuser($email); //detele all records from db
							}
							catch(Exception $e)
							{
								$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
								"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
								."-------------------------".PHP_EOL."\n";
								log_message('error',$log);
							}
							
							foreach($tparr as $acc)//account
							{
								
								$tpa_t = $acc->tpAccounts;
								
								
								$TradingPlatformAccountInfo_t = new TradingPlatformAccountInfo();
								$TradingPlatformAccountInfo_t = $tpa_t;
								
								$tparr_t =  array();
								if(is_object($TradingPlatformAccountInfo_t)) {
									$tparr_t[] =  $TradingPlatformAccountInfo_t;
								}
								else
								{
									$tparr_t = $TradingPlatformAccountInfo_t;
								}
								
								$cntry=$acc->country;
								
								$country=$this->user_model->get_iso2($cntry);// get iso2
								
								$email = $acc->email;
								$firstname = $acc->firstName;
								$lastname = $acc->lastName;
								$name = $firstname . ' ' . $lastname;
								$phone = $acc->phoneNumber;
								//$state = $acc->state;
								//$title = $acc->title;
								$mobilenumber = $acc->mobile;
								$address1 = $acc->address1;
								$address2 = $acc->address2;
								$date = $acc->dateOfBirth;
								$zip = $acc->zipCode;
								$city = $acc->city;
								$phonecountrycode = $acc->phoneCountryCode;
								$OwningBusinessUnit = $acc->owningBusinessUnit;
								/*echo"<br>";
								 echo $yyyy = substr($DateOfBirth, 5, 4 );
								 echo"<br>";
								 echo $mm = substr($DateOfBirth, 2, 2 );
								 echo"<br>";
								 echo $dd = substr($DateOfBirth, 8, 2 );
								 echo"<br>";
								 echo $date = gmDate ("Y-m-d\TH:i:s\Z", mktime( 0, 0, 0, $yyyy, $mm, $dd  ) );*/
								if($country == "")
								{
									$country = get_country_details();
									$phonecountrycode = $this->user_model->get_phone_code($country);
								}
								foreach ($tparr_t as $acc_t)//trading platform account
								{
									$ccode = $acc_t->currency;
									$ccode_id = $acc_t->currency_id;
									
									$trading_platform=$acc_t->tradingPlatformName;
									$account_type=$acc_t->type;
									
									$ty = $acc_t->type;
									$leverage=$acc_t->leverage;
									
									$account_Type = "REAL";
									if($ty == "Demo")
									{
										$account_Type = "DEMO";
									}
									
									
									
									if($account_Type == "DEMO")
									{
										//Inserting into table crm_user
										
										$data = array(
												'firstname' =>$firstname,
												'lastname' =>$lastname,
												'country'=>$country,
												'trading_platform'=>"DEMO",
												'account_type'=>$account_type,
												'currency'=>$ccode,
												'platform'=>$trading_platform,
												'secret_data'=>$this->input->post('password'),
												'phone_country_code'=>$phonecountrycode,
												'phone'=>$phone,
												'birth_date'=>$date,
												'email' =>$email,
												'trading_platform_accountid'=>$acc_t->tradingPlatformID,
												'account_id'=>$acc_t->parentAccountID,
												'business_unit'=>$OwningBusinessUnit,
												'currency_code'=>$ccode,
												'name'=> $acc_t->name,
												'leverage'=>$leverage,
												'currency_id'=>$ccode_id,
												'city'=>$city,
												'address1'=>$address1,
												'mobile'=>$mobilenumber,
												
										);
										try{
											$sql=$this->user_model->insert_crmuser($data);
										}
										catch(Exception $e)
										{
											$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
											"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
											."-------------------------".PHP_EOL."\n";
											log_message('error',$log);
										}
									}
									else if($account_Type == "REAL")
									{
										//Inserting into table crm_user
										
										$data = array(
												'firstname' =>$firstname,
												'lastname' =>$lastname,
												'country'=>$country,
												'trading_platform'=> "REAL",
												'account_type'=>$account_type,
												'currency'=> $ccode,
												'platform'=>$trading_platform,
												'secret_data'=>$this->input->post('password'),
												'phone_country_code'=>$phonecountrycode,
												'phone'=>$phone,
												'birth_date'=>$date,
												'email' =>$email,
												'trading_platform_accountid'=>$acc_t->tradingPlatformID,
												'account_id'=>$acc_t->parentAccountID,
												'business_unit'=>$OwningBusinessUnit,
												'currency_code'=>$ccode,
												'name'=> $acc_t->name,
												'leverage'=>$leverage,
												'city'=>$city,
												'address1'=>$address1,
												'currency_id'=>$ccode_id,
												
												'mobile'=>$mobilenumber,
												
												
										);
										try{
											$sql=$this->user_model->insert_crmuser($data);
										}
										catch(Exception $e)
										{
											$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
											"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
											."-------------------------".PHP_EOL."\n";
											log_message('error',$log);
										}
									}
								}
							}
							
							//$users = get_user_by ( 'login', $user->username );
							
							
							if($users)
							{
							
								if (! is_wp_error ( $users )) {
									wp_clear_auth_cookie ();
									wp_set_current_user ( $users->ID );
									wp_set_auth_cookie ( $users->ID );
								
								}
							}
							else {
								
								$fake_data=fake_data();
								
								$userinfo = array(
										
										'user_login' =>$this->input->post('username'),//username
										'first_name' => $fake_data['fname'],
										'last_name' => $fake_data['lname'],
										'user_email' => $fake_data['email'],
										'user_pass' => $this->input->post('password'),
										'role' => 'real'
								);
								
								$user_id = wp_insert_user( $userinfo );
								$wp_user = new WP_User( $user_id );
								$wp_user->set_role( 'real' );
								
								$user2 = get_user_by ( 'login', $this->input->post('username') );
								
								if (! is_wp_error ( $user2 )) {
									wp_clear_auth_cookie ();
									wp_set_current_user ( $user2->ID );
									wp_set_auth_cookie ( $user2->ID );
									
								}
								
							}
							
							// set session user datas
							$_SESSION['user_id']      = (int)$user->id;
							$_SESSION['username']     = (string)$user->username;
							$_SESSION['logged_in']    = (bool)true;
							$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
							//$_SESSION['is_admin']     = (bool)$user->is_admin;
							$_SESSION['user_role']     = (string)$user->user_role;
							
							$_SESSION['fname']     = $acc->firstName;
							$_SESSION['lname']     = $acc->lastName;
							$_SESSION['fullname']     = $acc->firstName." ".$acc->lastName;
							
							redirect($this->uri->segment(1).'/dashboard');
							exit();
						}
						else //if user not exist
						{
							foreach($tparr as $acc)
							{
								$tpa_t = $acc->tpAccounts;
								
								$TradingPlatformAccountInfo_t = new TradingPlatformAccountInfo();
								$TradingPlatformAccountInfo_t = $tpa_t;
								
								$tparr_t =  array();
								if(is_object($TradingPlatformAccountInfo_t)) {
									$tparr_t[] =  $TradingPlatformAccountInfo_t;
								}
								else
								{
									$tparr_t = $TradingPlatformAccountInfo_t;
								}
								
								foreach ($tparr_t as $acc_tt)
								{
									if($acc_tt->name == $username)
									{
										$acc_type=$acc_tt->type;
									}
								}
							}
							//-------------
							
							if($acc_type =="Demo")
							{
								
								$tparr =array();
								if(is_object($json_result)) {
									$tparr[] =  $json_result;
								}
								else
								{
									$tparr = $json_result;
								}
								
								$email=$json_result->email;
								try{
									$delete_records=$this->user_model->delete_crmuser($email); //detele all records from db
								}
								catch(Exception $e)
								{
									$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
									"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
									."-------------------------".PHP_EOL."\n";
									log_message('error',$log);
								}
								foreach($tparr as $acc)
								{
									$tpa_t = $acc->tpAccounts;
									
									$TradingPlatformAccountInfo_t = new TradingPlatformAccountInfo();
									$TradingPlatformAccountInfo_t = $tpa_t;
									
									$tparr_t =  array();
									if(is_object($TradingPlatformAccountInfo_t)) {
										$tparr_t[] =  $TradingPlatformAccountInfo_t;
									}
									else
									{
										$tparr_t = $TradingPlatformAccountInfo_t;
									}
									$cntry=$acc->country;
									
									$country=$this->user_model->get_iso2($cntry);// get iso2
									
									$email = $acc->email;
									$firstname = $acc->firstName;
									$lastname = $acc->lastName;
									
									$name = $firstname . ' ' . $lastname;
									$phone = $acc->phoneNumber;
									//$state = $acc->state;
									//$title = $acc->title;
									$address1 = $acc->address1;
									$address2 = $acc->address2;
									$date = $acc->dateOfBirth;
									$zip = $acc->zipCode;
									$city = $acc->city;
									$mobilenumber = $acc->mobile;
									
									$phonecountrycode = $acc->phoneCountryCode;
									$OwningBusinessUnit = $acc->owningBusinessUnit;
									/*
									 $yyyy = substr ( $DateOfBirth, 0, 4 );
									 $mm = substr ( $DateOfBirth, 5, 2 );
									 $dd = substr ( $DateOfBirth, 8, 2 );
									 $date = gmDate ( "Y-m-d\TH:i:s\Z", mktime ( 0, 0, 0, $mm, $dd, $yyyy ) );*/
									if($country == "")
									{
										$country = get_country_details();
										$phonecountrycode = $this->user_model->get_phone_code($country);
									}
									foreach ($tparr_t as $acc_t)
									{
										$ccode = $acc_t->currency;
										$ccode_id = $acc_t->currency_id;
										
										$trading_platform=$acc_t->tradingPlatformName;
										$account_type=$acc_t->type;
										
										
										$ty = $acc_t->type;
										$leverage=$acc_t->leverage;
										$account_Type = "REAL";
										if($ty == "Demo")
										{
											$account_Type = "DEMO";
										}
										
										
										if($account_Type == "DEMO")
										{
											//Inserting into table crm_user
											
											$data = array(
													'firstname' =>$firstname,
													'lastname' =>$lastname,
													'country'=>$country,
													'trading_platform'=>"DEMO",
													'account_type'=>$account_type,
													'currency'=> $ccode,
													'platform'=> $trading_platform,
													'secret_data'=>$this->input->post('password'),
													'phone_country_code'=>$phonecountrycode,
													'phone'=>$phone,
													'birth_date'=>$date,
													'email' =>$email,
													'trading_platform_accountid'=>$acc_t->tradingPlatformID,
													'account_id'=>$acc_t->parentAccountID,
													'business_unit'=>$OwningBusinessUnit,
													'currency_code'=>$ccode,
													'name'=> $acc_t->name,
													'leverage'=>$leverage,
													'currency_id'=>$ccode_id,
													'city'=>$city,
													'address1'=>$address1,
													'mobile'=>$mobilenumber,
													
											);
											try{
												$sql=$this->user_model->insert_crmuser($data);
											}
											catch(Exception $e)
											{
												$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
												"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
												."-------------------------".PHP_EOL."\n";
												log_message('error',$log);
											}
										}
										else if($account_Type == "REAL")
										{
											
											//Inserting into table crm_user
											
											$data = array(
													'firstname' =>$firstname,
													'lastname' =>$lastname,
													'country'=>$country,
													'trading_platform'=> "REAL",
													'account_type'=>$account_type,
													'currency'=>$ccode,
													'platform'=> $trading_platform,
													'secret_data'=>$this->input->post('password'),
													'phone_country_code'=>$phonecountrycode,
													'phone'=>$phone,
													'birth_date'=>$date,
													'email' =>$email,
													'trading_platform_accountid'=>$acc_t->tradingPlatformID,
													'account_id'=>$acc_t->parentAccountID,
													'business_unit'=>$OwningBusinessUnit,
													'currency_code'=>$ccode,
													'name'=> $acc_t->name,
													'leverage'=>$leverage,
													'currency_id'=>$ccode_id,
													'city'=>$city,
													'address1'=>$address1,
													'mobile'=>$mobilenumber,
													
											);
											try{
												$sql=$this->user_model->insert_crmuser($data);
											}
											catch(Exception $e)
											{
												$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
												"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
												."-------------------------".PHP_EOL."\n";
												log_message('error',$log);
											}
										}
										
									}
								}
								$type='DEMO';
								try{
									$create_user=$this->user_model->create_user($this->input->post('username'),$email,$this->input->post('password'),$type);
								}
								catch(Exception $e)
								{
									$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
									"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
									."-------------------------".PHP_EOL."\n";
									log_message('error',$log);
								}
								if($this->user_model->resolve_user_login($this->input->post('username'),$this->input->post('password')))
								{
									try{
										$user_id = $this->user_model->get_user_id_from_username($this->input->post('username'));
										$user= $this->user_model->get_user($user_id);
									}
									catch(Exception $e)
									{
										$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
										"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
										."-------------------------".PHP_EOL."\n";
										log_message('error',$log);
									}
									if($user)
									{
										
										$users = get_user_by ( 'login', $user->username );
										
										
										if($users)		{
											if (! is_wp_error ( $users )) {
												wp_clear_auth_cookie ();
												wp_set_current_user ( $users->ID );
												wp_set_auth_cookie ( $users->ID );
												
											}
											
										}
										else
										{
											$fake_data=fake_data();
											
											$userinfo = array (
													
													'user_login' => $this->input->post('username'),
													'user_nicename' => $fake_data['fname'],
													'user_email' =>$fake_data['email'],
													'user_pass' => $this->input->post('password'),
													'display_name' => 'demo'
											);
											
											$user_id = wp_insert_user ( $userinfo );
											$wp_user = new WP_User ( $user_id );
											$wp_user->set_role ( 'real' );
											
											
											$users = get_user_by ( 'login', $this->input->post('username') );
											
											if (! is_wp_error ( $users )) {
												wp_clear_auth_cookie ();
												wp_set_current_user ( $users->ID );
												wp_set_auth_cookie ( $users->ID );
											
											}
											
										}
										
										// set session user datas
										$_SESSION['user_id']      = (int)$user->id;
										$_SESSION['username']     = (string)$user->username;
										$_SESSION['logged_in']    = (bool)true;
										$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
										//$_SESSION['is_admin']     = (bool)$user->is_admin;
										$_SESSION['user_role']     = (string)$user->user_role;
										
										$_SESSION['fname']     = $acc->firstName;
										$_SESSION['lname']     = $acc->lastName;
										$_SESSION['fullname']     = $acc->firstName." ".$acc->lastName;
										
										
										
										
										$_SESSION['equity'] = $mgtapibalance_result->equity;
										$_SESSION['currency'] = $ccode;
										
										
										
										redirect($this->uri->segment(1).'/dashboard');
										exit();
									}
									
								}
								
							}
							else
							{
								
								$tparr =array();
								
								if(is_object($json_result)) {
									$tparr[] =  $json_result;
								}
								else
								{
									$tparr = $json_result;
								}
								
								$email=$json_result->email;
								try{
									$delete_records=$this->user_model->delete_crmuser($email); //detele all records from db
								}catch(Exception $e)
								{
									$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
									"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
									."-------------------------".PHP_EOL."\n";
									log_message('error',$log);
								}
								
								foreach($tparr as $acc)
								{
									$tpa_t = $acc->tpAccounts;
									
									$TradingPlatformAccountInfo_t = new TradingPlatformAccountInfo();
									$TradingPlatformAccountInfo_t = $tpa_t;
									
									$tparr_t =  array();
									if(is_object($TradingPlatformAccountInfo_t)) {
										$tparr_t[] =  $TradingPlatformAccountInfo_t;
									}
									else
									{
										$tparr_t = $TradingPlatformAccountInfo_t;
									}
									
									$cntry=$acc->country;
									
									$country=$this->user_model->get_iso2($cntry);// get iso2
									
									$email = $acc->email;
									$firstname = $acc->firstName;
									$lastname = $acc->lastName;
									$name = $firstname . ' ' . $lastname;
									$phone = $acc->phoneNumber;
									//$state = $acc->state;
									//$title = $acc->title;
									$address1 = $acc->address1;
									$address2 = $acc->address2;
									$DateOfBirth = $acc->dateOfBirth;
									$zip = $acc->zipCode;
									$city = $acc->city;
									$phonecountrycode = $acc->phoneCountryCode;
									$OwningBusinessUnit = $acc->owningBusinessUnit;
									
									$mobilenumber = $acc->mobile;
									
									$yyyy = substr ( $DateOfBirth, 0, 4 );
									$mm = substr ( $DateOfBirth, 5, 2 );
									$dd = substr ( $DateOfBirth, 8, 2 );
									$date = gmDate ( "Y-m-d\TH:i:s\Z", mktime ( 0, 0, 0, $mm, $dd, $yyyy ) );
									if($country == "")
									{
										$country = get_country_details();
										$phonecountrycode = $this->user_model->get_phone_code($country);
									}
									foreach ($tparr_t as $acc_t)
									{
										$ccode = $acc_t->currency;
										$ccode_id = $acc_t->currency_id;
										
										$trading_platform=$acc_t->tradingPlatformName;
										$account_type=$acc_t->type;
										
										
										$ty = $acc_t->type;
										$leverage=$acc_t->leverage;
										
										$account_Type = "REAL";
										if($ty == "Demo")
										{
											$account_Type = "DEMO";
										}
										
										if($account_Type == "DEMO")
										{
											//Inserting into table crm_user
											
											$data = array(
													'firstname' =>$firstname,
													'lastname' =>$lastname,
													'country'=>$country,
													'trading_platform'=>"DEMO",
													'account_type'=> $account_type,
													'currency'=> $ccode,
													'platform'=> $trading_platform,
													'secret_data'=>$this->input->post('password'),
													'phone_country_code'=>$phonecountrycode,
													'phone'=>$phone,
													'birth_date'=>$date,
													'email' =>$email,
													'trading_platform_accountid'=>$acc_t->tradingPlatformID,
													'account_id'=>$acc_t->parentAccountID,
													'business_unit'=>$OwningBusinessUnit,
													'currency_code'=>$ccode,
													'name'=> $acc_t->name,
													'leverage'=>$leverage,
													'currency_id'=>$ccode_id,
													'city'=>$city,
													'address1'=>$address1,
													'mobile'=>$mobilenumber,
													
											);
											try{
												$sql=$this->user_model->insert_crmuser($data);
											}catch(Exception $e)
											{
												$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
												"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
												."-------------------------".PHP_EOL."\n";
												log_message('error',$log);
											}
										}
										else if($account_Type == "REAL")
										{
											
											//Inserting into table crm_user
											
											$data = array(
													'firstname' =>$firstname,
													'lastname' =>$lastname,
													'country'=>$country,
													'trading_platform'=> "REAL",
													'account_type'=>$account_type,
													'currency'=>$ccode,
													'platform'=> $trading_platform,
													'secret_data'=>$this->input->post('password'),
													'phone_country_code'=>$phonecountrycode,
													'phone'=>$phone,
													'birth_date'=>$date,
													'email' =>$email,
													'trading_platform_accountid'=>$acc_t->tradingPlatformID,
													'account_id'=>$acc_t->parentAccountID,
													'business_unit'=>$OwningBusinessUnit,
													'currency_code'=>$ccode,
													'name'=> $acc_t->name,
													'leverage'=>$leverage,
													'currency_id'=>$ccode_id,
													'city'=>$city,
													'address1'=>$address1,
													'mobile'=>$mobilenumber,
													
											);
											try{
												$sql=$this->user_model->insert_crmuser($data);
											}catch(Exception $e)
											{
												$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
												"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
												."-------------------------".PHP_EOL."\n";
												log_message('error',$log);
											}
										}
									}
								}
								
								$type='REAL';
								
								//$this->input->post('username');
								//$email;
								try{
									$create_user=$this->user_model->create_user($this->input->post('username'),$email,$this->input->post('password'),$type);
								}catch(Exception $e)
								{
									$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
									"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
									."-------------------------".PHP_EOL."\n";
									log_message('error',$log);
								}
								
								if($this->user_model->resolve_user_login($this->input->post('username'),$this->input->post('password')))
								{
									try{
										$user_id = $this->user_model->get_user_id_from_username($this->input->post('username'));
										$user= $this->user_model->get_user($user_id);
									}
									catch(Exception $e)
									{
										$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
										"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
										."-------------------------".PHP_EOL."\n";
										log_message('error',$log);
									}
									
									if($user)
									{
										
										$users = get_user_by ( 'login', $user->username );
										
										if($users)
										{
											if (! is_wp_error ( $users )) {
												wp_clear_auth_cookie ();
												wp_set_current_user ( $users->ID );
												wp_set_auth_cookie ( $users->ID );
												
												
												
												
											}
										}
										
										else{
											
											$fake_data=fake_data();
											
											$userinfo = array (
													
													'user_login' => $this->input->post('username'),
													'user_nicename' => $fake_data['fname'],
													'user_email' =>$fake_data['email'],
													'user_pass' => $this->input->post('password'),
													'display_name' => 'real'
											);
											
											$user_id = wp_insert_user ( $userinfo );
											$wp_user = new WP_User ( $user_id );
											$wp_user->set_role ( 'real' );
											
											$users = get_user_by ( 'login', $this->input->post('username') );
											
											if (! is_wp_error ( $users )) {
												wp_clear_auth_cookie ();
												wp_set_current_user ( $users->ID );
												wp_set_auth_cookie ( $users->ID );
											
											}
											
										}
										
										// set session user datas
										$_SESSION['user_id']      = (int)$user->id;
										$_SESSION['username']     = (string)$user->username;
										$_SESSION['logged_in']    = (bool)true;
										$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
										//$_SESSION['is_admin']     = (bool)$user->is_admin;
										$_SESSION['user_role']     = (string)$user->user_role;
										$_SESSION['fname']     = $acc->firstName;
										$_SESSION['lname']     = $acc->lastName;
										
										$_SESSION['fullname']     = $acc->firstName." ".$acc->lastName;
										
										redirect($this->uri->segment(1).'/dashboard');
										exit();
									}
								}
							}
							redirect($this->uri->segment(1).'/dashboard');
							exit();
						}
					}
				}
				catch (Exception $e)
				{
					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
					"LOGIN EXCEPTION: ".json_encode($e).PHP_EOL
					."-------------------------".PHP_EOL."\n";
					log_message('error',$log);
					
					//	redirect($this->uri->segment(1).'/errors');
					$_SESSION['error_pop_mes'] = "<p>".json_encode($e)."</p>";
					
					redirect($this->uri->segment(1).'/Login');
				}
				
			}else
			{
				redirect($this->uri->segment(1).'/login');
			}
			
		}
	}
	
	
	
	public function autologin()
	{
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		
		
		
		$username = $_GET['username'];
		
		$owning_bu=get_owning_bu($username);
		
		global $mybu;
		
		if (!in_array($owning_bu, $mybu))
		{
			$_SESSION['acc_bu_not_exist'] = "Account does not exist.";
			redirect($this->uri->segment(1).'/login');
			exit(0);
			
		}
		
		
		
		
		
		
		try
		{
			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
			
			$this->load->helper('url');
			$this->load->helper('prodconfig');
			
			getParentBUDetails();
			
			//Login Account Request parameters
			$LoginAccount = new CrmLoginAccountModel();
			$LoginAccount->ownerUserId = $OWNER_USER_ID;
			$LoginAccount->tradingPlatformAccountName = $_GET['username'];
			$LoginAccount->tradingPlatformAccountPassword = $_GET['password'];
			$LoginAccount->organizationName = ORGANIZATION_NAME;
			$LoginAccount->businessUnitName = $BUSINESS_UNIT_NAME;
			
			/* send request to */
			$url = api_url."/customloginaccount";
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_URL, $url );
			curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $ch, CURLOPT_POST, TRUE );
			curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($LoginAccount));
			// WPF expect UTF-8 encoding
			curl_setopt ( $ch, CURLINFO_HEADER_OUT, TRUE );
			curl_setopt ( $ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=UTF-8" ));
			$curl_result = curl_exec ( $ch ); // Call WPF
			$curl_error = curl_error ( $ch ); // Collect errors
			$curl_info = curl_getinfo ( $ch );
			curl_close ( $ch );
			
			$json_result = json_decode ($curl_result);
			
			$http_code = $curl_info ['http_code'];
			
			$main_transaction_id = main_transaction_id();
			
			if ($http_code == "400" || $http_code == "404")
			{
				$_SESSION['pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p><p>Sub Transaction Id : ".$json_result->requestId."</p><p>Reason : ".$json_result->message."</p>";
				
				redirect($this->uri->segment(1).'/errors');
				
			}
			if ($http_code == "201" || $http_code == "200")
			{
				
				if(!empty($_POST['check_default']))
				{
					setcookie("acc_user",$_GET['username'],time() + (86400 * 180),"/");
					setcookie("acc_pwd",$_GET['password'],time() + (86400 * 180),"/");
				}
				
				
				$OwningBusinessUnit =$json_result->owningBusinessUnit;
				$email=$json_result->email;
				$_SESSION['BUSINESS_UNIT'] = $OwningBusinessUnit;
				
				$tparr =array(); //creating an array
				if(is_object($json_result))
				{
					$tparr[] =  $json_result;
				}
				else
				{
					$tparr = $json_result;
				}
				
				
				try{
					$user_id = $this->user_model->get_user_id_from_username($_GET['username']);
					$user    = $this->user_model->get_user($user_id);
				}
				catch(Exception $e)
				{
					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
					"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
					."-------------------------".PHP_EOL."\n";
					log_message('error',$log);
				}
				
				
				
				
				
				if($user)
				{
					$data = array(
							'password' => $_GET['password'],
					);
					try{
						$user_update=$this->user_model->update_user($user_id,$data); // update user password
						
						$delete_records=$this->user_model->delete_crmuser($email); //detele all records from db
					}
					catch(Exception $e)
					{
						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
						"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
						."-------------------------".PHP_EOL."\n";
						log_message('error',$log);
					}
					
					foreach($tparr as $acc)//account
					{
						
						$tpa_t = $acc->tpAccounts;
						
						
						$TradingPlatformAccountInfo_t = new TradingPlatformAccountInfo();
						$TradingPlatformAccountInfo_t = $tpa_t;
						
						$tparr_t =  array();
						if(is_object($TradingPlatformAccountInfo_t)) {
							$tparr_t[] =  $TradingPlatformAccountInfo_t;
						}
						else
						{
							$tparr_t = $TradingPlatformAccountInfo_t;
						}
						
						$cntry=$acc->country;
						
						$country=$this->user_model->get_iso2($cntry);// get iso2
						
						$email = $acc->email;
						$firstname = $acc->firstName;
						$lastname = $acc->lastName;
						$name = $firstname . ' ' . $lastname;
						$phone = $acc->phoneNumber;
						//$state = $acc->state;
						//$title = $acc->title;
						$mobilenumber = $acc->mobile;
						$address1 = $acc->address1;
						$address2 = $acc->address2;
						$date = $acc->dateOfBirth;
						$zip = $acc->zipCode;
						$city = $acc->city;
						$phonecountrycode = $acc->phoneCountryCode;
						$OwningBusinessUnit = $acc->owningBusinessUnit;
						/*echo"<br>";
						 echo $yyyy = substr($DateOfBirth, 5, 4 );
						 echo"<br>";
						 echo $mm = substr($DateOfBirth, 2, 2 );
						 echo"<br>";
						 echo $dd = substr($DateOfBirth, 8, 2 );
						 echo"<br>";
						 echo $date = gmDate ("Y-m-d\TH:i:s\Z", mktime( 0, 0, 0, $yyyy, $mm, $dd  ) );*/
						
						foreach ($tparr_t as $acc_t)//trading platform account
						{
							$ccode = $acc_t->currency;
							$ccode_id = $acc_t->currency_id;
							
							$trading_platform=$acc_t->tradingPlatformName;
							$account_type=$acc_t->type;
							
							$ty = $acc_t->type;
							$leverage=$acc_t->leverage;
							
							$account_Type = "REAL";
							if($ty == "Demo")
							{
								$account_Type = "DEMO";
							}
							
							
							
							if($account_Type == "DEMO")
							{
								//Inserting into table crm_user
								
								$data = array(
										'firstname' =>$firstname,
										'lastname' =>$lastname,
										'country'=>$country,
										'trading_platform'=>"DEMO",
										'account_type'=>$account_type,
										'currency'=>$ccode,
										'platform'=>$trading_platform,
										'secret_data'=>$_GET['password'],
										'phone_country_code'=>$phonecountrycode,
										'phone'=>$phone,
										'birth_date'=>$date,
										'email' =>$email,
										'trading_platform_accountid'=>$acc_t->tradingPlatformID,
										'account_id'=>$acc_t->parentAccountID,
										'business_unit'=>$OwningBusinessUnit,
										'currency_code'=>$ccode,
										'name'=> $acc_t->name,
										'leverage'=>$leverage,
										'currency_id'=>$ccode_id,
										'city'=>$city,
										'address1'=>$address1,
										'mobile'=>$mobilenumber,
										
								);
								try{
									$sql=$this->user_model->insert_crmuser($data);
								}
								catch(Exception $e)
								{
									$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
									"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
									."-------------------------".PHP_EOL."\n";
									log_message('error',$log);
								}
							}
							else if($account_Type == "REAL")
							{
								//Inserting into table crm_user
								
								$data = array(
										'firstname' =>$firstname,
										'lastname' =>$lastname,
										'country'=>$country,
										'trading_platform'=> "REAL",
										'account_type'=>$account_type,
										'currency'=> $ccode,
										'platform'=>$trading_platform,
										'secret_data'=>$_GET['password'],
										'phone_country_code'=>$phonecountrycode,
										'phone'=>$phone,
										'birth_date'=>$date,
										'email' =>$email,
										'trading_platform_accountid'=>$acc_t->tradingPlatformID,
										'account_id'=>$acc_t->parentAccountID,
										'business_unit'=>$OwningBusinessUnit,
										'currency_code'=>$ccode,
										'name'=> $acc_t->name,
										'leverage'=>$leverage,
										'city'=>$city,
										'address1'=>$address1,
										'currency_id'=>$ccode_id,
										
										'mobile'=>$mobilenumber,
										
										
								);
								try{
									$sql=$this->user_model->insert_crmuser($data);
								}
								catch(Exception $e)
								{
									$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
									"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
									."-------------------------".PHP_EOL."\n";
									log_message('error',$log);
								}
							}
						}
					}
					
					//$users = get_user_by ( 'login', $user->username );
					
					
					if($users)
					{
						
						if (! is_wp_error ( $users )) {
							wp_clear_auth_cookie ();
							wp_set_current_user ( $users->ID );
							wp_set_auth_cookie ( $users->ID );
							
						}
					}
					else {
						
						$fake_data=fake_data();
						
						$userinfo = array(
								
								'user_login' =>$_GET['username'],//username
								'first_name' => $fake_data['fname'],
								'last_name' => $fake_data['lname'],
								'user_email' => $fake_data['email'],
								'user_pass' => $_GET['password'],
								'role' => 'real'
						);
						
						$user_id = wp_insert_user( $userinfo );
						$wp_user = new WP_User( $user_id );
						$wp_user->set_role( 'real' );
						
						$user2 = get_user_by ( 'login', $_GET['username']);
						
						if (! is_wp_error ( $user2 )) {
							wp_clear_auth_cookie ();
							wp_set_current_user ( $user2->ID );
							wp_set_auth_cookie ( $user2->ID );
							
						}
						
					}
					
					// set session user datas
					$_SESSION['user_id']      = (int)$user->id;
					$_SESSION['username']     = (string)$user->username;
					$_SESSION['logged_in']    = (bool)true;
					$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
					//$_SESSION['is_admin']     = (bool)$user->is_admin;
					$_SESSION['user_role']     = (string)$user->user_role;
					
					$_SESSION['fname']     = $acc->firstName;
					$_SESSION['lname']     = $acc->lastName;
					$_SESSION['fullname']     = $acc->firstName." ".$acc->lastName;
					
					global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
					getParentBUDetails();
					$request = new CrmGetAccountBalanceModel();
					
					$request->tradingPlatformAccountName =$_SESSION['username'];
					$request->organizationName =ORGANIZATION_NAME;
					$request->ownerUserId =$OWNER_USER_ID;
					$request->businessUnitName =$BUSINESS_UNIT_NAME;
				
					    $method = "Login";
						$crmurl = api_url."/GetAccountBalance";
						$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
															
						$mgtapibalance_result = $update_mcrm['json_result'];
						$http_code = $update_mcrm['http_code'];
											
					$_SESSION['equity'] = $mgtapibalance_result->equity;
					$_SESSION['currency'] = $ccode;
					
					
						redirect($this->uri->segment(1).'/dashboard');
					//redirect($this->uri->segment(1).'/deposit/options');
					exit();
				}
				else //if user not exist
				{
					foreach($tparr as $acc)
					{
						$tpa_t = $acc->tpAccounts;
						
						$TradingPlatformAccountInfo_t = new TradingPlatformAccountInfo();
						$TradingPlatformAccountInfo_t = $tpa_t;
						
						$tparr_t =  array();
						if(is_object($TradingPlatformAccountInfo_t)) {
							$tparr_t[] =  $TradingPlatformAccountInfo_t;
						}
						else
						{
							$tparr_t = $TradingPlatformAccountInfo_t;
						}
						
						foreach ($tparr_t as $acc_tt)
						{
							if($acc_tt->name == $username)
							{
								$acc_type=$acc_tt->type;
							}
						}
					}
					//-------------
					
					if($acc_type =="Demo")
					{
						
						$tparr =array();
						if(is_object($json_result)) {
							$tparr[] =  $json_result;
						}
						else
						{
							$tparr = $json_result;
						}
						
						$email=$json_result->email;
						try{
							$delete_records=$this->user_model->delete_crmuser($email); //detele all records from db
						}
						catch(Exception $e)
						{
							$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
							"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
							."-------------------------".PHP_EOL."\n";
							log_message('error',$log);
						}
						foreach($tparr as $acc)
						{
							$tpa_t = $acc->tpAccounts;
							
							$TradingPlatformAccountInfo_t = new TradingPlatformAccountInfo();
							$TradingPlatformAccountInfo_t = $tpa_t;
							
							$tparr_t =  array();
							if(is_object($TradingPlatformAccountInfo_t)) {
								$tparr_t[] =  $TradingPlatformAccountInfo_t;
							}
							else
							{
								$tparr_t = $TradingPlatformAccountInfo_t;
							}
							$cntry=$acc->country;
							
							$country=$this->user_model->get_iso2($cntry);// get iso2
							
							$email = $acc->email;
							$firstname = $acc->firstName;
							$lastname = $acc->lastName;
							
							$name = $firstname . ' ' . $lastname;
							$phone = $acc->phoneNumber;
							//$state = $acc->state;
							//$title = $acc->title;
							$address1 = $acc->address1;
							$address2 = $acc->address2;
							$date = $acc->dateOfBirth;
							$zip = $acc->zipCode;
							$city = $acc->city;
							$mobilenumber = $acc->mobile;
							
							$phonecountrycode = $acc->phoneCountryCode;
							$OwningBusinessUnit = $acc->owningBusinessUnit;
							/*
							 $yyyy = substr ( $DateOfBirth, 0, 4 );
							 $mm = substr ( $DateOfBirth, 5, 2 );
							 $dd = substr ( $DateOfBirth, 8, 2 );
							 $date = gmDate ( "Y-m-d\TH:i:s\Z", mktime ( 0, 0, 0, $mm, $dd, $yyyy ) );*/
							
							foreach ($tparr_t as $acc_t)
							{
								$ccode = $acc_t->currency;
								$ccode_id = $acc_t->currency_id;
								
								$trading_platform=$acc_t->tradingPlatformName;
								$account_type=$acc_t->type;
								
								
								$ty = $acc_t->type;
								$leverage=$acc_t->leverage;
								$account_Type = "REAL";
								if($ty == "Demo")
								{
									$account_Type = "DEMO";
								}
								
								
								if($account_Type == "DEMO")
								{
									//Inserting into table crm_user
									
									$data = array(
											'firstname' =>$firstname,
											'lastname' =>$lastname,
											'country'=>$country,
											'trading_platform'=>"DEMO",
											'account_type'=>$account_type,
											'currency'=> $ccode,
											'platform'=> $trading_platform,
											'secret_data'=>$_GET['password'],
											'phone_country_code'=>$phonecountrycode,
											'phone'=>$phone,
											'birth_date'=>$date,
											'email' =>$email,
											'trading_platform_accountid'=>$acc_t->tradingPlatformID,
											'account_id'=>$acc_t->parentAccountID,
											'business_unit'=>$OwningBusinessUnit,
											'currency_code'=>$ccode,
											'name'=> $acc_t->name,
											'leverage'=>$leverage,
											'currency_id'=>$ccode_id,
											'city'=>$city,
											'address1'=>$address1,
											'mobile'=>$mobilenumber,
											
									);
									try{
										$sql=$this->user_model->insert_crmuser($data);
									}
									catch(Exception $e)
									{
										$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
										"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
										."-------------------------".PHP_EOL."\n";
										log_message('error',$log);
									}
								}
								else if($account_Type == "REAL")
								{
									
									//Inserting into table crm_user
									
									$data = array(
											'firstname' =>$firstname,
											'lastname' =>$lastname,
											'country'=>$country,
											'trading_platform'=> "REAL",
											'account_type'=>$account_type,
											'currency'=>$ccode,
											'platform'=> $trading_platform,
											'secret_data'=>$_GET['password'],
											'phone_country_code'=>$phonecountrycode,
											'phone'=>$phone,
											'birth_date'=>$date,
											'email' =>$email,
											'trading_platform_accountid'=>$acc_t->tradingPlatformID,
											'account_id'=>$acc_t->parentAccountID,
											'business_unit'=>$OwningBusinessUnit,
											'currency_code'=>$ccode,
											'name'=> $acc_t->name,
											'leverage'=>$leverage,
											'currency_id'=>$ccode_id,
											'city'=>$city,
											'address1'=>$address1,
											'mobile'=>$mobilenumber,
											
									);
									try{
										$sql=$this->user_model->insert_crmuser($data);
									}
									catch(Exception $e)
									{
										$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
										"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
										."-------------------------".PHP_EOL."\n";
										log_message('error',$log);
									}
								}
								
							}
						}
						$type='DEMO';
						try{
							$create_user=$this->user_model->create_user($_GET['username'],$email,$_GET['password'],$type);
						}
						catch(Exception $e)
						{
							$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
							"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
							."-------------------------".PHP_EOL."\n";
							log_message('error',$log);
						}
						if($this->user_model->resolve_user_login($_GET['username'],$_GET['password']))
						{
							try{
								$user_id = $this->user_model->get_user_id_from_username($_GET['username']);
								$user= $this->user_model->get_user($user_id);
							}
							catch(Exception $e)
							{
								$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
								"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
								."-------------------------".PHP_EOL."\n";
								log_message('error',$log);
							}
							if($user)
							{
								
								$users = get_user_by ( 'login', $user->username );
								
								
								if($users)		{
									if (! is_wp_error ( $users )) {
										wp_clear_auth_cookie ();
										wp_set_current_user ( $users->ID );
										wp_set_auth_cookie ( $users->ID );
										
									}
									
								}
								else
								{
									$fake_data=fake_data();
									
									$userinfo = array (
											
											'user_login' => $_GET['username'],
											'user_nicename' => $fake_data['fname'],
											'user_email' =>$fake_data['email'],
											'user_pass' => $_GET['password'],
											'display_name' => 'demo'
									);
									
									$user_id = wp_insert_user ( $userinfo );
									$wp_user = new WP_User ( $user_id );
									$wp_user->set_role ( 'real' );
									
									
									$users = get_user_by ( 'login', $_GET['username']);
									
									if (! is_wp_error ( $users )) {
										wp_clear_auth_cookie ();
										wp_set_current_user ( $users->ID );
										wp_set_auth_cookie ( $users->ID );
										
									}
									
								}
								
								// set session user datas
								$_SESSION['user_id']      = (int)$user->id;
								$_SESSION['username']     = (string)$user->username;
								$_SESSION['logged_in']    = (bool)true;
								$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
								//$_SESSION['is_admin']     = (bool)$user->is_admin;
								$_SESSION['user_role']     = (string)$user->user_role;
								
								$_SESSION['fname']     = $acc->firstName;
								$_SESSION['lname']     = $acc->lastName;
								$_SESSION['fullname']     = $acc->firstName." ".$acc->lastName;
								
								
								global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
								getParentBUDetails();
								$request = new CrmGetAccountBalanceModel();
								
								$request->tradingPlatformAccountName =$_SESSION['username'];
								$request->organizationName =ORGANIZATION_NAME;
								$request->ownerUserId =$OWNER_USER_ID;
								$request->businessUnitName =$BUSINESS_UNIT_NAME;
								
								$method = "Login";
								$crmurl = api_url."/GetAccountBalance";
								$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
																	
								$mgtapibalance_result = $update_mcrm['json_result'];
								$http_code = $update_mcrm['http_code'];
								
								$_SESSION['equity'] = $mgtapibalance_result->equity;
								$_SESSION['currency'] = $ccode;
								
								
								
							//	redirect($this->uri->segment(1).'/deposit/options');
								redirect($this->uri->segment(1).'/dashboard');
                              exit();
							}
							
						}
						
					}
					else
					{
						
						$tparr =array();
						
						if(is_object($json_result)) {
							$tparr[] =  $json_result;
						}
						else
						{
							$tparr = $json_result;
						}
						
						$email=$json_result->email;
						try{
							$delete_records=$this->user_model->delete_crmuser($email); //detele all records from db
						}catch(Exception $e)
						{
							$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
							"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
							."-------------------------".PHP_EOL."\n";
							log_message('error',$log);
						}
						
						foreach($tparr as $acc)
						{
							$tpa_t = $acc->tpAccounts;
							
							$TradingPlatformAccountInfo_t = new TradingPlatformAccountInfo();
							$TradingPlatformAccountInfo_t = $tpa_t;
							
							$tparr_t =  array();
							if(is_object($TradingPlatformAccountInfo_t)) {
								$tparr_t[] =  $TradingPlatformAccountInfo_t;
							}
							else
							{
								$tparr_t = $TradingPlatformAccountInfo_t;
							}
							
							$cntry=$acc->country;
							
							$country=$this->user_model->get_iso2($cntry);// get iso2
							
							$email = $acc->email;
							$firstname = $acc->firstName;
							$lastname = $acc->lastName;
							$name = $firstname . ' ' . $lastname;
							$phone = $acc->phoneNumber;
							//$state = $acc->state;
							//$title = $acc->title;
							$address1 = $acc->address1;
							$address2 = $acc->address2;
							$DateOfBirth = $acc->dateOfBirth;
							$zip = $acc->zipCode;
							$city = $acc->city;
							$phonecountrycode = $acc->phoneCountryCode;
							$OwningBusinessUnit = $acc->owningBusinessUnit;
							
							$mobilenumber = $acc->mobile;
							
							$yyyy = substr ( $DateOfBirth, 0, 4 );
							$mm = substr ( $DateOfBirth, 5, 2 );
							$dd = substr ( $DateOfBirth, 8, 2 );
							$date = gmDate ( "Y-m-d\TH:i:s\Z", mktime ( 0, 0, 0, $mm, $dd, $yyyy ) );
							foreach ($tparr_t as $acc_t)
							{
								$ccode = $acc_t->currency;
								$ccode_id = $acc_t->currency_id;
								
								$trading_platform=$acc_t->tradingPlatformName;
								$account_type=$acc_t->type;
								
								
								$ty = $acc_t->type;
								$leverage=$acc_t->leverage;
								
								$account_Type = "REAL";
								if($ty == "Demo")
								{
									$account_Type = "DEMO";
								}
								
								if($account_Type == "DEMO")
								{
									//Inserting into table crm_user
									
									$data = array(
											'firstname' =>$firstname,
											'lastname' =>$lastname,
											'country'=>$country,
											'trading_platform'=>"DEMO",
											'account_type'=> $account_type,
											'currency'=> $ccode,
											'platform'=> $trading_platform,
											'secret_data'=>$_GET['password'],
											'phone_country_code'=>$phonecountrycode,
											'phone'=>$phone,
											'birth_date'=>$date,
											'email' =>$email,
											'trading_platform_accountid'=>$acc_t->tradingPlatformID,
											'account_id'=>$acc_t->parentAccountID,
											'business_unit'=>$OwningBusinessUnit,
											'currency_code'=>$ccode,
											'name'=> $acc_t->name,
											'leverage'=>$leverage,
											'currency_id'=>$ccode_id,
											'city'=>$city,
											'address1'=>$address1,
											'mobile'=>$mobilenumber,
											
									);
									try{
										$sql=$this->user_model->insert_crmuser($data);
									}catch(Exception $e)
									{
										$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
										"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
										."-------------------------".PHP_EOL."\n";
										log_message('error',$log);
									}
								}
								else if($account_Type == "REAL")
								{
									
									//Inserting into table crm_user
									
									$data = array(
											'firstname' =>$firstname,
											'lastname' =>$lastname,
											'country'=>$country,
											'trading_platform'=> "REAL",
											'account_type'=>$account_type,
											'currency'=>$ccode,
											'platform'=> $trading_platform,
											'secret_data'=>$_GET['password'],
											'phone_country_code'=>$phonecountrycode,
											'phone'=>$phone,
											'birth_date'=>$date,
											'email' =>$email,
											'trading_platform_accountid'=>$acc_t->tradingPlatformID,
											'account_id'=>$acc_t->parentAccountID,
											'business_unit'=>$OwningBusinessUnit,
											'currency_code'=>$ccode,
											'name'=> $acc_t->name,
											'leverage'=>$leverage,
											'currency_id'=>$ccode_id,
											'city'=>$city,
											'address1'=>$address1,
											'mobile'=>$mobilenumber,
											
									);
									try{
										$sql=$this->user_model->insert_crmuser($data);
									}catch(Exception $e)
									{
										$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
										"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
										."-------------------------".PHP_EOL."\n";
										log_message('error',$log);
									}
								}
							}
						}
						
						$type='REAL';
						
						//$this->input->post('username');
						//$email;
						try{
							$create_user=$this->user_model->create_user($_GET['username'],$email,$_GET['password'],$type);
						}catch(Exception $e)
						{
							$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
							"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
							."-------------------------".PHP_EOL."\n";
							log_message('error',$log);
						}
						
						if($this->user_model->resolve_user_login($_GET['username'],$_GET['password']))
						{
							try{
								$user_id = $this->user_model->get_user_id_from_username($_GET['username']);
								$user= $this->user_model->get_user($user_id);
							}
							catch(Exception $e)
							{
								$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
								"LOGIN DB EXCEPTION: ".json_encode($e).PHP_EOL
								."-------------------------".PHP_EOL."\n";
								log_message('error',$log);
							}
							
							if($user)
							{
								$users = get_user_by ( 'login', $user->username );
								
								if($users)
								{
									if (! is_wp_error ( $users )) {
										wp_clear_auth_cookie ();
										wp_set_current_user ( $users->ID );
										wp_set_auth_cookie ( $users->ID );
										
									}
								}
								
								else{
									
									$fake_data=fake_data();
									
									$userinfo = array (
											
											'user_login' => $_GET['username'],
											'user_nicename' => $fake_data['fname'],
											'user_email' =>$fake_data['email'],
											'user_pass' => $_GET['password'],
											'display_name' => 'real'
									);
									
									$user_id = wp_insert_user ( $userinfo );
									$wp_user = new WP_User ( $user_id );
									$wp_user->set_role ( 'real' );
									
									$users = get_user_by ( 'login', $_GET['username']);
									
									if (! is_wp_error ( $users )) {
										wp_clear_auth_cookie ();
										wp_set_current_user ( $users->ID );
										wp_set_auth_cookie ( $users->ID );
										
									}
									
								}
								
								// set session user datas
								$_SESSION['user_id']      = (int)$user->id;
								$_SESSION['username']     = (string)$user->username;
								$_SESSION['logged_in']    = (bool)true;
								$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
								//$_SESSION['is_admin']     = (bool)$user->is_admin;
								$_SESSION['user_role']     = (string)$user->user_role;
								$_SESSION['fname']     = $acc->firstName;
								$_SESSION['lname']     = $acc->lastName;
								$_SESSION['fullname']     = $acc->firstName." ".$acc->lastName;
								
								
								global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
								getParentBUDetails();
								$request = new CrmGetAccountBalanceModel();
								
								$request->tradingPlatformAccountName =$_SESSION['username'];
								$request->organizationName =ORGANIZATION_NAME;
								$request->ownerUserId =$OWNER_USER_ID;
								$request->businessUnitName =$BUSINESS_UNIT_NAME;
								
								$method = "Login";
								$crmurl = api_url."/GetAccountBalance";
								$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
																	
								$mgtapibalance_result = $update_mcrm['json_result'];
								$http_code = $update_mcrm['http_code'];
								
								$_SESSION['equity'] = $mgtapibalance_result->equity;
								$_SESSION['currency'] = $ccode;
								
								
								
									redirect($this->uri->segment(1).'/dashboard');
								// redirect($this->uri->segment(1).'/deposit/options');
								exit();
							}
						}
					}
	redirect($this->uri->segment(1).'/dashboard');
				//	redirect($this->uri->segment(1).'/deposit/options');
					exit();
				}
			}
		}
		catch (Exception $e)
		{
			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
			"LOGIN EXCEPTION: ".json_encode($e).PHP_EOL
			."-------------------------".PHP_EOL."\n";
			log_message('error',$log);
			redirect($this->uri->segment(1).'/errors');
		}
		
		
	}
	
	
	
	/**
	 * logout function.
	 *
	 * @access public
	 * @return void
	 */
	public function logout() {
	
		$this->session->sess_destroy();
		wp_logout();
		redirect($this->uri->segment(1).'/login');
	}
	

	
}
