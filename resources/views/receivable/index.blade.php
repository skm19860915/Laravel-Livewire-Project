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

    <div class="cards-container mt-3 table-responsive">
        <h4>$ Due Today</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Patient</th>
                    <th>Ticket #</th>
                    <th>Date Due</th>
                    <th>Payment Owed</th>
                    <th>Balance</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                 @foreach ($todaydue as $index => $p)

                <tr class="bg-light-danger">
                    <td>#{{$index+=1}}.</td>
                    <td>
                        <a
                        target="_blank"
                        href="{{route('patient.overview',['patient' => $p->patient_id])}}">
                            {{$p->patient_last_name.', '.$p->patient_first_name}}
                        </a>
                    </td>
                    <td>
                        <a
                        target="_blank"
                        href="{{route('ticket.edit',['ticket'=> $p->ticket_id,'appointment'=>$p->schedule_id])}}" >#{{$p->ticket_id}}</a>
                    </td>
                    <td>{{$p->due}}</td>
                    <td>${{$p->payment_owed}}</td>
                    <td>${{$p->balance}}</td>
                    <td>
                        <a
                            {{-- action="{{route('payment.create',['ticket' => $p->ticket_id])}}"
                            proccess_payment_link='{{json_encode($p)}}'
                            token="{{csrf_token()}}" --}}
                            {{-- target="_blank" --}}
                            href="{{route('payment.create',['ticket' => $p->ticket_id])}}"
                            class="btn btn-light  brc-danger-m3 border-0 border-l-3 rounded-0"
                            >
                            Proccess Payment
                        </a>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
        
        <h4><i class="fas fa-exclamation-circle"></i> Overdue</h4>
        <hr>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th>
                    <th>Patient</th>
                    <th>Ticket #</th>
                    <th>Date Due</th>
                    <th>Payment Owed</th>
                    <th>Balance</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

                 @foreach ($overdue as $index => $p)

                <tr class="bg-light-danger">
                    <td>#{{$index+=1}}.</td>
                    <td>
                        <a
                        target="_blank"
                        href="{{route('patient.overview',['patient' => $p->patient_id])}}">
                            {{$p->patient_last_name.', '.$p->patient_first_name}}
                        </a>
                    </td>
                    <td>
                        <a
                        target="_blank"
                        href="{{route('ticket.edit',['ticket'=> $p->ticket_id,'appointment'=>$p->schedule_id])}}" >#{{$p->ticket_id}}</a>
                    </td>
                    <td>{{$p->due}}</td>
                    <td>${{$p->payment_owed}}</td>
                    <td>${{$p->balance}}</td>
                    <td>
                        <a
                            {{-- action="{{route('payment.create',['ticket' => $p->ticket_id])}}"
                            proccess_payment_link='{{json_encode($p)}}'
                            token="{{csrf_token()}}" --}}
                            {{-- target="_blank" --}}
                            href="{{route('payment.create',['ticket' => $p->ticket_id])}}"
                            class="btn btn-light  brc-danger-m3 border-0 border-l-3 rounded-0"
                            >
                            Proccess Payment
                        </a>
                    </td>
                </tr>

                @endforeach
           </tbody>
        </table>
    </div>
    </div>

  </div>

@endsection

@section('script')

<script src="{{mix('js/receivable.js')}}" defer></script>

@endsection
