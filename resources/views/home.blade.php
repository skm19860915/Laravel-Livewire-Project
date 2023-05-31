@extends('layouts.auth')

@section('content')


    <div role="main" class="main-content">
        <div class="p-1">
              @include('includes.alerts')
        </div>
      <div class="page-content container container-plus">
        <!-- page header and toolbox -->
        <div class="page-header pb-2">
          <h2 class="page-title text-primary-d2 text-150">
            Dashboard
          </h2>
        </div>


        <div class="row mt-3 ">
        <div class="col-12 ">
                <div class="cards-container my-3">
                <div class="card border-0 shadow-sm radius-0">
                    <div class="card-header bgc-primary-d1">
                    <h5 class="card-title text-white">
                        <i class="fa fa-dollar-sign mr-2px"></i>
                        Sales Summary
                    </h5>
                    </div>

                    <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0 pt-2">
                        <div class="d-flex">
                            <span class="mx-3 w-180px text-center fz-14px"><b>Monthly Sales</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Monthly AVG Ticket</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Monthly Down Payment</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Monthly AVG  Down</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Monthly Patients</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Monthly Collections</b></span>
                        </div>
                        <hr>
                        <div class="d-flex">
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$monthly_sales}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$monthly_avg_ticket}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$monthly_down_payment}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$monthly_avg_down}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$monthly_patinets}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$monthly_collections}}
                                </div>
                            </span>
                        </div>
                        {{-- <hr> --}}
                        <div class="d-flex mt-3">
                            <span class="mx-3 w-180px text-center fz-14px"><b>Daily Sales</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Daily AVG Ticket</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Daily Down Payment</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Daily AVG  Down</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Daily Patients</b></span>
                            <span class="mx-3 w-180px text-center fz-14px"><b>Daily Collections</b></span>
                        </div>
                        <hr>
                        <div class="d-flex">
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$daily_sales}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$daily_avg_ticket}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$daily_down_payment}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$daily_avg_down}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$daily_patinets}}
                                </div>
                            </span>
                            <span class="mx-3 w-180px text-center fz-14px">
                                <div class="mt-n1 text-180 text-secondary-d4 text-600">
                                    {{$daily_collections}}
                                </div>
                            </span>
                        </div>

                    </div>
                </div>
                </div>
        </div>

        <div class="col-8">
                <div class="cards-container my-3">
                <div class="card border-0 shadow-sm radius-0">
                    <div class="card-header bgc-primary-d1">
                    <h5 class="card-title text-white">
                        <i class="fa fa-folder mr-2px"></i>
                        Recent Tickets
                    </h5>
                    </div>

                    <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0 pt-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ticket#</th>
                                    <th>Date</th>
                                    <th>Patient</th>
                                    <th>Counselor</th>
                                    <th>Total</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($recent_tickets as $t)
                                    <tr>
                                        <td>
                                            <a href="{{route('ticket.edit',['ticket'=>$t->id,'appointment' => $t->appointment->id])}}">#{{$t->id}}</a>
                                        </td>
                                        <td>{{$t->date}}</td>
                                        <td>{{$t->patient->name(', ')}}</td>
                                        <td>{{$t->counselorName(', ')}}</td>
                                        <td>{{$t->total('money')}}</td>
                                        <td>{{$t->balance()}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{route('ticket.index')}}" class="d-block ml-auto w-150px">View All Tickets <i class="fas fa-arrow-alt-circle-right"></i></a>
                    </div>
                </div>
                </div>
        </div>

          <div class="col-xl-4 mt-4 mt-xl-0">
                        <div class="cards-container my-3">
                            <div class="card border-0 shadow-sm radius-0">
                                <div class="card-header bgc-primary-d1">
                                    <h5 class="card-title text-white">
                                        <i class="fa fa-calendar-alt mr-2px"></i>
                                        Recent Appointments
                                    </h5>
                                        <div class="card-toolbar no-border align-self-start mt-15 mr-1">
                                        <div class="dropdown dd-backdrop dd-backdrop-none-md">
                                            <a class="btn btn-light-secondary border-0 btn-bold btn-xs dropdown-toggle" href="#" role="button" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                                            Filter By
                                            <i class="fa fa-caret-down ml-2"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-right dropdown-caret dropdown-animated dd-slide-up dd-slide-none-md">
                                            <div class="dropdown-inner" id="appointment-types-checkboxs">
                                                @foreach ($appointment_types as $t)
                                                    <label for="type{{$t->id}}" class="dropdown-item m-1">
                                                        <input id="type{{$t->id}}" type="checkbox" value="{{$t->id}}" checked >
                                                        {{$t->description}}
                                                    </label>
                                                @endforeach
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                </div>
                                <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0 pt-2">

                                    @foreach ($recent_appointment as $p)
                                        <div class="d-flex my-2 bg-light justify-content-between appointments appointment-types-{{$p->schedule_type_id}}" >
                                            <div class="d-flex">
                                                <div class="bg-primary  w-25px h-25px d-flex justify-content-center align-items-center">
                                                    <i class="fas fa-question-circle text-white m-0 p-0 mx-auto"></i>
                                                </div>
                                                <a class="ml-2" href="{{route('patient.edit',['patient' => $p->patient->id])}}">{{$p->patient->name(", ")}}</a>
                                            </div>
                                            <span class="text-muted"><i>{{$p->time}}</i></span>
                                        </div>
                                    @endforeach
                                    <a href="{{route('schedule.index')}}" class="d-block ml-auto w-200px">View All Appointments <i class="fas fa-arrow-alt-circle-right"></i></a>
                                </div>
                            </div>
                        </div>
        </div>




        </div>


      </div>

    </div>

@endsection


@section('script')

<script src="{{asset('js/dashboard.js')}}"></script>

@endsection
