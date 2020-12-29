<?php
//require_once($_SERVER['DOCUMENT_ROOT'].'/PHPMailer/PHPMailerAutoload.php');
require_once('PHPMailer/PHPMailerAutoload.php');

class Contact extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('real_model');
		$this->load->model('demo_model');
		$this->load->helper('url_helper');
		$this->load->helper(array('url'));
		$this->load->model('deposite_model');
		//$this->load->library('CrmRealRequestModel');
		
		
		$this->load->helper('prodconfig');
		
		$uri_1 = $this->uri->segment(1);
		all_lang($uri_1);
		
	}
	
	public function aaw()
	{
		echo $logo = wp_site_url.'/wp-content/themes/website-theme/images/logo.png';
		//echo wp_site_url; 
		exit();
echo 	$details = $_SERVER["HTTP_CF_IPCOUNTRY"];
echo "aa";
 echo $details=$_SERVER["HTTP_CLOUDFRONT_VIEWER_COUNTRY"];
}

	public function captcha_contact()
	{
		if(isset ($_SESSION['vercode']))
		{
			unset($_SESSION['vercode']);
		}
		
		$ranStr = md5(uniqid(rand(), TRUE));
		$_SESSION["vercode"] = $ranStr;
		echo $ranStr;
		
	}
	
	public function countrychange()
	{
		$country_code = $_GET['q'];
		
		$pco_code = $this->real_model->get_phone_code($country_code);
		
		echo $data['pco'] =$pco_code['dialing_code'];
		
	}
	
	
	public function test()
	{
		$email = "krupa@intivion.com";
		$subject =  "Email Test";
		$message = "this is a mail testing email function on server";
		
		
		$sendMail = mail($email, $subject, $message);
		if($sendMail)
		{
			echo "Email Sent Successfully";
		}
		else
		
		{
			echo "Mail Failed";
		}
	}
	
	public function index()
	{
		
		$country_code = get_country_details();
		$country_name=get_country_full_name($country_code);
		
		$data['title'] = 'Contact Us';
		
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$country_code = get_country_details();
		$country_name=get_country_full_name($country_code);
		$data['country_name'] = $country_name;
		$data['country_code'] = $country_code;
		
		$pco_code = $this->real_model->get_phone_code($country_code);
		
		$data['pco'] =$pco_code['dialing_code'];
		$data['country'] = $this->real_model->get_countries();
		
		$data['language'] = $this->uri->segment(1);
		
		
		$this->form_validation->set_rules('firstname_support', 'firstname', 'trim|required');
		$this->form_validation->set_rules('phone_support', 'phone', 'trim|required');
		
		
		if($this->form_validation->run() === FALSE)
		{
			$this->load->view('templates/header', $data );
			$this->load->view('templates/left-sidebar', $data );
			$this->load->view('traders_room/contact/contact', $data);
			$this->load->view('templates/footer', $data );
		}
		
		else
		{
			
			
			$token = $this->input->post('my_token_support');
			if (isset($_COOKIE['form_token_support']))
			{
				if($_COOKIE['form_token_support'] != "null")
				{
					$cookie_token=$_COOKIE['form_token_support'];
					unset($_COOKIE['form_token_support']);
				}
			}if(!empty($token) == $cookie_token){
				$my_details=$this->deposite_model->get_details_from_crm_user($_SESSION['username']);
				$email = $my_details->email;
				$from_email = $email;
				
				$firstname = $this->input->post('firstname_support');
				$pco = $this->input->post('country_code_support');
				$phone = $this->input->post('phone_support');
				$call_time = $this->input->post('call_time_support');
				$name = $firstname;
				
				$request_array=array(
						'firstname'=>$firstname,
						'pco'=>$pco,
						'phone'=>$phone,
						'call_time'=>$call_time,
				);
				$log  = "ip:".get_client_ip().' - '.date("F j, Y, g:i a")."[INFO]".PHP_EOL.
				"contact REQUEST: ".json_encode($request_array).PHP_EOL
				."-------------------------".PHP_EOL;
				file_put_contents(logger_url, $log. "\n", FILE_APPEND);
				$mail = new PHPMailer;
				
				//	$mail->SMTPDebug = 3;
				
				$mail->isSMTP();
				
				$mail->Host = "smtp.gmail.com";
				
				$mail->SMTPAuth = true;
				
				$mail->Username = "crmfws@gmail.com";
				$mail->Password = "Google@101";
				
				$mail->SMTPSecure = "tls";
				
				$mail->Port = 587;
				
				$mail->From = $from_email;
				
				$mail->FromName = $name;
				
				
				$mail->addAddress('support@midaswms.com', website_name);
				//$mail->addAddress('support@b-trade.io', website_name);// $mail->addAddress('', website_name);
				
				$mail->isHTML(true);
				
				$mail->Subject= "Call me back request";
				
				$logo = wp_site_url.'/wp-content/themes/website-theme/images/logo.png';
				
				
				$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.website_name.' | The People&#039;s Platform</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href='.$logo.' />
		
</head>
		
<body style="margin:0 auto; text-align: center;">
<table class="main-table" cellpadding="0" cellspacing="0" style="width:70%; height: auto; margin:0 auto; text-align:left; border: 10px solid #10bf73;" >
  <tr>
    <td style="text-align: center; background: #fff none repeat scroll 0px 0px; padding: 15px;"><a href="'.wp_site_url.'" class="logo-holder"> <img src="'.$logo.'" alt="'.website_name.'" id="logo-image" width="220" height="85"> </a></td>
  </tr>
  <tr>
    <td style="background: #fff none repeat scroll 0% 0%; padding: 0px 35px; border-top: 10px solid #10bf73;"><h1 style="font-size: 30px; padding: 0px; margin: 30px 0px; font-family: museo_sans300, sans-serif !important; font-weight: 500; color:#10bf73; text-align: center;"></h1>
      <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Hello Admin,</p>
    		
      	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;"> '.$name.', has requested for call back. Below are the details :- </p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Email Id :  '.$from_email.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Phone : '.$pco.' '.$phone.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Call back at : '.$call_time.'</p>
         		
         <p style="color: #10bf73;font-size: 15px; padding: 0; margin:20px 0 5px; font-family: museo_sans300, sans-serif !important; font-weight:600;">Sincerely,</p>
         <p style="color: #000;font-size: 15px; padding: 0; margin:0 0 40px; font-family: museo_sans300, sans-serif !important;">'.website_name.' Support</p></td>
  </tr>
         		
         		
</table>
</body>
</html>
 			 ';
				$mail->WordWrap = 50;
				$mail->IsHTML(true);
				$mail->Body = $message;
				$mail->Send();
				// echo "<p id='pop_mes'>".lang("We will contact you shortly.")."</p>";
				
				//	 redirect ( $this->uri->segment ( 1 ) . '/Dashboard?status=success');
				
				
				//	$_SESSION['pop_mes'] = "Call me back request sent successfully.";
				///	echo "<p id='pop_mes'>Call me back request sent successfully.</p>";
				//	popup();
				$_SESSION['pop_mes'] = "Call me back request sent successfully.";
				redirect ( $this->uri->segment ( 1 ) . '/contact');
			}
			else{
				redirect ( $this->uri->segment ( 1 ) . '/contact/');
			}
		}
	}
	
	/*
	 public function contact_home_form()
	 {
	 
	 //print$_SESSION["vercode"]."=====".$_POST["captchacode1"];exit();
	 
	 if($_SESSION["vercode"] != "")
	 {
	 
	 if($_POST["captchacode1"] != "")
	 {
	 if($_SESSION["vercode"] == $_POST["captchacode1"])
	 {
	 
	 $firstname = $this->input->post('firstname_cont');
	 //	$lastname = $this->input->post('lastname');
	 $email = $this->input->post('email_cont');
	 $country = $this->input->post('country_cont');
	 $country_code = $this->input->post('country_code_cont');
	 $phone = $this->input->post('phone_cont');
	 $message_text = $this->input->post('message_cont');
	 
	 //	$name = $firstname." ".$lastname;
	 $phone_number = $country_code." ".$phone;
	 
	 
	 $mail = new PHPMailer;
	 
	 //	$mail->SMTPDebug = 3;
	 
	 $mail->isSMTP();
	 
	 $mail->Host = "smtp.gmail.com";
	 
	 $mail->SMTPAuth = true;
	 
	 $mail->Username = "crmfws@gmail.com";
	 $mail->Password = "Google101";
	 
	 $mail->SMTPSecure = "tls";
	 
	 $mail->Port = 587;
	 
	 $mail->From = "crmfws@gmail.com";
	 
	 $mail->FromName = "b-trade.io";
	 
	 $mail->addAddress('support@b-trade.io', 'midaswms');//  	$mail->addAddress($user_email, $user_firstname);
	 
	 
	 $mail->isHTML(true);
	 
	 $mail->Subject= "Request for contact";
	 
	 $logo = wp_site_url."/wp-content/themes/website-theme/images/logo.png";
	 $message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
	 <head>
	 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	 <title>Codexfx | The People&#039;s Platform</title>
	 <meta name="viewport" content="width=device-width, initial-scale=1">
	 <link rel="shortcut icon" href='.$logo.' />
	 
	 </head>
	 
	 <body style="margin:0 auto; text-align: center;">
	 <table class="main-table" cellpadding="0" cellspacing="0" style="width:70%; height: auto; margin:0 auto; text-align:left; border: 10px solid #10bf73;" >
	 <tr>
	 <td style="text-align: center; background: #fff none repeat scroll 0px 0px; padding: 15px;"><a href="'.wp_site_url.'" class="logo-holder"> <img src='.$logo.' alt="Image" id="logo-image" width="153" height="85"> </a></td>
	 </tr>
	 <tr>
	 <td style="background: #fff none repeat scroll 0% 0%; padding: 0px 35px; border-top: 10px solid #10bf73;"><h1 style="font-size: 30px; padding: 0px; margin: 30px 0px; font-family: museo_sans300, sans-serif !important; font-weight: 500; color:#10bf73; text-align: center;"></h1>
	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Hello Admin,</p>
	 
	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;"> '.$firstname.', has requested for contact. below are the details :- </p>
	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Email Id :  '.$email.'</p>
	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Phone : '.$this->input->post('real-countrycode-contact').$phone_number.'</p>
	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Country : '.$country.'</p>
	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Message : '.$message_text.'</p>
	 
	 <p style="color: #10bf73;font-size: 15px; padding: 0; margin:20px 0 5px; font-family: museo_sans300, sans-serif !important; font-weight:600;">Sincerely,</p>
	 <p style="color: #000;font-size: 15px; padding: 0; margin:0 0 40px; font-family: museo_sans300, sans-serif !important;">midaswms Support</p></td>
	 </tr>
	 
	 
	 </table>
	 </body>
	 </html>
	 ';
	 
	 
	 
	 $mail->WordWrap = 50;          // set word wrap to 50 characters
	 $mail->IsHTML(true);
	 $mail->Body = $message;
	 $mail->Send();
	 
	 //	echo "<p id='pop_mes'>".lang("Contact request sent successfully.")."</p>";
	 //	redirect('http://midaswms.com/?status=success/');
	 // Redirect browser
	 wp_redirect( wp_site_url."/?status=success");
	 exit;
	 // redirect(wp_site_url.'/thank-you/');
	 
	 
	 }else{
	 if($this->uri->segment(1) == 'en')
	 {
	 redirect(wp_site_url.'/');
	 }
	 else {
	 redirect(wp_site_url."/".$this->uri->segment(1).'/');
	 }
	 }
	 }else{
	 if($this->uri->segment(1) == 'en')
	 {
	 redirect(wp_site_url.'/');
	 }
	 else {
	 redirect(wp_site_url."/".$this->uri->segment(1).'/');
	 }
	 }
	 }else{
	 if($this->uri->segment(1) == 'en')
	 {
	 redirect(wp_site_url.'/');
	 }
	 else {
	 redirect(wp_site_url."/".$this->uri->segment(1).'/');
	 }
	 }
	 
	 
	 
	 
	 
	 }
	 */
	public function contact_request()
	{
		
	//	print $_SESSION["vercode"]."=". $_POST["captchacode02"];exit();
		
		if($_SESSION["vercode"] != "")
		{
			
			if($_POST["captchacode02"] != "")
			{
				if($_SESSION["vercode"] == $_POST["captchacode02"])
				{
					
					$firstname = $this->input->post('firstname_contact');
					//	$lastname = $this->input->post('lastname');
					$email = $this->input->post('email_contact');
					$country = $this->input->post('country_contact');
					$country_code = $this->input->post('countrycode_contact');
					$phone = $this->input->post('phone_contact');
					$message_text = $this->input->post('message_contact');
					
					
					$name = $firstname." ".$lastname;
					$phone_number = $country_code." ".$phone;
					
					//	$logo= base_url().'assets/images/logo.png';
					$logo = wp_site_url.'/wp-content/themes/website-theme/images/logo.png';
					$mail = new PHPMailer;
					
					//	$mail->SMTPDebug = 3;
					
					$mail->isSMTP();
					
					$mail->Host = "smtp.gmail.com";
					
					$mail->SMTPAuth = true;
					
					$mail->Username = "crmfws@gmail.com";
					$mail->Password = "Google@101";
					
					$mail->SMTPSecure = "tls";
					
					$mail->Port = 587;
					
					$mail->From = $email;
					
					$mail->FromName = $name;
					
					
					//$mail->addAddress('support@b-trade.io', 'midaswms');
					
					
					$mail->addAddress('ramabhopal91@gmail.com', 'b-trade');
					
					$mail->isHTML(true);
					
					$mail->Subject= "Contact Us Request";
					
					$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>midaswms | The People&#039;s Platform</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href='.$logo.' />
		
</head>
		
<body style="margin:0 auto; text-align: center;">
<table class="main-table" cellpadding="0" cellspacing="0" style="width:70%; height: auto; margin:0 auto; text-align:left; border: 10px solid #10bf73;" >
  <tr>
    <td style="text-align: center; background: #fff none repeat scroll 0px 0px; padding: 15px;"><a href="'.wp_site_url.'" class="logo-holder"> <img src=".$logo." alt="b-trade.io" id="logo-image" width="220" height="85"> </a></td>
  </tr>
  <tr>
    <td style="background: #fff none repeat scroll 0% 0%; padding: 0px 35px; border-top: 10px solid #10bf73;"><h1 style="font-size: 30px; padding: 0px; margin: 30px 0px; font-family: museo_sans300, sans-serif !important; font-weight: 500; color:#10bf73; text-align: center;"></h1>
      <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Hello Admin,</p>
    		
      	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;"> '.ucfirst($name).', has requested for contact. Below are the details :- </p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Email Id :  '.$email.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Phone : '.$phone_number.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Country : '.get_country_full_name($country).'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Message : '.$message_text.'</p>
         		
         <p style="color: #10bf73;font-size: 15px; padding: 0; margin:20px 0 5px; font-family: museo_sans300, sans-serif !important; font-weight:600;">Sincerely,</p>
         <p style="color: #000;font-size: 15px; padding: 0; margin:0 0 40px; font-family: museo_sans300, sans-serif !important;">'.website_name.' Support</p></td>
  </tr>
         		
         		
</table>
</body>
</html>
 			 ';
					
					
					$mail->WordWrap = 50;          // set word wrap to 50 characters
					$mail->IsHTML(true);
					$mail->Body = $message;
					$mail->Send();
					
					//	echo "<p id='pop_mes'>".lang("Contact request sent successfully.")."</p>";
					
					wp_redirect(wp_site_url."/contact-us/?status=success");
					exit;
					// redirect(wp_site_url.'/thank-you/');
					
					
				}else{
					redirect(wp_site_url.'/contact-us');
				}
			}else{
				redirect(wp_site_url.'/contact-us');
			}
		}else{
			redirect(wp_site_url.'/contact-us');
			
		}
		
	}
	 
	
	public function partnership_request()
	{
		
	//	print $_SESSION["vercode"]."=". $_POST["captchacode02"];exit();
		
		if($_SESSION["vercode"] != "")
		{
			
			if($_POST["captchacode02"] != "")
			{
				if($_SESSION["vercode"] == $_POST["captchacode02"])
				{
					
					$firstname = $this->input->post('firstname_contact');
					//	$lastname = $this->input->post('lastname');
					$email = $this->input->post('email_contact');
					$country = $this->input->post('country_contact');
					$country_code = $this->input->post('countrycode_contact');
					$phone = $this->input->post('phone_contact');
					$message_text = $this->input->post('message_contact');
					
					
					$name = $firstname." ".$lastname;
					$phone_number = $country_code." ".$phone;
					
					//	$logo= base_url().'assets/images/logo.png';
					$logo = wp_site_url.'/wp-content/themes/website-theme/images/logo.png';
					$mail = new PHPMailer;
					
					
					//	$mail->SMTPDebug = 3;
					
					$mail->isSMTP();
					
					$mail->Host = "smtp.gmail.com";
					
					$mail->SMTPAuth = true;
					
					$mail->Username = "crmfws@gmail.com";
					$mail->Password = "Google@101";
					
					$mail->SMTPSecure = "tls";
					
					$mail->Port = 587;
					
					$mail->From = $email;
					
					$mail->FromName = $name;
					
					
					//$mail->addAddress('support@b-trade.io', 'midaswms');
					
					
					$mail->addAddress('ramabhopal91@gmail.com', 'b-trade');
					
					$mail->isHTML(true);
					
					$mail->Subject= "Partnership Request";
					
					$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>midaswms | The People&#039;s Platform</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href='.$logo.' />
		
</head>
		
<body style="margin:0 auto; text-align: center;">
<table class="main-table" cellpadding="0" cellspacing="0" style="width:70%; height: auto; margin:0 auto; text-align:left; border: 10px solid #10bf73;" >
  <tr>
    <td style="text-align: center; background: #fff none repeat scroll 0px 0px; padding: 15px;"><a href="'.wp_site_url.'" class="logo-holder"> <img src='.$logo.' alt="b-trade.io" id="logo-image" width="220" height="85"> </a></td>
  </tr>
  <tr>
    <td style="background: #fff none repeat scroll 0% 0%; padding: 0px 35px; border-top: 10px solid #10bf73;"><h1 style="font-size: 30px; padding: 0px; margin: 30px 0px; font-family: museo_sans300, sans-serif !important; font-weight: 500; color:#10bf73; text-align: center;"></h1>
      <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Hello Admin,</p>
    		
      	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;"> '.ucfirst($name).', has requested for partnership program. Below are the details :- </p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Email Id :  '.$email.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Phone : '.$phone_number.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Country : '.get_country_full_name($country).'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#000;">Message : '.$message_text.'</p>
         		
         <p style="color: #10bf73;font-size: 15px; padding: 0; margin:20px 0 5px; font-family: museo_sans300, sans-serif !important; font-weight:600;">Sincerely,</p>
         <p style="color: #000;font-size: 15px; padding: 0; margin:0 0 40px; font-family: museo_sans300, sans-serif !important;">'.website_name.' Support</p></td>
  </tr>
         		
         		
</table>
</body>
</html>
 			 ';
					
					
					$mail->WordWrap = 50;          // set word wrap to 50 characters
					$mail->IsHTML(true);
					$mail->Body = $message;
					$mail->Send();
					
					//	echo "<p id='pop_mes'>".lang("Contact request sent successfully.")."</p>";
					
					wp_redirect(wp_site_url."/partnership/?status=success");
					exit;
					// redirect(wp_site_url.'/thank-you/');
					
					
				}else{
					redirect(wp_site_url.'/partnership');
				}
			}else{
				redirect(wp_site_url.'/partnership');
			}
		}else{
			redirect(wp_site_url.'/partnership');
			
		}
		
	}
	
	
	public function contact_page()
	{

		//print $_SESSION["vercode"]."=". $_POST["captchacode2"];exit();
		
		if($_SESSION["vercode"] != "")
		{
			
			if($_POST["captchacode03"] != "")
			{
			
				if($_SESSION["vercode"] == $_POST["captchacode03"])
				{
					
					
					
					$firstname = $this->input->post('firstname_home_contact');
					//	$lastname = $this->input->post('lastname');
					$email = $this->input->post('email_home_contact');
					$country = $this->input->post('country_home_contact');
					$country_code = $this->input->post('country_code_home_contact');
					$phone = $this->input->post('phone_home_contact');
					$message_text = $this->input->post('message_home_contact');
					
					
					$name = $firstname." ".$lastname;
					$phone_number = $country_code." ".$phone;
					
					//	$logo= base_url().'assets/images/logo.png';
					$logo = wp_site_url.'/wp-content/themes/website-theme/images/logo.png';
					$mail = new PHPMailer;
					
					//	$mail->SMTPDebug = 3;
					
					$mail->isSMTP();
					
					$mail->Host = "smtp.gmail.com";
					
					$mail->SMTPAuth = true;
					
					$mail->Username = "crmfws@gmail.com";
					$mail->Password = "Google101";
					
					$mail->SMTPSecure = "tls";
					
					$mail->Port = 587;
					
					$mail->From = $email;
					
					$mail->FromName = $name;
					
					$mail->addAddress('krupa.intivion@gmail.com', 'b-trade');//$mail->addAddress('support@b-trade.io', 'b-trade.io');//$mail->addAddress('support@b-trade.io', 'b-trade.io');
					
					
					$mail->isHTML(true);
					
					$mail->Subject= "Contact Us Request";
					
					$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>midaswms | The People&#039;s Platform</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href='.$logo.' />
		
</head>
		
<body style="margin:0 auto; text-align: center;">
<table class="main-table" cellpadding="0" cellspacing="0" style="width:70%; height: auto; margin:0 auto; text-align:left; border: 10px solid #10bf73;" >
  <tr>
    <td style="text-align: center; background: #fff none repeat scroll 0px 0px; padding: 15px;"><a href="'.wp_site_url.'" class="logo-holder"> <img src='.$logo.' alt="b-trade.io" id="logo-image" width="153" height="85"> </a></td>
  </tr>
  <tr>
    <td style="background: #fff none repeat scroll 0% 0%; padding: 0px 35px; border-top: 10px solid #10bf73;"><h1 style="font-size: 30px; padding: 0px; margin: 30px 0px; font-family: museo_sans300, sans-serif !important; font-weight: 500; color:#10bf73; text-align: center;"></h1>
      <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Hello Admin,</p>
    		
      	 <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;"> '.ucfirst($name).', has requested for contact. Below are the details :- </p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Email Id :  '.$email.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Phone : '.$phone_number.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Country : '.get_country_full_name($country).'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Message : '.$message_text.'</p>
         		
         <p style="color: #10bf73;font-size: 15px; padding: 0; margin:20px 0 5px; font-family: museo_sans300, sans-serif !important; font-weight:600;">Sincerely,</p>
         <p style="color: #000;font-size: 15px; padding: 0; margin:0 0 40px; font-family: museo_sans300, sans-serif !important;">'.website_name.' Support</p></td>
  </tr>
         		
         		
</table>
</body>
</html>
 			 ';
					
					
					$mail->WordWrap = 50;          // set word wrap to 50 characters
					$mail->IsHTML(true);
					$mail->Body = $message;
					$mail->Send();
					
					//	echo "<p id='pop_mes'>".lang("Contact request sent successfully.")."</p>";
					
					wp_redirect(wp_site_url."?status=contact_success");
					exit;
					// redirect(wp_site_url.'/thank-you/');
					
					
				}else{
					redirect(wp_site_url);
				}
			}else{
				redirect(wp_site_url);
			}
		}else{
			redirect(wp_site_url);
			
		}
		
		
	}
	
	
	public function refer_friend()
	{
		
		
		if($_SESSION["vercode"] != "")
		{
			
			if($_POST["captchacode212"] != "")
			{
				if($_SESSION["vercode"] == $_POST["captchacode212"])
				{
					
					
					
					$firstname = $this->input->post('firstname_refer');
					//	$lastname = $this->input->post('lastname');
					$email = $this->input->post('email_refer');
					$country = $this->input->post('country_refer');
					$country_code = $this->input->post('country_code_refer');
					$phone = $this->input->post('phone_refer');
					$acc_no = $this->input->post('acc_refer');
					
					//	$name = $firstname." ".$lastname;
					$phone_number = $country_code." ".$phone;
					
					
					$mail = new PHPMailer;
					
					//	$mail->SMTPDebug = 3;
					
					$mail->isSMTP();
					
					$mail->Host = "smtp.gmail.com";
					
					$mail->SMTPAuth = true;
					
					$mail->Username = "crmfws@gmail.com";
					$mail->Password = "Google101";
					
					$mail->SMTPSecure = "tls";
					
					$mail->Port = 587;
					
					$mail->From = "crmfws@gmail.com";
					
					$mail->FromName = "b-trade.io";
					
					$mail->addAddress('support@b-trade.io', 'b-trade.io');//  	$mail->addAddress($user_email, $user_firstname);
					
					$mail->isHTML(true);
					
					$mail->Subject= "Request for Refer a friend";
					
					//		$logo= base_url().'assets/images/logo.png';
					$logo = wp_site_url.'/wp-content/themes/website-theme/images/logo.png';
					$message = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Codexfx | The People&#039;s Platform</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href='.$logo.' />
		
</head>
		
<body style="margin:0 auto; text-align: center;">
<table class="main-table" cellpadding="0" cellspacing="0" style="width:70%; height: auto; margin:0 auto; text-align:left; border: 10px solid #10bf73;" >
  <tr>
    <td style="text-align: center; background: #fff none repeat scroll 0px 0px; padding: 15px;"><a href="'.wp_site_url.'" class="logo-holder"> <img src='.$logo.' alt="Image" id="logo-image" width="153" height="85"> </a></td>
  </tr>
  <tr>
    <td style="background: #fff none repeat scroll 0% 0%; padding: 0px 35px; border-top: 10px solid #10bf73;"><h1 style="font-size: 30px; padding: 0px; margin: 30px 0px; font-family: museo_sans300, sans-serif !important; font-weight: 500; color:#10bf73; text-align: center;"></h1>
      <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Hello Admin,</p>
    		
      	<p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;"> '.ucfirst($firstname).', has requested for refer a friend. below are the details :- </p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Email Id :  '.$email.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Phone : '.$phone_number.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Country : '.$country.'</p>
         <p style="font-family: museo_sans300, sans-serif !important; margin:25px 0 ;font-size:18px; color:#10bf73;">Account Number : '.$acc_no.'</p>
         		
         		
         <p style="color: #10bf73;font-size: 15px; padding: 0; margin:20px 0 5px; font-family: museo_sans300, sans-serif !important; font-weight:600;">Sincerely,</p>
         <p style="color: #000;font-size: 15px; padding: 0; margin:0 0 40px; font-family: museo_sans300, sans-serif !important;">'.website_name.' Support</p></td>
  </tr>
         		
         		
</table>
</body>
</html>
';
					
					
					
					$mail->WordWrap = 50;          // set word wrap to 50 characters
					$mail->IsHTML(true);
					$mail->Body = $message;
					$mail->Send();
					
					//	echo "<p id='pop_mes'>".lang("Contact request sent successfully.")."</p>";
					
					//		 redirect(wp_site_url.'/thank-you/');
					
					$location = wp_site_url."/recommend-a-friend/?status=success";
					wp_redirect( $location );
					exit;
					
					
				}else{
					if($this->uri->segment(1) == 'en')
					{
						redirect(wp_site_url.'/recommend-a-friend/');
					}
					else {
						redirect(wp_site_url."/".$this->uri->segment(1).'/recommend-a-friend/');
					}
				}
			}else{
				if($this->uri->segment(1) == 'en')
				{
					redirect(wp_site_url.'/recommend-a-friend/');
				}
				else {
					redirect(wp_site_url."/".$this->uri->segment(1).'/recommend-a-friend/');
				}
			}
		}else{
			if($this->uri->segment(1) == 'en')
			{
				redirect(wp_site_url.'/recommend-a-friend/');
			}
			else {
				redirect(wp_site_url."/".$this->uri->segment(1).'/recommend-a-friend/');
			}
		}
		
		
		
		
	}
	
	
	
} 