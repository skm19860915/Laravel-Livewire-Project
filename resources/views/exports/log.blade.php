<table>
    <thead>
        <tr>
            <th>TIMESTAMP</th>
            <th>TYPE</th>
            <th>NAME</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($logs as $l)
        <tr>
            <th>{{$l->created_at}}</th>
            <th>{{$l->type}}</th>
            <th>{{$l->user->name(', ')}}</th>
        </tr>
        @endforeach
    </tbody>
</table>

