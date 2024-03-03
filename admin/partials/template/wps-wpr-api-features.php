<?php
/**
 * API Feature Template.
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

$wps_api_array = array(
	array(
		'title' => __( 'API Settings', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Enable API Features', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'checkbox',
		'desc'  => __( 'Enable API Features.', 'ultimate-woocommerce-points-and-rewards' ),
		'id'    => 'wps_wpr_api_enable',
		'desc_tip' => __( 'Check this, If you want to API feature for points and rewards.', 'ultimate-woocommerce-points-and-rewards' ),
		'default'   => 0,
	),
	array(
		'title' => __( 'API secret key', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'text',
		'id'    => 'wps_wpr_api_secret_key',
		'class' => 'text_points wps_wpr_new_woo_ver_style_text',
		'desc_tip' => __( 'Use this secret key to use API features', 'ultimate-woocommerce-points-and-rewards' ),
		'default' => '',
	),

);
	$wps_api_array = apply_filters( 'wps_wpr_api_feature_before_settings', $wps_api_array );
	global $wpdb;
	$wps_woo_api_keys = $wpdb->get_results( "SELECT consumer_secret FROM {$wpdb->prefix}woocommerce_api_keys", ARRAY_A );

	$wps_wpr_api = array();
if ( isset( $wps_woo_api_keys ) && ! empty( $wps_woo_api_keys ) && is_array( $wps_woo_api_keys ) ) {
	foreach ( $wps_woo_api_keys as $key => $value ) {
		if ( isset( $value['consumer_secret'] ) ) {
			$wps_wpr_api[] = array(
				'id'   => $value['consumer_secret'],
				'name' => $value['consumer_secret'],
			);
		}
	}
	$wps_api_array[] = array(
		'title' => __( 'API secret key', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'singleSelectDropDownWithKeyvalue',
		'id'    => 'wps_wpr_api_secret_key',
		'class' => 'text_points wps_wpr_new_woo_ver_style_text wps_wpr_api_secret_key',
		'custom_attribute' => $wps_wpr_api,
		'desc_tip' => __( 'Use this secret key to use API features', 'ultimate-woocommerce-points-and-rewards' ),
	);
	foreach ( $wps_api_array as $key => $value ) {
		if ( isset( $value['type'] ) && 'text' == $value['type'] && isset( $value['id'] ) && 'wps_wpr_api_secret_key' == $value['id'] ) {
			unset( $wps_api_array[ $key ] );
		}
	}
}


	$wps_api_array[] = array(
		'type'  => 'sectionend',
	);

	$wps_api_array = apply_filters( 'wps_wpr_api_feature_after_settings', $wps_api_array );

	/*Generate and Save Settings*/
	if ( isset( $_POST['wps_wpr_api_feature'] ) && isset( $_POST['wps-wpr-nonce'] ) ) {
		$wps_wpr_nonce = sanitize_text_field( wp_unslash( $_POST['wps-wpr-nonce'] ) );
		if ( wp_verify_nonce( $wps_wpr_nonce, 'wps-wpr-nonce' ) ) {
			?>
			<?php
			/* Save Settings and check is not empty*/
			$postdata = $settings_obj->check_is_settings_is_not_empty( $wps_api_array, $_POST );
			/* End of the save Settings and check is not empty*/
			$general_settings_array = array();

			foreach ( $postdata as $key => $value ) {
				if ( 'wps_wpr_api_secret_key' == $key && empty( $value ) ) {
					$value = 'wps_' . wc_rand_hash();
					$general_settings_array[ $key ] = $value;
				} else {
					$general_settings_array[ $key ] = $value;
				}
			}
			if ( is_array( $general_settings_array ) && ! empty( $general_settings_array ) ) {
				update_option( 'wps_wpr_api_features_settings', $general_settings_array );
			}
			$settings_obj->wps_wpr_settings_saved();

		}
	}
	/*Save Settings*/
	if ( isset( $_POST['wps_wpr_api_feature_save_changes'] ) && isset( $_POST['wps-wpr-nonce'] ) ) {
		$wps_wpr_nonce = sanitize_text_field( wp_unslash( $_POST['wps-wpr-nonce'] ) );
		if ( wp_verify_nonce( $wps_wpr_nonce, 'wps-wpr-nonce' ) ) {
			?>
			<?php
			/* Save Settings and check is not empty*/
			$postdata = $settings_obj->check_is_settings_is_not_empty( $wps_api_array, $_POST );
			/* End of the save Settings and check is not empty*/
			$general_settings_array = array();

			foreach ( $postdata as $key => $value ) {
				$general_settings_array[ $key ] = $value;
			}
			if ( is_array( $general_settings_array ) && ! empty( $general_settings_array ) ) {
				update_option( 'wps_wpr_api_features_settings', $general_settings_array );
			}
			$settings_obj->wps_wpr_settings_saved();
			do_action( 'wps_wpr_api_features_settings_save_option', $general_settings_array );
		}
	}

	/*End of the save settings*/

	 $general_settings = get_option( 'wps_wpr_api_features_settings', true );
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
				foreach ( $wps_api_array as $key => $value ) {
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
						if ( 'text' == $value['type'] ) {

							$settings_obj->wps_rwpr_generate_text_html( $value, $general_settings );
						}
						if ( 'singleSelectDropDownWithKeyvalue' == $value['type'] ) {
							$admin_obj->wps_wpr_generate_single_select_drop_down_with_key_value_pair( $value, $general_settings );
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
		<input type="submit" value='<?php esc_html_e( 'Save Changes', 'ultimate-woocommerce-points-and-rewards' ); ?>' class="button-primary woocommerce-save-button wps_wpr_save_changes" name="wps_wpr_api_feature_save_changes">
		<input type="submit" value='<?php esc_html_e( 'Generate Secret Key', 'ultimate-woocommerce-points-and-rewards' ); ?>' class="button-primary woocommerce-save-button wps_wpr_save_changes" name="wps_wpr_api_feature" id="wps_wpr_api_feature">
	</p>

