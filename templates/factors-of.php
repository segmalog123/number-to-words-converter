<?php
/**
 * Template: /factors-of-X/
 * Dynamic factor result page — computes factors, prime factorization,
 * factor pairs, and math trivia entirely in PHP.
 */

if (!defined('ABSPATH'))
    exit;

/* ─── 1. Get & sanitise X ─────────────────────────────────────────────────── */
global $wp_query;
$x = intval($wp_query->get('factor_id'));
// Note: out-of-range (< 1 or > 1,000,000) is handled by TemplateLoader
// which will not load this template, letting WordPress show its default 404.

/* ─── 2. Computation helpers ──────────────────────────────────────────────── */

/** Return sorted array of all positive integer factors of $n */
function ntw_get_factors(int $n): array
{
    $factors = [];
    $sqrt = (int) sqrt($n);
    for ($i = 1; $i <= $sqrt; $i++) {
        if ($n % $i === 0) {
            $factors[] = $i;
            if ($i !== $n / $i) {
                $factors[] = $n / $i;
            }
        }
    }
    sort($factors);
    return $factors;
}

/** Return [prime => exponent, ...] for the prime factorization of $n */
function ntw_prime_factorization(int $n): array
{
    $primes = [];
    $d = 2;
    while ($d * $d <= $n) {
        while ($n % $d === 0) {
            $primes[$d] = ($primes[$d] ?? 0) + 1;
            $n = intdiv($n, $d);
        }
        $d++;
    }
    if ($n > 1) {
        $primes[$n] = ($primes[$n] ?? 0) + 1;
    }
    return $primes;
}

/** Format prime factorization as HTML string, e.g. "2<sup>3</sup> &times; 3" */
function ntw_format_prime_html(array $primes): string
{
    $parts = [];
    foreach ($primes as $prime => $exp) {
        if ($exp === 1) {
            $parts[] = (string) $prime;
        } else {
            $parts[] = $prime . '<sup>' . $exp . '</sup>';
        }
    }
    return implode(' &times; ', $parts);
}

/** Return factor pairs [[a, b], ...] where a <= b and a*b = $n */
function ntw_factor_pairs(array $factors, int $n): array
{
    $pairs = [];
    foreach ($factors as $f) {
        $partner = $n / $f;
        if ($f <= $partner) {
            $pairs[] = [$f, (int) $partner];
        }
    }
    return $pairs;
}

/** Aliquot sum: sum of all proper divisors (factors except $n itself) */
function ntw_aliquot_sum(array $factors, int $n): int
{
    $sum = 0;
    foreach ($factors as $f) {
        if ($f !== $n) {
            $sum += $f;
        }
    }
    return $sum;
}

/* ─── 3. Compute all values ────────────────────────────────────────────────── */
$factors = ntw_get_factors($x);
$factor_count = count($factors);
$factor_list = implode(', ', $factors);
$pairs = ntw_factor_pairs($factors, $x);
$primes_map = ntw_prime_factorization($x);
$prime_html = ntw_format_prime_html($primes_map);
$aliquot = ntw_aliquot_sum($factors, $x);
$is_prime = ($factor_count === 2); // only 1 and itself
$sqrt_x = sqrt($x);
$is_perfect_sq = (floor($sqrt_x) == $sqrt_x);
$sqrt_int = (int) $sqrt_x;

/* ─── 4. Rotate arrays by X mod 4 ────────────────────────────────────────── */
$idx = $x % 4; // 0, 1, 2, or 3

$pre_titles = [
    'Math solver for the factors of ' . $x,
    'Calculate the exact factors of ' . $x,
    'Find all factors and prime factors of ' . $x,
    'Learn how to factor ' . $x . ' completely',
];

$h1_titles = [
    'What are the Factors of ' . $x . '?',
    'The Exact Factors of ' . $x,
    'How to Find the Factors of ' . $x,
    'Factors of ' . $x . ': Prime Factorization &amp; Pairs',
];

