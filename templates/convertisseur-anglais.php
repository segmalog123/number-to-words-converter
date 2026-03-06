<?php
/**
 * Template: French Number Converter Landing Page (/french-converter/)
 * Plugin: Number to Words Converter
 * Modern standalone design — independent of theme.
 */

if (!defined('ABSPATH')) {
    exit;
}

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

    .ntw-fc-wrap {
        max-width: 860px;
        margin: 0 auto;
        padding: 20px 15px 50px;
        font-family: inherit;
        color: #333;
        line-height: 1.7;
    }

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

    .ntw-fc-table .pron {
        color: #888;
        font-style: italic;
        font-size: 13px;
    }

    .ntw-fc-ul {
        padding-left: 20px;
        margin: 8px 0 16px;
    }

    .ntw-fc-ul li {
        margin-bottom: 8px;
    }

    .ntw-fc-highlight {
        background: #eafaf1;
        border: 1px solid #a2dbb8;
        border-radius: 6px;
        padding: 14px 18px;
        margin: 16px 0;
        font-size: 15px;
    }

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
</style>

<?php // get_header() already called at top of this template ?>

<div class="content">
    <?php chiffre_breadcrumbs(); ?>

    <div class="ntw-fc-wrap">




        <div class="ntw-fc-intro">
            <p>
                Our free online tool lets you instantly convert any English number to its French word equivalent.
                Type a number in the box at the top of the page and click <strong>CONVERT</strong>. You'll get the
                correct French word, plus audio-style pronunciation guides and grammar tips for learners at
                every level — from absolute beginners to advanced French speakers.
            </p>
        </div>

        <div class="ntw-fc-tip">
            <p>
                <strong>💡 Quick tip:</strong> To convert a specific number, just type it in the search box above
                and press <em>CONVERT</em>. You can also browse individual number pages like
                <a href="<?php echo esc_url(site_url('/how-to-say-50-in-french/')); ?>">/how-to-say-50-in-french/</a>
                for detailed explanations, currency conversions, pronunciation, and more.
            </p>
        </div>

        <!-- ═══ SECTION 1 ═══════════════════════════════════════════════ -->
        <h2 class="ntw-fc-h2">1) How to Use Our English to French Converter</h2>

        <p>
            Converting a number to its French word form with our tool is a simple 3-step process:
        </p>
        <ul class="ntw-fc-ul">
            <li><strong>Step 1:</strong> Enter the number you want to convert in the input field at the top of the page.
            </li>
            <li><strong>Step 2:</strong> Click the <strong>CONVERT</strong> button.</li>
            <li><strong>Step 3:</strong> The French word equivalent is displayed instantly — copy it, use it in
                documents, or study the detailed breakdown by clicking the result link.</li>
        </ul>

        <div class="ntw-fc-highlight">
            Our converter handles all numbers from 1 to 999,999,999+, including decimals, currencies (USD, GBP,
            EUR, CAD), and ordinal forms. It follows the current Académie Française rules, including the
            <strong>1990 orthographic reform</strong> which mandates hyphens throughout compound numbers.
        </div>

        <!-- ═══ SECTION 2 ═══════════════════════════════════════════════ -->
        <h2 class="ntw-fc-h2">2) How to Count from 1 to 100 in French</h2>

        <p>
            Mastering French numbers from 1 to 100 is essential for everyday communication — telling time,
            shopping, giving phone numbers, and discussing ages. Below you'll find complete reference tables
            with spelling and pronunciation.
        </p>

        <!-- 2.1 -->
        <h3 class="ntw-fc-h3">2.1) Learning Numbers from 1–10</h3>

        <h4 style="color:#2a7d4f; font-size:1em; margin:10px 0 6px;">Numbers 1–5 in the French Language</h4>
        <table class="ntw-fc-table">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>French</th>
                    <th>Pronunciation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>un / une</td>
                    <td class="pron">œ̃ / yn</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>deux</td>
                    <td class="pron">dø</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>trois</td>
                    <td class="pron">tʁwa</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>quatre</td>
                    <td class="pron">katʁ</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>cinq</td>
                    <td class="pron">sɛ̃k</td>
                </tr>
            </tbody>
        </table>

        <h4 style="color:#2a7d4f; font-size:1em; margin:10px 0 6px;">Numbers 6–10 in the French Language</h4>
        <table class="ntw-fc-table">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>French</th>
                    <th>Pronunciation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>6</td>
                    <td>six</td>
                    <td class="pron">sis</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>sept</td>
                    <td class="pron">sɛt</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>huit</td>
                    <td class="pron">ɥit</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>neuf</td>
                    <td class="pron">nœf</td>
                </tr>
                <tr>
                    <td>10</td>
                    <td>dix</td>
                    <td class="pron">dis</td>
                </tr>
            </tbody>
        </table>

        <!-- 2.2 -->
        <h3 class="ntw-fc-h3">2.2) Learning 1 to 30 in French Numbers</h3>

        <p>
            The numbers from 11 to 19 are unique in French — they follow irregular patterns and must be
            memorised individually. From 20 onward, a pattern emerges.
        </p>
        <table class="ntw-fc-table">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>French</th>
                    <th>Pronunciation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>11</td>
                    <td>onze</td>
                    <td class="pron">ɔ̃z</td>
                </tr>
                <tr>
                    <td>12</td>
                    <td>douze</td>
                    <td class="pron">duz</td>
                </tr>
                <tr>
                    <td>15</td>
                    <td>quinze</td>
                    <td class="pron">kɛ̃z</td>
                </tr>
                <tr>
                    <td>16</td>
                    <td>seize</td>
                    <td class="pron">sɛz</td>
                </tr>
                <tr>
                    <td>17</td>
                    <td>dix-sept</td>
                    <td class="pron">di.sɛt</td>
                </tr>
                <tr>
                    <td>20</td>
                    <td>vingt</td>
                    <td class="pron">vɛ̃</td>
                </tr>
                <tr>
                    <td>21</td>
                    <td>vingt-et-un</td>
                    <td class="pron">vɛ̃.te.œ̃</td>
                </tr>
                <tr>
                    <td>25</td>
                    <td>vingt-cinq</td>
                    <td class="pron">vɛ̃.sɛ̃k</td>
                </tr>
                <tr>
                    <td>30</td>
                    <td>trente</td>
                    <td class="pron">tʁɑ̃t</td>
                </tr>
            </tbody>
        </table>

        <!-- 2.3 -->
        <h3 class="ntw-fc-h3">2.3) Navigating Through Numbers 70–100</h3>

        <p>
            Numbers 70 to 99 are where French diverges significantly from English and other Romance languages.
            The French counting system uses a <em>vigesimal</em> (base-20) structure for this range.
        </p>
        <table class="ntw-fc-table">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>French</th>
                    <th>Literal Translation</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>70</td>
                    <td>soixante-dix</td>
                    <td class="pron">sixty-ten</td>
                </tr>
                <tr>
                    <td>71</td>
                    <td>soixante-et-onze</td>
                    <td class="pron">sixty-and-eleven</td>
                </tr>
                <tr>
                    <td>75</td>
                    <td>soixante-quinze</td>
                    <td class="pron">sixty-fifteen</td>
                </tr>
                <tr>
                    <td>80</td>
                    <td>quatre-vingts</td>
                    <td class="pron">four-twenties</td>
                </tr>
                <tr>
                    <td>81</td>
                    <td>quatre-vingt-un</td>
                    <td class="pron">four-twenty-one</td>
                </tr>
                <tr>
                    <td>90</td>
                    <td>quatre-vingt-dix</td>
                    <td class="pron">four-twenty-ten</td>
                </tr>
                <tr>
                    <td>91</td>
                    <td>quatre-vingt-onze</td>
                    <td class="pron">four-twenty-eleven</td>
                </tr>
                <tr>
                    <td>99</td>
                    <td>quatre-vingt-dix-neuf</td>
                    <td class="pron">four-twenty-nineteen</td>
                </tr>
                <tr>
                    <td>100</td>
                    <td>cent</td>
                    <td class="pron">one hundred</td>
                </tr>
            </tbody>
        </table>

        <div class="ntw-fc-tip">
            <p>
                <strong>📌 Note:</strong> <em>Quatre-vingts</em> (80) takes a plural "s" on <em>vingt</em>,
                but this "s" disappears when the number is followed by another digit:
                <strong>quatre-vingt-un</strong> (81) — no "s".
            </p>
        </div>

        <!-- ═══ SECTION 3 ═══════════════════════════════════════════════ -->
        <h2 class="ntw-fc-h2">3) Numbers in French: 1–1000</h2>

        <p>
            Once you know 1 to 100, larger numbers follow predictable patterns. Here are the key milestones:
        </p>
        <ul class="ntw-fc-ul">
            <li><strong>200</strong> → <em>deux cents</em> (note the "s" on <em>cents</em> — it agrees because there's
                no following number)</li>
            <li><strong>201</strong> → <em>deux cent un</em> (no "s" because a number follows <em>cent</em>)</li>
            <li><strong>500</strong> → <em>cinq cents</em></li>
            <li><strong>1 000</strong> → <em>mille</em> (invariable — never takes an "s")</li>
            <li><strong>2 000</strong> → <em>deux mille</em></li>
            <li><strong>1 000 000</strong> → <em>un million</em></li>
            <li><strong>1 000 000 000</strong> → <em>un milliard</em></li>
        </ul>

        <div class="ntw-fc-highlight">
            <strong>Key rule (1990 Reform):</strong> All components of a compound number must be
            linked with hyphens. For example: <em>deux-cent-cinquante-trois</em> (253),
            <em>mille-neuf-cent-quatre-vingt-dix-neuf</em> (1999).
        </div>

        <!-- ═══ POPULAR EXAMPLES ══════════════════════════════════════════ -->
        <h2 class="ntw-fc-h2">Popular number conversions</h2>

        <ul class="ntw-fc-pills">
            <?php
            $popular_anchors = [
                1 => '%d in French',
                2 => 'how to say %d in French',
                10 => '%d in french language',
                20 => 'how to write %d in french',
                50 => 'say number %d in French',
                100 => 'pronounce numbers %d in French',
                200 => '%d in French',
                500 => 'how to say %d in French',
                1000 => '%d in french language',
                10000 => 'say number %d in French',
                100000 => 'pronounce numbers %d in French',
                1000000 => 'how to write %d in french',
            ];
            foreach ($popular_anchors as $n => $tpl):
                $url = esc_url(site_url('/how-to-say-' . $n . '-in-french/'));
                $anchor = esc_html(sprintf($tpl, $n));
                ?>
                <li><a href="<?php echo $url; ?>"><?php echo $anchor; ?></a></li>
            <?php endforeach; ?>
        </ul>

    </div><!-- /.ntw-fc-wrap -->
</div><!-- .content -->

<?php get_footer(); ?>