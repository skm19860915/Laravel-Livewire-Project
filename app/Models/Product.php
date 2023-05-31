<?php

namespace App\Models;

use Exception;
use App\Models\ProductType;
use App\Models\TicketProduct;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'price', 'amount', 'product_type_id', 'location_id', 'note', 'description', 'disable'];

    public $createProductRules = ['name' => 'required', 'price' => 'required', 'amount' => 'required'];

    public $with = ['productType'];

    public function productType()
    {
        return $this->belongsTo(ProductType::class)->withDefault();
    }

    public function ticketProducts()
    {
        return $this->hasMany(TicketProduct::class);
    }

    public function _store($params)
    {

        try {
            $created = self::create($params);
            return [
                'status' => 1,
                'msg' => 'Product created successfully.',
                'data' =>  [
                    'updateUrl' => route('product.update', ['product' => $created->id]),
                    'object' => $created
                ]
            ];
        } catch (Exception $e) {
            return [

                'status' => 0,
                'msg' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function _update(self $self, $params)
    {
        try {
            $self->update($params);
            return ['status' => 1, 'msg' => 'Product updated successfully.', 'data' => $self];
        } catch (Exception $e) {
            return ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
        }
    }

    static function deleteProduct(self $self, $request_type = 'web')
    {
        try {
            // if(!$self->deleteable){

            //     return ['status' => 0 ,'msg' => 'You Cant Delete This Product' , 'data' => null];
            // }
            // $haveTciket = TicketProduct::where('product_id',$self->id)->exists();
            // if($haveTciket) return ['status' => 0 ,"msg"=>"Cannot delete this product because it is associated with tickets.",'data' => null];

            $disable  = $self->disable;
            if ($disable == 0) {
                $self->update(['disable' =>  1]);
                $res  = ['status' => 1, 'msg' => 'Product disabled  successfully', 'data' => $self];
            } else {
                $self->update(['disable' =>  0]);
                $res  = ['status' => 1, 'msg' => 'Product activated  successfully', 'data' => $self];
            }
            if ($request_type == 'web') {
                return $res;
            }
            if ($request_type == 'api') {
                return response()->json($res);
            }
        } catch (Exception $e) {

            $res = ['status' => 0, 'msg' => $e->getMessage(), 'data' => null];
            if ($request_type == 'web') {
                return $res;
            }
            if ($request_type == 'api') {
                return response()->json($res);
            }
        }
    }


    static function getProductsOfCurrentLocation()
    {
        $current_location = session('current_location');
        $products = self::withTrashed()->where('location_id', $current_location->id)->get();

        return $products;
    }
    static function getProductsOfCurrentLocationForTicket()
    {
        $current_location = session('current_location');
        $products = self::where('location_id', $current_location->id)->where('disable', 0)->get();

        return $products;
    }
}
