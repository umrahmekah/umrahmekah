

		 {!! Form::open(array('url'=>'tourbounddates/savepublic', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

	@if(Session::has('messagetext'))
	  
		   {!! Session::get('messagetext') !!}
	   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		


<div class="col-md-12">
						<fieldset><legend> Tour Details</legend>
				{!! Form::hidden('tourdateID', $row['tourdateID']) !!}					
									  <div class="form-group  " >
										<label for="Tour Category" class=" control-label col-md-4 text-left"> Tour Category <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='tourcategoriesID' rows='5' id='tourcategoriesID' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Name" class=" control-label col-md-4 text-left"> Tour Name <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='tourID' rows='5' id='tourID' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Code" class=" control-label col-md-4 text-left"> Tour Code <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='tour_code' id='tour_code' value='{{ $row['tour_code'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Start Date" class=" control-label col-md-4 text-left"> Start Date <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('start', $row['start'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="End Date" class=" control-label col-md-4 text-left"> End Date <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('end', $row['end'],array('class'=>'form-control date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Guide" class=" control-label col-md-4 text-left"> Guide </label>
										<div class="col-md-6">
										  <select name='guideID' rows='5' id='guideID' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Featured" class=" control-label col-md-4 text-left"> Featured </label>
										<div class="col-md-6">
										  <?php $featured = explode(",",$row['featured']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='featured[]' value ='1'   class='' 
					@if(in_array('1',$featured))checked @endif 
					 /> Featured </label>  
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Definite Departure" class=" control-label col-md-4 text-left"> Definite Departure </label>
										<div class="col-md-6">
										  <?php $definite_departure = explode(",",$row['definite_departure']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='definite_departure[]' value ='2'   class='' 
					@if(in_array('2',$definite_departure))checked @endif 
					 /> Definite Departure </label>  
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Group Size" class=" control-label col-md-4 text-left"> Group Size <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='total_capacity' id='total_capacity' value='{{ $row['total_capacity'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Currency" class=" control-label col-md-4 text-left"> Currency </label>
										<div class="col-md-6">
										  <select name='currencyID' rows='5' id='currencyID' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for Single room" class=" control-label col-md-4 text-left"> Tour Cost for Single room </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_single' id='cost_single' value='{{ $row['cost_single'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for Double Room " class=" control-label col-md-4 text-left"> Tour Cost for Double Room  </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_double' id='cost_double' value='{{ $row['cost_double'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for Triple Room" class=" control-label col-md-4 text-left"> Tour Cost for Triple Room </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_triple' id='cost_triple' value='{{ $row['cost_triple'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for Quad Room" class=" control-label col-md-4 text-left"> Tour Cost for Quad Room </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_quad' id='cost_quad' value='{{ $row['cost_quad'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for a Child" class=" control-label col-md-4 text-left"> Tour Cost for a Child </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_child' id='cost_child' value='{{ $row['cost_child'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Status" class=" control-label col-md-4 text-left"> Status <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='0' required @if($row['status'] == '0') checked="checked" @endif > Passive </label>
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='1' required @if($row['status'] == '1') checked="checked" @endif > Active </label> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Remarks" class=" control-label col-md-4 text-left"> Remarks </label>
										<div class="col-md-6">
										  <textarea name='remarks' rows='5' id='remarks' class='form-control '  
				           >{{ $row['remarks'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Color" class=" control-label col-md-4 text-left"> Color </label>
										<div class="col-md-6">
										  <textarea name='color' rows='5' id='color' class='form-control '  
				           >{{ $row['color'] }}</textarea> 
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
		
		
		$("#tourcategoriesID").jCombo("{!! url('tourbounddates/comboselect?filter=def_tour_categories:tourcategoriesID:tourcategoryname') !!}",
		{  selected_value : '{{ $row["tourcategoriesID"] }}' });
		
		$("#tourID").jCombo("{!! url('tourbounddates/comboselect?filter=tours:tourID:tour_name') !!}&parent=tourcategoriesID:",
		{  parent: '#tourcategoriesID', selected_value : '{{ $row["tourID"] }}' });
		
		$("#guideID").jCombo("{!! url('tourbounddates/comboselect?filter=guides:guideID:name') !!}",
		{  selected_value : '{{ $row["guideID"] }}' });
		
		$("#currencyID").jCombo("{!! url('tourbounddates/comboselect?filter=def_currency:currencyID:currency_sym|symbol') !!}",
		{  selected_value : '{{ $row["currencyID"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
