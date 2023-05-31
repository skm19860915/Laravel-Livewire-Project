<?php

namespace App\Http\Controllers;

use App\Exports\LogExport;
use App\Exports\PatientsExport;
use App\Exports\PaymentsExport;
use App\Exports\SalesByProductExport;
use App\Exports\SchedulesExport;
use App\Exports\TicketsExport;
use App\Models\LogExcel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportsController extends Controller
{
    //
    public function patients(LogExcel $log)
    {
        $log->_store(['user_id' => auth()->user()->id , 'type' => 'Patients','location_id' => session('current_location')->id]);
       return Excel::download(new PatientsExport,"patient List ".now()->format('Y-m-d').".xlsx");
    }

    public function tickets(LogExcel $log)
    {
        $log->_store(['user_id' => auth()->user()->id , 'type' => 'Tickets','location_id' => session('current_location')->id]);
       return Excel::download(new TicketsExport,"tickets List ".now()->format('Y-m-d').".xlsx");
    }

    public function schedules(LogExcel $log)
    {
        $log->_store(['user_id' => auth()->user()->id , 'type' => 'Schedules' ,'location_id' => session('current_location')->id]);
       return Excel::download(new SchedulesExport,"Schedule List ".now()->format('Y-m-d').".xlsx");
    }

    public function payments(LogExcel $log)
    {
        $log->_store(['user_id' => auth()->user()->id , 'type' => 'Payments','location_id' => session('current_location')->id]);
       return Excel::download(new PaymentsExport,"Payments List ".now()->format('Y-m-d').".xlsx");
    }

    public function salesByProduct(LogExcel $log, Request $request)
    {
        $log->_store(['user_id' => auth()->user()->id , 'type' => 'Sales By Product','location_id' => session('current_location')->id]);
       return (new SalesByProductExport)->start($request['from'])->end($request['to'])->download("Sales By Product List ".now()->format('Y-m-d').".xlsx");
    }

    public function log()
    {
       return Excel::download(new LogExport,"Log List ".now()->format('Y-m-d').".xlsx");
    }
}
