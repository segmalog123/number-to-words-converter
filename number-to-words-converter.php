<?php
/**
 * Plugin Name:       Number To Words Converter
 * Plugin URI:        https://www.NumberToWordsConverter.com
 * Description:       Converts numbers to words in English and French. Generates virtual pages for /how-do-you-spell-{n}-in-words/ and /how-to-say-{n}-in-french/ URLs.
 * Version:           0.0.3
 * Author:            Chiffre en Lettre
 * Author URI:        https://www.NumberToWordsConverter.com
 * Text Domain:       number-to-words-converter
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Requires PHP:      7.2
 */

if (!defined('ABSPATH')) {
    exit;
}

// ============================================================
// EARLY CACHE BYPASS — must run before any cache plugin buffers
// ============================================================
// Tell WP Fastest Cache (and W3TC, WP Super Cache) NOT to cache
// our conversion pages. These pages are already cached by Cloudflare.
// This MUST be defined before plugins_loaded / output buffering starts.
$ntw_request_uri = $_SERVER['REQUEST_URI'] ?? '';
if (
    strpos($ntw_request_uri, '/how-do-you-spell-') !== false ||
    strpos($ntw_request_uri, '/how-to-say-') !== false
) {
    if (!defined('DONOTCACHEPAGE')) {
        define('DONOTCACHEPAGE', true);
    }
    if (!defined('DONOTMINIFY')) {
        define('DONOTMINIFY', true); // Also skip JS/CSS minification on these pages
    }
}
unset($ntw_request_uri);
// ============================================================

// Plugin constants
define('NTW_PLUGIN_VERSION', '1.0.0');
define('NTW_PLUGIN_FILE', __FILE__);
define('NTW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NTW_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NTW_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * PSR-4 style Autoloader for NumberToWordsConverter namespace.
 */
spl_autoload_register(function ($class) {
    $prefix = 'NumberToWordsConverter\\';
    $base_dir = NTW_PLUGIN_DIR . 'src/';
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Load global function wrappers (non-namespaced for template compatibility).
require_once NTW_PLUGIN_DIR . 'src/global-functions.php';

/**
 * Plugin activation.
 * We set a flag so rewrite rules are flushed on the NEXT request.
 * This is the correct WordPress pattern — flushing during activation
 * itself is unreliable because other plugins haven't registered their
 * rules yet at that point.
 */
function ntw_activate()
{
    // Register our rules first
    $rewrite = new NumberToWordsConverter\RewriteRules();
    $rewrite->register();
    // Set flag to flush on next load
    update_option('ntw_flush_rewrite_rules', true);
}
register_activation_hook(__FILE__, 'ntw_activate');

/**
 * Plugin deactivation: flush rewrite rules.
 */
function ntw_deactivate()
{
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'ntw_deactivate');

/**
 * Flush rewrite rules on the first request after activation.
 * This is more reliable than flushing during the activation hook itself.
 */
function ntw_maybe_flush_rewrite_rules()
{
    if (get_option('ntw_flush_rewrite_rules')) {
        flush_rewrite_rules();
        delete_option('ntw_flush_rewrite_rules');
    }
}
add_action('init', 'ntw_maybe_flush_rewrite_rules', 20);

/**
 * Initialize the plugin.
 */
function ntw_init_plugin()
{
    // Initialize the core plugin (handles templates, hooks, assets)
    $plugin = new \NumberToWordsConverter\Plugin();
    $plugin->init();

    // Initialize custom sitemaps
    $plugin_sitemap = new \NumberToWordsConverter\SitemapController();
    $plugin_sitemap->init();

    // Init Widgets
    add_action('widgets_init', function () {
        if (class_exists('\NumberToWordsConverter\Widgets\ConversionWidget')) {
            register_widget('\NumberToWordsConverter\Widgets\ConversionWidget');
        }
    });

}
add_action('plugins_loaded', 'ntw_init_plugin');
