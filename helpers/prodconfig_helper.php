<?php
use Aws\S3\S3Client;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
define("ORGANIZATION_NAME", "Digitalmedia");

define('DMX_BASE_URL','https://www.dmxmarkets.com/clientzone/');

global $mybu;

$mybu=array("Digitalmedia","Midaswms.com","English","Italian","Australia","EN-Australia","Conversion", "Conversion english");

define('openexchangerates_app_id','5da8b73ed0ef473dae842370790af767');

define('AWS_S3_BUCKET_NAME','www.b-trade.io');

define('AWS_S3_BUCKET_UPLOAD_PATH','documents/');

/*
define('GUMBALLPAY_GROUP_ID','1042');
define('GUMBALLPAY_LOGIN','Midas_wms');
define('GUMBALLPAY_MERCHANT_CONTROL','8A96CB84-D70B-11EA-B4AB-AC1F6B763778');
define('GUMBALLPAY_URL','https://sandbox.gumballpay.com');
*/

define('GUMBALLPAY_GROUP_ID','2038');
define('GUMBALLPAY_LOGIN','Midas_wms');
define('GUMBALLPAY_MERCHANT_CONTROL','19CD5FB3-6483-4AB0-B477-760155952C9E');
define('GUMBALLPAY_URL','https://gate.gumballpay.com');

// REAL_DEPOSITS Live details 
define('REAL_DEPOSITS_MERCHANT_ID','API-Midaswms');
define('REAL_DEPOSITS_MERCHANT_SECRET','qlJdlxixqWvg5ajouD51ewbycmimQ1D8');
define('REAL_DEPOSITS_URL','https://api.realdeposits.com/api/init-pay-in');
define('REAL_DEPOSITS_APPLICATION_KEY','midaswms'); // live
define('REAL_DEPOSITS_GATEWAY_IPAYTOTAL','DTc1ps0tRh1j9OqPVyeZgr0a_Z3WF6Pi');
define('REAL_DEPOSITS_GATEWAY_PAYCENT','Z8Q_jsHLsJtbOvT20_TQKpIJbqq1cfGi');

define('REAL_DEPOSITS_APPLICATION_KEY_TEST','midaswmstest'); // test

//////////////////////////////////////////////////////

 
 
 
//////////////////// CERTUS ///////////////
 
 define("CERTUS_MERCHANT_ID", "0dd67f90-007f-4309-9ac4-8b3768d19c73");
 define("CERTUS_ACCOUNT_ID", "abd2f862-74ca-42a9-a603-459e5027a7ed");
 define("CERTUS_USERNAME", "Midaswms32a61eebb5e2916c4e");
 define("CERTUS_PASSWORD", "a7a076846c8a40acbd8d69bef5d5e61b");
 define("CERTUS_KEY", "ad730eef3533cebb");
 define("CERTUS_URL", "https://api.certus.finance/FE/rest/tx/purchase/w/execute");
 
 //////////////////// CERTUS END  ///////////////
 
 
 
 //////////////////// CERTUS test ///////////////
 /*
 define("CERTUS_MERCHANT_ID", "459159e8-1ff3-4f79-b0c1-19f8302a0424");
 define("CERTUS_ACCOUNT_ID", "5914d99e-e72c-43d0-a788-7efdc3a53f96");
 define("CERTUS_USERNAME", "account1");
 define("CERTUS_PASSWORD", "account1");
 define("CERTUS_KEY", "1234567890098765");
 define("CERTUS_URL", "https://sandbox.certus.finance/FE/rest/tx/purchase/w/execute");
 */
 
 //////////////////// CERTUS END  ///////////////
 
 
 
 
 
 //////////////////// CERTUS btc ///////////////
 
 define("CERTUS_BTC_CLIENT_ID","KaOsYRcKJhEUOmme");
 define("CERTUS_BTC_CLIENT_SECRET", "tQk8O5OKJgSg9ckROb7GPFHnOKPRfY");
 define("CERTUS_BTC_MERCHANT_ACCOUNT_KEY", "bf16a3ed5570a75e");
 define("CERTUS_BTC_URL","https://api.certus.finance/cc");
 
 //////////////////// CERTUS btc END  ///////////////
 
 
 
 //////////////////// CERTUS btc test ///////////////
 
/* 
 define("CERTUS_BTC_CLIENT_ID","AMsLk1qFat4Ra5rd");
 define("CERTUS_BTC_CLIENT_SECRET", "9JpMa6kDLttqs6Enn8p8cf485cPcQN");
 define("CERTUS_BTC_MERCHANT_ACCOUNT_KEY", "e15ab43296d8d3df");
 define("CERTUS_BTC_URL","https://sandbox.certus.finance/cc");
*/  
 
 //////////////////// CERTUS btc END  ///////////////
 
 
 function get_uk_ip()
 {
 	
 	$username = 'lum-customer-hl_db480584-zone-static-route_err-pass_dyn';
 	$password = 'wa22odw9t60g';
 	$port = 22225;
 	$user_agent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';
 	$session = mt_rand();
 	$super_proxy = 'zproxy.lum-superproxy.io';
 	$curl = curl_init('http://lumtest.com/myip.json');
 	curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
 	curl_setopt($curl, CURLOPT_PROXY, "http://$super_proxy:$port");
 	curl_setopt($curl, CURLOPT_PROXYUSERPWD, "$username-country-gb-session-$session:$password");
 	$result = curl_exec($curl);
 	$curl_error = curl_error($curl);
 	$curl_info = curl_getinfo($curl);
 	curl_close($curl);
 	
 	$result_jd=json_decode($result,true);
 	
 	$ip=$result_jd['ip'];
 	
 	if(empty($ip))
 	{
 		$ip = get_client_ip();
 	}

 	return $ip;
 }
 
 
 function s3_bucket_uploadnew($new_file_name,$path)
 {
 	$CI = & get_instance();
 	$bucketName = AWS_S3_BUCKET_NAME;
 	$s3_file_upload_path = AWS_S3_BUCKET_UPLOAD_PATH;
 	
 	
 	$data = array();
 	
 	try {
 		
 		
 		
 		$sharedConfig = [
 				'region' => 'eu-west-1',
 				'version' => 'latest'
 		];
 		
 		// Create an SDK class used to share configuration across clients.
 		$sdk = new Aws\S3\S3Client($sharedConfig);
 		
 		
 		$uploadResult = $sdk->putObject([
 				'Bucket' => $bucketName,
 				'Key'    => $s3_file_upload_path.$new_file_name,
 				'SourceFile' => $path,
 		]);
 		
 		$data['uploadResult']=$uploadResult;
 		
 		$command = $sdk->getCommand('GetObject', [
 				'Bucket' => $bucketName,
 				'Key'    => $s3_file_upload_path.$new_file_name,
 		]);
 		
 		
 		// Create a pre-signed URL for a request with duration of 10 miniutes
 		$presignedRequest = $sdk->createPresignedRequest($command, '+7 days');
 		
 		// Get the actual presigned-url
 		$presignedUrl =  (string)  $presignedRequest->getUri();
 		$data['presignedUrl']=$presignedUrl;
 		
 	} catch (Exception $e) {
 		$data['status'] = false;
 		$data['error'] = $e->getMessage();
 	}
 	
 	return $data;
 }
 
 
 
 function s3_bucket_downloadfile($new_file_name)
 {
 	$CI = & get_instance();
 	$bucketName = AWS_S3_BUCKET_NAME;
 	$s3_file_upload_path = AWS_S3_BUCKET_UPLOAD_PATH;
 	
 	
 	$data = array();
 	
 	try {
 		
 		
 		
 		$sharedConfig = [
 				'region' => 'eu-west-1',
 				'version' => 'latest'
 		];
 		
 		// Create an SDK class used to share configuration across clients.
 		$sdk= new Aws\S3\S3Client($sharedConfig);
 		
 		
 		
 		$command = $sdk->getCommand('GetObject', [
 				'Bucket' => $bucketName,
 				'Key'    => $s3_file_upload_path.$new_file_name,
 		]);
 		
 		
 		// Create a pre-signed URL for a request with duration of 10 miniutes
 		$presignedRequest = $sdk->createPresignedRequest($command, '+7 days');
 		
 		// Get the actual presigned-url
 		$presignedUrl =  (string)  $presignedRequest->getUri();
 		
 		//send file to browser for download.
 		return $presignedUrl;
 		
 		
 		
 		
 		
 		
 		
 		
 	} catch (Exception $e) {
 		
 		return $e->getMessage();
 	}
 	
 	
 }
 
 
 
 
 
 
 function generateRandomString()
 {
 	$characters = 'abcdefghijklmnopqrstuvwxyz';
 	$charactersLength = strlen($characters);
 	$randomString = '';
 	for ($i = 0; $i < 5; $i++)
 	{
 		$randomString .= $characters[rand(0, $charactersLength - 1)];
 	}
 	return $randomString;
 }
 
 
 function fake_data()
 {
 	$data=array();
 	$data['phone']=rand();
 	$data['fname']=generateRandomString();
 	$data['lname']=generateRandomString();
 	$data['email']=generateRandomString().'@'.generateRandomString().'.com';
 	return $data;
 }
 
 

