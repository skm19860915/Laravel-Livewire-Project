<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CanEdit
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
        $user = User::find($request->id);

        if ( $user) {
            // cant edit super admin
            if ($auth->role_id != User::ROLE_SUPER_ADMIN && $auth->role_id != User::ROLE_ADMIN) {
                return abort(403);
            }

            // if admin
            if($auth->role_id === User::ROLE_ADMIN) {
                $authLocations = $auth->locations->pluck('id')->toArray();
                $userLocations = $user->locations->pluck('id')->toArray();
                $x = array_intersect($authLocations,$userLocations);
                // if target user doesn't have same locations as Admin then cannot  edit
                if (!count($x)) {
                    return abort(403);
                }
            }
        }

        return $next($request);

    }
}
