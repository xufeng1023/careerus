<?php

namespace App\Http\Middleware;

use Closure;

class SuperAdmin
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
        if(!in_array(auth()->user()->email, [
            'xfeng@dreamgo.com',
            'etsui@dreamgo.com'
        ])) {
            return redirect('/admin/jobs');
        }

        return $next($request);
    }
}
