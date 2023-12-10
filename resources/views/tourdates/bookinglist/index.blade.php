@extends('layouts.app')
@section('content')
	
	<div class="box box-primary">
		{{-- <h3><a href="/tourdates/show/{{$tourdate->tourdateID}}">{{$tourdate->tour->tour_name}} - {{$tourdate->tour_code}} ({{\Carbon::parse($tourdate->start)->format('d M Y')}} - {{\Carbon::parse($tourdate->end)->format('d M Y')}})</a></h3> --}}
		<div class="row">
			<div class="col-md-6">
				<h4>{{ Lang::get('core.bookinglist') }}</h4>
			</div>
			<div class="col-md-6">
				<h4>
					<a class="btn btn-xs btn-default tips" title="{{ Lang::get('core.add_booking') }}" data-toggle="modal" data-target="#add_booking"><i class="fa fa-plus fa-lg text-green"></i> {{ Lang::get('core.add_booking') }}</a>
					<a href="/tourdates/bookinglistpdf/{{$tourdate->tourdateID}}" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.booking_list_pdf') }}"><i class="fa fa-file fa-lg text-red"></i> {{ Lang::get('core.booking_list_pdf') }}</a>
				</h4>
			</div>
		</div>

		<hr style="margin-top: 0px;">

		<table style="border-style: none; margin-left: 7px;">
			<tr>
				<td style="border-style: none; width: 150px;"><b>{{ Lang::get('core.booking_list_for') }}</b></td>
				<td style="border-style: none;">: <a href="/tours/show/{{$tourdate->tour->tourID}}">{{ $tourdate->tour->tour_name }}</a> (<a href="/tourdates/show/{{$tourdate->tourdateID}}">{{ $tourdate->tour_code }}</a>)</td>
			</tr>
			<tr>
				<td style="border-style: none;"><b>{{ Lang::get('core.tourcategory') }}</b></td>
				<td style="border-style: none;">: {{ $tourdate->tourcategory->tourcategoryname }}</td>
			</tr>
			<tr>
				<td style="border-style: none;"><b>{{ Lang::get('core.packagedate') }}</b></td>
				<td style="border-style: none;">: {{ \Carbon::parse($tourdate->start)->format('d M Y') }} - {{ \Carbon::parse($tourdate->end)->format('d M Y') }} </td>
			</tr>
		</table>
		<br>

		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>No.</th>
							<th>{{ Lang::get('core.bookingdate') }}</th>
							<th>{{ Lang::get('core.bookingid') }}</th>
							<th>{{ Lang::get('core.name') }}</th>
							<th>{{ Lang::get('core.phone') }}</th>
							<th>Pax</th>
							<th>{{ Lang::get('core.bookingvalue') }}</th>
							<th>{{ Lang::get('core.paymentamount') }}</th>
							<th>{{ Lang::get('core.status') }}</th>
						</tr>
					</thead>
					<tbody>
						<?php $num = 1; ?>
						@foreach($tourdate->booktours as $booktour)
							<?php $booking = $booktour->booking; ?>
							@if($booking)
								<tr>
									<td>{{ $num++ }}</td>
									<td>{{ \Carbon::parse($booking->created_at)->format('d M Y') }}</td>
									<td><a href="/createbooking/show/{{$booking->bookingsID}}" @if (!$booking->settled)
										class="text-red" 
									@endif> @if (!$booking->settled) <i class="text-red fa fa-exclamation-triangle"></i> @endif {{ $booking->bookingno }}</a></td>
									<td>@if($booking->traveller) <a href="/travellers/show/{{$booking->traveller->travellerID}}">{{ $booking->traveller->fullname }}</a> @else Traveller Not Found @endif</td>
									<td>@if($booking->traveller) {{ $booking->traveller->phone }} @else Traveller Not Found @endif</td>
									<td>{{ $booking->pax }}</td>
									<td>@if($booking->invoice) {{ $booking->invoice->InvTotal }} @else Invoice Not Found @endif</td>
									<td>@if($booking->invoice) {{ $booking->invoice->total_paid }} @else Invoice Not found @endif</td>
									<td>@if($booktour->status == 2) Pending @elseif($booktour->status == 1) Confirmed @else Cancelled @endif</td>
								</tr>
							@endif
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		
	</div>

	<div class="modal inmodal" id="add_booking" tabindex="-1" role="dialog">
	    <div class="modal-dialog">
	    	<div class="modal-content animated bounceInRight">
	    		<form method="POST" enctype="multipart/form-data">
	    			{{csrf_field()}}
	    			<input type="hidden" name="tourdate_id" value="{{$tourdate->tourdateID}}">
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
		                <h4 class="modal-title">Add Booking</h4>
		                <small class="font-bold">Add your booking here.</small><br>
		                <small class="font-bold">Note: Booking entered here will have invoice automatically created.</small>
		            </div>
		            <div id="body">
		            	<div class="modal-body" id="booking_1">
		            		{{-- <h5>Booking 1</h5> --}}
			                <div class="form-group">
			                	<div class="row">
			                		<div class="col-md-3">
			                			<label>Primary Contact: </label>
			                		</div>
			                		<div class="col-md-9">
			                			<select class="form-control" name="primary_contact">
			                				<option hidden disabled selected>Please Select Primary Contact</option>
			                				@foreach($travellers as $traveller)
			                					<option value="{{$traveller->travellerID}}">{{$traveller->NRIC}} - {{$traveller->fullname}}</option>
			                				@endforeach
			                			</select>
			                		</div>
			                	</div>
			                </div>

			                <div id="rooms">
			                	<div class="panel panel-default" id="room_1">
	                                <div class="panel-body">
	                                    <div class="form-group">
	                                    	<div class="row">
	                                    		<div class="col-md-3">
	                                    			<label>Room 1: </label>
	                                    		</div>
	                                    		<div class="col-md-9">
	                                    			<select class="form-control" name="rooms[1][type]">
	                                    				<option hidden disabled selected>Please Select Room</option>
	                                    				@if($tourdate->cost_single > 0)
	                                    					<option value="1">Room for 1 ({{$tourdate->cost_single}})</option>
	                                    				@else
	                                    					<option disabled>Single Room Unavailable</option>
	                                    				@endif
	                                    				@if($tourdate->cost_double > 0)
	                                    					<option value="2">Room for 2 ({{$tourdate->cost_double}})</option>
	                                    				@else
	                                    					<option disabled>Double Room Unavailable</option>
	                                    				@endif
	                                    				@if($tourdate->cost_triple > 0)
	                                    					<option value="3">Room for 3 ({{$tourdate->cost_triple}})</option>
	                                    				@else
	                                    					<option disabled>Triple Room Unavailable</option>
	                                    				@endif
	                                    				@if($tourdate->cost_quad > 0)
	                                    					<option value="4">Room for 4 ({{$tourdate->cost_quad}})</option>
	                                    				@else
	                                    					<option disabled>Quad Room Unavailable</option>
	                                    				@endif
	                                    				@if($tourdate->cost_quint > 0)
	                                    					<option value="5">Room for 5 ({{$tourdate->cost_quint}})</option>
	                                    				@else
	                                    					<option disabled>Quint Room Unavailable</option>
	                                    				@endif
	                                    				@if($tourdate->cost_sext > 0)
	                                    					<option value="6">Room for 6 ({{$tourdate->cost_sext}})</option>
	                                    				@else
	                                    					<option disabled>Sext Room Unavailable</option>
	                                    				@endif
	                                    			</select>
	                                    		</div>
	                                    	</div>
	                                    	<div class="row">
	                                    		<div class="col-md-3">
	                                    			<label>Travellers: </label>
	                                    		</div>
	                                    		<div class="col-md-9">
	                                    			<select name="rooms[1][travellers][]" multiple rows="5" class="select2" placeholder="Please Select Travellers">
						                				@foreach($travellers as $traveller)
						                					<option value="{{$traveller->travellerID}}">{{$traveller->NRIC}} - {{$traveller->fullname}}</option>
						                				@endforeach
	                                    			</select>
	                                    		</div>
	                                    	</div>
	                                    	<div class="row">
	                                    		<div class="col-md-4">
	                                    			<input type="hidden" name="rooms[1][childcheck]" value="off" id="childcheck_1_alt">
	                                    			<label>Children <input type="checkbox" name="rooms[1][childcheck]" id="childcheck_1"></label>
	                                    		</div>
	                                    	</div>
	                                    	<div id="child_form_1" style="display: none;">
	                                    		<div class="row">
		                                    		<div class="col-md-3">
		                                    			<label>Type: </label>
		                                    		</div>
		                                    		<div class="col-md-9">
		                                    			<select class="form-control" name="rooms[1][child_room]">
		                                    				<option hidden disabled selected>Please Select Room</option>
		                                    				@if($tourdate->cost_child > 0)
		                                    					<option value="7">Children with bed ({{$tourdate->cost_child}})</option>
		                                    				@else
		                                    					<option disabled>Children with bed Unavailable</option>
		                                    				@endif
		                                    				@if($tourdate->cost_child_wo_bed > 0)
		                                    					<option value="8">Children without bed ({{$tourdate->cost_child_wo_bed}})</option>
		                                    				@else
		                                    					<option disabled>Children without bed Unavailable</option>
		                                    				@endif
		                                    				@if($tourdate->cost_infant_wo_bed > 0)
		                                    					<option value="9">Infant ({{$tourdate->cost_infant_wo_bed}})</option>
		                                    				@else
		                                    					<option disabled>Infant Unavailable</option>
		                                    				@endif
		                                    			</select>
		                                    		</div>
		                                    	</div>
	                                    		<div class="row">
		                                    		<div class="col-md-3">
		                                    			<label>Children: </label>
		                                    		</div>
		                                    		<div class="col-md-9">
		                                    			<select name="rooms[1][children][]" multiple rows="5" class="select2" placeholder="Please Select Travellers">
							                				@foreach($children as $traveller)
							                					<option value="{{$traveller->travellerID}}">{{$traveller->NRIC}} - {{$traveller->fullname}}</option>
							                				@endforeach
		                                    			</select>
		                                    		</div>
		                                    	</div>
	                                    	</div>
	                                    	<div class="row">
	                                    		<div class="col-md-4">
	                                    			<input type="hidden" name="rooms[1][infantcheck]" value="off" id="infantcheck_1_alt">
	                                    			<label>Infant <input type="checkbox" name="rooms[1][infantcheck]" id="infantcheck_1"></label>
	                                    		</div>
	                                    	</div>
	                                    	<div id="infant_form_1" style="display: none;">
	                                    		<div class="row">
		                                    		<div class="col-md-3">
		                                    			<label>Type: </label>
		                                    		</div>
		                                    		<div class="col-md-9">
		                                    			<select class="form-control" name="rooms[1][infant_room]">
		                                    				<option hidden disabled selected>Please Select Room</option>
		                                    				@if($tourdate->cost_infant_wo_bed > 0)
		                                    					<option value="9">Infant ({{$tourdate->cost_infant_wo_bed}})</option>
		                                    				@else
		                                    					<option disabled>Infant Unavailable</option>
		                                    				@endif
		                                    			</select>
		                                    		</div>
		                                    	</div>
	                                    		<div class="row">
		                                    		<div class="col-md-3">
		                                    			<label>Infants: </label>
		                                    		</div>
		                                    		<div class="col-md-9">
		                                    			<select name="rooms[1][infants][]" multiple rows="5" class="select2" placeholder="Please Select Travellers">
							                				@foreach($infants as $traveller)
							                					<option value="{{$traveller->travellerID}}">{{$traveller->NRIC}} - {{$traveller->fullname}}</option>
							                				@endforeach
		                                    			</select>
		                                    		</div>
		                                    	</div>
	                                    	</div>

	                                    </div>
	                                </div>
	                            </div>
			                </div>

			                <div class="row">
			                	<div class="col-md-12">
			                		<button type="button" class="btn btn-primary" style="float: right;" onclick="addRoom()">Add Room</button>
			                	</div>
			                </div>

			            </div>
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-white" data-dismiss="modal" style="margin-bottom: 0px;">Close</button>
		                <button type="submit" class="btn btn-primary">Add Booking</button>
		            </div>
	            </form>
	        </div>
	    </div>
	</div>

	<script>
		var roomnum = 1;

		function checkboxshowform() {
			$('[id^=childcheck_]').on('ifChecked', function () {
				let num = this.id.replace('childcheck_', '');
				document.getElementById('child_form_'+num).style.display = "block";
				document.getElementById('childcheck_'+num+'_alt').disabled = true;
			})

			$('[id^=childcheck_]').on('ifUnchecked', function () {
				let num = this.id.replace('childcheck_', '');
				document.getElementById('child_form_'+num).style.display = "none";
				document.getElementById('childcheck_'+num+'_alt').disabled = false;
			})

			$('[id^=infantcheck_]').on('ifChecked', function () {
				let num = this.id.replace('infantcheck_', '');
				document.getElementById('infant_form_'+num).style.display = "block";
				document.getElementById('infantcheck_'+num+'_alt').disabled = true;
			})

			$('[id^=infantcheck_]').on('ifUnchecked', function () {
				let num = this.id.replace('infantcheck_', '');
				document.getElementById('infant_form_'+num).style.display = "none";
				document.getElementById('infantcheck_'+num+'_alt').disabled = false;
			})
		}

		checkboxshowform();

		function addRoom() {
			roomnum++;

			let string = "<div class=\"panel panel-default\" id=\"room_"+roomnum+"\">"+
                            "<div class=\"panel-body\">"+
                            	"<div class=\"row\">"+
                            		"<div class=\"col-md-12\">"+
                            			"<a href=\"#\" onclick=\"deleteRoom('room_"+roomnum+"')\" style=\"float: right;\"><i class=\"fa fa-times-circle-o fa-2x text-red\"></i></a>"+
                            		"</div>"+
                            	"</div>"+
                                "<div class=\"form-group\">"+
                                	"<div class=\"row\">"+
                                		"<div class=\"col-md-3\">"+
                                			"<label>Room "+roomnum+": </label>"+
                                		"</div>"+
                                		"<div class=\"col-md-9\">"+
                                			"<select class=\"form-control\" name=\"rooms["+roomnum+"][type]\">"+
                                				"<option hidden disabled selected>Please Select Room</option>"+
                                				@if($tourdate->cost_single > 0)
                                					"<option value=\"1\">Room for 1 ({{$tourdate->cost_single}})</option>"+
                                				@else
                                					"<option disabled>Single Room Unavailable</option>"+
                                				@endif
                                				@if($tourdate->cost_double > 0)
                                					"<option value=\"2\">Room for 2 ({{$tourdate->cost_double}})</option>"+
                                				@else
                                					"<option disabled>Double Room Unavailable</option>"+
                                				@endif
                                				@if($tourdate->cost_triple > 0)
                                					"<option value=\"3\">Room for 3 ({{$tourdate->cost_triple}})</option>"+
                                				@else
                                					"<option disabled>Triple Room Unavailable</option>"+
                                				@endif
                                				@if($tourdate->cost_quad > 0)
                                					"<option value=\"4\">Room for 4 ({{$tourdate->cost_quad}})</option>"+
                                				@else
                                					"<option disabled>Quad Room Unavailable</option>"+
                                				@endif
                                				@if($tourdate->cost_quint > 0)
                                					"<option value=\"5\">Room for 5 ({{$tourdate->cost_quint}})</option>"+
                                				@else
                                					"<option disabled>Quint Room Unavailable</option>"+
                                				@endif
                                				@if($tourdate->cost_sext > 0)
                                					"<option value=\"6\">Room for 6 ({{$tourdate->cost_sext}})</option>"+
                                				@else
                                					"<option disabled>Sext Room Unavailable</option>"+
                                				@endif
                                			"</select>"+
                                		"</div>"+
                                	"</div>"+
                                	"<div class=\"row\">"+
                                		"<div class=\"col-md-3\">"+
                                			"<label>Travellers: </label>"+
                                		"</div>"+
                                		"<div class=\"col-md-9\">"+
                                			"<select name=\"rooms["+roomnum+"][travellers][]\" multiple rows=\"5\" class=\"select2\" placeholder=\"Please Select Travellers\">"+
				                				@foreach($travellers as $traveller)
				                					"<option value=\"{{$traveller->travellerID}}\">{{$traveller->NRIC}} - {{$traveller->fullname}}</option>"+
				                				@endforeach
                                			"</select>"+
                                		"</div>"+
                                	"</div>"+
                                	"<div class=\"row\">"+
                                		"<div class=\"col-md-4\">"+
                                			"<input type=\"hidden\" name=\"rooms["+roomnum+"][childcheck]\" value=\"off\" id=\"childcheck_"+roomnum+"_alt\">"+
                                			"<label>Children <input type=\"checkbox\" name=\"rooms["+roomnum+"][childcheck]\" id=\"childcheck_"+roomnum+"\"></label>"+
                                		"</div>"+
                                	"</div>"+
                                	"<div id=\"child_form_"+roomnum+"\" style=\"display: none;\">"+
                                		"<div class=\"row\">"+
                                    		"<div class=\"col-md-3\">"+
                                    			"<label>Type: </label>"+
                                    		"</div>"+
                                    		"<div class=\"col-md-9\">"+
                                    			"<select class=\"form-control\" name=\"rooms["+roomnum+"][child_room]\">"+
                                    				"<option hidden disabled selected>Please Select Room</option>"+
                                    				@if($tourdate->cost_child > 0)
                                    					"<option value=\"7\">Children with bed ({{$tourdate->cost_child}})</option>"+
                                    				@else
                                    					"<option disabled>Children with bed Unavailable</option>"+
                                    				@endif
                                    				@if($tourdate->cost_child_wo_bed > 0)
                                    					"<option value=\"8\">Children without bed ({{$tourdate->cost_child_wo_bed}})</option>"+
                                    				@else
                                    					"<option disabled>Children without bed Unavailable</option>"+
                                    				@endif
                                    				@if($tourdate->cost_infant_wo_bed > 0)
                                    					"<option value=\"9\">Infant ({{$tourdate->cost_infant_wo_bed}})</option>"+
                                    				@else
                                    					"<option disabled>Infant Unavailable</option>"+
                                    				@endif
                                    			"</select>"+
                                    		"</div>"+
                                    	"</div>"+
                                		"<div class=\"row\">"+
                                    		"<div class=\"col-md-3\">"+
                                    			"<label>Children: </label>"+
                                    		"</div>"+
                                    		"<div class=\"col-md-9\">"+
                                    			"<select name=\"rooms["+roomnum+"][children][]\" multiple rows=\"5\" class=\"select2\" placeholder=\"Please Select Travellers\">"+
					                				@foreach($children as $traveller)
					                					"<option value=\"{{$traveller->travellerID}}\">{{$traveller->NRIC}} - {{$traveller->fullname}}</option>"+
					                				@endforeach
                                    			"</select>"+
                                    		"</div>"+
                                    	"</div>"+
                                	"</div>"+
                                	"<div class=\"row\">"+
                                		"<div class=\"col-md-4\">"+
                                			"<input type=\"hidden\" name=\"rooms["+roomnum+"][infantcheck]\" value=\"off\" id=\"infantcheck_"+roomnum+"_alt\">"+
                                			"<label>Infant <input type=\"checkbox\" name=\"rooms["+roomnum+"][infantcheck]\" id=\"infantcheck_"+roomnum+"\"></label>"+
                                		"</div>"+
                                	"</div>"+
                                	"<div id=\"infant_form_"+roomnum+"\" style=\"display: none;\">"+
                                		"<div class=\"row\">"+
                                    		"<div class=\"col-md-3\">"+
                                    			"<label>Type: </label>"+
                                    		"</div>"+
                                    		"<div class=\"col-md-9\">"+
                                    			"<select class=\"form-control\" name=\"rooms["+roomnum+"][infant_room]\">"+
                                    				"<option hidden disabled selected>Please Select Room</option>"+
                                    				@if($tourdate->cost_infant_wo_bed > 0)
                                    					"<option value=\"9\">Infant ({{$tourdate->cost_infant_wo_bed}})</option>"+
                                    				@else
                                    					"<option disabled>Infant Unavailable</option>"+
                                    				@endif
                                    			"</select>"+
                                    		"</div>"+
                                    	"</div>"+
                                		"<div class=\"row\">"+
                                    		"<div class=\"col-md-3\">"+
                                    			"<label>Infants: </label>"+
                                    		"</div>"+
                                    		"<div class=\"col-md-9\">"+
                                    			"<select name=\"rooms["+roomnum+"][infants][]\" multiple rows=\"5\" class=\"select2\" placeholder=\"Please Select Travellers\">"+
					                				@foreach($infants as $traveller)
					                					"<option value=\"{{$traveller->travellerID}}\">{{$traveller->NRIC}} - {{$traveller->fullname}}</option>"+
					                				@endforeach
                                    			"</select>"+
                                    		"</div>"+
                                    	"</div>"+
                                	"</div>"+

                                "</div>"+
                            "</div>"+
                        "</div>";

            $('#room_'+(roomnum-1)).after(string);
            $('.select2').select2({ 
            	width:"100%"
            });
            $('input[type="checkbox"]').iCheck({
				checkboxClass: 'icheckbox_square-red'
			});	
			checkboxshowform();
		}

		function deleteRoom(id) {
			let room = document.getElementById(id);
			room.innerHTML = "";
			room.className = "";
		}

	</script>
@endsection