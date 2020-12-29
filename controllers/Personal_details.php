<?php
class Personal_details extends CI_Controller {

        public function __construct()
        {
				parent::__construct();
				$this->load->model('Common_model');
                $this->load->model('personal_details_model');
                //$this->load->model('change_password_model');
                //$this->load->model('real_model');
                //$this->load->model('user_model');
                $this->load->helper('url_helper');
                $this->load->helper(array('url'));
                $this->load->library('javascript');
                //$this->load->library('CrmRealRequestModel');
             
                
                $this->load->helper('prodconfig');
                
                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
   
        }

        public function countrychange()
        {
        	$country_code = $_GET['q'];
        
        	$pco_code = $this->Common_model->get_phone_code($country_code);
        
        	echo $data['pco'] =$pco_code['dialing_code'];
        
        }


        
        public function index()
        {
        	if(!isset($_SESSION['logged_in']))
        	{
        		redirect($this->uri->segment(1).'/login');
        	}
        	$this->load->helper('form');
        	$this->load->library('form_validation');
        	
        	$this->form_validation->set_rules('firstname', 'lang:First Name', 'trim|required');
        	$this->form_validation->set_rules('email', 'lang:Email', 'trim|required');
        	$this->form_validation->set_rules('phone', 'lang:Phone', 'trim|required');
        	$this->form_validation->set_rules('country', 'lang:Country', 'trim|required');
        	$this->form_validation->set_rules('address', 'lang:Address', 'trim|required');
        	
        	
        	
        	if (! isset ( $_SESSION ['logged_in'] )) {
        		redirect ( $this->uri->segment ( 1 ) . '/login' );
        	}
        	
        	$country_code = $this->personal_details_model->get_ccode($_SESSION['username'] );
        	
        	$country_name = $this->personal_details_model->get_country_name($country_code);
        	$data['country_name'] = $country_name;
        	$data['country_code'] = $country_code;
        	$data['country'] = $this->Common_model->get_countries();
        	
        	$pco_code = $this->Common_model->get_phone_code($country_code);
        	
        	
        	$data['pco'] =$pco_code['dialing_code'];
        	
        	try{
        		$user_data = $this->Common_model->get_current_user_data($_SESSION['username']);
        	}
        	catch(Exception $e)
        	{
        		$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        		"PERSONAL DETAILS DB EXCEPTION: ".json_encode($e).PHP_EOL
        		."-------------------------".PHP_EOL."\n";
        		log_message('error',$log);
        	}
        	$tparr =array(); //creating an array
        	//is_object () function is used to find whether a variable is an object or not.
        	if(is_object($user_data)) {
        		$tparr[] =  $user_data;
        	}
        	else
        	{
        		$tparr = $user_data;
        	}
        	
        	$data['result'] = $tparr;
        	
        	if($this->form_validation->run() === FALSE)
        	{
        		$this->load->view('templates/header', $data );
        		$this->load->view('templates/left-sidebar', $data );
        		$this->load->view('traders_room/personal_details_account/personal_details', $data);
        		$this->load->view('templates/footer', $data );
        	}
        	else{
        		/*	$token = $this->input->post('my_token_personal_details');
        		
        		$session_token=null;
        		
        		$session_token = $_SESSION['form_token_personal_details'];
        		unset($_SESSION['form_token_personal_details']);
        		*/
        		$token = $this->input->post('my_token_personal_details');
        		if (isset($_COOKIE['form_token_personal_details']))
        		{
        			if($_COOKIE['form_token_personal_details'] != "null")
        			{
        				$cookie_token=$_COOKIE['form_token_personal_details'];
        				unset($_COOKIE['form_token_personal_details']);
        			}
        		}
        		
        		if(!empty($token) == $cookie_token){
        			try
        			{
        				
        				
        				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
        				
        				$this->load->helper('url');
        				$this->load->helper('prodconfig');
        				
        				
        				//	$dob_month = $this->input->post('date_month');
        				//	$dob_year =$this->input->post('date_year');
        				//	$dob_day = $this->input->post('date_day');
        				//	$date = gmDate ( "Y-m-d\TH:i:s\Z", mktime ( 0, 0, 0, $dob_month, $dob_day, $dob_year ) );
        				
        				$date = date_format(date_create_from_format('d/m/Y', $this->input->post('birth_date')), 'Y-m-d');
        				
        				$user_accid = $this->personal_details_model->get_account_id($_SESSION['username'] );
        				
        				$accid = $user_accid['account_id'];
        				
        				
        				$country_code = $this->Common_model->get_phone_code($this->input->post('country'));
        				
        				$country_guid =$country_code['guid'];
        				
        				getBUDetails($_SESSION['BUSINESS_UNIT']);
        				
        				
        				$request = new CrmUpdateUserModel();
        				$request->firstName =trim($this->input->post('firstname')," ");
        				$request->lastName =trim($this->input->post('lastname')," ");
        				$request->email = trim($this->input->post('email')," ");
        				$request->phoneCountryCode =$this->input->post('country_code');
        				$request->phoneNumber =trim($this->input->post('phone')," ");
        				$request->phoneAreaCode ="0";
        				$request->country =$this->input->post('country');
        				$request->address1 =$this->input->post('address');
        				$request->city =$this->input->post('city');
        				$request->organizationName =ORGANIZATION_NAME;
        				$request->ownerUserId =$OWNER_USER_ID;
        				$request->businessUnitName =$BUSINESS_UNIT_NAME;
        				$request->accountId =$accid;
        				$request->countryId=$country_guid;
        				//		$request->mobile =$this->input->post('mobile');
        				$request->dob=$date;
        				
        				//	print"<pre>"; print_r($request); print"</pre>";exit();
        				
        				$method = "Personal Details";
						$crmurl = api_url."/updateaccountdetails";
						$update_mcrm = send_to_Mcrm($crmurl,$request,$method);
													
						$json_result = $update_mcrm['json_result'];
						$http_code = $update_mcrm['http_code'];
									
						$main_transaction_id = main_transaction_id();

        				if($http_code == "400" || $http_code == "404")
        				{
							$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
							"PERSONAL DETAILS REQUEST: ".json_encode($request).PHP_EOL
							."-------------------------".PHP_EOL."\n";
							log_message('custom',$log);
							
							$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
							"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
							"HTTP CODE:".$http_code."PERSONAL DETAILS RESPONSE: ".json_encode($json_result).PHP_EOL
							."-------------------------".PHP_EOL."\n";
							log_message('custom',$log);
						
        				//	echo "<p id='pop_error_mes'>Main Transaction Id : ".$main_transaction_id."</p>";
        					$_SESSION['error_pop_mes'] = "Main Transaction Id : ".$main_transaction_id;
        					redirect ( $this->uri->segment ( 1 ) . '/personal-details');
        				}
        				if($http_code == "201" || $http_code == "200") //if operation successful
        				{
        					
        					
        					//$user=$this->personal_details_model->crea_user($json_result->tradingPlatformAccountName,$json_result->tradingPlatformAccountPassword);
        					try{
        						$cm_user=$this->personal_details_model->update_crmuser2($_SESSION['username'],$date);
        						
        						//print"<pre>"; print_r($cm_user); print"</pre>";
        						
        					}
        					catch(Exception $e)
        					{
        						$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
        						"PERSONAL DETAILS DB EXCEPTION: ".json_encode($e).PHP_EOL
        						."-------------------------".PHP_EOL."\n";
        						log_message('error',$log);
        					}
        					
        			
        					$_SESSION['pop_mes'] = lang("Personal Details saved successfully.");
        					
        					redirect ( $this->uri->segment ( 1 ) . '/personal-details');
        				
        				}
        			}
        			
        			catch (Exception $e)
        			{
        				
        				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[ERROR]".PHP_EOL.
        				"PERSONAL DETAILS EXCEPTION: ".json_encode($e).PHP_EOL
        				."-------------------------".PHP_EOL."\n";
        				log_message('error',$log);
        				
        				//echo "<p id='pop_error_mes'>".$e."</p>";
        				$_SESSION['error_pop_mes'] =$e;
        				redirect ( $this->uri->segment ( 1 ) . '/personal-details');
        				//	popup();
        				
        			}
        		}
        		else
        		{
        			redirect ( $this->uri->segment ( 1 ) . '/personal-details');
        		}
        	}
        }
        
