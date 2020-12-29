<?php
class Cashier11 extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

                $this->load->helper('url_helper');
                
                $this->load->model('praxis_model');

              
                $this->load->helper(array('url'));
                $this->load->helper('prodconfig');
               

                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
     
        }
        
        
        public function ma_test()
        {
        	$str='{"responseTime":"2020-04-21 09:00:02","result":{"resultCode":"0","resultMessage":"Transaction failed.","errorId":null,"error":[{"errorCode":"694","errorMessage":"ACQUIRER_ERROR: The period allotted for card details or password entering has expired.","advice":null},{"errorCode":"694","errorMessage":"ACQUIRER_ERROR: The period allotted for card details or password entering has expired.","advice":null}],"reasonCode":"694"},"signature":"cSdFZezEtpGH4\/sJsZpYTLRGUDtWDQdtOPiLtmVgtOI=","metaData":null,"txId":"2d8796c9-9772-4bac-a04c-9336d5357c1f","txTypeId":"3","txType":"PURCHASE","recurrentTypeId":"1","requestId":"REQUESTac13103663151587458003","orderId":"ORDERac13103663151587458003","sourceAmount":{"amount":"250.00000000","currencyCode":"USD"},"amount":{"amount":"19111.88000000","currencyCode":"RUB"},"returnUrl":null,"cancelUrl":null,"ccNumber":"431950xxxxxx5657","cardId":null,"redirect3DUrl":null,"payUrl":null}
';
        	$myArray = json_decode($str, true);
        	$err_arr=$myArray['result']['error'];
       
        	foreach($err_arr as $my_row)
        	{
        		$errorMessage=$my_row['errorMessage'];
     
        	}
        	echo $errorMessage;
        	print"<pre>";print_r($myArray);print"</pre>";
        }
        
        
        public function sync()
        {
        	$myfile = fopen(logger_url_txt."praxispay_sync.txt", "a") or die("Unable to open file!");
        	
        	$inputJSON = file_get_contents('php://input');
        	$input= json_decode( $inputJSON, TRUE );
        	
        	fwrite($myfile, "received values[".date('Y-m-d H:i:s')."]:".json_encode($input)."\n\n");
        	
        	

        	$frontend=$input['frontend'];
        	$merchant_id=$input['merchant_id'];
        	$pin=$input['pin'];
        	$version=$input['version'];
     
        	$merchant_secret = PRAXISPAY_MERCHANT_SECRET;
        	
        	$received_signature=$input['signature'];
        	
        	unset($input['signature']);
        	
        	ksort($input);
        	
        	$concatenated_string_res = implode('', $input) . $merchant_secret;
        	
        	$calculated_signature = hash('sha384', $concatenated_string_res);
        	
        	if($received_signature==$calculated_signature)
        	{
        		$result123=get_acc_details_forgot_password($pin);
        		
        		$message=$result123->result->code;
        		
        		
        		
        		if($message != "0")
        		{
        			$request=array(
        					"status"=>"1",
        					"description"=>"Customer not found",
        					"timestamp"=>time(),
        					"version"=>$version,
        					
        					
        			);
        			
        			
        			ksort($request);
        			
        			$concatenated_string = implode('', $request) . $merchant_secret;
        			
        			$signature = hash('sha384', $concatenated_string);
        			
        			$request['signature']=$signature;
        			
        			fwrite($myfile, "response[".date('Y-m-d H:i:s')."]:".json_encode($request)."\n\n");
        			
        			
        			print json_encode($request);
        			
        			exit;
        		}
        		
        		$ipAddress=$result123->accountsInfo[0]->environmentInfo->ipAddress;
        		
        		$dateOfBirth=$result123->accountsInfo[0]->dateOfBirth;
        		
        		$pieces = explode("-", $dateOfBirth);
        		
        		$year=$pieces[0];
        		$month=$pieces[1];
        		$day=$pieces[2];
        		
        		$day = substr($day, 0, 2);
        		
        		if(empty($day) || strlen($day) < 2)
        		{
        			$day='01';
        		}
        		
        		if(empty($month) || strlen($month) < 2)
        		{
        			$month='01';
        		}
        		
        		if(empty($year) || $year=="0001" || $year=="1900" || strlen($year) < 4)
        		{
        			$year='1970';
        		}
        		
        		$dob=$month.'/'.$day.'/'.$year;
        		
        		$country_fullname=$result123->accountsInfo[0]->country;
        		
        		$country_iso2=get_country_iso2($country_fullname);
        		
        		if(empty($country_iso2))
        		{
        			$country_iso2 = get_country_details();
        		}
        		
        		$firstName=$result123->accountsInfo[0]->firstName;
        		$lastName=$result123->accountsInfo[0]->lastName;
        		$email=$result123->accountsInfo[0]->email;
        		$phoneCountryCode=$result123->accountsInfo[0]->phoneCountryCode;
        		$phoneNumber=$result123->accountsInfo[0]->phoneNumber;
        		
        		$full_phone=$phoneCountryCode.$phoneNumber;
        		
        		$full_phone=str_replace("+","",$full_phone);
        		
        		if(strlen($full_phone) < 6 || empty($full_phone))
        		{
        			$full_phone="112233";
        		}
        		
        		$address1=$result123->accountsInfo[0]->address1;
        		
        		if(empty($address1))
        		{
        			$address1="address";
        		}
        		
        		$city=$result123->accountsInfo[0]->city;
        		
        		if(empty($city))
        		{
        			$city="city";
        		}
        		
        		$zipCode=$result123->accountsInfo[0]->zipCode;
        		
        		if(empty($zipCode))
        		{
        			$zipCode="12345";
        		}
        		
        		$state=$result123->accountsInfo[0]->state;
        		
        		if(empty($state))
        		{
        			$state="state";
        		}
        		
        		
        		
        		
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
        			if($acc_t->name==$pin)
        			{
        				//$tp_account_id=$pin;
        				$currency = $acc_t->baseCurrency->code;
        				//$trading_platform_accountid= $acc_t->id;
        				//$parentAccountId= $acc_t->parentAccountId;
        			}
        			
        			
        		}
        		
        		
        		if($currency=='USD')
        		{
        			$frontend='Praxis TEST_5 USD';
        		}
        		elseif($currency=='EUR')
        		{
        			$frontend='Praxis TEST_5 EUR';
        		}
        		elseif($currency=='GBP')
        		{
        			$frontend='Praxis TEST_5 GBP';
        		}
        		
        		if($country_iso2=='CA')
        		{
        			$state='ON';
        		}
        		
        		if($country_iso2=='US')
        		{
        			$state='WA';
        		}
        		
        		if($country_iso2=='AU')
        		{
        			$state='ACT';
        		}
        		
        		
        		
        		
        		
        		$validation_url=base_url('en/cashier11/validate');
        		
        		$notification_url=base_url('en/cashier11/notify');
        		
        		$balance=get_balance($pin);
        		
        		$request=array(
        				"status"=>"0",
        				"description"=>"OK",
        				"frontend"=>$frontend,
        				"currency"=>$currency,
        				"pin"=>$pin,
        				"balance"=>$balance,
        				"ip"=>$ipAddress,
        				"first_name"=>$firstName,
        				"last_name"=>$lastName,
        				"dob"=>$dob,
        				"email"=>$email,
        				"address"=>$address1,
        				"city"=>$city,
        				"country"=>$country_iso2,
        				"state"=>$state,
        				"zip"=>$zipCode,
        				"phone"=>$full_phone,
        				"manual_validation_url"=>$validation_url,
        				"manual_notification_url"=>$notification_url,
        				"timestamp"=>time(),
        				"version"=>$version,
        				
        				
        		);
        		
        		
        		ksort($request);
        		
        		$concatenated_string = implode('', $request) . $merchant_secret;
        		
        		$signature = hash('sha384', $concatenated_string);
        		
        		$request['signature']=$signature;
        		
        		fwrite($myfile, "response[".date('Y-m-d H:i:s')."]:".json_encode($request)."\n\n");
        		
        		
        		print json_encode($request);
        		
        		
        		
        	}
        	
        	
        	
        	
        	fclose($myfile);
        }
        
        
        public function success_window()
        {

        	/*
        	$data = array();
        	$data['title'] = 'Deposit Success';
        	$data['user_role'] = strtolower($_SESSION['user_role']);
        	$this->load->view('templates/header' ,$data);
        	//$this->load->view('templates/sidebar');
        	$this->load->view('traders_room/fund_account/cashier1_success');
        	$this->load->view('templates/footer');
        	*/
        }
        
        

        
        public function success()
        {

        	$myfile = fopen(logger_url_txt."praxispay_success.txt", "a") or die("Unable to open file!");

        	$inputJSON = file_get_contents('php://input');
        	$input= json_decode( $inputJSON, TRUE );
        	
        	fwrite($myfile, "received values[".date('Y-m-d H:i:s')."]:".json_encode($input)."\n\n");
        	
        	fclose($myfile);
        	

        	
        	$_SESSION['pop_mes'] = 'Your transaction is successful.';
        	
        	$success_url=base_url($this->uri->segment(1).'/dashboard');
        	//redirect($success_url);
        	?>
        			        		        	        	        					    				    			
        	<body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo $success_url; ?>'; }, 0000)">
        			        		        	        	        					    			
        	<?php 
        	

        }
        
        
        public function validate()
        {
        	$myfile = fopen(logger_url_txt."praxispay_validate.txt", "a") or die("Unable to open file!");

        	$inputJSON = file_get_contents('php://input');
        	$input= json_decode( $inputJSON, TRUE );
        	
        	fwrite($myfile, "received values[".date('Y-m-d H:i:s')."]:".json_encode($input)."\n\n");
        	
        	
        	$transaction_type=$input['transaction_type'];
        	$transaction_status=$input['transaction_status'];
        	$transaction_id=$input['transaction_id'];
        	$trace_id=$input['trace_id'];
        	$order_id=$input['order_id'];
        	$frontend=$input['frontend'];
        	$pin=$input['pin'];
        	$amount=$input['amount'];
        	$currency=$input['currency'];
        	$payment_method=$input['payment_method'];
        	$created_by=$input['created_by'];
        	$auth_token=$input['auth_token'];
        	$merchant_id=$input['merchant_id'];
        	$version=$input['version'];

        	
        	$merchant_secret = PRAXISPAY_MERCHANT_SECRET;
        	
        	
        	$received_signature=$input['signature'];
        	
        	unset($input['signature']);
        	
        	ksort($input);
        	
        	$concatenated_string_res = implode('', $input) . $merchant_secret;
        	
        	$calculated_signature = hash('sha384', $concatenated_string_res);
        	
        	if($received_signature==$calculated_signature)
        	{
        		$result123=get_acc_details_forgot_password($pin);
        		
        		$message=$result123->result->code;
        		
        		if($message != "0")
        		{
        			$request=array(
        					"status"=>"1",
        					"description"=>"Customer not found",
        					"timestamp"=>time(),
        					"version"=>$version,
        					
        					
        			);
        			
        			
        			ksort($request);
        			
        			$concatenated_string = implode('', $request) . $merchant_secret;
        			
        			$signature = hash('sha384', $concatenated_string);
        			
        			$request['signature']=$signature;
        			
        			fwrite($myfile, "response[".date('Y-m-d H:i:s')."]:".json_encode($request)."\n\n");
        			
        			
        			print json_encode($request);
        			
        			exit;
        		}
        		
        		
        		
        		$request=array(
        				"description"=>"Success",
        				"status"=>"0",
        				"timestamp"=>time(),
        				"version"=>$version,
        		);
        		
        		
        		ksort($request);
        		
        		$concatenated_string = implode('', $request) . $merchant_secret;
        		
        		$signature = hash('sha384', $concatenated_string);
        		
        		$request['signature']=$signature;
        		
        		fwrite($myfile, "response[".date('Y-m-d H:i:s')."]:".json_encode($request)."\n\n");
        		
        		
        		print json_encode($request);
        		
        		
        		
        	}
        	
        	

        	
        	
        	fclose($myfile);
        }
        
        
        
        public function notify()
        {
        	$myfile = fopen(logger_url_txt."praxispay_notify.txt", "a") or die("Unable to open file!");

        	$inputJSON = file_get_contents('php://input');
        	$input= json_decode( $inputJSON, TRUE );
        	
        	fwrite($myfile, "received values[".date('Y-m-d H:i:s')."]:".json_encode($input)."\n\n");
        
        	$transaction_type=$input['transaction_type'];
        	$transaction_status=$input['transaction_status'];
        	$transaction_id=$input['transaction_id'];
        	$trace_id=$input['trace_id'];
        	$order_id=$input['order_id'];
        	$frontend=$input['frontend'];
        	$pin=$input['pin'];
        	$amount=$input['amount'];
        	
        	$currency=$input['currency'];
        	$payment_method=$input['payment_method'];
        	$payment_processor=$input['payment_processor'];
        	$created_by=$input['created_by'];
        	$auth_token=$input['auth_token'];
        	$merchant_id=$input['merchant_id'];
        	$card_number=$input['card_number'];
        	$card_type=$input['card_type'];
        	$card_exp=$input['card_exp'];
        	$account_identifier=$input['account_identifier'];
        	$cascade_level=$input['cascade_level'];
        	$is_cascade=$input['is_cascade'];
        	$error_code=$input['error_code'];
        	$error_details=$input['error_details'];
        	$reference_id=$input['reference_id'];
        	$version=$input['version'];

        	
   
        	
        	
        	$merchant_secret = PRAXISPAY_MERCHANT_SECRET;
        	
        	
        	$received_signature=$input['signature'];
        	
        	unset($input['signature']);
        	
        	ksort($input);
        	
        	$concatenated_string_res = implode('', $input) . $merchant_secret;
        	
        	$calculated_signature = hash('sha384', $concatenated_string_res);
        	
        	if($received_signature==$calculated_signature)
        	{
        		$request=array(
        				"description"=>"Success",
        				"status"=>"0",
        				"timestamp"=>time(),
        				"version"=>$version,
        		);
        		
        		
        		ksort($request);
        		
        		$concatenated_string = implode('', $request) . $merchant_secret;
        		
        		$signature = hash('sha384', $concatenated_string);
        		
        		$request['signature']=$signature;
        		
        		fwrite($myfile, "response[".date('Y-m-d H:i:s')."]:".json_encode($request)."\n\n");
        		
        		
        		print json_encode($request);
        		
        		$amount_cnvrtd=$amount/100;
        		
        		if(!empty($card_number))
        		{
        			$card_number_last_4_digit=substr( $card_number, -4 );
        		}
        		
        		if(!empty($card_exp))
        		{
        			
        			$my_arr=explode("/",$card_exp);
        			
        			$exp_month=$my_arr['0'];
        			$exp_year=$my_arr['1'];
        			$exp_year=substr( $exp_year, -2 );
        		}
        		
        		$result123=get_acc_details_forgot_password($pin);
        		
        		$email=$result123->accountsInfo[0]->email;
        		
        		$firstName=$result123->accountsInfo[0]->firstName;
        		
        		$lastName=$result123->accountsInfo[0]->lastName;
        		
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
        			if($acc_t->name==$pin)
        			{
        				//$tp_account_id=$pin;
        				//$currency = $acc_t->baseCurrency->code;
        				$trading_platform_accountid= $acc_t->id;
        				$parentAccountId= $acc_t->parentAccountId;
        			}
        			
        			
        		}
        		
        		
        		if($transaction_type=='sale')
        		{
        			if($transaction_status=='approved')
        			{
        				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        				
        				getBUDetails($owningBusinessUnit);
        				
        				$request1 = new CrmMonetaryTransactionModelRequestModel();
        				$request1->organizationName =ORGANIZATION_NAME;
        				$request1->ownerUserId =$OWNER_USER_ID;
        				$request1->businessUnitName = $BUSINESS_UNIT_NAME;
        				$request1->amount =$amount_cnvrtd;
        				$request1->tradingPlatformAccountId=$trading_platform_accountid;
        				$request1->internalComment ="Praxispay Deposit";
        				$request1->shouldAutoApprove = "TRUE";
        				$request1->filtertype =1;
        				$request1->cardExpirationMonth =$exp_month;
        				$request1->cardExpirationYear =$exp_year;
        				$request1->cardHolderName =$firstName." ".$lastName;
        				$request1->creditCardNumber =$card_number_last_4_digit;
        				
        				
        				
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
        				$curl_infos = curl_getinfo ($ch);
        				$curl_errors = curl_error ($ch);
        				curl_close ( $ch );
        				
        				
        				fwrite($myfile,"mon res [".date('Y-m-d H:i:s')."] =".json_encode($curl_result)."\n\n");
        				fwrite($myfile, "mon info[".date('Y-m-d H:i:s')."] = ".json_encode($curl_infos)."\n\n");
        				fwrite($myfile, "mon err[".date('Y-m-d H:i:s')."] = ".json_encode($curl_errors)."\n\n");
        			}
        			else
        			{
        	
        				$response_description=$input['error_details'];
        		
        				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        				
        				$owning_bu=get_owning_bu($pin);
        				
        				fwrite($myfile,"owning_bu[".date('Y-m-d H:i:s')."] = ".$owning_bu."\n\n");
        				
        				getBUDetails($owning_bu);
        				
        				$request11 = new CrmCreateCaseModel();
        				$request11->organizationName =ORGANIZATION_NAME;
        				$request11->ownerUserId =$OWNER_USER_ID;
        				$request11->businessUnitName = $BUSINESS_UNIT_NAME;
        				$request11->amount =$amount_cnvrtd;
        				$request11->email = $email;
        				$request11->firstName =$firstName;
        				$request11->lastName =$lastName;
        				$request11->title ="Praxis Deposit Failure Case";
        				$request11->description = "Transaction Id -> ". $trace_id."  TP Name -> ". $pin."  Reason -> ".$response_description;
        				$request11->accountId=$parentAccountId;
        				
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
        		
        	}
        	
        	
        	
        	
        	fclose($myfile);
        	
        }
        
   
        
        public function failure()
        {
        	/*
        	if(!empty($_GET['err_msg']))
        	{
        		$_SESSION['pop_mes']= $_GET['err_msg'];
        		popup();
        	}
        	 
        	$data = array();
        	$data['title'] = 'Deposit Failed';
        	$data['user_role'] = strtolower($_SESSION['user_role']);
        	$this->load->view('templates/header' ,$data);
        	//$this->load->view('templates/sidebar');
        	$this->load->view('traders_room/fund_account/cashier1_failure');
        	$this->load->view('templates/footer');
        	*/
        	 
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
        		

        		
        		if( strtolower($_SESSION['user_role']) != strtolower('real') )
        		{
        			$_SESSION['pop_mes'] = 'This Facility is Available only for Real Account Users.';
        			
        			$fail_url=base_url($this->uri->segment(1).'/dashboard');
        			//redirect($fail_url);
        			?>
        			        		        	        	        					    				    			
        	<body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo $fail_url; ?>'; }, 0000)">
        			        		        	        	        					    			
        	<?php 
        		        		        	        	        	
        		
        		}
        		else
				{
					
					
					
					
					$myfile = fopen(logger_url_txt."praxispay.txt", "a") or die("Unable to open file!");
					
					
					$acc=$_SESSION['username'];
					
					$result123=get_acc_details_forgot_password($acc);
					
					$dateOfBirth=$result123->accountsInfo[0]->dateOfBirth;
					
					$pieces = explode("-", $dateOfBirth);
					
					$year=$pieces[0];
					$month=$pieces[1];
					$day=$pieces[2];
					
					$day = substr($day, 0, 2);
					
					if(empty($day) || strlen($day) < 2)
					{
						$day='01';
					}
					
					if(empty($month) || strlen($month) < 2)
					{
						$month='01';
					}
					
					if(empty($year) || $year=="0001" || $year=="1900" || strlen($year) < 4)
					{
						$year='1970';
					}
					
					$dob=$month.'/'.$day.'/'.$year;
					
					$country_fullname=$result123->accountsInfo[0]->country;
					
					$country_iso2=get_country_iso2($country_fullname);
					
					if(empty($country_iso2))
					{
						$country_iso2 = get_country_details();
					}
					
					$firstName=$result123->accountsInfo[0]->firstName;
					$lastName=$result123->accountsInfo[0]->lastName;
					$email=$result123->accountsInfo[0]->email;
					$phoneCountryCode=$result123->accountsInfo[0]->phoneCountryCode;
					$phoneNumber=$result123->accountsInfo[0]->phoneNumber;
					
					$full_phone=$phoneCountryCode.$phoneNumber;
					
					$full_phone=str_replace("+","",$full_phone);
					
					if(strlen($full_phone) < 10 || empty($full_phone))
					{
						$full_phone="1122334455";
					}
					
					$address1=$result123->accountsInfo[0]->address1;
					
					if(empty($address1))
					{
						$address1="address";
					}
					
					$city=$result123->accountsInfo[0]->city;
					
					if(empty($city))
					{
						$city="city";
					}
					
					$zipCode=$result123->accountsInfo[0]->zipCode;
					
					if(empty($zipCode))
					{
						$zipCode="12345";
					}
					
					$state=$result123->accountsInfo[0]->state;
					
					if(empty($state))
					{
						$state="state";
					}
					
					$ip = get_client_ip();  
					
					$url=PRAXISPAY_URL;
					
					$merchant_id = PRAXISPAY_MERCHANT_ID;
					
					$merchant_secret = PRAXISPAY_MERCHANT_SECRET;
					
					
					
					
		
					
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
					
					
					if($currency=='USD')
					{
						$frontend='Praxis TEST_5 USD';
					}
					elseif($currency=='EUR')
					{
						$frontend='Praxis TEST_5 EUR';
					}
					elseif($currency=='GBP')
					{
						$frontend='Praxis TEST_5 GBP';
					}
					
					if($country_iso2=='CA')
					{
						$state='ON';
					}
					
					if($country_iso2=='US')
					{
						$state='WA';
					}
					
					if($country_iso2=='AU')
					{
						$state='ACT';
					}
					
					
					
					$return_url=base_url($this->uri->segment(1).'/cashier11/success');
					
					$validation_url=base_url('en/cashier11/validate');
					
					$notification_url=base_url('en/cashier11/notify');
					
					
					
					$request=array(
							"merchant_id"=>$merchant_id,
							"frontend"=>$frontend,
							"locale"=>"en-GB",
							"currency"=>$currency,
							"pin"=>$acc,
							"requester_ip"=>$ip,
							"first_name"=>$firstName,							
							"last_name"=>$lastName,
							"dob"=>$dob,
							"email"=>$email,
							"address"=>$address1,
							"city"=>$city,
							"country"=>$country_iso2,
							"state"=>$state,
							"zip"=>$zipCode,
							"phone"=>$full_phone,
							"return_url"=>$return_url,
							"validation_url"=>$validation_url,
							"notification_url"=>$notification_url,																				
							"timestamp"=>time(),
							"version"=>"1.1",
							
							
					);
					
					ksort($request);
					
					$concatenated_string = implode('', $request) . $merchant_secret;
					
					$signature = hash('sha384', $concatenated_string);
					
					$request['signature']=$signature;
					
					$request_json_en = json_encode($request);
					
					fwrite($myfile, "req[".date('Y-m-d H:i:s')."]:".$request_json_en."\n\n");
										
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_POST, TRUE);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$request_json_en);
					curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							"Content-Type: application/json"
					));
					$curl_result = curl_exec($ch);
					$curl_error = curl_error($ch);
					$curl_info = curl_getinfo($ch);
					curl_close($ch);
					
					fwrite($myfile, "res[".date('Y-m-d H:i:s')."]:".json_encode($curl_result)."\n\n");
					fwrite($myfile, "info[".date('Y-m-d H:i:s')."]:".json_encode($curl_info)."\n\n");
					fwrite($myfile, "err[".date('Y-m-d H:i:s')."]:".json_encode($curl_error)."\n\n");
					
					$curl_result_jd=json_decode($curl_result,true);
					
					if($curl_result_jd['status']==0)
					{



						$data['redirect_url'] = $curl_result_jd['redirect_url'];
						$data['title'] = 'Deposit';
						$this->load->helper('form');
						$this->load->library('form_validation');
						$data['language'] = $this->uri->segment(1);
						

						$this->load->view('traders_room/fund_account/praxis_iframe_view', $data);
						

						
					}
					else
					{
						$_SESSION['pop_mes']=$curl_result_jd['description'];
						
						$redirect_url=base_url($this->uri->segment(1).'/dashboard');
						
						?>
        	<script>
        	window.top.location.href = "<?php echo $redirect_url;?>"; 
        	</script>
        	<?php
						

        	
					}
					
	
					
					fclose($myfile);
					

				}
        	

        
        }
        
     
}