<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported application locales.
     */
    protected array $supportedLocales = ['ar', 'en', 'de', 'tr', 'ur', 'ms', 'fr'];

    /**
     * Handle an incoming request.
     * Priority: user preference → cookie → browser Accept-Language header → fallback ('ar')
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);

        App::setLocale($locale);

        // Persist the resolved locale in a cookie for subsequent requests
        $response = $next($request);

        return $response->withCookie(cookie()->forever('locale', $locale));
    }

    /**
     * Resolve the best matching locale from available sources.
     */
    protected function resolveLocale(Request $request): string
    {
        // 1. Authenticated user's stored preference
        if (Auth::check()) {
            $userLocale = Auth::user()->locale ?? null;
            if ($userLocale && in_array($userLocale, $this->supportedLocales)) {
                return $userLocale;
            }
        }

        // 2. Cookie value
        $cookieLocale = $request->cookie('locale');
        if ($cookieLocale && in_array($cookieLocale, $this->supportedLocales)) {
            return $cookieLocale;
        }

        // 3. Browser Accept-Language header (first matching supported locale)
        $acceptLanguage = $request->getPreferredLanguage($this->supportedLocales);
        if ($acceptLanguage && in_array($acceptLanguage, $this->supportedLocales)) {
            return $acceptLanguage;
        }

        // 4. Application fallback
        return config('app.locale', 'ar');
    }
}
