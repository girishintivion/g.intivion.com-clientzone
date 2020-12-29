<?php
class Interfund_transfer extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

				$this->load->helper('url_helper');
				$this->load->model('Common_model');
                $this->load->model('interfund_transfer_model');
                $this->load->helper(array('url'));
                $this->load->helper('prodconfig');

                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
     
        }

        public function update()
        {}
        
        public function index()
        {
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	
        	try{
        		$user_email = $this->Common_model->get_current_user_email($_SESSION['username'] );
        		
        		$data   = array();
        		
        		$data['result'] = $this->interfund_transfer_model->get_current_user_data($user_email);
        	}
        	catch(Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"INTERFUND TRANSFER DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('error',$log);
        	}
        	$data['title'] = 'Inter Account Fund Transfer';
        	
        	
        	
        	$this->form_validation->set_rules('accno', 'lang:From Account', 'trim|required|callback_check_default');
        	$this->form_validation->set_rules('accno_two', 'lang:accno_two', 'trim|required|callback_check_default');
        	$this->form_validation->set_rules('amount', 'lang:Amount', 'trim|required');
        	
        	if($this->form_validation->run() === FALSE)
        	{
        		$this->load->view('templates/header', $data );
        		$this->load->view('templates/left-sidebar', $data );
        		$this->load->view('traders_room/interfund_transfer/interfund', $data);
        		$this->load->view('templates/footer', $data );
        	}
        	else{
        		/*
        		 $token = $this->input->post('my_token_interfund');
        		 $session_token=null;
        		 $session_token = $_SESSION['form_token_interfund'];
        		 unset($_SESSION['form_token_interfund']);
        		 if (!empty($token) == $session_token){
        		 */
        		$token = $this->input->post('my_token_interfund');
        		if (isset($_COOKIE['form_token_interfund']))
        		{
        			if($_COOKIE['form_token_interfund'] != "null")
        			{
        				$cookie_token=$_COOKIE['form_token_interfund'];
        				unset($_COOKIE['form_token_interfund']);
        			}
        		}
        		if(!empty($token) == $cookie_token){
        			try
        			{
        				
        				
        				$this->load->helper('url');
        				$this->load->helper('prodconfig');
        				
        				
        				$accno = $this->input->post('accno');
        				$accno_two = $this->input->post('accno_two');
        				$fname = $this->input->post('fname');
        				$lname = $this->input->post('lname');
        				$amount = $this->input->post('amount');
        				$language = $this->input->post('language');
        				
        				
        				
        				
        				getBUDetails($_SESSION['BUSINESS_UNIT']);
        				
        				$balance = get_balance($accno);
        				if($balance < $this->input->post('amount'))
        				{        					
        					$_SESSION['error_pop_mes'] = "Insufficient Balance";
        					redirect($this->uri->segment(1).'/interfund-transfer');
        					
        					
        				}
        				
        				try{
        					$tp_acc_id_username = $this->interfund_transfer_model->get_user_tradingPlatformAccountId($accno);
        					
        					$tp_acc_id_username_two = $this->interfund_transfer_model->get_user_oppositeAccountId($accno_two);
        				}
        				catch(Exception $e)
        				{
        					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        					"INTERFUND TRANSFER DB EXCEPTION: ".json_encode($e).PHP_EOL
        					."-------------------------".PHP_EOL."\n";
        					log_message('error',$log);
        				}
        				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        				
        				$request = new CrmInterAccFundTransferRequestModel();
        				$request->organizationName=ORGANIZATION_NAME;
        				$request->businessUnitName=$BUSINESS_UNIT_NAME;
        				$request->ownerUserId=$OWNER_USER_ID;
        				$request->amount=$amount;
        				$request->tradingPlatformAccountId=$tp_acc_id_username;
        				$request->internalComment = "Transfer From Account -> ".$accno ."    To -> ". $accno_two ."    Amount -> ". $amount;
        				$request->oppositeAccountId=$tp_acc_id_username_two;
        				$request->typ="Inter Account Funds Transfer";
        				
        				$method = "Interfund Transfer";
						$crmurl = api_url."/CrmInterAccFundTransfer";
						$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
													
						$json_result = $update_mcrm['json_result'];
						$http_code = $update_mcrm['http_code'];
									
						$main_transaction_id = main_transaction_id();
        				
        				if($http_code == "400" || $http_code == "404")
        				{
        					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
							"INTER FUND TRANSFER REQUEST: ".json_encode($request).PHP_EOL
							."-------------------------".PHP_EOL."\n";
							log_message('custom',$log);
							
							$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
							"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
							"HTTP CODE:".$http_code."INTER FUND TRANSFER RESPONSE: ".json_encode($json_result).PHP_EOL
							."-------------------------".PHP_EOL."\n";
							log_message('custom',$log);
        				
        					$position = strstr($json_result,"[ Response ]");
        					$res = strstr($position,"[ Exception ]",true);
        					$res1= str_replace('"', "", $res);
        					$res2 = strstr($res1,"Message:");
        					$res3 = strstr($res2,"}",true);
        					$res4 = strstr($res3,",RequestId",true);
        					
         					//echo "<p id='pop_error_mes'>".$res4."</p><p>Main Transaction Id : ".$main_transaction_id."</p>";
        			$_SESSION['error_pop_mes'] = $res4."</p><p>Main Transaction Id : ".$main_transaction_id;
        			redirect ( $this->uri->segment ( 1 ) . '/interfund-transfer');
        				}
        				if($http_code == "201" || $http_code == "200") //if operation successful
        				{
        
        				//	echo "<p id='pop_mes'>".$json_result->message."</p>";
        					$_SESSION['pop_mes'] = "The transaction was successful.";//$json_result->message;
        					redirect ( $this->uri->segment ( 1 ) . '/interfund-transfer');
        				}
        				
        				
        			}
        			catch (Exception $e)
        			{
        				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        				"INTER FUND TRANSFER EXCEPTION: ".json_encode($e).PHP_EOL
        				."-------------------------".PHP_EOL."\n";
        				log_message('error',$log);
        				$_SESSION['error_pop_mes'] = $e;
        				redirect ( $this->uri->segment ( 1 ) . '/interfund-transfer');
        			}
        		}else{
        			redirect($this->uri->segment(1).'/interfund-transfer');
        		}
        		
        		//$this->real_model->user();
        		
        	}
        }
        
        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
}