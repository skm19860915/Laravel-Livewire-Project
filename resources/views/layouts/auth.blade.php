<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <meta name="token" content="{{csrf_token()}}">
    <base href="../" />

    <title>{{ session('current_location')->location_name }} - {{config('app.name')}}</title>
    <link href="{{mix('css/app.css')}}" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('/site.webmanifest') }} ">
    @livewireStyles
  </head>

  <body>
    <div class="body-container " style="background-image: none">
      <nav class="navbar navbar-expand-lg navbar-fixed navbar-blue">
        <div class="navbar-inner">

          <div class="navbar-intro justify-content-xl-between">

            <button type="button" class="btn btn-burger burger-arrowed static collapsed ml-2 d-flex d-xl-none" data-toggle-mobile="sidebar" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle sidebar">
              <span class="bars"></span>
            </button><!-- mobile sidebar toggler button -->

            <a class="navbar-brand text-white"  href="{{route('dashboard')}}" >
              <img src="{{asset('/storage/images/pryapus_icon.png')}}" alt="icon">
               {{config('app.name')}}
            </a><!-- /.navbar-brand -->

            <button type="button" class="btn btn-burger mr-2 d-none d-xl-flex" data-toggle="sidebar" data-target="#sidebar" aria-controls="sidebar" aria-expanded="true" aria-label="Toggle sidebar">
              <span class="bars"></span>
            </button><!-- sidebar toggler button -->

          </div><!-- /.navbar-intro -->


          <!-- mobile #navbarMenu toggler button -->
          <button class="navbar-toggler ml-1 mr-2 px-1" type="button" data-toggle="collapse" data-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navbar menu">
            <span class="pos-rel">
                  <span class="bgc-warning radius-round border-2 brc-white p-1 position-tr mr-n1px mt-n1px"></span>
            </span>
          </button>


          <div class="navbar-menu collapse navbar-collapse navbar-backdrop" id="navbarMenu">

            <div class="navbar-nav">
              <ul class="nav">
                <li class="nav-item dropdown order-first order-lg-last">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown"  role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="d-inline-block d-lg-none d-xl-inline-block">
                      <span class="text-90" id="id-user-welcome">Current Location: {{ session('current_location')->location_name ?? "N/A"}}</span>
                    </span>

                    <i class="caret fa fa-angle-down d-none d-xl-block"></i>
                    <i class="caret fa fa-angle-left d-block d-lg-none"></i>
                  </a>

                  <div class="dropdown-menu dropdown-caret dropdown-menu-right dropdown-animated brc-primary-m3 py-1">

                    @foreach ($locations  as $l)
                        <a
                        class="mt-1 dropdown-item btn btn-outline-grey bgc-h-primary-l3 btn-h-light-primary btn-a-light-primary"
                        href="{{url('/set/current-location/'.$l->id)}}">
                            {{$l->location_name}}
                        </a>
                    @endforeach
                  </div>
                </li><!-- /.nav-item:last -->
                <li class="nav-item dropdown order-first order-lg-last">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown"     role="button" aria-haspopup="true" aria-expanded="false">
                    <span class="d-inline-block d-lg-none d-xl-inline-block">
                      <span class="text-90" id="id-user-welcome">Logged in as {{auth()->user()->username}}</span>
                    </span>

                    <i class="caret fa fa-angle-down d-none d-xl-block"></i>
                    <i class="caret fa fa-angle-left d-block d-lg-none"></i>
                  </a>

                  <div class="dropdown-menu dropdown-caret dropdown-menu-right dropdown-animated brc-primary-m3 py-1">

                    <a class="mt-1 dropdown-item btn btn-outline-grey bgc-h-primary-l3 btn-h-light-primary btn-a-light-primary" href="{{url('my-account')}}">
                      <i class="fa fa-user text-primary-m1 text-105 mr-1"></i>
                      My Account
                    </a>

                    <div class="dropdown-divider brc-primary-l2"></div>
                    <a class="dropdown-item btn btn-outline-grey bgc-h-secondary-l3 btn-h-light-secondary btn-a-light-secondary"
                      onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    >
                      <i class="fa fa-power-off text-warning-d1 text-105 mr-1"></i>
                      Logout
                    </a>
                  </div>
                </li><!-- /.nav-item:last -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

              </ul><!-- /.navbar-nav menu -->
            </div><!-- /.navbar-nav -->

          </div><!-- /#navbarMenu -->


        </div><!-- /.navbar-inner -->
      </nav>

      <div class="main-container bgc-white">

        @include('layouts.sidebar')

        @yield('content')

      </div>

      <footer class="footer d-none d-sm-block">
        <div class="footer-inner bgc-white-tp1">
          <div class="pt-3 border-none border-t-3 brc-grey-l2 border-double">
            <span class="text-grey">{{config('app.name')}} &copy; {{date('Y')}}</span>
          </div>
        </div><!-- .footer-inner -->

        <!-- `scroll to top` button inside footer (for example when in boxed layout) -->
        <div class="footer-tools">
          <a href="#" class="btn-scroll-up btn btn-dark mb-2 mr-2">
            <i class="fa fa-angle-double-up mx-2px text-95"></i>
          </a>
        </div>
      </footer>

    </div>
    <script src="{{ mix('/js/manifest.js') }}" defer></script>
    <script src="{{ mix('/js/vendor.js') }}" defer></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>


    @yield('script')
    @livewireScripts
  </body>

</html>
