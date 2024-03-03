/**
 * The admin-specific js functionlity
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    points-and-rewards-for-wooCommerce
 * @subpackage points-and-rewards-for-wooCommerce/admin
 */

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

	$( document ).ready(
		function() {
			$( document ).on(
				'click',
				'.wps_wpr_common_slider',
				function(){
					$( this ).next( '.wps_wpr_points_view' ).slideToggle( 'slow' );
					$( this ).toggleClass( 'active' );
				}
			);
			$( document ).find( '#wps_wpr_restrictions_for_purchasing_cat' ).select2();
			$( document ).find( '#wps_wpr_restrictions_for_purchasing_cat_test' ).select2();
			/* Update user Points in the points Table*/
			$( '.wps_points_update' ).click(
				function(){
					var user_id = $( this ).data( 'id' );
					var user_points = $( document ).find( "#add_sub_points" + user_id ).val();
					var sign = $( document ).find( "#wps_sign" + user_id ).val();
					var reason = $( document ).find( "#wps_remark" + user_id ).val();
					user_points = Number( user_points );
					if (user_points > 0 && user_points === parseInt( user_points, 10 )) {
						if ( reason != '' ) {
							jQuery( "#wps_wpr_loader" ).show();
							var data = {
								action:'wps_wpr_points_update',
								points:user_points,
								user_id:user_id,
								sign:sign,
								reason:reason,
								wps_nonce:wps_wpr_object.wps_wpr_nonce,
							};
							$.ajax(
								{
									url: wps_wpr_object.ajaxurl,
									type: "POST",
									data: data,
									success: function(response)
								{
										jQuery( "#wps_wpr_loader" ).hide();
										$( 'html, body' ).animate(
											{
												scrollTop: $( ".wps_rwpr_header" ).offset().top
											},
											800
										);
										var assing_message = '<div class="notice notice-success is-dismissible"><p><strong>' + wps_wpr_object.success_update + '</strong></p></div>';
										$( assing_message ).insertAfter( $( '.wps_rwpr_header' ) );
										setTimeout( function(){ location.reload(); }, 1000 );
									}
								}
							);
						} else {
							alert( wps_wpr_object.reason );
						}
					} else {
						alert( wps_wpr_object.validpoint );
					}
				}
			);

			$( document ).on(
				'click',
				'.wps_wpr_email_wrapper_text',
				function(){
					$( this ).siblings( '.wps_wpr_email_wrapper_content' ).slideToggle();
				}
			);
			/*This will add new setting*/
			$( document ).on(
				"change",
				".wps_wpr_common_class_categ",
				function(){
					var count = $( this ).data( 'id' );
					var wps_wpr_categ_list = $( '#wps_wpr_membership_category_list_' + count ).val();
					jQuery( "#wps_wpr_loader" ).show();
					var data = {
						action:'wps_wpr_select_category',
						wps_wpr_categ_list:wps_wpr_categ_list,
						wps_nonce:wps_wpr_object.wps_wpr_nonce,
					};
					$.ajax(
						{
							url: wps_wpr_object.ajaxurl,
							type: "POST",
							data: data,
							dataType :'json',
							success: function(response)
						{

								if (response.result == 'success') {
									var product = response.data;
									var option = '';
									for (var key in product) {
										option += '<option value="' + key + '">' + product[key] + '</option>';
									}
									jQuery( "#wps_wpr_membership_product_list_" + count ).html( option );
									jQuery( "#wps_wpr_membership_product_list_" + count ).select2();
									jQuery( "#wps_wpr_loader" ).hide();
								}
							}
						}
					);

				}
			);
			var count = $( '.wps_wpr_repeat:last' ).data( 'id' );
			for (var i = 0; i <= count; i++) {
				 $( document ).find( '#wps_wpr_membership_category_list_' + i ).select2();
				 $( document ).find( '#wps_wpr_membership_product_list_' + i ).select2();
			}

			/*Add a label for purchasing the paid plan*/
			if (wps_wpr_object.check_pro_activate) {
				jQuery( document ).on(
					'click',
					'.wps_wpr_repeat_button',
					function(){
						var html = '';
						$( document ).find( '.wps_wpr_object_purchase' ).remove();
						html = '<div class="wps_wpr_object_purchase"><p>' + wps_wpr_object.pro_text + '</p></div>';
						$( '.parent_of_div' ).append( html );
					}
				);
			}

			/*Add a label for purchasing the paid plan*/
			if (wps_wpr_object.check_pro_activate) {
				$( document ).on(
					'click',
					'#wps_wpr_add_more',
					function() {
						var html = '';
						$( document ).find( '.wps_wpr_object_purchase' ).remove();
						html = '<div class="wps_wpr_object_purchase"><p>' + wps_wpr_object.pro_text + '</p></div>';
						$( html ).insertAfter( '.wp-list-table' );
					}
				);
			}
			jQuery( document ).on(
				'click',
				'.wps_wpr_remove_button',
				function(){
					var curr_div = $( this ).attr( 'id' );
					if (curr_div == 0) {
						$( document ).find( '.wps_wpr_repeat_button' ).hide();
						$( '#wps_wpr_membership_setting_enable' ).attr( 'checked',false );
					}
					$( '#wps_wpr_parent_repeatable_' + curr_div ).remove();

				}
			);

		}
	);

})( jQuery );
/*======================================
	=            Sticky-Sidebar            =
	======================================*/
setTimeout(
	function()
	  {
		if ( jQuery( window ).width() >= 900 ) {
			jQuery( '.wps_rwpr_navigator_template' ).stickySidebar(
				{
					topSpacing: 60,
					bottomSpacing: 60
				}
			);
		}
	},
	500
);

/*=====  End of Sticky-Sidebar  ======*/
jQuery( document ).ready(
	function(){
		jQuery( ".dashicons.dashicons-menu" ).click(
			function(){
				jQuery( ".wps_rwpr_navigator_template" ).toggleClass( "open-btn" );
			}
		);
	}
);
