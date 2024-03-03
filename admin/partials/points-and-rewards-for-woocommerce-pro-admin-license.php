<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="points-and-rewards-for-woocommerce-pro-license-sec">

	<h3><?php esc_html_e( 'Enter your License', 'ultimate-woocommerce-points-and-rewards' ); ?></h3>

	<p>
		<?php esc_html_e( 'This is the License Activation Panel. After purchasing the extension from ', 'ultimate-woocommerce-points-and-rewards' ); ?>
		<span>
			<a href="https://wpswings.com/?utm_source=wpswings-license&utm_medium=par-pro-backend&utm_campaign=official" target="_blank" ><?php esc_html_e( 'WP Swings', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
		</span>&nbsp;

		<?php esc_html_e( 'you will get the purchase code of this extension. Please verify your purchase below so that you can use the features of this plugin.', 'ultimate-woocommerce-points-and-rewards' ); ?>
	</p>

	<form id="points-and-rewards-for-woocommerce-pro-license-form">

		<label><b><?php esc_html_e( 'Purchase Code : ', 'ultimate-woocommerce-points-and-rewards' ); ?></b></label>

		<input type="text" id="points-and-rewards-for-woocommerce-pro-license-key" placeholder="<?php esc_html_e( 'Enter your code here.', 'ultimate-woocommerce-points-and-rewards' ); ?>" required="">

		<div id="points-and-rewards-for-woocommerce-pro-ajax-loading-gif"><img src="<?php echo 'images/spinner.gif'; ?>"></div>

		<p id="points-and-rewards-for-woocommerce-pro-license-activation-status"></p>

		<button type="submit" class="button-primary"  id="points-and-rewards-for-woocommerce-pro-license-activate"><?php esc_html_e( 'Activate', 'ultimate-woocommerce-points-and-rewards' ); ?></button>

		<?php wp_nonce_field( 'points-and-rewards-for-woocommerce-pro-license-nonce-action', 'points-and-rewards-for-woocommerce-pro-license-nonce' ); ?>

	</form>

</div>
