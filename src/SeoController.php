<?php
namespace NumberToWordsConverter;

if (!defined('ABSPATH')) {
    exit;
}

use NumberToWordsConverter\Converters\ConverterHelper;

/**
 * Hooks into Yoast SEO to dynamically inject SEO data
 * for the virtual conversion pages.
 */
class SeoController
{

    /**
     * Hook into Yoast filters.
     */
    public function init()
    {
        add_filter('wpseo_title', [$this, 'filterTitle'], 10, 1);
        add_filter('wpseo_metadesc', [$this, 'filterMetaDesc'], 10, 1);
        add_filter('wpseo_opengraph_title', [$this, 'filterOgTitle'], 10, 1);
        add_filter('wpseo_opengraph_url', [$this, 'filterOgUrl'], 10, 1);
        add_filter('wpseo_opengraph_type', [$this, 'filterOgType'], 10, 1);
        add_filter('wpseo_opengraph_image', [$this, 'filterOgImage'], 10, 1);
        add_filter('wpseo_robots', [$this, 'filterRobots'], 10, 1);

        // Yoast's Canonical_Presenter skips virtual pages entirely,
        // so wpseo_canonical never fires. Two-part fix:
        // 1. Tell Yoast not to output its own canonical (return false)
        // 2. Output our own <link rel="canonical"> via wp_head
        add_filter('wpseo_canonical', [$this, 'disableYoastCanonical'], 10, 1);
        add_action('wp_head', [$this, 'outputCanonical'], 2);
    }

    /**
     * Get the current number_id from the query.
     *
     * @return string|false
     */
    private function getNumberId()
    {
        global $wp_query;
        $number_id = $wp_query->get('number_id');
        if (isset($number_id) && $number_id !== '') {
            return $number_id;
        }
        return false;
    }

    /**
     * Get the current factorial_id from the query.
     * @return string|false
     */
    private function getFactorialId()
    {
        global $wp_query;
        $factorial_id = $wp_query->get('factorial_id');
        if (isset($factorial_id) && $factorial_id !== '') {
            return $factorial_id;
        }
        return false;
    }

    /**
     * Check if current page is the English landing page.
     *
     * @return bool
     */
    private function isEnglishLandingPage()
    {
        global $wp_query;
        return $wp_query->get('ntw_page') === 'numbers-in-french';
    }

    /**
     * Check if we are on the /factorial-calculator/ landing page.
     * @return bool
     */
    private function isFactorialPage()
    {
        global $wp_query;
        return $wp_query->get('ntw_page') === 'factorial-calculator';
    }

    /**
     * Check if we are on the /factoring-calculator/ landing page.
     * @return bool
     */
    private function isFactoringPage()
    {
        global $wp_query;
        return $wp_query->get('ntw_page') === 'factoring-calculator';
    }

    /**
     * Get the current factor_id from the query (/factors-of-X/).
     * @return string|false
     */
    private function getFactorId()
    {
        global $wp_query;
        $factor_id = $wp_query->get('factor_id');
        if (isset($factor_id) && $factor_id !== '') {
            return $factor_id;
        }
        return false;
    }

    /**
     * Filter the page title.
     *
     * @param string $title Original title.
     * @return string
     */
    public function filterTitle($title)
    {
        if ($this->isEnglishLandingPage()) {
            return 'Free English to French Converter | Master French numbers 1-100';
        }
        if ($this->isFactorialPage()) {
            return 'N Factorial Calculator: Find n! & Factorial Formula';
        }
        if ($this->isFactoringPage()) {
            return 'Factoring Calculator with Steps | Factor Completely';
        }
        $factorial = $this->getFactorialId();
        if ($factorial !== false) {
            return 'What is ' . $factorial . ' Factorial? (' . $factorial . '!) | Exact Result & Calculation';
        }
        $factor = $this->getFactorId();
        if ($factor !== false) {
            return 'Factors of ' . $factor . ' | Prime Factorization & Factor Pairs';
        }
        $number = $this->getNumberId();
        if ($number !== false) {
            return ConverterHelper::convert($number, 'title');
        }
        return $title;
    }