function getParentBUDetails()
{
	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$CRM_LOGIN,$CRM_PASSWORD;
	
	$OWNER_USER_ID = "{26231fe5-67f9-e911-a2cd-005056b1e92b}";
	$BUSINESS_UNIT_NAME = "Digitalmedia";
	$CRM_LOGIN="digitalmedia";
	$CRM_PASSWORD="YNh8q4cwUc@";
	
	
}


function getBUDetails($buName)
{
	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME, $CRM_LOGIN, $CRM_PASSWORD, $DEMO_PLATFORM_ID, $REAL_PLATFORM_ID;
	global $DEMO_PLATFORM_GROUP, $REAL_PLATFORM_GROUP;
	
	if($buName == "English")
	{
		$OWNER_USER_ID = "{B4B54210-68F9-E911-A2CD-005056B1E92B}";
		$BUSINESS_UNIT_NAME = "English";
		
	}
	elseif($buName == "Italian")
	{
		$OWNER_USER_ID = "{314DBD2F-68F9-E911-A2CD-005056B1E92B}";
		$BUSINESS_UNIT_NAME = "Italian";
		
	}
	elseif($buName == "Midaswms.com")
	{
		$OWNER_USER_ID = "{bcad260a-68f9-e911-a2cd-005056b1e92b}";
		$BUSINESS_UNIT_NAME = "Midaswms.com";
	}
	elseif($buName == "Australia")
	{
		$OWNER_USER_ID = "{7ba2e640-1278-ea11-a2ce-005056b10e15}";
		$BUSINESS_UNIT_NAME = "Australia";
	}
	elseif($buName == "EN-Australia")
	{
		$OWNER_USER_ID = "{0f0dcf4b-1378-ea11-a2ce-005056b10e15}";
		$BUSINESS_UNIT_NAME = "EN-Australia";
	}
	elseif($buName == "Conversion")
	{
		$OWNER_USER_ID = "{832da8c4-bed0-ea11-a2d2-005056b1b8b7}";
		$BUSINESS_UNIT_NAME = "Conversion";
	}
	elseif($buName == "Conversion english")
	{
		$OWNER_USER_ID = "{5f936502-c7d0-ea11-a2d2-005056b1b8b7}";
		$BUSINESS_UNIT_NAME = "Conversion english";
	}
	else
	{
		
		$OWNER_USER_ID = "{26231fe5-67f9-e911-a2cd-005056b1e92b}";
		$BUSINESS_UNIT_NAME = "Digitalmedia";
		
	}
	
	
	
}

function getBUDetailsExe($buName,$curr,$platform)
{
	
	
	global  $OWNER_USER_ID, $BUSINESS_UNIT_NAME, $CRM_LOGIN, $CRM_PASSWORD, $DEMO_PLATFORM_ID, $REAL_PLATFORM_ID;
	global $DEMO_PLATFORM_GROUP, $REAL_PLATFORM_GROUP;
	
	//$DEMO_PLATFORM_ID = "{065CFE2D-6AF9-E911-A2CD-005056B1E92B}";
	//$REAL_PLATFORM_ID = "{F93F6066-6AF9-E911-A2CD-005056B1E92B}";
	
	$DEMO_PLATFORM_ID = "{6f6cd207-1ede-ea11-a2d2-005056b13240}";	
	$REAL_PLATFORM_ID = "{7afc3359-1bde-ea11-a2d2-005056b13240}";
	
	if($buName == 'Midaswms.com')
	{
		$OWNER_USER_ID = "{bcad260a-68f9-e911-a2cd-005056b1e92b}";
		$BUSINESS_UNIT_NAME = "Midaswms.com";
		

		
	}
	
	elseif($buName == 'English')
	{
		$OWNER_USER_ID = "{B4B54210-68F9-E911-A2CD-005056B1E92B}";
		$BUSINESS_UNIT_NAME = "English";
	
	}
	
	elseif($buName == "Italian")
	{
		$OWNER_USER_ID = "{314DBD2F-68F9-E911-A2CD-005056B1E92B}";
		$BUSINESS_UNIT_NAME = "Italian";
	
	}
	elseif($buName == "Australia")
	{
		$OWNER_USER_ID = "{7ba2e640-1278-ea11-a2ce-005056b10e15}";
		$BUSINESS_UNIT_NAME = "Australia";

	}
	elseif($buName == "EN-Australia")
	{
		$OWNER_USER_ID = "{0f0dcf4b-1378-ea11-a2ce-005056b10e15}";
		$BUSINESS_UNIT_NAME = "EN-Australia";

	}
	elseif($buName == "Conversion")
	{
		$OWNER_USER_ID = "{832da8c4-bed0-ea11-a2d2-005056b1b8b7}";
		$BUSINESS_UNIT_NAME = "Conversion";

	}
	elseif($buName == "Conversion english")
	{
		$OWNER_USER_ID = "{5f936502-c7d0-ea11-a2d2-005056b1b8b7}";
		$BUSINESS_UNIT_NAME = "Conversion english";

	}
	
	else
	{
		
		$OWNER_USER_ID = "{26231fe5-67f9-e911-a2cd-005056b1e92b}";
		$BUSINESS_UNIT_NAME = "Digitalmedia";

	}
	
	
	
	
	
	if($curr == '0' || $curr == 'USD')
	{
		$DEMO_PLATFORM_GROUP = "demoMISTDVARUSD";
		$REAL_PLATFORM_GROUP = "MIREGSTDVARUSD";
		
	}
	
	elseif($curr == '1' || $curr == 'EUR' || $curr == 'EURO')
	{
		$DEMO_PLATFORM_GROUP = "demoMISTDVAREUR";
		$REAL_PLATFORM_GROUP = "MIREGSTDVAREUR";
		
	}
	
	elseif($curr == '2' || $curr == 'GBP')
	{
		$DEMO_PLATFORM_GROUP = "demoMISTDVARGBP";
		$REAL_PLATFORM_GROUP = "MIREGSTDVARGBP";
		
	}
	
	
	
}



