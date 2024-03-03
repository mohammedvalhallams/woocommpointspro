<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpswings.com/
 * @since             1.0.0
 * @package           Points_And_Rewards_For_Woocommerce_Pro
 *
 * @wordpress-plugin
 * Plugin Name:       Points and Rewards For WooCommerce Pro
 * Plugin URI:        https://wpswings.com/product/points-and-rewards-for-woocommerce-pro/?utm_source=wpswings-par-pro&utm_medium=par-pro-backend&utm_campaign=pro-plugin
 * Description:       <code><strong>Points and Rewards for WooCommerce Pro</strong></code> involve redeeming store credits as points and discount coupons on activities performed by customers. <a href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-par-shop&utm_medium=par-pro-backend&utm_campaign=shop-page" target="_blank"> Elevate your e-commerce store by exploring more on <strong> WP Swings </strong></a>
 * Version:           2.2.1
 * Author:            WP Swings
 * Author URI:        https://wpswings.com/?utm_source=wpswings-par-official&utm_medium=par-pro-backend&utm_campaign=official
 * Text Domain:       ultimate-woocommerce-points-and-rewards
 * Domain Path:       /languages
 *
 * Requires at least    : 5.5.0
 * Tested up to         : 6.1.1
 * WC requires at least : 5.5.0
 * WC tested up to      : 7.4.0
 *
 *
 * License:           Software License Agreement
 * License URI:       https://wpswings.com/license-agreement.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
// To Activate plugin only when WooCommerce is active.
$activated = false;

