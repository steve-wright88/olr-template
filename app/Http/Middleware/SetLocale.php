<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', $request->cookie('locale', config('app.locale')));

        if (in_array($locale, array_keys(config('olr.locales', ['en' => 'English'])))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
