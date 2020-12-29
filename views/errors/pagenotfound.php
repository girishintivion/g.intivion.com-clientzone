<?php defined('BASEPATH') OR exit('No direct script access allowed');

//require_once(APPPATH.'views/templates/header.php');

?>

<div class="container cntr-box">
  <div class="login-box clearfix">
    <div class="col-md-12 text-center">
      <div class="site-logo"> <a href="/"><img class="aligncenter img-responsive" src="/wp-content/themes/website-theme/images/logo.png"></a> </div>
    </div>
    <div class="col-md-12 text-center">
        <div class="login-body">
        <h3 class="login-heading"><i class="red fa fa-exclamation-triangle" aria-hidden="true"></i> <?php echo lang('PAGE NOT FOUND'); ?></h3>
          <div class="clearfix">
            <div class="error-pg not-found">
              <div class="message-box">
                <figure> <img class="img-responsive aligncenter" src="<?= base_url('assets/images/404.png')?>" alt="Page not found"></figure>
                <!--   <p class="spacetop2x"><?php echo lang('This page is no longer available on the site.');?><br class="hidden-xs">
                Please click <a href="<?= base_url($this->uri->segment(1)) ?>">here</a> to continue your valuable time. </p> -->
                <p class="spacetop2x"><?php echo lang('The page could not be found.'); ?><br class="hidden-xs">
                  <a href="<?= base_url($this->uri->segment(1)) ?>"><?php echo lang('Take me back to homepage.'); ?></a></p>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
