<?php

namespace Modules\Core\Http\Middleware;

use Closure;

class SetRequestTypeToJson
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept',
            'application/json');

        return $next($request);
    }
}
