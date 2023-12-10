@extends('layouts.app')

@section('content')

    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
         <li><a href="{{ url('flightmatching?return='.$return) }}"> {{ $pageTitle }} </a></li>
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

		 {!! Form::open(array('url'=>'flightmatching/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Flight Matching</legend>
									
									  {{-- <div class="form-group  " >
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
									  </div> 					 --}}
									  <div class="form-group  " >
										<label for="Flight Date" class=" control-label col-md-4 text-left"> Flight Date </label>
										<div class="col-md-6">
										  <input  type='text' name='flight_date' id='flight_date' value='{{ $row['flight_date'] }}' 
						     class='form-control ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div>
									  <div class="form-group  " >
										<label for="Flight Date" class=" control-label col-md-4 text-left"> Upload CSV flight </label>
										<div class="col-md-6">
										  <input type="file" name="flight_matching" id="flight_matching"> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div>
									   </fieldset>
			</div>
			
			

		
			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-success " > {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary " > {{ Lang::get('core.sb_save') }}</button>
					<button type="button" onclick="location.href='{{ URL::to('flightmatching?return='.$return) }}' " class="btn btn-danger  ">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
		 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		 

		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("flightmatching/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop