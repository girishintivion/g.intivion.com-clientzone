<?php
class Monetary_statement extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

				$this->load->helper('url_helper');
				$this->load->model('Common_model');
                //$this->load->model('monetary_statement_model');
                //$this->load->model('dashboard_model');
                $this->load->helper(array('url'));
                $this->load->helper('prodconfig');

                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
     
        }

        public function withdrawal_status()
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
        		$accountid = $this->Common_model->get_current_user_acc_id($_SESSION['username'] );
        	}
        	catch(Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"MONETARY STATEMENT DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('error',$log);
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
        	
        	$method = "Monetory Statement";
			$crmurl = api_url."/GetMonetaryStatement";
			$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
											
			$json_result = $update_mcrm['json_result'];
			$http_code = $update_mcrm['http_code'];
							
			$main_transaction_id = main_transaction_id();
        	
        	if($http_code == "400" || $http_code == "404")
        	{
        		$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
        		redirect($this->uri->segment(1)."/monetary-statement");
        		
        	}
        	
        	
        	if($http_code == "201" || $http_code == "200") //if operation successful
        	{
        		$WS  = new stdClass();
        		
        		return	$WS = $json_result->mtAccounts;
        		//   		$MP  = new stdClass();
        		
        		//   		$MP = $json_result->mtAccounts;
        		
        		//  		$data['MP']= $WS;
        		
        		//print_r($data);
        		
        		// 		$this->load->view('traders_room/withdrawal_request/withdrawal_status', $WS);
        		
        	}
        	
        	
        	
        	
        	
        }
        
        public function index()
        {
        	
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	}
        	
        	
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	
        	
        	try
        	{
        		
        		
        		try{
        			$accountid = $this->Common_model->get_current_user_acc_id($_SESSION['username'] );
        		}
        		catch(Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        			"MONETARY STATEMENT DB EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        		}
        		
        		
        		
        		global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        		getBUDetails($_SESSION['BUSINESS_UNIT']);
        		
        		//Get Monetary Statement Request parameters
        		$request = new CrmGetMonetaryStatmentModel();
        		$request->ownerUserId = $OWNER_USER_ID;
        		$request->businessUnitName = $BUSINESS_UNIT_NAME;
        		$request->organizationName = ORGANIZATION_NAME;
        		$request->accountId = $accountid;
        		
        		$method = "Monetory Statement";
				$crmurl = api_url."/GetMonetaryStatement";
				$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
											
				$json_result = $update_mcrm['json_result'];
				$http_code = $update_mcrm['http_code'];
							
				$main_transaction_id = main_transaction_id();

        		if($http_code == "400" || $http_code == "404")
        		{
        			$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;        			
        			redirect ( $this->uri->segment ( 1 ) . '/monetary-statement');
        			
        		}
        		if($http_code == "201" || $http_code == "200") //if operation successful
        		{
        			
        			$MP  = new stdClass();
        			
        			$data['MP'] = $json_result->mtAccounts;
        			
        			$data['WS'] = $this->withdrawal_status();
        			//	$MP = $json_result->mtAccounts;
        			
        			$data['title'] = 'Monetary Statement';
        				$this->load->view('templates/header', $data);
        				$this->load->view('templates/left-sidebar');
        			$this->load->view('traders_room/monetary_statement/monetary_stmt', $data);
        					$this->load->view('templates/footer');
        			
        			
        		}
        		
        		
        	}
        	catch (Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        		"MONETARY STATEMENT EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('error',$log);
        		redirect($this->uri->segment(1)."/errors");
        	}
        	
        	
        	
        }
        
        public function geraTimestamp($data)
        {
        	$partes = explode('/', $data);
        	return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
        }
        
        public function test()
        {
        	$user_email = $this->Common_model->get_current_user_email($_SESSION['username'] );
        	$res = $this->Common_model->get_current_user_email_data($user_email);
        	
        	//	print"<pre>";print_r($res);print"</pre>";
        	
        	$accountid = $this->Common_model->get_current_user_acc_id($_SESSION['username'] );
        	
        	
        	
        	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        	getBUDetails($_SESSION['BUSINESS_UNIT']);
        	
        	//Get Monetary Statement Request parameters
        	$request = new CrmGetMonetaryStatmentModel();
        	$request->ownerUserId = $OWNER_USER_ID;
        	$request->businessUnitName = $BUSINESS_UNIT_NAME;
        	$request->organizationName = ORGANIZATION_NAME;
        	$request->accountId = $accountid;
        	$request->transactionType= '2';
        
			$method = "Monetory Statement";
			$crmurl = api_url."/GetMonetaryStatement";
			$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
									
			$json_result = $update_mcrm['json_result'];
			$http_code = $update_mcrm['http_code'];
			
        	$MP = $json_result->mtAccounts;
        	
        	foreach ($MP as $ro)
        	{
        		if ( $ro->type == 'Withdrawal' && $ro->approved=='1') //&& $ro->approved=='1' added on 28OCt2019
        		{
        			$array_w[]=array(
        					'amount'=>$ro->amount,
        					'creationTime'=>date("d/m/Y", strtotime($ro->creationTime)),//$ro->creationTime,
        					'tpName'=>$ro->tpName,
        					
        			);
        		}
        		
        		if ( $ro->type == 'Deposit')
        		{
        			$array_d[]=array(
        					'amount'=>$ro->amount,
        					'creationTime'=>date("d/m/Y", strtotime($ro->creationTime)),//$ro->creationTime,
        					'tpName'=>$ro->tpName,
        					
        			);
        		}
        	}
        	
        	//   	print"<pre>";	print_r($array_d);print"</pre>";
        	for ($i = -1; $i <= 6; $i++)
        	{
        		$months[] = date("01/m/Y", strtotime( date( 'Y-m-01' )." -$i months"));
        	}
        	/*
        	 print "next month:".$m0 =$months[0]."<br>";
        	 print "current month:".$m1 =$months[1]."<br>";
        	 print "last 1 month:".$m2 =$months[2]."<br>";
        	 print "last 2 month:".$m3 =$months[3]."<br>";
        	 print "last 3 month:".$m4 =$months[4]."<br>";
        	 print "last 4 month:".$m5 =$months[5]."<br>";
        	 print "last 5 month:".$m6 =$months[6]."<br>";
        	 */
        	
        	if($array_d)
        	{
        		$sizeofd = sizeof($array_d);
        	}
        	else
        	{
        		$sizeofd ='0';
        	}
        	//$date >= $months[1] && $date < $months[0]
        	
        	for ($i = 0; $i < $sizeofd; $i++)
        	{
        		if (($this->geraTimestamp($array_d[$i]['creationTime']) >= $this->geraTimestamp($months[1])) && ($this->geraTimestamp($array_d[$i]['creationTime'])< $this->geraTimestamp($months[0])))
        		{
        			$d_amount[0] +=$array_d[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_d[$i]['creationTime'])>= $this->geraTimestamp($months[2])) && ($this->geraTimestamp($array_d[$i]['creationTime'])< $this->geraTimestamp($months[1])))
        		{
        			$d_amount[1] +=$array_d[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_d[$i]['creationTime'])>= $this->geraTimestamp($months[3])) && ($this->geraTimestamp($array_d[$i]['creationTime'])< $this->geraTimestamp($months[2])))
        		{
        			$d_amount[2] +=$array_d[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_d[$i]['creationTime'])>= $this->geraTimestamp($months[4])) && ($this->geraTimestamp($array_d[$i]['creationTime'])< $this->geraTimestamp($months[3])))
        		{
        			$d_amount[3] +=$array_d[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_d[$i]['creationTime'])>= $this->geraTimestamp($months[5])) && ($this->geraTimestamp($array_d[$i]['creationTime'])< $this->geraTimestamp($months[4])))
        		{
        			$d_amount[4] +=$array_d[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_d[$i]['creationTime'])>= $this->geraTimestamp($months[6])) && ($this->geraTimestamp($array_d[$i]['creationTime'])< $this->geraTimestamp($months[5])))
        		{
        			$d_amount[5] +=$array_d[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_d[$i]['creationTime'])>= $this->geraTimestamp($months[7])) && ($this->geraTimestamp($array_d[$i]['creationTime'])< $this->geraTimestamp($months[6])))
        		{
        			$d_amount[6] +=$array_d[$i]['amount'];
        		}
        	}
        	
        	if($array_w)
        	{
        		$sizeofw = sizeof($array_w);
        	}
        	else
        	{
        		$sizeofw ='0';
        	}
        	//$date >= $months[1] && $date < $months[0]
        	
        	for ($i = 0; $i < $sizeofw; $i++)
        	{
        		if (($this->geraTimestamp($array_w[$i]['creationTime']) >= $this->geraTimestamp($months[1])) && ($this->geraTimestamp($array_w[$i]['creationTime'])< $this->geraTimestamp($months[0])))
        		{
        			$d_amount[0] +=$array_w[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_w[$i]['creationTime'])>= $this->geraTimestamp($months[2])) && ($this->geraTimestamp($array_w[$i]['creationTime'])< $this->geraTimestamp($months[1])))
        		{
        			$d_amount[1] +=$array_w[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_w[$i]['creationTime'])>= $this->geraTimestamp($months[3])) && ($this->geraTimestamp($array_w[$i]['creationTime'])< $this->geraTimestamp($months[2])))
        		{
        			$d_amount[2] +=$array_w[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_w[$i]['creationTime'])>= $this->geraTimestamp($months[4])) && ($this->geraTimestamp($array_w[$i]['creationTime'])< $this->geraTimestamp($months[3])))
        		{
        			$d_amount[3] +=$array_w[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_w[$i]['creationTime'])>= $this->geraTimestamp($months[5])) && ($this->geraTimestamp($array_w[$i]['creationTime'])< $this->geraTimestamp($months[4])))
        		{
        			$d_amount[4] +=$array_w[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_w[$i]['creationTime'])>= $this->geraTimestamp($months[6])) && ($this->geraTimestamp($array_w[$i]['creationTime'])< $this->geraTimestamp($months[5])))
        		{
        			$d_amount[5] +=$array_w[$i]['amount'];
        		}
        		
        		if (($this->geraTimestamp($array_w[$i]['creationTime'])>= $this->geraTimestamp($months[7])) && ($this->geraTimestamp($array_w[$i]['creationTime'])< $this->geraTimestamp($months[6])))
        		{
        			$d_amount[6] +=$array_w[$i]['amount'];
        		}
        	}
        	
        	for ($i = 0; $i <= 5; $i++)
        	{
        		if($d_amount[$i] == null){$d_amount[$i]='0';}
        		if($w_amount[$i] == null){$w_amount[$i]='0';}
        		$new_data[]=array(
        				//		'month'=>$months[$i],
        				'deposit'=>$d_amount[$i],
        				'withdraw'=>$w_amount[$i],
        				
        		);
        	}
        	
        	
        	print_r(json_encode($new_data, true));
        }
        
        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
}