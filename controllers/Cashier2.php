<?php
class Cashier2 extends CI_Controller 
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
	
	
	public function redirect()
	{

		$redirect_url=base_url($this->uri->segment(1).'/cashier2/');
		
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
	
        

        
        public function index()
        {
        	
        	
        	/*
        	$ip = get_client_ip();
        	
        	if($ip!='136.244.67.225' && $ip!='122.170.10.24' && $ip !='95.179.224.11')
        	{
        		
        		
        		$_SESSION['pop_mes'] = "You Don't Have Access To This Page";
        		
        		redirect(base_url($this->uri->segment(1).'/cashier2/access_denied'));
        		
        		exit;
        		
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
        		$this->load->view('templates/before-login-header', $data );
        		$this->load->view('traders_room/fund_account/realdeposits_direct_new');
        		$this->load->view('templates/before-login-footer');
        	}
        	
        	else
        	{
        		
        		$token = $this->input->post('my_token_cashier2');
        		
        		$session_token = $_SESSION['form_token_cashier2'];
        		
        		unset($_SESSION['form_token_cashier2']);
        		
        		if( (empty($token)) || (empty($session_token)) || ($token != $session_token) )
        		{
        			
        			$_SESSION['pop_mes'] = 'Invalid Session';
        			
        			redirect(base_url($this->uri->segment(1).'/cashier2/'));
        			
        			exit;
        		}
        		

        		
        		
        		
        		$myfile = fopen(logger_url_txt."Real_deposits.txt", "a") or die("Unable to open file!");
        		
        		
        		$acc=$_POST['acc'];
        		
        		$result123=get_acc_details_forgot_password($acc);
        		
        		$code=$result123->result->code;
        		
        		if($code=='2')
        		{
        			
        			
        			$_SESSION['pop_mes'] = 'This account does not exists';
        			
        			redirect(base_url($this->uri->segment(1).'/cashier2/'));
        			
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
        		
        		$return_url=base_url($this->uri->segment(1).'/cashier2/redirect');
        		
        		$notification_url=base_url('en/cashier1/notify');
        		
        		$validation_url=base_url('en/cashier1/validate');
        		
        		$address1 = $this->input->post('address');
        		$city = $this->input->post('city');
        		
        		
        		$request=array(
        				
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
        			
        			$data ['redirect_url'] = $curl_result_jd['redirect_url'];

        			
        			$this->load->view('templates/before-login-header', $data );
        			$this->load->view('traders_room/fund_account/realdeposits_direct_new');
        			$this->load->view('templates/before-login-footer');
        			
        			
        			
        			
        		}
        		else
        		{
        			$_SESSION['pop_mes']=$curl_result_jd['description'];
        			
        			$redirect_url=base_url($this->uri->segment(1).'/cashier2/');
        			
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