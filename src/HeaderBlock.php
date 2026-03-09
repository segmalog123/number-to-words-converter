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
        $factorial_id = $wp_query->get('factorial_id');
        if (
            empty($number_to_convert) && !is_front_page() && $ntw_page !== 'numbers-in-french'
            && $ntw_page !== 'factorial-calculator' && empty($factorial_id)
        ) {
            return;
        }
        // Also skip for out-of-bounds factorial numbers (> 10000) or factor numbers (> 1000000) — let the theme 404 render clean
        if (!empty($factorial_id) && (int) $factorial_id > 10000) {
            return;
        }
        $factor_id = $wp_query->get('factor_id');
        if (!empty($factor_id) && ((int) $factor_id < 1 || (int) $factor_id > 1000000)) {
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

        // Suppress entirely for out-of-bounds pages — let the 404 render clean
        $factorial_id = $wp_query->get('factorial_id');
        if (!empty($factorial_id) && (int) $factorial_id > 10000) {
            return;
        }
        $factor_id = $wp_query->get('factor_id');
        if (!empty($factor_id) && ((int) $factor_id < 1 || (int) $factor_id > 1000000)) {
            return;
        }

        $number_to_convert = $wp_query->get('number_id');
        if (!isset($number_to_convert) || $number_to_convert === '') {
            $number_to_convert = '';
        }

        $current_url = home_url(add_query_arg([], $wp->request ?? ''));
        $ntw_page = $wp_query->get('ntw_page');
        $is_factorial = !empty($factorial_id);
        $is_factoring = ($ntw_page === 'factoring-calculator');
        $factor_id = $wp_query->get('factor_id');
        $is_factoring_result = !empty($factor_id);
        $convert_to = 'en';
        if (strpos($current_url, '/how-to-say-') !== false) {
            $convert_to = 'fr';
        } elseif ($is_factorial || $ntw_page === 'factorial-calculator') {
            $convert_to = 'factorial';
        } elseif ($is_factoring || $is_factoring_result) {
            $convert_to = 'factoring';
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
                            echo esc_html(ConverterHelper::convert($number_to_convert, 'h2'));
                        } elseif ($ntw_page === 'numbers-in-french') {
                            echo 'learn how to count in the French language with numbers 1-100';
                        } elseif ($ntw_page === 'factorial-calculator' || $is_factorial) {
                            if ($is_factorial) {
                                $pretitles = [
                                    'Math solver for ' . $factorial_id . ' factorial',
                                    'Calculate the exact value of ' . $factorial_id . '!',
                                    'Find the factorial of ' . $factorial_id . ' instantly',
                                    'Learn how to calculate ' . $factorial_id . ' factorial',
                                ];
                                echo esc_html($pretitles[(int) $factorial_id % 4]);
                            } else {
                                echo 'Calculate the factorial (n!) of any number instantly';
                            }
                        } elseif ($is_factoring) {
                            echo 'Calculate the factors of any number or find the GCF instantly';
                        } elseif ($is_factoring_result) {
                            $pretitles_fo = [
                                'Math solver for the factors of ' . $factor_id,
                                'Calculate the exact factors of ' . $factor_id,
                                'Find all factors and prime factors of ' . $factor_id,
                                'Learn how to factor ' . $factor_id . ' completely',
                            ];
                            echo esc_html($pretitles_fo[(int) $factor_id % 4]);
                        } else {
                            ?>
                            <?php if (!empty($number_to_convert)): ?>
                                Spelling of <?php echo esc_html($number_to_convert); ?> in words
                            <?php else: ?>
                                Spelling of numbers in words
                            <?php endif; ?>
                            <?php
                        }
                        ?>
                    </span>
                </p>
                <p class="convert-block">
                    <input min="0" step="any" <?php if ($is_factorial || $ntw_page === 'factorial-calculator'): ?>max="10000"
                            inputmode="numeric" <?php endif; ?> class="convert-input" type="text" name="tolettre" required="" title="<?php
                               if ($is_factorial || $ntw_page === 'factorial-calculator')
                                   echo 'Enter a positive integer (e.g., 5)';
                               elseif ($is_factoring || $is_factoring_result)
                                   echo 'Enter one number (e.g., 24) or two numbers (e.g., 12, 16)';
                               else
                                   echo 'Enter the number to convert here';
                               ?>"
                        value="<?php echo esc_attr($number_to_convert ?: ($is_factorial ? (string) $factorial_id : ($is_factoring_result ? (string) $factor_id : ''))); ?>"
                        placeholder="<?php
                        if ($is_factorial || $ntw_page === 'factorial-calculator')
                            echo 'Enter a positive integer (e.g., 5)';
                        elseif ($is_factoring || $is_factoring_result)
                            echo 'Enter one number (e.g., 24) or two numbers (e.g., 12, 16)';
                        else
                            echo 'Enter the number to convert here';
                        ?>" autocomplete="off">
                    <button class="convert-button" data-convert="<?php echo esc_attr($convert_to); ?>" type="button"
                        name="submitted"><i class="fa fa-refresh"></i>
                        <?php
                        if ($is_factorial || $ntw_page === 'factorial-calculator')
                            echo 'CALCULATE';
                        elseif ($is_factoring || $is_factoring_result)
                            echo 'FACTOR';
                        else
                            echo 'CONVERT';
                        ?></button>
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

        // Suppress for out-of-bounds pages
        $factorial_id_check = $wp_query->get('factorial_id');
        if (!empty($factorial_id_check) && (int) $factorial_id_check > 10000) {
            return;
        }
        $factor_id_check = $wp_query->get('factor_id');
        if (!empty($factor_id_check) && ((int) $factor_id_check < 1 || (int) $factor_id_check > 1000000)) {
            return;
        }

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
                    $ntw_page_after = $wp_query->get('ntw_page');
                    $factorial_id_after = $wp_query->get('factorial_id');
                    if (!empty($factorial_id_after)) {
                        $h1s = [
                            'What is ' . $factorial_id_after . ' Factorial? (' . $factorial_id_after . '!)',
                            'The Exact Value of ' . $factorial_id_after . ' Factorial',
                            'How to Calculate the Factorial of ' . $factorial_id_after,
                            $factorial_id_after . ' Factorial: Formula and Step-by-Step Result',
                        ];
                        echo '<h1>' . esc_html($h1s[(int) $factorial_id_after % 4]) . '</h1>';
                    } elseif (isset($number_to_convert) && $number_to_convert !== '') {
                        ?>
                        <h1><?php echo esc_html(ConverterHelper::convert($number_to_convert, 'h1')); ?></h1>
                        <?php
                    } elseif ($ntw_page_after === 'numbers-in-french') {
                        ?>
                        <h1>Convert English Numbers (1-100 and Beyond) to French Words Easily</h1>
                        <?php
                    } elseif ($ntw_page_after === 'factorial-calculator') {
                        ?>
                        <h1>N Factorial Calculator: Calculate n! &amp; Learn the Formula</h1>
                        <?php
                    } elseif ($ntw_page_after === 'factoring-calculator') {
                        ?>
                        <h1>Factoring Calculator: Factor Completely with Steps</h1>
                        <?php
                    } elseif (!empty($wp_query->get('factor_id'))) {
                        $fid = $wp_query->get('factor_id');
                        $h1s_fo = [
                            'What are the Factors of ' . $fid . '?',
                            'The Exact Factors of ' . $fid,
                            'How to Find the Factors of ' . $fid,
                            'Factors of ' . $fid . ': Prime Factorization &amp; Pairs',
                        ];
                        echo '<h1>' . esc_html($h1s_fo[(int) $fid % 4]) . '</h1>';
                    } elseif (is_home() || is_front_page()) {
                        ?>
                        <p style="font-size: 1.6em; font-weight: 700; line-height: 1.3; margin: 0; color: inherit;">How to write
                            numbers in words perfectly without mistakes ?</p>
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
