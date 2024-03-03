<?php
/**
 * Assign Points to Products Template
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

$wps_product_purchase_points = array(
	array(
		'title' => __( 'Purchase through Points', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'title',
	),
	array(
		'title' => __( 'Enable Purchase through Points', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'checkbox',
		'desc'  => __( 'Purchase Products through Points', 'ultimate-woocommerce-points-and-rewards' ),
		'id'    => 'wps_wpr_product_purchase_points',
		'desc_tip' => __( 'Check this box to enable purchasing products through points', 'ultimate-woocommerce-points-and-rewards' ),
		'default'   => 0,
	),
	array(
		'title' => __( 'Enable restrictions for above setting', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'checkbox',
		'desc'  => __( 'Allow some of the products for purchasing through points', 'ultimate-woocommerce-points-and-rewards' ),
		'id'    => 'wps_wpr_restrict_pro_by_points',
		'desc_tip' => __( 'Check this box if you want to allow some of the products for purchasing through points not all', 'ultimate-woocommerce-points-and-rewards' ),
		'default'   => 0,
	),
	array(
		'title' => __( 'Select Product Category', 'ultimate-woocommerce-points-and-rewards' ),
		'id' => 'wps_wpr_restrictions_for_purchasing_cat',
		'type' => 'search&select',
		'multiple' => 'multiple',
		'desc_tip' => __( 'Select those categories which you want to allow to customers for purchase that product through points.', 'ultimate-woocommerce-points-and-rewards' ),
		'options' => $settings_obj->wps_wpr_get_category(),
	),
	array(
		'title' => __( 'Enter Text', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'text',
		'id'    => 'wps_wpr_purchase_product_text',
		'class' => 'text_points wps_wpr_new_woo_ver_style_text',
		'desc'  => esc_html__( 'The entered text will get displayed on the Single Product Page', 'ultimate-woocommerce-points-and-rewards' ),
		'desc_tip' => __( 'The entered text will get displayed on the Single Product Page', 'ultimate-woocommerce-points-and-rewards' ),
		'default' => __( 'Use your Points for purchasing this Product', 'ultimate-woocommerce-points-and-rewards' ),
	),
	array(
		'title' => __( 'Purchase Points Conversion', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'number_text',
		'number_text' => apply_filters(
			'wps_wpr_currency_pro_filter',
			array(

				array(
					'type'  => 'text',
					'id'    => 'wps_wpr_product_purchase_price',
					'class'   => 'input-text wps_wpr_new_woo_ver_style_text wc_input_price',
					'custom_attributes' => array( 'min' => '"1"' ),
					'desc_tip' => __(
						'Entered points will be converted to price. (i.e., how many points will be equivalent to the product price)',
						'ultimate-woocommerce-points-and-rewards'
					),
					'desc' => __( '=', 'ultimate-woocommerce-points-and-rewards' ),
					'default' => '1',
					'curr' => get_woocommerce_currency_symbol(),
				),
				array(
					'type'  => 'number',
					'id'    => 'wps_wpr_purchase_points',
					'class'   => 'input-text wc_input_price wps_wpr_new_woo_ver_style_text',
					'custom_attributes' => array( 'min' => '"1"' ),
					'desc_tip' => __(
						'Entered points will be converted to price.(i.e., how many points will be equivalent to the product price)',
						'ultimate-woocommerce-points-and-rewards'
					),
					'desc' => __( 'Points', 'ultimate-woocommerce-points-and-rewards' ),
					'curr' => '',
				),
			)
		),
	),
	array(
		'title' => __( 'Make "Per Product Redemption" Readonly', 'ultimate-woocommerce-points-and-rewards' ),
		'type'  => 'checkbox',
		'desc'  => __( 'Readonly for entering Number of Points for Redemption ', 'ultimate-woocommerce-points-and-rewards' ),
		'id'    => 'wps_wpr_make_readonly',
		'desc_tip' => __( 'Check this box if you want to make the redemption box read-only(where the end-user can enter the number of points they want to redeem)', 'ultimate-woocommerce-points-and-rewards' ),
		'default'   => 0,
	),
	array(
		'type'  => 'sectionend',
	),
);

$current_tab                 = 'wps_wpr_save_product_purchase';
$wps_product_purchase_points = apply_filters( 'wps_wpr_add_product_purchase_points', $wps_product_purchase_points );
if ( isset( $_POST['wps_wpr_save_product_purchase'] ) && isset( $_POST['wps-wpr-nonce'] ) ) {
	$wps_nonce = sanitize_text_field( wp_unslash( $_POST['wps-wpr-nonce'] ) );
	if ( wp_verify_nonce( $wps_nonce, 'wps-wpr-nonce' ) ) {
		if ( 'wps_wpr_save_product_purchase' == $current_tab ) {

			/* Save Settings and check is not empty*/
			$postdata = $settings_obj->check_is_settings_is_not_empty( $wps_product_purchase_points, $_POST );
			/* End of the save Settings and check is not empty*/
			$general_settings_array = array();
			foreach ( $postdata as $key => $value ) {
				$general_settings_array[ $key ] = $value;
			}
			if ( is_array( $general_settings_array ) && ! empty( $general_settings_array ) ) {
				$general_settings_array = apply_filters( 'wps_wpr_general_settings_save_option', $general_settings_array );
				update_option( 'wps_wpr_product_purchase_settings', $general_settings_array );
			}
			$settings_obj->wps_wpr_settings_saved();
			do_action( 'wps_wpr_general_settings_save_option', $general_settings_array );
		}
	}
}

