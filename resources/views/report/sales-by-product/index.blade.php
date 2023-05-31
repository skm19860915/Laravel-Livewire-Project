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
                <div class="d-flex flex-row">    
                  <div class="d-flex ml-auto">
                    @can('export', Auth::user())           
                      <button id="export" class="btn btn-white border-dark shadow-sm text-dark d-block fz-12px">
                          <i class="fas fa-file-excel"></i>
                          Export
                      </button>
                    @endcan
                  </div>
                  <div class="d-flex">
                    <button id="daterange" data-start='{{$_start}}' data-end='{{$_end}}' class="btn btn-white border-dark shadow-sm text-dark ml-2 d-block fz-12px">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{$start}} - {{$end}}</span>
                    </button>
                  </div>
                </div>
                <div class="col-12 table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light ">
                                <th>Product Name</th>
                                <th>Orders</th>
                                <th>Average Duration</th>
                                <th>Average Sale</th>
                                <th>Total Sale</th>
                                <th>Total Down Payments</th>
                            </tr>
                        </thead>
                        <tbody class="">

                            @foreach ($product_totals as $row)

                            <tr id="product_{{ $row->id}}" class="bg-light">
                                <td><a role="button"><i class="fa fa-plus-square mr-4 row_icon"></i></a>{{ucwords(strtolower($row->name))}} - TOTAL</td>
                                <td>{{ $row->orders ?? '0' }}</td>
                                <td>{{ $row->avg_duration ?? '0' }}</td>
                                <td>${{number_format($row->avg_sale,2) ?? '0.00'}}</td>
                                <td>${{number_format($row->total_sales,2) ?? '0.00'}}</td>
                                <td>${{number_format($row->total_paid,2) ?? '0.00'}}</td>
                            </tr>
                              @foreach($detail as $detailRow)
                                @if($detailRow->id === $row->id)
                                  <tr class="product_children_{{ $row->id }} d-none">
                                      <td class="pl-4">{{ucwords(strtolower($detailRow->name))}} 
                                          @if($detailRow->reorder == 1) 
                                            - Reorder
                                          @elseif($detailRow->new_customer == 1)
                                            - New Customer 
                                          @else
                                            - Other
                                          @endif</td>
                                      <td>{{ $detailRow->orders ?? '0'}}</td>
                                      <td>{{ $detailRow->avg_duration ?? '0' }}</td>
                                      <td>${{number_format($detailRow->avg_sale,2) ?? '0.00'}}</td>
                                      <td>${{number_format($detailRow->total_sales,2) ?? '0.00'}}</td>
                                      <td>${{number_format($detailRow->total_paid,2) ?? '0.00'}}</td>
                                  </tr>
                                @endif
                              @endforeach
                            @endforeach
                            @if($totals->isEmpty())
                              <tr class="bg-light">
                                <th>TOTAL:</th>
                                <td>0</td>
                                <td>0</td>
                                <td>$0.00</td>
                                <td>$0.00</td>
                                <td>$0.00</td>
                              </tr>
                              @else
                                <tr id="product_totals" class="bg-light">
                                  @foreach($totals as $total)
                                    <th><a role="button"><i class="fa fa-plus-square mr-4 expand_totals"></i></a>TOTAL:</th>
                                    <td>{{ $total->orders ?? '0' }}</td>
                                    <td>{{ $total->avg_duration ?? '0'}}</td>
                                    <td>${{number_format($total->avg_sale,2) ?? '0.00'}}</td>
                                    <td>${{number_format($total->total_sales,2) ?? '0.00'}}</td>
                                    <td>${{number_format($total->total_paid,2) ?? '0.00'}}</td>
                                  @endforeach
                                </tr>
                                @foreach($total_details as $td)
                                  <tr class="product_totals_detail d-none bg-light">
                                      <td class="pl-4">
                                          @if($td->reorder == 1) 
                                            Reorder
                                          @elseif($td->new_customer == 1)
                                            New Customer 
                                          @else
                                            Other
                                          @endif</td>
                                      <td>{{ $td->orders ?? '0'}}</td>
                                      <td>{{ $td->avg_duration ?? '0' }}</td>
                                      <td>${{number_format($td->avg_sale,2) ?? '0.00'}}</td>
                                      <td>${{number_format($td->total_sales,2) ?? '0.00'}}</td>
                                      <td>${{number_format($td->total_paid,2) ?? '0.00'}}</td>
                                  </tr>
                                @endforeach
                              @endif
                          
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
<script src="{{mix('js/report.sales-by-product.js')}}" defer></script>
@endsection
