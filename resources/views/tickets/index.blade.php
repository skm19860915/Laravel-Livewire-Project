
@extends('layouts.auth')

@section('content')


  <div role="main" class="main-content">

    <div class="page-content container container-plus">
      <!-- page header and toolbox -->

<div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="{{route('ticket.index')}}">{{$page_name ?? ""}}</a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          {{$page_info ?? ""}}
        </small>
      </h2>
    </div>
      @include('includes.alerts')
            <div class="mt-2 w-50 ticket-filter-btn">
                <form action="{{route('ticket.filter')}}" method="post" class="d-flex align-items-center">
                    @csrf
                    <div class="mx-1 mr-3 ">
                        <span>Select:</span>
                    </div>
                    <select name="month" class="form-control mr-2">
                            <option value="01" {{$thisMonth == "January" ? 'selected' :'' }}>January</option>
                            <option value="02" {{$thisMonth == "February" ? 'selected' :'' }}>February</option>
                            <option value="03" {{$thisMonth == "March" ? 'selected' :'' }}>March</option>
                            <option value="04" {{$thisMonth == "April" ? 'selected' :'' }}>April</option>
                            <option value="05" {{$thisMonth == "May" ? 'selected' :'' }}>May</option>
                            <option value="06" {{$thisMonth == "June" ? 'selected' :'' }}>June</option>
                            <option value="07" {{$thisMonth == "July" ? 'selected' :'' }}>July</option>
                            <option value="08" {{$thisMonth == "August" ? 'selected' :'' }}>August</option>
                            <option value="09" {{$thisMonth == "September" ? 'selected' :'' }}>September</option>
                            <option value="10" {{$thisMonth == "October" ? 'selected' :'' }}>October</option>
                            <option value="11" {{$thisMonth == "November" ? 'selected' :'' }}>November</option>
                            <option value="12" {{$thisMonth == "December" ? 'selected' :'' }}>December</option>
                    </select>
                    <select name="year" class="form-control mr-2">
                        @for ($i = 2008; $i < now()->addYears(100)->format('Y') ; $i++)
                                <option value="{{$i}}" {{$i == $thisYear  ? "selected" : ""}}>{{$i}}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
      <div class="cards-container mt-3">
        <div class="card border-0 shadow-sm radius-0">
          <div class="card-header bgc-primary-d1">
            <h5 class="card-title text-white">
              <i class="fa fa-list mr-2px"></i>
                {{$card_title ?? ""}}
            </h5>
          </div>

          <div class="card-body bgc-transparent px-1 py-0 pb-2 border-1 brc-primary-m3 border-t-0 table-responsive">

            <table id="ticketsDatatable" class="d-style  w-100 table text-dark-m1 text-95 border-y-1 brc-black-tp11 collapsed">
              <thead   class="sticky-nav text-secondary-m1 text-uppercase text-85">
                <tr>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Ticket #
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Date
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Patient Name
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    SALES COUNSELOR
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Total
                  </th>
                  <th class="border-0 bgc-white bgc-h-yellow-l3 shadow-sm">
                    Balance
                  </th>
                </tr>
              </thead>

              <tbody class="pos-rel">

              </tbody>
            </table>
            <div class="d-flex">
                <div class="d-flex mx-1 mt-2">
                    <div class="w-30px h-30px bg-danger d-block mx-1"></div>
                    <span><small>- Incomplete / ACE </small></span>
                </div>
                <div class="d-flex mx-1 mt-2">
                    <div class="w-30px h-30px bg-warning d-block mx-1"></div>
                    <span><small>- Refill </small></span>
                </div>
                <div class="d-flex mx-1 mt-2">
                    <div class="w-30px h-30px bg-success d-block mx-1"></div>
                    <span><small>- Paid in Full </small></span>
                </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>

@endsection

@section('script')
<script src="{{mix('js/tickets.js')}}" defer></script>
@endsection
