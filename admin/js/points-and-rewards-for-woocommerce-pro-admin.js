(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function() {
		$( document ).find( '#wps_wpr_allowed_selected_user_role' ).select2();
		$( document ).find( '#wps_wpr_notification_button_page' ).select2();
		// On License form submit.
		jQuery(document).on('click','#points-and-rewards-for-woocommerce-pro-license-activate',function(e){
			e.preventDefault();
			$( 'div#points-and-rewards-for-woocommerce-pro-ajax-loading-gif' ).css( 'display', 'inline-block' );

			var license_key =  $( 'input#points-and-rewards-for-woocommerce-pro-license-key' ).val();
			points_and_rewards_for_woocommerce_pro_license_request( license_key );
		});
		
		// License Ajax request.
		function points_and_rewards_for_woocommerce_pro_license_request( license_key ) {

			$.ajax({

		        type:'POST',
		        dataType: 'json',
	    		url: license_ajax_object.ajaxurl,

		        data: {
		        	'action': 'points_and_rewards_for_woocommerce_pro_license',
		        	'points_and_rewards_for_woocommerce_pro_purchase_code': license_key,
		        	'points-and-rewards-for-woocommerce-pro-license-nonce': license_ajax_object.license_nonce,
		        },

		        success:function( data ) {

		        	$( 'div#points-and-rewards-for-woocommerce-pro-ajax-loading-gif' ).hide();

		        	if ( false === data.status ) {

	                    $( "p#points-and-rewards-for-woocommerce-pro-license-activation-status" ).css( "color", "#ff3333" );
	                }

	                else {

	                	$( "p#points-and-rewards-for-woocommerce-pro-license-activation-status" ).css( "color", "#42b72a" );
	                }

		        	$( 'p#points-and-rewards-for-woocommerce-pro-license-activation-status' ).html( data.msg );

		        	if ( true === data.status ) {

	                    setTimeout(function() {
	                    	window.location = license_ajax_object.reloadurl;
	                    }, 500);
	                }
		        }
			});
		}
		/*Add Section in the membership section list*/
		jQuery(document).on('click','.wps_wpr_repeat_button',function(){
			// alert();
    		var error = false;
    		var empty_message = '';
    		var count = $('.wps_wpr_repeat:last').data('id');
    		var LevelName = $('#wps_wpr_membership_level_name_'+count).val();
    		var LevelPoints = $('#wps_wpr_membership_level_value_'+count).val();
    		var CategValue = $('#wps_wpr_membership_category_list_'+count).val();
    		var ProdValue = $('#wps_wpr_membership_product_list_'+count).val();
    		var Discount = $('#wps_wpr_membership_discount_'+count).val();
    		if(!(LevelName) || !(LevelPoints) ||  !(CategValue)  || !(Discount))
    		{	
    			
    			if(!(LevelName))
    			{
    				error = true;
    				empty_message+= '<div class="notice notice-error is-dismissible"><p><strong>'+license_ajax_object.LevelName_notice+'</strong></p></div>'; 
    				$('#wps_wpr_membership_level_name_'+count).addClass('wps_wpr_error_notice');

    			}
    			else
    			{
    				$('#wps_wpr_membership_level_name_'+count).removeClass('wps_wpr_error_notice');	
    			}
    			if(!(LevelPoints))
    			{
    				error = true;
    				empty_message+= '<div class="notice notice-error is-dismissible"><p><strong>'+license_ajax_object.LevelValue_notice+'</strong></p></div>'; 
    				$('#wps_wpr_membership_level_value_'+count).addClass('wps_wpr_error_notice');

    			}
    			else
    			{
    				$('#wps_wpr_membership_level_value_'+count).removeClass('wps_wpr_error_notice');
    			}
    			if(!(CategValue))
    			{
    				error = true;
    				empty_message+= '<div class="notice notice-error is-dismissible"><p><strong>'+license_ajax_object.CategValue_notice+'</strong></p></div>';
    				$('#wps_wpr_membership_category_list_'+count).addClass('wps_wpr_error_notice');
    			}
    			else
    			{
    				$('#wps_wpr_membership_category_list_'+count).removeClass('wps_wpr_error_notice');
    			}
    			if(!(Discount))
    			{
    				error = true;
    				empty_message+= '<div class="notice notice-error is-dismissible"><p><strong>'+license_ajax_object.Discount_notice+'</strong></p></div>';
    				$('#wps_wpr_membership_discount_'+count).addClass('wps_wpr_error_notice');
    			}
    			else
    			{
    				$('#wps_wpr_membership_discount_'+count).removeClass('wps_wpr_error_notice');
    			}
    		}
    		if(error)
    		{
	        	$('.notice.notice-error.is-dismissible').each(function(){
                	$(this).remove();
	            });
	            $('.notice.notice-success.is-dismissible').each(function(){
	                $(this).remove();
	            });
	            $('html, body').animate({
                	scrollTop: $(".wps_rwpr_header").offset().top
            	}, 800);
				$(empty_message).insertAfter($('.wps_rwpr_header'));
    		}
    		else
    		{	
    			count = parseInt(count)+1; 
    			var cat_id;
    			var cat_name;
	    		var html = ""; var cat_options = "";
	    		var Categ_option = license_ajax_object.Categ_option;
	    		var cat_name = [];
	    		
	    		for(var key in Categ_option)
	    		{
	    			cat_name = Categ_option[key].cat_name;
	    			cat_id = Categ_option[key].id;
	    			cat_options+='<option value="'+cat_id+'">'+cat_name+'</option>';
	    		}
	    	
	    		html+='<div id ="wps_wpr_parent_repeatable_'+count+'" data-id="'+count+'" class="wps_wpr_repeat">';
	    		html+='<table class="wps_wpr_repeatable_section">';
	    		html+='<tr valign="top"><th scope="row" class="titledesc"><label for="wps_wpr_membership_level_name">'+license_ajax_object.Labeltext+'</label></th>';
	    		html+='<td class="forminp forminp-text"><label for="wps_wpr_membership_level_name"><input type="text" name="wps_wpr_membership_level_name_'+count+'" value="" id="wps_wpr_membership_level_name_'+count+'" class="text_points" required>'+license_ajax_object.Labelname+'</label><input type="button" value='+license_ajax_object.Remove_text+' class="button-primary woocommerce-save-button wps_wpr_remove_button" id="'+count+'"></td></tr>';
	    		html+='<tr valign="top"><th scope="row" class="titledesc"><label for="wps_wpr_membership_level_value">'+license_ajax_object.Points+'</label></th><td class="forminp forminp-text"><label for="wps_wpr_membership_level_value"><input type="number" min="1" value="" name="wps_wpr_membership_level_value_'+count+'" id="wps_wpr_membership_level_value_'+count+'" class="input-text" required></label></td></tr>';
	    		html+='<tr valign="top"><th scope="row" class="titledesc"><label for="wps_wpr_membership_expiration">'+license_ajax_object.Exp_period+'</label></th><td class="forminp forminp-text"><input type="number" min="1" value="" name="wps_wpr_membership_expiration_'+count+'"id="wps_wpr_membership_expiration_'+count+'" class="input-text"><select id="wps_wpr_membership_expiration_days_'+count+'" name="wps_wpr_membership_expiration_days_'+count+'"><option value="days">'+license_ajax_object.Days+'</option><option value="weeks">'+license_ajax_object.Weeks+'</option><option value="months">'+license_ajax_object.Months+'</option><option value="years">'+license_ajax_object.Years+'</option>';
	    		html+='<tr valign="top"><th scope="row" class="titledesc"><label for="wps_wpr_membership_category_list">'+license_ajax_object.Categ_text+'</label></th><td class="forminp forminp-text"><select id="wps_wpr_membership_category_list_'+count+'" required="true" class="wps_wpr_common_class_categ" data-id="'+count+'" multiple="multiple" name="wps_wpr_membership_category_list_'+count+'[]">'+cat_options+'</select></td></tr>';
	    		html+='<tr valign="top"><th scope="row" class="titledesc"><label for="wps_wpr_membership_product_list">'+license_ajax_object.Prod_text+'</label></th><td class="forminp forminp-text"><select id="wps_wpr_membership_product_list_'+count+'" multiple="multiple" name="wps_wpr_membership_product_list_'+count+'[]"></select></td></tr>';
	    		html+='<tr valign="top"><th scope="row" class="titledesc"><label for="wps_wpr_membership_discount">'+license_ajax_object.Discounttext+'</label></th><td class="forminp forminp-text"><label for="wps_wpr_membership_discount"><input type="number" min="1" value="" name="wps_wpr_membership_discount_'+count+'" id="wps_wpr_membership_discount_'+count+'" class="input-text" required></label></td><input type = "hidden" value="'+count+'" name="hidden_count"></tr></table></div>';
				$('.parent_of_div').append(html);
	    		$('#wps_wpr_parent_repeatable_'+count+'').find('#wps_wpr_membership_category_list_'+count).select2();
	    		$('#wps_wpr_parent_repeatable_'+count+'').find('#wps_wpr_membership_product_list_'+count).select2();
    		}
    	});

    	jQuery(document).on('click','.wps_wpr_remove_button',function(){
    		var curr_div = $(this).attr('id');
    		if(curr_div == 0) {
    			$(document).find('.wps_wpr_repeat_button').hide();
    			$('#wps_wpr_membership_setting_enable').attr('checked',false);
    		}
    		$('#wps_wpr_parent_repeatable_'+curr_div).remove();
    		
    	});

    	$(document).on("click",".wps_wpr_submit_per_category",function(){
    			var wps_wpr_categ_id = $(this).attr('id');
    			var wps_wpr_categ_point = $('#wps_wpr_points_to_per_categ_'+wps_wpr_categ_id).val();
    			var data = [];
    			if(wps_wpr_categ_point.length > 0)
    			{
    				if(wps_wpr_categ_point % 1 === 0 && wps_wpr_categ_point > 0)
	    			{
	    				jQuery("#wps_wpr_loader").show();
						data = {
							action:'wps_wpr_per_pro_category',
							wps_wpr_categ_id:wps_wpr_categ_id,
							wps_wpr_categ_point:wps_wpr_categ_point,
							wps_nonce:license_ajax_object.wps_wpr_nonce,
						};

				      	$.ajax({
				  			url: license_ajax_object.ajaxurl, 
				  			type: "POST",  
				  			data: data,
				  			dataType :'json',
				  			success: function(response) 
				  			{	
				  				
					  			if(response.result == 'success')
			                    {	var category_id = response.category_id;
			                    	var categ_point = response.categ_point;
		                        	jQuery('#wps_wpr_points_to_per_categ_'+category_id).val(categ_point);
		                        	$('.notice.notice-error.is-dismissible').each(function(){
									$(this).remove();
									});
									$('.notice.notice-success.is-dismissible').each(function(){
										$(this).remove();
									});
									
									$('html, body').animate({
								        scrollTop: $(".wps_rwpr_header").offset().top
								    }, 800);
								    var assing_message = '<div class="notice notice-success is-dismissible"><p><strong>'+license_ajax_object.cat_success_assign+'</strong></p></div>';
								    $(assing_message).insertBefore($('.wps_wpr_general_wrapper'));
			                        jQuery("#wps_wpr_loader").hide();
			                    }
				  			}
				  		});	
	    			}
	    			else
	    			{
	    				$('.notice.notice-error.is-dismissible').each(function(){
						$(this).remove();
						});
						$('.notice.notice-success.is-dismissible').each(function(){
							$(this).remove();
						});
						
						$('html, body').animate({
					        scrollTop: $(".wps_rwpr_header").offset().top
					    }, 800);
					    var valid_point = '<div class="notice notice-error is-dismissible"><p><strong>'+license_ajax_object.error_assign+'</strong></p></div>';
						$( valid_point ).insertBefore($('.wps_wpr_general_wrapper'));
	    			}
    			}
    			else
    			{	
    				jQuery("#wps_wpr_loader").show();
					data = {
						action:'wps_wpr_per_pro_category',
						wps_wpr_categ_id:wps_wpr_categ_id,
						wps_wpr_categ_point:wps_wpr_categ_point,
						wps_nonce:license_ajax_object.wps_wpr_nonce,
					};
			      	$.ajax({
			  			url: license_ajax_object.ajaxurl, 
			  			type: "POST",  
			  			data: data,
			  			dataType :'json',
			  			success: function(response) 
			  			{	
			  				
				  			if(response.result == 'success')
		                    {	var category_id = response.category_id;
		                    	var categ_point = response.categ_point;
	                        	jQuery('#wps_wpr_points_to_per_categ_'+category_id).val(categ_point);
	                        	$('.notice.notice-error.is-dismissible').each(function(){
								$(this).remove();
								});
								$('.notice.notice-success.is-dismissible').each(function(){
									$(this).remove();
								});
								$('html, body').animate({
							        scrollTop: $(".wps_rwpr_header").offset().top
							    }, 800);
							    var remove_message = '<div class="notice notice-success is-dismissible"><p><strong>'+license_ajax_object.success_remove+'</strong></p></div>';
							    $(remove_message).insertBefore($('.wps_wpr_general_wrapper'));
		                        jQuery("#wps_wpr_loader").hide();
		                    }
			  			}
			  		});
    			}
		});
		/*Assign the product purchase points category wise*/
		$(document).on("click",".wps_wpr_submit_purchase_points_per_category",function(){			
    			var wps_wpr_categ_id = $(this).attr('id');
    			var wps_wpr_categ_point = $('#wps_wpr_purchase_points_cat'+wps_wpr_categ_id).val();
    			var data = [];    			
    			if(wps_wpr_categ_point.length > 0)
    			{
    				if(wps_wpr_categ_point % 1 === 0 && wps_wpr_categ_point > 0)
	    			{
	    				jQuery("#wps_wpr_loader").show();
						data = {
							action:'wps_wpr_per_pro_pnt_category',
							wps_wpr_categ_id:wps_wpr_categ_id,
							wps_wpr_categ_point:wps_wpr_categ_point,
							wps_nonce:license_ajax_object.wps_wpr_nonce,

						};
				      	$.ajax({
				  			url: license_ajax_object.ajaxurl, 
				  			type: "POST",  
				  			data: data,
				  			dataType :'json',
				  			success: function(response) 
				  			{	

					  			if(response.result == 'success')
			                    {	
			                    	var category_id = response.category_id;
			                    	var categ_point = response.categ_point;
		                        	jQuery('#wps_wpr_purchase_points_cat'+category_id).val(categ_point);
		                        	$('.notice.notice-error.is-dismissible').each(function(){
									$(this).remove();
									});
									$('.notice.notice-success.is-dismissible').each(function(){
										$(this).remove();
									});
									
									$('html, body').animate({
								        scrollTop: $(".wps_rwpr_header").offset().top
								    }, 800);
								    var assing_message = '<div class="notice notice-success is-dismissible"><p><strong>'+license_ajax_object.cat_success_assign+'</strong></p></div>';
								     $(assing_message).insertBefore($('.wps_wpr_general_wrapper'));
			                        jQuery("#wps_wpr_loader").hide();
			                    }
				  			}
				  		});	
	    			}
	    			else
	    			{
	    				$('.notice.notice-error.is-dismissible').each(function(){
						$(this).remove();
						});
						$('.notice.notice-success.is-dismissible').each(function(){
							$(this).remove();
						});
						
						$('html, body').animate({
					        scrollTop: $(".wps_rwpr_header").offset().top
					    }, 800);
					    var valid_point = '<div class="notice notice-error is-dismissible"><p><strong>'+license_ajax_object.error_assign+'</strong></p></div>';
					    $(valid_point).insertBefore($('.wps_wpr_general_wrapper'));
					
	    			}
    			}
    			else
    			{	
    				jQuery("#wps_wpr_loader").show();
					data = {
						action:'wps_wpr_per_pro_pnt_category',
						wps_wpr_categ_id:wps_wpr_categ_id,
						wps_wpr_categ_point:wps_wpr_categ_point,
						wps_nonce:license_ajax_object.wps_wpr_nonce,
					};
			      	$.ajax({
			  			url: license_ajax_object.ajaxurl, 
			  			type: "POST",  
			  			data: data,
			  			dataType :'json',
			  			success: function(response) 
			  			{	
				  			if(response.result == 'success')
		                    {	var category_id = response.category_id;
		                    	var categ_point = response.categ_point;
	                        	jQuery('#wps_wpr_purchase_points_cat'+category_id).val(categ_point);
	                        	$('.notice.notice-error.is-dismissible').each(function(){
								$(this).remove();
								});
								$('.notice.notice-success.is-dismissible').each(function(){
									$(this).remove();
								});
								
								$('html, body').animate({
							        scrollTop: $(".wps_rwpr_header").offset().top
							    }, 800);
							    var remove_message = '<div class="notice notice-success is-dismissible"><p><strong>'+license_ajax_object.success_remove+'</strong></p></div>';
							    $(remove_message).insertBefore($('.wps_wpr_general_wrapper'));
		                        jQuery("#wps_wpr_loader").hide();
		                    }
			  			}
			  		});
    			}
		});
		/*Check add more column in the order total settings*/
		$(document).on('click','#wps_wpr_add_more',function() {
		

			if($('#wps_wpr_thankyouorder_enable').prop("checked") == true)
			{
				var response = check_validation_setting();
				if( response == true)
				{
					$('.wps_error').hide();
					var tbody_length = $('.wps_wpr_thankyouorder_tbody > tr').length;
					var new_row = '<tr valign="top"><td class="forminp forminp-text"><label for="wps_wpr_thankyouorder_minimum"><input type="text" name="wps_wpr_thankyouorder_minimum[]" class="wps_wpr_thankyouorder_minimum input-text wc_input_price" required=""></label></td><td class="forminp forminp-text"><label for="wps_wpr_thankyouorder_maximum"><input type="text" name="wps_wpr_thankyouorder_maximum[]" class="wps_wpr_thankyouorder_maximum"></label></td><td class="forminp forminp-text"><label for="wps_wpr_thankyouorder_current_type"><input type="text" name="wps_wpr_thankyouorder_current_type[]" class="wps_wpr_thankyouorder_current_type input-text wc_input_price" required=""></label></td><td class="wps_wpr_remove_thankyouorder_content forminp forminp-text"><input type="button" value="Remove" class="wps_wpr_remove_thankyouorder button" ></td></tr>';
					
					if( tbody_length == 2 )
					{
						$( '.wps_wpr_remove_thankyouorder_content' ).each( function() {
							$(this).show();
						});
					}
					$('.wps_wpr_thankyouorder_tbody').append(new_row);
				}else{
					$('html, body').animate({
						scrollTop: $(".wps_rwpr_header").offset().top
					}, 800);
				
					var remove_message = '<div class="notice notice-error is-dismissible wps_error"><p><strong>'+license_ajax_object.notice_error+'</strong></p></div>';
					
					$(remove_message).insertAfter('.wps_rwpr_header');
				}			
			}
		});
		$( "#mainform" ).submit(function( event ) {
			if ( $('#wps_order_ttol').val() == 1 ) {
				var response = check_validation_setting();
				if ( response == true ) {
					return true;
				} else {
					event.preventDefault();
					$('html, body').animate({
						scrollTop: $(".wps_rwpr_header").offset().top
					}, 800);
					var remove_message = '<div class="notice notice-error is-dismissible"><p><strong>'+license_ajax_object.notice_error+'</strong></p></div>';
					$(remove_message).insertAfter('.wps_rwpr_header');
				}
			} else {
				return true;
			}

		
		 });
		
		/*Check validation of the order total settings*/
		var check_validation_setting = function(){
		
			if($('#wps_wpr_thankyouorder_enable').prop("checked") == true) {
				var tbody_length = $('.wps_wpr_thankyouorder_tbody > tr').length;
				var i = 1;
				var min_arr = []; var max_arr = [];
				var empty_warning = false;
				var is_lesser = false;
				var num_valid = false;
				$('.wps_wpr_thankyouorder_minimum').each(function(){

					min_arr.push($(this).val());

				});
				var i = 1;

				$('.wps_wpr_thankyouorder_maximum').each(function(){

					max_arr.push($(this).val());
					i++;
					
				});
				var i = 1;
				var thankyouorder_arr = [];
				$('.wps_wpr_thankyouorder_current_type').each(function(){
					thankyouorder_arr.push($(this).val());
					if(!$(this).val()){				
						$('.wps_wpr_thankyouorder_tbody > tr:nth-child('+(i+1)+') .wps_wpr_thankyouorder_current_type').css("border-color", "red");
						empty_warning = true;
					}
					else {
						$('.wps_wpr_thankyouorder_tbody > tr:nth-child('+(i+1)+') .wps_wpr_thankyouorder_current_type').css("border-color", "");				
					}
					i++;			
				});
				if(empty_warning) {
					$('.notice.notice-error.is-dismissible').each(function(){
						$(this).remove();
					});
					$('.notice.notice-success.is-dismissible').each(function(){
						$(this).remove();
					});

					$('html, body').animate({
						scrollTop: $(".wps_rwpr_header").offset().top
					}, 800);
					var empty_message = '<div class="notice notice-error is-dismissible"><p><strong>Some Fields are empty!</strong></p></div>';
					$(empty_message).insertBefore($('.wps_wpr_general_wrapper'));
					return;
				}
				var minmaxcheck = false;
				if(max_arr.length >0 && min_arr.length > 0) {
	
					if( min_arr.length == max_arr.length && max_arr.length == thankyouorder_arr.length) {

						for ( var j = 0; j < min_arr.length; j++) {

							if(parseInt(min_arr[j]) > parseInt(max_arr[j])) {
								minmaxcheck = true;
								$('.wps_wpr_thankyouorder_tbody > tr:nth-child('+(j+2)+') .wps_wpr_thankyouorder_minimum').css("border-color", "red");
								$('.wps_wpr_thankyouorder_tbody > tr:nth-child('+(j+2)+') .wps_wpr_thankyouorder_minimum').css("border-color", "red");
							}
							else{
								$('.wps_wpr_thankyouorder_tbody > tr:nth-child('+(j+2)+') .wps_wpr_thankyouorder_minimum').css("border-color", "");
								$('.wps_wpr_thankyouorder_tbody > tr:nth-child('+(j+2)+') .wps_wpr_thankyouorder_minimum').css("border-color", "");
							}
						}
					}
					else {
						$('.notice.notice-error.is-dismissible').each(function(){
							$(this).remove();
						});
						$('.notice.notice-success.is-dismissible').each(function(){
							$(this).remove();
						});

						$('html, body').animate({
							scrollTop: $(".wps_rwpr_header").offset().top
						}, 800);
						var empty_message = '<div class="notice notice-error is-dismissible"><p><strong>Some Fields are empty!</strong></p></div>';
						$(empty_message).insertBefore($('.wps_wpr_general_wrapper'));
						return;
					}
				}
				if(minmaxcheck) {
					$('.notice.notice-error.is-dismissible').each(function(){
						$(this).remove();
					});
					$('.notice.notice-success.is-dismissible').each(function(){
						$(this).remove();
					});

					$('html, body').animate({
						scrollTop: $(".wps_rwpr_header").offset().top
					}, 800);
					var empty_message = '<div class="notice notice-error is-dismissible"><p><strong>Minimum value cannot have value grater than Maximim value.</strong></p></div>';
					$(empty_message).insertAfter($('.wps_wpr_general_wrapper'));
					return;
				}
				return true;
			}
		else {
			return true;
		}
	};

	$(document).on('click','.wps_wpr_remove_thankyouorder',function() {

		if($('#wps_wpr_thankyouorder_enable').prop("checked") == true)
		{
			
			  $(this).closest('tr').remove();
			var tbody_length = $('.wps_wpr_thankyouorder_tbody > tr').length;

			if( tbody_length == 1 ){
				$( '.wps_wpr_remove_thankyouorder_content' ).each( function() {
					$(this).hide();
				});
			}
		}
	});	

	//delete coupon.
	jQuery(document).on('click','.wps_wpr_delete_user_coupon',function(e){
		e.preventDefault();
		var confirm_delete = confirm(license_ajax_object.wps_wpr_confirm_delete);
		if ( confirm_delete ) {

			var coupon_id = jQuery(this).attr('data-id');
			var user_id = jQuery(this).attr('data-user_id');
			jQuery("#wps_wpr_loader").show();
			var data = {
				action:'wps_wpr_delete_user_coupon',
				coupon_id:coupon_id,
				user_id:user_id,
				wps_nonce:license_ajax_object.wps_wpr_nonce,
			};
			$.ajax({
	  			url: license_ajax_object.ajaxurl, 
	  			type: "POST",  
	  			data: data,
	  			dataType :'json',
	  			success: function(response) 
	  			{	
	  				jQuery("#wps_wpr_loader").hide();
	  				window.location.reload();
	  			}
	  		});
		}

	});
    	
    	var secret_key = jQuery(document).find('#wps_wpr_api_secret_key').val();
    	var secret_key1 = jQuery(document).find('.wps_wpr_api_secret_key').val();
    	if (!secret_key ) {
    		jQuery(document).find('#wps_wpr_api_secret_key').closest('.wps_wpr_general_row').hide();
    	}
    	if (jQuery(document).find('#wps_wpr_api_enable').prop( "checked" ) == true && secret_key ) {
    		jQuery(document).find('#wps_wpr_api_secret_key').closest('.wps_wpr_general_row').show();
    	}

    	if (jQuery(document).find('#wps_wpr_api_enable').prop( "checked" ) == true && !secret_key ) {
			jQuery( '#wps_wpr_api_feature' ).show();
		}
		else{
			jQuery( '#wps_wpr_api_feature' ).hide();
		}

		if (jQuery(document).find('#wps_wpr_api_enable').prop( "checked" ) == true && secret_key1) {
			jQuery( '#wps_wpr_api_feature' ).hide();
		}

    	jQuery( document ).on(
			'change',
			'#wps_wpr_api_enable',
			function()
			{
				var secret_key = jQuery(document).find('#wps_wpr_api_secret_key').val();
				var secret_key1 = jQuery(document).find('.wps_wpr_api_secret_key').val();
				console.log(secret_key1);
				if (jQuery( this ).prop( "checked" ) == true && !secret_key && !secret_key1) {
					jQuery( '#wps_wpr_api_feature' ).show();
				}
				else {
					jQuery( '#wps_wpr_api_feature' ).hide();
				}

			}
		);
	});

})( jQuery );

