<?php 

?>
<div class="container cntr-box">
  <div class="login-box clearfix">
    <div class="col-md-12 text-center">
      <div class="site-logo"> <a href="/"><img class="aligncenter img-responsive" src="/wp-content/themes/website-theme/images/logo.png"></a> </div>
    </div>
	 <div class="col-md-12 text-center">
	     <div class="lang-wrap">
            <div class="swich-language">
              <div id="language-content" class="flag-dropdown">
                <div class="lang-dropdown-wrapper">
               
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>
          </div>
	 </div>
	
    <div class="col-md-12">
      <div class="login-body">
        <h1 class="login-heading" id="login_heading">ACCESS DENIED</h1>
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
        <div class="clearfix" id="login_box">

        </div>

      </div>
    </div>
  </div>
</div>
