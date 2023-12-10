@extends('layouts.app')

@section('content')

    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
         <li><a href="{{ url('flightdates?return='.$return) }}"> {{ $pageTitle }} </a></li>
        <li  class="active"> Update </li>
      </ol>
    </section>

  <div class="content"> 

<div class="box box-primary">
	<div class="box-header with-border">

		<div class="box-header-tools pull-left" >
			<a href="{{ url($pageModule.'?return='.$return) }}" class="tips btn btn-sm btn-success btn-circle"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left"></i></a> 
		</div>
		<div class="box-header-tools pull-right" >
			@if(Session::get('gid') ==1)
				<a href="{{ URL::to('mmb/module/config/'.$pageModule) }}" class="tips btn btn-sm btn-success btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa  fa-ellipsis-v"></i></a>
			@endif 			
		</div> 

	</div>
	<div class="box-body"> 	

		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>	

		 {!! Form::open(array('url'=>'flightdates/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> {{ Lang::get('core.flightdates') }}</legend>	
							<input type="hidden" name="id" value="{{$row['id']}}">

									  <div class="form-group  " >
										<label for="Title" class=" control-label col-md-4 text-left"> {{ Lang::get('core.title') }} </label>
										<div class="col-md-6">
										  <input  type='text' name='title' id='title' value='{{ $row['title'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Start Date" class=" control-label col-md-4 text-left"> {{ Lang::get('core.startdate') }} </label>
										<div class="col-md-6">
											<div class="input-group m-b" style="width:180px !important; margin-bottom: 0px !important;" id="dpd1">
												{!! Form::text('start_date', $row['start_date'],array('class'=>'form-control date','autocomplete'=>'off')) !!}
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										  {{-- <input  type='text' name='start_date' id='start_date' value='{{ $row['start_date'] }}' 
						     class='form-control ' />  --}}
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="End Date" class=" control-label col-md-4 text-left"> {{ Lang::get('core.enddate') }} </label>
										<div class="col-md-6">
											<div class="input-group m-b" style="width:180px !important; margin-bottom: 0px !important;" id="dpd1">
												{!! Form::text('end_date', $row['end_date'],array('class'=>'form-control date','autocomplete'=>'off')) !!}
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>
										  {{-- <input  type='text' name='end_date' id='end_date' value='{{ $row['end_date'] }}' 
						     class='form-control ' />  --}}
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Flight Company" class=" control-label col-md-4 text-left"> {{Lang::get('core.airlines')}} </label>
										<div class="col-md-6">
										  <input  type='text' name='flight_company' id='flight_company' value='{{ $row['flight_company'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Email" class=" control-label col-md-4 text-left"> {{ Lang::get('core.email') }} </label>
										<div class="col-md-6">
										  <input  type='text' name='email' id='email' value='{{ $row['email'] }}' 
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
					<button type="submit" name="apply" class="btn btn-success " > {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary " > {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick="location.href='{{ URL::to('flightdates?return='.$return) }}' " class="btn btn-danger  ">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
		 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		 

		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("flightdates/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});

	function test(val) {
		console.log(val);
	}
	</script>		 
@stop

