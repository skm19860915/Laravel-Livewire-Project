<?php

namespace App\Exports;

use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\View\View ;
use Maatwebsite\Excel\Concerns\FromView;

class SchedulesExport implements FromView
{
    public function view():View
    {
        $schedules =  Schedule::where('location_id',session('current_location')->id)->get();

        foreach($schedules as $index => $s) {
            $s->patient_name = $s->patient ? $s->patient->name(', ') : null;
            $s->_createdBy  =  $s->_createdBy ? $s->_createdBy->name(', ') : null;
            $s->_updateBy  =  $s->_updateBy ? $s->_updateBy->name(', ') : null;
            $s->show = $s->getTicket->count();
            $s->Last_modified = $s->updated_at->format("m/d/Y");
            $s->date = Carbon::parse($s->date);
            $s->schedule_date  = $s->date->format('m/d/Y')." ". $s->time;
            $s->create_date  = $s->created_at->format('m/d/Y');
            $ticket = $s->getTicket->first();
            $s->description = $s->scheduleType ? $s->scheduleType->description : null;

        }
        // dd($schedules->toArray());
        $res['schedules'] = $schedules;
        return view('exports.schedules',$res);
    }
}
