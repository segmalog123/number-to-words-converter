<?php
namespace NumberToWordsConverter;

if (!defined('ABSPATH')) {
    exit;
}

use NumberToWordsConverter\Converters\ConverterHelper;

/**
 * Renders the converter input UI blocks.
 * Fully self-contained — works with any WordPress theme.
 *
 * The UI is injected via:
 *  1. The `before_custom_header_block` / `after_custom_header_block` actions
 *     (for backward compat with the Sahifa child theme).
 *  2. The `wp_body_open` action (for any other theme).
 *  3. The [ntw_converter_form] and [ntw_converter_heading] shortcodes.
 */
class HeaderBlock
{

    /**
     * Hook into WordPress.
     */
    public function init()
    {
        // Backward compat: Sahifa child theme hooks
        add_action('before_custom_header_block', [$this, 'renderBeforeBlock']);
        add_action('after_custom_header_block', [$this, 'renderAfterBlock']);

        // Universal fallback: inject via wp_body_open for any theme
        add_action('wp_body_open', [$this, 'maybeRenderBlocks'], 20);

        // Shortcodes for complete theme independence
        add_shortcode('ntw_converter_form', [$this, 'shortcodeForm']);
        add_shortcode('ntw_converter_heading', [$this, 'shortcodeHeading']);
    }

    /**
     * Render the before+after blocks via wp_body_open ONLY if the theme
     * does NOT fire the custom header block actions (i.e., non-Sahifa themes).
     */
    public function maybeRenderBlocks()
    {
        // If the Sahifa-specific actions exist with attached callbacks, skip.
        if (has_action('before_custom_header_block') > 1) {
            return;
        }
        // Only render on conversion pages, front page, or English landing page
        global $wp_query;
        $number_to_convert = $wp_query->get('number_id');
        $ntw_page = $wp_query->get('ntw_page');
        if (empty($number_to_convert) && !is_front_page() && $ntw_page !== 'convertisseur-anglais') {
            return;
        }
        $this->renderBeforeBlock();
        $this->renderAfterBlock();
    }

    /**
     * Render the converter input form (before block).
     */
    public function renderBeforeBlock()
    {
        global $wp_query, $wp;

        $number_to_convert = $wp_query->get('number_id');
        if (!isset($number_to_convert) || $number_to_convert === '') {
            $number_to_convert = '';
        }

        $current_url = home_url(add_query_arg([], $wp->request ?? ''));
        $convert_to = 'en'; // Tool 1 (en) is default.
        if (strpos($current_url, '/how-to-say-') !== false) {
            $convert_to = 'fr'; // Tool 2 (fr) context.
        }
        ?>
        <div class="container cat-box-content before_html_custom_header_block">
            <div class="e3lan e3lan-below_header" style="line-height: initial;">
                <?php
                if (is_front_page()) {
                    echo ' <h1 class="block_h1_front" style="padding-top: 10px;">Numbers to Words Converter</h1> ';
                }
                ?>
                <p style="padding: 18px 25px 0px 25px;">
                    <span style="font-size: 16px; line-height: 2em;">
                        <?php
                        if (strpos($current_url, '/how-to-say-') !== false) {
                            echo ConverterHelper::convert('', 'h2');
                        } else {
                            ?>
                            Write Numbers in Words and
                            <a href="<?php echo esc_url(site_url('/category/macro-excel/')); ?>">
                                <span style="color:#32A0E3;text-decoration: underline;">Download Excel Macros for Free</span>
                            </a>
                            Here
                            <?php
                        }
                        ?>
                    </span>
                </p>
                <p class="convert-block">
                    <input min="0" step="any" class="convert-input" type="text" name="tolettre" required=""
                        title="Enter the number to convert here" value="<?php echo esc_attr($number_to_convert); ?>"
                        placeholder="Enter the number to convert here" autocomplete="off">
                    <button class="convert-button" data-convert="<?php echo esc_attr($convert_to); ?>" type="button"
                        name="submitted"><i class="fa fa-refresh"></i> CONVERT</button>
                </p>
                <p style="text-align: center;text-align: center;color: red;padding: 5px;">
                    <span class="error-input"></span>
                </p>
            </div>
        </div>
        <?php
    }

    /**
     * Render the conversion result heading (after block).
     */
    public function renderAfterBlock()
    {
        global $wp_query;

        $number_to_convert = $wp_query->get('number_id');
        if (!isset($number_to_convert) || $number_to_convert === '') {
            $number_to_convert = '';
        }
        ?>
        <div class="container cat-box-content after_html_custom_header_block" style="position: relative">
            <div class="convert-block">
                <div class="e3lan e3lan-below_header" style="font-size: 12px; line-height: 2em;padding: 0px 5px">
                    <?php
                    $number_to_convert = $wp_query->get('number_id');
                    if (isset($number_to_convert) && $number_to_convert !== '') {
                        ?>
                        <h1><?php echo esc_html(ConverterHelper::convert($number_to_convert, 'h1')); ?></h1>
                        <?php
                    } elseif (is_home() || is_front_page()) {
                        ?>
                        <h1>Numbers to Words Converter - Spell Numbers Easily</h1>
                        <?php
                    } else {
                        ?>
                        <h2>Numbers to Words Converter - Spell Numbers Easily</h2>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Shortcode: [ntw_converter_form]
     * Allows placing the converter form anywhere in any theme.
     */
    public function shortcodeForm($atts)
    {
        ob_start();
        $this->renderBeforeBlock();
        return ob_get_clean();
    }

    /**
     * Shortcode: [ntw_converter_heading]
     * Allows placing the conversion heading anywhere in any theme.
     */
    public function shortcodeHeading($atts)
    {
        ob_start();
        $this->renderAfterBlock();
        return ob_get_clean();
    }
}
