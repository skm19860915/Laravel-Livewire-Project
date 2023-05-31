@extends('layouts.auth')

@section('content')
  <div role="main" class="main-content">
    <div class="page-content container container-plus ">
    <div class="page-header pb-2 no-print">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="{{route('ticket.index')}}">{{$page_name ?? ""}}</a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          {{$page_info ?? ""}}
        </small>
      </h2>
    </div>
        <div class="row w-100">

            <div class="col-12 text-right ">
                <h2>Invoice #{{$ticket->id}}</h2>
                <h2>{{$ticket->created_at}}</h2>
            </div>

            <div class="col-12">
                <hr>
            </div>

            <div class="col-12 w-100">
                <div class="row">
                    <div class="col-6">
                        <h4>Patient Info</h4>
                        <div>
                            <span>{{$patient->first_name}} {{$patient->last_name}}</span>
                        </div>
                        <div>
                            <span>P:{{$patient->home_phone}}</span>
                        </div>
                        <div>
                            <span>C:{{$patient->cell_phone}}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4>Payment Details</h4>
                        <div>
                            <span>{{$ticket->month_plan}} month(s) payment plan</span>
                        </div>
                        <div>
                            <span>{{$ticket->month_plan}} Payment(s) of ${{$ticket->payment_increments}}/month.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-2">
                <table class="table   table-striped">
                    <thead>

                    <tr>
                        <th>#</th>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Total</th>
                    </tr>

                    </thead>

                    <tbody>

                        @foreach ($items as $index => $i)
                            <tr>
                                <td>{{$index+=1}}</td>
                                <td>{{$i->item}}</td>
                                <td>{{$i->description}}</td>
                                <td>{{$i->total}}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <div class="col-12 w-100">
                <div class="row">
                    <div class="col-6">
                      <div class="w-100 bg-light h-250px p-2">
                          <h5><b>{{$clinic->location_name}}</b></h5>
                          <span> {{$clinic->address}} </span>
                          <br>
                          <span> {{$clinic->city}}, {{$clinic->state}} </span>
                          <br>
                          <span>P: {{$clinic->phone}} </span>
                          <br>
                          <span> {{$clinic->website}} </span>
                          <br>
                          <p class="mt-5 mb-0"><b>Email</b></p>
                          <span > {{$clinic->email}} </span>
                      </div>
                    </div>
                    <div class="col-6">
                        <div class="w-100   h-250px">
                            <div class="w-100   h-250px p-2 text-right">

                                <span><span><b>Total amount:</b></span> ${{$ticket->total}} </span>
                                <br>
                                <span><span><b>Paid on visit:</b></span> ${{$ticket->amount_paid_during_office_visit}} </span>
                                <br>
                                <span><span><b>Balance:</b></span> ${{$ticket->balanc_during_visit}} </span>
                                <br>
                                <span><i>First Payment of ${{$ticket->payment_increments}} due {{$ticket->first_payment_due}}</i></span>
                                <br>
                                <button type="button" class="btn btn-primary no-print" onclick="window.print()" >Print <i class="fas fa-print"></i></button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>
  </div>
@endsection
