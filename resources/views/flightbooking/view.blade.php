@extends('layouts.app')

@section('content')
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="#"> Home</a></li>
         <li><a href="{{ url('flightbooking?return='.$return) }}"> {{ $pageTitle }} </a></li>
        <li  class="active"> View </li>
      </ol>
    </section>

  <div class="content"> 

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-header-tools pull-left" >
	   		<a href="{{ url('flightbooking?return='.$return) }}" class="tips btn btn-sm btn-success btn-circle" title="{{ Lang::get('core.btn_back') }}"><i class="fa  fa-arrow-left"></i></a>
			@if($access['is_add'] ==1)
	   		<a href="{{ url('flightbooking/update/'.$id.'?return='.$return) }}" class="tips btn btn-sm btn-info btn-circle" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil"></i></a>
			@endif 
					
		</div>	

		<div class="box-header-tools pull-right" >
			<a href="{{ ($prevnext['prev'] != '' ? url('flightbooking/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips btn btn-xs btn-primary btn-circle"><i class="fa fa-arrow-left"></i>  </a>	
			<a href="{{ ($prevnext['next'] != '' ? url('flightbooking/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips btn btn-xs btn-primary btn-circle"> <i class="fa fa-arrow-right"></i>  </a>
			@if(Session::get('gid') ==1)
				<a href="{{ URL::to('mmb/module/config/'.$pageModule) }}" class="tips btn btn-sm btn-success btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa  fa-ellipsis-v"></i></a>
			@endif 			
		</div>


	</div>
	<div class="box-body" > 	

		<div class="row">
			<div class="col-md-7">
				<table>
					<tr>
						<th width="150px">{{ Lang::get('core.departuredate') }}</th>
						<td>{{ \Carbon::parse($row->departure_date)->format('d M Y') }}</td>
					</tr>
					<tr>
						<th width="150px">{{ Lang::get('core.returndate') }}</th>
						<td>{{ \Carbon::parse($row->return_date)->format('d M Y') }}</td>
					</tr>
					<tr>
						<th>{{ Lang::get('core.airline') }}</th>
						<td>{{ $row->flight_matching->flightDate->flight_company }}</td>
					</tr>
					<tr>
						<th>PNR</th>
						<td> @if($row->pnr) {{$row->pnr}} @else PENDING @endif </td>
					</tr>
					<tr>
						<th>PAX</th>
						<td>{{$row->pax}}</td>
					</tr>
					<tr>
						<th>{{ Lang::get('core.status') }}</th>
						<td> @if($row->status == 1) <span class="label label-warning">Pending</span> @elseif($row->status == 2) <span class="label label-success">Success</span> @else <span class="label label-danger">Failed</span> @endif </td>
					</tr>
					<tr style="margin-top: 10px">
						<th>Departure</th>
						<td>
							<table>
								<tr>
									<th style="padding-right: 40px; padding-top: 10px;">Fligt No</th>
									<th style="padding-right: 40px; padding-top: 10px;">Sector</th>
									<th style="padding-right: 40px; padding-top: 10px;">Day</th>
									<th style="padding-right: 40px; padding-top: 10px;">Departure / Arrival</th>
								</tr>
								<tr>
									<td>{{$row->flight_depart->flight_number}}</td>
									<td>{{$row->flight_depart->sector}}</td>
									<td>{{$row->flight_depart->day}}</td>
									<td>{{$row->flight_depart->dep_time}} / {{$row->flight_depart->arr_time}}</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th>Return</th>
						<td>
							<table>
								<tr>
									<th style="padding-right: 40px; padding-top: 10px;">Fligt No</th>
									<th style="padding-right: 40px; padding-top: 10px;">Sector</th>
									<th style="padding-right: 40px; padding-top: 10px;">Day</th>
									<th style="padding-right: 40px; padding-top: 10px;">Departure / Arrival</th>
								</tr>
								<tr>
									<td>{{$row->flight_return->flight_number}}</td>
									<td>{{$row->flight_return->sector}}</td>
									<td>{{$row->flight_return->day}}</td>
									<td>{{$row->flight_return->dep_time}} / {{$row->flight_return->arr_time}}</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-5">
				<div class="tips btn btn-xs btn-primary btn-circle" data-toggle="modal" data-target="#myModal"><i class="fa fa-file-o"></i>  Update Status</div>
				<a href="/flightbooking/travellerpdf?id={{$row->id}}" class="tips btn btn-xs btn-primary btn-circle" target="_blank"><i class="fa fa-file-o"></i>  APIS Data List Pdf</a>
				<a href="/flightbooking/apisexcel/{{$row->id}}" class="tips btn btn-xs btn-primary btn-circle"><i class="fa fa-file-o"></i>  APIS Data List Excel</a>
				@if($row->status == 2)
				<div class="tips btn btn-xs btn-primary btn-circle" data-toggle="modal" data-target="#emailModal"><i class="fa fa-envelope-o"></i>  Email Passenger List</div>
				@endif
			</div>
		</div>

	 	<br>
	 	<?php $i = 1; ?>
	 	<table class="table table-bordered">
			<thead>
		    	<tr>
		    		<th style="border: 1px solid #ddd !important">No.</th>
		    		<th style="border: 1px solid #ddd !important">Traveller Name</th>
		    		<th style="border: 1px solid #ddd !important">APIS Data</th>
		    	</tr>
			</thead>
			<tbody>
				@foreach($travellers as $traveller)
				<tr>
		    		<td style="border: 1px solid #ddd !important">{{$i}}</td>
		    		<td style="border: 1px solid #ddd !important">{{strtoupper($traveller->nameandsurname)}} / {{strtoupper($traveller->last_name)}}</td>
		    		<td style="border: 1px solid #ddd !important">
		    			SRDOCSSVHK1-P-MAS-@if($traveller->passportno){{$traveller->passportno}}@else<a href="/travellers/update/{{$traveller->travellerID}}" style="color: red;">Please Update Passport</a>@endif-MAS-@if($traveller->dateofbirth){{strtoupper(\Carbon::parse($traveller->dateofbirth)->format('dMy'))}}@else<a href="/travellers/update/{{$traveller->travellerID}}" style="color: red;">Please Update Date of Birth</a>@endif-@if($traveller->gender){{$traveller->gender}}@else<a href="/travellers/update/{{$traveller->travellerID}}" style="color: red;">Please Update Gender</a>@endif-@if($traveller->passportexpiry){{strtoupper(\Carbon::parse($traveller->passportexpiry)->format('dMy'))}}@else<a href="/travellers/update/{{$traveller->travellerID}}" style="color: red;">Please Update Passport Expiry Date</a>@endif-@if($traveller->nameandsurname){{str_replace(' ', '', strtoupper($traveller->nameandsurname))}}@else<a href="/travellers/update/{{$traveller->travellerID}}" style="color: red;">Please Update First Name</a>@endif/@if($traveller->last_name){{str_replace(' ', '', strtoupper($traveller->last_name))}}@else<a href="/travellers/update/{{$traveller->travellerID}}" style="color: red;">Please Update Last Name</a>@endif/P{{$i++}}
			    	</td>
		    	</tr>
		    	@endforeach
			</tbody>
		</table>
	
	</div>
