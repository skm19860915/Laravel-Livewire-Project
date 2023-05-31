<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        "location_id",
        "first_name",
        "last_name",
        "home_phone",
        "cell_phone",
        "email",
        "date_of_birth",
        "address",
        "state",
        "city",
        "zip",
        "high_blood_pressure",
        "high_cholesterol",
        "diabetes",
        "how_did_hear_about_clinic",
        "patient_note",
    ];

  public $createPatientRules = [
      'first_name' => "required",
      'last_name' => "required",
      'how_did_hear_about_clinic' => "required",
      //'date_of_birth' => "date",
  ];
  public $editPatientRules = [
      'first_name' => "required",
      'last_name' => "required",
      'how_did_hear_about_clinic' => "required",
      //'date_of_birth' => "date",
  ];


     public function getDateOfBirthAttribute($value)
    {
      if($value)
        return Carbon::parse($value)->format(config('app.date_format'));
    }

    public function getFirstNameAttribute($value)
    {
      if($value)
        return ucwords(strtolower($value));
    }

    public function getLastNameAttribute($value)
    {
      if($value)
        return ucwords(strtolower($value));
    }

    public function getAddressAttribute($value)
    {
      if($value)
        return ucwords(strtolower($value));
    }

    public function getCityAttribute($value)
    {
      if($value)
        return ucwords(strtolower($value));
    }

    public function getCellPhoneAttribute($value)
    {
      if($value){
        $value = preg_replace("/[^\d]/","",$value);

        // get number length.
        $length = strlen($value);

       // if number = 10
       if($length == 10) {
        $value = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $value);
       }

        return $value;
      }
    }

    public function getHomePhoneAttribute($value)
    {
      if($value){
        $value = preg_replace("/[^\d]/","",$value);

        // get number length.
        $length = strlen($value);

       // if number = 10
       if($length == 10) {
        $value = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "($1) $2-$3", $value);
       }

        return $value;
      }
    }

    public function name($space = ' ')
    {
        return $this->first_name.$space.$this->last_name;
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class);
    }

    public function lastAppointment()
    {
        return $this->hasOne(Schedule::class)->latest();
    }

    public function storePatient(int $current_location_id,array $request)
    {
        $request['location_id'] = $current_location_id;

        try{
          if(!empty($request['date_of_birth'])){
            $request['date_of_birth'] = Carbon::parse($request['date_of_birth']);
          }
          $saved = self::create($request);
          return ['status' => 1 , 'msg' => 'Patient created successfully.' ,'data' => $saved];
        }catch(Exception $e){
            return ['status' => 0 , 'msg' => $e->getMessage() ,'data' => null];
        }

    }
    public function updatePatient(self $self,$request)
    {
        isset($request['high_blood_pressure']) ? $request['high_blood_pressure'] = 1 : $request['high_blood_pressure'] = 0;
        isset($request['high_cholesterol']) ? $request['high_cholesterol'] = 1 : $request['high_cholesterol'] = 0;
        isset($request['diabetes']) ? $request['diabetes'] = 1 : $request['diabetes'] = 0;
        try{
          if(!empty($request['date_of_birth'])){
            $request['date_of_birth'] = Carbon::parse($request['date_of_birth']);
          }
          $updated = $self->update($request);
          return ['status'=> 1 , 'msg' => 'Patient updated successfully.','data'=> $self ];
        }catch(Exception $e)
        {
            return ['status'=> 0 , 'msg' => $e->getMessage(),'data'=>null];
        }
    }

    public function getAll(){
        return self::where('location_id',session('current_location')->id)->get();
    }

    public static function dataTable($request)
    {
      ## Read value
      $current_location_id = session('current_location')->id;
      $draw = $request->get('draw');
      $start = $request->get("start");
      $rowperpage = $request->get("length"); // Rows display per page

      $columnIndex_arr = $request->get('order');
      $columnName_arr = $request->get('columns');
      $order_arr = $request->get('order');
      $search_arr = $request->get('search');

      $columnIndex = $columnIndex_arr[0]['column']; // Column index
      $columnName = $columnName_arr[$columnIndex]['data']; // Column name
      $columnSortOrder = $order_arr[0]['dir']; // asc or desc
      $searchValue = $search_arr['value']; // Search value

      // Total records
      $totalRecords = Patient::select('count(*) as allcount')->count();
      $totalRecordswithFilter = Patient::select('count(*) as allcount')
      ->where('patients.location_id',$current_location_id  )
      ->where(function($q) use($searchValue){
            $q->where('first_name', 'like', '%' .$searchValue . '%')
            ->OrWhere('last_name', 'like', '%' .$searchValue . '%')
            ->OrWhere('home_phone', 'like', '%' .$searchValue . '%')
            ->OrWhere('cell_phone', 'like', '%' .$searchValue . '%')
            ->OrWhere('email', 'like', '%' .$searchValue . '%')
            ->OrWhere('date_of_birth', 'like', '%' .$searchValue . '%')
            ->OrWhere('address', 'like', '%' .$searchValue . '%');
      })
      ->count();

      // Fetch records
      $records = Patient::orderBy($columnName,$columnSortOrder)
      ->where('patients.location_id',$current_location_id  )
      ->where(function($q) use($searchValue){
            $q->Where('first_name', 'like', '%' .$searchValue . '%')
            ->OrWhere('last_name', 'like', '%' .$searchValue . '%')
            ->OrWhere('home_phone', 'like', '%' .$searchValue . '%')
            ->OrWhere('cell_phone', 'like', '%' .$searchValue . '%')
            ->OrWhere('email', 'like', '%' .$searchValue . '%')
            ->OrWhere('date_of_birth', 'like', '%' .$searchValue . '%')
            ->OrWhere('address', 'like', '%' .$searchValue . '%');
      })
      ->select('patients.*')
      ->skip($start)
      ->take($rowperpage)
      ->get();

      $data_arr = array();

      foreach($records as $record){
            $latestActivity = '';
            if ($record->lastAppointment && !empty($record->lastAppointment->date)) {
                $apptDate = Carbon::parse($record->lastAppointment->date)->format('m/d/Y');
                $apptTime = $record->lastAppointment->time;
                $latestActivity = $apptDate.' '.$apptTime;
            }

            $data_arr[] = array(
            "first_name"    => $record->first_name ." ". $record->last_name ,
            "last_name"     => $record->last_name,
            "home_phone"    => $record->home_phone ,
            "cell_phone"    => $record->cell_phone ,
            "email"         => $record->email ,
            "date_of_birth" => $record->date_of_birth ,
            "address"       => $record->address ,
            "last_activity" => $latestActivity,
            'action'        => $record->id,
            );
      }

      $response = array(
          "draw"                 => intval($draw),
          "iTotalRecords"        => $totalRecords,
          "iTotalDisplayRecords" => $totalRecordswithFilter,
          "aaData"               => $data_arr
      );

      return response()->json($response);
    }
}
