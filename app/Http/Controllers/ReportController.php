<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\MarketingLocation;
use App\Models\MarketingSource;
use App\Models\Patient;
use App\Models\Report;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    public function financial(Request $request, Ticket $ticket)
    {
        $sqlDateFormat = "Y-m-d";
        // $start = now()->format($sqlDateFormat);
        // $end = now()->addMonth()->format($sqlDateFormat);
        $start = now()->firstOfMonth()->format("Y-m-d");
        $end   = now()->lastOfMonth()->format("Y-m-d");
        if ($request->method() == 'POST') {
            try {
                $start = Carbon::parse($request->from)->format($sqlDateFormat);
                $end = Carbon::parse($request->to)->format($sqlDateFormat);
            } catch (Exception $e) {
                return back()->with('error', 'Invalid date.');
            }
        }


        $res['page_name'] = "Reports";
        $res['page_info'] = "Financials";

        $tickets  = $ticket->unassignedTickets($start, $end);
        $counselorsReport = Report::counselorReport($start, $end);
        // dd($tickets->toArray(),$counselors->toArray());
        // dd($counselors);

        $res['_start'] = Carbon::parse($start)->format(config('app.date_format'));
        $res['_end'] = Carbon::parse($end)->format(config('app.date_format'));
        $res['start'] = Carbon::parse($start)->format('F d-Y');
        $res['end'] = Carbon::parse($end)->format('F d-Y');
        $res['counselors'] = $counselorsReport['counselors'];
        $res['totals'] = $counselorsReport['totals'];
        $res['tickets'] = $tickets;
        return view('report.finance.index', $res);
    }

    public function marketing(Request $request)
    {
        $sqlDateFormat = "Y-m-d";

        // $start = now()->format($sqlDateFormat);
        // $end = now()->addMonth()->format($sqlDateFormat);
        $start = now()->firstOfMonth()->format("Y-m-d");
        $end = now()->lastOfMonth()->format("Y-m-d");
        if ($request->method() == 'POST') {
            try {
                $start = Carbon::parse($request->from)->format($sqlDateFormat);
                $end = Carbon::parse($request->to)->format($sqlDateFormat);
            } catch (Exception $e) {
                return back()->with('error', 'Invalid date.');
            }
        }
        $res['page_name'] = "Reports";
        $res['page_info'] = "Marketing";



        $marketing_sources = Report::marketingReport($start, $end);
        //$totals['ticket_count'] = 0;
        $totals['paid_amount'] = 0;
        $totals['avg_paid_amount'] = 0;
        $totals['avg_total_amount'] = 0;
        $totals['avg_age'] = 0;
        $totals['booked'] = 0;
        $totals['reschedule'] = 0;
        $totals['cancel'] = 0;
        $totals['confirm'] = 0;
        $totals['shows'] = 0;
        $totals['trimix'] = 0;
        $totals['sublingual'] = 0;
        $totals['testosterones'] = 0;
        $totals['doses_trimix'] = 0;
        $totals['doses_sublingual'] = 0;
        $totals['doses_testosterones'] = 0;
        //GET TOTALS
        foreach ($marketing_sources as $m) {

            $totals['paid_amount'] += (float)$m->paid_amount;
            $totals['avg_paid_amount'] += (float)$m->avg_paid_amount;
            $totals['avg_total_amount'] += (float)$m->avg_total_amount;
            $totals['avg_age'] += (float)$m->avg_age;
            $totals['booked'] += (float)$m->booked;
            $totals['reschedule'] += (float)$m->reschedule;
            $totals['cancel'] += (float)$m->cancel;
            $totals['confirm'] += (float)$m->confirm;
            $totals['shows'] += (float)$m->shows;
            //$totals['ticket_count']+=(float)$m->ticket_count;
            $totals['trimix'] += (float)$m->trimix;
            $totals['sublingual'] += (float)$m->sublingual;
            $totals['testosterones'] += (float)$m->testosterones;
            $totals['doses_trimix'] += (float)$m->doses_trimix;
            $totals['doses_sublingual'] += (float)$m->doses_sublingual;
            $totals['doses_testosterones'] += (float)$m->doses_testosterones;
        }
        // dd($totals,$marketing_sources)\
        $totals['avg_paid_amount'] = $totals['avg_paid_amount'] / ($marketing_sources->count() > 0 ? $marketing_sources->count() : 1);
        $totals['avg_total_amount'] = $totals['avg_total_amount'] / ($marketing_sources->count() > 0 ? $marketing_sources->count() : 1);
        $totals['avg_age'] = $totals['avg_age'] / ($marketing_sources->count() > 0 ? $marketing_sources->count() : 1);
        $res['_start'] = Carbon::parse($start)->format(config('app.date_format'));
        $res['_end'] = Carbon::parse($end)->format(config('app.date_format'));
        $res['start'] = Carbon::parse($start)->format('F d-Y');
        $res['end'] = Carbon::parse($end)->format('F d-Y');
        $res['marketing_sources'] = $marketing_sources;
        $res['totals'] = $totals;
        return view('report.marketing.index', $res);
    }

    public function salesByProduct(Request $request)
    {
        $sqlDateFormat = "Y-m-d";

        $start = now()->firstOfMonth()->format("Y-m-d");
        $end   = now()->lastOfMonth()->format("Y-m-d");
        if ($request->method() == 'POST') {
            try {
                $start = Carbon::parse($request->from)->format($sqlDateFormat);
                $end = Carbon::parse($request->to)->format($sqlDateFormat);
            } catch (Exception $e) {
                return back()->with('error', 'Invalid date.');
            }
        }


        $res['page_name'] = "Reports";
        $res['page_info'] = "Sales By Product";

        $salesByProduct = Report::salesByProduct($start, $end);

        $res['_start'] = Carbon::parse($start)->format(config('app.date_format'));
        $res['_end'] = Carbon::parse($end)->format(config('app.date_format'));
        $res['start'] = Carbon::parse($start)->format('F d-Y');
        $res['end'] = Carbon::parse($end)->format('F d-Y');
        $res['detail'] = $salesByProduct['detail'];
        $res['product_totals'] = $salesByProduct['product_totals'];
        $res['totals'] = $salesByProduct['totals'];
        $res['total_details'] = $salesByProduct['total_details'];

        return view('report.sales-by-product.index', $res);
    }


    public function marketingTrend(Request $request)
    {
        if (empty($request['type']) || isset($request['type']) && $request['type'] == 'WoW') {
            $thisPeriodStart = Carbon::now()->subDays(7)->format('Y-m-d');
            $priorPeriodStart = Carbon::now()->subDays(14)->format('Y-m-d');
        } else {
            $thisPeriodStart = Carbon::now()->startOfMonth()->format('Y-m-d');
            $priorPeriodStart = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        }

        $res['page_name'] = "Reports";
        $res['page_info'] = "Marketing Trend";

        $trendReport = Report::marketingTrendReport($thisPeriodStart, $priorPeriodStart);

        $res['thisPeriodStart'] = Carbon::parse($thisPeriodStart)->format('m/d');
        $res['priorPeriodStart'] = Carbon::parse($priorPeriodStart)->format('m/d');
        $res['sources'] = $trendReport;

        return view('report.marketing-trend.index', $res);
    }
}