function getBUDetailsCreateRealAcc($code,$curr,$accType,$platform)
{
	
	global  $OWNER_USER_ID, $BUSINESS_UNIT_NAME, $CRM_LOGIN, $CRM_PASSWORD, $DEMO_PLATFORM_ID, $REAL_PLATFORM_ID;
	global $DEMO_PLATFORM_GROUP, $REAL_PLATFORM_GROUP;
	
	//$DEMO_PLATFORM_ID = "{065CFE2D-6AF9-E911-A2CD-005056B1E92B}";
	//$REAL_PLATFORM_ID = "{F93F6066-6AF9-E911-A2CD-005056B1E92B}";
	
	$DEMO_PLATFORM_ID = "{6f6cd207-1ede-ea11-a2d2-005056b13240}";
	$REAL_PLATFORM_ID = "{7afc3359-1bde-ea11-a2d2-005056b13240}";
	
	if($code == 'en' || $code == 'English' )
	{
	//	$OWNER_USER_ID = "{B4B54210-68F9-E911-A2CD-005056B1E92B}";
	//	$BUSINESS_UNIT_NAME = "English";
		
        $OWNER_USER_ID = "{5f936502-c7d0-ea11-a2d2-005056b1b8b7}";
		$BUSINESS_UNIT_NAME = "Conversion english";
		
		
	}
	elseif($code == 'it' || $code == "Italian")
	{
		$OWNER_USER_ID = "{314DBD2F-68F9-E911-A2CD-005056B1E92B}";
		$BUSINESS_UNIT_NAME = "Italian";
		


		
	}
	else	
	{
		
		$OWNER_USER_ID = "{bcad260a-68f9-e911-a2cd-005056b1e92b}";
		$BUSINESS_UNIT_NAME = "Midaswms.com";
		

		
		
	}
	
	
	if($curr == '0' || $curr == 'USD')
	{
		$DEMO_PLATFORM_GROUP = "demoMISTDVARUSD";
		$REAL_PLATFORM_GROUP = "MIREGSTDVARUSD";
		
	}
	
	elseif($curr == '1' || $curr == 'EUR' || $curr == 'EURO')
	{
		$DEMO_PLATFORM_GROUP = "demoMISTDVAREUR";
		$REAL_PLATFORM_GROUP = "MIREGSTDVAREUR";
		
	}
	
	elseif($curr == '2' || $curr == 'GBP')
	{
		$DEMO_PLATFORM_GROUP = "demoMISTDVARGBP";
		$REAL_PLATFORM_GROUP = "MIREGSTDVARGBP";
		
	}
	
	
}




//sachu :encry&decry function
function my_simple_crypt( $string, $action = 'e' ) {
	$secret_key = 'MY_MIDAS_KEY';
	$secret_iv = 'MY_MIDAS_KEY';
	$output = false;
	$encrypt_method = "AES-256-CBC";
	$key = hash( 'sha256', $secret_key );
	$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
	
	if( $action == 'e' ) {
		$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
	}
	else if( $action == 'd' ){
		$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
	}
	
	return $output;
}
//end



function get_acc_count($email)
{
    global $OWNER_USER_ID, $BUSINESS_UNIT_NAME, $CRM_LOGIN, $CRM_PASSWORD;
    getParentBUDetails();
    $request1 = new CrmGetAccountDetailsModel();
    $request1->email = $email;
    $request1->organizationName = ORGANIZATION_NAME;
    $request1->ownerUserId =$OWNER_USER_ID;
    $request1->businessUnitName =$BUSINESS_UNIT_NAME;
    $request1->accountDetailsRequestFilterType ="2";
    
    //fwrite($myfile,"req[".date('Y-m-d H:i:s')."] = ".json_encode($request1)."\n\n");
    
    
    
    //print"<pre>"; print_r($request1); print"</pre>";
    
    $url = api_url."/GetAccountDetails";
    
    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
    curl_setopt ( $ch, CURLOPT_POST, TRUE );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request1));
    $curl_result = curl_exec ( $ch ); //getting response
    $curl_info = curl_getinfo ( $ch );
    curl_close ( $ch );
    
    
    
    $json_result1 = json_decode ($curl_result);
    
    /*
     $log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
     "Res_exist_email: ".json_encode($json_result1).PHP_EOL
     ."-------------------------".PHP_EOL;
     file_put_contents(logger_url, $log. "\n", FILE_APPEND);
     */
    
    if($json_result1->accountsInfo[0]->tradingPlatformAccounts)
    {
        $count = count($json_result1->accountsInfo[0]->tradingPlatformAccounts);
    }
    else
    {
        $count ='0';
    }
    return $count;
}


function get_country_iso3($userId) {
	
	//the database functions can not be called from within the helper
	//so we have to explicitly load the functions we need in to an object
	//that I will call ci. then we use that to access the regular stuff.
	$ci=& get_instance();
	$ci->load->database();
	
	//select the required fields from the database
	$ci->db->select('*');
	
	//tell the db class the criteria
	$ci->db->where('iso2', $userId);
	
	//supply the table name and get the data
	$row = $ci->db->get('countries_country')->row();
	
	//get the full name by concatinating the first and last names
	$fullName = $row->iso3;
	
	// return the full name;
	return $fullName;
	
}


function send_to_fwscrm($crmurl,$request,$methodname){
	
	
	
	
	$url = $crmurl;
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
	
	//print"<pre>"; print_r($json_result1); print"</pre>";
	$log  = "ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
	$methodname." RESPONSE: ".json_encode($json_result).PHP_EOL
	."-------------------------".PHP_EOL;
	file_put_contents(logger_url, $log. "\n", FILE_APPEND);
	
	
	
	return $json_result;
	
	
	
	
	
}

