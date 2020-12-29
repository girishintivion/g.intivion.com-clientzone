<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
if (!$_SERVER['HTTP_REFERER'])
	redirect($this->uri->segment(1).'/dashboard');
	?>
<?php 
// $data['my_array_data'] = json_decode($json_result, TRUE);
/*
if(count($my_array_data) != 0)
{
  foreach ( $my_array_data as $key ) 
  {  	
   $closedPosition = $key;
   $t = new Thistory ();
   $t->ActionType = $closedPosition['ActionType'];
   $t->Amount = $closedPosition['Amount'];
   $t->CloseRate = $closedPosition['CloseRate'];
  //$date_time = substr ( $closedPosition['CloseTime'], 6, 13 );
   $cdate = substr ( $closedPosition['CloseTime'], 6, 10 );
   $t->CloseTime = gmdate("Y-m-d H:i:s", $cdate);
   $ctime = substr ( $closedPosition['CloseTime'], 17, 2 ); 
	//$t->CloseTime = $cdate . ' @ ' . $ctime;
	//	$t->CloseTime = $date_time;
   $t->InstrumentName = $closedPosition['InstrumentName'];
   $t->ProfitInAccountCurrency = $closedPosition['ProfitInAccountCurrency'];
   $tHistory [] = $t;
   }

foreach ( $tHistory as $ti ) {
	
   $trade_hist [] = array (
     $ti->ActionType,
     $ti->Amount,
     $ti->CloseRate,
     $ti->CloseTime,
     $ti->InstrumentName,
     $ti->ProfitInAccountCurrency
   );
 	}
 	
}
*/
?>

<div id="content">
  <div class="container-fluid">
    <h1 class="lg-heading">Trading History</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
          <?php echo form_open('trading-history',array('class'=>'form-horizontal clearfix','name'=>'TradingHistory','id'=>'TradingHistory','method'=>'post'));?>
          <div class="row clearfix">
              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <div class="input-group date" data-provide="datepicker">
                    <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                    <input type="text" class="form-control" placeholder="From Date" id="from_dates"  name="from_date" />
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <div class="input-group date" data-provide="datepicker">
                    <div class="input-group-addon"> <span class="glyphicon glyphicon-calendar"></span> </div>
                    <input type="text" class="form-control" placeholder="To Date" id="to_dates" name="to_date" />
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="form-group">
                  <div class="input-group"> <span class="input-group-addon"><i class="fa fa-arrows-h" aria-hidden="true"></i></span>
                    <select class="form-control" id="maxRows" name="maxRows">
                      <option selected value="">Select transactions</option>
                      <option value="5">5</option>
                      <option value="10">10</option>
                      <option value="15">15</option>
                      <option value="20">20</option>
                      <option value="25">25</option>
                      <option value="30">30</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="btn-box text-right">
                  <button type="submit" class="btn-login" id="tradehist_submit">View</button>

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
          <div class="table-responsive common-table spacetop2x" id="default_view">
            <table class="table">
              <thead>
                <tr>
                  <th>Amount</th>
                  <th>Close Rate</th>
                  <th>Close Time</th>
                  <th>Symbol</th>
                  <th>Profit</th>
                </tr>
              </thead>
              <tbody>
                <?php 
 if (empty($th)) { 
         ?>
                <tr>
                  <td colspan="5"><strong>No trading history records found.</strong></td>
                </tr>
                <?php }    else{ 
 
    foreach ( $th as $trade_history ) {

         ?>
                <tr> 
                  <!--        <td><?php if($trade_history->actionType =="0")
          {
           echo "Buy";
         }
         if($trade_history->actionType =="1")
         {
           echo "Sell";
         } ?></td> -->
                  <td><?php if($trade_history->actionType=="0")
          {
          ?>
                    <i class="fa fa-arrow-up" aria-hidden="true"></i>
                    <?php 
         }
         if($trade_history->actionType =="1")
         {
          ?>
                    <i class="fa fa-arrow-down" aria-hidden="true"></i>
                    <?php 
         } ?>
                    <?php print " ".$trade_history->amount;?></td>
                  <td><?php print $trade_history->closeRate;?></td>
                  <td><?php print $trade_history->closeTime;?></td>
                  <td><?php print $trade_history->instrumentName;?></td>
                  <td><?php print $trade_history->profitInAccountCurrency;?></td>
                </tr>
                <?php



     }
 }?>
              </tbody>
            </table>
          </div>
          <div class="table-responsive common-table spacetop2x" id="onsubmitview" style="display:none;"> </div>
        </div>
      </div>
      
      <!-- Right Sidebar Here -->
      <?php 
      require_once (APPPATH.'views/templates/right-sidebar.php'); ?>
    </div>
  </div>
</div>
<script>
$(function() {

$(document).ready(function(){

	 

	$.validator.addMethod("lesserthan", function(value, element) {
	 	
		   var dateFrom = $('#from_dates').datepicker('getDate');
		    var dateTo = $('#to_dates').datepicker('getDate');

		    let day1 =new Date(dateFrom).getTime(); let day2 = new Date(dateTo).getTime();
		let diff = day2 - day1;
		
		 if(diff < 0)
	   {
			// alert("test");
		return false;
	   }
	   else
		{
	   //	alert("tester");
		return true;
		}
	}, "From Date should be smaller than To Date.");


     $("#TradingHistory").validate({
         rules: {
        	 from_date: { required: true },
        	 to_date: { required: true,lesserthan: "#from_dates"},
        	 maxRows: { required: true },

         },
         messages: {
        	 from_date: { required: '<?php echo lang('This field is required.'); ?>',
        		 lesserthan:'<?php echo lang('From Date should be smaller than To Date.'); ?>'  },
        	 to_date: { required: '<?php echo lang('This field is required.'); ?>', },             
        	 maxRows: { required: '<?php echo lang('This field is required.'); ?>', },
             },
         tooltip_options: {
            thefield: { placement: 'right' }
         },

         submitHandler: function (form) { 
        	  //  $('#default_view').hide();
        	    $(".page-loader").show();
         	$.ajax({
        		// url: 'trading_history_ajax.php',
        		
        		url:"<?= base_url($this->uri->segment(1).'/trading_history/update') ?>",
        		cache: false,
        	      type: "GET",
        	      data : $("#TradingHistory").serialize(),
        	      dataType: "html",
        	    
        	     
        	     success: function(data) {
        //	    alert(data);
        	    $(".page-loader").hide();
        	    $('#default_view').hide();
        	    $('#onsubmitview').show();
        	    	 $('#onsubmitview').html(data);
        	    //	 $('#loader').html(data);
        	     }
        	 });
         }

         
      });



  });
});
    </script> 