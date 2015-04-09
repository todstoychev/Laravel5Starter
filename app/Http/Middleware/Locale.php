<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Locale {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $settings = Settings::getAll();

        if (!Session::has('locale')) {
            if (Auth::user() && Auth::user()->locale) {
                Session::put('locale', Auth::user()->locale);
            } elseif (Auth::user() && !Auth::user()->locale) {
                Auth::user()->locale = $settings['fallback_locale'];
                Auth::user()->save();
                Session::put('locale', $settings['fallback_locale']);
            } else {
                Session::put('locale', $settings['fallback_locale']);
            }
        }
        
        if (count($settings['locales']) == 1) {
            Auth::user()->locale = $settings['fallback_locale'];
            Auth::user()->save();
            Session::put('locale', $settings['fallback_locale']);
        }
        
        app()->setLocale(Session::get('locale'));

        return $next($request);
    }

}
