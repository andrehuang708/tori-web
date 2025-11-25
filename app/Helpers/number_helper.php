<?php

if (!function_exists('number_to_currency')) {
    function number_to_currency($number, $currency = 'IDR', $locale = null, $fraction = 0)
    {
        // Format number with thousands separator
        $formatted = number_format($number, $fraction, ',', '.');
        
        // Add currency symbol
        switch (strtoupper($currency)) {
            case 'USD':
                return '$' . $formatted;
            case 'EUR':
                return '€' . $formatted;
            case 'GBP':
                return '£' . $formatted;
            case 'IDR':
            default:
                return 'Rp ' . $formatted;
        }
    }
}

if (!function_exists('number_to_size')) {
    function number_to_size($num, $precision = 1, $locale = null)
    {
        // Format file size
        $kb = 1024;
        $mb = $kb * 1024;
        $gb = $mb * 1024;
        $tb = $gb * 1024;

        if ($num < $kb) {
            return $num . ' B';
        }
        if ($num < $mb) {
            return round($num / $kb, $precision) . ' KB';
        }
        if ($num < $gb) {
            return round($num / $mb, $precision) . ' MB';
        }
        if ($num < $tb) {
            return round($num / $gb, $precision) . ' GB';
        }
        
        return round($num / $tb, $precision) . ' TB';
    }
}

if (!class_exists('NumberFormatter', false) && !extension_loaded('intl')) {
    class NumberFormatter
    {
        private $locale;
        private $style;
        
        public function __construct($locale, $style)
        {
            $this->locale = $locale;
            $this->style = $style;
        }
        
        public function format($number, $type = null)
        {
            if ($this->style === NumberFormatter::CURRENCY) {
                return 'Rp ' . number_format($number, 0, ',', '.');
            }
            return number_format($number, 0, ',', '.');
        }
        
        public function formatCurrency($value, $currency)
        {
            return 'Rp ' . number_format($value, 0, ',', '.');
        }
        
        public const DECIMAL = 1;
        public const CURRENCY = 2;
        public const PERCENT = 3;
        public const SCIENTIFIC = 4;
        public const SPELLOUT = 5;
        public const ORDINAL = 6;
        public const DURATION = 7;
        public const PATTERN_DECIMAL = 0;
        public const ROUND_DOWN = 0;
        public const ROUND_UP = 1;
        public const ROUND_CEILING = 2;
        public const ROUND_FLOOR = 3;
        public const ROUND_HALFUP = 4;
        public const ROUND_HALFDOWN = 5;
        public const ROUND_HALFEVEN = 6;
    }
}