$general_settings = get_option( 'wps_wpr_product_purchase_settings', array() );
if ( ! is_array( $general_settings ) ) :
	$general_settings = array();
endif;
?>
<?php do_action( 'wps_wpr_add_notice' ); ?>
<div class="wps_table">
	<div class="wps_wpr_general_wrapper">
			<?php
			foreach ( $wps_product_purchase_points as $key => $value ) {
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
							if ( 'text' == $val['type'] ) {
								echo esc_html( isset( $val['curr'] ) ? $val['curr'] : '' );
								$settings_obj->wps_rwpr_generate_text_html( $val, $general_settings );

							}
							if ( 'number' == $val['type'] ) {
								$settings_obj->wps_rwpr_generate_number_html( $val, $general_settings );
								echo '<br>';

							}
						}
					}
					if ( 'search&select' == $value['type'] ) {
						$settings_obj->wps_wpr_generate_search_select_html( $value, $general_settings );
					}
					if ( 'select' == $value['type'] ) {
						$settings_obj->wps_wpr__select_html( $value, $general_settings );
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
	<input type="submit" value='<?php esc_html_e( 'Save changes', 'ultimate-woocommerce-points-and-rewards' ); ?>' class="button-primary woocommerce-save-button wps_wpr_save_changes" name="wps_wpr_save_product_purchase">
</p>

<!-- Category Listing -->

<div class="wps_table">
	<h4><?php esc_html_e( 'Purchase Product Through Points Only', 'ultimate-woocommerce-points-and-rewards' ); ?></h4>
	<p class="wps_wpr_section_notice"><?php esc_html_e( 'This is the category wise setting for purchase product from points only, enter some valid points for assigning, leave blank fields for removing assigned points', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
	<div class="wps_wpr_categ_details">
		<table class="form-table wps_wpr_pro_points_setting mwp_wpr_settings">
			<tbody>
				<tr>
					<th class="titledesc"><?php esc_html_e( 'Categories', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
					<th class="titledesc"><?php esc_html_e( 'Enter Points', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
					<th class="titledesc"><?php esc_html_e( 'Assign/Remove', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
				</tr>
				<?php
				$args = array( 'taxonomy' => 'product_cat' );
				$categories = get_terms( $args );
				if ( isset( $categories ) && ! empty( $categories ) ) {
					foreach ( $categories as $category ) {
						$catid                        = $category->term_id;
						$catname                      = $category->name;
						$wps_wpr_purchase_categ_point = get_option( 'wps_wpr_purchase_points_cat' . $catid, '' );
						?>
						<tr>
							<td><?php echo esc_html( $catname ); ?></td>
							<td><input type="number" min="1" name="wps_wpr_purchase_points_per_categ" id="wps_wpr_purchase_points_cat<?php echo esc_html( $catid ); ?>" value="<?php echo esc_html( $wps_wpr_purchase_categ_point ); ?>" class="input-text wps_wpr_new_woo_ver_style_text"></td>
							<td><input type="button" value='<?php esc_html_e( 'Submit', 'ultimate-woocommerce-points-and-rewards' ); ?>' class="button-primary woocommerce-save-button wps_wpr_submit_purchase_points_per_category" name="wps_wpr_submit_purchase_points_per_category" id="<?php echo esc_html( $catid ); ?>"></td>
						</tr>
						<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>
