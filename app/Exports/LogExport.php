<?php

namespace App\Exports;

use App\Models\LogExcel;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LogExport implements FromView
{
    public function view() :View
    {

        $logs = LogExcel::with('user')->where('location_id',session('current_location')->id)->get();
         $res['logs']= $logs;
        return view('exports.log',$res);
    }
}
