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
    <h1 class="lg-heading">Pending Withdrawals</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
          <div class="table-responsive common-table" id="withdrawal_status" >
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
                  <td><?php print $row->paymentMethod;?></td>
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
                  <td class="text-center" colspan="6"><b>No Withdrawal Request records found.</b></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- Right Sidebar Here -->
      <?php 
      require_once (APPPATH.'views/templates/right-sidebar.php'); ?>
    </div>
  </div>
</div>
