@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="#"> Home</a></li>
         <li><a href="{{ url('flightdates?return='.$return) }}"> {{ $pageTitle }} </a></li>
        <li  class="active"> View </li>
      </ol>
    </section>

  <div class="content"> 

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-header-tools pull-left" >
	   		<a href="{{ url('flightdates?return='.$return) }}" class="tips btn btn-sm btn-success btn-circle" title="{{ Lang::get('core.btn_back') }}"><i class="fa  fa-arrow-left"></i></a>
			@if($access['is_add'] ==1)
	   		<a href="{{ url('flightdates/update/'.$id.'?return='.$return) }}" class="tips btn btn-sm btn-info btn-circle" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil"></i></a>
			@endif 
					
		</div>	

		<div class="box-header-tools pull-right" >
			<a href="{{ ($prevnext['prev'] != '' ? url('flightdates/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips btn btn-xs btn-primary btn-circle"><i class="fa fa-arrow-left"></i>  </a>	
			<a href="{{ ($prevnext['next'] != '' ? url('flightdates/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips btn btn-xs btn-primary btn-circle"> <i class="fa fa-arrow-right"></i>  </a>
			@if(Session::get('gid') ==1)
				<a href="{{ URL::to('mmb/module/config/'.$pageModule) }}" class="tips btn btn-sm btn-success btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa  fa-ellipsis-v"></i></a>
			@endif 			
		</div>


	</div>
	<div class="box-body" > 	

		<div class="row">
			<div class="col-md-8">
				<table>
					<tr>
						<th width="150px">Title</th>
						<td>{{ $row->title}}</td>
					</tr>
					<tr>
						<th>Date</th>
						<td>{{ \Carbon::parse($row->start_date)->format('M') }} {{ \Carbon::parse($row->end_date)->format('M') }}</td>
					</tr>
					<tr>
						<th>Flight Company</th>
						<td>{{ $row->flight_company}}</td>
					</tr>
					<tr>
						<th>Email</th>
						<td>{{ $row->email }}</td>
					</tr>
				</table>
			</div>
			<div class="col-md-4">
				@if(Auth::user()->group_id == 1)
				<div class="tips btn btn-xs btn-primary btn-circle" data-toggle="modal" data-target="#myModal"><i class="fa fa-file-o"></i>  Upload CSV</div>
				@endif
				<div class="tips btn btn-xs btn-primary btn-circle" data-toggle="modal" data-target="#mail"><i class="fa fa-envelope-o"></i>  Email</div>
			</div>
		</div>
		
		<br>
		
		<div class="row">
			<div class="col-md-6">
				<h3>Departs</h3>
				<table class="table table-bordered">
					<thead>
				    	<tr>
				    		<th style="border: 1px solid #ddd !important">SELECT</th>
				    		<th style="border: 1px solid #ddd !important">No.</th>
				    		<th style="border: 1px solid #ddd !important">FLIGHT NO</th>
				    		<th style="border: 1px solid #ddd !important">SECTOR</th>
				    		<th style="border: 1px solid #ddd !important">DAY</th>
				    		<th style="border: 1px solid #ddd !important">DEPARTURE / ARRIVAL</th>
				    	</tr>
					</thead>
					<tbody>
						<?php $i = 1; ?>
						@foreach($flight_matching_departs as $flight_matching)
				    	<tr>
				    		<td style="border: 1px solid #ddd !important"><input type="radio" value="{{$flight_matching->id}}" id="{{$flight_matching->id}}" name="depart"></td>
				    		<td style="border: 1px solid #ddd !important">{{$i++}}</td>
				    		<td style="border: 1px solid #ddd !important">{{$flight_matching->flight_number}}</td>
				    		<td style="border: 1px solid #ddd !important">{{$flight_matching->sector}}</td>
				    		<td style="border: 1px solid #ddd !important">{{$flight_matching->day}}</td>
				    		<td style="border: 1px solid #ddd !important">{{$flight_matching->dep_time}} / {{$flight_matching->arr_time}}</td>
				    	</tr>
				    	@endforeach
					</tbody>
				</table>
			</div>
			<div class="col-md-6">
				<h3>Returns</h3>
				<table class="table table-bordered">
					<thead>
				    	<tr>
				    		<th style="border: 1px solid #ddd !important">SELECT</th>
				    		<th style="border: 1px solid #ddd !important">No.</th>
				    		<th style="border: 1px solid #ddd !important">FLIGHT NO</th>
				    		<th style="border: 1px solid #ddd !important">SECTOR</th>
				    		<th style="border: 1px solid #ddd !important">DAY</th>
				    		<th style="border: 1px solid #ddd !important">DEPARTURE / ARRIVAL</th>
				    	</tr>
					</thead>
					<tbody>
						<?php $i = 1; ?>
						@foreach($flight_matching_returns as $flight_matching)
				    	<tr>
				    		<td style="border: 1px solid #ddd !important"><input type="radio" value="{{$flight_matching->id}}" id="{{$flight_matching->id}}" name="return"></td>
				    		<td style="border: 1px solid #ddd !important">{{$i++}}</td>
				    		<td style="border: 1px solid #ddd !important">{{$flight_matching->flight_number}}</td>
				    		<td style="border: 1px solid #ddd !important">{{$flight_matching->sector}}</td>
				    		<td style="border: 1px solid #ddd !important">{{$flight_matching->day}}</td>
				    		<td style="border: 1px solid #ddd !important">{{$flight_matching->dep_time}} / {{$flight_matching->arr_time}}</td>
				    	</tr>
				    	@endforeach
					</tbody>
				</table>
			</div>
		</div>

		
	 
	
	</div>
