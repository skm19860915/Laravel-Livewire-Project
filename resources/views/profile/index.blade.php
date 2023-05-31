@extends('layouts.auth')


@section('content')

<div role="main" class="main-content">

  <div class="page-content container container-plus">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="{{url('settings/locations')}}" >My Account</a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          Edit My Account
        </small>
      </h2>
    </div>

    @include('includes.alerts')

    <div class="cards-container mt-3">
      <div class="card border-0 shadow-sm radius-0">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            <i class="fa fa-edit mr-2px"></i>
            Edit My Account
          </h5>
        </div>

        <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
          <form class="mt-lg-3 forms" method="POST" action="{{url('update/account')}}">
            @csrf
            <div class="row">
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="location_name" class="mb-0">Username</label>
                  <span class="text-danger">*</span>
                  <input type="text" id="username" name="username" class="form-control" required value="{{ auth()->user()->username }}" />
                </div>
              </div>
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="address" class="mb-0">First Name</label>
                  <span class="text-danger">*</span>
                  <input type="text" id="fname" name="fname" class="form-control" value="{{ auth()->user()->first_name  }}" required/>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col12 col-md-5">
                <div class="form-group">
                  <label for="city" class="mb-0">Last Name</label>
                  <span class="text-danger">*</span>
                  <input type="text" id="lname" name="lname" class="form-control"  value="{{ auth()->user()->last_name }}"  required/>
                </div>
              </div>
              <div class="col12 col-md-5">
                <div class="form-group">
                  <label for="state" class="mb-0">Email</label>
                  <span class="text-danger">*</span>
                  <input type="email" id="email" name="email" value="{{auth()->user()->email}}" class="form-control"  required />
                </div>
              </div>

            </div>

            <div class="row mt-4">
              <div class="col12 col-md-12 text-left">
                <button class="btn btn-primary px-4 submit-buttons"  type="submit">
                  <i class="fa fa-check mr-1"></i>
                  Update account
                </button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>

  </div>

</div>
@endsection
