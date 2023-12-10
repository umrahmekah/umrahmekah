
@if($setting['form-method'] =='native')
<div class="box box-primary">
	<div class="box-header with-border">
			<div class="box-header-tools pull-right " >
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-default" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</div>
	</div>

	<div class="box-body"> 
@endif	
			{!! Form::open(array('url'=>'bookroom/save/'.SiteHelpers::encryptID($row['roomID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'bookroomFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend> {{Lang::get('core.bookroom')}}</legend>
				{!! Form::hidden('roomID', $row['roomID']) !!}
                                                    {!! Form::hidden('bookingID', app('request')->input('bookingID')) !!}					
									  <div class="form-group  " >
										<label for="Room Type" class=" control-label col-md-3 text-left"> {{Lang::get('core.roomtype')}}<span class="asterix"> * </span></label>
										<div class="col-md-9">
											<select class="form-control" name="roomtype" required>
												<option value="" disabled selected>Please Select Room Type</option>
												<option value="1" @if($row['roomtype'] == '1') selected @endif>{{Lang::get('core.single')}}</option>
												<option value="2" @if($row['roomtype'] == '2') selected @endif>{{Lang::get('core.double')}}</option>
												<option value="3" @if($row['roomtype'] == '3') selected @endif>{{Lang::get('core.triple')}}</option>
												<option value="4" @if($row['roomtype'] == '4') selected @endif>{{Lang::get('core.quad')}}</option>
												<option value="5" @if($row['roomtype'] == '5') selected @endif>{{Lang::get('core.quint')}}</option>
												<option value="6" @if($row['roomtype'] == '6') selected @endif>{{Lang::get('core.sext')}}</option>
											</select>
										</div> 
									  </div> 					
									  <div class="form-group  " >
										<label for="Travellers" class=" control-label col-md-3 text-left"> {{Lang::get('core.travellers')}} <span class="asterix"> * </span></label>
										<div class="col-md-9">
										  <select name='travellers[]' multiple rows='5' id='travellers' class='select2 ' required  ></select> 
										 </div> 
									  </div> 
                            		  {{-- <div class="form-group  " >
										<label for="Remarks" class=" control-label col-md-3 text-left"> {{Lang::get('core.remarks')}} </label>
										<div class="col-md-9">
										  <textarea name='remarks' rows='5' id='remarks' class='form-control '  
				           >{{ $row['remarks'] }}</textarea> 
										 </div> 
									  </div> --}}

									  <div class="form-group">
									  	<label class="control-label col-md-3 text-left"> {{Lang::get('core.travel_child')}} </label>
									  	<input type="checkbox" name="child_check" class="form-control" id="childCheck" @if($child_boolean) checked @endif>
									  </div>

									  <div id="child_form" @if($child_boolean) style="display: block;" @else style="display: none;" @endif>
									  	<div class="form-group  " >
									  		<label for="Room Type" class=" control-label col-md-3 text-left"> {{Lang::get('core.roomtype')}}</label>
											<div class="col-md-9">
												<select class="form-control" name="child_room">
													<option value="" disabled selected>Please Select Room Type</option>
													<option value="7" @if(isset($row['childsroom']) && $row['childsroom']->roomtype == '7') selected @endif>{{Lang::get('core.tourcostchild')}}</option>
													<option value="8" @if(isset($row['childsroom']) && $row['childsroom']->roomtype == '8') selected @endif>{{Lang::get('core.tourcostchildwithoutbed')}}</option>
												</select>
											</div> 
									  	</div>
									  	<div class="form-group  " >
											<label for="Child" class=" control-label col-md-3 text-left"> {{Lang::get('core.child')}}</label>
											<div class="col-md-9">
												<select name='children[]' multiple rows='5' id='child' class='select2 '>
													@foreach($children as $child)
														<option value="{{$child->travellerID}}" @if(in_array($child->travellerID, $child_list)) selected @endif>@if($child->NRIC){{$child->NRIC}}@else NIRC not set @endif - {{$child->nameandsurname}} {{$child->last_name}}</option>
													@endforeach
												</select>
											</div> 
										</div> 
									  </div>
									  
									  <div class="form-group">
									  	<label class="control-label col-md-3 text-left"> {{Lang::get('core.travel_infant')}} </label>
									  	<input type="checkbox" class="form-control" name="infant_check" id="infantCheck" @if($infant_boolean) checked @endif>
									  </div>

									  <div id="infant_form" @if($infant_boolean) style="display: block;" @else style="display: none;" @endif>
									  	<div class="form-group  " >
											<label for="Child" class=" control-label col-md-3 text-left"> {{Lang::get('core.infant')}}</label>
											<div class="col-md-9">
												<select name='infants[]' multiple rows='5' id='infants' class='select2 '>
													@foreach($infants as $infant)
														<option value="{{$infant->travellerID}}" @if(in_array($infant->travellerID, $infant_list)) selected @endif>@if($infant->NRIC){{$infant->NRIC}}@else NIRC not set @endif - {{$infant->nameandsurname}} {{$infant->last_name}}</option>
													@endforeach
												</select>
											</div> 
										</div> 
									  </div>

									  <div class="form-group  " >
										<label for="Status" class=" control-label col-md-3 text-left"> {{ Lang::get('core.status') }} <span class="asterix"> * </span></label>
										<div class="col-md-9">
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='2' required @if($row['status'] == '2') checked="checked" @endif > {{ Lang::get('core.fr_pending') }} </label>   										  
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='1' required @if($row['status'] == '1') checked="checked" @endif > {{ Lang::get('core.confirmed') }} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='0' required @if($row['status'] == '0') checked="checked" @endif > {{ Lang::get('core.cancelled') }} </label> 
										 </div> 
									  </div>  </fieldset>
			</div>
			
												
								
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<label class="col-sm-4 text-right">&nbsp;</label>
				<div class="col-sm-8">	
					<button type="submit" class="btn btn-success btn-sm ">  {{ Lang::get('core.sb_save') }} </button>
					<button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-danger btn-sm">  {{ Lang::get('core.sb_cancel') }} </button>
				</div>			
			</div> 		 
			{!! Form::close() !!}


@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

			 
<script type="text/javascript">
$(document).ready(function() { 
	
    $("#travellers").jCombo("{!! url('bookroom/comboselect?filter=travellers:travellerID:nameandsurname&limit=WHERE:status:=:1') !!}",
		{  selected_value : '{{ $row["travellers"] }}' });
	
	$('.editor').summernote();
	$('.tips').tooltip();	
	$(".select2").select2({ width:"100%" , maximumSelectionLength:3 ,dropdownParent: $('#mmb-modal-content')});	
    $('.date').datetimepicker({format: 'yyyy-mm-dd', autoclose:true , minView:2 , startView:2 , todayBtn:true }); 
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
	});			
		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("bookroom/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});
				
	var form = $('#bookroomFormAjax'); 
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

	$('#childCheck').on('ifChecked', function () {
		document.getElementById('child_form').style.display = "block";
	})

	$('#childCheck').on('ifUnchecked', function () {
		document.getElementById('child_form').style.display = "none";
	})

	$('#infantCheck').on('ifChecked', function () {
		document.getElementById('infant_form').style.display = "block";
	})

	$('#infantCheck').on('ifUnchecked', function () {
		document.getElementById('infant_form').style.display = "none";
	})


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
        setTimeout(location.reload.bind(location), 3000);
	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}			 

</script>		 