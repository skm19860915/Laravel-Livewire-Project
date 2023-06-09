<table>
<thead>
    <tr>
        <th>id</th>
        <th>Patient</th>
        <th>Created</th>
        <th>Last_modified</th>
        <th>Schedule_date</th>
        <th>modified_by</th>
        <th>description</th>
        <th>show?</th>
        <th>new_customer?</th>
        <th>appointment_notes</th>
    </tr>
</thead>
<tbody>
    @foreach ($schedules as $s)
        <tr>
            <th>{{$s->id}}</th>
            <th>{{$s->patient_name}}</th>
            <th>{{$s->create_date}}</th>
            <th>{{$s->Last_modified}}</th>
            <th>{{$s->schedule_date}}</th>
            <th>{{$s->_updateBy}}</th>
            <th>{{$s->description}}</th>
            <th>{{$s->show}}</th>
            <th>{{$s->new_customer}}</th>
            <th>{{$s->note}}</th>
        </tr>
    @endforeach
</tbody>
</table>
