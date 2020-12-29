<?php
class Cashier extends CI_Controller {
	
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

	
        
        
        
        public function notify()
        {
        	
        	echo 'OK';
        	
        	$myfile = fopen(logger_url_txt."qpg_notify.txt", "a") or die("Unable to open file!");

        	
        	fwrite($myfile, "===============================================\n"."received values[".date('Y-m-d H:i:s')."]:".json_encode($_POST)."\n\n");
        
        	
        	$transaction_status=$_POST['action'];
        	$secretId=$_POST['secretId'];
        	$amount=$_POST['amount'];
        	$btcamount=$_POST['btcamount'];
        	$notes=$_POST['notes'];
        	
        	$get_records = $this->deposite_model->get_records($secretId);
        	
        	$table_status = $get_records->status;      	
        	$tp_account_id= $get_records->tp_account_id;
        	
        	
        	$get_details = $this->deposite_model->get_details_from_crm_user($tp_account_id);
        	
        	$firstname = $get_details->firstname;
        	$lastname = $get_details->lastname;        
        	$email = $get_details->email;
        	$trading_platform_accountid = $get_details->trading_platform_accountid;
        	$account_id = $get_details->account_id;       	
        	$business_unit = $get_details->business_unit;
        	
        	$owningBusinessUnit=get_owning_bu($business_unit);

        	if(strtolower($transaction_status)==strtolower('approve'))
        			{

        				if( $table_status != 'Success' )
        				{
        				
        					$update_success = $this->deposite_model->update_success($secretId);
        				
        				
        				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        				
        				getBUDetails($owningBusinessUnit);
        				
        				$request1 = new CrmMonetaryTransactionModelRequestModel();
        				$request1->organizationName =ORGANIZATION_NAME;
        				$request1->ownerUserId =$OWNER_USER_ID;
        				$request1->businessUnitName = $BUSINESS_UNIT_NAME;
        				$request1->amount =$amount;
        				$request1->tradingPlatformAccountId=$trading_platform_accountid;
        				$request1->internalComment ="QPG Deposits";
        				$request1->shouldAutoApprove = "TRUE";
        				$request1->filtertype =1;
        				//$request1->cardExpirationMonth =$exp_month;
        				//$request1->cardExpirationYear =$exp_year;
        				//$request1->cardHolderName =$firstName." ".$lastName;
        				//$request1->creditCardNumber =$card_number_last_4_digit;
        				
        				$request1->new_transactionid=$secretId;
        				
        				$request1->new_psp='QUBEPAY';
        				
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
        				
        				
        				
        				
        				
        				
        				
        				
        				
        				
        				
        				
        				
        	
        				
        				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        				
        				getBUDetails($owningBusinessUnit);
        				
        				$request11 = new CrmCreateCaseModel();
        				$request11->organizationName =ORGANIZATION_NAME;
        				$request11->ownerUserId =$OWNER_USER_ID;
        				$request11->businessUnitName = $BUSINESS_UNIT_NAME;
        				$request11->amount =$amount;
        				$request11->email = $email;
        				$request11->firstName =$firstname;
        				$request11->lastName =$lastname;
        				$request11->title ="QPG Deposit Success Case";
        				$request11->description = "Transaction Id -> ". $secretId."  TP Name -> ". $tp_account_id;
        				$request11->accountId=$account_id;
        				$request11->lv_depositidentitynumber='QUBEPAY';
        				
        				
        				
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
				   	
				   	$update_fail = $this->deposite_model->update_fail($secretId);
				   	
				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        				
        		getBUDetails($owningBusinessUnit);
		
		$request11 = new CrmCreateCaseModel();
		$request11->organizationName =ORGANIZATION_NAME;
		$request11->ownerUserId =$OWNER_USER_ID;
		$request11->businessUnitName = $BUSINESS_UNIT_NAME;
		$request11->amount =$amount;
		$request11->email = $email;
		$request11->firstName =$firstname;
		$request11->lastName =$lastname;		
		$request11->title ="QPG Deposit Failure Case";
		$request11->description = "Transaction Id -> ". $secretId."  TP Name -> ". $tp_account_id."  Reason -> ".$notes;
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
        		
 
        	
        	fclose($myfile);
        	
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
        		$this->load->view('traders_room/fund_account/qpg', $data);
        		$this->load->view('templates/footer');
        	}
        
        	}
        	
        		else
				{
					
					
					$token = $this->input->post('my_token_cashier');
					
					$session_token = $_SESSION['form_token_cashier'];
					
					unset($_SESSION['form_token_cashier']);
					
					if( (empty($token)) || (empty($session_token)) || ($token != $session_token) )
					{
						
						$_SESSION['error_pop_mes'] = 'Invalid Session';
						
						redirect(base_url($this->uri->segment(1).'/dashboard/'));
						
						exit;
					}
					
					
					$myfile = fopen(logger_url_txt."qpg.txt", "a") or die("Unable to open file!");
					
					
					$acc = $this->input->post('acc');
					
					$get_details = $this->deposite_model->get_details_from_crm_user($acc);
					
					$firstname = $get_details->firstname;
					$lastname = $get_details->lastname;
					$currency = $get_details->currency;
					$email = $get_details->email;
					
					$email_encriptd=my_simple_crypt($email,'e');
					
					$phone = $get_details->phone;
					
					$phone_encriptd=my_simple_crypt($phone,'e');
					
					$country = $get_details->country;
					
					$amount= $this->input->post ( 'amount' );
					
					$amtpos = strpos ( $amount, "." );
					
					if ($amtpos == false) {
						$amount .= ".00";
					}
					
					$clientId=uniqid();
					
					$fundingSourceName='BTC3';

					$notification_url=base_url('en/cashier/notify');

					$request=array(
							
							"firstName"=>$firstname,
							"lastName"=>$lastname,
							"clientId"=>$clientId,
							"currency"=>$currency,
							"amount"=>$amount,
							"fundingSourceName"=>$fundingSourceName,
							"returnUrl"=>$notification_url,

							
					);

				
					
					$url='https://qpg.midaswms.com/preparePost';
					
					fwrite($myfile, "===============================================\n"."request[".date('Y-m-d H:i:s')."]:".json_encode($request)."\n\n");
					
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_POST, TRUE);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$request);
					curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							//"Content-Type: application/json"
					));
					$curl_result = curl_exec($ch);
					$curl_error = curl_error($ch);
					$curl_info = curl_getinfo($ch);
					curl_close($ch);
					
					// print"<pre>"; print_r($curl_result); print"</pre>";  exit();
					
				
					
					fwrite($myfile, "response[".date('Y-m-d H:i:s')."]:".json_encode($curl_result)."\n\n");
					fwrite($myfile, "info[".date('Y-m-d H:i:s')."]:".json_encode($curl_info)."\n\n");
					fwrite($myfile, "err[".date('Y-m-d H:i:s')."]:".json_encode($curl_error)."\n\n");
					
					$curl_result_jd=json_decode($curl_result,true);
					
				if(!empty($curl_result_jd['secretId']))
				{
					
					$secretId=$curl_result_jd['secretId'];
					
					$mcTxId=$curl_result_jd['mcTxId'];
					
					$redirect_url="https://qpg.midaswms.com/$fundingSourceName/Start?mcTxId=$mcTxId";
						
					$add_payment_details = $this->deposite_model->add_payment_details_qpg($firstName,$lastName,$acc,$secretId,$currency,$email_encriptd,$phone_encriptd,$country,date('Y-m-d H:i:s'));
					
					redirect($redirect_url);
					
					/*
						$data['redirect_url'] = $redirect_url;						
						$data['title'] = 'Deposit';
						$this->load->view ( 'templates/header', $data );
						$this->load->view ( 'templates/left-sidebar' );
						$this->load->view ( 'traders_room/fund_account/qpg', $data );
						$this->load->view ( 'templates/footer' );
						*/
						 
						
					}
					else
					{
						
						//$_SESSION['error_pop_mes']=$curl_result_jd['description'];
						
						$_SESSION['error_pop_mes']=$curl_result;
						
						$redirect_url=base_url($this->uri->segment(1).'/dashboard');
						
						redirect($redirect_url);

        	
					}
					
	
					
					fclose($myfile);
					

				}
        	

        
        }
        
     
}