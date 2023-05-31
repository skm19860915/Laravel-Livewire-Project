<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'job_id', 'name', 'trigger_date_type', 'status'
    ];

    public function storeEmailCommunication($request)
    {
        try
        {
            $saved = self::create($request);
            return $saved->id;
        }
        catch(Exception $e)
        {
            return 0;
        }
    }

    static function getAll($id)
    {
        $arr = self::where('patient_id', $id)->get();
        return $arr;
    }

    public function updateEmailCommunication($id, $request)
    {
        try{
            self::where('job_id', $id)->update($request);
            return true;
        }
        catch(Exception $e){
            return false;
        }
    }

    public function deleteEmailCommunication(self $self)
    {
        try{
            EmailCommunication::where('id', $self->id)->delete();
            return true;
        }
        catch(Exception $e){
            return false;
        }
    }
}
