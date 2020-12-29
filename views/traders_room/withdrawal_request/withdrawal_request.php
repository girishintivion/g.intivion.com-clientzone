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
    <h1 class="lg-heading">Withdrawal Request</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
         <?php echo form_open('withdrawal-request',array('class'=>'form-horizontal clearfix','id'=>'withdra_form','method'=>'post'));?>
        
            <input type="hidden" name="my_token_withdraw_req" value="<?php echo $token;?>">
            <div class="row clearfix">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <select class="form-control" id="acc" name="acc">
                      <option selected disabled>Account Number</option>
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
                    <input type="text" class="form-control" placeholder="<?php echo lang('Amount'); ?>" name="amount" id="amount_new">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                    <select class="form-control" id="type" name="type" >
                      <option value=""><?php echo lang('Select');?></option>
                      <option value="credit"><?php echo lang('Credit Card');?></option>
                      <option value="bank"><?php echo lang('Bank Wire');?></option>
                      <!--  <option value="skrill"><?php echo lang('Skrill');?></option>
					            <option value="netteller"><?php echo lang('Netteller');?></option>-->
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row clearfix credit-card-wrap credit" style="display:none;">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-address-card-o" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('Card Holder Name'); ?>"  name="ccholdername" id="ccholdername">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-credit-card" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('Credit Card Number'); ?>" name="ccnum" id="ccnum">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                    <select class="form-control" name="expiration_month" id="expiration_month">
                      <option selected value="">Expiration Month</option>
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
                    <select class="form-control" id="expiration_year" name="expiration_year">
                      <option selected value="">Expiration Year</option>
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
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-credit-card-alt" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('CVV'); ?>" name="cvv" id="cvv">
                  </div>
                </div>
              </div>
            </div>
            <div class="row clearfix bank-wire-wrap bank" style="display:none;">
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-address-book" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('Beneficiary'); ?>" name="beneficiary" id="beneficiary">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-university" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('IBAN NO/Bank Account Number'); ?>" name="iban" id="iban">
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-braille" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="<?php echo lang('Swift Code'); ?>" name="swiftcode" id="swiftcode">
                  </div>
                </div>
              </div>
            </div>
            <div class="row clearfix mbl-cntr mbl-flex">
              
              <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
                <div class="btn-box spacetop1x ">
                  <button type="reset" class="black-btn inner-blck-btn" name="Reset" value="Reset">Cancel</button>
                </div>
              </div>
			  <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
                <div class="btn-box spacetop1x cancel-btn-box">
                  <button type="submit" class="btn-login" name="submit" id="withdrawal_submit">Submit</button>
  
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
           	    
	$("#type").change(function () 
		    {		
		     $('#ccholdername').attr("disabled", "disabled");
		     $('#ccnum').attr("disabled", "disabled");
		     $('#expiration_month').attr("disabled", "disabled");
		     $('#expiration_year').attr("disabled", "disabled");
		     $('#cvv').attr("disabled", "disabled");
		     $('#beneficiary').attr("disabled", "disabled");
		     $('#iban').attr("disabled", "disabled");
		     $('#swiftcode').attr("disabled", "disabled");
		     $('#email').attr("disabled", "disabled");
		     $('#skrillnum').attr("disabled", "disabled");

		     var value = $("#type").val();
		     showFields(value);
		   });

		    function showFields(value) {
		     if (value == 'credit') {

			// 	   $("#edit-income-source-other").removeAttr('disabled');
		  $('#ccholdername').removeAttr('disabled');
		  $('#ccnum').removeAttr('disabled');
		  $('#expiration_month').removeAttr('disabled');
		  $('#expiration_year').removeAttr('disabled');
		  $('#cvv').removeAttr('disabled');
		  $('#beneficiary').attr("disabled", "disabled");
		  $('#iban').attr("disabled", "disabled");
		  $('#swiftcode').attr("disabled", "disabled");
		  $('#email').attr("disabled", "disabled");
		  $('#skrillnum').attr("disabled", "disabled");
		  $('.sknum').hide();
		  $('.netnum').hide();
		}
		if (value == 'bank')   {        	
		  $('#beneficiary').removeAttr('disabled');
		  $('#iban').removeAttr('disabled');
		  $('#swiftcode').removeAttr('disabled');
		  $('#email').attr("disabled", "disabled");
		  $('#skrillnum').attr("disabled", "disabled");
		  $('#ccholdername').attr("disabled", "disabled");
		  $('#ccnum').attr("disabled", "disabled");
		  $('#expiration_month').attr("disabled", "disabled");
		  $('#expiration_year').attr("disabled", "disabled");
		  $('#cvv').attr("disabled", "disabled");
		  $('.sknum').hide();
		  $('.netnum').hide();

		}
		/*
		if (value == 'skrill')   {        	
		  $('#email').removeAttr('disabled');
		  $('#skrillnum').removeAttr('disabled');
		  $('#ccholdername').attr("disabled", "disabled");
		  $('#ccnum').attr("disabled", "disabled");
		  $('#expiration_month').attr("disabled", "disabled");
		  $('#expiration_year').attr("disabled", "disabled");
		  $('#cvv').attr("disabled", "disabled");
		  $('#beneficiary').attr("disabled", "disabled");
		  $('#iban').attr("disabled", "disabled");
		  $('#swiftcode').attr("disabled", "disabled");
		  $('.sknum').show();
		  $('.netnum').hide();
		}

		if (value == 'netteller')   {        	
		  $('#email').removeAttr('disabled');
		  $('#skrillnum').removeAttr('disabled');
		  $('#ccholdername').attr("disabled", "disabled");
		  $('#ccnum').attr("disabled", "disabled");
		  $('#expiration_month').attr("disabled", "disabled");
		  $('#expiration_year').attr("disabled", "disabled");
		  $('#cvv').attr("disabled", "disabled");
		  $('#beneficiary').attr("disabled", "disabled");
		  $('#iban').attr("disabled", "disabled");
		  $('#swiftcode').attr("disabled", "disabled");
		  $('.sknum').hide();
		  $('.netnum').show();
		}

		*/
		}
		    var Privileges = jQuery('#type');
		    var select = this.value;
		    Privileges.change(function () {
		      if ($(this).val() == 'credit') {
		        $('.credit').show();
		        $('.bank').hide();
		        $('.skrillnetteller').hide();
		      }
		      if ($(this).val() == 'bank') {
		        $('.bank').show();
		        $('.credit').hide();
		        $('.skrillnetteller').hide();
		      }
		      /*
		      if (($(this).val() == 'skrill') || ($(this).val() == 'netteller')) {
		        $('.bank').hide();
		        $('.credit').hide();
		        $('.skrillnetteller').show();
		      }
		      */
		    });    	

			function setcookie(cname, cvalue, exdays) {
				  var d = new Date();
				  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
				  var expires = "expires="+d.toUTCString();
				  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
				}
		  
				$.validator.addMethod("IsAmount", function(value, element) {
			        return this.optional(element) || /^([0-9])+$/i.test(value);
			    }, "Enter valid Amount");
				
				$.validator.addMethod("IsCvv", function(value, element) {
			        return this.optional(element) || /^([0-9#]{3,4})+$/i.test(value);
			    }, "Enter valid CVV");
				
				$.validator.addMethod("IsBankAccount", function(value, element) {
			        return this.optional(element) || /^([0-9]{16,17})+$/i.test(value);
			    }, "Enter valid Number");
				$.validator.addMethod("IsSwiftCode", function(value, element) {
			        return this.optional(element) || /^([0-9#]{3,4})+$/i.test(value);
			    }, "Enter valid Code");

		        $("#popupclosew").click(function(){
				    $("#success-popupw").hide();
			    });

		        $("#popupclose2w").click(function(){
				    $("#error-popupw").hide();
				
			    });

		        $("#withdra_form").validate({
		             rules: {
		            		amount: {
		                        required: true,
		        				IsAmount: true,
		                    },
		        			acc: {
		                        required: true
		                    },
		        			type: {
		                        required: true
		                    },
		        			ccholdername: {
		                        required: true
		                    }
		        			,
		        			ccnum: {
		                        required: true,
		                    },
		        			expiration_month: {
		                        required: true,
		                    },
		        			expiration_year: {
		                        required: true,
		                    },
		        			cvv: {
		                        required: true,
		        				IsCvv : true
		                    },
		        			beneficiary: {
		                        required: true,
		                    },
		        			benificiary_name: {
		                        required: true
		                    },
		                    iban: {
		                        required: true,
		                    },
		        			swiftcode: {
		                        required: true,
		                    },
		                    
		             },
		             messages: {
		         		amount: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		     				IsAmount: '<?php echo lang('Please enter a valid Amount'); ?>',
		                 },
		     			acc: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		                 },
		     			type: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		                 },
		     			ccholdername: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		                 }
		     			,
		     			ccnum: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		                 },
		     			expiration_month: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		                 },
		     			expiration_year: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		                 },
		     			cvv: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		     				IsCvv : '<?php echo lang('Please enter a valid cvv'); ?>',
		                 },
		     			beneficiary: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		                 },
		     			benificiary_name: {
		                     required: '<?php echo lang('This field is required.'); ?>'
		                 },
		                 iban: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		                 },
		     			swiftcode: {
		                     required: '<?php echo lang('This field is required.'); ?>',
		                 },
		                 
		          },
		             tooltip_options: {
		                thefield: { placement: 'right' }
		             },

		             submitHandler: function (form) { 
		         	    
		                 $("#withdrawal_submit").hide();
		                 $(".page-loader").show();
		          //       form.submit();
		var token = Math.random().toString().replace('0.', ''); 
		     	 $("input[type=hidden][name=my_token_withdraw_req]").val(token); 
		     	setcookie('form_token_withdraw_req',token,'180');
		     	form.submit();
		    
		             }

		             
		          });			    


  });
});
    </script> 