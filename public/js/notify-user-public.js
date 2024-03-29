(function( $ ) {
	'use strict';

	$(document).ready(function(){
		
		/* Make draggable */
		$( "#wps-wps-pr-drag" ).draggable({
			containment: "window"
		});
		/* Make show/hide popup*/
  		$("#wps-pr-mobile-close-popup").hide();

  		//For desktops
		$(document).on('click','#notify_user_gain_tab',function(){
			$('#notify_user_gain_tab').addClass('active');
			$('#notify_user_redeem').removeClass('active');
			$('#notify_user_earn_more_section').css('display','block');
			$('#notify_user_redeem_section').css('display','none');
		});
		$(document).on('click','#notify_user_redeem',function(){
			$('#notify_user_gain_tab').removeClass('active');
			$('#notify_user_redeem').addClass('active');
			$('#notify_user_earn_more_section').css('display','none');
			$('#notify_user_redeem_section').css('display','block');
		});

		//For Mobile Devices
	  	$(document).on('click','#wps-pr-mobile-open-popup',function(){
		    $(".wps-pr-mobile-popup-main-container").addClass("show");
		    $(".wps-pr-mobile-popup-main-container").removeClass("hide");
		    $(this).hide();
		    $("#wps-pr-mobile-close-popup").show();
	  	});
		$(document).on('click','#wps-pr-mobile-close-popup',function(){
		    $(".wps-pr-mobile-popup-main-container").addClass("hide");
		    $(this).hide();
		   	$("#wps-pr-mobile-open-popup").show();
		});
		$(document).on('click','#notify_user_gain_tab_mobile',function(){
			$('#notify_user_gain_tab_mobile').addClass('active');
			$('#notify_user_redeem_mobile').removeClass('active');
			$('#notify_user_earn_more_section_mobile').css('display','block');
			$('#notify_user_redeem_section_mobile').css('display','none');
		});
		$(document).on('click','#notify_user_redeem_mobile',function(){
			$('#notify_user_gain_tab_mobile').removeClass('active');
			$('#notify_user_redeem_mobile').addClass('active');
			$('#notify_user_earn_more_section_mobile').css('display','none');
			$('#notify_user_redeem_section_mobile').css('display','block');
		});
 	});

})( jQuery );
