<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/admin
 * @author     makewebbetter <webmaster@wpswings.com>
 */
class Points_And_Rewards_For_Woocommerce_Pro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param string $hook return the page.
	 */
	public function enqueue_styles( $hook ) {

		// Enqueue styles only on this plugin's menu page.
		wp_enqueue_style( $this->plugin_name, POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'admin/css/points-and-rewards-for-woocommerce-pro-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param string $hook return the page.
	 */
	public function enqueue_scripts( $hook ) {
		if ( isset( $hook ) && 'woocommerce_page_wps-rwpr-setting' !== $hook ) {
			return;
		}
		wp_enqueue_script( $this->plugin_name . 'admin-js', POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'admin/js/points-and-rewards-for-woocommerce-pro-admin.js', array( 'jquery' ), $this->version, false );
		/*Category*/
		$args_cat     = array( 'taxonomy' => 'product_cat' );
		$categories   = get_terms( $args_cat );
		$option_categ = array();
		if ( isset( $categories ) && ! empty( $categories ) ) {
			foreach ( $categories as $category ) {

				$catid   = $category->term_id;
				$catname = $category->name;
				$option_categ[] = array(
					'id' => $catid,
					'cat_name' => $catname,
				);
			}
		}
		$url = admin_url( 'admin.php?page=wps-rwpr-setting' );
		wp_localize_script(
			$this->plugin_name . 'admin-js',
			'license_ajax_object',
			array(
				'ajaxurl'                => admin_url( 'admin-ajax.php' ),
				'reloadurl'              => admin_url( 'admin.php?page=wps-rwpr-setting' ),
				'license_nonce'          => wp_create_nonce( 'points-and-rewards-for-woocommerce-pro-license-nonce-action' ),
				'validpoint'             => __( 'Please enter a valid points', 'ultimate-woocommerce-points-and-rewards' ),
				'Labelname'              => __( 'Enter the Name of the Level', 'ultimate-woocommerce-points-and-rewards' ),
				'Labeltext'              => __( 'Enter Level', 'ultimate-woocommerce-points-and-rewards' ),
				'Points'                 => __( 'Enter Points', 'ultimate-woocommerce-points-and-rewards' ),
				'Categ_text'             => __( 'Select Product Category', 'ultimate-woocommerce-points-and-rewards' ),
				'Remove_text'            => __( 'Remove', 'ultimate-woocommerce-points-and-rewards' ),
				'Categ_option'           => $option_categ,
				'Prod_text'              => __( 'Select Product', 'ultimate-woocommerce-points-and-rewards' ),
				'Discounttext'           => __( 'Enter Discount (%)', 'ultimate-woocommerce-points-and-rewards' ),
				'error_notice'           => __( 'Fields cannot be empty', 'ultimate-woocommerce-points-and-rewards' ),
				'LevelName_notice'       => __( 'Please Enter the Name of the Level', 'ultimate-woocommerce-points-and-rewards' ),
				'LevelValue_notice'      => __( 'Please Enter valid Points', 'ultimate-woocommerce-points-and-rewards' ),
				'CategValue_notice'      => __( 'Please select a category', 'ultimate-woocommerce-points-and-rewards' ),
				'ProdValue_notice'       => __( 'Please select a product', 'ultimate-woocommerce-points-and-rewards' ),
				'Discount_notice'        => __( 'Please enter a valid discount', 'ultimate-woocommerce-points-and-rewards' ),
				'success_assign'         => __( 'Points are assigned successfully!', 'ultimate-woocommerce-points-and-rewards' ),
				'cat_success_assign'     => __( 'This is a category-wise setting for assigning points to product categories. Enter some points for giving, leave blank fields for removing assigned points', 'ultimate-woocommerce-points-and-rewards' ),
				'error_assign'           => __( 'Enter Some Valid Points!', 'ultimate-woocommerce-points-and-rewards' ),
				'success_remove'         => __( 'Points are removed successfully!', 'ultimate-woocommerce-points-and-rewards' ),
				'Days'                   => __( 'Days', 'ultimate-woocommerce-points-and-rewards' ),
				'Weeks'                  => __( 'Weeks', 'ultimate-woocommerce-points-and-rewards' ),
				'Months'                 => __( 'Months', 'ultimate-woocommerce-points-and-rewards' ),
				'Years'                  => __( 'Years', 'ultimate-woocommerce-points-and-rewards' ),
				'Exp_period'             => __( 'Expiration Period', 'ultimate-woocommerce-points-and-rewards' ),
				'notice_error'           => __( 'Please! fill the correct credentials to add more', 'ultimate-woocommerce-points-and-rewards' ),
				'wps_wpr_url'            => $url,
				'reason'                 => __( 'Please enter Remark', 'ultimate-woocommerce-points-and-rewards' ),
				'wps_wpr_nonce'          => wp_create_nonce( 'wps-wpr-verify-nonce' ),
				'wps_wpr_confirm_delete' => __( 'Do you want to delete it?', 'ultimate-woocommerce-points-and-rewards' ),
			)
		);

	}

	/**
	 * This function is used for getting the purchase settings
	 *
	 * @name wps_wpr_get_product_purchase_settings_num
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param string $id for key of the settings.
	 */
	public function wps_wpr_get_points_expiration_settings_num( $id ) {
		$wps_wpr_value = 0;
		$general_settings = get_option( 'wps_wpr_points_expiration_settings', true );
		if ( ! empty( $general_settings[ $id ] ) ) {
			$wps_wpr_value = $general_settings[ $id ];
		}
		return $wps_wpr_value;
	}

	/**
	 * This function is used for getting the product purchase points
	 *
	 * @name wps_wpr_get_general_settings
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param string $id for key of the settings.
	 */
	public function wps_wpr_get_points_expiration_settings( $id ) {
		$wps_wpr_value = '';
		$general_settings = get_option( 'wps_wpr_points_expiration_settings', true );
		if ( ! empty( $general_settings[ $id ] ) ) {
			$wps_wpr_value = $general_settings[ $id ];
		}
		return $wps_wpr_value;
	}

	/**
	 * Validate license.
	 *
	 * @since    1.0.0
	 */
	public function validate_license_handle() {

		/*First check the nonce, if it fails the function will break*/
		check_ajax_referer( 'points-and-rewards-for-woocommerce-pro-license-nonce-action', 'points-and-rewards-for-woocommerce-pro-license-nonce' );

		$wps_license_key = ! empty( $_POST['points_and_rewards_for_woocommerce_pro_purchase_code'] ) ? sanitize_text_field( wp_unslash( $_POST['points_and_rewards_for_woocommerce_pro_purchase_code'] ) ) : '';
		$registered_domain = ! empty( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : home_url();
		/*API query parameters*/
		$api_params = array(
			'slm_action'        => 'slm_activate',
			'secret_key'        => POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_SPECIAL_SECRET_KEY,
			'license_key'       => $wps_license_key,
			'registered_domain' => $registered_domain,
			'product_reference' => 'WPSPK-67567',
			'item_reference'    => urlencode( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_ITEM_REFERENCE ),
		);

		/*Send query to the license manager server*/
		$query = esc_url_raw( add_query_arg( $api_params, POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_SERVER_URL ) );

		$response = wp_remote_get(
			$query,
			array(
				'timeout' => 20,
				'sslverify' => false,
			)
		);

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( isset( $license_data->result ) && 'success' === $license_data->result ) {

			update_option( 'ultimate_woocommerce_points_and_rewards_lcns_key', $wps_license_key );
			update_option( 'ultimate_woocommerce_points_and_rewards_lcns_status', 'true' );

			echo json_encode(
				array(
					'status' => true,
					'msg' => __(
						'Successfully Verified...',
						'points-and-rewards-for-woocommerce-pro'
					),
				)
			);
		} else {

			$error_message = ! empty( $license_data->message ) ? $license_data->message : __( 'License Verification Failed.', 'ultimate-woocommerce-points-and-rewards' );

			echo json_encode(
				array(
					'status' => false,
					'msg' => $error_message,
				)
			);
		}
		wp_die();
	}

	/**
	 * Validate License daily.
	 *
	 * @since 1.0.0
	 */
	public function validate_license_daily() {

		$wps_license_key   = get_option( 'ultimate_woocommerce_points_and_rewards_lcns_key', '' );
		$registered_domain = ! empty( $_SERVER['SERVER_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SERVER_NAME'] ) ) : home_url();
		/*API query parameters*/
		$api_params = array(
			'slm_action'        => 'slm_check',
			'secret_key'        => POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_SPECIAL_SECRET_KEY,
			'license_key'       => $wps_license_key,
			'registered_domain' => $registered_domain,
			'product_reference' => 'WPSPK-67567',
			'item_reference'    => urlencode( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_ITEM_REFERENCE ),
		);

		$query        = esc_url_raw( add_query_arg( $api_params, POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_SERVER_URL ) );
		$wps_response = wp_remote_get(
			$query,
			array(
				'timeout' => 20,
				'sslverify' => false,
			)
		);

		$license_data = json_decode( wp_remote_retrieve_body( $wps_response ) );

		if ( isset( $license_data->result ) && 'success' === $license_data->result && isset( $license_data->status ) && 'active' === $license_data->status ) {

			update_option( 'ultimate_woocommerce_points_and_rewards_lcns_key', $wps_license_key );
			update_option( 'ultimate_woocommerce_points_and_rewards_lcns_status', 'true' );
		} else {

			delete_option( 'ultimate_woocommerce_points_and_rewards_lcns_key' );
			update_option( 'ultimate_woocommerce_points_and_rewards_lcns_status', 'false' );
		}

	}

	/**
	 * Register The settings in the Referral Settings
	 *
	 * @name add_wps_settings
	 * @param array $settings array of the settings.
	 * @since    1.0.0
	 */
	public function add_wps_settings( $settings ) {
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();

		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {
			$new_inserted_array = array(
				array(
					'title' => __( 'Minimum Referrals Required', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'id'    => 'wps_wpr_general_refer_minimum',
					'custom_attributes'   => array( 'min' => '1' ),
					'class'   => 'input-text wps_wpr_new_woo_ver_style_text',
					'desc_tip' => __( 'Minimum number of referrals required to get referral points when the new customer sign-ups.', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title' => __( 'Select page where you want to Redirect', 'ultimate-woocommerce-points-and-rewards' ),
					'type' => 'select',
					'id'       => 'wps_wpr_referral_page',
					'class'    => 'wc-enhanced-select',
					'desc_tip' => __( 'Choose page where you want to redirect user through referral link', 'ultimate-woocommerce-points-and-rewards' ),
					'options'  => $this->wps_wpr_all_pages(),
				),
				array(
					'title' => __( 'Enable Referral Purchase Points', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'default'   => 1,
					'id'    => 'wps_wpr_general_referal_purchase_enable',
					'class'   => 'input-text',
					'desc_tip' => __(
						'Check this box to enable the referral purchase points.',
						'points-and-rewards-for-woocommerce-pro'
					),
					'desc'    => __( 'Enable Referral Purchase Points', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title' => __( 'Referral Purchase Points Type', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'singleSelectDropDownWithKeyvalue',
					'id'    => 'wps_wpr_general_referal_purchase_point_type',
					'class'   => 'input-text wps_wpr_new_woo_ver_style_text',
					'custom_attribute' => array(
						array(
							'id' => 'wps_wpr_fixed_points',
							'name' => __( 'Fixed', 'ultimate-woocommerce-points-and-rewards' ),
						),
						array(
							'id' => 'wps_wpr_percentage_points',
							'name' => __( 'Percentage', 'ultimate-woocommerce-points-and-rewards' ),
						),
					),
					'desc_tip' => __(
						'Select the points type on referral purchase depending upon the order total.',
						'points-and-rewards-for-woocommerce-pro'
					),
				),
				array(
					'title' => __( 'Enter Referral Purchase Points', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'default'   => 1,
					'id'    => 'wps_wpr_general_referal_purchase_value',
					'class'   => 'input-text wps_wpr_new_woo_ver_style_text',
					'custom_attributes' => array( 'min' => '1' ),
					'desc_tip' => __(
						'Entered point will assign to that user by which another user referred from referral link and purchase some products.',
						'points-and-rewards-for-woocommerce-pro'
					),
				),

				array(
					'title' => __( 'Assign Only Referral Purchase Points', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'id'    => 'wps_wpr_general_refer_value_disable',
					'class'   => 'input-text',
					'desc_tip' => __(
						'Check this if you want to assign only purchase points to referred user not referral points.',
						'points-and-rewards-for-woocommerce-pro'
					),
					'desc'    => __( 'Make sure Referral Points & Referral Purchase Points should be enabled.', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title' => __( 'Enable Referral Purchase Limit', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'id'    => 'wps_wpr_general_referal_purchase_limit',
					'class'   => 'input-text',
					'desc'  => __( 'Enable limit for Referral Purchase Option', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip' => __(
						'Check this box to provide some limitation for referral purchase point, where You can set the number of orders for the referee',
						'points-and-rewards-for-woocommerce-pro'
					),
				),
				array(
					'title' => __( 'Set the Number of Orders for Referral Purchase Limit', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes'   => array( 'min' => '1' ),
					'id'    => 'wps_wpr_general_referal_order_limit',
					'class'   => 'input-text',
					'desc_tip' => __(
						'Enter the number of orders, Referee would get assigned only till the limit(no of orders) would be reached',
						'points-and-rewards-for-woocommerce-pro'
					),
				),
				array(
					'title' => __( 'Static Referral Link', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'id'    => 'wps_wpr_referral_link_permanent',
					'class'   => 'input-text',
					'desc_tip' => __( 'Check this box to make the referral key permanent.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'  => __( 'Make Referral Link Permanent', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title' => __( 'Referral Link Expiry', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes'   => array( 'min' => '1' ),
					'id'    => 'wps_wpr_ref_link_expiry',
					'class'   => 'input-text wps_wpr_new_woo_ver_style_text',
					'desc_tip' => __( 'Set the number of days after that the system will not able to remember the referred user anymore', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'  => __( 'Days', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title' => __( 'Enable to Refer via referral Coupon code', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'id'    => 'wps_wpr_general_referral_code_enable',
					'desc'  => __( 'Enable to Refer via referral Coupon code', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Check this box to Refer via referral Coupon code".', 'ultimate-woocommerce-points-and-rewards' ),

				),
				array(
					'title' => __( 'Set the amount for the referral coupon discount', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes'   => array( 'min' => '1' ),
					'id'    => 'wps_wpr_general_referral_code__limit',
					'class'   => 'input-text',
					'desc_tip' => __(
						'Enter the amount for the referral coupon discount',
						'points-and-rewards-for-woocommerce-pro'
					),
				),
				array(
					'title' => __( 'Referral Purchase Coupon Type', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'singleSelectDropDownWithKeyvalue',
					'id'    => 'wps_wpr_general_referal_coupon_purchase_type',
					'class'   => 'input-text wps_wpr_new_woo_ver_style_text',
					'custom_attribute' => array(
						array(
							'id' => 'wps_wpr_fixed_coupon_points',
							'name' => __( 'Fixed', 'ultimate-woocommerce-points-and-rewards' ),
						),
						array(
							'id' => 'wps_wpr_percentage_coupon_points',
							'name' => __( 'Percentage', 'ultimate-woocommerce-points-and-rewards' ),
						),
					),
					'desc_tip' => __(
						'Select Coupon type on referral purchase depending upon the order total.',
						'points-and-rewards-for-woocommerce-pro'
					),
				),
				array(
					'type'  => 'sectionend',
				),
				array(
					'title' => __( 'Comments Points', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'title' => __( 'Enable Comments Points', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'id'    => 'wps_wpr_general_comment_enable',
					'desc'  => __( 'Enable Comments Points for Rewards', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Check this box to enable the Comment Points when a comment is approved.', 'ultimate-woocommerce-points-and-rewards' ),

				),
				array(
					'title' => __( 'Enter Comments Points', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes'   => array( 'min' => '0' ),
					'id'    => 'wps_wpr_general_comment_value',
					'desc_tip' => __( 'The points which new customers will get after their comments are approved.', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title' => __( 'User per post comment', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes'   => array( 'min' => '0' ),
					'id'    => 'wps_wpr_general_comment_per_post_comment',
					'desc_tip' => __( 'This allows the limitation to the number of comments a user can have per post.', 'ultimate-woocommerce-points-and-rewards' ),
				),
			);
			$settings = $this->insert_key_value_pair( $settings, $new_inserted_array, 10 );
		}
		$settings = $this->wps_wpr_cart_add_max_apply_points_settings( $settings );
		return $settings;
	}

	/**
	 * Add the Email Notification Setting in the woocommerce
	 *
	 * @name add_wps_settings
	 * @since    1.0.0
	 * @param array $settings settings of the array.
	 */
	public function wps_wpr_add_email_notification_settings( $settings ) {
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {
			$new_inserted_array = array(
				array(
					'title' => __( 'Comment Points Notification Settings', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'checkbox',
					'id'            => 'wps_wpr_comment_email_enable',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Check this box to enable the comment points notification.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Enable Comment Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'text',
					'id'            => 'wps_wpr_comment_email_subject',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'Comment Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'textarea_email',
					'id'            => 'wps_wpr_comment_email_discription_custom_id',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'You have received', 'ultimate-woocommerce-points-and-rewards' ) . '[Points]' . __( ' points and your total points are', 'ultimate-woocommerce-points-and-rewards' ) . '[Total Points]' . __( '.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Use ', 'ultimate-woocommerce-points-and-rewards' ) . '[Points]' . __( ' shortcode in place of comment points ', 'ultimate-woocommerce-points-and-rewards' ) . '[USERNAME]' . __( ' shortcode in place of username ', 'ultimate-woocommerce-points-and-rewards' ) . '[Refer Points]' . __( ' shortcode in place of Referral points.', 'ultimate-woocommerce-points-and-rewards' ) . '[Per Currency Spent Points]' . __( 'in place of per currency spent points and', 'ultimate-woocommerce-points-and-rewards' ) . '[Total Points]' . __( 'shortcode in place of Total Points.', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'type'  => 'sectionend',
				),
				array(
					'title' => __( 'Referral Purchase Points Notification Settings', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'checkbox',
					'id'            => 'wps_wpr_referral_purchase_email_enable',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Check this box to enable the referral purchase points notification.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Enable Referral Purchase Notification', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'text',
					'id'            => 'wps_wpr_referral_purchase_email_subject',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'Referral Purchase Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'textarea_email',
					'id'            => 'wps_wpr_referral_purchase_email_discription_custom_id',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'You have received ', 'ultimate-woocommerce-points-and-rewards' ) . '[Points]' . __( ' points and your total points are ', 'ultimate-woocommerce-points-and-rewards' ) . '[Total Points]',
					'desc'          => __( 'Use ', 'ultimate-woocommerce-points-and-rewards' ) . '[Points]' . __( ' shortcode in place of Referral Purchase Points ', 'ultimate-woocommerce-points-and-rewards' ) . '[Refer Points]' . __( ' in place of Referral points', 'ultimate-woocommerce-points-and-rewards' ) . ' [Per Currency Spent Points]' . __( ' in place of Per Currency spent points and ', 'ultimate-woocommerce-points-and-rewards' ) . '[Total Points]' . __( ' shortcode in place of Total Points.', 'ultimate-woocommerce-points-and-rewards' ),

				),
				array(
					'type'  => 'sectionend',
				),
				array(
					'title' => __( "Deduct 'Per Currency Spent' Point Notification", 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'checkbox',
					'id'            => 'wps_wpr_deduct_per_currency_point_enable',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Check this box to enable the Deduct Per Currency Spent points notification.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Enable Deduct Per Currency Spent Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'text',
					'id'            => 'wps_wpr_deduct_per_currency_point_subject',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'Your Points have been Deducted', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'textarea_email',
					'id'            => 'wps_wpr_deduct_per_currency_point_description',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'Your [DEDUCTEDPOINT] has been deducted from your total points as you have request for your refund, and your Total Point are [TOTALPOINTS].', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Use ', 'ultimate-woocommerce-points-and-rewards' ) . '[DEDUCTEDPOINT]' . __( ' shortcode in place of points which has been deducted ', 'ultimate-woocommerce-points-and-rewards' ) . '[USERNAME]' . __( ' shortcode in place of username ', 'ultimate-woocommerce-points-and-rewards' ) . '[TOTALPOINTS]' . __( ' shortcode in place of Total Remaining Points.', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'type'  => 'sectionend',
				),
				array(
					'title' => __( 'Point Sharing Notification', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'checkbox',
					'id'            => 'wps_wpr_point_sharing_point_enable',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Check this box to enable the points sharing notification.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Enable Points Sharing Notification', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'text',
					'id'            => 'wps_wpr_point_sharing_subject',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'Received Points Successfully!!', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'textarea_email',
					'id'            => 'wps_wpr_point_sharing_description',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'You have received', 'ultimate-woocommerce-points-and-rewards' ) . '[RECEIVEDPOINT]' . __( 'by your one of the friends having an Email Id is' ) . '[SENDEREMAIL]' . __( 'and your total points are', 'ultimate-woocommerce-points-and-rewards' ) . '[Total Points]' . __( '.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Use ', 'ultimate-woocommerce-points-and-rewards' ) . '[RECEIVEDPOINT]' . __( ' shortcode in place of points which has been received ', 'ultimate-woocommerce-points-and-rewards' ) . '[USERNAME]' . __( ' shortcode in place of username ', 'ultimate-woocommerce-points-and-rewards' ) . '[SENDEREMAIL]' . __( ' shortcode in place of email id of Sender.', 'ultimate-woocommerce-points-and-rewards' ) . '[Total Points]' . __( 'shortcode in place of Total Points.', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'type'  => 'sectionend',
				),
				array(
					'title' => __( 'Purchase Products through Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'checkbox',
					'id'            => 'wps_wpr_pro_pur_by_points_email_enable',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Check this box to enable the purchase of products through points notification.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Purchase Products through Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'text',
					'id'            => 'wps_wpr_pro_pur_by_points_email_subject',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'Product Purchased Through Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'textarea_email',
					'id'            => 'wps_wpr_pro_pur_by_points_discription_custom_id',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'Product Purchased Point', 'ultimate-woocommerce-points-and-rewards' ) . '[PROPURPOINTS]' . __( 'has been deducted from your points on purchasing, and your Total Point is' ) . '[Total Points]' . __( '.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Use ', 'ultimate-woocommerce-points-and-rewards' ) . '[PROPURPOINTS]' . __( ' shortcode in place of purchasing points', 'ultimate-woocommerce-points-and-rewards' ) . '[USERNAME]' . __( ' shortcode in place of username ', 'ultimate-woocommerce-points-and-rewards' ) . '[Total Points]' . __( 'shortcode in place of Total Points.', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'type'  => 'sectionend',
				),
				array(
					'title' => __( "Return 'Product Purchase through Point' Notification", 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'checkbox',
					'id'            => 'wps_wpr_return_pro_pur_enable',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Check this box to enable the return product purchase through point.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Return Product Purchase through Point', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'text',
					'id'            => 'wps_wpr_return_pro_pur_subject',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'Your Points have been Deducted', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
					'type'          => 'textarea_email',
					'id'            => 'wps_wpr_return_pro_pur_description',
					'class'             => 'input-text',
					'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => __( 'Your [RETURNPOINT] has been returned to your point account as you have request for your refund and your Total Point is [TOTALPOINTS].', 'ultimate-woocommerce-points-and-rewards' ),
					'desc'          => __( 'Use ', 'ultimate-woocommerce-points-and-rewards' ) . '[RETURNPOINT]' . __( ' shortcode in place of points which has been returned ', 'ultimate-woocommerce-points-and-rewards' ) . '[USERNAME]' . __( ' shortcode in place of username ', 'ultimate-woocommerce-points-and-rewards' ) . '[TOTALPOINTS]' . __( ' shortcode in place of Total Remaining Points.', 'ultimate-woocommerce-points-and-rewards' ),
				),
			);
				$settings = $this->insert_key_value_pair( $settings, $new_inserted_array, 19 );
		}
		return $settings;
	}

	/**
	 * Add Coupon settings in the lite
	 *
	 * @name add_wps_settings
	 * @since    1.0.0
	 * @param array $coupon_settings settings of the array.
	 */
	public function wps_wpr_add_coupon_settings( $coupon_settings ) {
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {
			$new_inserted_array = array(
				array(
					'title' => __( 'Coupon Settings', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'title' => __( 'Enable Points Conversion', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'id'  => 'wps_wpr_enable_coupon_generation',
					'class' => 'input-text',
					'desc'  => __( 'Enable Points Conversion Fields', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Check this box if you want to enable the coupon generation functionality for customers.', 'ultimate-woocommerce-points-and-rewards' ),
				),

				array(
					'title' => __( 'Redeem Points Conversion', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip'  => __( 'Enter the redeem points for the coupon. (i.e. how many points will be equivalent to the amount)', 'ultimate-woocommerce-points-and-rewards' ),
					'type'    => 'number_text',
					'number_text' => array(
						array(
							'type'  => 'text',
							'id'    => 'wps_wpr_coupon_redeem_price',
							'class'   => 'input-text wps_wpr_new_woo_ver_style_text wc_input_price',
							'default'  => '1',
							'custom_attributes' => array( 'min' => '"1"' ),
							'desc' => __( '=', 'ultimate-woocommerce-points-and-rewards' ),
							'curr' => get_woocommerce_currency_symbol(),
						),
						array(
							'type'  => 'number',
							'id'    => 'wps_wpr_coupon_redeem_points',
							'class'   => 'input-text wc_input_price wps_wpr_new_woo_ver_style_text',
							'custom_attributes' => array( 'min' => '"1"' ),
							'desc' => __( 'Points', 'ultimate-woocommerce-points-and-rewards' ),
						),
					),
				),
				array(
					'title' => __( 'Enter Minimum Points Required For Generating Coupon', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes' => array( 'min' => '"0"' ),
					'id'    => 'wps_wpr_general_minimum_value',
					'desc_tip' => __( 'The minimum points customer requires for converting their points to coupon', 'ultimate-woocommerce-points-and-rewards' ),
					'default' => 50,
				),
				array(
					'title' => __( 'Enable Custom Convert Points', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'id'  => 'wps_wpr_general_custom_convert_enable',
					'class' => 'input-text',
					'desc'  => __( 'Enable to allow customers to convert some of the points to coupon out of their given total points.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Check this box to allow your customers to convert their custom points to coupon out of their total points.', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title' => __( 'Individual Use', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'id'  => 'wps_wpr_coupon_individual_use',
					'class' => 'input-text',
					'desc'  => __( 'Allow Coupons to use Individually.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Check this box to if this Coupon can not be used in conjunction with others Coupons.', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title' => __( 'Free Shipping', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'id'  => 'wps_wpr_points_freeshipping',
					'class' => 'input-text',
					'desc'  => __( 'Allow Coupons on Free Shipping.', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip' => __( 'Check this box if the coupon grants free shipping. A free shipping method must be enabled in your shipping zone and be set to require " a valid free shipping coupon" (see the "Free Shipping Requires" setting).', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'title' => __( 'Coupon Length', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes' => array( 'min' => '"0"' ),
					'id'    => 'wps_wpr_points_coupon_length',
					'desc_tip' => __( 'Enter Coupon length excluding the prefix.(Minimum length is set to 5', 'ultimate-woocommerce-points-and-rewards' ),
					'default' => 5,
				),
				array(
					'title' => __( 'Coupon Expiry After Days', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes' => array( 'min' => '"0"' ),
					'id'    => 'wps_wpr_coupon_expiry',
					'desc_tip' => __( 'Enter the number of days after which the Coupon will expire. Keep value "1" for one-day expiry when order is completed. Keep value "0" for no expiry.', 'ultimate-woocommerce-points-and-rewards' ),
					'default' => 0,
				),
				array(
					'title' => __( 'Minimum Spend', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes' => array( 'min' => '"0"' ),
					'id'    => 'wps_wpr_coupon_minspend',
					'desc_tip' => __( 'This field allows you to set the minimum spend (subtotal, including taxes) allowed to use the coupon. Keep value "0" for no limit.', 'ultimate-woocommerce-points-and-rewards' ),
					'default' => 0,
				),
				array(
					'title' => __( 'Maximum Spend', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes' => array( 'min' => '"0"' ),
					'id'    => 'wps_wpr_coupon_maxspend',
					'desc_tip' => __( 'This field allows you to set the maximum spend (subtotal, including taxes) allowed when using the Coupon. Keep value "0" for no limit.', 'ultimate-woocommerce-points-and-rewards' ),
					'default' => 0,
				),
				array(
					'title' => __( 'Coupon No of time uses', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'custom_attributes' => array( 'min' => '"0"' ),
					'id'    => 'wps_wpr_coupon_use',
					'desc_tip' => __( 'How many times this coupon can be used before the Coupon is void. Keep value "0" for no limit.', 'ultimate-woocommerce-points-and-rewards' ),
					'default' => 0,
				),
				array(
					'type'  => 'sectionend',
				),
			);
			$coupon_settings = $this->insert_key_value_pair( $coupon_settings, $new_inserted_array, 4 );
		}
		return $coupon_settings;
	}

	/**
	 * Add Pro settings of the other setting
	 *
	 * @name add_wps_settings
	 * @since    1.0.0
	 * @param array $settings array of the settings.
	 */
	public function wps_wpr_other_settings( $settings ) {

		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {
			$wps_pro_settings = array(
				array(
					'title' => __( 'Thankyou Page Settings', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'id'    => 'wps_wpr_thnku_order_msg',
					'type'  => 'textarea',
					'title' => __( 'Enter Thank you Order Message When your customer gains some points', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip'  => __( 'Entered Message will appears at thankyou page when any ordered item is having some of the points', 'ultimate-woocommerce-points-and-rewards' ),
					'class' => 'input-text',
					'desc2' => __( 'Use these shortcodes for providing an appropriate message for your customers ', 'ultimate-woocommerce-points-and-rewards' ) . '[POINTS]' . __( ' for product points ', 'ultimate-woocommerce-points-and-rewards' ) . '[TOTALPOINT]' . __( ' for their Total Points ', 'ultimate-woocommerce-points-and-rewards' ),

					'custom_attributes' => array(
						'cols' => '"35"',
						'rows' => '"5"',
					),

				),
				array(
					'id'    => 'wps_wpr_thnku_order_msg_usin_points',
					'type'  => 'textarea',
					'title' => __( 'Enter Thank you Order Message When your customer spent some of the points', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip'  => __( 'Entered Message will appears at thankyou page when any item has been purchased through points', 'ultimate-woocommerce-points-and-rewards' ),
					'class' => 'input-text',
					'desc2' => __( 'Use these shortcodes for providing an appropriate message for your customers ', 'ultimate-woocommerce-points-and-rewards' ) . '[POINTS]' . __( ' for product points ', 'ultimate-woocommerce-points-and-rewards' ) . ' [TOTALPOINT]' . __( ' for their Total Points ', 'ultimate-woocommerce-points-and-rewards' ),
					'custom_attributes' => array(
						'cols' => '"35"',
						'rows' => '"5"',
					),
					'default'   => '',
				),
				array(
					'type'  => 'sectionend',
				),
				array(
					'title' => __( 'Points Sharing', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'id'    => 'wps_wpr_user_can_send_point',
					'type'  => 'checkbox',
					'title' => __( 'Point Sharing', 'ultimate-woocommerce-points-and-rewards' ),
					'desc_tip'  => __( 'Check this box if you want to let your customers send some of the points from his/her account to any other user', 'ultimate-woocommerce-points-and-rewards' ),
					'class' => 'input-text',
					'desc'  => __( 'Enable Point Sharing', 'ultimate-woocommerce-points-and-rewards' ),
				),
				array(
					'type'  => 'sectionend',
				),
			);
			$settings = $this->insert_key_value_pair( $settings, $wps_pro_settings, 5 );
		}
		return $settings;
	}
	/**
	 * Mwb_wpr_allowed_user function.
	 *
	 * @return roles
	 */
	public function wps_wpr_allowed_user() {
		global $wp_roles;
		$all_roles   = $wp_roles->roles;
		$roles_array = array();
		foreach ( $all_roles as $role => $value ) {

			$roles_array[] = array(
				'id' => $role,
				'name' => $value['name'],
			);

		}
		return $roles_array;
	}

	/**
	 * Mwb_wpr_get_pages function
	 *
	 * @return pages
	 */
	public function wps_wpr_get_pages() {

		$wps_page_title = array();
		$wps_pages      = get_pages();
		if ( isset( $wps_pages ) && ! empty( $wps_pages ) && is_array( $wps_pages ) ) {
			foreach ( $wps_pages as $pagedata ) {
				$wps_page_title[] = array(
					'id' => $pagedata->ID,
					'name' => $pagedata->post_title,
				);
			}
		}
		$wps_page_title[] = array(
			'id' => 'details',
			'name' => 'Product Detail',
		);
		return $wps_page_title;

	}

	/**
	 * This functions is used to get all pages for redirect user using referral link.
	 *
	 * @return pages
	 */
	public function wps_wpr_all_pages() {
		$wps_pages_ids = array();
		$wps_pages     = get_pages();
		if ( isset( $wps_pages ) && ! empty( $wps_pages ) && is_array( $wps_pages ) ) {
			foreach ( $wps_pages as $pagedata ) {
				if ( 'Checkout' !== $pagedata->post_title ) {
					$wps_pages_ids[] = array(
						'id' => $pagedata->ID,
						'name' => $pagedata->post_title,
					);
				}
			}
		}
		return $wps_pages_ids;
	}

	/**
	 * Insert array
	 *
	 * @name insert_key_value_pair
	 * @since    1.0.0
	 * @param array $arr array of the settings.
	 * @param array $inserted_array new array of the settings.
	 * @param int   $index index of the array.
	 */
	public function insert_key_value_pair( $arr, $inserted_array, $index ) {
		$arrayend   = array_splice( $arr, $index );
		$arraystart = array_splice( $arr, 0, $index );
		return ( array_merge( $arraystart, $inserted_array, $arrayend ) );
	}

	/**
	 * This function update points on comment.
	 *
	 * @param int    $comment_id comment id.
	 * @param string $comment_approved comment approved.
	 * @return void
	 */
	public function wps_cooment_points_function( $comment_id, $comment_approved ) {
		$user = get_current_user_id();
		if ( 1 === $comment_approved ) {
			/*Generate the public class object*/
			$public_obj = $this->generate_public_obj();
			$enable_wps_comment = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_comment_enable' );
			if ( $enable_wps_comment ) {
				$today_date = date_i18n( 'Y-m-d h:i:sa' );
				$wps_comment_value = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_comment_value' );
				$wps_comment_value = ( 0 == $wps_comment_value ) ? 1 : $wps_comment_value;
				/*Get the total points of the users*/
				$get_points = get_user_meta( $user, 'wps_wpr_points', true );
				$get_points = ! empty( $get_points ) && ! is_null( $get_points ) ? $get_points : 0;
				/*Get Details of the points*/
				$get_detail_point = get_user_meta( $user, 'points_details', true );
				/*Update the points details in the woocommerce*/
				if ( isset( $get_detail_point['comment'] ) && ! empty( $get_detail_point['comment'] ) ) {
					$comment_arr = array();
					$comment_arr = array(
						'comment' => $wps_comment_value,
						'date' => $today_date,
					);
					$get_detail_point['comment'][] = $comment_arr;

				} else {
					if ( ! is_array( $get_detail_point ) ) {
						$get_detail_point = array();
					}
					$comment_arr = array(
						'comment' => $wps_comment_value,
						'date' => $today_date,
					);

					$get_detail_point['comment'][] = $comment_arr;
				}
				$usercomment = WC()->session->get( 'm1' );
				$wps_wpr_comment_limit = WC()->session->get( 'm2' );
				if ( count( $usercomment ) < $wps_wpr_comment_limit ) {
					/*Update user points*/
					update_user_meta( $user, 'wps_wpr_points', $wps_comment_value + $get_points );
					/*Update user points Details*/
					update_user_meta( $user, 'points_details', $get_detail_point );
					/*Send mail to customer that he has earned points*/
					$this->wps_wpr_send_mail_comment( $user, $wps_comment_value );
				}
			}
		}

	}

	/**
	 * This function update points on comment.
	 *
	 * @name wps_wpr_give_points_on_comment
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param string $new_status new status of the comment.
	 * @param string $old_status old status of the comment.
	 * @param object  $comment array of the comment data.
	 */
	public function wps_wpr_give_points_on_comment( $new_status, $old_status, $comment ) {
		global $current_user;
		$user_email = $comment->comment_author_email;
		if ( 'approved' == $new_status ) {
			/*Generate the public class object*/
			$public_obj = $this->generate_public_obj();
			$enable_wps_comment = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_comment_enable' );

			if ( $enable_wps_comment ) {
				$today_date        = date_i18n( 'Y-m-d h:i:sa' );
				$wps_comment_value = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_comment_value' );
				$wps_comment_value = ( 0 == $wps_comment_value ) ? 1 : $wps_comment_value;
				/*Get the total points of the users*/
				$get_points = get_user_meta( $comment->user_id, 'wps_wpr_points', true );
				$get_points = ! empty( $get_points ) && ! is_null( $get_points ) ? $get_points : 0;
				/*Get Details of the points*/
				$get_detail_point = get_user_meta( $comment->user_id, 'points_details', true );
				/*Update the points details in the woocommerce*/
				if ( isset( $get_detail_point['comment'] ) && ! empty( $get_detail_point['comment'] ) ) {
					$comment_arr = array();
					$comment_arr = array(
						'comment' => $wps_comment_value,
						'date' => $today_date,
					);
					$get_detail_point['comment'][] = $comment_arr;

				} else {
					if ( ! is_array( $get_detail_point ) ) {
						$get_detail_point = array();
					}
					$comment_arr = array(
						'comment' => $wps_comment_value,
						'date' => $today_date,
					);

					$get_detail_point['comment'][] = $comment_arr;
				}
				/*Update user points*/
				update_user_meta( $comment->user_id, 'wps_wpr_points', $wps_comment_value + $get_points );
				/*Update user points Details*/
				update_user_meta( $comment->user_id, 'points_details', $get_detail_point );
				/*Send mail to customer that he has earned points*/
				$this->wps_wpr_send_mail_comment( $comment->user_id, $wps_comment_value );
			}
		}
	}

	/**
	 * This function use to send mail to Regarding the customer points
	 *
	 * @name wps_wpr_send_mail_comment
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param int $user_id user id of the customer.
	 * @param int $wps_comment_value points for the comment.
	 */
	public function wps_wpr_send_mail_comment( $user_id, $wps_comment_value ) {
		$user                      = get_user_by( 'ID', $user_id );
		$user_name                 = $user->user_firstname;
		$wps_wpr_notificatin_array = get_option( 'wps_wpr_notificatin_array', true );
		/*Generate of the object of the public object*/
		$public_obj = $this->generate_public_obj();
		/*Get the points of the user*/
		$get_points = get_user_meta( $user_id, 'wps_wpr_points', true );
		/*check the condition*/
		if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {
			$total_points = $get_points;
			/* Get the subject of the comment email*/
			$wps_wpr_email_subject = Points_Rewards_For_WooCommerce_Public::wps_wpr_get_email_notification_description( 'wps_wpr_comment_email_subject' );
			/* Get the Description of the comment email*/
			$wps_wpr_email_discription = Points_Rewards_For_WooCommerce_Public::wps_wpr_get_email_notification_description( 'wps_wpr_comment_email_discription_custom_id' );
			/*Replace the shortcode in the description*/
			$wps_wpr_email_discription = str_replace( '[Points]', $wps_comment_value, $wps_wpr_email_discription );
			$wps_wpr_email_discription = str_replace( '[Total Points]', $total_points, $wps_wpr_email_discription );
			$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
			/*Check is points Email notification is enable*/
			$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'comment_notification' );
			if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {

				$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
				$email_status   = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
			}
		}
	}

	/**
	 * Generate the public obj.
	 *
	 * @name generate_public_obj
	 * @since    1.0.0
	 */
	public function generate_public_obj() {
		if ( class_exists( 'Points_Rewards_For_WooCommerce_Public' ) ) {
			$public_obj = new Points_Rewards_For_WooCommerce_Public( 'woocommerce-points-and-rewards-for-woocommerce-pro', '1.0.0' );
			return $public_obj;
		}
	}

	/**
	 * This is the function adding category wise settings
	 *
	 * @name wps_wpr_add_new_catories_wise_settings
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_add_new_catories_wise_settings() {
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {
			?>
			<p class="wps_wpr_notice"><?php esc_html_e( 'This is the category wise setting for assigning points to a product of categories, enter some valid points for assigning, leave blank fields for removing assigned points', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
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
							$catid = $category->term_id;
							$catname = $category->name;
							$wps_wpr_categ_point = get_option( 'wps_wpr_points_to_per_categ_' . $catid, '' );
							?>
							<tr>
								<td><?php echo esc_html( $catname ); ?></td>
								<td><input type="number" min="1" name="wps_wpr_points_to_per_categ" id="wps_wpr_points_to_per_categ_<?php echo esc_html( $catid ); ?>" value="<?php echo esc_html( $wps_wpr_categ_point ); ?>" class="input-text wps_wpr_new_woo_ver_style_text"></td>
								<td><input type="button" value='<?php esc_html_e( 'Submit', 'ultimate-woocommerce-points-and-rewards' ); ?>' class="button-primary woocommerce-save-button wps_wpr_submit_per_category" name="wps_wpr_submit_per_category" id="<?php echo esc_html( $catid ); ?>"></td>
							</tr>
							<?php
						}
					}
					?>
				</tbody>
			</table>
			<?php
		}
	}

	/**
	 * This function append the option field after selecting Product category through ajax in Assign Product Points Tab
	 *
	 * @name wps_wpr_select_category.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_per_pro_category() {
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		if ( isset( $_POST['wps_wpr_categ_id'] ) && ! empty( $_POST['wps_wpr_categ_id'] ) ) {
			$wps_wpr_categ_id = sanitize_text_field( wp_unslash( $_POST['wps_wpr_categ_id'] ) );
		}
		if ( isset( $_POST['wps_wpr_categ_point'] ) && ! empty( $_POST['wps_wpr_categ_point'] ) ) {
			$wps_wpr_categ_point = sanitize_text_field( wp_unslash( $_POST['wps_wpr_categ_point'] ) );
		}
		$response['result'] = __( 'Fail due to an error', 'ultimate-woocommerce-points-and-rewards' );
		if ( isset( $wps_wpr_categ_id ) && ! empty( $wps_wpr_categ_id ) ) {
			$products = array();
			$selected_cat = $wps_wpr_categ_id;
			$tax_query['taxonomy'] = 'product_cat';
			$tax_query['field'] = 'id';
			$tax_query['terms'] = $selected_cat;
			$tax_queries[] = $tax_query;
			$args = array(
				'post_type' => 'product',
				'posts_per_page' => -1,
				'tax_query' => $tax_queries,
				'orderby' => 'rand',
			);
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) :
				$loop->the_post();
				global $product;

				$product_id = $loop->post->ID;
				if ( isset( $wps_wpr_categ_point ) && ! empty( $wps_wpr_categ_point ) ) {
					$product = wc_get_product( $product_id );
					if ( $product->is_type( 'variable' ) && $product->has_child() ) {
						$parent_id = $product->get_id();
						$parent_product = wc_get_product( $parent_id );
						foreach ( $parent_product->get_children() as $child_id ) {
							update_post_meta( $parent_id, 'wps_product_points_enable', 'yes' );
							update_post_meta( $child_id, 'wps_wpr_variable_points', $wps_wpr_categ_point );
						}
					} else {
						update_post_meta( $product_id, 'wps_product_points_enable', 'yes' );
						update_post_meta( $product_id, 'wps_points_product_value', $wps_wpr_categ_point );
						update_option( 'wps_wpr_points_to_per_categ_' . $wps_wpr_categ_id, $wps_wpr_categ_point );
					}
				} else {
					update_post_meta( $product_id, 'wps_product_points_enable', 'no' );
					update_post_meta( $product_id, 'wps_points_product_value', '' );
					update_option( 'wps_wpr_points_to_per_categ_' . $wps_wpr_categ_id, '' );
				}
			endwhile;
			$response['category_id'] = $wps_wpr_categ_id;
			$response['categ_point'] = isset( $wps_wpr_categ_point ) ? $wps_wpr_categ_point : '';
			$response['result'] = 'success';
			wp_send_json( $response );
		}
	}

	/**
	 * This function append the puchase through settings tab
	 *
	 * @name wps_wpr_select_category.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param array $tabs array of the tabs.
	 */
	public function wps_add_purchase_through_points_settings_tab( $tabs ) {
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {
			$new_tab = array(
				'product-purchase-points' => array(
					'title' => __( 'Product Purchase Points', 'ultimate-woocommerce-points-and-rewards' ),
					'file_path' => POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . 'admin/partials/template/wps-pro-purchase-points.php',
				),
				'points-expiration' => array(
					'title' => __( 'Points Expiration', 'ultimate-woocommerce-points-and-rewards' ),
					'file_path' => POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . 'admin/partials/template/wps-point-expiration.php',
				),
			);
			$tabs = $this->insert_key_value_pair( $tabs, $new_tab, 7 );
		}
		return $tabs;
	}

	/**
	 * This function will add the license panel.
	 *
	 * @name wps_add_license_panel.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param array $tabs array of the tabs.
	 */
	public function wps_add_license_panel( $tabs ) {
		$callname_lic = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		if ( ! Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() ) {
			$new_tab = array(
				'license' => array(
					'title' => __( 'License', 'ultimate-woocommerce-points-and-rewards' ),
					'file_path' => POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . 'admin/partials/points-and-rewards-for-woocommerce-pro-admin-license.php',
				),
			);
			$tabs = $this->insert_key_value_pair( $tabs, $new_tab, 10 );
		}
		return $tabs;
	}


	/**
	 * This function append the option field after selecting Product category through ajax in Product Purchase Points Tab
	 *
	 * @name wps_wpr_per_pro_pnt_category.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_per_pro_pnt_category() {
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		if ( isset( $_POST['wps_wpr_categ_id'] ) && ! empty( $_POST['wps_wpr_categ_id'] ) ) {
			$wps_wpr_categ_id = sanitize_text_field( wp_unslash( $_POST['wps_wpr_categ_id'] ) );
		}
		if ( isset( $_POST['wps_wpr_categ_point'] ) && ! empty( $_POST['wps_wpr_categ_point'] ) ) {
			$wps_wpr_categ_point = sanitize_text_field( wp_unslash( $_POST['wps_wpr_categ_point'] ) );
		}
		$response['result'] = __( 'Fail due to an error', 'ultimate-woocommerce-points-and-rewards' );
		if ( isset( $wps_wpr_categ_id ) && ! empty( $wps_wpr_categ_id ) ) {

			$selected_cat          = $wps_wpr_categ_id;
			$tax_query['taxonomy'] = 'product_cat';
			$tax_query['field']    = 'id';
			$tax_query['terms']    = $selected_cat;
			$tax_queries[]         = $tax_query;
			$args                  = array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
				'tax_query'      => $tax_queries,
				'orderby'        => 'rand',
			);
			$loop = new WP_Query( $args );
			while ( $loop->have_posts() ) :
				$loop->the_post();
				global $product;

				$product_id = $loop->post->ID;
				if ( isset( $wps_wpr_categ_point ) && ! empty( $wps_wpr_categ_point ) ) {
					$product = wc_get_product( $product_id );
					if ( $product->is_type( 'variable' ) && $product->has_child() ) {
						$parent_id = $product->get_id();
						$parent_product = wc_get_product( $parent_id );
						foreach ( $parent_product->get_children() as $child_id ) {
							update_post_meta( $parent_id, 'wps_product_purchase_points_only', 'yes' );
							update_post_meta( $child_id, 'wps_wpr_variable_points_purchase', $wps_wpr_categ_point );
						}
					} else {
						update_post_meta( $product_id, 'wps_product_purchase_points_only', 'yes' );
						update_post_meta( $product_id, 'wps_points_product_purchase_value', $wps_wpr_categ_point );
						update_option( 'wps_wpr_purchase_points_cat' . $wps_wpr_categ_id, $wps_wpr_categ_point );
					}
				} else {
					update_post_meta( $product_id, 'wps_product_purchase_points_only', 'no' );
					update_post_meta( $product_id, 'wps_points_product_purchase_value', '' );
					update_option( 'wps_wpr_purchase_points_cat' . $wps_wpr_categ_id, '' );
				}
			endwhile;
			$response['category_id'] = $wps_wpr_categ_id;
			$response['categ_point'] = isset( $wps_wpr_categ_point ) ? $wps_wpr_categ_point : '';
			$response['result'] = 'success';
			wp_send_json( $response );
		}
	}

	/**
	 * This construct add tab in products menu.
	 *
	 * @name wps_wpr_add_points_tab
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param array $all_tabs type of array.
	 */
	public function wps_wpr_add_points_tab( $all_tabs ) {
		$all_tabs['points'] = array(
			'label'  => __( 'Points and Rewards', 'ultimate-woocommerce-points-and-rewards' ),
			'target' => 'points_data',
		);
		return $all_tabs;
	}

	/**
	 * This construct set products point.
	 *
	 * @name wps_wpr_points_input
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_points_input() {
		global $post;
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {
			$product_is_variable = false;
			$product = wc_get_product( $post->ID );
			if ( isset( $product ) && ! empty( $product ) ) {
				if ( $product->is_type( 'variable' ) && $product->has_child() ) {
					$product_is_variable = true;
				}
			}
			?>
			<div id="points_data" class="panel woocommerce_options_panel">
				<div class="options_group">
					<?php
					woocommerce_wp_checkbox(
						array(
							'id' => 'wps_product_points_enable',
							'wrapper_class' => 'show_if_points',
							'label' => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
							'description' => __( 'Enable Points Per Product', 'ultimate-woocommerce-points-and-rewards' ),
						)
					);
					if ( ! $product_is_variable ) {
						woocommerce_wp_text_input(
							array(
								'id'                => 'wps_points_product_value',
								'label'             => __( 'Enter the Points', 'ultimate-woocommerce-points-and-rewards' ),
								'desc_tip'          => true,
								'custom_attributes'   => array( 'min' => '0' ),
								'description'       => __( 'Please enter the number of points for this product ', 'ultimate-woocommerce-points-and-rewards' ),
								'type'              => 'number',
							)
						);
					}
					woocommerce_wp_checkbox(
						array(
							'id' => 'wps_product_purchase_through_point_disable',
							'wrapper_class' => 'show_if_points',
							'label' => __( 'Do not allow to purchase through points', 'ultimate-woocommerce-points-and-rewards' ),
							'description' => __( 'Do not allow to purchase this product through points', 'ultimate-woocommerce-points-and-rewards' ),
						)
					);

					woocommerce_wp_checkbox(
						array(
							'id' => 'wps_product_purchase_points_only',
							'wrapper_class' => 'show_if_points_only',
							'label' => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
							'description' => __( 'Enable Purchase through points only', 'ultimate-woocommerce-points-and-rewards' ),
						)
					);
					if ( ! $product_is_variable ) {
						woocommerce_wp_text_input(
							array(
								'id'                => 'wps_points_product_purchase_value',
								'label'             => __( 'Enter the Points For Purchase', 'ultimate-woocommerce-points-and-rewards' ),
								'desc_tip'          => true,
								'custom_attributes'   => array( 'min' => '0' ),
								'description'       => __( 'Please enter the number of points for purchase of this product ', 'ultimate-woocommerce-points-and-rewards' ),
								'type'              => 'number',
							)
						);
					}
					?>
					<input type="hidden" name="wps_product_hidden_field"></input>
					<input type="hidden" id="wps_simple_meta_check_nonce" name="wps_simple_meta_check_nonce" value="<?php echo esc_html( wp_create_nonce( 'meta-box-simple-nonce' ) ); ?>" />
				</div>
			</div>
			<?php
		}

	}

	/**
	 * This function is used to add the textbox for variable products
	 *
	 * @name wps_wpr_woocommerce_variation_options_pricing
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param array $loop array of the product data.
	 * @param array $variation_data array of the variation data.
	 * @param array $variation array of the variations.
	 */
	public function wps_wpr_woocommerce_variation_options_pricing( $loop, $variation_data, $variation ) {
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {

			if ( isset( $variation_data['wps_wpr_variable_points'][0] ) ) {
				$wps_wpr_variable_points = $variation_data['wps_wpr_variable_points'][0];
			} else {
				$wps_wpr_variable_points = '';
			}
			?>
			<?php
			if ( is_admin() ) {
				woocommerce_wp_text_input(
					array(
						'id'            => "wps_wpr_variable_points_{$loop}",
						'name'          => "wps_wpr_variable_points_{$loop}",
						'value'         => $wps_wpr_variable_points,
						'label'         => __( 'Customers will earn assigned points', 'ultimate-woocommerce-points-and-rewards' ),
						'data_type'     => 'price',
						'wrapper_class' => 'form-row form-row-first',
						'placeholder'   => __( 'Product Point', 'ultimate-woocommerce-points-and-rewards' ),
					)
				);
			}

			if ( isset( $variation_data['wps_wpr_variable_points_purchase'][0] ) ) {
				$wps_wpr_variable_points_purchase = $variation_data['wps_wpr_variable_points_purchase'][0];
			} else {
				$wps_wpr_variable_points_purchase = '';
			}

			if ( is_admin() ) {
				woocommerce_wp_text_input(
					array(
						'id'            => "wps_wpr_variable_points_purchase_{$loop}",
						'name'          => "wps_wpr_variable_points_purchase_{$loop}",
						'value'         => $wps_wpr_variable_points_purchase,
						'label'         => __( 'Required points to purchase this product', 'ultimate-woocommerce-points-and-rewards' ),
						'data_type'     => 'price',
						'wrapper_class' => 'form-row form-row-first',
						'placeholder'   => __( 'Product Point for purchase', 'ultimate-woocommerce-points-and-rewards' ),
					)
				);
			}
			?>
			<input type="hidden" id="wps_variable_meta_check_nonce" name="wps_variable_meta_check_nonce" value="<?php echo esc_html( wp_create_nonce( 'meta-box-variable-nonce' ) ); ?>" />
			<?php
		}
	}

	/**
	 * This function is used to save the product variation points
	 *
	 * @param int $variation_id variation id.
	 * @param int $i i.
	 * @return void
	 */
	public function wps_wpr_woocommerce_save_product_variation( $variation_id, $i ) {
		$wps_simple_meta_check_nonce = ! empty( $_POST['wps_variable_meta_check_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_variable_meta_check_nonce'] ) ) : '';
		if ( wp_verify_nonce( $wps_simple_meta_check_nonce, 'meta-box-variable-nonce' ) ) {
			if ( isset( $_POST[ 'wps_wpr_variable_points_' . $i ] ) ) {
				$wps_wpr_points = sanitize_text_field( wp_unslash( $_POST[ 'wps_wpr_variable_points_' . $i ] ) );
				update_post_meta( $variation_id, 'wps_wpr_variable_points', $wps_wpr_points );
			}
			if ( isset( $_POST[ 'wps_wpr_variable_points_purchase_' . $i ] ) ) {
				$wps_wpr_points_purchase = sanitize_text_field( wp_unslash( $_POST[ 'wps_wpr_variable_points_purchase_' . $i ] ) );

				update_post_meta( $variation_id, 'wps_wpr_variable_points_purchase', $wps_wpr_points_purchase );
			}
		}
	}

	/**
	 * This function update product custom points
	 *
	 * @param int $post_id post id.
	 * @return void
	 */
	public function woo_add_custom_points_fields_save( $post_id ) {
		$wps_simple_meta_check_nonce = ! empty( $_POST['wps_simple_meta_check_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_simple_meta_check_nonce'] ) ) : '';
		if ( wp_verify_nonce( $wps_simple_meta_check_nonce, 'meta-box-simple-nonce' ) ) {
			if ( isset( $_POST['wps_product_hidden_field'] ) ) {

				$enable_product_points          = isset( $_POST['wps_product_points_enable'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_product_points_enable'] ) ) : 'no';
				$enable_product_purchase_points = isset( $_POST['wps_product_purchase_points_only'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_product_purchase_points_only'] ) ) : 'no';
				$wps_pro_pur_by_point_disable   = isset( $_POST['wps_product_purchase_through_point_disable'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_product_purchase_through_point_disable'] ) ) : 'no';
				$wps_product_value              = ( isset( $_POST['wps_points_product_value'] ) && null != $_POST['wps_points_product_value'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_points_product_value'] ) ) : 1;
				$wps_product_purchase_value     = ( isset( $_POST['wps_points_product_purchase_value'] ) && null != $_POST['wps_points_product_purchase_value'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_points_product_purchase_value'] ) ) : 1;
				update_post_meta( $post_id, 'wps_product_points_enable', $enable_product_points );
				update_post_meta( $post_id, 'wps_product_purchase_points_only', $enable_product_purchase_points );

				update_post_meta( $post_id, 'wps_points_product_value', $wps_product_value );
				update_post_meta( $post_id, 'wps_points_product_purchase_value', $wps_product_purchase_value );
				update_post_meta( $post_id, 'wps_product_purchase_through_point_disable', $wps_pro_pur_by_point_disable );
			}
		}
	}

	/**
	 * This function is used for run the cron for points expiration and handles accordingly
	 *
	 * @return void
	 */
	public function wps_wpr_check_daily_about_points_expiration() {
		$message = '';
		/*Get all settings*/
		$wps_wpr_points_expiration_setting = get_option( 'wps_wpr_points_expiration_settings', array() );
		$wps_wpr_points_expiration_enable  = $this->wps_wpr_get_points_expiration_settings_num( 'wps_wpr_points_expiration_enable' );
		$wps_wpr_email_tpl                 = file_get_contents( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . '/admin/wps-wpr-points-expiration-email-template.php' );
		/*Get the expiration email*/
		$wps_wpr_points_expiration_email = $this->wps_wpr_get_points_expiration_settings( 'wps_wpr_points_expiration_email' );
		/*Check the condition*/
		if ( $wps_wpr_points_expiration_enable ) {
			/*Get the thresold value*/
			$wps_wpr_points_expiration_threshold = $this->wps_wpr_get_points_expiration_settings_num( 'wps_wpr_points_expiration_threshold' );
			/*Get the expiration time*/
			$wps_wpr_points_expiration_time_num = $this->wps_wpr_get_points_expiration_settings_num( 'wps_wpr_points_expiration_time_num' );
			/*Get the settings of expiration time*/
			$wps_wpr_points_expiration_time_drop = $this->wps_wpr_get_points_expiration_settings( 'wps_wpr_points_expiration_time_drop' );
			/*Check the condition is not empty*/
			$wps_wpr_points_expiration_time_drop = ( ! empty( $wps_wpr_points_expiration_time_drop ) ) ? $wps_wpr_points_expiration_time_drop['0'] : 'days';
			$today_date = date_i18n( 'Y-m-d' );
			$user_data  = get_users( array( 'fields' => 'ID') );

			if ( ! empty( $user_data ) && is_array( $user_data ) ) {
				foreach ( $user_data as $key => $value ) {
					$user_id = $value;

					if ( isset( $user_id ) && ! empty( $user_id ) ) {
						$get_points = get_user_meta( $user_id, 'wps_wpr_points', true );
						if ( $get_points == $wps_wpr_points_expiration_threshold || $get_points > $wps_wpr_points_expiration_threshold ) {
							$get_expiration_date = get_user_meta( $user_id, 'wps_wpr_points_expiration_date', true );

							if ( ! isset( $get_expiration_date ) || empty( $get_expiration_date ) ) {
								$expiration_date = date_i18n( 'Y-m-d', strtotime( $today_date . ' +' . $wps_wpr_points_expiration_time_num . ' ' . $wps_wpr_points_expiration_time_drop ) );
								update_user_meta( $user_id, 'wps_wpr_points_expiration_date', $expiration_date );
								$headers = array( 'Content-Type: text/html; charset=UTF-8' );
								// Expiration Date has been set to User.
								$subject = __( 'Redeem your Points before they expire!', 'ultimate-woocommerce-points-and-rewards' );
								$wps_wpr_threshold_notif = $wps_wpr_points_expiration_setting['wps_wpr_threshold_notif'];
								$message = $wps_wpr_email_tpl;
								$message = str_replace( '[CUSTOMMESSAGE]', $wps_wpr_threshold_notif, $message );
								$sitename = get_bloginfo();
								$message = str_replace( '[SITENAME]', $sitename, $message );
								$message = str_replace( '[TOTALPOINT]', $get_points, $message );
								$message = str_replace( '[EXPIRYDATE]', $expiration_date, $message );

								$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
								$email_status = $customer_email->trigger( $user_id, $message, $subject );
							}
						}

						$get_expiration_date = get_user_meta( $user_id, 'wps_wpr_points_expiration_date', true );
						if ( isset( $get_expiration_date ) && ! empty( $get_expiration_date ) ) {
							$send_notification_date = date_i18n( 'Y-m-d', strtotime( $get_expiration_date . ' -' . $wps_wpr_points_expiration_email . ' days' ) );

							if ( isset( $send_notification_date ) && ! empty( $send_notification_date ) ) {
								if ( $today_date == $send_notification_date ) {
									$wps_user_point_expiry = get_user_meta( $user_id, 'wps_wpr_points_expiration_date', true );
									$headers = array( 'Content-Type: text/html; charset=UTF-8' );
									$subject = __( 'Hurry!! Points Expiration has just a few days', 'ultimate-woocommerce-points-and-rewards' );
									$wps_wpr_re_notification = $wps_wpr_points_expiration_setting['wps_wpr_re_notification'];
									$message = $wps_wpr_email_tpl;
									$message = str_replace( '[CUSTOMMESSAGE]', $wps_wpr_re_notification, $message );
									$sitename = get_bloginfo();
									$message = str_replace( '[SITENAME]', $sitename, $message );
									$message = str_replace( '[TOTALPOINT]', $get_points, $message );
									$message = str_replace( '[EXPIRYDATE]', $wps_user_point_expiry, $message );
									// expiration email before one week.

									$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
									$email_status = $customer_email->trigger( $user_id, $message, $subject );
								}
							}

							if ( $today_date >= $get_expiration_date && $get_points > 0 ) {
								$expired_detail_points = get_user_meta( $user_id, 'points_details', true );
								if ( isset( $expired_detail_points['expired_details'] ) && ! empty( $expired_detail_points['expired_details'] ) ) {

									$exp_array = array(
										'expired_details' => $get_points,
										'date' => $today_date,
									);
									$expired_detail_points['expired_details'][] = $exp_array;
								} else {
									if ( ! is_array( $expired_detail_points ) ) {
										$expired_detail_points = array();
									}
									$exp_array = array(
										'expired_details' => $get_points,
										'date' => $today_date,
									);
									$expired_detail_points['expired_details'][] = $exp_array;
								}
								update_user_meta( $user_id, 'wps_wpr_points', 0 );
								update_user_meta( $user_id, 'points_details', $expired_detail_points );
								delete_user_meta( $user_id, 'wps_wpr_points_expiration_date' );
								$headers = array( 'Content-Type: text/html; charset=UTF-8' );
								$subject = __( 'Points have expired!', 'ultimate-woocommerce-points-and-rewards' );
								$wps_wpr_expired_notification = $wps_wpr_points_expiration_setting['wps_wpr_expired_notification'];
								$wps_wpr_expired_notification = ( ! empty( $wps_wpr_expired_notification ) ) ? $wps_wpr_expired_notification : __( 'Your Points has been expired, you may earn more Points and use the benefit more', 'ultimate-woocommerce-points-and-rewards' );
								$message = $wps_wpr_email_tpl;
								$sitename = get_bloginfo();
								$message = str_replace( '[SITENAME]', $sitename, $message );
								$message = str_replace( '[CUSTOMMESSAGE]', $wps_wpr_expired_notification, $message );
								// points has been expired.

								$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
								$email_status = $customer_email->trigger( $user_id, $message, $subject );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * This function is used to add the Custom Widget for Points and Reward
	 *
	 * @name wps_wpr_custom_widgets.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_custom_widgets() {
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {// phpcs:ignoreFile.
			include_once POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . '/admin/class-wps-wpr-custom-widget.php';
		}
	}

	/**
	 * This function is used to add the Points inside the Orders(if any)
	 *
	 * @name wps_wpr_woocommerce_admin_order_item_headers.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param object $order array of the order.
	 */
	public function wps_wpr_woocommerce_admin_order_item_headers( $order ) {

		foreach ( $order->get_items() as $item_id => $item ) {
			$wps_wpr_items = $item->get_meta_data();
			foreach ( $wps_wpr_items as $key => $wps_wpr_value ) {
				if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Points' == $wps_wpr_value->key ) ) {
					?>
					<th class="quantity sortable"><?php esc_html_e( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
					<?php
				}
			}
		}
	}

	/**
	 * This function is used to add the Points inside the Orders(if any)
	 *
	 * @name wps_wpr_woocommerce_admin_order_item_values.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param array  $product array of the product.
	 * @param object $item line item of the order.
	 * @param int    $item_id id of the item.
	 */
	public function wps_wpr_woocommerce_admin_order_item_values( $product, $item, $item_id ) {
		$wps_wpr_items = $item->get_meta_data();
		foreach ( $wps_wpr_items as $key => $wps_wpr_value ) {
			if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Points' == $wps_wpr_value->key ) ) {
				$item_points = (int) $wps_wpr_value->value;
				?>
				<td class="item_cost" width="1%" data-sort-value="<?php echo esc_html( $item_points ); ?>">
					<div class="view">
						<?php
						echo esc_html( $item_points );
						?>
					</div>
				</td>
				<?php
			}
		}
	}

	/**
	 * This function is used to remove action
	 *
	 * @name wps_wpr_woocommerce_admin_order_item_values.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_remove_action() {
		global $public_obj;
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() || 0 <= $day_count ) {
			remove_action( 'wps_wpr_add_membership_rule', array( $public_obj, 'wps_wpr_add_membership_rule' ), 10, 1 );
			add_action( 'wps_wpr_add_membership_rule', array( $this, 'wps_wpr_add_rule_pro' ) );
			remove_action( 'wps_wpr_order_total_points', array( $public_obj, 'wps_wpr_add_order_total_points' ), 10, 3 );
			add_action( 'wps_wpr_order_total_points', array( $this, 'wps_wpr_add_order_total_points_pro' ), 10, 3 );
		}
	}

	/**
	 * This function is used to add rule
	 *
	 * @name wps_wpr_woocommerce_admin_order_item_values.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param array $wps_wpr_membership_roles array of the membership role.
	 */
	public function wps_wpr_add_rule_pro( $wps_wpr_membership_roles ) {
		global $public_obj;
		?>
		<div class="parent_of_div">
			<?php
			$count = 0;
			if ( is_array( $wps_wpr_membership_roles ) && ! empty( $wps_wpr_membership_roles ) ) {

				foreach ( $wps_wpr_membership_roles as $role => $values ) {
					$public_obj->wps_wpr_membership_role( $count, $role, $values );
					$count++;
				}
			} else {
				$public_obj->wps_wpr_membership_role( $count, '', '' );
			}
			?>
		</div>
		<?php
	}

	/**
	 * This function is used to add rule for order total points.
	 *
	 * @name wps_wpr_woocommerce_admin_order_item_values.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 * @param array $thankyouorder_min array for the minimum order values.
	 * @param array $thankyouorder_max array for the maximum order values.
	 * @param array $thankyouorder_value array for the points on the order total.
	 */
	public function wps_wpr_add_order_total_points_pro( $thankyouorder_min, $thankyouorder_max, $thankyouorder_value ) {
		global $public_obj;
		if ( isset( $thankyouorder_min ) && null != $thankyouorder_min && isset( $thankyouorder_max ) && null != $thankyouorder_max && isset( $thankyouorder_value ) && null != $thankyouorder_value ) {
			$wps_wpr_no = 1;
			if ( count( $thankyouorder_min ) == count( $thankyouorder_max ) && count( $thankyouorder_max ) == count( $thankyouorder_value ) ) {
				?>
				<table class="form-table wp-list-table widefat fixed striped">
					<thead> 
						<tr valign="top">
							<th><?php esc_html_e( 'Minimum', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
							<th><?php esc_html_e( 'Maximum', 'ultimate-woocommerce-points-and-rewards' ); ?></th>

							<th><?php esc_html_e( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
							<?php if ( count( $thankyouorder_min ) > 1 ) { ?>
							<th class="wps_wpr_remove_thankyouorder_content"><?php esc_html_e( 'Action', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody class="wps_wpr_thankyouorder_tbody">
				<?php
				foreach ( $thankyouorder_min as $key => $value ) {
					$public_obj->wps_wpr_add_rule_for_order_total_points( $thankyouorder_min, $thankyouorder_max, $thankyouorder_value, $key );
				}
				?>
					</tbody>
				</table>
				<?php
			}
		} else {
			?>
			<table class="form-table wp-list-table widefat fixed striped">
				<thead> 
					<tr valign="top">
						<th><?php esc_html_e( 'Minimum', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
						<th><?php esc_html_e( 'Maximum', 'ultimate-woocommerce-points-and-rewards' ); ?></th>

						<th><?php esc_html_e( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
						
				
						
					</tr>
				</thead>
				<tbody  class="wps_wpr_thankyouorder_tbody">
			<?php
			$public_obj->wps_wpr_add_rule_for_order_total_points( array(), array(), array(), '' );
			?>
			</tbody>
			</table>
			<?php
		}
	}

	/**
	 * This function is used to add rule for order total points.
	 *
	 * @return void
	 */
	public function wps_wpr_add_notice() {
		$callname_lic         = Points_And_Rewards_For_Woocommerce_Pro::$lic_callback_function;
		$callname_lic_initial = Points_And_Rewards_For_Woocommerce_Pro::$lic_ini_callback_function;
		$day_count            = Points_And_Rewards_For_Woocommerce_Pro::$callname_lic_initial();
		if ( ! Points_And_Rewards_For_Woocommerce_Pro::$callname_lic() ) {
			if ( 0 <= $day_count ) {
				$day_count_warning = floor( $day_count );
				/* translators: %s: day */
				$day_string = sprintf( _n( '%s day', '%s days', $day_count_warning, 'ultimate-woocommerce-points-and-rewards' ), number_format_i18n( $day_count_warning ) );
				?>
				<div id="points-and-rewards-for-woocommerce-pro-thirty-days-notify" class="notice notice-warning">
					<p>
						<strong><a href="?page=wps-rwpr-setting&tab=license"><?php esc_html_e( 'Activate', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
						<?php
						esc_html_e( ' the license key before ', 'ultimate-woocommerce-points-and-rewards' );
						echo '<span id="points-and-rewards-for-woocommerce-pro-day-count" >' . esc_html( $day_string ) . '</span>';
						esc_html_e( ' or you may risk losing data and the plugin will also become dysfunctional.', 'ultimate-woocommerce-points-and-rewards' );
						?>
						</strong>
					</p>
				</div>
				<?php
			} else {
				$wps_license_key = get_option( 'ultimate_woocommerce_points_and_rewards_lcns_key', '' );
				if ( '' == $wps_license_key ) {
					?>
					<div id="points-and-rewards-for-woocommerce-pro-thirty-days-notify" class="notice notice-warning">
						<p>
							<strong><?php esc_html_e( 'Unfortunately, Your trial has expired. Please', 'ultimate-woocommerce-points-and-rewards' ); ?>
							<a href="?page=wps-rwpr-setting&tab=license"><?php esc_html_e( 'activate', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
							<?php esc_html_e( 'your license key to avail the premium features.', 'ultimate-woocommerce-points-and-rewards' ); ?>
							</strong>
						</p>
					</div>
					<?php
				}
			}
		}
	}

	/**
	 * This function is used to add rule for order total points.
	 *
	 * @param string $wps_wpr_no_of_section wps_wpr_no_of_section.
	 * @return void
	 */
	public function wps_wpr_save_membership_settings_pro( $wps_wpr_no_of_section ) {
		$wps_wpr_nonce = ! empty( $_POST['wps-wpr-nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wps-wpr-nonce'] ) ) : '';
		if ( wp_verify_nonce( $wps_wpr_nonce, 'wps-wpr-nonce' ) ) {
			if ( isset( $wps_wpr_no_of_section ) ) {
				$count                = $wps_wpr_no_of_section;
				$wps_wpr_mem_enable   = isset( $_POST['wps_wpr_membership_setting_enable'] ) ? 1 : 0;
				$exclude_sale_product = isset( $_POST['exclude_sale_product'] ) ? 1 : 0;
				for ( $count = 0; $count <= $wps_wpr_no_of_section; $count++ ) {

					$wps_wpr_membersip_roles  = isset( $_POST[ 'wps_wpr_membership_level_name_' . $count ] ) ? map_deep( wp_unslash( $_POST[ 'wps_wpr_membership_level_name_' . $count ] ), 'sanitize_text_field' ) : '';
					$wps_wpr_membersip_points = isset( $_POST[ 'wps_wpr_membership_level_value_' . $count ] ) ? map_deep( wp_unslash( $_POST[ 'wps_wpr_membership_level_value_' . $count ] ), 'sanitize_text_field' ) : '';
					$wps_wpr_categ_list       = ( isset( $_POST[ 'wps_wpr_membership_category_list_' . $count ] ) && ! empty( $_POST[ 'wps_wpr_membership_category_list_' . $count ] ) ) ? map_deep( wp_unslash( $_POST[ 'wps_wpr_membership_category_list_' . $count ] ), 'sanitize_text_field' ) : '';
					$wps_wpr_prod_list        = ( isset( $_POST[ 'wps_wpr_membership_product_list_' . $count ] ) && ! empty( $_POST[ 'wps_wpr_membership_product_list_' . $count ] ) ) ? map_deep( wp_unslash( $_POST[ 'wps_wpr_membership_product_list_' . $count ] ), 'sanitize_text_field' ) : '';
					$wps_wpr_discount         = ( isset( $_POST[ 'wps_wpr_membership_discount_' . $count ] ) && ! empty( $_POST[ 'wps_wpr_membership_discount_' . $count ] ) ) ? map_deep( wp_unslash( $_POST[ 'wps_wpr_membership_discount_' . $count ] ), 'sanitize_text_field' ) : '';
					$wps_wpr_expnum           = isset( $_POST[ 'wps_wpr_membership_expiration_' . $count ] ) ? map_deep( wp_unslash( $_POST[ 'wps_wpr_membership_expiration_' . $count ] ), 'sanitize_text_field' ) : '';
					$wps_wpr_expdays          = isset( $_POST[ 'wps_wpr_membership_expiration_days_' . $count ] ) ? map_deep( wp_unslash( $_POST[ 'wps_wpr_membership_expiration_days_' . $count ] ), 'sanitize_text_field' ) : '';

					if ( isset( $wps_wpr_membersip_roles ) && ! empty( $wps_wpr_membersip_roles ) ) {
						$membership_roles_list[ $wps_wpr_membersip_roles ] = array(
							'Points'     => $wps_wpr_membersip_points,
							'Prod_Categ' => $wps_wpr_categ_list,
							'Product'    => $wps_wpr_prod_list,
							'Discount'   => $wps_wpr_discount,
							'Exp_Number' => $wps_wpr_expnum,
							'Exp_Days'   => $wps_wpr_expdays,
						);
					} else {
						$membership_roles_list = array();
					}
				}
			}
			$membership_settings_array['wps_wpr_membership_setting_enable'] = $wps_wpr_mem_enable;
			$membership_settings_array['membership_roles']                  = $membership_roles_list;
			$membership_settings_array['exclude_sale_product']              = $exclude_sale_product;
			if ( is_array( $membership_settings_array ) ) {
				update_option( 'wps_wpr_membership_settings', $membership_settings_array );
			}
		}
	}

	/**
	 * Chnage the text of the Coupon Tab.
	 *
	 * @name wps_wpr_change_the_coupon_tab_text
	 * @param string $coupon_tab_text  coupon tab text.
	 */
	public function wps_wpr_change_the_coupon_tab_text( $coupon_tab_text ) {

		$coupon_tab_text = esc_html__( 'Per Currency Points & Coupon Settings', 'ultimate-woocommerce-points-and-rewards' );
		return $coupon_tab_text;

	}

	/**
	 * Add link for the coupon details in the Coupons Tab.
	 *
	 * @name wps_wpr_add_coupon_details
	 * @param array $action array of the link that will display below the points table.
	 * @param int   $user_id  user id of the current logged in user.
	 */
	public function wps_wpr_add_coupon_details( $action, $user_id ) {
		$action['view_coupon_detail'] = '<a href="' . WPS_RWPR_HOME_URL . 'admin.php?page=wps-rwpr-setting&tab=points-table&user_id=' . $user_id . '&action=view">' . esc_html__( 'View Coupon Detail', 'ultimate-woocommerce-points-and-rewards' ) . '</a>';
		return $action;
	}

	/**
	 * Add import button.
	 */
	public function wps_wpr_add_additional_import_points() {
		?>
		<div class="wps_wpr_import_userspoints">
		<h3 class="wps_wpr_heading"><?php esc_html_e( 'Import/Export Users Points', 'ultimate-woocommerce-points-and-rewards' ); ?></h3>
		<table class="form-table wps_wpr_general_setting">
			<tbody>
				<tr valign="top">
					<td colspan="3" class="wps_wpr_instructions_tabledata">
						<h3><?php esc_html_e( 'Instructions', 'ultimate-woocommerce-points-and-rewards' ); ?></h3>
						<p> 1 - <?php esc_html_e( 'For Importing users points. You need to choose a CSV file and click Import', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
						<p> 2 - <?php esc_html_e( 'CSV for user points must have 3 columns in this order (Users Email, Points, Reason. Also, the first row must have the respective headings )', 'ultimate-woocommerce-points-and-rewards' ); ?> </p>
						<p> 3 - <?php esc_html_e( 'Click on Export Button to export points table data.', 'ultimate-woocommerce-points-and-rewards' ); ?> </p>
					</td>
				</tr>
				<tr>
					<th><?php esc_html_e( 'Choose a CSV file:', 'ultimate-woocommerce-points-and-rewards' ); ?>
					</th>
					<td>
						<input class="wps_wpr_csv_custom_userpoints_import" name="userpoints_csv_import" id="userpoints_csv_import" type="file" size="25" value="" aria-required="true" />

						<input type="hidden" value="134217728" name="max_file_size"><br>
						<small><?php esc_html_e( 'Maximum size:128 MB', 'ultimate-woocommerce-points-and-rewards' ); ?></small>
					</td>
					<td>
						<a href="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>/uploads/wps_wpr_userpoints_sample.csv"><?php esc_html_e( 'Export Demo CSV', 'ultimate-woocommerce-points-and-rewards' ); ?>
						<span class="wps_sample_export"><img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>/images/download.png"></span>
						</a>
					</td>
				</tr>
				<tr>
					<td>
						<p id="wps_import_content"><input name="wps_wpr_csv_custom_userpoints_import" id="wps_wpr_csv_custom_userpoints_import" class="button-primary woocommerce-save-button wps_import" type="submit" value="<?php esc_html_e( 'Import', 'ultimate-woocommerce-points-and-rewards' ); ?>" /></p>
					</td>
					<td>
						<p class="wps_wpr_export_paragraph"><input type="button" id="wps_wpr_export_points_table_data" class="button-primary woocommerce-save-button" value="<?php esc_html_e( 'Export', 'ultimate-woocommerce-points-and-rewards' ); ?>" />
						<img class="wps_wpr_export_user_loader" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . 'images/loading.gif' ); ?>"></p>
						<span class="wps_wpr_export_table_notice"><?php esc_html_e( 'Exporting table please wait...', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
					</td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'wps_upload_csv', 'wps_wpr_nonce' ); ?>
		</div>
		<?php

	}

	/**
	 * This function is used to update user points.
	 *
	 * @param string $wps_user_email user email.
	 * @param int    $wps_user_points user points.
	 * @return bool
	 */
	public function wps_update_points_of_users( $wps_user_email, $wps_user_points ) {
		$user = get_user_by( 'email', $wps_user_email );
		if ( isset( $user ) ) {
			$user_id         = $user->ID;
			$get_user_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
			$get_user_points = ! empty( $get_user_points ) ? $get_user_points : 0;
			if ( empty( $wps_user_points ) ) {
				$wps_user_points = 0;
			}
			if ( empty( $get_user_points ) ) {
				$get_user_points = 0;
			}
			$wps_update_csv_points = $get_user_points + $wps_user_points;
			/*Update user points*/
			if ( $wps_update_csv_points > 0 ) {
				update_user_meta( $user_id, 'wps_wpr_points', $wps_update_csv_points );
			}
			/*Get the points details of the user*/
			$admin_points = get_user_meta( $user_id, 'points_details', true );
			/*Today date*/
			$today_date = date_i18n( 'Y-m-d h:i:sa' );
			/*Check is not empty the user points*/
			if ( isset( $wps_user_points ) && ! empty( $wps_user_points ) ) {
				/*Check is not empty admin points*/
				if ( isset( $admin_points['admin_points'] ) && ! empty( $admin_points['admin_points'] ) ) {
					$admin_array = array();
					$admin_array = array(
						'admin_points' => $wps_user_points,
						'date' => $today_date,
						'reason' => esc_html__( 'Updated By Admin', 'ultimate-woocommerce-points-and-rewards' ),
					);
					$admin_points['admin_points'][] = $admin_array;
				} else {
					if ( ! is_array( $admin_points ) ) {
						$admin_points = array();
					}
					$admin_array = array(
						'admin_points' => $wps_user_points,
						'date' => $today_date,
						'reason' => esc_html__( 'Updated By Admin', 'ultimate-woocommerce-points-and-rewards' ),
					);
					$admin_points['admin_points'][] = $admin_array;
				}
				update_user_meta( $user_id, 'points_details', $admin_points );
			}
		}
		return true;
	}

	/**
	 * Function to add the additional general settings for cart points.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_cart_add_max_apply_points_settings( $settings ) {
		$add = array(
			array(
				'title' => __( 'Enable Point Usage Limitation', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'checkbox',
				'id'    => 'wps_wpr_max_points_on_cart',
				'desc'  => esc_html__( 'Allow customers to pay a particular part of the order using points.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc_tip' => __( 'Check this box to enable the Maximum Points to apply to the cart', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title' => __( 'Select Points Limitation Type', 'ultimate-woocommerce-points-and-rewards' ),
				'id' => 'wps_wpr_cart_point_type',
				'class' => 'wps_wgm_new_woo_ver_style_select',
				'type' => 'singleSelectDropDownWithKeyvalue',
				'desc_tip' => __( 'Select the discount Type to apply points', 'ultimate-woocommerce-points-and-rewards' ),
				'custom_attribute' => array(
					array(
						'id' => 'wps_wpr_fixed_cart',
						'name' => __( 'Fixed', 'ultimate-woocommerce-points-and-rewards' ),
					),
					array(
						'id' => 'wps_wpr_percentage_cart',
						'name' => __( 'Percentage', 'ultimate-woocommerce-points-and-rewards' ),
					),
				),
			),
			array(
				'title' => __( 'Enter Amount', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'number',
				'custom_attributes'   => array( 'min' => '"1"' ),
				'id'    => 'wps_wpr_amount_value',
				'desc_tip' => __( 'Enter the amount that customers can pay using their points', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'  => __( ' Enter the amount that customer can pay using their points', 'ultimate-woocommerce-points-and-rewards' ),
			),

			array(
				'title' => __( 'Enable Point Restriction on sale Product', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'checkbox',
				'id'    => 'wps_wpr_points_restrict_sale',
				'desc'  => esc_html__( 'Check this box to restrict the points discount on sale items.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc_tip' => __( 'Check this box to restrict the points discount on sales item', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title' => __( 'Enter min points you want to start redemption', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'number',
				'custom_attributes'   => array( 'min' => '"0"' ),
				'id'    => 'wps_wpr_apply_points_value',
				'desc_tip' => esc_html__( 'Enter the min points you want the user to redeem points.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'  => __( 'Enter the min points you want the user to redeem points.', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);

		$key = (int) $this->wps_wpr_get_key( $settings );
		if ( $key > 1 ) {
			$arr1 = array_slice( $settings, $key + 1 );
			$arr2 = array_slice( $settings, 0, $key + 1 );
			array_splice( $arr1, 0, 0, $add );
		} else {
			$arr1 = array_slice( $settings, $key + 42 );
			$arr2 = array_slice( $settings, 0, $key + 42 );
			array_splice( $arr1, 0, 0, $add );
		}
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * Function to get the corresponding key of matching value.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'title', $val ) ) {
					if ( 'Enable apply points during checkout' == $val['title'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function is used to make user role settings.
	 *
	 * @param array $wps_wpr_general_settings wps_wpr_general_settings.
	 * @return array
	 */
	public function wps_wpr_user_roles( $wps_wpr_general_settings ) {
		$my_new_inserted_array = array(
			array(
				'type'  => 'sectionend',
			),
			array(
				'title' => __( 'Allow selected user role to use points feature', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),

			array(
				'title' => __( 'Allow user roles', 'ultimate-woocommerce-points-and-rewards' ),
				'id' => 'wps_wpr_allowed_selected_user_role',
				'type' => 'search&select',
				'multiple' => 'multiple',
				'desc_tip' => __( 'Allow selected user role to use points feature,leave empty for all users', 'ultimate-woocommerce-points-and-rewards' ),
				'options' => $this->wps_wpr_allowed_user(),
			),
		);

		$wps_wpr_general_settings  = $this->insert_key_value_pair( $wps_wpr_general_settings, $my_new_inserted_array, 130 );
		return $wps_wpr_general_settings;
	}

	/**
	 * Function to generate the input html tags.
	 *
	 * @since 1.0.0
	 * @name wps_wpr_additional_cart_points_settings().
	 * @param array $value Array of html.
	 * @param array $general_settings Array of html.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_additional_cart_points_settings( $value, $general_settings ) {
		if ( 'singleSelectDropDownWithKeyvalue' == $value['type'] ) {
			$this->wps_wpr_generate_single_select_drop_down_with_key_value_pair( $value, $general_settings );
		}
		if ( 'search&select' == $value['type'] ) {
			$this->wps_wpr_generate_search_select_html( $value, $general_settings );
		}
		if ( 'select' == $value['type'] ) {
			$this->wps_wpr__select_html( $value, $general_settings );
		}
	}

	/**
	 * Function to generate single selct drop dowm
	 *
	 * @since 1.0.0
	 * @name wps_wpr_generate_single_select_drop_down_with_key_value_pair().
	 * @param array $value Array of html.
	 * @param array $saved_settings Array of html.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_generate_single_select_drop_down_with_key_value_pair( $value, $saved_settings ) {
		$selectedvalue = isset( $saved_settings[ $value['id'] ] ) ? ( $saved_settings[ $value['id'] ] ) : array();
		if ( '' == $selectedvalue ) {
			$selectedvalue = '';
		}
		?>
		<select name="<?php echo esc_attr( array_key_exists( 'id', $value ) ? $value['id'] : '' ); ?>" class="<?php echo esc_attr( array_key_exists( 'class', $value ) ? $value['class'] : '' ); ?>">
			<?php
			if ( is_array( $value['custom_attribute'] ) && ! empty( $value['custom_attribute'] ) ) {
				foreach ( $value['custom_attribute'] as $option ) {
					$select = 0;
					if ( $option['id'] == $selectedvalue && ! empty( $selectedvalue ) ) {
						$select = 1;
					}
					?>
					<option value="<?php echo esc_attr( $option['id'] ); ?>" <?php echo selected( 1, $select ); ?> ><?php echo esc_attr( $option['name'] ); ?></option>
					<?php
				}
			}
			?>
		</select>
		<?php if ( isset( $value['desc'] ) && ! empty( $value['desc'] ) ) { ?>
		<p><?php echo esc_html( $value['desc'] ); ?></p>
	<?php } ?>
		<?php
	}


	/**
	 * Function to generate multi selct drop dowm
	 *
	 * @param array $value value.
	 * @param array $general_settings general settings.
	 * @return void
	 */
	public function wps_wpr_generate_search_select_html( $value, $general_settings ) {
		$selectedvalue = isset( $general_settings[ $value['id'] ] ) ? ( $general_settings[ $value['id'] ] ) : array();
		if ( '' == $selectedvalue ) {
			$selectedvalue = '';
		}
		?>
		<label for="<?php echo ( array_key_exists( 'id', $value ) ) ? esc_html( $value['id'] ) : ''; ?>">
			<select name="<?php echo ( array_key_exists( 'id', $value ) ) ? esc_html( $value['id'] ) : ''; ?>[]" id="<?php echo ( array_key_exists( 'id', $value ) ) ? esc_html( $value['id'] ) : ''; ?>" 
			<?php if ( array_key_exists( 'multiple', $value ) ) : ?>
			multiple = "<?php echo ( array_key_exists( 'multiple', $value ) ) ? esc_html( $value['multiple'] ) : false; ?>"
			<?php endif; ?>
				class="<?php echo ( array_key_exists( 'class', $value ) ) ? esc_html( $value['class'] ) : ''; ?>"
				<?php
				if ( array_key_exists( 'custom_attribute', $value ) ) {
					foreach ( $value['custom_attribute'] as $attribute_name => $attribute_val ) {
						echo wp_kses_post( $attribute_name . '=' . $attribute_val );
					}
				}
				if ( is_array( $value['options'] ) && ! empty( $value['options'] ) ) {
					foreach ( $value['options'] as $option ) {
						$select = 0;
						if ( is_array( $selectedvalue ) && in_array( $option['id'], $selectedvalue ) && ! empty( $selectedvalue ) ) {
							$select = 1;
						}
						?>
						><option value="<?php echo esc_html( $option['id'] ); ?>" <?php echo selected( 1, $select ); ?> ><?php echo esc_html( $option['name'] ); ?></option>
						<?php
					}
				}
				?>
			</select>
		</label>
		<?php
	}

	/**
	 * This function is used to create select dropdown
	 *
	 * @param array $value value.
	 * @param array $general_settings general settings.
	 * @return void
	 */
	public function wps_wpr__select_html( $value, $general_settings ) {
		$selectedvalue = isset( $general_settings[ $value['id'] ] ) ? ( $general_settings[ $value['id'] ] ) : array();
		if ( '' == $selectedvalue ) {
			$selectedvalue = '';
		}
		?>
		<label for="<?php echo ( array_key_exists( 'id', $value ) ) ? esc_html( $value['id'] ) : ''; ?>">
			<select name="<?php echo ( array_key_exists( 'id', $value ) ) ? esc_html( $value['id'] ) : ''; ?>[]" id="<?php echo ( array_key_exists( 'id', $value ) ) ? esc_html( $value['id'] ) : ''; ?>" 
			<?php if ( array_key_exists( 'select', $value ) ) : ?>
			<?php endif; ?>
				class="<?php echo ( array_key_exists( 'class', $value ) ) ? esc_html( $value['class'] ) : ''; ?>"
				<?php
				if ( array_key_exists( 'custom_attribute', $value ) ) {
					foreach ( $value['custom_attribute'] as $attribute_name => $attribute_val ) {
						echo wp_kses_post( $attribute_name . '=' . $attribute_val );
					}
				}
				if ( is_array( $value['options'] ) && ! empty( $value['options'] ) ) {
					foreach ( $value['options'] as $option ) {
						$select = 0;
						if ( is_array( $selectedvalue ) && in_array( $option['id'], $selectedvalue ) && ! empty( $selectedvalue ) ) {
							$select = 1;
						}
						?>
						><option value="<?php echo esc_html( $option['id'] ); ?>" <?php echo selected( 1, $select ); ?> ><?php echo esc_html( $option['name'] ); ?></option>
						<?php
					}
				}
				?>
			</select>
		</label>
		<?php

	}

	/**
	 * Function to delete user coupon.
	 *
	 * @return void
	 */
	public function wps_wpr_delete_user_coupon() {
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		$response['res'] = false;
		$user_id   = ! empty( $_POST['user_id'] ) ? sanitize_text_field( wp_unslash( $_POST['user_id'] ) ) : '';
		$coupon_id = ! empty( $_POST['coupon_id'] ) ? sanitize_text_field( wp_unslash( $_POST['coupon_id'] ) ) : '';
		if ( isset( $coupon_id ) && ! empty( $coupon_id ) ) {
			wp_delete_post( $coupon_id );
			$user_log    = get_user_meta( $user_id, 'wps_wpr_user_log', true );
			foreach ( $user_log as $key => $value ) {
				$wps_split   = explode( '#', $key );
				$user_coupon_id = $wps_split[1];
				if ( $user_coupon_id == $coupon_id ) {
					unset( $user_log[ $key ] );
					update_user_meta( $user_id, 'wps_wpr_user_log', $user_log );
				}
			}
			$response['res'] = true;
		}
		wp_send_json( $response );
	}


	/**
	 * Function to add reset bulk option.
	 *
	 * @param string $action action.
	 * @return string
	 */
	public function wps_wpr_points_log_bulk_option_callback( $action ) {

		$action['bulk-reset'] = __( 'Reset points log', 'ultimate-woocommerce-points-and-rewards' );
		return $action;
	}

	/**
	 * Function to add reset points log.
	 *
	 * @param string $current_option current_option.
	 * @param string $posted_data posted_data.
	 * @return void
	 */
	public function wps_wpr_process_bulk_reset_option_callback( $current_option, $posted_data ) {
		if ( 'bulk-reset' === $current_option ) {
			if ( isset( $posted_data['points-log'] ) ) {
				$wps_membership_nonce = sanitize_text_field( wp_unslash( $posted_data['points-log'] ) );
				if ( wp_verify_nonce( $wps_membership_nonce, 'points-log' ) ) {
					if ( isset( $posted_data['mpr_points_ids'] ) && ! empty( $posted_data['mpr_points_ids'] ) ) {
						$all_id = map_deep( wp_unslash( $posted_data['mpr_points_ids'] ), 'sanitize_text_field' );
						foreach ( $all_id as $key => $value ) {
							delete_user_meta( $value, 'points_details' );
						}
					}
				}
			}
		}
	}

	/**
	 * This function will add the api panel.
	 *
	 * @param array $tabs tabs.
	 * @return array
	 */
	public function wps_add_points_notification_addon_settings_tab( $tabs ) {
		$new_tab = array(
			'notification_addon' => array(
				'title'     => __( 'Notification Addon', 'ultimate-woocommerce-points-and-rewards' ),
				'file_path' => POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . 'admin/partials/template/points-and-rewards-for-woocommerce-pro-addon.php',
			),
		);
		$tabs = $this->insert_key_value_pair( $tabs, $new_tab, 11 );

		return $tabs;
	}

	/**
	 * Settings copatibility with WPML.
	 *
	 * @return void
	 */
	public function wps_wpr_setting_compatibility_wpml() {
		do_action( 'wpml_multilingual_options', 'wps_wpr_notification_button_page' );
	}

	/**
	 * This function is used for showing shortcode description.
	 *
	 * @param array $shortcode_array shortcode array.
	 * @return array
	 */
	public function wps_wpr_show_referral_link_shortcoe( $shortcode_array ) {
		$shortcode_array['desc5'] = __( 'Use shortcode [WPS_REFERRAL_LINK] for displaying referral link anywhere on site', 'ultimate-woocommerce-points-and-rewards' );

		return $shortcode_array;
	}


	/**
	 * This function will add the api panel.
	 *
	 * @param array $tabs tabs.
	 * @return array
	 */
	public function wps_add_api_settings_tab( $tabs ) {
		$new_tab = array(
			'api_settings' => array(
				'title'     => __( 'API Settings', 'ultimate-woocommerce-points-and-rewards' ),
				'file_path' => POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . 'admin/partials/template/wps-wpr-api-features.php',
			),
		);
		$tabs = $this->insert_key_value_pair( $tabs, $new_tab, 12 );

		return $tabs;
	}


	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_per_currrency_on_subtotal_option( $settings ) {
		$add = array(
			array(
				'title' => __( 'Enable Per currency points in subtotal', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'checkbox',
				'id'    => 'wps_wpr_per_cerrency_points_on_order_subtotal',
				'desc'  => __( 'Allow per currency points conversion on subtotal.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc_tip' => __( 'Check this box if you want to enable per currency points conversion on subtotal.', 'ultimate-woocommerce-points-and-rewards' ),
			),

		);
		$key = (int) $this->wps_wpr_get_key_per_currency_for_subtotal( $settings );
		$arr1 = array_slice( $settings, $key + 1 );
		$arr2 = array_slice( $settings, 0, $key + 1 );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_per_currency_for_subtotal( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_coupon_conversion_enable' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function will apply per currency in subtotal.
	 *
	 * @param object $_postdata postdata.
	 * @param array  $wps_wpr_notification_settings notification settings.
	 * @return array
	 */
	public function wps_wpr_check_notification_checkbox_is_empty( $_postdata, $wps_wpr_notification_settings ) {

		include_once POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . '/admin/partials/settings/class-ultimate-woocommerce-points-rewards-admin-settings.php';
		if ( class_exists( 'Ultimate_Woocommerce_Points_Rewards_Admin_Settings' ) ) {

			$settings_obj = new Ultimate_Woocommerce_Points_Rewards_Admin_Settings();
			foreach ( $wps_wpr_notification_settings as $key => $value ) {
				if ( 'checkbox' == $value['type'] && 'wps_wpr_notification_setting_enable' != $value['id'] ) {

					$_postdata[ $value['id'] ] = $settings_obj->wps_wpr_check_checkbox( $value, $_postdata );
				}
			}
		}

		return $_postdata;
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_custom_notification_callback( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_email_subject_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the custom points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Custom Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_custom_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_custom_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_email_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_signup_notification_callback( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_signup_email_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the signup points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Signup Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_signup_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_signup_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_signup_email_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}


	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_product_purchase_notification_callback( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_product_email_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the product purchase points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Product Purchase Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_product_purchase_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_product_purchase_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_product_email_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_order_amount_notification_callback( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_amount_email_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the order amount points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Order Amount Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_order_amount_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_order_amount_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_amount_email_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_referral_notification_callback( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_referral_email_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the referral points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Referral Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_order_referral_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}


	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_order_referral_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_referral_email_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_upgrade_membership_notification_callback( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_membership_email_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the referral points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Upgrade Membership Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_upgrade_membership_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for upgrade membership notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_upgrade_membership_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_membership_email_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}


	/**
	 * This function will add specific enable/disable for deduct assign points notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_deduct_assign_notification_callback( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_deduct_assigned_point_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the deduct assign points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Deduct Assign Points Notification', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_deduct_assign_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for deduct assign points notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_deduct_assign_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_deduct_assigned_point_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function will add specific enable/disable for deduct apply points on cart notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_points_on_cart_notification_callback( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_point_on_cart_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the points on cart subtotal points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Points On Cart Sub-Total Notification', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_points_on_cart_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for deduct apply points on cart notificatio
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_points_on_cart_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_point_on_cart_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function will add specific enable/disable for order total range notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_order_total_range_notification_callback( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_point_on_order_total_range_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the points on order total range points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Points On Order Total Range Notification', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_order_total_range_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for order total range notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_order_total_range_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_point_on_order_total_range_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function is used to create order rewards points enable html settings.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_number_of_order_rewards_points_notifications( $settings ) {
		$add = array(
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_enable_order_rewards_points_notifications',
				'class'         => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the points on order rewards points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Order Rewards Points', 'ultimate-woocommerce-points-and-rewards' ),
			),
		);
		$key = (int) $this->wps_wpr_get_key_order_rewards_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 0, 0, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function is used to show checkbox html on order rewards settings.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_order_rewards_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_order_rewards_points_subject' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function will check specific notification settings.
	 *
	 * @param string $validate validate.
	 * @param string $type type.
	 * @return string
	 */
	public function wps_wpr_check_specific_points_notification_enable_callback( $validate, $type ) {

		if ( $this->wps_wpr_check_custom_points_mail_notfication_is_enable() && 'admin_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_signup_points_mail_notfication_is_enable() && 'signup_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_referal_signup_points_mail_notfication_is_enable() && 'referral_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_product_points_mail_notfication_is_enable() && 'product_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_per_currency_spents_points_mail_notfication_is_enable() && 'product_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_comment_points_mail_notfication_is_enable() && 'comment_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_referral_purchase_points_mail_notfication_is_enable() && 'product_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_deduct_per_currency_points_mail_notfication_is_enable() && 'deduct_per_currency_spent_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_sharing_points_mail_notfication_is_enable() && 'product_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_product_purchase_through_points_mail_notfication_is_enable() && 'product_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_return_product_pur_thr_points_mail_notfication_is_enable() && 'return_product_pur_thr_points_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_upgrade_membership_points_mail_notfication_is_enable() && 'product_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_deduct_assign_points_mail_notfication_is_enable() && 'deduct_assign_points_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_cart_discount_points_mail_notfication_is_enable() && 'wps_cart_discount_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_check_order_total_range_points_mail_notfication_is_enable() && 'product_notification' == $type ) {
			$validate = true;
		} elseif ( $this->wps_wpr_is_rewards_points_notification_mail_enable() && 'rewards_points_notify' == $type ) {
			$validate = true;
		} else {
			$validate = false;
		}
		return $validate;
	}

	/**
	 * This function is use to check is custom notification setting is enable or not
	 *
	 * @name wps_wpr_check_custom_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_custom_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_email_subject_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_email_subject_setting_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is signup point notification setting is enable or not
	 *
	 * @name wps_wpr_check_signup_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_signup_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_signup_email_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_signup_email_setting_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is referal signup notification setting is enable or not
	 *
	 * @name wps_wpr_check_referal_signup_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_referal_signup_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_referral_email_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_referral_email_setting_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is product points notification setting is enable or not
	 *
	 * @name wps_wpr_check_product_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_product_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_product_email_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_product_email_setting_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is per currency notification setting is enable or not
	 *
	 * @name wps_wpr_check_per_currency_spents_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_per_currency_spents_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_amount_email_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_amount_email_setting_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is comments points notification setting is enable or not
	 *
	 * @name wps_wpr_check_comment_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_comment_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_comment_email_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_comment_email_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is referal purchase notification setting is enable or not
	 *
	 * @name wps_wpr_check_referral_purchase_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_referral_purchase_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_referral_purchase_email_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_referral_purchase_email_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is deduct per currency notification setting is enable or not
	 *
	 * @name wps_wpr_check_deduct_per_currency_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_deduct_per_currency_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is points sharing notification setting is enable or not
	 *
	 * @name wps_wpr_check_sharing_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_sharing_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_point_sharing_point_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_point_sharing_point_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is purchase through notification setting is enable or not
	 *
	 * @name wps_wpr_check_product_purchase_through_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_product_purchase_through_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_pro_pur_by_points_email_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_pro_pur_by_points_email_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}


	/**
	 * This function is use to check is return purchase product through points notification setting is enable or not
	 *
	 * @name wps_wpr_check_return_product_pur_thr_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_return_product_pur_thr_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is upgrade membership notification setting is enable or not
	 *
	 * @name wps_wpr_check_upgrade_membership_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_upgrade_membership_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_membership_email_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_membership_email_setting_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is deduct assign points notification setting is enable or not
	 *
	 * @name wps_wpr_check_deduct_assign_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_deduct_assign_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_setting_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is cart discount notification setting is enable or not
	 *
	 * @name wps_wpr_check_cart_discount_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_cart_discount_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_point_on_cart_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_point_on_cart_setting_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is use to check is order total range notification setting is enable or not
	 *
	 * @name wps_wpr_check_order_total_range_points_mail_notfication_is_enable
	 * @since      1.2.0
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_order_total_range_points_mail_notfication_is_enable() {
		$wps_points_notification_enable = false;
		$wps_wpr_notificatin_array      = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_notificatin_enable     = isset( $wps_wpr_notificatin_array['wps_wpr_point_on_order_total_range_setting_enable'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_point_on_order_total_range_setting_enable'] ) : 0;
		if ( 1 == $wps_wpr_notificatin_enable ) {
			$wps_points_notification_enable = true;
		}
		return $wps_points_notification_enable;
	}

	/**
	 * This function is used to check whether rewards notication email enable or not.
	 *
	 * @return bool
	 */
	public function wps_wpr_is_rewards_points_notification_mail_enable() {

		$is_rewards_notification_enable                    = false;
		$wps_wpr_notificatin_array                         = get_option( 'wps_wpr_notificatin_array', true );
		$wps_wpr_enable_order_rewards_points_notifications = isset( $wps_wpr_notificatin_array['wps_wpr_enable_order_rewards_points_notifications'] ) ? intval( $wps_wpr_notificatin_array['wps_wpr_enable_order_rewards_points_notifications'] ) : 0;
		if ( 1 == $wps_wpr_enable_order_rewards_points_notifications ) {
			$is_rewards_notification_enable = true;
		}
		return $is_rewards_notification_enable;
	}

	/**
	 * This function is use to check is order total range notification setting is enable or not.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_unset_settings_for_old_version_callback( $settings ) {
		foreach ( $settings as $key => $value ) {
			if ( 'wps_wpr_comment_email_enable' == isset( $value['id'] ) && $value['id'] ) {
				unset( $settings[ $key ] );
			}
			if ( 'wps_wpr_referral_purchase_email_enable' == isset( $value['id'] ) && $value['id'] ) {
				unset( $settings[ $key ] );
			}
			if ( 'wps_wpr_deduct_per_currency_point_enable' == isset( $value['id'] ) && $value['id'] ) {
				unset( $settings[ $key ] );
			}
			if ( 'wps_wpr_point_sharing_point_enable' == isset( $value['id'] ) && $value['id'] ) {
				unset( $settings[ $key ] );
			}
			if ( 'wps_wpr_pro_pur_by_points_email_enable' == isset( $value['id'] ) && $value['id'] ) {
				unset( $settings[ $key ] );
			}
			if ( 'wps_wpr_return_pro_pur_enable' == isset( $value['id'] ) && $value['id'] ) {
				unset( $settings[ $key ] );
			}
		}
		return $settings;
	}

	/**
	 * Add General settings in the lite.
	 *
	 * @param array $wps_wpr_general_settings general settings.
	 * @return array
	 */
	public function wps_wpr_first_order_points( $wps_wpr_general_settings ) {

			$my_new_inserted_array = array(
				array(
					'title' => __( 'Enable First order Points settings', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'title',
				),
				array(
					'title' => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Enable to give points on first order purchase', 'ultimate-woocommerce-points-and-rewards' ),
					'id'    => 'wps_wpr_general_setting_enablee',
					'desc_tip' => __( 'Check this box to enable the first order points setting.', 'ultimate-woocommerce-points-and-rewards' ),
					'default'   => 0,
				),
				array(
					'title' => __( 'Enter First order purchase Points', 'ultimate-woocommerce-points-and-rewards' ),
					'type'  => 'number',
					'default'   => 1,
					'id'    => 'wps_wpr_general_first_value',
					'custom_attributes'   => array( 'min' => '"1"' ),
					'class'   => 'input-text wps_wpr_new_woo_ver_style_text',
					'desc_tip' => __( 'The points which the new customer will get after his first order only', 'ultimate-woocommerce-points-and-rewards' ),
				),

				array(
					'type'  => 'sectionend',
				),

			);
			$wps_wpr_general_settings  = $this->insert_key_value_pair( $wps_wpr_general_settings, $my_new_inserted_array, 48 );
			return $wps_wpr_general_settings;
	}

	/**
	 * Add General settings in the lite.
	 *
	 * @param array $wps_wpr_general_settings general settings.
	 * @return array
	 */
	public function wps_wpr_daily_sign_up_points( $wps_wpr_general_settings ) {

		$my_new_inserted_array = array(
			array(
				'title' => __( 'Enable First Daily login Points settings', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),
			array(
				'title'    => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'     => 'checkbox',
				'desc'     => __( 'Enable to give points on first daily login', 'ultimate-woocommerce-points-and-rewards' ),
				'id'       => 'wps_wpr_general_setting_daily_enablee',
				'desc_tip' => __( 'Check this box to enable the setting of the daily points.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'  => 0,
			),
			array(
				'title'             => __( 'Enter First Daily login Points', 'ultimate-woocommerce-points-and-rewards' ),
				'type'              => 'number',
				'default'           => 1,
				'id'                => 'wps_wpr_general_daily_login_value',
				'custom_attributes' => array( 'min' => '"1"' ),
				'class'             => 'input-text wps_wpr_new_woo_ver_style_text',
				'desc_tip'          => __( 'The points which the new customer will get when he/she login daily', 'ultimate-woocommerce-points-and-rewards' ),
			),

			array(
				'type' => 'sectionend',
			),

		);
		$wps_wpr_general_settings  = $this->insert_key_value_pair( $wps_wpr_general_settings, $my_new_inserted_array, 48 );
		return $wps_wpr_general_settings;
	}

	/**
	 * Add General settings in the lite.
	 *
	 * @param array $wps_wpr_general_settings general settings.
	 * @return array
	 */
	public function wps_wpr_customer_rank_listing( $wps_wpr_general_settings ) {

		$my_new_inserted_array = array(
			array(
				'title' => __( 'Enable Customer Rank Settings', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),
			array(
				'title'    => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'     => 'checkbox',
				'desc'     => __( 'Enable To Rank the Customer on Basis of Points', 'ultimate-woocommerce-points-and-rewards' ),
				'id'       => 'wps_wpr_general_setting_customer_rank_list',
				'desc_tip' => __( 'Check this box to enable customer rank points setting.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'  => 0,
			),
			array(
				'title'             => __( 'Enter No of Customer To Be Listed With ShortCode [CUSTOMERRANK]', 'ultimate-woocommerce-points-and-rewards' ),
				'type'              => 'number',
				'default'           => 1,
				'id'                => 'wps_wpr_general_no_of_customer_list',
				'custom_attributes' => array( 'min' => '"1"' ),
				'class'             => 'input-text wps_wpr_new_woo_ver_style_text',
				'desc_tip'          => __( 'The Number of Customers To Be Listed During Ranking', 'ultimate-woocommerce-points-and-rewards' ),
			),

			array(
				'type' => 'sectionend',
			),

		);
		$wps_wpr_general_settings  = $this->insert_key_value_pair( $wps_wpr_general_settings, $my_new_inserted_array, 48 );
		return $wps_wpr_general_settings;
	}

	/**
	 * Add General settings in the lite.
	 *
	 * @param array $wps_wpr_general_settings general settings.
	 * @return array
	 */
	public function wps_wpr_birthday_order_points( $wps_wpr_general_settings ) {

		$my_new_inserted_array = array(
			array(
				'title' => __( 'Enable Birthday Points settings', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),
			array(
				'title'    => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'     => 'checkbox',
				'desc'     => __( 'Enable to give points on birthday', 'ultimate-woocommerce-points-and-rewards' ),
				'id'       => 'wps_wpr_general_setting_birthday_enablee',
				'desc_tip' => __( 'Check this box to enable points on the birthday setting.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'  => 0,
			),
			array(
				'title'             => __( 'Enter Birthday Points to give on bday', 'ultimate-woocommerce-points-and-rewards' ),
				'type'              => 'number',
				'default'           => 1,
				'id'                => 'wps_wpr_general_birthday_value',
				'custom_attributes' => array( 'min' => '"1"' ),
				'class'             => 'input-text wps_wpr_new_woo_ver_style_text',
				'desc_tip'          => __( 'The points which the  customer will get on his Birthday only', 'ultimate-woocommerce-points-and-rewards' ),
			),

			array(
				'type' => 'sectionend',
			),

		);
		$wps_wpr_general_settings  = $this->insert_key_value_pair( $wps_wpr_general_settings, $my_new_inserted_array, 150 );
		return $wps_wpr_general_settings;
	}

	/**
	 * This function will add specific enable/disable for order total range notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_first_order_notification_callback( $settings ) {
		$add = array(

			array(
				'title' => __( 'Points Only on  First order', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_point_on_first_order_point_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the points on first order points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Points Only on First order', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'text',
				'id'            => 'wps_wpr_point_on_first_order_subject',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'Points Added', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'textarea_email',
				'id'            => 'wps_wpr_point_on_first_order_desc',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'Your [FIRSTORDERPOINT] Points have been added in now your Total Points are [Total Points].', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Use ', 'ultimate-woocommerce-points-and-rewards' ) . '[FIRSTORDERPOINT]' . __( ' shortcode in place of points which has been added ', 'ultimate-woocommerce-points-and-rewards' ) . '[USERNAME]' . __( ' shortcode in place of username ', 'ultimate-woocommerce-points-and-rewards' ) . '[FIRSTORDERPOINT]' . __( ' shortcode in place of Total Points.', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'type'  => 'sectionend',
			),
		);
		$key = (int) $this->wps_wpr_get_key_first_order_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 100, 100, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for first_order_points_notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_first_order_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_first_order_points' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * Wps_point_on_first_order_admin_log function.
	 *
	 * @param array $point_log point log.
	 * @return void
	 */
	public function wps_point_on_first_order_admin_log( $point_log ) {
		?>
		<div class="wps_wpr_wrapper_div">
				<?php
				if ( array_key_exists( 'points_on_first_order', $point_log ) ) {
					?>
					<div class="wps_wpr_slide_toggle">
						<p class="wps_wpr_view_log_notice wps_wpr_common_slider" ><?php esc_html_e( 'Points on First Order', 'ultimate-woocommerce-points-and-rewards' ); ?>
						  <a class ="wps_wpr_open_toggle"  href="javascript:;"></a>
					  </p>
					  <div class="wps_wpr_points_view"> 
						  <table class="form-table mwp_wpr_settings wps_wpr_common_table" >
								  <thead>
									<tr valign="top">
										<th scope="row" class="wps_wpr_head_titledesc">
											<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date & Time', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
										</th>
										<th scope="row" class="wps_wpr_head_titledesc">
											<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
										</th>
									</tr>
								</thead>
								<tr valign="top">
								<td class="forminp forminp-text"><?php echo( esc_html( $point_log['points_on_first_order']['0']['date'] ) ); ?></td>
								<td class="forminp forminp-text"><?php echo '+' . esc_html( $point_log['points_on_first_order']['0']['points_on_first_order'] ); ?></td>
								</tr>
							</table>
						</div>
					</div>
					<?php
				}

				?>
		</table></div>
		<?php

	}

	// My Custom Code For Point Notification on daily login.
	/**
	 * This function will send  notification on first daily login.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_daily_login_callback( $settings ) {
		$add = array(

			array(
				'title' => __( 'Points Only on  First Daily Login', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_point_on_first_daily_login_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the points on the first daily login  points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Points Only on First Daily Login', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'text',
				'id'            => 'wps_wpr_point_on_first_daily_login_subject',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'Points Added', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'textarea_email',
				'id'            => 'wps_first_daily_login_desc',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'Your [FIRSTLOGINPOINT] Points have been added in now your Total Points are [TOTALPOINTNOW].', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Use ', 'ultimate-woocommerce-points-and-rewards' ) . '[FIRSTLOGINPOINT]' . __( ' shortcode in place of points which has been added ', 'ultimate-woocommerce-points-and-rewards' ) . '[USERNAME]' . __( ' shortcode in place of username ', 'ultimate-woocommerce-points-and-rewards' ) . '[TOTALPOINTNOW]' . __( ' shortcode in place of Total Points.', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'type'  => 'sectionend',
			),
		);
		$key = (int) $this->wps_wpr_get_key_first_daily_login_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 100, 100, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for firsy_daily_login_notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_key_first_daily_login_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_daily_sign_up_points' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	// My custome Code End Here.
	/**
	 * This Function is used for bdy notification
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_bithday_notification_callback( $settings ) {
		$add = array(

			array(
				'title' => __( 'Points Only on  Bday Notification', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_point_on_birthday_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the points on first order points notification.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Points Only on  Birthday', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'text',
				'id'            => 'wps_wpr_point_on_bday_subject',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'Points Added', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'textarea_email',
				'id'            => 'wps_wpr_point_on_bday_order_desc',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'Your [BIRTHDAYPOINT] Points have been added in now your Total Points are [TOTALPOINTS].', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Use', 'ultimate-woocommerce-points-and-rewards' ) . '[BIRTHDAYPOINT]' . __( ' shortcode in place of points which has been added ', 'ultimate-woocommerce-points-and-rewards' ) . '[USERNAME]' . __( ' shortcode in place of username ', 'ultimate-woocommerce-points-and-rewards' ) . '[Total Points]' . __( ' shortcode in place of Total Points.', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'type'  => 'sectionend',
			),
		);
		$key = (int) $this->wps_wpr_get_key_bday_points_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 101, 101, $add );
		return array_merge( $arr2, $arr1 );
	}

	/**
	 * This function will add specific enable/disable for bday_points_notification.
	 *
	 * @param array $settings Settings.
	 * @return array
	 */
	public function wps_wpr_get_key_bday_points_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_birthday_order_points' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This Function will display point log on admin side.
	 *
	 * @param array $point_log point log.
	 * @return void
	 */
	public function wps_wpr_admin_point_log( $point_log ) {
		if ( array_key_exists( 'points_on_birthday', $point_log ) ) {
			?>
			<div class="wps_wpr_slide_toggle">
				<p class="wps_wpr_view_log_notice wps_wpr_common_slider" ><?php esc_html_e( 'Points Earned on Birthday', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<a class ="wps_wpr_open_toggle"  href="javascript:;"></a>
				</p>
				<table class = "form-table mwp_wpr_settings wps_wpr_points_view wps_wpr_common_table">
					<thead>
						<tr valign="top">
							<th scope="row" class="wps_wpr_head_titledesc">
								<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date & Time', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
							<th scope="row" class="wps_wpr_head_titledesc">
								<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
						</tr>
					</thead>
					<?php
					foreach ( $point_log['points_on_birthday'] as $key => $value ) {
						?>
						<tr valign="top">
							<td class="forminp forminp-text"><?php echo esc_html( $value['date'] ); ?></td>
							<td class="forminp forminp-text"><?php echo '+' . esc_html( $value['points_on_birthday'] ); ?></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php
		}
		if ( array_key_exists( 'points_on_first_order', $point_log ) ) {
			?>
			<div class="wps_wpr_slide_toggle">
				<p class="wps_wpr_view_log_notice wps_wpr_common_slider" ><?php esc_html_e( 'Points Earned on First Order', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<a class ="wps_wpr_open_toggle"  href="javascript:;"></a>
				</p>
				<table class = "form-table mwp_wpr_settings wps_wpr_points_view wps_wpr_common_table">
					<thead>
						<tr valign="top">
							<th scope="row" class="wps_wpr_head_titledesc">
								<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date & Time', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
							<th scope="row" class="wps_wpr_head_titledesc">
								<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
						</tr>
					</thead>
					<?php
					foreach ( $point_log['points_on_first_order'] as $key => $value ) {
						?>
						<tr valign="top">
							<td class="forminp forminp-text"><?php echo esc_html( $value['date'] ); ?></td>
							<td class="forminp forminp-text"><?php echo '+' . esc_html( $value['points_on_first_order'] ); ?></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php
		}
		if ( array_key_exists( 'points_on_first_login_daily', $point_log ) ) {
			?>
			<div class="wps_wpr_slide_toggle">
				<p class="wps_wpr_view_log_notice wps_wpr_common_slider" ><?php esc_html_e( 'Points Earned on First Login', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<a class ="wps_wpr_open_toggle"  href="javascript:;"></a>
				</p>
				<table class = "form-table mwp_wpr_settings wps_wpr_points_view wps_wpr_common_table">
					<thead>
						<tr valign="top">
							<th scope="row" class="wps_wpr_head_titledesc">
								<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date & Time', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
							<th scope="row" class="wps_wpr_head_titledesc">
								<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
						</tr>
					</thead>
					<?php
					foreach ( $point_log['points_on_first_login_daily'] as $key => $value ) {
						?>
						<tr valign="top">
							<td class="forminp forminp-text"><?php echo esc_html( $value['date'] ); ?></td>
							<td class="forminp forminp-text"><?php echo '+' . esc_html( $value['points_on_first_login_daily'] ); ?></td>
						</tr>
						<?php
					}
					?>
				</table>
			</div>
			<?php
		}
		if ( array_key_exists( 'points_on_coupon_refer', $point_log ) ) {
			?>
			<div class="wps_wpr_slide_toggle">
			<p class="wps_wpr_view_log_notice wps_wpr_common_slider" ><?php esc_html_e( 'Points Earned on Coupon Referral', 'ultimate-woocommerce-points-and-rewards' ); ?>
				<a class ="wps_wpr_open_toggle"  href="javascript:;"></a>
			</p>
				<table class = "form-table mwp_wpr_settings wps_wpr_points_view wps_wpr_common_table">
				<thead>
					<tr valign="top">
						<th scope="row" class="wps_wpr_head_titledesc">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date & Time', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th scope="row" class="wps_wpr_head_titledesc">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th scope="row" class="wps_wpr_head_titledesc">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Referred User', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
					</tr>
				</thead>
				<?php
				foreach ( $point_log['points_on_coupon_refer'] as $key => $value ) {
					?>
					<tr valign="top">
						<td class="forminp forminp-text"><?php echo esc_html( $value['date'] ); ?></td>
						<td class="forminp forminp-text"><?php echo '+' . esc_html( $value['points_on_coupon_refer'] ); ?></td>
						<td class="forminp forminp-text"><?php echo '+' . esc_html( $value['refered_user'] ); ?></td>
					</tr>
					<?php
				}
				?>
				</table></div>
			<?php
		}
		// points added by admin through API.
		if ( array_key_exists( 'add_points_using_api', $point_log ) ) {
			?>
			<div class="wps_wpr_slide_toggle">
				<p class="wps_wpr_view_log_notice wps_wpr_common_slider" ><?php esc_html_e( 'Updated by Admin through API', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<a class ="wps_wpr_open_toggle"  href="javascript:;"></a>
				</p>
			<table class = "form-table mwp_wpr_settings wps_wpr_points_view wps_wpr_common_table">
				<thead>
					<tr valign="top">
						<th scope="row" class="wps_wpr_head_titledesc">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date & Time', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th scope="row" class="wps_wpr_head_titledesc">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th class="wps-wpr-view-log-Status">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Reason', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
					</tr>
				</thead>
					<?php
					foreach ( $point_log['add_points_using_api'] as $key => $value ) {
						?>
				<tr valign="top">
					<td class="forminp forminp-text"><?php echo esc_html( wps_wpr_set_the_wordpress_date_format( $value['date'] ) ); ?></td>
						<?php
						if ( '+' == $value['sign'] ) {
							?>
						<td class="forminp forminp-text"><?php echo '+' . esc_html( $value['add_points_using_api'] ); ?></td>
							<?php
						} else {
							?>
						<td class="forminp forminp-text"><?php echo '-' . esc_html( $value['add_points_using_api'] ); ?></td>
							<?php
						}
						?>
					<td class="forminp forminp-text"><?php echo esc_html( $value['reason'] ); ?></td>
				</tr>
						<?php
					}
					?>
			</table></div>
			<?php
		}
	}

	/**
	 * This Function add settings of round off on admin side.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_round_points_settings( $settings ) {

		$new_inserted_array = array(
			array(
				'title' => __( 'Points Round Off', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),

			array(

				'title' => __( 'Select Points Roundoff', 'ultimate-woocommerce-points-and-rewards' ),
				'id' => 'wps_wpr_point_round_off',
				'class' => 'wps_wgm_new_woo_ver_style_select',
				'type' => 'singleSelectDropDownWithKeyvalue',
				'desc_tip' => __( 'Select the discount Type to apply points', 'ultimate-woocommerce-points-and-rewards' ),
				'custom_attribute' => array(

					array(
						'id' => 'wps_wpr_round_up',
						'name' => 'Round Up',
					),
					array(
						'id' => 'wps_wpr_round_down',
						'name' => 'Round Down',
					),
				),
			),
			array(
				'type'  => 'sectionend',
			),
		);

		$settings = $this->insert_key_value_pair( $settings, $new_inserted_array, 200 );

		return $settings;
	}

	/**
	 * Wps_wpr_add_email_notification_settings_frontend_notification function.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_settings_frontend_notification( $settings ) {
		$addd = array(

			array(
				'title' => __( 'Mail template for  Email referral', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),
			array(
				'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'text',
				'id'            => 'wps_wpr_point_on_email_referal_subject',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'Use the referral link to join', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'textarea_email',
				'id'            => 'wps_wpr_point_on_email_referal_order_desc',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'You are being referred by [USERNAME] and Your Referral Link is [REFERALLINK].', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Use', 'ultimate-woocommerce-points-and-rewards' ) . '[REFERALLINK]' . __( ' shortcode in place of referral link', 'ultimate-woocommerce-points-and-rewards' ) . '[USERNAME]' . __( ' shortcode in place of username ', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'type'  => 'sectionend',
			),
		);
		$key = (int) $this->wps_wpr_get_email_refereal_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 102, 102, $addd );
		return array_merge( $arr2, $arr1 );

	}

	/**
	 * This function will add specific enable/disable for bday_points_notification
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_email_refereal_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_email' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * This function is used for coupon notification
	 * wps_wpr_add_email_notification_coupon_notification function
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_add_email_notification_coupon_notification( $settings ) {
		$addd = array(

			array(
				'title' => __( 'Mail Notification for Coupon referral', 'ultimate-woocommerce-points-and-rewards' ),
				'type'  => 'title',
			),
			array(
				'title'         => __( 'Enable', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'checkbox',
				'id'            => 'wps_wpr_point_on_coupon_setting_enable',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Check this box to enable the points notification  for  first order coupon referal completion.', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Points when referee gets points', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title'         => __( 'Email Subject', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'text',
				'id'            => 'wps_wpr_point_on_coupon_referal_subject',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Input subject for the email.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'Points added', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'title'         => __( 'Email Description', 'ultimate-woocommerce-points-and-rewards' ),
				'type'          => 'textarea_email',
				'id'            => 'wps_wpr_point_on_copon_referal_order_desc',
				'class'             => 'input-text',
				'desc_tip'      => __( 'Enter the Email Description for the user.', 'ultimate-woocommerce-points-and-rewards' ),
				'default'   => __( 'Your Coupon Code [COUPONCODE] is applied and you will get [POINTS] points', 'ultimate-woocommerce-points-and-rewards' ),
				'desc'          => __( 'Use', 'ultimate-woocommerce-points-and-rewards' ) . '[COUPONCODE]' . __( ' shortcode in place of coupon code', 'ultimate-woocommerce-points-and-rewards' ) . '[POINTS]' . __( ' shortcode in place of points ', 'ultimate-woocommerce-points-and-rewards' ),
			),
			array(
				'type'  => 'sectionend',
			),
		);
		$key = (int) $this->wps_wpr_get_copon_refereal_notification( $settings );
		$arr1 = array_slice( $settings, $key );
		$arr2 = array_slice( $settings, 0, $key );
		array_splice( $arr1, 103, 103, $addd );
		return array_merge( $arr2, $arr1 );

	}

	/**
	 * This function will add specific enable/disable for bday_points_notification.
	 *
	 * @param array $settings settings.
	 * @return array
	 */
	public function wps_wpr_get_copon_refereal_notification( $settings ) {
		if ( is_array( $settings ) && ! empty( $settings ) ) {
			foreach ( $settings as $key => $val ) {
				if ( array_key_exists( 'id', $val ) ) {
					if ( 'wps_wpr_general_referral_code_enable' == $val['id'] ) {
						return $key;
					}
				}
			}
		}
	}

	/**
	 * Wps_wpr_product_points_test function.
	 *
	 * @param string $wps_valid wps valid.
	 * @param object $product product.
	 * @return bool
	 */
	public function wps_wpr_product_points_test( $wps_valid, $product ) {

		if ( isset( $product ) && ! empty( $product ) ) {
			if ( $product->is_type( 'variable' ) && $product->has_child() ) {
				$wps_valid = true;
			}
		}

		return $wps_valid;
	}

	/**
	 * This function is used to import csv.
	 *
	 * @return void
	 */
	public function wps_large_scv_import() {
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		$start          = ! empty( $_POST['start'] ) ? sanitize_text_field( wp_unslash( $_POST['start'] ) ) : '';
		$limit          = ! empty( $_POST['limit'] ) ? sanitize_text_field( wp_unslash( $_POST['limit'] ) ) : '';
		$temp_file_path = ! empty( $_FILES['userpoints_csv_import']['tmp_name'] ) ? sanitize_text_field( wp_unslash( $_FILES['userpoints_csv_import']['tmp_name'] ) ) : '';
		$file_path      = ! empty( $_FILES['userpoints_csv_import']['name'] ) ? sanitize_text_field( wp_unslash( $_FILES['userpoints_csv_import']['name'] ) ) : '';
		if ( empty( pathinfo( $file_path, PATHINFO_EXTENSION ) ) ) {
			wp_send_json(
				array(
					'result' => false,
					'msg'    => 'Please choose file',
				)
			);
			wp_die();
		}
		if ( ( pathinfo( $file_path, PATHINFO_EXTENSION ) != 'csv' ) ) {
			wp_send_json(
				array(
					'result' => 'undefined',
					'msg'    => 'Please choose csv format',
				)
			);
			wp_die();
		}
		$imp_counter = 0;
		$success = $this->wps_file_get_contents_chunked(
			$temp_file_path,
			50,
			function( $chunk, &$handle, $iteration ) {
				global $imp_counter;
				if ( 0 == $imp_counter && ( 'Users Email' != $chunk[0] || 'Points' != $chunk[1] || '' != $chunk[3] ) ) {
					wp_send_json(
						array(
							'result' => 'incorrect headings',
							'msg'    => 'Please choose correct heading for csv',
						)
					);
					wp_die();
				}
				if ( 0 != $imp_counter ) {
					$this->wps_update_points_of_users( $chunk[0], $chunk[1] );
				}
				$imp_counter = 1;
			}
		);
		wp_send_json(
			array(
				'result' => true,
				'msg'    => 'imported successfully',
			)
		);
		wp_die();
	}

	/**
	 * This function is used to get write on csv file.
	 *
	 * @param string $file file.
	 * @param string $chunk_size size.
	 * @param string $callback callback.
	 * @return bool
	 */
	public function wps_file_get_contents_chunked( $file, $chunk_size, $callback ) {
		try {
			$handle = fopen( $file, 'r' );
			$i = 0;
			while ( ! feof( $handle ) ) {
				call_user_func_array( $callback, array( fgetcsv( $handle, $chunk_size ), &$handle, $i ) );
				$i++;
			}

			fclose( $handle );

		} catch ( Exception $e ) {
			return $e->getMessage();

		}
		return true;
	}

	/**
	 * Mwb_sfw_add_lock_custom_fields_ids function.
	 *
	 * @param int $ids id.
	 * @return string
	 */
	public function wps_wpr_add_lock_custom_fields_ids( $ids ) {

		$ids[] = 'wps_points_product_value';
		$ids[] = 'wps_points_product_purchase_value';
		$ids[] = 'wps_wpr_variable_points_';
		$ids[] = 'wps_wpr_variable_points_purchase_';
		$ids[] = 'wps_product_points_enable';
		$ids[] = 'wps_product_purchase_through_point_disable';
		$ids[] = 'wps_product_purchase_points_only';

		return apply_filters( 'wps_wpr_add_lock_fields_ids_pro', $ids );
	}

	/**
	 * This function is used to show icons on plugin listing page.
	 *
	 * @param string $links links.
	 * @param string $file files.
	 * @return string
	 */
	public function wps_wpr_custom_plugin_par_meta( $links, $file ) {

		if ( strpos( $file, 'ultimate-woocommerce-points-and-rewards/ultimate-woocommerce-points-and-rewards.php' ) !== false ) {

			$new_links = array(
				'demo'     => '<a href="https://demo.wpswings.com/points-and-rewards-for-woocommerce-pro/?utm_source=wpswings-par-demo&utm_medium=par-org-backend&utm_campaign=par-demo" target="_blank"><i class="far fa-file-alt" style="margin-right:3px;"></i><img src="' . esc_html( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'admin/images/Demo.svg" class="wps-info-img" alt="Demo image">' . esc_html__( 'Demo', 'ultimate-woocommerce-points-and-rewards' ) . '</a>',
				'doc'      => '<a href="https://docs.wpswings.com/points-and-rewards-for-woocommerce-pro/?utm_source=wpswings-par-doc&utm_medium=par-pro-backend&utm_campaign=doc" target="_blank"><i class="far fa-file-alt" style="margin-right:3px;"></i><img src="' . esc_html( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'admin/images/Documentation.svg" class="wps-info-img" alt="Demo image">' . esc_html__( 'Documentation', 'ultimate-woocommerce-points-and-rewards' ) . '</a>',
				'support'  => '<a href="https://wpswings.com/submit-query/?utm_source=wpswings-par-support&utm_medium=par-pro-backend&utm_campaign=support" target="_blank"><i class="fas fa-user-ninja" style="margin-right:3px;"></i><img src="' . esc_html( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'admin/images/Support.svg" class="wps-info-img" alt="Demo image">' . esc_html__( 'Support', 'ultimate-woocommerce-points-and-rewards' ) . '</a>',
				'services' => '<a href="https://wpswings.com/woocommerce-services/?utm_source=wpswings-par-services&utm_medium=par-pro-backend&utm_campaign=woocommerce-services" target="_blank"><i class="fas fa-user-ninja" style="margin-right:3px;"></i><img src="' . esc_html( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'admin/images/Services.svg" class="wps-info-img" alt="Demo image">' . esc_html__( 'Services', 'ultimate-woocommerce-points-and-rewards' ) . '</a>',
			);
			$links = array_merge( $links, $new_links );
		}
		return $links;
	}

	/**
	 * This function is used to export points table.
	 *
	 * @return void
	 */
	public function wps_wpr_export_points_table_call() {
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		if ( isset( $_POST ) ) {

			$user_data = get_users(
				array(
					'fields' => 'ID',
					'order'  => 'DESC',
				)
			);

			$arr_data   = array();
			$arr_data[] = array(
				esc_html__( 'Users Email', 'ultimate-woocommerce-points-and-rewards' ),
				esc_html__( 'Points', 'ultimate-woocommerce-points-and-rewards' ),
				esc_html__( 'User Name', 'ultimate-woocommerce-points-and-rewards' ),
			);
			
			if ( ! empty( $user_data ) && is_array( $user_data ) ) {
				foreach( $user_data as $user_id ) {
					
					$user        = get_user_by( 'id', $user_id );
					$user_name   = $user->display_name;
					$user_email  = $user->user_email;
					$user_points = get_user_meta( $user_id, 'wps_wpr_points', true );
					$user_points = ! empty( $user_points ) ? $user_points : 0;

					$arr_data[] = array(
						$user_email,
						$user_points,
						$user_name,
					);

				}
				wp_send_json( $arr_data );
			}
		}
		wp_die();
	}

	/**
	 * This function is used to remove assigned membership from user acoounts.
	 *
	 * @return void
	 */
	public function wps_wpr_remove_assigned_membership_call() {
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		if ( isset( $_POST ) ) {

			$level_name = ! empty( $_POST['level_name'] ) ? sanitize_text_field( wp_unslash( $_POST['level_name'] ) ) : '';
			$user_data  = get_users( array( 'meta_key' => 'membership_level', 'fields' => 'ID', ) );

			if ( ! empty( $user_data ) && is_array( $user_data ) ) {
				foreach ( $user_data as $user_id ) {

					$user_member_level_name = get_user_meta( $user_id, 'membership_level', true );
					if ( $user_member_level_name === $level_name ) {
						delete_user_meta( $user_id, 'membership_level' );
					}
				}
			}
		}
		wp_die();
	}

}
