<?php
namespace NumberToWordsConverter;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Manages JS and CSS asset registration and enqueuing.
 */
class AssetManager
{

    /**
     * Hook into WordPress.
     */
    public function init()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueAssets']);
    }

    /**
     * Enqueue plugin scripts and styles.
     */
    public function enqueueAssets()
    {
        // jQuery Countdown
        wp_enqueue_script(
            'cel-countdown-js',
            ntw_PLUGIN_URL . 'assets/js/jquery.countdown.min.js',
            ['jquery'],
            ntw_PLUGIN_VERSION,
            true
        );

        // Main converter script
        wp_register_script(
            'cel-custom-script',
            ntw_PLUGIN_URL . 'assets/js/script.js?v=' . strval(microtime(true)),
            ['jquery'],
            ntw_PLUGIN_VERSION,
            true
        );
        // Detect language context for the search bar
        $is_english_page = get_query_var('ntw_page') === 'convertisseur-anglais'
            || strpos($_SERVER['REQUEST_URI'] ?? '', '/comment-on-dit/') !== false;

        wp_localize_script(
            'cel-custom-script',
            'jsdata',
            [
                'site_url' => site_url(),
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ntw_convert_nonce'),
                'is_landing_page' => (is_front_page() || $is_english_page) ? '1' : '0',
                'default_lang' => $is_english_page ? 'en' : 'fr',
            ]
        );
        wp_enqueue_script('cel-custom-script');

        // Converter styles
        wp_enqueue_style(
            'cel-converter-css',
            ntw_PLUGIN_URL . 'assets/css/converter.css',
            [],
            ntw_PLUGIN_VERSION
        );

        // Result page styles (on conversion result pages OR English landing page)
        // Reuse the $is_english_page check from above (partial match) or direct check
        $should_load_css = get_query_var('number_id') !== '' || get_query_var('ntw_page') === 'convertisseur-anglais';

        if ($should_load_css) {
            wp_enqueue_style(
                'cel-result-page-css',
                ntw_PLUGIN_URL . 'assets/css/result-page.css',
                [],
                ntw_PLUGIN_VERSION
            );
        }
    }
}
