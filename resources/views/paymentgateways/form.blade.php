@extends('layouts.app')

@section('content')

    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
         <li><a href="{{ url('paymentgateways?return='.$return) }}"> {{ $pageTitle }} </a></li>
        <li  class="active"> Update </li>
      </ol>
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

		 {!! Form::open(array('url'=>'paymentgateways/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
<div class="col-md-12">
						<fieldset><legend> Payment gateway</legend>
				{!! Form::hidden('id', $row['id']) !!}					
									  <div class="form-group  " >
										<label for="Gateway Name" class=" control-label col-md-4 text-left"> Gateway Name <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='gateway_name' id='gateway_name' value='{{ $row['gateway_name'] }}' 
						required     class='form-control input-sm ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Gateway Api Key" class=" control-label col-md-4 text-left"> Gateway Api Key <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <input  type='text' name='gateway_api_key' id='gateway_api_key' value='{{ $row['gateway_api_key'] }}' 
						required     class='form-control input-sm ' /> 
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
					<button type="button" onclick="location.href='{{ URL::to('paymentgateways?return='.$return) }}' " class="btn btn-danger  ">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	  
			
				  </div> 
		 
		 {!! Form::close() !!}
	</div>
</div>		 
</div>	
		 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		 

		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("paymentgateways/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop