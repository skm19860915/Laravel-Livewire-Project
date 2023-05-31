@extends('layouts.auth')

@section('content')

<!-- Main content -->
<section class="w-100 p-2   no-print">
    @include('includes.alerts')
<div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="{{route('receivable.index')}}">{{$page_title ?? ""}}</a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          {!!$page_info ?? ""!!}
        </small>
      </h2>
</div>
    <a  href="{{route('ticket.edit',['ticket'=> $ticket->id,'appointment'=>$ticket->appointment->id])}}" class="btn btn-success mt-4">View Ticket <i class="fas fa-folder"></i></a>
<form
    data-increment="{{$suggest_payment}}"
    class="forms"
    balance="{{$remaining_balance}}"
    action="{{route('payment.store',['ticket'=> $ticket->id])}}"
    method="post">
    @csrf
      <div class="cards-container mt-3">
      <div class="card border-0 shadow-sm radius-0 ">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            <i class="fa fa-dollar-sign mr-2px"></i>
            {{$card_title ?? ""}}
          </h5>
        </div>
            <table class="table table-bordered mb-0">
                <tbody>
                    <tr>
                        <td class="text-right"><b>Date of Payment</b></td>
                        <td>
                            <input type="datepicker" class="form-control dates w-450px" value="{{now()->format('m/d/Y')}}" name="date" id="">
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"><b>Payment Amount</b></td>
                        <td>
                            <div class=" d-flex align-items-center">
                                <input type="number" class="form-control dates w-450px m-0" step="any"  placeholder="0.00"  name="amount">
                                <span>$</span>
                            </div>
                            <br>
                            <span class="text-muted mt-0">Suggested Payment: ${{$suggest_payment}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right"><b>Remaining Balance</b></td>
                        <td class="d-flex align-items-center">
                            <input type="text" class="form-control  w-450px" value="{{$remaining_balance}}" placeholder="0.00" readonly name="remaining_balance" id="">
                            <span>$</span>
                        </td>
                    </tr>
                    <tr>
                         <td colspan="2" class="bg-light">
                            <button
                                    type="submit"
                                    class="submit-buttons btn btn-primary  d-block mx-auto"
                                    style="margin-right: 59% !important;">Apply Payment</button>
                        </td>
                    </tr>
                </tbody>
            </table>
      </div>
    </div>
</form>
    <div class="cards-container mt-3" data-history="{{$scroll_into_history ?? 'false'}}">
      <div class="card border-0 shadow-sm radius-0 ">
        <div class="card-header bgc-primary-d1">
          <h5 class="card-title text-white">
            <i class="fa fa-list mr-2px"></i>
            Payment History
          </h5>
        </div>
            <table class="table table-bordered table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Amount</th>
                         <th>Balance</th>
                        <th>Payment Left (@ {{$suggest_payment}})</th>
                        <th>Payment Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($payments as $index => $p)
                    <tr class="{{ (isset($p->refund) && $p->refund == 1) ? 'text-danger' : '' }}">
                        <td>{{$index+=1}}.</td>
                        <td>{{$p->date}}</td>
                        <td>
                            @isset($p->refund)
                                @if ($p->refund)
                                    -
                                @endif
                            @endisset
                            ${{number_format($p->amount,2)}}</td>
                        <td>${{number_format($p->remaining_balance,2)}}</td>
                        <td>
                                @if ($p->on_visit)
                                        {{$p->month_plan}}
                                        @else
                                        {{$p->payment_left}}
                                @endif
                        </td>
                        <td>
                            @isset($p->on_visit)
                                @if ($p->on_visit)
                                  <span class="badge badge-pill badge-success">Down Payment</span>
                                @elseif(isset($p->refund) && $p->refund == 1)
                                  <span class="badge badge-pill badge-secondary">Payment Refunded</span>
                                @else
                                  <span class="badge badge-pill badge-primary">Payment Plan</span>
                                @endif
                            @endisset
                        </td>
                        <td>
                            @isset($p->refund)
                              @if($p->refund == 0)
                                <a class="btn btn-danger btn-sm ml-4" data-payment="{{$p->id}}" href="{{route('payment.refund',['payment' => $p->id])}}">Refund</a>
                              @endif
                            @endisset
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
      </div>
    </div>

    <!-- /.row -->


    {{-- form --}}


<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <p class="modal-title text-center">Are you sure you would like to refund this payment?</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-footer bg-white border-0">
         <form action="" id="refundForm" method="get">
             @csrf
            <button type="submit" class="btn btn-primary">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
         </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="confirmFormModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header border-0">
        <p class="modal-title text-center">Are You Sure You Would like To Refund This Payment?</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-footer bg-white border-0">
         <form action="" id="refundForm" method="get">
             @csrf
            <button type="submit" class="btn btn-primary">Yes</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
         </form>
      </div>
    </div>
  </div>
</div>
</section>
@endsection

@section('script')

<script src="{{mix('js/payment.js')}}" defer></script>


@endsection
