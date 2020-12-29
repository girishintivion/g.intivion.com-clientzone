<!-- Page Content  -->

<div class="container cntr-box">
  <div class="login-box container clearfix">
    <div class="row-flex clearfix">
      <div class="col-md-1 hidden-sm hidden-xs"></div>
      <div class="col-md-6 col-sm-7 col-xs-12">
        <div class="login-body extra-bg">
          <h1 class="main-heading text-center"><img class="img-responsive" src="<?= base_url('assets/images/login-icon.png')?>" alt="Login"> Forgot Password</h1>
          <div class="clearfix" id="login_box">
            <form method="post" action="<?php echo base_url($this->uri->segment(1).'/forgot_password')?>" id="forgot_password_form" class="form-horizontal clearfix">
              <?php 	
$token = md5(uniqid(rand(), TRUE));
if(isset ($_SESSION['form_token_forgot_pass'])){
unset($_SESSION['form_token_forgot_pass']);}
$_SESSION['form_token_forgot_pass'] = $token;
?>
              <input type="hidden" name="my_token_forgot_pass" value="<?php echo $token;?>">
              <div class="row clearfix">
                <div class="col-xs-12">
                  <div class="form-group">
                    <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                      <input type="text" class="form-control" id="accname" name="accname" placeholder="<?php echo lang('Enter your account number'); ?>" >
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 forgot-pass-text">
                  <div class="form-group"><a class="transitions">Login</a></div>
                </div>
                <div class="col-xs-12">
                  <div class="full-width">
                    <button type="submit" id="forgot_pass_submit" class="btn-login">Submit</button>

              <div class="page-loader" style="display:none;">
                      <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
              
                  </div>
                </div>
              </div>
            </form>
          </div>
          <?php if(isset($_SESSION['loginerror_pop_mes'])){?>
          <div class="success-msg error-msg" id="error-popuplogin">
            <div class="msg-header">
              <button type="button" id="popupclose2login" class="close-btn" data-dismiss="modal">×</button>
            </div>
            <div class="msg-body">
              <div class="message-box clearfix">
                <figure>
                  <div id="pdfailedmsglogin"> <?php print $_SESSION['loginerror_pop_mes']; ?>
                    </p>
                    <?php unset($_SESSION['loginerror_pop_mes']);?>
                  </div>
                </figure>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
      <div class="col-md-5 col-sm-5 col-xs-12 no-padding">
        <div class="info-box"> <a href="/"><img class="img-responsive aligncenter" src="<?= base_url('assets/images/logo.png')?>" alt="Logo" ></a>
          <h2 class="medium-heading spacetop2x">Dont' have an account?</h2>
          <div class="spacetop3x"> <a class="black-btn" href="<?php echo base_url($this->uri->segment(1).'/live_account_registration')?>">Sign Up</a> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://thrilleratplay.github.io/jquery-validation-bootstrap-tooltip/js/jquery.validate-1.14.0.min.js"></script> 
<script src="https://thrilleratplay.github.io/jquery-validation-bootstrap-tooltip/js/jquery-validate.bootstrap-tooltip.js"></script> 
<script>

$("#popupclose2login").click(function(){
    $("#error-popuplogin").hide();
});

function f1()
{
	    $('#login_box').hide();
	    $('#forgot_pass_box').show();

}
$.validator.addMethod("IsNumber", function(value, element) {
    return this.optional(element) || /^([0-9]{5,17})+$/i.test(value);
}, "Please provide above details");


$("#forgot_password_form").validate({
    rules: {
    	   accname: {
	                required: true,
	                IsNumber: true,
	                },
            },
    	
            messages: {

            	accname: "<?php echo lang('Please provide valid Account Number'); ?>",

          	}, 
          	
             tooltip_options: {
                thefield: { placement: 'bottom'  }
             },

             submitHandler: function (form) { 
         	    
                 $("#forgot_pass_submit").hide();
                 $(".page-loader").show();
                 form.submit();
             }

             
          });
</script> 
