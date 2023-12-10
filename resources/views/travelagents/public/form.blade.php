

		 {!! Form::open(array('url'=>'travelagents/savepublic', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

	@if(Session::has('messagetext'))
	  
		   {!! Session::get('messagetext') !!}
	   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		


<div class="col-md-12">
						<fieldset><legend> Agent Details</legend>
				{!! Form::hidden('travelagentID', $row['travelagentID']) !!}					
									  <div class="form-group  " >
										<label for="Agency Name" class=" control-label col-md-4 text-left"> Agency Name <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='agency_name' id='agency_name' value='{{ $row['agency_name'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Legalname" class=" control-label col-md-4 text-left"> Legalname </label>
										<div class="col-md-6">
										  <input  type='text' name='legalname' id='legalname' value='{{ $row['legalname'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Agency Licence Code" class=" control-label col-md-4 text-left"> Agency Licence Code </label>
										<div class="col-md-6">
										  <input  type='text' name='agency_licence_code' id='agency_licence_code' value='{{ $row['agency_licence_code'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Agency Code" class=" control-label col-md-4 text-left"> Agency Code </label>
										<div class="col-md-6">
										  <input  type='text' name='agency_code' id='agency_code' value='{{ $row['agency_code'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Email" class=" control-label col-md-4 text-left"> Email <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='email' id='email' value='{{ $row['email'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Website" class=" control-label col-md-4 text-left"> Website </label>
										<div class="col-md-6">
										  <input  type='text' name='website' id='website' value='{{ $row['website'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Agent Logo" class=" control-label col-md-4 text-left"> Agent Logo </label>
										<div class="col-md-6">
										  <input  type='file' name='agent_logo' id='agent_logo' @if($row['agent_logo'] =='') class='required' @endif style='width:150px !important;'  />
					 	<div >
						{!! SiteHelpers::showUploadedFile($row['agent_logo'],'/uploads/images/') !!}
						
						</div>					
					 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Country" class=" control-label col-md-4 text-left"> Country </label>
										<div class="col-md-6">
										  <select name='countryID' rows='5' id='countryID' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="City" class=" control-label col-md-4 text-left"> City </label>
										<div class="col-md-6">
										  <select name='cityID' rows='5' id='cityID' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Address" class=" control-label col-md-4 text-left"> Address </label>
										<div class="col-md-6">
										  <textarea name='address' rows='5' id='address' class='form-control '  
				           >{{ $row['address'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Personincontact" class=" control-label col-md-4 text-left"> Personincontact </label>
										<div class="col-md-6">
										  <input  type='text' name='personincontact' id='personincontact' value='{{ $row['personincontact'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Mobilephone" class=" control-label col-md-4 text-left"> Mobilephone </label>
										<div class="col-md-6">
										  <input  type='text' name='mobilephone' id='mobilephone' value='{{ $row['mobilephone'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Phone" class=" control-label col-md-4 text-left"> Phone </label>
										<div class="col-md-6">
										  <input  type='text' name='phone' id='phone' value='{{ $row['phone'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Fax" class=" control-label col-md-4 text-left"> Fax </label>
										<div class="col-md-6">
										  <input  type='text' name='fax' id='fax' value='{{ $row['fax'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Bankname" class=" control-label col-md-4 text-left"> Bankname </label>
										<div class="col-md-6">
										  <input  type='text' name='bankname' id='bankname' value='{{ $row['bankname'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Ibancode" class=" control-label col-md-4 text-left"> Ibancode </label>
										<div class="col-md-6">
										  <input  type='text' name='ibancode' id='ibancode' value='{{ $row['ibancode'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Holder Name" class=" control-label col-md-4 text-left"> Holder Name </label>
										<div class="col-md-6">
										  <input  type='text' name='holder_name' id='holder_name' value='{{ $row['holder_name'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Vatno" class=" control-label col-md-4 text-left"> Vatno </label>
										<div class="col-md-6">
										  <input  type='text' name='vatno' id='vatno' value='{{ $row['vatno'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Commissionrate" class=" control-label col-md-4 text-left"> Commissionrate </label>
										<div class="col-md-6">
										  <input  type='text' name='commissionrate' id='commissionrate' value='{{ $row['commissionrate'] }}' 
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
		
		
		$("#countryID").jCombo("{!! url('travelagents/comboselect?filter=def_country:countryID:country_name') !!}",
		{  selected_value : '{{ $row["countryID"] }}' });
		
		$("#cityID").jCombo("{!! url('travelagents/comboselect?filter=def_city:cityID:city_name') !!}&parent=countryID:",
		{  parent: '#countryID', selected_value : '{{ $row["cityID"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
