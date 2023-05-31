<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Receivable;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{

    public function index()
    {
        //
    }

    public function create(Request $request, Ticket $ticket, Payment $payment)
    {

        $current_location = session('current_location')->id;
        if (!session('cl')) {
            session(['cl' => $current_location]);
        } else {
            if (url()->previous() == url("payment/$ticket->id"))  if (session('cl') != $current_location) {
                session()->forget('cl');
                return  redirect()->route('receivable.index');
            };
        }
        $res['page_title'] = 'Receivables';
        $editTicketUrl = route('ticket.edit', ['ticket' => $ticket->id, 'appointment' => $ticket->appointment->id]);
        $res['page_info'] = "Payment for Ticket <a href='$editTicketUrl'>#$ticket->id</a>";
        $res['card_title'] = 'Process Payment';
        $res['payments'] = $ticket->payments;
        $res['ticket'] = $ticket;
        $res['remaining_balance'] = $payment->remaining_balance($ticket, 'number');
        $res['suggest_payment'] = $payment->suggest_payment($ticket, $res['remaining_balance']);

        //paid on visit
        // if((float)$ticket->amount_paid_during_office_visit)
        // {

        $res['payments']->prepend((object)[
            "id" => 0,
            "on_visit" => 1,
            "date" => $ticket->date,
            "month_plan" => $ticket->month_plan,
            "date_due" => Carbon::parse($ticket->date)->format(config('app.date_format')),
            "amount" => $ticket->amount_paid_during_office_visit,
            "remaining_balance" => $ticket->balanc_during_visit,
            "payment_left" => $payment->payment_left($ticket->month_plan, $ticket->balanc_during_visit),
        ]);
        // }

        //dd($res['payments']);
        return view('payment.create', $res);
    }

    public function history(Ticket $ticket, Payment $payment)
    {
        $res['page_title'] = 'Receivables';
        $editTicketUrl = route('ticket.edit', ['ticket' => $ticket->id, 'appointment' => $ticket->appointment->id]);
        $res['page_info'] = "Payment for Ticket <a href='$editTicketUrl'>#$ticket->id</a>";
        $res['card_title'] = 'Process Payment';
        $res['payments'] = $ticket->payments;
        $res['ticket'] = $ticket;
        $res['remaining_balance'] = $payment->remaining_balance($ticket, 'number');
        $res['suggest_payment'] = $payment->suggest_payment($ticket, $res['remaining_balance']);
        $res['scroll_into_history'] = 'true';
        //paid on visit
        // if($ticket->amount_paid_during_office_visit)
        // {
        $res['payments']->prepend((object)[
            "id" => 0,
            "on_visit" => 1,
            "date" => $ticket->date,
            "month_plan" => $ticket->month_plan,
            //"date_due" => Carbon::parse($ticket->date)->format(config('app.date_format')),
            "amount" => $ticket->amount_paid_during_office_visit,
            "remaining_balance" => $ticket->balanc_during_visit,
            "payment_left" => $payment->payment_left($ticket->month_plan, $ticket->balanc_during_visit),
        ]);
        // }

        return view('payment.create', $res);
    }

    public function store(Request $request, Ticket $ticket, Payment $payment, Receivable $receivable)
    {
        $request = $request->all();
        $request['remaining_balance'] =  $payment->remaining_balance($ticket, 'number');
        $suggest_balance  = (float) $payment->suggest_payment($ticket, $request['remaining_balance']);;
        $request['ticket_id'] = $ticket->id;
        $request['date'] = Carbon::parse($request['date']);
        //$request['month_index'] = (int) session('month_index');
        // dd(session('month_index'));
        //$request['date_due'] = session('date_due') ?? now();
        // session()->forget('date_due');
        // dd(session('month_index') && $suggest_balance <= (float) $request['amount'],(float) $request['amount'],$suggest_balance);
        $create = $payment->_store($request);
        if ($create['status']) {
            $payment = $create['data'];
            $payment->payment_left = $payment->payment_left($ticket->payment_increments, $payment->remaining_balance);
            if ($payment->remaining_balance == 0) {
                Receivable::where('ticket_id', $payment->ticket_id)->update(['proccess' => 1]);
            }
            $payment->save();
            /*if(session('month_index') && $suggest_balance <= (float) $request['amount'])
            {
                $month_indexs  = $ticket->paid_month_indexs;
                $month_indexs[] = (int) session('month_index');
                $ticket->paid_month_indexs = $month_indexs;
                $ticket->save();
            }*/
            return redirect()->route('payment.create', ['ticket' => $ticket->id])->with('success', $create['msg']);
        };
        return back()->with('error', $create['msg']);
    }

    public function refund(Payment $payment)
    {
        $refund  = $payment->refund($payment);
        if ($refund['status']) return redirect()->route('payment.create', ['ticket' => $payment->ticket->id])->with('success', $refund['msg']);
        // if($refund['status']) return redirect()->route('receivable.index')->with('success',$refund['msg']);
        return back()->with('error', $refund['msg']);
    }

    public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return substr($haystack, 0, $length) === $needle;
    }

    public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if (!$length) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }
}