jQuery( document ).on(
	"change",'input',
	'#wps_wpr_cart_price_rate',
	function(){
		var count = jQuery( this ).attr('id');
		var value1 = jQuery(this).val();
	console.log(count);
		console.log(count);
		if(value1<0 && count =='wps_wpr_cart_price_rate'){
			alert('Negative values not allowed');
			
			jQuery("#wps_wpr_cart_price_rate").val("1");
		
			
		}
		
	}
);
jQuery( document ).on(
	"change",'input',
	'#wps_wpr_pro_points_to_all',
	function(){
		var count = jQuery( this ).attr('id');
		var value1 = jQuery(this).val();
	console.log(count);
		console.log(count);
		if(value1<0 && count =='wps_wpr_pro_points_to_all'){
			alert('Negative values not allowed');
			
			jQuery("#wps_wpr_pro_points_to_all").val("1");
		
			
		}
		
	}
);
jQuery( document ).on(
	"change",'input',
	'#wps_wpr_points_expiration_email',
	function(){
		var count = jQuery( this ).attr('id');
		var value1 = jQuery(this).val();
	console.log(count);
		console.log(count);
		if(value1<0 && count =='wps_wpr_points_expiration_email'){
			alert('Negative values not allowed');
			
			jQuery("#wps_wpr_points_expiration_email").val("1");
		
			
		}
		
	}
);
jQuery( document ).on(
	"change",'input',
	'.wps_wpr_thankyouorder_minimum input-text',
	function(){
		var count = jQuery( this ).attr('class');
		var value1 = jQuery(this).val();
	
		if(value1<0 && count =='wps_wpr_thankyouorder_minimum input-text wc_input_price'){
			alert('Negative values not allowed');
			jQuery(this).val("1");
		
			
		}
		
	}
);
jQuery( document ).on(
	"change",'input',
	'.wps_wpr_thankyouorder_maximum',
	function(){
		var count = jQuery( this ).attr('class');
		var value1 = jQuery(this).val();
	console.log(count);
		console.log(value1);
		if(value1<0 && count =='wps_wpr_thankyouorder_maximum'){
			alert('Negative values not allowed');
			jQuery(this).val("1");
		
			
		}
		
	}
);
jQuery( document ).on(
	"change",'input',
	'.wps_wpr_thankyouorder_current_type input-text wc_input_price',
	function(){
		var count = jQuery( this ).attr('class');
		var value1 = jQuery(this).val();
	
		if(value1<0 && count =='wps_wpr_thankyouorder_current_type input-text wc_input_price'){
			alert('Negative values not allowed');
			jQuery(this).val("1");
		
			
		}
		
	}
);

