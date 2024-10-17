<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureApiEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user('api') ||
            ($request->user('api') instanceof MustVerifyEmail &&
            ! $request->user('api')->hasVerifiedEmail())) {

            return  response()->json([
                'message' => __('email.email_not_verified'),
            ], 401);
        }

        return $next($request);
    }
}
