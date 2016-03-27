<?php

namespace App\Http\Middleware;

use Closure;
use App\Config;

class ThemeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $stage)
    {
        if ($stage == 'backend'){
            \Theme::set(Config::getConfig('admin_theme'));
        } else {
            \Theme::set(Config::getConfig('theme'));
        }
        return $next($request);
    }
}
