<?php 

if (!empty($_GET['affid'])) {
	
	setcookie("affid",$_GET['affid'],time() + (86400 * 180),"/");
	
}

if (!empty($_GET['cxd'])) {
	
	setcookie("cxd",$_GET['cxd'],time() + (86400 * 180),"/");
	
}




?>
<!DOCTYPE HTML>
<html>
<head>
<title>Clientzone</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
<link rel="icon" href="<?= base_url('assets/images/favicon.ico')?>" type="image/x-icon">

<!--------------- Bootstrap CSS --------------->
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/css/datepicker.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/css/datatable.css')?>">
<!------------- Common Stylesheet ------------->
<link rel="stylesheet" href="<?= base_url('assets/css/style.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css')?>">
<!------------- Common JS ------------->
<script src="<?= base_url('assets/js/jquery.min.js')?>"></script>
</head>
<body>
<header class="site-header">
  <div class="container-fluid">
    <div class="row row-flex">
      <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 no-padding relative">
        <div class="logo"> <a href="/"><img class="aligncenter img-responsive" src="/wp-content/themes/website-theme/images/logo.png"></a></div>
        <div class="navbar-bars">
          <button type="button" id="sidebarCollapse" class="navbar-toggle"><i class="fa fa-bars" aria-hidden="true"></i></button>
        </div>
      </div>
      <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12 no-left-pad">
        <div class="top-header-right clearfix"> 
          <!--<div class="user-detail"><a href="#"><span><i class="fa fa-user-circle-o" aria-hidden="true"></i> User Name</span></a>
            <div class="user-dropdown"><span><a href="#!">Admin</a></span><span><a href="#!">Signout</a></span></div>
          </div>-->
          <?php if(strtolower($_SESSION['user_role']) =="real"){
		  $result_crm = get_all_acc_details();
		  $new_addpsp = $result_crm->getAllAccountDetails[0]->dynamicAttributeValue;
		  $lang_smallcase = strtolower($new_addpsp);
		  if ($lang_smallcase =="true" || $lang_smallcase=="yes")
		  {
		  ?>
          <div class="btn-wrap"><a class="cmn-btn" href="<?php echo base_url($this->uri->segment(1).'/deposit/options');?>">Deposit</a></div>
          <?php 
		  }
		  else
		  { ?>
			<div class="btn-wrap"><a class="cmn-btn" href="<?php echo base_url($this->uri->segment(1).'/cashier6');?>">Deposit</a></div>  
		 <?php }
		  }?>
          <div class="lang-wrap">
            <div class="swich-language">
              <div id="language-content" class="flag-dropdown">
                <div class="lang-dropdown-wrapper">
                  <div class="lang-dropdown">
                    <div class="drop-down">
                     <select  name="options" onchange="javascript:window.location.href='<?php echo base_url(); ?>'+this.value+'<?php echo substr($_SERVER['REQUEST_URI'],14); ?>'">
                        <option value="en" <?php if($this->uri->segment(1) == 'en') echo 'selected="selected"'; ?>style="background-image:url('<?= base_url('assets/images/flags/en.png')?>'); background-repeat:no-repeat;">EN</option>
                          <!--     <option value="it" <?php if($this->uri->segment(1) == 'it') echo 'selected="selected"'; ?>style="background-image:url('<?= base_url('assets/images/flags/it.png')?>'); background-repeat:no-repeat;">IT</option>
                    -->
                 <!--   <option value="ru" style="background-image:url('<?= base_url('assets/images/flags/ru.png')?>'); background-repeat:no-repeat;">RU</option>
                        <option value="ar" style="background-image:url('<?= base_url('assets/images/flags/sa.png')?>'); background-repeat:no-repeat;">AR</option>  --> 
                      </select>
                    </div>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
<!----- Header ends -----> 
<!----- Wrapper starts ----->
<div class="wrapper">
