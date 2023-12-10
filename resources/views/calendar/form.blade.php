@extends('layouts.app')

@section('content')

    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
         <li><a href="{{ url('calendar?return='.$return) }}"> {{ $pageTitle }} </a></li>
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

		{!! Form::open(array('url'=>'calendar/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
		<div class="col-md-12">
			<fieldset><legend> Calendar</legend>
				{!! Form::hidden('id', $row['id']) !!}
				<div class="form-group  " >
					<label for="Title" class=" control-label col-md-4 text-left"> Title </label>
					<div class="col-md-6">
						<input  type='text' name='title' id='title' value='{{ $row['title'] }}'
								class='form-control input-sm ' />
					</div>
					<div class="col-md-2">

					</div>
				</div>
				<div class="form-group  " >
					<label for="Description" class=" control-label col-md-4 text-left"> Description </label>
					<div class="col-md-6">
						<input  type='text' name='description' id='description' value='{{ $row['description'] }}'
								class='form-control input-sm ' />
					</div>
					<div class="col-md-2">

					</div>
				</div>
				<div class="form-group  " >
					<label for="Start" class=" control-label col-md-4 text-left"> Start </label>
					<div class="col-md-6">

						<div class="input-group m-b" style="width:150px !important;">
							{!! Form::text('start', $row['start'],array('class'=>'form-control input-sm date')) !!}
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>

					</div>
					<div class="col-md-2">

					</div>
				</div>
				<div class="form-group  " >
					<label for="End" class=" control-label col-md-4 text-left"> End </label>
					<div class="col-md-6">

						<div class="input-group m-b" style="width:150px !important;">
							{!! Form::text('end', $row['end'],array('class'=>'form-control input-sm date')) !!}
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
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
				<button type="button" onclick="location.href='{{ URL::to('calendar?return='.$return) }}' " class="btn btn-danger  ">  {{ Lang::get('core.sb_cancel') }} </button>
			</div>

		</div>

		{!! Form::close() !!}
	</div>
</div>		 
</div>	
		 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		 

		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("calendar/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
@stop