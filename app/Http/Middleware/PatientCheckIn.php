<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PatientCheckIn
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
        $auth = auth()->user();
        // if is superadmin 1 or admin 2  or manager 3 or Sales/Front Desk 4
        if(!in_array($auth->role_id,[1,2,3,4])) return abort(403);
        return $next($request);
    }
}