</div>	
</div>

<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog"">
    <div class="modal-dialog">
    	<div class="modal-content animated bounceInRight">
    		<form method="POST" enctype="multipart/form-data" action="/flightmatching/save">
    			<input type="hidden" name="flight_date" value="{{$row->id}}">
    			{{csrf_field()}}
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title">Flight Matching</h4>
	                <small class="font-bold">Upload Your Flight Matching CSV File Here</small>
	            </div>
	            <div class="modal-body">
	                <div class="form-group">
	                	<label>Upload Your CSV File</label> 
	                	<div class=""><input required type="file" name="flight_matching" id="flight_matching"></div>
	                </div>

	                <p>You can download the CSV template <a href="/flightmatching/downloadtemplate">here</a>.</p>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
	                <button type="submit" class="btn btn-primary">Upload File</button>
	            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal inmodal" id="mail" tabindex="-1" role="dialog"">
    <div class="modal-dialog">
    	<div class="modal-content animated bounceInRight">
    		<form method="POST" action="/flightbooking/save">
    			<input type="hidden" name="id" value="{{$row->id}}">
    			{{csrf_field()}}
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title">Email Booking</h4>
	                <small class="font-bold">Email your flight booking to flight company</small>
	            </div>
	            <div class="modal-body">
	            	<div class="form-group">
	                	<label>Tour</label> 
	                	<div class="">
	                		<select class="select2" name="tour" id="tour">
	                			<option>Select tour</option>
	                			@foreach($tours as $tour)
	                			<option value="{{$tour->tourID}}">{{$tour->tour_name}}</option>
	                			@endforeach
	                		</select>
	                	</div>
	                </div>
	                <div class="form-group">
	                	<label>Tour date</label> 
	                	<div class="">
	                		<select class="select2" name="tourdate" id="tourdate">
	                			<option>Please Select Tour First</option>
	                		</select>
	                	</div>
	                </div>
	                <div class="form-group">
	                	<label>Email</label> 
	                	<div class="">
	                		<input class="form-control" type="text" name="email" value="{{$row->email}}">
	                	</div>
	                </div>
	                <div class="form-group">
	                	<label>Message</label> 
	                	<div class="">
	                		<textarea class="form-control" id="message" name="message">Dear flight company. We would like to book the following flights.</textarea>
	                	</div>
	                </div>

	                <div>
	                	<label>Depart:</label>
	                	<div class="row">
		                	<div id="depart_table" class="col-md-8"></div>
		                	<div class="col-md-4" style="display: none;" id="show_depart">
		                		Departure Date
		                		<div class="input-group m-b" style="width:180px !important; margin-bottom: 0px !important;" id="dpd1">{!! Form::text("departure_date", NULL,array("class"=>"form-control date","autocomplete"=>"off", "id"=>"departure_date")) !!}<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>
		                	</div>
	                	</div>
	                </div>
	                <div>
	                	<label>Return:</label>
	                	<div class="row">
	                		<div id="return_table" class="col-md-8"></div>
	                		<div class="col-md-4" style="display: none;" id="show_return">
		                		Return Date
		                		<div class="input-group m-b" style="width:180px !important; margin-bottom: 0px !important;" id="dpd1">{!! Form::text("return_date", NULL,array("class"=>"form-control date","autocomplete"=>"off", "id"=>"return_date")) !!}<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>
		                	</div>
	                	</div>
	                </div>
	                
	                <br>

	                <div class="row">
	                	<div class="col-md-8">
	                		<label>Number of pax:</label>
	                		<div id="number_of_pax"></div>
	                		<input type="hidden" name="pax" id="pax">
	                	</div>
	                	<div class="col-md-4">
	                		<label style="margin-top: 15px">
			                	<input type="checkbox" name="cc" checked> CC to myself
			                </label>
	                	</div>
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
	                <button type="submit" class="btn btn-primary">Email Booking</button>
	            </div>
	        </form>
        </div>
    </div>
