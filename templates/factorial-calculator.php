<?php
/**
 * Template: Factorial Calculator Landing Page (/factorial-calculator/)
 * Plugin: Number to Words Converter
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<style>
    /* ── Layout ── */
    #sidebar {
        display: none !important;
    }

    .content {
        width: 100% !important;
    }

    /* ── Wrapper ── */
    .ntw-fc-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 20px 15px 50px;
        font-family: inherit;
        color: #333;
        line-height: 1.7;
    }

    /* ── Intro box ── */
    .ntw-fc-intro {
        background: #f7fdf9;
        border-left: 4px solid #2a7d4f;
        padding: 14px 18px;
        border-radius: 6px;
        margin-bottom: 28px;
        font-size: 15px;
        color: #444;
    }

    .ntw-fc-intro p {
        margin: 0;
    }

    /* ── Tip box ── */
    .ntw-fc-tip {
        background: #fffbe6;
        border: 1px dashed #e0c000;
        border-radius: 6px;
        padding: 12px 16px;
        margin: 14px 0 20px;
        font-size: 14px;
    }

    .ntw-fc-tip strong {
        color: #7a5c00;
    }

    /* ── Headings ── */
    .ntw-fc-h2 {
        font-size: 1.35em;
        font-weight: 700;
        color: #1a5c30;
        border-bottom: 2px solid #c5e8d3;
        padding-bottom: 6px;
        margin: 36px 0 14px;
    }

    .ntw-fc-h3 {
        font-size: 1.1em;
        font-weight: 700;
        color: #2a7d4f;
        margin: 24px 0 10px;
    }

    /* ── Tables ── */
    .ntw-fc-table {
        width: 100%;
        border-collapse: collapse;
        margin: 12px 0 24px;
        font-size: 14px;
    }

    .ntw-fc-table th {
        background: #2a7d4f;
        color: #fff;
        padding: 9px 14px;
        text-align: left;
    }

    .ntw-fc-table td {
        padding: 8px 14px;
        border-bottom: 1px solid #eee;
    }

    .ntw-fc-table tr:nth-child(even) td {
        background: #f5faf7;
    }

    .ntw-fc-table tr:hover td {
        background: #e8f7ee;
    }

    /* ── Math formula display ── */
    .ntw-fc-formula-wrap {
        text-align: center;
        margin: 16px 0;
        padding: 14px;
        background: #f5faf7;
        border-radius: 6px;
    }

    .ntw-fc-formula {
        font-size: 1.25em;
        font-style: italic;
        font-family: Georgia, serif;
        color: #1a1a1a;
        letter-spacing: 0.03em;
    }

    /* ── Highlight box ── */
    .ntw-fc-highlight {
        background: #eafaf1;
        border: 1px solid #a2dbb8;
        border-radius: 6px;
        padding: 14px 18px;
        margin: 16px 0;
        font-size: 15px;
    }

    /* ── Step list ── */
    .ntw-fc-steps {
        padding-left: 0;
        list-style: none;
        margin: 12px 0 18px;
    }

    .ntw-fc-steps li {
        padding: 8px 0 8px 0;
        border-bottom: 1px solid #eee;
    }

    .ntw-fc-steps li:last-child {
        border: none;
    }

    .ntw-fc-steps .step-label {
        display: inline-block;
        background: #2a7d4f;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 12px;
        margin-right: 8px;
        vertical-align: middle;
    }

    /* ── Pill links ── */
    .ntw-fc-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin: 14px 0 24px;
    }

    .ntw-fc-pills li a {
        background: #2a7d4f;
        color: #fff;
        text-decoration: none;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        transition: background 0.2s;
    }

    .ntw-fc-pills li a:hover {
        background: #1a5c30;
    }

    /* ── Inline code style ── */
    .ntw-fc-code {
        background: #f0f0f0;
        border-radius: 4px;
        padding: 1px 6px;
        font-family: monospace;
        font-size: 0.95em;
    }
</style>

