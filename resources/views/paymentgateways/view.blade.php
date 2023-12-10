@extends('layouts.app')

@section('content')
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="#"> Home</a></li>
         <li><a href="{{ url('paymentgateways?return='.$return) }}"> {{ $pageTitle }} </a></li>
        <li  class="active"> View </li>
      </ol>
    </section>

  <div class="content"> 

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-header-tools pull-left">
			<a href="{{ url('travellers?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
			@if($access['is_add'] ==1)
				<a href="{{ url('paymentgateways/update/'.$id.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil fa-2x"></i> </a>
				@endif
		</div>
		<div class="box-header-tools pull-right ">
			<a href="{{ ($prevnext['prev'] != '' ? url('paymentgateways/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.previous')}}"><i class="fa fa-arrow-left fa-2x"></i>  </a>
			<a href="{{ ($prevnext['next'] != '' ? url('paymentgateways/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.next')}}"> <i class="fa fa-arrow-right fa-2x"></i> </a>
			@if(Session::get('gid') ==1)
				{{--<a href="{{ URL::to('mmb/module/config/'.$pageModule) }}" class="tips btn btn-sm btn-success btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa  fa-ellipsis-v"></i></a>--}}
			@endif
		</div>


	</div>
	<div class="box-body" > 	

		<table class="table table-striped table-bordered" >
			<tbody>
			
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Gateway Name', (isset($fields['gateway_name']['language'])? $fields['gateway_name']['language'] : array())) }}</td>
						<td>{{ $row->gateway_name}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Gateway Api Key', (isset($fields['gateway_api_key']['language'])? $fields['gateway_api_key']['language'] : array())) }}</td>
						<td>{{ $row->gateway_api_key}} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	
</div>
	  
@stop