@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard')}}"> Home</a></li>
        <li  class="active"> {{ $pageTitle }} </li>
      </ol>
    </section>

  <div class="content"> 	

<div class="box box-primary">
	<div class="box-header with-border">		 
		<div class="box-header-tools pull-right" >
		@if($access['is_excel'] ==1)
			<a href="{{ URL::to('{class}/export/excel') }}" class="tips btn btn-sm btn-info btn-circle" title="{{ Lang::get('core.btn_download') }}">
			<i class="fa fa-arrow-down"></i></a>
			@endif
		@if(Session::get('gid') ==1)
			<a href="{{ URL::to('mmb/module/config/'.$pageModule) }}" class="btn btn-sm btn-success btn-circle tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-ellipsis-v"></i></a>
		@endif 
		</div>
	</div>
	<div class="box-body "> 	
	
	
	 {!! Form::open(array('url'=>'{class}/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
    <table class="table table-hover table-bordered  ">
        <thead>
			<tr>
				<th class="number"> No </th>			
				@foreach ($tableGrid as $t)
					@if($t['view'] =='1')
						<th>{{ $t['label'] }}</th>
					@endif
				@endforeach
			  </tr>
        </thead>

        <tbody>
						
            @foreach ($rowData as $row)
                <tr>
					<td width="30"> {{ ++$i }} </td>	
				@foreach ($tableGrid as $field)
					 @if($field['view'] =='1')
					 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
					 	@if(SiteHelpers::filterColumn($limited ))
						 <td>					 
						 	{!! SiteHelpers::formatRows($row->{$field['field']},$field ,$row ) !!}						 
						 </td>
						@endif	
					 @endif					 
				 @endforeach				 
				
                </tr>
				
            @endforeach
              
        </tbody>
      
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	@include('footer')
	</div>
</div>	
	</div>	  

@stop