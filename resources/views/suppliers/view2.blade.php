@extends('layouts.app')
@section('content')

<style>
  @media only screen and (min-width : 768px) {
      .is-table-row {
          display: table;
          width: 100%;
          margin-bottom: 10px;
      }
      .is-table-row [class*="col-"] {
          float: none;
          display: table-cell;
          vertical-align: top;
      }
  }
  .no-mergin {
    margin: 0px;
  }
  .center-right {
    text-align: right;
    vertical-align: middle;
  }
</style>

<div class="box-header with-border">
  <div class="box-header-tools pull-left" >
    <a href="{{ url('suppliers') }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
  </div>
</div>

<div class="col-md-3">
  <div class="box box-primary">
    <div class="box-body box-profile">

      <h3 class="profile-username text-center">{{ $supplier->name }}</h3>
      <ul class="list-group list-group-unbordered">
        <li class="list-group-item">
          <b>{{ Lang::get('core.type') }}</b> 
          <a {{-- href="#"  --}}class="pull-right">{{ $supplier->type->supplier_type }}</a>
        </li>                
        <li class="list-group-item">
          <b>{{ Lang::get('core.city') }}</b> 
          <a {{-- href="#"  --}}target="_blank" class="pull-right">{{ $supplier->city->city_name }}</a>
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.country') }}</b> 
          <a {{-- href="#"  --}}class="pull-right">{{ $supplier->country->country_name }}</a>
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.phone') }}</b> 
          <a {{-- href="#" --}} class="pull-right">{{ $supplier->phone }}</a>
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.address') }}</b> 
          <a {{-- href="#" --}} class="pull-right">{{ $supplier->address}}</a>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="col-md-9">

  <div class="box box-warning">
    <div class="box-header with-border">
      <h3 style="margin-top: 0px;">Services</h3>
    </div>

    <div class="box-body" >
      <div class="row">
        <table class="table table-striped">
          <tbody>
            <tr>
              <th style="width: 10px">#</th>
              <th>{{ Lang::get('core.name') }}</th>
              <th>{{ Lang::get('core.description') }}</th>
              <th>{{ Lang::get('core.startdate') }}</th>
              <th>{{ Lang::get('core.expdate') }}</th>
              <th>{{ Lang::get('core.price') }}</th>
              <th>{{ Lang::get('core.min_quantity') }}</th>
              <th>{{ Lang::get('core.max_quantity') }}</th>
              <th>{{ Lang::get('core.document') }}</th>
              <th>{{ Lang::get('core.action') }}</th>
              <th>{{ Lang::get('core.status') }}</th>
            </tr>
            <?php $num = 1; ?>
            @foreach($supplier->services as $service)
              <tr>
                <td>{{ $num++ }}</td>
                <td>{{ $service->name }}</td>
                <td>{{ $service->description }}</td>
                <td>{{ $service->start_date->format('d M Y') }}</td>
                <td>{{ $service->end_date->format('d M Y') }}</td>
                <td>{{ $symbol }} {{ $service->price }}</td>
                <td>{{ $service->min_quantity }} Pax</td>
                <td>{{ $service->max_quantity }} Pax</td>
                <td><a href="{{ $service->documentUrl }}">{{ $service->document }}</a></td>
                <td>
                  <button type="button" class="btn btn-primary btn-sm tips" data-toggle="modal" data-target="#service_{{ $service->id }}" title="Update"><i class="fa fa-file-o"></i></button>
                  <a href="{{ url('suppliers/deleteservice/'.$supplier->supplierID.'?an_id='.$service->id) }}" class="btn btn-danger btn-sm tips" title="Delete"><i class="fa fa-close"></i></a>
                </td>
                <td>{!! $service->statusLabel !!}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <button type="button" class="btn btn-primary pull-right" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Service</button>
      </div>
    </div>  
  </div>
</div>  

{{-- <a class="btn btn-xs btn-default tips" title="Upload Policy" data-toggle="modal" data-target="#myModal"><i class="fa fa-file-o fa-lg text-blue"></i>Upload Policy</a> --}}

<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content animated bounceInRight">
        <form method="POST" enctype="multipart/form-data" action="{{ url('suppliers/addservice/'.$supplier->supplierID) }}">
          {{csrf_field()}}
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title">Add Service</h4>
                  <small class="font-bold">Add service for this supplier</small>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-8 col-md-push-2">
                    <div class="row is-table-row">
                      <div class="col-md-4 center-right">
                        <label class="no-margin">{{ Lang::get('core.name') }}</label> 
                      </div>
                      <div class="col-md-8">
                        <div class=""><input required type="text" class="form-control" name="name" id="name"></div>
                      </div>
                    </div>
                    <div class="row is-table-row">
                      <div class="col-md-4 center-right">
                        <label class="no-margin">{{ Lang::get('core.description') }}</label> 
                      </div>
                      <div class="col-md-8">
                        <div class=""><input required type="text" class="form-control" name="description" id="description"></div>
                      </div>
                    </div>
                    <div class="row is-table-row">
                      <div class="col-md-4 center-right">
                        <label class="no-margin">{{ Lang::get('core.startdate') }}</label> 
                      </div>
                      <div class="col-md-8">
                        <div class=""><input required type="date" class="form-control" name="start_date" id="start_date" onkeydown="return false"></div>
                      </div>
                    </div>
                    <div class="row is-table-row">
                      <div class="col-md-4 center-right">
                        <label class="no-margin">{{ Lang::get('core.expdate') }}</label> 
                      </div>
                      <div class="col-md-8">
                        <div class=""><input required type="date" class="form-control" name="end_date" id="end_date" onkeydown="return false"></div>
                      </div>
                    </div>
                    <div class="row is-table-row">
                      <div class="col-md-4 center-right">
                        <label class="no-margin">{{ Lang::get('core.price') }}</label> 
                      </div>
                      <div class="col-md-8">
                        <div class=""><input required type="number" class="form-control" name="price" id="price" step=".01"></div>
                      </div>
                    </div>
                    <div class="row is-table-row">
                      <div class="col-md-4 center-right">
                        <label class="no-margin">{{ Lang::get('core.min_quantity') }}</label> 
                      </div>
                      <div class="col-md-8">
                        <div class=""><input required type="number" class="form-control" name="min_quantity" id="min_quantity"></div>
                      </div>
                    </div>
                    <div class="row is-table-row">
                      <div class="col-md-4 center-right">
                        <label class="no-margin">{{ Lang::get('core.max_quantity') }}</label> 
                      </div>
                      <div class="col-md-8">
                        <div class=""><input required type="number" class="form-control" name="max_quantity" id="max_quantity"></div>
                      </div>
                    </div>
                    <div class="row is-table-row">
                      <div class="col-md-4 center-right">
                        <label class="no-margin">Upload Document</label> 
                      </div>
                      <div class="col-md-8">
                        <div class=""><input type="file" name="document" id="document"></div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-12" style="text-align: center;">
                        <input type="radio" name="status" value="1" class="form-control" required> Active &nbsp 
                        <input type="radio" name="status" value="0" class="form-control" required> Inactive
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Add Service</button>
              </div>
            </form>
        </div>
    </div>
