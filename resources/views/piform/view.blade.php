@extends('layouts.app')

@section('content')
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="#"> Home</a></li>
         <li><a href="{{ url('piform?return='.$return) }}"> {{ $pageTitle }} </a></li>
        <li  class="active"> View </li>
      </ol>
    </section>

  <div class="content"> 

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-header-tools pull-left" >
	   		<a href="{{ url('tourdates/show/'.$piform->tourdate_id) }}" class="tips btn btn-sm btn-success btn-circle" title="{{ Lang::get('core.btn_back') }}"><i class="fa  fa-arrow-left"></i></a>
			@if($access['is_add'] ==1)
	   		<a href="{{ url('piform/update/'.$piform->id) }}" class="tips btn btn-sm btn-info btn-circle" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil"></i></a>
			@endif 

					
		</div>	

		<div class="box-header-tools pull-right" >
            <a href="{{ url('piform/pdf/'.$piform->id)}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.otherdetails') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.print_pi_form') }}</a>
			<a href="{{ ($prevnext['prev'] != '' ? url('piform/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips btn btn-xs btn-primary btn-circle"><i class="fa fa-arrow-left"></i>  </a>	
			<a href="{{ ($prevnext['next'] != '' ? url('piform/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips btn btn-xs btn-primary btn-circle"> <i class="fa fa-arrow-right"></i>  </a>
			@if(Session::get('gid') ==1)
				<a href="{{ URL::to('mmb/module/config/'.$pageModule) }}" class="tips btn btn-sm btn-success btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa  fa-ellipsis-v"></i></a>
			@endif 			
		</div>


	</div>
	<div class="box-body" >

		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>PIF NO</th>
                        <th>GROUP NAME</th>
                        <th>LEADER NAME</th>
                        <th>NO OF PAX</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{sprintf('%04d',$piform->pif_number)}}</td>
                        <td>{{$piform->group_name}}</td>
                        <td>@if($piform->leader){{$piform->leader->name}}@endif</td>
                        <td>
                        	<table border="1">
                        		<thead>
                        			<tr>
                        				<th style="padding-left: 10px;padding-right: 10px;">Adt</th>
                        				<th style="padding-left: 10px;padding-right: 10px;">Chd</th>
                        				<th style="padding-left: 10px;padding-right: 10px;">Inf</th>
                        				<th style="padding-left: 10px;padding-right: 10px;">Total</th>
                        			</tr>
                        		</thead>
                        		<tbody>
                        			<tr>
                        				<td style="padding-left: 10px;padding-right: 10px;">{{$pax['adults']}}</td>
                        				<td style="padding-left: 10px;padding-right: 10px;">{{$pax['children']}}</td>
                        				<td style="padding-left: 10px;padding-right: 10px;">{{$pax['infants']}}</td>
                        				<td style="padding-left: 10px;padding-right: 10px;">{{$pax['total']}}</td>
                        			</tr>
                        		</tbody>
                        	</table>
                        </td>
                    </tr>
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Flight Schedule</h4>
				@if($flight)
					<table class="table table-bordered">
	                    <thead>
	                    <tr>
	                        <th>CARRIER</th>
	                        <th>FROM / TO</th>
	                        <th>DATE</th>
	                        <th>ETD</th>
	                        <th>ETA</th>
	                        <th>PNR</th>
	                    </tr>
	                    </thead>
	                    <tbody>
	                    <tr>
	                        <td>{{$flight->depart->flightDate->flight_company}}</td>
	                        <td>{{$flight->depart->sector}}</td>
	                        <td>{{$flight->departure_date}}</td>
	                        <td>{{$flight->depart->dep_time}}</td>
	                        <td>{{$flight->depart->arr_time}}</td>
	                        <td>{{$flight->pnr}}</td>
	                    </tr>
	                    <tr>
	                        <td>{{$flight->return->flightDate->flight_company}}</td>
	                        <td>{{$flight->return->sector}}</td>
	                        <td>{{$flight->return_date}}</td>
	                        <td>{{$flight->return->dep_time}}</td>
	                        <td>{{$flight->return->arr_time}}</td>
	                        <td>{{$flight->pnr}}</td>
	                    </tr>
	                    </tbody>
	                </table>
                @else
                	<p>There's no confirmed flight booking found. This section will be automatically updated once flight booking have been made for this package.</p>
                @endif
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Accomodation</h4>
				<table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>CITY</th>
                        <th>HOTEL</th>
                        <th>CHECK IN</th>
                        <th>CHECK OUT</th>
                        <th>DURATION</th>
                        <th>SIGLE</th>
                        <th>DOUBLE</th>
                        <th>TRIPLE</th>
                        <th>QUAD</th>
                        <th>QUINT</th>
                        <th>SEXT</th>
                        <th>BOOKING BY</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($piform->accomodations as $accomodation)
                    <tr>
                        <td>{{$accomodation->hotel->city->city_name}}</td>
                        <td>{{$accomodation->hotel->hotel_name}}</td>
                        <?php $check_in = \Carbon::parse($accomodation->check_in); ?>
                        <td>{{$check_in->format('d M')}}</td>
                        <?php $check_out = \Carbon::parse($accomodation->check_out); ?>
                        <td>{{$check_out->format('d M')}}</td>
                        <td>{{$check_out->diffInDays($check_in)}} Days {{$check_out->diffInDays($check_in)-1}} Nights</td>
                        <td>{{$accomodation->single}}</td>
                        <td>{{$accomodation->double}}</td>
                        <td>{{$accomodation->triple}}</td>
                        <td>{{$accomodation->quad}}</td>
                        <td>{{$accomodation->quint}}</td>
                        <td>{{$accomodation->sext}}</td>
                        <td>{{CNF_COMNAME}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Ziarah</h4>
				<table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>CITY</th>
                        <th>DATE</th>
                        <th>TIME</th>
                        <th>TRANSPORT</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($piform->ziarahs as $ziarah)
                    <tr>
                        <td>{{$ziarah->city}}</td>
                        <td>{{\Carbon::parse($ziarah->date)->format('d M')}}</td>
                        <td>{{$ziarah->time}}</td>
                        <td>{{$ziarah->transportsupplier->name}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Transportation</h4>
				<table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>FROM</th>
                        <th>TO</th>
                        <th>DATE</th>
                        <th>TIME</th>
                        <th>REMARK</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($piform->transportations as $transportation)
                    <tr>
                        <td>{{$transportation->from}}</td>
                        <td>{{$transportation->to}}</td>
                        <td>{{\Carbon::parse($transportation->date)->format('d M')}}</td>
                        <td>{{$transportation->time}}</td>
                        <td>{{$transportation->remarks}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Local Contact Person</h4>
				<table class="table table-bordered">
                    <tbody>
                    @foreach($piform->localContacts as $lc)
                    <tr>
                    	<td>{{$lc->name}}</td>
                        <td>{{$lc->contact}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<hr>
				<h4>Remark</h4>
				<table class="table table-bordered">
                    <tbody>
                    <?php $num = 1;?>
                    @foreach($piform->remarks as $remark)
                    <tr>
                    	<td>{{$num++}}</td>
                        <td>{{$remark->remark}}</td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
			</div>
		</div>


	</div>
</div>	
</div>
	  
@stop