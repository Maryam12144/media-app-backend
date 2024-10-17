<?php

namespace App\Http;

use Illuminate\Routing\Router;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use Illuminate\Foundation\Application;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\VerifyCsrfToken;
use Modules\Core\Http\Middleware\AllowCors;
use Illuminate\Auth\Middleware\Authorize;
use Modules\Core\Http\Middleware\SetUserLang;
use Illuminate\Auth\Middleware\Authenticate;
use App\Http\Middleware\PermissionMiddleware;
use Illuminate\Session\Middleware\StartSession;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Modules\Core\Http\Middleware\SetRequestTypeToJson;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Modules\Core\Http\Middleware\EmailMustNotBeVerified;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Modules\Core\Http\Middleware\MustBeAdmin;
use Modules\Core\Http\Middleware\SanctumGuardSwitcherForApi;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Modules\User\Http\Middleware\BlockSuspendedUsers;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Modules\Core\Http\Middleware\EnsureApiEmailIsVerified;
use Illuminate\Session\Middleware\AuthenticateSession;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        // \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \Fruitcake\Cors\HandleCors::class,

    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            AuthenticateSession::class, //
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
        ],

        'api' => [
            'throttle:6000,1',
            'bindings',
            SetUserLang::class,
            SanctumGuardSwitcherForApi::class,
            SetRequestTypeToJson::class,
            BlockSuspendedUsers::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'auth.basic' => AuthenticateWithBasicAuth::class,
        'bindings' => SubstituteBindings::class,
        'can' => Authorize::class,
        'guest' => RedirectIfAuthenticated::class,
        'throttle' => ThrottleRequests::class,
        'email-not-verified' => EmailMustNotBeVerified::class,
        'permission' => PermissionMiddleware::class,
        'admin' => MustBeAdmin::class,
        'verified' => EnsureEmailIsVerified::class,
        'verified-email' => EnsureApiEmailIsVerified::class,
    ];

    /**
     * Kernel constructor.
     *
     * @param Application $app
     * @param Router $router
     */
    public function __construct(Application $app, Router $router)
    {
        /*
         * Because we are using a custom middleware,
         * we want to ensure it's executed early in the stack.
         */
        array_unshift($this->middlewarePriority,
            SanctumGuardSwitcherForApi::class);
        array_unshift($this->middlewarePriority,
            SetRequestTypeToJson::class);
        array_unshift($this->middlewarePriority,
            AllowCors::class);

        parent::__construct($app, $router);
    }
}
