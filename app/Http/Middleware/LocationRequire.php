<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LocationRequire
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
         if(!session('current_location'))  {
            // if no primary location or no location selected
            // select any user location  can have it
            $auth = auth()->user();
            $firstLocation = $auth->locations->first();
            $firstLocation ? session(['current_location' => $firstLocation]) : '';
        } ;
        return $next($request);
    }
}
