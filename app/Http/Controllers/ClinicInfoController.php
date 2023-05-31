<?php

namespace App\Http\Controllers;

use App\Models\ClinicInfo;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClinicInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $res = array();
        $res['page_name']   =   'Clinic info';
        $res['page_info']   =   'Edit Clinic info';
        $res['card_title']  =   'Edit Clinic info';
        $auth = auth()->user();
        $current_location = session('current_location');
        if($current_location)
        {
            $res['location']=  session('current_location');

        }else{
            $current_location = $auth->currentlocation->first();
            if($current_location){
                $res['location'] = $current_location;
            }else{
                return redirect('/')->with('error','You dont have default location.');
            }
        }
        return view('clinicinfo.edit',$res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request = $request->all();
        dd($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClinicInfo  $clinicInfo
     * @return \Illuminate\Http\Response
     */
    public function show(ClinicInfo $clinicInfo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClinicInfo  $clinicInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(ClinicInfo $clinicInfo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClinicInfo  $clinicInfo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $req, ClinicInfo $clinicInfo)
    {
        //
        $location = Location::findOrFail($req->id);

        $location->rules['email'] = $location->rules['email'].','.$req->id;
        $location->rules['phone'] = $location->rules['phone'].','.$req->id;

        $validator = Validator::make($req->all(),$location->rules);
        if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
        }

        $new = $location->update($req->all());
        session(['current_location' => Location::find($req->id)]);

        return back()->with('success', 'Location updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClinicInfo  $clinicInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClinicInfo $clinicInfo)
    {
        //
    }
}
