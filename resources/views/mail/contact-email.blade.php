@component('mail::message')
<h1>Dear {{$name}}</h1>
<hr>
<span>{{$body}}</span>
<br>
Thanks,<br>
{{ $location_name }}
@endcomponent
