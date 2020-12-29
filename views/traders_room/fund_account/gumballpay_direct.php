<?php

?>
<div class="container cntr-box">
  <div class="login-box gumball-pay clearfix">
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
      

      	<h1 class="login-heading" id="login_heading"><?php echo 'DEPOSIT Using GUMBALLPAY';?></h1>

      
        
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

<?php 
if(empty($redirect_url))
{
?>


         <?php echo form_open('',array('class'=>'form-horizontal clearfix','id'=>'cashier7_direct_form','method'=>'post'));?>
          <?php 	            
            $token = md5(uniqid(rand(), TRUE));            
            if(isset ($_SESSION['form_token_cashier7_backoffice']))
            {
            	unset($_SESSION['form_token_cashier7_backoffice']);
            }            
			$_SESSION['form_token_cashier7_backoffice'] = $token;	
			?>
            <input type="hidden" name="my_token_cashier7_backoffice" value="<?php echo $token;?>">
            <div class="row clearfix">
            
              <div class="col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="acc" name="acc" placeholder="Account Number">
                  </div>
                </div>
              </div>
              
              
                            <div class="col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount">
                  </div>
                </div>
              </div>
              
              
              
                            <div class="col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                  </div>
                </div>
              </div>
              
              
              
              
                            <div class="col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="city" name="city" placeholder="City">
                  </div>
                </div>
              </div>
              
			   <div class="col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="zip" name="zip" placeholder="Postal Code">
                  </div>
                </div>
              </div>
              
              
                             <div class="col-xs-12">
                <div class="form-group">             
				  <div class="input-group"> 
                  <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                     <select class="form-control" id="country" name="country" data-validation="required" data-validation-error-msg="<?php echo ("Please select Country");?>">
                      <option value="">Please select Country</option>
                      <?php   
                           foreach ($country as $country_item)
                           {
                             echo '<option value="' . $country_item['iso2'] . '"';
                             echo '>' . $country_item['name'] . '</option>' . "\n";

				            }
                        ?>
                    </select> 
                  </div>
                </div>
              </div>
              
              
                             <div class="col-xs-12 au_state_dv" style="display:none;">
                <div class="form-group">
                  
				  <div class="input-group"> 
                  <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                        <select class="form-control" id="au_state" name="au_state" data-validation="required" data-validation-error-msg="<?php echo ("Please select State");?>">
						<option value="">Please select State</option>
                      <option value="ACT">Australian Capital Territory (AU)</option>
                      <option value="NSW">New South Wales (AU)</option>
                      <option value="NT">Northern Territory (AU)</option>
                      <option value="QLD">Queensland (AU)</option>
                      <option value="SA">South Australia (AU)</option>
                      <option value="TAS">Tasmania (AU)</option>
                      <option value="VIC">Victoria (AU)</option>
                      <option value="WA">Western Australia (AU)</option>

     
          
                    </select> 
                  </div>
                </div>
              </div>
              
              
                             <div class="col-xs-12 ca_state_dv" style="display:none;">
                <div class="form-group">
                  
				  <div class="input-group"> 
                  <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                        <select class="form-control" id="ca_state" name="ca_state" data-validation="required" data-validation-error-msg="<?php echo ("Please select State");?>">

                  
<option value="">Please select State</option>
                      <option value="AB">Alberta (CA)</option>
                      <option value="BC">British Columbia (CA)</option>
                      <option value="MB">Manitoba (CA)</option>
                      <option value="NB">New Brunswick (CA)</option>
                      <option value="NL">Newfoundland and Labrador (CA)</option>
                      <option value="NT">Northwest Territories (CA)</option>
                      <option value="NS">Nova Scotia (CA)</option>
                      <option value="NU">Nunavut (CA)</option>
                      <option value="ON">Ontario (CA)</option>
                      <option value="PE">Prince Edward Island (CA)</option>
                      <option value="QC">Quebec (CA)</option>
                      <option value="SK">Saskatchewan (CA)</option>
                      <option value="YT">Yukon (CA)</option>
                      
                      
                                           
                      
          
                    </select> 
                  </div>
                </div>
              </div>
              
              
               <div class="col-xs-12 us_state_dv" style="display:none;">
                <div class="form-group">
                 
				  <div class="input-group"> 
                  <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                                        <select class="form-control" id="us_state" name="us_state" data-validation="required" data-validation-error-msg="<?php echo ("Please select State");?>">

<option value="">Please select State</option>
                                            <option value="AL">Alabama (US)</option>
                      <option value="AK">Alaska (US)</option>
                      <option value="AS">American Samoa (US)</option>
                      <option value="AZ">Arizona (US)</option>
                      <option value="AR">Arkansas (US)</option>
                      <option value="CA">California (US)</option>
                      <option value="CO">Colorado (US)</option>
                      <option value="CT">Connecticut (US)</option>
                      <option value="DE">Delaware (US)</option>
                      <option value="DC">District of Columbia (US)</option>
                      <option value="FL">Florida (US)</option>
                                            <option value="GA">Georgia (US)</option>
                      <option value="GU">Guam (US)</option>
                      <option value="HI">Hawaii (US)</option>
                      <option value="ID">Idaho (US)</option>
                      <option value="IL">Illinois (US)</option>
                      <option value="IN">Indiana (US)</option>
                      <option value="IA">Iowa (US)</option>
                      <option value="KS">Kansas (US)</option>                      
                      <option value="KY">Kentucky (US)</option>
                      <option value="LA">Louisiana (US)</option>
                      <option value="ME">Maine (US)</option>
                      <option value="MD">Maryland (US)</option>
                      <option value="MA">Massachusetts (US)</option>
                      <option value="MI">Michigan (US)</option>
                      <option value="MN">Minnesota (US)</option>
                      <option value="MS">Mississippi (US)</option>
                      <option value="MO">Missouri (US)</option>
                                            <option value="MT">Montana (US)</option>
                      <option value="NE">Nebraska (US)</option>
                      <option value="NV">Nevada (US)</option>
                      <option value="NH">New Hampshire (US)</option>
                      <option value="NJ">New Jersey (US)</option>
                      <option value="NM">New Mexico (US)</option>
                      <option value="NY">New York (US)</option>
                      <option value="NC">North Carolina (US)</option>
                      <option value="ND">North Dakota (US)</option>
                                            <option value="OH">Ohio (US)</option>
                      <option value="OK">Oklahoma (US)</option>
                      <option value="OR">Oregon (US)</option>
                      <option value="PA">Pennsylvania (US)</option>
                      <option value="PR">Puerto Rico (US)</option>
                      <option value="RI">Rhode Island (US)</option>
                      <option value="SC">South Carolina (US)</option>
                      <option value="SD">South Dakota (US)</option>
                      <option value="TN">Tennessee (US)</option>
                                            <option value="TX">Texas (US)</option>
                      <option value="UT">Utah (US)</option>
                      <option value="VT">Vermont (US)</option>
                      <option value="VI">Virgin Islands (US)</option>
                      <option value="VA">Virginia (US)</option>
                      <option value="WA">Washington (US)</option>
                      <option value="WV">West Virginia (US)</option>
                      <option value="WI">Wisconsin (US)</option>
                      <option value="WY">Wyoming (US)</option>
                      
          
                    </select> 
                  </div>
                </div>
              </div>
              
              
              
              
              
              
              
           
              <div class="clearfix"></div>
              
              <div class="page-loader" id="page-loader1"  style="display:none;">
             <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
           
         
              
              <div class="col-xs-12 col-xxs spacetop1x text-center">
                <button type="submit" id="cashier7_direct_submit" class="btn-login transitions">SUBMIT</button>
              </div>
            </div>
          </form>
          
          
<?php 
}
else
{
	?>
            <iframe id="safe-ifrm-direct" border=0 src="<?php echo $redirect_url; ?>" align="center" frameborder="0" width="100%" height="695px"></iframe>
	
	<?php
}
?>
          
          
        </div>

      </div>
    </div>
  </div>
