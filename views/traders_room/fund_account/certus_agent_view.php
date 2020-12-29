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
        <h1 class="login-heading" id="login_heading">DEPOSIT using CERTUS</h1>
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




         <?php echo form_open('',array('class'=>'form-horizontal clearfix','id'=>'cashier22_agent_form','method'=>'post'));?>
          <?php 	            
            $token = md5(uniqid(rand(), TRUE));            
            if(isset ($_SESSION['form_token_cashier22_agent']))
            {
            	unset($_SESSION['form_token_cashier22_agent']);
            }            
			$_SESSION['form_token_cashier22_agent'] = $token;	
			?>
            <input type="hidden" name="my_token_cashier22_agent" value="<?php echo $token;?>">
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
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount">
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
                <button type="submit" id="cashier22_agent_submit" class="btn-login transitions">SUBMIT</button>
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
    	   if(value<250 || value>1000)
    	  	   {
  			return false;
    	  	   }
    	   else
    	  	   {
    		 	return true;
    	  	   }
    	}, "Minimum deposit amount is 250 and Maximum deposit amount is 1000");

	
 	$.validator.addMethod("IsNumber", function(value, element) {
  	    return this.optional(element) || /^([0-9]{1,17})+$/i.test(value);
  	}, "Enter valid input");



$("#cashier22_agent_form").validate({
    rules: {
	
    				acc: {
	                required: true,
	                IsNumber: true,
	                },
	           	 amount: {
	  	                required: true,
	  	                IsNumber: true,
	  	              IsAmount: true,
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
      	                required: '<?php echo ('This field is required.'); ?>',
      	                 IsNumber: "<?php echo ('Please enter a valid Amount'); ?>",
      	                IsAmount: "<?php echo ('Minimum deposit amount is 250 and Maximum deposit amount is 1000'); ?>",
      	            },

          	}, 
   

             submitHandler: function (form) { 
         	    
                 $("#cashier22_agent_submit").hide();
                 $("#page-loader1").show();
                 form.submit();
             }

             
          });


});
</script> 