</div>	


<div class="modal inmodal" id="myModal" tabindex="-1" role="dialog"">
    <div class="modal-dialog">
    	<div class="modal-content animated bounceInRight">
    		<form method="POST" action="/flightbooking/updatebooking">
    			<input type="hidden" name="id" value="{{$row->id}}">
    			{{csrf_field()}}
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title">Update Booking</h4>
	                <small class="font-bold">Update your booking status here</small>
	            </div>
	            <div class="modal-body">
	                <div class="form-group">
	                	<label>Status</label> 
	                	<div class="">
	                		<select class="select2" name="status" id="status">
	                			<option value="2" @if($row->status == 2) selected @endif >Success</option>
	                			<option value="1" @if($row->status == 1) selected @endif >Pending</option>
	                			<option value="0" @if($row->status == 0) selected @endif >Failed</option>
	                		</select>
	                	</div>
	                </div>
	                <div class="form-group">
	                	<label>PNR</label> 
	                	<div class="">
	                		<input class="form-control" type="text" name="pnr" id="pnr" value="{{$row->pnr}}">
	                	</div>
	                </div>
	                <div class="form-group">
	                	<label>Payment Due</label>
	                	<div class="input-group m-b" style="width:180px !important; margin-bottom: 0px !important;" id="dpd1">{!! Form::text("payment_due", $row->payment_due,array("class"=>"form-control date","autocomplete"=>"off", "id"=>"payment_due", )) !!}<span class="input-group-addon"><i class="fa fa-calendar"></i></span></div>
	                </div>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
	                <button type="submit" class="btn btn-primary">Update Booking</button>
	            </div>
            </form>
        </div>
    </div>
</div>
	  
</div>

@if($row->status == 2)
<div class="modal inmodal" id="emailModal" tabindex="-1" role="dialog"">
    <div class="modal-dialog">
    	<div class="modal-content animated bounceInRight">
    		<form method="POST" action="/flightbooking/emailpassenger">
    			<input type="hidden" name="id" value="{{$row->id}}">
    			{{csrf_field()}}
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title">Email Passenger List</h4>
	                <small class="font-bold">Email your passenger list to the flight company. The passenger list will be attached to the email.</small>
	            </div>
	            <div class="modal-body">
	                <div class="form-group">
	                	<label>Email</label> 
	                	<div class="">
	                		<input class="form-control" type="text" name="email" value="{{$row->email}}">
	                	</div>
	                </div>
	                <div class="form-group">
	                	<label>Message</label> 
	                	<div class="">
	                		<textarea class="form-control" id="message" name="message">Dear flight company. Attached to this email are the passenger list for the flights with PNR {{$row->pnr}}.</textarea>
	                	</div>
	                </div>
	            </div>
	            <div>
	            	<div class="form-group">
	            		<label style="margin-left: 15px">
		                	<input type="checkbox" name="cc" checked> CC to myself
		                </label>
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
@endif
@stop