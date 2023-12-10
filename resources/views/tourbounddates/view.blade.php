@extends('layouts.app')
@section('content')
<?php
use \App\Http\Controllers\TourdatesController;
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
    <section class="content-header">
      <h1> {{ SiteHelpers::formatLookUp($row->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }}</h1>
    </section>
<div class="box-header with-border">
				<div class="box-header-tools pull-left" >
			   		<a href="{{ url('tourbounddates?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
                @if($total!=0)
                    <a href="{{ url('tourbounddates/show/'.$id.'?bookinglist=true')}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.bookinglist') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.bookinglist') }}</a>
                    <a href="{{ url('tourbounddates/show/'.$id.'?passportlist=true')}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.passportlist') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.passportlist') }}</a>
                    <a href="{{ url('tourbounddates/show/'.$id.'?emergencylist=true')}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.otherdetails') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.otherdetails') }}</a>
                @endif
				</div>	

				<div class="box-header-tools pull-right " >
					<a href="{{ ($prevnext['prev'] != '' ? url('tourbounddates/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="{{ Lang::get('core.previous') }}"><i class="fa fa-arrow-left fa-2x"></i>  </a>	
					<a href="{{ ($prevnext['next'] != '' ? url('tourbounddates/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="{{ Lang::get('core.next') }}"> <i class="fa fa-arrow-right fa-2x"></i>  </a>
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
                  <b>{{ Lang::get('core.status') }}</b> <a href="#" class="pull-right">{!! GeneralStatus::Tour($row->status,$row->start,$row->end,$row->tourdateID, $row->total_capacity, App\Models\Tourbounddates::find($row->tourdateID)->pax) !!}</a>
                </li>                
                <li class="list-group-item">
                  <b>{{ Lang::get('core.guide') }}</b> <a href="{{ url('guide/show/'.$row->guideID)}}" target="_blank" class="pull-right">{{ SiteHelpers::formatLookUp($row->guideID,'guideID','1:guides:guideID:name') }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ Lang::get('core.featured') }}</b> <a href="#" class="pull-right">{!! SiteHelpers::Featured($row->featured) !!}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ Lang::get('core.definitedeparture') }}</b> <a href="#" class="pull-right">{!! SiteHelpers::Definite_departure($row->definite_departure) !!}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ Lang::get('core.capacity') }}</b> <a href="#" class="pull-right">{{ $row->total_capacity}}</a>
                </li>

                  <li class="list-group-item">
                  <b>{{ Lang::get('core.singleroom') }}</b> <a href="#" class="pull-right">{{ $row->cost_single}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ Lang::get('core.doubleroom') }}</b> <a href="#" class="pull-right">{{ $row->cost_double}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ Lang::get('core.tripleroom') }}</b> <a href="#" class="pull-right">{{ $row->cost_triple}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ Lang::get('core.quadroom') }}</b> <a href="#" class="pull-right">{{ $row->cost_quad}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
                </li>
                <li class="list-group-item">
                  <b>{{ Lang::get('core.child') }}</b> <a href="#" class="pull-right">{{ $row->cost_child}} {{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:currency_sym|symbol') }}</a>
                </li>                  
              </ul>
             </div>
          </div>
            </div>
<div class="col-md-9">

<div class="box box-warning">
	
			<div class="box-body" > 	
                              <h3 class="profile-username">{{ Lang::get('core.bookinglist') }}</h3>
<div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green">
              <i class="fa fa-user" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">{{ Lang::get('core.singleroom') }}</span>
              <span class="info-box-number">{{ $room_single}}</span>
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
              <span class="info-box-number">{{ $room_double}}</span>
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
              <span class="info-box-number">{{ $room_triple}}</span>
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
              <span class="info-box-number">{{ $room_quad}}</span>
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
                    <th><div class='col-md-6'>{{ Lang::get('core.namesurname') }}</div><div class='col-md-2'>{{ Lang::get('core.country') }}</div><div class='col-md-4'>{{ Lang::get('core.remarks') }}</div> </th>
                </tr>
                    <?php $count = 1; ?>
                    @foreach($bkList as $bl)
                <tr>
                   <th><?php echo $count ; $count++ ; ?></th>
                    <td>{!! TourdatesController::travelersDetail($bl['travellers']) !!} <div class='col-md-4'>{!! $bl['remarks'] !!}</div> </td>
                </tr>               
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
          <!-- /.box -->
		</div>	

      
                  			<div style="clear: both;"></div>
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
	  
@stop