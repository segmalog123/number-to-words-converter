<?php
namespace NumberToWordsConverter;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registers custom sitemaps for conversion pages with Yoast SEO.
 */
class SitemapController
{

    /**
     * Hook into WordPress/Yoast.
     */
    public function init()
    {
        add_action('init', [$this, 'registerSitemaps']);
        add_action('init', [$this, 'registerSitemapActions']);
        add_filter('wpseo_sitemap_index', [$this, 'addEcrireSitemapIndex']);
        add_filter('wpseo_sitemap_index', [$this, 'addCommentOnDitSitemapIndex']);
        
        // New custom index filters
        add_filter('wpseo_sitemap_index', [$this, 'addFactorialSitemapIndex']);
        add_filter('wpseo_sitemap_index', [$this, 'addFactorsSitemapIndex']);
        add_filter('wpseo_sitemap_index', [$this, 'addGcfSitemapIndex']);
        add_filter('wpseo_sitemap_index', [$this, 'addCalculatorsSitemapIndex']);
    }

    /**
     * Register the custom sitemaps with Yoast.
     */
    public function registerSitemaps()
    {
        global $wpseo_sitemaps;

        if (isset($wpseo_sitemaps) && !empty($wpseo_sitemaps)) {
            $wpseo_sitemaps->register_sitemap('spellinwords', [$this, 'createEcrireSitemap']);
            $wpseo_sitemaps->register_sitemap('sayinfrench', [$this, 'createCommentOnDitSitemap']);
            
            // New sitemaps
            $wpseo_sitemaps->register_sitemap('factorial', [$this, 'createFactorialSitemap']);
            $wpseo_sitemaps->register_sitemap('factors', [$this, 'createFactorsSitemap']);
            $wpseo_sitemaps->register_sitemap('gcf', [$this, 'createGcfSitemap']);
            $wpseo_sitemaps->register_sitemap('calculators', [$this, 'createCalculatorsSitemap']);
        }
    }

    /**
     * Register sitemap action hooks.
     */
    public function registerSitemapActions()
    {
        add_action('wp_seo_do_sitemap_our-spellinwords', [$this, 'createEcrireSitemap']);
        add_action('wp_seo_do_sitemap_our-sayinfrench', [$this, 'createCommentOnDitSitemap']);
        
        // Actions for new sitemaps
        add_action('wp_seo_do_sitemap_our-factorial', [$this, 'createFactorialSitemap']);
        add_action('wp_seo_do_sitemap_our-factors', [$this, 'createFactorsSitemap']);
        add_action('wp_seo_do_sitemap_our-gcf', [$this, 'createGcfSitemap']);
        add_action('wp_seo_do_sitemap_our-calculators', [$this, 'createCalculatorsSitemap']);
    }

    /**
     * Create the French conversion sitemap (ecrirechiffre).
     */
    public function createEcrireSitemap()
    {
        global $wpseo_sitemaps;
        $output = '';
        $vips = \NumberToWordsConverter\NumberVipList::getAllVips();

        foreach ($vips as $i) {
            $url = [];
            $url['loc'] = site_url() . '/how-do-you-spell-' . $i . '-in-words/';
            $url['mod'] = date('c', time());
            $output .= $wpseo_sitemaps->renderer->sitemap_url($url);
        }

        $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
        $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $sitemap .= $output . '</urlset>';

        $wpseo_sitemaps->set_sitemap($sitemap);
    }

    /**
     * Create the English conversion sitemap (commentonditchiffre).
     */
    public function createCommentOnDitSitemap()
    {
        global $wpseo_sitemaps;
        $output = '';
        $vips = \NumberToWordsConverter\NumberVipList::getAllVips();

        foreach ($vips as $i) {
            $url = [];
            $url['loc'] = site_url() . '/how-to-say-' . $i . '-in-french/';
            $url['mod'] = date('c', time());
            $output .= $wpseo_sitemaps->renderer->sitemap_url($url);
        }

        $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
        $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $sitemap .= $output . '</urlset>';

        $wpseo_sitemaps->set_sitemap($sitemap);
    }

    /**
     * Create the Factorial conversion sitemap.
     */
    public function createFactorialSitemap()
    {
        global $wpseo_sitemaps;
        $output = '';
        
        // Factorial ranges:
        // IF X <= 200 : index
        // IF X > 200 AND X % 50 == 0 : index (up to 10000)
        for ($i = 0; $i <= 10000; $i++) {
            if ($i <= 200 || ($i > 200 && $i % 50 === 0)) {
                $url = [];
                $url['loc'] = site_url() . '/what-is-' . $i . '-factorial/';
                $url['mod'] = date('c', time());
                $output .= $wpseo_sitemaps->renderer->sitemap_url($url);
            }
        }

        $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
        $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $sitemap .= $output . '</urlset>';

        $wpseo_sitemaps->set_sitemap($sitemap);
    }

