<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ticket;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\Receivable;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use App\Models\TicketProduct;
use App\Models\TicketService;
use App\Models\TreatmentType;
use Illuminate\Validation\Rule;
use App\Jobs\ZingleApplyTagsJob;
use App\Models\ZingleIntegration;
use Illuminate\Support\Facades\Log;
use App\Jobs\ZingleUpdateTreatmentsJob;
use Illuminate\Support\Facades\Validator;
use App\Lib\EmailTemplate;
use App\Models\EmailJourney;
use App\Jobs\EmailQueueJob;
use App\Models\EmailCommunication;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->zingleIntegration = new ZingleIntegration();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            return Ticket::dataTable($request);
        }
        $res['page_name'] = 'Tickets';
        $res['page_info'] = "List";
        $res['card_title'] = 'Tickets List';
        $res['thisYear'] = now()->format("Y");
        $res['thisMonth'] = now()->format("F");
        return view('tickets.index', $res);
    }

    public function patientTicket(Request $request, Patient $patient)
    {
        if ($request->ajax()) {
            return Ticket::dataTablePatientTicket($request, $patient->id);
        }
    }

    public function filter(Request $request)
    {
        if ($request->ajax()) {

            return Ticket::dataTableFilter($request);
        }
        // dd($request->all());
        $res['page_name'] = "Tickets";
        $res['page_info'] = "Tickets  $request->month/$request->year";
        $res['card_title'] = 'Tickets List';
        $res['year'] = $request->year;
        $res['month'] = $request->month;
        return view('tickets.filter', $res);
    }

    public function editTicket(Ticket $ticket)
    {
        $current_location = session('current_location');
        if ($ticket->location_id !== $current_location->id) return redirect()->route('ticket.index');

        return redirect()->route('ticket.edit', ['appointment' => $ticket->appointment->id, 'ticket' => $ticket->id]);
    }

    public function view(Request $request, Ticket $ticket)
    {
        $current_location = session('current_location');
        if ($ticket->location_id !== $current_location->id) return redirect()->route('ticket.index');
        $res['page_name'] = 'Tickets';
        $res['page_info'] = "View Invoice";
        $res['card_title'] = 'Tickets List';
        $res['ticket'] = $ticket;
        $res['appointment'] = $ticket->appointment;
        $res['patient'] = $ticket->appointment->patient;
        $res['sales_counselor'] = $ticket->sales_counselor;

        // services
        $items = [];
        $services = $ticket->services;
        $products = $ticket->products;
        foreach ($services as $s) {
            $price = $s->custom_price ? $s->custom_price : 0.00;
            $description = $s->service->description;
            $item = $s->service->name;
            if (!(int)$price) continue;
            $service =  (object)  ['item' => $item, 'total' => '$' . $price, 'description' => $description];
            $items[] = $service;
        }
        foreach ($products as $p) {
            $price = $p->custom_price ? $p->custom_price  : 0.00;
            $description = $p->product->description;
            $item = $p->product->name;
            $product =  (object)  ['item' => $item, 'total' => '$' . $price, 'description' => $description];

            $items[] = $product;
        }
        $res['items'] = $items;
        $res['clinic'] = session('current_location');
        return view('tickets.view', $res);
    }

    public function create(Request $request, Schedule $appointment, UserLocation $userLocation)
    {
        $current_location = session('current_location')->id;
        if (!session('cl')) {
            session(['cl' => $current_location]);
        } else {
            if (url()->previous() == url("create/ticket/$appointment->id")) {
                if (session('cl') != $current_location) {
                    session()->forget('cl');
                    return  redirect()->route('ticket.index');
                }
            }
        }
        $res['page_title'] = 'Tickets';
        $res['page_info'] = 'Create Ticket';
        $res['card_title'] = 'Ticket Info';
        $res['appointment'] = $appointment;
        $res['products'] = Product::getProductsOfCurrentLocationForTicket();
        $res['services'] = Service::getServicesOfCurrentLocationForTicket();
        $res['treatment_types'] = TreatmentType::where('location_id', $current_location)->get();
        //get users thay belongs to current location
        $res['users'] = $userLocation->currentLocationUsers();

        return view('tickets.create', $res);
    }

    public function store(Request $request, Ticket $ticket, Schedule $appointment)
    {
        // current location
        $current_location = session('current_location');
        $locationId = (int) $current_location->id;

        $request = $request->all();
        $request['schedule_id'] = $appointment->id;
        $request['patient_id']  = $appointment->patient_id;
        $request['user_id'] = $request['sales_counselor'] ? (int) $request['sales_counselor'] : null;
        // filter payment increments
        $payment_increments = explode(',', $request['payment_increments']);
        $request['payment_increments'] = isset($payment_increments[0]) ? (float) $payment_increments[0] : 0;
        $request['month_plan'] =  isset($payment_increments[1]) ? $payment_increments[1] : null;
        $request['first_payment_due'] = $request['month_plan']  ? Carbon::parse($request['first_payment_due']) : null;
        if ($request['payment_increments'] == null) {
            $request['first_payment_due'] = null;
        }

        Validator::make($request, $ticket->createTicketRules, $ticket->customMessages)->validate();

        if (empty($request['products'])) {
            $request['treatment_end_date'] = null;
        } else {
            $request['treatment_end_date'] = ($request['treatment_end_date']) ? Carbon::parse($request['treatment_end_date'])->format('Y-m-d') : null;
        }

        // ticket belong to current location
        $request['location_id'] = $locationId;

        $request['refill'] = false;
        if (isset($request['services'])) {
            foreach ($request['services'] as $serviceId) {
                //Refill
                $service = Service::find($serviceId);
                if ($service) {
                    if (trim($service->name) == 'Refill') {
                        $request['refill'] = true;
                    }
                }
            }
        }

        //Store ticket
        $create = $ticket->_store($request);

        if ($create['status'])
        {
            $ticket =  $create['data'];
            $ticket_id = $create['data']->id;

            if (isset($request['services'])) $services = $ticket->_storeServicesTicket($request['services'], $ticket_id);
            if (isset($request['products'])) $products = $ticket->_storeProductsTicket($request['products'], $ticket_id);


            /// store future payments
            if ($ticket->month_plan !== 'full') {
                $receivable = new Receivable;
                $payment  = new Payment;
                // remaining_balance
                $balance = $payment->remaining_balance($ticket, 'number');
                $payment_owed = $payment->suggest_payment($ticket, $balance);
                $first_payment_due = Carbon::parse($ticket->first_payment_due);
                for ($month = 1; $month <=  $ticket->month_plan; $month++) {
                    $receivable->_save([
                        'ticket_id' => $ticket_id,
                        'balance' => $balance,
                        'payment_owed' => $payment_owed,
                        'due' => $month !== 1 ? clone $first_payment_due->addMonth() : clone $first_payment_due,
                    ]);
                }
            }
            $ticketData = collect($ticket);
            $ticketProducts = TicketProduct::with(['product', 'product.productType'])->where('ticket_id', $ticket->id)->get();

            if (count($ticketProducts) >  0) {
                $ticketProducts->each(function ($item) {
                    $item->product_type = $item->product->productType->description;
                });
            };

            $ticketData = $this->getZingleUpdates($ticketProducts, $ticketData);

            if ($this->zingleIntegration->isEnabled((int) $locationId)) {
                ZingleUpdateTreatmentsJob::dispatch($locationId, $ticketData);

                //Apply ace tag
                if ($ticket->month_plan == 'full' && $ticket->total == 99) {
                    ZingleApplyTagsJob::dispatch($locationId, $ticket, 'apply');
                } else {
                    ZingleApplyTagsJob::dispatch($locationId, $ticket, 'remove');
                }
            }

            $location_name = session('current_location')->location_name;
            $this->sendEmailWithTicketTemplate($location_name, $create['data'], 4);

            return response()->json($create);
        }

        return response()->json($create['msg']);
    }

    public function custom_dispatch($job): int
    {
        return app(\Illuminate\Contracts\Bus\Dispatcher::class)->dispatch($job);
    }

    public function sendEmailWithTicketTemplate($locationName, $data, $treatment_type_id)
    {
        $patient_id = $data['patient_id'];
        $appointment_date = Carbon::parse($data['date'])->format('Y-m-d');
        $patient = Patient::find($patient_id);

        $ticket_template  = EmailJourney::getTemplate(1, $treatment_type_id);
        $job_id = 0;

        if(!empty($ticket_template))
        {
            try
            {
                $emailTemplate = new EmailTemplate;
                $emailTemplate->email = $patient->email;
                $emailTemplate->name = $patient->first_name." ".$patient->last_name;
                $emailTemplate->subject = $ticket_template->subject;
                $emailTemplate->body = $ticket_template->body;
                $emailTemplate->date = $appointment_date;
                $emailTemplate->days = $ticket_template->days;
                $emailTemplate->location_name = $locationName;

                $job = new EmailQueueJob($emailTemplate);
                $job->delay(now()->addDays($emailTemplate->days)); //addMinutes
                $job_id = $this->custom_dispatch($job);
            }
            catch(Exception $e)
            {
                return 0;
            }
        }

        if($job_id > 0)
        {
            $this->saveEmailCommunication($ticket_template->name, $patient_id, $job_id, 1);
        }

        return 0;
    }

    public function saveEmailCommunication($temp_name, $patient_id, $job_id, $trigger_date_type)
    {
        $emailCommunication = new EmailCommunication;
        $data['patient_id'] = $patient_id;
        $data['job_id'] = $job_id;
        $data['name'] = $temp_name;
        $data['trigger_date_type'] = $trigger_date_type;

        $id = $emailCommunication->storeEmailCommunication($data);
        return $id;
    }

    public function edit(Request $request, Ticket $ticket, Schedule $appointment, UserLocation $userLocation)
    {
        $current_location = session('current_location')->id;
        if (!session('cl')) {
            session(['cl' => $current_location]);
        } else {
            if (url()->previous() == url("edit/ticket/$ticket->id/$appointment->id"))  if (session('cl') != $current_location) {
                session()->forget('cl');
                return  redirect()->route('ticket.index');
            };
        }
        $res['page_title'] = 'Tickets';
        $res['card_title'] = 'Edit Ticket';
        $res['appointment'] = $appointment;
        $res['products'] = Product::getProductsOfCurrentLocationForTicket();
        $res['services'] = Service::getServicesOfCurrentLocationForTicket();
        $res['ticket_services'] = $ticket->services;
        //Get services array to be checked but exclude Refill
        $res['ticket_services_ids'] = $res['ticket_services']->pluck('service_id')->toArray();
        $current_location = session('current_location');
        $res['ticket_products'] = TicketProduct::with('product')->where('ticket_id', $ticket->id)
            ->join('products', 'products.id', 'ticket_products.product_id')
            ->select('ticket_products.*', 'products.location_id')
            ->where('products.location_id', $current_location->id)
            ->get();
        $res['ticket'] = $ticket;
        $res['ticket']->treatment_end_date = $ticket->treatment_end_date ?? $ticket->date;

        $res['treatment_types'] = TreatmentType::where('location_id', $current_location->id)->get();
        //get users thay belongs to current location
        $res['users'] = $userLocation->currentLocationUsers();
        $res['sales_counselor'] = $ticket->sales_counselor;

        $res['longest_duration_product'] = TicketProduct::with('product')->where('ticket_id', $ticket->id)->orderBy('custom_amount', 'desc')->first();
        $res['ticket']->treatment_end_date = $res['ticket']->treatment_end_date ?? $this->getTreatmentEndDate($res['longest_duration_product'], $res['ticket']);

        $showRevisitButton = 1;
        $total = $ticket->total;

        $products = $ticket->products->count();

        if ($total > 0 and !$products) {
            $showRevisitButton = 0;
        }

        $res['showRevisitButton'] = $showRevisitButton;
        //  dd($res['ticket']);
        return view('tickets.edit', $res);
    }
    public function update(Request $request, Ticket $ticket, Schedule $appointment, Payment $payment, Receivable $receivable)
    {
        $current_location = session('current_location');
        $locationId = (int) $current_location->id;

        $request = $request->all();

        $request['schedule_id'] = $appointment->id;
        $request['user_id'] = (int) $request['sales_counselor'] == 0 ? null : (int) $request['sales_counselor'];
        $payment_increments = explode(',', $request['payment_increments']);
        $request['payment_increments'] = isset($payment_increments[0])  ? (float) $payment_increments[0] : 0;
        $request['month_plan'] =  isset($payment_increments[1]) ? $payment_increments[1] : null;
        $request['first_payment_due'] = $request['month_plan'] ? Carbon::parse($request['first_payment_due']) : null;
        if ($request['payment_increments'] == null) {
            $request['first_payment_due'] = null;
        }

        $request['total'] = str_replace(',', '', str_replace('$', '', $request['total']));
        $request['amount_paid_during_office_visit'] = str_replace(',', '', str_replace('$', '', $request['amount_paid_during_office_visit']));
        $request['balanc_during_visit'] = str_replace(',', '', str_replace('$', '', $request['balanc_during_visit']));

        Validator::make($request, $ticket->updateTicketRules, $ticket->customMessages)->validate();

        if (empty($request['products'])) {
            $request['treatment_end_date'] = null;
        } else {
            $request['treatment_end_date'] = ($request['treatment_end_date']) ? Carbon::parse($request['treatment_end_date'])->format('Y-m-d') : null;
        }

        $request['refill'] = false;
        if (isset($request['services'])) {
            foreach ($request['services'] as $serviceId) {
                //Refill
                $service = Service::find($serviceId);
                if ($service) {
                    if (trim($service->name) == 'Refill') {
                        $request['refill'] = true;
                    }
                }
            }
        }

        $update = $ticket->_update($ticket, $request);

        if ($update['status'])
        {
            if ($ticket->month_plan !== 'full') {
                // on update ticket delete receivables and payments
                Receivable::where('ticket_id', $ticket->id)->delete();
                //Payment::where('ticket_id',$ticket->id)->delete();
                $first_payment_due = Carbon::parse($ticket->first_payment_due);
                $balance = $payment->remaining_balance($ticket, 'number');
                $payment_owed = $payment->suggest_payment($ticket, $balance);
                for ($month = 1; $month <=  $ticket->month_plan; $month++) {
                    $receivable->_save([
                        'ticket_id' => $ticket->id,
                        'balance' => $balance,
                        'payment_owed' => $payment_owed,
                        'due' => $month !== 1 ? clone $first_payment_due->addMonth() : clone $first_payment_due,
                    ]);
                }
            }

            $ticketData = collect($ticket);
            $ticketProducts = TicketProduct::with(['product', 'product.productType'])->where('ticket_id', $ticket->id)->get();

            if (count($ticketProducts) >  0) {
                $ticketProducts->each(function ($item) {
                    $item->product_type = $item->product->productType->description;
                });
            };

            $ticketData = $this->getZingleUpdates($ticketProducts, $ticketData);

            if ($this->zingleIntegration->isEnabled((int) $locationId)) {
                ZingleUpdateTreatmentsJob::dispatch($locationId, $ticketData);

                //Apply ace tag
                if ($ticket->month_plan == 'full' && $ticket->total == 99) {
                    ZingleApplyTagsJob::dispatch($locationId, $ticket, 'apply');
                } else {
                    ZingleApplyTagsJob::dispatch($locationId, $ticket, 'remove');
                }
            }

            //dd($update['data']);
            if(!empty($update['data']))
            {
                $location_name = session('current_location')->location_name;

                $this->deleteUnsentEmailCommunications($update['data']['patient_id'], 1, 0); // trigger_date_type = 1 (ticket date); status = 0;
                $this->sendEmailWithTicketTemplate($location_name, $update['data'], $update['data']['treatment_type_id']);

                if(!empty($request['treatment_end_date']))
                {
                    $this->deleteUnsentEmailCommunications($update['data']['patient_id'], 3, 0); // trigger_date_type = 3 (end date); status = 0;
                    $this->sendEmailWithTreatmentEndTemplate($location_name, $update['data'], $request['treatment_end_date']);
                }
            }

            return response()->json($update);
        };

        return response()->json($update['msg']);
    }

    public function deleteUnsentEmailCommunications($patient_id, $trigger_date_type, $status)
    {
        $records = EmailCommunication::where('patient_id', $patient_id)->get();
        if(count($records) > 0)
        {
            foreach($records as $record)
            {
                DB::table('jobs')->where('id', '=', $record->job_id)->delete();
            }

            $patient_id = $records[0]['patient_id'];
            DB::delete('delete from email_communications where patient_id = ?',[$patient_id]);
        }
    }

    public function sendEmailWithTreatmentEndTemplate($locationName, $data, $treatment_end_date)
    {
        $interval = now()->diffInDays(Carbon::parse($treatment_end_date)->format('Y-m-d'));

        $patient_id = $data['patient_id'];
        $appointment_date = Carbon::parse($data['date'])->format('Y-m-d');
        $treatment_type_id = $data['treatment_type_id'];
        $patient = Patient::find($patient_id);

        $treatment_end_template  = EmailJourney::getTemplate(3, $treatment_type_id);
        $job_id = 0;

        if(!empty($treatment_end_template))
        {
            try
            {
                $emailTemplate = new EmailTemplate;
                $emailTemplate->email = $patient->email;
                $emailTemplate->name = $patient->first_name." ".$patient->last_name;
                $emailTemplate->subject = $treatment_end_template->subject;
                $emailTemplate->body = $treatment_end_template->body;
                $emailTemplate->date = $appointment_date;
                $emailTemplate->days = $treatment_end_template->days;
                $emailTemplate->location_name = $locationName;

                $job = new EmailQueueJob($emailTemplate);
                $job->delay(now()->addDays($emailTemplate->days + $interval)); //addMinutes
                $job_id = $this->custom_dispatch($job);
            }
            catch(Exception $e)
            {
                return 0;
            }
        }

        if($job_id > 0)
        {
            $this->saveEmailCommunication($treatment_end_template->name, $patient_id, $job_id, 3);
        }

        return 0;
    }

    public function delete(Ticket $ticket)
    {
        $ticket_id = ['ticket_id', $ticket->id];
        TicketProduct::where(...$ticket_id)->delete();
        TicketService::where(...$ticket_id)->delete();
        $ticket->delete();
        return redirect()->route('schedule.index')->with('success', 'Ticket delete successfully.');
    }

    public function revisit(Ticket $ticket)
    {
        $ticket->revisit = 1;
        $ticket->save();
        return back();
    }
    public function undoRevisit(Ticket $ticket)
    {
        $ticket->revisit = 0;
        $ticket->save();
        return back();
    }

    public function getTreatmentEndDate($ticketProduct, $ticket)
    {
        if ($ticket->total < 0 || empty($ticketProduct)) {
            return $ticket->date;
        }
        $productDetail = $ticketProduct->product()->with('productType')->get();

        if ($productDetail->first()->productType()->get()->isNotEmpty()) {
            $productType = $productDetail->first()->productType()->get()->first()->description;
        } else {
            $productType = 'Other';
        }

        if ($productType == 'ESWT') {
            $treatmentEndDate = Carbon::parse($ticket->date)->addMonths(3);
        } else {
            $treatmentEndDate = Carbon::parse($ticket->date)->addMonths((int) $ticketProduct->custom_amount);
        }

        return $treatmentEndDate->format('Y-m-d');
    }

    public function getZingleUpdates($ticketProducts, $ticketData)
    {
        if (count($ticketProducts) == 0) {
            $ticketData['treatment_end_date'] = null;
            $ticketData['date'] = null;
        }

        $ticketData['ed_treatment_plan'] = '';
        $ticketData['trt_treatment_plan'] = '';
        $ticketData['eswt_treatment_plan'] = '';
        $ticketData['sign_up_date'] = $ticketData['date'];

        foreach ($ticketProducts as $tp) {
            $type = strtolower($tp->product_type) ?? '';
            $duration = (int) $tp->custom_amount;

            if ($type == 'eswt') {
                if ($duration >= 5 && $duration <= 9) {
                    $ticketData['eswt_treatment_plan'] = 'eswt 6';
                } else if ($duration >= 10 && $duration <= 15) {
                    $ticketData['eswt_treatment_plan'] = 'eswt 12';
                } else {
                    $ticketData['eswt_treatment_plan'] = 'other';
                }
            } else {
                switch (true) {
                    case $duration == 1:
                        $ticketData[$type . '_treatment_plan'] = $type . ' 1 month';
                        break;
                    case $duration >= 2 && $duration <= 4:
                        $ticketData[$type . '_treatment_plan'] = $type . ' 3 month';
                        break;
                    case $duration >= 5 && $duration <= 9:
                        $ticketData[$type . '_treatment_plan'] = $type . ' 6 month';
                        break;
                    case $duration >= 10 && $duration <= 15:
                        $ticketData[$type . '_treatment_plan'] = $type . ' 12 month';
                        break;
                    case $duration >= 16 && $duration <= 24:
                        $ticketData[$type . '_treatment_plan'] = $type . ' 24 month';
                        break;
                    default:
                        $ticketData[$type . '_treatment_plan'] = 'other';
                        break;
                }
            }
        }

        return $ticketData;
    }
}