/* ─── 5. Division proof steps (first 4 factor pairs max) ──────────────────── */
$proof_steps = [];
$proof_count = 0;
foreach ($pairs as $pair) {
    if ($proof_count >= 4)
        break;
    $a = $pair[0];
    $b = $pair[1];
    if ($a === $b) {
        $proof_steps[] = $x . ' &divide; ' . $a . ' = ' . $b
            . ' (So, ' . $a . ' is a factor)';
    } else {
        $proof_steps[] = $x . ' &divide; ' . $a . ' = ' . $b
            . ' (So, ' . $a . ' and ' . $b . ' are factors)';
    }
    $proof_count++;
}

/* ─── 6. Nearby links (X-4…X-1, X+1…X+4 capped ≥ 1) ──────────────────── */
$nearby_labels_before = [
    'What are the factors of ',
    'Prime factorization of ',
    'Factors of ',
    'Factor [N] completely',
];
$nearby_labels_after = [
    'What are the factors of ',
    'Prime factorization of ',
    'Factors of ',
    'Factor [N] completely',
];
$nearby_links = [];

if (
    $x <= 1000 ||
    ($x >= 1900 && $x <= 2100)
) {
    // Core increment by 1
    for ($delta = 4; $delta >= 1; $delta--) {
        $n = $x - $delta;
        if ($n >= 1) {
            $label_tpl = $nearby_labels_before[$delta - 1]; // using existing backwards mapping 3, 2, 1, 0
            $label = (strpos($label_tpl, '[N]') !== false)
                ? str_replace('[N]', $n, $label_tpl)
                : $label_tpl . $n;
            $nearby_links[] = ['n' => $n, 'label' => $label];
        }
    }
    for ($delta = 1; $delta <= 4; $delta++) {
        $n = $x + $delta;
        if ($n <= 1000000) {
            $label_tpl = $nearby_labels_after[$delta - 1];
            $label = (strpos($label_tpl, '[N]') !== false)
                ? str_replace('[N]', $n, $label_tpl)
                : $label_tpl . $n;
            $nearby_links[] = ['n' => $n, 'label' => $label];
        }
    }
} elseif ($x <= 10000) {
    // Increment by 100
    $closest_below = floor(($x - 1) / 100) * 100;
    $closest_above = ceil(($x + 1) / 100) * 100;

    $idx = 0;
    for ($n = $closest_below - 300; $n <= $closest_below; $n += 100) {
        if ($n >= 1) {
            $label_tpl = $nearby_labels_before[$idx % 4];
            $label = (strpos($label_tpl, '[N]') !== false)
                ? str_replace('[N]', $n, $label_tpl)
                : $label_tpl . $n;
            $nearby_links[] = ['n' => $n, 'label' => $label];
        }
        $idx++;
    }

    $idx = 0;
    for ($n = $closest_above; $n <= $closest_above + 300; $n += 100) {
        if ($n <= 1000000) {
            $label_tpl = $nearby_labels_after[$idx % 4];
            $label = (strpos($label_tpl, '[N]') !== false)
                ? str_replace('[N]', $n, $label_tpl)
                : $label_tpl . $n;
            $nearby_links[] = ['n' => $n, 'label' => $label];
        }
        $idx++;
    }
} else {
    // Increment by 500
    $closest_below = floor(($x - 1) / 500) * 500;
    $closest_above = ceil(($x + 1) / 500) * 500;

    $idx = 0;
    for ($n = $closest_below - 1500; $n <= $closest_below; $n += 500) {
        if ($n >= 1) {
            $label_tpl = $nearby_labels_before[$idx % 4];
            $label = (strpos($label_tpl, '[N]') !== false)
                ? str_replace('[N]', $n, $label_tpl)
                : $label_tpl . $n;
            $nearby_links[] = ['n' => $n, 'label' => $label];
        }
        $idx++;
    }

    $idx = 0;
    for ($n = $closest_above; $n <= $closest_above + 1500; $n += 500) {
        if ($n <= 1000000) {
            $label_tpl = $nearby_labels_after[$idx % 4];
            $label = (strpos($label_tpl, '[N]') !== false)
                ? str_replace('[N]', $n, $label_tpl)
                : $label_tpl . $n;
            $nearby_links[] = ['n' => $n, 'label' => $label];
        }
        $idx++;
    }
}

