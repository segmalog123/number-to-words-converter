<?php
/**
 * Template: French Number Conversion Results
 * Plugin: Convertisseur Chiffre en Lettre
 * Modern standalone design — independent of theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

use NumberToWordsConverter\Converters\ConverterHelper;

get_header();
?>
<style>
    /* Force full width and hide sidebar for this specific template */
    #sidebar {
        display: none !important;
    }

    .content {
        width: 100% !important;
    }
</style>
<?php

global $wp_query;
$number_to_convert = $wp_query->get('number_id');
if (!isset($number_to_convert) || $number_to_convert === '') {
    $number_to_convert = '';
}

// Pre-compute all conversions once
$result_plain = ucfirst(funcConvert($number_to_convert, 'convert'));
$result_usd = ucfirst(funcConvert($number_to_convert, 'convert', 'USD'));
$result_gbp = ucfirst(funcConvert($number_to_convert, 'convert', 'GBP'));
$result_cad = ucfirst(funcConvert($number_to_convert, 'convert', 'CAD'));
$result_eur = ucfirst(funcConvert($number_to_convert, 'convert', 'EUR'));

$similar = funcListNumber($number_to_convert);
$range_label = from_zero_to($number_to_convert);
$url_en = esc_url(site_url('/how-to-say-' . str_replace(',', '.', $number_to_convert) . '-in-french/'));

$is_vip = \NumberToWordsConverter\NumberVipList::isVip($number_to_convert);

// Phase 3 & 5: Content Enrichment & Gating
$cheque_data = \NumberToWordsConverter\ContentGenerator::getChequeData($number_to_convert, $result_usd, 'USD');

if ($is_vip) {
    $math_facts = \NumberToWordsConverter\ContentGenerator::getMathFacts($number_to_convert);
    $grammar_rules = \NumberToWordsConverter\ContentGenerator::getGrammarRules($number_to_convert);
    $trivia = \NumberToWordsConverter\ContentGenerator::getContextualTrivia($number_to_convert);
    $dynamic_spelling = \NumberToWordsConverter\ContentGenerator::getDynamicSpellingText($number_to_convert, 'fr');
}
?>

