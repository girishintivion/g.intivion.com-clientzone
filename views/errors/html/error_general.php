<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<link rel="shortcut icon" href="<?php echo site_url().'assets/images/favicon.ico'?>" type="image/x-icon">
<link rel="icon" href="<?php echo site_url().'assets/images/favicon.ico'?>" type="image/x-icon">
<!--Bootstrap CSS-->
<link rel="stylesheet" href="<?php echo site_url().'assets/css/bootstrap.min.css'?>">
<link rel="stylesheet" href="<?php echo site_url().'assets/css/datepicker.css'?>">
<!--Common Stylesheet-->
<link rel="stylesheet" href="<?php echo site_url().'assets/css/style.css'?>">
<link rel="stylesheet" href="<?php echo site_url().'assets/css/font-awesome.min.css'?>">
</head>

<body class="before-login" style="text-align: center">
<!--Wrapper starts-->
<div class="login-section">

<div class="container cntr-box text-center" >
  <div class="login-box clearfix">
    <div class="col-md-12 text-center">
      <div class="site-logo"> <a href="/"><img class="aligncenter img-responsive" src="<?php echo site_url(); ?>/wp-content/themes/website-theme/images/logo.png" alt=""></a> </div>
    </div>
    <div class="col-md-12 text-center">
          <div class="content-wrapper-full">
        <h3>Access Denied</h3>
        <div class="content-wrapper-full">
          <div class="content-wrapper-full-inner pg-nt-found-inner">
            <!--<div class="heading-thank"> </div>-->
            <div class="page-not-found-content">
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pg-nt-img"></div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pg-nt-content">
                <p><?php echo $message; ?></p>
                <span class="link-div"> <a href="<?php echo site_url();?>" class="my-account-btn home-redirect-btn">Go to Home</a> </span> </div>
              <div style="clear:both"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
