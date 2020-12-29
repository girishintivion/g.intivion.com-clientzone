<?php 

?>
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
               
                  <div class="clearfix"></div>
                </div>
              </div>
            </div>
          </div>
	 </div>
	
    <div class="col-md-12">
      <div class="login-body">
        <h1 class="login-heading" id="login_heading">DEPOSIT</h1>
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
        
        
        
        

        
        
        
        
         <?php echo form_open('',array('class'=>'form-horizontal clearfix','id'=>'cashier2_form','method'=>'post'));?>
          <?php 	            
            $token = md5(uniqid(rand(), TRUE));            
            if(isset ($_SESSION['form_token_cashier2']))
            {
            	unset($_SESSION['form_token_cashier2']);
            }            
			$_SESSION['form_token_cashier2'] = $token;	
			?>
            <input type="hidden" name="my_token_cashier2" value="<?php echo $token;?>">
            <div class="row clearfix">
            
              <div class="col-xs-6">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="acc" name="acc" placeholder="Account Number">
                  </div>
                </div>
              </div>
              
              
                <div class="col-xs-6">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount">
                  </div>
                </div>
              </div>
              
                <div class="col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-address-card-o" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="card_holder_name" name="card_holder_name" placeholder="Card Holder Name">
                  </div>
                </div>
              </div>
              

              
              
               <div class="col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="card_num" name="card_num" placeholder="Card Number">
                  </div>
                </div>
              </div>
              
              
              
                <div class="col-xs-6">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                    <select class="form-control" name="expiry_month" id="expiry_month">
                      <option selected value="">Expiry Month</option>
                      <option value="01">January</option>
                      <option value="02">February</option>
                      <option value="03">March</option>
                      <option value="04">April</option>
                      <option value="05">May</option>
                      <option value="06">June</option>
                      <option value="07">July</option>
                      <option value="08">August</option>
                      <option value="09">September</option>
                      <option value="10">October</option>
                      <option value="11">November</option>
                      <option value="12">December</option>
                    </select>
                  </div>
                </div>
              </div>
              
              
              <div class="col-xs-6">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                    <select class="form-control" id="expiry_year" name="expiry_year">
                      <option selected value="">Expiry Year</option>
                      <option value="">YYYY</option>
                      <?php
                               for($d=date('Y');$d<=date('Y')+20;$d++)
                                {?>
                      <option value="<?php echo $d;?>"><?php echo $d;?></option>
                      <?php }?>
                    </select>
                  </div>
                </div>
              </div>
              
              
              
               <div class="col-xs-6">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-credit-card-alt" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="cvv" name="cvv" placeholder="CVV">
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
                <button type="submit" id="cashier2_submit" class="btn-login transitions">SUBMIT</button>
              </div>
            </div>
          </form>
          
          

          
          
        </div>

      </div>
    </div>
  </div>
</div>
<script>


$(function() {
	

	$.validator.addMethod("IsAmount", function(value, element) {
        return this.optional(element) || /^([0-9])+$/i.test(value);
    }, "Enter valid Amount");

	
$.validator.addMethod("IsNumber", function(value, element) {
    return this.optional(element) || /^([0-9]{5,17})+$/i.test(value);
}, "Please provide above details");



$("#cashier2_form").validate({
    rules: {
					amount: {
            		required: true,
					IsAmount: true,
        			},
    				acc: {
	                required: true,
	                IsNumber: true,
	                },
        			card_holder_name: {
                        required: true
                    },
        			card_num: {
                        required: true,
                    },
                    expiry_month: {
                        required: true,
                    },
                    expiry_year: {
                        required: true,
                    },
        			cvv: {
                        required: true,
                    },
	
 	                
            },

            tooltip_options: {
                thefield: { placement: 'right' }
             },
           
            messages: {
         		amount: {
                    required: '<?php echo ('Amount is required.'); ?>',
    				IsAmount: '<?php echo ('Please enter a valid Amount'); ?>',
                },
            	acc: {
            		required: '<?php echo ('Please provide valid Account Number'); ?>',
                	},
     			card_holder_name: {
                    required: '<?php echo ('First Name is required.'); ?>',
                },
    			card_num: {
                    required: '<?php echo ('Card Number is required.'); ?>',
                },
    			expiry_month: {
                    required: '<?php echo ('Expiry Month is required.'); ?>',
                },
    			expiry_year: {
                    required: '<?php echo ('Expiry Year is required.'); ?>',
                },
    			cvv: {
                    required: '<?php echo ('CVV is required.'); ?>',
                },

          	}, 
   

             submitHandler: function (form) { 
         	    
                 $("#cashier2_submit").hide();
                 $("#page-loader1").show();
                 form.submit();
             }

             
          });


});
</script> 
