<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Receivable;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Payment $payment)
    {
        //
        $res['page_name'] = 'Receivables';
        $res['page_info'] = 'Receivables';
        $todaydue = Receivable::getTodayDue();
        $overdue = Receivable::getOverDue();
        $res['overdue'] = $overdue;
        $res['todaydue'] = $todaydue;
        return view('receivable.index',$res);
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
     * @param  \App\Models\Receivable  $receivable
     * @return \Illuminate\Http\Response
     */
    public function show(Receivable $receivable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Receivable  $receivable
     * @return \Illuminate\Http\Response
     */
    public function edit(Receivable $receivable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Receivable  $receivable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Receivable $receivable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Receivable  $receivable
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receivable $receivable)
    {
        //
    }
}
