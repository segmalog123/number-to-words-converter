<?php
/**
 * Template: /what-is-X-factorial/
 * Dynamic factorial result page — full computation + rich content.
 */

if (!defined('ABSPATH'))
    exit;

/* ─── 1. Get & sanitise the number ──────────────────────────────────────── */
global $wp_query;
$x = intval($wp_query->get('factorial_id'));
if ($x < 0)
    $x = 0;

/* ─── 2. Computation helpers ─────────────────────────────────────────────── */

/**
 * Exact factorial using BCMath (returns string of digits).
 * Capped at 5000 — beyond that we only show scientific notation.
 */
function ntw_factorial_exact(int $n): string
{
    if ($n <= 1)
        return '1';
    if (function_exists('bcmul')) {
        $r = '1';
        for ($i = 2; $i <= $n; $i++) {
            $r = bcmul($r, (string) $i);
        }
        return $r;
    }
    // Fallback: PHP native int (valid up to ~20)
    if ($n <= 20) {
        $r = 1;
        for ($i = 2; $i <= $n; $i++)
            $r *= $i;
        return (string) $r;
    }
    return '';
}

/** Trailing zeros using Legendre's formula */
function ntw_trailing_zeros(int $n): int
{
    $z = 0;
    $p = 5;
    while ($p <= $n) {
        $z += intdiv($n, $p);
        $p *= 5;
    }
    return $z;
}

/** Format a big decimal string with commas (for smaller results) */
function ntw_format_number(string $s): string
{
    return number_format((float) $s);
}

/** Scientific notation from a digit string */
function ntw_sci_notation(string $digits, int $mantissa_digits = 6): string
{
    $len = strlen($digits);
    if ($len === 0)
        return '';
    $exp = $len - 1;
    $m = $digits[0] . '.' . substr($digits, 1, $mantissa_digits);
    $m = rtrim(rtrim($m, '0'), '.');
    return $m . ' &times; 10<sup>' . $exp . '</sup>';
}

/** Stirling's approximation → scientific notation */
function ntw_stirling_sci(int $n): string
{
    if ($n <= 0)
        return '1';
    $log10 = 0.5 * log10(2 * M_PI * $n) + $n * log10($n / M_E);
    $exp = (int) floor($log10);
    $mant = pow(10, $log10 - $exp);
    return number_format($mant, 4) . ' &times; 10<sup>' . $exp . '</sup>';
}

/* ─── 3. Hard cap + tiered computation ──────────────────────────────────────── */

// Numbers > 10,000 are out of bounds — serve the theme's native 404 page
if ($x > 10000) {
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    nocache_headers();
    include(get_query_template('404'));
    exit;
}

$EXACT_CAP = ($x <= 1000);   // Show exact integer for n ≤ 1000
$SCIENTIFIC_ONLY = !$EXACT_CAP;    // Stirling approximation only for 1001–10000

$exact_str = $EXACT_CAP ? ntw_factorial_exact($x) : '';
$digit_count = $EXACT_CAP ? strlen($exact_str) : null;
$trailing_zeros = ntw_trailing_zeros($x);
$sci = $EXACT_CAP
    ? (strlen($exact_str) > 1 ? ntw_sci_notation($exact_str) : '')
    : ntw_stirling_sci($x);

// In running text (permutations, FAQ): sci notation for X > 20 to avoid spamming huge digit strings
$display_in_text = ($x <= 20 && $exact_str !== '') ? $exact_str : ntw_stirling_sci($x);

/* Build the equation line — use sci notation result for X > 20 to prevent horizontal overflow */
if ($x == 0 || $x == 1) {
    $equation_line = "{$x}! = 1";
} elseif ($x <= 10) {
    $parts = [];
    for ($i = $x; $i >= 1; $i--)
        $parts[] = $i;
    $eq_result = ($x <= 20 && $exact_str) ? $exact_str : ntw_stirling_sci($x);
    $equation_line = "{$x}! = " . implode(' &times; ', $parts) . " = " . $eq_result;
} else {
    $eq_result = ($x <= 20 && $exact_str) ? $exact_str : ntw_stirling_sci($x);
    $equation_line = "{$x}! = {$x} &times; " . ($x - 1) . " &times; &hellip; &times; 3 &times; 2 &times; 1 = " . $eq_result;
}


