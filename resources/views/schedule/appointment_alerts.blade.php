@if($alert_content)
    <div class="alert alert-{{$alert_type}} alert-dismissible fade show" role="alert">
        {!!  $alert_content !!}
    </div>
@endif
