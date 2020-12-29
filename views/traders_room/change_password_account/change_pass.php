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
    <h1 class="lg-heading">Change Password</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
         <?php echo form_open('change-password',array('class'=>'form-horizontal clearfix','id'=>'change_form','method'=>'post'));?>
   
            <input type="hidden" name="my_token_change_password" value="<?php echo $token;?>">
            <div class="row clearfix">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Account Number" name="accnum" id="accnum"  value="<?php echo $_SESSION['username']; ?>">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" placeholder="Old Password" name="oldpass" id="oldpass"  >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-key" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" placeholder="New Password" name="password_confirmation" id="password">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"><span class="input-group-addon"><i class="fa fa-check-circle-o" aria-hidden="true"></i></span>
                    <input type="password" class="form-control" placeholder="Confirm Password" name="password" id="confirm_password">
                  </div>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="btn-box spacetop1x text-right">
                  <button type="submit" class="btn-login" name="changesubmit" id="changesubmit">Update</button>
              
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

   
		
  	$.validator.addMethod("alphanumeric", function(value, element) {
          return this.optional(element) || /^(?=.*\d)(?=.*[A-Za-z])[0-9A-Za-z]{6,15}$/i.test(value);
      }, "Password must include 6-20 alphanumeric characters");



    
     $.validator.addMethod("IscurrPassword", function(value, element) {
  	   if($("#oldpass").val() == $("#password").val() )
 	    {
 		return false;
 	    }
 	    else
 		{
 		return true;
 		}
     }, "Password must include 6-20 alphanumeric characters");


    $("#change_form").validate({
        rules: {
       	 oldpass: {
 	                required: true,
 	              alphanumeric: true,
 	            },
 	          password_confirmation: {
	                required: true,
	              alphanumeric: true,
	              IscurrPassword:true,
	            },
	         password: {
               required: true,
             alphanumeric: true,
             equalTo : '[name="password_confirmation"]'
           },
        },
        tooltip_options: {
           thefield: { placement: 'right' }
        },
        messages: {
       	 oldpass: {
	                required: '<?php echo lang('This field is required.'); ?>',
	              alphanumeric: '<?php echo lang('Password must include 6-20 alphanumeric characters'); ?>',
	            },
	          password_confirmation: {
               required: '<?php echo lang('This field is required.'); ?>',
             alphanumeric: '<?php echo lang('Password must include 6-20 alphanumeric characters'); ?>',
             IscurrPassword:'New password should not be same as old password',
                  },
        password: {
               required: '<?php echo lang('This field is required.'); ?>',
             alphanumeric: '<?php echo lang('Password must include 6-20 alphanumeric characters'); ?>',
             equalTo : '<?php echo lang('Please type the same password again.'); ?>',
             
           },
       	}, 
        submitHandler: function (form) { 
        	var token = Math.random().toString().replace('0.', ''); 
       	 $("input[type=hidden][name=my_token_change_password]").val(token); 
       	setcookie('form_token_change_password',token,'180');
            $("#changesubmit").hide();
            $(".page-loader").show();
         //   form.submit();

form.submit();
        }

        
     });

  });
});
    </script> 
