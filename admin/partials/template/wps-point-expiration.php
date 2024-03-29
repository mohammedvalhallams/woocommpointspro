<?php
/**
 * Points Expiration Template.
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


$wps_points_exipration_array = array(
	array(
		'title' => __( 'Points Expiration', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Enable Points Expiration', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'checkbox',
		'desc'  => __( 'Enable Point Expiration.', 'ultimate-woocommerce-points-and-rewards' ),
		'id'    => 'wps_wpr_points_expiration_enable',
		'desc_tip' => __( 'Check this, If you want to set the expiration period for the Rewarded Points.', 'ultimate-woocommerce-points-and-rewards' ),
		'default'   => 0,
	),
	array(
		'title' => __( 'Show Points expiration on My Account Page', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'checkbox',
		'id'    => 'wps_wpr_points_exp_onmyaccount',
		'class'   => 'input-text',
		'desc_tip' => __( 'Check this, If you want to show the expiration period for the Rewarded Points on My Account Page.', 'ultimate-woocommerce-points-and-rewards' ),
		'default'   => 0,
		'desc'    => __( 'Expiration will get displayed just below the Total Point.', 'ultimate-woocommerce-points-and-rewards' ),
	),
	array(
		'title' => __( 'Set the Required Threshold', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'number',
		'default'   => 1,
		'id'    => 'wps_wpr_points_expiration_threshold',
		'custom_attributes'   => array( 'min' => '"1"' ),
		'class'   => 'input-text wps_wpr_common_width',
		'desc_tip' => __( 'Set the threshold for points expiration, The expiration period will be calculated when the threshold has been reached.', 'ultimate-woocommerce-points-and-rewards' ),
	),
	array(
		'title' => __( 'Set Expiration Time', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'number_text',
		'desc_tip' => __( 'Set the Time-Period for "When do the points need to expire?" It will calculated over the above Threshold Time', 'ultimate-woocommerce-points-and-rewards' ),
		'number_text' => array(
			array(
				'type'  => 'number',
				'id'    => 'wps_wpr_points_expiration_time_num',
				'class'   => 'input-text wps_wpr_common_width',
				'custom_attributes' => array( 'min' => '"1"' ),
				'desc_tip' => __(
					'Set the Time-Period for "When do the points need to expire?" It will calculated over the above Threshold Time',
					'ultimate-woocommerce-points-and-rewards'
				),
			),
			array(
				'id' => 'wps_wpr_points_expiration_time_drop',
				'type' => 'search&select',
				'desc_tip' => __( 'Select those categories which you want to allow to customers for purchase that product through points.', 'ultimate-woocommerce-points-and-rewards' ),
				'options' => $settings_obj->wps_wpr_get_option_of_points(),
			),
		),
	),
	array(
		'title' => __( 'Email Notification(Re-Notify Days)', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'text',
		'custom_attributes' => array( 'min' => '0' ),
		'id'    => 'wps_wpr_points_expiration_email',
		'class' => 'text_points wps_wpr_new_woo_ver_style_text',

		'desc'  => __( 'Days.', 'ultimate-woocommerce-points-and-rewards' ),
		'desc_tip' => __( 'Set the number of days before the Email will get sent out.', 'ultimate-woocommerce-points-and-rewards' ),
	),
	array(
		'type'  => 'sectionend',
	),
	array(
		'title' => __( 'Points Expiry Notifications', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Enter the Message for notifying the user about they have reached their Threshold', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'textarea',
		'custom_attributes' => array(
			'cols' => '"35"',
			'rows' => '"5"',
		),
		'id'    => 'wps_wpr_threshold_notif',
		'class' => 'input-text',
		'desc_tip' => __( 'Entered Message will appears inside the Email Template for notifying the Customer that they have reached the Threshold now they should redeem their Points before it will get expired', 'ultimate-woocommerce-points-and-rewards' ),
		'default' => __( 'You have reached your Threshold and your Total Point is:', 'ultimate-woocommerce-points-and-rewards' ) . ' [TOTALPOINT]' . __( ', which will get expired on', 'ultimate-woocommerce-points-and-rewards' ) . '[EXPIRYDATE]',
		'desc2' => __( 'Use these shortcodes for providing an appropriate message for your customers', 'ultimate-woocommerce-points-and-rewards' ) . __( 'for their Total Points [EXPIRYDATE] for the Expiration Date ', 'ultimate-woocommerce-points-and-rewards' ),
	),
	array(
		'title' => __( 'Re-notify Message before some days', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'textarea',
		'custom_attributes' => array(
			'cols' => '"35"',
			'rows' => '"5"',
		),
		'id'    => 'wps_wpr_re_notification',
		'class' => 'input-text',
		'desc_tip' => __( 'Entered Message will appears inside the Email Template for notifying the Customer that they have left just some days more before the expiration', 'ultimate-woocommerce-points-and-rewards' ),
		'default' => __( 'Do not forget to redeem your points', 'ultimate-woocommerce-points-and-rewards' ) . ' [TOTALPOINT]' . __( 'before it will get expired on', 'ultimate-woocommerce-points-and-rewards' ) . '[EXPIRYDATE]',
		'desc2' => __( 'Use these shortcodes for providing an appropriate message for your customers', 'ultimate-woocommerce-points-and-rewards' ) . __( 'for their Total Points [EXPIRYDATE] for the Expiration Date ', 'ultimate-woocommerce-points-and-rewards' ),
	),
	array(
		'title' => __( 'Message when Points has been Expired', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'textarea',
		'custom_attributes' => array(
			'cols' => '"35"',
			'rows' => '"5"',
		),
		'id'    => 'wps_wpr_expired_notification',
		'class' => 'input-text',
		'desc_tip' => __( 'Entered Message will appears inside the Email Template for notifying the Customer that the Points has been expired', 'ultimate-woocommerce-points-and-rewards' ),
		'default' => __( 'Your Points has been expired, you may earn more Points and use the benefit more', 'ultimate-woocommerce-points-and-rewards' ),
		'desc2' => __( 'This mail will send when your points will get expired', 'ultimate-woocommerce-points-and-rewards' ),
	),
	array(
		'type'  => 'sectionend',
	),
);

$wps_points_exipration_array = apply_filters( 'wps_wpr_points_exprition_settings', $wps_points_exipration_array );
/*Save Settings*/
if ( isset( $_POST['wps_wpr_save_point_expiration'] ) && isset( $_POST['wps-wpr-nonce'] ) ) {
	$wps_wpr_nonce = sanitize_text_field( wp_unslash( $_POST['wps-wpr-nonce'] ) );
	if ( wp_verify_nonce( $wps_wpr_nonce, 'wps-wpr-nonce' ) ) {
		?>
		<?php
		/* Save Settings and check is not empty*/
		$postdata = $settings_obj->check_is_settings_is_not_empty( $wps_points_exipration_array, $_POST );
		/* End of the save Settings and check is not empty*/
		$general_settings_array = array();

		foreach ( $postdata as $key => $value ) {
			$general_settings_array[ $key ] = $value;
		}
		if ( is_array( $general_settings_array ) && ! empty( $general_settings_array ) ) {
			update_option( 'wps_wpr_points_expiration_settings', $general_settings_array );
		}
		$settings_obj->wps_wpr_settings_saved();
		do_action( 'wps_wpr_points_expiration_settings_save_option', $general_settings_array );
	}
}
/*End of the save settings*/
$general_settings = get_option( 'wps_wpr_points_expiration_settings', true );
?>
<?php
if ( ! is_array( $general_settings ) ) :
	$general_settings = array();
