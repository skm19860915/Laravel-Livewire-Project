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
                <div class="col-12 d-flex flex-row justify-content-end">
                    <div class="d-flex">
                        <input type="radio" name="range" value="WoW" id="WoW" class="form-control" @if(app('request')->input('type') == 'WoW') checked="" @endif>
                        <label for="WoW">Week Over Week</label>
                        <input type="radio" name="range" value="MoM" id="MoM" class="form-control ml-3" @if(app('request')->input('type') == 'MoM') checked="" @endif>
                        <label for="MoM">Month Over Month</label>
                    </div>
                </div>
     
                <div class="col-12 table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="bg-light text-secondary">
                                <th>Source</th>
                                <th>Appointments Booked Between {{ $priorPeriodStart }} and {{ $thisPeriodStart }}</th>
                                <th>Appointments Booked since {{ $thisPeriodStart }}</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sources as $source)
                              @if($source->trend < 0)
                                <tr class="table-danger text-secondary">
                              @elseif($source->trend >= 100.00)
                                <tr class="table-success  text-secondary">
                              @else
                                <tr class="text-secondary">
                              @endif
                                <td>{{ $source->description }}</td>
                                <td>{{ $source->priorPeriod }}</td>
                                <td>{{ $source->thisPeriod }}</td>
                                <td>
                                  @if($source->trend < 0)
                                    <span class="text-secondary">{{ $source->trend }}% <i class="nav-icon fa fa-arrow-down"></i></span>
                                  @elseif($source->trend > 100.00)
                                  <span class="text-secondary">{{ $source->trend }}% <i class="nav-icon fa fa-arrow-up"></i></span>
                                  @else 
                                    {{ $source->trend }}%
                                  @endif  
                                </td>
                              </tr>
                            @empty
                              <tr><td colspan="5">No data found.</td></tr>
                            @endforelse
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
<script src="{{mix('js/report.marketing-trend.js')}}" defer></script>
@endsection
