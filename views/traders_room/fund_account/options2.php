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
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg clearfix">
     
      <!--   <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
            <div class="deposit-option"><a href="<?= base_url($this->uri->segment(1).'/cashier11') ?>"><img src="<?= base_url('assets/images/praxis.png')?>" alt="Cashier3" /></a></div>
          </div> -->
           
        <!--    <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
           
            <div class="deposit-option"><a target="_blenk" href="https://app.venus.exchange/en-US/Account/Register?promo=EV0904">
            <h4 style="margin-left: 4%; ">Cashier1</h4>
            <img src="<? //= base_url('assets/images/cashier1.jpg')?>" alt="Cashier" /></a></div>
          </div>-->
          
		  <!--
           <div class="col-md-6 col-sm-6 col-xs-6 col-xxs">
           
            <div class="deposit-option"><a target="_blenk" href="<?= base_url($this->uri->segment(1).'/cashier1') ?>">
    
            <img src="<?= base_url('assets/images/cashier1.jpg')?>" alt="Cashier1" /></a></div>
          </div> 
          -->
            
			
			<div class="col-md-4 col-sm-4 col-xs-12">
              <div style="    
    padding: 30px 15px;
    margin: 10px 0;
    background: #f2f2f2;
    border: 1px solid #ccc;
"><a target="_blank" href="<?= base_url($this->uri->segment(1).'/cashier1') ?>"><img class="img-responsive aligncenter" src="<?= base_url('assets/images/cashier1_updated.png')?>" alt="cashier"></a></div>
            </div>
           
        </div>
      </div>
      <?php 
      require_once (APPPATH.'views/templates/right-sidebar.php'); 
            ?>
    </div>
  </div>
</div>
