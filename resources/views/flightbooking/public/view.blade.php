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
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}</strong></td>
						<td>{{ $row->id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Departure Date', (isset($fields['departure_date']['language'])? $fields['departure_date']['language'] : array())) }}</strong></td>
						<td>{{ $row->departure_date}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Pnr', (isset($fields['pnr']['language'])? $fields['pnr']['language'] : array())) }}</strong></td>
						<td>{{ $row->pnr}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Pax', (isset($fields['pax']['language'])? $fields['pax']['language'] : array())) }}</strong></td>
						<td>{{ $row->pax}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) }}</strong></td>
						<td>{{ $row->status}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Tourdates Id', (isset($fields['tourdates_id']['language'])? $fields['tourdates_id']['language'] : array())) }}</strong></td>
						<td>{{ $row->tourdates_id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Flight Match Id', (isset($fields['flight_match_id']['language'])? $fields['flight_match_id']['language'] : array())) }}</strong></td>
						<td>{{ $row->flight_match_id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Entry By', (isset($fields['entry_by']['language'])? $fields['entry_by']['language'] : array())) }}</strong></td>
						<td>{{ $row->entry_by}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Owner Id', (isset($fields['owner_id']['language'])? $fields['owner_id']['language'] : array())) }}</strong></td>
						<td>{{ $row->owner_id}} </td>
						
					</tr>
						
					<tr>
						<td width='30%' class='label-view text-right'></td>
						<td> <a href="javascript:history.go(-1)" class="btn btn-primary"> Back To Grid <a> </td>
						
					</tr>					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	