<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Ticket;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\ScheduleType;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
  protected Carbon $start;
  protected Carbon $end;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function index()
  {
    $start = Carbon::now(session('current_location')->time_zone)->startOfMonth()->format('Y-m-d');
    $today = Carbon::now(session('current_location')->time_zone)->startOfDay()->format('Y-m-d');

    $currentLocation = session('current_location');
    $auth = auth()->user();
    $locations =  $auth->locations;

    if (auth()->user()->role_id == 2 || auth()->user()->role_id == 1) return $this->adminDashboard($currentLocation, $locations, $start, $today);

    return $this->dashboard($currentLocation, $locations, $start, $today);
  }

  public function adminDashboard(Location $currentLocation, $locations, $start, $end)
  {
    foreach ($locations as $l) {
      $location = [];
      /////=====Monthly=====///
      //Total Sold this month #done
      $monthly_sales = Ticket::whereBetween('date', [$start, $end])->where('location_id', $l->id)->sum('total');
      $location['month']['monthly_sales'] = "$" . number_format($monthly_sales, 2);


      //Total Sold / Number Tickets
      $monthly_avg_ticket = Ticket::where('location_id', $l->id)->whereBetween('date', [$start, $end])->avg('total');
      $location['month']['monthly_avg_ticket'] = "$" . number_format($monthly_avg_ticket, 2);


      //Total paid during visit this month #done
      $monthly_down_payment = Ticket::where('location_id', $l->id)->whereBetween('date', [$start, $end])->sum('amount_paid_during_office_visit');
      $location['month']['monthly_down_payment'] = "$" . number_format($monthly_down_payment, 2);


      //Total paid during visit this month / number tickets #done
      $monthly_avg_down = Ticket::where('location_id', $l->id)->whereBetween('date', [$start, $end])->avg('amount_paid_during_office_visit');
      $location['month']['monthly_avg_down'] = "$" . number_format($monthly_avg_down, 2);


      //Total patients  in this month #done
      $monthly_patients = Ticket::whereBetween('date', [$start, $end])->where('location_id', $l->id)->count();
      $location['month']['monthly_patients'] = $monthly_patients;


      //Total payments received this month #done
      $monthly_collections = Payment::join('tickets', 'payments.ticket_id', '=', 'tickets.id')
        ->whereBetween('payments.date', [$start, $end])
        ->where('refund', 0)
        ->where('tickets.location_id', $l->id)
        ->sum('amount');
      $location['month']['monthly_collections'] = "$" . number_format($monthly_collections, 2);


      /////=====Daily=====///
      //Total Sold today #done
      $daily_sales = Ticket::whereDate('date', date('Y-m-d'))->where('location_id', $l->id)->sum('total');
      $location['daily']['daily_sales'] = "$" . number_format($daily_sales, 2);


      //Total Sold / Number Tickets
      $daily_avg_ticket = Ticket::where('location_id', $l->id)->whereDate('date', date('Y-m-d'))->avg('total');
      $location['daily']['daily_avg_ticket'] = "$" . number_format($daily_avg_ticket, 2);


      //Total paid during visit today #done
      $daily_down_payment = Ticket::where('location_id', $l->id)->whereDate('date', date('Y-m-d'))->sum('amount_paid_during_office_visit');
      $location['daily']['daily_down_payment'] = "$" . number_format($daily_down_payment, 2);


      //Total paid during visit today / number tickets #done
      $daily_avg_down = Ticket::where('location_id', $l->id)->whereDate('date', date('Y-m-d'))->avg('amount_paid_during_office_visit');
      $location['daily']['daily_avg_down'] = "$" . number_format($daily_avg_down, 2);


      //Total patients  today #done
      $daily_patients = Ticket::where('location_id', $l->id)->whereDate('date', date('Y-m-d'))->count();
      $location['daily']['daily_patients'] = $daily_patients;


      //Total payments received today #done
      $daily_collections = Payment::whereDate('date', date('Y-m-d'))->with('ticket')->where('refund', 0)->get()->where('ticket.location_id', $l->id)->sum('amount');
      $location['daily']['daily_collections'] = "$" . number_format($daily_collections, 2);

      $res['admin_locations'][$l->location_name] = $location;
    }

    //Recent 10 tickets
    $recent_tickets = Ticket::where('location_id', $currentLocation->id)->orderBy('created_at', 'desc')->take(10)
      ->whereBetween('date', [$start, $end])
      ->get([
        'tickets.*',
        DB::raw('(SELECT COUNT(ticket_id) FROM ticket_products WHERE ticket_products.ticket_id = tickets.id) AS count_product'),
        DB::raw('(SELECT SUM(amount) FROM payments WHERE payments.ticket_id = tickets.id AND refund=0) AS total_payment')
      ]);
    $res['recent_tickets'] = $recent_tickets;
    $res['recent_tickets']->map(function ($t) {
      $t->background = '';
      if (($t->total_payment - $t->balanc_during_visit) == 0) $t->background = 'bg-light-success';
      if ($t->refill) $t->background = 'bg-light-warning';
      if ($t->count_product == 0) $t->background = 'bg-light-danger';

      return $t;
    });

    // Recent 10 Appointments
    $ticketsToday = Ticket::where('location_id', $currentLocation->id)
      ->whereDate('date', $end)
      ->pluck('schedule_id')->all();

    $cast = "CAST(STR_TO_DATE(time, '%l:%i %p' ) as Time) ASC";
    $recent_appointment = Schedule::where('location_id', $currentLocation->id)
      ->whereDate('date', $end)
      ->whereNotIn('schedules.id', $ticketsToday)
      ->orderByRaw($cast)
      ->take(10)->get();
    $res['recent_appointment'] = $recent_appointment;


    // schedule types (appointment types)
    $appointment_types = ScheduleType::all();
    $res['appointment_types'] = $appointment_types;
    // dd($res);
    return view('adminDashboard', $res);
  }

  public function dashboard(Location $currentLocation, $locations, $start, $end)
  {
    /////=====Monthly=====///
    //Total Sold this month #done
    $monthly_sales = Ticket::where('location_id', $currentLocation->id)
      ->whereBetween('date', [$start, $end])
      ->sum('total');
    $res['monthly_sales'] = "$" . number_format($monthly_sales, 2);


    //Total Sold / Number Tickets
    $monthly_avg_ticket = Ticket::where('location_id', $currentLocation->id)
      ->whereBetween('date', [$start, $end])
      ->where('refill', false)
      ->avg('total');
    $res['monthly_avg_ticket'] = "$" . number_format($monthly_avg_ticket, 2);


    //Total paid during visit this month #done
    $monthly_down_payment = Ticket::where('location_id', $currentLocation->id)
      ->whereBetween('date', [$start, $end])
      ->sum('amount_paid_during_office_visit');
    $res['monthly_down_payment'] = "$" . number_format($monthly_down_payment, 2);


    //Total paid during visit this month / number tickets #done
    $monthly_avg_down = Ticket::where('location_id', $currentLocation->id)
      ->whereBetween('date', [$start, $end])
      ->where('refill', false)
      ->avg('amount_paid_during_office_visit');
    $res['monthly_avg_down'] = "$" . number_format($monthly_avg_down, 2);


    //Total patients  in this month #done
    $monthly_patients = Ticket::whereBetween('created_at', [$start, $end])
      ->where('location_id', $currentLocation->id)
      ->count();
    $res['monthly_patients'] = $monthly_patients;


    //Total payments received this month #done
    $monthly_collections = Payment::join('tickets', 'payments.ticket_id', '=', 'tickets.id')
      ->whereBetween('payments.date', [$start, $end])
      ->where('refund', 0)
      ->where('tickets.location_id', $currentLocation->id)
      ->sum('amount');
    $res['monthly_collections'] = "$" . number_format($monthly_collections, 2);


    /////=====Daily=====///
    //Total Sold this month #done
    $daily_sales = Ticket::whereDate('date', date('Y-m-d'))->where('location_id', $currentLocation->id)->sum('total');
    $res['daily_sales'] = "$" . number_format($daily_sales, 2);


    //Total Sold / Number Tickets
    $daily_avg_ticket = Ticket::where('location_id', $currentLocation->id)->whereDate('date', date('Y-m-d'))
      ->where('refill', false)
      ->avg('total');
    $res['daily_avg_ticket'] = "$" . number_format($daily_avg_ticket, 2);


    //Total paid during visit this month #done
    $daily_down_payment = Ticket::where('location_id', $currentLocation->id)->whereDate('date', date('Y-m-d'))->sum('amount_paid_during_office_visit');
    $res['daily_down_payment'] = "$" . number_format($daily_down_payment, 2);


    //Total paid during visit this month / number tickets #done
    $daily_avg_down = Ticket::where('location_id', $currentLocation->id)->whereDate('date', date('Y-m-d'))
      ->where('refill', false)
      ->avg('amount_paid_during_office_visit');
    $res['daily_avg_down'] = "$" . number_format($daily_avg_down, 2);


    //Total patients  in this month #done
    $daily_patients = Ticket::where('location_id', $currentLocation->id)->whereDate('date', date('Y-m-d'))->count();
    $res['daily_patients'] = $daily_patients;


    //Total payments received today #done
    $daily_collections = Payment::whereDate('date', date('Y-m-d'))->with('ticket')->where('refund', 0)->get()->where('ticket.location_id', $currentLocation->id)->sum('amount');
    $res['daily_collections'] = "$" . number_format($daily_collections, 2);


    //Recent 10 tickets
    $recent_tickets = Ticket::where('location_id', $currentLocation->id)->orderBy('created_at', 'desc')->take(10)
      ->whereBetween('date', [$start, $end])
      ->get([
        'tickets.*',
        DB::raw('(SELECT COUNT(ticket_id) FROM ticket_products WHERE ticket_products.ticket_id = tickets.id) AS count_product'),
        DB::raw('(SELECT SUM(amount) FROM payments WHERE payments.ticket_id = tickets.id AND refund=0) AS total_payment')
      ]);
    $res['recent_tickets'] = $recent_tickets;
    $res['recent_tickets']->map(function ($t) {
      $t->background = '';
      if (($t->total_payment - $t->balanc_during_visit) == 0) $t->background = 'bg-light-success';
      if ($t->refill) $t->background = 'bg-light-warning';
      if ($t->count_product == 0) $t->background = 'bg-light-danger';

      return $t;
    });

    // Recent 10 Appointments
    $ticketsToday = Ticket::where('location_id', $currentLocation->id)
      ->whereDate('date', $end)
      ->pluck('schedule_id')->all();

    $cast = "CAST(STR_TO_DATE(time, '%l:%i %p' ) as Time) ASC";
    $recent_appointment = Schedule::where('location_id', $currentLocation->id)
      ->whereDate('date', $end)
      ->whereNotIn('schedules.id', $ticketsToday)
      ->orderByRaw($cast)
      ->take(10)->get();
    $res['recent_appointment'] = $recent_appointment;


    // schedule types (appointment types)
    $appointment_types = ScheduleType::all();
    $res['appointment_types'] = $appointment_types;

    return view('dashboard', $res);
  }
}
