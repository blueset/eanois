<?php

namespace App\Http\Middleware;

use Closure;
use App\Setting;

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
        config(['eanois.stage' => $stage]);
        if ($stage == 'backend'){
            \Theme::set(Setting::getConfig('admin_theme'));
            
        } else {
            \Theme::set(Setting::getConfig('theme'));
        }
        return $next($request);
    }
}
