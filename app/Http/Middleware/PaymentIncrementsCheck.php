<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PaymentIncrementsCheck
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
        $req  = $request->route()->parameters();
        $ticket = $req['ticket'];
        if(!$ticket->payment_increments && !$ticket->month_plan )
        {
          return abort(403);
        }
        return $next($request);
    }
}
