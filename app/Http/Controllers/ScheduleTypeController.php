<?php

namespace App\Http\Controllers;

use App\Models\ScheduleType;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $res = array();
        $res['page_name'] = 'Schedule Types';
        $res['card_title'] = 'Schedule Types';
        $res['schedule_types'] = ScheduleType::all();
        return view('schedule_type.index',$res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $res = array();
        $res['page_name'] = 'Schedule Types';
        $res['page_info'] = 'Add New Schedule Types';
        $res['card_title'] = 'Add New Schedule Types';
        return view('schedule_type.create',$res);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request = $request->all();

        $scheduleType = new ScheduleType;

        Validator::make($request,$scheduleType->createScheduleTypeRoles)->validate();

        $create = $scheduleType->storeScheduleType($request);
        if($create['status']) return back()->with('success',$create['msg']);
        return back()->with('error',$create['msg']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ScheduleType  $scheduleType
     * @return \Illuminate\Http\Response
     */
    public function show(ScheduleType $scheduleType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScheduleType  $scheduleType
     * @return \Illuminate\Http\Response
     */
    public function edit(ScheduleType $scheduleType)
    {
        //
        $res = array();
        $res['page_name'] = 'Schedule Types';
        $res['page_info'] = 'Edit Schedule Type';
        $res['card_title'] = 'Edit Schedule Type';
        $res['st'] = $scheduleType;
        return view('schedule_type.edit',$res);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScheduleType  $scheduleType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ScheduleType $scheduleType)
    {
        //
        $request = $request->all();
        Validator::make($request,$scheduleType->editScheduleTypeRoles)->validate();
        $update = $scheduleType->updateScheduleType($scheduleType,$request);
        if($update['status']) return back()->with('success',$update['msg']);
        return back()->with('error',$update['msg']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScheduleType  $scheduleType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ScheduleType $scheduleType)
    {
        //
    }
}
