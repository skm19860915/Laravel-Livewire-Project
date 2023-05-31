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
          Edit Staff/User
        </small>
      </h2>
    </div>

    @include('includes.alerts')

    <div class="cards-container mt-3">
      <div class="card border-0 shadow-sm radius-0">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            <i class="fa fa-edit mr-2px"></i>
            Edit Staff/User
          </h5>
        </div>

        <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
          <form class="mt-lg-3 forms" autocomplete="off" method="POST" action="{{url('settings/update-user')}}" >
            @csrf
            <input type="hidden" name="id"  value="{{$user->id}}" />
            <div class="row">
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="username" class="mb-0">Username<span class="text-danger">*</span></label>
                  <input type="text" id="username" name="username" class="form-control" value="{{ old('username', $user->username)}}" required readonly
                  />
                </div>
              </div>
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="first_name" class="mb-0">First Name<span class="text-danger">*</span></label>
                  <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name)}}" required
                  />
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col12 col-md-5">
                <div class="form-group">
                  <label for="last_name" class="mb-0">Last Name<span class="text-danger">*</span></label>
                  <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name)}}" required/>
                </div>
              </div>
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="email" class="mb-0">Email<span class="text-danger">*</span></label>
                  <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email)}}" required readonly />
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
                        <option value="{{$r->id}}" {{$r->id  == $user->role->id ? "selected" : ""}} >{{$r->role}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>

            <div class="row text-center {{$user->role_id == 1 ? "d-none" : ""}}">
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
                            <input type="checkbox" name="location[{{$location->id}}]" {{$user->checkLocation($location->id,$user->id) ? 'checked' : ''}}>
                            {{$location->location_name}}
                          </label>
                        </td>
                        <td>
                          <input type="radio" class="is-primary" name="is_primary" value="{{$location->id}}" {{$user->checkPrimaryLocation($location->id,$user->id) ? 'checked' : ''}}>
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
              <div class="col-2 col-md-4 text-left">
                <button class="btn btn-primary px-4 submit-buttons mt-2"  type="submit">
                  <i class="fa fa-check mr-1"></i>
                  Save Changes
                </button>
            @if ($user->deleted_at)
                <a class="btn btn-success px-4 mt-2" href="{{route('user.active',['user'=>$user->id])}}"  type="submit">
                    <i class="fa fa-play-circle mr-1"></i>
                    Activate
                </a>
            @else
                <a class="btn btn-danger px-4 mt-2 disable-user-button" href="{{route('user.disable',['user'=>$user->id])}}"  type="submit">
                    <i class="fa fa-pause-circle mr-1"></i>
                    Disable
                </a>
            @endif
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
