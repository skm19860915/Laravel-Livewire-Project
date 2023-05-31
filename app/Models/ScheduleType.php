<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleType extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'alert_type'
    ];

    public $createScheduleTypeRoles = [
        'description'=> 'required'
    ];
    public $editScheduleTypeRoles = [
        'description'=> 'required'
    ];

    public function storeScheduleType($request)
    {
        try{
            $saved = self::create($request);
            return ['status' => 1 ,'msg'=>'Schedule type created successfully.','data'=> $saved];
        }catch(Exception $e)
        {
            return ['status' => 0 ,'msg'=>$e->getMessage(),'data'=> null];

        }
    }
    public function updateScheduleType(self $self,$request)
    {
        try{
            $self->update($request);
            return ['status' => 1 ,'msg' => 'Schedule type updated successfully.' , 'data' => $self ];
        }catch(Exception $e)
        {
            return ['status' => 0 ,'msg' => $e->getMessage() , 'data' => null];
        }
    }

    static function getAlertType(int $id)
    {
        $self = self::find($id);
        // schedule type not exsits return dark alert as default
        if(!$self) return 'dark';
        return $self->alert_type;
    }



}
