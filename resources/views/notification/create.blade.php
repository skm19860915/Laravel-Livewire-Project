@extends('layouts.auth')

@section('content')

  <div role="main" class="main-content">

    <div class="page-content container container-plus">
      <!-- page header and toolbox -->
      <div class="page-header pb-2">
        <h2 class="page-title text-primary-d2 text-150">
          {{$page_name ?? ""}}
        </h2>
      </div>

      @include('includes.alerts')

      <div class="cards-container mt-3">
        <div class="card border-0 shadow-sm radius-0">
          <div class="card-header bgc-primary-d1">
            <h5 class="card-title text-white">
              <i class="fa fa-list mr-2px"></i>
                {{$card_title ?? ""}}
            </h5>
          </div>
            <div class="card-body bgc-transparent px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
             <form action="{{route('notification.store')}}" method="post">
                @csrf
                <textarea name="emails" id="" cols="30" rows="10" class="form-control my-2">{{$emails}}</textarea>
                <button class="btn btn-primary" name="action" value="test">Test Notification</button>
                <button class="btn btn-primary" name="action" value="save">Save Changes</button>
            </form>
            </div>
        </div>
    </div>
</div>
</div>




@endsection
