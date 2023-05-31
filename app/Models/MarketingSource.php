<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingSource extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        //'user_id',
        'disable',
    ];

    public $createRoles = [
        'description' => 'required',
        'user_id' => 'required',
        'locations' => 'required',
    ];

    public $updateRoles = [
        'description' => 'required',
        //'user_id' => 'required',
        'locations' => 'required',
    ];

    public function store(array $request)
    {
        $self = new self();
        $self->description = $request['description'];
        //$self->user_id = $request['user_id'];
        try{
            $self->save();
            return ['status' => 1 , 'msg' => 'Success' , 'data' => $self];
        }catch(Exception $e){
            return ['status' => 0 , 'msg' => $e->getMessage() , 'data' => null];
        }
    }
    public function storeLocations(Self $self,array $locations)
    {
        if(count($locations))
        {
            $savedData = [];
            try{
                foreach ($locations as $l) {
                $marketingSourceLocations = new MarketingLocation();
                $marketingSourceLocations->marketing_source_id = $self->id;
                $marketingSourceLocations->location_id = $l;
                $marketingSourceLocations->save();
                $savedData[] = $marketingSourceLocations;
                }
            return ['status' => 1 , 'msg' => 'Marketing sources  saved successfully.' , 'data' => $savedData];

            }catch(Exception $e)
            {
                return ['status' => 0 , 'msg' => $e->getMessage() , 'data' => null];
            }
        }
    }

    public function getAll()
    {
        $auth = auth()->user();
        $current_location = session('current_location');
        if($auth->role_id == 1 )
        {
            $mss = $this->addGetToCollection(self::with('locations')->get(),$current_location->id);
            return $mss->where('get',1);
        }
        $locationsId = $auth->locations->pluck('id')->toArray();
        $mss =self::with('locations')->get();
        $_mss = collect();
        foreach($mss as $ms)
        {
            $exists = $ms->locations->whereIn('id',$locationsId)->where('id',$current_location->id)->count();
            if($exists) $_mss->push($ms);
        }
        return $_mss ;
    }
    public function locations()
    {
      return $this->belongsToMany(Location::class, 'marketing_locations');
    }

    public static function addGetToCollection($mss,$curren_location_id)
    {
        foreach($mss as $u)
        {
            $locations = $u->locations->pluck('id')->toArray();
            in_array($curren_location_id,$locations) ? $u->get = 1 : $u->get = 0;
        }
        return $mss;
    }
}
