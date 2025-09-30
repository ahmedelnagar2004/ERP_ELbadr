<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if this is the language switch route
        if ($request->is('lang/*')) {
            return $next($request);
        }

        // Get the locale from the session, URL, or use the default
        $locale = $request->segment(1); // Get from URL first (if using localized routes)
        
        // If not in URL, get from session or default to Arabic
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = Session::get('locale', 'ar');
        }
        
        // Store the locale in the session for future requests
        if (!Session::has('locale') || Session::get('locale') !== $locale) {
            Session::put('locale', $locale);
        }
        
        // Set RTL for Arabic
        $isRTL = $locale === 'ar';
        $direction = $isRTL ? 'rtl' : 'ltr';
        
        // Store in session
        Session::put('rtl', $isRTL);
        Session::put('direction', $direction);
        
        // Set the application locale
        App::setLocale($locale);
        
        // Share with all views
        view()->share([
            'currentLocale' => $locale,
            'isRTL' => $isRTL,
            'direction' => $direction
        ]);

        return $next($request);
    }
}