// Check if WooCommerce is active.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

	$activated = true;
}
if ( $activated ) {
	// To Activate plugin only when WooCommerce is active.
	$activated = false;
	// Check if WooCommerce is active.
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	if ( is_plugin_active( 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php' ) ) {

		$activated = true;
	}
	$old_org_present     = false;
	$wps_new_org_present = false;
	$installed_plugins   = get_plugins();

	if ( array_key_exists( 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php', $installed_plugins ) ) {
		$base_plugin = $installed_plugins['points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php'];
		if ( version_compare( $base_plugin['Version'], '1.2.5', '<' ) ) {
			$old_org_present = true;
		}
	}
	if ( array_key_exists( 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php', $installed_plugins ) ) {
		$base_plugin = $installed_plugins['points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php'];
		if ( version_compare( $base_plugin['Version'], '1.2.4', '>' ) ) {
			$wps_new_org_present = true;
		}
	}
	if ( true === $old_org_present ) {

		// Try org update to minimum.
		add_action( 'admin_notices', 'wps_points_and_rewards_project' );
		/**
		 * Try org update to minimum.
		 */
		function wps_points_and_rewards_project() {
			require_once 'wps-par-pro-functions.php';
			wps_par_replace_plugin();
		}
	}
	if ( $activated ) {
		/**
		 * Define plugin constants.
		 *
		 * @name define_points_and_rewards_for_woocommerce_pro_constants.
		 * @since 1.0.0
		 */
		function define_points_and_rewards_for_woocommerce_pro_constants() {

			points_and_rewards_for_woocommerce_pro_constants( 'POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_VERSION', '2.2.1' );
			points_and_rewards_for_woocommerce_pro_constants( 'POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH', plugin_dir_path( __FILE__ ) );
			points_and_rewards_for_woocommerce_pro_constants( 'POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_URL', plugin_dir_url( __FILE__ ) );
			points_and_rewards_for_woocommerce_pro_constants( 'WPS_UWPR_DOMAIN', 'ultimate-woocommerce-points-and-rewards' );

			// For License Validation.
			points_and_rewards_for_woocommerce_pro_constants( 'POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_SPECIAL_SECRET_KEY', '59f32ad2f20102.74284991' );
			points_and_rewards_for_woocommerce_pro_constants( 'POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_SERVER_URL', 'https://wpswings.com' );
			points_and_rewards_for_woocommerce_pro_constants( 'POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_ITEM_REFERENCE', 'Ultimate WooCommerce Points and Rewards' );
			points_and_rewards_for_woocommerce_pro_constants( 'WPR_DOMAIN', 'ultimate-woocommerce-points-and-rewards' );
		}

		/**
		 * Update the code for the plugin.
		 *
		 * @name points_and_rewards_for_woocommerce_pro_auto_update.
		 * @since 1.0.0
		 */
		function points_and_rewards_for_woocommerce_pro_auto_update() {

			$license_key = get_option( 'ultimate_woocommerce_points_and_rewards_lcns_key', '' );
			define( 'POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_LICENSE_KEY', $license_key );
			define( 'POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_BASE_FILE', __FILE__ );
			$update_check = 'https://wpswings.com/pluginupdates/ultimate-woocommerce-points-and-rewards/update.php';
			require_once 'class-points-and-rewards-for-woocommerce-pro-update.php';
		}

		/**
		 * Callable function for defining plugin constants.
		 *
		 * @name points_and_rewards_for_woocommerce_pro_constants
		 * @since 1.0.0
		 * @param string $key  constants of the plugins.
		 * @param string $value value of the constants.
		 */
		function points_and_rewards_for_woocommerce_pro_constants( $key, $value ) {

			if ( ! defined( $key ) ) {

				define( $key, $value );
			}
		}

		/**
		 * Dynamically Generate Coupon Code
		 *
		 * @name wps_wpr_coupon_generator
		 * @param number $length length of the coupon.
		 * @return string
		 * @author makewebbetter<webmaster@wpswings.com>
		 * @link https://www.makewebbetter.com/
		 */
		function wps_wpr_coupon_generator( $length = 5 ) {
			if ( '' == $length ) {
				$length = 5;
			}
			$password    = '';
			$alphabets   = range( 'A', 'Z' );
			$numbers     = range( '0', '9' );
			$final_array = array_merge( $alphabets, $numbers );
			while ( $length-- ) {
				$key       = array_rand( $final_array );
				$password .= $final_array[ $key ];
			}
			return $password;
		}

		/**
		 * The code that runs during plugin activation.
		 * This action is documented in includes/class-points-and-rewards-for-woocommerce-pro-activator.php
		 */
		function activate_points_and_rewards_for_woocommerce_pro() {
			if ( ! wp_next_scheduled( 'wps_wpr_membership_cron_schedule' ) ) {
				wp_schedule_event( time(), 'hourly', 'wps_wpr_membership_cron_schedule' );
			}
			if ( ! wp_next_scheduled( 'wps_wpr_points_expiration_cron_schedule' ) ) {
				wp_schedule_event( time(), 'daily', 'wps_wpr_points_expiration_cron_schedule' );
			}
			if ( ! wp_next_scheduled( 'wps_wpr_points_daily_login_cron_schedule' ) ) {
				wp_schedule_event( time(), 'daily', 'wps_wpr_points_daily_login_cron_schedule' );
			}
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-points-and-rewards-for-woocommerce-pro-activator.php';
			Points_And_Rewards_For_Woocommerce_Pro_Activator::activate();
		}

		register_activation_hook( __FILE__, 'activate_points_and_rewards_for_woocommerce_pro' );
		/**
		* The core plugin class that is used to define internationalization,
		* admin-specific hooks, and public-facing site hooks.
		*/
		require plugin_dir_path( __FILE__ ) . 'includes/class-points-and-rewards-for-woocommerce-pro.php';

		/**
		 * Begins execution of the plugin.
		 *
		 * Since everything within the plugin is registered via hooks,
		 * then kicking off the plugin from this point in the file does
		 * not affect the page life cycle.
		 *
		 * @since    1.0.0
		 */
		function run_points_and_rewards_for_woocommerce_pro() {

			define_points_and_rewards_for_woocommerce_pro_constants();
			points_and_rewards_for_woocommerce_pro_auto_update();

			$plugin = new Points_And_Rewards_For_Woocommerce_Pro();
			$plugin->run();
		}
		run_points_and_rewards_for_woocommerce_pro();

		// Add settings link on plugin page.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'points_and_rewards_for_woocommerce_pro_settings_link' );

		/**
		 * Settings link.
		 *
		 * @name points_and_rewards_for_woocommerce_pro_settings_link.
		 * @since 1.0.0
		 * @param string $links links of the settings.
		 */
		function points_and_rewards_for_woocommerce_pro_settings_link( $links ) {
			$my_link = array(
				'<a href="' . admin_url( 'admin.php?page=wps-rwpr-setting' ) . '">' . __( 'Settings', 'ultimate-woocommerce-points-and-rewards' ) . '</a>',
			);
			return array_merge( $my_link, $links );
		}
		// Include Api features.
		require_once POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_DIR_PATH . 'includes/class-points-and-rewards-for-woocommerce-pro-api.php';

	} else {

		$timestamp = get_option( 'points_and_rewards_for_woocommerce_pro_lcns_thirty_days', 'not_set' );
		if ( 'not_set' === $timestamp ) {

			$current_time = current_time( 'timestamp' );
			$thirty_days  = strtotime( '+30 days', $current_time );
			update_option( 'points_and_rewards_for_woocommerce_pro_lcns_thirty_days', $thirty_days );
		}
		// WooCommerce is not active so deactivate this plugin.
		add_action( 'admin_init', 'ultimate_woocommerce_points_rewards_activation_failure' );
		add_action( 'admin_enqueue_scripts', 'wps_wpr_enqueue_activation_script' );
		add_action( 'wp_ajax_wps_wpr_activate_lite_plugin', 'wps_wpr_activate_lite_plugin' );

		/**
		 * This is function handling the ajax request.
		 *
		 * @name wps_wpr_activate_lite_plugin.
		 * @since 1.0.0
		 */
		function wps_wpr_activate_lite_plugin() {

			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			$wps_plugin_name = 'points-and-rewards-for-woocommerce';
			$wps_plugin_api  = plugins_api(
				'plugin_information',
				array(
					'slug'   => $wps_plugin_name,
					'fields' => array( 'sections' => false ),
				)
			);
			if ( isset( $wps_plugin_api->download_link ) ) {

				$wps_ajax_obj = new WP_Ajax_Upgrader_Skin();
				$wps_obj      = new Plugin_Upgrader( $wps_ajax_obj );
				$wps_install  = $wps_obj->install( $wps_plugin_api->download_link );
				activate_plugin( 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php' );
			}
			echo 'success';
			wp_die();
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 * @name wps_wpr_enqueue_activation_script.
		 */
		function wps_wpr_enqueue_activation_script() {
			$wps_wpr_params = array(
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'wps_wpr_nonce' => wp_create_nonce( 'wps-wpr-activation-nonce' ),
			);
			wp_enqueue_script( 'admin-js', plugin_dir_url( __FILE__ ) . '/admin/js/points-and-rewards-for-woocommerce-pro-activation.js', array( 'jquery' ), '1.0.0', false );
			wp_enqueue_style( 'admin-css', plugin_dir_url( __FILE__ ) . '/admin/css/points-and-rewards-for-woocommerce-pro-activation.css', array(), '1.0.0', false );
			wp_localize_script( 'admin-js', 'wps_wpr_activation', $wps_wpr_params );
		}

		/**
		 * Deactivate this plugin.
		 *
		 * @name ultimate_woocommerce_points_rewards_activation_failure.
		 * @since 1.0.0
		 */
		function ultimate_woocommerce_points_rewards_activation_failure() {

			add_action( 'admin_notices', 'ultimate_woocommerce_points_rewards_activation_failure_admin_notice' );
		}
		// Add admin error notice.

		/**
		 * This function is used to display admin error notice when WooCommerce is not active.
		 *
		 * @name ultimate_woocommerce_points_rewards_activation_failure_admin_notice
		 * @since 1.0.0
		 */
		function ultimate_woocommerce_points_rewards_activation_failure_admin_notice() {

			// to hide Plugin activated notice.
			unset( $_GET['activate'] );
			?>
			<div style="display: none;" class="wps_loader_style" id="wps_notice_loader">
			<img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) ); ?>admin/images/loading.gif">
			</div>
			<?php
			if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				?>
				<div class="notice notice-error is-dismissible">
					<p><?php esc_html_e( 'WooCommerce is not activated, Please activate WooCommerce first to activate Ultimate WooCommerce Points and Rewards.', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
				</div>

				<?php
			} elseif ( ! is_plugin_active( 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php' ) ) {
				?>

				<div class="notice notice-error is-dismissible">
					<p><?php esc_html_e( 'Points and Rewards For WooCommerce is not activated, Please activate ', 'ultimate-woocommerce-points-and-rewards' ); ?>
					<a href="<?php echo esc_url( 'https://wordpress.org/plugins/points-and-rewards-for-woocommerce/?utm_source=admin-page&utm_medium=wps-PAR&utm_campaign=lite' ); ?>"><?php esc_html_e( 'Points and Rewards For WooCommerce ', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
					<?php esc_html_e( 'first to activate Points and Rewards For WooCommerce Pro.', 'ultimate-woocommerce-points-and-rewards' ); ?>
					</p>
					<?php
					$wps_lite_plugin = 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php';
					if ( file_exists( WP_PLUGIN_DIR . '/' . $wps_lite_plugin ) && ! is_plugin_active( 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php' ) ) {
						?>
						<p>
							<a class="button button-primary" href="<?php echo wp_kses_post( wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $wps_lite_plugin . '&amp;plugin_status=all&amp;paged=1&amp;s=', 'activate-plugin_' . $wps_lite_plugin ) ); ?>"><?php esc_html_e( 'Activate', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
						</p>
						<?php
					} else {
						?>
						<p>
							<a href = "#" id="wps-wpr-install-lite" class="button button-primary"><?php esc_html_e( 'Install', 'ultimate-woocommerce-points-and-rewards' ); ?></a>
						</p>
						<?php
					}
					?>
				</div>
				<?php
			}
		}

		/**
		 * THis function used for installing the plugin.
		 *
		 * @name wps_get_plugins.
		 * @since 1.0.0
		 * @param array $plugins $plugins is an array of the plugin that needs to be installed.
		 */
		function wps_get_plugins( $plugins ) {
			$args = array(
				'path'         => ABSPATH . 'wp-content/plugins/',
				'preserve_zip' => false,
			);

			foreach ( $plugins as $plugin ) {
				wps_plugin_download( $plugin['path'], $args['path'] . $plugin['name'] . '.zip' );
				wps_plugin_unpack( $args, $args['path'] . $plugin['name'] . '.zip' );
				wps_plugin_activate( $plugin['install'] );
			}
		}

		/**
		 * This function used for downloading the file of the server.
		 *
		 * @name wps_plugin_download
		 * @since 1.0.0
		 * @param string $url   url of the plugin.
		 * @param string $path  path of the plugin.
		 */
		function wps_plugin_download( $url, $path ) {
			$ch = curl_init( $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$data = curl_exec( $ch );
			curl_close( $ch );
			if ( file_put_contents( $path, $data ) ) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * This function is used for the unpacking the zip file
		 *
		 * @name wps_plugin_unpack
		 * @since 1.0.0
		 * @param array  $args  This is array of the parameters.
		 * @param string $target This is url of the where file needs to be installed.
		 */
		function wps_plugin_unpack( $args, $target ) {
			$zip = zip_open( $target );
			if ( $zip ) {
				while ( $entry = zip_read( $zip ) ) {
						$is_file   = substr( zip_entry_name( $entry ), -1 ) == '/' ? false : true;
						$file_path = $args['path'] . zip_entry_name( $entry );
					if ( $is_file ) {
						if ( zip_entry_open( $zip, $entry, 'r' ) ) {
							$fstream = zip_entry_read( $entry, zip_entry_filesize( $entry ) );
							file_put_contents( $file_path, $fstream );
							chmod( $file_path, 0777 );
						}
							zip_entry_close( $entry );
					} else {
						if ( zip_entry_name( $entry ) ) {
							mkdir( $file_path );
							chmod( $file_path, 0777 );
						}
					}
				}
				zip_close( $zip );
			}
			if ( false === $args['preserve_zip'] ) {
				unlink( $target );
			}
		}

		/**
		 * This function is used for installing the new plugin
		 *
		 * @since 1.0.0
		 * @name wps_plugin_activate
		 * @param string $installer name of the installer.
		 */
		function wps_plugin_activate( $installer ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
			$current = get_option( 'active_plugins' );
			$plugin  = plugin_basename( trim( $installer ) );
			if ( ! in_array( $plugin, $current ) ) {

				$current[] = $plugin;
				sort( $current );
				do_action( 'activate_plugin', trim( $plugin ) );
				update_option( 'active_plugins', $current );
				do_action( 'activate_' . trim( $plugin ) );
				do_action( 'activated_plugin', trim( $plugin ) );
			}
			return null;
		}
	}

	/**
	 * Migration to new domain notice.
	 *
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data.
	 * @param string $status Status filter currently applied to the plugin list.
	 */
	function wps_pro_wpr_ugrdae_notices( $plugin_file, $plugin_data, $status ) {

		?>
			<tr class="plugin-update-tr active notice-warning notice-alt">
			<td colspan="4" class="plugin-update colspanchange">
				<div class="notice notice-error inline update-message notice-alt">
					<p class='wps-notice-title wps-notice-section'>
						<?php esc_html_e( 'The latest update includes some substantial changes across different areas of the plugin. Hence, if you are not a new user then', 'points-and-rewards-for-woocommerce' ); ?><strong><?php esc_html_e( ' please migrate your old data and settings from ', 'points-and-rewards-for-woocommerce' ); ?><a style="text-decoration:none;" href="<?php echo esc_url( admin_url( 'admin.php?page=wps-rwpr-setting' ) ); ?>"><?php esc_html_e( 'Dashboard', 'points-and-rewards-for-woocommerce' ); ?></strong></a><?php esc_html_e( ' page then Click On Start Import Button.', 'points-and-rewards-for-woocommerce' ); ?>
					</p>
				</div>
			</td>
		</tr>
		<style>
			.wps-notice-section > p:before {
				content: none;
			}
		</style>
		<?php

	}

	add_action( 'wp_loaded', 'wps_wpr_disply_notice_on_plugin_dashboard_pro' );
	/**
	 * This function is used to show notice on plugin listing page.
	 *
	 * @return void
	 */
	function wps_wpr_disply_notice_on_plugin_dashboard_pro() {
		if ( class_exists( 'Points_Rewards_For_WooCommerce_Admin' ) ) {

			$wps_par_get_count = new Points_Rewards_For_WooCommerce_Admin( 'points-and-rewards-for-woocommerce', '1.2.9' );
			$wps_pending_par   = $wps_par_get_count->wps_par_get_count( 'wc-pending' );
			$wps_pending_par   = ! empty( $wps_pending_par ) && is_array( $wps_pending_par ) ? count( $wps_pending_par ) : 0;
			$wps_count_users   = $wps_par_get_count->wps_par_get_count_users( 'users' );
			$wps_count_users   = ! empty( $wps_count_users ) && is_array( $wps_count_users ) ? count( $wps_count_users ) : 0;

			if ( 0 !== $wps_pending_par || 0 !== $wps_count_users ) {
				add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'wps_pro_wpr_ugrdae_notices', 0, 3 );
			}
		}
	}
} else {

	// WooCommerce is not active so deactivate this plugin.
	add_action( 'admin_init', 'rewardeem_woocommerce_ultimate_points_rewards_activation_failure' );

	/**
	 * This function is used to deactivate plugin.
	 *
	 * @name rewardeem_woocommerce_points_rewards_activation_failure
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	function rewardeem_woocommerce_ultimate_points_rewards_activation_failure() {

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}
	// Add admin error notice.
	add_action( 'admin_notices', 'rewardeem_woocommerce_ultimate_points_rewards_activation_failure_admin_notice' );

	/**
	 * This function is used to deactivate plugin.
	 *
	 * @name rewardeem_woocommerce_points_rewards_activation_failure
	 * @author WP Swings <webmaster@wpswings.com>
	 * @link https://www.makewebbetter.com/
	 */
	function rewardeem_woocommerce_ultimate_points_rewards_activation_failure_admin_notice() {
			// hide Plugin activated notice.
		unset( $_GET['activate'] );
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'WooCommerce is not activated, Please activate WooCommerce first to activate Points and Rewards for WooCommerce.', 'ultimate-woocommerce-points-and-rewards' ); ?></p>
			</div>

			<?php
		}
	}
}
