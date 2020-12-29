<?php
class Withdrawal_request extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

				$this->load->helper('url_helper');
				$this->load->model('Common_model');
                $this->load->model('withdrawal_request_model');
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
        	
        	$data['title'] = 'Withdrawal Request';
        	
        	
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	$data['language'] = $this->uri->segment(1);
        	
        	try{
        		$user_email = $this->Common_model->get_current_user_email($_SESSION['username'] );
        		
        		$data   = array();
        		
        		$data['result'] = $this->withdrawal_request_model->get_current_user_data($user_email);
        	}
        	catch(Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"WITHDRAWAL DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('error',$log);
        	}
        	
        
        	$this->form_validation->set_rules('acc', 'lang:Trading Account', 'trim|required|callback_check_default');
        	$this->form_validation->set_rules('type', 'lang:Withdrawal Method', 'trim|required|callback_check_default');
        	$this->form_validation->set_rules('amount', 'lang:Amount', 'trim|required');
        	 
        	$data['language'] = $this->uri->segment(1);
        
        	$method =$this->input->post('type');
        	if($method == "credit")
        	{
        	
        	$this->form_validation->set_rules('ccholdername', 'lang:Card Holder Name', 'trim|required');
        	$this->form_validation->set_rules('ccnum', 'lang:Credit Card Number', 'trim|required|max_length[16]');
        	$this->form_validation->set_rules('expiration_month', 'lang:select Month', 'trim|required|callback_check_default');
        	$this->form_validation->set_rules('expiration_year', 'lang:select Year', 'trim|required|callback_check_default');
        	$this->form_validation->set_rules('cvv', 'lang:CVV NO', 'trim|required');
        }
        if($method == "bank"){
        	
        	$this->form_validation->set_rules('beneficiary', 'lang:Beneficiary', 'trim|required');
        	$this->form_validation->set_rules('iban', 'lang:IBAN NO/Bank Account Number', 'trim|required');
        	$this->form_validation->set_message('swiftcode', 'lang:Swift Code', 'trim|required');
        }
        
        	if($this->form_validation->run() === FALSE)
        	{
        		
        		$this->load->view('templates/header', $data );
        		$this->load->view('templates/left-sidebar', $data );
        		$this->load->view('traders_room/withdrawal_request/withdrawal_request', $data);
        		$this->load->view('templates/footer', $data );
        
        	}
        	else
        	{
        /*		
        		$token = $this->input->post('my_token_withdraw_req');
        		
        		$session_token=null;
        		
        		$session_token = $_SESSION['form_token_withdraw_req'];
        		unset($_SESSION['form_token_withdraw_req']);
        		
        		if(!empty($token) == $session_token)
        		{
        		*/        	
        		$token = $this->input->post('my_token_withdraw_req');
        		if (isset($_COOKIE['form_token_withdraw_req']))
        		{
        			if($_COOKIE['form_token_withdraw_req'] != "null")
        			{
        				$cookie_token=$_COOKIE['form_token_withdraw_req'];
        				unset($_COOKIE['form_token_withdraw_req']);
        			}
        		}
        		if(!empty($token) == $cookie_token){
        		try
        		{
        			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
        
        			$this->load->helper('url');
        			$this->load->helper('prodconfig');
        
        			
        			$language = $this->input->post('language');
        			$method =$this->input->post('type');
        			$acc = $this->input->post('acc');
        			$amount = $this->input->post('amount');
        			$ccnum =$this->input->post('ccnum');
        			$card_Number = substr($ccnum, -4, 4);
        			$card_holders_name = $this->input->post('ccholdername');
        			$cc_expiry_month = $this->input->post('expiration_month');
        			$cc_expiry_year = $this->input->post('expiration_year');
        			$cc_expiry_year_y = substr($cc_expiry_year,2,2);
        			$cvv = $_POST['cvv'];
        			$beneficiary = $this->input->post('beneficiary');
        			$skrillnum = $this->input->post('skrillnum');
        			$bank_account_number = $this->input->post('iban');
        			$swift_code = $this->input->post('swiftcode');
        			
        		  	$balance = get_balance2($this->input->post('acc'));
        		  	

        			
        		  	
        		  	try{
        		  		$account_id = $this->Common_model->get_current_user_acc_id($acc);
        		  		
        		  		$TradingPlatformAccountId = $this->withdrawal_request_model->get_user_tradingplatform_id($acc);
        		  		
        		  		$email = $this->Common_model->get_current_user_email($acc);
        		  		
        		  		$currency_id = $this->withdrawal_request_model->get_user_currency_id($acc);
        		  	}
        		  	catch(Exception $e)
        		  	{
        		  		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		  		"WITHDRAWAL DB EXCEPTION: ".json_encode($e).PHP_EOL
        		  		."-------------------------".PHP_EOL."\n";
        		  		log_message('error',$log);
        		//  		echo "<p id='pop_error_mes'>".$e."</p>";
        		  		
        		  		$_SESSION['error_pop_mes'] = $e;
        		  		redirect($this->uri->segment(1).'/withdrawal-request');
        		  		
        		  		/*
        		  		 $data['title'] = 'Withdrawal Request';
        		  		 $this->load->view('templates/header', $data);
        		  		 $this->load->view('templates/sidebar');
        		  		 $this->load->view('traders_room/withdrawal_request/withdrawal_request', $data);
        		  		 $this->load->view('templates/footer');
        		  		 
        		  		 */
        		  	}
        		  	
        			if($balance < $this->input->post('amount'))
        			{
       
        //		echo "<p id='pop_error_mes'>".lang("Insufficient Balance")."</p>";
        				//$_SESSION['error_pop_mes'] = "Insufficient Balance";
						$_SESSION['error_pop_mes'] = "Dear client, Please contact your account manager to complete this withdrawal request.";
        		redirect($this->uri->segment(1).'/withdrawal-request');
        				
        	
        			}
        			else {
        				
        				
        				
        				try{
        					$account_id123 = $this->withdrawal_request_model->get_current_user_data($email);
        					
        					$account_id=$account_id123[0]->account_id;
        					
        					$get_user_tpid=$account_id123[0]->trading_platform_accountid;
        					
        					$email=$account_id123->email;
        					
        					$currency_id=$account_id123[0]->currency_id;
        					
        				
        				}
        				catch(Exception $e)
        				{
        					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        					"WITHDRAWAL DB EXCEPTION: ".json_encode($e).PHP_EOL
        					."-------------------------".PHP_EOL."\n";
        					log_message('error',$log);
        				}
        				
        				
        				$owning_bu=get_owning_bu($acc);
        				
        				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        				
        				getBUDetails($owning_bu);
        				
        				
        			$request = new CrmCreateWithdrawalRequestModel();
					$request->organizationName=ORGANIZATION_NAME;
					$request->businessUnitName=$BUSINESS_UNIT_NAME;
					$request->ownerUserId=$OWNER_USER_ID;
					$request->creditCardNumber=$ccnum;
					$request->cardExpirationMonth=$cc_expiry_month;
					$request->cardExpirationYear=$cc_expiry_year_y;
					$request->cardHolderName=$card_holders_name;
					$request->email=$email;
					$request->number=$skrillnum;
					$request->amount=$amount;
					$request->accountId=$account_id;
					$request->tradingPlatformAccountName=$acc;
					$request->currencyId=$currency_id;
					$request->bankAccountNumber=$bank_account_number;
					$request->tradingPlatformAccountId=$TradingPlatformAccountId->trading_platform_accountid;
					 $request->IsCancellationTransaction = false;

					 $request->ShouldAutoApprove = "TRUE";
					 $request->UpdateTPOnApprove = "TRUE";
					 $request->updateTpOnApprove= "TRUE";
				$request->WithdrawalCompleteTradingRequest = false;
				$request->WithdrawalHasDocuments = false;
				$request->WithdrawalHasEnoughFundsInTradingPlatform = false;
				$request->WithdrawalIsMethodOfPaymentSuitable = false;
				$request->WithdrawalManagementApproval = false;
				$request->WithdrawalPaid = false;

					//$request->bankName="gdfg";
					$request->beneficiary=$beneficiary;
					$request->swiftCode=$swift_code;
					if($method == "credit")
					{
						$request->withdrawalMethod=2;
					}
						
					if($method == "bank")
					{
						$request->withdrawalMethod=3;
					}
					if($method == "skrill")
					{
						$request->withdrawalMethod=4;
					}
					if($method == "netteller")
					{
						$request->withdrawalMethod=5;
					}
					
					$method = "Withdrawal Request";
					$crmurl = api_url."/CreateWithdrawalUpdated";
					$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
											
					$json_result = $update_mcrm['json_result'];
					$http_code = $update_mcrm['http_code'];
							
					$main_transaction_id = main_transaction_id();

        			if($http_code == "400" || $http_code == "404")
        			{
						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
						"WITHDRAWAL REQUEST: ".json_encode($request).PHP_EOL
						."-------------------------".PHP_EOL."\n";
						log_message('custom',$log);
						
						$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
						"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
						"HTTP CODE:".$http_code."WITHDRAWAL RESPONSE: ".json_encode($json_result).PHP_EOL
						."-------------------------".PHP_EOL."\n";
						log_message('custom',$log);

						 $_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
						 redirect($this->uri->segment(1).'/withdrawal-request');
        			//	echo "<p>Main Transaction Id : ".$main_transaction_id."</p>";
        			}
        			if($http_code == "201" || $http_code == "200") //if operation successful
        			{
        					
        				
        				$_SESSION['pop_mes'] = "Withdrawal Request sent successfully.";
        				redirect($this->uri->segment(1).'/withdrawal-request');
        //	echo "<p id='pop_mes'>".lang("Withdrawal Request sent successfully.")."</p>";
        				}
        
        		}
        		}
        		catch (Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        			"WITHDRAWAL EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
    
        		//	echo "<p id='pop_error_mes'>".$e."</p>";
        			$_SESSION['error_pop_mes'] = $e;
        			redirect($this->uri->segment(1).'/withdrawal-request');
        
        		}
      		}else
        		{
        			redirect ( $this->uri->segment ( 1 ) . '/withdrawal-request');
        		}
    
        		//$this->real_model->user();
        
        	}
        }
        
        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
}