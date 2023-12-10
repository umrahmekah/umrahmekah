

		 {!! Form::open(array('url'=>'tourbound/savepublic', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

	@if(Session::has('messagetext'))
	  
		   {!! Session::get('messagetext') !!}
	   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		


<div class="col-md-12">
						<fieldset><legend> Tour</legend>
				{!! Form::hidden('tourID', $row['tourID']) !!}					
									  <div class="form-group  " >
										<label for="Tour Name" class=" control-label col-md-4 text-left"> Tour Name <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='tour_name' id='tour_name' value='{{ $row['tour_name'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Category" class=" control-label col-md-4 text-left"> Tour Category <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='tourcategoriesID' rows='5' id='tourcategoriesID' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Departs" class=" control-label col-md-4 text-left"> Departs <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  
					<label class='radio radio-inline'>
					<input type='radio' name='departs' value ='0' required @if($row['departs'] == '0') checked="checked" @endif > Daily </label>
					<label class='radio radio-inline'>
					<input type='radio' name='departs' value ='1' required @if($row['departs'] == '1') checked="checked" @endif > On Request </label>
					<label class='radio radio-inline'>
					<input type='radio' name='departs' value ='2' required @if($row['departs'] == '2') checked="checked" @endif > Set Date </label> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Description" class=" control-label col-md-4 text-left"> Tour Description <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <textarea name='tour_description' rows='5' id='tour_description' class='form-control '  
				         required  >{{ $row['tour_description'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Total Days" class=" control-label col-md-4 text-left"> Total Days <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='total_days' id='total_days' value='{{ $row['total_days'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Multicountry" class=" control-label col-md-4 text-left"> Multicountry </label>
										<div class="col-md-6">
										  <textarea name='multicountry' rows='5' id='multicountry' class='form-control '  
				           >{{ $row['multicountry'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Time" class=" control-label col-md-4 text-left"> Time </label>
										<div class="col-md-6">
										  <textarea name='time' rows='5' id='time' class='form-control '  
				           >{{ $row['time'] }}</textarea> 
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
										<label for="Total Nights" class=" control-label col-md-4 text-left"> Total Nights <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='total_nights' id='total_nights' value='{{ $row['total_nights'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Inclusions" class=" control-label col-md-4 text-left"> Inclusions </label>
										<div class="col-md-6">
										  <select name='inclusions[]' multiple rows='5' id='inclusions' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="CountryID" class=" control-label col-md-4 text-left"> CountryID </label>
										<div class="col-md-6">
										  <textarea name='countryID' rows='5' id='countryID' class='form-control '  
				           >{{ $row['countryID'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Similar Tours" class=" control-label col-md-4 text-left"> Similar Tours </label>
										<div class="col-md-6">
										  <select name='similartours[]' multiple rows='5' id='similartours' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	<a href="#" data-toggle="tooltip" placement="left" class="tips" title="You can pick more than one tour"><i class="icon-question2"></i></a>
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Payment Options" class=" control-label col-md-4 text-left"> Payment Options <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='payment_options[]' multiple rows='5' id='payment_options' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	<a href="#" data-toggle="tooltip" placement="left" class="tips" title="You can pick more than one payment option"><i class="icon-question2"></i></a>
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
										<label for="Term & Conditions" class=" control-label col-md-4 text-left"> Term & Conditions <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='policyandterms' rows='5' id='policyandterms' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Views" class=" control-label col-md-4 text-left"> Views </label>
										<div class="col-md-6">
										  <textarea name='views' rows='5' id='views' class='form-control '  
				           >{{ $row['views'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tourimage" class=" control-label col-md-4 text-left"> Tourimage </label>
										<div class="col-md-6">
										  <input  type='file' name='tourimage' id='tourimage' @if($row['tourimage'] =='') class='required' @endif style='width:150px !important;'  />
					 	<div >
						{!! SiteHelpers::showUploadedFile($row['tourimage'],'/uploads/images/') !!}
						
						</div>					
					 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="flight" class=" control-label col-md-4 text-left"> flight </label>
										<div class="col-md-6">
										  <input  type='text' name='flight' id='flight' value='{{ $row['flight'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="cost_triple" class=" control-label col-md-4 text-left"> cost_triple </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_triple' id='cost_triple' value='{{ $row['cost_triple'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="sector" class=" control-label col-md-4 text-left"> sector </label>
										<div class="col-md-6">
										  <input  type='text' name='sector' id='sector' value='{{ $row['sector'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="baggage_limit" class=" control-label col-md-4 text-left"> baggage_limit </label>
										<div class="col-md-6">
										  <input  type='text' name='baggage_limit' id='baggage_limit' value='{{ $row['baggage_limit'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="transit" class=" control-label col-md-4 text-left"> transit </label>
										<div class="col-md-6">
										  <input  type='text' name='transit' id='transit' value='{{ $row['transit'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Gallery" class=" control-label col-md-4 text-left"> Gallery </label>
										<div class="col-md-6">
										  
					<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('gallery')"><i class="fa fa-plus-square"></i></a>
					<div class="galleryUpl">	
					 	<input  type='file' name='gallery[]'  />			
					</div>
					<ul class="uploadedLists " >
					<?php $cr= 0; 
					$row['gallery'] = explode(",",$row['gallery']);
					?>
					@foreach($row['gallery'] as $files)
						@if(file_exists('./uploads/images/'.$files) && $files !='')
						<li id="cr-<?php echo $cr;?>" class="">							
							<a href="{{ url('/uploads/images//'.$files) }}" target="_blank" >{{ $files }}</a> 
							<span class="pull-right removeMultiFiles" rel="cr-<?php echo $cr;?>" url="/uploads/images/{{$files}}">
							<i class="fa fa-trash-o fa-2x"></i></span>
							<input type="hidden" name="currgallery[]" value="{{ $files }}"/>
							<?php ++$cr;?>
						</li>
						@endif
					
					@endforeach
					</ul>
					 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="cost_single" class=" control-label col-md-4 text-left"> cost_single </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_single' id='cost_single' value='{{ $row['cost_single'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="cost_double" class=" control-label col-md-4 text-left"> cost_double </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_double' id='cost_double' value='{{ $row['cost_double'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="cost_quad" class=" control-label col-md-4 text-left"> cost_quad </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_quad' id='cost_quad' value='{{ $row['cost_quad'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="cost_child" class=" control-label col-md-4 text-left"> cost_child </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_child' id='cost_child' value='{{ $row['cost_child'] }}' 
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
		
		
		$("#tourcategoriesID").jCombo("{!! url('tourbound/comboselect?filter=def_tour_categories:tourcategoriesID:tourcategoryname') !!}",
		{  selected_value : '{{ $row["tourcategoriesID"] }}' });
		
		$("#inclusions").jCombo("{!! url('tourbound/comboselect?filter=def_inclusions:inclusionID:inclusion') !!}",
		{  selected_value : '{{ $row["inclusions"] }}' });
		
		$("#similartours").jCombo("{!! url('tourbound/comboselect?filter=tours:tourID:tour_name') !!}",
		{  selected_value : '{{ $row["similartours"] }}' });
		
		$("#payment_options").jCombo("{!! url('tourbound/comboselect?filter=def_payment_types:paymenttypeID:payment_type') !!}",
		{  selected_value : '{{ $row["payment_options"] }}' });
		
		$("#policyandterms").jCombo("{!! url('tourbound/comboselect?filter=termsandconditions:tandcID:title') !!}",
		{  selected_value : '{{ $row["policyandterms"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
