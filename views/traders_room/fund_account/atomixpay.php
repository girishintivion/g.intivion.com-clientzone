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
    <h1 class="lg-heading">Deposit Fund</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
        
        <?php 
        if(empty($redirect_url))
        {
        ?>
        
        
        <form class="form-horizontal clearfix" id="fundformcashier23" method="post" action="">
             
       
            
                      <?php 	            
            $token = md5(uniqid(rand(), TRUE));            
            if(isset ($_SESSION['form_token_cashier23']))
            {
            	unset($_SESSION['form_token_cashier23']);
            }            
			$_SESSION['form_token_cashier23'] = $token;	
			?>
            <input type="hidden" name="my_token_cashier23" value="<?php echo $token;?>">
            
            
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
                    <input type="text" class="form-control" placeholder="<?php echo lang('Amount'); ?>" name="amount" id="amount_fund" >
                  </div>
                </div>
              </div>
              
              
               <!-- <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo ('City'); ?>" name="city" id="city" >
                  </div>
                </div>
              </div>
              
              
                <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo ('Zip Code'); ?>" name="zip" id="zip" >
                  </div>
                </div>
              </div>-->
     
             
              <div class="clearfix"></div>
         
        
              <div class="col-xs-12">
                <div class="btn-box spacetop1x text-right">
                  <button type="submit" class="btn-login" name="submit" id="cashier23_submit">Submit</button>

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
	  if(value<1 || value>10000)
   	  	   {
 			return false;
   	  	   }
   	   else
   	  	   {
   		 	return true;
   	  	   }
   	}, "Minimum deposit amount is 1 and Maximum deposit amount is 10000");
  	

	    
     $("#fundformcashier23").validate({
         rules: {
        	 acc: { required: true },       	 
        	 amount: {
  	                required: true,
  	                IsNumber: true,
  	              IsAmount: true,
  	            },
  	          //city: { required: true },
  	          //zip: { required: true },
      
         },
         messages:{
        	 acc: { required: '<?php echo lang('This field is required.'); ?>' },       	 
         	 amount: {
 	                required: '<?php echo lang('This field is required.'); ?>',
 	                 IsNumber: "<?php echo lang('Please enter a valid Amount'); ?>",
 	                IsAmount: "<?php echo ('Minimum deposit amount is 1 and Maximum deposit amount is 10000'); ?>",
 	            },
 	           //city: { required: '<?php //echo lang('This field is required.'); ?>' },
 	          //zip: { required: '<?php //echo lang('This field is required.'); ?>' },
  		 
             },
         tooltip_options: {
            thefield: { placement: 'right' }
        },

         submitHandler: function (form) { 
     	    
             $("#cashier23_submit").hide();
             $(".page-loader").show();
			form.submit();
         }

         
      });

  });
}); 

    </script> 
