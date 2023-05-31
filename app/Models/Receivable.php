<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    use HasFactory;


    protected $fillable = ['ticket_id','due','payment_owed','balance'];

    public function getDueAttribute($value)
    {
        return Carbon::parse($value)->format(config('app.date_format'));
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }


    public function _save(array $params)
    {
        try{
            $created  = self::create($params);
            return ['status' => 1 ,'msg' => 'Receivables created successfully.',$data = $created];
        }catch(\Exception $e)
        {
            return ['status' => 0 ,'msg' => $e->getMessage() ,$data = null];
        }
    }

    static function proccess($self)
    {
        try{
                $self->proccess =  1;
                $self->save();
                return ['status'=> 1 , 'msg' => 'Proccessed successfuly.','data' => $self];
            }catch(\Exception $e)
            {
            return ['status'=> 0 , 'msg' => $e->getMessage(),'data' => null];
        }
    }
    static function unproccess($self)
    {
        try{
                $self->proccess =  0;
                $self->save();
                return ['status'=> 1 , 'msg' => 'Proccessed successfuly.','data' => $self];
            }catch(\Exception $e)
            {
            return ['status'=> 0 , 'msg' => $e->getMessage(),'data' => null];
        }
    }

    static function todaydue(int $ticket_id,$proccess = 1)
    {
        return Receivable::select('receivables.*')
            ->where('receivables.ticket_id',$ticket_id)
            ->where('receivables.proccess',$proccess)
            ->whereDate('due',now())
            ->orderBy('due','ASC')
            ->get();
    }

    static function overdue(int $ticket_id,$proccess = 1)
    {
        return Receivable::select('receivables.*')
            ->where('receivables.ticket_id',$ticket_id)
            ->where('receivables.proccess',$proccess)
            ->whereDate('due','<',now())
            ->orderBy('due','ASC')
            ->get();
    }

    static function setBalance($ticket_id,$amount)
    {

        try{
                Receivable::where('ticket_id',$ticket_id)->update(['balance' => $amount ]);
                return ['status'=> 1 , 'msg' => 'Balance set successfully.','data' => null];
            }catch(\Exception $e)
            {
            return ['status'=> 0 , 'msg' => $e->getMessage(),'data' => null];
        }
    }
    static function _all(int $ticket_id,$proccess = 1)
    {
        return Receivable::select('receivables.*')
            ->where('receivables.ticket_id',$ticket_id)
            ->where('receivables.proccess',$proccess)
            ->orderBy('due','DESC')
            ->get();
    }

    static function getTodayDue()
    {
        return  self::join('tickets','tickets.id','receivables.ticket_id')
        ->join('patients','tickets.patient_id','patients.id')
        ->join('schedules','tickets.schedule_id','schedules.id')
        ->where('tickets.location_id',session('current_location')->id)
        ->where('receivables.proccess',0)
        ->where('receivables.balance', '>', 0)
        ->whereDate('due',now())
        ->select('receivables.*', 'schedules.id as schedule_id', 'patients.id as patient_id', 'patients.first_name as patient_first_name',  'patients.last_name as patient_last_name')
        ->orderBy('due','ASC')
        ->get();
    }

    static function getOverDue()
    {
        return Receivable::join('tickets','tickets.id','receivables.ticket_id')
        ->join('patients','tickets.patient_id','patients.id')
        ->join('schedules','tickets.schedule_id','schedules.id')
        ->where('tickets.location_id',session('current_location')->id)
        ->where('receivables.proccess',0)
        ->where('receivables.balance', '>', 0)
        ->whereDate('due','<',now())
        ->select('receivables.*', 'schedules.id as schedule_id', 'patients.id as patient_id', 'patients.first_name as patient_first_name',  'patients.last_name as patient_last_name')
        ->orderBy('due','ASC')
        ->get();
    }

}
