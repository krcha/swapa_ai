<?php

namespace App\Helpers;

class LanguageHelper
{
    /**
     * Get available languages
     */
    public static function getAvailableLanguages()
    {
        return [
            'en' => [
                'name' => 'English',
                'flag' => '🇺🇸',
                'code' => 'en'
            ],
            'sr' => [
                'name' => 'Srpski',
                'flag' => '🇷🇸',
                'code' => 'sr'
            ]
        ];
    }

    /**
     * Get current language
     */
    public static function getCurrentLanguage()
    {
        $locale = app()->getLocale();
        $languages = self::getAvailableLanguages();
        return $languages[$locale] ?? $languages['en'];
    }

    /**
     * Get language switch URL
     */
    public static function getLanguageUrl($locale)
    {
        $currentUrl = request()->fullUrl();
        $parsedUrl = parse_url($currentUrl);
        
        // Remove existing lang parameter
        $query = [];
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $query);
            unset($query['lang']);
        }
        
        // Add new lang parameter
        $query['lang'] = $locale;
        
        // Rebuild URL
        $newUrl = ($parsedUrl['scheme'] ?? 'http') . '://' . ($parsedUrl['host'] ?? 'localhost');
        if (isset($parsedUrl['port'])) {
            $newUrl .= ':' . $parsedUrl['port'];
        }
        $newUrl .= $parsedUrl['path'] ?? '/';
        
        if (!empty($query)) {
            $newUrl .= '?' . http_build_query($query);
        }
        
        return $newUrl;
    }
}
