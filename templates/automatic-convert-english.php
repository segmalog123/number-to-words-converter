<?php
/**
 * Template: English Number Conversion Results
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
$number_to_convert_php = (float) str_replace(['.', ','], ['', '.'], $number_to_convert);

// Pre-compute all conversions once
$result_usd = ucfirst(funcConvert($number_to_convert, 'convert', 'USD'));
$result_gbp = ucfirst(funcConvert($number_to_convert, 'convert', 'GBP'));
$result_cad = ucfirst(funcConvert($number_to_convert, 'convert', 'CAD'));
$result_eur = ucfirst(funcConvert($number_to_convert, 'convert', 'EUR'));
$result_fr_plain = ucfirst(enChiffre($number_to_convert)['final_number_lettre']);
$similar = funcListNumber($number_to_convert);
$percent_number = funcPercent($number_to_convert);
$similar_8_fr = ucfirst(enChiffre($similar[8])['final_number_lettre']);
$url_en = esc_url(site_url('/how-do-you-spell-' . str_replace('.', ',', $number_to_convert) . '-in-words/'));

$is_vip = \NumberToWordsConverter\NumberVipList::isVip($number_to_convert);

// Phase 3 & 5: Content Enrichment & Gating
$cheque_data = \NumberToWordsConverter\ContentGenerator::getChequeData($number_to_convert, $result_fr_plain, 'Euros', 'fr');

if ($is_vip) {
    $math_facts = \NumberToWordsConverter\ContentGenerator::getMathFacts($number_to_convert);
    $grammar_rules = \NumberToWordsConverter\ContentGenerator::getGrammarRules($number_to_convert, 'fr');
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
                    <?php echo esc_html($result_fr_plain); ?>
                </p>
                <button class="cel-copy-btn" id="celCopyBtn"
                    data-clipboard-text="<?php echo esc_attr($result_fr_plain); ?>">
                    ⧉ Copy
                </button>
            </div>
        </div>

        <!-- ═══ CURRENCY CARDS ══════════════════════════════════════ -->
        <div class="cel-cards-grid">
            <div class="cel-card">
                <p class="cel-card-label">🇺🇸 US Dollar ($)</p>
                <p class="cel-card-value"><?php echo esc_html($result_usd); ?></p>
            </div>
            <div class="cel-card">
                <p class="cel-card-label">🇬🇧 British Pound (£)</p>
                <p class="cel-card-value"><?php echo esc_html($result_gbp); ?></p>
            </div>
            <div class="cel-card">
                <p class="cel-card-label">🍁 Canadian Dollar (CAD)</p>
                <p class="cel-card-value"><?php echo esc_html($result_cad); ?></p>
            </div>
            <div class="cel-card">
                <p class="cel-card-label">💶 Euro (€)</p>
                <p class="cel-card-value"><?php echo esc_html($result_eur); ?></p>
            </div>
            <div class="cel-card"
                style="grid-column: 1 / -1; display:flex; align-items:center; justify-content:center; background:#e8f8f0; border-color:#b6e8c8;">
                <h4 class="cel-card-label" style="margin: 0 10px 0 0;">🇬🇧 In English</h4>
                <p class="cel-card-value" style="margin:0;">
                    <a href="<?php echo $url_en; ?>" style="color:#1a7a40; font-weight:700;">
                        See English spelling for number <?php echo esc_html($number_to_convert); ?> →
                    </a>
                </p>
            </div>
        </div>

        <!-- ═══ CHEQUE VISUAL (English Layout) ═══════════════════════ -->
        <div class="cel-cheque">
            <div class="cel-cheque-top">
                <div class="cel-cheque-date">
                    <span class="cel-cheque-label">DATE</span>
                    <?php echo esc_html($cheque_data['date']); ?>
                </div>
            </div>

            <div class="cel-cheque-row">
                <span class="cel-cheque-label">AMOUNT</span>
                <div class="cel-cheque-amount-box">
                    $ <?php echo esc_html($cheque_data['amount_num']); ?>
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
        $ad_left = get_option('ntw_ad_en_left', '');
        $ad_center = get_option('ntw_ad_en_center', '');
        $ad_right = get_option('ntw_ad_en_right', '');

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

            <!-- ═══ ENGLISH SEO CONTEXT ═══════════════════════════════════════ -->
            <div class="cel-section">
                <h2 class="cel-section-title">Contexts and usages: <?php echo esc_html($number_to_convert); ?> in French
                </h2>
                <ul style="margin:0; padding-left:20px; color:#444; line-height: 1.6;">
                    <?php
                    $english_seo_facts = \NumberToWordsConverter\ContentGenerator::getEnglishSeoFacts($number_to_convert, $result_fr_plain);
                    foreach ($english_seo_facts as $fact): ?>
                        <li style="margin-bottom:10px;"><?php echo $fact; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- ═══ SPELLING RULES (Dynamic) ═════════════════════════════ -->
            <div class="cel-section">
                <h2 class="cel-section-title">Spelling rules in French — <?php echo esc_html($number_to_convert); ?>
                </h2>
                <?php foreach ($dynamic_spelling as $text): ?>
                    <p><?php echo esc_html($text); ?></p>
                <?php endforeach; ?>
                <p>
                    In summary, the number <strong><?php echo esc_html($number_to_convert); ?></strong>
                    is translated to <strong><?php echo esc_html($result_fr_plain); ?></strong> in French.
                </p>
            </div>

            <!-- ═══ SIMILAR NUMBERS ══════════════════════════════════════ -->
            <div class="cel-section">
                <h2 class="cel-section-title">Similar numbers to <?php echo esc_html($number_to_convert); ?> in French
                </h2>
                <ul class="cel-pills">
                    <?php
                    $smart_similar = \NumberToWordsConverter\NumberVipList::getSmartRelated((int) $number_to_convert_php, 8);
                    $anchor_texts = [
                        '%s in french',
                        'How do you say %s in french',
                        '%s french',
                        'Translate %s in french',
                        'How to say %s in french',
                        '%s in french letters',
                        'How to write %s in french letters',
                        'Translation of %s in french'
                    ];

                    foreach ($smart_similar as $index => $n):
                        $anchor_phrase = sprintf($anchor_texts[$index % count($anchor_texts)], $n);
                        ?>
                        <li>
                            <a href="<?php echo esc_url(site_url('/how-to-say-' . $n . '-in-french/')); ?>">
                                <?php echo esc_html($anchor_phrase); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

        <?php endif; // End VIP Gating ?>

    </div><!-- /max-width wrapper -->
</div><!-- .content -->

<?php get_footer(); ?>