endif;
?>
<?php do_action( 'wps_wpr_add_notice' ); ?>
<div class="wps_table">
	<div class="wps_wpr_general_wrapper">
			<?php
			foreach ( $wps_points_exipration_array as $key => $value ) {
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
						if ( 'multiple_checkbox' == $value['type'] ) {
							foreach ( $value['multiple_checkbox'] as $k => $val ) {
								$settings_obj->wps_rwpr_generate_checkbox_html( $val, $general_settings );
							}
						}
						if ( 'text' == $value['type'] ) {
							$settings_obj->wps_rwpr_generate_text_html( $value, $general_settings );
						}
						if ( 'textarea' == $value['type'] ) {
							$settings_obj->wps_rwpr_generate_textarea_html( $value, $general_settings );
						}
						if ( 'number_text' == $value['type'] ) {
							foreach ( $value['number_text'] as $k => $val ) {
								if ( 'number' == $val['type'] ) {
									$settings_obj->wps_rwpr_generate_number_html( $val, $general_settings );
								}
								if ( 'search&select' == $val['type'] ) {
									$settings_obj->wps_wpr_generate_search_select_html( $val, $general_settings );

								}
							}
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
	<input type="submit" value='<?php esc_html_e( 'Save changes', 'ultimate-woocommerce-points-and-rewards' ); ?>' class="button-primary woocommerce-save-button wps_wpr_save_changes" name="wps_wpr_save_point_expiration">
</p>
