<?php

namespace App\Models;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable  =
    [
        'date',
        'user_id',
        'schedule_id',
        'total',
        'amount_paid_during_office_visit',
        'balanc_during_visit',
        'payment_increments',
        'month_plan',
        'first_payment_due',
        'patient_id',
        'location_id',
        'treatment_type_id',
        'created_by',
        'modified_by',
        'refill',
        'treatment_end_date'
    ];

    public $createTicketRules =
    [
        "date"                              => 'required',
        "schedule_id"                       => 'required',
        "total"                             => 'required',
        'products'                          => 'array',
        "amount_paid_during_office_visit"   => 'required',
        "balanc_during_visit"               => 'required',
        'treatment_end_date'                => 'required_with:products',
    ];

    public $updateTicketRules =
    [
        "date"                              => 'required',
        "total"                             => 'required',
        'products'                          => 'array',
        "amount_paid_during_office_visit"   => 'required',
        "balanc_during_visit"               => 'required',
        'treatment_type_id'                 => 'exists:treatment_types,id',
        'treatment_end_date'                => 'required_with:products',
    ];

    public $customMessages = [
        'balanc_during_visit.required' => 'Balance during visit is required.',
        'treatment_end_date.required_with' => 'Treatment end date is required if a package is purchased.'
    ];

    protected $casts = ['paid_month_indexs' => 'json'];
    protected $with = ['treatmentTypes'];



    public function treatmentTypes()
    {
        return $this->belongsTo(TreatmentType::class);
    }

    public function paid_month_indexs()
    {
        return is_array($this->paid_month_indexs) ? $this->paid_month_indexs : [];
    }

    public function services()
    {
        return $this->hasMany(TicketService::class);
    }
    public function products()
    {
        return $this->hasMany(TicketProduct::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }
    public function sales_counselor()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function payments()
    {
        return  $this->hasMany(Payment::class);
    }

    public function patient()
    {
        return  $this->belongsTo(Patient::class);
    }

    public function getFirstPaymentDueAttribute($value)
    {
        if ($value == null) return null;
        return Carbon::parse($value)->format(config('app.date_format'));
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }

    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }

    public function getTreatmentEndDateAttribute($value)
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }

    public function counselorName($space = ' ')
    {
        $counselor = $this->counselor;
        return $counselor ? $counselor->first_name . $space . $counselor->last_name : '';
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function total($type = 'number')
    {
        $total = (float) $this->total;
        if ($type == 'number')  return $total = number_format($total, 2);
        if ($type == 'money')  return $total = "$" . number_format($total, 2);
    }

    public function balance()
    {
        $payment = new Payment;
        return "$" . $payment->remaining_balance($this, 'money');
    }

    public function _store($params)
    {
        try {
            $params['date'] = Carbon::parse($params['date']);
            $params['created_by'] = auth()->user()->id;
            $create = self::create($params);
            return ['status' => 1, 'msg' => 'Ticket created successfully.', 'data' => $create];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    public function _update(self $self, $params)
    {
        try {
            $params['date'] = Carbon::parse($params['date'])->format('Y-m-d');
            $params['modified_by'] = auth()->user()->id;

            $self->update($params);

            TicketService::where('ticket_id', $self->id)->delete();
            TicketProduct::where('ticket_id', $self->id)->delete();

            if (isset($params['services']) && !empty($params['services'])) $this->_storeServicesTicket($params['services'], $self->id);
            if (isset($params['products']) && !empty($params['products'])) $this->_storeProductsTicket($params['products'], $self->id);

            return ['status' => 1, 'msg' => 'Ticket updated successfully.', 'data' => $self];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    public function _storeServicesTicket(array $params, $ticket_id)
    {
        try {
            $created = [];
            foreach ($params as $service_id => $price) {
                $ticketService = new TicketService();
                $ticketService->ticket_id = $ticket_id;
                $ticketService->service_id = $service_id;
                $ticketService->custom_price = $price;
                $ticketService->save();
                $created[] = $ticketService;
            }
            return ['status' => 1, 'msg' => 'Ticket service created successfully.', 'data' => $created];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
        //return dd($params);
    }
    public function _storeProductsTicket(array $params, $ticket_id)
    {
        try {
            $created = [];
            $products = $params;
            $product_arr = collect($products)->map(function ($array, $index) {
                if (is_array($array)) return  $array;
                return false;
            })->reject(function ($v) {
                return $v === false;
            });

            foreach ($product_arr as $key => $value) {
                $ticketProduct = new TicketProduct();
                $ticketProduct->ticket_id = $ticket_id;
                $ticketProduct->product_id = $value['product_id'];
                $ticketProduct->custom_price = $value['custom_price'];
                $ticketProduct->custom_amount = $value['custom_amount'];
                $ticketProduct->save();
                $created[] = $ticketProduct;
            }
            return ['status' => 1, 'msg' => 'Ticket product created successfully.', 'data' => $created];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
        //return dd($params);
    }

    public static function dataTablePatientTicket($request, int $patient)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        //get current location
        $current_location = session('current_location');
        $current_location_id = $current_location->id;
        //Refill ID
        //$refill_id =18;
        // Total records
        $totalRecords = Ticket::select('count(*) as allcount')->where('tickets.patient_id', $patient)->count();
        $totalRecordswithFilter = Ticket::select('count(*) as allcount')
            ->join('patients', 'patients.id', 'tickets.patient_id')
            ->leftJoin('users', 'users.id', 'tickets.user_id')
            ->select(
                'tickets.id',
                'tickets.date',
                DB::raw('CONCAT(patients.first_name,", ",patients.last_name) AS patient_name'),
                DB::raw('CONCAT(users.first_name,", ",users.last_name) AS user_name'),
                DB::raw('(SELECT COUNT(*) FROM ticket_products WHERE ticket_products.ticket_id = tickets.id) AS count_product'),
                'tickets.total',
                'tickets.balanc_during_visit',
                'tickets.refill'
            )
            ->where('tickets.patient_id', $patient)
            ->where(function ($q) use ($searchValue) {
                $q
                    ->OrWhere('patients.first_name', 'like', $searchValue . '%')
                    ->OrWhere('users.first_name', 'like', $searchValue . '%')
                    ->OrWhere('users.last_name', 'like', $searchValue . '%')
                    ->OrWhere('tickets.total', 'like', $searchValue . '%')
                    ->OrWhere('tickets.id', 'like', $searchValue . '%')
                    ->OrWhere('tickets.balanc_during_visit', 'like', $searchValue . '%');
            })
            ->count();

        // Fetch records
        if ($columnName !== 'action') {
            $records = Ticket::orderBy($columnName, $columnSortOrder);
        } else {
            $records = Ticket::orderBy('id', $columnSortOrder);
        }
        $records = $records->join('patients', 'patients.id', 'tickets.patient_id')
            ->leftJoin('users', 'users.id', 'tickets.user_id')
            ->select(
                'tickets.id',
                'tickets.date',
                DB::raw('CONCAT(patients.first_name,", ",patients.last_name) AS patient_name'),
                DB::raw('CONCAT(users.first_name,", ",users.last_name) AS user_name'),
                DB::raw('(SELECT COUNT(*) FROM ticket_products WHERE ticket_products.ticket_id = tickets.id) AS count_product'),
                'tickets.total',
                'tickets.balanc_during_visit',
                'tickets.refill',
                'tickets.month_plan'
            )
            ->where('tickets.patient_id', $patient)
            ->where(function ($q) use ($searchValue) {
                $q
                    ->OrWhere('patients.first_name', 'like', $searchValue . '%')
                    ->OrWhere('users.first_name', 'like', $searchValue . '%')
                    ->OrWhere('users.last_name', 'like', $searchValue . '%')
                    ->OrWhere('tickets.total', 'like', $searchValue . '%')
                    ->OrWhere('tickets.id', 'like', $searchValue . '%')
                    ->OrWhere('tickets.balanc_during_visit', 'like', $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();

        //   dd($records);

        $payment = new Payment;

        $data_arr = array();

        foreach ($records as $record) {
            $record->purchased = '';
            $productsIds = $record->products->pluck('product_id')->toArray();
            $p = Product::whereIn('id', $productsIds)->get()->map(function ($p) use ($record) {
                $product = $record->products->where('product_id', $p->id)->first();
                $amount = $product ? $product->custom_amount : 0;
                $price = $product ? $product->custom_price : 0;
                $text =  "$p->name - " . number_format($amount, 0) . " dose(s)/month(s) for $" . number_format($price, 2);
                $record->purchased .= '<li>' . $text . "</li>";
            });

            $data_arr[] = array(
                "action"                   => $record->purchased,
                "id"                       => $record->id,
                "date"                     => $record->date,
                "total"                    => '$' . number_format($record->total, 2),
                "balanc_during_visit"      => $record->balanc_during_visit,
                "patient_name"             => $record->patient_name,
                "user_name"                => $record->user_name,
                'count_product'            => $record->count_product,
                'remaining_balance'        => $payment->remaining_balance($record, 'number'),
                'refill'                   => $record->refill
            );
        }

        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData"               => $data_arr
        );

        return response()->json($response);
    }

    public static function dataTable($request)
    {

        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = lcfirst($columnName_arr[$columnIndex]['data']); // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        //get current location
        $current_location = session('current_location');
        $current_location_id = $current_location->id;
        // Total records
        $totalRecords = Ticket::select('count(id) as allcount')->where('tickets.location_id', $current_location_id)->count();
        //Refill ID
        //$refill_id = 18;

        $endDate = now()->tz($current_location->time_zone);
        $date = now()->tz($current_location->time_zone);
        if (date('I', time())) {
            $date->addHours(1);
            $endDate->addHours(1);
        }
        $date->addHours(1);
        $endDate->addHours(1);
        $startDate = $date->startOfMonth();

        $totalRecordswithFilter = Ticket::select('count(tickets.id) as allcount')
            ->join('patients', 'patients.id', 'tickets.patient_id')
            ->leftJoin('users', 'users.id', 'tickets.user_id')
            ->select(
                'tickets.id',
                'tickets.date',
                DB::raw('CONCAT(patients.first_name,", ",patients.last_name) AS patient_name'),
                DB::raw('CONCAT(users.first_name,", ",users.last_name) AS user_name'),
                DB::raw('(SELECT COUNT(*) FROM ticket_products WHERE ticket_products.ticket_id = tickets.id) AS count_product'),
                'tickets.total',
                'tickets.balanc_during_visit',
                'tickets.refill'
            )
            ->where('tickets.location_id', $current_location_id)
            ->whereBetween('tickets.date', [$startDate, $endDate])
            ->where(function ($q) use ($searchValue) {
                $q->orWhere('tickets.date', 'like', $searchValue . '%')
                    ->OrWhere('patients.first_name', 'like', $searchValue . '%')
                    ->OrWhere('patients.last_name', 'like', $searchValue . '%')
                    ->OrWhere('users.first_name', 'like', $searchValue . '%')
                    ->OrWhere('users.last_name', 'like', $searchValue . '%')
                    ->OrWhere('tickets.total', 'like', $searchValue . '%')
                    ->OrWhere('tickets.id', 'like', $searchValue . '%')
                    ->OrWhere('tickets.balanc_during_visit', 'like', $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Ticket::join('patients', 'patients.id', 'tickets.patient_id')
            ->leftJoin('users', 'users.id', 'tickets.user_id')
            ->select(
                'tickets.id',
                'tickets.date',
                DB::raw('CONCAT(patients.first_name,", ",patients.last_name) AS patient_name'),
                DB::raw('CONCAT(users.first_name,", ",users.last_name) AS user_name'),
                DB::raw('(SELECT COUNT(ticket_products.id) FROM ticket_products WHERE ticket_products.ticket_id = tickets.id) AS count_product'),
                'tickets.total',
                'tickets.balanc_during_visit',
                'tickets.refill',
                'tickets.month_plan'
            )
            ->where('tickets.location_id', $current_location_id)
            ->whereBetween('tickets.date', [$startDate, $endDate])
            ->where(function ($q) use ($searchValue) {
                $q->Where('tickets.date', 'like', '%' . $searchValue . '%')
                    ->OrWhere('patients.first_name', 'like', $searchValue . '%')
                    ->OrWhere('patients.last_name', 'like', $searchValue . '%')
                    ->OrWhere('users.first_name', 'like', $searchValue . '%')
                    ->OrWhere('users.last_name', 'like', $searchValue . '%')
                    ->OrWhere('tickets.total', 'like', $searchValue . '%')
                    ->OrWhere('tickets.id', 'like', $searchValue . '%')
                    ->OrWhere('tickets.balanc_during_visit', 'like', $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();

        //   dd($records);

        $payment = new Payment;

        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "id"                       => $record->id,
                "date"                     => $record->date,
                "total"                    => $record->total,
                "balanc_during_visit"      => $record->balanc_during_visit,
                "patient_name"             => $record->patient_name,
                "user_name"                => $record->user_name,
                'count_product'            => $record->count_product,
                'remaining_balance'        => $payment->remaining_balance($record, 'number'),
                'refill'              => $record->refill
            );
        }

        $sortyByArr = array_column($data_arr, $columnName);

        if ($columnSortOrder == 'asc') {
            array_multisort($sortyByArr, SORT_ASC, $data_arr);
        } else {
            array_multisort($sortyByArr, SORT_DESC, $data_arr);
        }

        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData"               => $data_arr
        );

        return response()->json($response);
    }

    public static function dataTableFilter($request)
    {

        ## Read value
        //   return $request->year . '  '. $request->month;
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        //get current location
        $current_location = session('current_location');
        $current_location_id = $current_location->id;


        // Total records
        $totalRecords = Ticket::select('count(*) as allcount')
            ->where('tickets.location_id', $current_location_id)
            ->count();

        $totalRecordswithFilter = Ticket::select('count(*) as allcount')
            ->join('patients', 'patients.id', 'tickets.patient_id')
            ->leftJoin('users', 'users.id', 'tickets.user_id')
            ->select(
                'tickets.id',
                'tickets.date',
                DB::raw('CONCAT(patients.first_name,", ",patients.last_name) AS patient_name'),
                DB::raw('CONCAT(users.first_name,", ",users.last_name) AS user_name'),
                DB::raw('(SELECT COUNT(*) FROM ticket_products WHERE ticket_products.ticket_id = tickets.id) AS count_product'),
                'tickets.total',
                'tickets.balanc_during_visit',
                'tickets.refill'
            )
            ->where('tickets.location_id', $current_location_id)
            ->whereYear('tickets.date', $request->year)
            ->whereMonth('tickets.date', $request->month)
            ->where(function ($q) use ($searchValue) {
                $q->orWhere('tickets.date', 'like', '%' . $searchValue . '%')
                    ->OrWhere('patients.first_name', 'like', $searchValue . '%')
                    ->OrWhere('patients.last_name', 'like', $searchValue . '%')
                    ->OrWhere('users.first_name', 'like', $searchValue . '%')
                    ->OrWhere('users.last_name', 'like', $searchValue . '%')
                    ->OrWhere('tickets.total', 'like', $searchValue . '%')
                    ->OrWhere('tickets.id', 'like', $searchValue . '%')
                    ->OrWhere('tickets.balanc_during_visit', 'like', $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = Ticket::join('patients', 'patients.id', 'tickets.patient_id')
            ->leftJoin('users', 'users.id', 'tickets.user_id')
            ->select(
                'tickets.id',
                'tickets.date',
                DB::raw('CONCAT(patients.first_name,", ",patients.last_name) AS patient_name'),
                DB::raw('CONCAT(users.first_name,", ",users.last_name) AS user_name'),
                DB::raw('(SELECT COUNT(*) FROM ticket_products WHERE ticket_products.ticket_id = tickets.id) AS count_product'),
                'tickets.total',
                'tickets.balanc_during_visit',
                'tickets.refill',
                'tickets.month_plan'
            )
            ->where('tickets.location_id', $current_location_id)
            ->whereYear('tickets.date', $request->year)
            ->whereMonth('tickets.date', $request->month)
            ->where(function ($q) use ($searchValue) {
                $q->orWhere('tickets.date', 'like', '%' . $searchValue . '%')
                    ->OrWhere('patients.first_name', 'like', $searchValue . '%')
                    ->OrWhere('patients.last_name', 'like', $searchValue . '%')
                    ->OrWhere('users.first_name', 'like', $searchValue . '%')
                    ->OrWhere('users.last_name', 'like', $searchValue . '%')
                    ->OrWhere('tickets.total', 'like', $searchValue . '%')
                    ->OrWhere('tickets.id', 'like', $searchValue . '%')
                    ->OrWhere('tickets.balanc_during_visit', 'like', $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();

        //   return dd($records->toArray());
        $payment = new Payment;
        $data_arr = array();

        foreach ($records as $record) {

            $data_arr[] = array(
                "id"                       => $record->id,
                "date"                     => $record->date,
                "total"                    => $record->total,
                "balanc_during_visit"      => $record->balanc_during_visit,
                "patient_name"             => $record->patient_name,
                "user_name"                => $record->user_name,
                'count_product'            => $record->count_product,
                'remaining_balance'        => $payment->remaining_balance($record, 'number'),
                'refill'              => $record->refill
            );
        }

        $sortyByArr = array_column($data_arr, $columnName);

        if ($columnSortOrder == 'asc') {
            array_multisort($sortyByArr, SORT_ASC, $data_arr);
        } else {
            array_multisort($sortyByArr, SORT_DESC, $data_arr);
        }

        $response = array(
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData"               => $data_arr
        );

        return response()->json($response);
    }


    public function unassignedTickets($start, $end)
    {
        $current_location = session('current_location');
        $tickets = self::where('location_id', $current_location->id)
            ->where('user_id', null)
            ->whereBetween('date', [$start, $end])
            ->get();
        $payment = new Payment;
        $tickets->map(function ($ticket) use ($payment) {
            $ticket->balance = (float) $payment->remaining_balance($ticket, 'number');
            return $ticket;
        });

        return $tickets;
    }
}
