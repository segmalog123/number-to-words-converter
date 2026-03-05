<?php
namespace NumberToWordsConverter;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles the admin settings page for the plugin.
 */
class AdminSettings
{
    /**
     * Hook into the admin.
     */
    public function init()
    {
        add_action('admin_menu', [$this, 'addSettingsPage']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    /**
     * Add the settings page under "Settings".
     */
    public function addSettingsPage()
    {
        add_options_page(
            'Convertisseur Ads',
            'Convertisseur Ads',
            'manage_options',
            'cel-settings',
            [$this, 'renderSettingsPage']
        );
    }

    /**
     * Register the settings and AJAX actions.
     */
    public function registerSettings()
    {
        // We still register the setting so WP knows about it, but we won't use options.php
        register_setting('ntw_settings_group', 'ntw_ad_code');

        // Handle custom AJAX save to bypass WAFs blocking options.php
        add_action('wp_ajax_ntw_save_ad_code', [$this, 'ajaxSaveAdCode']);
    }

    /**
     * Render the settings page HTML and interceptform via JS.
     */
    public function renderSettingsPage()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Get options for French tool
        $fr_left = get_option('ntw_ad_fr_left', '');
        $fr_center = get_option('ntw_ad_fr_center', '');
        $fr_right = get_option('ntw_ad_fr_right', '');

        // Get options for English tool
        $en_left = get_option('ntw_ad_en_left', '');
        $en_center = get_option('ntw_ad_en_center', '');
        $en_right = get_option('ntw_ad_en_right', '');
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <p>Here you can set up to 3 ad blocks (Left, Center, Right). They will be displayed side-by-side. If a block is
                empty, it will not appear.</p>

            <div id="cel-save-message" class="notice notice-success is-dismissible" style="display:none;">
                <p>Settings saved successfully!</p>
            </div>

            <form id="cel-ads-form" method="post">
                <hr style="margin: 20px 0;">
                <h2>Ads : Tool 1 - English Converter (/how-do-you-spell/...)</h2>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ntw_ad_fr_left">Left Block</label></th>
                            <td><textarea name="ntw_ad_fr_left" id="ntw_ad_fr_left" rows="5" cols="60"
                                    class="large-text code"><?php echo esc_textarea($fr_left); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ntw_ad_fr_center">Center Block</label></th>
                            <td><textarea name="ntw_ad_fr_center" id="ntw_ad_fr_center" rows="5" cols="60"
                                    class="large-text code"><?php echo esc_textarea($fr_center); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ntw_ad_fr_right">Right Block</label></th>
                            <td><textarea name="ntw_ad_fr_right" id="ntw_ad_fr_right" rows="5" cols="60"
                                    class="large-text code"><?php echo esc_textarea($fr_right); ?></textarea></td>
                        </tr>
                    </tbody>
                </table>

                <hr style="margin: 20px 0;">
                <h2>Ads : Tool 2 - French Converter (/how-to-say/...)</h2>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="ntw_ad_en_left">Left Block</label></th>
                            <td><textarea name="ntw_ad_en_left" id="ntw_ad_en_left" rows="5" cols="60"
                                    class="large-text code"><?php echo esc_textarea($en_left); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ntw_ad_en_center">Center Block</label></th>
                            <td><textarea name="ntw_ad_en_center" id="ntw_ad_en_center" rows="5" cols="60"
                                    class="large-text code"><?php echo esc_textarea($en_center); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="ntw_ad_en_right">Right Block</label></th>
                            <td><textarea name="ntw_ad_en_right" id="ntw_ad_en_right" rows="5" cols="60"
                                    class="large-text code"><?php echo esc_textarea($en_right); ?></textarea></td>
                        </tr>
                    </tbody>
                </table>

                <?php wp_nonce_field('ntw_save_ad_code_nonce', 'ntw_nonce'); ?>
                <p class="submit">
                    <button type="submit" class="button button-primary" id="cel-submit-btn">Save Changes</button>
                    <span class="spinner" id="cel-spinner"></span>
                </p>
            </form>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                $('#cel-ads-form').on('submit', function (e) {
                    e.preventDefault();

                    var btn = $('#cel-submit-btn');
                    var spinner = $('#cel-spinner');
                    var msg = $('#cel-save-message');

                    btn.prop('disabled', true);
                    spinner.addClass('is-active');
                    msg.hide();

                    var data = {
                        action: 'ntw_save_ad_code',
                        ntw_nonce: $('#ntw_nonce').val(),
                        ntw_ad_fr_left: $('#ntw_ad_fr_left').val(),
                        ntw_ad_fr_center: $('#ntw_ad_fr_center').val(),
                        ntw_ad_fr_right: $('#ntw_ad_fr_right').val(),
                        ntw_ad_en_left: $('#ntw_ad_en_left').val(),
                        ntw_ad_en_center: $('#ntw_ad_en_center').val(),
                        ntw_ad_en_right: $('#ntw_ad_en_right').val()
                    };

                    $.post(ajaxurl, data, function (response) {
                        btn.prop('disabled', false);
                        spinner.removeClass('is-active');

                        if (response.success) {
                            msg.show();
                        } else {
                            alert('Error while saving: ' + (response.data || 'Unknown'));
                        }
                    }).fail(function (xhr) {
                        btn.prop('disabled', false);
                        spinner.removeClass('is-active');
                        alert('Network Error. If Wordfence still blocks, permit this action in Wordfence Live Traffic.');
                    });
                });
            });
        </script>
        <?php
    }

    /**
     * AJAX handler to save the ad code securely while bypassing generic WAF rules.
     */
    public function ajaxSaveAdCode()
    {
        // Verify nonce
        if (!isset($_POST['ntw_nonce']) || !wp_verify_nonce($_POST['ntw_nonce'], 'ntw_save_ad_code_nonce')) {
            wp_send_json_error('Invalid nonce');
        }

        // Verify permissions (admin only)
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }

        // The exact keys we expect to save
        $ad_keys = [
            'ntw_ad_fr_left',
            'ntw_ad_fr_center',
            'ntw_ad_fr_right',
            'ntw_ad_en_left',
            'ntw_ad_en_center',
            'ntw_ad_en_right'
        ];

        foreach ($ad_keys as $key) {
            if (isset($_POST[$key])) {
                $code = wp_unslash($_POST[$key]);
                update_option($key, $code);
            }
        }

        wp_send_json_success();
    }
}
