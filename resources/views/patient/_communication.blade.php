<table id="emailCommunicationsTable" data-patient="{{ $patient->id }}" class="d-style  w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed">
    <thead class="sticky-nav text-secondary-m1 text-uppercase text-85">
        <tr>
            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Email Name</th>
            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Send Date</th>
            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Status</th>
            <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">Actions</th>
        </tr>
    </thead>
    <tbody class="pos-rel">
        @foreach($emails as $e)
        <tr class="d-style bgc-h-default-l4">
            <td>{{$e->name}}</td>
            <td>{{$e->updated_at}}</td>
            <td>
            @if($e->status == 0)
                <div class="btn btn-primary radius-4" style="cursor:unset;">Queued</div>
            @elseif ($e->status == -1)
                <div class="btn btn-danger radius-4" style="cursor:unset;">Failed</div>
            @else
                <div class="btn btn-success radius-4" style="cursor:unset;">Sent</div>
            @endif
            </td>
            <td class="align-middle" style="display:flex;">
                @if($e->status == 0)
                    <a id="{{$e->id}}" class="btn btn-success btn-status">Remove</a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

