<?php
namespace NumberToWordsConverter;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generates dynamic content to enrich conversion pages.
 * Supports context-specific content for better SEO engagement.
 */
class ContentGenerator
{
    /**
     * Get data for the Cheque Visual module.
     *
     * @param string $number Input number.
     * @param string $result Text result.
     * @param string $currency Currency label.
     * @param string $lang Target language for content ('fr' or 'en').
     * @return array
     */
    public static function getChequeData($number, $result, $currency = 'Euros', $lang = 'fr')
    {
        if ($lang === 'en') {
            $date = date('F j, Y');
            $amount_txt = ucfirst($result);
            $payee = 'Your Name Here';
            $num_fmt = number_format((float) str_replace(',', '.', $number), 2, '.', ',');
        } else {
            setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');
            $date = date('F j, Y'); // Changed to English date for English speakers viewing the French page
            $amount_txt = ucfirst($result);
            $payee = 'Your Name Here';
            $num_fmt = number_format((float) str_replace(',', '.', $number), 2, ',', ' ');
        }

        return [
            'date' => $date,
            'amount_num' => $num_fmt,
            'amount_txt' => $amount_txt,
            'currency' => $currency,
            'payee' => $payee,
        ];
    }

    /**
     * Get mathematical properties with high spintax variance in English.
     *
     * @param string $number
     * @return array List of fact strings (English).
     */
    public static function getMathFacts($number)
    {
        $n = (int) str_replace([' ', ','], '', $number);
        $facts = [];

        $pick = function ($variations) use ($n) {
            return $variations[$n % count($variations)];
        };

        // Even/Odd
        if ($n % 2 === 0) {
            $facts[] = $pick([
                "The parity of {$n} is even, which means it is exactly divisible by 2.",
                "Mathematically speaking, {$n} is an even natural number.",
                "Since it ends in " . ($n % 10) . ", {$n} is part of the even numbers.",
                "The integer {$n} is a multiple of 2 (even number)."
            ]);
        } else {
            $facts[] = $pick([
                "The parity of {$n} is odd, so it cannot be divided by 2 without a remainder.",
                "The number {$n} belongs to the algebraic category of odd integers.",
                "By its basic arithmetic properties, {$n} is classified as an odd number.",
                "Given its last digit, it is obvious that {$n} is absolutely odd."
            ]);
        }

        // Binary
        $bin = decbin($n);
        $facts[] = $pick([
            "If we translate {$n} into the binary system (base 2), we get the sequence: {$bin}.",
            "The equivalent of {$n} in classic computer binary code is {$bin}.",
            "In base 2 numeration (or binary), the value of {$n} is strictly equal to {$bin}.",
            "For machine logic, this number {$n} is read in the binary form {$bin}."
        ]);

        // Hex
        $hex = strtoupper(dechex($n));
        $facts[] = $pick([
            "In hexadecimal notation (base 16), the corresponding value is {$hex}.",
            "In the hexadecimal system frequently used in programming, {$n} is noted as {$hex}.",
            "The hexadecimal conversion of the decimal integer {$n} gives the result {$hex}.",
            "If encoded on arithmetic base 16, {$n} is displayed as: {$hex}."
        ]);

        // Squared
        $sqrt = sqrt($n);
        if ($sqrt == floor($sqrt)) {
            $facts[] = $pick([
                "Notable fact: {$n} is recognized as being the perfect square of the integer {$sqrt}.",
                "This number {$n} has an exact square root of {$sqrt}.",
                "Geometry tells us that {$n} forms a perfect square based on the side {$sqrt}."
            ]);
        }

        return $facts;
    }

