<?php 
//defined('BASEPATH') OR exit('No direct script access allowed');
if (!$_SERVER['HTTP_REFERER'])
	redirect($this->uri->segment(1).'/dashboard');
?>

<div id="content">
  <div class="container-fluid">
    <div class="success-msg spacebottom2x" id="success-popupad" style="display:none;">
      <div class="row clearfix">
        <div class="col-md-12">
          <div class="msg-header">
            <button type="button" id="popupclosead"  class="close-btn" data-dismiss="modal">×</button>
          </div>
          <div class="msg-body">
            <div class="message-box clearfix">
              <figure>
                <div id="pdsuccessmsgad"></div>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="success-msg error-msg spacebottom2x" id="error-popupad" style="display:none;">
      <div class="row clearfix">
        <div class="col-md-12">
          <div class="msg-header">
            <button type="button" id="popupclose2ad" class="close-btn" data-dismiss="modal">×</button>
          </div>
          <div class="msg-body">
            <div class="message-box clearfix">
              <figure>
                <div id="pdfailedmsgad"></div>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <h1 class="lg-heading">Additional Demo Account</h1>
    <div class="main-content-wrap white-bg">
     <?php echo form_open('Demo-exist/update',array('class'=>'form-horizontal clearfix','method'=>'post','id'=>'demoexistform'));?>
      <input type="hidden" name="my_token_demo_exist" value="<?php echo $token;?>">
        <input type="hidden" class="form-control" name="firstname"  placeholder="Full Name"  value="<?php echo $result[0]->firstname; ?>" >
        <input type="hidden" class="form-control" name="email"  value="<?php echo $result[0]->email; ?>" >
        <input type="hidden" class="form-control" name="country"  value="<?php echo $country_code; ?>" >
        <input type="hidden" class="form-control country-code" name="country_code"  value="<?php echo $result[0]->phone_country_code; ?>" >
        <input type="hidden" class="form-control phone-number" value="<?php echo $result[0]->phone; ?>"name="phone" >
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
            <div class="row">
              <div class="form-group row-flex">
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <label>Account Currency</label>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="btn-group currency-box clearfix" data-toggle="buttons">
                    <label class="btn select-btn active">
                      <input type="radio"  name="currency" id="option1" value="USD" checked>
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
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="row">
              <div class="form-group row-flex">
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <label>Trading Platform</label>
                </div>
                <div class="col-md-8 col-sm-8 col-xs-12">
                  <div class="btn-group currency-box clearfix" data-toggle="buttons">
                    <label class="btn select-btn active">
                      <input type="radio"  name="platform" id="option11" value="Sirix" checked>
                      Sirix </label>
                    <!--    <label class="btn select-btn">
                    <input type="radio" name="platform" id="option33" value="MT4">
                    MT4 </label>  --> 
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <!--   <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="form-group">
            <div class="input-group"> <span class="input-group-addon"><i class="fa fa-television" aria-hidden="true"></i></span>
              <select class="form-control" name="platform" id="platform">
                <option selected value="">Platforms</option>
                <option value="Sirix">Sirix</option>
                <option value="MT4">MT4</option>
              </select>
            </div>
          </div>
        </div> -->
          <div class="col-xs-12">
            <div class="form-group spacetop2x"> <span class="bold sm-text">Acceptance</span>
              <div class="checkbox demo-check">
                <label>
                  <input type="checkbox" id="terms" name="terms" checked>
                  <span class="cr"><i class="cr-icon fa fa-check"></i></span> <span class="acceptance risk-para">By proceeding with this application, I agree to the demo platform <a target="_blank" class="link" href="<?php echo '/terms-conditions/';?>">terms of use</a>.</label>
              </div>
            </div>
          </div>
          <div class="col-xs-12">
            <div class="btn-box spacetop1x text-right">
              <button type="submit" class="btn-login" value="submit" name="submit" id="additional_demo_submit">Submit</button>

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

	$.validator.addMethod("alphanumeric", function(value, element) {
        return this.optional(element) || /^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]{6,15}$/i.test(value);
    }, "Password must include 6-20 alphanumeric characters");

    $("#popupclosead").click(function(){
	    $("#success-popupad").hide();
	    window.location.href='<?php echo base_url();?>';
    });

    $("#popupclose2ad").click(function(){
	    $("#error-popupad").hide();
    });
    
	 $("#demoexistform").validate({
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
		            terms: {
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
			     terms: {
			    	 required: 'Please accept terms and conditions',
					 },
        	},
         tooltip_options: {
            thefield: { placement: 'right' }
         },

         submitHandler: function (form) { 
     	    
             $("#additional_demo_submit").hide();
             $(".page-loader").show();
          //   form.submit();
var token = Math.random().toString().replace('0.', ''); 
 	 $("input[type=hidden][name=my_token_demo_exist]").val(token); 
 	setcookie('form_token_demo_exist',token,'180');
 	
           	$.ajax({
         		// url: 'trading_history_ajax.php',
         //		Cookies.set('name', 'value');
         		url:"<?= base_url($this->uri->segment(1).'/Demo_exist/update') ?>",
         	//	cache: false,
         	      type: "POST",
         	      data : $("#demoexistform").serialize(),
         	      dataType: "html",
         	    
         	     
         	     success: function(data) {
         	             $("#additional_demo_submit").show();
                          $(".page-loader").hide();
        // 	   alert(data);
         	//    $('#defaultview').hide();
         	    	 if(data.indexOf('pop_mes')  >= 0){
             	  //  	 console.log("match");
         	    		 $('#success-popupad').show();
         		    	 $('#pdsuccessmsgad').html(data);
         	    //		location.href = "<?= base_url($this->uri->segment(1).'/Demo_exist/thankyou') ?>"
         	    		}
         	    	 if(data.indexOf('pop_error_mes')  >= 0){
         	    		 $('#error-popupad').show();
         		    	 $('#pdfailedmsgad').html(data);
         	    		}
         	    	 
		
      			
         	    	
         	    //	 $('#loader').html(data);
         	     }
         	 });
         }

         
      });

  });
});
    </script> 