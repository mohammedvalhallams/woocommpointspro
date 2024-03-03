(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	$( document ).ready(
		
		function(){
			$('#wps_wpr_enter_emaill').hide();
			$('#wps_wpr_point').hide();
			
			$(document).ajaxSuccess(function(event, xhr, settings,data) {
			    var key = "?wc-ajax=add_to_cart";
			    if(settings.url.indexOf(key) != -1){
			      
			    	var add_to_cart_button = jQuery(document).find('a.add_to_cart_button');
			   
			    	jQuery(add_to_cart_button).each(function(index,element){
						if ( jQuery(element).hasClass('added') ) {
							jQuery(element).addClass('testing');
						}
					});
			    }
			  });
		var message = '';
				$( '.wps_wpr_generate_coupon' ).click(
					function(){
						var user_id = $( this ).data( 'id' );
						var message = ''; var html = '';
						$( "#wps_wpr_points_notification" ).html( "" );
						jQuery( "#wps_wpr_loader" ).show();
						var data = {
							action:'wps_wpr_generate_original_coupon',
							user_id:user_id,
							wps_nonce:wps_wpr.wps_wpr_nonce,
						};

						$.ajax(
							{
								url: wps_wpr.ajaxurl,
								type: "POST",
								data: data,
								dataType :'json',
								success: function(response)
							{


									jQuery( "#wps_wpr_loader" ).hide();
									if (response.result == true) {
										   $( '.points_log' ).css( 'display','block' );
										   $( '.points_log' ).html( response.html );
										   $( '#wps_wpr_points_only' ).html( response.points );
										   message = response.message;
										   html = '<b style="color:green;">' + message + '</b>';
										   $( '.wps_current_points' ).html( response.points );
										   var minimum_points = wps_wpr.minimum_points;
										if (response.points < minimum_points) {
											$( '#points_form' ).hide();
										}
									} else {
										   message = response.message;
										   html = '<b style="color:red;">' + message + '</b>';
									}
									$( "#wps_wpr_points_notification" ).html( html );
								}
							}
						);

					}
				);
				/*Generate custom coupon*/
				$( '.wps_wpr_custom_coupon' ).click(
					function(){
						var user_id = $( this ).data( 'id' );
						var user_points = $( '#wps_custom_point_num' ).val();
						var message = ''; var html = '';
						$( "#wps_wpr_points_notification" ).html( "" );
						user_points = parseFloat( user_points );
						if (user_points > 0 && $.isNumeric( user_points )) {
							  jQuery( "#wps_wpr_loader" ).show();
							  var data = {
									action:'wps_wpr_generate_custom_coupon',
									points:user_points,
									user_id:user_id,
									wps_nonce:wps_wpr.wps_wpr_nonce,
							};
							$.ajax(
								{
									url: wps_wpr.ajaxurl,
									type: "POST",
									data: data,
									dataType :'json',
									success: function(response)
								{
										jQuery( "#wps_wpr_loader" ).hide();
										if (response.result == true) {
											$( '.points_log' ).css( 'display','block' );
											$( '.points_log' ).html( response.html );
											$( '#wps_wpr_points_only' ).html( response.points );
											message = response.message;
											html = '<b style="color:green;">' + message + '</b>';
											$( '.wps_current_points' ).html( response.points );
											var minimum_points = wps_wpr.minimum_points;
											if (response.points < minimum_points) {
												$( '#points_form' ).html( wps_wpr.minimum_points_text );
											}
										} else {
											message = response.message;
											html = '<b style="color:red;">' + message + '</b>';
										}
										$( "#wps_wpr_points_notification" ).html( html );
									}
								}
							);
						} else {
							 html = '<b style="color:red;">' + wps_wpr.message + '</b>';
							 $( "#wps_wpr_points_notification" ).html( html );
						}
					}
				);
				/*Send points to another one*/
				$( '#wps_wpr_share_point' ).click(
					function(){
						var user_id = $( this ).data( 'id' );
						var shared_point = $( '#wps_wpr_enter_point' ).val();
						var email_id = $( '#wps_wpr_enter_email' ).val();
						$( "#wps_wpr_shared_points_notification" ).html( "" );
						if (shared_point > 0 ) {
							  jQuery( "#wps_wpr_loader" ).show();
							  var data = {
									action:'wps_wpr_sharing_point_to_other',
									shared_point:shared_point,
									user_id:user_id,
									email_id:email_id,
									wps_nonce:wps_wpr.wps_wpr_nonce,
							};
							$.ajax(
								{
									url: wps_wpr.ajaxurl,
									type: "POST",
									data: data,
									dataType :'json',
									success: function(response)
								{
										jQuery( "#wps_wpr_loader" ).hide();
										if (response !== null && response.result == true) {
											$( '#wps_wpr_points_only' ).html( response.available_points );
											var message = response.message;
											var html = '<b style="color:green;">' + message + '</b>';
										} else {
											var message = response.message;
											var html = '<b style="color:red;">' + message + '</b>';
										}
										$( "#wps_wpr_shared_points_notification" ).html( html );
									}
								}
							);
						} else {
							 var html = '<b style="color:red;">' + wps_wpr.message + '</b>';
							 $( "#wps_wpr_shared_points_notification" ).html( html );
						}

					}
				);
			//email notification
			$('#wps_wpr_button').click(
				function(){
				$('#wps_wpr_enter_emaill').show();
				$('#wps_wpr_point').show();
				}
			);

			
			$('#wps_wpr_point').click(
				function(){
					$('#wps_wpr_point').prop('disabled', true);
					$( "#wps_wpr_shared_points_notificatio" ).hide();
					var email_id = $( '#wps_wpr_enter_emaill' ).val();
					var user_id = $( this ).data( 'id' );
					var data = {
						action:'wps_wpr_email_notify_refer',
						email_id:email_id,
						user_id:user_id,
						wps_wpr_a:wps_wpr_pro.wps_wpr_nonc,
				};
				console.log(data);
				$.ajax(
					{
					url: wps_wpr_pro.ajaxurl,
					type: "POST",
					data: data, 
					success: function(response)
					{
						$('#wps_wpr_point').prop('disabled', false);
						console.log(response.result);
						if (response !== null && response.result==true) {
						
						
							var html = '<b style="color:green;">' + wps_wpr_pro.successmesg + '</b>';
						}else if(response != null && response.message == false) {
							
							var html = '<b style="color:red;">' + wps_wpr_pro.myadminmessage + '</b>';
						}else if(response != null && response.match == false) {
							
							var html = '<b style="color:red;">' + wps_wpr_pro.match_email + '</b>';
						}else if(response != null && response.message == true){
							
							var html = '<b style="color:red;">' + wps_wpr_pro.mymessage + '</b>';
						} else {
						
							var html = '<b style="color:red;">' + wps_wpr_pro.myemailmessage + '</b>';
						}
						$( "#wps_wpr_shared_points_notificatio" ).show();
						$( "#wps_wpr_shared_points_notificatio" ).html( html );
						
					}
								
				}
			);
				});

				/*Make Readonly if selected in backend*/
				$( document ).on(
					'change',
					'#wps_wpr_pro_cost_to_points',
					function(){
						console.log( wps_wpr_pro.make_readonly );
						if (wps_wpr_pro.make_readonly == 1) {
							  $( '#wps_wpr_some_custom_points' ).attr( 'readonly',true );
						}
						if ($( this ).prop( "checked" ) == true) {
							 var wps_wpr_some_custom_points = $( '#wps_wpr_some_custom_points' ).val();
							 $( '.wps_wpr_enter_some_points' ).css( "display","block" );
						} else {
							$( '.wps_wpr_enter_some_points' ).css( "display","none" );
						}
					}
				);
				var pre_variation_id = '';
				$( document ).on(
					'change',
					'.variation_id',
					function(e) {
						e.preventDefault();
						var variation_id = $( this ).val();
						if ( variation_id != null && variation_id > 0 && pre_variation_id != variation_id) {
							  pre_variation_id = variation_id;
							  block( $( '.summary.entry-summary' ) );
							  var data = {
									action:'wps_wpr_append_variable_point',
									variation_id:pre_variation_id,
									wps_nonce:wps_wpr.wps_wpr_nonce,
							};
							$.ajax(
								{
									url: wps_wpr.ajaxurl,
									type: "POST",
									data: data,
									dataType :'json',
									success: function(response)
								{  
										if (response.result == true && response.variable_points > 0) {
											$( '.wps_wpr_variable_points' ).html( response.variable_points );
											$( '.wps_wpr_product_point' ).css( 'display','block' );
										}
										if (response.result_price == "html" && response.variable_price_html != null) {
											$( '.woocommerce-variation-price' ).html( response.variable_price_html );
										}
										if (response.result_point == "product_purchased_using_point" && response.variable_points_cal_html != null) {

											$( '.wps_wpr_variable_pro_pur_using_point' ).html( response.variable_points_cal_html );
											$( '.wps_wpr_variable_pro_pur_using_point' ).css( 'display','block' );
											$('.wps_wpr_purchase_pro_point').css('background', response.color_notification);
										}
										// WPS CUSTOM CODE
										if (response.purchase_pro_pnts_only == "purchased_pro_points" && response.price_html != null) {
											$( '.woocommerce-variation-price' ).html( response.price_html + ' ' + wps_wpr_pro.wps_points_string );
										}
										// WPS CUSTOM CODE
									},
									complete: function()
								{
										unblock( $( '.summary.entry-summary' ) );
									}
								}
							);
						} else if (variation_id != null && variation_id > 0) {
							 block( $( '.summary.entry-summary' ) );
							 var data = {
									action:'wps_pro_purchase_points_only',
									variation_id:variation_id,
									wps_nonce:wps_wpr.wps_wpr_nonce,
							};
							$.ajax(
								{
									url: wps_wpr.ajaxurl,
									type: "POST",
									data: data,
									dataType :'json',
									success: function(response)
								{
										if (response.purchase_pro_pnts_only == "purchased_pro_points" && response.price_html != null) {
											$( '.woocommerce-variation-price' ).html( response.price_html + ' ' + wps_wpr_pro.wps_points_string );
										}
										// WPS CUSTOM CODE
									},
									complete: function()
								{
										unblock( $( '.summary.entry-summary' ) );
									}
								}
							);
						}
					}
				);
			if ($( 'input[id="wps_wpr_pro_cost_to_points"]' ).prop( "checked" ) == true) {
				$( '.wps_wpr_enter_some_points' ).css( "display","block" );
			} else {
				$( '.wps_wpr_enter_some_points' ).css( "display","none" );
			}
			$( document ).on(
				'change',
				'#wps_wgm_price',
				function(){
					var wps_gift_price = $( this ).val();
					if (wps_gift_price != null) {
						$( '.wps_wpr_when_variable_pro' ).html( wps_gift_price );
						$( '#wps_wpr_some_custom_points' ).val( wps_gift_price );
						$( '#wps_wpr_pro_cost_to_points' ).val( wps_gift_price );
					}
				}
			);
			var block = function( $node ) {
				if ( ! is_blocked( $node ) ) {
					$node.addClass( 'processing' ).block(
						{
							message: null,
							overlayCSS: {
								background: '#fff',
								opacity: 0.6
							}
						}
					);
				}
			};
			var is_blocked = function( $node ) {
				return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
			};
			var unblock = function( $node ) {
				$node.removeClass( 'processing' ).unblock();
			};
			/*Removing Custom Points on Cart Subtotal handling via Ajax*/
			$( document ).on(
				'click',
				'#wps_wpr_remove_cart_purchase_via_points',
				function(){
					block( $( '.woocommerce-cart-form' ) );
					var data = {
						action:'wps_wpr_remove_cart_purchase_via_points',
						wps_nonce:wps_wpr.wps_wpr_nonce,
					};
					$.ajax(
						{
							url: wps_wpr.ajaxurl,
							type: "POST",
							data: data,
							dataType :'json',
							complete: function(){
								unblock( $( '.woocommerce-cart-form' ) );
								location.reload();
							}
						}
					);
				}
			);
		}
	);
})( jQuery );
