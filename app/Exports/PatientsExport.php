<?php

namespace App\Exports;

use App\Models\Patient;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class PatientsExport implements FromView
{
    public function view() :View
    {
        $patient_tickets_id  = 'patients.id = tickets.patient_id';
        $marketing_sources_id = 'patients.how_did_hear_about_clinic = marketing_sources.id';
        $visit =        DB::raw("(SELECT COUNT(*) FROM tickets WHERE $patient_tickets_id ) as visits ");
        $first_visit=   DB::raw("(SELECT DATE_FORMAT(tickets.date,'%m/%d/%Y') FROM tickets WHERE $patient_tickets_id ORDER BY tickets.date DESC LIMIT 1 ) as first_visit ");
        $last_visit=    DB::raw("(SELECT DATE_FORMAT(tickets.date,'%m/%d/%Y') FROM tickets WHERE $patient_tickets_id ORDER BY tickets.date ASC LIMIT 1 ) as last_visit ");
        $total_sales =  DB::raw("IFNULL((SELECT SUM(total) FROM tickets WHERE $patient_tickets_id ),0) as total_sales ");
        $paid =         DB::raw("IFNULL((SELECT SUM(tickets.amount_paid_during_office_visit) FROM tickets WHERE $patient_tickets_id ) ,0 ) as paid");
        $lead_source =  DB::raw("(SELECT marketing_sources.description FROM marketing_sources WHERE  $marketing_sources_id ) as lead_source");
        $payments =  DB::raw("IFNULL(
                                (
                                SELECT SUM(payments.amount)
                                FROM tickets
                                INNER JOIN payments ON tickets.id = payments.ticket_id
                                WHERE $patient_tickets_id

                                )
            ,0) as payments");
        $patient =      'patients.*' ;
        $patients =Patient::select($patient,$visit,$first_visit,$last_visit,$paid,$total_sales,$lead_source,$payments )
        ->where('location_id',session('current_location')->id)
        ->get();
        $res['patients'] = $patients;
        return view('exports.patients',$res);
    }
}
