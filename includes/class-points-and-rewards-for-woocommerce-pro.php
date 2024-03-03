<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Points_And_Rewards_For_Woocommerce_Pro
 * @subpackage Points_And_Rewards_For_Woocommerce_Pro/includes
 * @author     makewebbetter <webmaster@wpswings.com>
 */
class Points_And_Rewards_For_Woocommerce_Pro {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Points_And_Rewards_For_Woocommerce_Pro_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_VERSION' ) ) {

			$this->version = POINTS_AND_REWARDS_FOR_WOOCOMMERCE_PRO_VERSION;
		} else {

			$this->version = '2.2.1';
		}

		$this->plugin_name = 'points-and-rewards-for-woocommerce-pro';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Points_And_Rewards_For_Woocommerce_Pro_Loader. Orchestrates the hooks of the plugin.
	 * - Points_And_Rewards_For_Woocommerce_Pro_i18n. Defines internationalization functionality.
	 * - Points_And_Rewards_For_Woocommerce_Pro_Admin. Defines all hooks for the admin area.
	 * - Points_And_Rewards_For_Woocommerce_Pro_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-points-and-rewards-for-woocommerce-pro-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-points-and-rewards-for-woocommerce-pro-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-points-and-rewards-for-woocommerce-pro-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-points-and-rewards-for-woocommerce-pro-public.php';

		$this->loader = new Points_And_Rewards_For_Woocommerce_Pro_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Points_And_Rewards_For_Woocommerce_Pro_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Points_And_Rewards_For_Woocommerce_Pro_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Points_And_Rewards_For_Woocommerce_Pro_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'wps_rwpr_add_setting_tab', $plugin_admin, 'wps_add_license_panel' );

		/*Running action for ajax license.*/
		$this->loader->add_action( 'wp_ajax_points_and_rewards_for_woocommerce_pro_license', $plugin_admin, 'validate_license_handle' );
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'wps_wpr_custom_plugin_par_meta', 10, 2 );
		$this->loader->add_action( 'wps_wpr_add_notice', $plugin_admin, 'wps_wpr_add_notice' );
		$callname_lic         = self::$lic_callback_function;
		$callname_lic_initial = self::$lic_ini_callback_function;
		$day_count            = self::$callname_lic_initial();

		// Condition for validating.
		if ( self::$callname_lic() || 0 <= $day_count ) {
			// All admin actions and filters after License Validation goes here.
			// Using Settings API for settings menu.
			$this->loader->add_action( 'wp_ajax_wps_large_scv_import', $plugin_admin, 'wps_large_scv_import' );
			$this->loader->add_filter( 'wps_wpr_general_settings', $plugin_admin, 'add_wps_settings', 10, 1 );
			/*Daily ajax license action.*/
			$this->loader->add_action( 'points_and_rewards_for_woocommerce_pro_license_daily', $plugin_admin, 'validate_license_daily' );
			$this->loader->add_action( 'transition_comment_status', $plugin_admin, 'wps_wpr_give_points_on_comment', 10, 3 );
			$this->loader->add_action( 'comment_post', $plugin_admin, 'wps_cooment_points_function', 10, 2 );
			$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings' );
			$this->loader->add_filter( 'wps_wpr_coupon_settings', $plugin_admin, 'wps_wpr_add_coupon_settings' );
			$this->loader->add_action( 'wps_wpr_product_assign_points', $plugin_admin, 'wps_wpr_add_new_catories_wise_settings' );
			$this->loader->add_action( 'wp_ajax_wps_wpr_per_pro_category', $plugin_admin, 'wps_wpr_per_pro_category' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wpr_per_pro_category', $plugin_admin, 'wps_wpr_per_pro_category' );
			$this->loader->add_action( 'wps_wpr_others_settings', $plugin_admin, 'wps_wpr_other_settings' );
			$this->loader->add_filter( 'wps_rwpr_add_setting_tab', $plugin_admin, 'wps_add_purchase_through_points_settings_tab', 20, 1 );
			$this->loader->add_action( 'wp_ajax_wps_wpr_per_pro_pnt_category', $plugin_admin, 'wps_wpr_per_pro_pnt_category' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wpr_per_pro_pnt_category', $plugin_admin, 'wps_wpr_per_pro_pnt_category' );
			$this->loader->add_filter( 'woocommerce_product_data_tabs', $plugin_admin, 'wps_wpr_add_points_tab', 15, 1 );
			$this->loader->add_action( 'woocommerce_product_data_panels', $plugin_admin, 'wps_wpr_points_input' );
			$this->loader->add_action( 'woocommerce_variation_options', $plugin_admin, 'wps_wpr_woocommerce_variation_options_pricing', 10, 3 );
			$this->loader->add_action( 'woocommerce_save_product_variation', $plugin_admin, 'wps_wpr_woocommerce_save_product_variation', 10, 2 );
			$this->loader->add_action( 'woocommerce_process_product_meta', $plugin_admin, 'woo_add_custom_points_fields_save' );
			$this->loader->add_action( 'wps_wpr_points_expiration_cron_schedule', $plugin_admin, 'wps_wpr_check_daily_about_points_expiration' );

			$this->loader->add_action( 'widgets_init', $plugin_admin, 'wps_wpr_custom_widgets' );
			$this->loader->add_action( 'woocommerce_admin_order_item_headers', $plugin_admin, 'wps_wpr_woocommerce_admin_order_item_headers' );
			$this->loader->add_action( 'woocommerce_admin_order_item_values', $plugin_admin, 'wps_wpr_woocommerce_admin_order_item_values', 10, 3 );
			$this->loader->add_action( 'admin_head', $plugin_admin, 'wps_wpr_remove_action' );
			$this->loader->add_action( 'wps_wpr_save_membership_settings', $plugin_admin, 'wps_wpr_save_membership_settings_pro', 10, 1 );
			$this->loader->add_filter( 'wps_coupon_tab_text', $plugin_admin, 'wps_wpr_change_the_coupon_tab_text' );
			$this->loader->add_filter( 'wps_add_coupon_details', $plugin_admin, 'wps_wpr_add_coupon_details', 10, 2 );
			$this->loader->add_action( 'wps_wpr_add_additional_import_points', $plugin_admin, 'wps_wpr_add_additional_import_points', 10 );
			$this->loader->add_action( 'wps_wpr_additional_general_settings', $plugin_admin, 'wps_wpr_additional_cart_points_settings', 10, 2 );

			$this->loader->add_action( 'wp_ajax_wps_wpr_delete_user_coupon', $plugin_admin, 'wps_wpr_delete_user_coupon' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wpr_delete_user_coupon', $plugin_admin, 'wps_wpr_delete_user_coupon' );

			$this->loader->add_action( 'wps_wpr_points_log_bulk_option', $plugin_admin, 'wps_wpr_points_log_bulk_option_callback' );
			$this->loader->add_action( 'wps_wpr_process_bulk_reset_option', $plugin_admin, 'wps_wpr_process_bulk_reset_option_callback', 10, 2 );

			$this->loader->add_filter( 'wps_rwpr_add_setting_tab', $plugin_admin, 'wps_add_points_notification_addon_settings_tab', 22, 1 );
			/*Compatibility with WPML*/
			$this->loader->add_action( 'init', $plugin_admin, 'wps_wpr_setting_compatibility_wpml' );
			$this->loader->add_filter( 'wps_wpr_show_shortcoe_text', $plugin_admin, 'wps_wpr_show_referral_link_shortcoe' );

			$this->loader->add_filter( 'wps_rwpr_add_setting_tab', $plugin_admin, 'wps_add_api_settings_tab', 23, 1 );

			if ( is_plugin_active( 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php' ) ) {
				if ( defined( 'REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_VERSION' ) && REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_VERSION >= '1.0.12' ) {
					$this->loader->add_filter( 'wps_wpr_coupon_settings', $plugin_admin, 'wps_wpr_add_per_currrency_on_subtotal_option', 20 );
					$this->loader->add_filter( 'wps_wpr_notification_posted_data', $plugin_admin, 'wps_wpr_check_notification_checkbox_is_empty', 20, 2 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_custom_notification_callback', 20 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_signup_notification_callback', 21 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_product_purchase_notification_callback', 22 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_order_amount_notification_callback', 23 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_referral_notification_callback', 24 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_upgrade_membership_notification_callback', 25 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_deduct_assign_notification_callback', 26 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_points_on_cart_notification_callback', 27 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_order_total_range_notification_callback', 28 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_first_order_notification_callback', 29 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_bithday_notification_callback', 30 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_frontend_notification', 31 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_daily_login_callback', 30 );
					/*check sepecific notification enable*/
					$this->loader->add_filter( 'wps_wpr_check_custom_points_notification_enable', $plugin_admin, 'wps_wpr_check_specific_points_notification_enable_callback', 10, 2 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_coupon_notification', 31 );
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_number_of_order_rewards_points_notifications', 32 );

					$this->loader->add_filter( 'wps_wpr_is_variable_product', $plugin_admin, 'wps_wpr_product_points_test', 10, 2 );

				}
			}
			if ( is_plugin_active( 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php' ) ) {
				if ( defined( 'REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_VERSION' ) && REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_VERSION <= '1.0.11' ) {
					$this->loader->add_filter( 'wps_wpr_email_notification_settings', $plugin_admin, 'wps_wpr_add_email_notification_settings_unset_settings_for_old_version_callback', 99 );
				}
			}

			$this->loader->add_filter( 'wps_wpr_general_settings', $plugin_admin, 'wps_wpr_first_order_points' ); // first order points.
			$this->loader->add_filter( 'wps_wpr_general_settings', $plugin_admin, 'wps_wpr_round_points_settings' ); // make points round.
			$this->loader->add_filter( 'wps_wpr_general_settings', $plugin_admin, 'wps_wpr_birthday_order_points' ); // birthday points.
			$this->loader->add_filter( 'wps_wpr_general_settings', $plugin_admin, 'wps_wpr_daily_sign_up_points' ); // My Points Daily login Setting.
			$this->loader->add_filter( 'wps_wpr_general_settings', $plugin_admin, 'wps_wpr_customer_rank_listing' ); // My Customer Ranking Lisating Setting.
			$this->loader->add_filter( 'wps_wpr_general_settings', $plugin_admin, 'wps_wpr_user_roles' ); // user roles.

			$this->loader->add_filter( 'wps_points_admin_table_log', $plugin_admin, 'wps_wpr_admin_point_log' );
			$this->loader->add_filter( 'wcml_js_lock_fields_ids', $plugin_admin, 'wps_wpr_add_lock_custom_fields_ids' );

			// Export points table ajax call.
			$this->loader->add_action( 'wp_ajax_wps_wpr_export_points_table', $plugin_admin, 'wps_wpr_export_points_table_call' );
			// Remove assigned membership from user account.
			$this->loader->add_action( 'wp_ajax_wps_wpr_remove_assigned_membership', $plugin_admin, 'wps_wpr_remove_assigned_membership_call' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Points_And_Rewards_For_Woocommerce_Pro_Public( $this->get_plugin_name(), $this->get_version() );

		$callname_lic         = self::$lic_callback_function;
		$callname_lic_initial = self::$lic_ini_callback_function;
		$day_count            = self::$callname_lic_initial();

		/*Condition for validating.*/
		if ( self::$callname_lic() || 0 <= $day_count ) {

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
			$this->loader->add_action( 'wps_wpr_before_add_referral_section', $plugin_public, 'wps_wpr_add_referral_section' );
			$this->loader->add_action( 'wps_after_referral_link', $plugin_public, 'wps_wpr_add_invite_text' );
			$this->loader->add_filter( 'wps_wpr_referral_points', $plugin_public, 'wps_wpr_add_referral_resctrictions', 10, 3 );
			$this->loader->add_filter( 'woocommerce_product_review_comment_form_args', $plugin_public, 'wps_wpr_woocommerce_comment_point', 1000, 1 );
			/*This action is used for assigning the product referral purchase points*/
			$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'wps_wpr_pro_woocommerce_order_status_changed', 11, 3 );
			/*This action is used for generation of the user coupon*/
			$this->loader->add_action( 'wps_wpr_add_coupon_generation', $plugin_public, 'wps_wpr_add_coupon_conversion_settings', 10, 1 );
			$this->loader->add_action( 'wps_after_referral_link', $plugin_public, 'wps_wpr_add_coupon_code_generation', 10 );

			$this->loader->add_action( 'wps_wpr_list_coupons_generation', $plugin_public, 'wps_wpr_list_coupons_generation', 10, 1 );
			$this->loader->add_action( 'wp_ajax_wps_wpr_generate_original_coupon', $plugin_public, 'wps_wpr_generate_original_coupon' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wpr_generate_original_coupon', $plugin_public, 'wps_wpr_generate_original_coupon' );
			$this->loader->add_action( 'wp_ajax_wps_wpr_generate_custom_coupon', $plugin_public, 'wps_wpr_generate_custom_coupon' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wpr_generate_custom_coupon', $plugin_public, 'wps_wpr_generate_custom_coupon' );
			$this->loader->add_action( 'woocommerce_new_order_item', $plugin_public, 'wps_wpr_woocommerce_order_add_coupon_woo_latest_version', 10, 2 );
			$this->loader->add_action( 'wps_wpr_add_share_points', $plugin_public, 'wps_wpr_share_points_section' );
			$this->loader->add_action( 'wp_ajax_wps_wpr_sharing_point_to_other', $plugin_public, 'wps_wpr_sharing_point_to_other' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wpr_sharing_point_to_other', $plugin_public, 'wps_wpr_sharing_point_to_other' );
			$this->loader->add_action( 'woocommerce_before_add_to_cart_button', $plugin_public, 'wps_wpr_woocommerce_before_add_to_cart_button', 10, 1 );
			$this->loader->add_filter( 'woocommerce_add_cart_item_data', $plugin_public, 'wps_wpr_woocommerce_add_cart_item_data_pro', 10, 4 );
			$this->loader->add_filter( 'woocommerce_get_item_data', $plugin_public, 'wps_wpr_woocommerce_get_item_data_pro', 10, 2 );
			$this->loader->add_action( 'woocommerce_cart_calculate_fees', $plugin_public, 'wps_wpr_woocommerce_cart_calculate_fees_pro' );
			$this->loader->add_filter( 'woocommerce_update_cart_action_cart_updated', $plugin_public, 'wps_update_cart_points_pro' );
			$this->loader->add_action( 'woocommerce_before_calculate_totals', $plugin_public, 'wps_wpr_woocommerce_before_calculate_totals_pro', 20, 1 );
			$this->loader->add_action( 'woocommerce_checkout_create_order_line_item', $plugin_public, 'wps_wpr_woocommerce_add_order_item_meta_version_3', 20, 4 );
			// *Display the meta key*/
			$this->loader->add_filter( 'woocommerce_order_item_display_meta_key', $plugin_public, 'wps_wpr_woocommerce_order_item_display_meta_key_pro', 20, 1 );
			/*Update order meta of the order*/
			$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'wps_wpr_woocommerce_checkout_update_order_meta_pro', 20, 2 );
			$this->loader->add_action( 'wps_wpr_membership_cron_schedule', $plugin_public, 'wps_wpr_do_this_hourly' );
			$this->loader->add_filter( 'woocommerce_get_price_html', $plugin_public, 'wps_wpr_user_level_discount_on_price_pro', 20, 2 );
			$this->loader->add_action( 'wp_ajax_wps_wpr_append_variable_point', $plugin_public, 'wps_wpr_append_variable_point' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wpr_append_variable_point', $plugin_public, 'wps_wpr_append_variable_point' );
			$this->loader->add_action( 'wp_ajax_wps_pro_purchase_points_only', $plugin_public, 'wps_pro_purchase_points_only' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_pro_purchase_points_only', $plugin_public, 'wps_pro_purchase_points_only' );
			$this->loader->add_filter( 'woocommerce_thankyou_order_received_text', $plugin_public, 'wps_wpr_woocommerce_thankyou', 10, 2 );
			// *update*/
			$this->loader->add_filter( 'wps_wpr_enable_points_on_order_total', $plugin_public, 'wps_wpr_enable_points_on_order_total_pro', 10 );
			$this->loader->add_action( 'wps_wpr_points_on_order_total', $plugin_public, 'wps_wpr_points_on_order_total_pro', 10, 3 );
			$this->loader->add_filter( 'woocommerce_cart_totals_fee_html', $plugin_public, 'wps_wpr_woocommerce_cart_totals_fee_html_purchase_via_point', 10, 2 );
			$this->loader->add_action( 'wp_ajax_wps_wpr_remove_cart_purchase_via_points', $plugin_public, 'wps_wpr_remove_cart_purchase_via_points' );
			$this->loader->add_action( 'wps_wpr_point_limit_on_order_checkout', $plugin_public, 'wps_wpr_point_limit_on_order_checkout_pro', 10, 3 );
			$this->loader->add_filter( 'woocommerce_add_to_cart_validation', $plugin_public, 'wps_wpr_woocommerce_add_to_cart_validation', 10, 3 );
			$this->loader->add_filter( 'woocommerce_update_cart_validation', $plugin_public, 'wps_wpr_validate_update_cart', 10, 4 );
			$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'wps_wpr_woocommerce_order_status_cancel', 10, 3 );
			$this->loader->add_filter( 'woocommerce_variable_price_html', $plugin_public, 'wps_woocommerce_variable_price_html', 10, 2 );

			$this->loader->add_filter( 'wps_wpr_allowed_user_roles_points', $plugin_public, 'wps_wpr_allowed_user_roles_points_callback' );
			$this->loader->add_filter( 'wps_wpr_allowed_user_roles_points_features', $plugin_public, 'wps_wpr_allowed_user_roles_points_features_callback' );
			$this->loader->add_filter( 'wps_wpr_allowed_user_roles_points_features_order', $plugin_public, 'wps_wpr_allowed_user_roles_points_features_order_callback', 10, 2 );

			$this->loader->add_filter( 'init', $plugin_public, 'wps_wpr_referral_link_shortcode', 10, 2 );
			$this->loader->add_action( 'wp_footer', $plugin_public, 'wps_wpr_notify_user_load_popup_html' );

			$this->loader->add_filter( 'wps_wpr_allowed_user_roles_points_features_signup', $plugin_public, 'wps_wpr_allowed_user_roles_points_callback_signup', 10, 2 );

			$this->loader->add_filter( 'wps_wpr_fee_tax_calculation_points', $plugin_public, 'wps_wpr_fee_tax_calculation_on_product_purchase_points', 10, 3 );

			if ( is_plugin_active( 'points-and-rewards-for-woocommerce/points-rewards-for-woocommerce.php' ) ) {
				if ( defined( 'REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_VERSION' ) && REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_VERSION >= '1.0.12' ) {
					$this->loader->add_filter( 'wps_wpr_per_currency_points_on_subtotal', $plugin_public, 'wps_wpr_per_currency_points_on_subtotal_callback', 10, 2 );
					$this->loader->add_action( 'wps_wpr_membership_expiry_for_user_html', $plugin_public, 'wps_wpr_membership_expiry_for_user_html_callback' );
					$this->loader->add_action( 'wps_wpr_membership_expiry_date_for_user', $plugin_public, 'wps_wpr_membership_expiry_date_for_user_callback', 10, 3 );
				}
			}
			$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'wps_wpr_woocommerce_order_status_compl', 10, 3 );
			$this->loader->add_action( 'wps_points_on_first_order', $plugin_public, 'wps_wpr_woocommerce_points_log' );
			$this->loader->add_action( 'wps_change_amount_cart', $plugin_public, 'wps_wpr_function', 10, 3 );

			// For daily login point.
			$this->loader->add_action( 'wp_login', $plugin_public, 'wps_wpr_daily_login_function', 10, 2 );

			// Cron For daily Login.
			$this->loader->add_action( 'wps_wpr_points_daily_login_cron_schedule', $plugin_public, 'wps_wpr_do_this_daily_login_check', 10 );
			// For Listing the User in table.
			$this->loader->add_action( 'plugins_loaded', $plugin_public, 'wps_wpr_display_shortcode', 10 );

			$this->loader->add_action( 'woocommerce_before_cart_contents', $plugin_public, 'wps_wpr_woocommerce_notice_for_exclude_sale_item' );

			$this->loader->add_filter( 'wps_cart_content_check_for_sale_item', $plugin_public, 'wps_wpr_woocommerce_custom_restrict_discount' );
			$this->loader->add_filter( 'wps_round_down_cart_total_value', $plugin_public, 'wps_callback_for_round_down', 10, 4 );
			$this->loader->add_filter( 'wps_round_down_cart_total_value_amount', $plugin_public, 'wps_callback_for_round_down_fee', 10, 4 );

			$this->loader->add_action( 'woocommerce_edit_account_form', $plugin_public, 'wps_wpr_woocommerce_birthday_input_box' );
			$this->loader->add_action( 'woocommerce_save_account_details', $plugin_public, 'wps_woocommerce_save_account_details', 10, 1 );
			$this->loader->add_action( 'init', $plugin_public, 'wps_eg_schedule_midnight_loggg' );
			$this->loader->add_action( 'wps_eg_midnight_loggg', $plugin_public, 'wps_eg_log_action_data' );
			$this->loader->add_action( 'wps_mail_box', $plugin_public, 'wps_wpr_woocommerce_email', 10, 2 );
			$this->loader->add_action( 'woocommerce_before_checkout_form', $plugin_public, 'wps_wpr_woocommerce_notice_sale' );
			$this->loader->add_action( 'wp_ajax_wps_wpr_email_notify_refer', $plugin_public, 'wps_wpr_email_notify_refer' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_wpr_email_notify_refer', $plugin_public, 'wps_wpr_email_notify_refer' );
			$this->loader->add_action( 'woocommerce_order_status_changed', $plugin_public, 'wps_wpr_pro_woocommerce_order_status_changed_points', 10, 3 );

			$this->loader->add_action( 'woocommerce_coupon_is_valid', $plugin_public, 'wps_wpr_pro_woocommerce_apply_product_on_coupon', 10, 3 );

			// Coupon for paypal.
			$this->loader->add_filter( 'woocommerce_get_shop_coupon_data', $plugin_public, 'wps_wpr_pro_validate_virtual_coupon_for_points', 10, 2 );
		}
	}

	/**
	 * Public static variable to be accessed in this plugin.
	 *
	 * @var string lic_callback_function
	 */
	public static $lic_callback_function = 'check_lcns_validity';

	/**
	 * Public static variable to be accessed in this plugin.
	 *
	 * @var string lic_callback_function
	 */
	public static $lic_ini_callback_function = 'check_lcns_initial_days';

	/**
	 * Validate the use of features of this plugin.
	 *
	 * @since    1.0.0
	 */
	public static function check_lcns_validity() {

		$points_and_rewards_for_woocommerce_pro_lcns_key = get_option( 'ultimate_woocommerce_points_and_rewards_lcns_key', '' );

		$points_and_rewards_for_woocommerce_pro_lcns_status = get_option( 'ultimate_woocommerce_points_and_rewards_lcns_status', '' );

		if ( $points_and_rewards_for_woocommerce_pro_lcns_key && 'true' === $points_and_rewards_for_woocommerce_pro_lcns_status ) {

			return true;
		} else {

			return false;
		}
	}

	/**
	 * Validate the use of features of this plugin for initial days.
	 *
	 * @since    1.0.0
	 */
	public static function check_lcns_initial_days() {

		$thirty_days = get_option( 'points_and_rewards_for_woocommerce_pro_lcns_thirty_days', 0 );

		$current_time = current_time( 'timestamp' );

		$day_count = ( $thirty_days - $current_time ) / ( 24 * 60 * 60 );

		return $day_count;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Points_And_Rewards_For_Woocommerce_Pro_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