/* Calculator limit paragraph */
if ($x <= 12) {
    $calc_text = "The result of {$x}! is small enough to fit within a standard 32-bit integer limit. This means almost any basic computer program, spreadsheet, or calculator can compute the factorial of {$x} without encountering overflow errors.";
} elseif ($x <= 20) {
    $calc_text = "The value of {$x} factorial exceeds standard 32-bit limits but fits perfectly within a 64-bit integer. Standard programming languages like Python, C++, or Java can handle this natively using 'long' data types without losing precision.";

} elseif ($x <= 69) {
    $calc_text = "Warning: {$x}! produces a massive number. Most standard handheld calculators cannot display this many digits and will automatically switch to scientific notation to show the result. Furthermore, {$x} is approaching the absolute limit of what standard scientific calculators can process.";
} else {
    $calc_text = "The factorial of {$x} is so astronomically large that it causes an 'Overflow Error' on standard calculators (which typically max out at 69!). Calculating {$x}! requires specialized mathematical software, BigInt programming libraries, or advanced server-side computation engines.";
}

/* Easter egg text */
$easter_text = '';
if ($x == 3) {
    $easter_text = "For example, if you have 3 runners in a race, there are exactly <strong>6 possible ways</strong> they can finish on the podium for gold, silver, and bronze.";
} elseif ($x == 52) {
    $easter_text = "For example, 52! represents the number of ways you can shuffle a standard deck of playing cards. This number is so astronomically large that every time you properly shuffle a deck, you are likely creating a sequence that has never existed before in human history.";
}

/* Nearby factorial links */
$nearby = [];
$anchors = [
    'What is %d factorial',
    'Value of %d factorial',
    'Factorial of %d',
    '%d factorial',
];

if ($x <= 200) {
    // Core Group: increment by 1
    $link_idx = 0;
    for ($i = $x - 4; $i <= $x - 1; $i++) {
        if ($i >= 0) {
            $nearby[] = ['n' => $i, 'text' => sprintf($anchors[$link_idx % 4], $i)];
        }
        $link_idx++;
    }
    for ($i = $x + 1; $i <= $x + 4; $i++) {
        if ($i <= 10000) { // never link beyond our hard cap
            $nearby[] = ['n' => $i, 'text' => sprintf($anchors[$link_idx % 4], $i)];
        }
        $link_idx++;
    }
} else {
    // Milestone Group: jump by 50 to match index strategy
    $link_idx = 0;

    // Nearest multiple of 50 strictly below x
    // E.g., if x=500, closest_below=450. If x=525, closest_below=500.
    $closest_below = floor(($x - 1) / 50) * 50;
    for ($i = $closest_below - 150; $i <= $closest_below; $i += 50) {
        if ($i >= 0) {
            $nearby[] = ['n' => $i, 'text' => sprintf($anchors[$link_idx % 4], $i)];
        }
        $link_idx++;
    }

    // Nearest multiple of 50 strictly above x
    $closest_above = ceil(($x + 1) / 50) * 50;
    for ($i = $closest_above; $i <= $closest_above + 150; $i += 50) {
        if ($i <= 10000) {
            $nearby[] = ['n' => $i, 'text' => sprintf($anchors[$link_idx % 4], $i)];
        }
        $link_idx++;
    }
}

/* FAQ schema data */
$faq_exact_display = $exact_str ?: "a number with {$digit_count} digits";
// For FAQ text, show sci notation for X > 20 to keep text concise
$faq_result_text = ($x <= 20 && $exact_str) ? $exact_str : 'approximately ' . strip_tags(ntw_stirling_sci($x));
$faq_data = [
    [
        'q' => "How do you find the factorial of {$x}?",
        'a' => "To find the factorial of {$x}, you multiply the number {$x} by every positive integer less than itself, down to 1. The formula is {$x} &times; " . ($x - 1) . " &times; " . ($x - 2) . " &hellip; &times; 1, which equals {$faq_result_text}.",
    ],
    [
        'q' => "How many zeros at the end of {$x} factorial?",
        'a' => "There are exactly {$trailing_zeros} trailing zeros at the end of {$x} factorial.",
    ],
    [
        'q' => "What does {$x}! mean in math?",
        'a' => "In mathematics, {$x}! is the symbol for \"{$x} factorial\". It represents the product of all positive integers less than or equal to {$x}.",
    ],
];

