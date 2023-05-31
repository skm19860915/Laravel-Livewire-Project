<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        //
        $request = $request->all();
        $current_location = session('current_location');
        $request['location_id'] = $current_location->id;
        Validator::make($request, $product->createProductRules)->validate();
        $create  = $product->_store($request);
        return response()->json($create);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $porduct
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $porduct
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $porduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $update  = $product->_update($product, $request->all());
        return response()->json($update);
    }

    /**
     *
     * @param  \App\Models\Product  $porduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return $product->delete();
    }


    /**
     *
     * @param  \App\Models\Product  $porduct
     * @return \Illuminate\Http\Response
     */
    public function restore(Product $product)
    {
        return $product->restore();
    }
}
