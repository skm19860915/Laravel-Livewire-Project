<?php

namespace App\Models;


use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use HasFactory, Notifiable, SoftDeletes;

  protected $with = ['role'];
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'username',
    'email',
    'password',
    'first_name',
    'last_name',
    'role_id',
    'location_id'
  ];

  public  $createRules = [
    'first_name' => 'required',
    'last_name' => 'required',
    //'password' => 'required|min:6|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/|confirmed',
    'password' => 'required|min:6|confirmed',
    'email' => 'required|email|unique:users,email',
    'username' => 'required|unique:users,username',
    'role_id' => 'required',
    'location' => 'required',
    'is_primary' => 'required'
  ];

  public  $updateRules = [
    'first_name' => 'required',
    'last_name' => 'required',
    'email' => 'required|email|unique:users,email',
    'username' => 'required|unique:users,username',
    'role_id' => 'required',
    'location' => 'required',
    'is_primary' => 'required'

  ];

  public $messages = ['is_primary.required' => "The default location is required"];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  const ROLE_SUPER_ADMIN = 1;
  const ROLE_ADMIN = 2;
  const ROLE_MANAGER = 3;
  const ROLE_SALES_FRONT_DESK = 4;
  const ROLE_CALL_CENTER = 5;
  const ROLE_MARKETING_ONLY = 6;
  const ROLE_TRAVELING_SALESMAN = 7;
  const ROLE_TRAVELING_MANAGER = 8;

  protected $roles = [
    self::ROLE_SUPER_ADMIN => 'Super Admin',
    self::ROLE_ADMIN => 'Admin',
    self::ROLE_MANAGER => 'Manager',
    self::ROLE_SALES_FRONT_DESK => 'Sales/Front Desk',
    self::ROLE_CALL_CENTER => 'Call Center',
    self::ROLE_MARKETING_ONLY => 'Marketing ONLY',
    self::ROLE_TRAVELING_SALESMAN => 'Traveling Salesman',
    self::ROLE_TRAVELING_MANAGER => 'Traveling Manager'
  ];

  public function getFirstNameAttribute($value)
  {
    if ($value)
      return ucwords(strtolower($value));
  }

  public function getLastNameAttribute($value)
  {
    if ($value)
      return ucwords(strtolower($value));
  }

  static function getAll()
  {
    $auth = auth()->user();
    $current_location = session('current_location');
    $locationId = $current_location->id;
    $self = new self;

    //if current is not superadmin dont show user in list
    if ($auth->role_id !== self::ROLE_SUPER_ADMIN) {
      $authLocationsIds = $auth->locations->pluck('id')->toArray();
      $authLocationsIds = implode(',', $authLocationsIds);

      $roleSuperAdmin = self::ROLE_SUPER_ADMIN;
      $users = $self->join('roles', function ($q) use ($roleSuperAdmin) {
        $q->on('roles.id', 'users.role_id')
          ->where('roles.id', '<>', $roleSuperAdmin);
      })
        ->join('user_location', function ($q) use ($locationId, $authLocationsIds) {
          $q->on('users.id', 'user_location.user_id')
            ->where('user_location.location_id', '=', $locationId);;
        })
        ->where('users.id', '<>', $auth->id)
        ->orderBy('name')
        ->select('roles.role as role_name', 'users.*')
        ->get();

      return $users;
    }


    $users = $self->join('roles', 'roles.id', 'users.role_id')
      ->join('user_location', function ($q) use ($locationId) {
        $q->on('users.id', 'user_location.user_id')
          ->where('user_location.location_id', '=', $locationId);
      })
      ->where('users.id', '<>', $auth->id)
      ->orderBy('name')
      ->select('roles.role as role_name', 'users.*')
      ->get();

    return $users;
  }



  // public function getRoleName($roleId)
  // {
  //   return $this->roles[$roleId];
  // }

  public function locations()
  {
    return $this->belongsToMany(Location::class, 'user_location')->withPivot('primary');
  }

  public function currentlocation()
  {
    return $this->belongsToMany(Location::class, 'user_location')->wherePivot('primary', 1);
    //TODO: this is a problem. primary <> current. Not sure if this was intended to be called primaryLocation() or currentLocation(),
    //it says it's current, but it's returning primary.
    //My assumption is that current = primary when a user first logs in and it's set to the session, but what happens if they change locations?
  }

  public function role()
  {
    return $this->belongsTo(Role::class);
  }

  public function checkLocation($locationId, $userId)
  {
    $findLocation = \App\Models\UserLocation::where('location_id', $locationId)->where('user_id', $userId)->first();
    if (!$findLocation) {
      return false;
    }

    return true;
  }

  public function checkPrimaryLocation($locationId, $userId)
  {
    $findLocation = \App\Models\UserLocation::where('location_id', $locationId)
      ->where('user_id', $userId)->where('primary', 1)->first();
    if (!$findLocation) {
      return false;
    }

    return true;
  }

  public function name($space = ' ')
  {
    return $this->first_name . $space . $this->last_name;
  }


  public static function addGetToCollection($users, $curren_location_id)
  {
    foreach ($users as $u) {
      $locations = $u->locations->pluck('id')->toArray();
      in_array($curren_location_id, $locations) ? $u->get = 1 : $u->get = 0;
    }
    return $users;
  }
}
