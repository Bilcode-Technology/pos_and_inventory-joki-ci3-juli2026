<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('number_to_currency'))
{
    /**
     * Formats a number as a currency value.
     * Mimics CodeIgniter 4's number_to_currency helper function.
     *
     * @param float $num
     * @param string $currency
     * @param string|null $locale
     * @param int|null $fraction
     * @return string
     */
    function number_to_currency($num, $currency, $locale = null, $fraction = null)
    {
        // Default locale if none is provided
        if (empty($locale)) {
            $locale = 'en_US';
        }

        if (class_exists('NumberFormatter')) {
            $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);
            
            // For IDR, default to 0 fraction digits if not explicitly specified
            if ($currency === 'IDR' && $fraction === null) {
                $fraction = 0;
            }

            if ($fraction !== null) {
                $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $fraction);
            }
            
            $result = $formatter->formatCurrency($num, $currency);
            
            // Replace non-breaking spaces with normal spaces
            return str_replace("\xc2\xa0", ' ', $result);
        }

        // Fallback if NumberFormatter is not available
        if ($currency === 'IDR') {
            return 'Rp ' . number_format($num, 0, ',', '.');
        }

        return $currency . ' ' . number_format($num, 2);
    }
}
