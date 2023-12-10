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
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Tourdate Id', (isset($fields['tourdate_id']['language'])? $fields['tourdate_id']['language'] : array())) }}</strong></td>
						<td>{{ $row->tourdate_id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Pif Number', (isset($fields['pif_number']['language'])? $fields['pif_number']['language'] : array())) }}</strong></td>
						<td>{{ $row->pif_number}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Group Name', (isset($fields['group_name']['language'])? $fields['group_name']['language'] : array())) }}</strong></td>
						<td>{{ $row->group_name}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Leader Id', (isset($fields['leader_id']['language'])? $fields['leader_id']['language'] : array())) }}</strong></td>
						<td>{{ $row->leader_id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Flight Booking Id', (isset($fields['flight_booking_id']['language'])? $fields['flight_booking_id']['language'] : array())) }}</strong></td>
						<td>{{ $row->flight_booking_id}} </td>
						
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