jQuery( document ).on(
	"change",'input',
	'#wps_wpr_coupon_redeem_price',
	function(){
		var count = jQuery( this ).attr('id');
		var value1 = jQuery(this).val();
	
		if(value1<0 && count =='wps_wpr_coupon_redeem_price'){
			alert('Negative values not allowed');
			jQuery(this).val("1");
		
			
		}
		
	}
);

jQuery( document ).on(
	"change",'input',
	'#wps_wpr_product_purchase_price',
	function(){
		var count = jQuery( this ).attr('id');
		var value1 = jQuery(this).val();
	
		if(value1<0 && count =='wps_wpr_product_purchase_price'){
			alert('Negative values not allowed');
			jQuery(this).val("1");
		
			
		}
		
	}
);
jQuery( document ).ready(
	function(){
	
			jQuery( '.notice-dismiss' ).click(
				function(){
			
					jQuery( ".notice-success" ).remove();
				}
			);
	}
);
jQuery(document).on('click','.wps_import',function(e){
	jQuery("#wps_wpr_loader").show();
	e.preventDefault();
	var chunkAndLimit = 80;
	doChunkedImport(0,chunkAndLimit,chunkAndLimit);
});
function doChunkedImport(start,limit,chunkSize ){
	var form_data = new FormData(jQuery('form#mainform')[0]);
	form_data.append( 'action', 'wps_large_scv_import' );
	form_data.append( 'start', start );
	form_data.append( 'limit', limit );
	form_data.append( 'wps_nonce', license_ajax_object.wps_wpr_nonce );
	jQuery.ajax({
		type     : "post",
		dataType : "json",
		url      : license_ajax_object.ajaxurl,
		data     : form_data,
		processData: false,
  		contentType: false,
		success: function(response) {
			jQuery("#wps_wpr_loader").hide();
			if ( ! response.result ) {
				alert('Please choose file.');
				
			} else{
				alert(response.msg);
				location.reload();
			} 
		},
		error : function() {
			jQuery("#wps_wpr_loader").hide();
			alert('Invalid File');
		}
	});
}

