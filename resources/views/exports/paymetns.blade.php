<table>
<thead>
    <tr>
        <th>id</th>
        <th>visit_id</th>
        <th>Patient</th>
        <th>Amount</th>
        <th>payment_date</th>
        <th>input_by</th>
    </tr>
</thead>
<tbody>
    @foreach ($payments as $p)
    <tr>
        <th>{{$p->id}}</th>
        <th>{{$p->visit_id}}</th>
        <th>{{$p->patient_name}}</th>
        <th>{{$p->amount}}</th>
        <th>{{$p->date}}</th>
        <th>{{$p->input_by}}</th>
    </tr>
    @endforeach
</tbody>
</table>
