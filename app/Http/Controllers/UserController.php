<?php

namespace App\Http\Controllers;

use Exception;
use Validator;
use App\Models\Role;
use App\Models\User;
use App\Models\Location;
use App\Models\UserLocation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function index()
  {
    $locations = auth()->user()->locations();
    $locationIds = $locations->pluck('location_id');

    $locationUsers = new UserLocation();
    $userIds = $locationUsers->whereIn('location_id', $locationIds)->groupBy('user_id')->pluck('user_id');
    $users = User::withTrashed()
      ->whereIn('id', $userIds)
      ->where('role_id', '>=', auth()->user()->role->id)
      ->where('id', '<>', auth()->user()->id)
      ->get();

    return view('users.index')->with('users', $users);
  }

  public function create()
  {
    //Super admin can see all locations
    if (auth()->user()->role->id == 1) {
      $locations = Location::getAll();
    }

    //Admins can see their locations only
    if (auth()->user()->role->id == 2) {
      $locations = auth()->user()->locations;
    }

    $roles  = Role::where('id', '>=', auth()->user()->role->id)->get();

    return view('users.create')->with('locations', $locations)->with('roles', $roles);
  }

  public function store(Request $req)
  {
    $user = new User;
    $user->createRules['email'] = $user->createRules['email'] . ',' . $req->id;
    $user->createRules['username'] = $user->createRules['username'] . ',' . $req->id;

    $validator = Validator::make($req->all(), $user->createRules, $user->messages);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $reqArray = $req->all();
    $reqArray['password'] = Hash::make($req->password);

    $newUser = $user->create($reqArray);

    $locations = $req->location;
    $isPrimary = $req->is_primary;
    $locationData = [];
    $locationInsert = [];
    foreach ($locations as $locationId => $value) {
      $locationData['user_id'] = $newUser->id;
      $locationData['location_id'] = $locationId;
      if ($isPrimary == $locationId) {
        $locationData['primary'] = true;
      } else {
        $locationData['primary'] = false;
      }
      $locationInsert[] = $locationData;
    }
    UserLocation::insert($locationInsert);

    return redirect('settings/users')->with('success', 'User created successfully');
  }

  public function edit($userId)
  {
    $user = User::withTrashed()->findOrFail($userId);

    //Super admin can see all locations
    if (auth()->user()->role->id == 1) {
      $locations = Location::getAll();
    }

    //Admins can see their locations only
    if (auth()->user()->role->id == 2) {
      $locations = auth()->user()->locations;
    }

    $roles  = Role::where('id', '>=', auth()->user()->role->id)->get();

    return view('users.edit', compact('user', 'locations', 'roles'));
  }

  public function update(Request $req)
  {
    $user = User::withTrashed()->findOrFail($req->id);

    $user->updateRules['email'] = $user->updateRules['email'] . ',' . $req->id;
    $user->updateRules['username'] = $user->updateRules['username'] . ',' . $req->id;

    $validator = Validator::make($req->all(), $user->updateRules, $user->messages);
    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    }

    $user->update($req->all());


    //delete previous locations
    $user->locations()->detach();

    $locations = $req->location;
    $isPrimary = $req->is_primary;
    $locationData = [];
    $locationInsert = [];
    foreach ($locations as $locationId => $value) {
      $locationData['user_id'] = $req->id;
      $locationData['location_id'] = $locationId;
      if ($isPrimary == $locationId) {
        $locationData['primary'] = true;
      } else {
        $locationData['primary'] = false;
      }
      $locationInsert[] = $locationData;
    }
    UserLocation::insert($locationInsert);

    return redirect('settings/users')->with('success', 'User updated successfully');
  }

  public function profile(Request $request)
  {
    if ($request->method() == 'GET') {
      return view('profile.index');
    } elseif ($request->method() == 'POST') {
      $auth = auth()->user();
      $auth->first_name = $request->fname;
      $auth->last_name = $request->lname;
      $auth->username = $request->username;
      try {
        $auth->save();
        return back()->with('success', 'Account update successfully.');
      } catch (Exception $e) {
        return back()->with('error', $e->getMessage());
      }
    }
  }


  public function disable(User $user)
  {
    $user->delete();
    return back()->with('success', 'User disabled successfully.');
  }

  public function active($id)
  {
    $user = User::onlyTrashed()->findOrFail($id);
    $user->restore();
    return back()->with('success', 'User activated successfully.');
  }
}
