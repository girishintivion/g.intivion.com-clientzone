<nav class="navbar navbar-inverse" id="sidebar">
  <div class="menu-container">
    <div class="crbnMenu">
      <ul class="menu">
        <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/');?>"><i class="fa fa-laptop" aria-hidden="true"></i> <span>Dashboard</span></a></li>
        <?php 	if(strtolower($_SESSION['user_role']) =="real"){
	      $result_crm = get_all_acc_details();
		  $new_addpsp = $result_crm->getAllAccountDetails[0]->dynamicAttributeValue;
		  $lang_smallcase = strtolower($new_addpsp);
		  if ($lang_smallcase =="true" || $lang_smallcase=="yes")
		  {
		?>       
	   <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/deposit/options');?>"><i class="fa fa-credit-card" aria-hidden="true"></i> <span>Deposit Funds</span></a></li>			
        <?php 
		}
		else{?>
		
<li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/cashier6');?>"><i class="fa fa-credit-card" aria-hidden="true"></i> <span>Deposit Funds</span></a></li>					
			
		<?php }
		}?>
        <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/personal-details');?>"><i class="fa fa-user-circle-o" aria-hidden="true"></i> <span>Personal Details</span></a></li>
       <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/change-password');?>"><i class="fa fa-lock" aria-hidden="true"></i> <span>Change Password</span></a></li>
        <?php 	if(strtolower($_SESSION['user_role']) =="real"){?>
        <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/document-upload');?>"><i class="fa fa-file-text" aria-hidden="true"></i> <span>Upload Documents</span></a></li>
        <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/withdrawal-request');?>"><i class="fa fa-external-link" aria-hidden="true"></i> <span>Withdrawal Request</span> </a></li>
        <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/interfund-transfer');?>"><i class="fa fa-random" aria-hidden="true"></i> <span>Interfund Transfer</span> </a></li>
        <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/real-exist');?>"><i class="fa fa-address-card-o" aria-hidden="true"></i> <span>Additional Account</span></a></li>
     
        <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/monetary-statement');?>"><i class="fa fa-line-chart" aria-hidden="true"></i> <span>Monetary Statement</span></a></li>
       <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/pending-withdrawal');?>"><i class="fa fa-retweet" aria-hidden="true"></i> <span>Pending Withdrawals</span></a></li>
        <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/trading-history');?>"><i class="fa fa-area-chart" aria-hidden="true"></i> <span>Trading History</span></a></li>
        <?php }else{?>
         <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/real-exist');?>"><i class="fa fa-address-card-o" aria-hidden="true"></i> <span>Switch To Real</span></a></li>
       <?php }?>
       <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/economic-calendar');?>"><i class="fa fa-calendar-o" aria-hidden="true"></i> <span>Economic Calendar</span></a></li>
       <li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/news');?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i> <span>News</span></a></li>
		<li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/contact');?>"><i class="fa fa-tty" aria-hidden="true"></i> <span>Support</span></a></li>
		<li><a class="nav-link" href="<?php echo base_url($this->uri->segment(1).'/login/logout');?>"><i class="fa fa-sign-out" aria-hidden="true"></i> <span>Logout</span></a></li>
      </ul>
    </div>
  </div>
</nav>
