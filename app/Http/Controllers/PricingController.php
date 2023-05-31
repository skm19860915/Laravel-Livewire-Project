<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use App\Models\ProductType;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    //

    public function index()
    {
        $locationId = session('current_location')->id;
        $res['page_name'] = 'Pricing';
        $res['card_title'] = 'Pricing';

        $res['products'] = Product::getProductsOfCurrentLocation();
        $res['services'] = Service::getServicesOfCurrentLocation();
        $res['product_types'] = ProductType::where('location_id', $locationId)->get();

        return view('pricing.index', $res);
    }
}