    /**
     * Get grammar rules for the target language, explained in English with variance.
     *
     * @param string $number
     * @param string $target_lang The language of the number being written ('fr' or 'en').
     * @return array List of rules in English.
     */
    public static function getGrammarRules($number, $target_lang = 'fr')
    {
        $n = (int) str_replace([' ', ','], '', $number);
        $rules = [];

        $pick = function ($variations) use ($n) {
            return $variations[$n % count($variations)];
        };

        if ($target_lang === 'en') {
            // ENGLISH Rules
            if ($n >= 21 && $n <= 99) {
                $rules[] = $pick([
                    "English syntax: numbers ranging from 21 to 99 require a hyphen for {$n}.",
                    "For {$n}, grammar dictates linking the tens to the units with a hyphen.",
                    "Modern English requires the use of a hyphen on all compound numbers of this type."
                ]);
            }
            if ($n > 100 && $n % 100 !== 0) {
                $rules[] = $pick([
                    "In the United Kingdom, to write numbers like {$n}, the conjunction 'and' is commonly added.",
                    "British custom dictates the use of the connective 'and' before the tens of a number such as {$n}.",
                    "It's a regional specificity: the British insert 'and' into the spelling structure of {$n}."
                ]);
            }
            if ($n >= 1000) {
                $rules[] = $pick([
                    "Case of invariability: English nouns like 'thousand' or 'million' never take a plural marker here.",
                    "Note that even when multiplying thousands in {$n}, the word 'thousand' remains singular in English.",
                    "Large volumes like {$n} do not entail any plural spelling alterations on base multipliers in English."
                ]);
            }
        } else {
            // FRENCH Rules
            if ($n < 100 && $n > 0) {
                $rules[] = $pick([
                    "As a general principle, all elements of the number {$n} below one hundred use typographic hyphens.",
                    "Basic French grammar requires that a value like {$n} be written with one or more hyphens."
                ]);
            }
            if ($n % 100 === 0 && $n >= 200 && $n < 1000) {
                $rules[] = $pick([
                    "The plural marker applies to the word 'cent', because it ends the reading of {$n}.",
                    "This exact number ({$n}) forces the term 'cent' to obtain a final 's' in the absence of following digits."
                ]);
            }
            if ($n >= 1000 && $n < 2000) {
                $rules[] = $pick([
                    "The numeral adjective 'mille' is strictly invariable in both gender and number.",
                    "Since this number involves the value of thousands, remember that the word 'mille' is always written without an 's'."
                ]);
            }
        }

        return $rules;
    }

    /**
     * Get contextual trivia (Age, Salary, etc.) for unique content.
     *
     * @param string $number
     * @return string|null A contextual fact string or null if none applies.
     */
    public static function getContextualTrivia($number)
    {
        $n = (int) str_replace([' ', ','], '', $number);
        $current_year = (int) date('Y');

        // Age Context (18-100)
        if ($n >= 18 && $n <= 100) {
            $birth_year = $current_year - $n;
            return "If you are {$n} years old, you were born in {$birth_year}.";
        }

        // Monthly Salary Context (1200 - 10000)
        if ($n >= 1200 && $n <= 120000) {
            $monthly = number_format($n / 12, 2, '.', ',');
            return "An annual salary of {$n} dollars corresponds to about {$monthly} dollars per month.";
        }

        // Distance Context (1 - 50)
        if ($n >= 1 && $n <= 50) {
            $steps = number_format($n * 1300, 0, '.', ','); // Approx steps per km
            return "Walking {$n} km represents about {$steps} steps.";
        }

        // Year Context (1900 - 2026)
        if ($n >= 1900 && $n <= $current_year) {
            return "The year {$n} is a year of the Gregorian calendar.";
        }

        return null;
    }

