
@if($setting['form-method'] =='native')
<div class="box box-primary">
	<div class="box-header with-border">
			<div class="box-header-tools pull-right " >
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-default" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</div>
	</div>

	<div class="box-body"> 
@endif	
			{!! Form::open(array('url'=>'calendartourdates/save/'.SiteHelpers::encryptID($row['tourdateID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'calendartourdatesFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> {{ Lang::get('core.tourdetails') }}</legend>
				{!! Form::hidden('tourdateID', $row['tourdateID']) !!}					
									  <div class="form-group  " >
										<label for="Tour Category" class=" control-label col-md-4 text-left"> {{ Lang::get('core.tourcategory') }} <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='tourcategoriesID' rows='5' id='tourcategoriesID' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Name" class=" control-label col-md-4 text-left"> {{ Lang::get('core.tourname') }} <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='tourID' rows='5' id='tourID' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Code" class=" control-label col-md-4 text-left"> {{ Lang::get('core.tourcode') }} <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='tour_code' id='tour_code' value='{{ $row['tour_code'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Start Date" class=" control-label col-md-4 text-left"> {{ Lang::get('core.startdate') }} <span class="asterix"> * </span></label>
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
										<label for="End Date" class=" control-label col-md-4 text-left"> {{ Lang::get('core.enddate') }} <span class="asterix"> * </span></label>
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
										<label for="Guide" class=" control-label col-md-4 text-left"> {{ Lang::get('core.guide') }} </label>
										<div class="col-md-6">
										  <select name='guideID' rows='5' id='guideID' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Featured" class=" control-label col-md-4 text-left"> {{ Lang::get('core.featured') }} </label>
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
										<label for="Definite Departure" class=" control-label col-md-4 text-left"> {{ Lang::get('core.definitedeparture') }} </label>
										<div class="col-md-6">
										  <?php $definite_departure = explode(",",$row['definite_departure']); ?>
					 <label class='checked checkbox-inline'>   
					<input type='checkbox' name='definite_departure[]' value ='2'   class='' 
					@if(in_array('2',$definite_departure))checked @endif 
					 /> {{ Lang::get('core.definitedeparture') }} </label>  
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Group Size" class=" control-label col-md-4 text-left"> {{ Lang::get('core.groupsize') }} <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='total_capacity' id='total_capacity' value='{{ $row['total_capacity'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Currency" class=" control-label col-md-4 text-left"> {{ Lang::get('core.currency') }} </label>
										<div class="col-md-6">
										  <select name='currencyID' rows='5' id='currencyID' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for Single room" class=" control-label col-md-4 text-left"> {{ Lang::get('core.tourcostsingle') }} </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_single' id='cost_single' value='{{ $row['cost_single'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for Double Room " class=" control-label col-md-4 text-left"> {{ Lang::get('core.tourcostdouble') }}  </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_double' id='cost_double' value='{{ $row['cost_double'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for Triple Room" class=" control-label col-md-4 text-left"> {{ Lang::get('core.tourcosttriple') }} </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_triple' id='cost_triple' value='{{ $row['cost_triple'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for Quad Room" class=" control-label col-md-4 text-left"> {{ Lang::Get('core.tourcostquad') }} </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_quad' id='cost_quad' value='{{ $row['cost_quad'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Cost for a Child" class=" control-label col-md-4 text-left"> {{ Lang::get('core.tourcostchild') }} </label>
										<div class="col-md-6">
										  <input  type='text' name='cost_child' id='cost_child' value='{{ $row['cost_child'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Remarks" class=" control-label col-md-4 text-left"> {{ Lang::get('core.remarks') }} </label>
										<div class="col-md-6">
										  <textarea name='remarks' rows='5' id='remarks' class='form-control '  
				           >{{ $row['remarks'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Status" class=" control-label col-md-4 text-left"> {{ Lang::get('core.status') }} <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='0' required @if($row['status'] == '0') checked="checked" @endif > {{ Lang::get('core.passive') }} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='1' required @if($row['status'] == '1') checked="checked" @endif > {{ Lang::get('core.active') }} </label> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Color" class=" control-label col-md-4 text-left"> {{ Lang::get('core.color') }} </label>
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
					<button type="submit" class="btn btn-primary btn-sm "><i class="fa fa-play-circle"></i>  {{ Lang::get('core.sb_save') }} </button>
					<button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-danger btn-sm"><i class="fa fa-remove "></i>  {{ Lang::get('core.sb_cancel') }} </button>
				</div>			
			</div> 		 
			{!! Form::close() !!}


@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

	
</div>	
			 
<script type="text/javascript">
$(document).ready(function() { 
	
		$("#tourcategoriesID").jCombo("{!! url('calendartourdates/comboselect?filter=def_tour_categories:tourcategoriesID:tourcategoryname') !!}",
		{  selected_value : '{{ $row["tourcategoriesID"] }}', initial_text: '{{ Lang::get('core.pleaseselect') }}', });
		
		$("#tourID").jCombo("{!! url('calendartourdates/comboselect?filter=tours:tourID:tour_name') !!}&parent=tourcategoriesID:",
		{  parent: '#tourcategoriesID', selected_value : '{{ $row["tourID"] }}', initial_text: '{{ Lang::get('core.pleaseselect') }}' });
		
		$("#guideID").jCombo("{!! url('calendartourdates/comboselect?filter=guides:guideID:name') !!}",
		{  selected_value : '{{ $row["guideID"] }}', initial_text: '{{ Lang::get('core.pleaseselect') }}' });
		
		$("#currencyID").jCombo("{!! url('calendartourdates/comboselect?filter=def_currency:currencyID:currency_sym|symbol') !!}",
		{  selected_value : '{{ $row["currencyID"] }}', initial_text: '{{ Lang::get('core.pleaseselect') }}' });
		 
	
	$('.editor').summernote();
	$('.tips').tooltip();	
	$(".select2").select2({ width:"98%" , dropdownParent: $('#mmb-modal-content')});	
	$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true})
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
	});			
		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("calendartourdates/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});
				
	var form = $('#calendartourdatesFormAjax'); 
	form.parsley();
	form.submit(function(){
		
		if(form.parsley('isValid') == true){			
			var options = { 
				dataType:      'json', 
				beforeSubmit :  showRequest,
				success:       showResponse  
			}  
			$(this).ajaxSubmit(options); 
			return false;
						
		} else {
			return false;
		}		
	
	});

});

function showRequest()
{
	$('.ajaxLoading').show();		
}  
function showResponse(data)  {		
	
	if(data.status == 'success')
	{
		ajaxViewClose('#{{ $pageModule }}');
		ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
		notyMessage(data.message);	
		$('#mmb-modal').modal('hide');	
	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}			 

</script>		 