<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition PAI features
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The core plugin class.
 *
 * This is used to define Api feature.
 *
 * @since      1.0.1
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/includes
 * @author     makewebbetter <ticket@makewebbetter.com>
 */

if ( ! class_exists( 'Points_And_Rewards_For_Woocommerce_Pro_Api' ) ) {
	/**
	 * Points_And_Rewards_For_Woocommerce_Pro_Api class
	 */
	class Points_And_Rewards_For_Woocommerce_Pro_Api {
		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.2.0
		 */
		public function __construct() {
			$general_settings = get_option( 'wps_wpr_api_features_settings', true );
			if ( isset( $general_settings['wps_wpr_api_enable'] ) && 1 === $general_settings['wps_wpr_api_enable'] ) {
				add_action( 'rest_api_init', array( $this, 'wps_wpr_customer_endponts' ) );
			}

		}

		/**
		 * This function is used to create endpoints for user data.
		 *
		 * @since    1.2.0
		 */
		public function wps_wpr_customer_endponts() {
			register_rest_route(
				'wpr',
				'/wps-get-points/user',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'wps_wpr_get_customer_total_points' ),
					'permission_callback' => array( $this, 'wps_wpr_permission_check' ),
				)
			);
			register_rest_route(
				'wpr',
				'/wps-get-points/user/log',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'wps_wpr_get_customer_points_log' ),
					'permission_callback' => array( $this, 'wps_wpr_permission_check' ),
				)
			);

			register_rest_route(
				'wpr',
				'/wps-add-par-points/user',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'wps_par_update_customer_total_par_points' ),
					'permission_callback' => array( $this, 'wps_wpr_permission_check' ),
				)
			);
			register_rest_route(
				'wpr',
				'/wps-remove-par-points/user/',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'wps_par_reduce_customer_total_par_points' ),
					'permission_callback' => array( $this, 'wps_wpr_permission_check' ),
				)
			);
			register_rest_route(
				'wpr',
				'/mwb-get-points/user',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'wps_wpr_get_customer_total_points' ),
					'permission_callback' => array( $this, 'wps_wpr_permission_check' ),
				)
			);
			register_rest_route(
				'wpr',
				'/mwb-get-points/user/log',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'wps_wpr_get_customer_points_log' ),
					'permission_callback' => array( $this, 'wps_wpr_permission_check' ),
				)
			);

			register_rest_route(
				'wpr',
				'/mwb-add-par-points/user',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'wps_par_update_customer_total_par_points' ),
					'permission_callback' => array( $this, 'wps_wpr_permission_check' ),
				)
			);
			register_rest_route(
				'wpr',
				'/mwb-remove-par-points/user/',
				array(
					'methods'  => 'POST',
					'callback' => array( $this, 'wps_par_reduce_customer_total_par_points' ),
					'permission_callback' => array( $this, 'wps_wpr_permission_check' ),
				)
			);
		}

		/**
		 * Initialize call back for get total points and referal link.
		 *
		 * @param mixed $request for request.
		 * @since    1.0.11
		 */
		public function wps_wpr_get_customer_total_points( $request ) {
			global $woocommerce;

			$request_params = $request->get_params();
			$user_id = isset( $request_params['user_id'] ) ? absint( $request_params['user_id'] ) : '';

			$response = $this->wps_wpr_validate_user( $user_id );
			if ( isset( $response['data'] ) && isset( $response['data']['code'] ) && '404' === $response['data']['code'] ) {

				$response = new WP_REST_Response( $response );
				return $response;
			}
			$data = array(
				'user_id'    => $user_id,
				'total_points' => (int) get_user_meta( $user_id, 'wps_wpr_points', true ),
			);
			$get_referral = get_user_meta( $user_id, 'wps_points_referral', true );
			if ( isset( $get_referral ) && ! empty( $get_referral ) ) {
				$general_settings      = get_option( 'wps_wpr_settings_gallery', true );
				$wps_wpr_referral_page = ! empty( $general_settings['wps_wpr_referral_page'] ) ? $general_settings['wps_wpr_referral_page'] : '';
				$wps_wpr_page_url      = '';
				if ( ! empty( $wps_wpr_referral_page ) ) {
					$wps_wpr_page_url = get_page_link( $wps_wpr_referral_page[0] );
				} else {
					$wps_wpr_page_url = site_url();
				}

				$site_url = apply_filters( 'wps_wpr_referral_link_url', $wps_wpr_page_url );
				$referal_link = $site_url . '?pkey=' . $get_referral;
				$data['referal_link'] = $referal_link;
			}

			$response['status'] = 'success';
			$response['code']   = 200;
			$response['data']   = $data;
			$response           = new WP_REST_Response( $response );
			return $response;

		}
		/**
		 * Initialize call back for get points log.
		 *
		 * @param mixed $request is for request.
		 * @since    1.0.11
		 */
		public function wps_wpr_get_customer_points_log( $request ) {
			global $woocommerce;

			$request_params = $request->get_params();
			$user_id = isset( $request_params['user_id'] ) ? absint( $request_params['user_id'] ) : '';

			$response = $this->wps_wpr_validate_user( $user_id );
			if ( isset( $response['data'] ) && isset( $response['data']['code'] ) && '404' === $response['data']['code'] ) {

				$response = new WP_REST_Response( $response );
				return $response;
			}

			$data = array(
				'user_id'    => $user_id,
			);
			$wps_user_level = get_user_meta( $user_id, 'membership_level', true );
			if ( isset( $wps_user_level ) && ! empty( $wps_user_level ) ) {
				$data['membership_level'] = $wps_user_level;
			}
			$points_log  = get_user_meta( $user_id, 'points_details', true );

			if ( isset( $points_log ) && ! empty( $points_log ) && is_array( $points_log ) ) {

				// Signup Event log.
				if ( array_key_exists( 'registration', $points_log ) ) {
					$coupon_data = array();
					foreach ( $points_log['registration'] as $key => $value ) {
						$coupon_data[ $key ] = array(
							'points' => $value['registration'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['signup_event_points_log'] = $coupon_data;
				}

				// Import points log.
				if ( array_key_exists( 'import_points', $points_log ) ) {
					$coupon_data = array();
					foreach ( $points_log['import_points'] as $key => $value ) {
						$coupon_data[ $key ] = array(
							'points' => $value['import_points'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['import_points_log'] = $coupon_data;
				}

				// Coupon creation log.
				if ( array_key_exists( 'Coupon_details', $points_log ) ) {
					$coupon_data = array();
					foreach ( $points_log['Coupon_details'] as $key => $value ) {
						$coupon_data[ $key ] = array(
							'points' => - $value['Coupon_details'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['coupon_creation_points_log'] = $coupon_data;
				}

				// Points earn via particular product log.
				if ( array_key_exists( 'product_details', $points_log ) ) {
					$product_data = array();
					foreach ( $points_log['product_details'] as $key => $value ) {
						$product_data[ $key ] = array(
							'points' => $value['product_details'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['points_earn_via_partiular_product_points_log'] = $product_data;
				}

				// Points earn via per currency log.
				if ( array_key_exists( 'pro_conversion_points', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['pro_conversion_points'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => $value['pro_conversion_points'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['points_earn_via_per_currency_conversion_points_log'] = $points_data;
				}
				// Points earn via order total points.
				if ( array_key_exists( 'points_on_order', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['points_on_order'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => $value['points_on_order'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['points_earn_on_order_total_points_log'] = $points_data;
				}
				// Deducted Points earned on Order Total on Order Refund points log.
				if ( array_key_exists( 'refund_points_on_order', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['refund_points_on_order'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['refund_points_on_order'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['refund_order_points_log'] = $points_data;
				}

				// Deducted Points earned on Order Total on Order Cancellation points log.
				if ( array_key_exists( 'cancel_points_on_order_total', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['cancel_points_on_order_total'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['cancel_points_on_order_total'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['cancel_order_points_log'] = $points_data;
				}

				// Points earned via giving review/comment points log.
				if ( array_key_exists( 'comment', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['comment'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['comment'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['points_earned_via_review_comment_points_log'] = $points_data;
				}

				// Membership Points log.
				if ( array_key_exists( 'membership', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['membership'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['membership'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['membership_points_log'] = $points_data;
				}

				// Deduction of points as you have purchased your product through points log.
				if ( array_key_exists( 'pur_by_points', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['pur_by_points'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['pur_by_points'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['product_purchase_through_points_log'] = $points_data;
				}

				// Deduction of points for your return request points log.
				if ( array_key_exists( 'deduction_of_points', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['deduction_of_points'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['deduction_of_points'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['deduct_points_for_return_request_points_log'] = $points_data;
				}

				// Points returned successfully on your return request points log log.
				if ( array_key_exists( 'return_pur_points', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['return_pur_points'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => $value['return_pur_points'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['return_request_products_points_log'] = $points_data;
				}

				// Deduct Per Currency Spent Point on your return request points log.
				if ( array_key_exists( 'deduction_currency_spent', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['deduction_currency_spent'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['deduction_currency_spent'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['deduct_per_curency_return_request_points_log'] = $points_data;
				}

				// Applied on Cart points log.
				if ( array_key_exists( 'cart_subtotal_point', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['cart_subtotal_point'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['cart_subtotal_point'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['applied_on_cart_points_log'] = $points_data;
				}

				// Order rewards points.
				if ( array_key_exists( 'order__rewards_points', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['order__rewards_points'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => + $value['order__rewards_points'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['order_rewards__points'] = $points_data;
				}

				// Expired points log.
				if ( array_key_exists( 'expired_details', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['expired_details'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['expired_details'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['expired_points_log'] = $points_data;
				}

				// Order Points Deducted due to Cancelation of Order points log.
				if ( array_key_exists( 'deduct_currency_pnt_cancel', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['deduct_currency_pnt_cancel'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['deduct_currency_pnt_cancel'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['deduct_pre_currency_spent_on_cancel_order_points_log'] = $points_data;
				}
				// Assigned Points Deducted due Cancelation of Order.
				if ( array_key_exists( 'deduct_bcz_cancel', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['deduct_bcz_cancel'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => - $value['deduct_bcz_cancel'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['deduct_assign_product_points_on_cancel_order_points_log'] = $points_data;
				}

				// Points Returned due to Cancelation of Order.
				if ( array_key_exists( 'pur_points_cancel', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['pur_points_cancel'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => $value['pur_points_cancel'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['return_due_to_cancel_order_points_only_product_points_log'] = $points_data;
				}

				// Points deducted for purchasing the product.
				if ( array_key_exists( 'pur_pro_pnt_only', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['pur_pro_pnt_only'] as $key => $value ) {
						$points_data[ $key ] = array(
							'points' => $value['pur_pro_pnt_only'],
							'date'   => $value['date'],
						);

					}
					$data['points_log']['deduct_points_only_product_points_log'] = $points_data;
				}

				// Points deducted successfully as you have shared your points.
				if ( array_key_exists( 'Sender_point_details', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['Sender_point_details'] as $key => $value ) {
						$user_name = '';
						if ( isset( $value['given_to'] ) && ! empty( $value['given_to'] ) ) {
							$user      = get_user_by( 'ID', $value['given_to'] );
							if ( isset( $user ) && ! empty( $user ) ) {
								$user_name = $user->user_nicename;
							} else {
								$user_name = esc_html__( 'This user doesn\'t exist', 'ultimate-woocommerce-points-and-rewards' );
							}
						}
						$points_data[ $key ] = array(
							'points' => $value['Sender_point_details'],
							'date'   => $value['date'],
							'shared_to_user'   => $user_name,
						);

					}
					$data['points_log']['sender_point_details_log'] = $points_data;
				}
				// Points received successfully as someone has shared.
				if ( array_key_exists( 'Receiver_point_details', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['Receiver_point_details'] as $key => $value ) {
						$user_name = '';
						if ( isset( $value['received_by'] ) && ! empty( $value['received_by'] ) ) {
							$user      = get_user_by( 'ID', $value['received_by'] );
							if ( isset( $user ) && ! empty( $user ) ) {
								$user_name = $user->user_nicename;
							} else {
								$user_name = esc_html__( 'This user doesn\'t exist', 'ultimate-woocommerce-points-and-rewards' );
							}
						}
						$points_data[ $key ] = array(
							'points' => $value['Receiver_point_details'],
							'date'   => $value['date'],
							'received_by_user'   => $user_name,
						);

					}
					$data['points_log']['receiver_point_details_log'] = $points_data;
				}
				// Points received successfully as someone has shared.
				if ( array_key_exists( 'admin_points', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['admin_points'] as $key => $value ) {

						$points_data[ $key ] = array(
							'points' => $value['admin_points'],
							'date'   => $value['date'],
							'sign'   => $value['sign'],
							'reason' => $value['reason'],
						);

					}
					$data['points_log']['updated_by_admin_points_log'] = $points_data;
				}
				// Referral Sign Up.
				if ( array_key_exists( 'reference_details', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['reference_details'] as $key => $value ) {
						$user_name = '';
						if ( isset( $value['refered_user'] ) && ! empty( $value['refered_user'] ) ) {
							$user      = get_user_by( 'ID', $value['refered_user'] );
							if ( isset( $user ) && ! empty( $user ) ) {
								$user_name = $user->user_login;
							} else {
								$user_name = esc_html__( 'This user does not exist', 'points-and-rewards-for-woocommerce' );
							}
						}
						$points_data[ $key ] = array(
							'points' => $value['reference_details'],
							'date'   => $value['date'],
							'refered_user'   => $user_name,
						);

					}
					$data['points_log']['referral_sign_up_points_log'] = $points_data;
				}

				// Points earned by the purchase has been made by referrals.
				if ( array_key_exists( 'ref_product_detail', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['ref_product_detail'] as $key => $value ) {
						$user_name = '';
						if ( isset( $value['refered_user'] ) && ! empty( $value['refered_user'] ) ) {
							$user      = get_user_by( 'ID', $value['refered_user'] );
							if ( isset( $user ) && ! empty( $user ) ) {
								$user_name = $user->user_login;
							} else {
								$user_name = esc_html__( 'This user doesn\'t exist', 'points-and-rewards-for-woocommerce' );
							}
						}
						$points_data[ $key ] = array(
							'points' => $value['ref_product_detail'],
							'date'   => $value['date'],
							'refered_user'   => $user_name,
						);

					}
					$data['points_log']['referral_product_purchase_points_log'] = $points_data;
				}

				// Birthday Points log.
				if ( array_key_exists( 'points_on_birthday', $points_log ) ) {
					$points_data = array();
					foreach ( $points_log['points_on_birthday'] as $key => $values ) {
						$points_data[ $key ] = array(
							'points' => + $values['points_on_birthday'],
							'date'   => $values['date'],
						);
					}
					$data['points_log']['birthday_awarded_points'] = $points_data;
				}
			} else {
				$data['points_log'] = __( 'No Points Generated Yet.', 'ultimate-woocommerce-points-and-rewards' );
			}

			$user_coupon_log = get_user_meta( $user_id, 'wps_wpr_user_log', true );
			if ( isset( $user_coupon_log ) && ! empty( $user_coupon_log ) ) {
				$coupon_data = array();
				if ( is_array( $user_coupon_log ) ) {
					foreach ( $user_coupon_log as $key => $value ) {
						$wps_coupon_id = explode( '#', $key );
						if ( isset( $value['code'] ) && ! empty( $value['code'] ) ) {
							$wps_coupon_amount      = get_post_meta( $wps_coupon_id[1], 'coupon_amount', true );
							$wps_coupon_left_amount = explode( ';', $value['left'] );
							$code                   = $value['code'];
							$date__time             = $value['expiry'];

							if ( ! empty( $date__time ) && 'No Expiry' != $value['expiry'] ) {
								$date__time = new DateTime( "@$date__time" );
								$date__time = $date__time->format( 'Y-m-d' );
							}
							// WOOCS - WooCommerce Currency Switcher Compatibility.
							$coupon_data[][ $code ] = array(
								'points' => $value['points'],
								'coupon_code'   => $value['code'],
								'coupon_amount' => apply_filters( 'wps_wpr_show_conversion_price', $wps_coupon_amount ),
								'left_amount'   => apply_filters( 'wps_wpr_show_conversion_price', $wps_coupon_left_amount[1] ),
								'expiry_date'   => $date__time,
								'currency'      => get_woocommerce_currency(),
							);
						}
					}
				}
				$data['coupon_log'] = $coupon_data;
			}
			$response['status'] = 'success';
			$response['code'] = 200;
			$response['data'] = $data;
			$response = new WP_REST_Response( $response );
			return $response;

		}
		/**
		 * Initialize call back to reduce total points.
		 *
		 * @param mixed $request for request.
		 * @since    1.0.0
		 */
		public function wps_par_reduce_customer_total_par_points( $request ) {
			$request_params = $request->get_params();
			$user_id        = isset( $request_params['user_id'] ) ? absint( $request_params['user_id'] ) : '';
			$points         = isset( $request_params['points'] ) ? absint( $request_params['points'] ) : '';
			$desc           = isset( $request_params['reason'] ) ? $request_params['reason'] : __( 'Loyality', 'ultimate-woocommerce-points-and-rewards' );
			$user_auth      = $this->wps_wpr_validate_user( $user_id );
			if ( isset( $user_auth['data'] ) && isset( $user_auth['data']['code'] ) && '404' === $user_auth['data']['code'] ) {

				$response = new WP_REST_Response( $user_auth );
				return $response;
			}
			if ( ! empty( $points ) ) {
				$user_points = get_user_meta( $user_id, 'wps_wpr_points', true );
				if ( empty( $user_points ) ) {
					$user_points = 0;
				}
				if ( $user_points - $points >= 0 ) {
					update_user_meta( $user_id, 'wps_wpr_points', $user_points - $points );

					/* Get the points of the points details*/
					$today_date = date_i18n( 'Y-m-d h:i:sa' );
					$api_points = get_user_meta( $user_id, 'api_points_details', true );
					if ( empty( $api_points ) ) {
						$api_points = array();
					}
					$api_array = array(
						'api_points' => $points,
						'date'       => $today_date,
						'sign'       => '-',
						'reason'     => $desc,
					);
					$api_points[] = $api_array;

					// Update the points details.
					if ( ! empty( $api_points ) && is_array( $api_points ) ) {
						update_user_meta( $user_id, 'api_points_details', $api_points );
					}

					// Success response.
					$data = array(
						'status'  => 'success',
						'code'    => 200,
						'message' => __( 'Points Reduced Successfully', 'ultimate-woocommerce-points-and-rewards' ),
					);
					// Points log creation.
					$points_log = get_user_meta( $user_id, 'points_details', true );
					$points_log = ! empty( $points_log ) && is_array( $points_log ) ? $points_log : array();
					if ( isset( $points_log['add_points_using_api'] ) && ! empty( $points_log['add_points_using_api'] ) ) {

						$points_arr = array();
						$points_arr = array(
							'add_points_using_api' => $points,
							'date'                 => $today_date,
							'sign'                 => '-',
							'reason'               => $desc,
						);
						$points_log['add_points_using_api'][] = $points_arr;
					} else {
						if ( ! is_array( $points_log ) ) {
							$points_log = array();
						}
						$points_arr = array();
						$points_arr = array(
							'add_points_using_api' => $points,
							'date'                 => $today_date,
							'sign'                 => '-',
							'reason'               => $desc,
						);
						$points_log['add_points_using_api'][] = $points_arr;
					}
					update_user_meta( $user_id, 'points_details', $points_log );

				} else {
					// Success response.
					$data = array(
						'status'  => 'error',
						'code'    => 404,
						/* translators: %s: points. */
						'message' => sprintf( __( 'Sorry! Points go in the negative. Users have only %s points.', 'ultimate-woocommerce-points-and-rewards' ), $user_points ),
					);
				}
			} else {
				$data = array(
					'status' => 'error',
					'code'   => 404,
					'message' => __( 'No, points have been given to reduce', 'ultimate-woocommerce-points-and-rewards' ),
				);
			}
			$response['data'] = $data;
			return new WP_REST_Response( $response );
		}
		/**
		 * Initialize call back to update total points.
		 *
		 * @param mixed $request for request.
		 * @since    1.0.0
		 */
		public function wps_par_update_customer_total_par_points( $request ) {
			$request_params = $request->get_params();
			$user_id        = isset( $request_params['user_id'] ) ? absint( $request_params['user_id'] ) : '';
			$points         = isset( $request_params['points'] ) ? absint( $request_params['points'] ) : '';
			$desc           = isset( $request_params['reason'] ) ? $request_params['reason'] : __( 'Loyality', 'ultimate-woocommerce-points-and-rewards' );
			$user_auth      = $this->wps_wpr_validate_user( $user_id );
			if ( isset( $user_auth['data'] ) && isset( $user_auth['data']['code'] ) && '404' === $user_auth['data']['code'] ) {

				$response = new WP_REST_Response( $user_auth );
				return $response;
			}
			if ( ! empty( $points ) ) {
				$user_points  = get_user_meta( $user_id, 'wps_wpr_points', true );
				if ( empty( $user_points ) ) {
					$user_points = 0;
				}
				update_user_meta( $user_id, 'wps_wpr_points', $user_points + $points );

				/* Get the points of the points details*/
				$today_date = date_i18n( 'Y-m-d h:i:sa' );
				$api_points = get_user_meta( $user_id, 'api_points_details', true );
				if ( empty( $api_points ) ) {
					$api_points = array();
				}
				$api_array = array(
					'api_points' => $points,
					'date'       => $today_date,
					'sign'       => '+',
					'reason'     => $desc,
				);
				$api_points[] = $api_array;

				// Update the points details.
				if ( ! empty( $api_points ) && is_array( $api_points ) ) {
					update_user_meta( $user_id, 'api_points_details', $api_points );
				}

				// Success response.
				$data = array(
					'status'  => 'success',
					'code'    => 200,
					'message' => __( 'Points Updated Successfully', 'ultimate-woocommerce-points-and-rewards' ),
				);
				// Points log creation.
				$points_log = get_user_meta( $user_id, 'points_details', true );
				$points_log = ! empty( $points_log ) && is_array( $points_log ) ? $points_log : array();
				if ( isset( $points_log['add_points_using_api'] ) && ! empty( $points_log['add_points_using_api'] ) ) {

					$points_arr = array();
					$points_arr = array(
						'add_points_using_api' => $points,
						'date'                 => $today_date,
						'sign'                 => '+',
						'reason'               => $desc,
					);
					$points_log['add_points_using_api'][] = $points_arr;
				} else {
					if ( ! is_array( $points_log ) ) {
						$points_log = array();
					}
					$points_arr = array();
					$points_arr = array(
						'add_points_using_api' => $points,
						'date'                 => $today_date,
						'sign'                 => '+',
						'reason'               => $desc,
					);
					$points_log['add_points_using_api'][] = $points_arr;
				}
				update_user_meta( $user_id, 'points_details', $points_log );

			} else {
				$data = array(
					'status'  => 'error',
					'code'    => 404,
					'message' => __( 'No, points have been given to update', 'ultimate-woocommerce-points-and-rewards' ),
				);
			}
			$response['data'] = $data;
			return new WP_REST_Response( $response );
		}

		/**
		 * API validation
		 *
		 * @param mixed $request for request.
		 * @since    1.0.11
		 */
		public function wps_wpr_permission_check( $request ) {
			$result = false;
			$request_params = $request->get_params();
			$wps_secretkey = isset( $request_params['consumer_secret'] ) ? $request_params['consumer_secret'] : '';

			$result = $this->wps_wpr_validate_secretkey( $wps_secretkey );
			return $result;
		}

		/**
		 * Valiadte secret key
		 *
		 * @param mixed $wps_secretkey for secretkey.
		 * */
		public function wps_wpr_validate_secretkey( $wps_secretkey ) {
			$general_settings = get_option( 'wps_wpr_api_features_settings', true );
			$wps_secret_code  = '';
			if ( isset( $general_settings['wps_wpr_api_enable'] ) && 1 === $general_settings['wps_wpr_api_enable'] ) {
				$wps_secret_code = isset( $general_settings['wps_wpr_api_secret_key'] ) ? $general_settings['wps_wpr_api_secret_key'] : '';
			}
			if ( '' === $wps_secretkey ) {
				return false;
			} elseif ( trim( $wps_secret_code ) === trim( $wps_secretkey ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Validate user
		 *
		 * @param mixed $user_id for userid.
		 * @since    1.0.11
		 */
		public function wps_wpr_validate_user( $user_id ) {
				$data = array();
				// non-existent IDs return a valid WP_User object with the user ID = 0.
				$customer = new WP_User( $user_id );
				// validate ID.
			if ( empty( $user_id ) ) {
				$data = array(
					'status'  => 'error',
					'code'    => 404,
					'message' => __( 'Invalid user ID', 'ultimate-woocommerce-points-and-rewards' ),

				);
			} elseif ( 0 === $customer->ID ) {
				$data = array(
					'status' => 'error',
					'code'   => 404,
					'message' => __( 'Invalid user', 'ultimate-woocommerce-points-and-rewards' ),

				);
			} else {
				$data = array(
					'code'   => 200,

				);
			}
				$response['data'] = $data;
				return $response;

		}

	}
	new Points_And_Rewards_For_Woocommerce_Pro_Api();
}

