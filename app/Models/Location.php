<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
  use HasFactory;

  protected $fillable = [
    'location_name',
    'address',
    'city',
    'state',
    'zip',
    'website',
    'email',
    'phone',
    'time_zone'
  ];

  public  $rules = [
    'location_name' => 'required',
    'address' => 'required',
    'city' => 'required',
    'state' => 'required',
    'zip' => 'required',
    //'website' => 'required',
    'email' => 'required|unique:locations,email',
    'phone' => 'required|unique:locations,phone',
    'time_zone' => 'required'
  ];

  static function getAll()
  {
    return self::orderBy('location_name')->get();
  }
  public function marketing_sources()
  {
    return $this->belongsToMany(MarketingSource::class, 'marketing_locations');
  }

  public function users()
  {
    return $this->belongsToMany(User::class);
  }
}
