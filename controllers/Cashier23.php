<?php
class Cashier23 extends CI_Controller 
{

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
			redirect($this->uri->segment(1).'/login');
		}
		
		/*
		$result_crm = get_all_acc_details();
		$new_addpsp = $result_crm->getAllAccountDetails[0]->dynamicAttributeValue;
		$lang_smallcase = strtolower($new_addpsp);
		if ($lang_smallcase !="true" && $lang_smallcase!="yes")
		{
			redirect($this->uri->segment(1).'/dashboard');
		}
		*/
		
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
        		$this->load->view('traders_room/fund_account/atomixpay', $data);
        		$this->load->view('templates/footer');
        	}
        
        	}
		else
		{
			exit;
			
			$token = $this->input->post('my_token_cashier23');
			
			$session_token = $_SESSION['form_token_cashier23'];
			
			unset($_SESSION['form_token_cashier23']);
			
			if( (empty($token)) || (empty($session_token)) || ($token != $session_token) )
			{
				
				$_SESSION['error_pop_mes'] = 'Invalid Session';
				
				redirect(base_url($this->uri->segment(1).'/dashboard/'));
				
				exit;
			}
			
			
			
			
			$myfile = fopen(logger_url_txt."atomixpay_req.txt", "a") or die("Unable to open file!");
			 $result_data = $this->deposite_model->get_details_from_crm_user($this->input->post('acc'));

			 $acc=$this->input->post('acc');
			 
			 $city=$this->input->post('city');
			 
			 $city=trim($city);
			 
			 $zip=$this->input->post('zip');
			 
			 $zip=trim($zip);
			 
			 $firstname = $result_data->firstname;
			 $lastname = $result_data->lastname;
			 $email = $result_data->email;
			 
			 $email_encriptd=my_simple_crypt($email,'e');
			 
			 $currency = $result_data->currency_code;
			
			 $country = $result_data->country;
			 $phone = $result_data->phone;
			 
			 $phone_encriptd=my_simple_crypt($phone,'e');

			 $amount = $this->input->post('amount');

	

			 $apiVersion = trim("1.0.1");

			 
			 $client_orderid = "ac".$acc.time();
			
			 $requestId = trim("REQUEST".$client_orderid);
			 
			 $orderId = trim("ORDER".$client_orderid);
			
			 $amount = trim($amount);
			 $currencyCode = trim($currency);


			 $encryptedAccountUsername=trim(encryptDataInBase64($merchantacccountusername,$merchantaccountkey));
			 $encryptedAccountPassword=trim(encryptDataInBase64($merchantaccountpassword,$merchantaccountkey));
			

			 $merchantIdHash=trim(hashDataInBase64($merchantid));
			 $merchantaccountIdHash=trim(hashDataInBase64($merchantaccountid));

			 $time_stamp = trim(date ( 'Y-m-d H:i:s' ));
			//echo $time_stamp;
			$orderDescription=trim("orderpayment");
			$fname=trim($firstname);
			$lname=trim($lastname);
			$countrycode=trim($country);
			$phone=trim($phone);
			$email=trim($email);
		//	$statement=trim("Deposit");
			
			$cancel=trim(base_url($this->uri->segment(1).'/cashier23/failure'));
			
			$return=trim(base_url($this->uri->segment(1).'/cashier23/redirect'));
			
			$notification=trim(base_url('en/cashier23/notification'));

			$recurrenttype=trim("1");
			$perform3DS =trim("1");
			$invoiceNo=trim("INVOICE".$client_orderid);
			$mctMemo=trim("MEMO".$client_orderid);
			$itemName=trim('order');

			
			//$concatenatedstring=trim($time_stamp.$merchantIdHash.$merchantaccountIdHash.$encryptedAccountUsername.$encryptedAccountPassword.$apiVersion.$requestId.$recurrenttype.$perform3DS.$orderId.$orderDescription.$amount.$currencyCode.$fname.$lname.$city.$zip.$countrycode.$phone.$email.$cancel.$return.$notification);
			
			$concatenatedstring=trim($time_stamp.$merchantIdHash.$merchantaccountIdHash.$encryptedAccountUsername.$encryptedAccountPassword.$apiVersion.$requestId.$recurrenttype.$perform3DS.$orderId.$orderDescription.$amount.$currencyCode.$fname.$lname.$countrycode.$phone.$email.$cancel.$return.$notification);
			
			
			$signature1=createSignature($concatenatedstring,$merchantaccountkey);

			$user=$this->deposite_model->add_payment_details_atomixpay($fname,$lname,$this->input->post('acc'),$requestId,$amount,$currencyCode,$email_encriptd,$phone_encriptd,$countrycode,$time_stamp);
						
						

			
			$params = array (
					"requestTime"=>$time_stamp,
					"mId"=>$merchantIdHash,
					"maId"=>$merchantaccountIdHash,
					"userName"=>$encryptedAccountUsername,
					"password"=>$encryptedAccountPassword,
					"signature"=>$signature1,
					"lang"=>"en",
					"metaData"=>array(
							"merchantUserId"=>$acc,
					),
					"txDetails" => array (
							"apiVersion"=>$apiVersion,
							"requestId"=>$requestId,
							"perform3DS"=>$perform3DS,
							"recurrentType"=>$recurrenttype,
							"orderData" => array (
									"orderId"=>$orderId,
									"orderDescription"=>$orderDescription,
									"amount"=>$amount,
									"currencyCode"=>$currencyCode,
									"billingAddress" => array (
											"firstName"=>$fname,
											"lastName"=>$lname,
											//"city"=>$city,	
											//"zipcode"=>$zip,
											"countryCode"=>$countrycode,
											"phone"=>$phone,
											"email"=>$email,
									),
									"orderDetail"=>array(
											"invoiceNo"=>$invoiceNo,
											"mctMemo"=>$mctMemo,
											"orderItems"=>array(
													"itemName"=>$itemName,
											),
									),

							),
							"cancelUrl"=>$cancel,
							"returnUrl"=>$return,
							"notificationUrl"=>$notification,
					),
			);
			
			
						
						fwrite($myfile, "request [".date('Y-m-d H:i:s')."] = ".json_encode($params)."\n\n");
						
						
						$data_string = json_encode ( $params );
						$base64incod_jsonRequest=base64_encode($data_string);
						
		
		}	
	
	
	
	}
	

	public function redirect()
	{
		$myfile = fopen(logger_url_txt."atomixpay_redirect.txt", "a") or die("Unable to open file!");		
		
		fwrite($myfile, "===============================================\n"."redirect data [".date('Y-m-d H:i:s')."] = ".json_encode($_GET)."\n\n");
		
		fclose($myfile);
		
		 
		
		$resultCode=trim($_GET['resultCode']);
		
		$requestId=trim($_GET['requestId']);
		
		if($resultCode==1)
		{
			$_SESSION['pop_mes']= "Your transaction is successful.";
			redirect($this->uri->segment(1).'/dashboard' );
		}
		else
		{
			
			$_SESSION['error_pop_mes']= $_GET['errorMessage0'];
			redirect($this->uri->segment(1).'/dashboard' );
			
			
		}
		
		
	}
	
	public function notification()
	{
		//http_response_code(200);

		$jsonText = file_get_contents('php://input');
		$decodedText = html_entity_decode($jsonText);
		$myArray = json_decode($decodedText, true);
		
		$myfile = fopen(logger_url_txt."atomixpay_notification.txt", "a") or die("Unable to open file!");
		fwrite($myfile, "===============================================\n"."dmn array[".date('Y-m-d H:i:s')."] = ".json_encode($myArray)."\n\n");
		fwrite($myfile, "===============================================\n"."dmn post[".date('Y-m-d H:i:s')."] = ".json_encode($_POST)."\n\n");
		fwrite($myfile, "===============================================\n"."dmn get[".date('Y-m-d H:i:s')."] = ".json_encode($_GET)."\n\n");
		
		
		
		$requestId=trim($myArray['requestId']);
		
		$resultCode=trim($myArray['result']['resultCode']);
		
		$reasonCode=trim($myArray['result']['reasonCode']);
		
		$responseTime=trim($myArray['responseTime']);
		
		$txId=trim($myArray['txId']);
		
		$cardId=trim($myArray['cardId']);
		
		$txTypeId=trim($myArray['txTypeId']);
		
		$recurrentTypeId=trim($myArray['recurrentTypeId']);
		
		$orderId=trim($myArray['orderId']);
		
		$sourceAmount=trim($myArray['sourceAmount']['amount']);
		
		$sourceCurrencyCode=trim($myArray['sourceAmount']['currencyCode']);
		
		$amount=trim($myArray['amount']['amount']);
		
		$currencyCode=trim($myArray['amount']['currencyCode']);
		
		$ccNumber=trim($myArray['ccNumber']);
		
		$last_four = substr ($ccNumber, -4);
		
		
		
		$signature=trim($myArray['signature']);
		
		$Concatenated_string=trim($responseTime.$txId.$txTypeId.$recurrentTypeId.$requestId.$orderId.$sourceAmount.$sourceCurrencyCode.$amount.$currencyCode.$resultCode.$reasonCode.$ccNumber.$cardId);
		
		$response_signature=createSignature($Concatenated_string,$merchantaccountkey);
		
		fwrite($myfile, "cal sign[".date('Y-m-d H:i:s')."] = ".$response_signature."\n\n");
		
		fwrite($myfile, "received sign[".date('Y-m-d H:i:s')."] = ".$signature."\n\n");
		
		
		
		
		$get_records = $this->deposite_model->get_records($requestId);
		$fname = $get_records->firstname;
		$lname = $get_records->lastname;
		$acc = $get_records->tp_account_id;
		$table_status = $get_records->status;
		
		
		
		$result123=get_acc_details_forgot_password($acc);
		
		$owningBusinessUnit=$result123->accountsInfo[0]->owningBusinessUnit;
		
		$account_id = $result123->accountsInfo[0]->id;
		
		$email = $result123->accountsInfo[0]->email;
		
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
		
		

		

		
		if($signature==$response_signature)
		{
			if($resultCode=='1')
			{

				
				if( $table_status != 'Success' )
				{
					
					$update_success = $this->deposite_model->update_success($requestId);
					
 
					
					global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
					
					

					
					getBUDetails($owningBusinessUnit);
					
					$request1 = new CrmMonetaryTransactionModelRequestModel();
					$request1->organizationName =ORGANIZATION_NAME;
					$request1->ownerUserId =$OWNER_USER_ID;
					$request1->businessUnitName = $BUSINESS_UNIT_NAME;
					$request1->amount =$sourceAmount;
					$request1->tradingPlatformAccountId=$TradingPlatformAccountId;
					$request1->internalComment ="atomixpay Deposit";
					
					$request1->shouldAutoApprove = "TRUE";
					
					$request1->filtertype =1;
					//$request1->cardExpirationMonth =$date_month;
					//$request1->cardExpirationYear =$ExpiryYear;
					$request1->cardHolderName =$fname." ".$lname;
					$request1->creditCardNumber =$last_four;
					
					$request1->new_transactionid=$txId;
					
					$request1->new_psp='atomixpay';
					
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
					$request11->title ="atomixpay Deposit Success Case";
					$request11->description = "Transaction Id -> ". $requestId."  TP Name -> ". $acc;
					$request11->accountId=$account_id;
					$request11->lv_depositidentitynumber='atomixpay';
					
					
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
				$update_fail = $this->deposite_model->update_fail($requestId);
				
				$err_arr=$myArray['result']['error'];
				
				foreach($err_arr as $my_row)
				{
					$errorMessage=$my_row['errorMessage'];
					
				}
				
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
				$request11->title ="atomixpay Deposit Failure Case";
				$request11->description = "Transaction Id -> ". $requestId."  TP Name -> ". $acc."  Reason -> ".$errorMessage;
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




