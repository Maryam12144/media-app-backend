<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\Role;

class MustBeAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (($user = Auth::user()) && $user->hasRole(Role::ROLE_ADMIN)) {
            return $next($request);
        }else if(($user = Auth::user()) && $user->hasRole(Role::ROLE_NORMAL_ADMIN)){
            return $next($request);
        }

        abort(403);
        
        // if (!($user = Auth::user()) || !$user->hasRole(Role::ROLE_ADMIN)) {
        //     abort(403);
        // }

        // return $next($request);
    }
}
