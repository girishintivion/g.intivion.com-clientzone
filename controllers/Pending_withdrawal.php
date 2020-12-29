<?php
class Pending_withdrawal extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

                $this->load->helper('url_helper');
                $this->load->model('monetary_statement_model');
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
        	
        	$data['title'] = 'Withdrawal Status';
        	
        	
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	$data['language'] = $this->uri->segment(1);
        	
        	$data['language'] = $this->uri->segment(1);
        	
        	try{
        		$accountid = $this->monetary_statement_model->get_current_user_email($_SESSION['username'] );
        	}
        	catch(Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"MONETARY STATEMENT DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL;
        		file_put_contents(logger_url, $log. "\n", FILE_APPEND);
        	}
        	
        	
        	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        	getBUDetails($_SESSION['BUSINESS_UNIT']);
        	
        	//Get Monetary Statement Request parameters
        	$request = new CrmGetMonetaryStatmentModel();
        	$request->ownerUserId = $OWNER_USER_ID;
        	$request->businessUnitName = $BUSINESS_UNIT_NAME;
        	$request->organizationName = ORGANIZATION_NAME;
        	$request->accountId = $accountid;
        	$request->transactionType= '2';
        	
        	
        	$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
        	"MONETARY STATEMENT REQUEST: ".json_encode($request).PHP_EOL
        	."-------------------------".PHP_EOL;
        	file_put_contents(logger_url, $log. "\n", FILE_APPEND);
        	
        	//	print"<pre>"; print_r($request); print"</pre>";
        	
        	$url = api_url."/GetMonetaryStatement";
        	$ch = curl_init ();
        	curl_setopt ( $ch, CURLOPT_URL, $url );
        	curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
        	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
        	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        	curl_setopt ( $ch, CURLOPT_POST, TRUE );
        	curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request));
        	$curl_result = curl_exec ( $ch ); //getting response
        	$curl_info = curl_getinfo ( $ch );
        	curl_close ( $ch );
        	
        	$json_result = json_decode ($curl_result); //decode response in neat format
        	
        	
        	//	print"<pre>"; print_r($json_result); print"</pre>";
        	//	exit();
        	$http_code = $curl_info['http_code'];
        	
        	$rand_number = rand (100000,999999);
        	$date2 = new DateTime ();
        	$unique = uniqid();
        	$trans_refNum = $rand_number . $date2->getTimestamp();
        	$main_transaction_id = $trans_refNum."-".$rand_number."-".$unique;
        	
        	//	echo $http_code;
        	$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
        	"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
        	"HTTP CODE:".$http_code."MONETARY STATEMENT RESPONSE: ".json_encode($json_result).PHP_EOL
        	."-------------------------".PHP_EOL;
        	file_put_contents(logger_url, $log. "\n", FILE_APPEND);
        	
        	if($http_code == "400" || $http_code == "404")
        	{
        		$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
        		redirect($this->uri->segment(1)."/pending-withdrawal");
        		
        	}
        	
        	
        	if($http_code == "201" || $http_code == "200") //if operation successful
        	{
        		$WS  = new stdClass();
        		
        	//	return	$WS = $json_result->mtAccounts;
        		$data['WS'] = $json_result->mtAccounts;
        		$this->load->view('templates/header', $data);
        		$this->load->view('templates/left-sidebar');
        		$this->load->view('traders_room/monetary_statement/withdrawal', $data);
        		$this->load->view('templates/footer');
        	}
        	
        	
        	
        	
        	
        }

        
        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
}