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

          <div class="card-body bgc-transparent px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0 table-responsive">

            <a href="{{url('/create/patient')}}" class="btn btn-success mt-1 mb-1"><i class="fa fa-plus mr-1"></i>Add New Patient</a>

            <table id="patientDatatable" class="d-style w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed">
              <thead class="sticky-nav text-secondary-m1 text-uppercase text-85">
                <tr>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    name
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    home phone
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    cell phone
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    email
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    dob
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    address
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Latest Activity
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Actions
                  </th>
                </tr>
              </thead>

              <tbody class="pos-rel">

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>

@endsection

@section('script')

<script src="{{mix('js/patients.js')}}" defer></script>

@endsection
