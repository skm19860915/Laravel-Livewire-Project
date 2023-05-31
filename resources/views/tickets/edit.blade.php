@extends('layouts.auth')

@section('content')

    <!-- Main content -->
    <section class="w-100 p-2   no-print">

        @include('includes.alerts')
        <div class="page-header pb-2" id="page_header" data-ticket="{{ json_encode($ticket) }}">
            <h2 class="page-title text-primary-d2 text-150">
                <a href="{{ route('ticket.index') }}">{{ $page_title }}</a>
                <small class="page-info text-secondary-d2 text-nowrap">
                    <i class="fa fa-angle-double-right text-80"></i>
                    {{ $card_title }}
                </small>
            </h2>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-body p-2">
                        @can('converBackToAppt', auth()->user())
                            <a href="{{ route('ticket.delete', ['ticket' => $ticket->id]) }}" class="btn btn-success my-1">Convert Back to Appointment <i class="fas fa-sign-out-alt"></i></a>
                        @endcan
                        <a href="{{ route('ticket.view', ['ticket' => $ticket->id]) }}" class="btn btn-success my-1">View Invoice Format <i class="fas fa-external-link-alt"></i></a>
                        <a class="btn btn-success apply-payments my-1 {{ !(float) $ticket->payment_increments && !(float) $ticket->month_plan ? 'disabled' : '' }}" href="{{ route('payment.create', ['ticket' => $ticket->id]) }}">Apply Payment
                            <i class="fas fa-dollar-sign"></i></a>
                        <a class="btn btn-success payments-history my-1 {{ !(float) $ticket->payment_increments && !(float) $ticket->month_plan ? 'disabled' : '' }}" href="{{ route('payment.history', ['ticket' => $ticket->id]) }}">Payments
                            History <i class="fas fa-bars"></i></a>
                        @if (!$ticket->revisit)
                            @if (!$showRevisitButton)
                                <a href="{{ route('ticket.revisit', ['ticket' => $ticket->id]) }}" class="btn btn-success my-1 mark-for-revisit-btn ">Mark for Revisit <i class="fas fa-exclamation-circle"></i></a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @if ($ticket->revisit)
                <div class="col-12  mt-2">
                    <div class="alert alert-info" style="border-left: 8px solid #2470bd;">
                        NOTE: This ticket has been marked for a
                        'Revisit'. The purpose of a 'Revisit' is
                        to omit a customer's initial 'No Sale' or
                        'ACE' visit from reporting in the event they
                        change their mind and go through with the purchase
                        at a later date. In order to log the new appointment/ticket,
                        please create a separate appointment from the Schedule
                        screen. You will be unable to make any edits to
                        this ticket while marked as a Revisit.
                        If you need to undo this process,
                        select the 'UNDO REVISIT' button below.
                        <br>
                        <a href="{{ route('ticket.undoRevisit', ['ticket' => $ticket->id]) }}" class="btn btn-danger d-block ml-auto w-150px">UNDO REVISIT</a>
                    </div>
                </div>
            @endif
            <div class="col-12 mt-2">
                <div class="card border-0 shadow-sm radius-0 my-2">
                    <div class="card-header bgc-primary-d1">
                        <h5 class="card-title text-white ">
                            <i class="fas fa-file"></i>
                            {{ $card_title }}
                        </h5>
                    </div>
                    <div class="card-body px-2 py-2 pb-2 border-1 brc-primary-m3 border-t-0">

                        <form id="ticket_form" action="" data-action="{{ route('ticket.update', ['ticket' => $ticket->id, 'appointment' => $appointment->id]) }}" class="forms" enctype="multipart/form-data" method="post">
                            <div class="d-flex flex-column justify-content-start align-items-center">

                                <div class="col-12 d-flex align-items-end mt-2">
                                    <h4 class="m-0 mr-2">Purchase Details</h4>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>

                                <div class="col-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Date</label>
                                        <div class="col-sm-6">
                                            <input type="datepicker" id="ticket_date" name="date" value="{{ $ticket->date }}" class="form-control w-200px dates">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Sales Counselor</label>
                                        <div class="col-sm-6">
                                            <select name="sales_counselor" class="form-control">
                                                <option value="">----</option>
                                                @foreach ($users as $u)
                                                    @if (!isset($u->deleted_at))
                                                        <option value="{{ $u->id }}" {{ $u->id == $ticket->user_id ? 'selected' : '' }}>{{ $u->name() }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @foreach ($services as $s)
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">{{ $s->name }}</label>
                                            <div class="col-sm-6 d-flex align-items-center">
                                                <input type="checkbox" class="services" id="service_{{ $s->id }}" name="services" value="{{ $s->id }}" data-deleteabel="{{ $s->deleteable }}"
                                                    {{ in_array($s->id, $ticket_services_ids) ? 'checked' : '' }} data-price="{{ $s->price }}" data-receivable="{{ $s->receivable }}" class="form-control">
                                                @if ($s->receivable)
                                                    <input type="text" placeholder="$0.00"
                                                        value="{{ in_array($s->id, $ticket_services_ids)? number_format($ticket_services->where('service_id', $s->id)->where('ticket_id', $ticket->id)->first()->custom_price,2,'.',''): null }}"
                                                        id="service_{{ $s->id }}_price" class="w-100px text-center services_price" {{ in_array($s->id, $ticket_services_ids) ? '' : 'disabled' }}>
                                                @endif
                                                <span class="text-muted font-italic">{{ trim($s->note) ? "($s->note)" : '' }}</span>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Select Package(s) Sold</label>
                                        <div class="col-sm-9 ">

                                            <table class="table table-bordered table-hover  w-100">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Duration in Months (or doses for ESWT)</th>
                                                        <th>Price</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="products-rows" data-edit="true" data-products='{{ json_encode($products) }}' data-store='{{ route('product.store') }}' data-token="{{ csrf_token() }}">

                                                    @forelse ($ticket_products as $p)
                                                        <tr id="product_{{ $p->id }}">
                                                            <td>
                                                                <select name="product_{{ $p->id }}_select" id="product_{{ $p->id }}_select" class="form-control">
                                                                    @foreach ($products as $p2)
                                                                        <option value="{{ $p2->id }}" {{ $p2->id == $p->product_id ? 'selected' : '' }}>
                                                                            {{ $p2->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="amount-node">
                                                                <input type="text" value="{{ (int) $p->custom_amount }}" name="product_{{ $p->id }}_amount" id="product_{{ $p->id }}_amount" class="form-control numeric" />
                                                            </td>
                                                            <td class="price-node">
                                                                <input type="text" value=" ${{ number_format($p->custom_price, 2) }}" name="product_{{ $p->id }}_price" id="product_{{ $p->id }}_price" class="form-control currency" />

                                                            </td>
                                                            <td><a class="text-primary c-p link" id="delete_{{ $p->id }}">Delete</a></td>
                                                        </tr>
                                                    @empty
                                                        <tr class="bg-light" id="empty_row">
                                                            <td colspan="5"><small>No Sold Items dectected, Press the 'Add' button below to get started</small></td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>

                                            <button type="button" class="d-block btn btn-success ml-auto" id="addNewFormRowForProductsTable"><i class="fas fa-plus"></i> Add</button>
                                        </div>
                                    </div>

                                    <div class="form-group row {{ $ticket_products->count() > 0 ? 'd-none' : '' }}" id="recommended_treatment">
                                        <label class="col-sm-2 col-form-label mt-0">Recommended Treatment</label>
                                        <div class="col-sm-10">
                                            @foreach ($treatment_types as $tt)
                                                <div class="form-group mr-4 mb-0">
                                                    <input type="radio" id="treatment_type-{{ $tt->id }}" name="treatment_type" value="{{ $tt->id }}" class="form-control d-inline-block"
                                                        {{ $tt->id == $ticket->treatment_type_id || ($tt->description === 'None' && !$ticket->treatment_type_id)? 'checked': '' }}>
                                                    <label for="treatment_type-{{ $tt->id }}">{{ $tt->description }}</label>
                                                </div>
                                            @endforeach
                                            @foreach ($treatment_types as $tt)
                                                @if (($tt->description === 'None'))
                                                    <input type="hidden" id="selected_tt_id" value="{{$tt->id}}" />
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="form-group row" id="treatment_end_date_row">
                                        <label class="col-sm-2 col-form-label mt-0">Treatment End Date</label>
                                        <div class="col-sm-6">
                                            <input type="datepicker" id="treatment_end_date" value="{{ $ticket->treatment_end_date }}" name="treatment_end_date" class="dates form-control w-300px" />
                                            <span class="text-muted font-italic"><small>Defaults to the longest package purchased, or 3 months if only ESWT package purchased</small></span>
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
                                        <label class="col-sm-2 col-form-label">Total Due</label>
                                        <div class="col-sm-6">
                                            <input name="total" id="total" type="text" value="${{ number_format($ticket->total, 2) }}" readonly class="form-control w-200px currency">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Amount Paid During office visit</label>
                                        <div class="col-sm-6">
                                            <input id="apdov" type="text" placeholder="${{ number_format($ticket->amount_paid_during_office_visit, 2) }}" value="${{ number_format($ticket->amount_paid_during_office_visit, 2) }}" autocomplete="off"
                                                name="amount_paid_during_office_visit" class="form-control w-200px currency">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Balance during visit</label>
                                        <div class="col-sm-6">
                                            <input type="text" id="bdv" placeholder="${{ number_format($ticket->balanc_during_visit, 2) }}" value="${{ number_format($ticket->balanc_during_visit, 2) }}" id="bdv" name="balanc_during_visit" readonly
                                                class="form-control w-200px currency" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Pay Increments</label>
                                        <div class="col-sm-6">
                                            <select name="payment_increments" id="payment_increments" class="form-control w-300px" data-update='{{ json_encode($ticket) }}'>
                                                {{-- @for ($i = 1; $i < 13; $i++)
                                                        <option value="{{$amount}},{{$index}}">{{$index}} Month{{$index > 1 ? 's':''}} - ${{$amount}}</option>
                                                    @endfor --}}
                                            </select>
                                            <small class="text-muted font-italic">Increments do not include today's visit as payment date. (i.e The month plan includes both today's payment and the following month's payment)</small>
                                        </div>
                                    </div>
                                    <div class="form-group row firstPaymentDue">
                                        <label class="col-sm-2 col-form-label">First Payment Due</label>
                                        <div class="col-sm-6">
                                            <input type="datepicker" id="firstPaymentDueDate" autocomplete="off" value="{{ $ticket->first_payment_due }}" name="first_payment_due" class="dates form-control w-300px" />
                                        </div>
                                    </div>

                                </div>



                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-primary submit-buttons">Save Changes</button>
                                </div>

                        </form>

                        <div class="col-6 align-self-start mt-4" id="form_errors">
                        </div>

                    </div>
                </div>
                <!-- /.card -->
            </div>
            <div class="col-12 mt-2 p-0">
                <div class="alert alert-info" style="border-left: 8px solid #2470bd;">
                    <b>Patient Name:</b> <span>{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</span><br>
                    <b>Patient Notes:</b> <span>{{ $appointment->patient->patient_note }}</span><br>
                    <b>Appointment Notes:</b> <span>{{ $appointment->note }}</span><br>
                    <b>Appointment Was Created:</b> <span>{{ $appointment->getDate($appointment, 'created_at') }}</span><br>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('script')
    <script src="{{ mix('js/tickets.js') }}" defer></script>
@endsection
