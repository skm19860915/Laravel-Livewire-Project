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
                    <span>Individual Reports DO NOT Include refills</span>
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-light ">
                                <th>Counselor Name</th>
                                <th>ACE %</th>
                                <th>Number of Tickets</th>
                                <th>Total Paid</th>
                                <th>AVG Down Payments</th>
                                <th>AVG Ticket Amount</th>
                                <th>Gross Sales</th>
                                <th>Collected From Balances</th>
                            </tr>
                        </thead>
                        <tbody class="">

                            @foreach ($counselors as $c)

                            <tr>
                                <th>{{ucwords(strtolower($c->counselor_name))}}</th>
                                <td>{{empty($c->ace) ? '0.00' : $c->ace}}%</td>
                                <td>{{$c->number_of_tickets}}</td>
                                <td>${{number_format((float) $c->total_down_payments,2)}}</td>
                                <td>${{number_format((float) $c->avg_down_payments,2)}}</td>
                                <td>${{number_format((float) $c->avg_ticket_amount,2)}}</td>
                                <td>${{number_format((float) $c->gross_sales,2)}}</td>
                                <td>${{number_format((float) $c->collected_from_balances,2)}}</td>
                            </tr>
                            @endforeach
                                <tr>
                                    <th>Total</th>
                                    <td></td>
                                    <td>{{$totals['number_of_tickets'] }}</td>
                                    <td>${{number_format((float) $totals['total_down_payments'],2) }}</td>
                                    <td>${{number_format((float) $totals['avg_down_payments'],2) }}</td>
                                    <td>${{number_format((float) $totals['avg_ticket_amount'],2) }}</td>
                                    <td>${{number_format((float) $totals['gross_sales'],2) }}</td>
                                    <td>${{number_format((float) $totals['collected_from_balances'],2) }}</td>
                                </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-12">
                    <span>Unassigned Tickets</span>
                </div>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-light ">
                                <th>Ticket#</th>
                                <th>Date</th>
                                <th>Patient Name</th>
                                <th>Sales Counselor</th>
                                <th>Total</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($tickets as $t)
                            <tr class="bg-light-danger">
                                <th>
                                    <a href="{{route('ticket.edit',['ticket'=>$t->id,'appointment' => $t->appointment->id])}}">#{{$t->id}}</a>
                                </th>
                                <td>{{$t->date}}</td>
                                <td>{{$t->patient->name(', ')}}</td>
                                <td>Not Selected</td>
                                <td>${{number_format($t->total,2)}}</td>
                                <td>${{number_format($t->balance,2)}}</td>
                            </tr>
                            @endforeach
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
<script src="{{mix('js/report.finance.js')}}" defer></script>
@endsection
