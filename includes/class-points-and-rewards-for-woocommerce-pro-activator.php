<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/includes
 * @author     makewebbetter <webmaster@wpswings.com>
 */
class Points_And_Rewards_For_Woocommerce_Pro_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		$timestamp = get_option( 'points_and_rewards_for_woocommerce_pro_lcns_thirty_days', 'not_set' );

		if ( 'not_set' === $timestamp ) {

			$current_time = current_time( 'timestamp' );

			$thirty_days = strtotime( '+30 days', $current_time );

			update_option( 'points_and_rewards_for_woocommerce_pro_lcns_thirty_days', $thirty_days );
		}

		// Validate license daily cron.
		wp_schedule_event( time(), 'daily', 'points_and_rewards_for_woocommerce_pro_license_daily' );

	}

}