</div>
<script>


$(function() {

    $('#us_state').attr("disabled", "disabled");
    $('#au_state').attr("disabled", "disabled");
    $('#ca_state').attr("disabled", "disabled");

	$.validator.addMethod("IsAmount", function(value, element) {
        return this.optional(element) || /^([0-9])+$/i.test(value);
    }, "Enter valid Amount");

	function showFields(value) {
	

if(value=='US')
{
	$('#us_state').removeAttr('disabled');
	  $('.us_state_dv').show();
	  $('.au_state_dv').hide();
	  $('.ca_state_dv').hide();
	  
}
else if(value=='AU')
{
	$('#au_state').removeAttr('disabled');
	  $('.us_state_dv').hide();
	  $('.au_state_dv').show();
	  $('.ca_state_dv').hide();
}
else if(value=='CA')
{
	$('#ca_state').removeAttr('disabled');
	  $('.us_state_dv').hide();
	  $('.au_state_dv').hide();
	  $('.ca_state_dv').show();
}
else
{
	$('.us_state_dv').hide();
	$('.au_state_dv').hide();
	$('.ca_state_dv').hide();
    $('#us_state').attr("disabled", "disabled");
    $('#au_state').attr("disabled", "disabled");
    $('#ca_state').attr("disabled", "disabled");
}
	
	}

	//var my_country=$('#country').val();
	//showFields(my_country);

$("#country").change(function () 
	    {
	var my_country=$('#country').val();
	showFields(my_country);
	    });

$("#cashier7_direct_form").validate({
    rules: {
	
    				acc: {
	                required: true,
	              
	                },
	           	 amount: {
	  	                required: true,
	  	               
	  	              IsAmount: true,
	  	            },
    				address: {
    	                required: true,
    	                },
        				city: {
        	                required: true,
        	                },
            				country: {
            	                required: true,
            	                },
                				us_state: {
                	                required: true,
                	                },
                    				au_state: {
                    	                required: true,
                    	                },
                        				ca_state: {
                        	                required: true,
                        	                },
        	   
                				zip: {
                	                required: true,
                	                },
            
            },

            tooltip_options: {
                thefield: { placement: 'right' }
             },
           
            messages: {

            	acc: {
            		required: '<?php echo ('Please provide valid Account Number'); ?>',
                	},
                	 amount: {
      	                required: '<?php echo ('Please provide amount'); ?>',
      	              
      	                IsAmount: "<?php echo ('Minimum deposit amount is 1 and Maximum deposit amount is 1000'); ?>",
      	            },
                	address: {
                		required: '<?php echo ('Please provide Address'); ?>',
                    	},
                    	city: {
                    		required: '<?php echo ('Please provide City'); ?>',
                        	},

                        	country: {
                        		required: '<?php echo ('Please provide Country'); ?>',
                            	},
                            	us_state: {
                            		required: '<?php echo ('Please provide State'); ?>',
                                	},
                                	au_state: {
                                		required: '<?php echo ('Please provide State'); ?>',
                                    	},
                                    	ca_state: {
                                    		required: '<?php echo ('Please provide State'); ?>',
                                        	},
                
                            	zip: {
                            		required: '<?php echo ('Please provide Postal Code'); ?>',
                                	},

          	}, 
   

             submitHandler: function (form) { 
         	    
                 $("#cashier7_direct_submit").hide();
                 $("#page-loader1").show();
                 form.submit();
             }

             
          });


});
</script> 
