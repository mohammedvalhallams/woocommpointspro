/**
 * The admin-specific js functionlity
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    points-and-rewards-for-wooCommerce
 * @subpackage points-and-rewards-for-wooCommerce/admin
 */

jQuery(document).ready(function ($) {
    jQuery('#wps-wpr-install-lite').click(function (e) {
        e.preventDefault();
        jQuery("#wps_notice_loader").show();
        var data = {
            action: 'wps_wpr_activate_lite_plugin',
        };
        $.ajax({
            url: wps_wpr_activation.ajax_url,
            type: 'POST',
            data: data,
            success: function (response) {
                jQuery("#wps_notice_loader").show();
                if (response == 'success') {
                    window.location.reload();
                }
            }
        });
    });
});