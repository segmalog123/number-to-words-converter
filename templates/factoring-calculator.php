<?php
/**
 * Template: Factoring Calculator Landing Page (/factoring-calculator/)
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
    .ntw-fg-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 20px 15px 50px;
        font-family: inherit;
        color: #333;
        line-height: 1.7;
    }

    /* ── Intro box ── */
    .ntw-fg-intro {
        background: #f7fdf9;
        border-left: 4px solid #2a7d4f;
        padding: 14px 18px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 15px;
        color: #444;
    }

    .ntw-fg-intro p {
        margin: 0;
    }

    /* ── Algebra notice box ── */
    .ntw-fg-algebra-notice {
        background: #fff8e1;
        border-left: 4px solid #f9a825;
        padding: 14px 18px;
        border-radius: 6px;
        margin-bottom: 28px;
        font-size: 14.5px;
        color: #444;
    }

    .ntw-fg-algebra-notice strong {
        color: #7a5c00;
    }

    .ntw-fg-algebra-notice a {
        color: #2a7d4f;
        font-weight: 700;
        text-decoration: none;
    }

    .ntw-fg-algebra-notice a:hover {
        text-decoration: underline;
    }

    /* ── Tip box ── */
    .ntw-fg-tip {
        background: #fffbe6;
        border: 1px dashed #e0c000;
        border-radius: 6px;
        padding: 12px 16px;
        margin: 14px 0 20px;
        font-size: 14px;
    }

    .ntw-fg-tip strong {
        color: #7a5c00;
    }

    /* ── Headings ── */
    .ntw-fg-h2 {
        font-size: 1.35em;
        font-weight: 700;
        color: #1a5c30;
        border-bottom: 2px solid #c5e8d3;
        padding-bottom: 6px;
        margin: 36px 0 14px;
    }

    .ntw-fg-h3 {
        font-size: 1.1em;
        font-weight: 700;
        color: #2a7d4f;
        margin: 24px 0 10px;
    }

    /* ── Tables ── */
    .ntw-fg-table {
        width: 100%;
        border-collapse: collapse;
        margin: 12px 0 24px;
        font-size: 14px;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    .ntw-fg-table th {
        background: #2a7d4f;
        color: #fff;
        padding: 9px 14px;
        text-align: left;
    }

    .ntw-fg-table td {
        padding: 8px 14px;
        border-bottom: 1px solid #eee;
    }

    .ntw-fg-table tr:nth-child(even) td {
        background: #f5faf7;
    }

    .ntw-fg-table tr:hover td {
        background: #e8f7ee;
    }

    /* ── Highlight / note box ── */
    .ntw-fg-highlight {
        background: #eafaf1;
        border: 1px solid #a2dbb8;
        border-radius: 6px;
        padding: 14px 18px;
        margin: 16px 0;
        font-size: 15px;
    }

    /* ── Step list ── */
    .ntw-fg-steps {
        padding-left: 0;
        list-style: none;
        margin: 12px 0 18px;
    }

    .ntw-fg-steps li {
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .ntw-fg-steps li:last-child {
        border: none;
    }

    .ntw-fg-steps .step-label {
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

    /* ── Bullet list ── */
    .ntw-fg-list {
        margin: 8px 0 12px 20px;
        padding: 0;
    }

    .ntw-fg-list li {
        margin-bottom: 5px;
        font-size: 14.5px;
    }

    /* ── Pill links ── */
    .ntw-fg-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin: 14px 0 24px;
    }

    .ntw-fg-pills li a {
        background: #2a7d4f;
        color: #fff;
        text-decoration: none;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        transition: background 0.2s;
    }

    .ntw-fg-pills li a:hover {
        background: #1a5c30;
    }

    /* ── Link pills (algebra tools) ── */
    .ntw-fg-tool-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin: 12px 0 20px;
        padding: 0;
        list-style: none;
    }

    .ntw-fg-tool-links li a {
        display: inline-block;
        background: #1a5c30;
        color: #fff;
        text-decoration: none;
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        transition: background 0.2s;
    }

    .ntw-fg-tool-links li a:hover {
        background: #0f3d20;
    }

    /* ── FAQ ── */
    .ntw-fg-faq-item {
        border-bottom: 1px solid #eee;
        padding: 12px 0;
    }

    .ntw-fg-faq-item:last-child {
        border: none;
    }

    .ntw-fg-faq-q {
        font-weight: 700;
        color: #1a1a1a;
        font-size: 15px;
        margin-bottom: 5px;
    }

    .ntw-fg-faq-a {
        font-size: 14.5px;
        color: #555;
        word-break: break-word;
        overflow-wrap: break-word;
    }

    /* ── Algebra inline warning (injected into .error-input via JS) ── */
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

    <div class="ntw-fg-wrap">

        <!-- ══════════════════ INTRO ══════════════════════════════════ -->
        <div class="ntw-fg-intro">
            <p>
                Our free online <strong>factoring calculator</strong> lets you instantly find the factors of any
                number or extract the <strong>Greatest Common Factor (GCF)</strong>. Whether you need a simple
                <strong>calculator of factors</strong>, a <strong>prime factor calculator</strong>, or a
                <strong>GCF factor calculator</strong>, simply type your number(s) in the box at the top of the
                page and click <strong>FACTOR</strong>. You'll get the exact result, plus a detailed, step-by-step
                breakdown of the math.
            </p>
        </div>

        <!-- Algebra advanced tools notice -->
        <div class="ntw-fg-algebra-notice">
            <strong>💡 Looking for advanced algebra?</strong> If you need to factor equations, trinomials, or
            complex expressions, use our dedicated math solvers here:<br>
            ➔ Polynomial Factoring Calculator &nbsp;&nbsp;
            ➔ Quadratic Equation Factoring Calculator
        </div>


        <!-- ══════════════════ SECTION 1 ═════════════════════════════ -->
        <h2 class="ntw-fg-h2">1) How to Use Our Factoring Calculator with Steps</h2>

        <p>Factoring numbers and finding common multiples is effortless with our simple 3-step process. Our tool is
            designed to be the ultimate <strong>factors calculator with steps</strong>:</p>

        <ul class="ntw-fg-steps">
            <li>
                <span class="step-label">Step 1</span>
                Enter a single number (to find its factors) or two numbers separated by a comma (to find the GCF)
                into the input field at the top of the page.
            </li>
            <li>
                <span class="step-label">Step 2</span>
                Click the <strong>FACTOR</strong> button.
            </li>
            <li>
                <span class="step-label">Step 3</span>
                The exact mathematical breakdown is displayed instantly. You can view the step-by-step logic to see
                exactly how the problem was solved.
            </li>
        </ul>

        <div class="ntw-fg-highlight">
            <strong>Note:</strong> Our engine handles massive numbers and complex prime factor trees. Whether you
            need a basic <strong>conversion factor calculator</strong> or want to find the lowest common multiple,
            the system automatically detects your input and applies the correct mathematical rules.
        </div>

        <!-- ══════════════════ SECTION 2 ═════════════════════════════ -->
        <h2 class="ntw-fg-h2">2) Types of Factoring We Calculate</h2>

        <p>Because mathematics requires different methods for different sets of numbers, our site acts as an
            all-in-one <strong>calculator with factors</strong> for multiple disciplines.</p>

        <h3 class="ntw-fg-h3">Prime Factorization &amp; Basic Factors</h3>

        <p>Every number is made up of prime numbers multiplied together. If you are looking for a <strong>prime
                factor decomposition calculator</strong>, simply enter your number. The system will act as a
            <strong>prime
                number factor calculator</strong>, breaking down your input into its absolute base primes
            (e.g., 24 = 2³ &times; 3). It will also list every single divisible factor for that number.
        </p>

        <h3 class="ntw-fg-h3">Greatest Common Factor (GCF)</h3>

        <p>If you need to find <strong>greatest common factor calculator</strong> tools, our system easily calculates
            the highest integer that divides exactly into two numbers. Enter two numbers (like 12 and 16) and it
            functions perfectly as a:</p>

        <ul class="ntw-fg-list">
            <li>GCF factor calculator</li>
            <li>Highest common factors calculator</li>
            <li>Largest common factor calculator</li>
            <li>Least common factors calculator (and LCM)</li>
        </ul>

        <h3 class="ntw-fg-h3">Factoring Polynomials &amp; Trinomials <em style="font-size:0.85em;color:#777;">(Dedicated
                Tool)</em></h3>

        <p>Algebraic expressions require grouping and expanding. If you are dealing with algebra, navigate to our
            highly advanced <strong>calculator
                factoring polynomials</strong>. It will easily factor the polynomial completely calculator
            style,
            breaking down complex terms step-by-step.</p>

        <h3 class="ntw-fg-h3">Quadratic Equations <em style="font-size:0.85em;color:#777;">(Dedicated Tool)</em></h3>

        <p>Quadratics are the most searched factoring problems in algebra. For equations in the format
            <em>ax² + bx + c</em>, visit our dedicated
            <strong>factoring quadratic equations calculator</strong>. It supports all algebraic variations, acting as
            your:
        </p>

        <ul class="ntw-fg-list">
            <li>Factor quadratic expression calculator</li>
            <li>Quadratic formula factoring calculator</li>
            <li>Calculator for factoring quadratics</li>
        </ul>

        <!-- ══════════════════ SECTION 3: Reference Table ════════════ -->
        <h2 class="ntw-fg-h2">3) Basic Factors Reference Table (1 to 10)</h2>

        <p>Mastering the basic factors of single digits is essential for quick mental math. Here are the prime
            factorizations for the most common base numbers.</p>

        <table class="ntw-fg-table">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Factors</th>
                    <th>Prime Factor Decomposition</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2</td>
                    <td>1, 2</td>
                    <td>2</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>1, 3</td>
                    <td>3</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>1, 2, 4</td>
                    <td>2 &times; 2</td>
                </tr>
                <tr>
                    <td>6</td>
                    <td>1, 2, 3, 6</td>
                    <td>2 &times; 3</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>1, 2, 4, 8</td>
                    <td>2 &times; 2 &times; 2</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>1, 2, 5, 10</td>
                    <td>2 &times; 5</td>
                </tr>
            </tbody>
        </table>

        <!-- ══════════════════ SECTION 4: Rules ══════════════════════ -->
        <h2 class="ntw-fg-h2">4) Rules of Factoring: Step by Step</h2>

        <p>To manually <strong>factor completely calculator</strong> style, follow these universal mathematical rules
            depending on your problem:</p>

        <p><strong>1. Prime Factor Trees:</strong> To find the prime factors of a single number, divide it by the
            smallest prime number possible (2, 3, 5, etc.) and continue until all branches of your tree are
            prime.</p>

        <p><strong>2. Find the Greatest Common Factor:</strong> Always look to <strong>factor the common factor
                calculator</strong> style first. Extract the highest integer that applies to all terms.</p>

        <p><strong>3. Difference of Squares:</strong> If you have an algebraic binomial in the form
            <em>a² &minus; b²</em>, it factors to <em>(a&minus;b)(a+b)</em>.
        </p>

        <p><strong>4. Factor Quadratics:</strong> If you have a trinomial, use our <strong>factoring quadratic
                formula calculator</strong> logic to find two numbers that multiply to the constant and add up to the
            middle coefficient.</p>

        <!-- ══════════════════ POPULAR LINKS ═════════════════════════ -->
        <h3 class="ntw-fg-h3">Popular Factoring Calculations</h3>

        <ul class="ntw-fg-pills">
            <li><a href="<?php echo esc_url(site_url('/factors-of-12/')); ?>">Factors of 12</a></li>
            <li><a href="<?php echo esc_url(site_url('/factors-of-24/')); ?>">Factors of 24</a></li>
            <li><a href="<?php echo esc_url(site_url('/factors-of-80/')); ?>">Factors of 80</a></li>
            <li><a href="<?php echo esc_url(site_url('/factors-of-100/')); ?>">Prime factorization of 100</a></li>
            <li><a href="<?php echo esc_url(site_url('/factors-of-36/')); ?>">Factors of 36</a></li>
            <li><a href="<?php echo esc_url(site_url('/factors-of-60/')); ?>">Factors of 60</a></li>
            <li><a href="<?php echo esc_url(site_url('/gcf-of-12-and-16/')); ?>">GCF of 12 and 16</a></li>
            <li><a href="<?php echo esc_url(site_url('/factors-of-48/')); ?>">Factors of 48</a></li>
            <li><a href="<?php echo esc_url(site_url('/gcf-of-24-and-36/')); ?>">GCF of 24 and 36</a></li>
            <li><a href="<?php echo esc_url(site_url('/factors-of-72/')); ?>">Factors of 72</a></li>
        </ul>

        <!-- ══════════════════ FAQ ════════════════════════════════════ -->
        <h2 class="ntw-fg-h2">Frequently Asked Questions (FAQ)</h2>

        <div class="ntw-fg-faq-item">
            <div class="ntw-fg-faq-q">Is there a step by step factor calculator?</div>
            <div class="ntw-fg-faq-a">Yes, our tool is a complete <strong>factoring calculator with steps</strong>.
                Once you input your number or number pair, the engine will break down the exact mathematical steps
                taken to extract the prime factors or the greatest common factor.</div>
        </div>
        <div class="ntw-fg-faq-item">
            <div class="ntw-fg-faq-q">How do I use a factoring trinomials completely calculator?</div>
            <div class="ntw-fg-faq-a">To factor expressions like x² + 7x + 10, you should use our dedicated
                algebraic solvers linked at the top of the page. The system will automatically factor the quadratic
                expression calculator style and provide the paired binomials.</div>
        </div>
        <div class="ntw-fg-faq-item">
            <div class="ntw-fg-faq-q">What is a prime factor decomposition calculator?</div>
            <div class="ntw-fg-faq-a">A <strong>prime factor calculator</strong> breaks down any composite number
                into a string of prime numbers that, when multiplied together, equal the original number.</div>
        </div>

    </div><!-- /.ntw-fg-wrap -->
