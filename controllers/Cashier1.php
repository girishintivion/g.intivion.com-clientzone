<?php
class Cashier1 extends CI_Controller {
	
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
	
	
	public function atest()
	{
		
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		
		$username = 'lum-customer-hl_db480584-zone-static-route_err-pass_dyn';
		$password = 'wa22odw9t60g';
		$port = 22225;
		$user_agent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
		$session = mt_rand();
		$super_proxy = 'zproxy.lum-superproxy.io';
		$curl = curl_init('http://lumtest.com/myip.json');
		curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_PROXY, "http://$super_proxy:$port");
		curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$username-country-gb-session-$session:$password");
		$result = curl_exec($curl);
		$curl_error = curl_error($curl);
		$curl_info = curl_getinfo($curl);
		curl_close($curl);
		
		echo 'res--><br>';
		print"<pre>";print_r($result);print"</pre>";
		echo 'err--><br>';
		print"<pre>";print_r($curl_error);print"</pre>";
		echo 'info--><br>';
		print"<pre>";print_r($curl_info);print"</pre>";
		
		$result_jd=json_decode($result,true);
		echo $result_jd['ip'];
		//if ($result)
		//	echo $result;
	}
	
	public function mtest()
	{
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
		echo get_uk_ip();
	}
	
	public function checkpsp() {
		
		//$result=get_acc_details_forgot_password('1310398330');
		//print"<pre>"; print_r($result); print"</pre>";
		//exit;
		
		//$my_email='aa@bb.cc';
		//$my_phone='+23658741';
		
		//echo $my_phone_encriptd=my_simple_crypt($my_phone,'e');
		
		//echo '<br>'.$my_phone_decriptd=my_simple_crypt($my_phone_encriptd,'d');
		
		$this->db->select('*');
		$this->db->from('payment_status');
		//$this->db->where('tp_account_id', '1310397728');
		$query = $this->db->get();
		$result = $query->result();
		
	//	echo '<br>'.$my_email_encriptd=$result['0']->telephone;
	//	echo '<br>'.$my_email_decriptd=my_simple_crypt($my_email_encriptd,'d');
		print"<pre>"; print_r($result); print"</pre>";
		
	}
	
	
	
	
	public function redirect()
	{
		
		$redirect_url=base_url($this->uri->segment(1).'/cashier1/backoffice');
		
		//redirect($success_url);
		?>
        			        		        	        	        					    				    			
        	<body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo $redirect_url; ?>'; }, 0000)">
        			        		        	        	        					    			
        	<?php 
        	

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
     		
     		redirect(base_url($this->uri->segment(1).'/cashier1/access_denied'));
     		
     		exit;
     		
     	}
     	
     	
     	$data = array();
     	$data['title'] = 'Deposit Fund';
     	$data['language'] = $this->uri->segment(1);
     	
     	$this->load->helper('form');
     	$this->load->library('form_validation');
     	
     	$this->form_validation->set_rules('acc', 'lang:Trading Account No', 'trim|required');
     	
     	if($this->form_validation->run() === FALSE)
     	{
     		$data['gateway']='PayCent';
     		$this->load->view('templates/before-login-header', $data );
     		$this->load->view('traders_room/fund_account/realdeposits_direct_paycent');
     		$this->load->view('templates/before-login-footer');
     	}
     	
     	else
     	{
     		
     		$token = $this->input->post('my_token_cashier1_backoffice');
     		
     		$session_token = $_SESSION['form_token_cashier1_backoffice'];
     		
     		unset($_SESSION['form_token_cashier1_backoffice']);
     		
     		if( (empty($token)) || (empty($session_token)) || ($token != $session_token) )
     		{
     			
     			$_SESSION['pop_mes'] = 'Invalid Session';
     			
     			redirect(base_url($this->uri->segment(1).'/cashier1/backoffice'));
     			
     			exit;
     		}
     		
     		
     		
     		
     		
     		$myfile = fopen(logger_url_txt."Real_deposits.txt", "a") or die("Unable to open file!");
     		
     		
     		$acc=$_POST['acc'];
     		
     		$result123=get_acc_details_forgot_password($acc);
     		
     		$code=$result123->result->code;
     		
     		if($code=='2')
     		{
     			
     			
     			$_SESSION['pop_mes'] = 'This account does not exists';
     			
     			redirect(base_url($this->uri->segment(1).'/cashier1/backoffice'));
     			
     			exit;
     			
     		}
     		
     		$dateOfBirth=$result123->accountsInfo[0]->dateOfBirth;
     		
     		$pieces = explode("-", $dateOfBirth);
     		
     		$year=$pieces[0];
     		$month=$pieces[1];
     		$day=$pieces[2];
     		
     		$day = substr($day, 0, 2);
     		
     		if(empty($day) || strlen($day) < 2)
     		{
     			$day='';
     		}
     		
     		if(empty($month) || strlen($month) < 2)
     		{
     			$month='';
     		}
     		
     		if(empty($year) || $year=="0001" || $year=="1900" || strlen($year) < 4)
     		{
     			$year='';
     		}
     		
     		if( (!empty($day)) && (!empty($month)) && (!empty($year)) )
     		{
     			$dob=$month.'/'.$day.'/'.$year;
     		}
     		else
     		{
     			$dob='';
     		}
     		
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
     			$full_phone="";
     		}
     		
     		$address1=$result123->accountsInfo[0]->address1;
     		
     		if(empty($address1))
     		{
     			$address1="";
     		}
     		
     		$city=$result123->accountsInfo[0]->city;
     		
     		if(empty($city))
     		{
     			$city="";
     		}
     		
     		/*
     		$zipCode=$result123->accountsInfo[0]->zipCode;
     		
     		if(empty($zipCode))
     		{
     			$zipCode="";
     		}
     		
     		$state=$result123->accountsInfo[0]->state;
     		
     		if(empty($state))
     		{
     			$state="";
     		}
     		*/
     		
     		//$ip = get_client_ip();
     		
     		$ip=get_uk_ip();
     		
     		$transaction_id = $acc;
     		$client_orderid = "ac" . $transaction_id . time ();
     		
     		/*
     		 $amount= $this->input->post ( 'amount' );
     		 
     		 $amtpos = strpos ( $amount, "." );
     		 
     		 if ($amtpos == false) {
     		 $amount .= ".00";
     		 }
     		 
     		 $amount_in_cent = $amount * 100;
     		 
     		 */
     		$card_exp_month = $this->input->post ( 'expiration_month' );
     		$card_exp_year = $this->input->post ( 'expiration_year' );
     		
     		$card_exp = $card_exp_month.'/'. $card_exp_year;
     		
     		$card_number= $this->input->post ( 'ccnum' );
     		$cvv= $this->input->post ( 'cvv' );
     		
     		
     		$url=REAL_DEPOSITS_URL;
     		
     		$merchant_id = REAL_DEPOSITS_MERCHANT_ID;
     		
     		$merchant_secret = REAL_DEPOSITS_MERCHANT_SECRET;
     		
     		$application_key = REAL_DEPOSITS_APPLICATION_KEY;
     		
     		
     		
     		
     		
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
     		
     		/*
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
     		 */
     		
     		$return_url=base_url($this->uri->segment(1).'/cashier1/redirect');
     		
     		$notification_url=base_url('en/cashier1/notify');
     		
     		$validation_url=base_url('en/cashier1/validate');
     		
     		$address1 = $this->input->post('address');
     		$city = $this->input->post('city');
     		$state= $this->input->post('state');
     		$zipCode= $this->input->post('zip');
     		
     		
     		$request=array(
     				"gateway"=>REAL_DEPOSITS_GATEWAY_PAYCENT,
     				"address"=>$address1,
     				"application_key"=>$application_key,
     				"city"=>$city,
     				"country"=>$country_iso2,
     				"currency"=>$currency,
     				//	"amount"=>$amount_in_cent,
     				"dob"=>$dob,
     				"email"=>$email,
     				"first_name"=>$firstName,
     				"last_name"=>$lastName,
     				"locale"=> "en-GB",
     				"merchant_id"=>$merchant_id,
     				"notification_url"=>$notification_url,
     				//	"payment_method"=> null,
     				"payment_method"=> "Real Deposits Payment",
     				"phone"=>$full_phone,
     				"pin"=>$acc,
     				"requester_ip"=>$ip,
     				"return_url"=>$return_url,
     				"timestamp"=>time(),
     				"validation_url"=>$validation_url,
     				"version"=>"1.2",
     				"zip"=>$zipCode,
     				"signature"=>$signature,
     				
     				"state"=>$state,
     				//	"order_id"=>$client_orderid,
     				"variable1"=>$client_orderid,
     				
     				/*
     		
     				"card_exp"=>$card_exp,
     				"card_number"=>$card_number,
     				"cvv"=>$cvv,
     				"gateway"=>null,
     				"transaction_type"=>"sale",
     				*/
     				
     		);
     		
     		
     		ksort($request);
     		
     		$concatenated_string = implode('', $request) . $merchant_secret;
     		
     		$signature = hash('sha384', $concatenated_string);
     		
     		$request['signature']=$signature;
     		
     		fwrite($myfile, "===============================================\n"."signature[".date('Y-m-d H:i:s')."]:".$signature."\n\n");
     		
     		// print"<pre>"; print_r($request); print"</pre>";  exit();
     		
     		$request_json = json_encode($request);
     		
     		fwrite($myfile, "Real_deposits_Request[".date('Y-m-d H:i:s')."]: backoffice : ".$request_json."\n\n");
     		
     		
     		$ch = curl_init();
     		curl_setopt($ch, CURLOPT_URL, $url);
     		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
     		curl_setopt($ch, CURLOPT_POST, TRUE);
     		curl_setopt($ch, CURLOPT_POSTFIELDS,$request_json);
     		curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
     		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
     				"Content-Type: application/json"
     		));
     		$curl_result = curl_exec($ch);
     		$curl_error = curl_error($ch);
     		$curl_info = curl_getinfo($ch);
     		curl_close($ch);
     		
     		// print"<pre>"; print_r($curl_result); print"</pre>";  exit();
     		
     		
     		
     		fwrite($myfile, "Real_deposits_Response[".date('Y-m-d H:i:s')."]:".json_encode($curl_result)."\n\n");
     		fwrite($myfile, "info[".date('Y-m-d H:i:s')."]:".json_encode($curl_info)."\n\n");
     		fwrite($myfile, "err[".date('Y-m-d H:i:s')."]:".json_encode($curl_error)."\n\n");
     		
     		$curl_result_jd=json_decode($curl_result,true);
     		
     		if($curl_result_jd['status']==0)
     		{
     			
     			$add_payment_details = $this->deposite_model->add_payment_details_real_deposits($firstName,$lastName,$acc,$curl_result_jd['auth_token'],$currency,$email,$full_phone,$country_iso2,date('Y-m-d H:i:s'));
     			
     			//	$data['redirect_url'] = $curl_result_jd['redirect_url'];
     			$data['title'] = 'Deposit';
     			$this->load->helper('form');
     			$this->load->library('form_validation');
     			$data['language'] = $this->uri->segment(1);
     			
     			
     			//	$this->load->view('traders_room/fund_account/real_deposits_iframe_view', $data);
     			
     			$data ['redirect_url'] = $curl_result_jd['redirect_url'];
     			$data['gateway']='PayCent';
     			$data['from_ip']=$ip;
     			$this->load->view('templates/before-login-header', $data );
     			$this->load->view('traders_room/fund_account/realdeposits_direct_paycent');
     			$this->load->view('templates/before-login-footer');
     			
     			
     			
     			
     		}
     		else
     		{
     			$_SESSION['pop_mes']=$curl_result_jd['description'];
     			
     			$redirect_url=base_url($this->uri->segment(1).'/cashier1/backoffice');
     			
     			?>
        	<script>
        	window.top.location.href = "<?php echo $redirect_url;?>"; 
        	</script>
        	<?php
						

        	
					}
					
	
					
					fclose($myfile);
					

				
        		
        		
        		
        		
        		
        		
        		
        		
        		
        		
        	}
        	
        	
        	
        	
        
     }
	
	

	
	public function success()
	{
		/*
		$myfile = fopen(logger_url_txt."Real_deposits_success.txt", "a") or die("Unable to open file!");
		
		$inputJSON = file_get_contents('php://input');
		$input= json_decode( $inputJSON, TRUE );
		
		fwrite($myfile, "============================================\n"."received values[".date('Y-m-d H:i:s')."]:".json_encode($input)."\n\n");
		
		fclose($myfile);
		
		*/
		
	//	$_SESSION['pop_mes'] = 'Your transaction is successful.';
		
		$success_url=base_url($this->uri->segment(1).'/dashboard');
		
		//redirect($success_url);
		?>
        			        		        	        	        					    				    			
        	<body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo $success_url; ?>'; }, 0000)">
        			        		        	        	        					    			
        	<?php 
        	

        }
        
        
        public function validate()
        {
        	$myfile = fopen(logger_url_txt."Real_deposits_validate.txt", "a") or die("Unable to open file!");

        	$inputJSON = file_get_contents('php://input');
        	$input= json_decode( $inputJSON, TRUE );
        	
        	fwrite($myfile, "============================================\n"."received values[".date('Y-m-d H:i:s')."]:".json_encode($input)."\n\n");
        	
        	
        	$transaction_type=$input['transaction_type'];
        	$transaction_status=$input['transaction_status'];
        	$transaction_id=$input['transaction_id'];
        	$trace_id=$input['trace_id'];
        	$order_id=$input['order_id'];
        //	$frontend=$input['frontend'];
        	$pin=$input['pin'];
        	$amount=$input['amount'];
        	$currency=$input['currency'];
        	$payment_method=$input['payment_method'];
        //	$created_by=$input['created_by'];
        	$auth_token=$input['auth_token'];
        	$merchant_id=$input['merchant_id'];
        	$version=$input['version'];

        	
        	$merchant_secret = REAL_DEPOSITS_MERCHANT_SECRET;
        	
        	
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
        	$myfile = fopen(logger_url_txt."Real_deposits_notify.txt", "a") or die("Unable to open file!");

        	$inputJSON = file_get_contents('php://input');
        	$input= json_decode( $inputJSON, TRUE );
        	
        	fwrite($myfile, "===============================================\n"."received values[".date('Y-m-d H:i:s')."]:".json_encode($input)."\n\n");
        
        	$transaction_type=$input['transaction_type'];
        	$transaction_status=$input['transaction_status'];
        	$transaction_id=$input['transaction_id'];
        	$trace_id=$input['trace_id'];
        	$order_id=$input['order_id'];
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
            $error_code=$input['error_code'];
        	$error_details=$input['error_details'];
        	$reference_id=$input['reference_id'];
        	$version=$input['version'];

        	
        	
        	
        	$merchant_secret = REAL_DEPOSITS_MERCHANT_SECRET;
        	
        	
        	$received_signature=$input['signature'];
        	
        	unset($input['signature']);
        	
        	ksort($input);
        	
        	$concatenated_string_res = implode('', $input) . $merchant_secret;
        	
        	$calculated_signature = hash('sha384', $concatenated_string_res);
        	
        	fwrite($myfile, "received_signature[".date('Y-m-d H:i:s')."]:".$received_signature."\n\n");
        	fwrite($myfile, "calculated_signature[".date('Y-m-d H:i:s')."]:".$calculated_signature."\n\n");
        	
        	
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
        				//$parentAccountId= $acc_t->parentAccountId;
        			}
        			
        			
        		}
        		
        		
        		if($transaction_type=='sale')
        		{
        			if($transaction_status=='approved')
        			{
        				
        				$get_records = $this->deposite_model->get_records($auth_token);
        				
        				$table_status = $get_records->status;
        				
        				if( $table_status != 'Success' )
        				{
        				
        					$update_success = $this->deposite_model->update_success_real_deposits($auth_token,$amount_cnvrtd);
        				
        				
        				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        				
        				getBUDetails($owningBusinessUnit);
        				
        				$request1 = new CrmMonetaryTransactionModelRequestModel();
        				$request1->organizationName =ORGANIZATION_NAME;
        				$request1->ownerUserId =$OWNER_USER_ID;
        				$request1->businessUnitName = $BUSINESS_UNIT_NAME;
        				$request1->amount =$amount_cnvrtd;
        				$request1->tradingPlatformAccountId=$trading_platform_accountid;
        				$request1->internalComment ="$payment_processor Deposit";
        				$request1->shouldAutoApprove = "TRUE";
        				$request1->filtertype =1;
        				$request1->cardExpirationMonth =$exp_month;
        				$request1->cardExpirationYear =$exp_year;
        				$request1->cardHolderName =$firstName." ".$lastName;
        				$request1->creditCardNumber =$card_number_last_4_digit;
        				
        				$request1->new_transactionid=$transaction_id;
        				
        				$request1->new_psp=$payment_processor;
        				
        				
        				
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
        				$request11->amount =$amount_cnvrtd;
        				$request11->email = $result123->accountsInfo[0]->email;
        				$request11->firstName =$firstName;
        				$request11->lastName =$lastName;
        				
        				$request11->title ="$payment_processor Deposit Success Case";
        				$request11->description = "Transaction Id -> ". $transaction_id."  TP Name -> ". $pin;//$trading_platform_accountid;
        				$request11->accountId=$result123->accountsInfo[0]->id;
        				$request11->lv_depositidentitynumber=$payment_processor;
        				
        				
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
        				
        				
        				fwrite($myfile,"case httpcode[".date('Y-m-d H:i:s')."]=".$http_code11."\n\n");
        				fwrite($myfile,"case req [".date('Y-m-d H:i:s')."] =".json_encode($request11)."\n\n");
        				fwrite($myfile,"case res [".date('Y-m-d H:i:s')."] =".json_encode($curl_result11)."\n\n");
        				
        				
        				
        				
        				
        				
        				
        				
        				
        				
        				
        			}
        			
        		   }
				   else
				   {
				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        				
        				getBUDetails($owningBusinessUnit);
		
		$request11 = new CrmCreateCaseModel();
		$request11->organizationName =ORGANIZATION_NAME;
		$request11->ownerUserId =$OWNER_USER_ID;
		$request11->businessUnitName = $BUSINESS_UNIT_NAME;
		$request11->amount =$amount_cnvrtd;
		$request11->email = $result123->accountsInfo[0]->email;
		$request11->firstName =$firstName;
		$request11->lastName =$lastName;
		
		$request11->title ="$payment_processor Deposit Failure Case";
		$request11->description = "Transaction Id -> ". $transaction_id."  TP Name -> ". $pin."  Reason -> ".$error_details;//$trading_platform_accountid;
		$request11->accountId=$result123->accountsInfo[0]->id;
		
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
		
		
		fwrite($myfile,"case httpcode[".date('Y-m-d H:i:s')."]=".$http_code11."\n\n");
		fwrite($myfile,"case req [".date('Y-m-d H:i:s')."] =".json_encode($request11)."\n\n");
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
        	
        	//exit;

        	
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
        				$_SESSION['pop_mes']= "This Facility is Available only for Real Account Users.";
        				popup();
        				
        				?>
        		        		        	        		        	        	        					    				    			
        		   <body onLoad="timer=setTimeout( function(){ top.window.location='<?php echo base_url($this->uri->segment(1).'/dashboard'); ?>'; }, 2000)">
        		        		        	        		        	        	        					    			
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
        		
        		    $data['gateway']='PayCent';
        		$this->load->view('templates/header', $data);
        		$this->load->view('templates/left-sidebar');
        		$this->load->view('traders_room/fund_account/realdeposits', $data);
        		$this->load->view('templates/footer');
        	}
        
        	}
        	
        		else
				{
					
					
					$myfile = fopen(logger_url_txt."Real_deposits.txt", "a") or die("Unable to open file!");
					
					
					$acc=$_POST['acc'];
					
					$result123=get_acc_details_forgot_password($acc);
					
					$dateOfBirth=$result123->accountsInfo[0]->dateOfBirth;
					
					$pieces = explode("-", $dateOfBirth);
					
					$year=$pieces[0];
					$month=$pieces[1];
					$day=$pieces[2];
					
					$day = substr($day, 0, 2);
					
					if(empty($day) || strlen($day) < 2)
					{
						$day='';
					}
					
					if(empty($month) || strlen($month) < 2)
					{
						$month='';
					}
					
					if(empty($year) || $year=="0001" || $year=="1900" || strlen($year) < 4)
					{
						$year='';
					}
					
					if( (!empty($day)) && (!empty($month)) && (!empty($year)) )
					{
						$dob=$month.'/'.$day.'/'.$year;
					}
					else
					{
						$dob='';
					}
					
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
						$full_phone="";
					}
					
					/*
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
					*/
					
					
				$address1 = $this->input->post('address');
				$city = $this->input->post('city');
				$state= $this->input->post('state');
				$zipCode= $this->input->post('zip');
				
					/*
					$zipCode=$result123->accountsInfo[0]->zipCode;
					
					if(empty($zipCode))
					{
						$zipCode="";
					}
					
					$state=$result123->accountsInfo[0]->state;
					
					if(empty($state))
					{
						$state="";
					}
					*/
					
					$ip = get_client_ip();  
					
					$transaction_id = $acc;
					$client_orderid = "ac" . $transaction_id . time ();
					
					/*
					$amount= $this->input->post ( 'amount' );
					
					$amtpos = strpos ( $amount, "." );
					
					if ($amtpos == false) {
						$amount .= ".00";
					}
					
					$amount_in_cent = $amount * 100;
					
					*/
					$card_exp_month = $this->input->post ( 'expiration_month' );
					$card_exp_year = $this->input->post ( 'expiration_year' );
					
					$card_exp = $card_exp_month.'/'. $card_exp_year;
					
					$card_number= $this->input->post ( 'ccnum' );
					$cvv= $this->input->post ( 'cvv' );
	
					
					$url=REAL_DEPOSITS_URL;
					
					$merchant_id = REAL_DEPOSITS_MERCHANT_ID;
					
					$merchant_secret = REAL_DEPOSITS_MERCHANT_SECRET;
					
					$application_key = REAL_DEPOSITS_APPLICATION_KEY;
					
					
					
		
					
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
					
					/*
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
					*/
					
					$return_url=base_url($this->uri->segment(1).'/cashier1/success');
					
					$notification_url=base_url('en/cashier1/notify');
					
					$validation_url=base_url('en/cashier1/validate');
					
					
					$request=array(
							"gateway"=>REAL_DEPOSITS_GATEWAY_PAYCENT,
							"address"=>$address1,
							"application_key"=>$application_key,
							"city"=>$city,
							"country"=>$country_iso2,
							"currency"=>$currency,
						//	"amount"=>$amount_in_cent,
							"dob"=>$dob,
							"email"=>$email,
							"first_name"=>$firstName,
							"last_name"=>$lastName,
							"locale"=> "en-GB",
							"merchant_id"=>$merchant_id,
							"notification_url"=>$notification_url,
						//	"payment_method"=> null,
							"payment_method"=> "Real Deposits Payment",
							"phone"=>$full_phone,
							"pin"=>$acc,
							"requester_ip"=>$ip,
							"return_url"=>$return_url,
							"timestamp"=>time(),
							"validation_url"=>$validation_url,
							"version"=>"1.2",
							"zip"=>$zipCode,
							"signature"=>$signature,
							
							"state"=>$state,
						//	"order_id"=>$client_orderid,
							"variable1"=>$client_orderid,
							
							/*
							
							"card_exp"=>$card_exp,
							"card_number"=>$card_number,
							"cvv"=>$cvv,
							"gateway"=>null,
						    "transaction_type"=>"sale",
							*/
							
					);
					
					
					ksort($request);
					
					$concatenated_string = implode('', $request) . $merchant_secret;
					
					$signature = hash('sha384', $concatenated_string);
					
					$request['signature']=$signature;
				
					fwrite($myfile, "===============================================\n"."signature[".date('Y-m-d H:i:s')."]:".$signature."\n\n");
					
					// print"<pre>"; print_r($request); print"</pre>";  exit();
					
					$request_json = json_encode($request);
					
					fwrite($myfile, "Real_deposits_Request[".date('Y-m-d H:i:s')."]:".$request_json."\n\n");
					
					
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
					curl_setopt($ch, CURLOPT_POST, TRUE);
					curl_setopt($ch, CURLOPT_POSTFIELDS,$request_json);
					curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							"Content-Type: application/json"
					));
					$curl_result = curl_exec($ch);
					$curl_error = curl_error($ch);
					$curl_info = curl_getinfo($ch);
					curl_close($ch);
					
					// print"<pre>"; print_r($curl_result); print"</pre>";  exit();
					
				
					
					fwrite($myfile, "Real_deposits_Response[".date('Y-m-d H:i:s')."]:".json_encode($curl_result)."\n\n");
					fwrite($myfile, "info[".date('Y-m-d H:i:s')."]:".json_encode($curl_info)."\n\n");
					fwrite($myfile, "err[".date('Y-m-d H:i:s')."]:".json_encode($curl_error)."\n\n");
					
					$curl_result_jd=json_decode($curl_result,true);
					
					if($curl_result_jd['status']==0)
				{
						
					$add_payment_details = $this->deposite_model->add_payment_details_real_deposits($firstName,$lastName,$acc,$curl_result_jd['auth_token'],$currency,$email,$full_phone,$country_iso2,date('Y-m-d H:i:s'));
					
					//	$data['redirect_url'] = $curl_result_jd['redirect_url'];
						$data['title'] = 'Deposit';
						$this->load->helper('form');
						$this->load->library('form_validation');
						$data['language'] = $this->uri->segment(1);
						
						
					//	$this->load->view('traders_room/fund_account/real_deposits_iframe_view', $data);
						$data['gateway']='PayCent';
						$data ['redirect_url'] = $curl_result_jd['redirect_url'];
						$this->load->view ( 'templates/header', $data );
						$this->load->view ( 'templates/left-sidebar' );
						$this->load->view ( 'traders_room/fund_account/real_deposits_iframe_view', $data );
						$this->load->view ( 'templates/footer' );
						
						
						
						
						 
						
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