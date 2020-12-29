<?php defined('BASEPATH') OR exit('No direct script access allowed');


$balance = $mgtapibalance_result->balance;
$equity = $mgtapibalance_result->equity;
$margin = $mgtapibalance_result->margin;



?>
<style>
.dN { display:none; }
.dB { display:block; }
</style>
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> <?php echo lang("Dashboard"); ?> <small></small> </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i><?php echo lang("Home"); ?> </a></li>
      <li class="active"><?php echo lang("Dashboard"); ?></li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-md-3 pD10">
        <h5 class="title <?php $count = count($result);
                  if($count > 1)
                  {
                  	echo"dB";
                  }
                  else{
                  	echo"dN";
                  }?>"><?php echo lang("Select Your Trading Account"); ?></h5>
      </div>
      <div class="col-md-9">
        <form action="" name="TradingHistory"  id="TradingHistory" method="post">
          <div class="col-md-6 no-padding">
            <div class="form-group">
              <div class="arrow-indicator arrow-indicator-full <?php $count = count($result);
                  if($count > 1)
                  {
                  	echo"dB";
                  }
                  else{
                  	echo"dN";
                  }?>">
                <select class="form-control radius" name= "acc" id="acc">
                  <?php foreach($result  as $r){ ?>
                  <option selected value="<?php print $r->name; ?>"><?php print $r->name; ?>(<?php print $r->trading_platform; ?>)</option>
                  <?php }
                                ?>
                </select>
              </div>
            </div>
          </div>
          <?php 
                        if($_SESSION['user_role'] != "Mt4")
                         { ?>
          <div class="col-md-6">
            <div class="form-group">
              <select class="form-control radius <?php 
                  		if(empty($array))
                  		{
                  			echo"dN";
                  		}
                  		else{
                  			echo"dB";
                  		}
                  		?>" id="date" name="date">
                <option value="Last 7 days"><?php echo lang("Last 7 days"); ?></option>
                <option value="Last 14 days"><?php echo lang("Last 14 days"); ?></option>
                <option value="Last month"><?php echo lang("Last month"); ?></option>
                <option value="All"><?php echo lang("All"); ?></option>
              </select>
            </div>
          </div>
          <?php } ?>
        </form>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-9 col-xs-12 Plr col-sm-12">
        <div id="balancetrading_hide">
          <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4"> 
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $balance; ?></h3>
                <p><?php echo lang('Your Balance');?></p>
              </div>
              <div class="icon"> <i class="fa fa-balance-scale"></i> </div>
              <a href="javascript:void(0);" class="small-box-footer"></a> </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4"> 
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?php echo $equity; ?></h3>
                <p><?php echo lang('YOUR EQUITY');?></p>
              </div>
              <div class="icon"><i class="fa fa-bar-chart-o"></i></div>
              <a href="javascript:void(0);" class="small-box-footer"></a> </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4"> 
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?php echo $margin; ?></h3>
                <p><?php echo lang('Margin Level');?></p>
              </div>
              <div class="icon"><i class="ion ion-pie-graph"></i></div>
              <a href="javascript:void(0);" class="small-box-footer"></a> </div>
          </div>
        </div>
        <div id="balancetrading_show"> </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6"> 
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner mB5">
            <div class="clock">
              <h3>
              <span id="hours" class="fL time"></span>
              <span id="point" class="fL time">:</span>
              <span id="min" class="fL time"></span>
              <span id="point" class="fL time">:</span>
              <span id="sec" class="fL time"></span></h3>
              <div class="clearfix"></div>
              <p id="Date"></p>
            </div>
          </div>
          <div class="icon"><i class="fa fa-calendar"></i></div>
          <a href="javascript:void(0);" class="small-box-footer"></a> </div>
      </div>
      <!-- ./col --> 
    </div>
    <?php
                
                 if($_SESSION['user_role'] != "Mt4")
                 {
                     
                 	$array = json_decode($my_array_data, TRUE);
                    if(empty($array)) 
                    {
                 	$myurl2 = array ();
                 	$myurl2 [] = "Get trading successfully with 0 closed positions";
                 	
                 	$array=array("Message"=>"Get trading successfully with 0 closed positions");
                 	
                    }
                 
                    else
                    {
                 	
                             foreach ( $array as $key ) 
                             {
		
		
			$closedPosition = $key;
			$t = new Thistory ();
			$t->ActionType = $closedPosition['ActionType'];
			$t->Amount = $closedPosition['Amount'];
			$t->CloseRate = $closedPosition['CloseRate'];
	 	  //$date_time = substr ( $closedPosition['CloseTime'], 6, 13 );
			
				$cdate = substr ( $closedPosition['CloseTime'], 6, 10 );
				$ctime = substr ( $closedPosition['CloseTime'], 17, 2 ); 
			
			$t->CloseTime = $cdate . ' @ ' . $ctime;
		//	$t->CloseTime = $date_time;
			$t->InstrumentName = $closedPosition['InstrumentName'];
			$t->ProfitInAccountCurrency = $closedPosition['ProfitInAccountCurrency'];
		
			$tHistory [] = $t;
			
			
		
		                       }	
		
		                      if(!empty($t))
		                      {
			$out = array();
			foreach ($tHistory as $ti)
			{
				$out[]=$ti->InstrumentName;
				 
			}
			 
			$unique = array();
			$unique=array_unique($out);
			 
			 
			$myurl2[]="['Instrument Name','Count']";
			$myurl1[]="['Instrument Name','Profit']";
			 
			foreach($unique as $u)
			{
				 
				//echo $u."</br>";
				$count = 0;
				$ProfitInAccountCurrency = 0;
				foreach ($tHistory as $item)
				{
					if ($item->InstrumentName === $u)
					{
						$count++;
						//echo $count;
						$ProfitInAccountCurrency+=$item->ProfitInAccountCurrency;
						 
						 
					}
					 
					 
				}
			
				$myurl2[]="['".$u."',".$count."]";
				$myurl1[]="['".$u."',".$ProfitInAccountCurrency."]";
			}
			
		                         }
		
		
		
                 	
                 	
                 	
                 	$MP  = new stdClass();
                 	if(isset($MonetaryTransactions->mtAccounts))
                 	{
                 		$MP = $MonetaryTransactions->mtAccounts;
                 			
                 		if(count($MP) >=1)
                 		{
                 				
                 			if(is_object($MP)) {
                 				$tparr1[] =  $MP;
                 			}
                 			else
                 			{
                 				$tparr1 = $MP;
                 			}
                 				
                 			foreach($tparr1 as $mon_t ) {
                 					
                 				if($mon_t->Type == "Deposit")
                 				{
                 					$mt = new Tstatement();
                 					$mt->Amount = $mon_t->Amount;
                 					$mTransaction[] = $mt;
                 				}
                 					
                 				if($mon_t->Type == "Withdrawal")
                 				{
                 					$mt2 = new Tstatement2();
                 						
                 					$mt2->Amount = $mon_t->Amount;
                 						
                 					$mTransaction2[] = $mt2;
                 				}
                 					
                 			}
                 				
                 			$depositArray = array();
                 				
                 			foreach ($mTransaction as $k=>$subArray) {
                 				foreach ($subArray as $id=>$value) {
                 					$depositArray[$id]+=$value;
                 				}
                 			}
                 				
                 			$withdrawArray = array();
                 				
                 			foreach ($mTransaction2 as $k=>$subArray) {
                 				foreach ($subArray as $id=>$value) {
                 					$withdrawArray[$id]+=$value;
                 				}
                 			}
                 				
                 			$deposit = $depositArray['Amount'];
                 				
                 			$withdraw = $withdrawArray['Amount'];
                 				
                 			$total=$deposit - $withdraw;
                 				
                 				
                 			$myurl3[]="['Deposits','Current Balance']";
                 			$myurl3[]="['".$total."',".$current_bal."]";
                 		}
                 		else
                 		{
                 				
                 			$MonetaryTransaction = new MonetaryTransaction();
                 				
                 			$mt = new Tstatement();
                 				
                 			$mt->Amount = $MP->Amount;
                 				
                 			$mTransaction[] = $mt;
                 			?>
    <div id="trading_hide">
      <table class="responsive">
        <tbody>
          <tr>
            <td colspan='6'><?php echo lang('No Trading History items found');?></td>
          </tr>
        </tbody>
      </table>
    </div>
    <?php }	
                 	}
                 
                 
                 
                 
                 	?>
    <?php 	
                 }
                 ?>
    <?php 
if ($array['Message'] == "Get trading successfully with 0 closed positions") {
?>
    <div class="row mT20" style="clear:both; display:none; padding:20px;">
      <p><?php echo lang('No Trading History items found');?></p>
    </div>
    <?php }
                 else{
                 ?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
    <script type="text/javascript">
 
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
      
    function drawChart() {

    	var data = new google.visualization.arrayToDataTable([
       <?php echo (implode(",", $myurl2));?>
      	                                                  	]);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, {width: 279, height: 240});
    }

</script> 
    <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
    <script type="text/javascript">
 
    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);
      
    function drawChart() {

    	var data = new google.visualization.arrayToDataTable([
       <?php echo (implode(",", $myurl1));?>
      	                                                  	]);

      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
      chart.draw(data, {width: 393, height: 240});
    }
