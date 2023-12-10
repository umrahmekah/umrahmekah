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
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Title', (isset($fields['title']['language'])? $fields['title']['language'] : array())) }}</strong></td>
						<td>{{ $row->title}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Terms & Conditions', (isset($fields['tandc']['language'])? $fields['tandc']['language'] : array())) }}</strong></td>
						<td>{{ $row->tandc}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Created At', (isset($fields['created_at']['language'])? $fields['created_at']['language'] : array())) }}</strong></td>
						<td>{{ date('d-M-Y h:i',strtotime($row->created_at)) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Updated At', (isset($fields['updated_at']['language'])? $fields['updated_at']['language'] : array())) }}</strong></td>
						<td>{{ date('d-M-Y h:i',strtotime($row->updated_at)) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'><strong>{{ SiteHelpers::activeLang('Status', (isset($fields['status']['language'])? $fields['status']['language'] : array())) }}</strong></td>
						<td>{{ GeneralStatus::Status($row->status) }} </td>
						
					</tr>
						
					<tr>
						<td width='30%' class='label-view text-right'></td>
						<td> <a href="javascript:history.go(-1)" class="btn btn-primary"> Back To Grid <a> </td>
						
					</tr>					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	