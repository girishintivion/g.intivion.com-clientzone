<?php 
//defined('BASEPATH') OR exit('No direct script access allowed');
if (!$_SERVER['HTTP_REFERER'])
	redirect($this->uri->segment(1).'/dashboard');
?>

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
    <h1 class="lg-heading">
      <?php if(strtolower($_SESSION['user_role'])=='real'){?>
      Additional Real Account
      <?php }else{?>
      Switch To Real Account
      <?php }?>
    </h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
          <?php echo form_open('real-exist',array('class'=>'form-horizontal clearfix','method'=>'post','id'=>'realexistform'));?>
          <input type="hidden" name="my_token_real_exist" value="<?php echo $token;?>">
            <input type="hidden" class="form-control" name="firstname" value="<?php echo $result[0]->firstname; ?>" >
             <input type="hidden" class="form-control" name="lastname" value="<?php echo $result[0]->lastname; ?>" >
             <input type="hidden" class="form-control" name="email"value="<?php echo $result[0]->email; ?>" >
            <input type="hidden" class="form-control" name="country"  value="<?php echo $country_code; ?>" >
            <input type="hidden" class="form-control country-code" name="country_code" value="<?php echo $result[0]->phone_country_code; ?>" >
            <input type="hidden" class="form-control phone-number" value="<?php echo $result[0]->phone; ?>" name="phone" >
            <div class="row clearfix"> 
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="password_confirmation" id="password" placeholder="<?php echo lang('Password'); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-check-circle-o" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" name="password" id="confirm_password" placeholder="<?php echo lang('Confirm Password'); ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group row-flex">
                  <div class="col-md-4 col-sm-4 col-xs-12 no-padding">
                    <label>Account Currency</label>
                  </div>
                  <div class="col-md-8 col-sm-8 col-xs-12 no-padding">
                    <div class="btn-group currency-box clearfix" data-toggle="buttons">
                      <label class="btn select-btn active">
                        <input type="radio" name="currency" id="option1" value="USD" checked>
                        USD </label>
                      <label class="btn select-btn select-btn-middle">
                        <input type="radio" name="currency" id="option2" value="EUR">
                        EUR </label>
                      <label class="btn select-btn">
                        <input type="radio" name="currency" id="option3" value="GBP">
                        GBP </label>
                          
                    </div>
                  </div>
                </div>
              </div>
              
     <input type="hidden" name="platform" id="option11" value="mt4">
         <!--     
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group row-flex">
                  <div class="col-md-4 col-sm-4 col-xs-12 no-padding">
                    <label>Trading Platform</label>
                  </div>
                  <div class="col-md-8 col-sm-8 col-xs-12 no-padding">
                    <div class="btn-group currency-box clearfix" data-toggle="buttons">
                      <label class="btn select-btn active">
                        <input type="radio" name="platform" id="option11" value="Sirix" checked>
                        Sirix </label>
                   
                      
                    </div>
                  </div>
                </div>
              </div>
              -->  
              <div class="col-xs-12">
                <div class="form-group spacetop2x"> <span class="bold sm-text">Acceptance</span>
                  <div class="real-check spacetop1x">
                    <label>
                      <input type="checkbox" id="check1" name="check1" checked>
                      <span class="label-text">I declare that I have read, understood and accept the <a target="_blank" class="link transitions" href="<?php echo '/terms-conditions/';?>">Terms of conditions</a>, <a target="_blank" class="link transitions" href="<?php echo '/risk-disclosure/';?>">Risk disclosure</a> &amp; <a target="_blank" class="link transitions" href="<?php echo '/privacy-policy/';?>">Privacy policy</a>.</span> </label>
                  </div>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="btn-box spacetop1x text-right">
                  <button type="submit" class="btn-login" name="submit" id="additional_real_submit">Submit</button>

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
      </div>
      <!-- Right Sidebar Here -->
      <?php 
      require_once (APPPATH.'views/templates/right-sidebar.php'); 
            ?>
    </div>
  </div>
</div>
<script>
$(function() {

$(document).ready(function(){
           	    
 	function setcookie(cname, cvalue, exdays) {
 		  var d = new Date();
 		  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
 		  var expires = "expires="+d.toUTCString();
 		  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
 		}

    $("#popupclosear").click(function(){
	    $("#success-popupar").hide();
	    window.location.href='<?php echo base_url();?>';
    });
    
    $("#popupclose2ar").click(function(){
	    $("#error-popupar").hide();
    });
    
  	$.validator.addMethod("alphanumeric", function(value, element) {
          return this.optional(element) || /^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]{6,15}$/i.test(value);
      }, "Password must include 6-20 alphanumeric characters");

    
    $("#realexistform").validate({
        rules: {
 	          password_confirmation: {
	                required: true,
	              alphanumeric: true,
	            },
	         password: {
               required: true,
             alphanumeric: true,
             equalTo : '[name="password_confirmation"]'
           },
           platform: {
				 required: true,
				 },
           aware: {
				 required: true,
				 },
           check1: {
				 required: true,
				 },
        },
        messages: {
 	          password_confirmation: {
	                required: '<?php echo lang('This field is required.'); ?>',
	              alphanumeric: '<?php echo lang('Password must include 6-20 alphanumeric characters'); ?>',
	            },
	         password: {
	                required: '<?php echo lang('This field is required.'); ?>',
	              alphanumeric: '<?php echo lang('Password must include 6-20 alphanumeric characters'); ?>',
	              equalTo : '<?php echo lang('Please type the same password again.'); ?>'
	            },
	            platform: {
					 required: 'Required',
					 },
	            aware: {
					 required: 'Please accept terms and conditions',
					 },
	           check1: {
					 required: 'Please accept terms and conditions',
					 },
					 
	        	},
        tooltip_options: {
           thefield: { placement: 'right' }
        },

        submitHandler: function (form) { 
    	    
            $("#additional_real_submit").hide();
            $(".page-loader").show();
     //       form.submit();
var token = Math.random().toString().replace('0.', ''); 
	 $("input[type=hidden][name=my_token_real_exist]").val(token); 
	setcookie('form_token_real_exist',token,'180');
form.submit();
        }

        
     });


  });
});
    </script> 