<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\Service;
use App\Models\Ticket;
use Illuminate\Contracts\View\View ;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class TicketsExport implements FromView
{
    public function view() : View
    {

        $created_by = DB::raw("(SELECT CONCAT(users.first_name,', ',users.last_name) FROM users WHERE users.id = tickets.created_by) as created_by");
        $modified_by = DB::raw("(SELECT CONCAT(users.first_name,', ',users.last_name) FROM users WHERE users.id = tickets.modified_by) as modified_by");
        $tickets = 'tickets.*';
        $tickets = Ticket::select($tickets,$created_by,$modified_by)
        ->where('location_id',session('current_location')->id)
        ->get();

        foreach($tickets as $index => $t){
            $t->patient_name = $t->patient ? $t->patient->name(', ') : null;
            $t->sales_counselor = $t->sales_counselor ?$t->sales_counselor->name(', ') : null;
            $t->purchased = '';
            $productsIds = $t->products->pluck('product_id')->toArray();
            $servicesIds = $t->services->pluck('service_id')->toArray();
            $p = Product::whereIn('id',$productsIds)->get()->map(function($p) use($t) {
                $product = $t->products->where('product_id',$p->id)->first();
                $amount = $product ? $product->custom_amount : 0;
                $price = $product ? $product->custom_price : 0;
                $text =  "$p->name - ".number_format($amount,0)." doses/months for $".number_format($price,2);
                $t->purchased .=$text." , ";
            });
            $t->s  =Service::whereIn('id',$servicesIds)->where('deleteable',0)->get();
            $t->applicator = $t->s->where('applicator',1)->first() ?? 0;
            $t->officeVisit = $t->s->where('office_visit',1)->first() ?? 0;
            $t->refill = $t->s->where('refill',1)->first() ? 1 : 0;

            $t->applicator? $t->applicator= $t->services->where('service_id',$t->applicator->id)->first()->custom_price :$t->applicator =0;
            $t->officeVisit? $t->officeVisit= $t->services->where('service_id',$t->officeVisit->id)->first()->custom_price : $t->officeVisit = 0;

        }
        // dd($tickets->toArray());
        $res['tickets'] = $tickets;
        return view('exports.tickets',$res);
    }
}
