<?php
/**
 * Template Name: GCF Calculator Dynamic Default
 * Description: Dynamically generates the GCF calculation result page. Note:
 * WP_Query must have 'gcf_x' and 'gcf_y' set in order to load this correctly.
 */

if (!defined('ABSPATH')) {
    exit;
}

$x = (int) get_query_var('gcf_x');
$y = (int) get_query_var('gcf_y');

// Fallback just in case they aren't provided correctly
if (empty($x) || empty($y)) {
    wp_redirect(home_url());
    exit;
}

// Logic: Ensure x <= y for computations. The 301 is already enforced in TemplateLoader, but safety first.
if ($x > $y) {
    $temp = $x;
    $x = $y;
    $y = $temp;
}

// Function Helpers
if (!function_exists('ntw_get_factors')) {
    function ntw_get_factors($num)
    {
        $factors = [];
        for ($i = 1; $i <= $num; $i++) {
            if ($num % $i == 0) {
                $factors[] = $i;
            }
        }
        return $factors;
    }
}

if (!function_exists('ntw_prime_factorization')) {
    function ntw_prime_factorization($n)
    {
        $factors = [];
        while ($n % 2 == 0) {
            $factors[] = 2;
            $n = $n / 2;
        }
        for ($i = 3; $i <= sqrt($n); $i = $i + 2) {
            while ($n % $i == 0) {
                $factors[] = $i;
                $n = $n / $i;
            }
        }
        if ($n > 2) {
            $factors[] = $n;
        }
        return empty($factors) ? [$n] : $factors; // Return number itself if prime
    }
}

if (!function_exists('ntw_format_prime_html')) {
    function ntw_format_prime_html($primes)
    {
        if (count($primes) === 1) {
            return $primes[0]; // If it's prime, just return it
        }

        $counts = array_count_values($primes);
        $html_parts = [];
        foreach ($counts as $prime => $count) {
            if ($count > 1) {
                $html_parts[] = $prime . '<sup>' . $count . '</sup>';
            } else {
                $html_parts[] = $prime;
            }
        }
        return implode(' × ', $html_parts);
    }
}

// Factor computations
$factors_x = ntw_get_factors($x);
$factors_y = ntw_get_factors($y);
$common_factors = array_intersect($factors_x, $factors_y);
$gcf = max($common_factors);
$lcm = ($x * $y) / $gcf;

// Prime factor computations
$primes_x = ntw_prime_factorization($x);
$primes_y = ntw_prime_factorization($y);

