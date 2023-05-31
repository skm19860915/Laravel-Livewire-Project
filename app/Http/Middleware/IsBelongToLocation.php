<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsBelongToLocation
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
        if(count($auth->locations) > 0){
          $authLocations = $auth->locations->pluck('id')->toArray();
          $current_location = session('current_location');
          
          //if not belong to this locations  return 403
          if(!in_array($current_location->id,$authLocations) && $auth->role->id != 1) return abort(403);          
        }
        

        return $next($request);
    }
}
