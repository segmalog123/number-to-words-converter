<?php
namespace NumberToWordsConverter;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Loads the correct template for conversion pages.
 * Intercepts WordPress template loading to serve virtual page templates.
 */
class TemplateLoader
{

    /**
     * Hook into WordPress.
     */
    public function init()
    {
        add_filter('template_include', [$this, 'loadTemplate']);
        add_action('wp', [$this, 'fix404Flags']);
    }

    /**
     * Force WordPress to recognize our virtual pages as valid (200 OK)
     * instead of throwing a 404 Not Found header.
     */
    public function fix404Flags()
    {
        global $wp_query;

        $number_to_convert = $wp_query->get('number_id');
        $ntw_page = $wp_query->get('ntw_page');

        if (!empty($number_to_convert) || $ntw_page === 'convertisseur-anglais') {
            $wp_query->is_404 = false;
            $wp_query->is_page = true;
            status_header(200);
        }
    }

    /**
     * Load the appropriate conversion template.
     *
     * @param string $template Default template path.
     * @return string Modified template path.
     */
    public function loadTemplate($template)
    {
        global $wp_query, $wp;

        // English landing page: /convertisseur-anglais/
        $ntw_page = $wp_query->get('ntw_page');
        if ($ntw_page === 'convertisseur-anglais') {
            $plugin_template = NTW_PLUGIN_DIR . 'templates/convertisseur-anglais.php';
            if (file_exists($plugin_template)) {
                return $plugin_template;
            }
        }

        $number_to_convert = $wp_query->get('number_id');

        if (!isset($number_to_convert) || $number_to_convert === '') {
            return $template;
        }

        $current_url = home_url(add_query_arg([], $wp->request ?? ''));

        // Determine which template to load
        if (strpos($current_url, '/how-to-say-') !== false) {
            $template_file = 'automatic-convert-english.php'; // Second tool
        } else {
            $template_file = 'automatic-convert.php'; // First tool
        }

        // Allow theme override: check theme directory first
        $theme_template = locate_template('number-to-words-converter/' . $template_file);
        if ($theme_template) {
            return $theme_template;
        }

        // Fallback to plugin template
        $plugin_template = NTW_PLUGIN_DIR . 'templates/' . $template_file;
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }

        return $template;
    }
}
