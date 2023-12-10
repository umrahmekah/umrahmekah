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
						
					<tr>
						<td width='30%' class='label-view text-right'></td>
						<td> <a href="javascript:history.go(-1)" class="btn btn-primary"> Back To Grid <a> </td>
						
					</tr>					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	