<div class="login-body">
  <div class="row clearfix">
  
  <!--   <div class="col-md-4 col-sm-4 col-xs-12">
      <div class="deposit-option" id="dreamspay"><a href="#!"><img class="img-responsive aligncenter" src="<?php echo base_url('/assets/images/dreamspay.png');?>" alt="Dreamspay"></a></div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
      <div class="deposit-option" id="voguepay"><a href="#!"><img class="img-responsive aligncenter" src="<?php echo base_url('/assets/images/voguepay.png');?>" alt="Voguepay"></a></div>
    </div> -->
    
    <div class="col-md-12">
      <div class="success-msg" id="success-popupf" style="display:none;">
        <div class="success-msg-wrap">
          <div class="msg-header">
            <button type="button" id="popupclosef"  class="close-btn" data-dismiss="modal">×</button>
          </div>
          <div class="msg-body">
            <div class="message-box clearfix">
              <figure>
                <div id="pdsuccessmsgf"></div>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <div class="success-msg error-msg" id="error-popupf" style="display:none;">
        <div class="success-msg-wrap">
          <div class="msg-header">
            <button type="button" id="popupclose2f" class="close-btn" data-dismiss="modal">×</button>
          </div>
          <div class="msg-body">
            <div class="message-box clearfix">
              <figure>
                <div id="pdfailedmsgf"></div>
              </figure>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="col-md-12 spacetop2x" id="a" style="display:none;"> -->
    <div class="col-md-12 spacetop2x" id="">
            <iframe id="safe-ifrm" border=0 src="<?php echo $redirect_url; ?>" align="center" frameborder="0" width="100%" height="895px"></iframe>

    </div>
  </div>
</div>



<script>

function isSSL() {
    return window.location.protocol == 'https:';
}

function SetCookie(name, value, exdays) {
    var exp = new Date();
    exp.setDate(exp.getDate() + exdays);
    var c_value = encodeURIComponent(value) + ((exdays == null) ? "" : "; expires=" + exp.toUTCString());
    if (isSSL()) { c_value += "; secure"; }
    document.cookie = encodeURIComponent(name) + "=" + c_value;
}

// returns value of cookie or null if cookie does not exist
function GetCookie(name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == name) {
            return decodeURIComponent(y);
        }
    }
}

if ( navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1 ){

    var cashiercookie = GetCookie("CashierCookie");

    if (cashiercookie == null) {
        SetCookie("CashierCookie", true, 1);
        var currentLocation = window.location.href;
        window.location.href = 'https://cashier.praxispay.com/BounceBack.asp?returnUri=' + encodeURIComponent(currentLocation);
    }
}

</script>