</div>

<script>

	var depart_list = [];
	
	@foreach($flight_matching_departs as $flight_matching)
	depart_list[{{$flight_matching->id}}] = { id:'{{$flight_matching->id}}', flight_number:'{{$flight_matching->flight_number}}', sector:'{{$flight_matching->sector}}', day:'{{$flight_matching->day}}', dep_time:'{{$flight_matching->dep_time}}', arr_time:'{{$flight_matching->arr_time}}' };
	@endforeach

	var return_list = [];

	@foreach($flight_matching_returns as $flight_matching)
	return_list[{{$flight_matching->id}}] = { id:'{{$flight_matching->id}}', flight_number:'{{$flight_matching->flight_number}}', sector:'{{$flight_matching->sector}}', day:'{{$flight_matching->day}}', dep_time:'{{$flight_matching->dep_time}}', arr_time:'{{$flight_matching->arr_time}}' };
	@endforeach

	$('input').on('ifChecked', function (event) { $(event.target).click(); });

	$('input[name="depart"]').on("click", function(e) {
		fillTable(this.value, 1);	    
	});

	$('input[name="return"]').on("click", function(e) {
	    fillTable(this.value, 2);	
	});

	function fillTable(id, type) {
		if(type == 1){
			document.getElementById('depart_table').innerHTML = '<table><tr><th style="padding-right: 40px;">Fligt No</th><th style="padding-right: 40px;">Sector</th><th style="padding-right: 40px;">Day</th><th style="padding-right: 40px;">Departure / Arrival</th></tr><tr><td>'+depart_list[id].flight_number+'</td><td>'+depart_list[id].sector+'</td><td>'+depart_list[id].day+'</td><td>'+depart_list[id].dep_time+' / '+depart_list[id].arr_time+'</td></tr></table><input type="hidden" name="depart_flight" value="'+depart_list[id].id+'">';
			document.getElementById('show_depart').style.display = 'block';
		}else if(type == 2){
			document.getElementById('return_table').innerHTML = '<table><tr><th style="padding-right: 40px;">Fligt No</th><th style="padding-right: 40px;">Sector</th><th style="padding-right: 40px;">Day</th><th style="padding-right: 40px;">Departure / Arrival</th></tr><tr><td>'+return_list[id].flight_number+'</td><td>'+return_list[id].sector+'</td><td>'+return_list[id].day+'</td><td>'+return_list[id].dep_time+' / '+return_list[id].arr_time+'</td></tr></table><input type="hidden" name="return_flight" value="'+return_list[id].id+'">';
			document.getElementById('show_return').style.display = 'block';
		}
	}

	$(function(){

		$("#tour").change(function(){
		    var id = this.value;
		    axios.post('/flightdates/tourdates', { _token:'{{csrf_token()}}', id:id })
		    .then(response=>{
		    	var tourdates = response.data;
		    	var options = '<option>Select Dates<option>';
		    	for (var i = 0; i < tourdates.length; i++) {
		    		options += '<option value="'+tourdates[i].tourdateID+'">'+tourdates[i].start+' - '+tourdates[i].end+'</option>';
		    	}
		    	document.getElementById('tourdate').innerHTML = options;
		    }).catch(e=>{
		    	console.log(e);
		    });
		});

		$("#tourdate").change(function(){
		    var id = this.value;
		    console.log('running ' + id)
		    axios.post('/flightdates/tourdate', { _token:'{{csrf_token()}}', id:id })
		    .then(response=>{
		    	var tourdate = response.data;
		    	var departure_date = document.getElementById('departure_date');
		    	var return_date = document.getElementById('return_date');
		    	if (departure_date.value == '' || departure_date.value == null) {
		    		departure_date.value = tourdate.start;
		    	}
		    	if (return_date.value == '' || return_date.value == null) {
		    		return_date.value = tourdate.end;
		    	}
		    	document.getElementById('number_of_pax').innerHTML = tourdate.total_capacity;
		    	document.getElementById('pax').value = tourdate.total_capacity;
		    }).catch(e=>{
		    	console.log(e);
		    });
		});

	});
</script>


@endsection