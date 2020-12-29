<?php
class Cashier7 extends CI_Controller 
{

	public function __construct() 
	{
		
		parent::__construct();
		
		$this->load->helper('url_helper');
		
		$this->load->model('withdrawal_request_model');
		$this->load->model('deposite_model');
		$this->load->model('real_model');
		$this->load->helper(array('url'));
		$this->load->helper('prodconfig');
		
		
		$uri_1 = $this->uri->segment(1);
		all_lang($uri_1);
		
		
	}
	
	
	public function agent_redirect()
	{
		$myfile = fopen(logger_url_txt."gumballpay_redirect.txt", "a") or die("Unable to open file!");
		
		
		fwrite($myfile,"received values[".date('Y-m-d H:i:s')."] =".json_encode($_POST)."\n\n");
		
		
		fclose($myfile);
		
		$status=$_POST['status'];
		
		if(strtolower($status)=='approved')
		{
			$_SESSION['pop_mes']= "Your transaction is successful.";
			
			?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/cashier7/backoffice'); ?>'; }, 0000)">
        		        		        	        		        	        	        					    			
        		       <?php 
		}
		else
		{
			
			$_SESSION['error_pop_mes']= $_POST['error_message'];
			
			?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/cashier7/backoffice'); ?>'; }, 0000)">
        		        		        	        		        	        	        					    			
        		       <?php 
			
			
		}
		
		
	}
	
	
	
	public function access_denied()
	{
		
		$this->load->view('templates/before-login-header', $data );
		$this->load->view('traders_room/fund_account/access_denied');
		$this->load->view('templates/before-login-footer');
		
	}
	
	public function backoffice()
	{
		
		
		
		$ip = get_client_ip();
		
		if($ip!='136.244.67.225' && $ip!='122.170.10.24' && $ip!='95.179.224.11' && $ip!='103.121.242.250'  && $ip!='43.242.228.6' && $ip=='157.33.235.26')
		{
			
			
			$_SESSION['pop_mes'] = "You Don't Have Access To This Page";
			
			redirect(base_url($this->uri->segment(1).'/cashier7/access_denied'));
			
			exit;
			
		}
		
		
		$data = array();
		$data['title'] = 'Deposit Fund';
		$data['language'] = $this->uri->segment(1);
		$data['country'] = $this->real_model->get_countries();
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('acc', 'lang:Trading Account No', 'trim|required');
		
		if($this->form_validation->run() === FALSE)
		{
			
			$this->load->view('templates/before-login-header', $data );
			$this->load->view('traders_room/fund_account/gumballpay_direct');
			$this->load->view('templates/before-login-footer');
		}
		else
		{
			
			$token = $this->input->post('my_token_cashier7_backoffice');
			
			$session_token = $_SESSION['form_token_cashier7_backoffice'];
			
			unset($_SESSION['form_token_cashier7_backoffice']);
			
			if( (empty($token)) || (empty($session_token)) || ($token != $session_token) )
			{

				$_SESSION['pop_mes'] = 'Invalid Session';
				
				redirect(base_url($this->uri->segment(1).'/cashier7/backoffice'));
				
				exit;
				
			}
			
			
			
			
			$myfile = fopen(logger_url_txt."gumballpay_req.txt", "a") or die("Unable to open file!");
			
			$acc=$_POST['acc'];
			
			$result123=get_acc_details_forgot_password($acc);
			
			$code=$result123->result->code;
			
			if($code=='2')
			{
				
				
				$_SESSION['pop_mes'] = 'This account does not exists';
				
				redirect(base_url($this->uri->segment(1).'/cashier7/backoffice'));
				
				exit;
				
			}


			$address = $this->input->post('address');
			$city = $this->input->post('city');
			$country= $this->input->post('country');
			$zipCode= $this->input->post('zip');
			 
			$firstname=$result123->accountsInfo[0]->firstName;
			$lastname=$result123->accountsInfo[0]->lastName;
			$email=$result123->accountsInfo[0]->email;		
			$phone=$result123->accountsInfo[0]->phoneNumber;
						
			$country_fullname=$result123->accountsInfo[0]->country;
			
			//$country=get_country_iso2($country_fullname);
			
			if(empty($country))
			{
				$country = get_country_details();
			}
			
			$state='';
			
			if($country=='US')
			{
				$state=$this->input->post('us_state');
			}
			elseif($country=='AU')
			{
				$state=$this->input->post('au_state');
			}
			elseif($country=='CA')
			{
				$state=$this->input->post('ca_state');
			}
			

			 $email_encriptd=my_simple_crypt($email,'e');
			 
			 $accounts =  $result123->accountsInfo[0]->tradingPlatformAccounts;
			 
			 
			 $tparr =array();
			 
			 if(is_object($accounts)) {
			 	$tparr[] =  $accounts;
			 }
			 else
			 {
			 	$tparr = $accounts;
			 }
			 
			 foreach ($tparr as $acc_t)
			 {
			 	if($acc_t->name==$acc)
			 	{
			 		//$tp_account_id=$pin;
			 		$currency = $acc_t->baseCurrency->code;
			 		//$trading_platform_accountid= $acc_t->id;
			 		//$parentAccountId= $acc_t->parentAccountId;
			 	}
			 	
			 	
			 }

			 
			 $phone_encriptd=my_simple_crypt($phone,'e');

			 $amount= $this->input->post ( 'amount' );
			 
			 $amtpos = strpos ( $amount, "." );
			 
			 if ($amtpos == false) {
			 	$amount .= ".00";
			 }

			 $time_stamp = trim(date ( 'Y-m-d H:i:s' ));
			


			$client_orderid=uniqid();

			$user=$this->deposite_model->add_payment_details_gumballpay($firstname,$lastname,$acc,$client_orderid,$amount,$currency,$email_encriptd,$phone_encriptd,$country,$time_stamp);
	
			$groupId = GUMBALLPAY_GROUP_ID;
			$merchantControl = GUMBALLPAY_MERCHANT_CONTROL;
			
			$return=trim(base_url($this->uri->segment(1).'/cashier7/agent_redirect'));
			
			$notification=trim(base_url('en/cashier7/notification'));
			
			if($currency!='EUR')
			{
				$val=file_get_contents('https://openexchangerates.org/api/latest.json?app_id='.openexchangerates_app_id.'&base='.$currency);
				
				$val=json_decode($val);
				
				$current_val=$val->rates->EUR;
				
				$new_val = $amount * $current_val;
				
				$trans_amount=number_format((float)$new_val, 2, '.', '');
			}
			else
			{
				$trans_amount=$amount;
			}
			
			
			
			$requestFields = array(
					'client_orderid' => $client_orderid,
					'order_desc' => 'Deposit',
					'first_name' => $firstname,
					'last_name' => $lastname,
					'address1' => $address,
					'city' => $city,
					'state' => $state,
					'zip_code' => $zipCode,
					'country' => $country,
					'phone' => $phone,
					'amount' => $trans_amount,
					'email' => $email,
					'currency' => 'EUR',
					'ipaddress' => get_client_ip(),
					'site_url' => wp_site_url,					
					'redirect_url' => $return,
					'server_callback_url' => $notification,
					
					
			);
			
			
			$base = '';
			$base .= $groupId;
			$base .= $requestFields['client_orderid'];
			$base .= $requestFields['amount'] * 100;
			$base .= $requestFields['email'];
			
			$requestFields['control'] = sha1($base. $merchantControl);
			
			fwrite($myfile,"request[".date('Y-m-d H:i:s')."] backoffice =".json_encode($requestFields)."\n\n");
			
			$url=GUMBALLPAY_URL.'/paynet/api/v2/sale-form/group/'.$groupId;
			
			
			$curl = curl_init($url);
			
			curl_setopt_array($curl, array
					(
							CURLOPT_HEADER         => 0,
							CURLOPT_USERAGENT      => 'Gumballpay-Client/1.0',
							CURLOPT_SSL_VERIFYHOST => 0,
							CURLOPT_SSL_VERIFYPEER => 0,
							CURLOPT_POST           => 1,
							CURLOPT_RETURNTRANSFER => 1
					));
			
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestFields));
			
			$response = curl_exec($curl);
			$curl_error = curl_error($curl);
			$curl_info = curl_getinfo($curl);
			
			curl_close($curl);
			
			
			
			$responseFields = array();
			
			parse_str($response, $responseFields);


			fwrite($myfile,"response[".date('Y-m-d H:i:s')."] =".json_encode($responseFields)."\n\n");
			fwrite($myfile,"info[".date('Y-m-d H:i:s')."] =".json_encode($curl_info)."\n\n");
			fwrite($myfile,"err[".date('Y-m-d H:i:s')."] =".json_encode($curl_error)."\n\n");
						
			$redirect_url=$responseFields['redirect-url'];
			
			if(!empty($redirect_url))
			{
				$data['title'] = 'Deposit';

				$data ['redirect_url'] = $redirect_url;

				$this->load->view('templates/before-login-header', $data );
				$this->load->view('traders_room/fund_account/gumballpay_direct');
				$this->load->view('templates/before-login-footer');
				
			}
			else
			{
				
				$_SESSION['error_pop_mes']=$responseFields['error-message'];
				
				$redirect_url=base_url($this->uri->segment(1).'/cashier7/backoffice');
				
				?>
        	<script>
        	window.top.location.href = "<?php echo $redirect_url;?>"; 
        	</script>
        	<?php
			}
						
							
						
		}	
	
	
	
	
	}

	public function atest()
	{
		
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		
		$requestFields = array(
				'login' => 'Midas_wms',
				'client_orderid' => '5f2cf0c6ea9ef',
				'orderid' => '1163850',
				'by-request-sn' => '00000000-0000-0000-0000-000002527bff',

				
				
		);
				
		$requestFields['control'] = sha1('Midas_wms'.'5f2cf0c6ea9ef'.'1163850'.GUMBALLPAY_MERCHANT_CONTROL);
		
		print"<pre>";print_r($requestFields);print"</pre>";
		
		$url=GUMBALLPAY_URL.'/paynet/api/v2/status/group/1042';
		
		
		$curl = curl_init($url);
		
		curl_setopt_array($curl, array
				(
						CURLOPT_HEADER         => 0,
						CURLOPT_USERAGENT      => 'Gumballpay-Client/1.0',
						CURLOPT_SSL_VERIFYHOST => 0,
						CURLOPT_SSL_VERIFYPEER => 0,
						CURLOPT_POST           => 1,
						CURLOPT_RETURNTRANSFER => 1
				));
		
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestFields));
		
		$response = curl_exec($curl);
		$curl_error = curl_error($curl);
		$curl_info = curl_getinfo($curl);
		
		curl_close($curl);
		
		
		
		$responseFields = array();
		
		parse_str($response, $responseFields);
		
		print"<pre>";print_r($responseFields);print"</pre>";
	}

	

	public function index()
	{
		
		
		if(!isset($_SESSION['logged_in']))
		{
			redirect($this->uri->segment(1).'/login');
		}
		
		
		$result_crm = get_all_acc_details();
		$new_addpsp = $result_crm->getAllAccountDetails[0]->dynamicAttributeValue;
		$lang_smallcase = strtolower($new_addpsp);
		if ($lang_smallcase !="true" && $lang_smallcase!="yes")
		{
			redirect($this->uri->segment(1).'/dashboard');
		}
		
		
		$data = array();
		$data['title'] = 'Deposit Fund';
		$data['language'] = $this->uri->segment(1);
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('acc', 'lang:Trading Account No', 'trim|required');
		
		if($this->form_validation->run() === FALSE)
		{
			global $wpdb;
			
			$crm_user = $wpdb->prefix . 'crm_user';
			
			$trading_platform = $wpdb->get_results ( $wpdb->prepare ( "SELECT trading_platform  FROM $crm_user WHERE  name = %d", $_SESSION['username'] ) );
			
			$user_role = $trading_platform[0]->trading_platform;
			
			
			if($user_role == "DEMO")
			{
				$_SESSION['error_pop_mes']= "This Facility is Available only for Real Account Users.";
				//popup();
				
				?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/dashboard'); ?>'; }, 0000)">
        		        		        	        		        	        	        					    			
        		       <?php 
        		        		        	        		        	        	        		
        		        $data = array();
        		        $data['title'] = 'Deposit';
        		        		        	        		        	        	        		
        		        $this->load->view('templates/header' ,$data);
        		        $this->load->view('templates/sidebar');
        		        		        	        		        	        	        	
        		        $this->load->view('templates/footer');
        		     }
        		     
        		 else{
        		
        		try{
        			
        			$user_email = $this->withdrawal_request_model->get_current_user_email($_SESSION['username'] );
        			$data   = array();
        			$data['result'] = $this->withdrawal_request_model->get_current_user_data($user_email);
        			
        		    }
        		catch(Exception $e )
        		   {
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        			"Real deposit DB EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL;
        			file_put_contents(logger_url, $log. "\n", FILE_APPEND);
        		    }
        		
        		
        		$this->load->view('templates/header', $data);
        		$this->load->view('templates/left-sidebar');
        		$this->load->view('traders_room/fund_account/gumballpay', $data);
        		$this->load->view('templates/footer');
        	}
        
        	}
		else
		{
			
			$token = $this->input->post('my_token_cashier7');
			
			$session_token = $_SESSION['form_token_cashier7'];
			
			unset($_SESSION['form_token_cashier7']);
			
			if( (empty($token)) || (empty($session_token)) || ($token != $session_token) )
			{
				
				$_SESSION['error_pop_mes'] = 'Invalid Session';
				
				redirect(base_url($this->uri->segment(1).'/dashboard/'));
				
				exit;
			}
			
			
			
			
			$myfile = fopen(logger_url_txt."gumballpay_req.txt", "a") or die("Unable to open file!");
			
			$acc=$this->input->post('acc');
			
			$result_data = $this->deposite_model->get_details_from_crm_user($acc);


			$address = $this->input->post('address');
			$city = $this->input->post('city');
			$state= $this->input->post('state');
			$zipCode= $this->input->post('zip');
			 
		
			 
			 $firstname = $result_data->firstname;
			 $lastname = $result_data->lastname;
			 $email = $result_data->email;
			 
			 $email_encriptd=my_simple_crypt($email,'e');
			 
			 $currency = $result_data->currency_code;
			
			 $country = $result_data->country;
			 $phone = $result_data->phone;
			 
			 $phone_encriptd=my_simple_crypt($phone,'e');

			 $amount= $this->input->post ( 'amount' );
			 
			 $amtpos = strpos ( $amount, "." );
			 
			 if ($amtpos == false) {
			 	$amount .= ".00";
			 }

			 $time_stamp = trim(date ( 'Y-m-d H:i:s' ));
			


			$client_orderid=uniqid();

			$user=$this->deposite_model->add_payment_details_gumballpay($firstname,$lastname,$acc,$client_orderid,$amount,$currency,$email_encriptd,$phone_encriptd,$country,$time_stamp);
	
			$groupId = GUMBALLPAY_GROUP_ID;
			$merchantControl = GUMBALLPAY_MERCHANT_CONTROL;
			
			$return=trim(base_url($this->uri->segment(1).'/cashier7/redirect'));
			
			$notification=trim(base_url('en/cashier7/notification'));
			
			if($currency!='EUR')
			{
				$val=file_get_contents('https://openexchangerates.org/api/latest.json?app_id='.openexchangerates_app_id.'&base='.$currency);
				
				$val=json_decode($val);
				
				$current_val=$val->rates->EUR;
				
				$new_val = $amount * $current_val;
				
				$trans_amount=number_format((float)$new_val, 2, '.', '');
			}
			else
			{
				$trans_amount=$amount;
			}
			
			$requestFields = array(
					'client_orderid' => $client_orderid,
					'order_desc' => 'Deposit',
					'first_name' => $firstname,
					'last_name' => $lastname,
					'address1' => $address,
					'city' => $city,
					'state' => $state,
					'zip_code' => $zipCode,
					'country' => $country,
					'phone' => $phone,
					'amount' => $trans_amount,
					'email' => $email,
					'currency' => 'EUR',
					'ipaddress' => get_client_ip(),
					'site_url' => wp_site_url,					
					'redirect_url' => $return,
					'server_callback_url' => $notification,
					
					
			);
			
			
			$base = '';
			$base .= $groupId;
			$base .= $requestFields['client_orderid'];
			$base .= $requestFields['amount'] * 100;
			$base .= $requestFields['email'];
			
			$requestFields['control'] = sha1($base. $merchantControl);
			
			fwrite($myfile,"request[".date('Y-m-d H:i:s')."] =".json_encode($requestFields)."\n\n");
			
			$url=GUMBALLPAY_URL.'/paynet/api/v2/sale-form/group/'.$groupId;
			
			
			$curl = curl_init($url);
			
			curl_setopt_array($curl, array
					(
							CURLOPT_HEADER         => 0,
							CURLOPT_USERAGENT      => 'Gumballpay-Client/1.0',
							CURLOPT_SSL_VERIFYHOST => 0,
							CURLOPT_SSL_VERIFYPEER => 0,
							CURLOPT_POST           => 1,
							CURLOPT_RETURNTRANSFER => 1
					));
			
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestFields));
			
			$response = curl_exec($curl);
			$curl_error = curl_error($curl);
			$curl_info = curl_getinfo($curl);
			
			curl_close($curl);
			
			
			
			$responseFields = array();
			
			parse_str($response, $responseFields);


			fwrite($myfile,"response[".date('Y-m-d H:i:s')."] =".json_encode($responseFields)."\n\n");
			fwrite($myfile,"info[".date('Y-m-d H:i:s')."] =".json_encode($curl_info)."\n\n");
			fwrite($myfile,"err[".date('Y-m-d H:i:s')."] =".json_encode($curl_error)."\n\n");
						
			$redirect_url=$responseFields['redirect-url'];
			
			if(!empty($redirect_url))
			{
				$data['title'] = 'Deposit';

				$data ['redirect_url'] = $redirect_url;
				$this->load->view ( 'templates/header', $data );
				$this->load->view ( 'templates/left-sidebar' );
				$this->load->view ( 'traders_room/fund_account/gumballpay', $data );
				$this->load->view ( 'templates/footer' );
			}
			else
			{
				$_SESSION['error_pop_mes']= $responseFields['error-message'];
				redirect($this->uri->segment(1).'/dashboard');
			}
						
							
						
		}	
	
	
	
	}
	

	public function redirect()
	{
		$myfile = fopen(logger_url_txt."gumballpay_redirect.txt", "a") or die("Unable to open file!");		
		
		
		fwrite($myfile,"received values[".date('Y-m-d H:i:s')."] =".json_encode($_POST)."\n\n");

		
		fclose($myfile);
		
		$status=$_POST['status'];
		
		if(strtolower($status)=='approved')
		{
			$_SESSION['pop_mes']= "Your transaction is successful.";
			
			?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/dashboard'); ?>'; }, 0000)">
        		        		        	        		        	        	        					    			
        		       <?php 
		}
		else
		{
			
			$_SESSION['error_pop_mes']= $_POST['error_message'];
			
			?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/dashboard'); ?>'; }, 0000)">
        		        		        	        		        	        	        					    			
        		       <?php 
			
			
		}
		
		
	}
	
	public function notification()
	{

		
		$myfile = fopen(logger_url_txt."gumballpay_notification.txt", "a") or die("Unable to open file!");
		
		fwrite($myfile,"received values[".date('Y-m-d H:i:s')."] =".json_encode($_GET)."\n\n");
		
		$status=$_GET['status'];
		$orderid=$_GET['orderid'];
		$client_orderid=$_GET['client_orderid'];
		$merchant_control=GUMBALLPAY_MERCHANT_CONTROL;
		
		$str=$status.$orderid.$client_orderid.$merchant_control;

		$calculated_control=sha1($str);
		
		$received_control=$_GET['control'];
		
		//$sourceAmount=$_GET['amount'];
		
		$name=$_GET['name'];
		
		$last_four=$_GET['last-four-digits'];
		
		$card_exp_year = $_GET ['card-exp-year'];
		
		$card_exp_year = substr ( $card_exp_year, - 2 );
		
		$card_exp_month = $_GET ['card-exp-month'];
		
		
		if ($card_exp_month == "1" || $card_exp_month == "2" || $card_exp_month == "3" || $card_exp_month == "4" || $card_exp_month == "5" || $card_exp_month == "6" || $card_exp_month == "7" || $card_exp_month == "8" || $card_exp_month == "9") {
			$card_exp_month = "0" . $card_exp_month;
		}
		
		
		$get_records = $this->deposite_model->get_records($client_orderid);
		$fname = $get_records->firstname;
		$lname = $get_records->lastname;
		$acc = $get_records->tp_account_id;
		$table_status = $get_records->status;
		$sourceAmount = $get_records->amount;
		
		$result123=get_acc_details_forgot_password($acc);
		
		$owningBusinessUnit=$result123->accountsInfo[0]->owningBusinessUnit;
		
		$accounts =  $result123->accountsInfo[0]->tradingPlatformAccounts;
		
		
		$tparr =array();
		
		if(is_object($accounts)) {
			$tparr[] =  $accounts;
		}
		else
		{
			$tparr = $accounts;
		}
		
		foreach ($tparr as $acc_t)
		{
			if($acc_t->name==$acc)
			{
				
				$TradingPlatformAccountId= $acc_t->id;
				
			}
			
			
		}
	
				
		$account_id = $result123->accountsInfo[0]->id;
		$email = $result123->accountsInfo[0]->email;
		
		
		
		if($calculated_control==$received_control)
		{
			if(strtolower($status)=='approved')
			{
				if( $table_status != 'Success' )
				{
					
					
					$update_success = $this->deposite_model->update_success($client_orderid);
					
					
					
					global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
					
					
					getBUDetails($owningBusinessUnit);
					
					$request1 = new CrmMonetaryTransactionModelRequestModel();
					$request1->organizationName =ORGANIZATION_NAME;
					$request1->ownerUserId =$OWNER_USER_ID;
					$request1->businessUnitName = $BUSINESS_UNIT_NAME;
					$request1->amount =$sourceAmount;
					$request1->tradingPlatformAccountId=$TradingPlatformAccountId;
					$request1->internalComment ="Gumballpay Deposit";
					
					$request1->shouldAutoApprove = "TRUE";
					
					$request1->filtertype =1;
					$request1->cardExpirationMonth =$card_exp_month;
					$request1->cardExpirationYear =$card_exp_year;
					$request1->cardHolderName =$name;
					$request1->creditCardNumber =$last_four;
					
					$request1->new_transactionid=$orderid;
					
					$request1->new_psp='Gumballpay';
					
					fwrite($myfile,"mon req [".date('Y-m-d H:i:s')."] =".json_encode($request1)."\n\n");
					
					
					
					$url = api_url."/CreateMonetaryTransaction";
					$ch = curl_init ();
					curl_setopt ( $ch, CURLOPT_URL, $url );
					curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
					curl_setopt ( $ch, CURLOPT_POST, TRUE );
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request1));
					$curl_result = curl_exec ( $ch ); //getting response
					curl_close ( $ch );
					
					
					fwrite($myfile,"mon res [".date('Y-m-d H:i:s')."] =".json_encode($curl_result)."\n\n");
					
					
					

					
					
					
					
					
					
					global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
					
					
					
			
					
					getBUDetails($owningBusinessUnit);
					
					$request11 = new CrmCreateCaseModel();
					$request11->organizationName =ORGANIZATION_NAME;
					$request11->ownerUserId =$OWNER_USER_ID;
					$request11->businessUnitName = $BUSINESS_UNIT_NAME;
					$request11->amount =$sourceAmount;
					$request11->email = $email;
					$request11->firstName =$fname;
					$request11->lastName =$lname;
					$request11->title ="Gumballpay Deposit Success Case";
					$request11->description = "Transaction Id -> ". $client_orderid."  TP Name -> ". $acc;
					$request11->accountId=$account_id;
					$request11->lv_depositidentitynumber='Gumballpay';
					
					
					fwrite($myfile,"case request[".date('Y-m-d H:i:s')."]=".json_encode($request11)."\n\n");
					
					$url = api_url."/CreateCase";
					$ch = curl_init ();
					curl_setopt ( $ch, CURLOPT_URL, $url );
					curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
					curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
					curl_setopt ( $ch, CURLOPT_POST, TRUE );
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request11));
					$curl_result11 = curl_exec ( $ch ); //getting response
					$curl_info = curl_getinfo ( $ch );
					curl_close ( $ch );
					
					$json_result11 = json_decode ($curl_result11); //decode response in neat format
					$http_code11 = $curl_info['http_code'];
					
					
					
					fwrite($myfile,"case res [".date('Y-m-d H:i:s')."] =".json_encode($curl_result11)."\n\n");
					
					
					
					
					
					
					
					
					
					
					
				}
			}
			else
			{
				
				$update_fail = $this->deposite_model->update_fail($client_orderid);
				
				$errorMessage=$_GET['error_message'];
				

				
				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
				

				
				getBUDetails($owningBusinessUnit);
				
				$request11 = new CrmCreateCaseModel();
				$request11->organizationName =ORGANIZATION_NAME;
				$request11->ownerUserId =$OWNER_USER_ID;
				$request11->businessUnitName = $BUSINESS_UNIT_NAME;
				$request11->amount =$sourceAmount;
				$request11->email = $email;
				$request11->firstName =$fname;
				$request11->lastName =$lname;
				$request11->title ="Gumballpay Deposit Failure Case";
				$request11->description = "Transaction Id -> ". $client_orderid."  TP Name -> ". $acc."  Reason -> ".$errorMessage;
				$request11->accountId=$account_id;
				
				fwrite($myfile,"case request[".date('Y-m-d H:i:s')."]=".json_encode($request11)."\n\n");
				
				$url = api_url."/CreateCase";
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, $url );
				curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
				curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
				curl_setopt ( $ch, CURLOPT_POST, TRUE );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request11));
				$curl_result11 = curl_exec ( $ch ); //getting response
				$curl_info = curl_getinfo ( $ch );
				curl_close ( $ch );
				
				$json_result11 = json_decode ($curl_result11); //decode response in neat format
				$http_code11 = $curl_info['http_code'];
				
				
				
				fwrite($myfile,"case res [".date('Y-m-d H:i:s')."] =".json_encode($curl_result11)."\n\n");
				
				
			}
		}
		

		
		fclose($myfile);
		

		
	}
	
	
	
	
	
	
	
	

	public function success() 
	{
		
		$_SESSION['pop_mes']= "Your transaction is successful.";
		//popup();
		
		?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/dashboard'); ?>'; }, 0000)">
        		        		        	        		        	        	        					    			
        		       <?php 
	
	}
	
	
	public function failure() 
	{
		
		
		$_SESSION['error_pop_mes']= "Your transaction is failed.";
		//popup();
		
		?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/dashboard'); ?>'; }, 0000)">
        		        		        	        		        	        	        					    			
        		       <?php 
	
        
	}
	
	


	
}




