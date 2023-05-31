@extends('layouts.auth')

@section('content')

<!-- Main content -->
<section class="w-100 p-2   no-print">

    @include('includes.alerts')
<div class="page-header pb-2">
      <h2 class="page-title text-primary-d2 text-150">
        <a href="http://pryapus.test/patients">{{$page_title}}</a>
        <small class="page-info text-secondary-d2 text-nowrap">
          <i class="fa fa-angle-double-right text-80"></i>
          {{$card_title}}
        </small>
      </h2>
    </div>
    <div class="row">
          <div class="col-12">
            <div class="card card-primary ">
                <div class="card-body p-2">
                  {{-- <a href="#" class="btn btn-success my-1">Reschedule / Cancellation</a> --}}
                  <a href="{{route('ticket-info.delete',['ticket'=> $ticket->id])}}"   class="btn btn-success my-1">Covert Back to Appointment <i class="fas fa-sign-out-alt"></i></a>
                  <a class="btn btn-success my-1" >View Invoice Format <i class="fas fa-external-link-alt"></i></a>
                  <a class="btn btn-success my-1" >Apply Payment <i class="fas fa-dollar-sign"></i></a>
                  <a class="btn btn-success my-1" >Payments Histroy <i class="fas fa-bars"></i></a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <div class="col-12 mt-2">
                <div class="card border-0 shadow-sm radius-0 my-2">
                        <div class="card-header bgc-primary-d1">
                            <h5 class="card-title text-white ">
                                <i class="fas fa-file"></i>
                                {{$card_title}}
                            </h5>
                        </div>
                        <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0">

                            <form action="{{route('ticket-info.update',[ 'ticket' => $ticket->id ,'appointment' => $appointment->id])}}" class="forms" enctype="multipart/form-data" method="post">
                                @csrf
                            <div class="d-flex flex-column justify-content-start align-items-center">

                                <div class="col-12 d-flex align-items-end mt-2">
                                    <h4 class="m-0 mr-2">Purchase Details</h4>

                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label   class="col-sm-2 col-form-label">Date</label>
                                        <div class="col-sm-6">
                                              <input type="datepicker" name="date" value="{{now()->format('m/d/Y')}}" class="form-control w-200px dates">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label   class="col-sm-2 col-form-label">Sales Counselor</label>
                                        <div class="col-sm-6">
                                              <select  name="sales_counselor"  class="form-control">
                                                    <option value="">----</option>
                                                @foreach ($users as $u)
                                                    <option value="{{$u->id}}" {{$u->id == $ticket->user_id ? "selected" : ""}}>{{$u->first_name , $u->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @foreach ($services as $s)

                                    <div class="form-group row">
                                        <label   class="col-sm-2 col-form-label">{{$s->name}}</label>
                                        <div class="col-sm-6 d-flex align-items-center">
                                              <input type="checkbox" name="services[]" value="{{$s->id}}"  {{in_array($s->id,$ticket_services) ? "checked" : ""}}  data-price="{{$s->price}}" class="form-control">
                                              <input type="text" placeholder="{{$s->price}}" class="w-100px text-center" disabled>
                                              <span class="ml-2">{{trim($s->note) ? "($s->note)" : ""}}</span>
                                        </div>
                                    </div>

                                    @endforeach


                                    <div class="form-group row">
                                        <label   class="col-sm-2 col-form-label">Select Package(s) Sold</label>
                                                <div class="col-sm-9 ">

                                                <table class="table table-bordered table-hover  w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Product Name</th>
                                                            <th>Dose/Month Amount</th>
                                                            <th>Price</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="products-rows" data-products='{{json_encode($products)}}' data-store='{{route('product.store')}}' data-token="{{csrf_token()}}">

                                                        @foreach ($ticket_products as $p)

                                                        <tr>
                                                            <td>
                                                                <select name="products[]" id="" class="form-control">
                                                                        @foreach ($products as $p2)
                                                                            <option value="{{$p2->id}}" {{$p2->id == $p->product_id ? 'selected' : ''}}>{{$p2->name}}</option>
                                                                        @endforeach
                                                                </select>
                                                            </td>
                                                            <td>{{$products->where('id', $p->product_id)->first()->amount}}</td>
                                                            <td>{{$products->where('id', $p->product_id)->first()->price}}</td>
                                                            <td><a href=""></a></td>
                                                        </tr>

                                                        @endforeach
                                                    </tbody>
                                                </table>

                                                <button type="button" class="d-block btn btn-success ml-auto" id="addNewFormRowForProductsTable"><i class="fas fa-plus"></i> Add</button>
                                            </div>
                                    </div>

                                    <div class="form-group row">
                                        <label   class="col-sm-2 col-form-label">Total</label>
                                        <div class="col-sm-6">
                                              <input  name="total" type="number" value="{{$ticket->total}}" step='any' placeholder="$0.00"    readonly  class="form-control w-200px">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex align-items-end mt-2">
                                    <h4 class="m-0 mr-2">Payment Info</h4>

                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label   class="col-sm-2 col-form-label">Amount Paid During office visit</label>
                                        <div class="col-sm-6">
                                              <input type="number" id="apdov" value="{{$ticket->amount_paid_during_office_visit}}" autocomplete="off" value="0" name="amount_paid_during_office_visit" value="" class="form-control w-200px">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label   class="col-sm-2 col-form-label">Balance during visit</label>
                                        <div class="col-sm-6">
                                              <input type="number" value="00.00" id="bdv"  name="balanc_during_visit" step='any' readonly value="" placeholder="$0.00" class="form-control w-200px"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label   class="col-sm-2 col-form-label">Pay Increments</label>
                                        <div class="col-sm-6">
                                              <select  name="payment_increments"  class="form-control w-300px">

                                            </select>
                                            <small class="text-muted">Increments do not include today's visit as payment date. (i.e The month plan includes both today's payment and the  following month's payment)</small>
                                        </div>
                                    </div>




                                </div>

                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-primary submit-buttons">Save Changes</button>
                                </div>

                            </form>

                        </div>
                    </div>
            <!-- /.card -->
          </div>
          <div class="col-12 mt-2 p-0">
              <div class="alert alert-info" style="border-left: 8px solid #2470bd;">

                <b>Patient Name:</b> <span>{{$appointment->patient->first_name}} {{$appointment->patient->last_name}}</span><br>
                <b>Patient Notes:</b> <span>{{$appointment->patient->patient_note}}</span><br>
                <b>Appointment Notes:</b> <span>{{$appointment->note}}</span><br>
                <b>Appointment Was Created:</b> <span>{{$appointment->getDate($appointment,'created_at')}}</span><br>
               </div>
          </div>
          <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@endsection

@section('script')
<script src="{{mix('js/ticket_info.js')}}" defer></script>

@endsection
