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
    <h1 class="lg-heading">Interfund Transfer</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
          <?php echo form_open('interfund-transfer',array('class'=>'form-horizontal clearfix','id'=>'interfundform','method'=>'post'));?>
        
            <input type="hidden" name="my_token_interfund" value="<?php echo $token;?>">
            <div class="row clearfix">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-level-up" aria-hidden="true"></i></span>
                    <select class="form-control" name="accno" id="accno">
                      <option selected value=""><?php echo lang('From Account'); ?></option>
                      <?php 
			              			foreach ($result as $row)			    
			               			{ ?>
                      <option value="<?php echo $row->name; ?>"><?php echo $row->name; ?></option>
                      <?php   
			              			}
                					?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-level-down" aria-hidden="true"></i></span>
                    <select class="form-control" name="accno_two" id="accno_two">
                      <option value=""><?php echo lang("To Account"); ?></option>
                      <?php 
				               foreach ($result as $row)    
                 				{ ?>
                      <option value="<?php echo $row->name; ?>"><?php echo $row->name; ?></option>
                      <?php   
				                }
               				 	?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-suitcase" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('Amount'); ?>" id="amount" name="amount">
                  </div>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="btn-box spacetop1x text-right">
                  <button type="submit" class="btn-login" name="fund_transfer_submit" id="fund_submit">Submit</button>
  
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
    	$.validator.addMethod("IsNumber", function(value, element) {
    	    return this.optional(element) || /^([0-9]{1,20})+$/i.test(value);
    	}, "Enter valid input");

		$.validator.addMethod("notEqualTo", function(value, element) {	
			   return $('#accno').val() != $('#accno_two').val();
			}, "Please select a different account");

      $("#popupclosei").click(function(){
		    $("#success-popupi").hide();
	    });

      $("#popupclose2i").click(function(){
		    $("#error-popupi").hide();
	    });

	    	
      $("#interfundform").validate({
          rules: {
         	 accno: { required: true },
         	 accno_two: { required: true,
         		 notEqualTo:true },
         	 amount: { required: true,
         		 IsNumber:true },

          },
          messages: {
         	 accno: { required: "<?php echo lang('Select an account'); ?>" },
         	 accno_two: { required: "<?php echo lang('Select an account'); ?>",
         		 notEqualTo:"<?php echo lang("Please select a different account"); ?>" },
         	 amount: { required: "<?php echo lang('This field is required.'); ?>",
         		 IsNumber:"<?php echo lang('Enter a valid amount.'); ?>" },

          },
          tooltip_options: {
             thefield: { placement: 'right' }
          },

          submitHandler: function (form) { 
      	    
              $("#fund_submit").hide();
              $(".page-loader").show();
           //   form.submit();
var token = Math.random().toString().replace('0.', ''); 
  	 $("input[type=hidden][name=my_token_interfund]").val(token); 
  	setcookie('form_token_interfund',token,'180');
  	form.submit();
          }

          
       });


  });
});
    </script> 