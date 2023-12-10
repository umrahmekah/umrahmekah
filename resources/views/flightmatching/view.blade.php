@extends('layouts.app')

@section('content')
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="#"> Home</a></li>
         <li><a href="{{ url('flightmatching?return='.$return) }}"> {{ $pageTitle }} </a></li>
        <li  class="active"> View </li>
      </ol>
    </section>

  <div class="content"> 

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-header-tools pull-left" >
	   		<a href="{{ url('flightmatching?return='.$return) }}" class="tips btn btn-sm btn-success btn-circle" title="{{ Lang::get('core.btn_back') }}"><i class="fa  fa-arrow-left"></i></a>
			@if($access['is_add'] ==1)
	   		<a href="{{ url('flightmatching/update/'.$id.'?return='.$return) }}" class="tips btn btn-sm btn-info btn-circle" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil"></i></a>
			@endif 
					
		</div>	

		<div class="box-header-tools pull-right" >
			<a href="{{ ($prevnext['prev'] != '' ? url('flightmatching/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips btn btn-xs btn-primary btn-circle"><i class="fa fa-arrow-left"></i>  </a>	
			<a href="{{ ($prevnext['next'] != '' ? url('flightmatching/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips btn btn-xs btn-primary btn-circle"> <i class="fa fa-arrow-right"></i>  </a>
			@if(Session::get('gid') ==1)
				<a href="{{ URL::to('mmb/module/config/'.$pageModule) }}" class="tips btn btn-sm btn-success btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa  fa-ellipsis-v"></i></a>
			@endif 			
		</div>


	</div>
	<div class="box-body" > 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}</strong></td>
						<td>{{ $row->id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Flight Number 1', (isset($fields['flight_number_1']['language'])? $fields['flight_number_1']['language'] : array())) }}</strong></td>
						<td>{{ $row->flight_number_1}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Sector 1', (isset($fields['sector_1']['language'])? $fields['sector_1']['language'] : array())) }}</strong></td>
						<td>{{ $row->sector_1}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Day 1', (isset($fields['day_1']['language'])? $fields['day_1']['language'] : array())) }}</strong></td>
						<td>{{ $row->day_1}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Dep Time 1', (isset($fields['dep_time_1']['language'])? $fields['dep_time_1']['language'] : array())) }}</strong></td>
						<td>{{ $row->dep_time_1}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Arr Time 1', (isset($fields['arr_time_1']['language'])? $fields['arr_time_1']['language'] : array())) }}</strong></td>
						<td>{{ $row->arr_time_1}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Flight Number 2', (isset($fields['flight_number_2']['language'])? $fields['flight_number_2']['language'] : array())) }}</strong></td>
						<td>{{ $row->flight_number_2}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Sector 2', (isset($fields['sector_2']['language'])? $fields['sector_2']['language'] : array())) }}</strong></td>
						<td>{{ $row->sector_2}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Day 2', (isset($fields['day_2']['language'])? $fields['day_2']['language'] : array())) }}</strong></td>
						<td>{{ $row->day_2}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Dep Time 2', (isset($fields['dep_time_2']['language'])? $fields['dep_time_2']['language'] : array())) }}</strong></td>
						<td>{{ $row->dep_time_2}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Arr Time 2', (isset($fields['arr_time_2']['language'])? $fields['arr_time_2']['language'] : array())) }}</strong></td>
						<td>{{ $row->arr_time_2}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Number Of Days', (isset($fields['number_of_days']['language'])? $fields['number_of_days']['language'] : array())) }}</strong></td>
						<td>{{ $row->number_of_days}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Flight Date', (isset($fields['flight_date']['language'])? $fields['flight_date']['language'] : array())) }}</strong></td>
						<td>{{ $row->flight_date}} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	
</div>
	  
@stop