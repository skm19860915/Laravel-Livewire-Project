<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentsExport implements FromView
{
    public function view(): View
    {
        $paymetns = Payment::with('ticket')->get()->where('ticket.location_id',session('current_location')->id);
        foreach($paymetns as $p)
        {

            $p->patient_name = $p->ticket->patient->name(', ');
            $p->visit_id = $p->ticket->id;
            $p->input_by = $p->inputBy?$p->inputBy->name(', ') : "";
        }
        $res['payments'] = $paymetns;
        return view('exports.paymetns',$res);
    }
}
