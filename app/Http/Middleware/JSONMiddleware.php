<?php

namespace App\Http\Middleware;

use Closure;

class JSONMiddleware
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
        if ($request->wantsJson()){
            return $next($request);
        }
        return response("Only JSON is available.", 406);
    }
}
