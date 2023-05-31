
@extends('layouts.guest')

@section('content')    
    <div class="body-container">

      <div class="main-container container bgc-transparent">

        <div class="main-content minh-100 justify-content-center">
          <div class="p-2 p-md-4">
            <div class="row justify-content-center" id="row-1">
              <div class="bgc-white shadow radius-1 overflow-hidden col-12 col-lg-6 col-xl-5">

              <div class="row" id="row-2">                

                <div id="id-col-main" class="col-12 py-lg-5 bgc-white px-0">

                  <div class="tab-content tab-sliding border-0 p-0" data-swipe="right">

                    <div class="tab-pane show mh-100 px-3 px-lg-0 pb-3 active" id="id-tab-login">

                      <div class="text-secondary-m1 my-4 text-center">
                        <img src="{{asset('storage/images/pryapus_logo.png')}}" alt="Logo" style="height:200px;">
     
                        <h5 class="mt-4">Recover Password</h5>
                      </div>

                      @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                      @endif                                  

                      <form action="{{ route('password.email') }}" class="form-row mt-4" method="POST">
                        @csrf
                        <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2">
                          <label class="text-secondary-d3 mb-3">
                            Enter your email address and we'll send you the instructions:
                          </label>
                          <div class="d-flex align-items-center">
                            <input type="email" name="email" class="form-control form-control-lg pr-4 shadow-none  @error('email') is-invalid @enderror" 
                            id="id-recover-email" placeholder="Email" required  value="{{ old('email') }}"
                            >
                            <i class="fa fa-envelope text-grey-m2 ml-n4"></i>
                          </div>  
                          @error('email')
                            <span class="text-danger" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                          @enderror                                                  
                        </div>
                        

                        <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2 mt-1">
                          <button type="submit" class="btn btn-primary btn-block px-4 btn-bold mt-2 mb-4">
                            Send Password Reset Link
                          </button>
                        </div>
                      </form>


                      <div class="form-row w-100">
                        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 d-flex flex-column align-items-center justify-content-center">

                          <hr class="brc-default-l2 mt-0 mb-2 w-100">

                          <div class="p-0 px-md-2 text-dark-tp4 my-3">
                            <a class="text-blue-d1 text-600 btn-text-slide-x" href="{{route('login')}}">
                              <i class="btn-text-2 fa fa-arrow-left text-110 align-text-bottom mr-2"></i>Back to Login
                            </a>
                          </div>

                        </div>
                      </div>
                    </div>

                  </div><!-- .tab-content -->
               
                </div>

                </div>

              </div><!-- /.col -->
            </div><!-- /.row -->

            <div class="d-lg-none my-3 text-white-tp1 text-center">
               {{config('app.name')}} &copy; {{date('Y')}}
            </div>
          </div>
        </div>

      </div>

    </div>

@endsection