<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EmailCommunication;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //

        if($request->ajax()){
            return Patient::dataTable($request);
        }
        $res = array();
        $res['patients'] = collect();
        $res['page_name'] = 'Patients';
        $res['card_title'] = 'Patients';

        return view('patient.index',$res);
    }


    public function overview(Patient $patient,Payment $payment)
    {

      $current_location = session('current_location');
      if($patient->location_id !== $current_location->id) return redirect()->route('patient.index');

        $tickets = $patient->tickets;
        $office_visits =  $tickets->count();
        $avg_order =  $tickets->avg('total');
        $lifetime_spent =  $tickets->sum('total');
        $current_balance =  0;
        foreach($tickets as $t)
        {
            $r = (float) $payment->remaining_balance($t,'number');
            $current_balance += $r;
        }
        $res['office_visits']    =  $office_visits ?? 0;
        $res['avg_order']        =  $avg_order ?? 0;
        $res['lifetime_spent']   =  $lifetime_spent ?? 0;
        $res['current_balance']  =  $current_balance ?? 0;
        $res['patient']   = $patient;
        $res['page_name'] = "Patients";
        $res['page_info'] = "Patient Overview";
        $res['card_title']= "Patient Overview";
        //Marketing Source for current location
        $res['marketing_source'] = session('current_location')->marketing_sources->where('disable',0);

        /// patient Age
        $res['age'] = '';
        if($patient->date_of_birth){
          $born = (int) date_format(date_create_from_format('m/d/Y',$patient->date_of_birth),'Y');
          $now = (int) now()->format('Y');
          $res['age'] = abs($born - $now);
        }

        $res['age'] = $res['age'] ? ' - '.$res['age'].' years old' : '';

        $emails = EmailCommunication::getAll($patient->id);
        $res['emails'] = $emails;

        return view('patient.overview',$res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $res = array();
        $res['page_name'] = 'Patients';
        $res['page_info'] = 'Add New Patient';
        $res['card_title'] = 'Add New Patient';
        //Marketing Source for current location
        $res['marketing_source'] = session('current_location')->marketing_sources->where('disable',0);
        return view('patient.create',$res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $patient = new Patient;
        // request data into array
        $request = $request->all();
        //check point
        Validator::make($request,$patient->createPatientRules)->validate();
        $current_location = session('current_location')->id;
        $create = $patient->storePatient($current_location,$request);
        if($create['status']){
            return back()->with('success',$create['msg']);
        }else{
            return back()->with('error',$create['msg']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        //


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
        $current_location = session('current_location');
        if($patient->location_id !== $current_location->id) return redirect()->route('patient.index');

        $res['patient']   = $patient;
        $res['page_name'] = 'Patients';
        $res['page_info'] = 'Edit Patient';
        $res['card_title']= 'Edit Patient';
        //Marketing Source for current location
        $res['marketing_source'] = session('current_location')->marketing_sources->where('disable',0);
        return view('patient.edit',$res);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        //
        $request = $request->all();

        Validator::make($request ,$patient->editPatientRules )->validate();
        $update = $patient->updatePatient($patient,$request);
        if($update['status'] == 1){
            return back()->with('success',$update['msg']);
        }else{
            return back()->with('error',$update['msg']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
    }

    public function deleteEmailCommunication(Request $request)
    {
        $record = EmailCommunication::where('id', $request['id'])->first();

        $deleted = EmailCommunication::deleteEmailCommunication($record);
        DB::table('jobs')->where('id', '=', $record->job_id)->delete();

        return response()->json($deleted);
    }
}
