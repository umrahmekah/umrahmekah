@extends('layouts.app')

@section('content')

    <section class="content-header">
      <h1> {{ Lang::get('core.travelagents') }}</h1>
    </section>

  <div class="content"> 
      <div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-header-tools pull-left" >
			<a href="{{ url($pageModule.'?return='.$return) }}" class="tips"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left fa-2x"></i></a> 
		</div>
	</div>
	<div class="box-body"> 	
		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>	
		 {!! Form::open(array('url'=>'travelagents/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
				{!! Form::hidden('travelagentID', $row['travelagentID']) !!}					
									  <div class="form-group  " >
										<label for="Agency Name" class=" control-label col-md-4 text-left"> {{ Lang::get('core.agencyname') }} <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='agency_name' id='agency_name' value='{{ $row['agency_name'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Email" class=" control-label col-md-4 text-left"> {{ Lang::get('core.email') }} <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='email' id='email' value='{{ $row['email'] }}' 
						required     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Website" class=" control-label col-md-4 text-left"> {{ Lang::get('core.website') }} </label>
										<div class="col-md-6">
										  <input  type='text' name='website' placeholder="www.yourwebsite.com" id='website' value='{{ $row['website'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Agent Logo" class=" control-label col-md-4 text-left"> {{ Lang::get('core.agentlogo') }} </label>
										<div class="col-md-6">
										  			  <div class="btn btn-primary btn-file"><i class="fa fa-camera fa-lg"></i>  {{ Lang::get('core.profilepicture') }} 

                                            <input  type='file' name='agent_logo' id='agent_logo' @if($row['agent_logo'] =='') class='required' @endif style='width:150px !important;'  />
                                            </div>
					 	<div >
                         @if(file_exists('./uploads/images/'.CNF_OWNER.'/'.$row['agent_logo']) && $row['agent_logo'] !='')
                            <span class="pull-left removeMultiFiles "  url="/uploads/images/<?php echo CNF_OWNER;?>/{{$row['agent_logo']}}">
							<i class="fa fa-trash-o fa-2x text-red" 
                               data-toggle="confirmation" 
                               data-title="{{Lang::get('core.rusure')}}" 
                               data-content="{{ Lang::get('core.youwanttodeletethis') }}" >
                            </i></span>    
                        {!! SiteHelpers::showUploadedFile($row['agent_logo'],'/uploads/images/'.CNF_OWNER.'/') !!}
                        @endif
						</div>					
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get('core.country') }} </label>
										<div class="col-md-3">
										  <select name='countryID' rows='5' id='countryID' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="City" class=" control-label col-md-4 text-left"> {{ Lang::get('core.city') }} </label>
										<div class="col-md-3">
										  <select name='cityID' rows='5' id='cityID' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Address" class=" control-label col-md-4 text-left"> {{ Lang::get('core.address') }} </label>
										<div class="col-md-6">
										  <textarea name='address' rows='3' id='address' class='form-control '  
				           >{{ $row['address'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Phone" class=" control-label col-md-4 text-left"> {{ Lang::get('core.phone') }} </label>
										<div class="col-md-3">
										  <input  type='text' name='phone' id='phone' value='{{ $row['phone'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Fax" class=" control-label col-md-4 text-left"> {{ Lang::get('core.fax') }} </label>
										<div class="col-md-3">
										  <input  type='text' name='fax' id='fax' value='{{ $row['fax'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Status" class=" control-label col-md-4 text-left"> {{ Lang::get('core.status') }} </label>
										<div class="col-md-6">
										  
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='0'  @if($row['status'] == '0') checked="checked" @endif > {{ Lang::get('core.fr_minactive') }} </label>
					<label class='radio radio-inline'>
					<input type='radio' name='status' value ='1'  @if($row['status'] == '1') checked="checked" @endif > {{ Lang::get('core.fr_mactive') }} </label> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div>
			</div>
			
			

		
			<div style="clear:both"></div>	
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" > {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" > {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick="location.href='{{ URL::to('travelagents?return='.$return) }}' " class="btn btn-danger btn-sm ">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
		 
   <script type="text/javascript">
	$(document).ready(function() { 
    $('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    container: 'body'
    });	

		$("#countryID").jCombo("{!! url('travelagents/comboselect?filter=def_country:countryID:country_name') !!}",
		{  selected_value : '{{ $row["countryID"] }}' });
		 
		$("#cityID").jCombo("{!! url('travelagents/comboselect?filter=def_city:cityID:city_name') !!}&parent=countryID:",
		{  parent: '#countryID', selected_value : '{{ $row["cityID"] }}' });
		 
		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("travelagents/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});
		
	});
	</script>		 
@stop