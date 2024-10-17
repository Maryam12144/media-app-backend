<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailMustNotBeVerified
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (($user = Auth::user()) and $user->hasVerifiedEmail()) {
            return response([
                'message' => __('messages.verify-email.already-verified')
            ], 400);
        }

        return $next($request);
    }
}
