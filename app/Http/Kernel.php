<?php namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The application's global HTTP middleware stack.
     *
     * @var array
     */
    protected $middleware = [
        'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
        'Illuminate\Cookie\Middleware\EncryptCookies',
        'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
        'Illuminate\Session\Middleware\StartSession',
        'Illuminate\View\Middleware\ShareErrorsFromSession',
        'App\Http\Middleware\VerifyCsrfToken',
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'authenticated' => 'App\Http\Middleware\RedirectIfAuthenticated',
        'permissions' => 'App\Http\Middleware\Permissions',
        'force_https' => 'App\Http\Middleware\ForceHttps',
        'last_activity' => 'App\Http\Middleware\LastActivity',
        'locale' => 'App\Http\Middleware\Locale',
        'contacts' => 'App\Http\Middleware\Contacts'
    ];

}
