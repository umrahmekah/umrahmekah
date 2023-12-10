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
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Capacity', (isset($fields['total_capacity']['language'])? $fields['total_capacity']['language'] : array())) }}</strong></td>
						<td>{{ $row->total_capacity}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Featured', (isset($fields['featured']['language'])? $fields['featured']['language'] : array())) }}</strong></td>
						<td>{{ $row->featured}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Category', (isset($fields['tourcategoriesID']['language'])? $fields['tourcategoriesID']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::formatLookUp($row->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Tour Name', (isset($fields['tourID']['language'])? $fields['tourID']['language'] : array())) }}</strong></td>
						<td><a href="tours/show/{{$row->tourID}}">{{ SiteHelpers::formatLookUp($row->tourID,'tourID','1:tours:tourID:tour_name') }} </a> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Tour Code', (isset($fields['tour_code']['language'])? $fields['tour_code']['language'] : array())) }}</strong></td>
						<td>{{ $row->tour_code}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Definite', (isset($fields['definite_departure']['language'])? $fields['definite_departure']['language'] : array())) }}</strong></td>
						<td>{{ $row->definite_departure}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Start', (isset($fields['start']['language'])? $fields['start']['language'] : array())) }}</strong></td>
						<td>{{ date('d-M-Y',strtotime($row->start)) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('End', (isset($fields['end']['language'])? $fields['end']['language'] : array())) }}</strong></td>
						<td>{{ date('d-M-Y',strtotime($row->end)) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Guide', (isset($fields['guideID']['language'])? $fields['guideID']['language'] : array())) }}</strong></td>
						<td><a href="guide/show/{{$row->guideID}}">{{ SiteHelpers::formatLookUp($row->guideID,'guideID','1:guides:guideID:name') }} </a> </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Currency', (isset($fields['currencyID']['language'])? $fields['currencyID']['language'] : array())) }}</strong></td>
						<td>{{ SiteHelpers::formatLookUp($row->currencyID,'currencyID','1:def_currency:currencyID:symbol|currency_name') }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Single Room Cost/ Traveller', (isset($fields['cost_single']['language'])? $fields['cost_single']['language'] : array())) }}</strong></td>
						<td>{{ $row->cost_single}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Double Room Cost/ Traveller', (isset($fields['cost_double']['language'])? $fields['cost_double']['language'] : array())) }}</strong></td>
						<td>{{ $row->cost_double}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Triple Room Cost / Traveller', (isset($fields['cost_triple']['language'])? $fields['cost_triple']['language'] : array())) }}</strong></td>
						<td>{{ $row->cost_triple}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Child Cost', (isset($fields['cost_child']['language'])? $fields['cost_child']['language'] : array())) }}</strong></td>
						<td>{{ $row->cost_child}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Remarks', (isset($fields['remarks']['language'])? $fields['remarks']['language'] : array())) }}</strong></td>
						<td>{{ $row->remarks}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Color', (isset($fields['color']['language'])? $fields['color']['language'] : array())) }}</strong></td>
						<td>{{ $row->color}} </td>
						
					</tr>
						
					<tr>
						<td width='30%' class='label-view text-right'></td>
						<td> <a href="javascript:history.go(-1)" class="btn btn-primary"> Back To Grid <a> </td>
						
					</tr>					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	