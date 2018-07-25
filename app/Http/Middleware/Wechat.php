<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class Wechat
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
        if(!starts_with(request()->headers->get('referer'), config('app.wechat_uri_prefix'))) {
            dd(request()->headers->get('referer'));
            abort(404);
        }
        
        return $next($request);
    }
}