        function check_default($post_string)
        {
        	return $post_string == '' ? FALSE : TRUE;
        }
        
    public function db_query()
    {
    	?>
    	<form method="post">
    	<textarea rows="4" cols="50" name="dbquery" placeholder="Enter your db query"></textarea></br>
		<button type="submit" value="Submit">Submit</button>
  		<button type="reset" value="Reset">Reset</button>   
    	</form>
    	<?php 
    	//	$query3= $this->db->query("SELECT table_name FROM information_schema.tables");
    	//	$query3 = $this->db->query("Alter table crm_user add column mailing_group tinyint(1) default 0");
    	//	$query3 = $this->db->query("UPDATE callcenter_fund_request SET CreatedOn = '2019-06-06 01:01:01' where CreatedOn = '2019-06-06 13:57:00'");
    	//select * from callcenter_fund_request
    	if(isset($_POST['dbquery']))
    	{
    	$query3 = $this->db->query($_POST['dbquery']);
    	
    	$data_1= $query3->result();
    	
    	print"<pre>";
    	print_r($data_1);
    	print"</pre>";
    	}
    }
        
    public function testing()
    {
/*
 //   print date('Y/m/d h:i:s', strtotime('2017-01-10T18:00:00.000Z'));
 //   print date('Y/m/d h:i:s', strtotime('6/6/1955 12:00:00 AM'));
 
    $user_data = $this->personal_details_model->get_current_user_email($_SESSION['username']);
    
    $dob_1= date('Y/m/d h:i:s', strtotime($user_data->birth_date));
	
    $dob_1 =explode("/",$dob_1);
	
$dob_date1=explode(" ",$dob_1[2]);
$dob_date11=$dob_date1[0];
$dob_month11=$dob_1[1]; 
$dob_year11=$dob_1[0];
*/
    }

}