</script> 
    <script type="text/javascript" src="https://www.google.com/jsapi"></script> 
    <script type="text/javascript">
	
// Load the Visualization API and the piechart package.
google.load('visualization', '1', {'packages':['corechart']});
// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

function drawChart() {

var data = new google.visualization.arrayToDataTable([
<?php echo (implode(",", $myurl3));?>
]);

// Instantiate and draw our chart, passing in some options.
var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
chart.draw(data, {width: 393, height: 240});
}

</script>
    <div class="row mT20" style="clear:both;" id="trading_hide">
      <div class="col-lg-4 col-md-4 col-sm-12 text-center">
        <div class="panel panel-default"> 
          <!-- Default panel contents -->
          <div class="panel-heading offwhite">
            <h4><?php echo lang('Frequently Traded Pairs');?></h4>
          </div>
          <div class="panel-body" id="chart_div"></div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12 text-center">
        <div class="panel panel-default"> 
          <!-- Default panel contents -->
          <div class="panel-heading offwhite">
            <h4><?php echo lang('Profitable Pairs');?></h4>
          </div>
          <div class="panel-body" id="chart_div1"></div>
        </div>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-12 text-center">
        <div class="panel panel-default "> 
          <!-- Default panel contents -->
          <div class="panel-heading offwhite">
            <h4><?php echo lang('ROI');?></h4>
          </div>
          <div class="panel-body" id="chart_div3"></div>
        </div>
      </div>
    </div>
    <?php } }?>
    <div class="row mT20" style="clear:both; padding:20px; display:none;" id="show_ajax"> </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title mT10"><?php echo lang('Your Trading Accounts');?></h3>
            
          </div>
          <!-- /.box-header -->
          <div class="box-body table-responsive no-padding">
            <div class="col-xs-12">
              <table class="table table-hover table-dashboard">
                <thead>
                  <tr>
                    <th><?php echo lang('Account No');?></th>
                    <th><?php echo lang('Type');?></th>
                    <th><?php echo lang('Base Currency');?></th>
                    <th><?php echo lang('Action');?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <?php foreach($result  as $r){ ?>
                <tr>
                  <td><?php print $r->name; ?></td>
                  <td><?php echo $r->trading_platform; ?></td>
                  <td><?php print $r->currency_code; ?></td>
                  <?php
if($r->trading_platform == 'REAL')
{
?>
                  <td><a class="btn bg-orange hvr-shutter-out-vertical" href="<?= base_url($this->uri->segment(1).'/deposit/deposit_options') ?>"><?php echo lang('Deposit');?></span></td>
                  <?php 
								  }
								  else
								  {
								  ?>
                  <td><a class="btn bg-orange hvr-shutter-out-vertical" href="#"><?php echo lang('Deposit');?></span></td>
                  <?php } ?>
                </tr>
                <?php }
                                ?>
                  </tbody>
                
              </table>
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
    </div>
    
    <!-- /.row --> 
    <!-- Main row --> 
    
    <!-- /.row (main row) --> 
    
  </section>
  <!-- /.content --> 
