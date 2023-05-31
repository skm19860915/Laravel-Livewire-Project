<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\MarketingLocation;
use App\Models\MarketingSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarketingSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $marketingSource = new MarketingSource;
        $res = array();
        $res['MarketingSources'] = $marketingSource->getAll();
        $res['page_name'] = 'Marketing Sources';
        $res['card_title'] = 'Marketing Sources';
        return view('marketing.index',$res);
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

        $res['page_name'] = 'Marketing Sources';
        $res['page_info'] = 'Add New Marketing Source';
        $res['card_title'] = 'Add New Marketing Source';
        $auth = auth()->user();
        // if is superadmin get all locations.
        if($auth->role->id == 1) $res['locations'] = Location::getAll();
        //if is admin git Admin his location just.
        if($auth->role->id == 2) $res['locations'] = $auth->locations;

        return view('marketing.create',$res);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // all requrest data into array
        $request = $request->all();
        $marketingSource = new MarketingSource;
        // add user_id to request
        $request['user_id'] = auth()->user()->id;
        //check
        $validation = Validator::make($request,$marketingSource->createRoles)->validate();
        // save info in database
         $save = $marketingSource->store($request);
         // if store marketing source saved successfully. store his locations
         if($save['status'])
         {
             $saveLocations = $marketingSource->storeLocations($save['data'],$request['locations']);
            if($saveLocations['status'])
            {
                //update session of  current location [important]
                session(['current_location'=> Location::find(session('current_location')->id)]);
                return back()->with('success',$saveLocations['msg']);
            }else{
                return back()->with('error',$saveLocations['msg']);

            }

         }else{
             return back()->with('error',$save['msg']);
         }
         return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\marketingSource  $marketingSource
     * @return \Illuminate\Http\Response
     */
    public function show(marketingSource $marketingSource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\marketingSource  $marketingSource
     * @return \Illuminate\Http\Response
     */
    public function edit(marketingSource $marketingSource)
    {

        $res = array();

        $res['page_name'] = 'Marketing Sources';
        $res['page_info'] = 'Edit Marketing Source';
        $res['card_title'] = 'Edit Marketing Source';
        $res['ms'] = $marketingSource;
        $res['ms_locations'] = $marketingSource->locations->pluck('id')->toArray();
        $auth = auth()->user();
        // if is superadmin get all locations.
        if($auth->role->id == 1) $res['locations'] = Location::getAll();
        //if is admin git Admin his location just.
        if($auth->role->id == 2) $res['locations'] = $auth->locations;
        return view('marketing.edit',$res);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\marketingSource  $marketingSource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, marketingSource $marketingSource)
    {
        //requets into array
        $request= $request->all();
        //$request['user_id'] = auth()->user()->id;
        //
        $validation = Validator::make($request,$marketingSource->updateRoles)->validate();
        // save new data
        $marketingSource->description = $request['description'];
        // $marketingSource->user_id  =  $request['user_id']; we dont need resave user_id
        $marketingSource->save();
        // delete old locations
        $marketingSource->locations()->detach();
        //update locations
        $saveLocation = $marketingSource->storeLocations($marketingSource,$request['locations']);
        if($saveLocation['status']){
            //update session of  current location [important]
            session(['current_location'=> Location::find(session('current_location')->id)]);
            return back()->with('success','Marketing Source Update Successfully.');
        }else{
            return back()->with('erorr',$saveLocation['msg']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\marketingSource  $marketingSource
     * @return \Illuminate\Http\Response
     */
    public function destroy(marketingSource $marketingSource)
    {
        //
        $marketingSource->locations()->detach();
        $marketingSource->delete();
        return back()->with('success','Marketing source delete successfully.');
    }

    public function toggelDisable(MarketingSource $marketingSource)
    {
        if($marketingSource->disable == 1)
        {
            $marketingSource->update(['disable' => 0]);
            session(['current_location' => auth()->user()->currentlocation->first()]);
            return back()->with('success','Marketing source activated successfully.');
        }else{
            $marketingSource->update(['disable' => 1]);
            session(['current_location' => auth()->user()->currentlocation->first()]);
            return back()->with('success','Marketing source disabled successfully.');
        }
    }
}
