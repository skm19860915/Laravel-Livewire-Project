<?php

namespace App\Http\Controllers;

use App\Models\LogExcel;
use Illuminate\Http\Request;

class LogExcelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $logs = LogExcel::with('user')->where('location_id',session('current_location')->id)->get();
        //  dd($logs->take(1)->toArray());
         $res['logs']= $logs;
         $res['page_name']='Export Log';
         $res['card_title']='Export Log';
        return view('log.index',$res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LogExcel  $logExcel
     * @return \Illuminate\Http\Response
     */
    public function show(LogExcel $logExcel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LogExcel  $logExcel
     * @return \Illuminate\Http\Response
     */
    public function edit(LogExcel $logExcel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LogExcel  $logExcel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LogExcel $logExcel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LogExcel  $logExcel
     * @return \Illuminate\Http\Response
     */
    public function destroy(LogExcel $logExcel)
    {
        //
    }
}
