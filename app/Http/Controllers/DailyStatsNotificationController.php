<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Ticket;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Mail\DailyNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\DailyStatsNotification;

class DailyStatsNotificationController extends Controller
{
    //,evan@themansclinic.com,justin@themansclinic.com,kevin@themansclinic.com
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $res['page_name'] = 'Daily Stats Notification';
        $res['card_title'] = 'Daily Stats Notification';
        $res['emails'] = DailyStatsNotification::all();
        $res['emails'] = implode(',', $res['emails']->pluck('email')->toArray());
        return view('notification.create', $res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $emails = $request->emails;
        $emails = explode(',', $emails);
        foreach ($emails as $e) {
            $dsn = new DailyStatsNotification();
            if (filter_var($e, FILTER_VALIDATE_EMAIL)) {
                $dsn->email = trim($e);
                if ($request->action == 'test') {
                    try {
                        Mail::to($e)->send(new DailyNotification($e));
                    } catch (Throwable $e) {
                        logger()->error($e->getMessage());
                    }
                } else {
                    //Save email if doesn't exist
                    $exists = DailyStatsNotification::where('email', $e)->exists();
                    if (!$exists) $dsn->save();

                    //Delete all emails not included in the list of saved emails
                    DailyStatsNotification::whereNotIn('email', $emails)->delete();
                }
            } else {

                return redirect()->route('notification.create')->with('error', 'Invalid email "' . $e . '"');
            };
        }
        if ($request->action == 'test') return redirect()->route('notification.create')->with('success', 'Notification sent successfully.');
        return redirect()->route('notification.create')->with('success', 'Notification will be sent at 11PM UTC');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DailyStatsNotification  $dailyStatsNotification
     * @return \Illuminate\Http\Response
     */
    public function show(DailyStatsNotification $dailyStatsNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DailyStatsNotification  $dailyStatsNotification
     * @return \Illuminate\Http\Response
     */
    public function edit(DailyStatsNotification $dailyStatsNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DailyStatsNotification  $dailyStatsNotification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DailyStatsNotification $dailyStatsNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DailyStatsNotification  $dailyStatsNotification
     * @return \Illuminate\Http\Response
     */
    public function destroy(DailyStatsNotification $dailyStatsNotification)
    {
        //
    }
}