/* ─── 7. Render ────────────────────────────────────────────────────────────── */
get_header();
?>
<style>
    /* ── Layout (sidebar hidden, full width) ── */
    #sidebar {
        display: none !important;
    }

    .content {
        width: 100% !important;
    }

    /* ── Wrapper ── */
    .ntw-fo-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 20px 15px 50px;
        font-family: inherit;
        color: #333;
        line-height: 1.75;
    }

    /* ── Result box ── */
    .ntw-fo-result-box {
        background: #eafaf1;
        border: 2px solid #2a7d4f;
        border-radius: 8px;
        padding: 18px 22px;
        margin: 0 0 24px;
        font-size: 16px;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .ntw-fo-result-box .ntw-fo-result-label {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #2a7d4f;
        margin-bottom: 6px;
    }

    .ntw-fo-result-box .ntw-fo-result-value {
        font-size: 17px;
        font-weight: 700;
        color: #1a1a1a;
    }

    /* ── Headings ── */
    .ntw-fo-h2 {
        font-size: 1.3em;
        font-weight: 700;
        color: #1a5c30;
        border-bottom: 2px solid #c5e8d3;
        padding-bottom: 6px;
        margin: 34px 0 14px;
    }

    .ntw-fo-h3 {
        font-size: 1.1em;
        font-weight: 700;
        color: #2a7d4f;
        margin: 22px 0 8px;
    }

    /* ── Proof steps ── */
    .ntw-fo-steps {
        list-style: none;
        padding: 0;
        margin: 10px 0 18px;
    }

    .ntw-fo-steps li {
        padding: 6px 0 6px 12px;
        border-left: 3px solid #c5e8d3;
        margin-bottom: 6px;
        font-size: 15px;
        word-break: break-word;
    }

    /* ── Tables ── */
    .ntw-fo-table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0 22px;
        font-size: 14.5px;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .ntw-fo-table th {
        background: #2a7d4f;
        color: #fff;
        padding: 9px 14px;
        text-align: left;
    }

    .ntw-fo-table td {
        padding: 8px 14px;
        border-bottom: 1px solid #eee;
    }

    .ntw-fo-table tr:nth-child(even) td {
        background: #f5faf7;
    }

    .ntw-fo-table tr:hover td {
        background: #e8f7ee;
    }

    /* ── Trivia / info box ── */
    .ntw-fo-info {
        background: #f7fdf9;
        border-left: 4px solid #2a7d4f;
        padding: 12px 16px;
        border-radius: 0 6px 6px 0;
        margin: 10px 0 20px;
        font-size: 14.5px;
    }

    .ntw-fo-info.ntw-fo-prime {
        border-color: #e67e22;
        background: #fef9f5;
    }

    .ntw-fo-info.ntw-fo-perfect {
        border-color: #8e44ad;
        background: #fdf5ff;
    }

    /* ── Nearby links as pills ── */
    .ntw-fo-nearby {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin: 12px 0 24px;
    }

    .ntw-fo-nearby li a {
        display: inline-block;
        background: #2a7d4f;
        color: #fff;
        text-decoration: none;
        padding: 5px 13px;
        border-radius: 20px;
        font-size: 13px;
        transition: background 0.2s;
    }

    .ntw-fo-nearby li a:hover {
        background: #1a5c30;
    }

    /* ── Other conversions row ── */
    .ntw-fo-other {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin: 12px 0 24px;
    }

    .ntw-fo-other li a {
        display: inline-block;
        background: #1a5c30;
        color: #fff;
        text-decoration: none;
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        transition: background 0.2s;
    }

    .ntw-fo-other li a:hover {
        background: #0f3d20;
    }

    /* ── FAQ ── */
    .ntw-fo-faq-item {
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }

    .ntw-fo-faq-item:last-child {
        border: none;
    }

    .ntw-fo-faq-q {
        font-weight: 700;
        color: #1a1a1a;
        font-size: 15px;
        margin-bottom: 5px;
    }

    .ntw-fo-faq-a {
        font-size: 14.5px;
        color: #555;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    /* ── Algebra warning in .error-input ── */
    .error-input a {
        color: #2a7d4f !important;
        font-weight: 700;
        text-decoration: none;
    }

    .error-input a:hover {
        text-decoration: underline;
    }
</style>

<div class="content">
    <?php chiffre_breadcrumbs(); ?>

    <div class="ntw-fo-wrap">

        <!-- ═══════════════════ RESULT BOX ══════════════════════ -->
        <div class="ntw-fo-result-box">
            <div class="ntw-fo-result-label">All Factors of
                <?php echo esc_html($x); ?>
            </div>
            <div class="ntw-fo-result-value">
                <?php echo esc_html($factor_list); ?>
            </div>
        </div>

        <!-- ═══════════════════ INTRO ═══════════════════════════ -->
        <h2 class="ntw-fo-h2">What are the Factors of
            <?php echo esc_html($x); ?>?
        </h2>

        <p>If you are wondering <strong>what are the factors of
                <?php echo esc_html($x); ?>
            </strong>,
            you have come to the right place. In mathematics, the factors of a number are all the positive
            integers that divide perfectly into that number without leaving a remainder.</p>

        <p>The exact factors of <strong>
                <?php echo esc_html($x); ?>
            </strong> are:
            <strong>
                <?php echo esc_html($factor_list); ?>
            </strong>
        </p>

        <p>The number <strong>
                <?php echo esc_html($x); ?>
            </strong> has exactly
            <strong>
                <?php echo esc_html($factor_count); ?>
            </strong> factor
            <?php echo $factor_count !== 1 ? 's' : ''; ?>.
        </p>

        <!-- ═══════════════════ STEP-BY-STEP ════════════════════ -->
        <h2 class="ntw-fo-h2">How to Calculate the Factors of
            <?php echo esc_html($x); ?> Step-by-Step
        </h2>

        <p>To find the factors of <strong>
                <?php echo esc_html($x); ?>
            </strong> manually, we divide the number
            by every integer starting from 1 up to
            <?php echo esc_html($x); ?>. If the quotient is a whole
            number (no decimals), then both the divisor and the quotient are considered factors.
        </p>

        <ul class="ntw-fo-steps">
            <?php foreach ($proof_steps as $step): ?>
                <li>
                    <?php echo $step; ?>
                </li>
            <?php endforeach; ?>
            <?php if (count($pairs) > 4): ?>
                <li>&hellip; and so on up to
                    <?php echo esc_html($x); ?> &divide;
                    <?php echo esc_html($x); ?> = 1
                </li>
            <?php endif; ?>
        </ul>

        <!-- ═══════════════════ MATH PROPERTIES ═════════════════ -->
        <h2 class="ntw-fo-h2">Mathematical Properties of
            <?php echo esc_html($x); ?>
        </h2>

        <!-- Factor Pairs -->
        <h3 class="ntw-fo-h3">Factor Pairs of
            <?php echo esc_html($x); ?>
        </h3>

        <p>Factors often come in pairs. A factor pair is a combination of two numbers that, when multiplied
            together, equal
            <?php echo esc_html($x); ?>. Here are all the positive factor pairs:
        </p>

        <table class="ntw-fo-table">
            <thead>
                <tr>
                    <th>Factor 1</th>
                    <th>Factor 2</th>
                    <th>Product</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pairs as $pair): ?>
                    <tr>
                        <td>
                            <?php echo esc_html($pair[0]); ?>
                        </td>
                        <td>
                            <?php echo esc_html($pair[1]); ?>
                        </td>
                        <td>
                            <?php echo esc_html($pair[0]); ?> &times;
                            <?php echo esc_html($pair[1]); ?> =
                            <?php echo esc_html($x); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Prime Factorization (skip for prime numbers) -->
        <?php if (!$is_prime): ?>
            <h3 class="ntw-fo-h3">Prime Factorization of
                <?php echo esc_html($x); ?>
            </h3>

            <p>Every number can be broken down completely into prime numbers. This is called prime decomposition.
                The prime factorization of <strong>
                    <?php echo esc_html($x); ?>
                </strong> is:</p>

            <div class="ntw-fo-result-box">
                <div class="ntw-fo-result-label">Prime Factorization</div>
                <div class="ntw-fo-result-value">
                    <?php echo esc_html($x); ?> =
                    <?php echo $prime_html; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- NUMBER CLASSIFICATION TRIVIA -->
        <h2 class="ntw-fo-h2">🧠 Number Classification &amp; Trivia</h2>

        <!-- Rule 1: Prime vs Composite -->
        <?php if ($is_prime): ?>
            <div class="ntw-fo-info ntw-fo-prime">
                <strong>
                    <?php echo esc_html($x); ?> is a Prime Number.
                </strong>
                This means it only has two factors: 1 and itself. Because it cannot be divided by any other
                integer, it does not have a complex prime factorization.
            </div>
        <?php else: ?>
            <div class="ntw-fo-info">
                <strong>
                    <?php echo esc_html($x); ?> is a Composite Number
                </strong> because it has more than
                two factors. Specifically, it has
                <?php echo esc_html($factor_count); ?> total divisors.
            </div>
        <?php endif; ?>

        <!-- Rule 2: Perfect Square -->
        <?php if ($is_perfect_sq): ?>
            <div class="ntw-fo-info">
                <strong>⭐ Perfect Square:</strong> Did you know? <strong>
                    <?php echo esc_html($x); ?>
                </strong>
                is a perfect square. Because it is formed by multiplying an integer by itself
                (
                <?php echo esc_html($sqrt_int); ?> &times;
                <?php echo esc_html($sqrt_int); ?> =
                <?php echo esc_html($x); ?>),
                it is one of the rare numbers that has an odd total number of factors
                (
                <?php echo esc_html($factor_count); ?>).
            </div>
        <?php endif; ?>

        <!-- Rule 3: Abundant / Deficient / Perfect -->
        <?php if ($x > 1): ?>
            <?php if ($aliquot === $x): ?>
                <div class="ntw-fo-info ntw-fo-perfect">
                    <strong>🌟 Perfect Number:</strong> In number theory,
                    <strong>
                        <?php echo esc_html($x); ?>
                    </strong> is known as a
                    <strong>"Perfect Number."</strong> This means the sum of its proper divisors
                    (excluding itself) exactly equals the number itself. This is an extremely rare
                    mathematical property!
                </div>
            <?php elseif ($aliquot > $x): ?>
                <div class="ntw-fo-info">
                    <strong>Abundant Number:</strong> The sum of the proper divisors of
                    <?php echo esc_html($x); ?> is <strong>
                        <?php echo esc_html($aliquot); ?>
                    </strong>.
                    Because this sum is greater than the original number,
                    <?php echo esc_html($x); ?> is
                    classified mathematically as an <strong>"Abundant Number."</strong>
                </div>
            <?php else: ?>
                <div class="ntw-fo-info">
                    <strong>Deficient Number:</strong> The sum of the proper divisors of
                    <?php echo esc_html($x); ?> is <strong>
                        <?php echo esc_html($aliquot); ?>
                    </strong>.
                    Because this sum is less than the original number,
                    <?php echo esc_html($x); ?> is
                    classified mathematically as a <strong>"Deficient Number."</strong>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- ═══════════════════ NEARBY LINKS ════════════════════ -->
        <h2 class="ntw-fo-h2">Calculate Nearby Factors</h2>

        <ul class="ntw-fo-nearby">
            <?php foreach ($nearby_links as $link): ?>
                <li>
                    <a href="<?php echo esc_url(home_url('/factors-of-' . intval($link['n']) . '/')); ?>">
                        <?php echo esc_html($link['label']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- ═══════════════════ OTHER CONVERSIONS ═══════════════ -->
        <h2 class="ntw-fo-h2">Other Conversions of the Number
            <?php echo esc_html($x); ?>
        </h2>

        <ul class="ntw-fo-other">
            <li>
                <a href="<?php echo esc_url(home_url('/how-do-you-spell-' . intval($x) . '-in-words/')); ?>">
                    <?php echo esc_html($x); ?> in English
                </a>
            </li>
            <li>
                <a href="<?php echo esc_url(home_url('/how-to-say-' . intval($x) . '-in-french/')); ?>">
                    <?php echo esc_html($x); ?> in French
                </a>
            </li>
            <li>
                <a href="<?php echo esc_url(home_url('/what-is-' . intval($x) . '-factorial/')); ?>">
                    <?php echo esc_html($x); ?> Factorial (
                    <?php echo esc_html($x); ?>!)
                </a>
            </li>
        </ul>

        <!-- ═══════════════════ FAQ ══════════════════════════════ -->
        <h2 class="ntw-fo-h2">Frequently Asked Questions (FAQ)</h2>

        <div class="ntw-fo-faq-item">
            <div class="ntw-fo-faq-q">What is the prime factorization of
                <?php echo esc_html($x); ?>?
            </div>
            <div class="ntw-fo-faq-a">
                <?php if ($is_prime): ?>
                    The number
                    <?php echo esc_html($x); ?> is already a prime number, so its prime
                    factorization is simply <strong>
                        <?php echo esc_html($x); ?>
                    </strong> itself.
                <?php else: ?>
                    The prime factorization of
                    <?php echo esc_html($x); ?> is
                    <strong>
                        <?php echo esc_html($x); ?> =
                        <?php echo $prime_html; ?>
                    </strong>.
                    This represents the number broken down completely into its base prime numbers.
                <?php endif; ?>
            </div>
        </div>

        <div class="ntw-fo-faq-item">
            <div class="ntw-fo-faq-q">How many factors does
                <?php echo esc_html($x); ?> have?
            </div>
            <div class="ntw-fo-faq-a">
                The number <strong>
                    <?php echo esc_html($x); ?>
                </strong> has exactly
                <strong>
                    <?php echo esc_html($factor_count); ?>
                </strong>
                factor
                <?php echo $factor_count !== 1 ? 's' : ''; ?>.
                The complete list of factors is:
                <?php echo esc_html($factor_list); ?>.
            </div>
        </div>

        <div class="ntw-fo-faq-item">
            <div class="ntw-fo-faq-q">Is
                <?php echo esc_html($x); ?> a prime number or composite number?
            </div>
            <div class="ntw-fo-faq-a">
                <?php if ($is_prime): ?>
                    <strong>
                        <?php echo esc_html($x); ?> is a prime number
                    </strong> because it only has
                    two divisors: 1 and itself.
                <?php else: ?>
                    <strong>
                        <?php echo esc_html($x); ?> is a composite number
                    </strong> because it has
                    more than two divisors (
                    <?php echo esc_html($factor_count); ?> total).
                <?php endif; ?>
            </div>
        </div>

    </div><!-- /.ntw-fo-wrap -->
</div><!-- .content -->

<!-- JSON-LD FAQ Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "What is the prime factorization of <?php echo esc_js($x); ?>?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "<?php if ($is_prime): ?>The number <?php echo esc_js($x); ?> is a prime number. Its prime factorization is <?php echo esc_js($x); ?> itself.<?php else: ?>The prime factorization of <?php echo esc_js($x); ?> is <?php echo esc_js($x . ' = ' . strip_tags($prime_html)); ?>.<?php endif; ?>"
    }
  }, {
    "@type": "Question",
    "name": "How many factors does <?php echo esc_js($x); ?> have?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "The number <?php echo esc_js($x); ?> has exactly <?php echo esc_js($factor_count); ?> factors. The complete list of factors is <?php echo esc_js($factor_list); ?>."
    }
  }, {
    "@type": "Question",
    "name": "Is <?php echo esc_js($x); ?> a prime number or composite number?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "<?php echo $is_prime ? esc_js($x . ' is a prime number because it only has two divisors: 1 and itself.') : esc_js($x . ' is a composite number because it has more than two divisors (' . $factor_count . ' total).'); ?>"
    }
  }]
}
</script>

