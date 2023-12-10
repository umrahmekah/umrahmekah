@extends('layouts.app')

@section('content')
    <section class="content-header">
		<h1> {{ $pageTitle }} </h1>
    </section>

  <div class="content"> 

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-header-tools pull-left" >
			<a href="{{ url($pageModule.'?return='.$return) }}" class="tips"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left fa-2x"></i></a>
			@if($access['is_add'] ==1)
				<a href="{{ url('credittotals/update/'.$id.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil fa-2x"></i> </a>
			@endif
			<a href="{{ url('credittransactions/update/'.$id.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-money fa-2x"></i> </a>
		</div>
		<div class="box-header-tools pull-right" >
			<a href="{{ ($prevnext['prev'] != '' ? url('credittotals/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.previous')}}"><i class="fa fa-arrow-left fa-2x"></i>  </a>
			<a href="{{ ($prevnext['next'] != '' ? url('credittotals/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.next')}}"> <i class="fa fa-arrow-right fa-2x"></i> </a>
			@if(Session::get('gid') == 1) @endif
		</div>


	</div>
	<div class="box-body" > 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<tr hidden>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}</td>
						<td>{{ $row->id}} </td>
						
					</tr>
				
					<tr hidden>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Owner Id', (isset($fields['owner_id']['language'])? $fields['owner_id']['language'] : array())) }}</td>
						<td>{{ $row->owner_id}} </td>
						
					</tr>
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Owner Name', (isset($fields['owner_id']['language'])? $fields['owner_id']['language'] : array())) }}</td>
						<td>{{ $row->name}} </td>

					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Total Credit', (isset($fields['total_credit']['language'])? $fields['total_credit']['language'] : array())) }}</td>
						<td>{{ $row->total_credit}} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	
</div>
	  
@stop