<!-- Page Content Holder -->
<!-- Page Content  -->

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

              <div class="page-loader" style="display:none;">
               <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
              
    <div class="clearfix"></div>
    <h1 class="lg-heading">Welcome, <?php echo $_SESSION['fullname'];?>(<?php echo $_SESSION['username'];?>)</h1>
    
     <?php 
      		// GetAcountBalance Start
 //   $results= $newoutput;
    $results = $mgtapiBalance_res;
    
        //   print"<pre>";print_r($results);print"</pre>"; 
           
            $rows_bal =  array();
           	if(is_object($results)) {
           		$rows_bal[] =  $results;
           	}else{
           		$rows_bal = $results;
           	}

           	$balance = $rows_bal[0]->balance;
            $equity = $rows_bal[0]->equity;
           	$margin = $rows_bal[0]->margin;

           	// GetAcountBalance end
           	//----------------------------------------//
           	// GetMonetaryStatement start
           	$json_result = $mgtapiMonetorystatement;
           	$http_code1 = $mgtapiMonetorystatement_httpinfo;
           	// GetMonetaryStatement end
              
             ?>
        <!--     
    <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6"> 
              <div class="small-box bg-aqua">
                <div class="inner">
                  <p>Your Balance</p><h3><?php echo $balance; ?></h3>
                  
                </div>
              </div>
            </div>
            
            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
              <div class="small-box bg-aqua">
               <div class="inner">
                <p>Your Equity</p><h3><?php echo $equity; ?></h3>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
            <div class="small-box bg-aqua">
             <div class="inner">
               <p>Margin Level</p><h3><?php echo $margin; ?></h3>
             </div>
           </div>
         </div>
    -->
    
    <div class="main-content-wrap">
      <div class="row clearfix">
        <div class="top-btn-wrap cmn-btm-pad clearfix">
          <div class="col-md-4 col-sm-4 col-xs-6 col-xxs"><a class="cmn-btn full-wdth" href="https://download.mql5.com/cdn/web/15321/mt4/digitalcmedia4setup.exe">Download MT4</a></div>
        <!--    <div class="col-md-4 col-sm-4 col-xs-6 col-xxs"><a class="scnd-btn full-wdth" href="<?php echo base_url($this->uri->segment(1).'/Webtrader');?>">Trade Now</a></div> -->
          <div class="col-md-4 col-sm-4 col-xs-6 col-xxs"><a class="cmn-btn full-wdth" href="<?php echo base_url($this->uri->segment(1).'/Real_exist');?>">Add Trading Account</a></div>
        </div>
        <div class="account-table-section cmn-btm-pad">
          <div class="clearfix">
            <div class="col-xs-12">
              <div class="table-responsive common-table dashboard-tbl">
                <table class="table newtable" id="trading_acc_record">
                  <thead>
                    <tr>
                      <th><i class="fa fa-user-plus"></i><br/><span>Account<br/>Type</span></th>
                      <th><i class="fa fa-list"></i><br/><span>Account<br/>Number</span></th>
                      <th><i class="fa fa-globe"></i><br/><span>Base<br/>Currency</span></th>
                      <th><i class="fa fa-lock"></i><br/><span>Password<br/>&nbsp;</span></th>
					  <th><i class="fa fa-money"></i><br/><span>Balance<br/>&nbsp;</span></th>
					  <th><i class="fa fa-bar-chart"></i><br/><span>Equity<br/>&nbsp;</span></th>
					  <th><i class="fa fa-pie-chart"></i><br/><span>Margin<br/>Level</span></th>
					  <th><i class="fa fa-suitcase"></i><br/><span>Trade Now</span><br/>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($newresult as $r){ ?>
                    <tr>
                      <td><?php print $r['type']; ?></td>
                      <td><?php print $r['name']; ?></td>
                      <td><?php print $r['currency_code']; ?></td>
                      <td><a class="link transitions" onclick="send_password()" href="<?= base_url($this->uri->segment(1).'/forgot-password/send/'.$r['name']) ?>">Send Reminder</a></td>
                    
					 <td><?php print $r['balance']; ?></td>
					<td><?php print $r['equity']; ?></td>
					<td><?php print $r['margin']; ?></td>
					 <td><a class="scnd-btn full-wdth" href="<?php echo base_url($this->uri->segment(1).'/Webtrader/new?tpname='.$r['name']);?>">Trade Now</a></td>
					
					</tr>
                    <script>
			function send_password()
			{
				  $(".page-loader").show();
			
			}
            </script>
                    <?php }  ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="middle-info-section cmn-btm-pad">
          <div class="flex-wrap clearfix">
            <div class="col-md-6 col-sm-12 col-xs-12">
              <div class="common-box clearfix">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <h2 class="small-heading">Economic Calendar</h2>
                </div>
                <div class="col-xs-12">
                  <div class="spacetop1x">
                    <div class="widget-wrap"> 
                      <!-- TradingView Widget BEGIN -->
                      <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-events.js" async>
  {
  "colorTheme": "light",
  "isTransparent": true,
  "width": "100%",
  "height": "350",
  "locale": "en",
  "importanceFilter": "-1,0,1"
}
  </script> 
                      </div>
                      <!-- TradingView Widget END --></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12">
              <div class="common-box clearfix">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <h2 class="small-heading">Market News</h2>
                </div>
                <div class="col-xs-12">
                  <div class="spacetop1x">
                    <div class="news-wrap"> <script src="https://news.24-7pressrelease.com/247pr-news-widget.js?widgettitle=&amp;categories=106,489,300,109,112,341,&amp;showRelease=1&amp;width=auto&amp;headlinebold=1&amp;headlinesonly=0&amp;headlinecolor=000000&amp;briefcolor=494949&amp;textcolor=919191&amp;typeface=arial&amp;fontsize=11&amp;width=auto&amp;headlinesepstyle=solid&amp;showimages=0&amp;numstories=10&amp;bgcolor=ffffff&amp;urldest=local"></script> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex-wrap spacetop3x clearfix">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="common-box clearfix">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <h2 class="small-heading">Forex Cross Rates</h2>
                </div>
                <div class="col-xs-12">
                  <div class="spacetop1x">
                    <div class="widget-wrap"> 
                      <!-- TradingView Widget BEGIN -->
                      <div class="tradingview-widget-container">
                        <div class="tradingview-widget-container__widget"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-forex-cross-rates.js" async>
  {
  "width": "100%",
  "height": "350",
  "currencies": [
    "EUR",
    "USD",
    "JPY",
    "GBP",
    "AUD",
    "CNY",
    "ZAR",
    "IDR",
    "RUB"
  ],
  "locale": "en"
}
  </script> 
                      </div>
                      <!-- TradingView Widget END --> 
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script> 
<script src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script> 

<!-- 
<link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/chosen.css')?>">
<script src="<?= base_url('assets/js/chosen.jquery.min.js')?>"></script>
 --> 
<script>
$(function(){
	$(document).ready(function() { 	
$('#trading_acc_record').DataTable({
  paging: true,
//  "lengthMenu": [[3, 5, -1], [3, 5, "All"]],
  "bFilter":false,
  "responsive": true,
  "aaSorting": [[1, "desc"]],
  "pageLength": 4,
  "lengthChange": false,
  "bInfo" : false,
  "pagingType": "numbers",//"simple"
});
});
});
</script>