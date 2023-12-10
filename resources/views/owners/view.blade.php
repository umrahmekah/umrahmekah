@extends('layouts.app')

@section('content')
    <section class="content-header">
      <h1> {{ $pageTitle }}</h1>
    </section>

  <div class="content"> 

<div class="box box-primary">
	<div class="box-header with-border">
		<div class="box-header-tools pull-left" >
			<a href="{{ url('owners?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
			@if($access['is_add'] ==1)
				<a href="{{ url('owners/update/'.$id.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil fa-2x"></i> </a>
			@endif 
					
		</div>

		<div class="box-header-tools pull-right ">
			<a href="{{ ($prevnext['prev'] != '' ? url('travellers/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.previous')}}"><i class="fa fa-arrow-left fa-2x"></i>  </a>
			<a href="{{ ($prevnext['next'] != '' ? url('travellers/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips" title="{{Lang::get('core.next')}}"> <i class="fa fa-arrow-right fa-2x"></i> </a>
			@if(Session::get('gid') == 1) @endif
		</div>


	</div>
	<div class="box-body" > 	

		<table class="table table-striped table-bordered" >
			<tbody>
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Name', (isset($fields['name']['language'])? $fields['name']['language'] : array())) }}</td>
						<td>{{ $row->name}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Domain', (isset($fields['domain']['language'])? $fields['domain']['language'] : array())) }}</td>
						<td>{{ $row->domain}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Address', (isset($fields['address']['language'])? $fields['address']['language'] : array())) }}</td>
						<td>{{ $row->address}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Telephone', (isset($fields['telephone']['language'])? $fields['telephone']['language'] : array())) }}</td>
						<td>{{ $row->telephone}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Email', (isset($fields['email']['language'])? $fields['email']['language'] : array())) }}</td>
						<td>{{ $row->email}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Facebook', (isset($fields['facebook']['language'])? $fields['facebook']['language'] : array())) }}</td>
						<td>{{ $row->facebook}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Twitter', (isset($fields['twitter']['language'])? $fields['twitter']['language'] : array())) }}</td>
						<td>{{ $row->twitter}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Instagram', (isset($fields['instagram']['language'])? $fields['instagram']['language'] : array())) }}</td>
						<td>{{ $row->instagram}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Tripdavisor', (isset($fields['tripdavisor']['language'])? $fields['tripdavisor']['language'] : array())) }}</td>
						<td>{{ $row->tripdavisor}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Tagline', (isset($fields['tagline']['language'])? $fields['tagline']['language'] : array())) }}</td>
						<td>{{ $row->tagline}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Description', (isset($fields['description']['language'])? $fields['description']['language'] : array())) }}</td>
						<td>{{ $row->description}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Template Color', (isset($fields['template_color']['language'])? $fields['template_color']['language'] : array())) }}</td>
						<td>{{ $row->template_color}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Meta Keyword', (isset($fields['meta_keyword']['language'])? $fields['meta_keyword']['language'] : array())) }}</td>
						<td>{{ $row->meta_keyword}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Meta Description', (isset($fields['meta_description']['language'])? $fields['meta_description']['language'] : array())) }}</td>
						<td>{{ $row->meta_description}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Group', (isset($fields['group']['language'])? $fields['group']['language'] : array())) }}</td>
						<td>{{ $row->group}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Activation', (isset($fields['activation']['language'])? $fields['activation']['language'] : array())) }}</td>
						<td>{{ $row->activation}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Maintenance', (isset($fields['maintenance']['language'])? $fields['maintenance']['language'] : array())) }}</td>
						<td>{{ $row->maintenance}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Show Help', (isset($fields['show_help']['language'])? $fields['show_help']['language'] : array())) }}</td>
						<td>{{ $row->show_help}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Multi Language', (isset($fields['multi_language']['language'])? $fields['multi_language']['language'] : array())) }}</td>
						<td>{{ $row->multi_language}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Default Language', (isset($fields['default_language']['language'])? $fields['default_language']['language'] : array())) }}</td>
						<td>{{ $row->default_language}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Registration', (isset($fields['registration']['language'])? $fields['registration']['language'] : array())) }}</td>
						<td>{{ $row->registration}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Front', (isset($fields['front']['language'])? $fields['front']['language'] : array())) }}</td>
						<td>{{ $row->front}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Captcha', (isset($fields['captcha']['language'])? $fields['captcha']['language'] : array())) }}</td>
						<td>{{ $row->captcha}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Theme', (isset($fields['theme']['language'])? $fields['theme']['language'] : array())) }}</td>
						<td>{{ $row->theme}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Mode', (isset($fields['mode']['language'])? $fields['mode']['language'] : array())) }}</td>
						<td>{{ $row->mode}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Logo', (isset($fields['logo']['language'])? $fields['logo']['language'] : array())) }}</td>
						<td>{{ $row->logo}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Header Image', (isset($fields['header_image']['language'])? $fields['header_image']['language'] : array())) }}</td>
						<td>{{ $row->header_image}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Allow Ip', (isset($fields['allow_ip']['language'])? $fields['allow_ip']['language'] : array())) }}</td>
						<td>{{ $row->allow_ip}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Restrict Ip', (isset($fields['restrict_ip']['language'])? $fields['restrict_ip']['language'] : array())) }}</td>
						<td>{{ $row->restrict_ip}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Date', (isset($fields['date']['language'])? $fields['date']['language'] : array())) }}</td>
						<td>{{ $row->date}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Google Analytics', (isset($fields['google_analytics']['language'])? $fields['google_analytics']['language'] : array())) }}</td>
						<td>{{ $row->google_analytics}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Google Calendar', (isset($fields['google_calendar']['language'])? $fields['google_calendar']['language'] : array())) }}</td>
						<td>{{ $row->google_calendar}} </td>
						
					</tr>
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	
</div>
	  
@stop