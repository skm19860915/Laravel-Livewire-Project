<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocationCheck
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
        $params = $request->route()->parameters;
        // dd($params);
        $auth = auth()->user();
        $authLocations = $auth->locations->pluck('id')->toArray();

        if(isset($params['ticket']))
        {
            $ticket = $params['ticket'];
            $ticket_location_id = $ticket->appointment->location_id;
            if(!in_array($ticket_location_id,$authLocations)) return abort(403);
        }
        if(isset($params['patient']))
        {
            $patient = $params['patient'];
            $patient_location_id = $patient->location_id;
            if(!in_array($patient_location_id,$authLocations)) return abort(403);
        }
        if(isset($params['appointment']))
        {
            $appointment = $params['appointment'];
            $appointment_location_id = $appointment->location_id;
            if(!in_array($appointment_location_id,$authLocations)) return abort(403);
        }
        if(isset($params['marketingSource']))
        {
            $marketingSource = $params['marketingSource'];
            $msLocations = $marketingSource->locations->pluck('id')->toArray();
            if(!count(array_intersect($msLocations,$authLocations))) return abort(403);
        }
        return $next($request);
    }
}
