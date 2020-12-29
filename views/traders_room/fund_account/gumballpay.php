<?php 
$crm_country = $result[0]->country;
$ip_country = get_country_details();
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
    <!-- <div class="col-md-12 spacetop2x" id="a" style="display:none;"> -->
    <h1 class="lg-heading">Deposit Funds using GUMBALLPAY</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
        
        <?php 
        if(empty($redirect_url))
        {
        ?>
        
        
        <form class="form-horizontal clearfix" id="fundformcashier7" method="post" action="">
             
       
            
                      <?php 	            
            $token = md5(uniqid(rand(), TRUE));            
            if(isset ($_SESSION['form_token_cashier7']))
            {
            	unset($_SESSION['form_token_cashier7']);
            }            
			$_SESSION['form_token_cashier7'] = $token;	
			?>
            <input type="hidden" name="my_token_cashier7" value="<?php echo $token;?>">
            
            
            <div class="row clearfix">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <select class="form-control" id="acc_fund" name="acc" data-validation="required" data-validation-error-msg="<?php echo lang("Please select Trading Account");?>">
                      <?php 
             foreach ($result as $row)
             { ?>
                      <option value="<?php echo $row->name; ?>"><?php echo $row->name; ?></option>
                      <?php   }
            ?>
                    </select> 
                  </div>
                </div>
              </div>
              
             <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('Amount'); ?>" name="amount" id="amount" >
                  </div>
                </div>
              </div>
              
              			  <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo ('Address'); ?>" name="address" id="address" value="<?php echo $result[0]->address1; ?>" >
                  </div>
                </div>
              </div>
              
			  
			  <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('City'); ?>" name="city" id="city" value="<?php echo $result[0]->city; ?>" >
                  </div>
                </div>
              </div>
              
              

			  
			  <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo ('Postal Code'); ?>" name="zip" id="zip" value="<?php echo $result[0]->zipcode; ?>" >
                  </div>
                </div>
              </div>
              
              
              
                            <?php 
              if($crm_country=='US'||$ip_country=='US')
              {
              ?>
              
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <select class="form-control" id="state" name="state" data-validation="required" data-validation-error-msg="<?php echo ("Please select State");?>">

                      <option value="AL">Alabama</option>
                      <option value="AK">Alaska</option>
                      <option value="AS">American Samoa</option>
                      <option value="AZ">Arizona</option>
                      <option value="AR">Arkansas</option>
                      <option value="CA">California</option>
                      <option value="CO">Colorado</option>
                      <option value="CT">Connecticut</option>
                      <option value="DE">Delaware</option>
                      <option value="DC">District of Columbia</option>
                      <option value="FL">Florida</option>
                                            <option value="GA">Georgia</option>
                      <option value="GU">Guam</option>
                      <option value="HI">Hawaii</option>
                      <option value="ID">Idaho</option>
                      <option value="IL">Illinois</option>
                      <option value="IN">Indiana</option>
                      <option value="IA">Iowa</option>
                      <option value="KS">Kansas</option>                      
                      <option value="KY">Kentucky</option>
                      <option value="LA">Louisiana</option>
                      <option value="ME">Maine</option>
                      <option value="MD">Maryland</option>
                      <option value="MA">Massachusetts</option>
                      <option value="MI">Michigan</option>
                      <option value="MN">Minnesota</option>
                      <option value="MS">Mississippi</option>
                      <option value="MO">Missouri</option>
                                            <option value="MT">Montana</option>
                      <option value="NE">Nebraska</option>
                      <option value="NV">Nevada</option>
                      <option value="NH">New Hampshire</option>
                      <option value="NJ">New Jersey</option>
                      <option value="NM">New Mexico</option>
                      <option value="NY">New York</option>
                      <option value="NC">North Carolina</option>
                      <option value="ND">North Dakota</option>
                                            <option value="OH">Ohio</option>
                      <option value="OK">Oklahoma</option>
                      <option value="OR">Oregon</option>
                      <option value="PA">Pennsylvania</option>
                      <option value="PR">Puerto Rico</option>
                      <option value="RI">Rhode Island</option>
                      <option value="SC">South Carolina</option>
                      <option value="SD">South Dakota</option>
                      <option value="TN">Tennessee</option>
                                            <option value="TX">Texas</option>
                      <option value="UT">Utah</option>
                      <option value="VT">Vermont</option>
                      <option value="VI">Virgin Islands</option>
                      <option value="VA">Virginia</option>
                      <option value="WA">Washington</option>
                      <option value="WV">West Virginia</option>
                      <option value="WI">Wisconsin</option>
                      <option value="WY">Wyoming</option>

                    </select> 
                  </div>
                </div>
              </div>
              
              <?php 
        }
              ?>
              
              
                            <?php 
              if($crm_country=='AU'||$ip_country=='AU')
              {
              ?>
              
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <select class="form-control" id="state" name="state" data-validation="required" data-validation-error-msg="<?php echo ("Please select State");?>">

                      <option value="ACT">Australian Capital Territory</option>
                      <option value="NSW">New South Wales</option>
                      <option value="NT">Northern Territory</option>
                      <option value="QLD">Queensland</option>
                      <option value="SA">South Australia</option>
                      <option value="TAS">Tasmania</option>
                      <option value="VIC">Victoria</option>
                      <option value="WA">Western Australia</option>
            
                    </select> 
                  </div>
                </div>
              </div>
              
              <?php 
        }
              ?>
              
              
              
              
                            <?php 
              if($crm_country=='CA'||$ip_country=='CA')
              {
              ?>
              
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <select class="form-control" id="state" name="state" data-validation="required" data-validation-error-msg="<?php echo ("Please select State");?>">


                      <option value="AB">Alberta</option>
                      <option value="BC">British Columbia</option>
                      <option value="MB">Manitoba</option>
                      <option value="NB">New Brunswick</option>
                      <option value="NL">Newfoundland and Labrador</option>
                      <option value="NT">Northwest Territories</option>
                      <option value="NS">Nova Scotia</option>
                      <option value="NU">Nunavut</option>
                      <option value="ON">Ontario</option>
                      <option value="PE">Prince Edward Island</option>
                      <option value="QC">Quebec</option>
                      <option value="SK">Saskatchewan</option>
                      <option value="YT">Yukon</option>
                      
          
                    </select> 
                  </div>
                </div>
              </div>
              
              <?php 
        }
              ?>
              
     
             
              <div class="clearfix"></div>
         
        
              <div class="col-xs-12">
                <div class="btn-box spacetop1x text-right">
                  <button type="submit" class="btn-login" name="submit" id="cashier7_submit">Submit</button>

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
          
          <?php 
        }
        else
        {
        	?>
        	
        	<iframe id="safe-ifrm" border=0 src="<?php echo $redirect_url; ?>" align="center" frameborder="0" width="100%" height="695px"></iframe>
        	
        	<?php
        }
          
          ?>
          
          
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
	

 	$.validator.addMethod("IsNumber", function(value, element) {
  	    return this.optional(element) || /^([0-9]{1,17})+$/i.test(value);
  	}, "Enter valid input");



 	$.validator.addMethod("IsAmount", function(value, element) {
   	  // if(value<250 || value>1000)
	  if(value<0 || value>1000)
   	  	   {
 			return false;
   	  	   }
   	   else
   	  	   {
   		 	return true;
   	  	   }
   	}, "Minimum deposit amount is 1 and Maximum deposit amount is 1000");
  	

	    
     $("#fundformcashier7").validate({
         rules: {
        	 acc: { required: true },       	 
        	 amount: {
  	                required: true,
  	                IsNumber: true,
  	              IsAmount: true,
  	            },
  				 address: { required: true },
  				 city: { required: true },
  				 <?php 
  				 if($crm_country=='US'||$ip_country=='US'||$crm_country=='AU'||$ip_country=='AU'||$crm_country=='CA'||$ip_country=='CA')
  				 {
  				 	?>
  				 	state: { required: true },
  				 	<?php
  				 }
  				 ?>
  				 
  				 zip: { required: true },
      
         },
         messages:{
        	 acc: { required: '<?php echo lang('This field is required.'); ?>' },       	 
         	 amount: {
 	                required: '<?php echo lang('This field is required.'); ?>',
 	                 IsNumber: "<?php echo lang('Please enter a valid Amount'); ?>",
 	                IsAmount: "<?php echo ('Minimum deposit amount is 1 and Maximum deposit amount is 1000'); ?>",
 	            },
 				 address: { required: '<?php echo lang('This field is required.'); ?>' },
 				 city: { required: '<?php echo lang('This field is required.'); ?>' },
  				 <?php 
  		  				 if($crm_country=='US'||$ip_country=='US'||$crm_country=='AU'||$ip_country=='AU'||$crm_country=='CA'||$ip_country=='CA')
  		  				 {
  		  				 	?>
 				 state: { required: '<?php echo lang('This field is required.'); ?>' },
 				 <?php 
						}
 				 ?>
 				 zip: { required: '<?php echo lang('This field is required.'); ?>' },
  		 
             },
         tooltip_options: {
            thefield: { placement: 'right' }
        },

         submitHandler: function (form) { 
     	    
             $("#cashier7_submit").hide();
             $(".page-loader").show();
			form.submit();
         }

         
      });

  });
}); 

    </script> 
