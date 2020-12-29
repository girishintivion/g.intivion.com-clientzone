<?php
class Webtrader extends CI_Controller {

        public function __construct()
        {
                parent::__construct();

                $this->load->helper('url_helper');
                $this->load->model('webtrader_model');
                $this->load->helper(array('url'));
                $this->load->helper('prodconfig');

                $uri_1 = $this->uri->segment(1);
                all_lang($uri_1);
     
        }
		public function new()
		{
		
        	//redirect($this->uri->segment(1)."/dashboard");
           // exit (); 
        	
        	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
        	{
        	
        		$data = $this->webtrader_model->get_current_user_data($_GET['tpname']);
        		
        		 $user_roles   = $data[0]->trading_platform;
        		$user_email   = $data[0]->email;
        		$secret_data   = $data[0]->secret_data;
        		
        		// $user_email = urlencode ($user_email);
        		$language = $this->uri->segment(1);
        		
        		
        		if ($user_roles == "REAL") {
        				
        			$surl = "https://uk2-websrv1-midaswms.FOREXWEBSERVICES.COM/Authentication/CreateSession?UserID=" . $_GET['tpname']. "&Password=" . $secret_data . "&clientPlatformType=1";
        				
                  	$session_id = create_session ( $surl );
        				
     	        	$url = "https://digitalcmedia.sirixtrader.com/?sessionId=" . $session_id . "&autoLoginServerUrl=https://uk2-websrv1-midaswms.FOREXWEBSERVICES.COM";
        		
        		
        		} else if ($user_roles == "DEMO") {
        				
        			$surl = "https://uk2-demowebsrv3.forexwebservices.com/Authentication/CreateSession?UserID=" . $_GET['tpname'] . "&Password=" . $secret_data . "&clientPlatformType=1";
        				
        			$session_id = create_session ( $surl );
        				
        			$url = "https://digitalcmedia.sirixtrader.com/?sessionId=" . $session_id . "&autoLoginServerUrl=https://uk2-demowebsrv3.forexwebservices.com";
        		}
        		
        		    redirect ( $url );
        		    exit ();
        	

        	
        	}
        	else 
        	{
        		wp_redirect ( 'https://digitalcmedia.sirixtrader.com/' );  
        		exit ();
        		
        	}
        	
		}
		
        public function index()
        {
        	//redirect($this->uri->segment(1)."/dashboard");
           // exit (); 
        	
        	if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
        	{
        	
        		$data = $this->webtrader_model->get_current_user_data($_SESSION['username'] );
        		
        		 $user_roles   = $data[0]->trading_platform;
        		$user_email   = $data[0]->email;
        		$secret_data   = $data[0]->secret_data;
        		
        		// $user_email = urlencode ($user_email);
        		$language = $this->uri->segment(1);
        		
        		
        		if ($user_roles == "REAL") {
        				
        			$surl = "https://uk2-websrv1-midaswms.FOREXWEBSERVICES.COM/Authentication/CreateSession?UserID=" . $_SESSION['username'] . "&Password=" . $secret_data . "&clientPlatformType=1";
        				
                  	$session_id = create_session ( $surl );
        				
     	        	$url = "https://digitalcmedia.sirixtrader.com/?sessionId=" . $session_id . "&autoLoginServerUrl=https://uk2-websrv1-midaswms.FOREXWEBSERVICES.COM";
        		
        		
        		} else if ($user_roles == "DEMO") {
        				
        			$surl = "https://uk2-demowebsrv3.forexwebservices.com/Authentication/CreateSession?UserID=" . $_SESSION['username'] . "&Password=" . $secret_data . "&clientPlatformType=1";
        				
        			$session_id = create_session ( $surl );
        				
        			$url = "https://digitalcmedia.sirixtrader.com/?sessionId=" . $session_id . "&autoLoginServerUrl=https://uk2-demowebsrv3.forexwebservices.com";
        		}
        		
        		    redirect ( $url );
        		    exit ();
        	

        	
        	}
        	else 
        	{
        		wp_redirect ( 'https://digitalcmedia.sirixtrader.com/' );  
        		exit ();
        		
        	}
        	
        }
}