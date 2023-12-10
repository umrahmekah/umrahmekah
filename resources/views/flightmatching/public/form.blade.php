

		 {!! Form::open(array('url'=>'flightmatching/savepublic', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

	@if(Session::has('messagetext'))
	  
		   {!! Session::get('messagetext') !!}
	   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		


<div class="col-md-12">
						<fieldset><legend> Flight Matching</legend>
									
									  <div class="form-group  " >
										<label for="Id" class=" control-label col-md-4 text-left"> Id </label>
										<div class="col-md-6">
										  <input  type='text' name='id' id='id' value='{{ $row['id'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Flight Number 1" class=" control-label col-md-4 text-left"> Flight Number 1 </label>
										<div class="col-md-6">
										  <input  type='text' name='flight_number_1' id='flight_number_1' value='{{ $row['flight_number_1'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Sector 1" class=" control-label col-md-4 text-left"> Sector 1 </label>
										<div class="col-md-6">
										  <input  type='text' name='sector_1' id='sector_1' value='{{ $row['sector_1'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Day 1" class=" control-label col-md-4 text-left"> Day 1 </label>
										<div class="col-md-6">
										  <input  type='text' name='day_1' id='day_1' value='{{ $row['day_1'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Dep Time 1" class=" control-label col-md-4 text-left"> Dep Time 1 </label>
										<div class="col-md-6">
										  <input  type='text' name='dep_time_1' id='dep_time_1' value='{{ $row['dep_time_1'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Arr Time 1" class=" control-label col-md-4 text-left"> Arr Time 1 </label>
										<div class="col-md-6">
										  <input  type='text' name='arr_time_1' id='arr_time_1' value='{{ $row['arr_time_1'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Flight Number 2" class=" control-label col-md-4 text-left"> Flight Number 2 </label>
										<div class="col-md-6">
										  <input  type='text' name='flight_number_2' id='flight_number_2' value='{{ $row['flight_number_2'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Sector 2" class=" control-label col-md-4 text-left"> Sector 2 </label>
										<div class="col-md-6">
										  <input  type='text' name='sector_2' id='sector_2' value='{{ $row['sector_2'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Day 2" class=" control-label col-md-4 text-left"> Day 2 </label>
										<div class="col-md-6">
										  <input  type='text' name='day_2' id='day_2' value='{{ $row['day_2'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Dep Time 2" class=" control-label col-md-4 text-left"> Dep Time 2 </label>
										<div class="col-md-6">
										  <input  type='text' name='dep_time_2' id='dep_time_2' value='{{ $row['dep_time_2'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Arr Time 2" class=" control-label col-md-4 text-left"> Arr Time 2 </label>
										<div class="col-md-6">
										  <input  type='text' name='arr_time_2' id='arr_time_2' value='{{ $row['arr_time_2'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Number Of Days" class=" control-label col-md-4 text-left"> Number Of Days </label>
										<div class="col-md-6">
										  <input  type='text' name='number_of_days' id='number_of_days' value='{{ $row['number_of_days'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Flight Date" class=" control-label col-md-4 text-left"> Flight Date </label>
										<div class="col-md-6">
										  <input  type='text' name='flight_date' id='flight_date' value='{{ $row['flight_date'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> </fieldset>
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
		
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
