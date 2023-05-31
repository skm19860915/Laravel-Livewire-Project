@extends('layouts.auth')

@section('content')

<div role="main" class="main-content">
  <div class="page-content container container-plus">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="{{url('/schedule')}}" >{{$page_name ?? ""}}</a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          {{$page_info ?? ""}}
        </small>
      </h2>
    </div>

    @include('includes.alerts')

    <div class="cards-container mt-3">
    <form action="{{url('block/store')}}" method="post" id="block_form">
        @csrf
     
      <div class="card border-0 shadow-sm radius-0 my-2">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white ">
             Block Calendar
          </h5>
        </div>

        <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0">
          <div class="row">
              <div class="col-12 col-md-4 col-lg-4">
                <div class="form-group row">
                    <label for="block_reason" class="col-sm-4">Description <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                      <input  id="block_reason" name="block_reason" class="form-control"  >{{old('block_reason')}}</input>
                      <span class="error" ></span>
                    </div>
                </div>
                
                  <div class="form-group row">
                      <label for="block_date" class="col-sm-4">Block Date <span class="text-danger">*</span></label>
                      <div class="col-sm-10">
                        <input type="datepicker" autocomplete="0" id="block_date" name="block_date" value="{{old('block_date')}}" class="form-control dates"  required />
                        
                      </div>
                      
                    </div>                   
              
                  <div class="form-group row">
                      <label for="block_start" class="col-sm-4">Start Time <span class="text-danger">*</span></label>
                      <div class="col-sm-10">
                        <input type="text" autocomplete="0" id="block_start" name="block_start" value="{{old('block_start')}}" class="form-control times" required/>
                      </div>
                  </div>
              
                  <div class="form-group row">
                    <label for="block_end" class="col-sm-4">End Time <span class="text-danger">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" autocomplete="0" id="block_end" name="block_end" value="{{old('block_end')}}" class="form-control times" required/>
                    </div>
                  </div>
              </div>
            </div>    
          </div>
        </div>

        <button type="submit" id="block_submit" class="btn btn-primary submit-buttons" disabled><i class="fas fa-check"></i> Add Block </button>

      </div>
        </form>
    </div>

  </div>

</div>

@endsection
@section('script')

 <script src="{{mix('js/schedule.js')}}" defer></script>
@endsection
