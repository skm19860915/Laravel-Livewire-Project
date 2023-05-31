<?php

namespace App\Models;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreatmentType extends Model
{
    use HasFactory, SoftDeletes;

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
