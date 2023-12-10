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
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}</td>
						<td>{{ $row->id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Entry By', (isset($fields['entry_by']['language'])? $fields['entry_by']['language'] : array())) }}</td>
						<td>{{ $row->entry_by}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Owner Id', (isset($fields['owner_id']['language'])? $fields['owner_id']['language'] : array())) }}</td>
						<td>{{ $row->owner_id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Transaction Id', (isset($fields['transaction_id']['language'])? $fields['transaction_id']['language'] : array())) }}</td>
						<td>{{ $row->transaction_id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Amount Paid', (isset($fields['amount_paid']['language'])? $fields['amount_paid']['language'] : array())) }}</td>
						<td>{{ $row->amount_paid}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Credit Request', (isset($fields['credit_request']['language'])? $fields['credit_request']['language'] : array())) }}</td>
						<td>{{ $row->credit_request}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Transaction Date', (isset($fields['transaction_date']['language'])? $fields['transaction_date']['language'] : array())) }}</td>
						<td>{{ $row->transaction_date}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Created At', (isset($fields['created_at']['language'])? $fields['created_at']['language'] : array())) }}</td>
						<td>{{ $row->created_at}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Payment Gateway Id', (isset($fields['payment_gateway_id']['language'])? $fields['payment_gateway_id']['language'] : array())) }}</td>
						<td>{{ $row->payment_gateway_id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Credit', (isset($fields['credit']['language'])? $fields['credit']['language'] : array())) }}</td>
						<td>{{ $row->credit}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Currency', (isset($fields['currency']['language'])? $fields['currency']['language'] : array())) }}</td>
						<td>{{ $row->currency}} </td>
						
					</tr>
						
					<tr>
						<td width='30%' class='label-view text-right'></td>
						<td> <a href="javascript:history.go(-1)" class="btn btn-primary"> Back To Grid <a> </td>
						
					</tr>					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	