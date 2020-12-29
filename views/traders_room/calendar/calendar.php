<div id="content">
  <div class="container-fluid">
    <div class="clearfix"></div>
    <h1 class="lg-heading">Economic Calendar</h1>
    <div class="row clearfix">
      <div class="col-md-9 col-sm-12 col-xs-12">
        <div class="main-content-wrap white-bg">
          <div class="widget-wrap"> 
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container">
              <div class="tradingview-widget-container__widget"></div>
              <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-events.js" async>
  {
  "colorTheme": "light",
  "isTransparent": true,
  "width": "100%",
  "height": "510",
  "locale": "en",
  "importanceFilter": "-1,0,1"
}
  </script> 
            </div>
            <!-- TradingView Widget END --></div>
        </div>
      </div>
      <!-- Right Sidebar Here -->
      <?php 
       
        require_once (APPPATH.'views/templates/right-sidebar.php'); 
            ?>
    </div>
  </div>
</div>
