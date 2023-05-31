
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
     
                        <h5 class="mt-4">Please login to your account</h5>
                      </div>

                      @if (session('success'))
                        <div class="alert alert-success mx-auto">{{session('success')}}</div>
                      @endif                    
                      @if (session('error'))
                        <div class="alert alert-danger mx-auto">{{session('error')}}</div>
                      @endif                                        
                    
                      <form action="{{route('login')}}" method="POST" autocomplete="off" class="form-row mt-4">
                        @csrf
                        <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2">
                          <div class="d-flex align-items-center input-floating-label text-blue brc-blue-m2">
                            <input type="text" class="form-control form-control-lg pr-4 shadow-none" id="id-login-username" name="username" required>
                            <i class="fa fa-user text-grey-m2 ml-n4"></i>
                            <label class="floating-label text-grey-l1 ml-n3" for="id-login-username" >
                              Username
                            </label>
                          </div>
                          @error('username')
                            <span class="text-danger" role="alert">
                              <strong>{{ $message }}</strong>
                            </span>
                          @enderror                            
                        </div>

                        <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2 mt-2 mt-md-1">
                          <div class="d-flex align-items-center input-floating-label text-blue brc-blue-m2">
                            <input type="password" name="password" class="form-control form-control-lg pr-4 shadow-none @error('password') is-invalid @enderror" id="id-login-password" required>
                            <i class="fa fa-key text-grey-m2 ml-n4"></i>
                            <label class="floating-label text-grey-l1 ml-n3" for="id-login-password">
                              Password
                            </label>                           
                          </div>
                          @error('password')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror                            
                        </div>


                        <div class="col-sm-10 offset-sm-1 col-md-8 offset-md-2 text-right text-md-right mt-n2 mb-2">
                          <a href="{{route('password.request')}}" class="text-primary-m1 text-95" >
                            Forgot Password?
                          </a>
                        </div>

                        <div class="form-group col-sm-10 offset-sm-1 col-md-8 offset-md-2">
                          <button type="submit" class="btn btn-primary btn-block px-4 btn-bold mt-2 mb-4">
                            Login
                          </button>
                        </div>
                      </form>


                      <div class="form-row">
                        <div class="col-sm-10 offset-sm-1 col-md-8 offset-md-2 d-flex flex-column align-items-center justify-content-center">


                        </div>
                      </div>
                    </div>

                    </div>

                  </div><!-- .tab-content -->              

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