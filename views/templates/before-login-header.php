<?php 
if (!empty($_GET['affid'])) {
	
	setcookie("affid",$_GET['affid'],time() + (86400 * 180),"/");
	
}

if (!empty($_GET['cxd'])) {
	
	setcookie("cxd",$_GET['cxd'],time() + (86400 * 180),"/");
	
}
?><!DOCTYPE HTML>
<html>
<head>
<title>Clientzone</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico')?>" type="image/x-icon">
<link rel="icon" href="<?= base_url('assets/images/favicon.ico')?>" type="image/x-icon"> 
<!--------------- Bootstrap CSS --------------->
<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/css/datepicker.css')?>">
<!------------- Common Stylesheet ------------->
<link rel="stylesheet" href="<?= base_url('assets/css/style.css')?>">
<link rel="stylesheet" href="<?= base_url('assets/css/font-awesome.min.css')?>">
<!------------- Common JS ------------->
<script src="<?= base_url('assets/js/jquery.min.js')?>"></script>
</head>

<body class="before-login">
<!----- Wrapper starts ----->
<div class="login-section">
