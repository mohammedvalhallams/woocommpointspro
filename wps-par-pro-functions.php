<?php
/**
 * The update-specific functionality of the plugin.
 *
 * @author MakeWebBetter <webmaster@makewebbetter.com>
 * @package Wordpress__Generator
 */

/**
 * Replace plugin main function.
 *
 * @return boolean
 */
function wps_par_replace_plugin() {
	$plugin_slug        = 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php';
	$plugin_name        = 'Points and Rewards For WooCommerce';
	$plugin_zip         = 'https://downloads.wordpress.org/plugin/points-and-rewards-for-woocommerce.zip';
	$current_pro_plugin = 'ultimate-woocommerce-points-and-rewards/ultimate-woocommerce-points-and-rewards.php';
	ob_start();

	if ( wps_par_plugin_slug( $plugin_slug ) ) {
		wps_par_slug_upgraded( $plugin_slug );
		$installed = true;
	} else {
		$installed = wps_par_install_plugin_zip( $plugin_zip );
	}
	if ( ! is_wp_error( $installed ) && $installed ) {
		$status_free = activate_plugin( $plugin_slug );
		if ( ! is_wp_error( $status_free ) ) {
			return true;
		}
	}
	ob_end_clean();
	return false;
}

/**
 * Checking if plugin is already installed.
 *
 * @param string $slug string containing the plugin slug.
 * @return boolean
 */
function wps_par_plugin_slug( $slug ) {
	if ( ! function_exists( 'get_plugins' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	$all_plugins = get_plugins();
	if ( ! empty( $all_plugins[ $slug ] ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Install plugin.
 *
 * @param string $plugin_zip url for the plugin zip file at WordPress.
 * @return boolean
 */
function wps_par_install_plugin_zip( $plugin_zip ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	wp_cache_flush();
	$upgrader  = new Plugin_Upgrader();
	$installed = $upgrader->install( $plugin_zip );
	return $installed;
}

/**
 * Upgrade plugin.
 *
 * @param string $plugin_slug string contining the plugin slug.
 * @return boolean
 */
function wps_par_slug_upgraded( $plugin_slug ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
	wp_cache_flush();
	$upgrader = new Plugin_Upgrader();
	$upgraded = $upgrader->upgrade( $plugin_slug );
	return $upgraded;
}
