<?php 
//defined('BASEPATH') OR exit('No direct script access allowed');
if (!$_SERVER['HTTP_REFERER'])
	redirect($this->uri->segment(1).'/dashboard');
?>

<div id="content">
  <div class="container-fluid">
    <?php 
if(isset($_SESSION['error_pop_mes']))
{
	error_popup();
}
?>
    <div class="clearfix"></div>
    <h1 class="lg-heading">Monetary Statement</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
          <div class="table-responsive common-table" id="defaultview">
            <table class="table">
              <thead>
                <tr>
                  <th><?php echo lang("Account"); ?></th>
                  <th><?php echo lang("Method"); ?></th>
                  <th><?php echo lang("Amount"); ?></th>
                  <th><?php echo lang("Date"); ?></th>           
                  <th><?php echo lang("Currency"); ?></th>
                  <th><?php echo lang("Type"); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php 
                       
                         if (count ((array) $MP) > 0)
							//	if(count($MP) > 0)
								{
								foreach ($MP as $row) { ?>
                <tr>
                 <td><?php print $row->tpName;?></td>
                 <td><?php if($row->paymentMethod=="CreditCard"){print "Credit Card";}else if($row->paymentMethod=="WireTransfer"){print "Wire Transfer";}else{print $row->paymentMethod;}?></td>
                  <td><?php print $row->amount;?></td>
                  <td><?php print $row->creationTime;?></td>
                  <td><?php print $row->code;?></td>
               <!--   <td><?php echo wordwrap($row->type,12,"<br>\n",True);?></td>  --> 
               <td><?php if($row->type == "TransferBetweenTradingPlatformAccounts"){echo "Interfund Transfer";}else{echo $row->type;}?></td>
                </tr>
                <?php }
								}
								else {	
								?>
                <tr>
                  <td colspan="6"><strong>No monetary transaction records found.</strong></td>
                  <?php } ?>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="withdraw-box spacetop2x">
            <button class="btn-login transitions" onclick="f1()"><?php echo 'Pending Withdrawals'; ?></button>

              <div class="page-loader" style="display:none;">
           <div class="cssloader">
    <div class="sh1"></div>
    <div class="sh2"></div>
    <h4 class="lt">loading</h4>
</div>
              </div>
              
          </div>
          <div class="spacetop2x">
            <div class="table-responsive common-table" id="withdrawal_status" style="display:none;">
              <table class="table">
                <thead>
                  <tr>
                    <th><?php echo lang("Account"); ?></th>
                    <th><?php echo lang("Method"); ?></th>
                    <th><?php echo lang("Amount"); ?></th>
                    <th><?php echo lang("Date"); ?></th>
                    <th><?php echo lang("Currency"); ?></th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
               //     print"<pre>"; print_r($MP); print"</pre>";
                
                    foreach ($WS as $ro)
                    {
                    	if ( $ro->type == 'Withdrawal') 
                    	{
                    	  $count = "1";
                    	}
                    	
                    }
                   
                    if( $count == "1")
                    //	if(count($MP) > 0)
								{
							    foreach ($WS as $row)
							    { 
							    	if ($row->type == 'Withdrawal') 
								{
									?>
                  <tr>
                    <td><?php print $row->tpName;?></td>
                    <td><?php if($row->paymentMethod=="WireTransfer"){print "Wire Transfer";}else{print $row->paymentMethod;}?></td>
                    <td><?php print $row->amount;?></td>
                    <td><?php print $row->creationTime;?></td>
                    <td><?php print $row->code;?></td>
                    <td><?php 
                      
                      if($row->approved) 
                      { 
                      	echo "Approved";
					  }
					 else  {
						echo "Pending";
					   }?></td>
                  </tr>
                  <?php } 
						  }
                          } 
                  else {
                    	?>
                  <tr>
                    <td class="text-center" colspan="6"><b>No withdrawal request records found.</b></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Right Sidebar Here -->
      <?php 
      require_once (APPPATH.'views/templates/right-sidebar.php'); ?>
    </div>
  </div>
</div>
<script>


 function f1()
 {

 	    $('#withdrawal_status').show();

 }
 

</script> 