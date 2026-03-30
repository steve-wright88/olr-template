<?php

return [
    'loft_id' => env('OLR_LOFT_ID', 104),
    'site_name' => env('OLR_SITE_NAME', 'Who Dares Wins'),
    'tagline' => env('OLR_TAGLINE', 'International One Loft Race'),
    'primary_color' => env('OLR_PRIMARY_COLOR', '#1a2332'),
    'accent_color' => env('OLR_ACCENT_COLOR', '#2788CF'),
    'logo' => env('OLR_LOGO', '/images/logo.jpg'),
    'banner' => env('OLR_BANNER', '/images/banner.jpeg'),
    'sponsor_image' => env('OLR_SPONSOR_IMAGE', '/images/sponsor.jpeg'),
    'sponsor_name' => env('OLR_SPONSOR_NAME', 'Vanrobaeys'),
    'sponsor_url' => env('OLR_SPONSOR_URL', 'https://www.vanrobaeysbelgium.com/en-GB'),
    'promo_video' => env('OLR_PROMO_VIDEO', 'https://www.youtube.com/watch?v=VSfwCwad8U8'),
    'contact_email' => env('OLR_CONTACT_EMAIL', 'who.dares.wins@aol.com'),
    'contact_phone' => env('OLR_CONTACT_PHONE', '+44 7368 307667'),
    'address' => env('OLR_ADDRESS', 'Chapel Farm, Plains Lane, Blackbrook, Derbyshire DE56 2DD'),

    'social' => [
        'facebook' => env('OLR_FACEBOOK', ''),
        'youtube' => env('OLR_YOUTUBE', ''),
        'instagram' => env('OLR_INSTAGRAM', ''),
    ],

    'features' => [
        'analysis' => true,
        'news' => true,
        'owner_tracking' => false,
    ],

    'locales' => [
        'en' => 'English',
        'nl' => 'Nederlands',
        'de' => 'Deutsch',
        'fr' => 'Français',
        'pl' => 'Polski',
        'ro' => 'Română',
        'pt' => 'Português',
        'es' => 'Español',
        'it' => 'Italiano',
        'zh' => '中文',
    ],
];
