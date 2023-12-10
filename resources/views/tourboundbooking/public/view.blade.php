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
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Booking#', (isset($fields['bookingsID']['language'])? $fields['bookingsID']['language'] : array())) }}</strong></td>
						<td>{{ $row->bookingsID}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Traveller', (isset($fields['travellerID']['language'])? $fields['travellerID']['language'] : array())) }}</strong></td>
						<td><a href="travellers/show/{{$row->travellerID}}">{{ SiteHelpers::formatLookUp($row->travellerID,'travellerID','1:travellers:travellerID:nameandsurname') }} </a> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Tour', (isset($fields['tour']['language'])? $fields['tour']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::Tour($row->tour) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Hotel', (isset($fields['hotel']['language'])? $fields['hotel']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::Hotel($row->hotel) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Flight', (isset($fields['flight']['language'])? $fields['flight']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::Flight($row->flight) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Car', (isset($fields['car']['language'])? $fields['car']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::Car($row->car) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Extra Service', (isset($fields['extraservices']['language'])? $fields['extraservices']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::Extraservices($row->extraservices) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Created At', (isset($fields['created_at']['language'])? $fields['created_at']['language'] : array())) }}</strong></td>
						<td>{{ date('d-M-Y H:i',strtotime($row->created_at)) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Updated At', (isset($fields['updated_at']['language'])? $fields['updated_at']['language'] : array())) }}</strong></td>
						<td>{{ date('d-M-Y H:i',strtotime($row->updated_at)) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Entry By', (isset($fields['entry_by']['language'])? $fields['entry_by']['language'] : array())) }}</strong></td>
						<td>{{ $row->entry_by}} </td>
						
					</tr>
						
					<tr>
						<td width='30%' class='label-view text-right'></td>
						<td> <a href="javascript:history.go(-1)" class="btn btn-primary"> Back To Grid <a> </td>
						
					</tr>					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	