    /**
     * Create the Factors conversion sitemap.
     */
    public function createFactorsSitemap()
    {
        global $wpseo_sitemaps;
        $output = '';

        // Factors ranges:
        // IF X <= 1000 : index
        // (X >= 1900 && X <= 2100): index
        // (X > 1000 && X <= 10000 && X % 100 == 0): index
        // (X > 10000 && X % 500 == 0) : index
        for ($i = 1; $i <= 1000000; $i++) {
            if (
                $i <= 1000 ||
                ($i >= 1900 && $i <= 2100) ||
                ($i > 1000 && $i <= 10000 && $i % 100 === 0) ||
                ($i > 10000 && $i % 500 === 0)
            ) {
                $url = [];
                $url['loc'] = site_url() . '/factors-of-' . $i . '/';
                $url['mod'] = date('c', time());
                $output .= $wpseo_sitemaps->renderer->sitemap_url($url);
            }
        }

        $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
        $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $sitemap .= $output . '</urlset>';

        $wpseo_sitemaps->set_sitemap($sitemap);
    }

    /**
     * Create the Greatest Common Factor (GCF) sitemap.
     */
    public function createGcfSitemap()
    {
        global $wpseo_sitemaps;
        $output = '';

        // GCF range: 1 to 100, x <= y to prevent duplication
        for ($x = 1; $x <= 100; $x++) {
            for ($y = $x; $y <= 100; $y++) {
                $url = [];
                $url['loc'] = site_url() . '/gcf-of-' . $x . '-and-' . $y . '/';
                $url['mod'] = date('c', time());
                $output .= $wpseo_sitemaps->renderer->sitemap_url($url);
            }
        }

        $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
        $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $sitemap .= $output . '</urlset>';

        $wpseo_sitemaps->set_sitemap($sitemap);
    }

    /**
     * Create the Calculators landing pages sitemap.
     */
    public function createCalculatorsSitemap()
    {
        global $wpseo_sitemaps;
        $output = '';

        $pages = [
            '/numbers-in-french/',
            '/factorial-calculator/',
            '/factoring-calculator/'
        ];

        foreach ($pages as $path) {
            $url = [];
            $url['loc'] = site_url() . $path;
            $url['mod'] = date('c', time());
            $output .= $wpseo_sitemaps->renderer->sitemap_url($url);
        }

        $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
        $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
        $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        $sitemap .= $output . '</urlset>';

        $wpseo_sitemaps->set_sitemap($sitemap);
    }

    /**
     * Add the ecrire sitemap to the sitemap index.
     *
     * @param string $items Existing sitemap index items.
     * @return string
     */
    public function addEcrireSitemapIndex($items)
    {
        $items .= '    <sitemap>   
        <loc>' . site_url() . '/spellinwords-sitemap.xml</loc>
        <lastmod>' . date('c', time()) . '</lastmod>
    </sitemap>
';
        return $items;
    }

    /**
     * Add the comment-on-dit sitemap to the sitemap index.
     *
     * @param string $items Existing sitemap index items.
     * @return string
     */
    public function addCommentOnDitSitemapIndex($items)
    {
        $items .= '    <sitemap>   
        <loc>' . site_url() . '/sayinfrench-sitemap.xml</loc>
        <lastmod>' . date('c', time()) . '</lastmod>
    </sitemap>
';
        return $items;
    }

    /**
     * Add the factorial sitemap to the sitemap index.
     */
    public function addFactorialSitemapIndex($items)
    {
        $items .= '    <sitemap>   
        <loc>' . site_url() . '/factorial-sitemap.xml</loc>
        <lastmod>' . date('c', time()) . '</lastmod>
    </sitemap>
';
        return $items;
    }

    /**
     * Add the factors sitemap to the sitemap index.
     */
    public function addFactorsSitemapIndex($items)
    {
        $items .= '    <sitemap>   
        <loc>' . site_url() . '/factors-sitemap.xml</loc>
        <lastmod>' . date('c', time()) . '</lastmod>
    </sitemap>
';
        return $items;
    }

    /**
     * Add the GCF sitemap to the sitemap index.
     */
    public function addGcfSitemapIndex($items)
    {
        $items .= '    <sitemap>   
        <loc>' . site_url() . '/gcf-sitemap.xml</loc>
        <lastmod>' . date('c', time()) . '</lastmod>
    </sitemap>
';
        return $items;
    }

    /**
     * Add the Calculators landing pages sitemap to the sitemap index.
     */
    public function addCalculatorsSitemapIndex($items)
    {
        $items .= '    <sitemap>   
        <loc>' . site_url() . '/calculators-sitemap.xml</loc>
        <lastmod>' . date('c', time()) . '</lastmod>
    </sitemap>
';
        return $items;
    }
}