function send_to_Mcrm($crmurl,$request,$methodname){

	$url = $crmurl;
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch, CURLOPT_POST, TRUE );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request));
	$curl_result = curl_exec ( $ch ); //getting response
	$curl_info = curl_getinfo ( $ch );
	$curl_error = curl_error ( $ch ); 
	curl_close ( $ch );
	
	$json_result = json_decode ($curl_result); //decode response in neat format
	
	$http_code = $curl_info['http_code'];
	$CombinedResult = array();
	$CombinedResult['curl_info'] = $curl_info;
	$CombinedResult['http_code'] = $http_code;
	$CombinedResult['json_result'] = $json_result; //decode response in neat format;
	$CombinedResult['curl_error'] = $curl_error;
	
	return $CombinedResult;
}

function main_transaction_id()
{
    $rand_number = rand (100000,999999);
    $date2 = new DateTime ();
	$unique = uniqid();
    $trans_refNum = $rand_number . $date2->getTimestamp();
	$main_transaction_id = $trans_refNum."-".$rand_number."-".$unique; 
	return $main_transaction_id;
}

function get_acc_details(){
	
	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
	
	getParentBUDetails();
	
	$request1 = new CrmGetAccountDetailsModel();
	$request1->tradingPlatformAccountName =$_SESSION['username'];
	$request1->organizationName = ORGANIZATION_NAME;
	$request1->ownerUserId =$OWNER_USER_ID;
	$request1->businessUnitName =$BUSINESS_UNIT_NAME;
	$request1->accountDetailsRequestFilterType ="3";
	
	
	//print"<pre>"; print_r($request1); print"</pre>";
	$log  = "ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
	"GET Account DEMO REQUEST: ".json_encode($request1).PHP_EOL
	."-------------------------".PHP_EOL;
	file_put_contents(logger_url, $log. "\n", FILE_APPEND);
	
	
	$url = api_url."/GetAccountDetails";
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch, CURLOPT_POST, TRUE );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request1));
	$curl_result1 = curl_exec ( $ch ); //getting response
	$curl_info = curl_getinfo ( $ch );
	curl_close ( $ch );
	
	$json_result1 = json_decode ($curl_result1); //decode response in neat format
	
	//print"<pre>"; print_r($json_result1); print"</pre>";
	$log  = "ip:".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
	"GET Account DEMO RESPONSE: ".json_encode($json_result1).PHP_EOL
	."-------------------------".PHP_EOL;
	file_put_contents(logger_url, $log. "\n", FILE_APPEND);
	
	
	$resultcode = $json_result1->result->code;
	
	
	
	return $json_result1;
	
	
	
	
	
}

function get_all_acc_details(){
	
	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
	
	getParentBUDetails();
	
	$request1 = new CrmGetAccountDetailsModel();
	$request1->tradingPlatformAccountName =$_SESSION['username'];
	$request1->organizationName = ORGANIZATION_NAME;
	$request1->ownerUserId =$OWNER_USER_ID;
	$request1->businessUnitName =$BUSINESS_UNIT_NAME;
	$request1->accountDetailsRequestFilterType ="3";
	
	$url = api_url."/GetAllAccountDetails";
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch, CURLOPT_POST, TRUE );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request1));
	$curl_result1 = curl_exec ( $ch ); //getting response
	$curl_info = curl_getinfo ( $ch );
	curl_close ( $ch );
	
	$json_result1 = json_decode ($curl_result1); //decode response in neat format

	$resultcode = $json_result1->result->code;
	return $json_result1;
	
}

function get_country_iso2($userId) {
	
	//the database functions can not be called from within the helper
	//so we have to explicitly load the functions we need in to an object
	//that I will call ci. then we use that to access the regular stuff.
	$ci=& get_instance();
	$ci->load->database();
	
	//select the required fields from the database
	$ci->db->select('*');
	
	//tell the db class the criteria
	$ci->db->where('name', $userId);
	
	//supply the table name and get the data
	$row = $ci->db->get('countries_country')->row();
	
	//get the full name by concatinating the first and last names
	$fullName = $row->iso2;
	
	// return the full name;
	return $fullName;
	
}



function get_acc_details_forgot_password($username){
	
	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME,$REAL_PLATFORM_ID,$REAL_PLATFORM_GROUP;
	
	getParentBUDetails();
	
	$request1 = new CrmGetAccountDetailsModel();
	$request1->tradingPlatformAccountName =$username;
	$request1->organizationName = ORGANIZATION_NAME;
	$request1->ownerUserId =$OWNER_USER_ID;
	$request1->businessUnitName =$BUSINESS_UNIT_NAME;
	$request1->accountDetailsRequestFilterType ="3";
	
	$url = api_url."/GetAccountDetails";
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch, CURLOPT_POST, TRUE );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request1));
	$curl_result1 = curl_exec ( $ch ); //getting response
	$curl_info = curl_getinfo ( $ch );
	curl_close ( $ch );
	
	$json_result1 = json_decode ($curl_result1); //decode response in neat format
	
	$resultcode = $json_result1->result->code;
	
	return $json_result1;
	
}

function registration_exception_notification($request,$json_result,$http_code,$subj)
{
	
	//	$url = siteURL();
	$logo = wp_site_url . '/wp-content/uploads/2016/09/logo.png';
	
	$from='support@intivion.com';
	
	$to = 'errors@intivion.com';
	
	$subject = $subj;//"Real Registration Failed in ExxonFX";
	
	$headers = "From: " . $from . "\r\n";
	$headers .= "Reply-To: ". $from . "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	
	$message = '<html><body>';
	$message .= '<table class="main-table" cellpadding="0" cellspacing="0" style="width:90%; height: auto; margin:0 auto; text-align:left; border:2px solid #000;"><tbody>';
	$message .= '<tr><td style="background:#ecf0f1; padding:20px 35px; text-align:center;"><img alt="ExxonFX" src='.$logo.' width="auto" height="auto" /></a></td></tr>';
	$message .= "<tr><td style='padding:0 35px;'><p style='font-family: Arial, Helvetica, sans-serif; margin:30px 0 10px;font-size:18px; color:#000;'> Below are the details of user who tried to register, </p></td></tr>";
	$message .= "<tr><td style='padding:0 35px;'></td></tr>";
	$message .= "<tr><td style='padding:0 35px;'><p style='font-family: Arial, Helvetica, sans-serif; margin:10px 0;font-size:18px; color:#000;'><strong>Request </strong>: " . json_encode($request) ."</p></td></tr>";
	$message .= "<tr><td style='padding:0 35px;'><p style='font-family: Arial, Helvetica, sans-serif; margin:10px 0;font-size:18px; color:#000;'><strong>Response </strong> : " . json_encode($json_result) . "</p></td></tr>";
	$message .= "<tr><td style='padding:0 35px;'><p style='font-family: Arial, Helvetica, sans-serif; margin:10px 0;font-size:18px; color:#000;'><strong>Http Code </strong> : " . $http_code . "</p></td></tr>";
	$message .= "<tr><td style='padding:0 35px;'><p style='font-family: Arial, Helvetica, sans-serif; margin:10px 0;font-size:18px; color:#000;'><strong>Date </strong>: " . date('Y-m-j H:i:s')."</p></td></tr>";$message .= "</tbody></table>";
	$message .= "</body></html>";
	mail ( $to, $subject, $message, $headers );
	
	
}