</div><!-- .content -->

<!-- JSON-LD FAQ Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "Is there a step by step factor calculator?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "Yes, our tool is a complete factoring calculator with steps. Once you input your number or number pair, the engine will break down the exact mathematical steps taken to extract the prime factors or the greatest common factor."
    }
  }, {
    "@type": "Question",
    "name": "How do I use a factoring trinomials completely calculator?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "To factor expressions like x^2 + 7x + 10, you should use our dedicated algebraic solvers linked at the top of the page. The system will automatically factor the quadratic expression and provide the paired binomials."
    }
  }, {
    "@type": "Question",
    "name": "What is a prime factor decomposition calculator?",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "A prime factor calculator breaks down any composite number into a string of prime numbers that, when multiplied together, equal the original number."
    }
  }]
}
</script>

<!--
  ══════════════════════════════════════════════════════════════════
  SMART ROUTER — Factoring Calculator
  Rule 1: single integer              → /factors-of-X/
  Rule 2: two integers (comma/space/  → /gcf-of-X-and-Y/
           "and" separated)
  Rule 3: letters or ^ detected       → show algebra warning, no redirect
  ══════════════════════════════════════════════════════════════════
-->
<script>
    (function () {
        var BASE = '<?php echo esc_js(home_url('/')); ?>';

        function doFactoringRedirect() {
            var input = document.querySelector('.convert-input');
            if (!input) return;
            var raw = input.value.trim();
            var err = document.querySelector('.error-input');

            /* ── Reset error area ── */
            if (err) { err.innerHTML = ''; err.style.color = ''; }

            if (!raw) {
                if (err) err.textContent = 'Please enter a number (e.g. 24) or two numbers (e.g. 12, 16).';
                return;
            }

            /* ── Rule 3 — algebra/letters detected → show warning right under search box ── */
            if (/[a-zA-Z]/.test(raw) || /\^/.test(raw)) {
                if (err) {
                    err.style.color = '#555';
                    err.innerHTML = '\u26a0\ufe0f Looking to factor an equation? Please use our dedicated Quadratic Equation Factoring Calculator or Polynomial Factoring Calculator.';
                }
                return;
            }

            /* ── Normalise separators: "12 and 16" → "12,16"; "12 16" → "12,16" ── */
            var normalised = raw
                .replace(/\band\b/gi, ',')
                .replace(/\s+/g, ',')
                .replace(/,+/g, ',')
                .replace(/^,|,$/g, '');

            var parts = normalised.split(',').map(function (s) { return s.trim(); });

            /* ── Rule 2 — two integers → GCF ── */
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

            /* ── Rule 1 — single integer → factors ── */
            if (parts.length === 1 && /^\d+$/.test(parts[0])) {
                var n = parseInt(parts[0], 10);
                if (n < 1 || n > 1000000) {
                    if (err) err.textContent = '\u26a0\ufe0f Our factoring calculator supports numbers from 1 to 1,000,000.';
                    return;
                }
                window.location.href = BASE + 'factors-of-' + parts[0] + '/';
                return;
            }

            /* ── Fallback: invalid input ── */
            if (err) err.textContent = 'Please enter a valid number (e.g. 24) or two numbers separated by a comma (e.g. 12, 16).';
        }

        window.addEventListener('load', function () {
            /* Capture phase ensures we fire before any other handlers */
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