get_header();
?>
<style>
    /* ── Layout / Colours ────────────────────────────────────── */
    #sidebar {
        display: none !important;
    }

    .content {
        width: 100% !important;
    }

    .nwf-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 20px 15px 50px;
        line-height: 1.75;
        color: #333;
    }

    /* ── Intro / boxes ───────────────────────────────────────── */
    .nwf-section-intro {
        background: #f7fdf9;
        border-left: 4px solid #2a7d4f;
        padding: 14px 18px;
        border-radius: 6px;
        margin-bottom: 28px;
        font-size: 15px;
    }

    /* ── Exact result box ────────────────────────────────────── */
    .nwf-result-box {
        background: #1a5c30;
        color: #fff;
        border-radius: 10px;
        padding: 18px 22px;
        margin: 16px 0 24px;
        font-family: monospace;
        font-size: 0.95em;
        word-break: break-all;
        overflow-wrap: anywhere;
        line-height: 1.6;
    }

    .nwf-result-label {
        font-weight: 700;
        font-size: 1.05em;
        margin-bottom: 6px;
        font-family: inherit;
    }

    /* ── Formula ─────────────────────────────────────────────── */
    .nwf-formula-wrap {
        text-align: center;
        margin: 16px 0;
        padding: 14px;
        background: #f5faf7;
        border-radius: 6px;
        overflow-x: auto;
        /* allow horizontal scroll if formula is very long */
        max-width: 100%;
    }

    .nwf-formula {
        font-size: 1.2em;
        font-style: italic;
        font-family: Georgia, serif;
        color: #1a1a1a;
        word-break: break-all;
        overflow-wrap: break-word;
        display: inline-block;
        max-width: 100%;
    }

    /* ── Headings ────────────────────────────────────────────── */
    .nwf-h2 {
        font-size: 1.3em;
        font-weight: 700;
        color: #1a5c30;
        border-bottom: 2px solid #c5e8d3;
        padding-bottom: 5px;
        margin: 34px 0 12px;
    }

    .nwf-h3 {
        font-size: 1.05em;
        font-weight: 700;
        color: #2a7d4f;
        margin: 22px 0 8px;
    }

    /* ── Info boxes ──────────────────────────────────────────── */
    .nwf-info {
        background: #eafaf1;
        border: 1px solid #a2dbb8;
        border-radius: 6px;
        padding: 12px 18px;
        margin: 14px 0;
        font-size: 15px;
    }

    .nwf-warning {
        background: #fff8e1;
        border: 1px solid #ffe082;
        border-radius: 6px;
        padding: 12px 18px;
        margin: 14px 0;
        font-size: 15px;
    }

    .nwf-easter {
        background: #f3e5f5;
        border-left: 4px solid #9c27b0;
        padding: 12px 16px;
        border-radius: 6px;
        margin: 12px 0;
        font-size: 14px;
    }

    /* ── Cross-link pills ─────────────────────────────────────── */
    .nwf-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
        list-style: none;
        padding: 0;
        margin: 12px 0 20px;
    }

    .nwf-pills li a {
        background: #2a7d4f;
        color: #fff;
        text-decoration: none;
        padding: 5px 13px;
        border-radius: 18px;
        font-size: 13px;
        transition: background .2s;
    }

    .nwf-pills li a:hover {
        background: #1a5c30;
    }

    /* ── FAQ ─────────────────────────────────────────────────── */
    .nwf-faq-item {
        border-bottom: 1px solid #ddd;
        padding: 14px 0;
    }

    .nwf-faq-item:last-child {
        border: none;
    }

    .nwf-faq-q {
        font-weight: 700;
        font-size: 1em;
        color: #1a5c30;
        margin-bottom: 6px;
    }

    .nwf-faq-a {
        font-size: 14.5px;
        color: #555;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    /* ── Table ───────────────────────────────────────────────── */
    .nwf-props-table {
        width: 100%;
        border-collapse: collapse;
        margin: 12px 0 20px;
    }

    .nwf-props-table th {
        background: #2a7d4f;
        color: #fff;
        padding: 8px 14px;
        text-align: left;
    }

    .nwf-props-table td {
        padding: 9px 14px;
        border-bottom: 1px solid #eee;
        word-break: break-all;
        overflow-wrap: break-word;
    }

    .nwf-props-table tr:nth-child(even) td {
        background: #f7fdf9;
    }
</style>

<?php
// JSON-LD FAQ Schema
$schema = ['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => []];
foreach ($faq_data as $faq_item) {
    $schema['mainEntity'][] = [
        '@type' => 'Question',
        'name' => $faq_item['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => wp_strip_all_tags($faq_item['a'])],
    ];
}
echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
?>

<div class="content">
    <?php if (function_exists('chiffre_breadcrumbs'))
        chiffre_breadcrumbs(); ?>

    <div class="nwf-wrap">

        <!-- ══ INTRO ═══════════════════════════════════════════════════ -->
        <h2 class="nwf-h2">What is the Factorial of
            <?php echo $x; ?>?
        </h2>

        <div class="nwf-section-intro">
            <p>
                If you are wondering <strong>what is
                    <?php echo $x; ?> factorial
                </strong>, you have come to the right place.
                In mathematics, the <strong>factorial of
                    <?php echo $x; ?>
                </strong> (written as <strong>
                    <?php echo $x; ?>!
                </strong>)
                is calculated by multiplying the number <strong>
                    <?php echo $x; ?>
                </strong> by every whole number less than it,
                all the way down to 1.
            </p>
        </div>

        <p>The exact value of <strong>
                <?php echo $x; ?> factorial
            </strong> is:</p>

        <div class="nwf-result-box">
            <div class="nwf-result-label">
                <?php echo $SCIENTIFIC_ONLY ? 'Approximate Value:' : esc_html("{$x}! ="); ?>
            </div>
            <?php if ($SCIENTIFIC_ONLY): ?>
                <?php echo $sci; ?> <span style="font-size:0.8em;opacity:0.8;">(Stirling&#8217;s approximation)</span>
            <?php else: ?>
                <?php echo esc_html($exact_str ?: "Number too large — see scientific notation below"); ?>
            <?php endif; ?>
        </div>

        <?php if ($x > 15 && $sci): ?>
            <p class="nwf-info">
                Because this calculation produces such a massive number, scientists and calculators often display
                <strong>
                    <?php echo $x; ?> factorial
                </strong> in scientific notation as
                <strong>
                    <?php echo $sci; ?>
                </strong>.
            </p>
        <?php endif; ?>

        <!-- ══ HOW TO CALCULATE ════════════════════════════════════════ -->
        <h2 class="nwf-h2">How to Calculate
            <?php echo $x; ?> Factorial
        </h2>

        <p>To understand how we get the <strong>value of
                <?php echo $x; ?> factorial
            </strong>, we use the standard factorial formula:</p>

        <div class="nwf-formula-wrap">
            <span class="nwf-formula"><em>n</em>! = <em>n</em> &times; (<em>n</em>&minus;1) &times; (<em>n</em>&minus;2)
                &times; &hellip; &times; 1</span>
        </div>

        <p>Applying this to our specific number:</p>

        <div class="nwf-formula-wrap">
            <span class="nwf-formula">
                <?php echo $equation_line; ?>
            </span>
        </div>

        <!-- ══ MATHEMATICAL PROPERTIES ════════════════════════════════ -->
        <h2 class="nwf-h2">Mathematical Properties of
            <?php echo $x; ?>!
        </h2>

        <h3 class="nwf-h3">Trailing Zeros and Digit Count</h3>
        <p>
            A common math test question is finding out how many zeros are at the end of a factorial.
            The factorial of <strong>
                <?php echo $x; ?>
            </strong> ends with exactly
            <strong>
                <?php echo $trailing_zeros; ?> trailing zeros
            </strong>.
            <?php if (!$SCIENTIFIC_ONLY): ?>
                When written out completely, the value of
                <?php echo $x; ?> factorial is a massive number that contains
                exactly <strong>
                    <?php echo $digit_count; ?> digits
                </strong>.
            <?php endif; ?>
        </p>

        <table class="nwf-props-table">
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Trailing zeros</td>
                    <td><strong>
                            <?php echo $trailing_zeros; ?>
                        </strong></td>
                </tr>
                <tr>
                    <td>Total digits</td>
                    <td>
                        <?php echo $SCIENTIFIC_ONLY ? 'N/A (see scientific notation)' : $digit_count; ?>
                    </td>
                </tr>
                <?php if ($sci): ?>
                    <tr>
                        <td>Scientific notation</td>
                        <td>
                            <?php echo $sci; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 class="nwf-h3">Combinatorics &amp; Permutations</h3>
        <p>
            In probability and statistics, <strong>
                <?php echo $x; ?> factorial
            </strong> represents the total number of ways
            you can arrange <strong>
                <?php echo $x; ?> distinct objects
            </strong>. If you have
            <?php echo $x; ?> items,
            there are <strong>
                <?php echo ($x <= 20 && $exact_str) ? esc_html($exact_str) : ntw_stirling_sci($x); ?>
            </strong>
            different possible combinations or permutations.
        </p>

        <?php if ($easter_text): ?>
            <div class="nwf-easter">
                <p>
                    <?php echo $easter_text; ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- ══ CALCULATOR LIMITS ═══════════════════════════════════════ -->
        <h3 class="nwf-h3">Calculator Limits for
            <?php echo $x; ?> Factorial
        </h3>
        <p>
            <?php echo $calc_text; ?>
        </p>

        <?php if ($x > 10): ?>
            <!-- ══ STIRLING'S APPROXIMATION ═══════════════════════════════ -->
            <h3 class="nwf-h3">Stirling's Approximation</h3>
            <p>
                When dealing with a number as large as <strong>
                    <?php echo $x; ?>
                </strong>, mathematicians often use
                Stirling's approximation to estimate the factorial rather than calculating the exact product.
                The formula is:
            </p>
            <div class="nwf-formula-wrap">
                <span class="nwf-formula">
                    <em>n</em>! &asymp; &radic;(2&pi;<em>n</em>) &sdot; (<em>n</em>/<em>e</em>)<sup><em>n</em></sup>
                </span>
            </div>
            <p>
                If we apply Stirling's approximation to <strong>
                    <?php echo $x; ?>
                </strong>, the estimated scientific
                value is <strong>
                    <?php echo ntw_stirling_sci($x); ?>
                </strong> — incredibly close to our exact calculated answer above.
            </p>
        <?php endif; ?>

        <!-- ══ NEARBY FACTORIALS ═══════════════════════════════════════ -->
        <h2 class="nwf-h2">Calculate Nearby Factorials</h2>
        <ul class="nwf-pills">
            <?php foreach ($nearby as $nb): ?>
                <li>
                    <a href="<?php echo esc_url(home_url('/what-is-' . $nb['n'] . '-factorial/')); ?>">
                        <?php echo esc_html($nb['text']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- ══ OTHER CONVERSIONS ═══════════════════════════════════════ -->
        <h2 class="nwf-h2">Other Conversions of the Number
            <?php echo $x; ?>
        </h2>
        <ul class="nwf-pills">
            <li><a href="<?php echo esc_url(home_url('/how-do-you-spell-' . $x . '-in-words/')); ?>">
                    <?php echo $x; ?> in English
                </a></li>
            <li><a href="<?php echo esc_url(home_url('/how-to-say-' . $x . '-in-french/')); ?>">
                    <?php echo $x; ?> in French
                </a></li>
        </ul>

        <!-- ══ FAQ ═════════════════════════════════════════════════════ -->
        <h2 class="nwf-h2">Frequently Asked Questions (FAQ)</h2>
        <?php foreach ($faq_data as $faq_item): ?>
            <div class="nwf-faq-item">
                <div class="nwf-faq-q">
                    <?php echo esc_html($faq_item['q']); ?>
                </div>
                <div class="nwf-faq-a">
                    <?php echo $faq_item['a']; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div><!-- /.nwf-wrap -->
</div><!-- .content -->

<script>
    (function () {
        function doFactorialRedirect() {
            var input = document.querySelector('.convert-input');
            if (!input) return;
            var val = parseInt(input.value, 10);
            var err = document.querySelector('.error-input');
            if (isNaN(val) || val < 0) {
                if (err) err.textContent = 'Please enter a valid non-negative integer.';
                return;
            }
            if (val > 10000) {
                if (err) err.textContent = '\u26a0\ufe0f Our calculator supports numbers from 0 to 10,000.';
                return;
            }
            if (err) err.textContent = '';
            window.location.href = '<?php echo esc_js(home_url("/")); ?>what-is-' + val + '-factorial/';
        }
        window.addEventListener('load', function () {
            /* Capture phase — fires BEFORE the plugin's default click handler */
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.convert-button[data-convert="factorial"]');
                if (btn) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    doFactorialRedirect();
                }
            }, true);
            /* Also intercept Enter key in the input field */
            var inp = document.querySelector('.convert-input');
            if (inp) {
                inp.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        doFactorialRedirect();
                    }
                }, true);
            }
        });
    }());
</script>

<?php get_footer(); ?>