function get_owning_bu($tp_account_id)
{
	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME, $CRM_LOGIN, $CRM_PASSWORD;
	getParentBUDetails();
	$request1 = new CrmGetAccountDetailsModel();
	$request1->tradingPlatformAccountName = $tp_account_id;
	$request1->organizationName = ORGANIZATION_NAME;
	$request1->ownerUserId =$OWNER_USER_ID;
	$request1->businessUnitName =$BUSINESS_UNIT_NAME;
	$request1->accountDetailsRequestFilterType ="3";
	
	$url = api_url."/GetAccountDetails";
	
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_USERAGENT, 'PHP Tester' );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch, CURLOPT_POST, TRUE );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, http_build_query ($request1));
	$curl_result = curl_exec ( $ch ); //getting response
	$curl_info = curl_getinfo ( $ch );
	curl_close ( $ch );
	
	$json_result1 = json_decode ($curl_result); //decode response in neat format
	
	//print"<pre>"; print_r($json_result1); print"</pre>";
	
	$http_code = $curl_info['http_code'];
	$code=$json_result1->result->code;
	
	/////////////////////////////////////////////////////////
	//if($code=="0")
	//{
	$owningBusinessUnit= $json_result1->accountsInfo[0]->owningBusinessUnit;
	//}
	
	return $owningBusinessUnit;
}


function create_session($url)
{
	if (!function_exists('curl_init')){
		echo "Curl is not installed";
	}
	//	debug($url, "before create session");
	
	// OK cool - then let's create a new cURL resource handle
	$ch = curl_init();
	// Now set some options (most are optional)
	
	// Set URL to download
	curl_setopt($ch, CURLOPT_URL, $url);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	// Include header in result? (0 = yes, 1 = no)
	curl_setopt($ch, CURLOPT_HEADER, 0);
	
	// Should cURL return or print out the data? (true = return, false = print)
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	// Timeout in seconds
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	
	// Download the given URL, and return output
	$output = curl_exec($ch);
	
	// Close the cURL resource, and free system resources
	curl_close($ch);
	
	$array = json_decode($output, true);
	
	
	
	return $array['Data']['SessionID'];
}
function popup(){
	if(isset($_SESSION['pop_mes']))
	{ ?>
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
   <script type="text/javascript">
	$(document).ready(function() { 
		$('#success-popup').show();


	    $("#popupclose").click(function(){
		    $("#success-popup").hide();
	    });
	    
	});
	</script>

    <div class="success-msg spacebottom2x" id="success-popup" style="display:none;">
      <div class="row clearfix">
        <div class="col-md-12">
          <div class="msg-header">
            <button type="button" id="popupclose"  class="close-btn" data-dismiss="modal">×</button>
          </div>
          <div class="msg-body">
            <div class="message-box clearfix">
              <figure>
                <div id="pdsuccessmsg"><p><?php print $_SESSION['pop_mes']; ?></p>
	      <?php unset($_SESSION['pop_mes']);?></div>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </div>
                   

	<?php } 
}

function error_popup(){
	if(isset($_SESSION['error_pop_mes']))
	{ ?>
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> 
   <script type="text/javascript">
	$(document).ready(function() { 
		$('#error-popup').show();
		$("#popupclose2").click(function(){
		    $("#error-popup").hide();
	    });
	});
	</script>
	
    <div class="success-msg error-msg spacebottom2x" id="error-popup" style="display:none;">
      <div class="row clearfix">
        <div class="col-md-12">
          <div class="msg-header">
            <button type="button" id="popupclose2" class="close-btn" data-dismiss="modal">×</button>
          </div>
          <div class="msg-body">
            <div class="message-box clearfix">
              <figure>
                <div id="pdfailedmsg"><p><?php print $_SESSION['error_pop_mes']; ?></p>
	      <?php unset($_SESSION['error_pop_mes']);?></div>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </div> 	
	
	<?php } 
}


 //BLOCKLIST

global $blocked_IPs;
$blocked_IPs= array('5.101.1.33','95.67.106.58','31.130.66.230','46.99.61.121','185.157.160.74','172.68.94.0','172.68.94.24','5.39.217.139','176.52.34.16','188.163.11.51','5.189.161.133');



