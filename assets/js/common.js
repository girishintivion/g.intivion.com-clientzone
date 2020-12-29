// Dropdown & Sidebar push
$(document).ready(function(){
        // Show hide popover
        $(".user-detail").click(function(){
            $(this).find(".user-dropdown").slideToggle("fast");
        });
    });
    $(document).on("click", function(event){
        var $trigger = $(".user-detail");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $(".user-dropdown").slideUp("fast");
        }            
    });

$(document).ready(function(){
        // Show hide popover
        $(".notification-detail").hover(function(){
            $(this).find(".notification-dropdown").slideToggle("fast");
        });
    });
    $(document).on("hover", function(event){
        var $trigger = $(".notification-detail");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $(".notification-dropdown").slideUp("fast");
        }            
    });	
	
	$(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });
        });
// Dropdown & Sidebar push ends

/**** Language Dropdown ****/
/*
jQuery(document).ready(function(){
 
jQuery('.drop-down').append('<div class="button"></div>');    
jQuery('.drop-down').append('<ul class="select-list"></ul>');    
jQuery('.drop-down select option').each(function() {  
var bg = jQuery(this).css('background-image');    
jQuery('.select-list').append('<li class="clsAnchor"><a href=""><span value="' + jQuery(this).val() + '" class="' + jQuery(this).attr('class') + '" style=background-image:' + bg + '>' + jQuery(this).text() + '</span></a></li>');   
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
*/
/**** Language Dropdown ****/
		
// Upload Files
function initializeFileUploads() {
    $('.file-upload').change(function () {
        var file = $(this).val();
        $(this).closest('.input-group').find('.file-upload-text').val(file);
    });
    $('.file-upload-btn').click(function () {
        $(this).find('.file-upload').trigger('click');
    });
    $('.file-upload').click(function (e) {
        e.stopPropagation();
    });
}
// On document load:
$(function() {
    initializeFileUploads();
});
// Upload Ends

var url = window.location;

// for sidebar menu entirely
//$('.menu-container .crbnMenu .menu li a').click( function(){
//    if ( $(this).hasClass('current') ) {
//        $(this).removeClass('current');
//    } else {
//        $('li a.current').removeClass('current');
//        $(this).addClass('current');    
//    }
//});

// for sidebar menu active class
$(function(){
		$('.menu-container .crbnMenu .menu li a').filter(function(){return this.href==location.href}).parent().addClass('active').siblings().removeClass('active')
		$('.menu-container .crbnMenu .menu li a').click(function(){
			$(this).parent().addClass('active').siblings().removeClass('active')	
		})
})

// Upload document
$(document).on('click', '.browse', function(){
  var file = $(this).parent().parent().parent().find('.file');
  file.trigger('click');
});
$(document).on('change', '.file', function(){
  $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
});