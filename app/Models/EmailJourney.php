<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailJourney extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'subject', 'body', 'trigger_date_type', 'days', 'treatment_type_id', 'status'
    ];

    public function storeEmailJourney($request)
    {
        try
        {
            $saved = self::create($request);
            return true;
        }
        catch(Exception $e)
        {
            return false;
        }
    }

    public function updateEmailJourney($id, $request)
    {
        try{
            self::where('id', $id)->update($request);
            return true;
        }
        catch(Exception $e){
            return false;
        }
    }

    static function getTemplate($date_type, $treatment_type)
    {
        $template = null;

        if($date_type == 2){
           $template = self::where('trigger_date_type', $date_type)->where('status', 1)->first();
        }
        else{
            $template = self::where('trigger_date_type', $date_type)->where('treatment_type_id', $treatment_type)->where('status', 1)->first();
        }

        return $template;
    }
}
