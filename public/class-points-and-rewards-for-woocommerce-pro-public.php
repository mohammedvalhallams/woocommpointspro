<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/public
 * @author     makewebbetter <webmaster@wpswings.com>
 */
class Points_And_Rewards_For_Woocommerce_Pro_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'public/css/points-and-rewards-for-woocommerce-pro-public.css', array(), $this->version, 'all' );
		/*Check addon notification enable*/
		if ( $this->wps_wpr_check_enabled_notification_addon() && ( $this->wps_wpr_check_seected_page() || $this->wps_wpr_check_notification_shortcode_enable() ) ) {

			wp_enqueue_style( 'jquery_ui', POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'modal/css/jquery-ui.css', array(), $this->version );
			wp_enqueue_style( 'material_style', POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'modal/css/material.indigo-pink.min.css', array(), $this->version );
			wp_enqueue_style( 'material_modal', POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'modal/css/material-modal.css', array(), $this->version );
			wp_enqueue_style( 'material_icons', POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'modal/css/icon.css', array(), $this->version );
			wp_enqueue_style( 'modal_style', POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'modal/css/style.css', array(), $this->version );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$wps_wpr_enable   = '';
		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		if ( isset( $general_settings['wps_wpr_general_setting_enable'] ) ) {
			$wps_wpr_enable = $general_settings['wps_wpr_general_setting_enable'];
		}

		if ( ! empty( $wps_wpr_enable ) && 1 == $wps_wpr_enable ) {

			wp_enqueue_script( $this->plugin_name, POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'public/js/points-and-rewards-for-woocommerce-pro-public.js', array( 'jquery' ), $this->version, false );
			/*Get the settings of the products*/
			$wps_wpr_make_readonly = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_make_readonly' );
			/*Array of the*/
			$wps_wpr_array = array(
				'make_readonly'     => $wps_wpr_make_readonly,
				'ajaxurl'           => admin_url( 'admin-ajax.php' ),
				'mymessage'         => __( 'Try Again Invalid Email Id', 'ultimate-woocommerce-points-and-rewards' ),
				'myadminmessage'    => __( 'email template not assigned by admin Try again after sometime', 'ultimate-woocommerce-points-and-rewards' ),
				'myemailmessage'    => __( 'Please enter Your email address first', 'ultimate-woocommerce-points-and-rewards' ),
				'successmesg'       => __( 'success', 'ultimate-woocommerce-points-and-rewards' ),
				'match_email'       => __( "You can't send an email to yourself!", 'ultimate-woocommerce-points-and-rewards' ),
				'wps_wpr_nonc'      => wp_create_nonce( 'wps-wpr-verify-nonce' ),
				'wps_points_string' => esc_html__( 'Points', 'ultimate-woocommerce-points-and-rewards' ),

			);
			wp_localize_script( $this->plugin_name, 'wps_wpr_pro', $wps_wpr_array );

			/*Check addon notification enable*/
			if ( $this->wps_wpr_check_enabled_notification_addon() && ( $this->wps_wpr_check_seected_page() || $this->wps_wpr_check_notification_shortcode_enable() ) ) {

				wp_enqueue_script( 'notify-user-public', POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'public/js/notify-user-public.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( 'wps_materal_modal_min_js', POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'modal/js/material-modal.min.js', array( 'jquery' ), $this->version, true );
				wp_enqueue_script( 'wps_materal_modal', POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'modal/js/material.min.js', array(), $this->version );
				wp_enqueue_script( 'jquery-ui-draggable' );
			}
		}
	}

	/**
	 * This function is used for getting the product purchase points
	 *
	 * @name wps_wpr_get_general_settings
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param string $id for key of the settings.
	 */
	public function wps_wpr_get_product_purchase_settings_num( $id ) {
		$wps_wpr_value    = 0;
		$general_settings = get_option( 'wps_wpr_product_purchase_settings', true );
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
	 * @param string $id name of the option.
	 */
	public function wps_wpr_get_product_purchase_settings( $id ) {
		$wps_wpr_value    = '';
		$general_settings = get_option( 'wps_wpr_product_purchase_settings', true );
		if ( ! empty( $general_settings[ $id ] ) ) {
			$wps_wpr_value = $general_settings[ $id ];
		}
		return $wps_wpr_value;
	}

	/**
	 * Add the referral link parameter in the woocommerce.
	 *
	 * @name wps_wpr_add_referral_section
	 * @since    1.0.0
	 * @param int $user_id  user id of the customer.
	 */
	public function wps_wpr_add_referral_section( $user_id ) {

		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		/* Get the Refer Minimum Value*/
		$wps_refer_min                   = isset( $general_settings['wps_wpr_general_refer_minimum'] ) ? intval( $general_settings['wps_wpr_general_refer_minimum'] ) : 1;
		$wps_wpr_referral_link_permanent = isset( $general_settings['wps_wpr_referral_link_permanent'] ) ? intval( $general_settings['wps_wpr_referral_link_permanent'] ) : 0;
		$get_referral                    = get_user_meta( $user_id, 'wps_points_referral', true );
		$get_referral_invite             = get_user_meta( $user_id, 'wps_points_referral_invite', true );
		if ( isset( $get_referral ) && isset( $get_referral_invite ) && null != $get_referral_invite && $get_referral_invite >= $wps_refer_min ) {
			if ( 0 == $wps_wpr_referral_link_permanent ) {

				$referral_key = wps_wpr_create_referral_code();
				update_user_meta( $user_id, 'wps_points_referral', $referral_key );
			}
			/* update the invites as soon as user got the referral rewards */
			$referral_invite = 0;
			update_user_meta( $user_id, 'wps_points_referral_invite', $referral_invite );
		}
	}

	/**
	 * Add the text below the referral link.
	 *
	 * @name wps_wpr_add_invite_text
	 * @since    1.0.0
	 * @param int $user_id  user id of the customer.
	 */
	public function wps_wpr_add_invite_text( $user_id ) {

		$general_settings        = get_option( 'wps_wpr_settings_gallery', true );
		$wps_refer_min           = isset( $general_settings['wps_wpr_general_refer_minimum'] ) ? intval( $general_settings['wps_wpr_general_refer_minimum'] ) : 1;
		$get_referral_invite     = get_user_meta( $user_id, 'wps_points_referral_invite', true );
		$wps_refer_value         = isset( $general_settings['wps_wpr_general_refer_value'] ) ? intval( $general_settings['wps_wpr_general_refer_value'] ) : 1;
		$wps_refer_value_disable = isset( $general_settings['wps_wpr_general_refer_value_disable'] ) ? intval( $general_settings['wps_wpr_general_refer_value_disable'] ) : 1;
		if ( ! $wps_refer_value_disable ) { ?>
			<p class="wps_wpr_message">
				<?php
				esc_html_e( ' Minimum ', 'ultimate-woocommerce-points-and-rewards' );
				echo esc_html( $wps_refer_min );
				esc_html_e( ' Invite Required by the User to Get a Reward of  ', 'ultimate-woocommerce-points-and-rewards' );
				echo esc_html( $wps_refer_value );
				esc_html_e( ' Referral Points', 'ultimate-woocommerce-points-and-rewards' );
				?>
			</p>
			<p> 
				<?php
				if ( $wps_refer_min > 1 ) {
					echo esc_html__( 'Current Invites: ', 'ultimate-woocommerce-points-and-rewards' ) . esc_html( $get_referral_invite );
				}
				?>
			</p>
			<?php
		} else {
			?>
			<p><?php echo esc_html__( 'Invite Users to get Reward Points on their Purchase using the Referral Link.', 'ultimate-woocommerce-points-and-rewards' ); ?>
			<?php
		}
	}

	/**
	 * Referrals points rescrtion.
	 *
	 * @name wps_wpr_add_referral_resctrictions
	 * @since    1.0.0
	 */
	public function generate_public_obj() {
		$public_obj = new Points_Rewards_For_WooCommerce_Public( 'points-and-rewards-for-woocommerce-pro', '1.1.1' );
		return $public_obj;
	}

	/**
	 * Referrals points Restriction.
	 *
	 * @name wps_wpr_add_referral_resctrictions
	 * @since    1.0.0
	 * @param bool $is_referral_true  this variable will return true or false.
	 * @param int  $customer_id user id of the customer.
	 * @param int  $refere_id  refere_id of the refered user.
	 */
	public function wps_wpr_add_referral_resctrictions( $is_referral_true, $customer_id, $refere_id ) {

		$user_id             = $refere_id;
		$get_referral_invite = get_user_meta( $user_id, 'wps_points_referral_invite', true );
		/*Generate public obj*/
		$public_obj = $this->generate_public_obj();
		/*Get the minimum referral required for giving the signup points*/
		$wps_refer_min           = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_refer_minimum' );
		$wps_refer_value_disable = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_refer_value_disable' );
		/*Custom Work*/
		$custom_ref_pnt = get_user_meta( $user_id, 'wps_custom_points_referral_invite', true );
		/*Check the condition of the minimum referral requred*/
		if ( $get_referral_invite < $wps_refer_min ) {
			$get_referral_invite = (int) $get_referral_invite;
			update_user_meta( $user_id, 'wps_points_referral_invite', $get_referral_invite + 1 );
			update_user_meta( $customer_id, 'user_visit_through_link', $user_id );
			$custom_ref_pnt = (int) $custom_ref_pnt;
			update_user_meta( $user_id, 'wps_custom_points_referral_invite', $custom_ref_pnt + 1 );
			$public_obj->wps_wpr_destroy_cookie();
			$is_referral_true = false;
		}
		$get_referral_invite = get_user_meta( $user_id, 'wps_points_referral_invite', true );
		if ( $get_referral_invite == $wps_refer_min ) {
			// update_user_meta( $user_id, 'wps_points_referral_invite', 0 ).
			/*Check Assign product points is not enable*/
			if ( ! $wps_refer_value_disable ) {
				$is_referral_true = true;
			}
		}
		return $is_referral_true;
	}

	/**
	 * This function is used to edit comment template for points
	 *
	 * @name wps_wpr_woocommerce_comment_point.
	 * @param array $comment_data  all data related to the comment.
	 * @since 1.0.0
	 * @return array
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_comment_point( $comment_data ) {

		global $current_user,$post;
		$public_obj            = $this->generate_public_obj();
		$args                  = array(
			'user_id' => $current_user->ID,
			'post_id' => $post->ID,
		);
		$wps_wpr_comment_limit = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_comment_per_post_comment' );
		$usercomment           = get_comments( $args );
		$user_id               = get_current_user_ID();
		WC()->session->set( 'm1', $usercomment );
		WC()->session->set( 'm2', $wps_wpr_comment_limit );

		if ( count( $usercomment ) < $wps_wpr_comment_limit ) {
			$wps_wpr_comment_enable = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_comment_enable' );
			if ( isset( $wps_wpr_comment_enable ) && 1 == $wps_wpr_comment_enable && isset( $user_id ) && ! empty( $user_id ) ) {

				$wps_wpr_comment_value = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_comment_value' );
				$wps_wpr_comment_value = ( 0 == $wps_wpr_comment_value ) ? 1 : $wps_wpr_comment_value;
				$comment_data['comment_field'] .= '<p class="comment-wps-wpr-points-comment"><label>' . esc_html__( 'You will get ', 'ultimate-woocommerce-points-and-rewards' ) . esc_html( $wps_wpr_comment_value ) . esc_html__( ' points for the products review', 'ultimate-woocommerce-points-and-rewards' ) . '</p>';

			}
			return $comment_data;
		}
		return $comment_data;
	}

	/**
	 * This function is used to give product points to user if order status of Product is complete and processing.
	 *
	 * @name wps_wpr_woocommerce_order_status_changed
	 * @param int    $order_id id of the order.
	 * @param string $old_status status of the order.
	 * @param string $new_status new status of the order.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_pro_woocommerce_order_status_changed( $order_id, $old_status, $new_status ) {
		if ( $old_status != $new_status ) {

			$order        = wc_get_order( $order_id );
			$itempointset = get_post_meta( $order_id, "$order_id#refral_conversion_id", true );
			if ( isset( $itempointset ) && 'set' == $itempointset ) {
				return;
			}

			// akash.
			$user_id                = $order->get_user_id();
			$wps_wpr_ref_noof_order = get_user_meta( $user_id, 'wps_wpr_no_of_orders', true );
			$order_limit            = get_post_meta( $order_id, "$order_id#$wps_wpr_ref_noof_order", true );
			if ( isset( $order_limit ) && 'set' == $order_limit ) {
				return;
			}

			/*Generate object of the public class*/
			$public_obj = $this->generate_public_obj();
			/*Check is referral purchase is enable*/
			$wps_referral_purchase_enable = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_referal_purchase_enable' );
			/*Get the referral purchase value*/
			$wps_referral_purchase_value = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_referal_purchase_value' );
			/*Assign Default value to 1*/
			$wps_referral_purchase_value                 = ( 0 == $wps_referral_purchase_value ) ? 1 : $wps_referral_purchase_value;
			$wps_wpr_general_referal_purchase_point_type = $public_obj->wps_wpr_get_general_settings( 'wps_wpr_general_referal_purchase_point_type' );

			if ( 'wps_wpr_percentage_points' == $wps_wpr_general_referal_purchase_point_type ) {
				$order_total = $order->get_total();
				// WOOCS - WooCommerce Currency Switcher Compatibility.
				$order_total        = apply_filters( 'wps_wpr_convert_same_currency_base_price', $order_total, $order_id );
				$round_down_setting = $this->wps_general_setting();

				if ( 'wps_wpr_round_down' == $round_down_setting ) {
					$wps_referral_purchase_value = floor( ( $wps_referral_purchase_value * $order_total ) / 100 );
				} else {
					$wps_referral_purchase_value = ceil( ( $wps_referral_purchase_value * $order_total ) / 100 );
				}
			}

			$wps_referral_purchase_limit         = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_referal_purchase_limit' );
			$wps_refer_value                     = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_refer_value' );
			$wps_wpr_general_referal_order_limit = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_referal_order_limit' );

			if ( 'completed' == $new_status || 'processing' == $new_status ) {
				/*Referral Purchase*/
				if ( $wps_referral_purchase_enable ) {

					$refer_id   = get_user_meta( $user_id, 'user_visit_through_link', true );
					$refer_user = get_user_by( 'ID', $refer_id );
					/*Check that Refer is not empty*/
					if ( ! empty( $refer_user ) ) {
						$referee_user_name = $refer_user->user_firstname;
					}

					if ( 0 == $wps_referral_purchase_limit ) {
						if ( isset( $refer_id ) && ! empty( $refer_id ) ) {

							/*Get total points of the referred user*/
							$prev_points_of_ref_userid = (int) get_user_meta( $refer_id, 'wps_wpr_points', true );
							$update_points             = $prev_points_of_ref_userid + $wps_referral_purchase_value;
							/*Update users Total points*/
							update_user_meta( $refer_id, 'wps_wpr_points', $update_points );
							update_post_meta( $order_id, "$order_id#refral_conversion_id", 'set' );
							/*Update points details*/
							$data = array(
								'referr_id' => $user_id,
							);
							$public_obj->wps_wpr_update_points_details( $refer_id, 'ref_product_detail', $wps_referral_purchase_value, $data );
							/*Shortcode Array*/
							$wps_wpr_shortcode = array(
								'[Points]'                    => $wps_referral_purchase_value,
								'[Total Points]'              => $update_points,
								'[Refer Points]'              => $wps_refer_value,
								'[Comment Points]'            => $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_comment_value' ),
								'[Per Currency Spent Points]' => $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_conversion_points' ),
								'[USERNAME]'                  => $referee_user_name,
							);

							/*Insert id of the subject and email subjects*/
							$wps_wpr_subject_content = array(
								'wps_wpr_subject' => 'wps_wpr_referral_purchase_email_subject',
								'wps_wpr_content' => 'wps_wpr_referral_purchase_email_discription_custom_id',
							);

							/*Send mail to client regarding product purchase*/
							$public_obj->wps_wpr_send_notification_mail_product( $refer_id, $wps_referral_purchase_value, $wps_wpr_shortcode, $wps_wpr_subject_content );
						}
					} else {

						if ( isset( $wps_wpr_ref_noof_order ) && ! empty( $wps_wpr_ref_noof_order ) && $wps_wpr_ref_noof_order <= $wps_wpr_general_referal_order_limit ) {
							/*Check Refer is is not empty*/
							if ( isset( $refer_id ) && ! empty( $refer_id ) ) {
	
								$prev_points_of_ref_userid = (int) get_user_meta( $refer_id, 'wps_wpr_points', true );
								$update_points             = $prev_points_of_ref_userid + $wps_referral_purchase_value;

								/*Update users Total points*/
								update_user_meta( $refer_id, 'wps_wpr_points', $update_points );
								update_post_meta( $order_id, "$order_id#refral_conversion_id", 'set' );
								/*Update points details*/
								$data = array(
									'referr_id' => $user_id,
								);
								$public_obj->wps_wpr_update_points_details( $refer_id, 'ref_product_detail', $wps_referral_purchase_value, $data );
								/*Insert id of the subject and email subjects*/
								$wps_wpr_subject_content = array(
									'wps_wpr_subject' => 'wps_wpr_referral_purchase_email_subject',
									'wps_wpr_content' => 'wps_wpr_referral_purchase_email_discription_custom_id',
								);

								/*Shortcode Array*/
								$wps_wpr_shortcode = array(
									'[Points]'                    => $wps_referral_purchase_value,
									'[Total Points]'              => $update_points,
									'[Refer Points]'              => $wps_refer_value,
									'[Comment Points]'            => $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_comment_value' ),
									'[Per Currency Spent Points]' => $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_conversion_points' ),
									'[USERNAME]'                  => $referee_user_name,
								);
								/*Send mail to client regarding product purchase*/
								$public_obj->wps_wpr_send_notification_mail_product( $refer_id, $wps_referral_purchase_value, $wps_wpr_shortcode, $wps_wpr_subject_content );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * This function use to add coupon generation
	 *
	 * @name wps_wpr_woocommerce_order_status_changed
	 * @param int $user_id user id of the user.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_add_coupon_conversion_settings( $user_id ) {
		$public_obj = $this->generate_public_obj();
		/*Check is checkbox is enable*/
		$wps_wpr_disable_coupon_generation = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_enable_coupon_generation' );
		/*Get Coupon Redeem Points*/
		$coupon_redeem_points = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_redeem_points' );
		$coupon_redeem_points = ( $coupon_redeem_points ) ? $coupon_redeem_points : 1;
		/*Check Coupon Redeem Price*/
		$coupon_redeem_price = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_redeem_price' );
		$coupon_redeem_price = ( $coupon_redeem_price ) ? $coupon_redeem_price : 1;

		/*Get total points of the users*/
		$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
		/*Get minimum pints value for coupon generation*/
		$wps_minimum_points_value = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_general_minimum_value' );
		/*Check Enable Custom Convert Points*/
		$enable_custom_convert_point = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_general_custom_convert_enable' );
		if ( 1 == $wps_wpr_disable_coupon_generation ) {
			?>
			<p class="wps_wpr_heading"><?php echo esc_html__( 'Points Conversion', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
			<fieldset class="wps_wpr_each_section">
				<p>
				<!-- WOOCS - WooCommerce Currency Switcher Compatibility. -->
					<?php echo esc_html__( 'Points Conversion: ', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<?php echo esc_html( $coupon_redeem_points ) . esc_html__( ' points = ', 'ultimate-woocommerce-points-and-rewards' ) . wp_kses_post( wc_price( apply_filters( 'wps_wpr_show_conversion_price', $coupon_redeem_price ) ) ); ?>
				</p>
				<form id="points_form" enctype="multipart/form-data" action="" method="post">
					<?php
					if ( is_numeric( $wps_minimum_points_value ) ) {
						if ( $wps_minimum_points_value <= $get_points ) {
							if ( 1 == $enable_custom_convert_point ) {
								?>
								<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
									<label for="wps_custom_text">
										<?php esc_html_e( 'Enter your points:', 'ultimate-woocommerce-points-and-rewards' ); ?>
									</label>
									<p id="wps_wpr_points_notification"></p>
									<input type="number" class="woocommerce-Input woocommerce-Input--number input-number" name="wps_custom_number" min="1" id="wps_custom_point_num" style="width: 160px;">

									<input type="button" name="wps_wpr_custom_coupon" class="wps_wpr_custom_coupon button" value="<?php esc_html_e( 'Generate Coupon', 'ultimate-woocommerce-points-and-rewards' ); ?>" data-id="<?php echo esc_html( $user_id ); ?>">
								</p>
								<?php
							} else {
								?>
								<p class="wps_wpr_coupon_auto_generate_notice"><?php esc_html_e( 'Convert Points To Coupon', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
								<p id="wps_wpr_points_notification"></p>
								<input type="button" name="wps_wpr_generate_coupon" class="wps_wpr_generate_coupon button" value="<?php esc_html_e( 'Generate Coupon', 'ultimate-woocommerce-points-and-rewards' ); ?>" data-id="<?php echo esc_html( $user_id ); ?>">
								<?php
							}
						} else {
							/* translators: %u: minimum points */
							printf( esc_html__( 'The minimum points required to convert points to coupons is %u', 'ultimate-woocommerce-points-and-rewards' ), esc_html( $wps_minimum_points_value ) );
						}
					}
					?>
				</form>
			</fieldset>
			<?php
		}
	}

	/**
	 * This is used for listing of the Coupons
	 *
	 * @name wps_wpr_list_coupons_generation
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param int $user_id id of the user.
	 */
	public function wps_wpr_list_coupons_generation( $user_id ) {
		$user_log = get_user_meta( $user_id, 'wps_wpr_user_log', true );
		if ( isset( $user_log ) && is_array( $user_log ) && ! empty( $user_log ) ) {
			?>

			<p class="wps_wpr_heading"><?php echo esc_html__( 'Coupon Details', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
			<div class="points_log">
				<table class="woocommerce-MyAccount-points shop_table my_account_points account-points-table wps_wpr_coupon_details">
					<thead>
						<tr>
							<th class="points-points">
								<span class="nobr"><?php echo esc_html__( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
							<th class="points-code">
								<span class="nobr"><?php echo esc_html__( 'Coupon Code', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
							<th class="points-amount">
								<span class="nobr"><?php echo esc_html__( 'Coupon Amount', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
							<th class="points-left">
								<span class="nobr"><?php echo esc_html__( 'Amount Left', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
							<th class="points-expiry">
								<span class="nobr"><?php echo esc_html__( 'Expiry', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $user_log as $key => $wps_user_log ) : ?>
							<tr class="points">
								<?php foreach ( $wps_user_log as $column_id => $column_name ) : ?>
									<td class="points-<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_id ); ?>" >
										<?php

										$wps_split = explode( '#', $key );
										if ( 'left' == $column_id ) {
											$column_name = get_post_meta( $wps_split[1], 'coupon_amount', true );
											// WOOCS - WooCommerce Currency Switcher Compatibility.
											$column_name = apply_filters( 'wps_wpr_show_conversion_price', $column_name );
											echo esc_html( get_woocommerce_currency_symbol() ) . esc_html( $column_name );
										} elseif ( 'camount' == $column_id ) {
											$column_name = get_post_meta( $wps_split[1], 'wps_coupon_static_amount', true );
											// WOOCS - WooCommerce Currency Switcher Compatibility.
											$column_name = apply_filters( 'wps_wpr_show_conversion_price', $column_name );
											echo esc_html( get_woocommerce_currency_symbol() ) . esc_html( $column_name );
										} elseif ( 'expiry' == $column_id ) {
											if ( WC()->version < '3.0.6' ) {

												$column_name = get_post_meta( $wps_split[1], 'expiry_date', true );
												echo esc_html( $column_name );
											} else {
												$column_name = get_post_meta( $wps_split[1], 'date_expires', true );
												if ( ! empty( $column_name ) ) {
													$dt = new DateTime( "@$column_name" );
													echo esc_html( $dt->format( 'Y-m-d' ) );
												} else {
													esc_html_e( 'No Expiry', 'ultimate-woocommerce-points-and-rewards' );
												}
											}
										} else {
											echo esc_html( $column_name );
										}
										?>
									</td>
								<?php endforeach; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>			
			<?php
		} else {
			?>
			<div class="points_log" style="display: none"></div>
			<?php
		}
	}

	/**
	 * This function is used to generate coupon of total points.
	 *
	 * @name wps_wpr_generate_original_coupon
	 * @since 1.0.0
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */// check.
	public function wps_wpr_generate_original_coupon() {
		/*Check Ajax Nonce*/
		define( 'WP_DEBUG_DISPLAY', true );
		error_reporting( E_ALL );
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		/*Create object of the public object*/
		$public_obj          = $this->generate_public_obj();
		$response['result']  = false;
		$response['message'] = __( 'Coupon generation error.', 'ultimate-woocommerce-points-and-rewards' );
		if ( isset( $_POST['user_id'] ) && ! empty( $_POST['user_id'] ) ) {

			/*Get the the user id*/
			$user_id = sanitize_text_field( wp_unslash( $_POST['user_id'] ) );
			/*Get all user points*/
			$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
			/*Get the coupon length*/
			$wps_coupon_length = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_points_coupon_length' );
			$wps_coupon_length = ( 0 == $wps_coupon_length ) ? 1 : $wps_coupon_length;
			$tot_points        = ( isset( $get_points ) && null != $get_points ) ? (int) $get_points : 0;

			if ( $tot_points ) {

				/*Generate the coupon number*/
				$couponnumber = wps_wpr_coupon_generator( $wps_coupon_length );
				/*Get the Redeem Price*/
				$coupon_redeem_price = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_redeem_price' );
				$coupon_redeem_price = ( 0 == $coupon_redeem_price ) ? 1 : $coupon_redeem_price;
				/*Get the coupon Redeem Points*/
				$coupon_redeem_points = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_redeem_points' );
				$coupon_redeem_points = ( 0 == $coupon_redeem_points ) ? 1 : $coupon_redeem_points;

				$coupon_redeem_price = str_replace( wc_get_price_decimal_separator(), '.', strval( $coupon_redeem_price ) );
				$couponamont         = ( $get_points * $coupon_redeem_price ) / $coupon_redeem_points;
				$couponamont         = str_replace( '.', wc_get_price_decimal_separator(), strval( $couponamont ) );

				if ( $this->wps_wpr_create_points_coupon( $couponnumber, $couponamont, $user_id, $get_points ) ) {
					$user_log = get_user_meta( $user_id, 'wps_wpr_user_log', true );
					// $user_log = array_reverse( $user_log );
					$response['html'] = '<table class="woocommerce-MyAccount-points shop_table shop_table_responsive my_account_points account-points-table">
					<thead>
						<tr>
							<th class="points-points">
								<span class="nobr">' . esc_html__( 'Points', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
							<th class="points-code">
								<span class="nobr">' . esc_html__( 'Coupon Code', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
							<th class="points-amount">
								<span class="nobr">' . esc_html__( 'Coupon Amount', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
							<th class="points-left">
								<span class="nobr">' . esc_html__( 'Amount Left', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
							<th class="points-expiry">
								<span class="nobr">' . esc_html__( 'Expiry', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
						</tr>
					</thead>
					<tbody>';

					foreach ( $user_log as $key => $wps_user_log ) {
						$response['html'] .= '<tr class="points">';
						foreach ( $wps_user_log as $column_id => $column_name ) {
							$response['html'] .= '<td class="points-' . esc_attr( $column_id ) . '" >';
							if ( 'left' == $column_id ) {
								$wps_split = explode( '#', $key );
								$column_name = get_post_meta( $wps_split[1], 'coupon_amount', true );
								$response['html'] .= get_woocommerce_currency_symbol() . $column_name;
							} elseif ( 'expiry' == $column_id ) {
								if ( WC()->version < '3.0.6' ) {

									$column_name = get_post_meta( $wps_split[1], 'expiry_date', true );
									$response['html'] .= $column_name;
								} else {
									$column_name = get_post_meta( $wps_split[1], 'date_expires', true );
									if ( ! empty( $column_name ) ) {
										$dt = new DateTime( "@$column_name" );
										$response['html'] .= $dt->format( 'Y-m-d' );
									} else {
										$response['html'] .= esc_html__( 'No Exprity', 'ultimate-woocommerce-points-and-rewards' );
									}
								}
							} else {
								$response['html'] .= $column_name;
							}
							$response['html'] .= '</td>';
						}
						$response['html'] .= '</tr>';
					}
						$response['html'] .= '</tbody>
					</table>';
					$response['result'] = true;
					$response['message'] = esc_html__( 'Your points are converted to coupon', 'ultimate-woocommerce-points-and-rewards' );
					$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
					$response['points'] = $get_points;
				}
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * This function is used to generate coupon according to points.
	 *
	 * @name wps_wpr_create_points_coupon
	 * @since 1.0.0
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param int $couponnumber  coupon code of the coupon.
	 * @param int $couponamont  amount of the coupon.
	 * @param int $user_id  id of the user.
	 * @param int $points   points that will converted to the coupon amount.
	 */
	public function wps_wpr_create_points_coupon( $couponnumber, $couponamont, $user_id, $points ) {
		/*Create object of the public object*/
		$public_obj = $this->generate_public_obj();
		/*Coupon Code*/
		$coupon_code        = $couponnumber; // Code.
		$amount             = $couponamont; // Amount.
		$woo_ver            = WC()->version;// Version.
		$discount_type      = 'fixed_cart';
		$coupon_description = esc_html__( 'Points And Reward - User ID#', 'ultimate-woocommerce-points-and-rewards' ) . $user_id;

		$coupon = array(
			'post_title'   => $coupon_code,
			'post_content' => $coupon_description,
			'post_excerpt' => $coupon_description,
			'post_status'  => 'publish',
			'post_author'  => $user_id,
			'post_type'    => 'shop_coupon',
		);

		$new_coupon_id = wp_insert_post( $coupon );
		if ( $new_coupon_id ) {
			$coupon_obj = new WC_Coupon( $new_coupon_id );
			$coupon_obj->save();
		}
		/*Get the settings of the Individual Coupon Settings*/
		$individual_use = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_individual_use' );

		if ( $individual_use ) {
			$individual_use = 'yes';
		} else {
			$individual_use = 'no';
		}
		/*Get the value of the shipping from the coupon settings*/
		$free_shipping = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_points_freeshipping' );
		if ( $free_shipping ) {
			$free_shipping = 'yes';
		} else {
			$free_shipping = 'no';
		}
		/*Get the coupon length*/
		$wps_coupon_length = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_points_coupon_length' );
		$wps_coupon_length = ( 0 == $wps_coupon_length ) ? 5 : $wps_coupon_length;
		/*Get the expriy date of the coupons*/
		$expiry_date = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_expiry' );
		/*Get the coupon minimum expend*/
		$minimum_amount = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_minspend' );
		/*Get the max expend of the coupon*/
		$maximum_amount = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_maxspend' );

		$usage_limit = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_use' );
		/*Get the current date*/
		$todaydate = date_i18n( 'Y-m-d' );
		if ( $expiry_date > 0 ) {
			$expirydate = date_i18n( 'Y-m-d', strtotime( "$todaydate +$expiry_date day" ) );
		} else {
			$expirydate = '';
		}
		/*Get the user data*/
		$user = get_user_by( 'ID', $user_id );
		/*Get the user mail*/
		$user_email = $user->user_email;
		/*update post meta of the coupon*/
		update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
		update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
		update_post_meta( $new_coupon_id, 'individual_use', $individual_use );

		// update coupon original amount.
		$wps_coupon_static_amount = get_post_meta( $new_coupon_id, 'wps_coupon_static_amount', true );
		if ( empty( $wps_coupon_static_amount ) || is_null( $wps_coupon_static_amount ) ) {
			update_post_meta( $new_coupon_id, 'wps_coupon_static_amount', $amount );
		}

		if ( 0 != $usage_limit ) {
			update_post_meta( $new_coupon_id, 'usage_limit', $usage_limit );
		}
		/*Coupons Expriry date*/
		if ( ! empty( $expirydate ) ) {
			if ( $woo_ver < '3.6.0' ) {
				update_post_meta( $new_coupon_id, 'expiry_date', $expirydate );
			} else {
				$expirydate = strtotime( $expirydate );
				update_post_meta( $new_coupon_id, 'date_expires', $expirydate );
			}
		}
		update_post_meta( $new_coupon_id, 'free_shipping', $free_shipping );
		if ( 0 != $minimum_amount ) {
			update_post_meta( $new_coupon_id, 'minimum_amount', $minimum_amount );
		}
		if ( 0 != $maximum_amount ) {
			update_post_meta( $new_coupon_id, 'maximum_amount', $maximum_amount );
		}
		update_post_meta( $new_coupon_id, 'customer_email', $user_email );
		update_post_meta( $new_coupon_id, 'wps_wpr_points_coupon', $user_id );
		if ( empty( $expirydate ) ) {
			$expirydate = esc_html__( 'No Expiry', 'ultimate-woocommerce-points-and-rewards' );
		}

		$get_points          = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
		$available_points    = $get_points - $points;
		$coupon_point_detail = get_user_meta( $user_id, 'points_details', true );
		$today_date          = date_i18n( 'Y-m-d h:i:sa' );

		if ( isset( $coupon_point_detail['Coupon_details'] ) && ! empty( $coupon_point_detail['Coupon_details'] ) ) {
			$coupon_array = array(
				'Coupon_details' => $points,
				'date' => $today_date,
			);
			$coupon_point_detail['Coupon_details'][] = $coupon_array;
		} else {
			if ( ! is_array( $coupon_point_detail ) ) {
				$coupon_point_detail = array();
			}
			$coupon_array = array(
				'Coupon_details' => $points,
				'date' => $today_date,
			);
			$coupon_point_detail['Coupon_details'][] = $coupon_array;
		}
		update_user_meta( $user_id, 'wps_wpr_points', $available_points );
		update_user_meta( $user_id, 'points_details', $coupon_point_detail );
		$user_log = get_user_meta( $user_id, 'wps_wpr_user_log', true );
		if ( empty( $user_log ) ) {

			$user_log = array();
			$user_log[ 'wps_wpr_' . $coupon_code . '#' . $new_coupon_id ] = array(
				'points'  => $points,
				'code'    => $coupon_code,
				'camount' => get_woocommerce_currency_symbol() . $amount,
				'left'    => get_woocommerce_currency_symbol() . $amount,
				'expiry'  => $expirydate,
			);
		} else {

			$user_log[ 'wps_wpr_' . $coupon_code . '#' . $new_coupon_id ] = array(
				'points'  => $points,
				'code'    => $coupon_code,
				'camount' => get_woocommerce_currency_symbol() . $amount,
				'left'    => get_woocommerce_currency_symbol() . $amount,
				'expiry'  => $expirydate,
			);
		}
		update_user_meta( $user_id, 'wps_wpr_user_log', $user_log );
		return true;
	}

	/**
	 * This function is used to generate coupon for custom points.
	 *
	 * @name wps_wpr_generate_custom_coupon
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_generate_custom_coupon() {
		/*Create object of the public object*/
		define( 'WP_DEBUG_DISPLAY', true );
		error_reporting( E_ALL );
		$public_obj = $this->generate_public_obj();
		/*Check Ajax Referer*/
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		$response['result']  = false;
		$response['message'] = __( 'Coupon generation error.', 'ultimate-woocommerce-points-and-rewards' );
		if ( isset( $_POST['points'] ) && ! empty( $_POST['points'] ) && isset( $_POST['user_id'] ) && ! empty( $_POST['user_id'] ) ) {

			$user_id       = sanitize_text_field( wp_unslash( $_POST['user_id'] ) );
			$get_points    = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
			$custom_points = sanitize_text_field( wp_unslash( $_POST['points'] ) );

			/*Get the coupon length*/
			$wps_coupon_length = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_points_coupon_length' );
			$wps_coupon_length = ( 0 == $wps_coupon_length ) ? 5 : $wps_coupon_length;

			if ( $custom_points <= $get_points ) {

				/*Generate the coupon number*/
				$couponnumber = wps_wpr_coupon_generator( $wps_coupon_length );
				/*Get the Redeem Price*/
				$coupon_redeem_price = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_redeem_price' );
				$coupon_redeem_price = ( 0 == $coupon_redeem_price ) ? 1 : $coupon_redeem_price;
				/*Get the coupon Redeem Points*/
				$coupon_redeem_points = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_redeem_points' );
				$coupon_redeem_points = ( 0 == $coupon_redeem_points ) ? 1 : $coupon_redeem_points;

				$coupon_redeem_price = str_replace( wc_get_price_decimal_separator(), '.', strval( $coupon_redeem_price ) );
				$couponamont         = ( $custom_points * $coupon_redeem_price ) / $coupon_redeem_points;
				$couponamont         = str_replace( '.', wc_get_price_decimal_separator(), strval( $couponamont ) );

				if ( $this->wps_wpr_create_points_coupon( $couponnumber, $couponamont, $user_id, $custom_points ) ) {
					$user_log = get_user_meta( $user_id, 'wps_wpr_user_log', true );
					$response['html'] = '<table class="woocommerce-MyAccount-points shop_table shop_table_responsive my_account_points account-points-table">
					<thead>
						<tr>
							<th class="points-points">
								<span class="nobr">' . esc_html__( 'Points', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
							<th class="points-code">
								<span class="nobr">' . esc_html__( 'Coupon Code', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
							<th class="points-amount">
								<span class="nobr">' . esc_html__( 'Coupon Amount', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
							<th class="points-left">
								<span class="nobr">' . esc_html__( 'Amount Left', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
							<th class="points-expiry">
								<span class="nobr">' . esc_html__( 'Expiry', 'ultimate-woocommerce-points-and-rewards' ) . '</span>
							</th>
						</tr>
					</thead>
					<tbody>';
					$user_log = array_reverse( $user_log );
					foreach ( $user_log as $key => $wps_user_log ) {

						$response['html'] .= '<tr class="points">';
						foreach ( $wps_user_log as $column_id => $column_name ) {
							$response['html'] .= '<td class="points-' . esc_attr( $column_id ) . '" >';
							if ( 'left' == $column_id ) {
								$wps_split = explode( '#', $key );
								$column_name = get_post_meta( $wps_split[1], 'coupon_amount', true );
								$response['html'] .= get_woocommerce_currency_symbol() . $column_name;
							} elseif ( 'expiry' == $column_id ) {
								if ( WC()->version < '3.0.6' ) {

									$column_name = get_post_meta( $wps_split[1], 'expiry_date', true );
									$response['html'] .= $column_name;
								} else {
									$column_name = get_post_meta( $wps_split[1], 'date_expires', true );
									if ( ! empty( $column_name ) ) {
										$dt = new DateTime( "@$column_name" );
										$response['html'] .= $dt->format( 'Y-m-d' );
									} else {
										$response['html'] .= esc_html__( 'No Expiry', 'ultimate-woocommerce-points-and-rewards' );
									}
								}
							} else {
								$response['html'] .= $column_name;
							}
							$response['html'] .= '</td>';
						}
						$response['html'] .= '</tr>';
					}
						$response['html'] .= '</tbody>
					</table>';
					$response['result'] = true;
					$response['message'] = __( 'Your points are converted to coupon', 'ultimate-woocommerce-points-and-rewards' );
					$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
					$response['points'] = $get_points;
				}
			} else {
				$response['result'] = false;
				$response['message'] = __( 'Points cannot be greater than your current points', 'ultimate-woocommerce-points-and-rewards' );
			}
		}
		wp_send_json( $response );
	}

	/**
	 * This function is used to maintain coupon value of latest version of woocommerce.
	 *
	 * @name wps_wpr_woocommerce_order_add_coupon_woo_latest_version
	 * @since 1.0.0
	 * @param string $item_id id of the item meta.
	 * @param object $item    array of the items.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_order_add_coupon_woo_latest_version( $item_id, $item ) {
		if ( get_class( $item ) == 'WC_Order_Item_Coupon' ) {

			$mwp_wpr_coupon_code = $item->get_code();
			$the_coupon          = new WC_Coupon( $mwp_wpr_coupon_code );
			if ( isset( $the_coupon ) ) {

				$mwp_wpr_discount_amount     = $item->get_discount();
				$mwp_wpr_discount_amount_tax = $item->get_discount_tax();
				$mwp_wpr_coupon_id           = $the_coupon->get_id();
				$pointscoupon                = get_post_meta( $mwp_wpr_coupon_id, 'wps_wpr_points_coupon', true );

				if ( ! empty( $pointscoupon ) ) {

					$amount         = get_post_meta( $mwp_wpr_coupon_id, 'coupon_amount', true );
					$total_discount = $mwp_wpr_discount_amount + $mwp_wpr_discount_amount_tax;
					// WOOCS - WooCommerce Currency Switcher Compatibility.
					$total_discount = apply_filters( 'wps_wpr_convert_base_price_diffrent_currency', $total_discount );
					if ( $amount < $total_discount ) {
						$remaining_amount = 0;
					} else {
						$remaining_amount = $amount - $total_discount;
					}
					if ( ! empty( $amount ) ) {

						update_post_meta( $mwp_wpr_coupon_id, 'coupon_amount_before_use', $amount );
					}
					update_post_meta( $mwp_wpr_coupon_id, 'coupon_amount', $remaining_amount );
				}
			}
		}
	}

	/**
	 * This function is used to add the share points section in the myaccount page
	 *
	 * @name wps_wpr_share_points_section
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param int $user_id  user id of the customer.
	 */
	public function wps_wpr_share_points_section( $user_id ) {
		/*Create object of the public object*/
		$wps_wpr_user_can_send_point = 0;
		$other_settings = get_option( 'wps_wpr_other_settings', true );
		if ( ! empty( $other_settings['wps_wpr_user_can_send_point'] ) ) {
			$wps_wpr_user_can_send_point = (int) $other_settings['wps_wpr_user_can_send_point'];
		}
		if ( $wps_wpr_user_can_send_point ) {
			?>
			<p class="wps_wpr_heading"><?php echo esc_html__( 'Share Points', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
			<fieldset class="wps_wpr_each_section">
				<p id="wps_wpr_shared_points_notification"></p>
				<input type="email" style="width: 45%;" id="wps_wpr_enter_email" placeholder="<?php esc_html_e( 'Enter Email', 'ultimate-woocommerce-points-and-rewards' ); ?>">
				<input type="number" id="wps_wpr_enter_point" placeholder="<?php esc_html_e( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?>" style="width: 20%;">
				<input id="wps_wpr_share_point" data-id="<?php echo esc_html( $user_id ); ?>"type="button" name="wps_wpr_share_point" value="<?php esc_html_e( 'GO', 'ultimate-woocommerce-points-and-rewards' ); ?>">
			</fieldset>	
			<div id="wps_wpr_loader" style="display: none;">
				<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) ); ?>/images/loading.gif">
			</div>
			<?php
		}
	}

	/**
	 * The function is for share the points to other member for same site
	 *
	 * @name wps_wpr_sharing_point_to_other
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */// check.
	public function wps_wpr_sharing_point_to_other() {
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		$response['result'] = false;
		/*Create object of the public object*/
		$public_obj          = $this->generate_public_obj();
		$response['message'] = __( 'Error during Point Sharing. Try Again!', 'ultimate-woocommerce-points-and-rewards' );
		/*Get the points to be send*/
		if ( isset( $_POST['shared_point'] ) && ! empty( $_POST['shared_point'] ) && isset( $_POST['user_id'] ) && ! empty( $_POST['user_id'] ) && isset( $_POST['email_id'] ) ) {

			$wps_wpr_shared_point = (int) sanitize_text_field( wp_unslash( $_POST['shared_point'] ) );
			$user_id              = sanitize_text_field( wp_unslash( $_POST['user_id'] ) );
			/*Get the user id of the user*/
			$user          = get_user_by( 'ID', $user_id );
			$sender_email  = $user->user_email;
			$wps_wpr_email = sanitize_text_field( wp_unslash( $_POST['email_id'] ) );

			if ( isset( $user_id ) && ! empty( $user_id ) && isset( $wps_wpr_shared_point ) && ! empty( $wps_wpr_shared_point ) ) {
				/*Check isset email*/
				if ( isset( $wps_wpr_email ) && ! empty( $wps_wpr_email ) ) {

					/*get the providers points*/
					$providers_points    = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
					$wps_wpr_receiver    = get_user_by( 'email', $wps_wpr_email );
					$wps_wpr_receiver_id = $wps_wpr_receiver->data->ID;
					if ( isset( $wps_wpr_receiver ) && ! empty( $wps_wpr_receiver ) ) {
						if ( $providers_points >= $wps_wpr_shared_point ) {

							$receivers_points = (int) get_user_meta( $wps_wpr_receiver_id, 'wps_wpr_points', true );
							$receivers_updated_point = $receivers_points + $wps_wpr_shared_point;
							/*Update points logs*/
							$data = array(
								'type'    => 'received_by',
								'user_id' => $user_id,
							);
							$public_obj->wps_wpr_update_points_details( $wps_wpr_receiver_id, 'Receiver_point_details', $wps_wpr_shared_point, $data );
							/*Update user points*/
							update_user_meta( $wps_wpr_receiver_id, 'wps_wpr_points', $receivers_updated_point );
							$providers_updated_point = $providers_points - $wps_wpr_shared_point;
							/*Update points logs*/
							$data = array(
								'type'    => 'given_to',
								'user_id' => $wps_wpr_receiver_id,
							);
							$public_obj->wps_wpr_update_points_details( $user_id, 'Sender_point_details', $wps_wpr_shared_point, $data );
							/*Update the total points*/
							update_user_meta( $user_id, 'wps_wpr_points', $providers_updated_point );
							$available_points = get_user_meta( $user_id, 'wps_wpr_points', true );
							$wps_wpr_shortcode = array(
								'[Total Points]'  => $receivers_updated_point,
								'[USERNAME]'      => $wps_wpr_receiver->user_firstname,
								'[RECEIVEDPOINT]' => $wps_wpr_shared_point,
								'[SENDEREMAIL]'   => $sender_email,
							);
							$wps_wpr_subject_content = array(
								'wps_wpr_subject' => 'wps_wpr_point_sharing_subject',
								'wps_wpr_content' => 'wps_wpr_point_sharing_description',
							);
							/*Send mail to client regarding product purchase*/
							$public_obj->wps_wpr_send_notification_mail_product( $wps_wpr_receiver_id, $wps_wpr_shared_point, $wps_wpr_shortcode, $wps_wpr_subject_content );
							$response['result'] = true;
							$response['message'] = __( 'Points assigned successfully', 'ultimate-woocommerce-points-and-rewards' );
							$response['available_points'] = $available_points;

						} else {

							$response['result'] = false;
							$response['message'] = __( 'Entered Point should be less than your Total Point', 'ultimate-woocommerce-points-and-rewards' );
						}
					} else {

						$response['result'] = false;
						$response['message'] = __( 'Please Enter a Valid Email', 'ultimate-woocommerce-points-and-rewards' );
					}
				} else {

					$response['result'] = false;
					$response['message'] = __( 'Please Enter Email', 'ultimate-woocommerce-points-and-rewards' );
				}
			} else {

				$response['result'] = false;
				$response['message'] = __( 'Please fill Required fields', 'ultimate-woocommerce-points-and-rewards' );
			}
			echo json_encode( $response );
			wp_die();
		}
	}

	/**
	 * This function will add checkbox for purchase the products through points
	 *
	 * @name wps_wpr_woocommerce_before_add_to_cart_button
	 * @param object $product array of the product.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_before_add_to_cart_button( $product ) {
		// check allowed user for points features.
		if ( apply_filters( 'wps_wpr_allowed_user_roles_points_features', false ) ) {
			return;
		}

		global $product;
		$product_id = $product->get_id();
		$today_date = date_i18n( 'Y-m-d' );

		/*Create object of the public object*/
		$public_obj    = $this->generate_public_obj();
		$check_disbale = get_post_meta( $product_id, 'wps_product_purchase_through_point_disable', 'no' );
		if ( empty( $check_disbale ) ) {

			$check_disbale = 'no';
		}

		$_product            = wc_get_product( $product_id );
		$product_is_variable = $public_obj->wps_wpr_check_whether_product_is_variable( $_product );
		$price               = $_product->get_price();
		$user_ID             = get_current_user_ID();
		$get_points          = (int) get_user_meta( $user_ID, 'wps_wpr_points', true );
		$user_level          = get_user_meta( $user_ID, 'membership_level', true );
		$wps_wpr_mem_expr    = get_user_meta( $user_ID, 'membership_expiration', true );

		/*Get the settings of the purchase points*/
		$enable_purchase_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_points' );
		/*Check the restrction */
		$wps_wpr_restrict_pro_by_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_restrict_pro_by_points' );
		/*Product purchase product text*/
		$wps_wpr_purchase_product_text = $this->wps_wpr_get_product_purchase_settings( 'wps_wpr_purchase_product_text' );
		/*Check not product text should not be empty*/
		if ( empty( $wps_wpr_purchase_product_text ) ) {
			$wps_wpr_purchase_product_text = __( 'Use your Points for purchasing this Product', 'ultimate-woocommerce-points-and-rewards' );
		}

		$wps_wpr_purchase_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_purchase_points' );
		$wps_wpr_purchase_points = ( 0 == $wps_wpr_purchase_points ) ? 1 : $wps_wpr_purchase_points;
		$new_price               = 1;
		/*Get the price eqivalent to the purchase*/
		$wps_wpr_product_purchase_price = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_price' );
		$wps_wpr_product_purchase_price = ( 0 == $wps_wpr_product_purchase_price ) ? 1 : $wps_wpr_product_purchase_price;

		/*Get the membership role*/
		$membership_settings_array = get_option( 'wps_wpr_membership_settings', true );
		$wps_wpr_membership_roles  = isset( $membership_settings_array['membership_roles'] ) && ! empty( $membership_settings_array['membership_roles'] ) ? $membership_settings_array['membership_roles'] : array();
		$wps_wpr_categ_list        = $this->wps_wpr_get_product_purchase_settings( 'wps_wpr_restrictions_for_purchasing_cat' );
		if ( empty( $wps_wpr_categ_list ) ) {
			$wps_wpr_categ_list = array();
		}

		$wps_wpr_notification_color = $public_obj->wps_wpr_get_other_settings( 'wps_wpr_notification_color' );
		$wps_wpr_notification_color = ( ! empty( $wps_wpr_notification_color ) ) ? $wps_wpr_notification_color : '#55b3a5';
		if ( $enable_purchase_points && ! $product_is_variable ) {
			if ( ! $wps_wpr_restrict_pro_by_points && 'no' == $check_disbale ) {
				if ( isset( $user_level ) && ! empty( $user_level ) ) {

					if ( isset( $wps_wpr_mem_expr ) && ! empty( $wps_wpr_mem_expr ) && $today_date <= $wps_wpr_mem_expr ) {
						if ( ! empty( $wps_wpr_membership_roles[ $user_level ] ) && is_array( $wps_wpr_membership_roles[ $user_level ] ) ) {

							if ( is_array( $wps_wpr_membership_roles[ $user_level ]['Product'] ) && ! empty( $wps_wpr_membership_roles[ $user_level ]['Product'] ) ) {
								if ( in_array( $product_id, $wps_wpr_membership_roles[ $user_level ]['Product'] ) && ! $public_obj->check_exclude_sale_products( $_product ) ) {

									$new_price = $price - ( $price * $wps_wpr_membership_roles[ $user_level ]['Discount'] ) / 100;
								} else {
									$new_price = $_product->get_price();
								}
							} else {
								$terms = get_the_terms( $product_id, 'product_cat' );
								if ( is_array( $terms ) && ! empty( $terms ) ) {
									foreach ( $terms as $term ) {

										$cat_id     = $term->term_id;
										$parent_cat = $term->parent;
										if ( ( in_array( $cat_id, $wps_wpr_membership_roles[ $user_level ]['Prod_Categ'] ) || in_array( $parent_cat, $wps_wpr_membership_roles[ $user_level ]['Prod_Categ'] ) ) && ! $public_obj->check_exclude_sale_products( $_product ) ) {

											$new_price = $price - ( $price * $wps_wpr_membership_roles[ $user_level ]['Discount'] ) / 100;
											break;
										} else {
											$new_price = $_product->get_price();
										}
									}
								}
							}

							$round_down_setting = $this->wps_general_setting();
							if ( 'wps_wpr_round_down' == $round_down_setting ) {
								$points_calculation = floor( ( $new_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
							} else {
								$points_calculation = ceil( ( $new_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
							}
							if ( $points_calculation <= $get_points ) {
								?>
							<label for="wps_wpr_pro_cost_to_points">
									<input type="checkbox" name="wps_wpr_pro_cost_to_points" id="wps_wpr_pro_cost_to_points" class="" value="<?php echo esc_html( $points_calculation ); ?>"> <?php echo esc_html( $wps_wpr_purchase_product_text ); ?>
								</label>
								<input type="hidden" name="wps_wpr_hidden_points" class="wps_wpr_hidden_points" value="<?php echo esc_html( $points_calculation ); ?>">
								<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
								<?php
								esc_html_e( 'Spend ', 'ultimate-woocommerce-points-and-rewards' );
								echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $points_calculation ) . '</span>';
								esc_html_e( ' Points for Purchasing this Product for Single Quantity', 'ultimate-woocommerce-points-and-rewards' );
								?>
								</p>
								<span class="wps_wpr_notice"></span>
								<div class="wps_wpr_enter_some_points" style="display: none;">
									<input type="number" name="wps_wpr_some_custom_points" id="wps_wpr_some_custom_points" value="<?php echo esc_html( $points_calculation ); ?>">
									<input type="hidden" id="wps_variable_check_nonce" name="wps_variable_check_nonce" value="<?php echo esc_html( wp_create_nonce( 'meta-variable-nonce' ) ); ?>" />
								</div>
								<?php
							} else {
								$extra_need = $points_calculation - $get_points;
								?>

								<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
								<?php
								esc_html_e( 'You need extra ', 'ultimate-woocommerce-points-and-rewards' );
								echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $extra_need ) . '</span>';
								esc_html_e( ' Points for getting this product for free', 'ultimate-woocommerce-points-and-rewards' );
								?>
								</p>
								<?php
							}
						}
					} else {

						$round_down_setting = $this->wps_general_setting();
						if ( 'wps_wpr_round_down' == $round_down_setting ) {
							$points_calculation = floor( ( $price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
						} else {
							$points_calculation = ceil( ( $price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
						}

						if ( $points_calculation <= $get_points ) {
							?>
							<label for="wps_wpr_pro_cost_to_points">
								<input type="checkbox" name="wps_wpr_pro_cost_to_points" id="wps_wpr_pro_cost_to_points" class="input-text" value="<?php echo esc_html( $points_calculation ); ?>"> <?php echo esc_html( $wps_wpr_purchase_product_text ); ?>
							</label>
							<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
							<?php
							esc_html_e( 'Spend ', 'ultimate-woocommerce-points-and-rewards' );
							echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $points_calculation ) . '</span>';
							esc_html_e( ' Points for Purchasing this Product for Single Quantity', 'ultimate-woocommerce-points-and-rewards' );
							?>
							</p>
							<input type="hidden" name="wps_wpr_hidden_points" class="wps_wpr_hidden_points" value="<?php echo esc_html( $points_calculation ); ?>">
							<span class="wps_wpr_notice"></span>
							<div class="wps_wpr_enter_some_points" style="display: none;">
								<input type="number" name="wps_wpr_some_custom_points" id="wps_wpr_some_custom_points" value="<?php echo esc_html( $points_calculation ); ?>">
								<input type="hidden" id="wps_variable_check_nonce" name="wps_variable_check_nonce" value="<?php echo esc_html( wp_create_nonce( 'meta-variable-nonce' ) ); ?>" />
							</div>
							<?php
						} else {
							$extra_need = $points_calculation - $get_points;
							?>
							<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
							<?php
							esc_html_e( 'You need extra  ', 'ultimate-woocommerce-points-and-rewards' );
							echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $extra_need ) . '</span>';
							esc_html_e( ' Points for getting this product for free', 'ultimate-woocommerce-points-and-rewards' );
							?>
							</p>
							<?php
						}
					}
				} else {

					$round_down_setting = $this->wps_general_setting();
					if ( 'wps_wpr_round_down' == $round_down_setting ) {
						$points_calculation = floor( ( $price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
					} else {
						$points_calculation = ceil( ( $price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
					}

					if ( $points_calculation <= $get_points ) {

						?>
						<label for="wps_wpr_pro_cost_to_points">
							<input type="checkbox" name="wps_wpr_pro_cost_to_points" id="wps_wpr_pro_cost_to_points" class="input-text" value="<?php echo esc_html( $points_calculation ); ?>"> <?php echo esc_html( $wps_wpr_purchase_product_text ); ?>
						</label>
						<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
						<?php
						esc_html_e( 'Spend ', 'ultimate-woocommerce-points-and-rewards' );
						echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $points_calculation ) . '</span>';
						esc_html_e( ' Points for Purchasing this Product for Single Quantity', 'ultimate-woocommerce-points-and-rewards' );
						?>
						</p>
						<input type="hidden" name="wps_wpr_hidden_points" class="wps_wpr_hidden_points" value="<?php echo esc_html( $points_calculation ); ?>">
						<span class="wps_wpr_notice"></span>
						<div class="wps_wpr_enter_some_points" style="display: none;">
							<input type="number" name="wps_wpr_some_custom_points" id="wps_wpr_some_custom_points" value="<?php echo esc_html( $points_calculation ); ?>">
							<input type="hidden" id="wps_variable_check_nonce" name="wps_variable_check_nonce" value="<?php echo esc_html( wp_create_nonce( 'meta-variable-nonce' ) ); ?>" />
						</div>
						<?php
					} else {

						$extra_need = $points_calculation - $get_points;
						?>
						<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
						<?php
						esc_html_e( 'You need extra  ', 'ultimate-woocommerce-points-and-rewards' );
						echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $extra_need ) . '</span>';
						esc_html_e( ' Points for getting this product for free', 'ultimate-woocommerce-points-and-rewards' );
						?>
						</p>
						<?php
					}
				}
			} else {
				if ( 'no' == $check_disbale ) {

					$terms = get_the_terms( $product_id, 'product_cat' );
					if ( is_array( $terms ) && ! empty( $terms ) ) {
						foreach ( $terms as $term ) {

							$cat_id     = $term->term_id;
							$parent_cat = $term->parent;
							if ( in_array( $cat_id, $wps_wpr_categ_list ) || in_array( $parent_cat, $wps_wpr_categ_list ) ) {
								if ( isset( $user_level ) && ! empty( $user_level ) ) {

									if ( isset( $wps_wpr_mem_expr ) && ! empty( $wps_wpr_mem_expr ) && $today_date <= $wps_wpr_mem_expr ) {
										if ( ! empty( $wps_wpr_membership_roles[ $user_level ] ) && is_array( $wps_wpr_membership_roles[ $user_level ] ) ) {

											if ( is_array( $wps_wpr_membership_roles[ $user_level ]['Product'] ) && ! empty( $wps_wpr_membership_roles[ $user_level ]['Product'] ) ) {
												if ( in_array( $product_id, $wps_wpr_membership_roles[ $user_level ]['Product'] ) && ! $public_obj->check_exclude_sale_products( $_product ) ) {

													$new_price = $price - ( $price * $wps_wpr_membership_roles[ $user_level ]['Discount'] ) / 100;
												} else {
													$new_price = $_product->get_price();
												}
											} else {

												$terms = get_the_terms( $product_id, 'product_cat' );
												if ( is_array( $terms ) && ! empty( $terms ) ) {
													foreach ( $terms as $term ) {

														$cat_id     = $term->term_id;
														$parent_cat = $term->parent;
														if ( ( in_array( $cat_id, $wps_wpr_membership_roles[ $user_level ]['Prod_Categ'] ) || in_array( $parent_cat, $wps_wpr_membership_roles[ $user_level ]['Prod_Categ'] ) ) && ! $public_obj->check_exclude_sale_products( $_product ) ) {

															$new_price = $price - ( $price * $wps_wpr_membership_roles[ $user_level ]['Discount'] ) / 100;
															break;
														} else {
															$new_price = $_product->get_price();
														}
													}
												}
											}
											$round_down_setting = $this->wps_general_setting();
											if ( 'wps_wpr_round_down' == $round_down_setting ) {
												$points_calculation = floor( ( $new_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
											} else {
												$points_calculation = ceil( ( $new_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
											}

											if ( $points_calculation <= $get_points ) {

												?>
												<label for="wps_wpr_pro_cost_to_points">
													<input type="checkbox" name="wps_wpr_pro_cost_to_points" id="wps_wpr_pro_cost_to_points" class="input-text" value="<?php echo esc_html( $points_calculation ); ?>"> <?php echo esc_html( $wps_wpr_purchase_product_text ); ?>
												</label>
												<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
												<?php
												esc_html_e( 'Spend ', 'ultimate-woocommerce-points-and-rewards' );
												echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $points_calculation ) . '</span>';
												esc_html_e( ' Points for Purchasing this Product for Single Quantity', 'ultimate-woocommerce-points-and-rewards' );
												?>
												</p>
												<input type="hidden" name="wps_wpr_hidden_points" class="wps_wpr_hidden_points" value="<?php echo esc_html( $points_calculation ); ?>">
												<span class="wps_wpr_notice"></span>
												<div class="wps_wpr_enter_some_points" style="display: none;">
													<input type="number" name="wps_wpr_some_custom_points" id="wps_wpr_some_custom_points" value="<?php echo esc_html( $points_calculation ); ?>">
													<input type="hidden" id="wps_variable_check_nonce" name="wps_variable_check_nonce" value="<?php echo esc_html( wp_create_nonce( 'meta-variable-nonce' ) ); ?>" />
												</div>
												<?php
											} else {
												$extra_need = $points_calculation - $get_points;
												?>

												<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
												<?php
												esc_html_e( 'You need extra ', 'ultimate-woocommerce-points-and-rewards' );
												echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $extra_need ) . '</span>';
												esc_html_e( ' Points for getting this product for free', 'ultimate-woocommerce-points-and-rewards' );
												?>
												</p>
												<?php
											}
										}
									} else {
										$round_down_setting = $this->wps_general_setting();
										if ( 'wps_wpr_round_down' == $round_down_setting ) {
											$points_calculation = floor( ( $price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
										} else {

											$points_calculation = ceil( ( $price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
										}
										if ( $points_calculation <= $get_points ) {
											?>
											<label for="wps_wpr_pro_cost_to_points">
												<input type="checkbox" name="wps_wpr_pro_cost_to_points" id="wps_wpr_pro_cost_to_points" class="input-text" value="<?php echo esc_html( $points_calculation ); ?>"> <?php echo esc_html( $wps_wpr_purchase_product_text ); ?>
											</label>
											<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
											<?php
											esc_html_e( 'Spend ', 'ultimate-woocommerce-points-and-rewards' );
											echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $points_calculation ) . '</span>';
											esc_html_e( ' Points for Purchasing this Product for Single Quantity', 'ultimate-woocommerce-points-and-rewards' );
											?>
											</p>
											<input type="hidden" name="wps_wpr_hidden_points" class="wps_wpr_hidden_points" value="<?php echo esc_html( $points_calculation ); ?>">
											<span class="wps_wpr_notice"></span>
											<div class="wps_wpr_enter_some_points" style="display: none;">
												<input type="number" name="wps_wpr_some_custom_points" id="wps_wpr_some_custom_points" value="<?php echo esc_html( $points_calculation ); ?>">
												<input type="hidden" id="wps_variable_check_nonce" name="wps_variable_check_nonce" value="<?php echo esc_html( wp_create_nonce( 'meta-variable-nonce' ) ); ?>" />
											</div>
											<?php
										} else {
											$extra_need = $points_calculation - $get_points;
											?>
											<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
											<?php
											esc_html_e( 'You need extra ', 'ultimate-woocommerce-points-and-rewards' );
											echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $extra_need ) . '</span>';
											esc_html_e( ' Points for getting this product for free', 'ultimate-woocommerce-points-and-rewards' );
											?>
											</p>
											<?php
										}
									}
								} else {
									$round_down_setting = $this->wps_general_setting();
									if ( 'wps_wpr_round_down' == $round_down_setting ) {
										$points_calculation = floor( ( $price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
									} else {
										$points_calculation = ceil( ( $price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
									}

									if ( $points_calculation <= $get_points ) {
										?>
										<label for="wps_wpr_pro_cost_to_points">
											<input type="checkbox" name="wps_wpr_pro_cost_to_points" id="wps_wpr_pro_cost_to_points" class="input-text" value="<?php echo esc_html( $points_calculation ); ?>"> <?php echo esc_html( $wps_wpr_purchase_product_text ); ?>
										</label>
										<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
										<?php
										esc_html_e( 'Spend ', 'ultimate-woocommerce-points-and-rewards' );
										echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $points_calculation ) . '</span>';
										esc_html_e( ' Points for Purchasing this Product for Single Quantity', 'ultimate-woocommerce-points-and-rewards' );
										?>
										</p>
										<input type="hidden" name="wps_wpr_hidden_points" class="wps_wpr_hidden_points" value="<?php echo esc_html( $points_calculation ); ?>">
										<span class="wps_wpr_notice"></span>
										<div class="wps_wpr_enter_some_points" style="display: none;">
											<input type="number" name="wps_wpr_some_custom_points" id="wps_wpr_some_custom_points" value="<?php echo esc_html( $points_calculation ); ?>">
											<input type="hidden" id="wps_variable_check_nonce" name="wps_variable_check_nonce" value="<?php echo esc_html( wp_create_nonce( 'meta-variable-nonce' ) ); ?>" />
										</div>
										<?php
									} else {
										$extra_need = $points_calculation - $get_points;
										?>
										<p class="wps_wpr_purchase_pro_point" style="background:<?php echo esc_html( $wps_wpr_notification_color ); ?>;">
										<?php
										esc_html_e( 'You need extra ', 'ultimate-woocommerce-points-and-rewards' );
										echo '<span class=wps_wpr_when_variable_pro>' . esc_html( $extra_need ) . '</span>';
										esc_html_e( ' Points for getting this product for free', 'ultimate-woocommerce-points-and-rewards' );
										?>
										</p>
										<?php
									}
								}
								break;
							}
						}
					}
				}
			}
		} elseif ( $enable_purchase_points && $product_is_variable ) {

			$wps_wpr_nonce = wp_create_nonce( 'meta-variable-nonce' );
			echo '<input type="hidden" id="wps_variable_check_nonce" name="wps_variable_check_nonce" value="' . esc_html( $wps_wpr_nonce ) . '" />';
			echo '<div class="wps_wpr_variable_pro_pur_using_point" style="display: none;"></div>';
		}
	}

	/**
	 * This function is used to save points in add to cart session0.
	 *
	 * @name wps_wpr_woocommerce_add_cart_item_data_pro
	 * @param array $the_cart_data  array of the cart data.
	 * @param int   $product_id  id of the product.
	 * @param int   $variation_id  id of the variation.
	 * @param int   $quantity  quantity of the product.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_add_cart_item_data_pro( $the_cart_data, $product_id, $variation_id, $quantity ) {
		$public_obj             = $this->generate_public_obj();
		$enable_purchase_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_points' );
		/*Get the current user Id*/
		$user_id = get_current_user_ID();
		/*Get the total points of the user*/
		$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
		WC()->session->set( 'wps_wpr_purchase_via_points', $get_points );
		if ( $enable_purchase_points ) {

			$nonce_value = ! empty( $_POST['wps_variable_check_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_variable_check_nonce'] ) ) : '';
			if ( wp_verify_nonce( $nonce_value, 'meta-variable-nonce' ) ) {
				if ( isset( $_POST['wps_wpr_pro_cost_to_points'] ) && ! empty( $_POST['wps_wpr_pro_cost_to_points'] ) ) {

					$wps_points_cart = ! empty( $_POST['wps_wpr_some_custom_points'] ) ? sanitize_text_field( wp_unslash( $_POST['wps_wpr_some_custom_points'] ) ) : '';
					if ( $wps_points_cart > $get_points ) {

						$item_meta_points = $get_points;
					} else {
						$item_meta_points = $wps_points_cart;
					}
					$the_cart_data ['product_meta']['meta_data']['pro_purchase_by_points'] = $item_meta_points;
				}
			}
		}
		// WPS Custom Work.
		$_product                       = wc_get_product( $product_id );
		$enable_product_purchase_points = get_post_meta( $product_id, 'wps_product_purchase_points_only', true );
		$wps_product_purchase_value     = get_post_meta( $product_id, 'wps_points_product_purchase_value', true );
		$prod_type                      = $_product->get_type();
		if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {
			if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) && ( 'simple' == $prod_type ) ) {
				if ( $wps_product_purchase_value <= $get_points ) {

					$the_cart_data ['product_meta']['meta_data']['wps_wpr_purchase_point_only'] = $wps_product_purchase_value * (int) $quantity;
				}
			}
		}

		/*Custom Work for Variable Product*/
		if ( $public_obj->wps_wpr_check_whether_product_is_variable( $_product ) ) {
			/*Get the parent id of the post*/
			$wps_wpr_parent_id              = wp_get_post_parent_id( $variation_id );
			$enable_product_purchase_points = get_post_meta( $wps_wpr_parent_id, 'wps_product_purchase_points_only', true );
			$wps_product_purchase_value     = get_post_meta( $variation_id, 'wps_wpr_variable_points_purchase', true );
			if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {
				if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) ) {
					if ( is_user_logged_in() ) {
						if ( $wps_product_purchase_value <= $get_points ) {

							$the_cart_data ['product_meta']['meta_data']['wps_wpr_purchase_point_only'] = $wps_product_purchase_value * (int) $quantity;
						}
					}
				}
			}
		}
		/*End of Custom Work*/
		return $the_cart_data;
	}

	/**
	 * This function is used to show item poits in time of order .
	 *
	 * @name wps_wpr_woocommerce_get_item_data
	 * @param array $item_meta meta information of the product.
	 * @param array $existing_item_meta  exiting item meta of the product.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_get_item_data_pro( $item_meta, $existing_item_meta ) {
		if ( isset( $existing_item_meta ['product_meta']['meta_data'] ) ) {
			if ( $existing_item_meta ['product_meta']['meta_data'] ) {
				foreach ( $existing_item_meta['product_meta'] ['meta_data'] as $key => $val ) {
					if ( 'wps_wpr_purchase_point_only' == $key ) {

						$item_meta [] = array(
							'name'  => esc_html__( 'Purchased By Points', 'ultimate-woocommerce-points-and-rewards' ),
							'value' => stripslashes( $val ),
						);
					}
				}
			}
		}
		return $item_meta;
	}

	/**
	 * The function is convert the points and add this to in the ofrm of Fee(add_fee)
	 *
	 * @name wps_wpr_woocommerce_cart_calculate_fees_pro
	 * @param object $cart array of the cart.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_cart_calculate_fees_pro( $cart ) {
		/*Get the enable setting value*/
		$enable_purchase_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_points' );
		/*Get the purchase points of the product*/
		$wps_wpr_purchase_points  = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_purchase_points' );
		$wps_wpr_purchase_points  = ( 0 == $wps_wpr_purchase_points ) ? 1 : $wps_wpr_purchase_points;
		$wps_wpr_discount_bcz_pnt = 0;
		$wps_wpr_pnt_fee_added    = false;
		$points_calculation       = 0;
		/*Get the price eqivalent to the purchase*/
		$wps_wpr_product_purchase_price = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_price' );
		$wps_wpr_product_purchase_price = ( 0 == $wps_wpr_product_purchase_price ) ? 1 : $wps_wpr_product_purchase_price;
		$user_id                        = get_current_user_ID();
		$get_points                     = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
		if ( ! empty( $cart ) ) {
			foreach ( $cart->cart_contents as $key => $value ) {
				if ( ! empty( $value ) ) {

					$product_id = $value['product_id'];
					$pro_quant  = $value['quantity'];
					$_product   = wc_get_product( $product_id );
					/*check is product purchase is enable or not*/
					if ( $enable_purchase_points ) {
						if ( isset( $value['product_meta']['meta_data']['pro_purchase_by_points'] ) && ! empty( $value['product_meta']['meta_data']['pro_purchase_by_points'] ) ) {

							$original_price     = $_product->get_price();
							$original_price     = $pro_quant * $original_price;
							$round_down_setting = $this->wps_general_setting();
							if ( 'wps_wpr_round_down' == $round_down_setting ) {
								$points_calculation += floor( ( $original_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
							} else {
								$points_calculation += ceil( ( $original_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
							}

							$wps_wpr_about_to_pay = ( $value['product_meta']['meta_data']['pro_purchase_by_points'] / $wps_wpr_purchase_points * $wps_wpr_product_purchase_price );
							$wps_wpr_discount_bcz_pnt = $wps_wpr_discount_bcz_pnt + $wps_wpr_about_to_pay;
							$wps_wpr_pnt_fee_added = true;
						}
					}
				}
			}

			if ( $get_points > 0 && $wps_wpr_pnt_fee_added ) {
				$convert_in_point = ( $wps_wpr_discount_bcz_pnt * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price;
				$points_discount = __( 'Point Discount', 'ultimate-woocommerce-points-and-rewards' );
				if ( $convert_in_point > $get_points ) {
					if ( ! empty( WC()->session->get( 'wps_wpr_purchase_via_points' ) ) ) {

						$wps_wpr_about_to_pay = (int) ( $get_points / $wps_wpr_purchase_points * $wps_wpr_product_purchase_price );
						$cart->add_fee( $points_discount, -$wps_wpr_about_to_pay, true, '' );
					}
				} else {
					if ( ! empty( WC()->session->get( 'wps_wpr_purchase_via_points' ) ) ) {
						$cart->add_fee( $points_discount, -$wps_wpr_discount_bcz_pnt, true, '' );
					}
				}
			}
		}
	}

	/**
	 * This function used to update points of the purchased products.
	 *
	 * @name wps_update_cart_points_pro
	 * @param array $cart_updated  array of the updated cart.
	 * @return array
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_update_cart_points_pro( $cart_updated ) {
		$nonce_value = ! empty( $_REQUEST['woocommerce-cart-nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['woocommerce-cart-nonce'] ) ) : '';
		if ( $cart_updated && wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) ) {

			$public_obj = $this->generate_public_obj();
			$cart       = WC()->session->get( 'cart' );
			$user_id    = get_current_user_ID();
			$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );

			if ( isset( $_POST['cart'] ) && null != $_POST['cart'] && isset( $cart ) && null != $cart ) {
				$cart_update = map_deep( wp_unslash( $_POST['cart'] ), 'sanitize_text_field' );

				foreach ( $cart_update as $key => $value ) {
					if ( isset( WC()->cart->cart_contents[ $key ]['product_meta'] ) ) {
						if ( isset( WC()->cart->cart_contents[ $key ]['product_meta']['meta_data']['wps_wpr_purchase_point_only'] ) ) {

							$product = wc_get_product( $cart[ $key ]['product_id'] );
							if ( $public_obj->wps_wpr_check_whether_product_is_variable( $product ) ) {
								if ( isset( $cart[ $key ]['variation_id'] ) && ! empty( $cart[ $key ]['variation_id'] ) ) {

									$wps_variable_purchase_value = get_post_meta( $cart[ $key ]['variation_id'], 'wps_wpr_variable_points_purchase', true );
								}
								$total_pro_pnt = (int) $wps_variable_purchase_value * (int) $value['qty'];

								if ( isset( $total_pro_pnt ) && ! empty( $total_pro_pnt ) && $get_points >= $total_pro_pnt ) {
									WC()->cart->cart_contents[ $key ]['product_meta']['meta_data']['wps_wpr_purchase_point_only'] = $total_pro_pnt;
								} else {

									wc_add_notice( __( 'You cant purchase that much quantity for Free', 'ultimate-woocommerce-points-and-rewards' ), 'error' );
									WC()->cart->cart_contents[ $key ]['product_meta']['meta_data']['wps_wpr_purchase_point_only'] = 0;
								}
							} else {

								if ( isset( $cart[ $key ]['product_id'] ) && ! empty( $cart[ $key ]['product_id'] ) ) {
									$get_product_points = get_post_meta( $cart[ $key ]['product_id'], 'wps_points_product_purchase_value', true );
								}
								$total_pro_pnt = (int) $get_product_points * (int) $value['qty'];
								if ( isset( $total_pro_pnt ) && ! empty( $total_pro_pnt ) && $get_points >= $total_pro_pnt ) {
									WC()->cart->cart_contents[ $key ]['product_meta']['meta_data']['wps_wpr_purchase_point_only'] = (int) $get_product_points * (int) $value['qty'];
								} else {

									wc_add_notice( __( 'You cant purchase that much quantity for Free', 'ultimate-woocommerce-points-and-rewards' ), 'error' );
									WC()->cart->cart_contents[ $key ]['product_meta']['meta_data']['wps_wpr_purchase_point_only'] = 0;
								}
							}
						}
					}
				}
			}
		}
		return $cart_updated;
	}

	/**
	 * This function will add discounted price for selected products in any  Membership Level.
	 *
	 * @name wps_wpr_user_level_discount_on_price
	 * @param int   $price price of the product.
	 * @param object $product_data  data of the product.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_user_level_discount_on_price_pro( $price, $product_data ) {

		$product_id                     = $product_data->get_id();
		$_product                       = wc_get_product( $product_id );
		$reg_price                      = $_product->get_price();
		$prod_type                      = $_product->get_type();
		$enable_product_purchase_points = get_post_meta( $product_id, 'wps_product_purchase_points_only', true );
		$wps_product_purchase_value     = get_post_meta( $product_id, 'wps_points_product_purchase_value', true );
		if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {
			if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) && ( 'simple' == $prod_type ) ) {

				$wps_points = __( 'Points', 'ultimate-woocommerce-points-and-rewards' );
				$price      = $wps_product_purchase_value . ' ' . $wps_points;
			}
		}
		return $price;
	}

	/**
	 * This function will add discounted price in cart page.
	 *
	 * @name wps_wpr_woocommerce_before_calculate_totals
	 * @param object $cart  array of the cart.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_before_calculate_totals_pro( $cart ) {
		/*Get the settings of the purchase points*/
		$public_obj = $this->generate_public_obj();
		/*Product purchase product text*/
		$wps_wpr_purchase_product_text = $this->wps_wpr_get_product_purchase_settings( 'wps_wpr_purchase_product_text' );
		/*Check not product text should not be empty*/
		if ( empty( $wps_wpr_purchase_product_text ) ) {
			$wps_wpr_purchase_product_text = __( 'Use your Points for purchasing this Product', 'ultimate-woocommerce-points-and-rewards' );
		}
		$wps_wpr_purchase_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_purchase_points' );
		$wps_wpr_purchase_points = ( 0 == $wps_wpr_purchase_points ) ? 1 : $wps_wpr_purchase_points;
		/*Get the price eqivalent to the purchase*/
		$wps_wpr_product_purchase_price = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_price' );
		$wps_wpr_product_purchase_price = ( 0 == $wps_wpr_product_purchase_price ) ? 1 : $wps_wpr_product_purchase_price;
		$user                           = wp_get_current_user();
		$user_id                        = $user->ID;
		$get_points                     = (int) get_user_meta( $user_id, 'wps_wpr_points', true );

		if ( isset( $cart ) ) {
			foreach ( $cart->cart_contents as $key => $value ) {
				if ( isset( $value ) ) {

					$product_id = $value['product_id'];
					$pro_quant  = $value['quantity'];
					$_product   = wc_get_product( $product_id );
					// ===== Custom work==========
					$enable_product_purchase_points = get_post_meta( $product_id, 'wps_product_purchase_points_only', true );
					$wps_product_purchase_value     = get_post_meta( $product_id, 'wps_points_product_purchase_value', true );
					$product_type                   = $_product->get_type();

					if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {
						if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) && ( 'simple' == $product_type ) ) {
							if ( $wps_product_purchase_value < $get_points ) {

								$cart->cart_contents[ $key ]['product_meta']['meta_data']['wps_wpr_purchase_point_only'] = $wps_product_purchase_value * (int) $pro_quant;
							}
						}
					}

					if ( $public_obj->wps_wpr_check_whether_product_is_variable( $_product ) ) {
						$wps_wpr_parent_id              = wp_get_post_parent_id( $value['variation_id'] );
						$enable_product_purchase_points = get_post_meta( $wps_wpr_parent_id, 'wps_product_purchase_points_only', true );
						$wps_product_purchase_value     = get_post_meta( $value['variation_id'], 'wps_wpr_variable_points_purchase', true );

						if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {
							if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) ) {
								if ( is_user_logged_in() ) {
									if ( $wps_product_purchase_value < $get_points ) {

										$cart->cart_contents[ $key ]['product_meta']['meta_data']['wps_wpr_purchase_point_only'] = $wps_product_purchase_value * (int) $pro_quant;
									}
								}
							}
						}
					}
					/*Product purchase through point only*/
					if ( isset( $enable_product_purchase_points )
						&& 'yes' == $enable_product_purchase_points
						&& ! empty( $enable_product_purchase_points ) ) {
						if ( isset( $wps_product_purchase_value )
							&& ! empty( $wps_product_purchase_value )
							&& ( 'simple' == $product_type ) ) {
							if ( is_user_logged_in() ) {
								if ( ( $wps_product_purchase_value * $pro_quant ) <= $get_points ) {
									$value['data']->set_price( 0 );
								}
							}
						}
					}
					/*Variable product purchase through points*/
					if ( $public_obj->wps_wpr_check_whether_product_is_variable( $_product ) ) {
						$variation_id                   = $value['variation_id'];
						$wps_wpr_parent_id              = wp_get_post_parent_id( $variation_id );
						$enable_product_purchase_points = get_post_meta( $wps_wpr_parent_id, 'wps_product_purchase_points_only', true );
						$wps_product_purchase_value     = get_post_meta( $variation_id, 'wps_wpr_variable_points_purchase', true );
						if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {
							if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) ) {
								if ( is_user_logged_in() ) {
									if ( ( $wps_product_purchase_value * $pro_quant ) <= $get_points ) {
										$value['data']->set_price( 0 );
									}
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * This function is used to save item points in time of order.
	 *
	 * @name wps_wpr_woocommerce_add_order_item_meta_version_3
	 * @param object $item array of the items.
	 * @param string $cart_key key of the cart.
	 * @param array  $values values of the cart.
	 * @param array  $order array of the order.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_add_order_item_meta_version_3( $item, $cart_key, $values, $order ) {

		if ( isset( $values['product_meta'] ) ) {
			foreach ( $values['product_meta'] ['meta_data'] as $key => $val ) {
				$order_val = stripslashes( $val );
				if ( $val ) {
					if ( 'pro_purchase_by_points' == $key ) {
						$item->add_meta_data( 'Purchasing Option', $order_val );
					}
					if ( 'wps_wpr_purchase_point_only' == $key ) {
						$item->add_meta_data( 'Purchased By Points', $order_val );
					}
				}
			}
		}
	}

	/**
	 * The function is for let the meta keys translatable
	 *
	 * @name wps_wpr_woocommerce_order_item_display_meta_key
	 * @param string $display_key display key of order.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_order_item_display_meta_key_pro( $display_key ) {
		if ( 'Purchasing Option' == $display_key ) {
			$display_key = __( 'Purchasing Option', 'ultimate-woocommerce-points-and-rewards' );
		}
		return $display_key;
	}

	/**
	 * This function will update the user points as they purchased products through points
	 *
	 * @name wps_wpr_woocommerce_checkout_update_order_meta_pro.
	 * @param int   $order_id id of the order.
	 * @param array $data array of the order.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_checkout_update_order_meta_pro( $order_id, $data ) {
		/*Get the settings of the purchase points*/
		$public_obj                 = $this->generate_public_obj();
		$user_id                    = get_current_user_id();
		$user                       = get_user_by( 'ID', $user_id );
		$user_email                 = $user->user_email;
		$deduct_point               = '';
		$points_deduct              = 0;
		$wps_wpr_is_pnt_fee_applied = false;
		/*Get the purchase points*/
		$wps_wpr_purchase_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_purchase_points' );
		$wps_wpr_purchase_points = ( 0 == $wps_wpr_purchase_points ) ? 1 : $wps_wpr_purchase_points;
		/*Get the price eqivalent to the purchase*/
		$wps_wpr_product_purchase_price = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_price' );
		$wps_wpr_product_purchase_price = ( 0 == $wps_wpr_product_purchase_price ) ? 1 : $wps_wpr_product_purchase_price;

		/*Get the order from the order id */
		$get_points     = get_user_meta( $user_id, 'wps_wpr_points', true );
		$order          = wc_get_order( $order_id );
		$line_items_fee = $order->get_items( 'fee' );
		if ( ! empty( $line_items_fee ) ) {
			foreach ( $line_items_fee as $item_id => $item ) {

				$points_discount = __( 'Point Discount', 'ultimate-woocommerce-points-and-rewards' );
				$wps_wpr_fee_name = $item->get_name();
				if ( $points_discount == $wps_wpr_fee_name ) {
					$wps_wpr_is_pnt_fee_applied = true;
					$fee_amount                 = $item->get_amount();
				}
			}
		}

		if ( $wps_wpr_is_pnt_fee_applied ) {
			$fee_amount = -( $fee_amount );
			// WOOCS - WooCommerce Currency Switcher Compatibility.
			$fee_amount = apply_filters( 'wps_wpr_convert_base_price_diffrent_currency', $fee_amount );
			$round_down_setting = $this->wps_general_setting();
			if ( 'wps_wpr_round_down' == $round_down_setting ) {

				$fee_to_point = floor( ( $wps_wpr_purchase_points * $fee_amount ) / $wps_wpr_product_purchase_price );
			} else {

				$fee_to_point = ceil( ( $wps_wpr_purchase_points * $fee_amount ) / $wps_wpr_product_purchase_price );
			}

			$points_deduct = $fee_to_point;
			$total_points  = $get_points - $points_deduct;
			$data          = array();
			/*update points detais of the customer*/
			$this->wps_wpr_update_points_details_pro( $user_id, 'pur_by_points', $points_deduct, $data );
			/*update users points*/
			update_user_meta( $user_id, 'wps_wpr_points', $total_points );
			$wps_wpr_shortcode = array(
				'[Total Points]' => $total_points,
				'[USERNAME]' => $user->user_firstname,
				'[PROPURPOINTS]' => $points_deduct,
			);
			$wps_wpr_subject_content = array(
				'wps_wpr_subject' => 'wps_wpr_pro_pur_by_points_email_subject',
				'wps_wpr_content' => 'wps_wpr_pro_pur_by_points_discription_custom_id',
			);
			/*Send mail to client regarding product purchase through points*/
			$public_obj->wps_wpr_send_notification_mail_product( $user_id, $points_deduct, $wps_wpr_shortcode, $wps_wpr_subject_content );
		}
		$product_points = 0;
		$product_purchased_pnt_only = false;
		foreach ( $order->get_items() as $item_id => $item ) {
			$wps_wpr_items = $item->get_meta_data();
			foreach ( $wps_wpr_items as $key => $wps_wpr_value ) {
				if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Purchased By Points' == $wps_wpr_value->key ) ) {
					$product_points += (int) $wps_wpr_value->value;
					$product_purchased_pnt_only = true;
				}
			}
		}

		$get_points = get_user_meta( $user_id, 'wps_wpr_points', true );
		/*Product purchase through points*/
		if ( $product_purchased_pnt_only && isset( $user_id ) && $user_id > 0 ) {
			if ( $get_points >= $product_points ) {

				$total_points_only = $get_points - $product_points;
				/*Update user points*/
				update_user_meta( $user_id, 'wps_wpr_points', $total_points_only );
				$data = array();
				/*update points detais of the customer*/
				$this->wps_wpr_update_points_details_pro( $user_id, 'pur_pro_pnt_only', $product_points, $data );
				$wps_wpr_shortcode = array(
					'[Total Points]' => $total_points_only,
					'[USERNAME]' => $user->user_firstname,
					'[PROPURPOINTS]' => $product_points,
				);
				$wps_wpr_subject_content = array(
					'wps_wpr_subject' => 'wps_wpr_pro_pur_by_points_email_subject',
					'wps_wpr_content' => 'wps_wpr_pro_pur_by_points_discription_custom_id',
				);
				/*Send mail to client regarding product purchase through points*/
				$public_obj->wps_wpr_send_notification_mail_product( $user_id, $product_points, $wps_wpr_shortcode, $wps_wpr_subject_content );
			}
		}
	}

	/**
	 * Update points details in the public section
	 *
	 * @name wps_wpr_update_points_details
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param int    $user_id user id of the customer.
	 * @param string $type type of the order.
	 * @param int    $points points in the order.
	 * @param array  $data  data of the order.
	 */
	public function wps_wpr_update_points_details_pro( $user_id, $type, $points, $data ) {
		$today_date = date_i18n( 'Y-m-d h:i:sa' );

		/*Here is cart discount through the points*/
		if ( 'cart_subtotal_point' == $type || 'pur_by_points' == $type ) {
			$cart_subtotal_point_arr = get_user_meta( $user_id, 'points_details', true );
			if ( isset( $cart_subtotal_point_arr[ $type ] ) && ! empty( $cart_subtotal_point_arr[ $type ] ) ) {
				$cart_array = array(
					$type  => $points,
					'date' => $today_date,
				);
				$cart_subtotal_point_arr[ $type ][] = $cart_array;
			} else {
				if ( ! is_array( $cart_subtotal_point_arr ) ) {
					$cart_subtotal_point_arr = array();
				}
				$cart_array = array(
					$type => $points,
					'date' => $today_date,
				);
				$cart_subtotal_point_arr[ $type ][] = $cart_array;
			}
			/*Update the user meta for the points details*/
			update_user_meta( $user_id, 'points_details', $cart_subtotal_point_arr );
		}

		if ( 'Receiver_point_details' == $type || 'Sender_point_details' == $type ) {
			$wps_points_sharing = get_user_meta( $user_id, 'points_details', true );
			if ( isset( $wps_points_sharing[ $type ] ) && ! empty( $wps_points_sharing[ $type ] ) ) {

				$custom_array = array(
					$type => $points,
					'date' => $today_date,
					$data['type'] => $data['user_id'],
				);
				$wps_points_sharing[ $type ][] = $custom_array;
			} else {
				if ( ! is_array( $wps_points_sharing ) ) {
					$wps_points_sharing = array();
				}
				$wps_points_sharing[ $type ][] = array(
					$type => $points,
					'date' => $today_date,
					$data['type'] => $data['user_id'],
				);
			}
			/*Update the user meta for the points details*/
			update_user_meta( $user_id, 'points_details', $wps_points_sharing );
		}
		if ( 'pur_pro_pnt_only' == $type ) {
			$pur_pro_pnt_only = get_user_meta( $user_id, 'points_details', true );
			if ( isset( $pur_pro_pnt_only[ $type ] ) && ! empty( $pur_pro_pnt_only[ $type ] ) ) {

				$point_only_array = array(
					'pur_pro_pnt_only' => $points,
					'date' => $today_date,
				);
				$pur_pro_pnt_only['pur_pro_pnt_only'][] = $point_only_array;
			} else {
				if ( ! is_array( $pur_pro_pnt_only ) ) {
					$pur_pro_pnt_only = array();
				}
				$point_only_array = array(
					'pur_pro_pnt_only' => $points,
					'date' => $today_date,
				);
				$pur_pro_pnt_only['pur_pro_pnt_only'][] = $point_only_array;
			}
			update_user_meta( $user_id, 'points_details', $pur_pro_pnt_only );
		}
		return 'Successfully';
	}

	/**
	 * Runs a cron for notifying the users who have any memberhip level and which is going to be expired in next two weeks.
	 *
	 * @name wps_wpr_do_this_hourly.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_do_this_hourly() {
		$today_date = date_i18n( 'Y-m-d' );
		$args['meta_query'] = array(
			array(
				'key' => 'membership_level',
			),
		);

		$user_data = get_users( $args );
		if ( is_array( $user_data ) && ! empty( $user_data ) ) {
			foreach ( $user_data as $key => $value ) {
				$user_id = $value->data->ID;
				if ( isset( $user_id ) && ! empty( $user_id ) ) {

					$wps_wpr_mem_expr = get_user_meta( $user_id, 'membership_expiration', true );
					$user_level       = get_user_meta( $user_id, 'membership_level', true );
					if ( isset( $wps_wpr_mem_expr ) && ! empty( $wps_wpr_mem_expr ) ) {
						$notification_date = gmdate( 'Y-m-d', strtotime( $wps_wpr_mem_expr . ' -1 weeks' ) );
						if ( $today_date == $notification_date ) {

							$subject = __( 'Membership Expiration Alert!', 'ultimate-woocommerce-points-and-rewards' );
							$message = __( 'Your User Level ', 'ultimate-woocommerce-points-and-rewards' ) . $user_level . __( ' is going to expire on the date of ', 'ultimate-woocommerce-points-and-rewards' ) . $wps_wpr_mem_expr . __( ' You can upgrade your level or can renew that level again after expiration.', 'ultimate-woocommerce-points-and-rewards' );

							$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
							$email_status   = $customer_email->trigger( $user_id, $message, $subject );
						}
						$expired_date = gmdate( 'Y-m-d', strtotime( $wps_wpr_mem_expr ) );
						if ( $today_date > $expired_date ) {

							delete_user_meta( $user_id, 'membership_level' );
							$subject = __( 'No Longer Membership User', 'ultimate-woocommerce-points-and-rewards' );
							$message = __( 'Your User Level ', 'ultimate-woocommerce-points-and-rewards' ) . $user_level . __( ' has expired. You can upgrade your level to another or can renew that level again ', 'ultimate-woocommerce-points-and-rewards' );

							$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
							$email_status = $customer_email->trigger( $user_id, $message, $subject );
						}
					}
				}
			}
		}
	}

	/**
	 * The function is used for append the variable point to the single product page as well as variable product support for purchased through points and for membership product
	 *
	 * @name wps_wpr_append_variable_point
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_append_variable_point() {
		$public_obj                                = $this->generate_public_obj();
		$wps_wpr_notification_color                = $public_obj->wps_wpr_get_other_settings( 'wps_wpr_notification_color' );
		$wps_wpr_notification_color                = ( ! empty( $wps_wpr_notification_color ) ) ? $wps_wpr_notification_color : '#55b3a5';
		$response['result']                        = false;
		$response['message']                       = __( 'Error during various variation handling. Try Again!', 'ultimate-woocommerce-points-and-rewards' );
		$wps_wpr_proceed_for_purchase_throgh_point = false;
		$points_calculation                        = '';
		$price                                     = '';
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		$public_obj = $this->generate_public_obj();

		if ( isset( $_POST['variation_id'] ) && ! empty( $_POST['variation_id'] ) ) {
			$variation_id = sanitize_text_field( wp_unslash( $_POST['variation_id'] ) );
		}

		// Get the resctiction settings.
		// Check the restrction.
		$wps_wpr_restrict_pro_by_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_restrict_pro_by_points' );
		$wps_wpr_categ_list = $this->wps_wpr_get_product_purchase_settings( 'wps_wpr_restrictions_for_purchasing_cat' );
		if ( empty( $wps_wpr_categ_list ) ) {
			$wps_wpr_categ_list = array();
		}
		$user_id = get_current_user_ID();
		if ( ! empty( $variation_id ) ) {

			$user_level                = get_user_meta( $user_id, 'membership_level', true );
			$get_points                = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
			$wps_wpr_mem_expr          = get_user_meta( $user_id, 'membership_expiration', true );
			$membership_settings_array = get_option( 'wps_wpr_membership_settings', true );
			$wps_wpr_membership_roles  = isset( $membership_settings_array['membership_roles'] ) && ! empty( $membership_settings_array['membership_roles'] ) ? $membership_settings_array['membership_roles'] : array();
			$today_date                = date_i18n( 'Y-m-d' );
			/*Product purchase product text*/
			$wps_wpr_purchase_product_text = $this->wps_wpr_get_product_purchase_settings( 'wps_wpr_purchase_product_text' );
			/*Check not product text should not be empty*/
			if ( empty( $wps_wpr_purchase_product_text ) ) {
				$wps_wpr_purchase_product_text = __( 'Use your Points for purchasing this Product', 'ultimate-woocommerce-points-and-rewards' );
			}
			$wps_wpr_parent_id = wp_get_post_parent_id( $variation_id );

			/*Get the settings of the purchase points*/
			$enable_purchase_points         = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_points' );
			$wps_wpr_purchase_points        = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_purchase_points' );
			$wps_wpr_purchase_points        = ( 0 == $wps_wpr_purchase_points ) ? 1 : $wps_wpr_purchase_points;
			$new_price                      = 1;
			$wps_wpr_product_purchase_price = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_price' );
			$wps_wpr_product_purchase_price = ( 0 == $wps_wpr_product_purchase_price ) ? 1 : $wps_wpr_product_purchase_price;
			$wps_wpr_restrict_pro_by_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_restrict_pro_by_points' );

			if ( ! empty( $wps_wpr_parent_id ) && $wps_wpr_parent_id > 0 ) {

				$check_enable = get_post_meta( $wps_wpr_parent_id, 'wps_product_points_enable', 'no' );
				$check_disbale = get_post_meta( $wps_wpr_parent_id, 'wps_product_purchase_through_point_disable', 'no' );
				if ( empty( $check_disbale ) ) {
					$check_disbale = 'no';
				}

				if ( 'yes' == $check_enable ) {
					$wps_wpr_variable_points = (int) get_post_meta( $variation_id, 'wps_wpr_variable_points', true );
					if ( $wps_wpr_variable_points > 0 ) {

						$response['result']             = true;
						$response['variable_points']    = $wps_wpr_variable_points;
						$response['message']            = __( 'Successfully Assigned!', 'ultimate-woocommerce-points-and-rewards' );
						$response['color_notification'] = $wps_wpr_notification_color;
					}
				}

				if ( $enable_purchase_points ) {
					if ( $wps_wpr_restrict_pro_by_points ) {

						$terms = get_the_terms( $wps_wpr_parent_id, 'product_cat' );
						if ( is_array( $terms ) && ! empty( $terms ) ) {
							foreach ( $terms as $term ) {

								$cat_id     = $term->term_id;
								$parent_cat = $term->parent;
								if ( isset( $wps_wpr_categ_list ) && ! empty( $wps_wpr_categ_list ) ) {
									if ( in_array( $cat_id, $wps_wpr_categ_list ) || in_array( $parent_cat, $wps_wpr_categ_list ) ) {

										$wps_wpr_proceed_for_purchase_throgh_point = true;
										break;
									}
								} else {
									$wps_wpr_proceed_for_purchase_throgh_point = false;
								}
							}
						}
					} else {
						$wps_wpr_proceed_for_purchase_throgh_point = true;
					}
				}

				$variable_product = wc_get_product( $variation_id );
				$variable_price = $variable_product->get_price();
				if ( isset( $user_level ) && ! empty( $user_level ) ) {
					if ( isset( $wps_wpr_mem_expr ) && ! empty( $wps_wpr_mem_expr ) && $today_date <= $wps_wpr_mem_expr ) {

						if ( is_array( $wps_wpr_membership_roles ) && ! empty( $wps_wpr_membership_roles ) ) {
							foreach ( $wps_wpr_membership_roles as $roles => $values ) {

								if ( $user_level == $roles ) {
									if ( is_array( $values['Product'] ) && ! empty( $values['Product'] ) ) {
										if ( in_array( $wps_wpr_parent_id, $values['Product'] ) && ! $public_obj->check_exclude_sale_products( $variable_product ) ) {

											$new_price          = $variable_price - ( $variable_price * $values['Discount'] ) / 100;
											$price              = '<span class="price"><del><span class="woocommerce-Price-amount amount">' . wc_price( $variable_price ) . '</del> <ins><span class="woocommerce-Price-amount amount">' . wc_price( $new_price ) . '</span></ins></span>';
											$round_down_setting = $this->wps_general_setting();
											if ( 'wps_wpr_round_down' == $round_down_setting ) {
												$points_calculation = floor( ( $new_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
											} else {
												$points_calculation = ceil( ( $new_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
											}
										}

										$response['result_price'] = 'html';
										$response['variable_price_html'] = $price;
										$wps_wpr_variable_pro_pur_pnt = '<label for="wps_wpr_pro_cost_to_points"><input type="checkbox" name="wps_wpr_pro_cost_to_points" id="wps_wpr_pro_cost_to_points" class="input-text" value="' . esc_html( $points_calculation ) . '">' . esc_html( $wps_wpr_purchase_product_text ) . '</label><input type="hidden" name="wps_wpr_hidden_points" class="wps_wpr_hidden_points" value="' . esc_html( $points_calculation ) . '"><p class="wps_wpr_purchase_pro_point" style="background: ' . esc_html( $wps_wpr_notification_color ) . '">' . esc_html__( 'Spend ', 'ultimate-woocommerce-points-and-rewards' ) . esc_html( $points_calculation ) . __( ' Points for Purchasing this Product for Single Quantity', 'ultimate-woocommerce-points-and-rewards' ) . '</p><span class="wps_wpr_notice"></span><div class="wps_wpr_enter_some_points" style="display: none;"><input type="number" name="wps_wpr_some_custom_points" id="wps_wpr_some_custom_points" value="' . esc_html( $points_calculation ) . '"></div>';
										if ( $enable_purchase_points && $wps_wpr_proceed_for_purchase_throgh_point && 'no' == $check_disbale ) {
											if ( $get_points >= $points_calculation ) {

												$response['result_point']             = 'product_purchased_using_point';
												$response['variable_points_cal_html'] = $wps_wpr_variable_pro_pur_pnt;
											} elseif ( $points_calculation > $get_points ) {

												$extra_need                           = $points_calculation - $get_points;
												$wps_wpr_variable_pro_pur_pnt         = '<p class="wps_wpr_purchase_pro_point">' . __( 'You need extra ', 'ultimate-woocommerce-points-and-rewards' ) . esc_html( $extra_need ) . __( ' Points for getting this product for free', 'ultimate-woocommerce-points-and-rewards' ) . '</p>';
												$response['result_point']             = 'product_purchased_using_point';
												$response['variable_points_cal_html'] = $wps_wpr_variable_pro_pur_pnt;
											}
										}
									} else if ( ! $public_obj->check_exclude_sale_products( $variable_product ) ) {

										$terms = get_the_terms( $wps_wpr_parent_id, 'product_cat' );
										if ( is_array( $terms ) && ! empty( $terms ) ) {
											foreach ( $terms as $term ) {

												$cat_id     = $term->term_id;
												$parent_cat = $term->parent;
												if ( in_array( $cat_id, $values['Prod_Categ'] ) || in_array( $parent_cat, $values['Prod_Categ'] ) ) {

													$new_price          = $variable_price - ( $variable_price * $values['Discount'] ) / 100;
													$price              = '<span class="price"><del><span class="woocommerce-Price-amount amount">' . wc_price( $variable_price ) . '</del> <ins><span class="woocommerce-Price-amount amount">' . wc_price( $new_price ) . '</span></ins></span>';
													$round_down_setting = $this->wps_general_setting();
													if ( 'wps_wpr_round_down' == $round_down_setting ) {
														$points_calculation = floor( ( $new_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
													} else {
														$points_calculation = ceil( ( $new_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
													}

													$response['result_price']        = 'html';
													$response['variable_price_html'] = $price;
													$wps_wpr_variable_pro_pur_pnt = '<label for="wps_wpr_pro_cost_to_points"><input type="checkbox" name="wps_wpr_pro_cost_to_points" id="wps_wpr_pro_cost_to_points" class="input-text" value="' . esc_html( $points_calculation ) . '">' . esc_html( $wps_wpr_purchase_product_text ) . '</label><input type="hidden" name="wps_wpr_hidden_points" class="wps_wpr_hidden_points" value="' . esc_html( $points_calculation ) . '"><p class="wps_wpr_purchase_pro_point" style="background: ' . esc_html( $wps_wpr_notification_color ) . '">' . __( 'Spend ', 'ultimate-woocommerce-points-and-rewards' ) . esc_html( $points_calculation ) . __( ' Points for Purchasing this Product for Single Quantity', 'ultimate-woocommerce-points-and-rewards' ) . '</p><span class="wps_wpr_notice"></span><div class="wps_wpr_enter_some_points" style="display: none;"><input type="number" name="wps_wpr_some_custom_points" id="wps_wpr_some_custom_points" value="' . esc_html( $points_calculation ) . '"></div>';
													if ( $enable_purchase_points && $wps_wpr_proceed_for_purchase_throgh_point && 'no' == $check_disbale ) {
														if ( $get_points >= $points_calculation ) {

															$response['result_point']             = 'product_purchased_using_point';
															$response['variable_points_cal_html'] = $wps_wpr_variable_pro_pur_pnt;
														} elseif ( $enable_purchase_points && $points_calculation > $get_points ) {

															$extra_need                           = $points_calculation - $get_points;
															$wps_wpr_variable_pro_pur_pnt         = '<p class="wps_wpr_purchase_pro_point">' . __( 'You need extra ', 'ultimate-woocommerce-points-and-rewards' ) . esc_html( $extra_need ) . __( ' Points for getting this product for free', 'ultimate-woocommerce-points-and-rewards' ) . '</p>';
															$response['result_point']             = 'product_purchased_using_point';
															$response['variable_points_cal_html'] = $wps_wpr_variable_pro_pur_pnt;
														}
													}
													break;
												}
											}
										}
									}
								}
							}
						}
					}
				} else {

					$round_down_setting = $this->wps_general_setting();
					if ( 'wps_wpr_round_down' == $round_down_setting ) {
						$points_calculation = floor( ( $variable_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
					} else {
						$points_calculation = ceil( ( $variable_price * $wps_wpr_purchase_points ) / $wps_wpr_product_purchase_price );
					}

					$wps_wpr_variable_pro_pur_pnt = '<label for="wps_wpr_pro_cost_to_points"><input type="checkbox" name="wps_wpr_pro_cost_to_points" id="wps_wpr_pro_cost_to_points" class="input-text" value="' . esc_html( $points_calculation ) . '">' . esc_html( $wps_wpr_purchase_product_text ) . '</label><input type="hidden" name="wps_wpr_hidden_points" class="wps_wpr_hidden_points" value="' . esc_html( $points_calculation ) . '"><p class="wps_wpr_purchase_pro_point" style="background: ' . esc_html( $wps_wpr_notification_color ) . '">' . __( 'Spend ', 'ultimate-woocommerce-points-and-rewards' ) . esc_html( $points_calculation ) . __( ' Points for Purchasing this Product for Single Quantity', 'ultimate-woocommerce-points-and-rewards' ) . '</p><span class="wps_wpr_notice"></span><div class="wps_wpr_enter_some_points" style="display: none;"><input type="number" name="wps_wpr_some_custom_points" id="wps_wpr_some_custom_points" value="' . esc_html( $points_calculation ) . '"></div>';
					if ( $enable_purchase_points && $wps_wpr_proceed_for_purchase_throgh_point && 'no' == $check_disbale ) {
						if ( $get_points >= $points_calculation ) {

							$response['result_point']             = 'product_purchased_using_point';
							$response['variable_points_cal_html'] = $wps_wpr_variable_pro_pur_pnt;
						} elseif ( $points_calculation > $get_points ) {

							$extra_need                           = $points_calculation - $get_points;
							$wps_wpr_variable_pro_pur_pnt         = '<p class="wps_wpr_purchase_pro_point">' . __( 'You need extra ', 'ultimate-woocommerce-points-and-rewards' ) . esc_html( $extra_need ) . __( ' Points for getting this product for free', 'ultimate-woocommerce-points-and-rewards' ) . '</p>';
							$response['result_point']             = 'product_purchased_using_point';
							$response['variable_points_cal_html'] = $wps_wpr_variable_pro_pur_pnt;
						}
					}
				}
			}

			// WPS CUSTOM CODE.
			$enable_product_purchase_points = get_post_meta( $wps_wpr_parent_id, 'wps_product_purchase_points_only', true );
			$wps_product_purchase_value     = get_post_meta( $variation_id, 'wps_wpr_variable_points_purchase', true );

			if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {
				if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) ) {

					$response['purchase_pro_pnts_only'] = 'purchased_pro_points';
					$response['price_html']             = $wps_product_purchase_value;
				}
			}
		}
		echo json_encode( $response );
		wp_die();
	}

	/**
	 * The function is used assignging the custom point through purchase
	 *
	 * @name wps_pro_purchase_points_only
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_pro_purchase_points_only() {
		$response['result']  = false;
		$response['message'] = __( 'Error during various variation handling. Try Again!', 'ultimate-woocommerce-points-and-rewards' );
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		if ( isset( $_POST['variation_id'] ) ) {

			$variation_id                   = ! empty( $_POST['variation_id'] ) ? sanitize_text_field( wp_unslash( $$_POST['variation_id'] ) ) : '';
			$wps_wpr_parent_id              = wp_get_post_parent_id( $variation_id );
			$enable_product_purchase_points = get_post_meta( $wps_wpr_parent_id, 'wps_product_purchase_points_only', true );
			$wps_product_purchase_value     = get_post_meta( $variation_id, 'wps_wpr_variable_points_purchase', true );

			if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {
				if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) ) {

					$response['result']                 = true;
					$response['purchase_pro_pnts_only'] = 'purchased_pro_points';
					$response['price_html']             = $wps_product_purchase_value;
				}
			}
		}
		wp_send_json( $response );
	}

	/**
	 * The function for appends the required/custom message for users to let them know how many points they are going to earn/deduct.
	 *
	 * @name wps_wpr_woocommerce_thankyou
	 * @param string $thankyou_msg thankyou msg for the order.
	 * @param object $order order of the customer.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_woocommerce_thankyou( $thankyou_msg, $order ) {
		if ( empty( $order ) ) {
			return;
		}

		$wps_other_settings                  = get_option( 'wps_wpr_other_settings', array() );
		$wps_wpr_thnku_order_msg             = isset( $wps_other_settings['wps_wpr_thnku_order_msg'] ) ? $wps_other_settings['wps_wpr_thnku_order_msg'] : '';
		$wps_wpr_thnku_order_msg_usin_points = isset( $wps_other_settings['wps_wpr_thnku_order_msg_usin_points'] ) ? $wps_other_settings['wps_wpr_thnku_order_msg_usin_points'] : '';
		$item_points                         = 0;
		$purchasing_points                   = 0;
		$wps_wpr_coupon_conversion_value     = get_option( 'wps_wpr_coupons_gallery', array() );
		$wps_wpr_coupon_conversion_price     = ! empty( $wps_wpr_coupon_conversion_value['wps_wpr_coupon_conversion_price'] ) ? $wps_wpr_coupon_conversion_value['wps_wpr_coupon_conversion_price'] : 1;
		$wps_wpr_coupon_conversion_points    = ! empty( $wps_wpr_coupon_conversion_value['wps_wpr_coupon_conversion_points'] ) ? $wps_wpr_coupon_conversion_value['wps_wpr_coupon_conversion_points'] : 1;
		$order_id                            = $order->get_order_number();
		$user_id                             = $order->get_user_id();
		$get_points                          = (int) get_user_meta( $user_id, 'wps_wpr_points', true );

		foreach ( $order->get_items() as $item_id => $item ) {
			$item_quantity = wc_get_order_item_meta( $item_id, '_qty', true );
			$wps_wpr_items = $item->get_meta_data();
			foreach ( $wps_wpr_items as $key => $wps_wpr_value ) {
				if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Purchased By Points' == $wps_wpr_value->key ) ) {

					$item_points  += (int) $wps_wpr_value->value;
					$thankyou_msg .= $wps_wpr_thnku_order_msg;
					$thankyou_msg  = str_replace( '[POINTS]', $item_points, $thankyou_msg );
					$thankyou_msg  = str_replace( '[TOTALPOINT]', $get_points, $thankyou_msg );
				}
				if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Purchasing Option' == $wps_wpr_value->key ) ) {

					$purchasing_points += (int) $wps_wpr_value->value * $item_quantity;
					$thankyou_msg      .= $wps_wpr_thnku_order_msg_usin_points;
					$thankyou_msg       = str_replace( '[POINTS]', $purchasing_points, $thankyou_msg );
					$thankyou_msg       = str_replace( '[TOTALPOINT]', $get_points, $thankyou_msg );
				}
			}
		}

		$item_conversion_id_set = get_post_meta( $order_id, "$order_id#item_conversion_id", false );
		$order_total            = $order->get_total();
		$order_total            = str_replace( wc_get_price_decimal_separator(), '.', strval( $order_total ) );
		$round_down_setting     = $this->wps_general_setting();
		if ( 'wps_wpr_round_down' == $round_down_setting ) {
			$points_calculation = floor( ( $order_total * $wps_wpr_coupon_conversion_points ) / $wps_wpr_coupon_conversion_price );
		} else {
			$points_calculation = ceil( ( $order_total * $wps_wpr_coupon_conversion_points ) / $wps_wpr_coupon_conversion_price );
		}

		if ( isset( $item_conversion_id_set[0] ) && ! empty( $item_conversion_id_set[0] ) && 'set' == $item_conversion_id_set[0] ) {

			$item_points  += $points_calculation;
			$thankyou_msg .= $wps_wpr_thnku_order_msg;
			$thankyou_msg  = str_replace( '[POINTS]', $item_points, $thankyou_msg );
			$thankyou_msg  = str_replace( '[TOTALPOINT]', $get_points, $thankyou_msg );
		}
		return $thankyou_msg;
	}

	/**
	 * The function enable and disable the points om order total.
	 *
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_enable_points_on_order_total_pro() {
		$public_obj = $this->generate_public_obj();
		$wps_wpr_max_points_on_cart = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_max_points_on_cart' );
		if ( 1 == $wps_wpr_max_points_on_cart ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * The function for appending the html on for points apply.
	 *
	 * @param int $get_points get points.
	 * @param int $user_id user id.
	 * @param int $get_min_redeem_req get min redeem req.
	 * @return void
	 */
	public function wps_wpr_points_on_order_total_pro( $get_points, $user_id, $get_min_redeem_req ) {

		$public_obj                 = $this->generate_public_obj();
		$wps_wpr_max_points_on_cart = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_max_points_on_cart' );

		if ( 1 == $wps_wpr_max_points_on_cart ) {

			$wps_wpr_cart_point_type = $public_obj->wps_wpr_get_general_settings( 'wps_wpr_cart_point_type' );
			$wps_wpr_amount_value    = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_amount_value' );

			if ( 'wps_wpr_percentage_cart' == $wps_wpr_cart_point_type ) {
				global $woocommerce;
				$cart_subtotal = $woocommerce->cart->subtotal;
				$applicable_amount = ( $wps_wpr_amount_value / 100 ) * $cart_subtotal;
			} else {
				$applicable_amount = $wps_wpr_amount_value;
			}
			$wps_wpr_cart_points_rate = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_points_rate' );
			$wps_wpr_cart_points_rate = ( 0 == $wps_wpr_cart_points_rate ) ? 1 : $wps_wpr_cart_points_rate;
			$wps_wpr_cart_price_rate  = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_price_rate' );
			$wps_wpr_cart_price_rate  = ( 0 == $wps_wpr_cart_price_rate ) ? 1 : $wps_wpr_cart_price_rate;
			$round_down_setting       = $this->wps_general_setting();

			if ( 'wps_wpr_round_down' == $round_down_setting ) {
				$new_points = floor( ( $wps_wpr_cart_points_rate * $applicable_amount ) / $wps_wpr_cart_price_rate );
			} else {
				$new_points = ceil( ( $wps_wpr_cart_points_rate * $applicable_amount ) / $wps_wpr_cart_price_rate );
			}

			if ( $get_points <= $new_points ) {
				$new_points = $get_points;
			}
			if ( $get_points >= $get_min_redeem_req ) {
				?>
				<div class="wps_wpr_apply_custom_points">
					<input type="number" min="0" name="wps_cart_points" class="input-text" id="wps_cart_points" value="" placeholder="<?php esc_attr_e( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?>"/>
					<input type="button" class="button wps_cart_points_apply" name="wps_cart_points_apply" id="wps_cart_points_apply" value="<?php esc_html_e( 'Apply Points', 'ultimate-woocommerce-points-and-rewards' ); ?>" data-id="<?php echo esc_html( $user_id ); ?>" data-order-limit="<?php echo esc_html( $new_points ); ?>">
					<p><?php esc_html_e( 'Points applicable on this order : ', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<?php echo esc_html( $new_points ); ?></p>
				</div>	
				<?php
			} else {
				$extra_req = abs( $get_min_redeem_req - $get_points );
				?>
				<div class="wps_wpr_apply_custom_points">
					<input type="number" min="0" name="wps_cart_points" class="input-text" id="wps_cart_points" value="" placeholder="<?php esc_attr_e( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?>" readonly/>
					<button class="button wps_cart_points_apply" name="wps_cart_points_apply" id="wps_cart_points_apply" value="<?php esc_html_e( 'Apply Points', 'ultimate-woocommerce-points-and-rewards' ); ?>" data-id="<?php echo esc_html( $user_id ); ?>" data-order-limit="0" disabled><?php esc_html_e( 'Apply Points', 'ultimate-woocommerce-points-and-rewards' ); ?></button>
					<p><?php esc_html_e( 'You require :', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<?php echo esc_html( $extra_req ); ?></p>
					<p><?php esc_html_e( 'more to get redeem', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
				</div>
				<?php
			}
		}
	}

	/**
	 * This function is used to add Remove button along with Cart Discount Fee
	 *
	 * @name wps_wpr_woocommerce_cart_totals_fee_html
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param string $cart_totals_fee_html html of the fees.
	 * @param object  $fee array of the fees.
	 */
	public function wps_wpr_woocommerce_cart_totals_fee_html_purchase_via_point( $cart_totals_fee_html, $fee ) {
		if ( isset( $fee ) && ! empty( $fee ) ) {
			$points_discount = __( 'Point Discount', 'ultimate-woocommerce-points-and-rewards' );
			$fee_name        = $fee->name;

			if ( isset( $fee_name ) && $points_discount == $fee_name ) {
				$cart_totals_fee_html = $cart_totals_fee_html . '<a href="javascript:void(0);" id="wps_wpr_remove_cart_purchase_via_points">' . __( '[Remove]', 'ultimate-woocommerce-points-and-rewards' ) . '</a>';
			}
		}
		return $cart_totals_fee_html;
	}

	/**
	 * This function is used to Remove Cart Discount Fee.
	 *
	 * @name wps_wpr_remove_cart_point
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_remove_cart_purchase_via_points() {
		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_nonce' );
		$response['result']  = false;
		$response['message'] = __( 'Failed to Remove Cart Discount', 'ultimate-woocommerce-points-and-rewards' );
		if ( ! empty( WC()->session->get( 'wps_wpr_purchase_via_points' ) ) ) {
			WC()->session->__unset( 'wps_wpr_purchase_via_points' );
			$response['result'] = true;
			$response['message'] = __( 'Successfully Removed Cart Discount', 'ultimate-woocommerce-points-and-rewards' );
		}
		wp_send_json( $response );
	}

	/**
	 * This function is used for limit apply points on checkout.
	 *
	 * @param int $get_points get points.
	 * @param int $user_id user id.
	 * @param int $get_min_redeem_req get min redeem req.
	 * @return void
	 */
	public function wps_wpr_point_limit_on_order_checkout_pro( $get_points, $user_id, $get_min_redeem_req ) {

		$public_obj                 = $this->generate_public_obj();
		$wps_wpr_max_points_on_cart = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_max_points_on_cart' );
		if ( 1 == $wps_wpr_max_points_on_cart ) {

			$wps_wpr_cart_point_type = $public_obj->wps_wpr_get_general_settings( 'wps_wpr_cart_point_type' );
			$wps_wpr_amount_value    = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_amount_value' );

			if ( $get_points >= $get_min_redeem_req ) {

				if ( 'wps_wpr_percentage_cart' == $wps_wpr_cart_point_type ) {
					global $woocommerce;
					$cart_subtotal     = $woocommerce->cart->subtotal;
					$applicable_amount = ( $wps_wpr_amount_value / 100 ) * $cart_subtotal;
				} else {
					$applicable_amount = $wps_wpr_amount_value;
				}

				$wps_wpr_cart_points_rate = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_points_rate' );
				$wps_wpr_cart_points_rate = ( 0 == $wps_wpr_cart_points_rate ) ? 1 : $wps_wpr_cart_points_rate;
				$wps_wpr_cart_price_rate  = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_price_rate' );
				$wps_wpr_cart_price_rate  = ( 0 == $wps_wpr_cart_price_rate ) ? 1 : $wps_wpr_cart_price_rate;
				$round_down_setting       = $this->wps_general_setting();

				if ( 'wps_wpr_round_down' == $round_down_setting ) {
					$new_points = floor( ( $wps_wpr_cart_points_rate * $applicable_amount ) / $wps_wpr_cart_price_rate );
				} else {
					$new_points = ceil( ( $wps_wpr_cart_points_rate * $applicable_amount ) / $wps_wpr_cart_price_rate );
				}

				if ( $get_points <= $new_points ) {
					$new_points = $get_points;
				}
				?>
				<div class="custom_point_checkout woocommerce-info wps_wpr_checkout_points_class">
					<input type="number" min="0" name="wps_cart_points" class="input-text" id="wps_cart_points" value="" placeholder="<?php esc_attr_e( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?>"/>
					<button class="button wps_cart_points_apply" name="wps_cart_points_apply" id="wps_cart_points_apply" value="<?php esc_html_e( 'Apply Points', 'ultimate-woocommerce-points-and-rewards' ); ?>" data-point="<?php echo esc_html( $new_points ); ?>" data-id="<?php echo esc_html( $user_id ); ?>" data-order-limit="<?php echo esc_html( $new_points ); ?>"><?php esc_html_e( 'Apply Points', 'ultimate-woocommerce-points-and-rewards' ); ?></button>
					<p><?php echo esc_html__( ' Points Applicable on this order : ', 'ultimate-woocommerce-points-and-rewards' ) . esc_html( $new_points ); ?></p>
				</div>
				<?php
			} else {
				$extra_req = abs( $get_min_redeem_req - $get_points );
				?>
				<div class="custom_point_checkout woocommerce-info wps_wpr_checkout_points_class">
					<input type="number" min="0" name="wps_cart_points" class="input-text" id="wps_cart_points" value="" placeholder="<?php esc_attr_e( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?>" readonly/>
					<button class="button wps_cart_points_apply" name="wps_cart_points_apply" id="wps_cart_points_apply" value="<?php esc_html_e( 'Apply Points', 'ultimate-woocommerce-points-and-rewards' ); ?>" data-id="<?php echo esc_html( $user_id ); ?>" data-order-limit="0" disabled><?php esc_html_e( 'Apply Points', 'ultimate-woocommerce-points-and-rewards' ); ?></button>
					<p><?php esc_html_e( 'You require :', 'ultimate-woocommerce-points-and-rewards' ); ?> <?php echo esc_html( $extra_req ); ?> <?php esc_html_e( 'more points to get redeem', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
				</div>
				<?php
			}
		}
	}


	/**
	 * This function is used for validating product purchase using points.
	 *
	 * @name wps_wpr_woocommerce_add_to_cart_validation
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param bool $validate validate result.
	 * @param int  $product_id points.
	 * @param int  $quantity user id.
	 */
	public function wps_wpr_woocommerce_add_to_cart_validation( $validate, $product_id, $quantity ) {
		$_product                       = wc_get_product( $product_id );
		$enable_product_purchase_points = get_post_meta( $product_id, 'wps_product_purchase_points_only', true );
		$wps_product_purchase_value     = (int) get_post_meta( $product_id, 'wps_points_product_purchase_value', true );
		$user                           = wp_get_current_user();
		$user_id                        = $user->ID;
		$get_points                     = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
		$public_obj                     = $this->generate_public_obj();

		if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {
			if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) && ( ! empty( $_product ) && $_product->is_type( 'simple' ) ) ) {

				if ( ! is_user_logged_in() ) {
					$validate = false;
					wc_add_notice( __( 'You must Log in to purchase this product', 'ultimate-woocommerce-points-and-rewards' ), 'error' );
					return $validate;
				} else if ( ( $wps_product_purchase_value * $quantity ) > $get_points ) {
					$validate = false;
					wc_add_notice( __( "You don't have sufficient points to purchase this product", 'ultimate-woocommerce-points-and-rewards' ), 'error' );
					return $validate;
				}
			} elseif ( $public_obj->wps_wpr_check_whether_product_is_variable( $_product ) ) {

				$variation_id                   = ! empty( $_REQUEST['variation_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['variation_id'] ) ) : '';
				$wps_wpr_parent_id              = wp_get_post_parent_id( $variation_id );
				$enable_product_purchase_points = get_post_meta( $wps_wpr_parent_id, 'wps_product_purchase_points_only', true );
				$wps_product_purchase_value     = get_post_meta( $variation_id, 'wps_wpr_variable_points_purchase', true );
				if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {

					if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) ) {
						if ( ! is_user_logged_in() ) {
							$validate = false;
							wc_add_notice( __( 'You must be logged in to purchase this product', 'ultimate-woocommerce-points-and-rewards' ), 'error' );
							return $validate;
						} else if ( ( $wps_product_purchase_value * $quantity ) > $get_points ) {
							$validate = false;
							wc_add_notice( __( "You don't have sufficient points to purchase this product", 'ultimate-woocommerce-points-and-rewards' ), 'error' );
							return $validate;
						}
					}
				}
			}
		}

		$cart_content       = WC()->cart->get_cart();
		$purchas_meta_point = 0;
		if ( ! empty( $cart_content ) ) {
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				if ( isset( $cart_item['product_meta']['meta_data']['wps_wpr_purchase_point_only'] ) && ! empty( $cart_item['product_meta']['meta_data']['wps_wpr_purchase_point_only'] ) ) {

					$purchas_meta_point       += $cart_item['product_meta']['meta_data']['wps_wpr_purchase_point_only'];
					$total_point_to_purchased = $purchas_meta_point + ( $wps_product_purchase_value * $quantity );
					if ( $total_point_to_purchased > $get_points ) {

						$validate = false;
						wc_add_notice( __( "You have already used your points, Now you don't have much!", 'ultimate-woocommerce-points-and-rewards' ), 'error' );
						return $validate;
					}
				}
			}
		}
		return $validate;
	}

	/**
	 * This function is used for validating product purchase using points.
	 *
	 * @name wps_wpr_validate_update_cart
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param string $passed passed.
	 * @param int    $cart_item_key cart item key.
	 * @param string $values value.
	 * @param int    $updated_quantity quantity.
	 */
	public function wps_wpr_validate_update_cart( $passed, $cart_item_key, $values, $updated_quantity ) {
		$nonce_value = ! empty( $_REQUEST['woocommerce-cart-nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['woocommerce-cart-nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) ) {

			$total_pro_pnt = 0;
			$public_obj    = $this->generate_public_obj();
			$cart          = WC()->session->get( 'cart' );
			$user_id       = get_current_user_ID();
			$get_points    = (int) get_user_meta( $user_id, 'wps_wpr_points', true );

			if ( isset( $_POST['cart'] ) && null != $_POST['cart'] && isset( $cart ) && null != $cart ) {
				$cart_update = map_deep( wp_unslash( $_POST['cart'] ), 'sanitize_text_field' );
				foreach ( $cart_update as $key => $value ) {

					if ( isset( WC()->cart->cart_contents[ $key ]['product_meta'] ) ) {
						if ( isset( WC()->cart->cart_contents[ $key ]['product_meta']['meta_data']['wps_wpr_purchase_point_only'] ) ) {

							$product = wc_get_product( $cart[ $key ]['product_id'] );
							if ( $public_obj->wps_wpr_check_whether_product_is_variable( $product ) ) {
								if ( isset( $cart[ $key ]['variation_id'] ) && ! empty( $cart[ $key ]['variation_id'] ) ) {

									$wps_variable_purchase_value = get_post_meta( $cart[ $key ]['variation_id'], 'wps_wpr_variable_points_purchase', true );
								}
								$total_pro_pnt += (int) $wps_variable_purchase_value * (int) $value['qty'];
							} else {
								if ( isset( $cart[ $key ]['product_id'] ) && ! empty( $cart[ $key ]['product_id'] ) ) {

									$get_product_points = get_post_meta( $cart[ $key ]['product_id'], 'wps_points_product_purchase_value', true );
								}
								$total_pro_pnt += (int) $get_product_points * (int) $value['qty'];
							}
						}
					}
				}
				if ( isset( $total_pro_pnt ) && ! empty( $total_pro_pnt ) && $get_points < $total_pro_pnt ) {
					$passed = false;
					wc_add_notice( __( 'You do not have sufficient points for further purchases.', 'ultimate-woocommerce-points-and-rewards' ), 'error' );
				}
			}
		}
		return $passed;
	}

	/**
	 * This function is used for refund points.
	 *
	 * @name wps_wpr_woocommerce_order_status_cancel
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param int    $order_id order_id.
	 * @param string $old_status old_status.
	 * @param string $new_status new_status.
	 */
	public function wps_wpr_woocommerce_order_status_cancel( $order_id, $old_status, $new_status ) {
		$order = wc_get_order( $order_id );
		if ( function_exists( 'wcs_order_contains_renewal' ) && wcs_order_contains_renewal( $order ) ) {
			return;
		}
		if ( ! empty( get_post_meta( $order_id, 'wps_pos_order', true ) ) ) {
			return;
		}
		if ( $old_status != $new_status ) {

			$public_obj = $this->generate_public_obj();
			$order      = wc_get_order( $order_id );
			$user_id    = $order->get_user_id();
			$today_date = date_i18n( 'Y-m-d h:i:sa' );
			$user_log   = get_user_meta( $user_id, 'wps_wpr_user_log', true );
			$user       = get_user_by( 'id', $user_id );

			if ( 'completed' == $old_status && 'refunded' == $new_status ) {
				// refund purchase points.
				$user_id       = $order->get_user_id();
				$refer_id      = get_user_meta( $user_id, 'user_visit_through_link', true );
				$points_log    = get_user_meta( $refer_id, 'points_details', true );
				$pre_wps_check = get_post_meta( $order_id, 'refunded_refer_purchase_point', true );
				if ( ! isset( $pre_wps_check ) || 'done' != $pre_wps_check ) {
					if ( array_key_exists( 'ref_product_detail', $points_log ) ) {
						foreach ( $points_log['ref_product_detail'] as $key => $value ) {
							if ( $value['refered_user'] == $user_id ) {
								$wps_refunded_value_purchase_point = $value['ref_product_detail'];
								$wps_total_points_par = get_user_meta( $refer_id, 'wps_wpr_points', true );
								$wps_points_newly_updated = (int) ( $wps_total_points_par - $wps_refunded_value_purchase_point );
								$wps_refer_deduct_points = get_user_meta( $refer_id, 'points_details', true );
								if ( isset( $wps_refer_deduct_points['wps_refer_purchase_point_refund'] ) && ! empty( $wps_refer_deduct_points['wps_refer_purchase_point_refund'] ) ) {
									$wps_par_refund_purchase = array();
									$wps_par_refund_purchase = array(
										'wps_refer_purchase_point_refund' => $wps_refunded_value_purchase_point,
										'date' => $today_date,
									);
									$wps_refer_deduct_points['wps_refer_purchase_point_refund'][] = $wps_par_refund_purchase;
								} else {
									if ( ! is_array( $wps_refer_deduct_points ) ) {
										$wps_refer_deduct_points = array();
									}
									$wps_par_refund_purchase = array();
									$wps_par_refund_purchase = array(
										'wps_refer_purchase_point_refund' => $wps_refunded_value_purchase_point,
										'date' => $today_date,
									);
									$wps_refer_deduct_points['wps_refer_purchase_point_refund'][] = $wps_par_refund_purchase;
								}
								update_user_meta( $refer_id, 'wps_wpr_points', $wps_points_newly_updated );
								update_user_meta( $refer_id, 'points_details', $wps_refer_deduct_points );
								update_post_meta( $order_id, 'refunded_refer_purchase_point', 'done' );
							}
						}
					}
				}
				// end of purchase point.
				// ========Start Return amount into the coupon========.
				$coupons = $order->get_items( 'coupon' );
				if ( is_array( $coupons ) && ! empty( $user_log ) ) {
					foreach ( $coupons as $item_id => $item ) :
						foreach ( $user_log as $key => $value ) {
							if ( in_array( strtoupper( $item->get_code() ), $value ) ) {
								$coupon_obj = new WC_Coupon( $item->get_code() );
								$coupon_id = $coupon_obj->get_id();
								$couponamont = get_post_meta( $coupon_id, 'coupon_amount', true );
								$order_discount_tax_amount = wc_get_order_item_meta( $item_id, 'discount_amount_tax', true );
								$coupon_amount_before_use = get_post_meta( $coupon_id, 'coupon_amount_before_use', true );
								if ( empty( $coupon_amount_before_use ) ) {
									$coupon_amount_before_use = 0;
								}
								$total_discount = $order_discount_tax_amount + $item->get_discount();

								if ( $coupon_amount_before_use < $total_discount ) {
									$couponamont = $coupon_amount_before_use;
								} else {
									$couponamont = $couponamont + $total_discount;
								}
								// ======Decrese Usages Limit========
								$usage_limit = get_post_meta( $coupon_id, 'usage_limit', true );
								if ( ! empty( $usage_limit ) && $usage_limit >= 1 ) {
									update_post_meta( $coupon_id, 'usage_limit', --$usage_limit );
								}
								// ======Update Coupon Amount========
								if ( ! empty( $couponamont ) ) {
									update_post_meta( $coupon_id, 'coupon_amount', $couponamont );
									$couponamont = 0;
								}
							}
						}
					endforeach;
				}
				// ========End Return amount into the coupon========
				// ======== refund points of product purchase ========
				$user_email = $user->user_email;
				$wps_wpr_notificatin_array = get_option( 'wps_wpr_notificatin_array', true );

				/*Get the purchase points of the product*/
				$wps_wpr_purchase_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_purchase_points' );
				$wps_wpr_purchase_points = ( 0 == $wps_wpr_purchase_points ) ? 1 : $wps_wpr_purchase_points;

				/*Get the price eqivalent to the purchase*/
				$wps_wpr_product_purchase_price = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_price' );
				$wps_wpr_product_purchase_price = ( 0 == $wps_wpr_product_purchase_price ) ? 1 : $wps_wpr_product_purchase_price;

				$deduction_of_points  = get_user_meta( $user_id, 'points_details', true );
				$total_points         = '';
				$updated              = false;
				$refund_assign_points = 0;
				foreach ( $order->get_items() as $item_id => $item ) {

					$item_quantity             = wc_get_order_item_meta( $item_id, '_qty', true );
					$wps_wpr_items             = $item->get_meta_data();
					$wps_product_id            = $item->get_product_id();
					$wps_product_points_enable = get_post_meta( $wps_product_id, 'wps_product_points_enable', 'no' );

					if ( is_array( $wps_wpr_items ) && ! empty( $wps_wpr_items ) ) {
						foreach ( $wps_wpr_items as $key => $wps_wpr_value ) {

							// ========refund points of product purchase ( global assign product points ) ========
							$wps_wpr_assign_products_points = get_option( 'wps_wpr_assign_products_points', true );
							$wps_check_global_points_assign = $wps_wpr_assign_products_points['wps_wpr_global_product_enable'];
							if ( '1' === $wps_check_global_points_assign ) {
								if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Points' == $wps_wpr_value->key ) ) {
									$is_refunded = get_post_meta( $order_id, "$order_id#$item_id#refund_points", true );
									if ( ! isset( $is_refunded ) || 'yes' != $is_refunded ) {
										$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
										$deduct_point = $wps_wpr_value->value;
										$total_points = $get_points - $deduct_point;
										if ( isset( $deduction_of_points['deduction_of_points'] ) && ! empty( $deduction_of_points['deduction_of_points'] ) ) {

											$deduction_arr = array();
											$deduction_arr = array(
												'deduction_of_points' => $deduct_point,
												'date' => $today_date,
											);
											$deduction_of_points['deduction_of_points'][] = $deduction_arr;
										} else {
											$deduction_arr = array();
											$deduction_arr = array(
												'deduction_of_points' => $deduct_point,
												'date' => $today_date,
											);
											$deduction_of_points['deduction_of_points'][] = $deduction_arr;
										}
										update_user_meta( $user_id, 'wps_wpr_points', $total_points );
										update_user_meta( $user_id, 'points_details', $deduction_of_points );
										update_post_meta( $order_id, "$order_id#$item_id#refund_points", 'yes' );
										if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

											$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] : '';
											$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] : '';
											$wps_wpr_email_discription = str_replace( '[DEDUCTEDPOINT]', $deduct_point, $wps_wpr_email_discription );
											$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $total_points, $wps_wpr_email_discription );
											$user = get_user_by( 'email', $user_email );
											$user_name = $user->user_firstname;
											$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );

											/*check is mail notification is enable or not*/
											$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'deduct_assign_points_notification' );
											if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
												$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
												$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
											}
										}
									}
								}
							}

							// ====== refund per product points ( product edit page ) =====
							if ( 'yes' === $wps_product_points_enable ) {
								if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Points' == $wps_wpr_value->key ) ) {
									$is_refunded = get_post_meta( $order_id, "$order_id#$item_id#refund_points", true );
									if ( ! isset( $is_refunded ) || 'yes' != $is_refunded ) {
										$get_points            = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
										$deduct_point          = $wps_wpr_value->value;
										$total_points          = $get_points - $deduct_point;
										$refund_assign_points += $deduct_point;

										update_user_meta( $user_id, 'wps_wpr_points', $total_points );
										// update_user_meta( $user_id, 'points_details', $deduction_of_points ).
										update_post_meta( $order_id, "$order_id#$item_id#refund_points", 'yes' );
										$updated = true;
										if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

											$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] : '';
											$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] : '';
											$wps_wpr_email_discription = str_replace( '[DEDUCTEDPOINT]', $deduct_point, $wps_wpr_email_discription );
											$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $total_points, $wps_wpr_email_discription );
											$user = get_user_by( 'email', $user_email );
											$user_name = $user->user_firstname;
											$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );

											/*check is mail notification is enable or not*/
											$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'deduct_assign_points_notification' );
											if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
												$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
												$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
											}
										}
									}
								}
							}

							// ========refund points when product purchased through point ========
							if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Purchased By Points' == $wps_wpr_value->key ) ) {
								$is_refunded = get_post_meta( $order_id, "$order_id#$item_id#refund_purchased_point", true );
								if ( ! isset( $is_refunded ) || 'yes' != $is_refunded ) {
									$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
									$return_pur_point = $wps_wpr_value->value;
									$total_return_points = $get_points + $return_pur_point;
									if ( isset( $deduction_of_points['return_pur_points'] ) && ! empty( $deduction_of_points['return_pur_points'] ) ) {
										$return_arr = array();
										$return_arr = array(
											'return_pur_points' => $return_pur_point,
											'date' => $today_date,
										);
										$deduction_of_points['return_pur_points'][] = $return_arr;
									} else {
										if ( ! is_array( $deduction_of_points ) ) {
											$deduction_of_points = array();
										}
										$return_arr = array(
											'return_pur_points' => $return_pur_point,
											'date' => $today_date,
										);
										$deduction_of_points['return_pur_points'][] = $return_arr;
									}
									update_user_meta( $user_id, 'wps_wpr_points', $total_return_points );
									update_user_meta( $user_id, 'points_details', $deduction_of_points );
									update_post_meta( $order_id, "$order_id#$item_id#refund_purchased_point", 'yes' );
									if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

										$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] : '';
										$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] : '';
										$wps_wpr_email_discription = str_replace( '[RETURNPOINT]', $return_pur_point, $wps_wpr_email_discription );
										$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $total_return_points, $wps_wpr_email_discription );
										$user = get_user_by( 'email', $user_email );
										$user_name = $user->user_firstname;
										$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
										/*check is mail notification is enable or not*/
										$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'return_product_pur_thr_points_notification' );
										if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {

											$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
											$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
										}
									}
								}
							}
						}
					}
				}
				// refund global assign points when order refunded.
				if ( $updated ) {
					if ( isset( $deduction_of_points['deduction_of_points'] ) && ! empty( $deduction_of_points['deduction_of_points'] ) ) {

						$deduction_arr = array();
						$deduction_arr = array(
							'deduction_of_points' => $refund_assign_points,
							'date' => $today_date,
						);
						$deduction_of_points['deduction_of_points'][] = $deduction_arr;
					} else {
						$deduction_arr = array();
						$deduction_arr = array(
							'deduction_of_points' => $refund_assign_points,
							'date' => $today_date,
						);
						$deduction_of_points['deduction_of_points'][] = $deduction_arr;
					}
					update_user_meta( $user_id, 'points_details', $deduction_of_points );
				}
				// ========refund points of product purchase ========

				// ======== start of refund Order Total Points - Per Currency Spent ========
				if ( $public_obj->is_order_conversion_enabled() ) {
					$item_conversion_id_set = get_post_meta( $order_id, "$order_id#item_conversion_id", false );
					$order_total            = $order->get_total();
					$order_total            = str_replace( wc_get_price_decimal_separator(), '.', strval( $order_total ) );
					$round_down_setting     = $this->wps_general_setting();

					if ( isset( $item_conversion_id_set[0] ) && ! empty( $item_conversion_id_set[0] ) && 'set' == $item_conversion_id_set[0] ) {
						$refund_per_currency_spend_points = get_post_meta( $order_id, "$order_id#refund_per_currency_spend_points", true );
						if ( ! isset( $refund_per_currency_spend_points ) || 'yes' != $refund_per_currency_spend_points ) {
							$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
							$points_log = get_user_meta( $user_id, 'points_details', true );
							$all_refunds = $order->get_refunds();
							if ( isset( $all_refunds ) && ! empty( $all_refunds ) ) {

								$refund_item = $all_refunds[0];
								// $refund_amount = $refund_item->get_amount();
								/*total calculation of the points*/
								$wps_wpr_coupon_conversion_points = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_conversion_points' );
								$wps_wpr_coupon_conversion_points = ( 0 == $wps_wpr_coupon_conversion_points ) ? 1 : $wps_wpr_coupon_conversion_points;
								/*Get the value of the price*/
								$wps_wpr_coupon_conversion_price = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_coupon_conversion_price' );
								$wps_wpr_coupon_conversion_price = ( 0 == $wps_wpr_coupon_conversion_price ) ? 1 : $wps_wpr_coupon_conversion_price;
								$round_down_setting = $this->wps_general_setting();
								if ( array_key_exists( 'pro_conversion_points', $points_log ) ) {
									foreach ( $points_log['pro_conversion_points'] as $key => $value ) {
										if ( ! empty( $value['refered_order_id'] ) ) {
											if ( $value['refered_order_id'] == $order_id ) {
												$refund_amount = $value['pro_conversion_points'];
											}
										}
									}
								}

								if ( 'wps_wpr_round_down' == $round_down_setting ) {
									$refund_amount = floor( $refund_amount );
								} else {
									$refund_amount = ceil( $refund_amount );
								}

								$deduct_currency_spent = $refund_amount;
								$remaining_points = $get_points - $deduct_currency_spent;
								if ( isset( $deduction_of_points['deduction_currency_spent'] ) && ! empty( $deduction_of_points['deduction_currency_spent'] ) ) {
									$currency_arr = array();
									$currency_arr = array(
										'deduction_currency_spent' => $deduct_currency_spent,
										'date' => $today_date,
									);
									$deduction_of_points['deduction_currency_spent'][] = $currency_arr;
								} else {
									$currency_arr = array();
									$currency_arr = array(
										'deduction_currency_spent' => $deduct_currency_spent,
										'date' => $today_date,
									);
									$deduction_of_points['deduction_currency_spent'][] = $currency_arr;
								}
								update_user_meta( $user_id, 'wps_wpr_points', $remaining_points );
								update_user_meta( $user_id, 'points_details', $deduction_of_points );
								update_post_meta( $order_id, "$order_id#refund_per_currency_spend_points", 'yes' );
								if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

									$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_subject'] : '';
									$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_description'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_description'] : '';
									$wps_wpr_email_discription = str_replace( '[DEDUCTEDPOINT]', $deduct_currency_spent, $wps_wpr_email_discription );
									$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $remaining_points, $wps_wpr_email_discription );
									$user = get_user_by( 'email', $user_email );
									$user_name = $user->user_firstname;
									$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
									/*Check is points Email notification is enable*/
									$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'deduct_per_currency_spent_notification' );
									if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
										$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
										$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
									}
								}
							}
						}
					}
				}
				// ======== end of refund Order Total Points - Per Currency Spent ========
				// Refund amount of Point Discount
				if ( isset( $order ) && ! empty( $order ) ) {
					$order_fees = $order->get_fees();
					if ( ! empty( $order_fees ) ) {
						$deduction_of_points = get_user_meta( $user_id, 'points_details', true );
						$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
						foreach ( $order_fees as $fee_item_id => $fee_item ) {
							$fee_name = $fee_item->get_name();
							$fee_amount = $fee_item->get_amount();
							$points_discount = __( 'Point Discount', 'ultimate-woocommerce-points-and-rewards' );
							if ( isset( $fee_name ) && $fee_name == $points_discount ) {
								$refund_point_discount = get_post_meta( $order_id, '$order_id#$fee_id#refund_point_discount', true );
								if ( ! isset( $refund_point_discount ) || 'yes' != $refund_point_discount ) {
									$fee_amount = -( $fee_amount );
									// WOOCS - WooCommerce Currency Switcher Compatibility.
									$fee_amount = apply_filters( 'wps_wpr_convert_base_price_diffrent_currency', $fee_amount );
									$round_down_setting = $this->wps_general_setting();
									if ( 'wps_wpr_round_down' == $round_down_setting ) {

										$fee_to_point = floor( ( $wps_wpr_purchase_points * $fee_amount ) / $wps_wpr_product_purchase_price );
									} else {
										$fee_to_point = ceil( ( $wps_wpr_purchase_points * $fee_amount ) / $wps_wpr_product_purchase_price );
									}

									$total_point = $get_points + $fee_to_point;
									if ( isset( $deduction_of_points['return_pur_points'] ) && ! empty( $deduction_of_points['return_pur_points'] ) ) {
										$return_arr = array(
											'return_pur_points' => $fee_to_point,
											'date' => $today_date,
										);
										$deduction_of_points['return_pur_points'][] = $return_arr;
									} else {
										if ( ! is_array( $deduction_of_points ) ) {
											$deduction_of_points = array();
										}
										$return_arr = array(
											'return_pur_points' => $fee_to_point,
											'date' => $today_date,
										);
										$deduction_of_points['return_pur_points'][] = $return_arr;
									}
									update_user_meta( $user_id, 'wps_wpr_points', $total_point );
									update_user_meta( $user_id, 'points_details', $deduction_of_points );
									update_post_meta( $order_id, '$order_id#$fee_id#refund_point_discount', 'yes' );
									if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

										$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] : '';
										$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] : '';
										$wps_wpr_email_discription = str_replace( '[RETURNPOINT]', $fee_to_point, $wps_wpr_email_discription );
										$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $total_point, $wps_wpr_email_discription );
										$user = get_user_by( 'email', $user_email );
										$user_name = $user->user_firstname;
										$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
										/*check is mail notification is enable or not*/
										$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'return_product_pur_thr_points_notification' );
										if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
											$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
											$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
										}
									}
								}
							}
							$cart_discount = __( 'Cart Discount', 'ultimate-woocommerce-points-and-rewards' );
							if ( isset( $fee_name ) && $cart_discount == $fee_name ) {
								$refund_cart_discount = get_post_meta( $order_id, '$order_id#$fee_id#refund_cart_discount', true );
								if ( ! isset( $refund_cart_discount ) || 'yes' != $refund_cart_discount ) {
									$fee_amount = -( $fee_amount );
									// WOOCS - WooCommerce Currency Switcher Compatibility.
									$fee_amount = apply_filters( 'wps_wpr_convert_base_price_diffrent_currency', $fee_amount );
									$wps_wpr_cart_points_rate = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_points_rate' );
									$wps_wpr_cart_points_rate = ( 0 == $wps_wpr_cart_points_rate ) ? 1 : $wps_wpr_cart_points_rate;
									$wps_wpr_cart_price_rate = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_price_rate' );
									$wps_wpr_cart_price_rate = ( 0 == $wps_wpr_cart_price_rate ) ? 1 : $wps_wpr_cart_price_rate;
									$round_down_setting = $this->wps_general_setting();
									if ( 'wps_wpr_round_down' == $round_down_setting ) {
										$fee_to_point = floor( ( $wps_wpr_cart_points_rate * $fee_amount ) / $wps_wpr_cart_price_rate );
									} else {
										$fee_to_point = ceil( ( $wps_wpr_cart_points_rate * $fee_amount ) / $wps_wpr_cart_price_rate );

									}

									$total_return_points = $get_points + $fee_to_point;
									if ( isset( $deduction_of_points['return_pur_points'] ) && ! empty( $deduction_of_points['return_pur_points'] ) ) {
										$return_arr = array();
										$return_arr = array(
											'return_pur_points' => $fee_to_point,
											'date' => $today_date,
										);
										$deduction_of_points['return_pur_points'][] = $return_arr;
									} else {
										if ( ! is_array( $deduction_of_points ) ) {
											$deduction_of_points = array();
										}
										$return_arr = array(
											'return_pur_points' => $fee_to_point,
											'date' => $today_date,
										);
										$deduction_of_points['return_pur_points'][] = $return_arr;
									}
									update_user_meta( $user_id, 'wps_wpr_points', $total_return_points );
									update_user_meta( $user_id, 'points_details', $deduction_of_points );
									update_post_meta( $order_id, '$order_id#$fee_id#refund_cart_discount', 'yes' );

									if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {
										$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] : '';
										$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] : '';
										$wps_wpr_email_discription = str_replace( '[RETURNPOINT]', $fee_to_point, $wps_wpr_email_discription );
										$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $total_return_points, $wps_wpr_email_discription );
										$user = get_user_by( 'email', $user_email );
										$user_name = $user->user_firstname;
										$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
										/*check is mail notification is enable or not*/
										$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'return_product_pur_thr_points_notification' );
										if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
											$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
											$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
										}
									}
								}
							}
						}
					}

					// Refund of order total points.
					if ( $public_obj->check_enable_offer() ) {
						$this->wps_refund_order_total_point( $order_id );
					}
				}
			}

			$wps_wpr_array_status = array( 'processing', 'on-hold', 'pending', 'completed' );
			// $wps_wpr_array_status = apply_filter( 'wps_custom_order_statutes', $wps_wpr_array_status );
			if ( in_array( $old_status, $wps_wpr_array_status ) && ( 'cancelled' == $new_status || 'refunded' == $new_status ) ) {

				// ========Start Return amount into the coupon========
				$coupons = $order->get_items( 'coupon' );
				if ( is_array( $coupons ) && ! empty( $user_log ) ) {
					foreach ( $coupons as $item_id => $item ) :
						foreach ( $user_log as $key => $value ) {
							if ( in_array( strtoupper( $item->get_code() ), $value ) ) {
								$coupon_obj = new WC_Coupon( $item->get_code() );
								$coupon_id = $coupon_obj->get_id();
								$couponamont = get_post_meta( $coupon_id, 'coupon_amount', true );
									$order_discount_tax_amount = wc_get_order_item_meta( $item_id, 'discount_amount_tax', true );
								$coupon_amount_before_use = get_post_meta( $coupon_id, 'coupon_amount_before_use', true );
								if ( empty( $coupon_amount_before_use ) ) {
									$coupon_amount_before_use = 0;
								}
								$total_discount = $order_discount_tax_amount + $item->get_discount();

								if ( $coupon_amount_before_use < $total_discount ) {
									$couponamont = $coupon_amount_before_use;
								} else {
									$couponamont = $couponamont + $total_discount;
								}
								// ======Decrese Usages Limit========
								$usage_limit = get_post_meta( $coupon_id, 'usage_limit', true );
								if ( ! empty( $usage_limit ) && $usage_limit >= 1 ) {
									update_post_meta( $coupon_id, 'usage_limit', --$usage_limit );
								}
								// ======Update Coupon Amount========.
								if ( ! empty( $couponamont ) ) {
									update_post_meta( $coupon_id, 'coupon_amount', $couponamont );
									$couponamont = 0;
								}
							}
						}
					endforeach;
				}

				$coupons = $order->get_items( 'coupon' );
				if ( is_array( $coupons ) && ! empty( $coupons ) ) {
					$wps_deduct_coupon_points = 0;
					foreach ( $coupons as $item_id => $item ) {
						$coupon_obj = new WC_Coupon( $item->get_code() );
						$coupon_id  = $coupon_obj->get_id();
						$user_id    = get_post_meta( $coupon_id, 'refferedby_coupon', true );
						if ( ! empty( $user_id ) ) {
							$total_points = get_user_meta( $user_id, 'wps_wpr_points', true );
							$points_log = get_user_meta( $user_id, 'points_details', true );
							if ( ! empty( $points_log['points_on_coupon_refer'] ) ) {
								foreach ( $points_log['points_on_coupon_refer'] as $key => $value ) {
									$order = wc_get_order( $order_id );
									$wps_current_user_id = $order->get_user_id();
									$user_detail = get_user_by( 'id', $wps_current_user_id );
									$user_email = $user_detail->user_email;
									if ( $value['refered_user'] == $user_email ) {
										$wps_deduct_coupon_points = $value['points_on_coupon_refer'];
										$wps_final_points = $total_points - $wps_deduct_coupon_points;
										update_user_meta( $user_id, 'wps_wpr_points', $wps_final_points );
										$today_date = date_i18n( 'Y-m-d h:i:sa' );
										if ( isset( $points_log['refunded_coupon_refer_points'] ) && ! empty( $points_log['refunded_coupon_refer_points'] ) ) {
											$points_arr = array();
											$points_arr = array(
												'refunded_coupon_refer_points' => $wps_deduct_coupon_points,
												'date' => $today_date,
											);
											$points_log['refunded_coupon_refer_points'][] = $points_arr;
										} else {
											$points_arr = array();
											$points_arr = array(
												'refunded_coupon_refer_points' => $wps_deduct_coupon_points,
												'date' => $today_date,
											);
											$points_log['refunded_coupon_refer_points'][] = $points_arr;
										}
										update_user_meta( $user_id, 'points_details', $points_log );
										break;
									}
								}
							}
						}
					}
				}
				// ========End Return amount into the coupon========

				// ======== refund points of product purchase cancellation ( refund assign points ) ========
				$user_email = $user->user_email;
				$wps_wpr_notificatin_array = get_option( 'wps_wpr_notificatin_array', true );
				/*Get the purchase points of the product*/
				$wps_wpr_purchase_points = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_purchase_points' );
				$wps_wpr_purchase_points = ( 0 == $wps_wpr_purchase_points ) ? 1 : $wps_wpr_purchase_points;

				/*Get the price eqivalent to the purchase*/
				$wps_wpr_product_purchase_price = $this->wps_wpr_get_product_purchase_settings_num( 'wps_wpr_product_purchase_price' );
				$wps_wpr_product_purchase_price = ( 0 == $wps_wpr_product_purchase_price ) ? 1 : $wps_wpr_product_purchase_price;
				$user_id                        = $order->get_user_id();

				$wps_wpr_array_status = array( 'processing', 'on-hold', 'pending', 'completed' );
				if ( in_array( $old_status, $wps_wpr_array_status ) && ( 'cancelled' == $new_status ) ) {
					$deduct_bcoz_cancel           = get_user_meta( $user_id, 'points_details', true );
					$total_points                 = '';
					$updated                      = false;
					$assign_purchase_point_return = 0;

					foreach ( $order->get_items() as $item_id => $item ) {
						$item_quantity = wc_get_order_item_meta( $item_id, '_qty', true );
						$wps_wpr_items = $item->get_meta_data();

						if ( is_array( $wps_wpr_items ) && ! empty( $wps_wpr_items ) ) {
							foreach ( $wps_wpr_items as $key => $wps_wpr_value ) {
								// ========refund points of product purchase cancellation ========
								if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Points' == $wps_wpr_value->key ) ) {
									$is_get_pro_points = get_post_meta( $order_id, "$order_id#$wps_wpr_value->id#set", true );

									if ( 'set' == $is_get_pro_points ) {
										$cancel_points = get_post_meta( $order_id, "$order_id#$item_id#cancel_points", true );
										if ( ! isset( $cancel_points ) || 'yes' != $cancel_points ) {
											$get_points                    = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
											$deduct_point                  = $wps_wpr_value->value;
											$total_points                  = $get_points - $deduct_point;
											$assign_purchase_point_return += $deduct_point;

											update_user_meta( $user_id, 'wps_wpr_points', $total_points );
											// update_user_meta( $user_id, 'points_details', $deduct_bcoz_cancel ).
											update_post_meta( $order_id, "$order_id#$item_id#cancel_points", 'yes' );
											$updated = true;
											if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

												$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] : '';
												$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] : '';
												$wps_wpr_email_discription = str_replace( '[DEDUCTEDPOINT]', $deduct_point, $wps_wpr_email_discription );
												$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $total_points, $wps_wpr_email_discription );
												$user = get_user_by( 'email', $user_email );
												$user_name = $user->user_firstname;
												$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
												/*check is mail notification is enable or not*/
												$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'deduct_assign_points_notification' );
												if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
													$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
													$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
												}
											}
										}
									}
								}

								// Return Points on Cancellation Order (Product Purchasedby using Points only).
								if ( isset( $wps_wpr_value->key ) && ! empty( $wps_wpr_value->key ) && ( 'Purchased By Points' == $wps_wpr_value->key ) ) {
									$cancel_pro_purchase_point = get_post_meta( $order_id, "$order_id#$item_id#cancel_pro_purchase_point", true );
									if ( ! isset( $cancel_pro_purchase_point ) || 'yes' != $cancel_pro_purchase_point ) {

										$deduct_bcz_cancel   = get_user_meta( $user_id, 'points_details', true );
										$get_points          = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
										$return_pur_point    = $wps_wpr_value->value;
										$total_return_points = $get_points + $return_pur_point;

										if ( isset( $deduct_bcz_cancel['pur_points_cancel'] ) && ! empty( $deduct_bcz_cancel['pur_points_cancel'] ) ) {
											$return_arr = array();
											$return_arr = array(
												'pur_points_cancel' => $return_pur_point,
												'date' => $today_date,
											);
											$deduct_bcz_cancel['pur_points_cancel'][] = $return_arr;
										} else {
											if ( ! is_array( $deduct_bcz_cancel ) ) {
												$deduct_bcz_cancel = array();
											}
											$return_arr = array(
												'pur_points_cancel' => $return_pur_point,
												'date' => $today_date,
											);
											$deduct_bcz_cancel['pur_points_cancel'][] = $return_arr;
										}
										update_user_meta( $user_id, 'wps_wpr_points', $total_return_points );
										update_user_meta( $user_id, 'points_details', $deduct_bcz_cancel );
										update_post_meta( $order_id, "$order_id#$item_id#cancel_pro_purchase_point", 'yes' );
										if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

											$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] : '';
											$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] : '';
											$wps_wpr_email_discription = str_replace( '[RETURNPOINT]', $return_pur_point, $wps_wpr_email_discription );
											$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $total_return_points, $wps_wpr_email_discription );
											$user = get_user_by( 'email', $user_email );
											$user_name = $user->user_firstname;
											$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
											/*check is mail notification is enable or not*/
											$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'return_product_pur_thr_points_notification' );
											if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
												$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
												$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
											}
										}
									}
								}
							}
						}
					}
					if ( $updated ) {
						if ( isset( $deduct_bcoz_cancel['deduct_bcz_cancel'] ) && ! empty( $deduct_bcoz_cancel['deduct_bcz_cancel'] ) ) {
							$deduction_arr = array(
								'deduct_bcz_cancel' => $assign_purchase_point_return,
								'date' => $today_date,
							);
							$deduct_bcoz_cancel['deduct_bcz_cancel'][] = $deduction_arr;
						} else {
							if ( ! is_array( $deduct_bcoz_cancel ) ) {
								$deduct_bcoz_cancel = array();
							}
							$deduction_arr = array(
								'deduct_bcz_cancel' => $assign_purchase_point_return,
								'date' => $today_date,
							);
							$deduct_bcoz_cancel['deduct_bcz_cancel'][] = $deduction_arr;
						}
						update_user_meta( $user_id, 'points_details', $deduct_bcoz_cancel );
					}
				}
				// pos problm.

				// ======== start of refund points Order Total Points - Per Currency Spent on calcellation ========
				$wps_wpr_array_status = array( 'processing', 'on-hold', 'pending', 'completed' );
				if ( 'completed' == $old_status && 'cancelled' == $new_status ) {
					if ( $public_obj->is_order_conversion_enabled() ) {
						$user_id                          = $order->get_user_id();
						$points_logs                      = get_user_meta( $user_id, 'points_details', true );
						$is_get_currency_spend_points     = get_post_meta( $order_id, "$order_id#item_conversion_id", true );
						$cancel_per_currency_spend_points = get_post_meta( $order_id, "$order_id#cancel_per_currency_spend_points", true );

						if ( 'set' == $is_get_currency_spend_points ) {
							if ( ! isset( $cancel_per_currency_spend_points ) || 'yes' != $cancel_per_currency_spend_points ) {

								// get per currency given points.
								$order_total = 0;
								if ( array_key_exists( 'pro_conversion_points', $points_logs ) ) {
									foreach ( $points_logs['pro_conversion_points'] as $key => $value ) {
										if ( ! empty( $value['refered_order_id'] ) ) {
											if ( $value['refered_order_id'] == $order_id ) {
												$order_total = $value['pro_conversion_points'];
											}
										}
									}
								}
								// WOOCS - WooCommerce Currency Switcher Compatibility.
								$order_total        = apply_filters( 'wps_wpr_convert_base_price_diffrent_currency', $order_total );
								$order_total        = str_replace( wc_get_price_decimal_separator(), '.', strval( $order_total ) );
								$round_down_setting = $this->wps_general_setting();
								$get_points         = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
								$refund_amount      = $order_total;

								if ( $refund_amount > 0 ) {
									if ( 'wps_wpr_round_down' == $round_down_setting ) {
										$refund_amount = floor( $refund_amount );
									} else {
										$refund_amount = ceil( $refund_amount );
									}

									$deduct_currency_spent = $refund_amount;
									$remaining_points      = $get_points - $deduct_currency_spent;
									if ( isset( $points_logs['deduct_currency_pnt_cancel'] ) && ! empty( $points_logs['deduct_currency_pnt_cancel'] ) ) {
										$currency_arr = array();
										$currency_arr = array(
											'deduct_currency_pnt_cancel' => $deduct_currency_spent,
											'date' => $today_date,
										);
										$points_logs['deduct_currency_pnt_cancel'][] = $currency_arr;
									} else {
										$currency_arr = array();
										$currency_arr = array(
											'deduct_currency_pnt_cancel' => $deduct_currency_spent,
											'date' => $today_date,
										);
										$points_logs['deduct_currency_pnt_cancel'][] = $currency_arr;
									}
									update_user_meta( $user_id, 'wps_wpr_points', $remaining_points );
									update_user_meta( $user_id, 'points_details', $points_logs );
									update_post_meta( $order_id, "$order_id#cancel_per_currency_spend_points", 'yes' );
									if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

										$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_subject'] : '';
										$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_description'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_per_currency_point_description'] : '';
										$wps_wpr_email_discription = str_replace( '[DEDUCTEDPOINT]', $deduct_currency_spent, $wps_wpr_email_discription );
										$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $remaining_points, $wps_wpr_email_discription );
										$user = get_user_by( 'email', $user_email );
										$user_name = $user->user_firstname;
										$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
										$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'deduct_per_currency_spent_notification' );
										if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
											$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
											$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
										}
									}
								}
							}
						}
					}
				}
				// ======== end of refund points Order Total Points - Per Currency Spent on calcellation ========

				// Refund amount of Point Discount.
				if ( isset( $order ) && ! empty( $order ) ) {
					$order_fees = $order->get_fees();
					$wps_wpr_array_status = array( 'processing', 'on-hold', 'pending', 'completed' );
					if ( in_array( $old_status, $wps_wpr_array_status ) && ( 'cancelled' == $new_status ) ) {
						if ( ! empty( $order_fees ) ) {
							$deduction_of_points = get_user_meta( $user_id, 'points_details', true );
							$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
							foreach ( $order_fees as $fee_item_id => $fee_item ) {
								$fee_id          = $fee_item_id;
								$fee_name        = $fee_item->get_name();
								$fee_amount      = $fee_item->get_amount();
								$points_discount = __( 'Point Discount', 'ultimate-woocommerce-points-and-rewards' );

								if ( isset( $fee_name ) && $fee_name == $points_discount ) {
									$refund_point_discount_on_cancel = get_post_meta( $order_id, '$order_id#$fee_id#refund_point_discount_on_cancel', true );
									if ( ! isset( $refund_point_discount_on_cancel ) || 'yes' != $refund_point_discount_on_cancel ) {
										$fee_amount = -( $fee_amount );
										// WOOCS - WooCommerce Currency Switcher Compatibility.
										if ( class_exists( 'WOOCS' ) ) {
											global $WOOCS;
											$wps_currency = get_post_meta( $order_id, '_order_currency', true );
											if ( $wps_currency == $WOOCS->default_currency ) {

												$currencies = $WOOCS->get_currencies();
												$rate       = $currencies[ $wps_currency ]['rate'];
												$fee_amount = round( $fee_amount / ( $rate ) );

											} elseif ( $wps_currency != $WOOCS->default_currency ) {
												$currencies = $WOOCS->get_currencies();
												$rate       = $currencies[ $wps_currency ]['rate'];
												$fee_amount = round( $fee_amount / ( $rate ) );
											}
										}

										$round_down_setting = $this->wps_general_setting();
										if ( 'wps_wpr_round_down' == $round_down_setting ) {
											$fee_to_point = floor( ( $wps_wpr_purchase_points * $fee_amount ) / $wps_wpr_product_purchase_price );
										} else {
											$fee_to_point = ceil( ( $wps_wpr_purchase_points * $fee_amount ) / $wps_wpr_product_purchase_price );

										}

										$total_point = $get_points + $fee_to_point;
										if ( isset( $deduction_of_points['return_pur_points'] ) && ! empty( $deduction_of_points['return_pur_points'] ) ) {
											$return_arr = array(
												'return_pur_points' => $fee_to_point,
												'date' => $today_date,
											);
											$deduction_of_points['return_pur_points'][] = $return_arr;
										} else {
											if ( ! is_array( $deduction_of_points ) ) {
												$deduction_of_points = array();
											}
											$return_arr = array(
												'return_pur_points' => $fee_to_point,
												'date' => $today_date,
											);
											$deduction_of_points['return_pur_points'][] = $return_arr;
										}
										update_user_meta( $user_id, 'wps_wpr_points', $total_point );
										update_user_meta( $user_id, 'points_details', $deduction_of_points );
										update_post_meta( $order_id, '$order_id#$fee_id#refund_point_discount_on_cancel', 'yes' );
										if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

											$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] : '';
											$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] : '';
											$wps_wpr_email_discription = str_replace( '[RETURNPOINT]', $fee_to_point, $wps_wpr_email_discription );
											$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $total_point, $wps_wpr_email_discription );
											$user = get_user_by( 'email', $user_email );
											$user_name = $user->user_firstname;
											$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
											/*check is mail notification is enable or not*/
											$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'return_product_pur_thr_points_notification' );
											if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {

												$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
												$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
											}
										}
									}
								}

								$cart_discount = __( 'Cart Discount', 'ultimate-woocommerce-points-and-rewards' );
								if ( isset( $fee_name ) && $cart_discount == $fee_name ) {
									$refund_cart_discount_on_cancel = get_post_meta( $order_id, '$order_id#$fee_id#refund_cart_discount_on_cancel', true );
									if ( ! isset( $refund_cart_discount_on_cancel ) || 'yes' != $refund_cart_discount_on_cancel ) {
										$fee_amount = -( $fee_amount );
										// WOOCS - WooCommerce Currency Switcher Compatibility.
										$fee_amount = apply_filters( 'wps_wpr_convert_base_price_diffrent_currency', $fee_amount );
										$wps_wpr_cart_points_rate = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_points_rate' );
										$wps_wpr_cart_points_rate = ( 0 == $wps_wpr_cart_points_rate ) ? 1 : $wps_wpr_cart_points_rate;
										$wps_wpr_cart_price_rate = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_price_rate' );
										$wps_wpr_cart_price_rate = ( 0 == $wps_wpr_cart_price_rate ) ? 1 : $wps_wpr_cart_price_rate;
										$round_down_setting = $this->wps_general_setting();
										if ( 'wps_wpr_round_down' == $round_down_setting ) {
											$fee_to_point = floor( ( $wps_wpr_cart_points_rate * $fee_amount ) / $wps_wpr_cart_price_rate );

										} else {
											$fee_to_point = ceil( ( $wps_wpr_cart_points_rate * $fee_amount ) / $wps_wpr_cart_price_rate );
										}
										$total_return_points = $get_points + $fee_to_point;

										if ( isset( $deduction_of_points['return_pur_points'] ) && ! empty( $deduction_of_points['return_pur_points'] ) ) {
											$return_arr = array();
											$return_arr = array(
												'return_pur_points' => $fee_to_point,
												'date' => $today_date,
											);
											$deduction_of_points['return_pur_points'][] = $return_arr;
										} else {
											if ( ! is_array( $deduction_of_points ) ) {
												$deduction_of_points = array();
											}
											$return_arr = array(
												'return_pur_points' => $fee_to_point,
												'date' => $today_date,
											);
											$deduction_of_points['return_pur_points'][] = $return_arr;
										}
										update_user_meta( $user_id, 'wps_wpr_points', $total_return_points );
										update_user_meta( $user_id, 'points_details', $deduction_of_points );
										update_post_meta( $order_id, '$order_id#$fee_id#refund_cart_discount_on_cancel', 'yes' );

										if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

											$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_subject'] : '';
											$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] ) ? $wps_wpr_notificatin_array['wps_wpr_return_pro_pur_description'] : '';
											$wps_wpr_email_discription = str_replace( '[RETURNPOINT]', $fee_to_point, $wps_wpr_email_discription );
											$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $total_return_points, $wps_wpr_email_discription );
											$user = get_user_by( 'email', $user_email );
											$user_name = $user->user_firstname;
											$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
											/*check is mail notification is enable or not*/
											$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'return_product_pur_thr_points_notification' );
											if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {

												$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
												$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
											}
										}
									}
								}
							}
						}
					}

					// Refund of order total points min max.
					if ( $public_obj->check_enable_offer() ) {
						if ( 'completed' == $old_status && 'cancelled' == $new_status ) {
							$this->wps_refund_order_total_points_on_cancellation( $order_id );
						}
					}
				}
			}
		}
	}

	/**
	 * This function is used to refund order.
	 *
	 * @param int $order_id order id.
	 * @return void
	 */
	public function wps_refund_order_total_point( $order_id ) {
		$is_refunded = get_post_meta( $order_id, '$order_id#wps_point_on_order_total', true );
		if ( ! isset( $is_refunded ) || 'yes' !== $is_refunded ) {
			$public_obj = $this->generate_public_obj();
			$today_date = date_i18n( 'Y-m-d h:i:sa' );
			$wps_wpr_notificatin_array = get_option( 'wps_wpr_notificatin_array', true );
			/*Get the minimum order total value*/
			$thankyouorder_min = $public_obj->wps_wpr_get_order_total_settings( 'wps_wpr_thankyouorder_minimum' );
			/*Get the maxmimm order total value*/
			$thankyouorder_max = $public_obj->wps_wpr_get_order_total_settings( 'wps_wpr_thankyouorder_maximum' );
			/*Get the order points value that will assigned to the user*/
			$thankyouorder_value = $public_obj->wps_wpr_get_order_total_settings( 'wps_wpr_thankyouorder_current_type' );
			$order = wc_get_order( $order_id );
			/*Get the order total points*/
			$order_total = $order->get_total();
			// WOOCS - WooCommerce Currency Switcher Compatibility.
			$order_total = apply_filters( 'wps_wpr_convert_base_price_diffrent_currency', $order_total );
			$user_id = $order->get_user_id();
			$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
			$deduction_of_points = get_user_meta( $user_id, 'points_details', true );
			$deduction_of_points = ! empty( $deduction_of_points ) && is_array( $deduction_of_points ) ? $deduction_of_points : array();
			/*Get the user*/
			$user = get_user_by( 'ID', $user_id );
			/*Get the user email*/
			$user_email = $user->user_email;
			$total_points = 0;

			if ( is_array( $thankyouorder_value ) && ! empty( $thankyouorder_value ) ) {
				foreach ( $thankyouorder_value as $key => $value ) {
					if (
						isset( $thankyouorder_min[ $key ] ) && ! empty( $thankyouorder_min[ $key ] ) && isset( $thankyouorder_max[ $key ] ) &&
						! empty( $thankyouorder_max[ $key ] )
					) {

						if (
							$thankyouorder_min[ $key ] <= $order_total &&
							$order_total <= $thankyouorder_max[ $key ]
						) {
							$wps_wpr_point = (int) $thankyouorder_value[ $key ];
							$total_points = $total_points + $wps_wpr_point;
						}
					} else if (
						isset( $thankyouorder_min[ $key ] ) &&
						! empty( $thankyouorder_min[ $key ] ) &&
						empty( $thankyouorder_max[ $key ] )
					) {
						if ( $thankyouorder_min[ $key ] <= $order_total ) {
							$wps_wpr_point = (int) $thankyouorder_value[ $key ];
							$total_points = $total_points + $wps_wpr_point;
						}
					}
				}
			}
			$deduct_currency_spent = $total_points;
			$remaining_points = $get_points - $deduct_currency_spent;
			if ( isset( $deduction_of_points['refund_points_on_order'] ) && ! empty( $deduction_of_points['refund_points_on_order'] ) ) {
				$currency_arr = array();
				$currency_arr = array(
					'refund_points_on_order' => $deduct_currency_spent,
					'date' => $today_date,
				);
				$deduction_of_points['refund_points_on_order'][] = $currency_arr;
			} else {
				$currency_arr = array();
				$currency_arr = array(
					'refund_points_on_order' => $deduct_currency_spent,
					'date' => $today_date,
				);
				$deduction_of_points['refund_points_on_order'][] = $currency_arr;
			}
			update_user_meta( $user_id, 'wps_wpr_points', $remaining_points );
			update_user_meta( $user_id, 'points_details', $deduction_of_points );
			update_post_meta( $order_id, '$order_id#wps_point_on_order_total', 'yes' );
			if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

				$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] : '';
				$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] : '';
				$wps_wpr_email_discription = str_replace( '[DEDUCTEDPOINT]', $deduct_currency_spent, $wps_wpr_email_discription );
				$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $remaining_points, $wps_wpr_email_discription );
				$user = get_user_by( 'email', $user_email );
				$user_name = $user->user_firstname;
				$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
				/*check is mail notification is enable or not*/
				$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'deduct_assign_points_notification' );
				if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
					$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
					$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
				}
			}
		}
	}

	/**
	 * This function is used for refund points.
	 *
	 * @name wps_refund_order_total_points_on_cancellation
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param int $order_id order_id.
	 */
	public function wps_refund_order_total_points_on_cancellation( $order_id ) {
		$is_cancelled = get_post_meta( $order_id, '$order_id#wps_cancel_order_total_points', true );
		if ( ! isset( $is_cancelled ) || 'yes' != $is_cancelled ) {
			$public_obj = $this->generate_public_obj();
			$today_date = date_i18n( 'Y-m-d h:i:sa' );
			$wps_wpr_notificatin_array = get_option( 'wps_wpr_notificatin_array', true );
			/*Get the minimum order total value*/
			$thankyouorder_min = $public_obj->wps_wpr_get_order_total_settings( 'wps_wpr_thankyouorder_minimum' );
			/*Get the maxmimm order total value*/
			$thankyouorder_max = $public_obj->wps_wpr_get_order_total_settings( 'wps_wpr_thankyouorder_maximum' );
			/*Get the order points value that will assigned to the user*/
			$thankyouorder_value = $public_obj->wps_wpr_get_order_total_settings( 'wps_wpr_thankyouorder_current_type' );
			$order = wc_get_order( $order_id );
			/*Get the order total points*/
			$order_total = $order->get_total();
			// WOOCS - WooCommerce Currency Switcher Compatibility.
			$order_total = apply_filters( 'wps_wpr_convert_base_price_diffrent_currency', $order_total );
			$user_id = $order->get_user_id();
			$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
			$deduction_of_points = get_user_meta( $user_id, 'points_details', true );
			/*Get the user*/
			$user = get_user_by( 'ID', $user_id );
			/*Get the user email*/
			$user_email = $user->user_email;
			$total_points = 0;

			if ( is_array( $thankyouorder_value ) && ! empty( $thankyouorder_value ) ) {
				foreach ( $thankyouorder_value as $key => $value ) {
					if (
						isset( $thankyouorder_min[ $key ] ) && ! empty( $thankyouorder_min[ $key ] ) && isset( $thankyouorder_max[ $key ] ) &&
						! empty( $thankyouorder_max[ $key ] )
					) {

						if (
							$thankyouorder_min[ $key ] <= $order_total &&
							$order_total <= $thankyouorder_max[ $key ]
						) {
							$wps_wpr_point = (int) $thankyouorder_value[ $key ];
							$total_points = $total_points + $wps_wpr_point;
						}
					} else if (
						isset( $thankyouorder_min[ $key ] ) &&
						! empty( $thankyouorder_min[ $key ] ) &&
						empty( $thankyouorder_max[ $key ] )
					) {
						if ( $thankyouorder_min[ $key ] <= $order_total ) {
							$wps_wpr_point = (int) $thankyouorder_value[ $key ];
							$total_points = $total_points + $wps_wpr_point;
						}
					}
				}
			}
			$deduct_currency_spent = $total_points;
			$remaining_points = $get_points - $deduct_currency_spent;
			if ( isset( $deduction_of_points['cancel_points_on_order_total'] ) && ! empty( $deduction_of_points['cancel_points_on_order_total'] ) ) {
				$currency_arr = array();
				$currency_arr = array(
					'cancel_points_on_order_total' => $deduct_currency_spent,
					'date' => $today_date,
				);
				$deduction_of_points['cancel_points_on_order_total'][] = $currency_arr;
			} else {
				$currency_arr = array();
				$currency_arr = array(
					'cancel_points_on_order_total' => $deduct_currency_spent,
					'date' => $today_date,
				);
				$deduction_of_points['cancel_points_on_order_total'][] = $currency_arr;
			}
			update_user_meta( $user_id, 'wps_wpr_points', $remaining_points );
			update_user_meta( $user_id, 'points_details', $deduction_of_points );
			update_post_meta( $order_id, '$order_id#wps_cancel_order_total_points', 'yes' );
			if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

				$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_subject'] : '';
				$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] ) ? $wps_wpr_notificatin_array['wps_wpr_deduct_assigned_point_desciption'] : '';
				$wps_wpr_email_discription = str_replace( '[DEDUCTEDPOINT]', $deduct_currency_spent, $wps_wpr_email_discription );
				$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $remaining_points, $wps_wpr_email_discription );
				$user = get_user_by( 'email', $user_email );
				$user_name = $user->user_firstname;
				$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
				/*check is mail notification is enable or not*/
				$check_enable = apply_filters( 'wps_wpr_check_custom_points_notification_enable', true, 'deduct_assign_points_notification' );
				if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() && $check_enable ) {
					$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
					$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
				}
			}
		}
	}

	/**
	 * This function is used to show products points for variable product.
	 *
	 * @name wps_woocommerce_variable_price_html
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param int    $val_price val_price.
	 * @param object $product product.
	 */
	public function wps_woocommerce_variable_price_html( $val_price, $product ) {
		$variations = $product->get_available_variations();
		$var_data   = array();
		$points     = esc_html__( 'Points', 'ultimate-woocommerce-points-and-rewards' );
		if ( ! empty( $variations ) ) {
			foreach ( $variations as $key => $variation ) {
				foreach ( $variation as $key => $variation_id ) {
					if ( 'variation_id' == $key ) {
						$wps_wpr_parent_id              = wp_get_post_parent_id( $variation_id );
						$enable_product_purchase_points = get_post_meta( $wps_wpr_parent_id, 'wps_product_purchase_points_only', true );
						$wps_product_purchase_value     = get_post_meta( $variation_id, 'wps_wpr_variable_points_purchase', true );
						if ( isset( $enable_product_purchase_points ) && 'yes' == $enable_product_purchase_points ) {

							if ( isset( $wps_product_purchase_value ) && ! empty( $wps_product_purchase_value ) ) {
								$var_data[] = $wps_product_purchase_value;
							}
						}
					}
				}
			}
		}

		if ( isset( $var_data ) && ! empty( $var_data ) ) {
			$min_value = min( $var_data );
			$max_value = max( $var_data );
		}

		if ( isset( $min_value ) && isset( $max_value ) && ! empty( $max_value ) && ! empty( $min_value ) ) {
			if ( $min_value == $max_value ) {

				$val_price = '<p class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"></span>' . esc_html( $min_value ) . '</span><span> ' . esc_html( $points ) . '</span></p>';
				return $val_price;
			} else {
				$val_price = '<p class="price"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"></span>' . esc_html( $min_value ) . '</span><span> ' . esc_html( $points ) . '</span> – <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol"></span>' . esc_html( $max_value ) . '</span><span> ' . esc_html( $points ) . '</span></p>';
				return $val_price;
			}
		} else {
			return $val_price;
		}
	}

	/**
	 * This function is used for allowed user for points tabs.
	 *
	 * @name wps_wpr_allowed_user_roles_points_callback
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param array $items items.
	 */
	public function wps_wpr_allowed_user_roles_points_callback( $items ) {
		$user_ID = get_current_user_ID();
		$user = new WP_User( $user_ID );
		$user_role = isset( $user->roles[0] ) ? $user->roles[0] : '';

		$allowed_user_role = $this->wps_rwpr_is_allowed_user_role();
		if ( isset( $allowed_user_role ) && ! empty( $allowed_user_role ) ) {
			if ( ! in_array( $user_role, $allowed_user_role ) ) {
				unset( $items['points'] );
			}
		}
		return $items;
	}

	/**
	 * This function is used to get allowed user.
	 *
	 * @name wps_rwpr_is_allowed_user_role
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_rwpr_is_allowed_user_role() {

		$wps_wpr_allowed_user = '';
		$general_settings     = get_option( 'wps_wpr_settings_gallery', true );
		if ( isset( $general_settings['wps_wpr_allowed_selected_user_role'] ) ) {
			$wps_wpr_allowed_user = $general_settings['wps_wpr_allowed_selected_user_role'];
		}
		return $wps_wpr_allowed_user;
	}

	/**
	 * This function is used for validate allowed user.
	 *
	 * @name wps_wpr_allowed_user_roles_points_features_callback
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param bool $validate validate.
	 */
	public function wps_wpr_allowed_user_roles_points_features_callback( $validate ) {
		$user_ID           = get_current_user_ID();
		$user              = new WP_User( $user_ID );
		$user_role         = isset( $user->roles[0] ) ? $user->roles[0] : '';
		$allowed_user_role = $this->wps_rwpr_is_allowed_user_role();
		if ( isset( $allowed_user_role ) && ! empty( $allowed_user_role ) ) {
			if ( ! in_array( $user_role, $allowed_user_role ) ) {
				$validate = true;
			}
		}
		return $validate;
	}

	/**
	 * This function is used for validate allowed user for order.
	 *
	 * @name wps_wpr_allowed_user_roles_points_features_order_callback
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param bool   $validate validate.
	 * @param object $user user.
	 */
	public function wps_wpr_allowed_user_roles_points_features_order_callback( $validate, $user ) {
		if ( isset( $user ) && ! empty( $user ) ) {
			$user_role = $user->roles[0];
			$allowed_user_role = $this->wps_rwpr_is_allowed_user_role();
			if ( isset( $allowed_user_role ) && ! empty( $allowed_user_role ) ) {
				if ( ! in_array( $user_role, $allowed_user_role ) ) {
					$validate = true;
				}
			}
		}
		return $validate;
	}

	/**
	 * This function is used to create shortcode for referral link.
	 *
	 * @name wps_wpr_referral_link_shortcode
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */
	public function wps_wpr_referral_link_shortcode() {

		if ( $this->wps_wpr_check_referal_enable() ) {
			add_shortcode( 'WPS_REFERRAL_LINK', array( $this, 'wps_wpr_referral_link_shortcode_callback' ) );
		}
		if ( $this->wps_wpr_check_enabled_notification_addon() && $this->wps_wpr_check_notification_shortcode_enable() ) {
			add_shortcode( 'wps_wpr_notification_button', array( $this, 'wps_wpr_notification_button_shortcode' ) );
		}
	}

	/**
	 * This function is used to create shortcode for referral link.
	 *
	 * @name wps_wpr_referral_link_shortcode_callback
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 */// Check.
	public function wps_wpr_referral_link_shortcode_callback() {
		if ( is_user_logged_in() ) {
			$user_id = get_current_user_id();
			$get_referral = get_user_meta( $user_id, 'wps_points_referral', true );
			if ( isset( $get_referral ) && ! empty( $get_referral ) ) {
				ob_start();
				$general_settings      = get_option( 'wps_wpr_settings_gallery', true );
				$wps_wpr_referral_page = ! empty( $general_settings['wps_wpr_referral_page'] ) ? $general_settings['wps_wpr_referral_page'] : '';
				$wps_wpr_page_url      = '';
				if ( ! empty( $wps_wpr_referral_page ) ) {
					$wps_wpr_page_url = get_page_link( $wps_wpr_referral_page[0] );
				} else {
					$wps_wpr_page_url = site_url();
				}
				$site_url = apply_filters( 'wps_wpr_referral_link_url', $wps_wpr_page_url );
				?>
			<p calss="wps_wpr_referal_link_shortcode"><code><?php echo esc_url( $site_url . '?pkey=' . $get_referral ); ?></code></p>
				<?php
			}
		}
		return ob_get_clean();
	}

	/**
	 * Check the enable referal settings.
	 *
	 * @since    1.0.0
	 */
	public function wps_wpr_check_referal_enable() {
		$is_enable        = false;
		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		$enable_wps_refer = isset( $general_settings['wps_wpr_general_refer_enable'] ) ? intval( $general_settings['wps_wpr_general_refer_enable'] ) : 0;
		if ( $enable_wps_refer ) {
			$is_enable = true;
		}
		return $is_enable;
	}

	/**
	 * Check the enable points and rewards settings.
	 *
	 * @since    1.0.0
	 */
	public function wps_wpr_general_setting_enable() {
		$is_enable        = false;
		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		$enable_wps_refer = isset( $general_settings['wps_wpr_general_setting_enable'] ) ? intval( $general_settings['wps_wpr_general_setting_enable'] ) : 0;
		if ( $enable_wps_refer ) {
			$is_enable = true;
		}
		return $is_enable;
	}

	/**
	 * Check the enable signup.
	 *
	 * @since    1.0.0
	 */
	public function wps_wpr_check_signup_enable() {
		$is_enable        = false;
		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		$enable_wps_refer = isset( $general_settings['wps_wpr_general_signup'] ) ? intval( $general_settings['wps_wpr_general_signup'] ) : 0;
		if ( $enable_wps_refer ) {
			$is_enable = true;
		}
		return $is_enable;
	}

	/**
	 * Get sigup value.
	 *
	 * @since    1.0.0
	 */
	public function wps_wpr_get_signup_value() {

		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		$get_signup_value = isset( $general_settings['wps_wpr_general_signup_value'] ) ? intval( $general_settings['wps_wpr_general_signup_value'] ) : 1;
		return $get_signup_value;
	}


	/**
	 * Check whether the Order Conversion Feature is enable or not
	 *
	 * @name wps_wpr_is_order_conversion_enabled()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_is_order_conversion_enabled() {
		$enable                     = false;
		$coupon_settings_array      = get_option( 'wps_wpr_coupons_gallery', array() );
		$is_order_conversion_enable = isset( $coupon_settings_array['wps_wpr_coupon_conversion_enable'] ) ? $coupon_settings_array['wps_wpr_coupon_conversion_enable'] : 0;
		if ( $is_order_conversion_enable ) {
			$enable = true;
		}
		return $enable;
	}

	/**
	 * Return the conversion Rate for Per Currency Spent Feature
	 *
	 * @name wps_wpr_order_conversion_rate()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_order_conversion_rate() {
		$coupon_settings_array        = get_option( 'wps_wpr_coupons_gallery', array() );
		$order_conversion_rate_value  = isset( $coupon_settings_array['wps_wpr_coupon_conversion_price'] ) ? $coupon_settings_array['wps_wpr_coupon_conversion_price'] : 1;
		$order_conversion_rate_points = isset( $coupon_settings_array['wps_wpr_coupon_conversion_points'] ) ? $coupon_settings_array['wps_wpr_coupon_conversion_points'] : 1;
		$order_conversion_rate        = array(
			'Value' => $order_conversion_rate_value,
			'Points' => $order_conversion_rate_points,
		);
		return $order_conversion_rate;
	}

	/**
	 * Return the value having the currency sign
	 *
	 * @name wps_wpr_value_return_in_currency()
	 * @param int $value value return currency.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_value_return_in_currency( $value ) {
		if ( empty( $value ) ) {
			$value = 0;
		}
		return wc_price( $value );
	}

	/**
	 * Check the Review Reward is enabled or not
	 *
	 * @name wps_wpr_is_review_reward_enabled()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_is_review_reward_enabled() {
		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		$enable           = false;
		if ( isset( $general_settings['wps_wpr_general_comment_enable'] ) && 1 == $general_settings['wps_wpr_general_comment_enable'] ) {
			$enable = true;
		}
		return $enable;
	}

	/**
	 * Limit the comment per user per post
	 *
	 * @name wps_wpr_comment_limitation()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_comment_limitation() {
		$general_settings      = get_option( 'wps_wpr_settings_gallery', true );
		$wps_wpr_comment_limit = isset( $general_settings['wps_wpr_general_comment_per_post_comment'] ) ? intval( $general_settings['wps_wpr_general_comment_per_post_comment'] ) : 1;
		return $wps_wpr_comment_limit;
	}

	/**
	 * Return the Reward Point for Review
	 *
	 * @name wps_wpr_get_reward_review()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_get_reward_review() {
		$general_settings      = get_option( 'wps_wpr_settings_gallery', true );
		$wps_wpr_comment_value = isset( $general_settings['wps_wpr_general_comment_value'] ) ? intval( $general_settings['wps_wpr_general_comment_value'] ) : 1;
		return $wps_wpr_comment_value;
	}

	/**
	 * Return the Referral Link of User
	 *
	 * @name wps_wpr_get_referral_link()
	 * @param int $user_id of the user.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_get_referral_link( $user_id ) {

		$get_referral        = get_user_meta( $user_id, 'wps_points_referral', true );
		$get_referral_invite = get_user_meta( $user_id, 'wps_points_referral_invite', true );
		if ( empty( $get_referral ) && empty( $get_referral_invite ) ) {

			$referral_key = wps_wpr_create_referral_code();
			$referral_invite = 0;
			update_user_meta( $user_id, 'wps_points_referral', $referral_key );
			update_user_meta( $user_id, 'wps_points_referral_invite', $referral_invite );
		}
		$referral_link = get_user_meta( $user_id, 'wps_points_referral', true );
		return $referral_link;
	}

	/**
	 * Check whether the Product Purchase using Points feature is enabled or not
	 *
	 * @name wps_wpr_is_purchase_product_using_points_enabled()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_is_purchase_product_using_points_enabled() {
		$enable                 = false;
		$general_settings       = get_option( 'wps_wpr_product_purchase_settings', true );
		$enable_purchase_points = isset( $general_settings['wps_wpr_product_purchase_points'] ) ? intval( $general_settings['wps_wpr_product_purchase_points'] ) : 0;
		if ( $enable_purchase_points ) {
			$enable = true;
		}
		return $enable;
	}

	/**
	 * Returns the Conversion rate of  Product Purchase using Points
	 *
	 * @name wps_wpr_purchase_product_using_pnt_rate()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_purchase_product_using_pnt_rate() {
		$general_settings                   = get_option( 'wps_wpr_product_purchase_settings', true );
		$wps_wpr_purchase_points            = ( isset( $general_settings['wps_wpr_purchase_points'] ) && null != $general_settings['wps_wpr_purchase_points'] ) ? $general_settings['wps_wpr_purchase_points'] : 1;
		$wps_wpr_product_purchase_price     = ( isset( $general_settings['wps_wpr_product_purchase_price'] ) && null != $general_settings['wps_wpr_product_purchase_price'] ) ? intval( $general_settings['wps_wpr_product_purchase_price'] ) : 1;
		$product_purchasing_conversion_rate = array(
			'Currency' => $wps_wpr_product_purchase_price,
			'Points' => $wps_wpr_purchase_points,
		);
		return $product_purchasing_conversion_rate;
	}

	/**
	 * Returns the Conversion rate of any feature in proper format
	 *
	 * @name wps_wpr_return_conversion_rate()
	 * @param int $points of the database array.
	 * @param int $currency of the database array.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_return_conversion_rate( $points, $currency ) {
		$point    = ( isset( $points ) && null != $points ) ? $points : 1;
		$currency = ( isset( $currency ) && null != $currency ) ? $currency : 1;
		$currency = $this->wps_wpr_value_return_in_currency( $currency );
		return $currency . ' = ' . $point . __( ' Point', 'ultimate-woocommerce-points-and-rewards' );
	}

	/**
	 * Check whether the Apply Point on Cart is enabled or not
	 *
	 * @name wps_wpr_is_apply_point_on_cart_enabled()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_is_apply_point_on_cart_enabled() {
		$enable = false;
		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		if ( isset( $general_settings['wps_wpr_custom_points_on_cart'] ) && 1 == $general_settings['wps_wpr_custom_points_on_cart'] ) {
			$enable = true;
		}
		return $enable;
	}

	/**
	 * Returns the Conversion rate of Apply Point on Cart
	 *
	 * @name wps_wpr_apply_point_on_cart_rate()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_apply_point_on_cart_rate() {
		$general_settings         = get_option( 'wps_wpr_settings_gallery', true );
		$wps_wpr_cart_points_rate = ( isset( $general_settings['wps_wpr_cart_points_rate'] ) && null != $general_settings['wps_wpr_cart_points_rate'] ) ? $general_settings['wps_wpr_cart_points_rate'] : 1;
		$wps_wpr_cart_price_rate  = ( isset( $general_settings['wps_wpr_cart_price_rate'] ) && null != $general_settings['wps_wpr_cart_price_rate'] ) ? intval( $general_settings['wps_wpr_cart_price_rate'] ) : 1;
		$point_on_cart_rate       = array(
			'Currency' => $wps_wpr_cart_price_rate,
			'Points' => $wps_wpr_cart_points_rate,
		);
		return $point_on_cart_rate;
	}

	/**
	 * Check whether the Convert Points into Coupon is enabled or not
	 *
	 * @name wps_wpr_is_convert_points_to_coupon_enabled()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_is_convert_points_to_coupon_enabled() {
		$enable                     = false;
		$coupon_settings_array      = get_option( 'wps_wpr_coupons_gallery', array() );
		$is_enable_coupon_generator = isset( $coupon_settings_array['wps_wpr_enable_coupon_generation'] ) ? $coupon_settings_array['wps_wpr_enable_coupon_generation'] : 0;
		if ( $is_enable_coupon_generator ) {
			$enable = true;
		}
		return $enable;
	}

	/**
	 * Returns the Conversion rate of Convert Points into Coupons
	 *
	 * @name wps_wpr_convert_points_to_coupons_rate()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_convert_points_to_coupons_rate() {
		$coupon_settings                = get_option( 'wps_wpr_coupons_gallery', array() );
		$coupon_redeem_price            = ( isset( $coupon_settings['wps_wpr_coupon_redeem_price'] ) && null != $coupon_settings['wps_wpr_coupon_redeem_price'] ) ? $coupon_settings['wps_wpr_coupon_redeem_price'] : 1;
		$coupon_redeem_points           = ( isset( $coupon_settings['wps_wpr_coupon_redeem_points'] ) && null != $coupon_settings['wps_wpr_coupon_redeem_points'] ) ? intval( $coupon_settings['wps_wpr_coupon_redeem_points'] ) : 1;
		$convert_points_to_coupons_rate = array(
			'Currency' => $coupon_redeem_price,
			'Points' => $coupon_redeem_points,
		);
		return $convert_points_to_coupons_rate;
	}

	/**
	 * Check whether the Referral Purchase Point is enabled or not
	 *
	 * @name wps_wpr_is_referral_purchase_enabled()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_is_referral_purchase_enabled() {
		$enable                       = false;
		$general_settings             = get_option( 'wps_wpr_settings_gallery', true );
		$wps_referral_purchase_enable = isset( $general_settings['wps_wpr_general_referal_purchase_enable'] ) ? intval( $general_settings['wps_wpr_general_referal_purchase_enable'] ) : 0;
		if ( $wps_referral_purchase_enable ) {
			$enable = true;
		}
		return $enable;
	}

	/**
	 * Returns the referral purchase amount
	 *
	 * @name wps_wpr_get_referral_purchase_reward()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_get_referral_purchase_reward() {
		$general_settings            = get_option( 'wps_wpr_settings_gallery', true );
		$wps_referral_purchase_value = isset( $general_settings['wps_wpr_general_referal_purchase_value'] ) ? intval( $general_settings['wps_wpr_general_referal_purchase_value'] ) : 1;
		return $wps_referral_purchase_value;
	}

	/**
	 * Returns the referral purchase value.
	 *
	 * @name wps_wpr_get_referral_reward()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_get_referral_reward() {
		$general_settings            = get_option( 'wps_wpr_settings_gallery', true );
		$wps_referral_purchase_value = isset( $general_settings['wps_wpr_general_refer_value'] ) ? intval( $general_settings['wps_wpr_general_refer_value'] ) : 1;
		return $wps_referral_purchase_value;
	}

	/**
	 * Check whether the Only Referral Purchase Point Feature is enable or not
	 *
	 * @name wps_wpr_is_only_referral_purchase_enabled()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_is_only_referral_purchase_enabled() {
		$enable                 = false;
		$general_settings       = get_option( 'wps_wpr_settings_gallery', true );
		$only_referral_purchase = isset( $general_settings['wps_wpr_general_refer_value_disable'] ) ? intval( $general_settings['wps_wpr_general_refer_value_disable'] ) : 0;
		if ( $only_referral_purchase ) {
			$enable = true;
		}
		return $enable;
	}


		/**
		 * Check whether the notification addon is enable.
		 *
		 * @name wps_wpr_check_enabled_notification_addon()
		 * @author makewebbetter<webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
	public function wps_wpr_check_enabled_notification_addon() {
		$enable = false;
		$general_settings = get_option( 'wps_wpr_notification_addon_settings', true );
		$enable_notification_addon = isset( $general_settings['wps_wpr_enable_notification_addon'] ) ? intval( $general_settings['wps_wpr_enable_notification_addon'] ) : 0;
		if ( $enable_notification_addon ) {
			$enable = true;
		}
		return $enable;
	}

	/**
	 * Notification addon button color.
	 *
	 * @name wps_wpr_notification_addon_get_color()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_notification_addon_get_color() {

		$general_settings = get_option( 'wps_wpr_notification_addon_settings', true );
		$get_notification_addon = isset( $general_settings['wps_wpr_notification_color'] ) ? $general_settings['wps_wpr_notification_color'] : '#ff0000';
		return $get_notification_addon;
	}

	/**
	 * Notification addon button position.
	 *
	 * @name wps_wpr_enable_notification_addon_button_position()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_enable_notification_addon_button_position() {
		$class = '';
		$general_settings = get_option( 'wps_wpr_notification_addon_settings', true );
		$get_notification_addon = isset( $general_settings['wps_wpr_enable_notification_addon_button_position'] ) ? $general_settings['wps_wpr_enable_notification_addon_button_position'] : 'right_bottom';
		if ( ! empty( $get_notification_addon ) && 'left_bottom' == $get_notification_addon ) {
			$class = 'wps_wpr_btn_left_bottom';
		}
		if ( ! empty( $get_notification_addon ) && 'right_bottom' == $get_notification_addon ) {
			$class = 'wps_wpr_btn_right_bottom';
		}
		if ( ! empty( $get_notification_addon ) && 'top_left' == $get_notification_addon ) {
			$class = 'wps_wpr_btn_top_left';
		}
		if ( ! empty( $get_notification_addon ) && 'top_right' == $get_notification_addon ) {
			$class = 'wps_wpr_btn_top_right';
		}
		return $class;
	}

	/**
	 * Check notification addon shortcode enable.
	 *
	 * @name wps_wpr_check_notification_shortcode_enable()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_notification_shortcode_enable() {
		$enable = false;
		$general_settings = get_option( 'wps_wpr_notification_addon_settings', true );
		$check_shortcode = isset( $general_settings['wps_wpr_enable_notification_addon_button_position'] ) ? $general_settings['wps_wpr_enable_notification_addon_button_position'] : 'right_bottom';
		if ( 'shortcode' == $check_shortcode ) {
			$enable = true;
		}
		return $enable;
	}

	/**
	 * Notification addon buton text.
	 *
	 * @name wps_wpr_notification_button_text()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_notification_button_text() {
		$general_settings = get_option( 'wps_wpr_notification_addon_settings', true );
		$get_notification_addon_btn_text = isset( $general_settings['wps_wpr_notification_button_text'] ) ? $general_settings['wps_wpr_notification_button_text'] : __( 'Notify Me', 'ultimate-woocommerce-points-and-rewards' );
		return $get_notification_addon_btn_text;
	}

	/**
	 * Get selected page.
	 *
	 * @name wps_wpr_notification_button_selected_page()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_notification_button_selected_page() {
		$general_settings = get_option( 'wps_wpr_notification_addon_settings', true );
		$wpr_selected_page = ! empty( $general_settings['wps_wpr_notification_button_page'] ) ? $general_settings['wps_wpr_notification_button_page'] : '';
		return $wpr_selected_page;
	}

	/**
	 * Load popup html.
	 *
	 * @name wps_wpr_notify_user_load_popup_html()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_notify_user_load_popup_html() {
		if ( is_user_logged_in() && apply_filters( 'wps_wpr_allowed_user_roles_points_features', false ) ) {
			return;
		}

		if ( $this->wps_wpr_check_enabled_notification_addon() ) {

			if ( $this->wps_wpr_check_seected_page() ) {
				$this->wps_wpr_notification_button_html();
			}
			if ( $this->wps_wpr_check_seected_page() || $this->wps_wpr_check_notification_shortcode_enable() ) {
				include_once POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . '/modal/notify-user-popup.php';
			}
		}
	}

	/**
	 * Check selected page.
	 *
	 * @name wps_wpr_check_seected_page()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_check_seected_page() {
		global $wp_query;
		$is_selected        = false;
		$wps_selected_pages = array();
		$wps_selected_pages = $this->wps_wpr_notification_button_selected_page();

		if ( empty( $wps_selected_pages ) && ! $this->wps_wpr_check_notification_shortcode_enable() ) {
			$is_selected = true;
		} elseif ( is_single() && ! $this->wps_wpr_check_notification_shortcode_enable() && ! empty( $wps_selected_pages ) ) {

			$page_id = 'details';
			if ( in_array( $page_id, $wps_selected_pages ) ) {

				$is_selected = true;
			}
		} elseif ( ! is_shop() && ! is_home() && ! empty( $wps_selected_pages ) && ! $this->wps_wpr_check_notification_shortcode_enable() ) {

			$page = $wp_query->get_queried_object();
			$page_id = isset( $page->ID ) ? $page->ID : '';

			if ( in_array( $page_id, $wps_selected_pages ) ) {
				$is_selected = true;
			}
		} elseif ( is_shop() && ! $this->wps_wpr_check_notification_shortcode_enable() && ! empty( $wps_selected_pages ) ) {
			$page_id = wc_get_page_id( 'shop' );
			if ( in_array( $page_id, $wps_selected_pages ) ) {
				$is_selected = true;
			}
		} else {
			$is_selected = false;
		}
		return $is_selected;
	}

	/**
	 * Notification button html.
	 *
	 * @name wps_wpr_notification_button_html()
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_notification_button_html() {
		$button_text = $this->wps_wpr_notification_button_text();
		$position_class = $this->wps_wpr_enable_notification_addon_button_position();
		?>
		<a href="javascript:;" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored modal__trigger wps-pr-drag-btn <?php echo esc_html( $position_class ); ?>" style="background-color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?> " data-modal="#modal" id="wps-wps-pr-drag"><?php echo esc_html( $button_text ); ?></a>
		<?php
	}

	/**
	 * Notification button html shortcode.
	 *
	 * @name wps_wpr_notification_button_shortcode()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_notification_button_shortcode() {
		$button_text = $this->wps_wpr_notification_button_text();
		ob_start();
		?>
		<a href="javascript:;" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored modal__trigger wps-pr-drag-btn" style="background-color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?> " data-modal="#modal" id="wps-wpr-shortcode-pr-drag"><?php echo esc_html( $button_text ); ?></a>
		<?php
		return ob_get_clean();
	}

	/**
	 * Signup points for allowd user's.
	 *
	 * @name wps_wpr_allowed_user_roles_points_callback_signup()
	 * @param bool $validate reture validate value.
	 * @param int  $user_id user id.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_allowed_user_roles_points_callback_signup( $validate, $user_id ) {
		$user      = new WP_User( $user_id );
		$user_role = isset( $user->roles[0] ) ? $user->roles[0] : '';

		$allowed_user_role = $this->wps_rwpr_is_allowed_user_role();
		if ( isset( $allowed_user_role ) && ! empty( $allowed_user_role ) ) {
			if ( ! in_array( $user_role, $allowed_user_role ) ) {
				$validate = true;
			}
		}
		return $validate;
	}

	/**
	 * Tax calculation for products points.
	 *
	 * @name wps_wpr_fee_tax_calculation()
	 * @param array  $fee_taxes Tax.
	 * @param object $fee fee.
	 * @param object $object fee object.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_fee_tax_calculation_on_product_purchase_points( $fee_taxes, $fee, $object ) {
		$points_discount = __( 'Point Discount', 'ultimate-woocommerce-points-and-rewards' );
		if ( $points_discount == $fee->object->name ) {
			foreach ( $fee_taxes as $key => $value ) {
				$fee_taxes[ $key ] = 0;
			}
		}
		return $fee_taxes;
	}

	/**
	 * Per currency spent on subtotal.
	 *
	 * @since    1.2.0
	 * @name wps_wpr_per_currency_points_on_subtotal_callback()
	 * @param int    $order_total order_total.
	 * @param object $order order.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_per_currency_points_on_subtotal_callback( $order_total, $order ) {
		if ( isset( $order ) && ! empty( $order ) ) {
			$public_obj = $this->generate_public_obj();
			$wps_wpr_points_on_subtotal = $public_obj->wps_wpr_get_coupon_settings_num( 'wps_wpr_per_cerrency_points_on_order_subtotal' );

			if ( 1 == $wps_wpr_points_on_subtotal ) {
				$order_total = $order->get_subtotal();
			}
		}
		return $order_total;
	}

	/**
	 * This is used to create html for membership expiry.
	 *
	 * @since    1.2.0
	 * @name wps_wpr_membership_expiry_for_user_html_callback()
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_membership_expiry_for_user_html_callback() {
		?>
		<th class="wps-wpr-points-expiry">
			<span class="wps_wpr_nobr"><?php echo esc_html__( 'Membership Expiry', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
		</th>
		<?php
	}

	/**
	 * This is used to show membership expiry.
	 *
	 * @since    1.2.0
	 * @name wps_wpr_membership_expiry_date_for_user_callback()
	 * @param int    $user_id of the database array.
	 * @param array  $values of the database array.
	 * @param string $wps_role of the database array.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_membership_expiry_date_for_user_callback( $user_id, $values, $wps_role ) {

		$user_membership_expiry = get_user_meta( $user_id, 'membership_expiration', true );
		$wps_user_level = get_user_meta( $user_id, 'membership_level', true );

		if ( $wps_role == $wps_user_level ) {
			if ( isset( $user_membership_expiry ) && ! empty( $user_membership_expiry ) ) {
				echo esc_html( $this->wps_wpr_show_the_wordpress_date_format( $user_membership_expiry ) );
			}
		} else {
			echo esc_html( $values['Exp_Number'] ) . ' ' . esc_html( 'year' );
		}

	}

	/**
	 * This function is used to return the date format as per WP settings
	 *
	 * @name wps_wpr_show_the_wordpress_date_format
	 * @since 1.2.0
	 * @param string $saved_date saved data in the wordpress formet.
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_show_the_wordpress_date_format( $saved_date ) {
		$saved_date  = strtotime( $saved_date );
		$date_format = get_option( 'date_format', 'Y-m-d' );
		$wp_date     = date_i18n( $date_format, $saved_date );
		$return_date = $wp_date;
		return $return_date;
	}

	/**
	 * This function is used for giving points on first order .
	 *
	 * @name wps_wpr_woocommerce_order_status_completed
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param int    $order_id order_id.
	 * @param string $old_status old_status.
	 * @param string $new_status new_status.
	 */
	public function wps_wpr_woocommerce_order_status_compl( $order_id, $old_status, $new_status ) {
		if ( $old_status != $new_status ) {

			$order      = wc_get_order( $order_id );
			$user_id    = $order->get_user_id();
			$value      = get_user_meta( $user_id, 'points_on_first_order', true );
			if ( ! empty( $value ) ) {
				return;
			} else {
				$customer_orders = get_posts(
					array(
						'meta_key'    => '_customer_user',
						'meta_value'  => $user_id,
						'post_type'   => 'shop_order',
						'post_status' => array_keys( wc_get_order_statuses() ),
						'numberposts' => -1,
					)
				);
				$first_order_status = $customer_orders[0]->post_status;

				if ( is_array( $customer_orders ) && ! empty( $customer_orders ) && 'wc-completed' == $first_order_status ) {
					$my_array = get_option( 'wps_wpr_settings_gallery' );

					if ( empty( $value ) && ( '1' == $my_array['wps_wpr_general_setting_enablee'] ) ) {
							$usr_points = get_user_meta( $user_id, 'wps_wpr_points', true );
							$usr_points = ! empty( $usr_points ) ? $usr_points : 0;
							$myypoints = $my_array['wps_wpr_general_first_value'];
							$usr_points = $usr_points + $myypoints;
							update_user_meta( $user_id, 'wps_wpr_points', $usr_points );
							update_user_meta( $user_id, 'points_on_first_order', $myypoints );
							$today_date = date_i18n( 'Y-m-d h:i:sa' );
							$points_on_first_order = get_user_meta( $user_id, 'points_on_first_order', true );
							$points_log = get_user_meta( $user_id, 'points_details', true );
							$points_log = ! empty( $points_log ) && is_array( $points_log ) ? $points_log : array();
						if ( isset( $points_log['points_on_first_order'] ) && ! empty( $points_log['points_on_first_order'] ) ) {

							$points_arr = array();
							$points_arr = array(
								'points_on_first_order' => $points_on_first_order,
								'date' => $today_date,
							);
							$points_log['points_on_first_order'][] = $points_arr;
						} else {
							if ( ! is_array( $points_log ) ) {
								$points_log = array();
							}
							$points_arr = array();
							$points_arr = array(
								'points_on_first_order' => $points_on_first_order,
								'date' => $today_date,
							);
							$points_log['points_on_first_order'][] = $points_arr;
						}
						update_user_meta( $user_id, 'points_details', $points_log );

						$first_order_points = get_user_meta( $user_id, 'points_on_first_order', true );

						$wps_wpr_notificatin_array = get_option( 'wps_wpr_notificatin_array', true );
						$user = get_user_by( 'ID', $user_id );

						$user_email = $user->user_email;
						$wps_wpr_point_on_first_order_point_setting_enable = ! empty( $wps_wpr_notificatin_array['wps_wpr_point_on_first_order_point_setting_enable'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_first_order_point_setting_enable'] : '';
						$wps_wpr_notification_setting_enable = ! empty( $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] ) ? $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] : '';
						if ( '1' == $wps_wpr_point_on_first_order_point_setting_enable && '1' == $wps_wpr_notification_setting_enable ) {
							if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

								$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_point_on_first_order_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_first_order_subject'] : '';
								$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_point_on_first_order_desc'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_first_order_desc'] : '';
								$wps_wpr_email_discription = str_replace( '[FIRSTORDERPOINT] ', $first_order_points, $wps_wpr_email_discription );
								$wps_wpr_email_discription = str_replace( '[Total Points].', $usr_points, $wps_wpr_email_discription );
								$user = get_user_by( 'email', $user_email );
								$user_name = $user->user_firstname;
								$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );

								if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() ) {

									$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
									$email_status = $customer_email->trigger( $user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
								}
							}
						} else {
							return;
						}
					}
				}
			}
		}
	}

	// My Custom Code Start.
	/**
	 *
	 * This function is used for giving points on first login per day .
	 *
	 * @name wps_wpr_daily_login_function
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link http://www.makewebbetter.com/
	 * @param string $user_login user login.
	 * @param object $user user.
	 */
	public function wps_wpr_daily_login_function( $user_login, $user ) {

		$my_array               = get_option( 'wps_wpr_settings_gallery' );
		$wps_check_login_enable = ! empty( $my_array['wps_wpr_general_setting_daily_enablee'] ) ? $my_array['wps_wpr_general_setting_daily_enablee'] : '';
		$check_daily_login      = ! empty( $_COOKIE['login_check_mark'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['login_check_mark'] ) ) : '';
		$login_amount_check     = get_user_meta( $user->ID, 'login_amount', true );

		// check daily login feature enable or not.
		if ( '1' == $wps_check_login_enable ) {
			// check if daily login enable and than user is login.
			if ( empty( $login_amount_check ) ) {

				update_user_meta( $user->ID, 'login_amount', 1 );
				setcookie( 'login_check_mark', 'done_first_login', time() + ( 86400 * 30 ), '/' ); // 86400 = 1 day.

				$usr_points = get_user_meta( $user->ID, 'wps_wpr_points', true );
				$usr_points = ! empty( $usr_points ) ? $usr_points : 0;
				$myypoints  = ! empty( $my_array['wps_wpr_general_daily_login_value'] ) ? $my_array['wps_wpr_general_daily_login_value'] : 0;
				$usr_points = $usr_points + $myypoints;
				update_user_meta( $user->ID, 'wps_wpr_points', $usr_points );
				update_user_meta( $user->ID, 'points_on_first_login_daily', $myypoints );

				$today_date                  = date_i18n( 'Y-m-d h:i:sa' );
				$points_on_first_login_daily = get_user_meta( $user->ID, 'points_on_first_login_daily', true );
				$points_log                  = get_user_meta( $user->ID, 'points_details', true );
				$points_log                  = ! empty( $points_log ) && is_array( $points_log ) ? $points_log : array();

				if ( isset( $points_log['points_on_first_login_daily'] ) && ! empty( $points_log['points_on_first_login_daily'] ) ) {

					$points_arr = array();
					$points_arr = array(
						'points_on_first_login_daily' => $points_on_first_login_daily,
						'date' => $today_date,
					);
					$points_log['points_on_first_login_daily'][] = $points_arr;
				} else {
					$points_arr = array();
					$points_arr = array(
						'points_on_first_login_daily' => $points_on_first_login_daily,
						'date' => $today_date,
					);
					$points_log['points_on_first_login_daily'][] = $points_arr;
				}
				update_user_meta( $user->ID, 'points_details', $points_log );

				$first_order_points = get_user_meta( $user->ID, 'points_on_first_login_daily', true );
				$usr_points         = get_user_meta( $user->ID, 'wps_wpr_points', true );
				$user_email         = $user->user_email;

				$wps_wpr_notificatin_array                         = get_option( 'wps_wpr_notificatin_array', true );
				$wps_wpr_point_on_first_daily_login_setting_enable = ! empty( $wps_wpr_notificatin_array['wps_wpr_point_on_first_daily_login_setting_enable'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_first_daily_login_setting_enable'] : '';
				$wps_wpr_notification_setting_enable               = ! empty( $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] ) ? $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] : '';

				if ( '1' == $wps_wpr_point_on_first_daily_login_setting_enable && '1' == $wps_wpr_notification_setting_enable ) {
					if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

						$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_point_on_first_daily_login_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_first_daily_login_subject'] : '';
						$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_first_daily_login_desc'] ) ? $wps_wpr_notificatin_array['wps_first_daily_login_desc'] : '';
						$wps_wpr_email_discription = str_replace( '[FIRSTLOGINPOINT] ', $first_order_points, $wps_wpr_email_discription );
						$wps_wpr_email_discription = str_replace( '[TOTALPOINTNOW]', $usr_points, $wps_wpr_email_discription );
						$user = get_user_by( 'email', $user_email );
						$user_name = $user->user_firstname;
						$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );

						if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() ) {
							$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
							$email_status = $customer_email->trigger( $user->ID, $wps_wpr_email_discription, $wps_wpr_email_subject );
						}
					}
				} else {
					return;
				}
			}
		}
	}

	/**
	 * Runs a cron to check the user whether its first login or not.
	 *
	 * @name wps_wpr_do_this_daily_login_check.
	 * @author makewebbetter<webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	public function wps_wpr_do_this_daily_login_check() {
		$user_id = get_current_user_id();
		delete_user_meta( get_current_user_id(), 'login_amount' );
	}

	/**
	 * Add this shortcode.
	 *
	 * @return void
	 */
	public function wps_wpr_display_shortcode() {
		add_shortcode( 'CUSTOMERRANK', array( $this, 'wps_wpr_shortcode_to_show_all_user_points' ) );
	}

	/**
	 * This function is used to show all user points.
	 *
	 * @return string
	 */
	public function wps_wpr_shortcode_to_show_all_user_points() {
		ob_start();
		$my_array = get_option( 'wps_wpr_settings_gallery' );
		if ( '1' == $my_array['wps_wpr_general_setting_customer_rank_list'] ) {
			$customer_no  = $my_array['wps_wpr_general_no_of_customer_list'];
			?>
			<div class="wps_user_ranking_table_wrapper">
				<table id="wps_user_ranking_table" class="table">
					<thead class="thead-dark">
						<tr>
							<th scope="col"><?php esc_html_e( 'Customer Ranking', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Name', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
							<th scope="col"><?php esc_html_e( 'Points', 'ultimate-woocommerce-points-and-rewards' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$args = array(
							'orderby'    => 'meta_value_num',
							'order'      => 'DESC',
							'number' => $customer_no,
							'meta_query' => array(
								'relation' => 'OR',
								array(
									'key'     => 'wps_wpr_points',
									'compare' => 'EXISTS',
								),
								array(
									'key'     => 'wps_wpr_points',
									'compare' => 'NOT EXISTS',
								),
							),
						);
						$user_data = new WP_User_Query( $args );
						if ( ! empty( $user_data ) && is_object( $user_data ) ) {
							$wps_user_data = $user_data->get_results();
						}
						if ( ! empty( $wps_user_data ) && is_array( $wps_user_data ) ) {
							foreach ( $wps_user_data as $wps_key => $value ) {
								$points = ! empty( get_user_meta( $value->ID, 'wps_wpr_points', true ) ) ? get_user_meta( $value->ID, 'wps_wpr_points', true ) : 0;
								?>
								<tr>
									<td><?php echo esc_html( '#' . (string) ( $wps_key + 1 ) ); ?></td>
									<td><?php echo esc_html( $value->data->user_nicename ); ?></td>
									<td><?php echo esc_html( $points ); ?></td>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
		<?php
		}
		return ob_get_clean();
	}

	/**
	 * This function is used for giving points on first order.
	 *
	 * @param array $point_log array of user points.
	 * @return void
	 */
	public function wps_wpr_woocommerce_points_log( $point_log ) {
		if ( array_key_exists( 'points_on_first_order', $point_log ) ) {

			?>
		<div class="wps_wpr_slide_toggle">
			<p class="wps_wpr_view_log_notice wps_wpr_common_slider"><?php esc_html_e( 'Points on first order', 'ultimate-woocommerce-points-and-rewards' ); ?><a class ="wps_wpr_open_toggle"  href="javascript:;"></a></p>
			<table class="wps_wpr_common_table">
				<thead>
					<tr>
						<th class="wps-wpr-view-log-Date">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th class="wps-wpr-view-log-Status">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
					</tr>
				</thead>
				<?php
				foreach ( $point_log['points_on_first_order'] as $key => $value ) {
					?>
					<tr>
						<td><?php echo esc_html( wps_wpr_set_the_wordpress_date_format( $value['date'] ) ); ?></td>
						<td><?php echo '+' . esc_html( $value['points_on_first_order'] ); ?></td>
					
					</tr>
					<?php
				}
				?>
			</table>
		</div>
			<?php
		} //My Custom Code Start Here.
		if ( array_key_exists( 'points_on_first_login_daily', $point_log ) ) {
			?>
		<div class="wps_wpr_slide_toggle">
			<p class="wps_wpr_view_log_notice wps_wpr_common_slider"><?php esc_html_e( 'Points on first Login', 'ultimate-woocommerce-points-and-rewards' ); ?><a class ="wps_wpr_open_toggle"  href="javascript:;"></a></p>
			<table class="wps_wpr_common_table">
				<thead>
					<tr>
						<th class="wps-wpr-view-log-Date">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th class="wps-wpr-view-log-Status">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
					</tr>
				</thead>
				<?php
				foreach ( $point_log['points_on_first_login_daily'] as $key => $value ) {
					?>
					<tr>
						<td><?php echo esc_html( wps_wpr_set_the_wordpress_date_format( $value['date'] ) ); ?></td>
						<td><?php echo '+' . esc_html( $value['points_on_first_login_daily'] ); ?></td>
					
					</tr>
					<?php
				}
				?>
			</table>
		</div>
			<?php
		} // My Custom Code End Here.
		if ( array_key_exists( 'points_on_birthday', $point_log ) ) {
			?>

		<div class="wps_wpr_slide_toggle">
			<p class="wps_wpr_view_log_notice wps_wpr_common_slider"><?php esc_html_e( 'Points on Birthday', 'ultimate-woocommerce-points-and-rewards' ); ?><a class ="wps_wpr_open_toggle"  href="javascript:;"></a></p>
			<table class="wps_wpr_common_table">
				<thead>
					<tr>
						<th class="wps-wpr-view-log-Date">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date', 'ultimate-woocommerce-points-and-rewards' ); ?> </span>
						</th>
						<th class="wps-wpr-view-log-Status">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?> </span>
						</th>
					</tr>
				</thead>
				<?php
				foreach ( $point_log['points_on_birthday'] as $key => $value ) {
					?>
					<tr>
				
						<td> <?php echo esc_html( wps_wpr_set_the_wordpress_date_format( $value['date'] ) ); ?> </td>
						<td> <?php echo '+' . esc_html( $value['points_on_birthday'] ); ?> </td>
					
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
			<p class="wps_wpr_view_log_notice wps_wpr_common_slider"><?php esc_html_e( 'Earned referral coupon points', 'ultimate-woocommerce-points-and-rewards' ); ?><a class ="wps_wpr_open_toggle"  href="javascript:;"></a></p>
			<table class="wps_wpr_common_table">
				<thead>
					<tr>
						<th class="wps-wpr-view-log-Date">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th class="wps-wpr-view-log-Status">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th class="wps-wpr-view-log-email">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'refered user', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
					</tr>
				</thead>
				<?php foreach ( $point_log['points_on_coupon_refer'] as $key => $value ) { ?>
					<tr>
						<td><?php echo esc_html( wps_wpr_set_the_wordpress_date_format( $value['date'] ) ); ?></td>
						<td><?php echo '+' . esc_html( $value['points_on_coupon_refer'] ); ?></td>
						<td><?php echo esc_html( $value['refered_user'] ); ?></td>
					
					</tr>
					<?php
				}
				?>
			</table>
		</div>
			<?php
		}
		if ( array_key_exists( 'refunded_coupon_refer_points', $point_log ) ) {

			?>
		<div class="wps_wpr_slide_toggle">
			<p class="wps_wpr_view_log_notice wps_wpr_common_slider"><?php esc_html_e( 'Refunded Coupon Points', 'ultimate-woocommerce-points-and-rewards' ); ?><a class ="wps_wpr_open_toggle"  href="javascript:;"></a></p>
			<table class="wps_wpr_common_table">
				<thead>
					<tr>
						<th class="wps-wpr-view-log-Date">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th class="wps-wpr-view-log-Status">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
					</tr>
				</thead>
				<?php
				foreach ( $point_log['refunded_coupon_refer_points'] as $key => $value ) {
					?>
					<tr>
						<td><?php echo esc_html( wps_wpr_set_the_wordpress_date_format( $value['date'] ) ); ?></td>
						<td><?php echo '-' . esc_html( $value['refunded_coupon_refer_points'] ); ?></td>
					
					</tr>
					<?php
				}
				?>
			</table>
		</div>
			<?php
		}
		// Refer_purchase_points.
		// panda.
		if ( array_key_exists( 'wps_refer_purchase_point_refund', $point_log ) ) {

			?>
		<div class="wps_wpr_slide_toggle">
			<p class="wps_wpr_view_log_notice wps_wpr_common_slider"><?php esc_html_e( 'Refunded Purchase Points', 'ultimate-woocommerce-points-and-rewards' ); ?><a class ="wps_wpr_open_toggle"  href="javascript:;"></a></p>
			<table class="wps_wpr_common_table">
				<thead>
					<tr>
						<th class="wps-wpr-view-log-Date">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Date', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
						<th class="wps-wpr-view-log-Status">
							<span class="wps_wpr_nobr"><?php echo esc_html__( 'Point Status', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						</th>
					</tr>
				</thead>
				<?php
				foreach ( $point_log['wps_refer_purchase_point_refund'] as $key => $value ) {
					?>
					<tr>
						<td><?php echo esc_html( wps_wpr_set_the_wordpress_date_format( $value['date'] ) ); ?></td>
						<td><?php echo '-' . esc_html( $value['wps_refer_purchase_point_refund'] ); ?></td>
					
					</tr>
					<?php
				}
				?>
			</table>
		</div>
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
	 * This Function is used for displayong notice for excluding discount on sale products.
	 *
	 * Wps_wpr_woocommerce_notice_for_exclude_sale_item function.
	 *
	 * @return void
	 */
	public function wps_wpr_woocommerce_notice_for_exclude_sale_item() {

		$general_settings      = get_option( 'wps_wpr_settings_gallery' );
		$restrict_sale_on_cart = ! empty( $general_settings['wps_wpr_points_restrict_sale'] ) ? $general_settings['wps_wpr_points_restrict_sale'] : '';
		$points_apply_enable   = ! empty( $general_settings['wps_wpr_general_setting_enable'] ) ? $general_settings['wps_wpr_general_setting_enable'] : '';
		if ( '1' == $points_apply_enable && '1' == $restrict_sale_on_cart ) {
			$general_settings_color = get_option( 'wps_wpr_other_settings', true );
			$wps_wpr_notification_color = ! empty( $general_settings_color['wps_wpr_notification_color'] ) ? $general_settings_color['wps_wpr_notification_color'] : '#55b3a5';
			?>
			<div class="woocommerce-message" id="wps_wpr_order_notice" style="background-color: <?php echo esc_html( $wps_wpr_notification_color ); ?>"> <?php esc_html_e( 'Points will not be applied to Sale products', 'ultimate-woocommerce-points-and-rewards' ); ?></div>
			<?php
		}
	}

	/**
	 * This Function is used
	 * wps_wpr_function function
	 *
	 * @param int    $wps_fee_on_cart Price on cart.
	 * @param object $cart object of the cart.
	 * @param string $cart_discount data of the cart.
	 * @return void
	 */
	public function wps_wpr_function( $wps_fee_on_cart, $cart, $cart_discount ) {
		global $woocommerce;

		$public_obj                          = $this->generate_public_obj();
		$general_settings                    = get_option( 'wps_wpr_settings_gallery' );
		$wps_limit_points                    = ! empty( $general_settings['wps_wpr_max_points_on_cart'] ) ? $general_settings['wps_wpr_max_points_on_cart'] : '';
		$restrict_sale_on_cart               = ! empty( $general_settings['wps_wpr_points_restrict_sale'] ) ? $general_settings['wps_wpr_points_restrict_sale'] : '';
		$points_apply_enable                 = ! empty( $general_settings['wps_wpr_general_setting_enable'] ) ? $general_settings['wps_wpr_general_setting_enable'] : '';
		$amount_customer_can_pay_using_point = ! empty( $general_settings['wps_wpr_amount_value'] ) ? $general_settings['wps_wpr_amount_value'] : '';

		if ( '1' == $points_apply_enable && '1' == $restrict_sale_on_cart ) {

			$wps_wpr_cart_price_rate    = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_price_rate' );
			$wps_per_currency_value     = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_points_rate' );
			$wps_discount_percent_fixed = ! empty( $general_settings['wps_wpr_cart_point_type'] ) ? $general_settings['wps_wpr_cart_point_type'] : '0';

			$items                             = $woocommerce->cart->get_cart();
			$total                             = 0;
			$total_price_sale_product          = 0;
			$total_price_sale_product_variable = 0;
			foreach ( $items as $item => $values ) {

				$product = wc_get_product( $values['product_id'] );
				if ( ( $product->is_on_sale() ) && ( $product->is_type( 'variable' ) ) == '' ) {

					$price = get_post_meta( $values['product_id'], '_price', true );
					$total_price_sale_product += $price * $values['quantity'];
				}

				if ( ( $product->is_on_sale() ) && ( $product->is_type( 'variable' ) ) != '' ) {

					$price_variable_on_sale = get_post_meta( $values['variation_id'], '_price', true );
					$total_price_sale_product_variable += $price_variable_on_sale * $values['quantity'];
				}
				$total = $total_price_sale_product_variable + $total_price_sale_product;

			}

			$new_subtotal               = $total;
			$tax                        = ( WC()->cart->get_subtotal() );
			$subtotal_with_tax          = ( WC()->cart->get_subtotal_tax() );
			$total_sub_total            = $subtotal_with_tax + $tax;
			$wps_price_excluding_sales  = $total_sub_total - $new_subtotal;

			if ( ! empty( WC()->session->get( 'wps_cart_points' ) ) ) {
				$wps_wpr_points = WC()->session->get( 'wps_cart_points' );
				if ( $this->wps_wpr_enable_points_on_order_total_pro() ) {
					if ( 'wps_wpr_fixed_cart' == $wps_discount_percent_fixed ) {

						$per_currency_applicable_points_value = $wps_wpr_cart_price_rate / $wps_per_currency_value;
						$new_discount_value = $wps_wpr_points * $per_currency_applicable_points_value;
						if ( 0 != $total ) {
								$amount_customer_can_pay_using_point = $wps_price_excluding_sales;
						}

						if ( $new_discount_value > $amount_customer_can_pay_using_point ) {
							$new_discount_value = $amount_customer_can_pay_using_point;
						}
						$tax = ( WC()->cart->get_subtotal() );

						$subtotal_with_tax = ( WC()->cart->get_subtotal_tax() );
						$wps_sub_total = $subtotal_with_tax + $tax;
						$wps_total_excluding_sales = $wps_sub_total - $new_subtotal;

						if ( $new_discount_value > $wps_total_excluding_sales ) {
							$new_discount_value = $wps_total_excluding_sales;
						}
						if ( isset( $woocommerce->cart ) ) {
							if ( ! $woocommerce->cart->has_discount( $cart_discount ) ) {
								if ( $woocommerce->cart->applied_coupons ) {
									foreach ( $woocommerce->cart->applied_coupons as $code ) {
										if ( $cart_discount === $code ) {
											return;
										}
									}
								}
								$woocommerce->cart->applied_coupons[] = $cart_discount;
							}
						}
						// $cart->add_fee( $cart_discount, -$new_discount_value, true, '' );
					} else {

						$per_currency_applicable_points_value = $wps_wpr_cart_price_rate / $wps_per_currency_value;
						$new_discount_value                   = $wps_wpr_points * $per_currency_applicable_points_value;
						$tax                                  = ( WC()->cart->get_subtotal() );
						$subtotal_with_tax                    = WC()->cart->get_subtotal_tax();
						$sub_total                            = $subtotal_with_tax + $tax;
						$final_subtotal                       = $sub_total - $new_subtotal;
						$discount_applicable                  = ( $final_subtotal * $amount_customer_can_pay_using_point ) / 100;

						if ( $discount_applicable <= $new_discount_value ) {
							$new_discount_value = $discount_applicable;
						}

						// amount restruct.
						// paypal.
						if ( isset( $woocommerce->cart ) ) {
							if ( ! $woocommerce->cart->has_discount( $cart_discount ) ) {
								if ( $woocommerce->cart->applied_coupons ) {
									foreach ( $woocommerce->cart->applied_coupons as $code ) {
										if ( $cart_discount === $code ) {
											return;
										}
									}
								}
								$woocommerce->cart->applied_coupons[] = $cart_discount;
							}
						}
						// $cart->add_fee( $cart_discount, -$new_discount_value, true, '' );
					}
				}

				if ( 0 != $new_subtotal ) {
					$new_discount_value = ( $wps_wpr_points * $wps_price_excluding_sales ) / 100;
					$applicable_amount  = $new_discount_value;
					if ( $amount_customer_can_pay_using_point < $applicable_amount ) {
						$applicable_amount = $amount_customer_can_pay_using_point;
						// $cart->add_fee( $cart_discount, -$applicable_amount, true, '' );
						if ( isset( $woocommerce->cart ) ) {
							if ( ! $woocommerce->cart->has_discount( $cart_discount ) ) {
								if ( $woocommerce->cart->applied_coupons ) {
									foreach ( $woocommerce->cart->applied_coupons as $code ) {
										if ( $cart_discount === $code ) {
											return;
										}
									}
								}
								$woocommerce->cart->applied_coupons[] = $cart_discount;
							}
						}
					} else {

						$tax                = ( WC()->cart->get_subtotal() );
						$subtotal_with_tax  = ( WC()->cart->get_subtotal_tax() );
						$wps_sub_total      = $subtotal_with_tax + $tax;
						$new_discount_value = ( $wps_wpr_points * $wps_sub_total ) / 100;
						$applicable_amount  = $new_discount_value;
						if ( $amount_customer_can_pay_using_point < $applicable_amount ) {
							$applicable_amount = $amount_customer_can_pay_using_point;
						}
						if ( isset( $woocommerce->cart ) ) {
							if ( ! $woocommerce->cart->has_discount( $cart_discount ) ) {
								if ( $woocommerce->cart->applied_coupons ) {
									foreach ( $woocommerce->cart->applied_coupons as $code ) {
										if ( $cart_discount === $code ) {
											return;
										}
									}
								}
								$woocommerce->cart->applied_coupons[] = $cart_discount;
							}
						}
						// $cart->add_fee( $cart_discount, -$applicable_amount, true, '' );
					}
				}
			}
		} else {

			$public_obj = $this->generate_public_obj();
			$general_settings = get_option( 'wps_wpr_settings_gallery', array() );
			$wps_discount_percent_fixed = ! empty( $general_settings['wps_wpr_cart_point_type'] ) ? $general_settings['wps_wpr_cart_point_type'] : '0';

			if ( $this->wps_wpr_enable_points_on_order_total_pro() ) {
				if ( 'wps_wpr_fixed_cart' == $wps_discount_percent_fixed ) {
					if ( ! empty( $wps_limit_points ) ) {
						if ( $amount_customer_can_pay_using_point < $wps_fee_on_cart ) {
								$wps_fee_on_cart = $amount_customer_can_pay_using_point;
						}
					}
					$tax = ( WC()->cart->get_subtotal() );

					$subtotal_with_tax = ( WC()->cart->get_subtotal_tax() );
					$wps_sub_total = $subtotal_with_tax + $tax;
					if ( $wps_fee_on_cart > $wps_sub_total ) {
						$wps_fee_on_cart = $wps_sub_total;
					}
					if ( isset( $woocommerce->cart ) ) {
						if ( ! $woocommerce->cart->has_discount( $cart_discount ) ) {
							if ( $woocommerce->cart->applied_coupons ) {
								foreach ( $woocommerce->cart->applied_coupons as $code ) {
									if ( $cart_discount === $code ) {
										return;
									}
								}
							}
							$woocommerce->cart->applied_coupons[] = $cart_discount;
						}
					}
					// $cart->add_fee( $cart_discount, -$wps_fee_on_cart, true, '' );
				} else {
					if ( ! empty( $wps_limit_points ) ) {
						if ( ! empty( WC()->session->get( 'wps_cart_points' ) ) ) {

							$wps_wpr_points          = WC()->session->get( 'wps_cart_points' );
							$wps_wpr_cart_price_rate = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_price_rate' );
							$wps_per_currency_value  = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_points_rate' );
							$per_currency_value      = $wps_wpr_cart_price_rate / $wps_per_currency_value;
							$total_deduction         = ( $per_currency_value * $wps_wpr_points );

							$tax = ( WC()->cart->get_subtotal() );

							$subtotal_with_tax = ( WC()->cart->get_subtotal_tax() );
							$sub_total = $subtotal_with_tax + $tax;
							$discount_applicable = ( $sub_total * $amount_customer_can_pay_using_point ) / 100;

							if ( $discount_applicable <= $total_deduction ) {
								$total_deduction = $discount_applicable;
							}
							if ( isset( $woocommerce->cart ) ) {
								if ( ! $woocommerce->cart->has_discount( $cart_discount ) ) {
									if ( $woocommerce->cart->applied_coupons ) {
										foreach ( $woocommerce->cart->applied_coupons as $code ) {
											if ( $cart_discount === $code ) {
												return;
											}
										}
									}
									$woocommerce->cart->applied_coupons[] = $cart_discount;
								}
							}
							// $cart->add_fee( $cart_discount, -$total_deduction, true, '' );
						}
					}
				}
			}
		}
	}

	/**
	 * This function is used to apply coupon on cart page.
	 *
	 * @param bool   $response response.
	 * @param object $coupon_data coupon data.
	 * @return bool
	 */
	public function wps_wpr_pro_validate_virtual_coupon_for_points( $response, $coupon_data ) {
		global $woocommerce;
		if ( ! is_admin() ) {

			if ( false !== $coupon_data && 0 !== $coupon_data ) {

				/*Get the current user id*/
				$my_cart_change_return = 0;
				if ( ! empty( WC()->cart ) ) {
					$my_cart_change_return = apply_filters( 'wps_cart_content_check_for_sale_item', WC()->cart->get_cart() );
				}

				$cart_discount = __( 'Cart Discount', 'points-and-rewards-for-woocommerce' );
				if ( '1' == $my_cart_change_return ) {

					return;
				} else {

						$user_id = get_current_user_ID();
						/*Check is custom points on cart is enable*/
						$wps_wpr_custom_points_on_cart = $this->wps_wpr_get_general_settings_num( 'wps_wpr_custom_points_on_cart' );
						$wps_wpr_custom_points_on_checkout = $this->wps_wpr_get_general_settings_num( 'wps_wpr_apply_points_checkout' );

					if ( isset( $user_id ) && ! empty( $user_id ) && ( 1 == $wps_wpr_custom_points_on_cart || 1 == $wps_wpr_custom_points_on_checkout ) ) {

						$public_obj                          = $this->generate_public_obj();
						$general_settings                    = get_option( 'wps_wpr_settings_gallery' );
						$wps_limit_points                    = ! empty( $general_settings['wps_wpr_max_points_on_cart'] ) ? $general_settings['wps_wpr_max_points_on_cart'] : '';
						$restrict_sale_on_cart               = ! empty( $general_settings['wps_wpr_points_restrict_sale'] ) ? $general_settings['wps_wpr_points_restrict_sale'] : '';
						$points_apply_enable                 = ! empty( $general_settings['wps_wpr_general_setting_enable'] ) ? $general_settings['wps_wpr_general_setting_enable'] : '';
						$amount_customer_can_pay_using_point = ! empty( $general_settings['wps_wpr_amount_value'] ) ? $general_settings['wps_wpr_amount_value'] : '';
						$wps_wpr_cart_final_amount           = 0;

						if ( isset( WC()->session ) && WC()->session->has_session() ) {
							if ( ! empty( WC()->session->get( 'wps_cart_points' ) ) ) {

								$wps_wpr_points          = WC()->session->get( 'wps_cart_points' );
								$wps_wpr_cart_price_rate = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_price_rate' );
								$wps_per_currency_value  = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_cart_points_rate' );

								if ( '1' == $points_apply_enable && '1' == $restrict_sale_on_cart ) {
									$wps_discount_percent_fixed = ! empty( $general_settings['wps_wpr_cart_point_type'] ) ? $general_settings['wps_wpr_cart_point_type'] : '0';

									$items                             = $woocommerce->cart->get_cart();
									$total                             = 0;
									$total_price_sale_product          = 0;
									$total_price_sale_product_variable = 0;

									foreach ( $items as $item => $values ) {

										$product = wc_get_product( $values['product_id'] );
										if ( ( $product->is_on_sale() ) && ( $product->is_type( 'variable' ) ) == '' ) {

											$price = get_post_meta( $values['product_id'], '_price', true );
											$total_price_sale_product += $price * $values['quantity'];
										}

										if ( ( $product->is_on_sale() ) && ( $product->is_type( 'variable' ) ) != '' ) {

											$price_variable_on_sale           = get_post_meta( $values['variation_id'], '_price', true );
											$total_price_sale_product_variable += $price_variable_on_sale * $values['quantity'];
										}
										$total = $total_price_sale_product_variable + $total_price_sale_product;
									}

									$new_subtotal              = $total;
									$tax                       = ( WC()->cart->get_subtotal() );
									$subtotal_with_tax         = ( WC()->cart->get_subtotal_tax() );
									$total_sub_total           = $subtotal_with_tax + $tax;
									$wps_price_excluding_sales = $total_sub_total - $new_subtotal;

									if ( ! empty( WC()->session->get( 'wps_cart_points' ) ) ) {
										$wps_wpr_points = WC()->session->get( 'wps_cart_points' );

										if ( $this->wps_wpr_enable_points_on_order_total_pro() ) {
											if ( 'wps_wpr_fixed_cart' == $wps_discount_percent_fixed ) {
												$per_currency_applicable_points_value = $wps_wpr_cart_price_rate / $wps_per_currency_value;
												$new_discount_value                   = $wps_wpr_points * $per_currency_applicable_points_value;

												if ( 0 != $total ) {
													$amount_customer_can_pay_using_point = $wps_price_excluding_sales;
												}

												if ( $new_discount_value > $amount_customer_can_pay_using_point ) {
													$new_discount_value = $amount_customer_can_pay_using_point;
												}

												$tax                       = ( WC()->cart->get_subtotal() );
												$subtotal_with_tax         = ( WC()->cart->get_subtotal_tax() );
												$wps_sub_total             = $subtotal_with_tax + $tax;
												$wps_total_excluding_sales = $wps_sub_total - $new_subtotal;

												if ( $new_discount_value > $wps_total_excluding_sales ) {
													$new_discount_value = $wps_total_excluding_sales;
												}

												$wps_wpr_cart_final_amount = $new_discount_value;
												// $woocommerce->cart->applied_coupons[] = $cart_discount;
												// $cart->add_fee( $cart_discount, -$new_discount_value, true, '' );
											} else {
												$per_currency_applicable_points_value = $wps_wpr_cart_price_rate / $wps_per_currency_value;
												$new_discount_value = $wps_wpr_points * $per_currency_applicable_points_value;

												$tax                 = ( WC()->cart->get_subtotal() );
												$subtotal_with_tax   = WC()->cart->get_subtotal_tax();
												$sub_total           = $subtotal_with_tax + $tax;
												$final_subtotal      = $sub_total - $new_subtotal;
												$discount_applicable = ( $final_subtotal * $amount_customer_can_pay_using_point ) / 100;

												if ( $discount_applicable <= $new_discount_value ) {
													$new_discount_value = $discount_applicable;
												}
												// paypal.
												$wps_wpr_cart_final_amount = $new_discount_value;
												// $woocommerce->cart->applied_coupons[] = $cart_discount;
												// $cart->add_fee( $cart_discount, -$new_discount_value, true, '' );
											}
										} else {
											$wps_fee_on_cart = ( $wps_wpr_points * $wps_wpr_cart_price_rate / $wps_per_currency_value );
											$subtotal        = $woocommerce->cart->get_subtotal();
											if ( $subtotal > $wps_fee_on_cart ) {
												$wps_fee_on_cart = $wps_fee_on_cart;
											} else {
												$wps_fee_on_cart = $subtotal;
											}
											$wps_wpr_cart_final_amount = $wps_fee_on_cart;
										}

										if ( 0 != $new_subtotal ) {
											$new_discount_value = ( $wps_wpr_points * $wps_price_excluding_sales ) / 100;
											$applicable_amount  = $new_discount_value;

											if ( $amount_customer_can_pay_using_point < $applicable_amount ) {
												$applicable_amount = $amount_customer_can_pay_using_point;
												$wps_wpr_cart_final_amount = $applicable_amount;
												// $cart->add_fee( $cart_discount, -$applicable_amount, true, '' );
												// $woocommerce->cart->applied_coupons[] = $cart_discount;
											} else {

												$tax                = ( WC()->cart->get_subtotal() );
												$subtotal_with_tax  = ( WC()->cart->get_subtotal_tax() );
												$wps_sub_total      = $subtotal_with_tax + $tax;
												$new_discount_value = ( $wps_wpr_points * $wps_sub_total ) / 100;
												$applicable_amount  = $new_discount_value;

												if ( $amount_customer_can_pay_using_point < $applicable_amount ) {
													$applicable_amount = $amount_customer_can_pay_using_point;
												}
												$wps_wpr_cart_final_amount = $applicable_amount;
												// $woocommerce->cart->applied_coupons[] = $cart_discount;
												// $cart->add_fee( $cart_discount, -$applicable_amount, true, '' );
											}
										}
									}
								} else {

									$wps_discount_percent_fixed = ! empty( $general_settings['wps_wpr_cart_point_type'] ) ? $general_settings['wps_wpr_cart_point_type'] : '0';
									$wps_fee_on_cart            = ( $wps_wpr_points * $wps_wpr_cart_price_rate / $wps_per_currency_value );

									// apply points on subtotal.
									$subtotal = ( WC()->cart->get_subtotal() );
									if ( $subtotal > $wps_fee_on_cart ) {
										$wps_fee_on_cart = $wps_fee_on_cart;
									} else {
										$wps_fee_on_cart = $subtotal;
									}

									if ( $this->wps_wpr_enable_points_on_order_total_pro() ) {
										if ( 'wps_wpr_fixed_cart' == $wps_discount_percent_fixed ) {

											if ( ! empty( $wps_limit_points ) ) {
												if ( $amount_customer_can_pay_using_point < $wps_fee_on_cart ) {
														$wps_fee_on_cart = $amount_customer_can_pay_using_point;
												}
											}
											$tax               = ( WC()->cart->get_subtotal() );
											$subtotal_with_tax = ( WC()->cart->get_subtotal_tax() );
											$wps_sub_total     = $subtotal_with_tax + $tax;

											if ( $wps_fee_on_cart > $wps_sub_total ) {
												$wps_fee_on_cart = $wps_sub_total;
											}
											$wps_wpr_cart_final_amount = $wps_fee_on_cart;
											// $woocommerce->cart->applied_coupons[] = $cart_discount;
											// $cart->add_fee( $cart_discount, -$wps_fee_on_cart, true, '' );
										} else {

											if ( ! empty( $wps_limit_points ) ) {
												if ( ! empty( WC()->session->get( 'wps_cart_points' ) ) ) {
													$wps_wpr_points = WC()->session->get( 'wps_cart_points' );

													$per_currency_value = $wps_wpr_cart_price_rate / $wps_per_currency_value;
													$total_deduction    = ( $per_currency_value * $wps_wpr_points );

													$tax                 = ( WC()->cart->get_subtotal() );
													$subtotal_with_tax   = ( WC()->cart->get_subtotal_tax() );
													$sub_total           = $subtotal_with_tax + $tax;
													$discount_applicable = ( $sub_total * $amount_customer_can_pay_using_point ) / 100;

													if ( $discount_applicable <= $total_deduction ) {
														$total_deduction = $discount_applicable;
													}
													$wps_wpr_cart_final_amount = $total_deduction;
													// $woocommerce->cart->applied_coupons[] = $cart_discount;
													// $cart->add_fee( $cart_discount, -$total_deduction, true, '' );
												}
											}
										}
									} else {
										$wps_fee_on_cart = ( $wps_wpr_points * $wps_wpr_cart_price_rate / $wps_per_currency_value );
										$subtotal        = $woocommerce->cart->get_subtotal();
										if ( $subtotal > $wps_fee_on_cart ) {
											$wps_fee_on_cart = $wps_fee_on_cart;
										} else {
											$wps_fee_on_cart = $subtotal;
										}
										$wps_wpr_cart_final_amount = $wps_fee_on_cart;
									}
								}
								// WOOCS - WooCommerce Currency Switcher Compatibility.
								$wps_wpr_cart_final_amount = apply_filters( 'wps_wpr_show_conversion_price', $wps_wpr_cart_final_amount );
								if ( $coupon_data == $cart_discount ) {
									$discount_type = 'fixed_cart';
									$coupon = array(
										'id' => time() . rand( 2, 9 ),
										'amount' => $wps_wpr_cart_final_amount,
										'individual_use' => false,
										'product_ids' => array(),
										'exclude_product_ids' => array(),
										'usage_limit' => '',
										'usage_limit_per_user' => '',
										'limit_usage_to_x_items' => '',
										'usage_count' => '',
										'expiry_date' => '',
										'apply_before_tax' => 'yes',
										'free_shipping' => false,
										'product_categories' => array(),
										'exclude_product_categories' => array(),
										'exclude_sale_items' => false,
										'minimum_amount' => '',
										'maximum_amount' => '',
										'customer_email' => '',
									);
									$coupon['discount_type'] = $discount_type;
									return $coupon;
								}
							}
						}
					}
				}
			}
		}
		return $response;
	}

	/**
	 * Wps_wpr_woocommerce_custom_restrict_discount function
	 *
	 * @param object $cart object of the cart.
	 */
	public function wps_wpr_woocommerce_custom_restrict_discount( $cart ) {
		global $woocommerce;
		$sale_settings = get_option( 'wps_wpr_settings_gallery', true );
		$sale_enable   = ! empty( $sale_settings['wps_wpr_points_restrict_sale'] ) ? $sale_settings['wps_wpr_points_restrict_sale'] : '';
		if ( '1' == $sale_enable ) {

			$user_id = get_current_user_ID();
			$count   = 0;
			$items   = $woocommerce->cart->get_cart();
			if ( ! empty( $items ) ) {
				foreach ( $items as $item => $values ) {

					$product = wc_get_product( $values['product_id'] );
					if ( ( $product->is_on_sale() ) ) {
						$count++;
					}
				}
			}
			if ( ( count( WC()->cart->get_cart() ) ) == $count ) {
				return '1';
			} else {
				return '2';
			}
		} else {
			return '2';
		}
	}

	/**
	 * Wps_wpr_woocommerce_birthday_input_box function
	 *
	 * @return void
	 */
	public function wps_wpr_woocommerce_birthday_input_box() {

		$public_obj          = $this->generate_public_obj();
		$wps_birthday_enable = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_setting_birthday_enablee' );
		if ( 1 == $wps_birthday_enable ) {
			?>
			<fieldset>
			<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
				<label for="account_first_name">
					<?php
					esc_html_e( 'Date Of Birth', 'ultimate-woocommerce-points-and-rewards' );
					?>
				&nbsp;</label>
				<input type="date" class="woocommerce-Input woocommerce-Input--text input-text" name="account_bday" id="account_bday" value="<?php echo esc_html( ! empty( get_user_meta( get_current_user_id(), '_my_bday', true ) ) ? get_user_meta( get_current_user_id(), '_my_bday', true ) : '' ); ?>" <?php echo esc_html( ! empty( get_user_meta( get_current_user_id(), '_my_bday', true ) ) ? 'disabled' : '' ); ?> />
			</p>
			</fieldset>
			<?php
		}
	}

	/**
	 * Wps_woocommerce_save_account_details function.
	 *
	 * @param int $user_id of the database array.
	 * @return void
	 */
	public function wps_woocommerce_save_account_details( $user_id ) {
		$nonce_value = ! empty( sanitize_text_field( wp_unslash( $_REQUEST['save-account-details-nonce'] ) ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['save-account-details-nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce_value, 'save_account_details' ) ) {
			return;
		}

		$birthday         = ! empty( $_POST['account_bday'] ) ? sanitize_text_field( wp_unslash( $_POST['account_bday'] ) ) : '';
		$current_wps_date = gmdate( 'Y-m-d' );

		if ( empty( get_user_meta( $user_id, '_my_bday', true ) ) && $birthday < $current_wps_date ) {

			$my_array      = get_option( 'wps_wpr_settings_gallery' );
			$myybdaypoints = ! empty( $my_array['wps_wpr_general_birthday_value'] ) ? $my_array['wps_wpr_general_birthday_value'] : '';
			update_user_meta( $user_id, '_my_bday', $birthday );
			update_user_meta( $user_id, 'points_on_birthday_order', $myybdaypoints );
		}
	}

	/**
	 * Wps_eg_schedule_midnight_loggg function
	 *
	 * @return void
	 */
	public function wps_eg_schedule_midnight_loggg() {
		if ( false === as_next_scheduled_action( 'wps_eg_midnight_loggg' ) ) {
			as_schedule_recurring_action( time(), DAY_IN_SECONDS, 'wps_eg_midnight_loggg' );
		}
	}

	/**
	 * Wps_eg_log_action_data function
	 *
	 * @return void
	 */
	public function wps_eg_log_action_data() {

		$public_obj = $this->generate_public_obj();
		$wps_birthday_enable = $public_obj->wps_wpr_get_general_settings_num( 'wps_wpr_general_setting_birthday_enablee' );

		if ( 1 == $wps_birthday_enable ) {

			$users = get_users( array( 'fields' => array( 'ID' ) ) );
			if ( ! empty( $users ) ) {
				foreach ( $users as $user ) {

					$value = get_option( 'wps_wpr_settings_gallery' );

					$myybdaypoints = ! empty( $value['wps_wpr_general_birthday_value'] ) ? $value['wps_wpr_general_birthday_value'] : '';
					$date  = get_user_meta( $user->ID, '_my_bday', true );
					$date2 = substr( $date, 5 );
					$current_year = gmdate( 'Y' );
					$user_already_get = get_user_meta( $user->ID, 'wps_wpr_birthday_points_get', true );
					if ( $user_already_get != $current_year ) {

						if ( gmdate( 'm-d' ) == $date2 ) {

							$usr_points = (int) get_user_meta( $user->ID, 'wps_wpr_points', true );
							$usr_points = $usr_points + $myybdaypoints;
							update_user_meta( $user->ID, 'wps_wpr_points', $usr_points );
							update_user_meta( $user->ID, 'points_on_birthday_order', $myybdaypoints );

							update_user_meta( $user->ID, 'wps_wpr_birthday_points_get', $current_year );
							$birthday_points_years = get_user_meta( $user->ID, 'wps_wpr_birthday_points_year', true );
							if ( ! empty( $birthday_points_years ) && is_array( $birthday_points_years ) ) {
								$birthday_points_years[] = $current_year;
								update_user_meta( $user->ID, 'wps_wpr_birthday_points_year', $birthday_points_years );
							} else {
								$birthday_points_years = array( $current_year );
								update_user_meta( $user->ID, 'wps_wpr_birthday_points_year', $birthday_points_years );
							}
							$points_log = get_user_meta( $user->ID, 'points_details', true );
							if ( isset( $points_log['points_on_birthday'] ) && ! empty( $points_log['points_on_birthday'] ) ) {

								$points_bday_arr = array();
								$points_bday_arr = array(
									'points_on_birthday' => $myybdaypoints,
									'date' => gmdate( 'Y-m-d' ),
								);
								$points_log['points_on_birthday'][] = $points_bday_arr;
							} else {
								if ( ! is_array( $points_log ) ) {
									$points_log = array();
								}
								$points_bday_arr = array();
								$points_bday_arr = array(
									'points_on_birthday' => $myybdaypoints,
									'date' => gmdate( 'Y-m-d' ),
								);
								$points_log['points_on_birthday'][] = $points_bday_arr;
							}
							update_user_meta( $user->ID, 'points_details', $points_log );

							$wps_wpr_notificatin_array = get_option( 'wps_wpr_notificatin_array', true );
							$wps_wpr_point_on_birthday_setting_enable = ! empty( $wps_wpr_notificatin_array['wps_wpr_point_on_birthday_setting_enable'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_birthday_setting_enable'] : '';
							$wps_wpr_notification_setting_enable = ! empty( $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] ) ? $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] : '';
							$user = get_user_by( 'ID', $user->ID );

							$user_email = $user->user_email;

							if ( '1' == $wps_wpr_point_on_birthday_setting_enable && '1' == $wps_wpr_notification_setting_enable ) {

								if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

									$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_point_on_bday_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_bday_subject'] : '';
									$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_point_on_bday_order_desc'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_bday_order_desc'] : '';
									$wps_wpr_email_discription = str_replace( '[BIRTHDAYPOINT] ', $myybdaypoints, $wps_wpr_email_discription );
									$wps_wpr_email_discription = str_replace( '[TOTALPOINTS]', $usr_points, $wps_wpr_email_discription );
									$user = get_user_by( 'email', $user_email );
									$user_name = $user->user_firstname;
									$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );

									if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() ) {

										$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
										$email_status = $customer_email->trigger( $user->ID, $wps_wpr_email_discription, $wps_wpr_email_subject );

									}
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * This function is used for getting setting.
	 * Mwb_wpr_get_general_settings_num function.
	 *
	 * @param string $id id of data.
	 */
	public function wps_wpr_get_general_settings_num( $id ) {
		$wps_wpr_value = 0;
		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		if ( ! empty( $general_settings[ $id ] ) ) {
			$wps_wpr_value = (int) $general_settings[ $id ];
		}
		return $wps_wpr_value;
	}

	/**
	 * This Function generates sale notice.
	 * wps_callback_for_round_down function
	 *
	 * @return void
	 */
	public function wps_wpr_woocommerce_notice_sale() {

		$general_settings = get_option( 'wps_wpr_settings_gallery' );
		$restrict_sale_on_cart = ! empty( $general_settings['wps_wpr_points_restrict_sale'] ) ? $general_settings['wps_wpr_points_restrict_sale'] : '';
		$points_apply_enable = ! empty( $general_settings['wps_wpr_general_setting_enable'] ) ? $general_settings['wps_wpr_general_setting_enable'] : '';
		if ( '1' == $points_apply_enable && '1' == $restrict_sale_on_cart ) {
			$general_settings_color = get_option( 'wps_wpr_other_settings', true );
			$wps_wpr_notification_color = ! empty( $general_settings_color['wps_wpr_notification_color'] ) ? $general_settings_color['wps_wpr_notification_color'] : '#55b3a5';
			?>
			<div class="woocommerce-message" id="wps_wpr_order_notice" style="background-color: <?php echo esc_html( $wps_wpr_notification_color ); ?>"><?php esc_html_e( 'Points will not be applied to Sale products', 'ultimate-woocommerce-points-and-rewards' ); ?></div>
			<?php
		}
	}

	/**
	 * This function is used for points Calculation .
	 * Mwb_callback_for_round_down function
	 *
	 * @param int $points_calculation of the database array.
	 * @param int $order_total of the database array.
	 * @param int $wps_wpr_coupon_conversion_points of the database array.
	 * @param int $wps_wpr_coupon_conversion_price of the database array.
	 */
	public function wps_callback_for_round_down( $points_calculation, $order_total, $wps_wpr_coupon_conversion_points, $wps_wpr_coupon_conversion_price ) {

		$general_settings   = get_option( 'wps_wpr_settings_gallery' );
		$round_down_setting = ! empty( $general_settings['wps_wpr_point_round_off'] ) ? $general_settings['wps_wpr_point_round_off'] : '';
		if ( 'wps_wpr_round_down' == $round_down_setting ) {

			$points_calculation = floor( ( $order_total * $wps_wpr_coupon_conversion_points ) / $wps_wpr_coupon_conversion_price );
		}
		return $points_calculation;
	}

	/**
	 * This function is used for Round down fee.
	 * wps_callback_for_round_down_fee function
	 *
	 * @param int $fee_to_point of the database array.
	 * @param int $wps_wpr_cart_points_rate of the database array.
	 * @param int $fee_amount of the database array.
	 * @param int $wps_wpr_cart_price_rate of the database array.
	 */
	public function wps_callback_for_round_down_fee( $fee_to_point, $wps_wpr_cart_points_rate, $fee_amount, $wps_wpr_cart_price_rate ) {

		$general_settings   = get_option( 'wps_wpr_settings_gallery' );
		$round_down_setting = ! empty( $general_settings['wps_wpr_point_round_off'] ) ? $general_settings['wps_wpr_point_round_off'] : '';
		if ( 'wps_wpr_round_down' == $round_down_setting ) {

			$fee_to_point = floor( ( $wps_wpr_cart_points_rate * $fee_amount ) / $wps_wpr_cart_price_rate );
		}
		return $fee_to_point;
	}

	/**
	 * This Function returns a round_down setting.
	 * Mwb_general_Setting function.
	 */
	public function wps_general_setting() {
		$general_settings = get_option( 'wps_wpr_settings_gallery' );

		$round_down_setting = ! empty( $general_settings['wps_wpr_point_round_off'] ) ? $general_settings['wps_wpr_point_round_off'] : '';
		return $round_down_setting;
	}

	/**
	 * This Function deterimines layout of the email referal feature.
	 * wps_wpr_woocommerce_email function
	 *
	 * @param string $content content data.
	 * @param int    $user_id of user.
	 */
	public function wps_wpr_woocommerce_email( $content, $user_id ) {

		$wps_enter_email = esc_html__( 'Enter Email', 'ultimate-woocommerce-points-and-rewards' );
		$wps_send        = esc_html__( 'Send', 'ultimate-woocommerce-points-and-rewards' );
		$mailll          = '<p id="wps_wpr_shared_points_notificatio"></p><button id="wps_wpr_button" class="wps_wpr_mail_button wps_wpr_common_class"><img src="' . POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'public/images/email.png"></button>';
		$content         = $content . '<div class="wps-wpr__email-input"><input type="email" style="width: 45%;" id="wps_wpr_enter_emaill" placeholder="' . $wps_enter_email . '"><input id="wps_wpr_point" data-id="' . $user_id . '" type="button" name="wps_wpr_point" value="' . $wps_send . '"></div>' . $mailll;
		return $content;
	}

	/**
	 * This Function notifies user through mail.
	 * Mwb_wpr_email_notify_refer function.
	 */
	public function wps_wpr_email_notify_refer() {

		check_ajax_referer( 'wps-wpr-verify-nonce', 'wps_wpr_a' );
		$response['result'] = false;
		if ( isset( $_POST['email_id'] ) && ! empty( $_POST['email_id'] ) && isset( $_POST['user_id'] ) && ! empty( $_POST['user_id'] ) ) {

			$user_id            = sanitize_text_field( wp_unslash( $_POST['user_id'] ) );
			$wps_reciever_email = sanitize_text_field( wp_unslash( $_POST['email_id'] ) );
			/*Get the user id of the user*/
			$user       = get_user_by( 'ID', $user_id );
			$user_email = $user->user_email;
			if ( $user_email == $wps_reciever_email ) {
				$response['match'] = false;
				wp_send_json( $response );
				return;
			}

			$get_referral       = get_user_meta( $user_id, 'wps_points_referral', true );
			$site_url           = site_url();
			$my_link            = ( $site_url . '?pkey=' . $get_referral );
			$wps_reciever_email = sanitize_text_field( wp_unslash( $_POST['email_id'] ) );
			if ( ! ( filter_var( $wps_reciever_email, FILTER_VALIDATE_EMAIL ) ) ) {
				$response['message'] = true;
				wp_send_json( $response );
			}

			$wps_wpr_notificatin_array = ! empty( get_option( 'wps_wpr_notificatin_array', true ) ) ? get_option( 'wps_wpr_notificatin_array', true ) : '';
			$wps_wpr_email_subject     = ! empty( $wps_wpr_notificatin_array['wps_wpr_point_on_email_referal_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_email_referal_subject'] : '';
			$wps_wpr_email_discription = ! empty( $wps_wpr_notificatin_array['wps_wpr_point_on_email_referal_order_desc'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_email_referal_order_desc'] : '';
			if ( empty( $wps_wpr_email_subject ) && empty( $wps_wpr_email_discription ) ) {
				$response['message'] = false;
				wp_send_json( $response );
			}

			$wps_wpr_email_discription = str_replace( '[REFERALLINK]', $my_link, $wps_wpr_email_discription );
			$userr                     = get_user_by( 'email', $user_email );
			$user_name                 = $userr->user_firstname;
			$wps_wpr_email_discription = str_replace( '[USERNAME]', $user_name, $wps_wpr_email_discription );
			$customer_email            = WC()->mailer()->emails['wps_wpr_email_notification'];
			$email_status              = $customer_email->trigger_test( $user->ID, $wps_wpr_email_discription, $wps_wpr_email_subject, $wps_reciever_email );
			$response['result']        = true;
		}
		wp_send_json( $response );
	}

	/**
	 * This function is used for Coupon code division on myaccount page
	 * Mwb_wpr_add_coupon_code_generation function.
	 *
	 * @param int $user_id of the database array.
	 * @return void
	 */
	public function wps_wpr_add_coupon_code_generation( $user_id ) {

		global $mycouponnreferal, $amount_referal,$coupon_code,$enable_copon_code;
		$my_settings       = get_option( 'wps_wpr_settings_gallery' );
		$enable_copon_code = ! empty( $my_settings['wps_wpr_general_referral_code_enable'] ) ? $my_settings['wps_wpr_general_referral_code_enable'] : '0';
		$copon_code_amount = ! empty( $my_settings['wps_wpr_general_referral_code__limit'] ) ? $my_settings['wps_wpr_general_referral_code__limit'] : '';
		$coupon_code       = wps_wpr_create_referral_code();

		if ( '1' == $enable_copon_code ) {
			update_post_meta( $user_id, 'my_coupon_referal_code_amount', $copon_code_amount );
		}

		if ( empty( get_post_meta( $user_id, 'my_coupon_referal_code', $mycouponnreferal ) ) ) {
			update_post_meta( $user_id, 'my_coupon_referal_code', $coupon_code );
		}

		if ( '1' == $enable_copon_code ) {

			$wps_wpr_settings_gallery = get_option( 'wps_wpr_settings_gallery', array() );
			$mycouponnreferal         = get_post_meta( $user_id, 'my_coupon_referal_code', true );
			$amount_referal           = get_post_meta( $user_id, 'my_coupon_referal_code_amount', true );
			$this->wps_coupon_code( $user_id );
			?>

			<fieldset class="wps_wpr_each_section">
				<p id="wps_wpr_referal_points_notification"><?php esc_html_e( 'Your Coupon Referral Code is ', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
				<div class="wps_wpr_shared_points_code">
					<p id="wps_copy"><?php echo esc_html( $mycouponnreferal ); ?></p>
					<button class="wps_wpr_btn_copy wps_tooltip" data-clipboard-target="#wps_copy" aria-label="copied">
						<span class="wps_tooltiptext">Copy</span>
						<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL . 'public/images/copy.png' ); ?>" alt="Copy to clipboard">
					</button>
				<?php
				if ( 'wps_wpr_fixed_coupon_points' == $wps_wpr_settings_gallery['wps_wpr_general_referal_coupon_purchase_type'] ) {
					// WOOCS - WooCommerce Currency Switcher Compatibility.
					?>
					</div><?php esc_html_e( 'and anyone you refer who buys from using this coupon is entitled to a one-off  ', 'ultimate-woocommerce-points-and-rewards' ); ?> <?php echo esc_html( get_woocommerce_currency_symbol() . esc_html( apply_filters( 'wps_wpr_show_conversion_price', $amount_referal ) ) ); ?> <?php esc_html_e( ' as a discount.', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<?php
				} else {
					?>
					</div><?php esc_html_e( 'and anyone you refer who buys from using this coupon is entitled to a one-off ', 'ultimate-woocommerce-points-and-rewards' ); ?> <?php echo esc_html( $amount_referal ); ?> <?php esc_html_e( '% as a discount.', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<?php
				}
				?>
			</fieldset>
			<?php
		}
	}

	/**
	 * This Function is used for generating couponcode
	 * Mwb_coupon_code function.
	 *
	 * @param int $user_id database of the user id.
	 * @return void
	 */
	public function wps_coupon_code( $user_id ) {
		global $new_coupon_id, $coupon_code;

		$my_settings    = get_option( 'wps_wpr_settings_gallery' );
		$discount_typee = ! empty( $my_settings['wps_wpr_general_referal_coupon_purchase_type'] ) ? $my_settings['wps_wpr_general_referal_coupon_purchase_type'] : '';

		$coupon_code = ! empty( get_post_meta( $user_id, 'my_coupon_referal_code', true ) ) ? get_post_meta( $user_id, 'my_coupon_referal_code', true ) : '';
		$amount      = ! empty( get_post_meta( $user_id, 'my_coupon_referal_code_amount', true ) ) ? get_post_meta( $user_id, 'my_coupon_referal_code_amount', true ) : '0';

		if ( 'wps_wpr_percentage_coupon_points' == $discount_typee ) {

			$discount_type = 'percent';
		} else {
			$discount_type = 'fixed_cart';
		}

		$coupon = array(
			'post_title'   => $coupon_code,
			'post_content' => 'Points And Reward - User ID#' . $user_id,
			'post_status'  => 'publish',
			'post_excerpt' => 'Points And Reward - User ID#' . $user_id,
			'post_author'  => 1,
			'post_type'    => 'shop_coupon',
		);

		if ( ! is_admin() ) {
			require_once( ABSPATH . 'wp-admin/includes/post.php' );
		}

		if ( ! post_exists( $coupon_code ) ) {

			$new_coupon_id = wp_insert_post( $coupon );
			$wc_coupon     = new WC_Coupon( $coupon_code );
			$wc_coupon->set_discount_type( $discount_type );

			update_post_meta( $new_coupon_id, 'discount_type', $discount_type );
			update_post_meta( $new_coupon_id, 'coupon_amount', $amount );
			update_post_meta( $new_coupon_id, 'individual_use', 'no' );
			update_post_meta( $new_coupon_id, 'usage_limit', '' );
			update_post_meta( $new_coupon_id, 'apply_before_tax', 'yes' );
			update_post_meta( $new_coupon_id, 'free_shipping', 'no' );
			update_post_meta( $new_coupon_id, 'refferedby_coupon', $user_id );
		}

		if ( post_exists( $coupon_code ) ) {

			$wc_coupon = new WC_Coupon( $coupon_code );
			$wc_coupon->set_discount_type( $discount_type );
			$existing_coupon_id = $wc_coupon->get_id();

			update_post_meta( $existing_coupon_id, 'discount_type', $discount_type );
			update_post_meta( $existing_coupon_id, 'coupon_amount', $amount );
			update_post_meta( $existing_coupon_id, 'individual_use', 'no' );
			update_post_meta( $existing_coupon_id, 'usage_limit', '' );
			update_post_meta( $existing_coupon_id, 'apply_before_tax', 'yes' );
			update_post_meta( $existing_coupon_id, 'free_shipping', 'no' );
			update_post_meta( $existing_coupon_id, 'refferedby_coupon', $user_id );
		}
	}

	/** This Function is used for updating points.
	 * Mwb_wpr_pro_woocommerce_order_status_changed_points function.
	 *
	 * @param int    $order_id of the database array.
	 * @param string $old_status of the database array.
	 * @param string $new_status of the database array.
	 * @return void
	 */
	public function wps_wpr_pro_woocommerce_order_status_changed_points( $order_id, $old_status, $new_status ) {

		if ( $old_status != $new_status && 'completed' == $new_status ) {

			$order         = wc_get_order( $order_id );
			$order_user_id = $order->get_user_id();
			if ( empty( $order_user_id ) || is_null( $order_user_id ) ) {
				return;
			}
			$user_customer = $order->get_user();

			$user_emaill      = $user_customer->user_email;
			$used_cuopon      = $order->get_coupon_codes();
			$general_settings = get_option( 'wps_wpr_settings_gallery' );
			$referal_amt      = ! empty( $general_settings['wps_wpr_general_refer_value'] ) ? $general_settings['wps_wpr_general_refer_value'] : '';
			if ( isset( $used_cuopon ) && ! empty( $used_cuopon ) && is_array( $used_cuopon ) ) {
				foreach ( $used_cuopon as $coupon_code ) {
					$coupon_obj = new WC_Coupon( $coupon_code );
					$coupon_id = $coupon_obj->get_id();

					$coupon_user_id = get_post_meta( $coupon_id, 'refferedby_coupon', true );
					if ( ! isset( $coupon_user_id ) && empty( $coupon_user_id ) ) {
						continue;
					}
					$total_points    = get_user_meta( $coupon_user_id, 'wps_wpr_points', true );
					$myupdatedpoints = (int) $referal_amt + (int) $total_points;

					update_user_meta( $coupon_user_id, 'wps_wpr_points', $myupdatedpoints );
					update_user_meta( $order_user_id, 'user_visit_through_link', $coupon_user_id );
					$wps_wpr_notificatin_array = get_option( 'wps_wpr_notificatin_array', true );
					$user = get_user_by( 'ID', $coupon_user_id );

					$user_email = $user->user_email;
					if ( isset( $wps_wpr_notificatin_array['wps_wpr_point_on_coupon_setting_enable'] ) && isset( $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] ) ) {
						if ( '1' == $wps_wpr_notificatin_array['wps_wpr_point_on_coupon_setting_enable'] && '1' == $wps_wpr_notificatin_array['wps_wpr_notification_setting_enable'] ) {

							if ( is_array( $wps_wpr_notificatin_array ) && ! empty( $wps_wpr_notificatin_array ) ) {

								$wps_wpr_email_subject = isset( $wps_wpr_notificatin_array['wps_wpr_point_on_coupon_referal_subject'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_coupon_referal_subject'] : '';
								$wps_wpr_email_discription = isset( $wps_wpr_notificatin_array['wps_wpr_point_on_copon_referal_order_desc'] ) ? $wps_wpr_notificatin_array['wps_wpr_point_on_copon_referal_order_desc'] : '';
								$wps_wpr_email_discription = str_replace( '[COUPONCODE]', $coupon_code, $wps_wpr_email_discription );
								$wps_wpr_email_discription = str_replace( '[POINTS]', $referal_amt, $wps_wpr_email_discription );
								$user = get_user_by( 'email', $user_email );

								if ( Points_Rewards_For_WooCommerce_Admin::wps_wpr_check_mail_notfication_is_enable() ) {

									$customer_email = WC()->mailer()->emails['wps_wpr_email_notification'];
									if ( ! empty( $customer_email ) ) {
										$email_status = $customer_email->trigger( $coupon_user_id, $wps_wpr_email_discription, $wps_wpr_email_subject );
									}
								}
							}
						}
					}
						$points_log = get_user_meta( $coupon_user_id, 'points_details', true );
					if ( isset( $points_log['points_on_coupon_refer'] ) && ! empty( $points_log['points_on_coupon_refer'] ) ) {

						$points_on_coupon_refer = array();
						$points_on_coupon_refer = array(
							'points_on_coupon_refer' => $referal_amt,
							'date' => gmdate( 'Y-m-d' ),
							'refered_user' => $user_emaill,
						);
						$points_log['points_on_coupon_refer'][] = $points_on_coupon_refer;
					} else {
						if ( ! is_array( $points_log ) ) {
							$points_log = array();
						}
						$points_on_coupon_refer = array();
						$points_on_coupon_refer = array(
							'points_on_coupon_refer' => $referal_amt,
							'date' => gmdate( 'Y-m-d' ),
							'refered_user' => $user_emaill,
						);
						$points_log['points_on_coupon_refer'][] = $points_on_coupon_refer;
					}

						update_user_meta( $coupon_user_id, 'points_details', $points_log );
				}
			} else {
					return;
			}
		}
	}

	/**
	 * This function sends error notice on cart page.
	 * Mwb_wpr_pro_woocommerce_apply_product_on_coupon function.
	 *
	 * @param int    $valid of the database array.
	 * @param object $coupon of the database array.
	 * @param int    $object of the database array.
	 * @return       $valid.
	 * @throws \Lexik\Bundle\WorkflowBundle\Exception\WorkflowException Throwexception.
	 */
	public function wps_wpr_pro_woocommerce_apply_product_on_coupon( $valid, $coupon, $object ) {

		global $customer_orders;
		$coupon_id = $coupon->get_id();
		$user_id   = get_current_user_id();
		if ( empty( $user_id ) ) {
			return $valid;
		}
		$coupon_user_id = get_post_meta( $coupon_id, 'refferedby_coupon', true );

		if ( isset( $coupon_id ) && $user_id == $coupon_user_id ) {
			throw new Exception( __( 'Referral code cannot be used by self', 'ultimate-woocommerce-points-and-rewards' ), 100 );
		}

		$customer_orders = get_posts(
			array(
				'numberposts' => 1,
				'meta_key'    => '_customer_user',
				'meta_value'  => $user_id,
				'post_type'   => 'shop_order',
				'post_status' => wc_get_order_types(),
				'fields'      => 'ids',
			)
		);

		if ( count( $customer_orders ) > 0 && ! empty( $coupon_user_id ) ) {
			throw new Exception( __( 'The coupon code is valid only on the first order', 'ultimate-woocommerce-points-and-rewards' ), 100 );
		}
		return $valid;
	}

	/**
	 * Mwb_my_wpr_get_general_settings_num function
	 *
	 * @param int $id user id.
	 */
	public function wps_my_wpr_get_general_settings_num( $id ) {
		$wps_wpr_value = 0;
		$general_settings = get_option( 'wps_wpr_settings_gallery', true );
		if ( ! empty( $general_settings[ $id ] ) ) {
			$wps_wpr_value = (int) $general_settings[ $id ];
		}
		return $wps_wpr_value;
	}
}
