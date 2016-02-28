<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Settings;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Session;

class Locale {

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Redirector
     */
    protected $redirector;

    public function __construct(Application $app, Request $request, Redirector $redirector)
    {
        $this->app = $app;
        $this->request = $request;
        $this->redirector = $redirector;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        $segments = $request->segments();
        $locale = (Session::has('locale')) ? Session::get('locale') : Settings::get('fallback_locale');

        if (!array_key_exists(0, $segments) || $segments[0] !== $locale) {
            Session::put('locale', $locale);
            $segments[0] = $locale;

            return $this->redirector->to(implode('/', $segments));
        }

        return $next($request);
    }

}
