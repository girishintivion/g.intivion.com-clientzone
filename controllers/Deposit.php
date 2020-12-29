<?php
class Deposit extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

                $this->load->helper('url_helper');
                $this->load->model('user_model');
				$this->load->model('real_model');
                $this->load->model('withdrawal_request_model');
                $this->load->helper(array('url'));
                $this->load->helper('prodconfig');

                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
     
        }
        
		public function test()
		{
			
			//redirect ( $this->uri->segment ( 1 ) . '/dashboard');
			
			
			$data = array();
			$data['title'] = 'Deposit Options';
			

				
				$this->load->view('templates/header' ,$data);
				$this->load->view('templates/left-sidebar');
				$this->load->view('traders_room/fund_account/options');
				$this->load->view('templates/footer');
		
			
			
		}
        
        
        public function options()
        {
        //redirect ( $this->uri->segment ( 1 ) . '/dashboard');
        				
        
        	$data = array();
        	$data['title'] = 'Deposit Options';
      
		  $result_crm = get_all_acc_details();
		  $new_addpsp = $result_crm->getAllAccountDetails[0]->dynamicAttributeValue;
		  $lang_smallcase = strtolower($new_addpsp);
		  if ($lang_smallcase =="true" || $lang_smallcase=="yes")
		  {
		
        	$this->load->view('templates/header' ,$data);
        	$this->load->view('templates/left-sidebar');
        	$this->load->view('traders_room/fund_account/options');
        	$this->load->view('templates/footer');
		 }
		  else
		  {
		  redirect ( $this->uri->segment ( 1 ) . '/dashboard');
		  }
      
        }
        
  public function options2()
        {
        
        
        	$data = array();
        	$data['title'] = 'Deposit Options';
        
        	$this->load->view('templates/header' ,$data);
        	$this->load->view('templates/left-sidebar');
        	$this->load->view('traders_room/fund_account/options2');
        	$this->load->view('templates/footer');
        
        
        }
        

        public function checkpsp() {
        	
        	//$my_email='aa@bb.cc';
        	//$my_phone='+23658741';
        	
        	//echo $my_phone_encriptd=my_simple_crypt($my_phone,'e');
        	
        	//echo '<br>'.$my_phone_decriptd=my_simple_crypt($my_phone_encriptd,'d'); 
        	echo 'hiiiii';
        	$this->db->select('*');
        	$this->db->from('crm_user');
        	$this->db->where('name', '1310356156');
        	$query = $this->db->get();
        	$result = $query->result();
        	
        	print"<pre>"; print_r($result); print"</pre>";
        	
        }

        

        
        public function deposit_redirect()
        {

        	
        	$myfile = fopen(logger_url_txt."dreamspay_redirect.txt", "a") or die("Unable to open file!");

        	fwrite($myfile, "request".json_encode($_REQUEST)."\n\n");

        	fclose($myfile);
        	
        	$replyCode=$_REQUEST['order_status'];
        	
        	if (strtolower($replyCode) == strtolower("approved")) {
        		
        		$data = array();
        		$data['title'] = 'Deposit Success';
        		 
        		$this->load->view('templates/header' ,$data);
        		$this->load->view('templates/sidebar');
        		$this->load->view('traders_room/fund_account/cashier1_success');
        		$this->load->view('templates/footer');
        		
        	}
        	
        	else{
        		
        		$data = array();
        		$data['title'] = 'Deposit failure';
        		
        		$this->load->view('templates/header' ,$data);
        		$this->load->view('templates/sidebar');
        		$this->load->view('traders_room/fund_account/cashier1_failure');
        		$this->load->view('templates/footer');
        		
        	}
        
        }
        
        public function deposit_dmn()
        {
        	$jsonText = file_get_contents('php://input');
        	$decodedText = html_entity_decode($jsonText);
        	
        	$myArray = json_decode($decodedText, true);
        	
        	$myfile = fopen(logger_url_txt."dreamspay_dmn.txt", "a") or die("Unable to open file!");

        	fwrite($myfile, "DMN".$decodedText."\n\n");
        	
        	$order_status=$myArray['order_status'];
        	
        	$ourTransactionID=$myArray['order_id'];
        	
        	if($order_status=="approved")
        	{
        		
        		$result_data = $this->netpay_deposit_model->get_details_from_payment_status($ourTransactionID);
        		 
        		$table_status = $result_data->status;
        		
        		if($table_status!="success")
        		{
        		
        		
        	
        	$masked_card=$myArray['masked_card'];
        	
        	$amount=$myArray['amount'];
        	
        	$amount=$amount/100;
        	
        	$card_num = substr($masked_card,-4);

        		$psp="dreamspay";
        		
        		//$user=$this->netpay_deposit_model->add_dmn_in_notifications($psp,$decodedText,$ourTransactionID);


        			$status="success";
        			$user=$this->netpay_deposit_model->update_payment_status($status,$ourTransactionID);
        			
        			//$result_data = $this->netpay_deposit_model->get_details_from_payment_status($ourTransactionID);
        			
        			$tp_account_id = $result_data->tp_account_id;
        			
        			
        			$result_data1 = $this->netpay_deposit_model->get_details_from_crm_user($tp_account_id);
        			$trading_platform_accountid = $result_data1->trading_platform_accountid;
        			$firstname= $result_data1->firstname;
        			$lastname= $result_data1->lastname;
        			$Name = $firstname.' '.$lastname;

        			
        			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME, $CRM_LOGIN, $CRM_PASSWORD, $DEMO_PLATFORM_ID, $REAL_PLATFORM_ID;
        			global $DEMO_PLATFORM_GROUP, $REAL_PLATFORM_GROUP;
        			
        			
        			$owning_bu=get_owning_bu($tp_account_id);
        			
        			fwrite($myfile,"owning_bu[".date('Y-m-d H:i:s')."] = ".$owning_bu."\n\n");
        			
        			getBUDetails($owning_bu);


        			$request1 = new CrmMonetaryTransactionModelRequestModel();
        			$request1->organizationName =ORGANIZATION_NAME;
        			$request1->ownerUserId =$OWNER_USER_ID;
        			$request1->businessUnitName = $BUSINESS_UNIT_NAME;
        			$request1->amount =$amount;
        			$request1->tradingPlatformAccountId=$trading_platform_accountid;
        			$request1->internalComment ="Dreamspay Deposit";
        			$request1->shouldAutoApprove = "TRUE";
        			$request1->filtertype =1;
        			$request1->cardExpirationMonth ="";
        			$request1->cardExpirationYear ="";
        			$request1->cardHolderName ="";
        			$request1->creditCardNumber =$card_num;
        			
        		fwrite($myfile,"monetory_req[".date('Y-m-d H:i:s')."] = ".json_encode($request1)."\n\n");
        		
        		
        		$url = api_url."/CreateMonetaryTransaction";
        		$ch = curl_init ();
        		curl_setopt ( $ch, CURLOPT_URL, $url );
        		curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
        		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        		curl_setopt ( $ch, CURLOPT_POST, TRUE );
        		curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request1));
        		$curl_result = curl_exec ( $ch ); //getting response
        		$curl_info = curl_getinfo ( $ch );
        		$curl_error = curl_error ( $ch ); // Collect errors
        		curl_close ( $ch );

        		fwrite($myfile,"curl monetory_res[".date('Y-m-d H:i:s')."] = ".json_encode($curl_result)."\n\n");
        		fwrite($myfile,"curl monetory_info[".date('Y-m-d H:i:s')."] = ".json_encode($curl_info)."\n\n");
        		fwrite($myfile,"curl monetory_err[".date('Y-m-d H:i:s')."] = ".json_encode($curl_error)."\n\n");
        		
        		
        		
        		
        		
        		
        	}
        }
        else
        {
        	$status="failure";
        	$user=$this->netpay_deposit_model->update_payment_status($status,$ourTransactionID);
        }
        			
        		
        		
        		
        		fclose($myfile);

        	//header("HTTP/1.1 200 OK");
        		
        }
        


        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
        
        public function index()
        {
        	
        	
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	}
        	
        	$data['title'] = 'Deposit';
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	$data['language'] = $this->uri->segment(1);
        	
        	try{
        		$user_email = $this->withdrawal_request_model->get_current_user_email($_SESSION['username'] );
        		$data   = array();
        		$data['result'] = $this->withdrawal_request_model->get_current_user_data($user_email);
        		
        	}
        	catch(Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"WITHDRAWAL DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL;
        		file_put_contents(logger_url, $log. "\n", FILE_APPEND);
        	}
        	
           	$this->load->view('templates/header' ,$data);
        	$this->load->view('templates/left-sidebar');
        	$this->load->view('traders_room/fund_account/deposit', $data);
        	$this->load->view('templates/footer');
        	
        	
        }
               
}
function signature($data, $merchant_password)
{
	ksort($data);
	$string = join('|', $data);
	return sha1($merchant_password.'|'.$string);
}