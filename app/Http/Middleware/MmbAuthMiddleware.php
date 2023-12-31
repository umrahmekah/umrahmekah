<?php

namespace App\Http\Middleware;

use Closure;

class MmbAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $superadmin = app('session')->get('gid');

        if ('1' != $superadmin and '2' != $superadmin) {
            return redirect('dashboard')->with('msgstatus', 'error')->with('messagetext', 'Sorry! This is not your page');
        }

        return $next($request);
    }
}