<div class="content">
    <?php chiffre_breadcrumbs(); ?>

    <div style="margin:0 auto; padding:0 10px 30px;">

        <!-- ═══ HERO: Main Result ═══════════════════════════════════ -->
        <div class="cel-result-hero">
            <div class="cel-result-wrapper"
                style="display:flex; align-items:center; justify-content:center; gap:15px; flex-wrap:wrap;">
                <p class="cel-main-result" id="celMainResultText" style="margin:0;">
                    <?php echo esc_html($result_plain); ?>
                </p>
                <button class="cel-copy-btn" id="celCopyBtn"
                    data-clipboard-text="<?php echo esc_attr($result_plain); ?>">
                    ⧉ Copier
                </button>
            </div>
        </div>

        <!-- ═══ CURRENCY CARDS ══════════════════════════════════════ -->
        <div class="cel-cards-grid">
            <div class="cel-card" style="position:relative;">
                <h4 class="cel-card-label" style="margin: 0 0 6px;">🇺🇸 US Dollar ($)</h4>
                <p class="cel-card-value"><?php echo esc_html($result_usd); ?></p>
                <button class="cel-currency-copy-btn" data-clipboard-text="<?php echo esc_attr($result_usd); ?>"
                    title="Copy this amount">⧉</button>
            </div>
            <div class="cel-card" style="position:relative;">
                <h4 class="cel-card-label" style="margin: 0 0 6px;">🇬🇧 British Pound (£)</h4>
                <p class="cel-card-value"><?php echo esc_html($result_gbp); ?></p>
                <button class="cel-currency-copy-btn" data-clipboard-text="<?php echo esc_attr($result_gbp); ?>"
                    title="Copy this amount">⧉</button>
            </div>
            <div class="cel-card" style="position:relative;">
                <h4 class="cel-card-label" style="margin: 0 0 6px;">🍁 Canadian Dollar (CAD)</h4>
                <p class="cel-card-value"><?php echo esc_html($result_cad); ?></p>
                <button class="cel-currency-copy-btn" data-clipboard-text="<?php echo esc_attr($result_cad); ?>"
                    title="Copy this amount">⧉</button>
            </div>
            <div class="cel-card" style="position:relative;">
                <h4 class="cel-card-label" style="margin: 0 0 6px;">💶 Euro (€)</h4>
                <p class="cel-card-value"><?php echo esc_html($result_eur); ?></p>
                <button class="cel-currency-copy-btn" data-clipboard-text="<?php echo esc_attr($result_eur); ?>"
                    title="Copy this amount">⧉</button>
            </div>
            <div class="cel-card"
                style="grid-column: 1 / -1; display:flex; align-items:center; justify-content:center; background:#e8f8f0; border-color:#b6e8c8;">
                <h4 class="cel-card-label" style="margin: 0 10px 0 0;">🇬🇧 En anglais</h4>
                <p class="cel-card-value" style="margin:0;">
                    <a href="<?php echo $url_en; ?>" style="color:#1a7a40; font-weight:700;">
                        See French translation for number <?php echo esc_html($number_to_convert); ?> →
                    </a>
                </p>
            </div>
        </div>

        <!-- ═══ SEO REPEATING BLOCK ═════════════════════════════════ -->
        <div class="cel-seo-repeater"
            style="font-size: 13px; color: #666; text-align: center; margin-bottom: 25px; line-height: 1.6;">
            Convert <strong><?php echo esc_html($number_to_convert); ?> usd in words</strong> •
            Write <strong><?php echo esc_html($number_to_convert); ?> dollars in words</strong> •
            <strong><?php echo esc_html($number_to_convert); ?> pounds in words</strong> •
            <br />number in words dollars • USD, EUR, GBP, CAD, AUD.
        </div>

        <!-- ═══ CHEQUE VISUAL ════════════════════════════════════════ -->
        <div class="cel-cheque">
            <div class="cel-cheque-top">
                <div class="cel-cheque-date">
                    <span class="cel-cheque-label">LE</span>
                    <?php echo esc_html($cheque_data['date']); ?>
                </div>
            </div>

            <div class="cel-cheque-row">
                <span class="cel-cheque-label">PAYEZ CONTRE CE CHÈQUE</span>
                <div class="cel-cheque-amount-box">
                    # <?php echo esc_html($cheque_data['amount_num']); ?> #
                </div>
            </div>

            <div class="cel-cheque-row">
                <div class="cel-cheque-words">
                    <?php echo esc_html($cheque_data['amount_txt']); ?>
                </div>
            </div>

            <div class="cel-cheque-row">
                <span class="cel-cheque-label">PAY TO THE ORDER OF</span>
                <div class="cel-cheque-payee">
                    <?php echo esc_html($cheque_data['payee']); ?>
                </div>
            </div>

            <div class="cel-cheque-bottom">
                <div class="cel-cheque-signature">
                    Signature
                </div>
            </div>
        </div>

        <?php
        $ad_left = get_option('ntw_ad_fr_left', '');
        $ad_center = get_option('ntw_ad_fr_center', '');
        $ad_right = get_option('ntw_ad_fr_right', '');

        if (!empty(trim($ad_left)) || !empty(trim($ad_center)) || !empty(trim($ad_right))):
            ?>
            <!-- ═══ AD CODE INJECTION (3 COLUMNS) ════════════════════════════════════════ -->
            <div
                style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin: 30px 0; min-height: 90px;">
                <?php if (!empty(trim($ad_left))): ?>
                    <div style="flex: 1; min-width: 250px; text-align: center;">
                        <?php echo $ad_left; // XSS ignored intentionally to allow raw JS/HTML from admin ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty(trim($ad_center))): ?>
                    <div style="flex: 1; min-width: 250px; text-align: center;">
                        <?php echo $ad_center; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty(trim($ad_right))): ?>
                    <div style="flex: 1; min-width: 250px; text-align: center;">
                        <?php echo $ad_right; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($is_vip): // 🛑 SEO CONTENT GATING FOR VIP ONLY ?>

            <!-- ═══ MATH & GRAMMAR ═══════════════════════════════════════ -->
            <div class="cel-section">
                <h2 class="cel-section-title">Propriétés Mathematiques de <?php echo esc_html($number_to_convert); ?></h2>
                <ul style="margin:0; padding-left:20px; color:#444;">
                    <?php foreach ($math_facts as $fact): ?>
                        <li style="margin-bottom:6px;"><?php echo esc_html($fact); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if (!empty($grammar_rules)): ?>
                <div class="cel-section">
                    <h2 class="cel-section-title">Why is <?php echo esc_html($number_to_convert); ?> spelled like this in words?
                    </h2>
                    <ul style="margin:0; padding-left:20px; color:#444;">
                        <?php foreach ($grammar_rules as $rule): ?>
                            <li style="margin-bottom:6px;"><?php echo esc_html($rule); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- ═══ SPELLING RULES (Dynamic) ═════════════════════════════ -->
            <div class="cel-section">
                <h2 class="cel-section-title">Spelling rules for the number
                    <?php echo esc_html($number_to_convert); ?>
                </h2>
                <?php foreach ($dynamic_spelling as $text): ?>
                    <p><?php echo esc_html($text); ?></p>
                <?php endforeach; ?>
                <p>
                    In summary, the number <strong><?php echo esc_html($number_to_convert); ?></strong>
                    is spelled <strong><?php echo esc_html($result_plain); ?></strong> in words.
                </p>
            </div>

            <!-- ═══ SIMILAR NUMBERS ══════════════════════════════════════ -->
            <div class="cel-section">
                <h2 class="cel-section-title">Similar numbers to <?php echo esc_html($number_to_convert); ?> to convert
                </h2>
                <ul class="cel-pills">
                    <?php
                    $anchor_texts = [
                        'Convert %s usd in words',
                        'Amount %s in words',
                        '%s dollars in words',
                        'Write %s in english',
                        'Spelling of number %s',
                        'Translation for number %s',
                        'number %s in english dollars',
                        'How to write amount %s'
                    ];
                    foreach (array_slice($similar, 0, 8) as $index => $n):
                        $anchor_phrase = sprintf($anchor_texts[$index % count($anchor_texts)], $n);
                        ?>
                        <li>
                            <a href="<?php echo esc_url(site_url('/how-do-you-spell-' . $n . '-in-words/')); ?>">
                                <?php echo esc_html($anchor_phrase); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if ($trivia): ?>
                <div class="cel-section">
                    <h2 class="cel-section-title">Context & Trivia</h2>
                    <p style="margin:0; color:#444;"><?php echo esc_html($trivia); ?></p>
                </div>
            <?php endif; ?>

            <!-- ═══ NUMBER INFO ══════════════════════════════════════════ -->
            <div class="cel-section">
                <h2 class="cel-section-title">Information about the number <?php echo esc_html($number_to_convert); ?></h2>
                <p>
                    <?php echo esc_html($number_to_convert); ?> is the number that follows
                    <a
                        href="<?php echo esc_url(site_url('/how-do-you-spell-' . $similar[0] . '-in-words/')); ?>"><?php echo esc_html($similar[0]); ?></a>
                    and precedes
                    <a
                        href="<?php echo esc_url(site_url('/how-do-you-spell-' . $similar[1] . '-in-words/')); ?>"><?php echo esc_html($similar[1]); ?></a>.
                </p>
                <?php if ($range_label !== ''): ?>
                    <p>The number <?php echo esc_html($number_to_convert); ?> is part of
                        <?php echo esc_html($range_label); ?>.
                    </p>
                <?php endif; ?>
                <a class="cel-crosslink" href="<?php echo $url_en; ?>">
                    🇫🇷 See <?php echo esc_html($number_to_convert); ?> in French
                </a>
            </div>

        <?php endif; // End VIP Gating ?>

    </div><!-- /max-width wrapper -->
</div><!-- .content -->

<?php get_footer(); ?>