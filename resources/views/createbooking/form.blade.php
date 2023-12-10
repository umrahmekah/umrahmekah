@extends('layouts.app')

@section('content')

    <section class="content-header">
      <h1> {{ Lang::get('core.createbooking') }}</h1>
    </section>

  <div class="content"> 
      <div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-header-tools pull-left" >
			<a href="{{ url($pageModule.'?return='.$return) }}" class="tips"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left fa-2x"></i></a> 
		</div>
		<div class="box-header-tools " >
		</div> 
	</div>
	<div class="box-body"> 	
		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>	

		 {!! Form::open(array('url'=>'createbooking/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

		 					<?php 
                            $bookingno1 = substr(str_shuffle(str_repeat("ABCDEFGHJKLMNPQRSTUVWYZ", 2)), 0, 2);
                            $bookingno2 = substr(str_shuffle(str_repeat("123456789", 4)), 0, 4);
                            ?>

				{!! Form::hidden('bookingsID', $row['bookingsID']) !!}	
    
    @if($row['bookingno'] == NULL)
				{!! Form::hidden('bookingno', $bookingno1.$bookingno2 )!!}	
    @else
                {!! Form::hidden('bookingno', $row['bookingno'] )!!}	
    @endif

    								  <div class="form-group  " >
										<label for="TravellerID" class=" control-label col-md-5 text-left"> {{ Lang::get('core.select_treveller_book') }} </label>
										<div class="col-md-5">
										  <select name='travellerID' rows='5' id='travellerID' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div>

									  <input type='hidden' name='tour[]' value ='1'/>

									  <div class="form-group  " >
										<label for="TravellerID" class=" control-label col-md-5 text-left"> {{ Lang::get('core.tourcategories') }} </label>
										<div class="col-md-5">
										  <select name='tourcategoriesID' id='tourcategoriesID' class='select2' required>
										  	<option selected disabled hidden>{{ Lang::get('core.selectcategory') }}</option>
										  	@foreach($tourcategories as $category)
										  		<option value="{{ $category->tourcategoriesID }}" @if(isset($booktour) && $booktour->tourcategoriesID == $category->tourcategoriesID) selected @endif>{{ $category->tourcategoryname }}</option>
										  	@endforeach
										  </select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div>

									  <div class="form-group  " >
										<label for="TravellerID" class=" control-label col-md-5 text-left"> {{ Lang::get('core.tour') }} </label>
										<div class="col-md-5">
										  <select name='tourID' id='tourID' class='select2' required>
										  	<option selected disabled hidden>{{ Lang::get('core.selectcategoryfirst') }}</option>
										  </select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div>

									  <div class="form-group  " >
										<label for="TravellerID" class=" control-label col-md-5 text-left"> {{ Lang::get('core.tourdate') }} </label>
										<div class="col-md-5">
										  <select name='tourdateID' id='tourdateID' class='select2' required>
										  	<option selected disabled hidden>{{ Lang::get('core.selecttourfirst') }}</option>
										  </select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div>

									  <div class="form-group  " >
										<label for="TravellerID" class=" control-label col-md-5 text-left"> {{ Lang::get('core.source') }} </label>
										<div class="col-md-5">
										  <select name='source_id' id='source_id' class='select2' required>
										  	<option selected disabled hidden>{{ Lang::get('core.selectsource') }}</option>
										  	@foreach($sources as $key => $source)
										  		@if($key != 9)
										  			<option value="{{ $key }}" @if(isset($booktour) && $booktour->source_id == $key) selected @endif>{{$source}}</option>
										  		@endif
										  	@endforeach
										  </select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div>
									  
<!-- <div class="col-md-12">
                            <?php 
                            $bookingno1 = substr(str_shuffle(str_repeat("ABCDEFGHJKLMNPQRSTUVWYZ", 4)), 0, 4);
                            $bookingno2 = substr(str_shuffle(str_repeat("123456789", 6)), 0, 6);
                            ?>

				{!! Form::hidden('bookingsID', $row['bookingsID']) !!}	
    
    @if($row['bookingno'] == NULL)
				{!! Form::hidden('bookingno', $bookingno1.$bookingno2 )!!}	
    @else
                {!! Form::hidden('bookingno', $row['bookingno'] )!!}	
    @endif
									  <div class="form-group  " >
										<label for="TravellerID" class=" control-label col-md-5 text-left"> {{ Lang::get('core.namesurname') }} <span class="asterix"> * </span></label>
										<div class="col-md-5">
										  <select name='travellerID' rows='5' id='travellerID' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
                                    <div class="col-md-1">
										 </div>

										<div class="col-md-2">
										  <?php $tour = explode(",",$row['tour']); ?>
					 <label class='checked checkbox-inline text-center'>  <a class="btn btn-app" style="height: auto;">
                 {{ Lang::get('core.tour') }}
              </a><br> 
					<input type='checkbox' name='tour[]' value ='1'   class='' 
					@if(in_array('1',$tour))checked  @endif 
					 />  </label>  
										 </div> 
										<div class="col-md-2">
										  <?php $hotel = explode(",",$row['hotel']); ?>
					 <label class='checked checkbox-inline text-center'><a class="btn btn-app" style="height: auto;">
                 {{ Lang::get('core.hotel') }}
              </a><br>   
					<input type='checkbox' name='hotel[]' value ='1'   class='' 
					@if(in_array('1',$hotel))checked  @endif 
					 />  </label>  
										 </div> 
										<div class="col-md-2">
										  <?php $flight = explode(",",$row['flight']); ?>
					 <label class='checked checkbox-inline text-center'><a class="btn btn-app" style="height: auto;">
                 {{ Lang::get('core.flight') }}
              </a><br>   
					<input type='checkbox'  name='flight[]' value ='1'   class='' 
					@if(in_array('1',$flight))checked  @endif 
					 />  </label>  
										 </div> 
										<div class="col-md-2">
										  <?php $car = explode(",",$row['car']); ?>
					 <label class='checked checkbox-inline text-center'>  <a class="btn btn-app" style="height: auto;">
                 {{ Lang::get('core.car') }}
              </a><br> 
					<input type='checkbox' name='car[]' value ='1'   class='' 
					@if(in_array('1',$car))checked  @endif 
					 />  </label>  
										 </div> 
										<div class="col-md-2">
										  <?php $extraservices = explode(",",$row['extraservices']); ?>
					 <label class='checked checkbox-inline text-center'><a class="btn btn-app" style="height: auto;">
                 {{ Lang::get('core.extra') }}
              </a><br>   
					<input type='checkbox' name='extraservices[]' value ='1'   class='' 
					@if(in_array('1',$extraservices))checked  @endif 
					 />  </label>  
										 </div> 
                        <div class="col-md-1">
										 	
										 </div>

									  </div>
			</div> -->
			
			
			<div class="form-group  " >
				<label for="Status" class=" control-label col-md-4 text-left"> {{ Lang::get('core.status') }} <span class="asterix"> * </span></label>
				<div class="col-md-8">
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='2' required @if( ($booktour->status ?? 2) == 2) checked="checked" @endif > {{ Lang::get('core.fr_pending') }} </label>   										  
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='1' required @if( ($booktour->status ?? 2) == 1) checked="checked" @endif > {{ Lang::get('core.confirmed') }} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='0' required @if( ($booktour->status ?? 2) == 0) checked="checked" @endif > {{ Lang::get('core.cancelled') }} </label> 
				 </div> 
			</div>
		
			<div style="clear:both"></div>	
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<!-- <button type="submit" name="apply" class="btn btn-info btn-sm" > {{ Lang::get('core.sb_apply') }}</button> -->
					<button type="submit" name="submit" class="btn btn-primary btn-sm" > {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick="location.href='{{ URL::to('createbooking?return='.$return) }}' " class="btn btn-danger"> {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
		 
   <script>
	var inited = false;
	$(document).ready(function() { 
		
		
		$("#travellerID").jCombo("{!! url('createbooking/comboselect?filter=travellers:travellerID:nameandsurname&limit=WHERE:status:=:1&limit=WHERE:owner_id:=:'.CNF_OWNER) !!}",
        {  selected_value : '@if ( app('request')->input('travellerID') != NULL ) {{app('request')->input('travellerID')}} @else {{ $row["travellerID"] }} @endif' });

		 
		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("createbooking/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		

		$('#tourcategoriesID').change(function() {
		    var tourcategoriesID = $(this).val();
		    console.log($(this).val());
		    axios.post('/createbooking/tourlist', { _token:"{{csrf_token()}}", tourcategoriesID:tourcategoriesID })
		    .then(response => {
		    	var tours = response.data;
		    	var options = '<option selected disabled hidden value="">Select Tour</option>';
		    	for (var i = 0; i < tours.length; i++) {
		    		if (tours[i].tourID === {{ $booktour->tourID ?? 'null' }}) {
		    			var option = '<option selected value="'+String(tours[i].tourID)+'">'+tours[i].tour_name+'</option>';
		    		}else{
		    			var option = '<option value="'+String(tours[i].tourID)+'">'+tours[i].tour_name+'</option>';
		    		}
		    		
		    		options += option;
		    	}
		    	document.getElementById('tourID').innerHTML = options;
		    	if (document.getElementById('tourID').value !== null || document.getElementById('tourID').value !== "") {
		    		$('#tourID').trigger('change');
		    	}
		    }).catch(e => {
		    	console.log(e.response.data);
		    });
		});

		$('#tourID').change(function() {
		    var tourID = $(this).val();
		    console.log($(this).val());
		    axios.post('/createbooking/tourdatelist', { _token:"{{csrf_token()}}", tourID:tourID })
		    .then(response => {
		    	var dates = response.data;
		    	var options = '<option selected disabled hidden>Select Date</option>';
		    	for (var i = 0; i < dates.length; i++) {
		    		var start = dates[i].start.split("-");
		    		var end = dates[i].end.split("-");
		    		if (dates[i].tourdateID == {{ $booktour->tourdateID ?? 'null' }}) {
		    			var option = '<option selected value="'+String(dates[i].tourdateID)+'">'+String(start[2])+'/'+String(start[1])+'/'+String(start[0])+' - '+String(end[2])+'/'+String(end[1])+'/'+String(end[0])+' ('+dates[i].tour_code+')</option>';
		    		}else{
		    			var option = '<option value="'+String(dates[i].tourdateID)+'">'+String(start[2])+'/'+String(start[1])+'/'+String(start[0])+' - '+String(end[2])+'/'+String(end[1])+'/'+String(end[0])+' ('+dates[i].tour_code+')</option>';
		    		}
		    		
		    		options += option;
		    	}
		    	document.getElementById('tourdateID').innerHTML = options;
		    }).catch(e => {
		    	console.log(e);
		    });
		});

		@if (isset($booktour))
			fun1();
		@endif

		function fun1() {
			console.log('fun1');
			$('#tourcategoriesID').trigger('change');
		}
		
	});

	
	</script>		 
@stop