//end 
function all_lang($uri_1)
{

	$CI =& get_instance();
	if($uri_1=="en")
	{
		$CI->lang->load('all_pages_lang','english');
		$CI->lang->load('form_validation','english');

	}
	
	elseif ($uri_1=="it")
	{
		
		$CI->lang->load('all_pages_lang','italian');
		
	}
	
	elseif ($uri_1=="de")
	{
		$CI->lang->load('all_pages_lang','german');
		
	}
	
	else
	{
		$CI->lang->load('all_pages_lang','english');
		$CI->lang->load('form_validation','english');	
	}
	
	
	

}
///////////////mt4 functions ////////////
function create_realmt4($firstname,$lastname,$password,$email,$acctype,$date,$platformtype,$currency,$country)
{
	try
	{
	$CreateUser= new MT4CreateUser();
	$CreateUser->name=$firstname." ".$lastname; // string
	$CreateUser->pwd=$password; // string
	$CreateUser->country=$country; // string
	//$CreateUser->state="Maharashtra"; // string
	//$CreateUser->addr="mumbai"; // string
	$CreateUser->email=$email;
	$CreateUser->comment="real"; // string
	if($acctype == "1")
	{
		$CreateUser->group="Standard";
	}
	elseif($acctype == "2")
	{
		$CreateUser->group="Classic";
	}
	elseif($acctype == "3")
	{
		$CreateUser->group="VIP";
	}
	elseif($acctype == "4")
	{
		$CreateUser->group="Standard";
	}
		
	//$CreateUser->group="sub-acc-demo";
	$CreateUser->balance="0"; // string
	$CreateUser->credit = "0";

	
	
		
	//print "<pre>";
	//print_r($CreateUser);
	//	print $cid;
	//print "</pre>";
		
	$MT4Service = new MT4Service(MT4_API_WSDL, array('location' => MT4_API_LOCATION, 'encoding'=>'UTF-8', 'cache_wsdl' => WSDL_CACHE_BOTH));
		
		
	$CompositeReturn = new MT4CreateUserResponse();
	$CompositeReturn = $MT4Service->MT4CreateUser($CreateUser);
		
	if($CompositeReturn->MT4CreateUserResult == "0")//not create user
	{
		return "error";
	}
	else
	{
			
		//print "<pre>";
		//print_r($CompositeReturn);
		//print "</pre>";
		$CI = get_instance();
		
		// You may need to load the model if it hasn't been pre-loaded
		$CI->load->model('real_model');
		$CI->load->model('user_model');
			
		$user=$CI->real_model->create_user($CompositeReturn->MT4CreateUserResult,$password,$platformtype);
			
		$cm_user=$CI->real_model->create_crmuser($CompositeReturn->MT4CreateUserResult,$password,$CreateUser->group,"NULL",$currency,"NULL","NULL",$date,$country);
			
			
		$_SESSION['BUSINESS_UNIT'] = $CreateUser->group;
	
			
		if($CI->user_model->resolve_user_login($CompositeReturn->MT4CreateUserResult, $password))
		{
			$user_id = $CI->user_model->get_user_id_from_username($CompositeReturn->MT4CreateUserResult);
			$user= $CI->user_model->get_user($user_id);
				
				
			if(is_object($user))
			{
				// set session user datas
				$_SESSION['user_id']      = (int)$user->id;
				$_SESSION['username']     = (string)$user->username;
				$_SESSION['logged_in']    = (bool)true;
				$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
				//$_SESSION['is_admin']     = (bool)$user->is_admin;
				$_SESSION['user_role']     = (string)$user->user_role;
					
				//$CI->load->view('traders_room/real_account/thankyou-real');
				
				return $CompositeReturn->MT4CreateUserResult;
				
			}
			
		}
	}
	}
	catch(Exception $e)
	{
		return $e;
		
	}
}
///////// login Mt4/////////////
function login_mt4_account($username,$password)
{
	$CI = get_instance();

	$CI->load->model('user_model');

	try
	{
		
		$ValidateUser= new MT4ValidateUser();
		$ValidateUser->userid=$username; // string
		$ValidateUser->pass=$password;

		$MT4Service = new MT4Service(MT4_API_WSDL, array('location' => MT4_API_LOCATION, 'encoding'=>'UTF-8', 'cache_wsdl' => WSDL_CACHE_BOTH));

		$ValidateUserResponse = new MT4ValidateUserResponse();
		$ValidateUserResponse = $MT4Service->MT4ValidateUser($ValidateUser);

		if($ValidateUserResponse->MT4ValidateUserResult =="0")
		{
			return "error";
			//return "INVALID Account No OR PASSWORD";
			 
		}
		else
		{
			$userrecord=get_user_mt4($username);
			
			
			print "<pre>";
			print_r($userrecord);
			print "</pre>";

			$user_id = $CI->user_model->get_user_id_from_username($username);
			$user    = $CI->user_model->get_user($user_id);

			if($user)
			{


				$data = array(
						'password' => $password,
				);
				$user_update=$CI->user_model->update_user($user_id,$data); // update user password

			}
			else
			{
				$create_user=$CI->user_model->create_user($username,$email,$password,$type);
				 


			}
			$delete_records=$CI->user_model->delete_crmuser($userrecord->email);


			$data = array(
					'name' =>$userrecord->name,
					'country'=>$userrecord->country,
					'trading_platform'=> "",
					'account_type'=>$account_type,
					'currency'=> $ccode,
					'platform'=>$trading_platform,
					'password'=>$password,
					'phone_country_code'=>$phonecountrycode,
					'phone'=>$phone,
					'birth_date'=>$date,
					'email' =>$email,
					'trading_platform_accountid'=>$acc_t->tradingPlatformID,
					'account_id'=>$acc_t->parentAccountID,
					'business_unit'=>$OwningBusinessUnit,
					'currency_code'=>$ccode,
					'name'=> $acc_t->name,
					'leverage'=>$userrecord->leverage,
					'currency_id'=>$ccode_id
					 
			);

			$sql=$CI->user_model->insert_crmuser($data);

			if($CI->user_model->resolve_user_login($username,$password))
			{
				$user_id = $CI->user_model->get_user_id_from_username($username);
				$user= $CI->user_model->get_user($user_id);

				if($user)
				{
					// set session user datas
					$_SESSION['user_id']      = (int)$user->id;
					$_SESSION['username']     = (string)$user->username;
					$_SESSION['logged_in']    = (bool)true;
					$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
					//$_SESSION['is_admin']     = (bool)$user->is_admin;
					$_SESSION['user_role']     = (string)$user->user_role;
					 
					redirect('dashboard');
					exit();
				}
			}

			 
		}


		///////////////////////////

	}
	catch (Exception $e) {

		return $e;
	}
}


////////////mt4 change password//////////////

function change_password_mt4($username,$old_password,$new_password)
{
	$CI = get_instance();
	$CI->load->model('user_model');
	$CI->load->model('change_password_model');
	
	try
	{
		$MT4ValidateUser= new MT4ValidateUser();
		$MT4ValidateUser->userid=$username; // string
		$MT4ValidateUser->pass=$old_password;
	
		$MT4Service = new MT4Service(MT4_API_WSDL, array('location' => MT4_API_LOCATION, 'encoding'=>'UTF-8', 'cache_wsdl' => WSDL_CACHE_BOTH));
		
		$MT4ValidateUserResponse = new MT4ValidateUserResponse();
		$MT4ValidateUserResponse = $MT4Service->MT4ValidateUser($MT4ValidateUser);
	
		
		
		if($MT4ValidateUserResponse->MT4ValidateUserResult =="0")
		{
			return "error";
			//drupal_set_message(t("ERROR!. Your password could not be changed sucessfully. Internal System Message = Old password is incorrect."),'error');
		}
		else
		{
			try {
				$MT4SetPass= new MT4SetPass();
				$MT4SetPass->userid=$username; // string
				$MT4SetPass->pass=$new_password;
	
				$MT4Service = new MT4Service(MT4_API_WSDL, array('location' => MT4_API_LOCATION, 'encoding'=>'UTF-8', 'cache_wsdl' => WSDL_CACHE_BOTH));
			
				$MT4SetPassResponse= new MT4SetPassResponse();
				$MT4SetPassResponse = $MT4Service->MT4SetPass($MT4SetPass);
				if($MT4SetPassResponse->MT4SetPassResult == "0")
				{
					return "error";
					//drupal_set_message(t("ERROR!. Your password could not be changed sucessfully."),'error');
				}
				else
				{
					
					$user_id = $CI->user_model->get_user_id_from_username($username);
					$user    = $CI->user_model->get_user($user_id);
					if($user)
					{
						$data = array(
								'password' => $new_password,
						);
						$user_update=$CI->user_model->update_user($user_id,$data); // update user password
						$cm_user=$CI->change_password_model->update_crmuser($username,$new_password);
					}
						

					//drupal_set_message(t("Password Changed Sucessfully"));
					return "success";
				}
			}
			catch (Exception $e)
			{
				return $e;
			}		
		}
	}
	catch (Exception $e) 
    {
		return $e;
	}
}

function sendRequest($url, array $requestFields)
{
	$curl = curl_init($url);

	curl_setopt_array($curl, array
			(
					CURLOPT_HEADER         => 0,
					CURLOPT_USERAGENT      => 'Zotapay-Client/1.0',
					CURLOPT_SSL_VERIFYHOST => 0,
					CURLOPT_SSL_VERIFYPEER => 0,
					CURLOPT_POST           => 1,
					CURLOPT_RETURNTRANSFER => 1
			));

	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestFields));

	$response = curl_exec($curl);

	if(curl_errno($curl))
	{
		$error_message  = 'Error occurred: ' . curl_error($curl);
		$error_code     = curl_errno($curl);
	}
	elseif(curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200)
	{
		$error_code     = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$error_message  = "Error occurred. HTTP code: '{$error_code}'";
	}

	curl_close($curl);

	if (!empty($error_message))
	{
		throw new RuntimeException($error_message, $error_code);
	}

	if(empty($response))
	{
		throw new RuntimeException('Host response is empty');
	}

	$responseFields = array();

	parse_str($response, $responseFields);

	return $responseFields;
}

