<?php

namespace App\Services;

class EmbedService
{
    /**
     * Detect video platform from URL and return embed HTML.
     */
    public static function embed(string $url, int $width = 560, int $height = 315): ?string
    {
        if ($videoId = self::youtubeId($url)) {
            return '<iframe width="'.$width.'" height="'.$height.'" src="https://www.youtube.com/embed/'.$videoId.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;aspect-ratio:16/9;height:auto;border-radius:8px;"></iframe>';
        }

        if ($fbUrl = self::facebookVideoUrl($url)) {
            $encoded = urlencode($fbUrl);

            return '<iframe src="https://www.facebook.com/plugins/video.php?href='.$encoded.'&show_text=false&width='.$width.'" width="'.$width.'" height="'.$height.'" style="width:100%;aspect-ratio:16/9;height:auto;border:none;overflow:hidden;border-radius:8px;" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>';
        }

        // Generic: return a link
        return null;
    }

    public static function youtubeId(string $url): ?string
    {
        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/live\/([a-zA-Z0-9_-]+)/',
            '/youtu\.be\/([a-zA-Z0-9_-]+)/',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $m)) {
                return $m[1];
            }
        }

        return null;
    }

    public static function facebookVideoUrl(string $url): ?string
    {
        if (str_contains($url, 'facebook.com') && str_contains($url, 'video')) {
            return $url;
        }

        return null;
    }

    public static function isLivestreamUrl(string $url): bool
    {
        return str_contains($url, 'youtube.com/live/')
            || str_contains($url, 'youtu.be/')
            || str_contains($url, 'youtube.com/watch')
            || (str_contains($url, 'facebook.com') && str_contains($url, 'video'));
    }
}
