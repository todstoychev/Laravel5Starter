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
        $settings = Settings::getAll();

        if (!Session::has('locale')) {
            Session::put('locale', $settings['fallback_locale']);
        }
           
        app()->setLocale(Session::get('locale'));

        return $next($request);
    }

}
