<?php
class Cashier3 extends CI_Controller 
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
	
	
	public function test()
	{}
	

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
        		$this->load->view('traders_room/fund_account/certus_btc', $data);
        		$this->load->view('templates/footer');
        	}
        
        	}
		else
		{
			
			$token = $this->input->post('my_token_cashier3');
			
			$session_token = $_SESSION['form_token_cashier3'];
			
			unset($_SESSION['form_token_cashier3']);
			
			if( (empty($token)) || (empty($session_token)) || ($token != $session_token) )
			{
				
				$_SESSION['error_pop_mes'] = 'Invalid Session';
				
				redirect(base_url($this->uri->segment(1).'/dashboard/'));
				
				exit;
			}
			
			
			
			
			$myfile = fopen(logger_url_txt."certus_btc_req.txt", "a") or die("Unable to open file!");
			 $result_data = $this->deposite_model->get_details_from_crm_user($this->input->post('acc'));

			 $acc=$this->input->post('acc');
			 
			 $city=$this->input->post('city');

			 $zip=$this->input->post('zip');
					 
			 $firstname = $result_data->firstname;
			 
			 $lastname = $result_data->lastname;
			 
			 $email = $result_data->email;
			 
			 $email_encriptd=my_simple_crypt($email,'e');
			 
			 $currency = $result_data->currency_code;
			
			 $country = $result_data->country;
			 
			 if(empty($country))
			 {
			 	$country=get_country_details();
			 }
			 
			 $country_iso3_arr=$this->deposite_model->get_country_iso3($country);
			 
			 $country_iso3=$country_iso3_arr['iso3'];
			 
			 $phone = $result_data->phone;
			 
			 $phone_encriptd=my_simple_crypt($phone,'e');

			 $amount = $this->input->post('amount');

			 $client_orderid = "ac".$acc.time();

			 $time_stamp = trim(date ( 'Y-m-d H:i:s' ));

			$cancel=trim(base_url($this->uri->segment(1).'/cashier3/failure'));
			
			$return=trim(base_url($this->uri->segment(1).'/cashier3/redirect'));
			
			$notification=trim(base_url('en/cashier3/notification'));




			$user=$this->deposite_model->add_payment_details_certus($firstname,$lastname,$acc,$client_orderid,$amount,$currency,$email_encriptd,$phone_encriptd,$country,$time_stamp);
						
						
		
			fwrite($myfile, "===============================================\n"."certus credentials [".date('Y-m-d H:i:s')."] = ".json_encode($certus_details)."\n\n");

			
			$params = array (
					
					"crypto_currency"=>"BTC",   
					"purchase_currency"=>$currency,   
					"purchase_amount"=>$amount,   
					"email"=>$email,   
					"login_type"=>"without_account",   
					"description"=>$acc,   // test if between 600 to 700 fail otherwise success
					"merchant_ref_id"=>$client_orderid,    
					"country"=>$country_iso3,    
					"mobile"=>$phone,
					
			);
			
			
						
						fwrite($myfile, "request [".date('Y-m-d H:i:s')."] = ".json_encode($params)."\n\n");
						
						$url=CERTUS_BTC_URL.'/api/order/create';
						
	
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
						curl_setopt($ch, CURLOPT_POST, TRUE);
						curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($params));
						curl_setopt($ch, CURLOPT_USERPWD, CERTUS_BTC_CLIENT_ID. ":" . CERTUS_BTC_CLIENT_SECRET);
						curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array(
								"Content-Type: application/json"
						));
						$curl_result = curl_exec($ch);
						$curl_error = curl_error($ch);
						$curl_info = curl_getinfo($ch);
						curl_close($ch);
						
						// print"<pre>"; print_r($curl_result); print"</pre>";  exit();
						
								
						$curl_result_jd=json_decode($curl_result,true);
						
						fwrite($myfile, "response [".date('Y-m-d H:i:s')."] = ".json_encode($curl_result_jd)."\n\n");
						fwrite($myfile, "info [".date('Y-m-d H:i:s')."] = ".json_encode($curl_info)."\n\n");
						fwrite($myfile, "err [".date('Y-m-d H:i:s')."] = ".json_encode($curl_error)."\n\n");
						
						$resp_status=$curl_result_jd['result']['status'];
						
						if($resp_status==true)
						{
							redirect($curl_result_jd['responseData']['payment_url']);
						}
						else
						{
							$_SESSION['pop_mes']=$curl_result_jd['result']['message'];
							
							redirect(base_url($this->uri->segment(1).'/dashboard'));
						}
							
		
		}	
	
	
	
	}
	


	
	public function notification()
	{
		http_response_code(200);

		$jsonText = file_get_contents('php://input');
		$decodedText = html_entity_decode($jsonText);
		$myArray = json_decode($decodedText, true);
		
		$myfile = fopen(logger_url_txt."certus_btc_notification.txt", "a") or die("Unable to open file!");
		fwrite($myfile, "===============================================\n"."dmn array[".date('Y-m-d H:i:s')."] = ".json_encode($myArray)."\n\n");
		
		
		
		
		$signature=$myArray['sign'];
		
		$order_id=$myArray['order_id'];
		
		$requestId=$myArray['merchant_ref_id'];
		
		$order_status=$myArray['order_status'];
		
		$order_type=$myArray['order_type'];
		
		$message=$myArray['message'];
		
		
		$response_signature= base64_encode(hash("sha256", $order_id.$requestId.$order_status.CERTUS_BTC_MERCHANT_ACCOUNT_KEY, True));

		
		fwrite($myfile, "cal sign[".date('Y-m-d H:i:s')."] = ".$response_signature."\n\n");
		
		fwrite($myfile, "received sign[".date('Y-m-d H:i:s')."] = ".$signature."\n\n");
		
		
		
		
		$get_records = $this->deposite_model->get_records($requestId);
		$fname = $get_records->firstname;
		$lname = $get_records->lastname;
		$acc = $get_records->tp_account_id;
		$table_status = $get_records->status;
		$sourceAmount= $get_records->amount;
		
		
		$get_details_from_crm_user = $this->deposite_model->get_details_from_crm_user($acc);
		
		$TradingPlatformAccountId = $get_details_from_crm_user->trading_platform_accountid;		
		$account_id = $get_details_from_crm_user->account_id;
		$email = $get_details_from_crm_user->email;
		
		
		

		
		if($signature==$response_signature)
		{
			if(strtolower($order_status)=='completed' && strtolower($order_type)=='buy')
			{

				
				if( $table_status != 'Success' )
				{
					
					$update_success = $this->deposite_model->update_success($requestId);
					
 
					
					global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
					
					
					$owning_bu=get_owning_bu($acc);
					
					fwrite($myfile,"owning_bu[".date('Y-m-d H:i:s')."] = ".$owning_bu."\n\n");
					
					getBUDetails($owning_bu);
					
					$request1 = new CrmMonetaryTransactionModelRequestModel();
					$request1->organizationName =ORGANIZATION_NAME;
					$request1->ownerUserId =$OWNER_USER_ID;
					$request1->businessUnitName = $BUSINESS_UNIT_NAME;
					$request1->amount =$sourceAmount;
					$request1->tradingPlatformAccountId=$TradingPlatformAccountId;
					$request1->internalComment ="Certus BTC Deposit";
					
					$request1->shouldAutoApprove = "TRUE";
					
					$request1->filtertype =1;
					//$request1->cardExpirationMonth =$date_month;
					//$request1->cardExpirationYear =$ExpiryYear;
					//$request1->cardHolderName =$fname." ".$lname;
					//$request1->creditCardNumber =$last_four;
					
					$request1->new_transactionid=$order_id;
					
					$request1->new_psp='CERTUS BTC';
					
					
					
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
					
					getBUDetails($owning_bu);
					
					$request11 = new CrmCreateCaseModel();
					$request11->organizationName =ORGANIZATION_NAME;
					$request11->ownerUserId =$OWNER_USER_ID;
					$request11->businessUnitName = $BUSINESS_UNIT_NAME;
					$request11->amount =$sourceAmount;
					$request11->email = $email;
					$request11->firstName =$fname;
					$request11->lastName =$lname;
					$request11->title ="Certus BTC Deposit Success Case";
					$request11->description = "Transaction Id -> ". $requestId."  TP Name -> ". $acc;
					$request11->accountId=$account_id;
					$request11->lv_depositidentitynumber='CERTUS BTC';
					
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
				if(strtolower($order_status)=='failed')
				{

				$update_fail = $this->deposite_model->update_fail($requestId);
				
				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
				
				$owning_bu=get_owning_bu($acc);
				
				fwrite($myfile,"owning_bu[".date('Y-m-d H:i:s')."] = ".$owning_bu."\n\n");
				
				getBUDetails($owning_bu);
				
				$request11 = new CrmCreateCaseModel();
				$request11->organizationName =ORGANIZATION_NAME;
				$request11->ownerUserId =$OWNER_USER_ID;
				$request11->businessUnitName = $BUSINESS_UNIT_NAME;
				$request11->amount =$sourceAmount;
				$request11->email = $email;
				$request11->firstName =$fname;
				$request11->lastName =$lname;
				$request11->title ="Certus BTC Deposit Failure Case";
				$request11->description = "Transaction Id -> ". $requestId."  TP Name -> ". $acc."  Reason -> ".$message;
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
		}
		
		fclose($myfile);
		

		
	}
	
	
	
	
	
	
	
	

	public function success() 
	{
		
		$myfile = fopen(logger_url_txt."certus_btc_success.txt", "a") or die("Unable to open file!");
		fwrite($myfile, "===============================================\n"."success data[".date('Y-m-d H:i:s')."] = ".json_encode($_GET)."\n\n");
		fclose($myfile);
		
		$_SESSION['pop_mes']= "Your transaction is successful.";
		//popup();
		
		?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/dashboard'); ?>'; }, 0000)">
        		        		        	        		        	        	        					    			
        		       <?php 
	
	}
	
	
	public function failure() 
	{
		$myfile = fopen(logger_url_txt."certus_btc_failure.txt", "a") or die("Unable to open file!");
		fwrite($myfile, "===============================================\n"."failure data[".date('Y-m-d H:i:s')."] = ".json_encode($_GET)."\n\n");
		fclose($myfile);
		
		$_SESSION['error_pop_mes']= $_GET['message'];
		//popup();
		
		?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/dashboard'); ?>'; }, 0000)">
        		        		        	        		        	        	        					    			
        		       <?php 
	
        
	}
	
	


	
}




