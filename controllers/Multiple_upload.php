<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Multiple_upload extends CI_Controller{

	
  function __construct()
  {
		parent::__construct();
		$this->load->model('document_upload_model');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('prodconfig');
		$uri_1 = $this->uri->segment(1);
		all_lang($uri_1);
	}
	
	


	function upload_it() {
		if(!isset($_SESSION['logged_in']))
		{
			redirect('user/login');
		}
		//load the helper
		$this->load->helper('form');

		//Configure
		//set the path where the files uploaded will be copied. NOTE if using linux, set the folder to permission 777
		$config['upload_path'] = 'application/views/traders_room/document_upload/';
		
    // set the filter image types
		$config['allowed_types'] = 'gif|jpg|png|JPG|JPEG|PNG|GIF|PDF|pdf|DOCX|docx|DOC|doc';
		
		//load the upload library
		$this->load->library('upload', $config);
    
        $this->upload->initialize($config);
    
   //     $this->upload->set_allowed_types('*');

		$data['upload_data'] = '';
    
		//if not successful, set the error message
		if (!$this->upload->do_upload('userfile')) {
			$data = array('msg' => $this->upload->display_errors());

		} 
		else
		 { //else, set the success message
			$data = array('msg' => "Upload success!");
			
			global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
			try{
			$accountid=$this->document_upload_model->get_account_id($_SESSION['username']);
			}
			catch(Exception $e)
			{
				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
				"UPLOAD FILE DB EXCEPTION: ".json_encode($e).PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
			}
			getBUDetails($_SESSION['BUSINESS_UNIT']);
	
			$file_upload_details=$this->upload->data();
			$original_name=$file_upload_details['orig_name'];
			$filecontents=$file_upload_details['full_path'];
			$modi_name=$file_upload_details['file_name'];
					
			//File Upload Request parameters
			$uploadFile = new CrmUploadFileModel();
			$uploadFile->ownerUserId = $OWNER_USER_ID;
			$uploadFile->organizationName = ORGANIZATION_NAME;
			$uploadFile->businessUnitName = $BUSINESS_UNIT_NAME;
			$uploadFile->fileName = $original_name;
			$uploadFile->accountId = $accountid;
			$uploadFile->fileContent = base64_encode($filecontents);
			
			$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
			"UPLOAD FILE REQUEST: ".json_encode($uploadFile).PHP_EOL
			."-------------------------".PHP_EOL;
			file_put_contents(logger_url, $log. "\n", FILE_APPEND);
			
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
			
			 $rand_number = rand (100000,999999);
                  $date2 = new DateTime ();
				  $unique = uniqid();
                  $trans_refNum = $rand_number . $date2->getTimestamp();
				  $main_transaction_id = $trans_refNum."-".$rand_number."-".$unique; 

			$log  = "Transaction Id :".$main_transaction_id.PHP_EOL.
			"ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
			"HTTP CODE:".$http_code."UPLOAD FILE RESPONSE: ".json_encode($json_result).PHP_EOL
			."-------------------------".PHP_EOL;
			file_put_contents(logger_url, $log. "\n", FILE_APPEND);
			
			if ($http_code == "400" || $http_code == "404")
			{	
                $_SESSION['pop_mes'] = "<p>Main Transaction Id : ".$main_transaction_id."</p>";		
				redirect("errors");
			}
			if ($http_code == "201" || $http_code == "200") //if operation successful
			{
				$doc_type= $this->input->post('doc_type');
				try{
				$accountids=$this->document_upload_model->save_file($accountid,$original_name,$doc_type,$modi_name);
				
				$data   = array();
				
				$data['result']=$this->document_upload_model->get_upload_details($accountid);
				}
				catch(Exception $e)
				{
					$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[FATAL]".PHP_EOL.
					"UPLOAD FILE DB EXCEPTION: ".json_encode($e).PHP_EOL
					."-------------------------".PHP_EOL;
					file_put_contents(logger_url, $log. "\n", FILE_APPEND);
				}
				
			//	$_SESSION['pop_mes'] = lang("Document Uploaded successfully.");
			//	popup();
				redirect('upload_document');
			}

		}
		
	/*	
		$data['title'] = 'Upload a Document';
		
		//load the view/upload.php
		$this->load->view('templates/header', $data);
		$this->load->view('templates/sidebar');
		$this->load->view('traders_room/document_upload/multiple_upload', $data);
		$this->load->view('templates/footer');
		*/
	}

}