<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'location_id', 'note', 'description', 'receivable', 'disable'];

    public $createServiceRules = ['name' => 'required', 'price' => 'required'];


    public function _store($params)
    {
        try {
            $created =  self::create($params);
            return [
                'status' => 1, 'msg' => 'Service Created Successfully.',
                'data' =>  [
                    'updateUrl' => route('service.update', ['service' => $created->id]),
                    'object' => $created
                ]
            ];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    public function _update(self $self, $params)
    {

        try {
            $updated = $self->update($params);
            return ['status' => 1, 'msg' => 'Service Updated Successfully.', 'data' => $self];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    static function deleteService(self $self, $request_type = 'web')
    {
        try {
            // if(!$self->deleteable){

            //     return ['status' => 0 ,'msg' => 'You Cant Delete This Service' , 'data' => null];
            // }
            // $haveTciket = TicketService::where('service_id',$self->id)->exists();
            // if($haveTciket) return ['status' => 0 ,"msg"=>"Cannot delete this service because it is associated with tickets.",'data' => null];
            // $self->delete();
            // $res  = ['status' => 1 ,'msg' => 'Service Deleted  Successfully' , 'data' => null];
            $disable  = $self->disable;
            if ($disable == 0) {
                $self->update(['disable' =>  1]);
                $res  = ['status' => 1, 'msg' => 'Service disabled  successfully', 'data' => $self];
            } else {
                $self->update(['disable' =>  0]);
                $res  = ['status' => 1, 'msg' => 'Service activated  successfully', 'data' => $self];
            }
            // dd($self);

            if ($request_type == 'web') {
                return $res;
            }
            if ($request_type == 'api') {
                return response()->json($res);
            }
        } catch (Exception $e) {

            $res = ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
            if ($request_type == 'web') {
                return $res;
            }
            if ($request_type == 'api') {
                return response()->json($res);
            }
        }
    }

    static function getServicesOfCurrentLocation()
    {
        $current_location = session('current_location');
        $services =  self::where('location_id', $current_location->id)->get();

        return $services;
    }
    static function getServicesOfCurrentLocationForTicket()
    {
        $current_location = session('current_location');
        $services =  self::where('location_id', $current_location->id)->where('disable', 0)->get();

        return $services;
    }
}
