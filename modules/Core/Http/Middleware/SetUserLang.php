<?php

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetUserLang
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
        if ($request->header('Accept-Language')) {
            app()->setLocale($request->header('Accept-Language'));
        }
        if ($request->user() && $request->user()->lang) {
            app()->setLocale($request->user()->lang);
        }
        return $next($request);
    }
}
