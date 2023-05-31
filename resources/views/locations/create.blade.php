@extends('layouts.auth')

@section('content')   

<div role="main" class="main-content">

  <div class="page-content container container-plus">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="{{url('settings/locations')}}" >Locations</a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          Add New Location
        </small>
      </h2>
    </div>

    @include('includes.alerts')

    <div class="cards-container mt-3">
      <div class="card border-0 shadow-sm radius-0">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            <i class="fa fa-plus mr-2px"></i>
            Add New Location
          </h5>
        </div>    

        <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0">
          <form class="mt-lg-3 forms" method="POST" action="{{url('settings/add-location')}}">
            @csrf
            <div class="row">
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="location_name" class="mb-0">Location Name</label>
                  <input type="text" id="location_name" name="location_name" class="form-control" required value="{{ old('location_name') }}" />
                </div>          
              </div>
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="address" class="mb-0">Address</label>
                  <input type="text" id="address" name="address" class="form-control" value="{{ old('address') }}" required/>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col12 col-md-5">
                <div class="form-group">
                  <label for="city" class="mb-0">City</label>
                  <input type="text" id="city" name="city" class="form-control"  value="{{ old('city') }}"  required/>
                </div>
              </div>
              <div class="col12 col-md-5">
                <div class="form-group">
                  <label for="state" class="mb-0">State</label>
                  <select type="text" id="state" name="state" class="form-control"  required>
                  </select>
                </div> 
              </div> 
              <div class="col12 col-md-2">
                <div class="form-group">
                  <label for="zip" class="mb-0">Zip</label>
                  <input type="text" id="zip" name="zip" class="form-control"  value="{{ old('zip') }}"  required/>
                </div>   
              </div>  
            </div>

            <div class="row">
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="website" class="mb-0">Website</label>
                  <input type="url" id="website" name="website" class="form-control"  value="{{ old('website') }}" />
                </div> 
              </div> 
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="email" class="mb-0">Email</label>
                  <input type="email" id="email" name="email" class="form-control"  value="{{ old('email') }}" required />
                </div>  
              </div> 
            </div>

            <div class="row">
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="phone" class="mb-0">Phone</label>
                  <input type="text" id="phone" name="phone" class="form-control phones"  value="{{ old('phone') }}"  placeholder="(___) ___-____" required />
                </div>  
              </div>
              <div class="col12 col-md-6">
                <div class="form-group">
                  <label for="time_zone" class="mb-0">Time Zone</label>
                  <select type="text" id="time_zone" name="time_zone" class="form-control" required>
                    <option value="CST" >CST</option>
                    <option value="EST" >EST</option>
                    <option value="MST">MST</option>
                    <option value="PST" >PST</option>
                  </select>
                </div> 
              </div>             
            </div>                                             

            <div class="row mt-4">
              <div class="col12 col-md-12 text-left">
                <button class="btn btn-primary px-4 submit-buttons"  type="submit">
                  <i class="fa fa-check mr-1"></i>
                  Add Location
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