</div>
<script type="text/javascript">
$(document).ready(function() {
// Create two variable with the names of the months and days in an array
var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ]; 
var dayNames= ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]

// Create a newDate() object
var newDate = new Date();
// Extract the current date from Date object
newDate.setDate(newDate.getDate());
// Output the day, date, month and year   
$('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());

setInterval( function() {
	// Create a newDate() object and extract the seconds of the current time on the visitor's
	var seconds = new Date().getSeconds();
	// Add a leading zero to seconds value
	$("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
	},1000);
	
setInterval( function() {
	// Create a newDate() object and extract the minutes of the current time on the visitor's
	var minutes = new Date().getMinutes();
	// Add a leading zero to the minutes value
	$("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
    },1000);
	
setInterval( function() {
	// Create a newDate() object and extract the hours of the current time on the visitor's
	var hours = new Date().getHours();
	// Add a leading zero to the hours value
	$("#hours").html(( hours < 10 ? "0" : "" ) + hours);
    }, 1000);	
});
</script> 
<script>

$( document ).ready(function() {
            	
            	$('#ajaxed_img').bind('ajaxStart', function(){
            	    $(this).show();
            	}).bind('ajaxStop', function(){
            	    $(this).hide();
            	});
            	
            	 $('#date').change(function(){
            		
            		 $("#trading_hide").hide();
            		 $("#show_ajax").show();
            		 
            	$.ajax({
            		// url: 'trading_history_ajax.php',
            		
            		url:"<?= base_url($this->uri->segment(1).'/dashboard/trading_chart') ?>",
            		cache: false,
            	      type: "GET",
            	      data : $("#TradingHistory").serialize(),
            	      dataType: "html",
            	    
            	     
            	     success: function(data) {
            	    
            	    	 $('#show_ajax').html(data);
            	    	 $('#loader').html(data);
            	     }
            	 });
            	 });
            });
            </script> 
<script>

       $( document ).ready(function() {
    	  
            
            	$('#ajaxed_img').bind('ajaxStart', function(){
            	    $(this).show();
            	}).bind('ajaxStop', function(){
            	    $(this).hide();
            	});
            	
            	 $('#acc').change(function(){
            		
            		 $("#balancetrading_hide").hide();
            		 $("#balancetrading_show").show();
            		
            		 
            	$.ajax({
            		// url: 'trading_history_ajax.php',
            		
            		url:"<?= base_url($this->uri->segment(1).'/dashboard/trading_balance') ?>",
            		cache: false,
            	      type: "GET",
            	      data : $("#TradingHistory").serialize(),
            	      dataType: "html",
            	    
            	     
            	     success: function(data) {
            	    //alert(data);
            	    	 $('#balancetrading_show').html(data);
            	    	 $('#loader').html(data);
            	     }
            	 });
            	 });
            });
            </script> 
