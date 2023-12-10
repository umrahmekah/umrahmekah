

		 {!! Form::open(array('url'=>'credittransactions/savepublic', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

	@if(Session::has('messagetext'))
	  
		   {!! Session::get('messagetext') !!}
	   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		


<div class="col-md-12">
						<fieldset><legend> Credit Transactions</legend>
				{!! Form::hidden('id', $row['id']) !!}{!! Form::hidden('owner_id', $row['owner_id']) !!}{!! Form::hidden('transaction_id', $row['transaction_id']) !!}					
									  <div class="form-group  " >
										<label for="Amount Paid" class=" control-label col-md-4 text-left"> Amount Paid </label>
										<div class="col-md-6">
										  <input  type='text' name='amount_paid' id='amount_paid' value='{{ $row['amount_paid'] }}' 
						     class='form-control input-sm ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Credit Request" class=" control-label col-md-4 text-left"> Credit Request </label>
										<div class="col-md-6">
										  <input  type='text' name='credit_request' id='credit_request' value='{{ $row['credit_request'] }}' 
						     class='form-control input-sm ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Transaction Date" class=" control-label col-md-4 text-left"> Transaction Date </label>
										<div class="col-md-6">
										  
				<div class="input-group m-b" style="width:150px !important;">
					{!! Form::text('transaction_date', $row['transaction_date'],array('class'=>'form-control input-sm date')) !!}
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> {!! Form::hidden('created_at', $row['created_at']) !!}					
									  <div class="form-group  " >
										<label for="Payment Gateway Id" class=" control-label col-md-4 text-left"> Payment Gateway Id </label>
										<div class="col-md-6">
										  <select name='payment_gateway_id' rows='5' id='payment_gateway_id' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Credit" class=" control-label col-md-4 text-left"> Credit </label>
										<div class="col-md-6">
										  <input  type='text' name='credit' id='credit' value='{{ $row['credit'] }}' 
						     class='form-control input-sm ' /> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Currency" class=" control-label col-md-4 text-left"> Currency </label>
										<div class="col-md-6">
										  <select name='currency' rows='5' id='currency' class='select2 '   ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> </fieldset>
			</div>
			
			

			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
				  </div>	  
			
		</div> 
		 
		 {!! Form::close() !!}
		 
   <script type="text/javascript">
	$(document).ready(function() { 
		
		
		$("#payment_gateway_id").jCombo("{!! url('credittransactions/comboselect?filter=payment_gateways:id:gateway_name') !!}",
		{  selected_value : '{{ $row["payment_gateway_id"] }}' });
		
		$("#currency").jCombo("{!! url('credittransactions/comboselect?filter=def_currency:currencyID:currency_name') !!}",
		{  selected_value : '{{ $row["currency"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
