<?php
class Pages extends CI_Controller {

        public function view($page = 'home')
        {
        	
        	if ( ! file_exists(APPPATH.'views/pages/'.$page.'.php'))
        	{
        		// Whoops, we don't have a page for that!
        		show_404();
        	}
        	
        	$data['title'] = ucfirst($page); // Capitalize the first letter
        	
        	$this->load->view('templates/header', $data);
        	$this->load->view('pages/'.$page, $data);
        	$this->load->view('templates/footer', $data);
        }
        
        
        public function test()
        {
            $email = "test0102@t2est.com";	
        	$cURLConnection = curl_init();
        	
        	curl_setopt($cURLConnection, CURLOPT_URL, 'https://doc-uploader.azurewebsites.net/api/auth/EmailWebhook/ACC015B9C0F1B6A831C399E269772661/'.$email);
        	curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        	
        	$phoneList = curl_exec($cURLConnection);
        	curl_close($cURLConnection);
        	
        	echo $phoneList;
        }
}