
@if($setting['form-method'] =='native')
<div class="box box-primary">
	<div class="box-header with-border">
			<div class="box-header-tools pull-right " >
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-default" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</div>
	</div>

	<div class="box-body"> 
@endif	
			{!! Form::open(array('url'=>'booktour/save/'.SiteHelpers::encryptID($row['booktourID']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'booktourFormAjax')) !!}
			<div class="col-md-12">
						<fieldset><legend>{{Lang::get('core.booktour')}}</legend>
				{!! Form::hidden('booktourID', $row['booktourID']) !!}
				{!! Form::hidden('entry_by', Auth::User()->id) !!}
{!! Form::hidden('bookingID', app('request')->input('bookingID') ) !!}					
									  <div class="form-group  " >
										<label for="Tour Category" class=" control-label col-md-4 text-left"> {{Lang::get('core.tourcategory')}} </label>
										<div class="col-md-6">
										  <select name='tourcategoriesID' rows='5' id='tourcategoriesID' class='select2 '   >
										  	<option> Select Category </option>
										  	@foreach($tour_categories as $tour_category)
										  	<option value="{{ $tour_category->tourcategoriesID }}" @if($tour_category->tourcategoriesID == $row["tourcategoriesID"]) selected @endif >{{$tour_category->tourcategoryname}}</option>
										  	@endforeach
										  </select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Name" class=" control-label col-md-4 text-left"> {{Lang::get('core.tourname')}} </label>
										<div class="col-md-6">
										  <select name='tourID' rows='5' id='tourID' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Tour Date" class=" control-label col-md-4 text-left"> {{Lang::get('core.tourdate')}} <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='tourdateID' rows='5' id='tourdateID' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Status" class=" control-label col-md-4 text-left"> {{ Lang::get('core.status') }} <span class="asterix"> * </span></label>
										<div class="col-md-8">
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='2' required @if($row['status'] == '2') checked="checked" @endif > {{ Lang::get('core.fr_pending') }} </label>   										  
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='1' required @if($row['status'] == '1') checked="checked" @endif > {{ Lang::get('core.confirmed') }} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='0' required @if($row['status'] == '0') checked="checked" @endif > {{ Lang::get('core.cancelled') }} </label> 
										 </div> 
									  </div> </fieldset>
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

	
</div>	
			 
<script type="text/javascript">
$(document).ready(function() { 
	
		
		// $("#tourID").jCombo("{!! url('booktour/comboselect?filter=tours:tourID:tour_name&limit=WHERE:status:=:1') !!}&parent=tourcategoriesID:",
		// {  parent: '#tourcategoriesID', selected_value : '{{ $row["tourID"] }}' });
		
		// $("#tourdateID").jCombo("{!! url('booktour/comboselect?filter=tour_date:tourdateID:start|end&limit=WHERE:status:=:1') !!}&parent=tourID:",
		// {  parent: '#tourID', selected_value : '{{ $row["tourdateID"] }}' });
		 
	
	$('.editor').summernote();
	$('.tips').tooltip();	
	$(".select2").select2({ width:"100%" , dropdownParent: $('#mmb-modal-content')});	
		$('.date').datetimepicker({format: 'yyyy-mm-dd', autoclose:true , minView:2 , startView:2 , todayBtn:true }); 
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
	});			
		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("booktour/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});
				
	var form = $('#booktourFormAjax'); 
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

	$('#tourcategoriesID').change(function() {
	    var tourcategoriesID = $(this).val();
	    console.log($(this).val());
	    axios.post('/booktour/tours', { _token:"{{csrf_token()}}", tourcategoriesID:tourcategoriesID })
	    .then(response => {
	    	var tours = response.data;
	    	var options = '<option>Select Tour</option>';
	    	for (var i = 0; i < tours.length; i++) {
	    		var option = '<option value="'+String(tours[i].tourID)+'">'+tours[i].tour_name+'</option>';
	    		options += option;
	    	}
	    	document.getElementById('tourID').innerHTML = options;
	    }).catch(e => {
	    	console.log(e);
	    });
	});

	$('#tourID').change(function() {
	    var tourID = $(this).val();
	    console.log($(this).val());
	    axios.post('/booktour/dates', { _token:"{{csrf_token()}}", tourID:tourID })
	    .then(response => {
	    	var dates = response.data;
	    	var options = '<option>Select Date</option>';
	    	for (var i = 0; i < dates.length; i++) {
	    		var start = dates[i].start.split("-");
	    		var end = dates[i].end.split("-");
	    		var option = '<option value="'+String(dates[i].tourdateID)+'">'+String(start[2])+'/'+String(start[1])+'/'+String(start[0])+' - '+String(end[2])+'/'+String(end[1])+'/'+String(end[0])+'</option>';
	    		options += option;
	    	}
	    	document.getElementById('tourdateID').innerHTML = options;
	    }).catch(e => {
	    	console.log(e);
	    });
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
        setTimeout(location.reload.bind(location), 3000);

	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}			 

</script>		 