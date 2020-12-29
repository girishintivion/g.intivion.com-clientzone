<div id="content">
  <div class="container-fluid">
    <?php 
if(isset($_SESSION['pop_mes']))
{
	popup();
}?>
    <div class="clearfix"></div>
    <?php 
if(isset($_SESSION['error_pop_mes']))
{
	error_popup();
}
?>
    <div class="clearfix"></div>
    <!-- <div class="col-md-12 spacetop2x" id="a" style="display:none;"> -->
    <h1 class="lg-heading">Deposit Using <?php echo $gateway;?></h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
        

 
            <iframe id="safe-ifrm" border=0 src="<?php echo $redirect_url; ?>" align="center" frameborder="0" width="100%" height="695px"></iframe>

  
    

        </div>
      </div>
      <!-- Right Sidebar Here -->
      <?php 
      require_once (APPPATH.'views/templates/right-sidebar.php'); 
            ?>
    </div>
  </div>
</div>