<div class="content">
    <?php chiffre_breadcrumbs(); ?>

    <div class="ntw-fc-wrap">

        <!-- ═══════════════════ INTRO ═════════════════════════════════ -->
        <div class="ntw-fc-intro">
            <p>
                Our free online <strong>n factorial calculator</strong> lets you instantly find the factorial of
                any non-negative integer. Type a number in the box at the top of the page and click
                <strong>CALCULATE</strong>. You'll get the exact result, the scientific notation for massive
                numbers, and a step-by-step breakdown of the math.
            </p>
        </div>

        <div class="ntw-fc-tip">
            <p>
                <strong>💡 Quick tip:</strong> To calculate a specific factorial, just type it in the search box
                above and press <em>CALCULATE</em>. You can also browse our popular factorial pages below for
                detailed explanations, mathematical properties, and practical applications.
            </p>
        </div>

        <!-- ═══════════════════ SECTION 1 ════════════════════════════ -->
        <h2 class="ntw-fc-h2">1) How to Use Our N Factorial Calculator</h2>

        <p>Calculating a factorial is effortless with our simple 3-step process:</p>

        <ul class="ntw-fc-steps">
            <li>
                <span class="step-label">Step 1</span>
                Enter the number you want to calculate into the input field at the top of the page.
            </li>
            <li>
                <span class="step-label">Step 2</span>
                Click the <strong>CALCULATE</strong> button.
            </li>
            <li>
                <span class="step-label">Step 3</span>
                The exact mathematical equivalent is displayed instantly — copy it, use it in your formulas,
                or study the detailed breakdown by viewing the calculation steps.
            </li>
        </ul>

        <div class="ntw-fc-highlight">
            <strong>Note:</strong> Our calculator handles massive numbers. For extremely large factorials
            (like <span class="ntw-fc-code">100!</span>), it automatically formats the output using
            <strong>scientific notation</strong> so you can easily read astronomical figures.
        </div>

        <!-- ═══════════════════ SECTION 2 ════════════════════════════ -->
        <h2 class="ntw-fc-h2">2) What is a Factorial? (Definition &amp; Formula)</h2>

        <p>
            In mathematics, the <strong>factorial</strong> of a non-negative integer <em>n</em>, denoted by
            the exclamation mark <span class="ntw-fc-code">n!</span>, is the product of all positive integers
            less than or equal to <em>n</em>. It is an essential concept used heavily in combinatorics,
            algebra, and probability to calculate permutations and combinations.
        </p>

        <!-- 2.1 The Formula -->
        <h3 class="ntw-fc-h3">The Factorial Formula</h3>

        <p>The standard formula to calculate a factorial is written as:</p>
        <div class="ntw-fc-formula-wrap">
            <span class="ntw-fc-formula">
                <em>n</em>! = <em>n</em> &times; (<em>n</em> &minus; 1) &times; (<em>n</em> &minus; 2) &times; &hellip;
                &times; 1
            </span>
        </div>

        <p>
            There is also a <strong>recursive formula</strong>, which defines a factorial based on the
            previous number's factorial:
        </p>
        <div class="ntw-fc-formula-wrap">
            <span class="ntw-fc-formula">
                <em>n</em>! = <em>n</em> &times; (<em>n</em> &minus; 1)!
            </span>
        </div>

        <!-- 2.2 Basic Factorials -->
        <h3 class="ntw-fc-h3">Basic Factorials (1 to 5)</h3>

        <p>
            Mastering the first few factorials is essential for quick mental math.
            Here are the most common small factorials.
        </p>

        <table class="ntw-fc-table">
            <thead>
                <tr>
                    <th>Number (<em>n</em>)</th>
                    <th>Factorial (<em>n</em>!)</th>
                    <th>Calculation Breakdown</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>1</td>
                    <td>1</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>2</td>
                    <td>2 &times; 1</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>6</td>
                    <td>3 &times; 2 &times; 1</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>24</td>
                    <td>4 &times; 3 &times; 2 &times; 1</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>120</td>
                    <td>5 &times; 4 &times; 3 &times; 2 &times; 1</td>
                </tr>
            </tbody>
        </table>

        <!-- ═══════════════════ SECTION 3 ════════════════════════════ -->
        <h2 class="ntw-fc-h2">3) Special Rules: Zero and Negative Numbers</h2>

        <p>
            Factorials follow a strict set of mathematical rules, specifically when dealing with zero
            and negative integers.
        </p>

        <!-- 3.1 Zero -->
        <h3 class="ntw-fc-h3">What is the factorial of 0?</h3>

        <p>By mathematical convention, the factorial of zero is exactly one:</p>
        <div class="ntw-fc-formula-wrap">
            <span class="ntw-fc-formula">0! = 1</span>
        </div>
        <p>
            This is known as an <strong>"empty product."</strong> It is a necessary rule so that formulas for
            permutations and combinations remain valid even when you are selecting zero items from a set.
        </p>

        <!-- 3.2 Negative Numbers -->
        <h3 class="ntw-fc-h3">Can you calculate the factorial of a negative number?</h3>

        <p>
            <strong>No.</strong> The standard factorial function is only defined for
            <strong>non-negative integers</strong> (0, 1, 2, 3, …). If you attempt to calculate the
            factorial of a negative number (e.g., <span class="ntw-fc-code">-5!</span>), it is considered
            <strong>mathematically undefined</strong>. (Advanced mathematics uses the Gamma function to
            extend factorials to complex numbers, but standard calculators do not.)
        </p>

        <!-- 3.3 Medium Factorials -->
        <h3 class="ntw-fc-h3">Medium Factorials (6 to 10)</h3>

        <p>
            As numbers grow, factorials increase exponentially. Notice how quickly the results scale.
        </p>

        <table class="ntw-fc-table">
            <thead>
                <tr>
                    <th>Number (<em>n</em>)</th>
                    <th>Factorial (<em>n</em>!)</th>
                    <th>Literal Translation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>6</td>
                    <td>720</td>
                    <td>6 &times; 5!</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>5,040</td>
                    <td>7 &times; 6!</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>40,320</td>
                    <td>8 &times; 7!</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>362,880</td>
                    <td>9 &times; 8!</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>3,628,800</td>
                    <td>10 &times; 9!</td>
                </tr>
            </tbody>
        </table>

        <!-- ═══════════════════ POPULAR LINKS ════════════════════════ -->
        <h3 class="ntw-fc-h3">Popular factorial calculations</h3>

        <ul class="ntw-fc-pills">
            <li><a href="<?php echo esc_url(site_url('/what-is-5-factorial/')); ?>">What is 5 factorial</a></li>
            <li><a href="<?php echo esc_url(site_url('/what-is-10-factorial/')); ?>">10 factorial</a></li>
            <li><a href="<?php echo esc_url(site_url('/what-is-100-factorial/')); ?>">What is 100 factorial</a></li>
            <li><a href="<?php echo esc_url(site_url('/what-is-52-factorial/')); ?>">52 factorial (deck of cards)</a>
            </li>
            <li><a href="<?php echo esc_url(site_url('/what-is-0-factorial/')); ?>">factorial of 0</a></li>
            <li><a href="<?php echo esc_url(site_url('/what-is-6-factorial/')); ?>">6 factorial</a></li>
            <li><a href="<?php echo esc_url(site_url('/what-is-4-factorial/')); ?>">factorial of 4</a></li>
            <li><a href="<?php echo esc_url(site_url('/what-is-7-factorial/')); ?>">factorial of 7</a></li>
            <li><a href="<?php echo esc_url(site_url('/what-is-8-factorial/')); ?>">8 factorial in math</a></li>
            <li><a href="<?php echo esc_url(site_url('/what-is-69-factorial/')); ?>">What is 69 factorial</a></li>
        </ul>

    </div><!-- /.ntw-fc-wrap -->
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
            document.addEventListener('click', function (e) {
                var btn = e.target.closest('.convert-button[data-convert="factorial"]');
                if (btn) { e.preventDefault(); e.stopImmediatePropagation(); doFactorialRedirect(); }
            }, true);
            var inp = document.querySelector('.convert-input');
            if (inp) inp.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); e.stopImmediatePropagation(); doFactorialRedirect(); }
            }, true);
        });
    }());
</script>

<?php get_footer(); ?>