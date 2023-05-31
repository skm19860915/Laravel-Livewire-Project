<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
  use HasFactory;

  public $timestamps = false;

  protected $fillable = [
    'user_id',
    'location_id',
    'primary'
  ];

  protected $table = 'user_location';

  public function currentLocationUsers()
  {
    //current location
    $current_location = session('current_location');
    $locations  = self::where('location_id', $current_location->id)->where('user_id', '!=', '1')->select('user_id')->get()->pluck('user_id')->toArray();
    $users = User::whereIn('id', $locations)->get();
    return $users;
  }
}
