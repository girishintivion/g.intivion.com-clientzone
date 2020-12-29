<?php
class Cashier4 extends CI_Controller 
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
	
	
	public function cancel()
	{

		$myfile = fopen(logger_url_txt."dmx_certus_cancel.txt", "a") or die("Unable to open file!");
		
		fwrite($myfile, "===============================================\n"."cancel data [".date('Y-m-d H:i:s')."] = ".json_encode($_GET)."\n\n");
		
		fclose($myfile);

		redirect(DMX_BASE_URL.$this->uri->segment(1).'/cashier4/cancel?'.$_SERVER['QUERY_STRING']);
		
	}
	
	
	public function redirect()
	{

		$myfile = fopen(logger_url_txt."dmx_certus_redirect.txt", "a") or die("Unable to open file!");
		
		fwrite($myfile, "===============================================\n"."redirect data [".date('Y-m-d H:i:s')."] = ".json_encode($_GET)."\n\n");
		
		fclose($myfile);
		
		redirect(DMX_BASE_URL.$this->uri->segment(1).'/cashier4/redirect/?'.$_SERVER['QUERY_STRING']);

	}
	
	public function notification()
	{
		http_response_code(200);
		
		$jsonText = file_get_contents('php://input');
		$decodedText = html_entity_decode($jsonText);
		$myArray = json_decode($decodedText, true);
		
		$myfile = fopen(logger_url_txt."dmx_certus_notification.txt", "a") or die("Unable to open file!");
		
		fwrite($myfile, "===============================================\n"."data[".date('Y-m-d H:i:s')."] = ".json_encode($myArray)."\n\n");

		$myArray_json_en = json_encode($myArray);

		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, DMX_BASE_URL.'en/cashier4/notification');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$myArray_json_en);
		curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type: application/json"
		));
		$curl_result = curl_exec($ch);
		$curl_error = curl_error($ch);
		$curl_info = curl_getinfo($ch);
		curl_close($ch);
		
		fwrite($myfile, "res[".date('Y-m-d H:i:s')."]:".json_encode($curl_result)."\n\n");
		fwrite($myfile, "info[".date('Y-m-d H:i:s')."]:".json_encode($curl_info)."\n\n");
		fwrite($myfile, "err[".date('Y-m-d H:i:s')."]:".json_encode($curl_error)."\n\n");
		fclose($myfile);
	}
	

	public function index()
	{
		$myfile = fopen(logger_url_txt."dmx_certus_request.txt", "a") or die("Unable to open file!");
		
		fwrite($myfile, "===============================================\n"."request data [".date('Y-m-d H:i:s')."] = ".json_encode($_GET)."\n\n");
		
		fclose($myfile);
		
		?>
		<html>
		<head>
		
		</head>
		<body OnLoad="AutoSubmitForm();">
		<form name="downloadForm" action="<?php echo $_GET['url'];?>" method="POST">
		
		
		
		<input type="hidden" name="request" value="<?php echo $_GET['params'];?>">
		<SCRIPT >
		
		function AutoSubmitForm()
		{
			document.downloadForm.submit();
		}
		
		</SCRIPT>
		<h3>Transaction is in progress. Please wait...</h3>
		</form>
		</body>
		</html>
		<?php
	}
	


	


	
}




