<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/aws/aws-autoloader.php');
class Document_upload extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url_helper');
		$this->load->helper(array('url'));
		$this->load->model('document_upload_model');
		$this->load->model('real_model');
		$this->load->helper('prodconfig');
		$uri_1 = $this->uri->segment(1);
		all_lang($uri_1);
	}
	
	public function download_attempt()
	{
		if (! isset ( $_SESSION ['logged_in'] )) {
			echo '';
		}
		else{
			$modi_name = $_GET['dw_file'];
			$res = s3_bucket_downloadfile($modi_name);
			
			print $res;
		}
	}
	
	/*
	public function index()
	{
		
		
		if(!isset($_SESSION['logged_in']))
		{
			redirect($this->uri->segment(1).'/login');
		}
		
		$this->load->helper('form');
		$this->load->helper('url');
		//Set the message for the first time
		
		$data['title'] = 'Upload a Document';
		
		$accountid=$this->document_upload_model->get_account_id($_SESSION['username']);
		$data['result']=$this->document_upload_model->get_upload_details($accountid);
		
		$this->load->helper('form');
		
		$BUSINESS_UNIT_NAME = $_SESSION['BUSINESS_UNIT'];
		
		$config['encrypt_name']=TRUE; //31July2019 If set to TRUE the file name will be converted to a random encrypted string. This can be useful if you would like the file saved with a name that can not be discerned by the person uploading it.
		
		//Configure
		//set the path where the files uploaded will be copied. NOTE if using linux, set the folder to permission 777
		$config['upload_path'] = 'upload_document';
		
		// set the filter image types
		$config['allowed_types'] = 'gif|jpg|png|JPG|JPEG|PNG|GIF|PDF|pdf|DOCX|docx|DOC|doc';
		
		//load the upload library
		$this->load->library('upload', $config);
		
		$this->upload->initialize($config);
		
		//	$this->upload->set_allowed_types('*');
		
		$data['upload_data'] = '';
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('language', 'lang:File', 'trim|required');
		
		if($this->form_validation->run() === FALSE)
		{
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/left-sidebar');
			$this->load->view('traders_room/document_upload/upload_doc', $data);
			$this->load->view('templates/footer');
		}
else {
	$token = $this->input->post('my_token_doc');
			
		$session_token=null;
			
		$session_token = $_SESSION['form_token_doc']; 
		unset($_SESSION['form_token_doc']);
		if(!empty($token) == $session_token)
		{
	
			//else, set the success message
		
		

			if ($this->upload->do_upload('file1'))
			{ //else, set the success message
				$file_upload_details=$this->upload->data();
				$original_name=$file_upload_details['orig_name'];
				$filecontents=$file_upload_details['full_path'];
				$modi_name=$file_upload_details['file_name'];
					
				$_SESSION['orignalfile_address'] = $original_name;
				$_SESSION['filecontents_address'] = $filecontents;
				$_SESSION['modiname_address'] = $modi_name;
				$doc_type ="Proof of identity";
					
					
				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
					
				$owning_bu = get_owning_bu($_SESSION['username']);
				getBUDetails($owning_bu);
					
			//	$path = ROOT_DIR;//	$path = $_SERVER['DOCUMENT_ROOT'];
				$path = $_SERVER['DOCUMENT_ROOT'];
		
				$get_file=str_replace($path,wp_site_url,$filecontents);
//print $get_file;
				//	print $filecontents;print"<br>";
				//	print $get_file;print"<br>";
				//	print $path;exit();
					
				//	$filecontents2 = file_get_contents($get_file);
				$filecontents2 = file_get_contents($filecontents);
					
				$log  = "ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
				"UPLOAD FILE DB EXCEPTION: ".$get_file.PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
					
					
				$uploadFile = new CrmUploadFileModel();
				$uploadFile->ownerUserId = $OWNER_USER_ID;
				$uploadFile->organizationName = ORGANIZATION_NAME;
				$uploadFile->businessUnitName = $BUSINESS_UNIT_NAME;
				$uploadFile->fileName = $original_name;
				$uploadFile->accountId = $accountid;
				
//print"<pre>";print_r($uploadFile);print"</pre>";	
				
				$log  = "ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"UPLOAD FILE REQUEST: ".json_encode($uploadFile).PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
					
				$uploadFile->fileContent = base64_encode($filecontents2);
					
					
					
			//	print_r($uploadFile);	exit();
					
					
				$url = api_url."/uploadfile";
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, $url );
				curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
				curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
				curl_setopt ( $ch, CURLOPT_POST, TRUE );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($uploadFile));
				$curl_result = curl_exec ( $ch ); //getting response
				$curl_info = curl_getinfo ( $ch );
				curl_close ( $ch );
					
				$json_result = json_decode ($curl_result); //decode response in neat format
				$http_code = $curl_info ['http_code'];
					
					
//print"<pre>";print_r($json_result);print"</pre>";exit();
					
				$rand_number = rand (100000,999999);
						$date2 = new DateTime ();
						$unique1 = uniqid();
						$trans_refNum = $rand_number . $date2->getTimestamp();
						$main_transaction_id = $trans_refNum."-".$rand_number."-".$unique1;
						
						$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
						"ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"HTTP CODE:".$http_code."UPLOAD FILE RESPONSE: ".json_encode($json_result).PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
					
					
					
				if ($http_code == "400" || $http_code == "404")
				{
					$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'0');
					$_SESSION['error_pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
					redirect ( $this->uri->segment ( 1 ) . '/document-upload');
				}
					
					
					
				if ($http_code == "201" || $http_code == "200") //if operation successful
				{
		
		
		
					$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'1');
					$_SESSION['pop_mes'] = "<p>".lang("Document Uploaded successfully.")."</p>";
					redirect ( $this->uri->segment ( 1 ) . '/document-upload');
		
				}
					
			}
		
		
		
		
		elseif ($this->upload->do_upload('file2'))
			{ //else, set the success message
				$file_upload_details2=$this->upload->data();
				$original_name=$file_upload_details2['orig_name'];
				$filecontents=$file_upload_details2['full_path'];
				$modi_name=$file_upload_details2['file_name'];
					
				$_SESSION['orignalfile_address'] = $original_name;
				$_SESSION['filecontents_address'] = $filecontents;
				$_SESSION['modiname_address'] = $modi_name;
				$doc_type ="proof of residence";
				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
					
				$owning_bu = get_owning_bu($_SESSION['username']);
				getBUDetails($owning_bu);
					
				$path = $_SERVER['DOCUMENT_ROOT'];
				$get_file=str_replace($path,wp_site_url,$filecontents);
				//	$filecontents2 = file_get_contents($get_file);
				$filecontents2 = file_get_contents($filecontents);
					
				$log  = "ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
				"UPLOAD FILE DB EXCEPTION: ".$get_file.PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
					
					
				$uploadFile = new CrmUploadFileModel();
				$uploadFile->ownerUserId = $OWNER_USER_ID;
				$uploadFile->organizationName = ORGANIZATION_NAME;
				$uploadFile->businessUnitName = $BUSINESS_UNIT_NAME;
				$uploadFile->fileName = $original_name;
				$uploadFile->accountId = $accountid;
					
				$log  = "ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"UPLOAD FILE REQUEST: ".json_encode($uploadFile).PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
					
				$uploadFile->fileContent = base64_encode($filecontents2);
					
					
					
					
					
					
				$url = api_url."/uploadfile";
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, $url );
				curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
				curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
				curl_setopt ( $ch, CURLOPT_POST, TRUE );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($uploadFile));
				$curl_result = curl_exec ( $ch ); //getting response
				$curl_info = curl_getinfo ( $ch );
				curl_close ( $ch );
					
				$json_result = json_decode ($curl_result); //decode response in neat format
				$http_code = $curl_info ['http_code'];
					
					
				// print"<pre>";print_r('response'.json_encode($json_result));print"</pre>";
				
				$rand_number = rand (100000,999999);
						$date2 = new DateTime ();
						$unique1 = uniqid();
						$trans_refNum = $rand_number . $date2->getTimestamp();
						$main_transaction_id = $trans_refNum."-".$rand_number."-".$unique1;
						
						$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
						"ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"HTTP CODE:".$http_code."UPLOAD FILE RESPONSE: ".json_encode($json_result).PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
					
					
					
				if ($http_code == "400" || $http_code == "404")
				{
					$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'0');
					
					$_SESSION['error_pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
					redirect ( $this->uri->segment ( 1 ) . '/document-upload');
					
				}
					
					
					
				if ($http_code == "201" || $http_code == "200") //if operation successful
				{
						
		
		
					$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'1');
					$_SESSION['pop_mes'] = lang("Document Uploaded successfully.");
					redirect ( $this->uri->segment ( 1 ) . '/document-upload');
		
				}
			}
			
			
			
			
			elseif ($this->upload->do_upload('file3'))
			{ //else, set the success message
				$file_upload_details=$this->upload->data();
				$original_name=$file_upload_details['orig_name'];
				$filecontents=$file_upload_details['full_path'];
				$modi_name=$file_upload_details['file_name'];
				
				$_SESSION['orignalfile_address'] = $original_name;
				$_SESSION['filecontents_address'] = $filecontents;
				$_SESSION['modiname_address'] = $modi_name;
				$doc_type ="Credit Card";
				
				
				global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
				
				$owning_bu = get_owning_bu($_SESSION['username']);
				getBUDetails($owning_bu);
				
				//	$path = ROOT_DIR;//	$path = $_SERVER['DOCUMENT_ROOT'];
				$path = $_SERVER['DOCUMENT_ROOT'];
				
				$get_file=str_replace($path,wp_site_url,$filecontents);
				//print $get_file;
				//	print $filecontents;print"<br>";
				//	print $get_file;print"<br>";
				//	print $path;exit();
				
				//	$filecontents2 = file_get_contents($get_file);
				$filecontents2 = file_get_contents($filecontents);
				
				$log  = "ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
				"UPLOAD FILE DB EXCEPTION: ".$get_file.PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
				
				
				$uploadFile = new CrmUploadFileModel();
				$uploadFile->ownerUserId = $OWNER_USER_ID;
				$uploadFile->organizationName = ORGANIZATION_NAME;
				$uploadFile->businessUnitName = $BUSINESS_UNIT_NAME;
				$uploadFile->fileName = $original_name;
				$uploadFile->accountId = $accountid;
				
				//print"<pre>";print_r($uploadFile);print"</pre>";
				
				$log  = "ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"UPLOAD FILE REQUEST: ".json_encode($uploadFile).PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
				
				$uploadFile->fileContent = base64_encode($filecontents2);
				
				
				
				//	print_r($uploadFile);	exit();
				
				
				$url = api_url."/uploadfile";
				$ch = curl_init ();
				curl_setopt ( $ch, CURLOPT_URL, $url );
				curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
				curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
				curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
				curl_setopt ( $ch, CURLOPT_POST, TRUE );
				curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($uploadFile));
				$curl_result = curl_exec ( $ch ); //getting response
				$curl_info = curl_getinfo ( $ch );
				curl_close ( $ch );
				
				$json_result = json_decode ($curl_result); //decode response in neat format
				$http_code = $curl_info ['http_code'];
				
				
				//print"<pre>";print_r($json_result);print"</pre>";exit();
				
				$rand_number = rand (100000,999999);
				$date2 = new DateTime ();
				$unique1 = uniqid();
				$trans_refNum = $rand_number . $date2->getTimestamp();
				$main_transaction_id = $trans_refNum."-".$rand_number."-".$unique1;
				
				$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
				"ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"HTTP CODE:".$http_code."UPLOAD FILE RESPONSE: ".json_encode($json_result).PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
				
				
				
				if ($http_code == "400" || $http_code == "404")
				{
					$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'0');
					$_SESSION['error_pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
					redirect ( $this->uri->segment ( 1 ) . '/document-upload');
				}
				
				
				
				if ($http_code == "201" || $http_code == "200") //if operation successful
				{
					
					
					
					$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'1');
					$_SESSION['pop_mes'] = "<p>".lang("Document Uploaded successfully.")."</p>";
					redirect ( $this->uri->segment ( 1 ) . '/document-upload');
					
				}
				
			}
			
			
			
			
			else {
				//if not successful, set the error message
		
					$data = array('msg' => $this->upload->display_errors());
					$_SESSION['error_pop_mes'] = lang("Please select a File.");
					redirect ( $this->uri->segment ( 1 ) . '/document-upload');
				
			}
		
		
	
			
		}else{
			redirect ( $this->uri->segment ( 1 ) . '/document-upload');
		}
		
			
	}
		
	}
	*/
	
	
	public function index()
	{
		
		ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

		if(!isset($_SESSION['logged_in']))
		{
			redirect($this->uri->segment(1).'/login');
		}
		
		$this->load->helper('form');
		$this->load->helper('url');
		//Set the message for the first time
		
		$data['title'] = 'Upload a Document';
		
		$accountid=$this->document_upload_model->get_account_id($_SESSION['username']);
		$data['result']=$this->document_upload_model->get_upload_details($accountid);
		
		$this->load->helper('form');
		
		$BUSINESS_UNIT_NAME = $_SESSION['BUSINESS_UNIT'];
		
		$config['encrypt_name']=TRUE; //31July2019 If set to TRUE the file name will be converted to a random encrypted string. This can be useful if you would like the file saved with a name that can not be discerned by the person uploading it.
		
		//Configure
		//set the path where the files uploaded will be copied. NOTE if using linux, set the folder to permission 777
		$config['upload_path'] = 'upload_document';
		
		// set the filter image types
		$config['allowed_types'] = 'gif|jpg|png|JPG|JPEG|PNG|GIF|PDF|pdf';
		
		//load the upload library
		$this->load->library('upload', $config);
		
		$this->upload->initialize($config);
		
		//	$this->upload->set_allowed_types('*');
		
		$data['upload_data'] = '';
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('language', 'lang:File', 'trim|required');
		
		if($this->form_validation->run() === FALSE)
		{
			
			$this->load->view('templates/header', $data);
			$this->load->view('templates/left-sidebar');
			$this->load->view('traders_room/document_upload/upload_doc', $data);
			$this->load->view('templates/footer');
		}
		else {
			$token = $this->input->post('my_token_doc');
			
			$session_token=null;
			
			$session_token = $_SESSION['form_token_doc'];
			unset($_SESSION['form_token_doc']);
			if(!empty($token) == $session_token)
			{
				
				//else, set the success message
				
				
				
				if (isset($_FILES['file1']))
				{
					
					//else, set the success message
					
					
					$file_input_name = "file1";
					$s3_file_upload_path = AWS_S3_BUCKET_UPLOAD_PATH;
					$bucket_name = AWS_S3_BUCKET_NAME;
					$allowedExts = array("gif", "jpeg", "jpg", "png", "pdf");
					
					
					if( isset($_FILES[$file_input_name]) && file_exists($_FILES[$file_input_name]['tmp_name']) ) {
						
						if( isset($_FILES[$file_input_name]) && file_exists($_FILES[$file_input_name]['tmp_name']) ) {
							$typefile    = explode(".", $_FILES[$file_input_name]["name"]);
							//print_r($typefile);
							$extension   = end($typefile);
							if (!in_array(strtolower($extension), $allowedExts)) {
								//not a valid extension
								$_SESSION ['pop_mes'] = lang("This file type is not allowed.");
								redirect ( $this->uri->segment ( 1 ) . '/document-upload');
							} else {
								
								$uuid = uniqid("", true);
								$new_file_name = $uuid . "." . $extension;
								
								$path = $_FILES[$file_input_name]['tmp_name'];
								
								$uploadResult = s3_bucket_uploadnew($new_file_name,$path);
								
								// print"<pre>"; print_r($uploadResult); print"</pre>"; exit();
								
								if ($uploadResult['uploadResult']['@metadata']['statusCode'] == "200") {
									
									$get_file = $uploadResult['presignedUrl'];
								
									global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
									
									$owning_bu = get_owning_bu($_SESSION['username']);
									getBUDetails($owning_bu);
									
									$_SESSION['orignalfile_address'] = $_FILES[$file_input_name]["name"];
									//	$_SESSION['filecontents_address'] = $filecontents;
									$_SESSION['modiname_address'] = $new_file_name;
									$doc_type ="Proof of identity";
									
									$uploadFile = new CrmUploadFileModel();
									$uploadFile->ownerUserId = $OWNER_USER_ID;
									$uploadFile->organizationName = ORGANIZATION_NAME;
									$uploadFile->businessUnitName = $BUSINESS_UNIT_NAME;
									$uploadFile->fileName =  $_FILES[$file_input_name]["name"];
									$uploadFile->accountId = $accountid;
									
									$filecontents2 = file_get_contents($get_file);
									$uploadFile->fileContent = base64_encode($filecontents2);
									
									$method = "Document Upload";
									$crmurl = api_url."/uploadfile";
									$update_mcrm = send_to_Mcrm($crmurl,$uploadFile,$method);
															
									$json_result = $update_mcrm['json_result'];
									$http_code = $update_mcrm['http_code'];
											
									$main_transaction_id = main_transaction_id();	

									if ($http_code == "400" || $http_code == "404")
									{
										//$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'0');
										$firstfileupload = $this->real_model->document_upload($accountid,$new_file_name,$_FILES[$file_input_name]["name"],$get_file,$BUSINESS_UNIT_NAME,$doc_type,'0');
										
										$_SESSION['error_pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
										redirect ( $this->uri->segment ( 1 ) . '/document-upload');
									}
									
									
									
									if ($http_code == "201" || $http_code == "200") //if operation successful
									{
										
										
										
										//	$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'1');
										$firstfileupload = $this->real_model->document_upload($accountid,$new_file_name,$_FILES[$file_input_name]["name"],$get_file,$BUSINESS_UNIT_NAME,$doc_type,'1');
										
										$_SESSION['pop_mes'] = "<p>".lang("Document Uploaded successfully.")."</p>";
										redirect ( $this->uri->segment ( 1 ) . '/document-upload');
										
									}
									
								}
							}
						}
						
						
					}
					
					
				}
				
				
				
				
				elseif ($this->upload->do_upload('file2'))
				{
					
					//else, set the success message
					
					
					$file_input_name = "file2";
					$s3_file_upload_path = AWS_S3_BUCKET_UPLOAD_PATH;
					$bucket_name = AWS_S3_BUCKET_NAME;
					$allowedExts = array("gif", "jpeg", "jpg", "png", "pdf");
					
					
					
					
					if( isset($_FILES[$file_input_name]) && file_exists($_FILES[$file_input_name]['tmp_name']) ) {
						
						if( isset($_FILES[$file_input_name]) && file_exists($_FILES[$file_input_name]['tmp_name']) ) {
							$typefile    = explode(".", $_FILES[$file_input_name]["name"]);
							//print_r($typefile);
							$extension   = end($typefile);
							if (!in_array(strtolower($extension), $allowedExts)) {
								//not a valid extension
								$_SESSION ['pop_mes'] = lang("This file type is not allowed.");
								redirect ( $this->uri->segment ( 1 ) . '/document-upload');
							} else {
								
								$uuid = uniqid("", true);
								$new_file_name = $uuid . "." . $extension;
								
								$path = $_FILES[$file_input_name]['tmp_name'];
								
								$uploadResult = s3_bucket_uploadnew($new_file_name,$path);
								
								if ($uploadResult['uploadResult']['@metadata']['statusCode'] == "200") {
									
									$get_file = $uploadResult['presignedUrl'];
								
									global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
									
									$owning_bu = get_owning_bu($_SESSION['username']);
									getBUDetails($owning_bu);
									
									$_SESSION['orignalfile_address'] = $_FILES[$file_input_name]["name"];
									//	$_SESSION['filecontents_address'] = $filecontents;
									$_SESSION['modiname_address'] = $new_file_name;
									$doc_type ="proof of residence";
									
									
									
									
									
									$uploadFile = new CrmUploadFileModel();
									$uploadFile->ownerUserId = $OWNER_USER_ID;
									$uploadFile->organizationName = ORGANIZATION_NAME;
									$uploadFile->businessUnitName = $BUSINESS_UNIT_NAME;
									$uploadFile->fileName =  $_FILES[$file_input_name]["name"];
									$uploadFile->accountId = $accountid;
									
									$filecontents2 = file_get_contents($get_file);
									$uploadFile->fileContent = base64_encode($filecontents2);
								
									$method = "Document Upload";
									$crmurl = api_url."/uploadfile";
									$update_mcrm = send_to_Mcrm($crmurl,$uploadFile,$method);
															
									$json_result = $update_mcrm['json_result'];
									$http_code = $update_mcrm['http_code'];
											
									$main_transaction_id = main_transaction_id();	

									if ($http_code == "400" || $http_code == "404")
									{
										//$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'0');
										$firstfileupload = $this->real_model->document_upload($accountid,$new_file_name,$_FILES[$file_input_name]["name"],$get_file,$BUSINESS_UNIT_NAME,$doc_type,'0');
										
										$_SESSION['error_pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
										redirect ( $this->uri->segment ( 1 ) . '/document-upload');
									}
									
									
									
									if ($http_code == "201" || $http_code == "200") //if operation successful
									{
										
										
										
										//	$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'1');
										$firstfileupload = $this->real_model->document_upload($accountid,$new_file_name,$_FILES[$file_input_name]["name"],$get_file,$BUSINESS_UNIT_NAME,$doc_type,'1');
										
										$_SESSION['pop_mes'] = "<p>".lang("Document Uploaded successfully.")."</p>";
										redirect ( $this->uri->segment ( 1 ) . '/document-upload');
										
									}
									
								}
							}
						}
						
						
					}
					
					
				}
				
				
				
				
				elseif (isset($_FILES['file3']))
				{
					
					//else, set the success message
					
					
					$file_input_name = "file3";
					$s3_file_upload_path = AWS_S3_BUCKET_UPLOAD_PATH;
					$bucket_name = AWS_S3_BUCKET_NAME;
					$allowedExts = array("gif", "jpeg", "jpg", "png", "pdf");
					
					
					
					
					if( isset($_FILES[$file_input_name]) && file_exists($_FILES[$file_input_name]['tmp_name']) ) {
						
						if( isset($_FILES[$file_input_name]) && file_exists($_FILES[$file_input_name]['tmp_name']) ) {
							$typefile    = explode(".", $_FILES[$file_input_name]["name"]);
							//print_r($typefile);
							$extension   = end($typefile);
							if (!in_array(strtolower($extension), $allowedExts)) {
								//not a valid extension
								$_SESSION ['pop_mes'] = lang("This file type is not allowed.");
								redirect ( $this->uri->segment ( 1 ) . '/document-upload');
							} else {
								
								$uuid = uniqid("", true);
								$new_file_name = $uuid . "." . $extension;
								
								$path = $_FILES[$file_input_name]['tmp_name'];
								
								
								/*
								$uploadResult = s3_bucket_upload($path, $s3_file_upload_path . $new_file_name);
								$s3_base_url = "http://" . $bucket_name . ".s3.amazonaws.com/" .$s3_file_upload_path;
								$get_file = $s3_base_url . $new_file_name;
								*/
								
								
								$uploadResult = s3_bucket_uploadnew($new_file_name,$path);
								
								if ($uploadResult['uploadResult']['@metadata']['statusCode'] == "200") {
									
									$get_file = $uploadResult['presignedUrl'];
									
									global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
									
									$owning_bu = get_owning_bu($_SESSION['username']);
									getBUDetails($owning_bu);
									
									$_SESSION['orignalfile_address'] = $_FILES[$file_input_name]["name"];
									//	$_SESSION['filecontents_address'] = $filecontents;
									$_SESSION['modiname_address'] = $new_file_name;
									$doc_type ="credit card";
									
									$uploadFile = new CrmUploadFileModel();
									$uploadFile->ownerUserId = $OWNER_USER_ID;
									$uploadFile->organizationName = ORGANIZATION_NAME;
									$uploadFile->businessUnitName = $BUSINESS_UNIT_NAME;
									$uploadFile->fileName =  $_FILES[$file_input_name]["name"];
									$uploadFile->accountId = $accountid;
									
									$filecontents2 = file_get_contents($get_file);
									$uploadFile->fileContent = base64_encode($filecontents2);
									
									$method = "Document Upload";
									$crmurl = api_url."/uploadfile";
									$update_mcrm = send_to_Mcrm($crmurl,$uploadFile,$method);
															
									$json_result = $update_mcrm['json_result'];
									$http_code = $update_mcrm['http_code'];
											
									$main_transaction_id = main_transaction_id();	
									
									if ($http_code == "400" || $http_code == "404")
									{
										//$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'0');
										$firstfileupload = $this->real_model->document_upload($accountid,$new_file_name,$_FILES[$file_input_name]["name"],$get_file,$BUSINESS_UNIT_NAME,$doc_type,'0');
										
										$_SESSION['error_pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";
										redirect ( $this->uri->segment ( 1 ) . '/document-upload');
									}
									
									
									
									if ($http_code == "201" || $http_code == "200") //if operation successful
									{
										
										
										
										//	$firstfileupload = $this->real_model->document_upload($accountid,$modi_name,$original_name,$filecontents,$BUSINESS_UNIT_NAME,$doc_type,'1');
										$firstfileupload = $this->real_model->document_upload($accountid,$new_file_name,$_FILES[$file_input_name]["name"],$get_file,$BUSINESS_UNIT_NAME,$doc_type,'1');
										
										$_SESSION['pop_mes'] = "<p>".lang("Document Uploaded successfully.")."</p>";
										redirect ( $this->uri->segment ( 1 ) . '/document-upload');
										
									}
									
								}
							}
						}
						
						
					}
					
					
				}
				
				
				
				
				else {
					//if not successful, set the error message
					
					$data = array('msg' => $this->upload->display_errors());
					$_SESSION['error_pop_mes'] = lang("Please select a File.");
					redirect ( $this->uri->segment ( 1 ) . '/document-upload');
					
				}
				
				
				
				
			}else{
				redirect ( $this->uri->segment ( 1 ) . '/document-upload');
			}
			
			
		}
		
	}
	

	

	
}