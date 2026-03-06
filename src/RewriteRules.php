<?php
namespace NumberToWordsConverter;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handles URL rewrite rules and query variable registration
 * for the virtual conversion pages.
 */
class RewriteRules
{

    /**
     * Hook into WordPress.
     */
    public function init()
    {
        add_action('init', [$this, 'register']);
        add_filter('query_vars', [$this, 'registerQueryVars']);
        // Use template_redirect (fires on every front-end request) instead of
        // redirect_canonical (which WordPress skips for virtual pages).
        add_action('template_redirect', [$this, 'forceTrailingSlash'], 1);
    }

    /**
     * Register the rewrite rules for both conversion endpoints.
     */
    public function register()
    {
        add_rewrite_rule(
            '^how-do-you-spell-([0-9]+)-in-words/?$',
            'index.php?number_id=$matches[1]',
            'top'
        );

        add_rewrite_rule(
            '^how-to-say-([0-9]+)-in-french/?$',
            'index.php?number_id=$matches[1]',
            'top'
        );

        // Add support for the non-dynamic English landing page.
        add_rewrite_rule(
            '^numbers-in-french/?$',
            'index.php?ntw_page=numbers-in-french',
            'top'
        );
    }

    /**
     * Register the query variables so WP can parse them from the URL.
     * @param array $vars Existing query vars.
     * @return array Modified query vars.
     */
    public function registerQueryVars($vars)
    {
        $vars[] = 'number_id';
        $vars[] = 'ntw_page'; // used for /numbers-in-french/
        return $vars;
    }

    /**
     * Force trailing slash on conversion URLs via 301 redirect.
     * Fires on template_redirect so it works for virtual pages.
     *
     * /how-do-you-spell-95-in-words   → 301 → /how-do-you-spell-95-in-words/
     * /how-to-say-25-in-french → 301 → /how-to-say-25-in-french/
     */
    public function forceTrailingSlash()
    {
        global $wp_query;

        $number_id = $wp_query->get('number_id');
        if (empty($number_id)) {
            return;
        }

        // Get the current request URI (e.g. /how-do-you-spell-95-in-words)
        $request_uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($request_uri, PHP_URL_PATH);

        // If the path does NOT end with a trailing slash, 301 redirect
        if ($path !== '/' && substr($path, -1) !== '/') {
            $redirect_url = trailingslashit(home_url($path));
            // Preserve any query string
            $query = parse_url($request_uri, PHP_URL_QUERY);
            if (!empty($query)) {
                $redirect_url .= '?' . $query;
            }
            wp_redirect($redirect_url, 301);
            exit;
        }
    }
}

