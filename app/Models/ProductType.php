<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductType extends Model
{
    use HasFactory, SoftDeletes;

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function ticketProduct()
    {
        return $this->hasOneThrough(TicketProduct::class, Product::class);
    }
}
