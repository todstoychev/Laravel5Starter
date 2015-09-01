<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Support\Facades\Auth;

class Permissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $action = $request->route()->getActionName();
        $roleIds = (Auth::user()) ? Auth::user()->getRoleIds() : null;
        $permission = Permission::getPermission($action, $roleIds);

        if ($permission) {
            return $next($request);
        } else {
            flash()->warning(trans('users.access_denied'));

            return redirect()->guest('/');
        }
    }
}
