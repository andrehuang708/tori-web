<?php

if (!class_exists('Locale', false)) {
    class Locale
    {
        private static $default = 'en';
        
        public static function setDefault($locale)
        {
            self::$default = $locale;
            return true;
        }
        
        public static function getDefault()
        {
            return self::$default;
        }
        
        public static function acceptFromHttp($header)
        {
            return self::$default;
        }
        
        public static function getPrimaryLanguage($locale)
        {
            return self::$default;
        }
        
        public static function getDisplayLanguage($locale, $inLocale = null)
        {
            return 'English';
        }
    }
}
