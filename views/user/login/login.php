<!-- Page Content Nw3333q -->

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
	
    <div class="col-md-12">
      <div class="login-body">
        <h1 class="login-heading" id="login_heading">LOG IN TO YOUR ACCOUNT</h1>
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
         <?php echo form_open('Login',array('class'=>'form-horizontal clearfix','id'=>'login_form','method'=>'post'));?>
          <?php 	            
            $token = md5(uniqid(rand(), TRUE));            
            if(isset ($_SESSION['form_token_login']))
            {
            	unset($_SESSION['form_token_login']);
            }            
			$_SESSION['form_token_login'] = $token;	
			?>
            <input type="hidden" name="my_token_login" value="<?php echo $token;?>">
            <div class="row clearfix">
              <div class="col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="username" value="<?php echo $_COOKIE['acc_user'];?>" name="username" placeholder="Account Number">
                  </div>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-lock" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" id="password" value="<?php echo $_COOKIE['acc_pwd'];?>" name="password" placeholder="Password">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
                <div class="form-group">
                  <div class="form-check spacetop1x">
                    <label>
                      <input type="checkbox" id="remember_me" name="remember_me" >
                      <span class="label-text">Remember me</span></label>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-6 col-xxs forgot-pass-text"><a class="transitions" onclick="f1()">Forgot Password</a></div>
              <div class="clearfix"></div>
              
              <div class="page-loader" id="page-loader1"  style="display:none;">
             <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
           
         
              
              <div class="col-md-5 col-sm-5 col-xs-5 col-xxs spacetop1x">
                <button type="submit" id="login_submit" class="btn-login transitions">LOGIN</button>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-7 col-xxs new-accnt-txt"> <a class="transitions" href="<?php echo base_url($this->uri->segment(1).'/live-account-registration')?>">REGISTER NEW ACCOUNT</a> </div>
            </div>
          </form>
        </div>
        <div class="clearfix" id="forgot_pass_box" style="display:none;">
         <?php echo form_open('forgot-password',array('class'=>'form-horizontal clearfix','id'=>'forgot_password_form','method'=>'post'));?>
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
                    <input type="text" class="form-control" id="accname" name="accname" placeholder="Username">
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-sm-6 col-xs-6 col-xxs forgot-pass-text back-btn"><a class="transitions" onclick="f2()"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back to Login</a></div>
              <div class="clearfix"></div>
              
              <div class="page-loader" id="page-loader2"  style="display:none;">
       <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
              
              <div class="col-md-5 col-sm-5 col-xs-5 col-xxs spacetop1x">
                <button type="submit" id="forgot_pass_submit" class="btn-login transitions">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function f1()
{
	    $('#login_box').hide();
	    $('#forgot_pass_box').show();
	    $('#login_heading').text('FORGOT PASSWORD');
}
function f2()
{
	    $('#login_box').show();
	    $('#forgot_pass_box').hide();
$('#login_heading').text('LOG IN TO YOUR ACCOUNT');
}
$(function() {
	
	$("#popupcloselogin").click(function(){
	    $("#success-popuplogin").hide();
	});

	$("#popupclose2login").click(function(){
	    $("#error-popuplogin").hide();
	});

	
$.validator.addMethod("IsNumber", function(value, element) {
    return this.optional(element) || /^([0-9]{5,17})+$/i.test(value);
}, "Please provide above details");

$.validator.addMethod("alphanumeric", function(value, element) {
    return this.optional(element) || /^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]{6,15}$/i.test(value);
}, "Password must include 6-20 alphanumeric characters");

$("#login_form").validate({
    rules: {
    	username: {
	                required: true,
	                IsNumber: true,
	                },

	        password: {
 	             required: true,
 	            alphanumeric: true,
 	             },
 	                
            },

            tooltip_options: {
                thefield: { placement: 'right' }
             },
           
            messages: {

            	username: "<?php echo lang('Please provide valid Account Number'); ?>",
            	password: "<?php echo lang('Please provide valid Password'); ?>",

          	}, 
   

             submitHandler: function (form) { 
         	    
                 $("#login_submit").hide();
                 $("#page-loader1").show();
                 form.submit();
             }

             
          });

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
                 $("#page-loader2").show();
                 form.submit();
             }

             
          });
});
</script> 