// Export points table.
jQuery(document).on('click', '#wps_wpr_export_points_table_data', function() {
	
	jQuery('.wps_wpr_export_table_notice').show();
	jQuery('.wps_wpr_export_user_loader').show();
	jQuery('#wps_wpr_export_points_table_data').attr( 'disabled', true );

	var data = {
		action    : 'wps_wpr_export_points_table',
		wps_nonce : license_ajax_object.wps_wpr_nonce,
	};

	jQuery.ajax({
		url       : license_ajax_object.ajaxurl,
		method    : 'POST',
		data      : data,
		datatType : 'JSON',
		success   : function( response ) {
			
			jQuery('.wps_wpr_export_table_notice').hide();
			jQuery('.wps_wpr_export_user_loader').hide();
			jQuery('#wps_wpr_export_points_table_data').attr( 'disabled', false );

			var file_name   = 'wps_wpr_point_table_data.csv';
			let csv_content = "data:text/csv;charset=utf-8,";

			response.forEach(function (rowArray) {
				let row     = rowArray;
				csv_content += row + "\r\n";
			});

			var encodedUri = encodeURI(csv_content);
			wps_wpr_download_csv(file_name, encodedUri);
		}
	});
});

// This function is used to download csv file.
function wps_wpr_download_csv(file_name, text) {
	var element = document.createElement('a');
	element.setAttribute('href', text);
	element.setAttribute('download', file_name);
	element.style.display = 'none';
	document.body.appendChild(element);
	// automatically run the click event for anchor tag
	element.click();
	document.body.removeChild(element);
}

// This function is used to remove assigned membership from user account.
jQuery(document).on('click', '.wps_wpr_remove_button', function(){
	var count      = jQuery(this).attr('id');
	var level_name = jQuery('#wps_wpr_membership_level_name_'+count).val();

	var data = {
		action     : 'wps_wpr_remove_assigned_membership',
		level_name : level_name,
		wps_nonce  : license_ajax_object.wps_wpr_nonce,
	};

	jQuery.ajax({
		method  : 'POST',
		url     : license_ajax_object.ajaxurl,
		data    : data,
		success : function(response) {
			console.log(response);
		},
	});
});
