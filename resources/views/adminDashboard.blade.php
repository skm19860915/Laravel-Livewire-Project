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
                        <div class="table-responsive ">
                            <table class="table text-center" >
                                <thead>
                                    <tr>
                                        <th ><b>Location</b></th>
                                        <th><b>Monthly Sales</b></th>
                                        <th><b>Monthly AVG Ticket</b></th>
                                        <th><b>Monthly Down Payment</b></th>
                                        <th><b>Monthly AVG  Down</b></th>
                                        <th><b>Monthly Patients</b></th>
                                        <th><b>Monthly Collections</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($admin_locations as $location => $l)
                                    <tr class="text-600 ">
                                        <td>{{$location}}</td>
                                        <td class="text-180 text-secondary-d4">{{$l['month']['monthly_sales']}}</td>
                                        <td class="text-180 text-secondary-d4">{{$l['month']['monthly_avg_ticket']}}</td>
                                        <td class="text-180 text-secondary-d4">{{$l['month']['monthly_down_payment']}}</td>
                                        <td class="text-180 text-secondary-d4">{{$l['month']['monthly_avg_down']}}</td>
                                        <td class="text-180 text-secondary-d4">{{$l['month']['monthly_patients']}}</td>
                                        <td class="text-180 text-secondary-d4">{{$l['month']['monthly_collections']}}</td>
                                    </tr>
                                   @endforeach
                                </tbody>
                            </table>
                                <table class="table text-center">
                                    <thead>
                                        <tr>
                                            <th><b>Location</b></th>
                                            <th><b>Daily Sales</b></th>
                                            <th><b>Daily AVG Ticket</b></th>
                                            <th><b>Daily Down Payment</b></th>
                                            <th><b>Daily AVG  Down</b></th>
                                            <th><b>Daily Patients</b></th>
                                            <th><b>Daily Collections</b></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($admin_locations as $location => $l)
                                        <tr class="text-600">
                                            <td>{{$location}}</td>
                                            <td class="text-180 text-secondary-d4">{{$l['daily']['daily_sales']}}</td>
                                            <td class="text-180 text-secondary-d4">{{$l['daily']['daily_avg_ticket']}}</td>
                                            <td class="text-180 text-secondary-d4">{{$l['daily']['daily_down_payment']}}</td>
                                            <td class="text-180 text-secondary-d4">{{$l['daily']['daily_avg_down']}}</td>
                                            <td class="text-180 text-secondary-d4">{{$l['daily']['daily_patients']}}</td>
                                            <td class="text-180 text-secondary-d4">{{$l['daily']['daily_collections']}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                        </div>
                        </div>
                        </div>
                        </div>
        </div>

        <div class="col-12 col-md-12 col-lg-8">
                <div class="cards-container my-3">
                <div class="card border-0 shadow-sm radius-0">
                    <div class="card-header bgc-primary-d1">
                    <h5 class="card-title text-white">
                        <i class="fa fa-folder mr-2px"></i>
                        Recent Tickets
                    </h5>
                    </div>

                    <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0 pt-2">
                        <div class="table-responsive ">
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
                                @forelse ($recent_tickets as $t)
                                    <tr class="{{$t->background}}">
                                        <td>
                                            <a href="{{route('ticket.edit',['ticket'=>$t->id,'appointment' => $t->appointment->id])}}">#{{$t->id}}</a>
                                        </td>
                                        <td>{{$t->date}}</td>
                                        <td>{{$t->patient->name(', ')}}</td>
                                        <td>{{$t->counselorName(', ')}}</td>
                                        <td>{{$t->total('money')}}</td>
                                        <td>{{$t->balance()}}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7">No recent tickets found</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
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
                                                  @if(!in_array($t->id, [2,7,8]))
                                                    <label for="type{{$t->id}}" class="dropdown-item m-1">
                                                        <input id="type{{$t->id}}" type="checkbox" value="{{$t->id}}" checked >
                                                        {{str_replace(' / No Show','',$t->description)}}
                                                    </label>
                                                  @endif
                                                @endforeach
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                </div>
                                <div class="card-body px-2 py-0 pb-2 border-1 brc-primary-m3 border-t-0 pt-2">

                                    @foreach ($recent_appointment as $p)
                                        <div class="d-flex my-2 bg-light justify-content-between appointments appointment-types-{{$p->schedule_type_id}}" >
                                            @if (isset($p->patient->last_name) && isset($p->patient->first_name))
                                                <div class="d-flex">
                                                    @if($p->schedule_type_id == 8  || $p->schedule_type_id == 1)
                                                    <div class="bg-primary  w-25px h-25px d-flex justify-content-center align-items-center">
                                                        <i class="fas fa-question-circle text-white m-0 p-0 mx-auto"></i>
                                                    </div>
                                                    @endif
                                                    @if($p->schedule_type_id  == 3)
                                                    <div class="bg-success  w-25px h-25px d-flex justify-content-center align-items-center">
                                                        <i class="fas fa-check text-white m-0 p-0 mx-auto"></i>
                                                    </div>
                                                    @endif
                                                    @if($p->schedule_type_id  == 5)
                                                    <div class="bg-warning  w-25px h-25px d-flex justify-content-center align-items-center">
                                                        <i class="fas fa-share-square text-white m-0 p-0 mx-auto"></i>
                                                    </div>
                                                    @endif
                                                    @if($p->schedule_type_id  == 4)
                                                    <div class="bg-danger w-25px h-25px d-flex justify-content-center align-items-center">
                                                        <i class="fas fa-times-circle text-white m-0 p-0 mx-auto"></i>
                                                    </div>
                                                    @endif
                                                    @if($p->schedule_type_id  == 6)
                                                    <div class="appt-voicemail w-25px h-25px d-flex justify-content-center align-items-center" >
                                                        <i class="fas fa-comment text-white m-0 p-0 mx-auto"></i>
                                                    </div>
                                                    @endif

                                                    <a class="ml-2" href="{{route('appointment.edit',['appointment' => $p->id])}}">{{$p->patient->last_name ?? ""}}, {{$p->patient->first_name ?? ""}}</a>
                                                </div>
                                                <span class="text-muted"><i>{{$p->time}}</i></span>
                                            @endif
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

<script src="{{asset('js/dashboard.js')}}" defer></script>
@endsection