///////personal details/////////


function personal_details_mt4($accid,$country_guid,$username)
{
	$CI = get_instance();
	
	// You may need to load the model if it hasn't been pre-loaded
	$CI->load->model('personal_details_model');
	$CI->load->model('user_model');
	
	try
	{

		$updateAccountDetailsRequest = new MT4UpdateUser();

		$updateAccountDetailsRequest->name = $CI->input->post('firstname')."".$CI->input->post('lastname');
		$updateAccountDetailsRequest->logincode = $username;
		//$updateAccountDetailsRequest->logincode = $accid;
		$updateAccountDetailsRequest->pwd = "";
		$updateAccountDetailsRequest->country = $country_guid;
		$updateAccountDetailsRequest->state = "";
		$updateAccountDetailsRequest->addr = $CI->input->post('address');
		$updateAccountDetailsRequest->email = $CI->input->post('email');
		$updateAccountDetailsRequest->balance = "";
		$updateAccountDetailsRequest->comment = "Personal Details Update";
		$updateAccountDetailsRequest->credit = "";
		$updateAccountDetailsRequest->group = $_SESSION['BUSINESS_UNIT'];

		$MT4Service = new MT4Service(MT4_API_WSDL, array('location' => MT4_API_LOCATION, 'encoding'=>'UTF-8', 'cache_wsdl' => WSDL_CACHE_BOTH));

		$UpdateUserResponse = new MT4UpdateUserResponse();
		$UpdateUserResponse = $MT4Service->MT4UpdateUser($updateAccountDetailsRequest);

		if($UpdateUserResponse->MT4UpdateUserResult=="0")//not update user
		{
			return "error";
		}

		else
		{
			$cm_user=$CI->personal_details_model->update_crmuser($_SESSION['username']);

             return "success";
		}

		//debug($updateAccountDetailsResponse);

	} 
	catch(Exception $e)
	{
		return $e;

	}

}

function get_balance($tp_account_id)
{
	
	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
	getParentBUDetails();
	$request = new CrmGetAccountBalanceModel();
	
	$request->tradingPlatformAccountName =$tp_account_id;
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
	
	
	$mgtapibalance_result = json_decode ($curl_result); //decode response in neat format
	
	//print"<pre>"; print_r($json_result1); print"</pre>";
	
	$http_code = $curl_info['http_code'];
	if($http_code == "201" || $http_code == "200") //if operation successful
	{
		//$balance = $mgtapibalance_result->balance;
		$balance = $mgtapibalance_result->equity;
		//}
	}
	else {
		$balance = "";
		
	}
	
	return $balance;
}

function get_balance2($tp_account_id)
{
	
	global $OWNER_USER_ID, $BUSINESS_UNIT_NAME;
	getParentBUDetails();
	$request = new CrmGetAccountBalanceModel();
	
	$request->tradingPlatformAccountName =$tp_account_id;
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
	
	
	$mgtapibalance_result = json_decode ($curl_result); //decode response in neat format
	
	$http_code = $curl_info['http_code'];
	if($http_code == "201" || $http_code == "200") //if operation successful
	{
		//$balance = $mgtapibalance_result->balance;
		$balance = $mgtapibalance_result->equity;
		//$balance = $mgtapibalance_result->margin; /*08-07-2020 Please don’t allow client to open withdraw request if they don’t have enough free margin  */
		//}
	}
	else {
		$balance = "";
		
	}
	
	return $balance;
}

function get_user_mt4($username)
{
	try 
    {
		$MT4GetUser= new MT4GetUser();
		$MT4GetUser->logincode=$username; // string

		$MT4Service = new MT4Service(MT4_API_WSDL, array('location' => MT4_API_LOCATION, 'encoding'=>'UTF-8', 'cache_wsdl' => WSDL_CACHE_BOTH));
	
		$UserRecord = new UserRecord();
		$UserRecord = $MT4Service->MT4GetUser($MT4GetUser);
			

		$GetUserResult=$UserRecord->MT4GetUserResult;
		$tparr2=array();
		if(is_object($GetUserResult)) {
			$tparr2[] =  $GetUserResult;
		}
		else
		{
			$tparr2 = $GetUserResult;
		}
	
		return $tparr2;
	
	}
	catch (Exception $e)
	{
	
		return $e;
	}
}



