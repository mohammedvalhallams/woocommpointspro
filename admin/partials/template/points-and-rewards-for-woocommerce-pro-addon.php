<?php
/**
 * Notification Addon Template
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
include_once WPS_RWPR_DIR_PATH . '/admin/partials/settings/class-points-rewards-for-woocommerce-settings.php';
include_once POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . '/admin/partials/settings/class-ultimate-woocommerce-points-rewards-admin-settings.php';
$settings_obj = new Ultimate_Woocommerce_Points_Rewards_Admin_Settings();
$admin_obj = new Points_And_Rewards_For_Woocommerce_Pro_Admin( 'ultimate-woocommerce-points-and-rewards', '1.0.0' );
$wps_wpr_save_notification_addon = array(
	array(
		'title' => __( 'Points notification addon', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Enable points notification addon', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'checkbox',
		'desc'  => __( 'Enable points notification addon', 'ultimate-woocommerce-points-and-rewards' ),
		'id'    => 'wps_wpr_enable_notification_addon',
		'desc_tip' => __( 'Check this box to enable points notification addon', 'ultimate-woocommerce-points-and-rewards' ),
		'default'   => 0,
	),
	array(
		'title'    => __( 'Select Position', 'ultimate-woocommerce-points-and-rewards' ),
		'type'     => 'singleSelectDropDownWithKeyvalue',
		'id'       => 'wps_wpr_enable_notification_addon_button_position',
		'class'    => 'wc-enhanced-select',
		'desc'     => __( 'Use this shortcode [wps_wpr_notification_button] to display a pop-up button.', 'ultimate-woocommerce-points-and-rewards' ),
		'desc_tip' => __( 'Select whether you want to display the Notification Button left, right, or want to use a shortcode.  If you select the shortcode [wps_wpr_notification_button] then the notification button will work only on the page you use this code. Note- If you select shortcode then Select Pages will not work plus you can’t change the position of the Button.', 'ultimate-woocommerce-points-and-rewards' ),
		'custom_attribute' => array(
			array(
				'id' => 'right_bottom',
				'name' => __( 'Right Bottom', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'id' => 'left_bottom',
				'name' => __( 'Left Bottom', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'id' => 'top_left',
				'name' => __( 'Top Left', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'id' => 'top_right',
				'name' => __( 'Top Right', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'id' => 'shortcode',
				'name' => __( 'shortcode', 'ultimate-woocommerce-points-and-rewards' ),
			),
		),
	),
	array(
		'title'    => __( 'Select pages', 'ultimate-woocommerce-points-and-rewards' ),
		'type' => 'search&select',
		'multiple' => 'multiple',
		'id'       => 'wps_wpr_notification_button_page',
		'class'    => 'wc-enhanced-select',
		'desc_tip' => __( 'Select the page where you want to display the button, leave blank if you want to display on all the pages.', 'ultimate-woocommerce-points-and-rewards' ),
		'options'  => $admin_obj->wps_wpr_get_pages(),
	),
	array(
		'id'    => 'wps_wpr_notification_color',
		'type'  => 'color',
		'title' => __( 'Select Color Notification Bar', 'ultimate-woocommerce-points-and-rewards' ),
		'desc_tip'  => __( 'You can also choose the color for your Notification Bar.', 'ultimate-woocommerce-points-and-rewards' ),
		'class' => 'input-text',
		'desc'  => __( 'Enable Point Sharing', 'ultimate-woocommerce-points-and-rewards' ),
		'default' => '#ff0000',
	),
	array(
		'title' => __( 'Notification button text', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'text',
		'id'    => 'wps_wpr_notification_button_text',
		'desc_tip' => __( 'Set the notification button text', 'ultimate-woocommerce-points-and-rewards' ),
		'default'   => __( 'Notify Me', 'ultimate-woocommerce-points-and-rewards' ),
	),

	array(
		'type'  => 'sectionend',
	),
);

$current_tab                     = 'wps_wpr_save_notification_addon';
$wps_wpr_save_notification_addon = apply_filters( 'wps_wpr_save_notification_addon', $wps_wpr_save_notification_addon );
if ( isset( $_POST['wps_wpr_save_notification_addon'] ) && isset( $_POST['wps-wpr-nonce'] ) ) {
	$wps_nonce = sanitize_text_field( wp_unslash( $_POST['wps-wpr-nonce'] ) );
	if ( wp_verify_nonce( $wps_nonce, 'wps-wpr-nonce' ) ) {
		if ( 'wps_wpr_save_notification_addon' == $current_tab ) {

			/* Save Settings and check is not empty*/
			$postdata = $settings_obj->check_is_settings_is_not_empty( $wps_wpr_save_notification_addon, $_POST );
			/* End of the save Settings and check is not empty*/
			$general_settings_array = array();
			foreach ( $postdata as $key => $value ) {
				$general_settings_array[ $key ] = $value;
			}
			if ( is_array( $general_settings_array ) && ! empty( $general_settings_array ) ) {
				$general_settings_array = apply_filters( 'wps_wpr_notification_addon_settings', $general_settings_array );
				update_option( 'wps_wpr_notification_addon_settings', $general_settings_array );
			}
			$settings_obj->wps_wpr_settings_saved();
			do_action( 'wps_wpr_notification_addon_settings', $general_settings_array );
		}
	}
}
$general_settings = get_option( 'wps_wpr_notification_addon_settings', array() );

