@extends('layouts.app')

@section('content')
<style>
	th, td{
		padding: 2px;
	}
</style>

    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
         <li><a href="{{ url('piform?return='.$return) }}"> {{ $pageTitle }} </a></li>
        <li  class="active"> Update </li>
      </ol>
    </section>

  <div class="content"> 

<div class="box box-primary">
	<div class="box-header with-border">

		<div class="box-header-tools pull-left" >
			<a href="{{ url($pageModule.'?return='.$return) }}" class="tips btn btn-sm btn-success btn-circle"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left"></i></a> 
		</div>
		<div class="box-header-tools pull-right" >
			@if(Session::get('gid') ==1)
				<a href="{{ URL::to('mmb/module/config/'.$pageModule) }}" class="tips btn btn-sm btn-success btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa  fa-ellipsis-v"></i></a>
			@endif 			
		</div> 

	</div>
	<div class="box-body"> 	

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>	

		 {!! Form::open(array('url'=>'piform/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Pi Form</legend>
									
									  <input type="hidden" name="id" value="{{$piform->id}}">

									  {{-- <input type="hidden" name="tourdate_id" value="{{$row['tourdate_id']}}"> --}}

									  {{-- <div class="form-group  " >
										<label for="Tourdate Id" class=" control-label col-md-4 text-left"> Tourdate Id </label>
										<div class="col-md-6">
											<select class="form-control" name='tourdate_id'>
												<option></option>
											</select>
										  <input  type='text' name='tourdate_id' id='tourdate_id' value='{{ $row['tourdate_id'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2"> --}}

									<div class="form-group">
										<label for="Tour Category" class="control-label col-md-4 text-left"> Tour Category </label>
										<div class="col-md-6">
											<select class="form-control" onchange="changeCategory(this.value);" @if ($tourdate)
												disabled="" 
											@endif>
												<option disabled selected hidden>Select umrah/tour category...</option>
												<option disabled style="background: grey; color: white;">Umrah Category</option>
												@foreach($tourcategories->where('type', 1) as $umrahtype)
													<option value="{{$umrahtype->tourcategoriesID}}" @if ($piform->tourdate)
														@if ($piform->tourdate->tourcategoriesID == $umrahtype->tourcategoriesID)
															selected 
														@endif
													@endif>{{$umrahtype->tourcategoryname}}</option>
												@endforeach
												<option disabled style="background: grey; color: white;">Tour Category</option>
												@foreach($tourcategories->where('type', 2) as $tourtype)
													<option value="{{$tourtype->tourcategoriesID}}" @if ($piform->tourdate)
														@if ($piform->tourdate->tourcategoriesID == $tourtype->tourcategoriesID)
															selected 
														@endif
													@endif>{{$tourtype->tourcategoryname}}</option>
												@endforeach
												@if($tourdate)
													<option selected>{{$tourdate->tourcategory->tourcategoryname}}</option>
												@endif
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for="Tour" class="control-label col-md-4 text-left"> Tour </label>
										<div class="col-md-6">
											<select class="form-control" id="tour" onchange="changeTour(this.value);" @if ($tourdate)
												disabled 
											@endif>
												@if($piform->tourdate)
													@foreach($piform->tourdate->tourcategory->tours as $tour)
														<option value="{{$tour->tourID}}" @if ($piform->tourdate->tourID == $tour->tourID)
															selected 
														@endif>{{$tour->tour_name}}</option>
													@endforeach
												@else
													<option disabled hidden selected>Please select category first</option>
													@if($tourdate)
														<option selected>{{$tourdate->tour->tour_name}}</option>
													@endif
												@endif
											</select>
										</div>
									</div>

									<div class="form-group">
										<label for="Tour Date" class="control-label col-md-4 text-left"> Tour Date </label>
										<div class="col-md-6">
											@if ($tourdate)
												<input type="hidden" name="tourdate_id" value="{{$tourdate->tourdateID}}">
											@endif
											<select class="form-control" id="tourdate" onchange="changeTourdate(this.value);" name="tourdate_id" @if ($tourdate)
												disabled 
											@endif>
												@if($piform->tourdate && $piform->tourdate->tour)
													@foreach($piform->tourdate->tour->tourdates as $tourdate)
														<option value="{{$tourdate->tourdateID}}" @if ($piform->tourdate->tourdateID == $tourdate->tourdateID)
															selected 
														@endif>{{$tourdate->start}} - {{$tourdate->end}} ({{$tourdate->tour_code}})</option>
													@endforeach
												@else
													<option disabled hidden selected>Please select tour first</option>
													@if($tourdate)
														<option value="{{$tourdate->tourdateID}}" selected>{{$tourdate->start}} - {{$tourdate->end}} ({{$tourdate->tour_code}})</option>
													@endif
												@endif
											</select>
										</div>
									</div>

									  <div class="form-group  " >
										<label for="Group Name" class=" control-label col-md-4 text-left"> Group Name </label>
										<div class="col-md-6">
										  <input  type='text' name='group_name' id='group_name' value='{{$piform->group_name}}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Leader Id" class=" control-label col-md-4 text-left"> Leader </label>
										<div class="col-md-6">
											<select name="leader_id" class="form-control">
												<option disabled hidden selected>Please select group leader...</option>
												@foreach($guides as $guide)
													<option value="{{$guide->guideID}}" @if ($piform->leader_id == $guide->guideID)
														selected 
													@endif>{{$guide->name}}</option>
												@endforeach
											</select>
										  {{-- <input  type='text' name='leader_id' id='leader_id' value='{{ $row['leader_id'] }}' 
						     class='form-control ' />  --}}
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 	

									  <hr>
									  <h4>Flight Schedule</h4>

									  <div id="flight_schedule">Waiting for tour and date...</div>

									  <hr>
									  <h4>Accomodation</h4>

									  <table id="accomodation_item">
									  	<tr>
									  		<th style="width: 30px"></th>
									  		<th>Hotel</th>
									  		<th>Check In</th>
									  		<th>Check out</th>
									  		<th>Single</th>
									  		<th>Double</th>
									  		<th>Triple</th>
									  		<th>Quad</th>
									  		<th>Quintuple</th>
									  		<th>Sextuple</th>
									  	</tr>
									  	@if($piform->id)
									  		<?php $num = 0; ?>
									  		@foreach($piform->accomodations as $accomodation)
									  			<tr id="accom_{{++$num}}">
										  			<td><a class="btn btn-danger" onclick="removeItem('accom_{{$num}}');"><i class="fa fa-times"></i></a></td>
										  			<td>
										  				<input type="hidden" name="accomodation[{{$num}}][id]" value="{{$accomodation->id}}">
										  				<select class="form-control" name="accomodation[{{$num}}][hotel_id]">
										  					@foreach($hotels as $hotel)
										  					<option value="{{$hotel->hotelID}}" @if ($accomodation->hotel_id == $hotel->hotelID)
										  						selected="" 
										  					@endif>{{$hotel->city->city_name}} - {{$hotel->hotel_name}}</option>
										  					@endforeach
										  				</select>
										  			</td>
										  			<td>
										  				<input type="date" name="accomodation[{{$num}}][check_in]" class="form-control" style="width: 173px;" value="{{$accomodation->check_in}}">
										  			</td>
										  			<td>
														<input type="date" name="accomodation[{{$num}}][check_out]" class="form-control" style="width: 173px;" value="{{$accomodation->check_out}}">
										  			</td>
										  			<td>
										  				<input type="number" name="accomodation[{{$num}}][single]" class="form-control" style="width: 70px;" value="{{$accomodation->single}}">
										  			</td>
										  			<td>
										  				<input type="number" name="accomodation[{{$num}}][double]" class="form-control" style="width: 70px;" value="{{$accomodation->double}}">
										  			</td>
										  			<td>
										  				<input type="number" name="accomodation[{{$num}}][triple]" class="form-control" style="width: 70px;" value="{{$accomodation->triple}}">
										  			</td>
										  			<td>
										  				<input type="number" name="accomodation[{{$num}}][quad]" class="form-control" style="width: 70px;" value="{{$accomodation->quad}}">
										  			</td>
										  			<td>
										  				<input type="number" name="accomodation[{{$num}}][quint]" class="form-control" style="width: 70px;" value="{{$accomodation->quint}}">
										  			</td>
										  			<td>
										  				<input type="number" name="accomodation[{{$num}}][sext]" class="form-control" style="width: 70px;" value="{{$accomodation->sext}}">
										  			</td>

										  		</tr>
									  		@endforeach
									  	@else
									  		<tr id="accom_1">
									  			<td><a class="btn btn-danger" onclick="removeItem('accom_1');"><i class="fa fa-times"></i></a></td>
									  			<td>
									  				<input type="hidden" name="accomodation[1][id]">
									  				<select class="form-control" name="accomodation[1][hotel_id]">
									  					@foreach($hotels as $hotel)
									  					<option value="{{$hotel->hotelID}}">{{$hotel->city->city_name}} - {{$hotel->hotel_name}}</option>
									  					@endforeach
									  				</select>
									  			</td>
									  			<td>
									  				<input type="date" name="accomodation[1][check_in]" class="form-control" style="width: 173px;">
									  			</td>
									  			<td>
													<input type="date" name="accomodation[1][check_out]" class="form-control" style="width: 173px;">
									  			</td>
									  			<td>
									  				<input type="number" name="accomodation[1][single]" class="form-control" style="width: 70px;">
									  			</td>
									  			<td>
									  				<input type="number" name="accomodation[1][double]" class="form-control" style="width: 70px;">
									  			</td>
									  			<td>
									  				<input type="number" name="accomodation[1][triple]" class="form-control" style="width: 70px;">
									  			</td>
									  			<td>
									  				<input type="number" name="accomodation[1][quad]" class="form-control" style="width: 70px;">
									  			</td>
									  			<td>
									  				<input type="number" name="accomodation[1][quint]" class="form-control" style="width: 70px;">
									  			</td>
									  			<td>
									  				<input type="number" name="accomodation[1][sext]" class="form-control" style="width: 70px;">
									  			</td>

									  		</tr>
								  		@endif
									  </table>	
									  <div class="row">
									  	<div class="col-md-12">
									  		<a class="btn btn-success pull-right" onclick="addAccomodation();"><i class="fa fa-plus-square-o fa-lg"></i></a>
									  	</div>
									  </div>	

									  <hr>
									  <h4>Ziarah</h4>

									  <table id="ziarah_item">
									  	<tr>
									  		<th style="width: 30px"></th>
									  		<th>City</th>
									  		<th>Date</th>
									  		<th>Time</th>
									  		<th>Transport</th>
									  	</tr>
									  	@if($piform->id)
									  		<?php $num = 0; ?>
									  		@foreach($piform->ziarahs as $ziarah)
									  			<tr id="zia_{{++$num}}">
											  		<td><a class="btn btn-danger" onclick="removeItem('zia_{{$num}}');"><i class="fa fa-times"></i></a></td>
											  		<td>
											  			<input type="hidden" name="ziarah[{{$num}}][id]" value="{{$ziarah->id}}">
											  			<input type="text" name="ziarah[{{$num}}][city]" class="form-control"  value="{{$ziarah->city}}">
											  		</td>
											  		<td>
											  			<input type="date" name="ziarah[{{$num}}][date]" class="form-control" style="width: 173px;" value="{{$ziarah->date}}">
											  		</td>
											  		<td>
											  			<input type="text" name="ziarah[{{$num}}][time]" class="form-control"  value="{{$ziarah->time}}">
											  		</td>
											  		<td>
											  			<select class="form-control" name="ziarah[{{$num}}][transport]">
											  				<option disabled selected hidden>Please Select Supplier...</option>
											  				@foreach($supplier_types as $type)
											  					<option disabled style="background: grey; color: white;">{{$type->supplier_type}}</option>
											  					@foreach($type->suppliers as $supplier)
											  						<option value="{{$supplier->supplierID}}" @if ($ziarah->transport == $supplier->supplierID)
											  							selected 
											  						@endif>{{$supplier->name}}</option>
											  					@endforeach
											  				@endforeach
											  			</select>
											  		</td>
											  	</tr>
									  		@endforeach
									  	@else
										  	<tr id="zia_1">
										  		<td><a class="btn btn-danger" onclick="removeItem('zia_1');"><i class="fa fa-times"></i></a></td>
										  		<td>
										  			<input type="hidden" name="ziarah[1][id]">
										  			<input type="text" name="ziarah[1][city]" class="form-control">
										  		</td>
										  		<td>
										  			<input type="date" name="ziarah[1][date]" class="form-control" style="width: 173px;">
										  		</td>
										  		<td>
										  			<input type="text" name="ziarah[1][time]" class="form-control">
										  		</td>
										  		<td>
										  			<select class="form-control" name="ziarah[1][transport]">
										  				<option disabled selected hidden>Please Select Supplier...</option>
										  				@foreach($supplier_types as $type)
										  					<option disabled style="background: grey; color: white;">{{$type->supplier_type}}</option>
										  					@foreach($type->suppliers as $supplier)
										  						<option value="{{$supplier->supplierID}}">{{$supplier->name}}</option>
										  					@endforeach
										  				@endforeach
										  			</select>
										  		</td>
										  	</tr>
									  	@endif
									  </table>
									  <div class="row">
									  	<div class="col-md-12">
									  		<a class="btn btn-success pull-right" onclick="addZiarah();"><i class="fa fa-plus-square-o fa-lg"></i></a>	
									  	</div>
									  </div>
									  

									  <hr>
									  <h4>Transportation</h4>	

									  <table id="transportation_item">
									  	<tr>
									  		<th style="width: 30px"></th>
									  		<th>From</th>
									  		<th>To</th>
									  		<th>Date</th>
									  		<th>Time</th>
									  		<th>Remark</th>
									  	</tr>
									  	@if($piform->id)
									  	<?php $num = 0; ?>
									  		@foreach($piform->transportations as $transportation)
									  			<tr id="trans_{{++$num}}">
											  		<td><a class="btn btn-danger" onclick="removeItem('trans_{{$num}}');"><i class="fa fa-times"></i></a></td>
											  		<td>
											  			<input type="hidden" name="transportation[{{$num}}][id]" value="{{$transportation->id}}">
											  			<input type="text" name="transportation[{{$num}}][from]" class="form-control" value="{{$transportation->from}}">
											  		</td>
											  		<td>
											  			<input type="text" name="transportation[{{$num}}][to]" class="form-control" value="{{$transportation->to}}">
											  		</td>
											  		<td>
											  			<input type="date" name="transportation[{{$num}}][date]" class="form-control" style="width: 173px;" value="{{$transportation->date}}">
											  		</td>
											  		<td>
											  			<input type="text" name="transportation[{{$num}}][time]" class="form-control" value="{{$transportation->time}}">
											  		</td>
											  		<td>
											  			<input type="text" name="transportation[{{$num}}][remarks]" class="form-control" value="{{$transportation->remarks}}">
											  		</td>
											  	</tr>
									  		@endforeach
									  	@else
										  	<tr id="trans_1">
										  		<td><a class="btn btn-danger" onclick="removeItem('trans_1');"><i class="fa fa-times"></i></a></td>
										  		<td>
										  			<input type="hidden" name="transportation[1][id]">
										  			<input type="text" name="transportation[1][from]" class="form-control">
										  		</td>
										  		<td>
										  			<input type="text" name="transportation[1][to]" class="form-control">
										  		</td>
										  		<td>
										  			<input type="date" name="transportation[1][date]" class="form-control" style="width: 173px;">
										  		</td>
										  		<td>
										  			<input type="text" name="transportation[1][time]" class="form-control">
										  		</td>
										  		<td>
										  			<input type="text" name="transportation[1][remarks]" class="form-control">
										  		</td>
										  	</tr>
									  	@endif
									  </table>
									  <div class="row">
									  	<div class="col-md-12">
									  		<a class="btn btn-success pull-right" onclick="addTransportation();"><i class="fa fa-plus-square-o fa-lg"></i></a>
									  	</div>
									  </div>

									  <hr>
									  <h4>Local Contact</h4>	

									  <table id="local_contact_item">
									  	<tr>
									  		<th style="width: 30px"></th>
									  		<th>Name</th>
									  		<th>Contact</th>
									  	</tr>
									  	@if($piform->id)
									  		<?php $num = 0 ?>
									  		@foreach($piform->localContacts as $lc)
									  			<tr id="lc_{{++$num}}">
											  		<td><a class="btn btn-danger" onclick="removeItem('lc_{{$num}}');"><i class="fa fa-times"></i></a></td>
											  		<td>
											  			<input type="hidden" name="local_contact[{{$num}}][id]" value="{{$lc->id}}">
											  			<input type="text" name="local_contact[{{$num}}][name]" class="form-control" value="{{$lc->name}}">
											  		</td>
											  		<td>
											  			<input type="text" name="local_contact[{{$num}}][contact]" class="form-control" value="{{$lc->contact}}">
											  		</td>
											  	</tr>
									  		@endforeach
									  	@else
										  	<tr id="lc_1">
										  		<td><a class="btn btn-danger" onclick="removeItem('lc_1');"><i class="fa fa-times"></i></a></td>
										  		<td>
										  			<input type="hidden" name="local_contact[1][id]">
										  			<input type="text" name="local_contact[1][name]" class="form-control">
										  		</td>
										  		<td>
										  			<input type="text" name="local_contact[1][contact]" class="form-control">
										  		</td>
										  	</tr>
									  	@endif
									  </table>
									  <div class="row">
									  	<div class="col-md-12">
									  		<a class="btn btn-success pull-right" onclick="addLocalContact();"><i class="fa fa-plus-square-o fa-lg"></i></a>
									  	</div>
									  </div>

									  <hr>
									  <h4>Remarks</h4>	

									  <table id="remark_item">
									  	<tr>
									  		<th style="width: 30px"></th>
									  		<th>Remark</th>
									  	</tr>
									  	@if($piform->id)
									  		<?php $num = 0; ?>
									  		@foreach($piform->remarks as $remark)
									  			<tr id="remark_{{++$num}}">
											  		<td><a class="btn btn-danger" onclick="removeItem('remark_{{$num}}');"><i class="fa fa-times"></i></a></td>
											  		<td>
											  			<input type="hidden" name="remark[{{$num}}][id]" value="{{$remark->id}}">
											  			<input type="text" name="remark[{{$num}}][remark]" class="form-control" value="{{$remark->remark}}">
											  		</td>
											  	</tr>
									  		@endforeach
									  	@else
										  	<tr id="remark_1">
										  		<td><a class="btn btn-danger" onclick="removeItem('remark_1');"><i class="fa fa-times"></i></a></td>
										  		<td>
										  			<input type="hidden" name="remark[1][id]">
										  			<input type="text" name="remark[1][remark]" class="form-control">
										  		</td>
										  	</tr>
									  	@endif
									  </table>
									  <div class="row">
									  	<div class="col-md-12">
									  		<a class="btn btn-success pull-right" onclick="addRemark();"><i class="fa fa-plus-square-o fa-lg"></i></a>
									  	</div>
									  </div>


									   </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-success " > {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary " > {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick="location.href='{{ URL::to('piform?return='.$return) }}' " class="btn btn-danger  ">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
		 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		 

		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("piform/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		

	<script>
		@if($piform->id)
		var accomodation_num = {{$piform->accomodations->count()}};
		var ziarah_num = {{$piform->ziarahs->count()}};
		var transportation_num = {{$piform->transportations->count()}};
		var lc_num = {{$piform->localContacts->count()}};
		var remark_num = {{$piform->remarks->count()}};
		@else
		var accomodation_num = 1;
		var ziarah_num = 1;
		var transportation_num = 1;
		var lc_num = 1;
		var remark_num = 1;
		@endif

		function addAccomodation() {
			accomodation_num++;
			let items = document.getElementById("accomodation_item");
			let string = "<tr id=\"accom_"+accomodation_num+"\">"+
					  			"<td><a class=\"btn btn-danger\" onclick=\"removeItem('accom_"+accomodation_num+"');\"><i class=\"fa fa-times\"></i></a></td>"+
					  			"<td>"+
					  				"<input type=\"hidden\" name=\"accomodation["+accomodation_num+"][id]\">"+
					  				"<select class=\"form-control\" name=\"accomodation["+accomodation_num+"][hotel_id]\">"+
					  					"@foreach($hotels as $hotel)"+
					  					"<option value=\"{{$hotel->hotelID}}\">{{$hotel->city->city_name}} - {{$hotel->hotel_name}}</option>"+
					  					"@endforeach"+
					  				"</select>"+
					  			"</td>"+
					  			"<td>"+
					  				"<div class=\"input-group\" style=\"width: 113px;\">"+
										"<input type=\"date\" name=\"accomodation["+accomodation_num+"][check_in]\" class=\"form-control\" style=\"width: 173px;\">"+
									"</div>"+
					  			"</td>"+
					  			"<td>"+
					  				"<div class=\"input-group\" style=\"width: 113px;\">"+
										"<input type=\"date\" name=\"accomodation["+accomodation_num+"][check_out]\" class=\"form-control\" style=\"width: 173px;\">"+
									"</div>"+
					  			"</td>"+
					  			"<td>"+
					  				"<input type=\"number\" name=\"accomodation["+accomodation_num+"][single]\" class=\"form-control\" style=\"width: 70px;\">"+
					  			"</td>"+
					  			"<td>"+
					  				"<input type=\"number\" name=\"accomodation["+accomodation_num+"][double]\" class=\"form-control\" style=\"width: 70px;\">"+
					  			"</td>"+
					  			"<td>"+
					  				"<input type=\"number\" name=\"accomodation["+accomodation_num+"][triple]\" class=\"form-control\" style=\"width: 70px;\">"+
					  			"</td>"+
					  			"<td>"+
					  				"<input type=\"number\" name=\"accomodation["+accomodation_num+"][quad]\" class=\"form-control\" style=\"width: 70px;\">"+
					  			"</td>"+
					  			"<td>"+
					  				"<input type=\"number\" name=\"accomodation["+accomodation_num+"][quint]\" class=\"form-control\" style=\"width: 70px;\">"+
					  			"</td>"+
					  			"<td>"+
					  				"<input type=\"number\" name=\"accomodation["+accomodation_num+"][sext]\" class=\"form-control\" style=\"width: 70px;\">"+
					  			"</td>"+

					  		"</tr>";

			$('#accom_'+(accomodation_num-1)).after(string);
		}

		function removeItem(id) {
			document.getElementById(id).outerHTML = "";
		}

		function addZiarah() {
			ziarah_num++;
			let items = document.getElementById("ziarah_item");
			let string = "<tr id=\"zia_"+ziarah_num+"\">"+
						  		"<td><a class=\"btn btn-danger\" onclick=\"removeItem('zia_"+ziarah_num+"');\"><i class=\"fa fa-times\"></i></a></td>"+
						  		"<td>"+
						  			"<input type=\"hidden\" name=\"ziarah["+ziarah_num+"][id]\">"+
						  			"<input type=\"text\" name=\"ziarah["+ziarah_num+"][city]\" class=\"form-control\">"+
						  		"</td>"+
						  		"<td>"+
						  			"<input type=\"date\" name=\"ziarah["+ziarah_num+"][date]\" class=\"form-control\" style=\"width: 173px;\">"+
						  		"</td>"+
						  		"<td>"+
						  			"<input type=\"text\" name=\"ziarah["+ziarah_num+"][time]\" class=\"form-control\">"+
						  		"</td>"+
						  		"<td>"+
						  			"<select class=\"form-control\" name=\"ziarah["+ziarah_num+"][transport]\">"+
						  				"<option disabled selected hidden>Please Select Supplier...</option>"+
						  				"@foreach($supplier_types as $type)"+
						  					"<option disabled style=\"background: grey; color: white;\">{{$type->supplier_type}}</option>"+
						  					"@foreach($type->suppliers as $supplier)"+
						  						"<option value=\"{{$supplier->supplierID}}\">{{$supplier->name}}</option>"+
						  					"@endforeach"+
						  				"@endforeach"+
						  			"</select>"+
						  		"</td>"+
						  	"</tr>";

			$('#zia_'+(ziarah_num-1)).after(string);
		}

		function addTransportation() {
			transportation_num++;
			let items = document.getElementById("transportation_item");
			let string = "<tr id=\"trans_"+transportation_num+"\">"+
						  		"<td><a class=\"btn btn-danger\" onclick=\"removeItem('trans_"+transportation_num+"');\"><i class=\"fa fa-times\"></i></a></td>"+
						  		"<td>"+
						  			"<input type=\"hidden\" name=\"transportation["+transportation_num+"][id]\">"+
						  			"<input type=\"text\" name=\"transportation["+transportation_num+"][from]\" class=\"form-control\">"+
						  		"</td>"+
						  		"<td>"+
						  			"<input type=\"text\" name=\"transportation["+transportation_num+"][to]\" class=\"form-control\">"+
						  		"</td>"+
						  		"<td>"+
						  			"<input type=\"date\" name=\"transportation["+transportation_num+"][date]\" class=\"form-control\" style=\"width: 173px;\">"+
						  		"</td>"+
						  		"<td>"+
						  			"<input type=\"text\" name=\"transportation["+transportation_num+"][time]\" class=\"form-control\">"+
						  		"</td>"+
						  		"<td>"+
						  			"<input type=\"text\" name=\"transportation["+transportation_num+"][remarks]\" class=\"form-control\">"+
						  		"</td>"+
						  	"</tr>";

			$('#trans_'+(transportation_num-1)).after(string);
		}

		function addLocalContact() {
			lc_num++;
			let items = document.getElementById("local_contact_item");
			let string = "<tr id=\"lc_"+lc_num+"\">"+
						  		"<td><a class=\"btn btn-danger\" onclick=\"removeItem('lc_"+lc_num+"');\"><i class=\"fa fa-times\"></i></a></td>"+
						  		"<td>"+
						  			"<input type=\"hidden\" name=\"local_contact["+lc_num+"][id]\">"+
						  			"<input type=\"text\" name=\"local_contact["+lc_num+"][name]\" class=\"form-control\">"+
						  		"</td>"+
						  		"<td>"+
						  			"<input type=\"text\" name=\"local_contact["+lc_num+"][contact]\" class=\"form-control\">"+
						  		"</td>"+
						  	"</tr>";

			$('#lc_'+(lc_num-1)).after(string);
		}

		function addRemark() {
			remark_num++;
			let items = document.getElementById("remark_item");
			let string = "<tr id=\"remark_"+remark_num+"\">"+
						  		"<td><a class=\"btn btn-danger\" onclick=\"removeItem('remark_"+remark_num+"');\"><i class=\"fa fa-times\"></i></a></td>"+
						  		"<td>"+
						  			"<input type=\"hidden\" name=\"remark["+remark_num+"][id]\">"+
						  			"<input type=\"text\" name=\"remark["+remark_num+"][remark]\" class=\"form-control\">"+
						  		"</td>"+
						  	"</tr>";

			$('#remark_'+(remark_num-1)).after(string);
		}

		function changeCategory(id) {
			axios.post('/piform/gettour', {id:id})
			.then(response=>{
				let tours = response.data;
				let tour_dropdown = document.getElementById("tour");
				let string = "<option disabled selected hidden>Please Select Tour...</option>";

				for (var i = 0; i < tours.length; i++) {
					string += "<option value=\""+tours[i]['tourID']+"\">"+tours[i]['tour_name']+"</option>";
				}

				tour_dropdown.innerHTML = string;
			}).catch(e=>{
				console.log(e);
			});
		}

		function changeTour(id) {
			axios.post('/piform/getdate', {id:id})
			.then(response=>{
				let tourdates = response.data;
				let tourdate_dropdown = document.getElementById("tourdate");
				let string = "<option disabled selected hidden>Please Select Tour...</option>";

				for (var i = 0; i < tourdates.length; i++) {
					string += "<option value=\""+tourdates[i]['tourdateID']+"\">"+tourdates[i]['start']+" - "+tourdates[i]['end']+" ("+tourdates[i]['tour_code']+")</option>";
				}

				tourdate_dropdown.innerHTML = string;
			}).catch(e=>{
				console.log(e);
			});
		}

		function changeTourdate(id) {
			axios.post('/piform/getflight', {id:id})
			.then(response=>{
				let flight_booking = response.data;
				let flight_schedule = document.getElementById("flight_schedule");
				// let string = "<option disabled selected hidden>Please Select Tour...</option>";

				// for (var i = 0; i < flight_booking.length; i++) {
				// 	string += "<option value=\""+flight_booking[i]['tourdateID']+"\">"+flight_booking[i]['start']+" - "+flight_booking[i]['end']+" ("+flight_booking[i]['tour_code']+")</option>";
				// }

				flight_schedule.innerHTML = flight_booking;
			}).catch(e=>{
				console.log(e);
			});
		}

		@if($piform->tourdate || $tourdate)
			changeTourdate(document.getElementById('tourdate').value);
		@endif


	</script> 
@stop