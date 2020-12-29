<!-- Page Content  -->

<div class="cntr-box">
  <div class="acnt-step-box container clearfix">
    <div class="row">
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
          <div class="account-top-frame">
            <p class="text-center"><a class="transitions" href="<?php echo base_url($this->uri->segment(1).'/login')?>">Already have an account?</a></p>
            <!--      <div class="btn-frame spacetop1x"><a class="white-btn transitions" href="<?php echo base_url($this->uri->segment(1).'/login')?>">LOG IN</a></div> -->
            <div class="account-links-box"><a class="acnt-link" href="<?php echo base_url($this->uri->segment(1).'/live-account-registration')?>">REAL</a> <a class="acnt-link active" href="<?php echo base_url($this->uri->segment(1).'/demo-account-registration')?>">DEMO</a></div>
          </div>
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
          <div class="clearfix">
          <?php echo form_open('demo-account-registration',array('class'=>'form-horizontal clearfix','method'=>'post','id'=>'demo_form'));?>
         
              <?php 	            
            $token = md5(uniqid(rand(), TRUE));            
            if(isset ($_SESSION['form_token_demo_step1']))
            {
            	unset($_SESSION['form_token_demo_step1']);
            }            
			$_SESSION['form_token_demo_step1'] = $token;	
			?>
              <input type="hidden" name="my_token_demo_step1" value="<?php echo $token;?>" >
              <div class="row clearfix">
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="form-group">
                    <input type="text" class="form-control" name="firstname" id="firstname" placeholder="First Name">
                  </div>
                    
                  <div class="form-group">
                    <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Last Name">
                  </div> 
                  
                   <div class="form-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                  </div>
                  
                  
                    
                        
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                  <div class="form-group">
                    <select class="form-control country_change" name="country" id="real-country"  onchange="changecode(this.value);">
                      <option value="<?php echo $country_code;?>" selected><?php echo $country_name;?></option>
                      <?php   
                           foreach ($country as $country_item)
                           {
                             echo '<option value="' . $country_item['iso2'] . '"';
                             echo '>' . $country_item['name'] . '</option>' . "\n";

				            }
                        ?>
                    </select>
                  </div>
                  
                    <div class="form-group">
                    <input type="text" class="form-control country-code" name="country_code" id="demo_countrycode" value="<?php echo $pco;?>" readonly>
                    <input type="text" class="form-control phone-number" id="phone" name="phone" placeholder="Phone number">
                  </div>
                  
                    <div class="form-group row-flex">
                    <div class="col-md-5 col-sm-5 col-xs-12 no-padding">
                      <label>Account Currency</label>
                    </div>
                    <div class="col-md-7 col-sm-7 col-xs-12 no-padding">
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
                <div class="col-md-4 col-sm-4 col-xs-12">
                  
               <div class="form-group">
                    <input type="password" class="form-control" name="password_confirmation" id="password" placeholder="Password">
                  </div>
                  
                  <div class="form-group">
                    <input type="password" class="form-control" name="password" id="confirm_password" placeholder="Confirm Password">
                  </div>
               
               <input type="hidden" name="platform" id="option11" value="mt4">   
       <!--  
        <div class="form-group row-flex">
              <div class="col-md-5 col-sm-5 col-xs-12 no-padding">
                <label>Trading Platform</label>
              </div>
              <div class="col-md-7 col-sm-7 col-xs-12 no-padding">
                <div class="btn-group currency-box clearfix" data-toggle="buttons">
                  <label class="btn select-btn active">
                    <input type="radio" name="platform" id="option11" value="Sirix" checked>
                    Sirix </label>
                
                  
                </div>
              </div>
            </div>
        --> 
                </div>
                <div class="clearfix"></div>
                <div class="col-xs-12 spacetop2x text-center">
                  <div class="form-group">
                    <div class="demo-check">
                      <label>
                        <input type="checkbox" name="check1"  id="check1" checked>
                        <span class="label-text"> By proceeding with this application, I agree to the demo platform <a href="/terms-conditions/" target="_blank">terms of use</a>.</span></label>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 spacetop1x text-center">
                  <button type="submit" name="demo_form_submit" id="demo_form_submit" class="btn-login transitions">OPEN DEMO ACCOUNT</button>

              <div class="page-loader" style="display:none;"> 
                     <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
              
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



<script>
/*
var value = $("#real-country").val();  

$(".country_change").change(function () 
	    {
    
  value = $("#real-country").val();
 //  alert(value);
	    });
	    */
function changecode(str) {

	 
	   $.ajax({
		   url : "<?= base_url($this->uri->segment(1).'/Live_account_registration/countrychange') ?>",
	    type : "get",
	    data : "q=" + str,
	    success : function(a) {
	    

	     $("#demo_countrycode").val(a);
	    }
	   });
	  };

	$(document).ready(function(){
	  $('.year18').datepicker({
		  endDate: '-18y'});
	  });
	  