    /**
     * Filter the meta description.
     *
     * @param string $desc Original description.
     * @return string
     */
    public function filterMetaDesc($desc)
    {
        if ($this->isEnglishLandingPage()) {
            return 'Convert English numbers to French words with our free tool. Get pronunciation tips, rules, and exercises. Master French numbers 1-100 easily!';
        }
        if ($this->isFactorialPage()) {
            return 'Use our free n factorial calculator to instantly find n! for any number. Discover the factorial formula, step-by-step calculations, and rules for zero (0!).';
        }
        if ($this->isFactoringPage()) {
            return 'Use our free factoring calculator with steps to instantly find the factors of any number, calculate prime factor decomposition, and find the GCF completely.';
        }
        $factorial = $this->getFactorialId();
        if ($factorial !== false) {
            return 'Find out exactly what ' . $factorial . ' factorial (' . $factorial . '!) is. See the step-by-step calculation, trailing zeros, and permutations for the number ' . $factorial . '.';
        }
        $factor = $this->getFactorId();
        if ($factor !== false) {
            return 'Find the exact factors of ' . $factor . ', including its prime factorization, factor pairs, and a step-by-step breakdown of how to completely factor the number ' . $factor . '.';
        }
        $number = $this->getNumberId();
        if ($number !== false) {
            return ConverterHelper::convert($number, 'desc');
        }
        return $desc;
    }

    /**
     * Disable Yoast's canonical for our virtual pages.
     * Return false so Yoast doesn't output a <link rel="canonical">.
     * We handle it ourselves in outputCanonical().
     *
     * @param string $canonical Original canonical.
     * @return string|false
     */
    public function disableYoastCanonical($canonical)
    {
        $number = $this->getNumberId();
        if ($number !== false) {
            return false;
        }
        return $canonical;
    }

    /**
     * Output <link rel="canonical"> directly in <head>.
     * Yoast's Canonical_Presenter silently skips virtual pages,
     * so we must output it ourselves.
     */
    public function outputCanonical()
    {
        if ($this->isEnglishLandingPage()) {
            echo '<link rel="canonical" href="' . esc_url(home_url('/numbers-in-french/')) . '" />' . "\n";
            return;
        }
        if ($this->isFactorialPage()) {
            echo '<link rel="canonical" href="' . esc_url(home_url('/factorial-calculator/')) . '" />' . "\n";
            return;
        }
        $factorial = $this->getFactorialId();
        if ($factorial !== false) {
            echo '<link rel="canonical" href="' . esc_url(home_url('/what-is-' . $factorial . '-factorial/')) . '" />' . "\n";
            return;
        }
        $factor = $this->getFactorId();
        if ($factor !== false) {
            echo '<link rel="canonical" href="' . esc_url(home_url('/factors-of-' . $factor . '/')) . '" />' . "\n";
            return;
        }
        $number = $this->getNumberId();
        if ($number !== false) {
            $url = ConverterHelper::convert($number, 'url');
            echo '<link rel="canonical" href="' . esc_url($url) . '" />' . "\n";
        }
    }

    /**
     * Filter the OG title.
     *
     * @param string $title Original OG title.
     * @return string
     */
    public function filterOgTitle($title)
    {
        $number = $this->getNumberId();
        if ($number !== false) {
            return ConverterHelper::convert($number, 'title');
        }
        return $title;
    }

    /**
     * Filter the OG URL.
     *
     * @param string $url Original OG URL.
     * @return string
     */
    public function filterOgUrl($url)
    {
        $number = $this->getNumberId();
        if ($number !== false) {
            return ConverterHelper::convert($number, 'url');
        }
        return $url;
    }

    /**
     * Filter the OG type to 'article' for conversion pages.
     *
     * @param string $type Original OG type.
     * @return string
     */
    public function filterOgType($type)
    {
        $number = $this->getNumberId();
        if ($number !== false) {
            return 'article';
        }
        return $type;
    }

    /**
     * Filter the OG image.
     *
     * @param string $image Original OG image.
     * @return string
     */
    public function filterOgImage($image)
    {
        $number = $this->getNumberId();
        if ($number !== false) {
            return home_url('/wp-content/uploads/2020/04/NumberToWordsConverterdefault.png');
        }
        return $image;
    }

    /**
     * Filter robots meta to ensure indexing.
     *
     * @param string $robots Original robots string.
     * @return string
     */
    public function filterRobots($robots)
    {
        if ($this->isEnglishLandingPage()) {
            return 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
        }
        $number = $this->getNumberId();
        if ($number !== false) {
            return 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
        }
        $factorial = $this->getFactorialId();
        if ($factorial !== false) {
            return 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
        }
        $factor = $this->getFactorId();
        if ($factor !== false) {
            return 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
        }
        return $robots;
    }
}
