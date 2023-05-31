<?php

namespace App\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogExcel extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','type','location_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function getCreatedAtAttribute($value)
    {

        if($value == null) return null;
        $x = explode(' ',$value);
        $x =  explode(":",$x[1]);
        $mints = $x[1];
        $date = Carbon::parse($this->attributes['created_at']);
        $tz = session('current_location')->time_zone;
        $date = $date->tz($tz);
        // if DST  add hour
        date('I',time()) ? $date->addHours(1) : '' ;
        $date->addHours(1);
        // format date
        $date = $date->format("m/d/Y h:$mints A");
        // dd($date);
        return $date ;
    }

    public function _store($params)
    {

        try{
            $created = self::create($params);
            return ['status' => 1 , 'msg'=> 'Log created successfully.','data'=> $created];
        }catch(Exception $e)
        {
            return ['status' => 1 , 'msg'=> $e->getMessage() ,'data'=> null];
        }
    }
}
