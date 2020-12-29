<?php
class Dashboard extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

                $this->load->helper('url_helper');
                $this->load->model('dashboard_model');
                $this->load->model('real_model');
                $this->load->model('demo_model');
                $this->load->model('personal_details_model');
                $this->load->model('monetary_statement_model');
                $this->load->model('trading_history_model');
                $this->load->model('withdrawal_request_model');
                $this->load->model('interfund_transfer_model');
                $this->load->model('document_upload_model');
                
                $this->load->helper(array('url'));
                $this->load->helper('prodconfig');

                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
                
     
        }
        
        public function countrychange()
        {
        	$country_code = $_GET['q'];
        	
        	$pco_code = $this->real_model->get_phone_code($country_code);
        	
        	echo $data['pco'] =$pco_code['dialing_code'];
        	
        }

        public function index()
        {
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	}
      	
        	$data   = array();
        	
        	$country_code = $this->personal_details_model->get_ccode($_SESSION['username'] );
        	$country_name = $this->personal_details_model->get_country_name($country_code);
        	$data['country_name'] = $country_name;
        	$data['country_code'] = $country_code;
        	$data['country'] = $this->personal_details_model->get_countries();
        	
        	$pco_code = $this->personal_details_model->get_phone_code($country_code);
        	$data['pco'] =$pco_code['dialing_code'];
        	
        	try{
        		$user_email = $this->dashboard_model->get_current_user_email($_SESSION['username'] );
        		
        		
        		$user_group = $this->dashboard_model->get_current_user_group($_SESSION['username'] );
        		$_SESSION['user_group'] = $user_group;
        		
        		
        		
        		$res =$data['result'] = $this->dashboard_model->get_current_user_data($user_email);
        		//$count =count($res);
        		
        		global  $OWNER_USER_ID, $BUSINESS_UNIT_NAME, $CRM_LOGIN, $CRM_PASSWORD;;
        		getParentBUDetails();
	
        		$newoutput = array();
        		foreach($res as $result)
        		{
       			
        			$request = new CrmGetAccountBalanceModel();
        			$request->tradingPlatformAccountName =$result->name;
        			$request->organizationName =ORGANIZATION_NAME;
        			$request->ownerUserId =$OWNER_USER_ID;
        			$request->businessUnitName =$BUSINESS_UNIT_NAME;
        			$url = api_url."/GetAccountBalance";
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
        			
        			$data['mgtapiBalance_res']= json_decode ($curl_result);
        			
        			$mgtapiBalance= json_decode ($curl_result);
   
        			//		print_r($mgtapiBalance);
        			
        			$output =array(
        					'type'=>$result->trading_platform,
        					'name'=>$result->name,
        					'trading_platform'=>$result->trading_platform,
        					'currency_code'=>$result->currency_code,
        					'balance'=>$mgtapiBalance->balance,
        					'equity'=>$mgtapiBalance->equity,
        					'margin'=>$mgtapiBalance->margin,
        					
        			);
        			$newoutput[]=$output;
        			
        			//	print"<pre>";	print_r($output);print"</pre>";
        			
        		
        			
        		}
        		
        		$data['newresult'] =$newoutput;

        		
        		
        	}
        	catch(Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"DASHBOARD DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL;
        		file_put_contents(logger_url, $log. "\n", FILE_APPEND);
        	}
        	
    
        	
        	
        	
        	
        	
        	
        	
        	$data['user_role'] = strtolower($_SESSION['user_role']);
        	$this->load->view('templates/header', $data );
        	$this->load->view('templates/left-sidebar', $data );
        	if($data['user_role']=='real')
        	{
        	$this->load->view('user/real-account-dashboard', $data);
        	}
        	if($data['user_role']=='demo')
        	{
        		$this->load->view('user/demo-account-dashboard', $data);
        	}
        	$this->load->view('templates/footer');
        
        	
        }
        
        public function demo_to_real()
        {}
        
        public function personal_details()
        {}
        
        public function change_pass()
        {}
        
        public function withdrawal_request()
        {}
        
        
        public function deposit()
        {}
        
        public function monetary_stmt()
        {}
        
        public function withdrawal_status()
        {}
        
        public function upload_doc()
        {}
        public function trading_hist()
        {}
        
        public function interfund()
        {}
        
        
        public function support()
        {}
        
        public function additional_acc()
        {}

        public function testing()
        {
        	
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	}
        	
        	$data['user_role'] = strtolower($_SESSION['user_role']);
        	$this->load->view('templates/header', $data );
        	$this->load->view('traders_room/testtab', $data);
        	$this->load->view('templates/footer');

        	
        	
        }
        
        public function change_pass_test()
        {}
        
        public function personal_details_test()
        {}
        

        public function deposit_option_view()
        {}

        

}

?>