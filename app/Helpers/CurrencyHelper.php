<?php

if (!function_exists('currency_symbol')) {
    /**
     * Get the current user's currency symbol
     *
     * @return string
     */
    function currency_symbol()
    {
        if (auth()->check()) {
            return auth()->user()->currency_symbol ?? '€';
        }
        return '€';
    }
}

if (!function_exists('currency_code')) {
    /**
     * Get the current user's currency code
     *
     * @return string
     */
    function currency_code()
    {
        if (auth()->check()) {
            return auth()->user()->currency_code ?? 'PHP';
        }
        return 'EUR';
    }
}

if (!function_exists('format_currency')) {
    /**
     * Format a number with the user's currency
     *
     * @param float $amount
     * @param bool $showCode
     * @return string
     */
    function format_currency($amount, $showCode = false)
    {
        $symbol = currency_symbol();
        $code = currency_code();
        $formatted = number_format($amount, 2);
        
        if ($showCode) {
            return $symbol . $formatted . ' ' . $code;
        }
        
        return $symbol . $formatted;
    }
}
