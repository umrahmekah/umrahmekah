

		 {!! Form::open(array('url'=>'tourboundbooking/savepublic', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

	@if(Session::has('messagetext'))
	  
		   {!! Session::get('messagetext') !!}
	   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		


<div class="col-md-12">
						<fieldset><legend> Tourbound Bookings</legend>
				{!! Form::hidden('bookingsID', $row['bookingsID']) !!}					
									  <div class="form-group  " >
										<label for="Bookingno" class=" control-label col-md-4 text-left"> Bookingno </label>
										<div class="col-md-6">
										  <textarea name='bookingno' rows='5' id='bookingno' class='form-control '  
				           >{{ $row['bookingno'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="TravellerID" class=" control-label col-md-4 text-left"> TravellerID <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='travellerID' rows='5' id='travellerID' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour" class=" control-label col-md-4 text-left"> Tour </label>
										<div class="col-md-6">
										  <?php $tour = explode(",",$row['tour']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='tour[]' value ='1'   class='' 
					@if(in_array('1',$tour))checked @endif 
					 /> Tour </label>  
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Hotel" class=" control-label col-md-4 text-left"> Hotel </label>
										<div class="col-md-6">
										  <?php $hotel = explode(",",$row['hotel']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='hotel[]' value ='1'   class='' 
					@if(in_array('1',$hotel))checked @endif 
					 /> Hotel </label>  
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Flight" class=" control-label col-md-4 text-left"> Flight </label>
										<div class="col-md-6">
										  <?php $flight = explode(",",$row['flight']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='flight[]' value ='1'   class='' 
					@if(in_array('1',$flight))checked @endif 
					 /> Flight </label>  
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Car" class=" control-label col-md-4 text-left"> Car </label>
										<div class="col-md-6">
										  <?php $car = explode(",",$row['car']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='car[]' value ='1'   class='' 
					@if(in_array('1',$car))checked @endif 
					 /> Car </label>  
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Extraservices" class=" control-label col-md-4 text-left"> Extraservices </label>
										<div class="col-md-6">
										  <?php $extraservices = explode(",",$row['extraservices']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='extraservices[]' value ='1'   class='' 
					@if(in_array('1',$extraservices))checked @endif 
					 /> Extra </label>  
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> {!! Form::hidden('totaltravellers', $row['totaltravellers']) !!}{!! Form::hidden('balance', $row['balance']) !!}{!! Form::hidden('affiliatelink', $row['affiliatelink']) !!}{!! Form::hidden('owner_id', $row['owner_id']) !!}{!! Form::hidden('new_traveler', $row['new_traveler']) !!}{!! Form::hidden('old_traveller', $row['old_traveller']) !!}</fieldset>
			</div>
			
			

			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
				  </div>	  
			
		</div> 
		 
		 {!! Form::close() !!}
		 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
		$("#travellerID").jCombo("{!! url('tourboundbooking/comboselect?filter=travellers:travellerID:nameandsurname') !!}",
		{  selected_value : '{{ $row["travellerID"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
