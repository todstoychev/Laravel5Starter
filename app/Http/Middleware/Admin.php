<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!Auth::user()->hasRole('admin')) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                flash()->warning(trans('users.access_denied'));
                
                return redirect()->guest('/');
            }
        }
        
        return $next($request);
    }

}