function date_dropdown($year_limit = 0){


	$html_output = '    <div name="dob" id="date_select"  >'."\n";
	$html_output .= ' '."\n";

	/*days*/
	$html_output .= '   <div class="col-xs-4 no-padding"><div class="select-box border-box"> <select name="date_day" class="form-control" id="day_select">'."\n"."<option value=>DD</option>\n";
	for ($day = 1; $day <= 31; $day++) {
		$html_output .= '               <option value="' . $day . '">' . $day . '</option>'."\n";
	}
	$html_output .= '           </select></div></div>'."\n";

	/*months*/
	$html_output .= '          <div class="col-xs-4 no-padding"><div class="select-box border-box">  <select name="date_month" class="form-control" id="month_select" >'."\n"."<option value=>MM</option>\n";
	$months = array("", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
	for ($month = 1; $month <= 12; $month++) {
		$html_output .= '               <option value="' . $month . '">' . $months[$month] . '</option>'."\n";
	}
	$html_output .= '           </select></div></div>'."\n";

	/*years*/
	$html_output .= '<div class="col-xs-4 no-padding"><div class="select-box border-box"> <select name="date_year" class="form-control" id="year_select" >'."\n"."<option value=>YYYY</option>\n";
	for ($year = 1950; $year <= (date("Y") - 18); $year++) {
		$html_output .= '               <option>' . $year . '</option>'."\n";
	}
	$html_output .= '           </select></div></div>'."\n";


	$html_output .= '   </div>'."\n";

	return $html_output;
}







function doc_issue_date_dropdown($year_limit = 0){
	
	
	$html_output = '    <div name="dob" id="date_select"  >'."\n";
	$html_output .= ' '."\n";
	
	/*days*/
	$html_output .= '          <div class="arrow-indicator select-arrow" > <select name="issue_date_day" class="form-control" id="issue_day_select" data-validation="required" data-validation-error-msg="Please Select Day">'."\n"."<option value=>DD</option>\n";
	for ($day = 1; $day <= 31; $day++) {
		$html_output .= '               <option value="' . $day . '">' . $day . '</option>'."\n";
	}
	$html_output .= '           </select></div>'."\n";
	
	/*months*/
	$html_output .= '          <div class="arrow-indicator arrow-indicator-middle select-arrow" >  <select name="issue_date_month" class="form-control" id="issue_month_select" data-validation="required" data-validation-error-msg="Please Select Month" >'."\n"."<option value=>MM</option>\n";
	$months = array("", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
	for ($month = 1; $month <= 12; $month++) {
		$html_output .= '               <option value="' . $month . '">' . $months[$month] . '</option>'."\n";
	}
	$html_output .= '           </select></div>'."\n";
	
	/*years*/
	$html_output .= '<div class="arrow-indicator" > <select name="issue_date_year" class="form-control" id="issue_year_select" data-validation="required" data-validation-error-msg="Please Select Year">'."\n"."<option value=>YYYY</option>\n";
	for ($year = 1914; $year <= (date("Y") - 0); $year++) {
		$html_output .= '               <option>' . $year . '</option>'."\n";
	}
	$html_output .= '           </select></div>'."\n";
	
	
	$html_output .= '   </div>'."\n";
	
	return $html_output;
}



function doc_expiry_date_dropdown($year_limit = 0){
	
	
	$html_output = '    <div name="dob" id="date_select"  >'."\n";
	$html_output .= ' '."\n";
	
	/*days*/
	$html_output .= '          <div class="arrow-indicator select-arrow" > <select name="exp_date_day" class="form-control" id="exp_day_select" data-validation="required" data-validation-error-msg="Please Select Day">'."\n"."<option value=>DD</option>\n";
	for ($day = 1; $day <= 31; $day++) {
		$html_output .= '               <option value="' . $day . '">' . $day . '</option>'."\n";
	}
	$html_output .= '           </select></div>'."\n";
	
	/*months*/
	$html_output .= '          <div class="arrow-indicator arrow-indicator-middle select-arrow" >  <select name="exp_date_month" class="form-control" id="exp_month_select" data-validation="required" data-validation-error-msg="Please Select Month" >'."\n"."<option value=>MM</option>\n";
	$months = array("", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
	for ($month = 1; $month <= 12; $month++) {
		$html_output .= '               <option value="' . $month . '">' . $months[$month] . '</option>'."\n";
	}
	$html_output .= '           </select></div>'."\n";
	
	/*years*/
	$html_output .= '<div class="arrow-indicator" > <select name="exp_date_year" class="form-control" id="exp_year_select" data-validation="required" data-validation-error-msg="Please Select Year">'."\n"."<option value=>YYYY</option>\n";
	for ($year = 1914; $year <= 2031; $year++) {
		$html_output .= '               <option>' . $year . '</option>'."\n";
	}
	$html_output .= '           </select></div>'."\n";
	
	
	$html_output .= '   </div>'."\n";
	
	return $html_output;
}





function dob_date_dropdown($year_limit = 0){
	
	
	$html_output = '    <div name="dob" id="date_select"  >'."\n";
	$html_output .= ' '."\n";
	
	/*days*/
	$html_output .= '          <div class="arrow-indicator select-arrow" > <select name="dob_date_day" class="form-control" id="dob_day_select" data-validation="required" data-validation-error-msg="Please Select Day">'."\n"."<option value=>DD</option>\n";
	for ($day = 1; $day <= 31; $day++) {
		$html_output .= '               <option value="' . $day . '">' . $day . '</option>'."\n";
	}
	$html_output .= '           </select></div>'."\n";
	
	/*months*/
	$html_output .= '          <div class="arrow-indicator arrow-indicator-middle select-arrow" >  <select name="dob_date_month" class="form-control" id="dob_month_select" data-validation="required" data-validation-error-msg="Please Select Month" >'."\n"."<option value=>MM</option>\n";
	$months = array("", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
	for ($month = 1; $month <= 12; $month++) {
		$html_output .= '               <option value="' . $month . '">' . $months[$month] . '</option>'."\n";
	}
	$html_output .= '           </select></div>'."\n";
	
	/*years*/
	$html_output .= '<div class="arrow-indicator" > <select name="dob_date_year" class="form-control" id="dob_year_select" data-validation="required" data-validation-error-msg="Please Select Year">'."\n"."<option value=>YYYY</option>\n";
	for ($year = 1914; $year <= (date("Y") - 18); $year++) {
		$html_output .= '               <option>' . $year . '</option>'."\n";
	}
	$html_output .= '           </select></div>'."\n";
	
	
	$html_output .= '   </div>'."\n";
	
	return $html_output;
}








function get_client_ip() {
	

$ipaddress = '';
	if (isset($_SERVER['HTTP_CLIENT_IP']))
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_X_FORWARDED']))
				$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
				else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
					$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
					else if(isset($_SERVER['HTTP_FORWARDED']))
						$ipaddress = $_SERVER['HTTP_FORWARDED'];
						else if(isset($_SERVER['REMOTE_ADDR']))
							$ipaddress = $_SERVER['REMOTE_ADDR'];
							else
								$ipaddress = 'UNKNOWN';
								$ipaddress = explode(",",$ipaddress);
								return $ipaddress[0];
	
}

function get_country_full_name($userId) {
	//the database functions can not be called from within the helper
	//so we have to explicitly load the functions we need in to an object
	//that I will call ci. then we use that to access the regular stuff.
	$ci=& get_instance();
	$ci->load->database();

	//select the required fields from the database
	$ci->db->select('*');

	//tell the db class the criteria
	$ci->db->where('iso2', $userId);

	//supply the table name and get the data
	$row = $ci->db->get('countries_country')->row();

	//get the full name by concatinating the first and last names
	$fullName = $row->name;

	// return the full name;
	return $fullName;

}



function get_country_details() {
 
	// $details=$_SERVER["HTTP_CLOUDFRONT_VIEWER_COUNTRY"];
	// $details = $_SERVER["HTTP_CF_IPCOUNTRY"];
	$details = file_get_contents('http://ip2location.forexwebsolution.com/location.php?ip='.get_client_ip());
	return $details;
}

function getRandomWord($len = 5) {
	$word = array_merge ( range ( '0', '9' ) );
	shuffle ( $word );
	return substr ( implode ( $word ), 0, $len );
}

function s3_bucket_upload($source_file_Path, $destination_file_path)
{
	/*
	 $CI = & get_instance();
	 $bucketName = $CI->config->item('aws_s3_bucket_name');
	 $key = $CI->config->item('aws_s3_key');
	 $secret = $CI->config->item('aws_s3_secret');
	 */
	
	$bucketName = AWS_S3_BUCKET_NAME;
	$key = AWS_S3_BUCKET_KEY;
	$secret = AWS_S3_BUCKET_SECRET;
	
	$data = array();
	
	try {
		
		$s3Client = new S3($key, $secret);
		
		$uploadResult = $s3Client->putObjectFile($source_file_Path, $bucketName, $destination_file_path, S3::ACL_PUBLIC_READ);
		$log = "ip:" . $_SERVER ['REMOTE_ADDR'] . ' - ' . date ( "F j, Y, g:i a" ) . "[INFO]" . PHP_EOL . "S3 Response: " . json_encode ( $uploadResult ) . PHP_EOL . "-------------------------" . PHP_EOL;
		file_put_contents ( logger_url, $log . "\n", FILE_APPEND );
		
		if ($uploadResult) {
			$data['status'] = true;
		} else {
			$data['status'] = false;
			$data['error'] = "Unable to upload the file to S3.";
		}
		
		
	} catch (Exception $e) {
		$data['status'] = false;
		$data['error'] = $e->getMessage();
	}
	
	return $data;
}



