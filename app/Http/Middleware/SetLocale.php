<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = config('translatable.locales', ['en', 'ur']);
        $flatLocales = [];

        // Flatten nested locales (e.g., 'ur' => ['PK', 'RO']) to ['en', 'ur-PK', 'ur-RO', ...]
        foreach ($supportedLocales as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $variant) {
                    $flatLocales[] = $key . '-' . $variant;
                }
            } else {
                $flatLocales[] = is_int($key) ? $value : $key;
            }
        }

        if ($request->is('api/*')) {
            $locale = $request->header('X-Language');
        } else {
            // First, check the session
            $locale = Session::get('locale');

            // If not in session, check authenticated user's preference
            if (!$locale && Auth::check()) {
                $locale = Auth::user()->preferred_language;
            }

            // If still not set, use the browser's preferred language
            if (!$locale) {
                $locale = $request->getPreferredLanguage($flatLocales);
            }
        }

        // Ensure the locale is supported, otherwise fall back to default
        if (!in_array($locale, $flatLocales)) {
            $locale = config('app.fallback_locale', 'en');
        }

        // Set the application locale
        App::setLocale($locale);

        // Update session
        Session::put('locale', $locale);

        return $next($request);
    }
}
