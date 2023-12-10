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
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Tour Name', (isset($fields['tour_name']['language'])? $fields['tour_name']['language'] : array())) }}</strong></td>
						<td><a href="tours/show/{{$row->tourID}}">{{ $row->tour_name}} </a> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Tour Category', (isset($fields['tourcategoriesID']['language'])? $fields['tourcategoriesID']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::formatLookUp($row->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Tour Description', (isset($fields['tour_description']['language'])? $fields['tour_description']['language'] : array())) }}</strong></td>
						<td>{{ $row->tour_description}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Total Days', (isset($fields['total_days']['language'])? $fields['total_days']['language'] : array())) }}</strong></td>
						<td>{{ $row->total_days}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Total Nights', (isset($fields['total_nights']['language'])? $fields['total_nights']['language'] : array())) }}</strong></td>
						<td>{{ $row->total_nights}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Featured', (isset($fields['featured']['language'])? $fields['featured']['language'] : array())) }}</strong></td>
						<td>{{ $row->featured}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Time', (isset($fields['time']['language'])? $fields['time']['language'] : array())) }}</strong></td>
						<td>{{ $row->time}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Similar Tours', (isset($fields['similartours']['language'])? $fields['similartours']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::formatLookUp($row->similartours,'similartours','1:tours:tourID:tour_name') }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Multicountry', (isset($fields['multicountry']['language'])? $fields['multicountry']['language'] : array())) }}</strong></td>
						<td>{{ $row->multicountry}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Departs', (isset($fields['departs']['language'])? $fields['departs']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::Departs($row->departs) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('CountryID', (isset($fields['countryID']['language'])? $fields['countryID']['language'] : array())) }}</strong></td>
						<td>{{ $row->countryID}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) }}</strong></td>
						<td>{{ GeneralStatus::Status($row->status) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Term & Conditions', (isset($fields['policyandterms']['language'])? $fields['policyandterms']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::formatLookUp($row->policyandterms,'policyandterms','1:termsandconditions:tandcID:title') }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Tourimage', (isset($fields['tourimage']['language'])? $fields['tourimage']['language'] : array())) }}</strong></td>
						<td>{!! SiteHelpers::formatRows($row->tourimage,$fields['tourimage'],$row ) !!} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Gallery', (isset($fields['gallery']['language'])? $fields['gallery']['language'] : array())) }}</strong></td>
						<td>{!! SiteHelpers::formatRows($row->gallery,$fields['gallery'],$row ) !!} </td>
						
					</tr>
						
					<tr>
						<td width='30%' class='label-view text-right'></td>
						<td> <a href="javascript:history.go(-1)" class="btn btn-primary"> Back To Grid <a> </td>
						
					</tr>					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	