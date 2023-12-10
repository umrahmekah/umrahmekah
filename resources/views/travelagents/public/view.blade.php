<div class="m-t" style="padding-top:25px;">	
    <div class="row m-b-lg animated fadeInDown delayp1 text-center">
        <h3> {{ $pageTitle }} <small> {{ $pageNote }} </small></h3>
        <hr />       
    </div>
</div>
<div class="m-t">
	<div class="table-responsive" > 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
			
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Legalname', (isset($fields['legalname']['language'])? $fields['legalname']['language'] : array())) }}</strong></td>
						<td>{{ $row->legalname}} </td>
						
					</tr>
				
					<tr>
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
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Email', (isset($fields['email']['language'])? $fields['email']['language'] : array())) }}</strong></td>
						<td><a href="mailto:{{$row->email}}">{{ $row->email}} </a> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Personincontact', (isset($fields['personincontact']['language'])? $fields['personincontact']['language'] : array())) }}</strong></td>
						<td>{{ $row->personincontact}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Website', (isset($fields['website']['language'])? $fields['website']['language'] : array())) }}</strong></td>
						<td>{{ $row->website}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Mobilephone', (isset($fields['mobilephone']['language'])? $fields['mobilephone']['language'] : array())) }}</strong></td>
						<td>{{ $row->mobilephone}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Phone', (isset($fields['phone']['language'])? $fields['phone']['language'] : array())) }}</strong></td>
						<td>{{ $row->phone}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Fax', (isset($fields['fax']['language'])? $fields['fax']['language'] : array())) }}</strong></td>
						<td>{{ $row->fax}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Address', (isset($fields['address']['language'])? $fields['address']['language'] : array())) }}</strong></td>
						<td>{{ $row->address}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Country', (isset($fields['countryID']['language'])? $fields['countryID']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::formatLookUp($row->countryID,'countryID','1:def_country:countryID:country_name') }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('City', (isset($fields['cityID']['language'])? $fields['cityID']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::formatLookUp($row->cityID,'cityID','1:def_city:cityID:city_name') }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Agent Logo', (isset($fields['agent_logo']['language'])? $fields['agent_logo']['language'] : array())) }}</strong></td>
						<td>{!! SiteHelpers::formatRows($row->agent_logo,$fields['agent_logo'],$row ) !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) }}</strong></td>
						<td>{{ GeneralStatus::Status($row->status) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Bankname', (isset($fields['bankname']['language'])? $fields['bankname']['language'] : array())) }}</strong></td>
						<td>{{ $row->bankname}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Ibancode', (isset($fields['ibancode']['language'])? $fields['ibancode']['language'] : array())) }}</strong></td>
						<td>{{ $row->ibancode}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Holder Name', (isset($fields['holder_name']['language'])? $fields['holder_name']['language'] : array())) }}</strong></td>
						<td>{{ $row->holder_name}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Vatno', (isset($fields['vatno']['language'])? $fields['vatno']['language'] : array())) }}</strong></td>
						<td>{{ $row->vatno}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Commissionrate', (isset($fields['commissionrate']['language'])? $fields['commissionrate']['language'] : array())) }}</strong></td>
						<td>{{ $row->commissionrate}} </td>
						
					</tr>
						
					<tr>
						<td width='30%' class='label-view text-right'></td>
						<td> <a href="javascript:history.go(-1)" class="btn btn-primary"> Back To Grid <a> </td>
						
					</tr>					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	