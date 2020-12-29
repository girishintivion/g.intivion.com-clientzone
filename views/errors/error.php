
<div class="container cntr-box">
  <div class="login-box register-box container clearfix">
    <div class="clearfix">
      <div class="login-body">
        <h1 class="medium-heading text-center"><i class="red fa fa-exclamation-triangle" aria-hidden="true"></i> ERROR</h1>
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