<!--
  ══════════════════════════════════════════════════
  SMART ROUTER — stays on /factors-of-X/ pages
  Same rules as /factoring-calculator/ smart router
  ══════════════════════════════════════════════════
-->
<script>
    (function () {
        var BASE = '<?php echo esc_js(home_url('/')); ?>';

        function doFactoringRedirect() {
            var input = document.querySelector('.convert-input');
            if (!input) return;
            var raw = input.value.trim();
            var err = document.querySelector('.error-input');

            if (err) { err.innerHTML = ''; err.style.color = ''; }

            if (!raw) {
                if (err) err.textContent = 'Please enter a number (e.g. 24) or two numbers (e.g. 12, 16).';
                return;
            }

            /* Rule 3 — algebra/letters */
            if (/[a-zA-Z]/.test(raw) || /\^/.test(raw)) {
                if (err) {
                    err.style.color = '#555';
                    err.innerHTML = '\u26a0\ufe0f Looking to factor an equation? Please use our dedicated Quadratic Equation Factoring Calculator or Polynomial Factoring Calculator.';
                }
                return;
            }

            /* Normalise separators */
            var normalised = raw
                .replace(/\band\b/gi, ',')
                .replace(/\s+/g, ',')
                .replace(/,+/g, ',')
                .replace(/^,|,$/g, '');

            var parts = normalised.split(',').map(function (s) { return s.trim(); });

            /* Rule 2 — two integers → GCF */
            if (parts.length === 2 && /^\d+$/.test(parts[0]) && /^\d+$/.test(parts[1])) {
                var p1 = parseInt(parts[0], 10);
                var p2 = parseInt(parts[1], 10);

                if (p1 > 100 || p2 > 100) {
                    if (err) {
                        err.style.color = 'red';
                        err.textContent = 'Calculations of GCF are limited to numbers between 1 and 100.';
                    }
                    return;
                }

                var x = Math.min(p1, p2);
                var y = Math.max(p1, p2);
                window.location.href = BASE + 'gcf-of-' + x + '-and-' + y + '/';
                return;
            }

            /* Rule 1 — single integer → factors */
            if (parts.length === 1 && /^\d+$/.test(parts[0])) {
                var n = parseInt(parts[0], 10);
                if (n < 1 || n > 1000000) {
                    if (err) err.textContent = '\u26a0\ufe0f Our factoring calculator supports numbers from 1 to 1,000,000.';
                    return;
                }
                window.location.href = BASE + 'factors-of-' + parts[0] + '/';
                return;
            }

            if (err) err.textContent = 'Please enter a valid number (e.g. 24) or two numbers separated by a comma (e.g. 12, 16).';
        }

        window.addEventListener('load', function () {
            document.addEventListener('click', function (e) {
                if (e.target.closest('.convert-button[data-convert="factoring"]')) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    doFactoringRedirect();
                }
            }, true);

            var inp = document.querySelector('.convert-input');
            if (inp) {
                inp.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        doFactoringRedirect();
                    }
                }, true);
            }
        });
    }());
</script>

<?php get_footer(); ?>