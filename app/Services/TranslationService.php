<?php

namespace App\Services;

use App\Models\Translation;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationService
{
    protected GoogleTranslate $translator;

    public function __construct()
    {
        $this->translator = new GoogleTranslate();
        $this->translator->setSource('en');
    }

    public function translate(string $text, string $locale): string
    {
        if ($locale === 'en' || empty(trim(strip_tags($text)))) {
            return $text;
        }

        $hash = hash('sha256', $text);

        $cached = Translation::where('hash', $hash)
            ->where('locale', $locale)
            ->first();

        if ($cached) {
            return $cached->translated;
        }

        try {
            $this->translator->setTarget($locale);

            // For HTML content, translate in chunks to preserve tags
            if ($text !== strip_tags($text)) {
                $translated = $this->translateHtml($text, $locale);
            } else {
                $translated = $this->translator->translate($text);
            }

            Translation::create([
                'hash' => $hash,
                'locale' => $locale,
                'source' => $text,
                'translated' => $translated,
            ]);

            return $translated;
        } catch (\Throwable $e) {
            report($e);
            return $text;
        }
    }

    protected function translateHtml(string $html, string $locale): string
    {
        // Split HTML into translatable text segments and tags
        // Use a simple approach: translate the whole thing, Google handles HTML
        $this->translator->setTarget($locale);

        // Google Translate handles HTML reasonably well
        return $this->translator->translate($html);
    }
}
