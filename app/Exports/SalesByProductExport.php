<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;


class SalesByProductExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct()
    {
        $this->locationId = session('current_location')->id;
    }

    public function start(string $start)
    {
        $this->start = $start;

        return $this;
    }

    public function end(string $end)
    {
        $this->end = $end;

        return $this;
    }

    public function headings(): array
    {
        return [
            'ticket_id',
            'ticket_date',
            'patient_name',
            'sales_counselor_name',
            'ticket_total',
            'amount_paid_during_office_visit',
            'balance_at_office_visit',
            'new_customer',
            'reorder',
            'product_name',
            'duration',
            'product_sale_price'
        ];
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        $export = DB::table('ticket_products')
            ->selectRaw(
                'tickets.id,
                tickets.date,
                CONCAT(patients.first_name, " ", patients.last_name),
                CONCAT(users.first_name, " ", users.last_name),
                tickets.total,
                tickets.amount_paid_during_office_visit,
                tickets.balanc_during_visit,
                schedules.new_customer,
                schedules.reorder,
                products.name AS product_name,
                ticket_products.custom_amount,
                ticket_products.custom_price'
            )
            ->join('products', 'ticket_products.product_id', '=', 'products.id')
            ->join('tickets', 'ticket_products.ticket_id', '=' ,'tickets.id')
            ->join('patients', 'patients.id', '=', 'tickets.patient_id')
            ->join('schedules', 'tickets.schedule_id', '=', 'schedules.id')
            ->join('users', 'tickets.user_id', '=', 'users.id')
            ->whereBetween('tickets.date', [$this->start, $this->end])
            ->where('tickets.revisit', '0')
            ->where('tickets.location_id', $this->locationId)
            ->orderBy('tickets.id', 'asc');

            return $export;

    }
}
