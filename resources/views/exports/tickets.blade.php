<table>
<thead>
    <tr>
        <th>id</th>
        <th>Patient</th>
        <th>visit_date</th>
        <th>Modified</th>
        <th>Modified By</th>
        <th>Sales_counselor</th>
        <th>Office_Visit_amount</th>
        <th>Applicator Amount</th>
        <th>paid_amount</th>
        <th>total_amount</th>
        <th>pay_increments</th>
        <th>Refill</th>
        <th>purchased</th>
        {{-- <th>office_visit_amount</th> --}}
    </tr>
</thead>
<tbody>
    @foreach ($tickets as $t)

        <tr>
        <th>{{$t->id}}</th>
        <th>{{$t->patient_name}}</th>
        <th>{{$t->date}}</th>
        <th>{{$t->updated_at}}</th>
        <th>{{$t->modified_by}}</th>
        <th>{{$t->sales_counselor}}</th>
        <th>{{$t->officeVisit}}</th>
        <th>{{$t->applicator}}</th>
        <th>{{number_format($t->amount_paid_during_office_visit)}}</th>
        <th>{{number_format($t->total)}}</th>
        <th>{{$t->month_plan}}</th>
        <th>{{$t->refill}}</th>
        <th>{{$t->purchased}}</th>
        {{-- <th>{{number_format($t->balanc_during_visit,2)}}</th> --}}
        </tr>
    @endforeach
</tbody>
</table>
