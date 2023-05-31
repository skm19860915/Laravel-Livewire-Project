@extends('layouts.auth')

@section('content')

<div role="main" class="main-content">

  <div class="page-content container container-plus">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="{{url('settings/users')}}" >Staff/User</a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          Add New Staff/User
        </small>
      </h2>
    </div>

    @include('includes.alerts')

    <div class="cards-container mt-3">
      <div class="card border-0 shadow-sm radius-0">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            <i class="fa fa-edit mr-2px"></i>
            Add New Staff/User
          </h5>
        </div>

        <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
          <form class="mt-lg-3 forms" autocomplete="off" method="POST" action="{{url('settings/add-user')}}" >
            @csrf
            <div class="row">
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="username" class="mb-0">Username<span class="text-danger">*</span></label>
                  <input type="text" id="username" name="username" class="form-control" value="{{ old('username')}}" required
                  />
                </div>
              </div>
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="first_name" class="mb-0">First Name<span class="text-danger">*</span></label>
                  <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name')}}" required
                  />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col12 col-md-5">
                <div class="form-group">
                  <label for="last_name" class="mb-0">Last Name<span class="text-danger">*</span></label>
                  <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name')}}" required/>
                </div>
              </div>
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="email" class="mb-0">Email<span class="text-danger">*</span></label>
                  <input type="email" id="email" name="email" class="form-control" value="{{ old('email')}}" required />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="password" class="mb-0">Password<span class="text-danger">*</span></label>
                  <input type="password" id="password" name="password" class="form-control"  required/>
                </div>
              </div>
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="password_confirmation" class="mb-0">Confirm Password<span class="text-danger">*</span></label>
                  <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"  required />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="role" class="mb-0">Role<span class="text-danger">*</span></label>
                  <select type="text" id="role" name="role_id" class="form-control" required>
                      <option value="">Please Select</option>
                    @foreach ($roles as $r)
                        <option value="{{$r->id}}">{{$r->role}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row text-center">
              <div class="col-md-3 col-lg-3"></div>
              <div class="col-md-6 col-lg-6">
                <table>
                  <thead>
                    <th style="width:max-content;">Location</th>
                    <th style="width:150px;">Default</th>
                  </thead>
                  <tbody>
                  @if($locations->isNotEmpty())
                    @foreach($locations as $location)
                      <tr>
                        <td class="text-left pt-2">
                          <label>
                            <input type="checkbox" name="location[{{$location->id}}]" @if($loop->index == 0) checked @endif>
                            {{$location->location_name}}
                          </label>
                        </td>
                        <td>
                          <input type="radio" class="is-primary" name="is_primary" value="{{$location->id}}" @if($loop->index == 0) checked @endif>
                        </td>
                      </tr>
                    @endforeach
                   @endif
                  </tbody>
                </table>
              </div>
              <div class="col-md-3 col-lg-3"></div>
            </div>

            <div class="row mt-4">
              <div class="col12 col-md-12 text-left">
                <button class="btn btn-primary px-4 submit-buttons"  type="submit">
                  <i class="fa fa-check mr-1"></i>
                  Add User
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

@section('script')
<script src="{{asset('js/locations.js')}}" defer></script>

@endsection