?><?php do_action( 'wps_wpr_add_notice' ); ?>
	<div class="wps_table">
		<div class="wps_wpr_general_wrapper">
				<?php
				foreach ( $wps_wpr_save_notification_addon as $key => $value ) {
					if ( 'title' == $value['type'] ) {
						?>
					<div class="wps_wpr_general_row_wrap">
						<?php $settings_obj->wps_rwpr_generate_heading( $value ); ?>
					<?php } ?>
					<?php if ( 'title' != $value['type'] && 'sectionend' != $value['type'] ) { ?>
				<div class="wps_wpr_general_row">
						<?php $settings_obj->wps_rwpr_generate_label( $value ); ?>
					<div class="wps_wpr_general_content">
						<?php
						$settings_obj->wps_rwpr_generate_tool_tip( $value );
						if ( 'checkbox' == $value['type'] ) {
							$settings_obj->wps_rwpr_generate_checkbox_html( $value, $general_settings );
						}
						if ( 'number' == $value['type'] ) {
							$settings_obj->wps_rwpr_generate_number_html( $value, $general_settings );
						}
						if ( 'text' == $value['type'] ) {
							$settings_obj->wps_rwpr_generate_text_html( $value, $general_settings );
						}
						if ( 'textarea' == $value['type'] ) {
							$settings_obj->wps_rwpr_generate_textarea_html( $value, $general_settings );
						}
						if ( 'color' == $value['type'] ) {
							$settings_obj->wps_rwpr_generate_color_box( $value, $general_settings );
						}
						if ( 'singleSelectDropDownWithKeyvalue' == $value['type'] ) {
							$admin_obj->wps_wpr_generate_single_select_drop_down_with_key_value_pair( $value, $general_settings );
						}
						if ( 'search&select' == $value['type'] ) {
							$admin_obj->wps_wpr_generate_search_select_html( $value, $general_settings );
						}
						if ( 'select' == $value['type'] ) {
							$admin_obj->wps_wpr__select_html( $value, $general_settings );
						}
						?>
					</div>
				</div>
				<?php } ?>
					<?php if ( 'sectionend' == $value['type'] ) : ?>
				</div>	
				<?php endif; ?>
			<?php } ?> 		
		</div>
	</div>
<div class="clear"></div>
<p class="submit">
	<input type="submit" value='<?php esc_html_e( 'Save changes', 'ultimate-woocommerce-points-and-rewards' ); ?>' class="button-primary woocommerce-save-button wps_wpr_save_changes" name="wps_wpr_save_notification_addon">
</p>