$(function(){
$("#popupclose2dreg").click(function(){
    $("#error-popupdreg").hide();
});


	$.validator.addMethod("IsNumber", function(value, element) {
	value = value.trim();
        return this.optional(element) || /^([0-9]{5,17})+$/i.test(value);
    }, "Please provide above details");



		$.validator.addMethod("IsPhone", function(value, element) {
		value = value.trim();
	        return this.optional(element) || /^([0-9]{7,13})+$/i.test(value);
	    }, "Please provide above details");

	

    
		 $.validator.addMethod("IsFullName", function(value, element) {
		 value = value.trim();
	         return this.optional(element) || /^[a-zA-Z\u00E4\u00F6\u00FC\u00C4\u00D6\u00DC\u00df\u0600-\u06FF]+([a-zA-Z\u00E4\u00F6\u00FC\u00C4\u00D6\u00DC\u00df\u0600-\u06FF][ ]{0,1})*[a-zA-Z\u00E4\u00F6\u00FC\u00C4\u00D6\u00DC\u00df\u0600-\u06FF]$/i.test(value);
	     }, "Please provide above details");

		 $.validator.addMethod("IsEmail", function(value, element) {
		 value = value.trim();
	         return this.optional(element) || /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/i.test(value);
	     }, "Please provide above details");
		 

		    	$.validator.addMethod("alphanumeric", function(value, element) {
		            return this.optional(element) || /^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]{6,15}$/i.test(value);
		        }, "Password must include 6-20 alphanumeric characters");

		    	/*
		    	$.validator.addMethod("IsFullName", function(value, element) {
		    		  if (/^([a-zA-Z]{3,}\s[a-zA-z]{1,}?-?[a-zA-Z]{1,}\s?([a-zA-Z]{1,})?)/.test(value)) {
		    		    return true;
		    		  } else {
		    		    return false;
		    		  };
		    		}, 'Please enter your full name.');
	    		*/
		       $("#demo_form").validate({
		           rules: {
		        	   firstname: { 
			        	   required: true ,
			        //	   IsName: true,
			        	   IsFullName:true,
			        	   },

			        	   lastname: { 
				        	   required: true ,
				        //	   IsName: true,
				        	   IsFullName:true,
				        	   },
				        	   
			     //   	   lastname: { required: true ,IsName: true,},
			           email: { 
				        	   required: true,
				        	   IsEmail: true, 
				        	  
				        	   },

							     password_confirmation: {
						                required: true,
						    			alphanumeric: true,
						    	        rangelength: [6, 20]
						                },
						     password: {
						    	         required: true,
						    			 alphanumeric: true,
						    	         equalTo : '[name="password_confirmation"]'

						    	         },
						    	       
				    	 	     phone: {
				    	                required: true,
				    	             //   IsNumber: true,
				    	                IsPhone: true,
				    	           
				    	             //   rangelength: [12, 20],
				    	                 
				    	                },
						    	       
						    	      
						    	         
				 				       platform: {
				 					    	 required: true,
				 					    	},
				 				country: {
						 						required: true,
						 					 },						 	
						       currency: {
						    	 required: true,
						    	},
						    	platform: {
							    	 required: true,
							    	},						    	
								check1: {
								 required: true,
								// isselect: true,
								 },
		           },
		           tooltip_options: {
		                thefield: { placement: 'right' }
		             },
		           messages: {

		        	   firstname: "<?php echo lang('Please provide valid details'); ?>",
		       // 	   lastname: "<?php echo lang('Please provide valid details'); ?>",
		        	   lastname: "<?php echo lang('Please provide above details'); ?>",
		        	   email: "<?php echo lang('Please provide Valid Email'); ?>",
		        	   password_confirmation: "<?php echo lang('Password must include 6-20 alphanumeric characters'); ?>",
		        	   password: "<?php echo lang('Please type the same password again.'); ?>",		        	  	
		        	   phone: "<?php echo lang('Please provide above details'); ?>",	
		        	   country: "<?php echo lang('Please provide above details'); ?>",
		        	   country_code: "<?php echo lang('Please provide above details'); ?>",
		        	   currency: "<?php echo lang('Please provide above details'); ?>",
		        	   platform: "<?php echo lang('Please provide above details'); ?>",
		        	   check1: "<?php echo 'Please accept terms and conditions'; ?>",
		        	}, 
		
		           submitHandler: function (form) { 
		 
		               $("#demo_form_submit").hide();
		               $(".page-loader").show();
		               form.submit();
		           }

		           
		        });
});
</script> 
