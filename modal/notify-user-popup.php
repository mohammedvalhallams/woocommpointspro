<?php
/**
 * Exit if accessed directly.
 *
 * @package file
 */

/*  Popup Style */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<style type="text/css">

	.wps-pr-popup-tab-wrapper .tab-link:after {
		background-color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?> !important;
		border: 1px solid <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?> !important;
	}

	.wps-pr-popup-tab-wrapper .tab-link {
		color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?>;
	}

	.wps-pr-popup-tab-wrapper .tab-link.active {
		border: 1px solid <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?> !important;
		color: #ffffff !important;
	}

	.wps-pr-popup-rewards-right-content a {
		color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?>;
	}

	.wps-pr-close-btn:hover {
		color: #ffffff !important;
		background-color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?>;
	}

	.wps-pr-popup-rewards-right-content a:hover {
	  
		color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?>;
		opacity: .75;
	}    

</style>
<?php

$is_wps_wpr_enabled = $this->wps_wpr_general_setting_enable();
$get_points         = 0;
$position_class     = $this->wps_wpr_enable_notification_addon_button_position();

if ( $position_class ) {
	$position = 'left: 10px !important';
} else {
	$position = 'right: 10px !important';
}

if ( $this->wps_wpr_check_enabled_notification_addon() && $is_wps_wpr_enabled ) {

	$user_id = get_current_user_ID();
	if ( is_user_logged_in() ) {
		$wps_guest_user = false;
		if ( isset( $user_id ) && ! empty( $user_id ) ) {
			$get_points = (int) get_user_meta( $user_id, 'wps_wpr_points', true );
		}
	} else {
		$wps_guest_user = true;
	}
	$order_conversion_rate = $this->wps_wpr_order_conversion_rate();
	$referral_link = $this->wps_wpr_get_referral_link( $user_id );

	$general_settings      = get_option( 'wps_wpr_settings_gallery', true );
	$wps_wpr_referral_page = ! empty( $general_settings['wps_wpr_referral_page'] ) ? $general_settings['wps_wpr_referral_page'] : '';
	$wps_wpr_page_url      = '';
	if ( ! empty( $wps_wpr_referral_page ) ) {
		$wps_wpr_page_url = get_page_link( $wps_wpr_referral_page[0] );
	} else {
		$wps_wpr_page_url = site_url();
	}

	$site_url = apply_filters( 'wps_wpr_referral_link_url', $wps_wpr_page_url );
	$purchase_product_using_pnt_rate = $this->wps_wpr_purchase_product_using_pnt_rate();
	$apply_point_on_cart_rate = $this->wps_wpr_apply_point_on_cart_rate();
	$convert_points_to_coupons_rate = $this->wps_wpr_convert_points_to_coupons_rate();

	$button_text = $this->wps_wpr_notification_button_text();
	?>
	<div id="modal" class="modal modal__bg wps_wpr_modal_wrapper">
	  <div class="modal__dialog wps-pr-dialog">
		<div class="modal__content">
		  <div class="modal__header">
			<div class="modal__title">
			  <h2 class="modal__title-text"><?php esc_html_e( 'How to Earn More!', 'ultimate-woocommerce-points-and-rewards' ); ?></h2>
			</div>
			<span class="mdl-button mdl-button--icon mdl-js-button  material-icons  modal__close"></span>
		  </div>
		  <div class="modal__text wps-pr-popup-text">
			<div class="wps-pr-popup-body">
							  <div class="wps-popup-points-label">
				  <h6><?php esc_html_e( 'Total Point : ', 'ultimate-woocommerce-points-and-rewards' ); ?><?php echo esc_html( $get_points ); ?></h6>
				</div>
				<div class="wps-pr-popup-tab-wrapper">
				<a class="tab-link active" id ="notify_user_gain_tab" href="javascript:;"><?php esc_html_e( 'Gain Points', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
				<a class="tab-link" href="javascript:;" id="notify_user_redeem"><?php esc_html_e( 'Redeem Points', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
			  </div>
			  <!--wps-pr-popup- rewards tab-container-->
			  <div class="wps-pr-popup-tab-container active" id="notify_user_earn_more_section">
				<ul class="wps-pr-popup-tab-listing wps-pr-popup-rewards-tab-listing">
				  <?php if ( $this->wps_wpr_check_signup_enable() && ! is_user_logged_in() ) { ?>
				  <li>
					<div class="wps-pr-popup-left-rewards">
					  <div class="wps-pr-popup-rewards-icon">
						<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
					  </div>
					  
						<div class="wps-pr-popup-rewards-left-content">
						  <h6><?php esc_html_e( 'Register Your Self and Earn', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
						  <span>
						  <?php
							echo esc_html( $this->wps_wpr_get_signup_value() );
							esc_html_e( ' Points', 'ultimate-woocommerce-points-and-rewards' );
							?>
							</span>
						</div>
					</div>
					<div class="wps-pr-popup-right-rewards">
					  <div class="wps-pr-popup-rewards-right-content">
					  </div>
					</div>
				  </li>
						<?php
				  }
				  if ( $this->wps_wpr_is_order_conversion_enabled() ) {
						?>
				  <li>
					<div class="wps-pr-popup-left-rewards">
					  <div class="wps-pr-popup-rewards-icon">
						<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/shopping-cart.png'; ?>" alt="">
					  </div>
					  <div class="wps-pr-popup-rewards-left-content">
					  <?php esc_html_e( 'Place The Order and Earn Points', 'ultimate-woocommerce-points-and-rewards' ); ?>         
						<span>
						<?php
						esc_html_e( 'Earn ', 'ultimate-woocommerce-points-and-rewards' );
						echo wp_kses_post( $order_conversion_rate['Value'] );
						esc_html_e( ' Points on every ', 'ultimate-woocommerce-points-and-rewards' );
						echo wp_kses_post( $this->wps_wpr_value_return_in_currency( $order_conversion_rate['Points'] ) );
						esc_html_e( ' spent', 'ultimate-woocommerce-points-and-rewards' );
						?>
						</span>
					  </div>
					</div>
					<div class="wps-pr-popup-right-rewards">
					  <div class="wps-pr-popup-rewards-right-content">
					  </div>
					</div>
				  </li>
					  <?php
				  }
				  if ( $this->wps_wpr_is_review_reward_enabled() ) {
						?>
				  <li>
					<div class="wps-pr-popup-left-rewards">
					  <div class="wps-pr-popup-rewards-icon">
						<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
					  </div>
					  <div class="wps-pr-popup-rewards-left-content">
						<h6><?php esc_html_e( 'Write Review ', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
						<span>
						<?php
						esc_html_e( 'Reward is : ', 'ultimate-woocommerce-points-and-rewards' );
						echo esc_html( $this->wps_wpr_get_reward_review() );
						esc_html_e( ' Point', 'ultimate-woocommerce-points-and-rewards' );
						?>
						</span>
					  </div>
					</div>
					<div class="wps-pr-popup-right-rewards">
					  <div class="wps-pr-popup-rewards-right-content">
					  </div>
					</div>
				  </li>
					  <?php
				  }
				  if ( $this->wps_wpr_check_referal_enable() ) {
						?>
				  <li>
					<div class="wps-pr-popup-left-rewards">
					  <div class="wps-pr-popup-rewards-icon">
						<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
					  </div>
					  <?php if ( ! $this->wps_wpr_is_only_referral_purchase_enabled() ) { ?>
					  <div class="wps-pr-popup-rewards-left-content">
						<h6><?php esc_html_e( 'Refer Someone', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
						<span>
							<?php
							esc_html_e( 'Reward is : ', 'ultimate-woocommerce-points-and-rewards' );
							echo wp_kses_post( $this->wps_wpr_get_referral_reward() );
							esc_html_e( ' Point', 'ultimate-woocommerce-points-and-rewards' );
							?>
						</span>
					  </div>
					<?php } else { ?>
					  <div class="wps-pr-popup-rewards-left-content">
						<h6><?php esc_html_e( 'Referral Link', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
						<span><?php esc_html_e( 'Share this link and get a reward on their purchase only ', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
					  </div>
					<?php } ?>
					</div>
					  <?php if ( ! $wps_guest_user ) { ?>
					  <div class="wps-pr-popup-right-rewards wps-pr-popup-right-rewards--login">
						<div class="wps-pr-popup-rewards-right-content">
						  <span id="wps_notify_user_copy"><?php echo wp_kses_post( $site_url . '?pkey=' . $referral_link ); ?></span>
						</div>
						<div class="wps-pr-popup-rewards-right-content">
						  <button class="wps_wpr_btn_copy wps_tooltip" data-clipboard-target="#wps_notify_user_copy" aria-label="copied">
						  <span class="wps_tooltiptext"><?php esc_html_e( 'Copy', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
						  <img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/copy.png'; ?>" alt="Copy to clipboard"></button>
						</div>
					  </div>
					<?php } ?>
				  </li>
					  <?php
				  }
				  if ( $this->wps_wpr_is_referral_purchase_enabled() ) {
					$public_obj = $this->generate_public_obj();
					$wps_wpr_general_referal_purchase_point_type = $public_obj->wps_wpr_get_general_settings( 'wps_wpr_general_referal_purchase_point_type' );
						?>
					<li>
					<div class="wps-pr-popup-left-rewards">
						<div class="wps-pr-popup-rewards-icon">
						<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
						</div>
						<?php
						if ( 'wps_wpr_fixed_points' == $wps_wpr_general_referal_purchase_point_type ) {
						?>
						<div class="wps-pr-popup-rewards-left-content">
							<h6><?php esc_html_e( 'Earn on Someone else Purchasing ', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
							<span>
							<?php
							esc_html_e( 'Reward is : ', 'ultimate-woocommerce-points-and-rewards' );
							echo esc_html( $this->wps_wpr_get_referral_purchase_reward() );
							esc_html_e( ' Point', 'ultimate-woocommerce-points-and-rewards' );
							?>
							</span>
						</div>
						<?php
						} else {
							?>
							<div class="wps-pr-popup-rewards-left-content">
								<h6><?php esc_html_e( 'Earn on Someone else Purchasing ', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
								<span>
								<?php
								esc_html_e( 'You will get : ', 'ultimate-woocommerce-points-and-rewards' );
								echo esc_html( $this->wps_wpr_get_referral_purchase_reward() );
								esc_html_e( '% of Order total', 'ultimate-woocommerce-points-and-rewards' );
								?>
								</span>
							</div>
							<?php
						}
						?>
					</div>
					<div class="wps-pr-popup-right-rewards">
					  <div class="wps-pr-popup-rewards-right-content">
					  </div>
					</div>
				  </li>
					  <?php
				  }
				  if ( ! $this->wps_wpr_check_signup_enable() && ! $this->wps_wpr_is_order_conversion_enabled() && ! $this->wps_wpr_is_review_reward_enabled() && ! $this->wps_wpr_is_referral_purchase_enabled() && ! $this->wps_wpr_check_referal_enable() ) {
						?>
				  <li>
					<div class="wps-pr-popup-left-rewards">
					  <div class="wps-pr-popup-rewards-icon">
					  </div>
					  <div class="wps-pr-popup-rewards-left-content">
						<h6><?php esc_html_e( 'No Features Are Available Right Now! ', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
					  </div>
					</div>
					<div class="wps-pr-popup-right-rewards">
					  <div class="wps-pr-popup-rewards-right-content">
					  </div>
					</div>
				  </li>
					  <?php
				  }
					?>
				</ul>
			  </div><!--wps-pr-popup- rewards tab-container-->
			  <!--wps-pr-popup- earn more tab-container-->
			  <div class="wps-pr-popup-tab-container" id="notify_user_redeem_section">
				<ul class="wps-pr-popup-tab-listing">
				  <?php if ( $this->wps_wpr_is_purchase_product_using_points_enabled() ) { ?>
				  <li>
					<div class="wps-pr-tab-popup-icon">
					   <img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/shopping-cart.png'; ?>" alt="">
					</div>
					<div class="wps-pr-tab-popup-content">
					  <h6><?php esc_html_e( 'Redeem Points on Particluar Products', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
					  <p>
						<?php
						esc_html_e( 'Conversion Rule : ', 'ultimate-woocommerce-points-and-rewards' );
						echo wp_kses_post( $this->wps_wpr_return_conversion_rate( $purchase_product_using_pnt_rate['Points'], $purchase_product_using_pnt_rate['Currency'] ) );
						?>
						</p>
					</div>
				  </li>
						<?php
				  }
				  if ( $this->wps_wpr_is_apply_point_on_cart_enabled() ) {
						?>
					<li>
					  <div class="wps-pr-tab-popup-icon">
						<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/shopping-cart.png'; ?>" alt="">
					  </div>
					  <div class="wps-pr-tab-popup-content">
						<h6><?php esc_html_e( 'Apply Points on Cart Total', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
						<p>
						<?php
						esc_html_e( 'Conversion Rule : ', 'ultimate-woocommerce-points-and-rewards' );
						echo wp_kses_post( $this->wps_wpr_return_conversion_rate( $apply_point_on_cart_rate['Points'], $apply_point_on_cart_rate['Currency'] ) );
						?>
						</p>
					  </div>
					</li>
					  <?php
				  }
				  if ( $this->wps_wpr_is_convert_points_to_coupon_enabled() ) {
						?>
				  <li>
					<div class="wps-pr-tab-popup-icon">
					  <img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
					</div>
					<div class="wps-pr-tab-popup-content">
					  <h6><?php esc_html_e( 'Convert Points into Coupons', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
					  <p>
					  <?php
						esc_html_e( 'Conversion Rule : ', 'ultimate-woocommerce-points-and-rewards' );
						echo wp_kses_post( $this->wps_wpr_return_conversion_rate( $convert_points_to_coupons_rate['Points'], $convert_points_to_coupons_rate['Currency'] ) );
						?>
						</p>
					</div>
				  </li>
					  <?php
				  }
				  if ( ! $this->wps_wpr_is_convert_points_to_coupon_enabled() && ! $this->wps_wpr_is_apply_point_on_cart_enabled() && ! $this->wps_wpr_is_purchase_product_using_points_enabled() ) {
						?>
				  <li>
					<div class="wps-pr-popup-left-rewards">
					  <div class="wps-pr-popup-rewards-icon">
					  </div>
					  <div class="wps-pr-popup-rewards-left-content">
						<h6><?php esc_html_e( 'No Features Are Available Right Now! ', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
					  </div>
					</div>
					<div class="wps-pr-popup-right-rewards">
					  <div class="wps-pr-popup-rewards-right-content">
					  </div>
					</div>
				  </li>
					  <?php
				  }
					?>
				</ul>
			  </div>
			</div><!--wps-pr-popup-body-->
		  </div>
		  <div class="modal__footer wps-pr-footer">
			<a class="mdl-button mdl-button--colored mdl-js-button  modal__close wps-pr-close-btn" style="border-color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?>; color:<?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?>"> <?php esc_html_e( 'Close', 'ultimate-woocommerce-points-and-rewards' ); ?>
			</a>
		  </div>
		</div>
	  </div>
	</div>
  <!--=====================================
  =            for mobile view            =
  ======================================-->
  <div class="wps-pr-mobile-popup-main-container">
	<div class="wps-pr-mobile-popup-wrapper">
	 <div class="wps-pr-popup-body">
	   <?php if ( is_user_logged_in() ) { ?>
		  <div class="wps-popup-points-label">
			<h6><?php esc_html_e( 'Total Point : ', 'ultimate-woocommerce-points-and-rewards' ); ?><?php echo esc_html( $get_points ); ?></h6>
		  </div>
		<?php } ?>
	  <div class="wps-pr-popup-tab-wrapper">
		<a class="tab-link active" id ="notify_user_gain_tab_mobile" href="javascript:;"><?php esc_html_e( 'Gain Points', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
		<a class="tab-link" href="javascript:;" id="notify_user_redeem_mobile"><?php esc_html_e( 'Redeem Points', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
	  </div>
	  <!--wps-pr-popup- rewards tab-container-->
	  <div class="wps-pr-popup-tab-container active" id="notify_user_earn_more_section_mobile">
		<ul class="wps-pr-popup-tab-listing wps-pr-popup-rewards-tab-listing">
		  <?php if ( $this->wps_wpr_check_signup_enable() && ! is_user_logged_in() ) { ?>
		  <li>
			<div class="wps-pr-popup-left-rewards">
			  <div class="wps-pr-popup-rewards-icon">
				<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
			  </div>
			  
				<div class="wps-pr-popup-rewards-left-content">
				  <h6><?php esc_html_e( 'Register Your Self and Earn', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
				  <span>
				  <?php
					echo esc_html( $this->wps_wpr_get_signup_value() );
					esc_html_e( ' Points', 'ultimate-woocommerce-points-and-rewards' );
					?>
					</span>
				</div>
			</div>
			<div class="wps-pr-popup-right-rewards">
			  <div class="wps-pr-popup-rewards-right-content">
			  </div>
			</div>
		  </li>
				<?php
		  }
		  if ( $this->wps_wpr_is_order_conversion_enabled() ) {
				?>
			<li>
			  <div class="wps-pr-popup-left-rewards">
				<div class="wps-pr-popup-rewards-icon">
				  <img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/shopping-cart.png'; ?>" alt="">
				</div>
				<div class="wps-pr-popup-rewards-left-content">
				<?php esc_html_e( 'Place The Order and Earn Points', 'ultimate-woocommerce-points-and-rewards' ); ?>
				  <span>
				  <?php
					esc_html_e( 'Earn ', 'ultimate-woocommerce-points-and-rewards' );
					echo wp_kses_post( $order_conversion_rate['Value'] );
					esc_html_e( ' Points on every ', 'ultimate-woocommerce-points-and-rewards' );
					echo wp_kses_post( $this->wps_wpr_value_return_in_currency( $order_conversion_rate['Points'] ) );
					esc_html_e( ' spent', 'ultimate-woocommerce-points-and-rewards' );
					?>
					</span>
				</div>
			  </div>
			  <div class="wps-pr-popup-right-rewards">
				<div class="wps-pr-popup-rewards-right-content">
				</div>
			  </div>
			</li>
		  <?php } if ( $this->wps_wpr_is_review_reward_enabled() ) { ?>
		  <li>
			<div class="wps-pr-popup-left-rewards">
			  <div class="wps-pr-popup-rewards-icon">
				<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
			  </div>
			  <div class="wps-pr-popup-rewards-left-content">
				<h6><?php esc_html_e( 'Write Review ', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
				<span>
				<?php
				esc_html_e( 'Write reviews and earn ', 'ultimate-woocommerce-points-and-rewards' );
				echo esc_html( $this->wps_wpr_get_reward_review() );
				esc_html_e( ' Point', 'ultimate-woocommerce-points-and-rewards' );
				?>
				</span>
			  </div>
			</div>
			<div class="wps-pr-popup-right-rewards">
			  <div class="wps-pr-popup-rewards-right-content">
			  <!--  <span>you need 350more poins</span> -->
			  </div>
			</div>
		  </li>
		<?php } if ( $this->wps_wpr_check_referal_enable() ) { ?>
		<li>
		  <div class="wps-pr-popup-left-rewards">
			<div class="wps-pr-popup-rewards-icon">
			  <img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
			</div>
			  <?php if ( ! $this->wps_wpr_is_only_referral_purchase_enabled() ) { ?>
			<div class="wps-pr-popup-rewards-left-content">
			  <h6><?php esc_html_e( 'Refer Someone', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
			  <span>
					<?php
					esc_html_e( 'Reward is : ', 'ultimate-woocommerce-points-and-rewards' );
					echo esc_html( $this->wps_wpr_get_referral_reward() );
					esc_html_e( ' Point', 'ultimate-woocommerce-points-and-rewards' );
					?>
				</span>
			</div>
		  <?php } else { ?>
			<div class="wps-pr-popup-rewards-left-content">
			  <h6><?php esc_html_e( 'Refer Link', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
			  <span><?php esc_html_e( 'Share this link and get a reward on their purchase only', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
			</div>
		  <?php } ?>
		  </div>
			  <?php if ( ! $wps_guest_user ) { ?>
			<div class="wps-pr-popup-right-rewards wps-pr-popup-right-rewards--login">
			  <div class="wps-pr-popup-rewards-right-content">
				<span id="wps_notify_user_copy"><?php echo wp_kses_post( $site_url . '?pkey=' . $referral_link ); ?></span>
			  </div>
			  <div class="wps-pr-popup-rewards-right-content">
				<button class="wps_wpr_btn_copy wps_tooltip" data-clipboard-target="#wps_notify_user_copy" aria-label="copied">
				<span class="wps_tooltiptext"><?php esc_html_e( 'Copy', 'ultimate-woocommerce-points-and-rewards' ); ?></span>
				<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/copy.png'; ?>" alt="Copy to clipboard"></button>
			  </div>
			</div>
		  <?php } ?>
		</li>
			  <?php
		}
		if ( $this->wps_wpr_is_referral_purchase_enabled() ) {
			?>
			<li>
			<div class="wps-pr-popup-left-rewards">
			  <div class="wps-pr-popup-rewards-icon">
				<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
			  </div>
			  <div class="wps-pr-popup-rewards-left-content">
				<h6><?php esc_html_e( 'Earn on Someone Else Purchasing ', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
				<span>
				<?php
				esc_html_e( 'Reward is : ', 'ultimate-woocommerce-points-and-rewards' );
				echo esc_html( $this->wps_wpr_get_referral_purchase_reward() );
				esc_html_e( ' Point', 'ultimate-woocommerce-points-and-rewards' );
				?>
				</span>
			  </div>
			</div>
			<div class="wps-pr-popup-right-rewards">
			  <div class="wps-pr-popup-rewards-right-content">
			  <!--  <span>you need 350more poins</span> -->
			  </div>
			</div>
		  </li>
			<?php
		}
		if ( ! $this->wps_wpr_check_signup_enable() && ! $this->wps_wpr_is_order_conversion_enabled() && ! $this->wps_wpr_is_review_reward_enabled() && ! $this->wps_wpr_is_referral_purchase_enabled() && ! $this->wps_wpr_check_referal_enable() ) {
			?>
		  <li>
			<div class="wps-pr-popup-left-rewards">
			  <div class="wps-pr-popup-rewards-icon">
			  </div>
			  <div class="wps-pr-popup-rewards-left-content">
				<h6><?php esc_html_e( 'No Features Are Available Right Now! ', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
			  </div>
			</div>
			<div class="wps-pr-popup-right-rewards">
			  <div class="wps-pr-popup-rewards-right-content">
			  </div>
			</div>
		  </li>
			<?php
		}
		?>
		</ul>
	  </div>
	  <div class="wps-pr-popup-tab-container" id="notify_user_redeem_section_mobile">
		<ul class="wps-pr-popup-tab-listing">
		  <?php if ( $this->wps_wpr_is_purchase_product_using_points_enabled() ) { ?>
		  <li>
			<div class="wps-pr-tab-popup-icon">
			   <img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/shopping-cart.png'; ?>" alt="">
			</div>
			<div class="wps-pr-tab-popup-content">
			  <h6><?php esc_html_e( 'Redeem Points on Particluar Products', 'ultimate-woocommerce-points-and-rewards' ); // phpcs:ignoreFile. ?></h6>
			  <p>
				<?php
				esc_html_e( 'Conversion Rule : ', 'ultimate-woocommerce-points-and-rewards' );
				echo wp_kses_post( $this->wps_wpr_return_conversion_rate( $purchase_product_using_pnt_rate['Points'], $purchase_product_using_pnt_rate['Currency'] ) );
				?>
				</p>
			</div>
		  </li>
				<?php
		  }
		  if ( $this->wps_wpr_is_apply_point_on_cart_enabled() ) {
				?>
			<li>
			  <div class="wps-pr-tab-popup-icon">
				<img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/shopping-cart.png'; ?>" alt="image">
			  </div>
			  <div class="wps-pr-tab-popup-content">
				<h6><?php esc_html_e( 'Apply Points on Cart Total', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
				<p>
				<?php
				esc_html_e( 'Conversion Rule : ', 'ultimate-woocommerce-points-and-rewards' );
				echo wp_kses_post( $this->wps_wpr_return_conversion_rate( $apply_point_on_cart_rate['Points'], $apply_point_on_cart_rate['Currency'] ) );
				?>
				</p>
			  </div>
			</li>
			  <?php
		  }
		  if ( $this->wps_wpr_is_convert_points_to_coupon_enabled() ) {
				?>
		  <li>
			<div class="wps-pr-tab-popup-icon">
			  <img src="<?php echo esc_url( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL ) . 'modal/images/voucher.png'; ?>" alt="">
			</div>
			<div class="wps-pr-tab-popup-content">
			  <h6><?php esc_html_e( 'Convert Points into Coupons', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
			  <p>
			  <?php
				esc_html_e( 'Conversion Rule : ', 'ultimate-woocommerce-points-and-rewards' );
				echo wp_kses_post( $this->wps_wpr_return_conversion_rate( $convert_points_to_coupons_rate['Points'], $convert_points_to_coupons_rate['Currency'] ) );
				?>
				</p>
			</div>
		  </li>
			  <?php
		  }
		  if ( ! $this->wps_wpr_is_convert_points_to_coupon_enabled() && ! $this->wps_wpr_is_apply_point_on_cart_enabled() && ! $this->wps_wpr_is_purchase_product_using_points_enabled() ) {
				?>
			<li>
			  <div class="wps-pr-popup-left-rewards">
				<div class="wps-pr-popup-rewards-icon">
				</div>
				<div class="wps-pr-popup-rewards-left-content">
				  <h6><?php esc_html_e( 'No Features Are Available Right Now! ', 'ultimate-woocommerce-points-and-rewards' ); ?></h6>
				</div>
			  </div>
			  <div class="wps-pr-popup-right-rewards">
				<div class="wps-pr-popup-rewards-right-content">
				</div>
			  </div>
			</li>
			  <?php
		  }
			?>
		</ul>
	  </div>
	</div><!--wps-pr-popup-body-->
  </div>
</div>
<span id="wps-pr-mobile-open-popup" style="background-color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?>" class="wps-pr-mobile-open-popup <?php echo esc_html( $position_class ); ?>"><?php echo esc_html( $button_text ); ?></span>
<span id="wps-pr-mobile-close-popup" style="background-color: <?php echo esc_html( $this->wps_wpr_notification_addon_get_color() ); ?>" class="wps-pr-mobile-open-popup close <?php echo esc_html( $position_class ); ?>"><?php echo esc_html( $button_text ); ?></span>
	<?php
}
