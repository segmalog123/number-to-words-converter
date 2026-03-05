<?php
/**
 * Global function wrappers for backward compatibility.
 * This file is intentionally NOT namespaced so these functions
 * are available in the global scope for templates and themes.
 *
 * @package ConvertisseurNumberToWordsConverter
 */

if (!defined('ABSPATH')) {
    exit;
}

// =========================================================================
// CONVERTER FUNCTIONS
// =========================================================================

if (!function_exists('funcConvert')) {
    function funcConvert($number_to_convert, $action = '', $type = '')
    {
        return \NumberToWordsConverter\Converters\ConverterHelper::convert($number_to_convert, $action, $type);
    }
}

if (!function_exists('funcListNumber')) {
    function funcListNumber($number_to_convert)
    {
        return \NumberToWordsConverter\Converters\ConverterHelper::listSimilarNumbers($number_to_convert);
    }
}

if (!function_exists('funcNumBetween')) {
    function funcNumBetween($number_to_convert)
    {
        return \NumberToWordsConverter\Converters\ConverterHelper::numberBetween($number_to_convert);
    }
}

if (!function_exists('funcPercent')) {
    function funcPercent($number_to_convert)
    {
        return \NumberToWordsConverter\Converters\ConverterHelper::percent($number_to_convert);
    }
}

if (!function_exists('from_zero_to')) {
    function from_zero_to($n)
    {
        return \NumberToWordsConverter\Converters\FrenchConverter::fromZeroTo($n);
    }
}



if (!function_exists('enChiffre')) {
    function enChiffre($nombre)
    {
        return \NumberToWordsConverter\Converters\FrenchConverter::enChiffre($nombre);
    }
}

if (!function_exists('enDevise')) {
    function enDevise($nombre, $devise)
    {
        return \NumberToWordsConverter\Converters\FrenchConverter::enDevise($nombre, $devise);
    }
}

if (!function_exists('enlettres')) {
    function enlettres($nombre, $options = null, $separateur = null)
    {
        return \NumberToWordsConverter\Converters\FrenchConverter::enlettres($nombre, $options, $separateur);
    }
}

if (!function_exists('convertCurrencyToWords')) {
    function convertCurrencyToWords($number, $to)
    {
        return \NumberToWordsConverter\Converters\EnglishConverter::convertCurrencyToWords($number, $to);
    }
}

if (!function_exists('convertIntegerToWords')) {
    function convertIntegerToWords($x)
    {
        return \NumberToWordsConverter\Converters\EnglishConverter::convertIntegerToWords($x);
    }
}

// =========================================================================
// BREADCRUMB FUNCTION
// =========================================================================

if (!function_exists('chiffre_breadcrumbs')) {
    function chiffre_breadcrumbs()
    {
        \NumberToWordsConverter\BreadcrumbController::render();
    }
}
