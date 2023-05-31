<div class="table-responsive">
  <table class="table table-bordered table-hover">
      <thead>
          <tr>
              <th>Service Name</th>
              <th>Price</th>
              <th>Receivable</th>
              <th>Description</th>
              <th>Note</th>
              <th></th>
              <th></th>
          </tr>
      </thead>
      <tbody id="services-rows" data-store='{{route('service.store')}}' data-token="{{csrf_token()}}">
          @foreach ($services as $s)
          <tr
              data-id="{{$s->id}}"
              data-delete="{{route('service.enable',[ 'service' => $s->id ])}}"
              data-update="{{route('service.update',[ 'service' => $s->id ])}}"
              data-object="{{json_encode($s)}}"
          >
              <td>{{$s->name}}</td>
              <td>${{number_format($s->price,2,'.','')}}</td>
              <td>{{$s->receivable ? "Yes" : "No"}}</td>
              <td>{{$s->description}}</td>
              <td>{{$s->note}}</td>
              <td><a href=""></a></td>
              <td><a href=""></a></td>
          </tr>
          @endforeach
      </tbody>
  </table>
</div>
<button class="d-block btn btn-success ml-auto" id="addNewFormRowForServicesTable"><i class="fas fa-plus"></i> Add</button>
