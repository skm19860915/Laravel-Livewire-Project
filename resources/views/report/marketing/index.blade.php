@extends('layouts.auth')

@section('content')

<div role="main" class="main-content">

  <div class="page-content container container-plus ">
    <!-- page header and toolbox -->
    <div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="#" >{{$page_name ?? ""}}</a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          {{$page_info ?? ""}}
        </small>
      </h2>
    </div>

    @include('includes.alerts')

    <div class="cards-container mt-3">
      <div class="card border-0 shadow-sm radius-0">


        <div class="card-body px-2  pb-2 border-1 brc-primary-m3 ">
            <div class="row w-100">
                <div class="col-12">

                    <button id="daterange" data-start='{{$_start}}' data-end='{{$_end}}' class="btn btn-white border-dark shadow-sm text-dark  d-block ml-auto fz-12px">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{$start}} - {{$end}}</span>
                    </button>
                </div>
                <div class="col-12">
                    <span>Please note that Tickets marked for 'Revisit' are not included in AVG reports however WILL be included within the 'Paid Amount' column</span>
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-light ">
                                <th>Source</th>
                                <th>Paid Amount</th>
                                <th>AVG Paid Amount</th>
                                <th>AVG Total Amount</th>
                                <th>AVG Age</th>
                                <th>Booked</th>
                                <th>Reschedule</th>
                                <th>Cancel</th>
                                <th>Confirm</th>
                                <th>Shows</th>
                                <th>Trimix - Tickets/Doses</th>
                                <th>Sub - Tickets/Doses</th>
                                <th>T - Tickets/Doses</th>
                            </tr>
                        </thead>
                        <tbody class="">

                            @foreach ($marketing_sources as $m)
                            <tr>
                              <td>{{$m->description}}</td>
                              <td>${{number_format($m->paid_amount,2)}}</td>
                              <td>${{$m->avg_paid_amount}}</td>
                              <td>${{$m->avg_total_amount}}</td>
                              <td>{{number_format($m->avg_age,0)}}</td>
                              <td>{{$m->booked}}</td>
                              <td>{{$m->reschedule}}</td>
                              <td>{{$m->cancel}}</td>
                              <td>{{$m->confirm}}</td>
                              <td>{{$m->shows}}</td>
                              <td>{{$m->trimix}}/{{number_format($m->doses_trimix,0)}}</td>
                              <td>{{$m->sublingual}}/{{number_format($m->doses_sublingual,0)}}</td>
                              <td>{{$m->testosterones}}/{{number_format($m->doses_testosterones,0)}}</</td>
                            </tr>
                            @endforeach
                                <tr>
                                    <th>Total</th>
                                    <td>${{number_format($totals['paid_amount'],2)}}</td>
                                    <td>${{number_format($totals['avg_paid_amount'],2)}}</td>
                                    <td>${{number_format($totals['avg_total_amount'],2)}}</td>
                                    <td>{{number_format($totals['avg_age'],0)}}</td>
                                    <td>{{($totals['booked'])}}</td>
                                    <td>{{($totals['reschedule'])}}</td>
                                    <td>{{($totals['cancel'])}}</td>
                                    <td>{{($totals['confirm'])}}</td>
                                    <td>{{($totals['shows'])}}</td>
                                    <td>{{$totals['trimix']}}/{{number_format($totals['doses_trimix'],0)}}</td>
                                    <td>{{$totals['sublingual']}}/{{number_format($totals['doses_sublingual'],0)}}</td>
                                    <td>{{$totals['testosterones']}}/{{number_format($totals['doses_testosterones'],0)}}</td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
      </div>
    </div>

  </div>

</div>

@endsection

@section('script')

    <script src="{{mix('js/report.marketing.js')}}" defer></script>

@endsection