    /**
     * Get highly specific, dynamic text explaining spelling rules for this exact number.
     * Uses modulo selection from large arrays of synonyms to crash duplication rates < 20%.
     *
     * @param string $number
     * @param string $lang Target language ('fr' or 'en')
     * @return array Array of explanation paragraphs.
     */
    public static function getDynamicSpellingText($number, $lang = 'fr')
    {
        $n = (int) str_replace([' ', ','], '', $number);
        $texts = [];

        $pick = function ($variations) use ($n) {
            return $variations[$n % count($variations)];
        };

        if ($lang === 'fr') {
            // FRENCH VARIATIONS
            if ($n === 0) {
                $texts[] = $pick([
                    "Symbolic grammatical fact: the number 'zéro' is the only quantity noun that agrees in plural when used as a common noun (des zéros).",
                    "Absolute conceptual specificity: unlike ordinary numerals, {$n} paradoxically accepts pluralization when it qualifies vague null elements.",
                    "Linguistics separates itself here from mathematics concerning {$n}: taken in isolation as a state marking a declinable absence, it justifies occasionally acquiring an 's'."
                ]);
            }

            // Round tens (10, 20, 30, 40, 50, 60, 70) — single undivided word in French
            if ($n >= 10 && $n <= 70 && $n % 10 === 0) {
                $texts[] = $pick([
                    "In French, round multiples of ten like {$n} are expressed as a single word (e.g., cinquante, soixante). No hyphens are needed as the number is formed by one base word.",
                    "Unlike compound numbers, round tens like {$n} are written as a single undivided word in French. This makes them simpler to spell than composite numbers.",
                    "The French number {$n} is a round ten and is therefore written as a single word, contrasting with composite numbers that require hyphens per the 1990 reform."
                ]);
            }

            // Hyphens (1990 Reform)
            if ($n > 10 && $n < 100 && $n % 10 !== 0 && $n !== 11 && $n !== 71 && $n !== 81 && $n !== 91) {
                $texts[] = $pick([
                    "Classical French spelling separated words with spaces. But since the 1990 reform, the Academy recommends linking the parts of the number {$n} with hyphens (e.g., vingt-et-un).",
                    "To avoid any questioning, the spelling rule modernized since 1990 obliges to link all the numerical syllables of a compound number equivalent to {$n} with horizontal hyphens.",
                    "It is important to emphasize that, following the 1990 adjustments, all elements constituting the written transcription of {$n} are now compulsorily attached with a coordinating hyphen."
                ]);
            } elseif ($n > 100) {
                $texts[] = $pick([
                    "Good to remember for {$n}: the new regulations of 1990 expressly authorize affixing a hyphen between each term, including the hundreds.",
                    "The use of hyphens has broadened. The rectified spelling implies that all components of a large integer like {$n} can now be officially linked by dashes.",
                    "To clarify the reading of {$n}, the Higher Council of the French Language standardized the affixing of hyphens on all blocks of decimal words."
                ]);
            }

            // Exceptions for 80, 20
            if ($n === 80 || ($n > 80 && $n < 100)) {
                $texts[] = $pick([
                    "The term 'vingt' presents a rare subtlety: it takes the plural in 'quatre-vingts' but immediately loses it if followed by an adjective (e.g., quatre-vingt-deux), as is the case for the slice {$n}.",
                    "A typical French grammatical exception found with {$n}: we agree 'vingt' by adding a terminal 's' only if it is at the end of the chain (e.g., 80), but never elsewhere.",
                    "The lexical calculation of 'vingt' in the wake of {$n} obeys a strong constraint: it can only agree in the plural if it closes the numeral expression."
                ]);
            }

            // Exceptions for 100
            if ($n === 100) {
                $texts[] = $pick([
                    "Invariable rule: the word 'cent' only takes the plural mark (an 's') if it is multiplied by another integer, and it ends the numerical component of the number.",
                    "Regarding the centesimal pivot {$n}, the word 'cent' remains singular in this specific case.",
                    "The French Academy dictates that the term 'cent' only agrees in the plural under the effect of a multiplication, which is not the case here."
                ]);
            } elseif ($n % 100 === 0 && $n > 100 && $n < 1000) {
                $texts[] = $pick([
                    "Did you know? The word 'cent' exceptionally takes an 's' in the plural in this number because it is multiplied by the hundred and ends the expression.",
                    "In a round volume like {$n}, the front multiplier forces the word 'cent' to don the grammatical mark of the plural.",
                    "This is the golden rule for the value {$n}: since no additional digit follows the multiplied hundred, the plural agreement in 's' is necessary."
                ]);
            } elseif ($n > 100 && $n % 100 !== 0 && $n < 1000) {
                $texts[] = $pick([
                    "In this very specific configuration regarding {$n}, the word 'cent' remains strictly singular (without 's') because it is followed by another decimal or unitary precision.",
                    "Hundred exception applied to {$n}: although logically multiplied, the pivot 'cent' cancels its plural as soon as it is constrained by a following digit.",
                    "Here we note an essential linguistic mechanism for writing {$n}: the tail of the number inevitably blocks the agreement of the marker 'cent'."
                ]);
            }

            // Mille
            if ($n >= 1000 && $n < 1000000) {
                if ($n >= 1000 && $n < 2000) {
                    $texts[] = $pick([
                        "Golden rule of the French language: the word 'mille' is totally invariable. Whatever quantity it expresses, it will never take an 's' (nor a hyphen if it follows a multiplier).",
                        "The spelling of {$n} illustrates the dogma of the invariability of thousands: the word 'mille' is always written in the singular.",
                        "When writing {$n}, note well that 'mille' escapes the plural rule. It is a historical French exception.",
                        "The thousands milestone in {$n} is marked by the term 'mille', which stubbornly refuses the plural mark.",
                        "No addition of a final letter is allowed on the word 'mille' during the textual transcription of {$n}.",
                        "Francophone syntax is strict for {$n}: the word designating the thousands remains frozen in its singular form."
                    ]);
                } else {
                    $multi_mille = floor($n / 1000);
                    $texts[] = $pick([
                        "Even if {$n} formally symbolizes {$multi_mille} thousands, the term 'mille' will never take an 's'. It represents absolute invariability.",
                        "Contrary to the mathematical logic of multiplication ({$multi_mille} times a thousand), the word remains singular in the writing of {$n}.",
                        "In the specific case of {$n}, the multiplicity does not affect the spelling of the large volume: 'mille' remains without a plural mark.",
                        "The French academy imposes for an amount like {$n} that the thousands marker remains singular, despite the multiplier {$multi_mille}.",
                        "Do not fall into the trap for {$n}: we never pluralize thousands in letters, regardless of their initial quantity.",
                        "The exact quantity of {$multi_mille} thousands changes nothing; the literal expression of {$n} freezes the central word in strict singular."
                    ]);
                }
            }

            // Millions / Milliards
            if ($n >= 1000000) {
                $texts[] = $pick([
                    "Unlike the word 'mille', the terms 'million' and 'milliard' have the grammatical status of common nouns. Consequently, they agree in number and naturally take an 's' in the plural as soon as there are at least two.",
                    "Linguistic fact about {$n}: the words denoting millions or billions are nouns. They therefore explicitly receive a terminal 's' in the plural.",
                    "The grammar of {$n} imposes an 's' on the millions or billions cap, fundamentally distinguishing itself from the invariability of 'mille'."
                ]);
            }

            // Exceptions for 11, 71, 91 and the word "et"
            $last_digit = $n % 10;
            if ($last_digit === 1 && $n !== 11 && $n !== 71 && $n !== 81 && $n !== 91 && $n > 20) {
                $texts[] = $pick([
                    "Unlike other endings, the suffix '1' is often nested using the connector 'et' in these orders of magnitude, in the logical manner of the term {$n}.",
                    "The grammatical treatment of the digit one (vis-à-vis {$n}) usually forces the intervention of the hinge 'et' as a substitute for the clean hyphen.",
                    "If {$n} has to agree before a feminine gender concept (like a 'page' or a 'time'), this unique suffix 'un' will phonetically pivot to 'une'.",
                    "The coordinating conjunction 'et' serves as an exclusive grammatical bridge to hook the final unit 1 into the structure of {$n}.",
                    "It is a phonetic tradition: we soften the pronunciation of {$n} by inserting 'et' before the unit one, rather than a sharp trait."
                ]);
            }
            if ($n === 11 || $n % 100 === 71 || $n % 100 === 91) {
                $texts[] = $pick([
                    "To build this type of number like 71 or 91 in France, we do not say 'soixante-dix-et-un' but we use the compound 'onze' (e.g., soixante-et-onze) to respect counting on a sexagesimal base (base 60).",
                    "To designate {$n}, we free ourselves from the classic 'un' to summon the archaic block 'onze' according to the old vigesimal system.",
                    "Reading the terminal unit in {$n} structurally resorts to the group 'onze', marking an asymmetry with the rest of the decimal declensions."
                ]);
            }
            if ($n % 100 === 81) {
                $texts[] = "French exception: we write 'quatre-vingt-un' deliberately omitting the conjunction 'et', unlike other similar cases like 'vingt-et-un'.";
            }

            // Regional variants (Septante, Nonante, Huitante)
            if (($n >= 70 && $n < 80) || ($n >= 90 && $n < 100)) {
                $texts[] = $pick([
                    "A Swiss or Belgian peculiarity to translate the tens of {$n}: the geographical areas having adopted the use of 'septante' or 'nonante' adroitly bypass the complex base-20 calculation method of the French.",
                    "A captivating cultural detail implied by {$n}: Switzerland and Francophone Belgium radically simplify this number via the vernacular use of clear and logical words like septante or nonante.",
                    "By freeing themselves from the vigesimal system dating partially from the Gallo-Roman era, certain territories rely on an innovative lexicon format to approach the magnitudes around {$n}."
                ]);
            }
            if ($n >= 80 && $n < 90) {
                $texts[] = $pick([
                    "Regional peculiarity: in French-speaking Switzerland, it is common to hear 'huitante' instead of 'quatre-vingts', greatly simplifying the reading of the number {$n}.",
                    "Local linguistic custom: the Swiss often remove the mathematics 'four times twenty' integrated in {$n} to substitute it with the logical root 'huitante'."
                ]);
            }

        } else {
            // ENGLISH VARIATIONS

            // Zero
            if ($n === 0) {
                $texts[] = $pick([
                    "Vocabulary: in American English, the word 'zero' is used almost exclusively, while in British English, 'nought' is frequently heard.",
                    "Usage peculiarity of {$n}: the British often call it 'nought' mathematically, or even 'nil' in a sports score, compared to 'zero' in the United States.",
                    "The nominal approach to {$n} divides the Atlantic, with 'zero' as the target in North America and 'nought' dominating in the Commonwealth."
                ]);
            }

            // Teens (13-19)
            if ($n >= 13 && $n <= 19) {
                $texts[] = $pick([
                    "Origin of words: numbers from 13 to 19 invariably end with the suffix '-teen', etymologically corresponding to the addition of 10 ('ten').",
                    "Specificity of the 13-19 bracket studied here with {$n}: its nominal marker necessarily ends with '-teen', indicating the addition of the unit with ten.",
                    "The English phonetics of {$n} betrays its construction: the final piece '-teen' is nothing other than a historical declension meaning base 10 addition.",
                    "English frames the numerical adolescence ({$n}) with the recurring suffix '-teen'. It is a pivotal rule of original Germanic numeration.",
                    "Faced with a number like {$n}, observe the unwavering use of the ending '-teen', which semantically links the root of the simple digit to the ten."
                ]);
            }

            // Tens (20-90)
            if ($n >= 20 && $n < 100 && $n % 10 === 0) {
                $texts[] = $pick([
                    "Basic rule: round English tens are systematically formed with the addition of the final suffix '-ty' (here to symbolize {$n}).",
                    "Unlike the 'teens', the ending '-ty' used in {$n} is strictly intended to designate a perfect set of tens.",
                    "Pay attention to the English pronunciation of {$n}: the stress generally glides on the first syllable to differentiate this '-ty' from the young '-teens'.",
                    "Fundamental grammar point on {$n}: exclusive multiples of the ten between 20 and 90 require the invariable suffix '-ty'.",
                    "To transcribe the full ten {$n}, the English language merges the root term or its derivative with the essential nominal particle '-ty'.",
                    "When writing {$n}, note well the clarity of the suffix: this '-ty' unambiguously indicates the strict closure of a block of ten, without unitary remainder."
                ]);
            }

            // Hyphens 21-99
            if ($n >= 21 && $n <= 99 && $n % 10 !== 0) {
                $texts[] = $pick([
                    "English syntax: numbers from 21 to 99 require a hyphen to adequately connect {$n}.",
                    "To write {$n}, grammar requires linking the ten to the terminal unit with a hyphen.",
                    "Modern English dictates the restrictive typographic use of the hyphen on all compounds of this caliber like {$n}.",
                    "Pay attention to this detail of punctuation for {$n}: you must imperatively insert a hyphen between the heavy ten and the frail unit.",
                    "Golden rule from 21 to 99: faced with the complex {$n}, academies require welding the numeral expression with a short connective dash.",
                    "Never separate the ten and the unit with a simple space for an amount like {$n}; horizontal conjunction via a hyphen is formally imposed.",
                    "Anglo-Saxon cardinal typography: {$n} obeys the law of junction by hyphen distinctly linking the root of the decade to the accompanying digit."
                ]);
            }

            // Usage of "And"
            if ($n > 100 && $n % 100 !== 0) {
                $texts[] = $pick([
                    "Major transatlantic difference identified on {$n}: United Kingdom English grammar structurally imposes inserting an abstract linking word 'and' between the group of hundreds and the final pair, a practice largely eluded in the United States.",
                    "Focus on the verbal punctuation of {$n}: for British or Australian speakers, voicing this amount aloud will naturally urge the conjunctive inlay of an 'and' just before reading the pairs ending the numerical subject.",
                    "Regional subtleties globally, specifically regarding a string like {$n}, reflect that an orthographic bridge 'and' will appear in European Anglo-Saxon territory while it will be arbitrarily struck by the modern North American speaker.",
                    "By analyzing the construction of {$n} in Shakespeare's language, note that British English meticulously requires the addition of the connector 'and' after the volumes of hundreds.",
                    "The formalism of London schools firmly recommends inserting a logical 'and' before the final fraction of the number {$n}, a specificity erased across the Atlantic."
                ]);
            }

            // Invariable Thousand/Million
            if ($n >= 1000) {
                $texts[] = $pick([
                    "Unshakable Anglo-Saxon invariability rule: in the scenario of {$n}, terms like 'hundred', 'thousand' as well as major tiers like 'million' fiercely preserve the singular as soon as they carry a definite meaning.",
                    "Did you know? When writing the result associated with {$n} in an English format, never try under any pretext to force the plural with an 's' on numerical magnitude lexical markers (e.g., 'thousand'). These global volume units remain mathematically fossilized.",
                    "Constant singular guaranteed: no 's' ending adding a plural scope is permitted in the enunciation of {$n}. The only motive that would provoke this asymmetrical turn would reside in a vast elusive estimate (e.g., 'thousands of books').",
                    "English grammatical warning for {$n}: although this amount is essentially plural as for its value, the pivot multiplier terms ('thousand') must never receive a pluralizing terminal consonant.",
                    "Gauge pillars such as 'thousand' remain frozen in pure singular in English prose. When drafting the wording of {$n}, memorize well this formal invariability.",
                    "English prohibits the presence of a quantity plural ('s' final) on scale words to frame the strict result of {$n}."
                ]);
            }

            // Punctuation Difference
            if ($n >= 1000) {
                $texts[] = $pick([
                    "Radical punctuation contrast for a format like {$n}: English-speaking cultures voluntarily exclude the usual space (European standard) or the period to graphically mark the clusters of thousands by threes; they systematically summon the native use of the isolating comma (,).",
                    "Rupture on the Anglicized decimal system applied to {$n}: in order to guide visual scanning comfort and cut the strata by packets of a thousand, these populations mechanically affix the comma (e.g., 1,000) and carefully avoid the period, which they symbolically confine to fractional decimation.",
                    "If you ever lay down {$n} on a financial commercial piece written in American syntax, firmly keep in mind that the universal indicator of compartmentalization of three zeros turns out to be irrevocably symbolized by the 'comma'.",
                    "The global arithmetic signaling changes the rules face to {$n}: where France places a visual space to aerate large numbers, the conventional UK/US standard executes this caesura exclusively by means of the fine comma.",
                ]);
            }

            // General English Rule 1: Capitalization
            $texts[] = $pick([
                "English grammatical capitalization rule: no matter the length of the number {$n}, the words composing it are written entirely in lowercase, with the obvious exception of the beginning of a sentence.",
                "In British and American typography, note that writing the result of {$n} is done exclusively in lowercase. We only capitalize the very first word of the line.",
                "Contrary to some titling usages, the body of the text stipulates that the literal development of {$n} in English formally rejects capitalization in its middle.",
                "Recurring orthographic detail with {$n} in the English language: no noun of value or unit takes a capital letter. The whole spreads evenly in current lowercase.",
                "During the formal transcription of {$n}, the English dictionary imposes an integral minor case on all the generated words."
            ]);

            // General English Rule 2: Pluralization of the number itself
            if ($n >= 10) {
                $texts[] = $pick([
                    "Linguistic curiosity: in English, if we must speak of the years or values of {$n} in the plural (e.g., 'the {$n}s'), we simply append a final 's' to the word without an apostrophe.",
                    "Usual formatting: when the context forces the plural on the complete number of {$n} (to designate a decade or a batch), contemporary English grammar bans the apostrophe and directly welds the 's' to the relevant word.",
                    "If the scenario involves declining the generic idea of {$n} in the plural (e.g., 'tens of {$n}'), basic English will suffix the numeral matrix with a simple final letter 's'.",
                    "To collectively express the notion of several '{$n}', forget the possessive syntax with apostrophe; style manuals advocate the raw suffix 's' coupled to the terminal segment.",
                    "The application of the general plural marker on the overall entity of {$n} obeys a direct rule in English: we graft a glued 's' indiscriminately to the last term of the set."
                ]);
            }
        }

        // Limit to max 4 diverse rules and randomly shuffle to make it look highly dynamic without losing order
        if (empty($texts)) {
            $texts[] = "The translation and writing of numbers require the utmost attention to rules of agreement, grammar, and punctuation set by both grammar institutions and English linguistics universities.";
        } else {
            // Select up to 4 applicable rules to keep UI balanced
            $texts = array_slice($texts, 0, 4);
        }

        return $texts;
    }

