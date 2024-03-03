<?php
/**
 * The update-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/admin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Points_And_Rewards_For_Woocommerce_Pro_Update' ) ) {
	/**
	 * Points_And_Rewards_For_Woocommerce_Pro_Update class
	 */
	class Points_And_Rewards_For_Woocommerce_Pro_Update {
		/**
		 * Construct function
		 */
		public function __construct() {
			register_activation_hook( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_BASE_FILE, array( $this, 'wps_check_activation' ) );
			add_action( 'wps_check_event', array( $this, 'wps_check_update' ) );
			add_filter( 'http_request_args', array( $this, 'wps_updates_exclude' ), 5, 2 );

			add_action( 'install_plugins_pre_plugin-information', array( $this, 'wps_plugin_details' ) );
			register_deactivation_hook( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_BASE_FILE, array( $this, 'wps_check_deactivation' ) );
		}
		/**
		 * Mwb_check_deactivation function
		 *
		 * @return void
		 */
		public function wps_check_deactivation() {
			wp_clear_scheduled_hook( 'wps_check_event' );
		}
		/**
		 * Mwb_check_activation function
		 *
		 * @return void
		 */
		public function wps_check_activation() {
			wp_schedule_event( time(), 'daily', 'wps_check_event' );
		}
		/**
		 * Mwb_check_update function
		 *
		 * @return boolean
		 */
		public function wps_check_update() {
			global $wp_version;
			global $update_check;
			$update_check  = 'https://wpswings.com/pluginupdates/ultimate-woocommerce-points-and-rewards/update.php';
			$plugin_folder = plugin_basename( dirname( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_BASE_FILE ) );
			$plugin_file   = basename( ( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_BASE_FILE ) );

			if ( defined( 'WP_INSTALLING' ) ) {
				return false;
			}
			$postdata = array(
				'action' => 'check_update',
				'license_key' => POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_LICENSE_KEY,
			);
			$args = array(
				'method' => 'POST',
				'body' => $postdata,
			);
			$response = wp_remote_post( $update_check, $args );
			if ( is_wp_error( $response ) ) {
				return false;
			}
			if ( empty( $response['response']['code'] ) || 200 !== $response['response']['code'] ) {
				return false;
			}
			if ( isset( $response['body'] ) && ! empty( $response['body'] ) ) {

				list($version, $url) = explode( '~', $response['body'] );
			}
			if ( $this->wps_plugin_get( 'Version' ) >= $version ) {
				return false;
			}

			$plugin_transient = get_site_transient( 'update_plugins' );
			$a = array(
				'slug' => $plugin_folder,
				'new_version' => $version,
				'url' => $this->wps_plugin_get( 'AuthorURI' ),
				'package' => $url,
			);
			$o = (object) $a;
			$plugin_transient->response[ $plugin_folder . '/' . $plugin_file ] = $o;
			set_site_transient( 'update_plugins', $plugin_transient );
		}

		/**
		 * Undocumented function
		 *
		 * @param int    $r for api.
		 * @param string $url for url.
		 * @return value
		 */
		public function wps_updates_exclude( $r, $url ) {
			if ( 0 !== strpos( $url, 'http://api.wordpress.org/plugins/update-check' ) ) {
				return $r;
			}
			if ( isset( $r['body'] ) && ! empty( $r['body'] ) ) {
				$plugins = isset( $r['body']['plugins'] ) ? unserialize( $r['body']['plugins'] ) : '';
				if ( isset( $plugins ) && '' !== $plugins ) {
					if ( isset( $plugins ) && ! empty( $plugins ) ) {
						unset( $plugins->plugins[ plugin_basename( __FILE__ ) ] );
						unset( $plugins->active[ array_search( plugin_basename( __FILE__ ), $plugins->active ) ] );
						$r['body']['plugins'] = serialize( $plugins );
					}
				}
			}
			return $r;
		}

		/**
		 * Mwb_plugin_get function
		 *
		 * @param int $i for i.
		 * @return path
		 */
		public function wps_plugin_get( $i ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}
			$plugin_folder = get_plugins( '/' . plugin_basename( dirname( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_BASE_FILE ) ) );
			$plugin_file   = basename( ( POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_BASE_FILE ) );
			return $plugin_folder[ $plugin_file ][ $i ];
		}

		/**
		 * Mwb_plugin_details function
		 *
		 * @return void
		 */
		public function wps_plugin_details() {
			global $tab;
			$plugins = ! empty( $_REQUEST['plugin'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['plugin'] ) ) : '';// phpcs:ignore
			if ( 'plugin-information' === $tab && 'ultimate-woocommerce-points-and-rewards' === $plugins ) {

				$url = 'https://wpswings.com/pluginupdates/ultimate-woocommerce-points-and-rewards/update.php';

				$postdata = array(
					'action'       => 'check_update',
					'license_code' => POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_LICENSE_KEY,
				);

				$args = array(
					'method' => 'POST',
					'body'   => $postdata,
				);

				$data = wp_remote_post( $url, $args );
				if ( is_wp_error( $data ) ) {
					return;
				}

				if ( isset( $data['body'] ) ) {
					$all_data = json_decode( $data['body'], true );

					if ( is_array( $all_data ) && ! empty( $all_data ) ) {
						$this->create_html_data( $all_data );
						wp_die();
					}
				}
				if ( empty( $data['response']['code'] ) || 200 !== $data['response']['code'] ) {
					return false;
				}
			}
		}

		/**
		 * Create_html_data function
		 *
		 * @param int $all_data for alldata.
		 * @return void
		 */
		public function create_html_data( $all_data ) {
			?>
			<style>
				#TB_window{
					top : 4% !important;
				}
				.wps_plugin_banner > img {
					height: 55%;
					width: 100%;
					border: 1px solid;
					border-radius: 7px;
				}
				.wps_plugin_description > h4 {
					background-color: #3779B5;
					padding: 5px;
					color: #ffffff;
					border-radius: 5px;
				}
				.wps_plugin_requirement > h4 {
					background-color: #3779B5;
					padding: 5px;
					color: #ffffff;
					border-radius: 5px;
				}
				#error-page > p {
					display: none;
				}
			</style>
			<div class="wps_plugin_details_wrapper">
				<div class="wps_plugin_banner">
					<img src="<?php echo esc_attr( $all_data['banners']['low'] ); ?>">	
				</div>
				<div class="wps_plugin_description">
					<h4><?php esc_html_e( 'Plugin Description', 'ultimate-woocommerce-points-and-rewards' ); ?></h4>
					<span><?php echo esc_html( $all_data['sections']['description'] ); ?></span>
				</div>
				<div class="wps_plugin_requirement">
					<h4><?php esc_html_e( 'Plugin Change Log', 'ultimate-woocommerce-points-and-rewards' ); ?></h4>
					<span><?php echo wp_kses_post( $all_data['sections']['changelog'] ); ?></span>
				</div> 
			</div>
			<?php
		}
	}
	new Points_And_Rewards_For_Woocommerce_Pro_Update();
}

