@extends('layouts.app')

@section('content')
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="{{ url('{class}?return='.$return) }}"><i class="fa fa-th"></i> {{ $pageTitle }} </a></li>
        <li  class="active"> View </li>
      </ol>
    </section>

<div class="content"> 
  		
<div class="box box-primary">
	<div class="box-header with-border">
				<div class="box-header-tools pull-left" >
			   		<a href="{{ url('{class}?return='.$return) }}" class="tips btn btn-sm btn-info btn-circle" title="{{ Lang::get('core.btn_back') }}"><i class="fa  fa-arrow-left"></i></a>
					@if($access['is_add'] ==1)
			   		<a href="{{ url('{class}/update/'.$id.'?return='.$return) }}" class="tips btn btn-sm btn-success btn-circle" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil"></i></a>
					@endif 
							
				</div>	

				<div class="box-header-tools pull-right " >
					<a href="{{ ($prevnext['prev'] != '' ? url('{class}/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips btn btn-sm btn-primary btn-circle"><i class="fa fa-arrow-left"></i>  </a>	
					<a href="{{ ($prevnext['next'] != '' ? url('{class}/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips btn btn-sm btn-primary btn-circle"> <i class="fa fa-arrow-right"></i>  </a>
					@if(Session::get('gid') ==1)
						<a href="{{ URL::to('mmb/module/config/'.$pageModule) }}" class="tips btn btn-sm btn-success btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa  fa-ellipsis-v"></i></a>
					@endif 			
				</div> 
			</div>
			<div class="box-body" > 

		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
		  	<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><b>{{ $pageTitle }} : </b>  View Detail </a></li>
			@foreach($subgrid as $sub)
				<li role="presentation"><a href="#{{ str_replace(" ","_",$sub['title']) }}" aria-controls="profile" role="tab" data-toggle="tab"><b>{{ $pageTitle }}</b>  : {{ $sub['title'] }}</a></li>
			@endforeach
		  </ul>

			 <!-- Tab panes -->
			  <div class="tab-content m-t">
			  	<div role="tabpanel" class="tab-pane active" id="home">

					<table class="table table-striped table-bordered" >
						<tbody>	
					{form_view}
							
						</tbody>	
					</table>  
				</div>
				
			  	@foreach($subgrid as $sub)
			  		<div role="tabpanel" class="tab-pane" id="{{ str_replace(" ","_",$sub['title']) }}"></div>
			  	@endforeach					 
			
			</div>

		</div>	
  	</div>
</div>


<script type="text/javascript">
	$(function(){
		<?php for($i=0 ; $i<count($subgrid); $i++)  :?>
			$('#{{ str_replace(" ","_",$subgrid[$i]['title']) }}').load('{{ url("{class}/lookup/".implode("-",$subgrid["$i"])."-".$id)}}')
		<?php endfor;?>
	})

</script>
	  
@stop