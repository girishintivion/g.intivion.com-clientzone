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
              <?php 
      if(empty($redirect_url))
      {
      	?>
      	<h1 class="login-heading" id="login_heading"><?php echo 'DEPOSIT Using '.$gateway;?></h1>
      	<?php
      }
      else
      {
      	?>
      	<h1 class="login-heading" id="login_heading"><?php echo 'DEPOSIT Using '.$gateway.' From IP '.$from_ip;?></h1>
      	<?php
      }
      ?>
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


         <?php echo form_open('',array('class'=>'form-horizontal clearfix','id'=>'cashier1_direct_form','method'=>'post'));?>
          <?php 	            
            $token = md5(uniqid(rand(), TRUE));            
            if(isset ($_SESSION['form_token_cashier1_backoffice']))
            {
            	unset($_SESSION['form_token_cashier1_backoffice']);
            }            
			$_SESSION['form_token_cashier1_backoffice'] = $token;	
			?>
            <input type="hidden" name="my_token_cashier1_backoffice" value="<?php echo $token;?>">
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
                    <input type="text" class="form-control" id="state" name="state" placeholder="Province">
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
              
              
           
              <div class="clearfix"></div>
              
              <div class="page-loader" id="page-loader1"  style="display:none;">
             <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
           
         
              
              <div class="col-xs-12 col-xxs spacetop1x text-center">
                <button type="submit" id="cashier1_direct_submit" class="btn-login transitions">SUBMIT</button>
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
	

	$.validator.addMethod("IsAmount", function(value, element) {
        return this.optional(element) || /^([0-9])+$/i.test(value);
    }, "Enter valid Amount");

	
$.validator.addMethod("IsNumber", function(value, element) {
    return this.optional(element) || /^([0-9]{5,17})+$/i.test(value);
}, "Please provide above details");



$("#cashier1_direct_form").validate({
    rules: {
	
    				acc: {
	                required: true,
	                IsNumber: true,
	                },
    				address: {
    	                required: true,
    	                },
        				city: {
        	                required: true,
        	                },
            				state: {
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
                	address: {
                		required: '<?php echo ('Please provide Address'); ?>',
                    	},
                    	city: {
                    		required: '<?php echo ('Please provide City'); ?>',
                        	},
                        	state: {
                        		required: '<?php echo ('Please provide Province'); ?>',
                            	},
                            	zip: {
                            		required: '<?php echo ('Please provide Postal Code'); ?>',
                                	},

          	}, 
   

             submitHandler: function (form) { 
         	    
                 $("#cashier1_direct_submit").hide();
                 $("#page-loader1").show();
                 form.submit();
             }

             
          });


});
</script> 