// Determine overlapping primes for the GCF equation
$counts_x = array_count_values($primes_x);
$counts_y = array_count_values($primes_y);
$shared_primes = [];
foreach ($counts_x as $prime => $count) {
    if (isset($counts_y[$prime])) {
        $overlap = min($count, $counts_y[$prime]);
        for ($i = 0; $i < $overlap; $i++) {
            $shared_primes[] = $prime;
        }
    }
}
$gcf_primes_html = empty($shared_primes) ? '1' : ntw_format_prime_html($shared_primes);


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
    <?php if (function_exists('chiffre_breadcrumbs')) {
        chiffre_breadcrumbs();
    } ?>

    <div class="ntw-fo-wrap">

        <!-- ═══════════════════ RESULT BOX ══════════════════════ -->
        <div class="ntw-fo-result-box">
            <div class="ntw-fo-result-label">The exact GCF of <?php echo $x; ?> and <?php echo $y; ?> is:</div>
            <div class="ntw-fo-result-value">
                <?php echo esc_html($gcf); ?>
            </div>
        </div>

        <!-- ═══════════════════ INTRO ═══════════════════════════ -->
        <p>If you are wondering <strong>what is the GCF of <?php echo esc_html($x); ?> and
                <?php echo esc_html($y); ?></strong>, you have come to the right place. In mathematics, the Greatest
            Common Factor (GCF) is the largest positive integer that divides perfectly into both numbers without leaving
            a remainder.</p>

        <p>The exact <strong>GCF of <?php echo esc_html($x); ?> and <?php echo esc_html($y); ?></strong> is:<br>
            <span style="font-size: 1.2em; font-weight: bold;"><?php echo esc_html($gcf); ?></span>
        </p>

        <!-- ═══════════════════ HOW TO FIND GCF ════════════════════ -->
        <h2 class="ntw-fo-h2">How to Find the Greatest Common Factor of <?php echo esc_html($x); ?> and
            <?php echo esc_html($y); ?>
        </h2>
        <p>There are two primary mathematical methods used to calculate the <strong>greatest common factor of
                <?php echo esc_html($x); ?> and <?php echo esc_html($y); ?></strong>. Our calculator processes both
            instantly.</p>

        <!-- ═══════════════════ STEP-BY-STEP (LISTING) ════════════════════ -->
        <h3 class="ntw-fo-h3">Method 1: Listing the Factors</h3>
        <p>The simplest way to find the common divisors is to list all the factors for both numbers and find the largest
            matching number.</p>

        <ul class="ntw-fo-steps">
            <li><strong>Factors of <?php echo $x; ?>:</strong> <?php echo implode(', ', $factors_x); ?></li>
            <li><strong>Factors of <?php echo $y; ?>:</strong> <?php echo implode(', ', $factors_y); ?></li>
        </ul>

        <p>If we look at both lists, the matching divisors are
            <strong><?php echo implode(', ', $common_factors); ?></strong>. The largest number they both share is
            <strong><?php echo $gcf; ?></strong>.
        </p>

        <!-- ═══════════════════ STEP-BY-STEP (PRIME FACTORIZATION) ════════════════════ -->
        <h3 class="ntw-fo-h3">Method 2: Prime Factorization</h3>
        <p>For a more advanced algebraic approach, we can break both numbers down into their base prime factors and
            multiply the shared primes.</p>

        <ul class="ntw-fo-steps">
            <li><strong>Prime factorization of <?php echo $x; ?>:</strong>
                <?php echo ntw_format_prime_html($primes_x); ?></li>
            <li><strong>Prime factorization of <?php echo $y; ?>:</strong>
                <?php echo ntw_format_prime_html($primes_y); ?></li>
        </ul>

        <p>By multiplying the prime numbers that overlap in both equations, we get:
            <strong><?php echo $gcf_primes_html; ?> = <?php echo $gcf; ?></strong>.
        </p>

        <!-- ═══════════════════ MATH PROPERTIES ═════════════════ -->
        <h2 class="ntw-fo-h2">Mathematical Properties of <?php echo $x; ?> and <?php echo $y; ?></h2>

        <?php if ($gcf === 1): ?>
            <p>Did you know? Because the greatest common factor of <?php echo $x; ?> and <?php echo $y; ?> is exactly 1,
                these two numbers are classified mathematically as "Coprime" (or relatively prime). This means they share
                absolutely no common divisors other than the number 1.</p>
        <?php elseif ($y % $x === 0): ?>
            <p>Notice a mathematical shortcut here! Because <?php echo $y; ?> is a direct multiple of <?php echo $x; ?>
                (meaning <?php echo $x; ?> &times; <?php echo ($y / $x); ?> = <?php echo $y; ?>), the Greatest Common Factor
                will always simply be the smaller number itself. Therefore, the GCF is <?php echo $x; ?>.</p>
        <?php endif; ?>

        <p><strong>The LCM Connection</strong><br>
            Once you know the GCF, you can easily find the Least Common Multiple (LCM) of <?php echo $x; ?> and
            <?php echo $y; ?>. Using the mathematical formula LCM(a,b) = (a &times; b) / GCF, we can
            determine that the LCM of <?php echo $x; ?> and <?php echo $y; ?> is
            <strong><?php echo number_format($lcm); ?></strong>.
        </p>

        <!-- ═══════════════════ NEARBY LINKS ════════════════════ -->
        <h2 class="ntw-fo-h2">Calculate Nearby GCF Pairings</h2>
        <ul class="ntw-fo-nearby">
            <?php
            // Generate exact 4 below and 4 above pairs
            $nearby_urls = [];
            for ($i = 1; $i <= 4; $i++) {
                if ($y - $i >= 1 && ($y - $i) !== $x) {
                    $link_x = min($x, $y - $i);
                    $link_y = max($x, $y - $i);
                    $nearby_urls[] = ['url' => '/gcf-of-' . $link_x . '-and-' . $link_y . '/', 'text' => "What is the GCF of {$x} and " . ($y - $i)];
                }
            }
            for ($i = 1; $i <= 4; $i++) {
                if ($y + $i <= 100 && ($y + $i) !== $x) {
                    $link_x = min($x, $y + $i);
                    $link_y = max($x, $y + $i);
                    $nearby_urls[] = ['url' => '/gcf-of-' . $link_x . '-and-' . $link_y . '/', 'text' => "GCF of {$x} and " . ($y + $i)];
                }
            }

            $labels = [
                "What is the GCF of [X] and [Y]",
                "Greatest common factor of [X] and [Y]",
                "GCF of [X] and [Y]",
                "Common factors of [X] and [Y]"
            ];

            $limit = min(8, count($nearby_urls));
            for ($i = 0; $i < $limit; $i++) {
                $item = $nearby_urls[$i];
                // Extract target Y from url string (after 'and-')
                preg_match('/-and-(\d+)\//', $item['url'], $matches);
                $target_y = isset($matches[1]) ? $matches[1] : '';

                // Replace [X] and [Y] in pattern
                $pattern = $labels[$i % 4];
                $link_text = str_replace(['[X]', '[Y]'], [$x, $target_y], $pattern);

                echo '<li><a href="' . esc_url(site_url($item['url'])) . '">' . esc_html($link_text) . '</a></li>';
            }
            ?>
        </ul>

        <!-- ═══════════════════ INTERNAL LINKS ════════════════════ -->
        <h2 class="ntw-fo-h2">Explore Individual Factors</h2>
        <ul class="ntw-fo-other">
            <li><a href="<?php echo esc_url(site_url('/factors-of-' . $x . '/')); ?>">View all factors of
                    <?php echo $x; ?> completely</a></li>
            <li><a href="<?php echo esc_url(site_url('/factors-of-' . $y . '/')); ?>">View all factors of
                    <?php echo $y; ?> completely</a></li>
        </ul>

        <!-- ═══════════════════ FAQ & SCHEMA ════════════════════ -->
        <h2 class="ntw-fo-h2">Frequently Asked Questions (FAQ)</h2>

        <div class="ntw-fo-faq-item">
            <div class="ntw-fo-faq-q">What is the greatest common factor of <?php echo $x; ?> and <?php echo $y; ?>?
            </div>
            <div class="ntw-fo-faq-a">The greatest common factor (GCF) of <?php echo $x; ?> and <?php echo $y; ?> is
                <?php echo $gcf; ?>. This is the largest integer that divides perfectly into both <?php echo $x; ?> and
                <?php echo $y; ?> without a remainder.
            </div>
        </div>

        <div class="ntw-fo-faq-item">
            <div class="ntw-fo-faq-q">How do you find the GCF of <?php echo $x; ?> and <?php echo $y; ?>?</div>
            <div class="ntw-fo-faq-a">To find the GCF, list the factors of <?php echo $x; ?>
                (<?php echo implode(', ', $factors_x); ?>) and the factors of <?php echo $y; ?>
                (<?php echo implode(', ', $factors_y); ?>). The highest number that appears in both lists is
                <?php echo $gcf; ?>.
            </div>
        </div>

        <div class="ntw-fo-faq-item">
            <div class="ntw-fo-faq-q">What are the common divisors of <?php echo $x; ?> and <?php echo $y; ?>?</div>
            <div class="ntw-fo-faq-a">The shared common divisors for <?php echo $x; ?> and <?php echo $y; ?> are
                <?php echo implode(', ', $common_factors); ?>. The largest of these is <?php echo $gcf; ?>.
            </div>
        </div>

        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "FAQPage",
          "mainEntity": [{
            "@type": "Question",
            "name": "What is the greatest common factor of <?php echo $x; ?> and <?php echo $y; ?>?",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "The greatest common factor (GCF) of <?php echo $x; ?> and <?php echo $y; ?> is <?php echo $gcf; ?>. This is the largest integer that divides perfectly into both <?php echo $x; ?> and <?php echo $y; ?> without a remainder."
            }
          }, {
            "@type": "Question",
            "name": "How do you find the GCF of <?php echo $x; ?> and <?php echo $y; ?>?",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "To find the GCF, list the factors of <?php echo $x; ?> and the factors of <?php echo $y; ?>. The highest number that appears in both lists is <?php echo $gcf; ?>."
            }
          }, {
            "@type": "Question",
            "name": "What are the common divisors of <?php echo $x; ?> and <?php echo $y; ?>?",
            "acceptedAnswer": {
              "@type": "Answer",
              "text": "The shared common divisors for <?php echo $x; ?> and <?php echo $y; ?> are <?php echo implode(', ', $common_factors); ?>."
            }
          }]
        }
        </script>

    </div>
