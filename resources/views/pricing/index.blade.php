@extends('layouts.auth')

@section('content')
    <div role="main" class="main-content">

        <div class="page-content container container-plus">
            <!-- page header and toolbox -->
            <div class="page-header pb-2">
                <h2 class="page-title text-primary-d2 text-150">
                    {{ $page_name ?? '' }}
                </h2>
            </div>

            @include('includes.alerts')


            <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#products" role="tab" aria-controls="home" aria-selected="true">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#services" role="tab" aria-controls="profile" aria-selected="false">Services</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="home-tab">

                    @include('pricing.products')

                </div>
                <div class="tab-pane fade" id="services" role="tabpanel" aria-labelledby="profile-tab">

                    @include('pricing.services')

                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ mix('js/pricing.js') }}" defer></script>
@endsection