    /**
     * Accurately converts an English cardinal text (e.g. "Twenty-one") 
     * into an ordinal text (e.g. "Twenty-first").
     *
     * @param string $plain_text 
     * @return string
     */
    public static function getEnglishOrdinal($plain_text)
    {
        $text = strtolower(trim($plain_text));
        if (empty($text))
            return '';

        $exceptions = [
            'one' => 'first',
            'two' => 'second',
            'three' => 'third',
            'five' => 'fifth',
            'eight' => 'eighth',
            'nine' => 'ninth',
            'twelve' => 'twelfth',
        ];

        // Match the very last word directly, optionally preceded by a space or hyphen
        preg_match('/([ \-]?)([a-z]+)$/', $text, $matches);
        if (!$matches)
            return $text . 'th';

        $separator = $matches[1];
        $lastWord = $matches[2];
        $prefix = substr($text, 0, -strlen($matches[0]));

        if (isset($exceptions[$lastWord])) {
            $ordinalLast = $exceptions[$lastWord];
        } elseif (substr($lastWord, -2) === 'ty') { // twenty -> twentieth
            $ordinalLast = substr($lastWord, 0, -1) . 'ieth';
        } else {
            $ordinalLast = $lastWord . 'th';
        }

        return ucfirst($prefix . $separator . $ordinalLast);
    }

