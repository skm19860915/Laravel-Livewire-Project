<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingLocation extends Model
{
    use HasFactory;


    public function getMarketingSourceOfCurrentLocation()
    {
        return $this->belongsTo(MarketingSource::class,'marketing_source_id');
    }
}
