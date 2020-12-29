<?php 
//defined('BASEPATH') OR exit('No direct script access allowed');
if (!$_SERVER['HTTP_REFERER'])
	redirect($this->uri->segment(1).'/dashboard');
	
	if($result[0]->birth_date !="")
	{
		$dob_1= date('Y/m/d h:i:s', strtotime($result[0]->birth_date));
		
		$dob_1 =explode("/",$dob_1);
		
		$dob_date1=explode(" ",$dob_1[2]);
		$dob_date=$dob_date1[0];
		$dob_month=$dob_1[1];
		$dob_year=$dob_1[0];
		
		$data =$dob_date."/".$dob_month."/".$dob_year;
	}
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
    <h1 class="lg-heading">Personal Details</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
         <?php echo form_open('personal-details',array('class'=>'form-horizontal clearfix','id'=>'personal_form','method'=>'post'));?>
     
            <input type="hidden" name="my_token_personal_details" value="<?php echo $token;?>">
            <div class="row clearfix">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="First Name" name="firstname" id="first_name" value="<?php echo $result[0]->firstname; ?>" readonly>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Last Name" name="lastname" id="lastname" value="<?php echo $result[0]->lastname; ?>" readonly>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Email address" name="email"id="email"  value="<?php echo $result[0]->email; ?>" readonly>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group date year18" data-provide="datepicker">
                    <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                    <input type="text" class="form-control" readonly placeholder="Date of Birth" id="birth_date" name="birth_date" value="<?php if($data !=""){echo $data;}?>"/>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-globe" aria-hidden="true"></i></span>
                    <select class="form-control" name="country" id="real-country" onchange="changecode(this.value)">
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
                  <input type="text" class="form-control country-code" name="country_code" id="real-countrycode" value="<?php echo $pco;?>" readonly>
                  <input type="text" class="form-control phone-number" placeholder="Phone number" id="phone" name="phone" value="<?php echo $result[0]->phone; ?>">
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Address" name="address" id="address" value="<?php echo $result[0]->address1; ?>">
                  </div>
                </div>
              </div>
              <div class="col-xs-12">
                <div class="btn-box spacetop1x text-right">
                  <button type="submit" class="btn-login" id="personalsubmit"  name="personalsubmit" >Change</button>

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
	  function changecode(str) {	   		
	//alert($("#real-country").val());
	//alert($("#real-countrycode").val());
	      	  
	      	   jQuery.ajax({
	      	    url : "<?= base_url($this->uri->segment(1).'/Dashboard/countrychange') ?>",
	      	    type : "get",
	      	    data : "q=" + str,
	      	    success : function(a) {
	      	     jQuery("#real-countrycode").val(a);
	      	   }
	      	 });
	      	 };
$(function() {

$(document).ready(function(){
           	    
	  $('.year18').datepicker({
		  endDate: '-18y'});
	  
     	function setcookie(cname, cvalue, exdays) {
     		  var d = new Date();
     		  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
     		  var expires = "expires="+d.toUTCString();
     		  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
     		}
     	
      	$.validator.addMethod("IsNumber", function(value, element) {
      	    return this.optional(element) || /^([0-9]{5,11})+$/i.test(value);
      	}, "Enter valid input");


         $("#personal_form").validate({
             rules: {
            	 firstname: { required: true },
            	 firstname: { required: true },
            	 phone: {
      	                required: true,
      	                IsNumber: true,
      	              rangelength: [8, 14],
      	            },
      	//        date_day: { required: true },
      	//        date_month: { required: true },
      	//      	date_year: { required: true },
      	birth_date: { required: true },
      	    	address: { required: true },
             },
             messages: {

	        	   firstname: "<?php echo lang('Please provide above details'); ?>",
	        	   lastname: "<?php echo lang('Please provide above details'); ?>",
	        	   email: "<?php echo lang('Please provide Valid Email'); ?>",
	        	   password_confirmation: "<?php echo lang('Password must include 6-20 alphanumeric characters'); ?>",
	        	   Password: "<?php echo lang('Please enter the same value again.'); ?>",
	      //  	   date_day: "<?php echo lang('Please select day'); ?>",
	      //  	   date_month: "<?php echo lang('Please select month'); ?>",
	      //  	   date_year: "<?php echo lang('Please select year'); ?>",
	        	   phone: "<?php echo lang('Please provide Valid Phone Number'); ?>",
	        	   address: "<?php echo lang('Please provide above details'); ?>",
	        	   birth_date: "<?php echo lang('Please provide above details'); ?>",
	        	}, 
             tooltip_options: {
                thefield: { placement: 'right' }
             },

             submitHandler: function (form) { 
            	 var token = Math.random().toString().replace('0.', ''); 
             	 $("input[type=hidden][name=my_token_personal_details]").val(token); 
             	setcookie('form_token_personal_details',token,'180');
                 $("#personalsubmit").hide();
                 $(".page-loader").show();
             //    form.submit();

 form.submit();
             }

             
          });

  });
});
    </script> 