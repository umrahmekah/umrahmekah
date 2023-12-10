@extends('layouts.app')
@section('content')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
      <section class="content-header">
        <h1> {{ SiteHelpers::formatLookUp($row->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }}</h1>
      </section>
<div class="box-header with-border">
  <div class="box-header-tools pull-left" >
    <a href="{{ url('tourdates?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
    @if($total!=0)
      @if($piform)
        <a href="/piform/show/{{$piform->id}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.view_pi_form') }}"><i class="fa fa-file-text-o fa-lg text-blue"></i> {{ Lang::get('core.pi_form') }}</a>
      @else
        <a href="/piform/update/?tourdate={{$id}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.create_pi_form') }}"><i class="fa fa-file-text-o fa-lg text-blue"></i> {{ Lang::get('core.create_pi_form') }}</a>
      @endif
      <a href="/tourdates/roomarrange/{{$id}}" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.manage_room') }}"><i class="fa fa-bed fa-lg text-blue"></i> {{ Lang::get('core.manage_room') }}</a>
    @endif
    @if($tourdate->policy)
      <a href="{{ url('tourdates/downloadpolicy/'.$tourdate->tourdateID) }}" class="btn btn-xs btn-default tips" title="Download Policy"><i class="fa fa-file-o fa-lg text-blue"></i>{{ Lang::get('core.download_policy') }}</a>
    @else
      <a class="btn btn-xs btn-default tips" title="{{ Lang::get('core.upload_policy') }}" data-toggle="modal" data-target="#myModal"><i class="fa fa-file-o fa-lg text-blue"></i>{{ Lang::get('core.upload_policy') }}</a>
    @endif
      <a class="btn btn-xs btn-default tips" title="{{ Lang::get('core.set_discount') }}" data-toggle="modal" data-target="#set_discount"><i class="fa fa-tag fa-o fa-lg text-blue"></i>{{ Lang::get('core.set_discount') }}</a>
  </div>	

  <div class="box-header-tools pull-right " >
    <a href="{{ ($prevnext['prev'] != '' ? url('tourdates/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="{{ Lang::get('core.previous') }}"><i class="fa fa-arrow-left fa-2x"></i>  </a>	
    <a href="{{ ($prevnext['next'] != '' ? url('tourdates/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="{{ Lang::get('core.next') }}"> <i class="fa fa-arrow-right fa-2x"></i>  </a>
  </div> 
</div>
<div class="col-md-3">
  <div class="box box-primary">
    <div class="box-body box-profile">
      <canvas id="booking"></canvas>

      <h3 class="profile-username text-center">{{ SiteHelpers::formatLookUp($row->tourID,'tourID','1:tours:tourID:tour_name') }}</h3>
      <p class="text-muted text-center">{{ $row->tour_code}}</p>
      <p class="text-muted text-center">{{ SiteHelpers::TarihFormat($row->start)}} - {{ SiteHelpers::TarihFormat($row->end)}}</p>
      <ul class="list-group list-group-unbordered">
        <li class="list-group-item">
          <b>{{ Lang::get('core.status') }}</b> 
          <a href="#" class="pull-right">{!! GeneralStatus::Tour($row->status,$row->start,$row->end,$row->tourdateID, $row->total_capacity, $total) !!}</a>
        </li>                
        <li class="list-group-item">
          <b>{{ Lang::get('core.guide') }}</b> 
          <a href="{{ url('guide/show/'.$row->guideID)}}" target="_blank" class="pull-right">{{ SiteHelpers::formatLookUp($row->guideID,'guideID','1:guides:guideID:name') }}</a>
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.featured') }}</b> 
          <a href="#" class="pull-right">{!! SiteHelpers::Featured($row->featured) !!}</a>
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.definitedeparture') }}</b> 
          <a href="#" class="pull-right">{!! SiteHelpers::Definite_departure($row->definite_departure) !!}</a>
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.capacity') }}</b> 
          <a href="#" class="pull-right">{{ $row->total_capacity}}</a>
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.singleroom') }}</b> 
          @if($tourdate->discount)
          <a href="#" class="pull-right">{{ $tourdate->discount_price[0]}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @else
          <a href="#" class="pull-right">{{ $row->cost_single}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @endif
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.doubleroom') }}</b>
          @if($tourdate->discount)
          <a href="#" class="pull-right">{{ $tourdate->discount_price[1]}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a> 
          @else
          <a href="#" class="pull-right">{{ $row->cost_double}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @endif
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.tripleroom') }}</b> 
          @if($tourdate->discount)
          <a href="#" class="pull-right">{{ $tourdate->discount_price[2]}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @else
          <a href="#" class="pull-right">{{ $row->cost_triple}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @endif
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.quadroom') }}</b> 
          @if($tourdate->discount)
          <a href="#" class="pull-right">{{ $tourdate->discount_price[3]}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @else
          <a href="#" class="pull-right">{{ $row->cost_quad}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @endif
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.quintroom') }}</b> 
          @if($tourdate->discount)
          <a href="#" class="pull-right">{{ $tourdate->discount_price[4]}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @else
          <a href="#" class="pull-right">{{ $row->cost_quint}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @endif
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.sextroom') }}</b> 
          @if($tourdate->discount)
          <a href="#" class="pull-right">{{ $tourdate->discount_price[5]}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @else
          <a href="#" class="pull-right">{{ $row->cost_sext}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @endif
        </li>
        <li class="list-group-item">
          <b>{{ Lang::get('core.child') }}</b> 
          @if($tourdate->discount)
          <a href="#" class="pull-right">{{ $tourdate->discount_price[6]}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @else
          <a href="#" class="pull-right">{{ $row->cost_child}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
          @endif
        </li>    
        <li class="list-group-item">
          <b>Discount</b> 
          <a href="#" class="pull-right">{{ $tourdate->discount}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
        </li>  
      </ul>
    </div>
  </div>
</div>
<div class="col-md-9">

  @if($tourdate->existBookingNeedsAttention)
    <div class="alert alert-warning">
      There are bookings needs your attention. Please go to booking list to see which booking needs your attention.
    </div>
  @endif

  <div class="box box-warning">
    <div class="box-header with-border">
      <div class="row">
        <div class="col-md-12" style="margin-left: 5px;">
          @if($total!=0)
            {{-- <a href="{{ url('tourdates/show/'.$id.'?bookinglist=true')}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.bookinglist') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.bookinglist') }}</a> --}}
            <a href="{{ url('tourdates/masterlist/'.$id)}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.master_list') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.master_list') }}</a>
            <a href="{{ url('tourdates/masterlistexcel/'.$id)}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.master_list') }} Excel"><i class="fa fa-file-excel-o fa-lg text-green"></i> {{ Lang::get('core.master_list') }}</a>
            <a href="{{ url('tourdates/visalist/'.$id)}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.visalist') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.visalist') }}</a>
            <a href="{{ url('tourdates/show/'.$id.'?passportlist=true')}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.passportlist') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.passportlist') }}</a>
            <a href="{{ url('tourdates/show/'.$id.'?emergencylist=true')}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.otherdetails') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.otherdetails') }}</a>
            <a href="{{ url('tourdates/insurancelist/'.$id)}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.insurancelist') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.insurancelist') }}</a>
            <a href="{{ url('tourdates/insuranceexcel/'.$id)}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.insurancelist') }}"><i class="fa fa-file-excel-o fa-lg text-green"></i> {{ Lang::get('core.insurancelist') }}</a>
          @endif
          <a href="/tourdates/bookinglist/{{$id}}" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.booking_list') }}"><i class="fa fa-list fa-lg text-blue"></i> {{ Lang::get('core.booking_list') }}</a>
          <a href="/tourdates/exportticketmanifest/{{$id}}" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.ticket_manifest') }}"><i class="fa fa-file-excel-o fa-lg text-green"></i> {{ Lang::get('core.ticket_manifest') }}</a>
        </div>
      </div>
    </div>

    <div class="box-body" > 	
      {{-- <h3 class="profile-username">{{ Lang::get('core.bookinglist') }}</h3> --}}
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green">
              <i class="fa fa-user" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">{{ Lang::get('core.singleroom') }}</span>
              <span class="info-box-number">{{ $room_type[1]}}</span>
            </div>
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green">
              <i class="fa fa-users" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">{{ Lang::get('core.doubleroom') }}</span>
              <span class="info-box-number">{{ $room_type[2]}}</span>
            </div>
          </div>
        </div>
        <div class="clearfix visible-sm-block"></div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green">
              <i class="fa fa-user" aria-hidden="true"></i>
              <i class="fa fa-users" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">{{ Lang::get('core.tripleroom') }}</span>
              <span class="info-box-number">{{ $room_type[3]}}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green">
              <i class="fa fa-users" aria-hidden="true"></i>
              <i class="fa fa-users" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">{{ Lang::get('core.quadroom') }}</span>
              <span class="info-box-number">{{ $room_type[4]}}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green">
              <i class="fa fa-user" aria-hidden="true"></i>
              <i class="fa fa-users" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">{{ Lang::get('core.quintroom') }}</span>
              <span class="info-box-number">{{ $room_type[5]}}</span>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green">
              <i class="fa fa-users" aria-hidden="true"></i>
              <i class="fa fa-users" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">{{ Lang::get('core.sextroom') }}</span>
              <span class="info-box-number">{{ $room_type[6]}}</span>
            </div>
          </div>
        </div>

        @if($total==0)
          {{ Lang::get('core.nobookingmade') }}
        @else

          <table class="table table-striped">
            <tbody>
              <tr>
                <th style="width: 10px">#</th>
                <th>{{ Lang::get('core.namesurname') }}</th>
                <th>{{ Lang::get('core.country') }}</th>
              </tr>
              <?php $count = 1; ?>

              @foreach($tourdate->booktours as $booktour)
                <?php $booking = $booktour->booking;?>
                @if($booking)
                  @foreach($booking->bookRoom as $room)
                    @foreach($room->travellerList as $traveller)
                      <tr>
                        <td>{{$count++}}</td>
                        <td><a href="/travellers/show/{{$traveller->travellerID}}">{{$traveller->fullname}}</a></td>
                        <td>@if($traveller->country) {{$traveller->country->country_name}} @endif</td>
                      </tr>
                    @endforeach
                  @endforeach
                @endif
              @endforeach

            </tbody>
          </table>
        @endif

      </div>
    </div>	

  </div>	
  @if($row->remarks !=NULL)
    <div class="box box-solid">
      <div class="box-header with-border">
        <h3 class="box-title text-danger">{{ Lang::get('core.remarks') }}</h3>
      </div>
      <div class="box-body">
        {!! $row->remarks !!}
      </div>
    </div>
  @endif

@if($tasks)
    <div class="box box-solid">
      <div class="box-header with-border">
        <h3 class="box-title text-info">{{ Lang::get('core.tasks') }}</h3>
      </div>
      <div class="box-body">
          
           <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-striped table-bordered " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th align="center" class="number"> No </th>
				<th align="center"> <input type="checkbox" class="checkall" /></th>
				<th align="center">{{ Lang::get('core.task') }}</th>	
				<th align="center">{{ Lang::get('core.duedate') }}</th>	
				<th align="center">{{ Lang::get('core.assigned_to') }}</th>	
				<th align="center">{{ Lang::get('core.assigned_by') }}</th>	
				<th align="center">{{ Lang::get('core.status') }}</th>	
				<th align="center">{{ Lang::get('core.btn_action') }}</th>
			  </tr>
        </thead>
        
        <tbody> @foreach($tasks as $task)
                <tr>
                    
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids minimal-red" name="ids[]" value="" /></td>
					
                    <td><a href="{{ url('tasks/show/'.$task->id.'')}}" class="tips" title="{{ Lang::get('core.btn_view') }}">{{$task->task_name}} </a></td>
                    <td>{{$task->due_date}}</td>
                    <td>@if($task->assignedToUser){{$task->assignedToUser->fullName}}@endif</td>
                    <td>@if($task->assignedByUser){{$task->assignedByUser->fullName}}@endif</td>
                    @if($task->status == 0)<td width="90">
                    <span class="label label-block label-info label-sm">Ongoing</span>
                    </td>@endif
                    @if($task->status == 1)<td width="90">
                    <span class="label label-block label-danger label-sm">Cancelled</span>
                    </td>@endif
                    @if($task->status == 2)<td width="90">
                    <span class="label label-block label-success label-sm">Completed</span>
                    </td>@endif
                    <td> 
                        @if($task->status == 0)
                        {!! Form::open(array('url'=>'tasks/completeStatus/'.$task->id, 'class'=>'form-horizontal')) !!}
                        <button type="submit" name="" class="btn btn-primary btn-sm" >Mark Completed</button> 
                        {!! Form::close() !!}
                        @endif
                    </td>
                </tr>
              @endforeach
        </tbody>

    </table>
	<input type="hidden" name="md" value="" />
	</div>
          
      </div>
    </div>
@endif
  <!-- /.box -->
</div>	


<div style="clear: both;"></div>
<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content animated bounceInRight">
        <form method="POST" enctype="multipart/form-data" action="{{ url('tourdates/uploadpolicy/'.$tourdate->tourdateID) }}">
          {{csrf_field()}}
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title">{{ Lang::get('core.set_discount') }}</h4>
                  {{-- <small class="font-bold">Upload Your Flight Matching CSV File Here</small> --}}
              </div>
              <div class="modal-body">
                  <div class="form-group">
                    <label>{{ Lang::get('core.upload_policy_description') }}</label> 
                    <div class=""><input required type="file" name="policy" id="policy"></div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-white" data-dismiss="modal">{{ Lang::get('core.close') }}</button>
                  <button type="submit" class="btn btn-primary">{{ Lang::get('core.uploadfile') }}</button>
              </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="set_discount" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content animated bounceInRight">
        <form method="POST" action="{{ url('tourdates/TdDiscount/'.$tourdate->tourdateID) }}">
          <input type="hidden" name="tourdateID" value="$tourdate->tourdateID"> <!-- EDIT HERE !-->
          {{csrf_field()}}
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title">{{ Lang::get('core.set_discount') }}</h4>
                  <small class="font-bold">{{ Lang::get('core.set_discount_description') }}</small>
              </div>
              <div class="modal-body">
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-2">
                        <label style="margin-top: 7px">{{ Lang::get('core.discount') }} ({{CURRENCY_SYMBOLS}})</label> 
                      </div>
                      <div class="col-md-4">
                        <input type="number" name="discount" class="form-control" value="$tourdate->discount"> <!-- EDIT HERE !-->
                      </div>
                      <div class="col-md-2" style="margin-top: 7px"> Per Pax</div>
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-white" data-dismiss="modal">{{ Lang::get('core.close') }}</button>
                  <button type="submit" class="btn btn-primary">{{ Lang::get('core.apply_discount') }}</button>
              </div>
            </form>
        </div>
    </div>
</div>

  <script>
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
  </script>
@endsection