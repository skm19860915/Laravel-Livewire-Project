<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use stdClass;

class Payment extends Model
{
    use HasFactory;


    protected $fillable = ['date','amount','remaining_balance','ticket_id','payment_left','month_index','date_due','input_by','previous_balance','order'];


    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }
    public function getDateDueAttribute($value)
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }
    public function inputBy()
    {
        return $this->belongsTo(User::class,'input_by');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    static function proccess($params)
    {
        // get receiables
        $todaydue = Receivable::todaydue($params['ticket_id'],0);
        $overdue = Receivable::overdue($params['ticket_id'],0);
        // update receivable
        if($overdue->count())Receivable::proccess($overdue->first());
        if(!$overdue->count() && $todaydue->count()) Receivable::proccess($todaydue->first());
    }

    public function _store($params)
    {
        try
        {
            $remaining_balance = ((float) $params['remaining_balance'] - (float) $params['amount']);
            if((float)$params['amount']  == 0) return ['status' => 0 ,'msg' => 'Payment amount cannot be $0.','data'=>null];
            if($remaining_balance < 0) return ['status' => 0 ,'msg' => 'Payment amount cannot be greater than balance.','data'=>null];
            $params['previous_balance'] =  $params['remaining_balance'];
            $params['remaining_balance'] = $remaining_balance;
            $params['input_by']  = auth()->user()->id;
            $params['order']= Payment::where('ticket_id',$params['ticket_id'])->count();

            $payment = new Payment;
            ///suggest_payment === payment_owed
            $suggest_payment = (float) $payment->suggest_payment(Ticket::find($params['ticket_id']),$params['remaining_balance']);
           // if payment owed small than amount dont proccess payment this mean will not delete from receviable page
            if($suggest_payment <= (float) $params['amount'])
           {
                self::proccess($params);
            }
            // create
            $created = self::create($params);
            // update balances
            Receivable::setBalance($params['ticket_id'],$params['remaining_balance']);
            $totalAmount  = self::where('ticket_id',$params['ticket_id'])
                            ->where('refund',0)
                            ->where('proccess',0)
                            ->where('amount','<',$suggest_payment)
                            ->orderBy('amount','ASC')
                            ->take(2)
                            ->sum('amount');
            if($totalAmount >= $suggest_payment)
            {
                self::proccess($params);
                //This is for when the customer paid less thans suggested amount and come back to pay the remaining
                self::where('ticket_id',$params['ticket_id'])
                            ->where('refund',0)
                            ->where('amount','<',$suggest_payment)
                            ->orderBy('amount','ASC')
                            ->take(2)
                            ->update(['proccess' => 1]);
            }
            return ['status' => 1 ,'msg' => 'Payment processed successfully.','data'=> $created];
        }
        catch(Exception $e)
        {
            return ['status' => 0 , 'msg' => $e->getMessage(),'data'=> null];
        }
    }

    public function refund(self $payment)
    {

        try
        {
            $ticket = $payment->ticket;

            $payment->refund = 1;
            $previous_balance = $payment->previous_balance;
            //************ */
            $payment->save();
            //************ */
            //update Receviable
            $suggest_payment = (float) $payment->suggest_payment($ticket,$payment->remaining_balance);
            if($payment->amount >= $suggest_payment)
            {
                $r = Receivable::_all($payment->ticket_id,1)->first();
                Receivable::unproccess($r);
            }


            // re calac
            $payments = Payment::where('ticket_id',$ticket->id)->where('order','>=',$payment->order)
                                ->orderBy('order','ASC')
                                ->get();
            foreach($payments as $p)
            {

                if($p->refund)
                {
                    $p->remaining_balance = abs($payment->amount + $p->remaining_balance);
                    $p->payment_left = $payment->payment_left($ticket->payment_increments,$p->remaining_balance);
                    $p->save();
                }else{
                    $prePayment = Payment::where('ticket_id',$ticket->id)->where("order",abs($p->order - 1))->first();
                    if($prePayment)
                    {
                        $p->remaining_balance = abs($p->amount - $prePayment->remaining_balance);
                        $p->payment_left = $payment->payment_left($ticket->payment_increments,$p->remaining_balance);
                        $p->save();

                    }
                }
            }

            $payment = Payment::find($payment->id);
            $totalAmount  = self::where('ticket_id',$payment->ticket_id)
                            ->where('refund',1)
                            ->where('proccess',1)
                            ->where('amount','<',$suggest_payment)
                            ->orderBy('amount','ASC')
                            ->take(2)
                            ->sum('amount');
            if($totalAmount >= $suggest_payment)
            {
                $r = Receivable::_all($payment->ticket_id,1)->first();
                Receivable::unproccess($r);
                self::where('ticket_id',$payment->ticket_id)
                            ->where('refund',1)
                            ->where('amount','<',$suggest_payment)
                            ->orderBy('amount','ASC')
                            ->take(2)
                            ->update(['proccess' => 1]);
            }
            Receivable::setBalance($payment->ticket_id,$payment->remaining_balance($ticket,'number'));
            return ['status' => 1 ,'msg' => 'Payment refunded successfully.','data'=> $payment];
        }
        catch(Exception $e)
        {
            return ['status' => 0 , 'msg' => $e->getMessage(),'data'=> null];
        }
    }
    public function remaining_balance(Ticket $ticket,$format = 'money')
    {
        $remaining_balance = 0;
        if ($ticket->month_plan !== 'full') {
            $payments_count  = $ticket->payments->where('refund',0)->count();
            if($payments_count) {
                $totalPayments = Payment::where('ticket_id',$ticket->id)->where('refund',0)->sum('amount');
                $remaining_balance = abs($totalPayments-$ticket->balanc_during_visit);
                if($format == 'money') return number_format($remaining_balance,2);
                if($format == 'number') return str_replace(',','',number_format($remaining_balance,2));
            }else{
                $remaining_balance = $ticket->balanc_during_visit;
                if($format == 'money') return number_format($remaining_balance,2);
                if($format == 'number') return str_replace(',','',number_format($remaining_balance,2));
            };
        } else {
            if($format == 'money') return number_format($remaining_balance,2);
            if($format == 'number') return str_replace(',','',number_format($remaining_balance,2));
        }
    }

    public function suggest_payment(Ticket $ticket,$remaining_balance)
    {
        $remaining_balance =  (float) $remaining_balance;
        $payment_increments = (float) $ticket->payment_increments;
        // suggest balance come from payment increment in tikcets table
        // dd($remaining_balance,$payment_increments);
        return $remaining_balance>$payment_increments ? number_format($payment_increments,2) : number_format($remaining_balance,2);
    }

    public function payment_left($payment_increments,$remaining_balance) // balance
    {
        $balance =  (float) $remaining_balance;
        $payment_increments = (float) $payment_increments;
        $payment_left = 0;
        //**payment cant be zero bcs can divided by zero**//
        if($payment_increments != 0){$payment_left= ceil(round($balance /$payment_increments,2));}
        return $payment_left;
    }

    public function getLastTwoPaymentTotalAmount(Ticket $ticket,Payment $payment,$index)
    {

                $rb  =   (float) $payment->remaining_balance($ticket,'number');
                $sgb =   (float) $payment->suggest_payment($ticket,$rb);
                $m_p =   (float) $ticket->month_plan;
                /// get count payments
                $lp  = $ticket->payments->where('refund',0)->where('amount','<',$sgb)->count();
               // if not even exist;
                if($lp % 2 != 0) return FALSE;
                // get last payment index
                --$lp;
                // get array of paymetns
                $ps  = $ticket->payments->where('refund',0)->where('amount','<',$sgb);
                $psArray = clone $ps;
                $psArray = $psArray->toArray();
                $psLastIndex = $ps->keys()->last();
                $psBeforeLast = abs(1-$psLastIndex);

                try{
                    // get last payment
                    $last_payment = $psArray[$psLastIndex];
                    // get payment before last payment
                    $before_last_payment = $psArray[$psBeforeLast];
                    // assgin last payment amount  to before last payment amount;
                    $amount = (float) $last_payment['amount'] +  (float) $before_last_payment['amount'];
                    $res = $sgb <= $amount ? TRUE : FALSE;
                    if($res) {
                        $paid_month_indexs = $ticket->paid_month_indexs();
                        $last_index = abs(1-count($paid_month_indexs));
                        if(isset($paid_month_indexs[$last_index]))
                        {
                            $paid_month_indexs[] = abs($paid_month_indexs[$last_index] + 1);
                            $ticket->paid_month_indexs = $paid_month_indexs;
                            $ticket->save();
                        }else{
                            $paid_month_indexs[] =  1;
                            $ticket->paid_month_indexs = $paid_month_indexs;
                            $ticket->save();
                        }

                        return $ticket->paid_month_indexs();
                    };

                    return $res;
                }catch(\Exception $e)
                {
                    $res = FALSE;
                }
                return FALSE;
    }

    public function receviable()
    {
        //Payment Model
        $payment = new Payment;
        //Payments
        $dueToday = collect();
        $overdue  = collect();
        // get all tickets
        $tickets  = Ticket::where('location_id',session('current_location')->id)->get();
        // $tickets  = Ticket::all();
        foreach($tickets as $index => $ticket)
        {
            // if month plan or payment date === null or "" or FALSE remove it from collection
            if( !$ticket->first_payment_due || !$ticket->month_plan) continue;
            // get Remaining Balance
            $remaining_balance = $payment->remaining_balance($ticket,'number');
            if((float)$remaining_balance == 0)  continue ;
            // get Month Plan
            $month_plan = (int) $ticket->month_plan;
            $first_payment_due = Carbon::parse($ticket->first_payment_due);
            $payment_increments = $ticket->payment_increments;
            $ticket_id = $ticket->id;
            $patient_name = $ticket->patient->name();
            $patient_id = $ticket->patient->id;
            $appointment_id = $ticket->appointment->id;
            $ticket_date = $ticket->date;
            $paid_month_indexs = $ticket->paid_month_indexs();
            $payments_dates = collect();

            $x= $this->getLastTwoPaymentTotalAmount($ticket,$payment,$index);
            if($x)
            {
                $paid_month_indexs = $x;
            }
            for($month = 1;$month <=  $month_plan ;$month++)
            {
                $_payment = new stdClass;
                $_payment->suggest_payment = $payment->suggest_payment($ticket,$remaining_balance);
                // if first payment date dont add month to fist date
                $_payment->payment_date_due  = $month !== 1 ? clone $first_payment_due->addMonth() : clone $first_payment_due;
                $_payment->date_due = $_payment->payment_date_due;
                // $_payment->date_due2 = $_payment->date_due->format('m/d/Y');
                $_payment->remaining_balance = $remaining_balance;
                $_payment->patient_name = $patient_name;
                $_payment->patient_id = $patient_id;
                $_payment->ticket_id = $ticket_id;
                $_payment->appointment_id = $appointment_id;
                $_payment->ticket_date = $ticket_date;
                $_payment->month_index = $month;
                if(in_array($month,$paid_month_indexs)) continue;
                /**Due Today**/
                $payment_date_due = $_payment->payment_date_due->format(config('app.date_format'));
                if(now()->format(config('app.date_format')) == $payment_date_due){
                    $todayPayments = $ticket->payments->where('refund',0)->where('date_due',$_payment->payment_date_due->format('Y-m-d'))->count();
                    if(!$todayPayments) $dueToday->push($_payment);
                    $payments_dates->push((object)[
                        'month_index' => $_payment->month_index ,
                        'payment_date_due' => $_payment->payment_date_due,
                        'ticket_id' => $_payment->ticket_id,
                        ]);
                }
                /**Overdue**/
                elseif( now() > $_payment->payment_date_due){
                    $overdue->push($_payment);
                    $payments_dates->push((object)[
                        'month_index' => $_payment->month_index ,
                        'payment_date_due' => $_payment->payment_date_due,
                        'ticket_id' => $_payment->ticket_id,
                        ]);
                }
            }
            $firstPayment = $payments_dates->sortBy('month_index')->first();
                // dd($firstPayment);
                $dueToday->map(function($payment) use($firstPayment){
                    if(isset($firstPayment->ticket_id))
                    {
                        if($payment->ticket_id == $firstPayment->ticket_id){
                            // dd($payment,$firstPayment);
                            $payment->payment_date_due = $firstPayment->payment_date_due;
                            $payment->month_index = $firstPayment->month_index;
                        }
                    }
                    return $payment;
                });
                $overdue->map(function($payment) use($firstPayment){
                    if(isset($firstPayment->ticket_id))
                    {
                        if($payment->ticket_id == $firstPayment->ticket_id){
                            // dd($payment,$firstPayment);
                            $payment->payment_date_due = $firstPayment->payment_date_due;
                            $payment->month_index = $firstPayment->month_index;
                        }
                    }
                    return $payment;
                });
                // dd($overdue);
            // dd("Payments Dates",$payments_dates->sortBy('month_index')->first(),"dueToday",$dueToday,"Overdue",$overdue);
        }
        // dd($overdue->sortByDesc('date_due2'));
        $overdue = $overdue->sortBy('date_due');
        $receviable = (object)['dueToday' => $dueToday , 'overDue' => $overdue];
        // dd($overdue);
         return $receviable;
    }
    public function paymentsOverdue()
    {
        $payments = self::whereDate('date','<',now())->get();
        return $payments;
    }
}
