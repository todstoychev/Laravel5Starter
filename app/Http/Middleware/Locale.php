<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Settings;
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
        $fallback_locale = Settings::getFallBackLocale();
        $locales = Settings::getLocales();

        if (!Session::has('locale') || !in_array(Session::get('locale'), $locales)) {
            Session::put('locale', $fallback_locale);
        }
           
        app()->setLocale(Session::get('locale'));

        return $next($request);
    }

}
