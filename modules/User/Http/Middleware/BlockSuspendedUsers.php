<?php

namespace Modules\User\Http\Middleware;

use App\Exceptions\Handler;
use Closure;
use Illuminate\Http\Request;

class BlockSuspendedUsers
{
    /**
     * @var array
     */
    protected $allowedRoutes = [
        'api.logout',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route()->getName();

        if (in_array($route, $this->allowedRoutes)) {
            return $next($request);
        }

        if (($user = $request->user())
            && $user->suspended) {
            return response([
                'message' => __('general.banned'),
                'reason' => Handler::ERROR_REASON_BANNED
            ], 403);
        }

        return $next($request);
    }
}
