<?php 
$ci=& get_instance();
$ci->load->database();
$ci->db->select('*');
$ci->db->where('name', $_SESSION['username']);
$row = $ci->db->get('crm_user')->row();
$my_country = $row->country;

$ip_country = get_country_details();

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
    <h1 class="lg-heading"><?php echo lang('Deposit Funds');?></h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12 no-padding"> 
        <div class="main-content-wrap white-bg deposit-options-wrap clearfix">
     
      <!--   <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
            <div class="deposit-option"><a href="<?= base_url($this->uri->segment(1).'/cashier11') ?>"><img src="<?= base_url('assets/images/praxis.png')?>" alt="Cashier3" /></a></div>
          </div> -->
           
        <!--    <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
           
            <div class="deposit-option"><a target="_blank" href="https://app.venus.exchange/en-US/Account/Register?promo=EV0904">
            <h4 style="margin-left: 4%; ">Cashier1</h4>
            <img src="<? //= base_url('assets/images/cashier1.jpg')?>" alt="Cashier" /></a></div>
          </div>-->
          
          
          <!-- assets/images/cashier1.jpg -->
          

          
          
          
                     <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
           
            <div class="deposit-option"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier5') ?>">
          
            <img src="" alt="" /><h3>IPAYTOTAL</h3></a></div>
          </div> 
          
          
          
                     <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
           
            <div class="deposit-option"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier1') ?>">
          
            <img src="" alt="" /><h3>PAYCENT</h3></a></div>
          </div> 
          
          
                                <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
           
            <div class="deposit-option"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier7') ?>">
          
            <img src="" alt="" /><h3>GUMBALLPAY</h3></a></div>
          </div> 
          
          
                                                    <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
           
            <div class="deposit-option"><a target="_blank" href="https://bitx-pay.com/?ref=987234&label=MidasWMS">
          
            <img src="" alt="" /><h3>BITXPAY</h3></a></div>
          </div> 
          
          
          
          <?php 
          
      //    if($my_country=='AU'||$ip_country=='AU')
          {
          	?>
          	          <!-- assets/images/cashier1_updated.png -->
         
			<!-- <div class="col-md-4 col-sm-4 col-xs-12">
              <div style="    
    padding: 30px 15px;
    margin: 10px 0;
    background: #f2f2f2;
  
"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier1') ?>"><img class="img-responsive aligncenter" src="<?= base_url('assets/images/flag-logo.png')?>" alt="cashier"></a></div>
<a class="cmn-btn-new" target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier1') ?>" >Deposit Now</a>
            </div>-->
            
             <!--
                     <div class="col-md-6 col-sm-6 col-xs-12">
              <div class="grey-box"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier22') ?>"><img class="img-responsive aligncenter" src="<?= base_url('assets/images/CF.png')?>" alt="cashier"></a></div>
<a class="cmn-btn-new" target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier22') ?>" >Deposit Now</a>
            </div>
            -->
            
          	<?php
          }
          
          ?>
          
          
          
          
          
         <!-- assets/images/enovate.png -->  
          
    <!-- <div class="col-md-4 col-sm-4 col-xs-12">
              <div style="    
    padding: 30px 15px;
    margin: 10px 0;
    background: #f2f2f2;
  
"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier3') ?>"><img class="img-responsive aligncenter" src="<?= base_url('assets/images/cashier1_updated.png')?>" alt="cashier"></a></div>
<a class="cmn-btn-new" target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier3') ?>" >Deposit Now</a>
            </div>-->
            
            
            
            
         <!-- <div class="col-md-4 col-sm-4 col-xs-12">
              <div style="    
    padding: 30px 15px;
    margin: 10px 0;
    background: #f2f2f2;
  
"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier22') ?>"><img class="img-responsive aligncenter" src="<?= base_url('assets/images/CF.png')?>" alt="cashier"></a></div>
<a class="cmn-btn-new" target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier22') ?>" >Deposit Now</a>
            </div>-->
          
          
          
          

          <?php 
          
          if($my_country!='AU')
          {
          	?>
          	          <!-- assets/images/cashier1_updated.png -->
         
			<!-- <div class="col-md-4 col-sm-4 col-xs-12">
              <div style="    
    padding: 30px 15px;
    margin: 10px 0;
    background: #f2f2f2;
  
"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier1') ?>"><img class="img-responsive aligncenter" src="<?= base_url('assets/images/flag-logo.png')?>" alt="cashier"></a></div>
<a class="cmn-btn-new" target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier1') ?>" >Deposit Now</a>
            </div>-->
          	<?php
          }
          
          ?>
            
            
            
            

   <!-- assets/images/qpg.png -->         
            <!-- commented qpay on 04June2020-->  
      <!--      
  <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="grey-box"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier') ?>"><img class="img-responsive aligncenter" src="<?= base_url('assets/images/qpg.png')?>" alt="cashier"></a></div>
<a class="cmn-btn-new" target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier') ?>" >Deposit Now</a>
            </div>
            
            -->
            
            

            
            
            
           
        </div>
      </div>
      <?php 
      require_once (APPPATH.'views/templates/right-sidebar.php'); 
            ?>
    </div>
  </div>
</div>
