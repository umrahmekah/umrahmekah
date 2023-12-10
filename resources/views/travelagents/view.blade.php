@extends('layouts.app')

@section('content')
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
         <li><a href="{{ url('travelagents?return='.$return) }}"><i class="fa fa-th"></i> {{ $pageTitle }} </a></li>
        <li  class="active"> View </li>
      </ol>
    </section>

<div class="content"> 
  		
<div class="box box-primary">
	<div class="box-header with-border">
				<div class="box-header-tools pull-left" >
			   		<a href="{{ url('travelagents?return='.$return) }}" class="tips btn btn-sm btn-info btn-circle" title="{{ Lang::get('core.btn_back') }}"><i class="fa  fa-arrow-left"></i></a>
					@if($access['is_add'] ==1)
			   		<a href="{{ url('travelagents/update/'.$id.'?return='.$return) }}" class="tips btn btn-sm btn-success btn-circle" title="{{ Lang::get('core.btn_edit') }}"><i class="fa  fa-pencil"></i></a>
					@endif 
							
				</div>	

				<div class="box-header-tools pull-right " >
					<a href="{{ ($prevnext['prev'] != '' ? url('travelagents/show/'.$prevnext['prev'].'?return='.$return ) : '#') }}" class="tips btn btn-sm btn-primary btn-circle"><i class="fa fa-arrow-left"></i>  </a>	
					<a href="{{ ($prevnext['next'] != '' ? url('travelagents/show/'.$prevnext['next'].'?return='.$return ) : '#') }}" class="tips btn btn-sm btn-primary btn-circle"> <i class="fa fa-arrow-right"></i>  </a>
					@if(Session::get('gid') ==1)
						<a href="{{ URL::to('sximo/module/config/'.$pageModule) }}" class="tips btn btn-sm btn-success btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa  fa-ellipsis-v"></i></a>
					@endif 			
				</div> 
			</div>
			<div class="box-body" > 

		  <!-- Nav tabs -->
		  <ul class="nav nav-tabs" role="tablist">
		  	<li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><b>{{ $pageTitle }} : </b>  {{ Lang::get('core.detail') }} </a></li>
			{{-- @foreach($subgrid as $sub)
				<li role="presentation"><a href="#{{ str_replace(" ","_",$sub['title']) }}" aria-controls="profile" role="tab" data-toggle="tab"><b>{{ $pageTitle }}</b>  : {{ $sub['title'] }}</a></li>
			@endforeach --}}
			<li role="presentation"><a href="#Agent" aria-controls="profile" role="tab" data-toggle="tab"><b>{{ $pageTitle }} : </b>  {{ Lang::get('core.sales_performance') }} </a></li>
		  </ul>

			 <!-- Tab panes -->
			  <div class="tab-content m-t">
			  	<div role="tabpanel" class="tab-pane active" id="home">
			  		{{-- full name(legalname), email, mobile phone, address1, address2(website), postcode(phone), city(agency_licence_code), state, bank name, account number(ibancode), commission rate, affiliatelink, agency name --}}
					<table class="table table-striped table-bordered" >
						<tbody>	
					
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.fullname') }}</strong></td>
						<td>{{ $row->legalname}} </td>
						
					</tr>
				
					{{-- <tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Agency Code', (isset($fields['agency_code']['language'])? $fields['agency_code']['language'] : array())) }}</strong></td>
						<td>{{ $row->agency_code}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Agency Name', (isset($fields['agency_name']['language'])? $fields['agency_name']['language'] : array())) }}</strong></td>
						<td>{{ $row->agency_name}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Agency Licence Code', (isset($fields['agency_licence_code']['language'])? $fields['agency_licence_code']['language'] : array())) }}</strong></td>
						<td>{{ $row->agency_licence_code}} </td>
						
					</tr> --}}
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Email', (isset($fields['email']['language'])? $fields['email']['language'] : array())) }}</strong></td>
						<td><a href="mailto:{{$row->email}}">{{ $row->email}} </a> </td>
						
					</tr>
				
					{{-- <tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Personincontact', (isset($fields['personincontact']['language'])? $fields['personincontact']['language'] : array())) }}</strong></td>
						<td>{{ $row->personincontact}} </td>
						
					</tr> --}}
				
					{{-- <tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Website', (isset($fields['website']['language'])? $fields['website']['language'] : array())) }}</strong></td>
						<td>{{ $row->website}} </td>
						
					</tr> --}}
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Mobilephone', (isset($fields['mobilephone']['language'])? $fields['mobilephone']['language'] : array())) }}</strong></td>
						<td>{{ $row->mobilephone}} </td>
						
					</tr>
				
					{{-- <tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Phone', (isset($fields['phone']['language'])? $fields['phone']['language'] : array())) }}</strong></td>
						<td>{{ $row->phone}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Fax', (isset($fields['fax']['language'])? $fields['fax']['language'] : array())) }}</strong></td>
						<td>{{ $row->fax}} </td>
						
					</tr> --}}
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.address') }}</strong></td>
						<td>{{ $row->address}} </td>
						
					</tr>

					<tr style="height: 28px;">
						<td width='30%' class='label-view text-right'><strong></strong></td>
						<td>{{ $row->website}} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.postcode') }}</strong></td>
						<td>{{ $row->phone}} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.city1') }}</strong></td>
						<td>{{ $row->agency_licence_code}} </td>
						
					</tr>

					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.city') }}</strong></td>
						<td>{{ SiteHelpers::formatLookUp($row->cityID,'cityID','1:def_city:cityID:city_name') }} </td>
						
					</tr>

					{{-- <tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Country', (isset($fields['countryID']['language'])? $fields['countryID']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::formatLookUp($row->countryID,'countryID','1:def_country:countryID:country_name') }} </td>
						
					</tr> --}}

					{{-- <tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Agent Logo', (isset($fields['agent_logo']['language'])? $fields['agent_logo']['language'] : array())) }}</strong></td>
						<td>{!! SiteHelpers::formatRows($row->agent_logo,$fields['agent_logo'],$row ) !!} </td>
						
					</tr> --}}
				
					{{-- <tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) }}</strong></td>
						<td>{!! GeneralStatus::Status($row->status) !!} </td>
						
					</tr> --}}
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.bankname') }}</strong></td>
						<td>{{ $row->bankname}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.banknumber') }}</strong></td>
						<td>{{ $row->ibancode}} </td>
						
					</tr>
				
					{{-- <tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Holder Name', (isset($fields['holder_name']['language'])? $fields['holder_name']['language'] : array())) }}</strong></td>
						<td>{{ $row->holder_name}} </td>
						
					</tr> --}}
				
					{{-- <tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Vatno', (isset($fields['vatno']['language'])? $fields['vatno']['language'] : array())) }}</strong></td>
						<td>{{ $row->vatno}} </td>
						
					</tr> --}}
				
					@if($row->commissionrate)
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.commisionrate') }}</strong></td>
						<td>{{ $row->commissionrate}} </td>
						
					</tr>
					@endif
					@if($row->affiliatelink)
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ Lang::get('core.affiliatelink') }}</strong></td>
						<td>{{  CNF_DOMAIN }}/package?affiliate={{ $row->affiliatelink }}</td>
						
					</tr>
					@endif
				
							
						</tbody>	
					</table>  
				</div>
				
			  	{{-- @foreach($subgrid as $sub)
			  		<div role="tabpanel" class="tab-pane" id="{{ str_replace(" ","_",$sub['title']) }}"></div>
			  	@endforeach	--}}

			  	<div role="tabpanel" class="tab-pane" id="Agent">
		  			<div class="box-body "> 	
   
				 	{{-- {!! (isset($search_map) ? $search_map : '') !!} --}}
					
					{!! Form::open(array('url'=>'travelagents/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
					<div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
				    <table class="table table-bordered table-hover " id="{{ $pageModule }}Table">
				        <thead>
							<tr>
								<th class="number"> No </th>
								<th>Date</th>
								<th>Booking #</th>
								<th>Package</th>
								<th>Total Sales</th>
								<th>Payment Status</th>
								<th>Commission</th>
								
							  </tr>
				        </thead>

				        <tbody>        						
				            @foreach ($bookings as $booking)
				                <tr>
									<td width="30"> {{ ++$i }} </td>
									<td>{{ $booking->created_at }}</td>
									<td>{{ $booking->bookingno }}</td>
									<td>{{ $booking->package }}</td>
									<td>{{ $booking->total_sales }}</td>
									<td>{{ $booking->payment_status }}</td>
									<td>{{ $booking->commission }}</td>
				                </tr>
								
				            @endforeach
				              
				        </tbody>
				      
				    </table>
					<input type="hidden" name="md" value="" />
					</div>
					{!! Form::close() !!}
					</div>
			  	</div>
			
			</div>

		</div>	
  	</div>
</div>
	  


<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#MmbTable').attr('action','{{ url("travelagents/multisearch")}}');
		$('#MmbTable').submit();
	});

	// $('input[type="checkbox"],input[type="radio"]').iCheck({
	// 	checkboxClass: 'icheckbox_square-red',
	// 	radioClass: 'iradio_square-red',
	// });
	// $('#{{ $pageModule }}Table .checkall').on('ifChecked',function(){
	// 	$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
	// });
	// $('#{{ $pageModule }}Table .checkall').on('ifUnchecked',function(){
	// 	$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('uncheck');
	// });	
    
	// $('.copy').click(function() {
	// 	var total = $('input[class="ids"]:checkbox:checked').length;
	// 	if(confirm('{{ Lang::get('core.rusureyouwanttocopythis') }}'))
	// 	{
	// 			$('#MmbTable').attr('action','{{ url("travelagents/copy")}}');
	// 			$('#MmbTable').submit();
	// 	}
	// })

});
</script>
<style>
.table th , th { text-align: none !important;  }
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>

<script>
  $(function () {
    $('#{{ $pageModule }}Table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
      "autoWidth": true
    });
  });
</script>
@stop