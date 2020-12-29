<!-- Page Content  -->

<div class="cntr-box">
  <div class="login-box container clearfix">
    <div class="col-md-12 text-center">
      <div class="site-logo"> <a href="/"><img class="aligncenter img-responsive" src="<?= base_url('assets/images/login-logo.png')?>"></a> </div>
    </div>
    <div class="col-md-12">
      <div class="login-body">
        <div class="thank-top"><i class="fa fa-check-circle" aria-hidden="true"></i>
          <h1 class="login-heading">THANK YOU & WELCOME TO <span class="black-text"><?php echo website_name?></span></h1>
          <p>The below information will be used to access your trading account on the platform and the <strong><?php echo site_url();?></strong> Client Portal. <br class="hidden-sm hidden-xs"/>
            A copy of the login information has been sent to you by email for your convenience.</p>
        </div>
        <div class="clearfix">
          <p><strong>TRADING ACCOUNT INFORMATION (Note the below details) :</strong></p>
          <ul class="list">
       <!--      <li><strong>PLATFORM : </strong> <?php echo strtoupper($platform);?></li> -->
            <li><strong>LOGIN/ACCOUNT NO :</strong> <?php echo $login;?></li>
            <li><strong>PASSWORD :</strong> <?php echo $password;?></li>
            <li><strong>ACCOUNT TYPE :</strong> REAL</li>
          </ul>
        </div>
        <div class="row btn-box spacetop2x clearfix">
          <div class="col-md-6 col-sm-6 col-xs-6 col-xxs start-btn"><a target="_blank" href="<?php echo base_url($this->uri->segment(1).'/Webtrader');?>" class="btn-login transitions">Start Trading</a></div>
          <div class="col-md-6 col-sm-6 col-xs-6 col-xxs my-acnt-btn"><a href="<?php echo base_url($this->uri->segment(1).'/dashboard');?>" class="btn-login transitions">My account</a></div>
        </div>
      </div>
    </div>
  </div>
</div>