</div>

@foreach($supplier->services as $service)
  <div class="modal inmodal" id="service_{{ $service->id }}" tabindex="-1" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
          <form method="POST" enctype="multipart/form-data" action="{{ url('suppliers/editservice/'.$supplier->supplierID) }}">
            {{csrf_field()}}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title">Edit Service</h4>
                    <small class="font-bold">Edit this service</small>
                </div>
                <input type="hidden" name="service_id" value="{{ $service->id }}">
                <div class="modal-body">
                  <div class="row">
                    <div class="col-md-8 col-md-push-2">
                      <div class="row is-table-row">
                        <div class="col-md-4 center-right">
                          <label class="no-margin">{{ Lang::get('core.name') }}</label> 
                        </div>
                        <div class="col-md-8">
                          <div class=""><input required type="text" class="form-control" name="name" id="name" value="{{ $service->name }}"></div>
                        </div>
                      </div>
                      <div class="row is-table-row">
                        <div class="col-md-4 center-right">
                          <label class="no-margin">{{ Lang::get('core.description') }}</label> 
                        </div>
                        <div class="col-md-8">
                          <div class=""><input required type="text" class="form-control" name="description" id="description" value="{{ $service->description }}"></div>
                        </div>
                      </div>
                      <div class="row is-table-row">
                        <div class="col-md-4 center-right">
                          <label class="no-margin">{{ Lang::get('core.startdate') }}</label> 
                        </div>
                        <div class="col-md-8">
                          <div class=""><input required type="date" class="form-control" name="start_date" id="start_date" onkeydown="return false"  value="{{ $service->start_date->format('Y-m-d') }}"></div>
                        </div>
                      </div>
                      <div class="row is-table-row">
                        <div class="col-md-4 center-right">
                          <label class="no-margin">{{ Lang::get('core.expdate') }}</label> 
                        </div>
                        <div class="col-md-8">
                          <div class=""><input required type="date" class="form-control" name="end_date" id="end_date" onkeydown="return false" value="{{ $service->end_date->format('Y-m-d') }}"></div>
                        </div>
                      </div>
                      <div class="row is-table-row">
                        <div class="col-md-4 center-right">
                          <label class="no-margin">{{ Lang::get('core.price') }}</label> 
                        </div>
                        <div class="col-md-8">
                          <div class=""><input required type="number" class="form-control" name="price" id="price" value="{{ $service->price }}" step=".01"></div>
                        </div>
                      </div>
                      <div class="row is-table-row">
                        <div class="col-md-4 center-right">
                          <label class="no-margin">{{ Lang::get('core.min_quantity') }}</label> 
                        </div>
                        <div class="col-md-8">
                          <div class=""><input required type="number" class="form-control" name="min_quantity" id="min_quantity" value="{{ $service->min_quantity }}"></div>
                        </div>
                      </div>
                      <div class="row is-table-row">
                        <div class="col-md-4 center-right">
                          <label class="no-margin">{{ Lang::get('core.max_quantity') }}</label> 
                        </div>
                        <div class="col-md-8">
                          <div class=""><input required type="number" class="form-control" name="max_quantity" id="max_quantity" value="{{ $service->max_quantity }}"></div>
                        </div>
                      </div>
                      <div class="row is-table-row">
                        <div class="col-md-4 center-right">
                          <label class="no-margin">Upload Document</label> 
                        </div>
                        <div class="col-md-8">
                          <div class=""><input type="file" name="document" id="document"></div>
                          <span>{{ $service->document }}</span>
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-md-12" style="text-align: center;">
                          <input type="radio" name="status" value="1" class="form-control" required @if($service->status == 1) checked @endif> Active &nbsp 
                          <input type="radio" name="status" value="0" class="form-control" required @if($service->status == 0) checked @endif> Inactive
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Service</button>
                </div>
              </form>
          </div>
      </div>
  </div>
@endforeach

{{-- <script>
  var ctx = document.getElementById("booking");
  var booking = new Chart(ctx, {
      type: 'doughnut',
      data: {
              labels: ["{{ Lang::get('core.booked') }}","{{ Lang::get('core.available') }}"],
      datasets: [{
          data: [{{$total}}, {{ $row->total_capacity}}-{{$total}}],
          backgroundColor: [
                  '#fb6b5b',
                  '#65bd77'
          ],

      }],
  }});
</script> --}}
@endsection