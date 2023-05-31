<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use App\Models\UserLocation;
use Validator;

class LocationController extends Controller
{

  public function index()
  {
    $locations = Location::getAll();

    return view('locations.index')->with('locations', $locations);
  }

  public function create()
  {
    return view('locations.create');
  }

  public function store(Request $req)
  {
    $location = new Location;
    $validator = Validator::make($req->all(),$location->rules);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    // if superadmin
    if (auth()->user()->role_id ==  1) {

        $newLocation = $location->create($req->all());
        //Automatically assign location to superadmin
        $locationData = [
          'user_id'=>auth()->user()->id,
          'location_id'=>$newLocation->id,
          'primary'=>false
        ];

        UserLocation::insert($locationData);
    }

    return redirect('settings/locations')->with('success', 'Location created successfully');
  }

  public function edit($locationId)
  {
    $location = Location::findOrFail($locationId);

    return view('locations.edit')->with('location', $location);
  }

  public function update(Request $req)
  {
    $location = Location::findOrFail($req->id);

    $location->rules['email'] = $location->rules['email'].','.$req->id;
    $location->rules['phone'] = $location->rules['phone'].','.$req->id;

    $validator = Validator::make($req->all(),$location->rules);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $location->update($req->all());

    return redirect('settings/locations')->with('success', 'Location updated successfully');
  }

  public function setCurrentLocation(Location $location)
  {
      session(['current_location' => $location]);
      return redirect('/dashboard');
  }

}
