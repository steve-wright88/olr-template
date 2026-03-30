<?php

if (! function_exists('t')) {
    /**
     * Auto-translate text to the current locale.
     * For DB content (pages, posts, settings) that isn't in lang files.
     */
    function t(string $text): string
    {
        $locale = app()->getLocale();

        if ($locale === 'en' || empty(trim(strip_tags($text)))) {
            return $text;
        }

        return app(\App\Services\TranslationService::class)->translate($text, $locale);
    }
}