</div>

<?php
// Enqueue Custom JS for the Smart Router Error limits specifically for this page.
// The main routing logic is in script.js but we inject the bounds validation specifically for GCF here.
?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const convertBtn = document.querySelector('.convert-button');
        const inputField = document.querySelector('.convert-input');
        const errorSpan = document.querySelector('.error-input');

        if (convertBtn && inputField && errorSpan && convertBtn.getAttribute('data-convert') === 'factoring') {
            const originalBtnClick = convertBtn.onclick;

            // Remove existing listeners if any
            const newBtn = convertBtn.cloneNode(true);
            convertBtn.parentNode.replaceChild(newBtn, convertBtn);

            newBtn.addEventListener("click", function (e) {
                errorSpan.textContent = ''; // clear error
                const rawVal = inputField.value.trim();

                // Allow commas or spaces as delimiters
                let parts = rawVal.split(/[\s,]+/);
                parts = parts.filter(p => p.length > 0);

                // Check limits for GCF pair routing if 2 parts
                if (parts.length === 2) {
                    let p1 = parseInt(parts[0], 10);
                    let p2 = parseInt(parts[1], 10);

                    if (isNaN(p1) || isNaN(p2)) {
                        errorSpan.textContent = "Please enter valid numbers.";
                        return;
                    }

                    if (p1 > 100 || p2 > 100) {
                        errorSpan.textContent = "Calculations are limited to numbers between 1 and 100.";
                        return;
                    }

                    // Enforce numerical URL lock natively in JS
                    let x = Math.min(p1, p2);
                    let y = Math.max(p1, p2);

                    window.location.href = `<?php echo site_url(); ?>/gcf-of-${x}-and-${y}/`;
                } else if (parts.length === 1) { // Normal factors routing logic
                    let p1 = parseInt(parts[0], 10);
                    if (isNaN(p1)) {
                        errorSpan.textContent = "Please enter a valid number.";
                        return;
                    }

                    if (p1 > 1000000) {
                        errorSpan.textContent = "Calculations are limited to numbers up to 1,000,000.";
                        return;
                    }
                    window.location.href = `<?php echo site_url(); ?>/factors-of-${p1}/`;
                } else {
                    errorSpan.textContent = "Please enter one or two numbers (e.g., 24 or 12, 16).";
                }
            });

            // Trigger on enter key
            inputField.addEventListener("keydown", function (e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    newBtn.click();
                }
            });
        }
    });
</script>

<?php get_footer(); ?>