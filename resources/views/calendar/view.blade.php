@extends('layouts.app')

@section('content')
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="#"> Home</a></li>
         <li><a href="{{ url('calendar?return='.$return) }}"> {{ $pageTitle }} </a></li>
        <li  class="active"> View </li>
      </ol>
    </section>

	<div class="content">

		<div class="box box-primary">
			<div class="box-header-tools pull-left">
				<a href="{{ url('travellers?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
				@if($access['is_add'] ==1)
					<a href="{{ url('calendar/update/'.$id.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil fa-2x"></i> </a>
				@endif
			</div>
			<div class="box-header-tools pull-right ">
				<a href="{{ ($prevnext['prev'] != '' ? url('calendar/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.previous')}}"><i class="fa fa-arrow-left fa-2x"></i>  </a>
				<a href="{{ ($prevnext['next'] != '' ? url('calendar/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.next')}}"> <i class="fa fa-arrow-right fa-2x"></i> </a>
				@if(Session::get('gid') == 1) @endif
			</div>
			<div class="box-body" >

				<table class="table table-striped table-bordered" >
					<tbody>

						<tr>
							<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Title', (isset($fields['title']['language'])? $fields['title']['language'] : array())) }}</strong></td>
							<td>{{ $row->title}} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Description', (isset($fields['description']['language'])? $fields['description']['language'] : array())) }}</strong></td>
							<td>{{ $row->description}} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Start', (isset($fields['start']['language'])? $fields['start']['language'] : array())) }}</strong></td>
							<td>{{ $row->start}} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('End', (isset($fields['end']['language'])? $fields['end']['language'] : array())) }}</strong></td>
							<td>{{ $row->end}} </td>

						</tr>

					</tbody>
				</table>



			</div>
		</div>
	</div>
	  
@stop