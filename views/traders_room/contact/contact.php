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
    <h1 class="lg-heading">Support</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
         <?php echo form_open('contact',array('class'=>'form-horizontal clearfix','id'=>'support_forms','method'=>'post'));?>
     
            <input type="hidden" name="my_token_support" value="<?php echo $token;?>">
            <div class="row clearfix">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('Full Name'); ?>" name="firstname_support" id="firstname_support" >
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-globe" aria-hidden="true"></i></span>
                    <select class="form-control" name="country_support" id="real_country_support"  onchange="changecode_support(this.value)">
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
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <input type="text" class="form-control country-code" name="country_code_support" id="real_countrycode_support" value="<?php echo $pco;?>" readonly="readonly">
                  <input type="text" class="form-control phone-number" placeholder="<?php echo lang('Phone Number');?>" id="phone_support" name="phone_support" >
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
                    <select class="form-control" name="call_time_support" id="call_time_support" >
                      <option value="Morning"><?php echo lang('Morning');?></option>
                      <option value="Afternoon"><?php echo lang('Afternoon');?></option>
                      <option value="Evening"><?php echo lang('Evening');?></option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="btn-box spacetop1x text-right">
                  <button type="submit" class="btn-login transitions" name="submit" id="support_form_submit"><?php echo lang('Call Me Back');?></button>

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
function changecode_support(str) {

	   $.ajax({
	    url : "<?= base_url($this->uri->segment(1).'/Dashboard/countrychange') ?>",
	    type : "get",
	    data : "q=" + str,
	    success : function(a) {
	     jQuery("#real_countrycode_support").val(a);

	     
	   }
	 });
	 }





	 $(function(){

		 $("#popupclosesu").click(function(){
			    $("#success-popupsu").hide();
			});

		 
$(document).ready(function(){

 	function setcookie(cname, cvalue, exdays) {
		  var d = new Date();
		  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		  var expires = "expires="+d.toUTCString();
		  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
		}
$.validator.addMethod("IsName", function(value, element) {
    return this.optional(element) || /^[a-zA-Z]+([A-Za-z][ ]{0,1})*[a-zA-Z]$/i.test(value);
}, "Please provide above details");

$.validator.addMethod("IsNumber", function(value, element) {
	    return this.optional(element) || /^([0-9]{5,11})+$/i.test(value);
	}, "Enter valid input");



$("#support_forms").validate({
   rules: {
	   firstname_support: { 
       	   required: true,
       	   IsName: true
       	   },
        
	     phone_support: {
                required: true,
                IsNumber: true,
                rangelength: [5, 11],
                },
   },
   messages: {
	   firstname_support: { 
       	   required: '<?php echo lang('This field is required.');?>',
       	   IsName: '<?php echo lang('Enter a vaild Name.');?>',
       	   },
        
	     phone_support: {
                required: '<?php echo lang('This field is required.');?>',
                IsNumber: '<?php echo lang('Enter a vaild Phone Number.');?>',
                },
   },

   submitHandler: function (form) { 
       var token = Math.random().toString().replace('0.', ''); 
     	 $("input[type=hidden][name=my_token_support]").val(token); 
     	setcookie('form_token_support',token,'180');  
       $("#support_form_submit").hide();
       $(".page-loader").show();
       

form.submit();
   }

   
   
});
});
});
</script>