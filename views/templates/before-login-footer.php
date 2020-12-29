
</div>
<!-- /#wrapper ends -->
<div class="clearfix"></div>
<footer class="main-footer">
  <div class="container-fluid">© 2019 All Rights Reserved.</div>
</footer>
<!----- Footer -----> 

<!------------- Common JS -------------> 
<script src="<?= base_url('assets/js/bootstrap.min.js')?>"></script> 
<script src="<?= base_url('assets/js/jquery.validate.min.js')?>"></script>
<script src="<?= base_url('assets/js/jquery-validate.bootstrap-tooltip.js')?>"></script> 
<script src="<?= base_url('assets/js/bootstrap-datepicker.js')?>"></script> 
<script src="<?= base_url('assets/js/common.js')?>"></script> 


<script>


jQuery(document).ready(function(){
	/* Custom select design */ 
jQuery('.drop-down').append('<div class="button"></div>');    
jQuery('.drop-down').append('<ul class="select-list"></ul>');    
jQuery('.drop-down select option').each(function() {  
var bg = jQuery(this).css('background-image');    
jQuery('.select-list').append('<li class="clsAnchor"><a href="<?php echo base_url(); ?>'+this.value+'<?php echo substr($_SERVER['REQUEST_URI'],14);
	  ?>"><span value="' + jQuery(this).val() + '" class="' + jQuery(this).attr('class') + '" style=background-image:' + bg + '>' + jQuery(this).text() + '</span></a></li>');   
});    
jQuery('.drop-down .button').html('<span style=background-image:' + jQuery('.drop-down select').find(':selected').css('background-image') + '>' + jQuery('.drop-down select').find(':selected').text() + '</span>' + '<a href="javascript:void(0);" class="select-list-link"><span class="fa fa-angle-down" aria-hidden="true"></span></a>');   
jQuery('.drop-down ul li').each(function() {   
if (jQuery(this).find('span').text() == jQuery('.drop-down select').find(':selected').text()) {  
jQuery(this).addClass('active');       
}      
});     
jQuery('.drop-down .select-list span').on('click', function()
{          
var dd_text = jQuery(this).text();  
var dd_img = jQuery(this).css('background-image'); 
var dd_val = jQuery(this).attr('value');   
jQuery('.drop-down .button').html('<span style=background-image:' + dd_img + '>' + dd_text + '</span>' + '<a href="javascript:void(0);" class="select-list-link"><i class="fa fa-angle-down" aria-hidden="true"></i></a>');      
jQuery('.drop-down .select-list span').parent().removeClass('active');    
jQuery(this).parent().addClass('active');     
$('.drop-down select[name=options]').val( dd_val ); 
$('.drop-down .select-list li').slideUp();     
});       
jQuery('.drop-down .button').on('click','a.select-list-link', function()
{      
jQuery('.drop-down ul li').slideToggle();  
});     
}); 

</script>

</body>
</html>