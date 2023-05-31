<?php

namespace App\Exports;

use App\Models\Patient;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PatientExport implements FromView
{
    public function view() :View
    {

        $patients =Patient::all();
        $res['patients'] = $patients;
        return view('exports.patients',$res);
    }
}
