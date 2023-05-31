<table>
<thead>
    <tr>
        <th>id</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Date of Birth</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip</th>
        <th>Home Phone</th>
        <th>Cell Phone</th>
        <th>Email</th>
        <th>note</th>
        <th>HBP</th>
        <th>Cholesterol</th>
        <th>Diabetes</th>
        <th>Visits</th>
        <th>Total Sales</th>
        <th>Paid</th>
        <th>First Visit</th>
        <th>Last Visit</th>
        <th>Lead Source</th>
        <th>Payments</th>
    </tr>
</thead>
<tbody>
    @foreach ($patients as $p)
        <tr>
            <td>{{$p->id}}</td>
            <td>{{$p->first_name}}</td>
            <td>{{$p->last_name}}</td>
            <td>{{$p->date_of_birth}}</td>
            <td>{{$p->address}}</td>
            <td>{{$p->city}}</td>
            <td>{{$p->state}}</td>
            <td>{{$p->zip}}</td>
            <td>{{$p->home_phone}}</td>
            <td>{{$p->cell_phone}}</td>
            <td>{{$p->email}}</td>
            <td>{{$p->patient_note}}</td>
            <td>{{$p->high_blood_pressure}}</td>
            <th>{{$p->high_cholesterol}}</th>
            <th>{{$p->diabetes}}</th>
            <td>{{$p->visits}}</td>
            <td>{{$p->total_sales}}</td>
            <td>{{$p->paid}}</td>
            <td>{{$p->first_visit}}</td>
            <td>{{$p->last_visit}}</td>
            <td>{{$p->lead_source}}</td>
            <td>{{$p->payments}}</td>
        </tr>
    @endforeach
</tbody>
</table>