    /**
     * Generates English-specific SEO context (Ordinals, years, age) 
     * to avoid duplicate content penalties with the French math facts.
     * Includes extensive spintax so pages have unique phrasing.
     *
     * @param string $number
     * @param string $result_en_plain
     * @return array
     */
    public static function getEnglishSeoFacts($number, $result_en_plain)
    {
        $n = (int) str_replace([' ', ','], '', $number);
        $facts = [];

        $pick = function ($variations) use ($n) {
            return $variations[$n % count($variations)];
        };

        if ($n === 1) {
            $ordinal = 'Premier';
        } else {
            $text = strtolower(trim($result_en_plain));
            preg_match('/([ \-]?)([a-z]+)$/', $text, $matches);
            if ($matches) {
                $separator = $matches[1];
                $lastWord = $matches[2];
                $prefix = substr($text, 0, -strlen($matches[0]));
                if ($lastWord === 'un')
                    $ordinalLast = 'unième';
                elseif ($lastWord === 'neuf')
                    $ordinalLast = 'neuvième';
                elseif ($lastWord === 'cinq')
                    $ordinalLast = 'cinquième';
                elseif (substr($lastWord, -1) === 'e')
                    $ordinalLast = substr($lastWord, 0, -1) . 'ième';
                else
                    $ordinalLast = $lastWord . 'ième';
                $ordinal = ucfirst($prefix . $separator . $ordinalLast);
            } else {
                $ordinal = ucfirst($text . 'ième');
            }
        }
        $ord_lower = strtolower($ordinal);
        $res_lower = strtolower($result_en_plain);

        // Ordinal Spintax
        $facts[] = $pick([
            "<strong>Ordinal form:</strong> To indicate a position (the {$n}th), the grammatical translation is \"{$ordinal}\". French broadly utilizes ordinal numbers: <em>le {$ord_lower}</em>.",
            "<strong>How to say the {$n}th:</strong> In French, the exact equivalent of the rank {$n} is written ordinally: <em>le {$ord_lower}</em>.",
            "<strong>The {$n}th in French:</strong> If your goal is to express a rank (the {$n}th component), the correct expression is firmly <em>le {$ord_lower}</em>.",
            "<strong>Ranking and ordering:</strong> To verbalize the {$n}th place or position, the adequate French phrasing is formally <em>le {$ord_lower}</em>."
        ]);

        // Age Context
        if ($n >= 1 && $n <= 110) {
            $facts[] = $pick([
                "<strong>The age of {$n} years old:</strong> To express \"I am {$n} years old\", French uses the verb 'to have' (avoir). It translates to: <em>J'ai {$res_lower} ans</em>.",
                "<strong>How to express I am {$n} years old:</strong> Unlike English which uses 'to be', French uses 'to have'. Hence the proper phrase <em>J'ai {$res_lower} ans</em>.",
                "<strong>Expressing age ({$n} years):</strong> If you want to talk about a specific age like {$n} years old, French grammatical structure dictates the verb 'avoir': <em>J'ai {$res_lower} ans</em>.",
                "<strong>Stating one's age:</strong> To state your age of {$n} years, never use the verb \"to be\" in French. The exact and correct translation is <em>J'ai {$res_lower} ans</em>."
            ]);
        }

        // Decades context ("les années 90")
        if ($n >= 10 && $n <= 90 && $n % 10 === 0) {
            $facts[] = $pick([
                "<strong>Decade (The {$n}s):</strong> To talk about the {$n}s decade in French, you use the plural article followed by the word 'années'. For example, you say <em>les années {$res_lower}</em>.",
                "<strong>The {$n}s in French:</strong> When discussing this historical decade, French grammar requires the word 'années': <em>les années {$res_lower}</em>.",
                "<strong>Era of the {$n}s:</strong> If you are referring to the general culture of this historical epoch, French uses: <em>les années {$res_lower}</em>."
            ]);
        }

        // Specific Year context (19XX, 20XX)
        if ($n >= 1900 && $n <= 2099) {
            $facts[] = $pick([
                "<strong>Date and year ({$n}):</strong> If it is a historical year, French reading often groups the digits. For example, {$n} can sometimes be pronounced with 'cent' (e.g., dix-neuf cent...).",
                "<strong>Pronouncing the year {$n}:</strong> Keep in mind that a vintage such as {$n} in French can be read conventionally or using the 'cent' grouping for years before 2000."
            ]);
        }

        // Generic pluralization rule (fallback if few rules match)
        if ($n >= 100) {
            $facts[] = $pick([
                "<strong>Grammar tip:</strong> Unlike English, French applies specific plural rules to numbers like {$n}. The word <em>vingt</em> and <em>cent</em> can take an 's' under certain multiplication conditions.",
                "<strong>Plural rule:</strong> When writing a global amount like {$n} in French, remember that units of hundred (cent) or twenty (vingt) might agree in the plural if they end the number.",
                "<strong>Mathematical phrasing:</strong> For the value of {$n}, the French spelling requires strict adherence to hyphens and specific plural exceptions for <em>cent</em> and <em>vingt</em>."
            ]);
        }

        $limit_facts = [];
        foreach ($facts as $f) {
            $limit_facts[] = $f;
        }

        return array_slice($limit_facts, 0, 4);
    }
}
