<?php
class Trading_history extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

				$this->load->helper('url_helper');
				$this->load->model('Common_model');
                $this->load->model('trading_history_model');
                $this->load->helper(array('url'));
                $this->load->helper('prodconfig');

                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
     
        }

        public function update()
        {
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	}
        	/*
        	 $this->load->helper('form');
        	 $this->load->library('form_validation');
        	 
        	 $this->form_validation->set_rules('fromDate','lang:fromDate','trim|required');
        	 $this->form_validation->set_rules('toDate','lang:toDate','trim|required');
        	 $this->form_validation->set_rules('maxRows','lang:maxRows','trim|required');
        	 
        	 if($this->form_validation->run() === FALSE)
        	 {
        	 redirect($this->uri->segment(1).'/login');
        	 }
        	 */
        	try
        	{
        	
        		
        		try{
        			$acc = $this->trading_history_model->get_current_user_acc($_SESSION['username'] );
        		}
        		catch(Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        			"TRADING HISTORY DB EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        		}
        		
        		$tHistory = array ();
        		
        		$current_date = gmDate ( "Y-m-d\TH:i:s\Z" );
        		
        		if(!empty($_GET['maxRows']))
        		{
        			$maxRows=$_GET['maxRows'];
        		}
        	/*	else
        		{
        			$maxRows=5;//100; //As Discussed
        		}
        		*/
        		
        		if(!empty($_GET['from_date']))
        		{
        			$from_date=$_GET['from_date'];
        			
        			$fromdate1 = date_format(date_create_from_format('d/m/Y', $from_date), 'Y-m-d');//date ( 'Y-m-d\TH:i:s\Z', strtotime($from_date) );
        			
        		}
        /*		else
        		{
        			
        			$sevenday = strtotime ( '-1 year', strtotime ( $current_date ) );
        			$fromdate = date ( 'Y-m-d\TH:i:s\Z', $sevenday );
        			$fromdate1 = date ( 'Y-m-d', $sevenday );
        		}
        */		
        		
        		if(!empty($_GET['to_date']))
        		{
        			$to_date=$_GET['to_date'];
        			
        			$enddate1 = date_format(date_create_from_format('d/m/Y', $to_date), 'Y-m-d');//date ( 'Y-m-d\TH:i:s\Z', strtotime($to_date) ); 
        			
        		}
        /*		else
        		{
        			
        			
        			$enddate = $current_date;
        			$enddate1 = date ( 'Y-m-d');
        		}
        */		
        		
        		global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        		getBUDetails($_SESSION['BUSINESS_UNIT']);
        		
        		$request = new CrmGetTradingHistoryModel ();
        		$request->ownerUserId = $OWNER_USER_ID;
        		$request->businessUnitName = $BUSINESS_UNIT_NAME;
        		$request->organizationName = ORGANIZATION_NAME;
        		$request->tradingPlatformAccountId = $acc;
        		$request->maxRows = $maxRows;
        		$request->startTime = $fromdate1;
        		$request->endTime = $enddate1;
        	
        		$method = "Trading History";
				$crmurl = api_url."/GetTradingHistoryUpdated";
				$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
												
				$json_result = $update_mcrm['json_result'];
				$http_code = $update_mcrm['http_code'];

				$th1 = $json_result->thAccounts;	
				$main_transaction_id = main_transaction_id();

        		if($http_code == "400" || $http_code == "404")
        		{
        			$_SESSION['pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
        			//	redirect($this->uri->segment(1)."/errors");
        		}
        		
        		if($http_code == "201" || $http_code == "200") //if operation successful
        		{
        			/*
        			foreach ( $my_array_data as $key ) {
        				
        				
        				$closedPosition = $key;
        				$t = new Thistory2 ();
        				$t->ActionType = $closedPosition['ActionType'];
        				$t->Amount = $closedPosition['Amount'];
        				$t->CloseRate = $closedPosition['CloseRate'];
        				//$date_time = substr ( $closedPosition['CloseTime'], 6, 13 );
        				
        				$cdate = substr ( $closedPosition['CloseTime'], 6, 10 );
        				$ctime = substr ( $closedPosition['CloseTime'], 17, 2 );
        				
        				$t->CloseTime = $cdate . ' @ ' . $ctime;
        				//	$t->CloseTime = $date_time;
        				$t->InstrumentName = $closedPosition['InstrumentName'];
        				$t->ProfitInAccountCurrency = $closedPosition['ProfitInAccountCurrency'];
        				
        				$tHistory [] = $t;
        			}  
        			*/
        			echo "<table class='table'>
                        <thead>
                          <tr>                        
                            <th>".lang("Amount")."</th>
                            <th>".lang("Close Rate")."</th>
                            <th>".lang("Close Time")."</th>
                            <th>Symbol</th>
                            <th>Profit</th>
                          </tr>
                        </thead>
                        <tbody>";
        			
        			if (empty($th1)) {
        				
        				echo "<tr><td colspan='6'>No trading history records found.</tr></td>";
        			}
        			foreach ( $th1 as $trade_history ){
        				
        				echo "<tr>";         
        				echo "<td>";
        				if($trade_history->actionType=="0")
        				{
        					echo"<i class='fa fa-arrow-up' aria-hidden='true'></i>";
        				}
        				if($trade_history->actionType =="1")
        				{
        					echo"<i class='fa fa-arrow-down' aria-hidden='true'></i>";
        				};echo " ".$trade_history->amount."</td>";
        				echo "<td>".$trade_history->closeRate."</td>";
        				echo "<td>".$trade_history->closeTime."</td>";
        				echo "<td>".$trade_history->instrumentName."</td>";
        				echo "<td>".$trade_history->profitInAccountCurrency."</td>";
        				echo "</tr>";
        			}
        			
        			echo "</tbody>
                      </table>";
        		}
        		
        		
        	}
        	catch (Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        		"TRADING HISTORY EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('error',$log);
        		//	redirect('error?error='.$e);
        		redirect($this->uri->segment(1)."/errors");
        		
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
        			$acc = $this->trading_history_model->get_current_user_acc($_SESSION['username'] );
        		}
        		catch(Exception $e)
        		{
        			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        			"TRADING HISTORY DB EXCEPTION: ".json_encode($e).PHP_EOL
        			."-------------------------".PHP_EOL."\n";
        			log_message('error',$log);
        		}
        		
        		$tHistory = array ();
        		
        		$current_date = gmDate ( "Y-m-d\TH:i:s\Z" );
        		
        		if(!empty($_POST['num_trans']))
        		{
        			$maxRows=$_POST['num_trans'];
        			
        			$token = $this->input->post('form_token_history');
        			
        			$session_token=null;
        			
        			$session_token = $_SESSION['form_token_history'];
        			unset($_SESSION['form_token_history']);
        			if(!empty($token) != $session_token)
        			{
        				redirect($this->uri->segment(1)."/trading-history");
        			}
        			
        		}
        		else
        		{
        			$maxRows=10;
        		}
        		
        		if(!empty($_POST['from_date']))
        		{
        			$from_date=$_POST['from_date'];
        			
        			$fromdate1 = date_format(date_create_from_format('d/m/Y', $from_date), 'Y-m-d');//date ( 'Y-m-d\TH:i:s\Z', strtotime($from_date) );
        			
        		}
        		else
        		{
        			
        			$sevenday = strtotime ( '-1 year', strtotime ( $current_date ) );
        			$fromdate = date ( 'Y-m-d\TH:i:s\Z', $sevenday );
        			$fromdate1 = date ( 'Y-m-d', $sevenday );
        		}
        		
        		
        		if(!empty($_POST['to_date']))
        		{
        			$to_date=$_POST['to_date'];
        			
        			$enddate1 = date_format(date_create_from_format('d/m/Y', $to_date), 'Y-m-d');//date ( 'Y-m-d\TH:i:s\Z', strtotime($to_date) );
        			
        		}
        		else
        		{
        			
        			
        			$enddate = $current_date;
        			$enddate1 = date("Y-m-d", strtotime("+ 1 day"));//tomorrow //date ( 'Y-m-d'); //current day
        		}
        		
        		
        		global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
        		getBUDetails($_SESSION['BUSINESS_UNIT']);
        		
        		$request = new CrmGetTradingHistoryModel ();
        		$request->ownerUserId = $OWNER_USER_ID;
        		$request->businessUnitName = $BUSINESS_UNIT_NAME;
        		$request->organizationName = ORGANIZATION_NAME;
        		$request->tradingPlatformAccountId = $acc;
        		$request->maxRows = $maxRows;
        		$request->startTime = $fromdate1;
        		$request->endTime = $enddate1;
        	
        		$method = "Trading History";
				$crmurl = api_url."/GetTradingHistoryUpdated";
				$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
												
				$json_result = $update_mcrm['json_result'];
				$http_code = $update_mcrm['http_code'];

				$main_transaction_id = main_transaction_id();

        		if($http_code == "400" || $http_code == "404")
        		{
        			$_SESSION['pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
        			redirect($this->uri->segment(1)."/errors");
        		}
        		
        		if($http_code == "201" || $http_code == "200") //if operation successful
        		{
        			$data = array();
        			//	$data['my_array_data'] = json_decode($json_result, TRUE);
        			
        			//	print_r($data['my_array_data']);
        			try{
        				$user_email = $this->Common_model->get_current_user_email($_SESSION['username'] );
        				
        				
        				
        				$TH = $json_result->thAccounts;
        				$data['th']=$TH;
        				//		$data['result'] = $this->trading_history_model->get_current_user_data($user_email);
        			}
        			catch(Exception $e)
        			{
        				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        				"TRADING HISTORY DB EXCEPTION: ".json_encode($e).PHP_EOL
        				."-------------------------".PHP_EOL."\n";
        				log_message('error',$log);
        			}
        			$data['title'] = 'Trading History';
        			$data['bred_title'] = 'Trading History';
        			
        			
        			
        			$data['fromDate'] = $fromdate1;
        			$data['toDate'] = $enddate1;
        			
        				$this->load->view('templates/header', $data);
        				$this->load->view('templates/left-sidebar');
        				$this->load->view('traders_room/trading_history/trading_hist', $data);
        				$this->load->view('templates/footer');
        		//	$this->load->view('traders_room/trading_history/trading_hist', $data);
        			
        		}
        		
        		
        	}
        	catch (Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        		"TRADING HISTORY EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('error',$log);
        		//	redirect('error?error='.$e);
        		redirect($this->uri->segment(1)."/errors");
        		
        	}
        	
        	
        	
        }
        
        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
}