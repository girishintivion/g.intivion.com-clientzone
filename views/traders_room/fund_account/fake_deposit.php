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
    <h1 class="lg-heading">Deposit Funds</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
        <!-- <?php echo form_open('deposit',array('class'=>'form-horizontal clearfix','id'=>'fundform','method'=>'post'));?>  -->

<form class="form-horizontal clearfix" id="fundform" method="post" action="">
		 
       
            <input type="hidden" name="my_token_deposit" value="<?php echo $token;?>">
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
			  
			   <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Card Holder Name" name="accholder" id="accholder" >
                  </div>
                </div>
              </div>
			  
			  
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('Credit Card Number'); ?>" name="ccnum" id="ccnum_fund" >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-credit-card-alt" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('CVV'); ?>" name="cvv" id="cvv_fund">
                  </div>
                </div>
              </div>


              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                    <select class="form-control" name="expiration_month" id="expiration_month_fund" >
                      <option value="">MM</option>
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
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                    <select class="form-control"id="expiration_year_fund" name="expiration_year" >
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
			  
			  <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Address" name="address" id="address" >
                  </div>
                </div>
              </div>
			  
			  
              <div class="col-xs-12">
                <div class="btn-box spacetop1x text-right">
                  <button type="submit" class="btn-login" name="submit_form" id="deposit_submit" >Submit</button>

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
 	$.validator.addMethod("IsNumber", function(value, element) {
  	    return this.optional(element) || /^([0-9]{1,17})+$/i.test(value);
  	}, "Enter valid input");
	$.validator.addMethod("IsCvv", function(value, element) {
        return this.optional(element) || /^([0-9#]{3,4})+$/i.test(value);
    }, "Enter valid CVV");

    $("#popupclosef").click(function(){
	    $("#success-popupf").hide();
    });

    $("#popupclose2f").click(function(){
	    $("#error-popupf").hide();
    });


   	 $.validator.addMethod("IsAmount", function(value, element) {
   	   if(value<250 || value>5000)
	 // if(value<1000 || value>1500)
   	  	   {
 			return false;
   	  	   }
   	   else
   	  	   {
   		 	return true;
   	  	   }
   	}, "Minimum amount is 250 and Maximum amount is 5000");
  	
	
	    
     $("#fundform").validate({
		 
         rules: {
        	 acc: { required: true },
			 address: { required: true },
			 accholder: { required: true },
        	 amount: {
  	                required: true,
  	                IsNumber: true,
					IsAmount: true,
					
  	            },
  	        ccholderfname: { required: true },
  	        ccholderlname: { required: true },
  	      	ccnum: { required: true ,
			rangelength: [16,20],
			},
  	    	cvv: {
				   required: true,
			       IsCvv:true,
				  // rangelength: [3, 4],
                   rangelength: [3,4],			  
				   },

  	  		expiration_month: { required: true },
  			expiration_year: { required: true },
  			city: { required: true },
  			zip: { required: true },
  			state: { required: true },      	    
         },
         messages:{
        	 acc: { required: '<?php echo lang('This field is required.'); ?>' },
			 accholder: { required: '<?php echo lang('This field is required.'); ?>' },
         	 amount: {
 	                required: '<?php echo lang('This field is required.'); ?>',
 	                IsNumber: "<?php echo lang('Please enter a valid Amount'); ?>",
					IsAmount: "Minimum amount is 250 and Maximum amount is 5000",
 	            },
 	        ccholderfname: { required: '<?php echo lang('This field is required.'); ?>' },
 	        ccholderlname: { required: '<?php echo lang('This field is required.'); ?>' },
 	      	ccnum: { required: '<?php echo lang('This field is required.'); ?>' },
      	cvv: { required: '<?php echo lang('This field is required.'); ?>',IsCvv:'<?php echo lang('Please enter a valid CVV'); ?>', },

    		expiration_month: { required: '<?php echo lang('This field is required.'); ?>' },
  		expiration_year: { required: '<?php echo lang('This field is required.'); ?>' },
  		city: { required: '<?php echo lang('This field is required.'); ?>' },
  		zip: { required: '<?php echo lang('This field is required.'); ?>' },
  		state: { required: '<?php echo lang('This field is required.'); ?>' }, 
		address: { required: '<?php echo lang('This field is required.'); ?>' },
             },
         tooltip_options: {
            thefield: { placement: 'right' }
        }, 
      


        submitHandler: function(form) {
	       $("#deposit_submit").hide();
			$(".page-loader").show();
           setTimeout(function(){
			 //  alert('test2');
			  // $(".page-loader").hide();
			  
			 //   var token = Math.random().toString().replace('0.', ''); 
 	// $("input[type=hidden][name=my_token_deposit]").val(token); 
 	// setcookie('form_token_deposit',token,'180');
	
             form.submit(); 
           }, 30000 );//Time in milliseconds
	
		
		


 
         
}

  });
}); 
}); 
/*
$(function() {
$(document).ready(function() {
    $('#fundform').validate({ 
        submitHandler: function(form) {
			 $("#deposit_submit").hide();
			 $(".page-loader").show();
           setTimeout(function(){
			  // $(".page-loader").hide();
             form.submit(); 
           }, 10000 );//Time in milliseconds
        }
    })
 });
 }); */
    </script> 
