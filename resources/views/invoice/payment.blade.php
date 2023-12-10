
<!-- @if($setting['form-method'] =='native')

<div class="box box-primary">
	<div class="box-header with-border">
			<div class="box-header-tools pull-right " >
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-default" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</div>
	</div>

	<div class="box-body"> 
@endif	 -->
        <?php 
        $currency = DB::table('def_currency')->select('symbol')->where('currencyID',CNF_CURRENCY)->get();
        if(empty($currency))
        $symbol = '';
        else
        $symbol = $currency[0]->symbol; 
        ?>
			@if(Auth::User()->group_id == 6)
			<form method="post" action="/invoice/pay" class="form-horizontal">
				@else
			<form method="post" action="/invoice/submitpay" class="form-horizontal" accept-charset="UTF-8" enctype="multipart/form-data">
				@endif
				{{csrf_field()}}
				<div class="col-md-12">
						<fieldset><legend> @if(Auth::User()->group_id == 6) {{Lang::get('core.online_payment')}} @else {{Lang::get('core.new_payment')}} @endif </legend>
                                                    {!! Form::hidden('invoiceID', $invoiceID) !!}			
									  <div class="form-group  " >
										<label for="Amount" class=" control-label col-md-3 text-left">{{Lang::get('core.amount')}}</label>
										<div class="col-md-9">
										  <p class="control-label" style="float: left;">{{$symbol}} {{ number_format($amount) }} </p> 
										 </div> 
									  </div>  
									  <div class="form-group  " >
										<label for="Paid" class=" control-label col-md-3 text-left">{{Lang::get('core.amountpaid')}}</label>
										<div class="col-md-9">
										  <p class="control-label" style="float: left;">{{$symbol}} {{ number_format($paid) }}</p> 
										 </div> 
									  </div> 
									  <div class="form-group  " >
										<label for="Balance Due" class=" control-label col-md-3 text-left">{{Lang::get('core.balancedue')}}</label>
										<div class="col-md-9">
										  <p class="control-label" style="float: left;">{{$symbol}} {{ number_format($balance) }}</p> 
										 </div> 
									  </div> 
									  <div class="form-group  " >
										<label for="Payment Amount" class=" control-label col-md-3 text-left">{{Lang::get('core.paymentamount')}}<span class="asterix"> * </span> </label>
										<div class="col-md-9">
										  <input type="number" name="payment_amount" class="form-control parsley-validated" required @if(!CNF_BILLPLZAPIKEY && Auth::User()->group_id == 6) disabled placeholder="Online Payment is disabled" @endif>
										 </div> 
									  </div> 
									  @if(Auth::User()->group_id != 6)
									  <div class="form-group  " >
										<label for="Payment prove" class=" control-label col-md-3 text-left">{{Lang::get('core.referencenumber')}}<span class="asterix"> * </span> </label>
										<div class="col-md-9">
										  <input type="text" name="payment_prove" class="form-control parsley-validated" required>
										 </div> 
									  </div>
									  <div class="form-group  " >
										<label for="Payment date" class=" control-label col-md-3 text-left">{{Lang::get('core.paymentdate')}}<span class="asterix"> * </span> </label>
										<div class="col-md-9">
										  <input type="date" name="payment_date" class="form-control parsley-validated" required>
										 </div> 
									  </div>
									<div class="form-group  " >
										<label for="Payment Type" class=" control-label col-md-3 text-left"> {{ Lang::get('core.paymenttype') }}<span class="asterix"> * </span></label>
										<div class="col-md-9">
										  <select name='payment_type' rows='5' id='payment_type' class='select2 ' required  >
										  <option></option> 
										  @foreach($payment_type as $type)
										  <option value="{{$type->paymenttypeID}}">{{$type->payment_type}}</option>
										  @endforeach
										  </select>
										 </div> 
										 <div class="col-md-3">
										 	
										 </div>
									  </div>
									  <!-- <label for="Received" class=" control-label col-md-3 text-left"> {{Lang::get('core.received')}}</label>
										<div class="col-md-9">
					 <label class='checked checkbox-inline' style="margin-bottom: 5px">   
					<input type='checkbox' name='received[]' value ='1'   class=''
					 /></label>  </div> -->
									  <div class="col-md-3"></div>
									  <div class="galleryUpl col-md-9">	
									 	<input  type='file' name='file'  />			
									</div> 
									  @endif
                            			  </fieldset>
			</div>
			
												
								
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<label class="col-sm-4 text-right">&nbsp;</label>
				<div class="col-sm-8">	
					@if(Auth::User()->group_id == 6)
						@if(CNF_BILLPLZAPIKEY)
						<button type="submit" class="btn btn-success btn-sm ">  Pay Now </button>
						@endif
					@else
					<button type="submit" class="btn btn-success btn-sm ">  Submit Payment </button>
					@endif
					<button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-danger btn-sm">  {{ Lang::get('core.sb_cancel') }} </button>
				</div>			
			</div> 		 
			</form>


<!-- @if($setting['form-method'] =='native')
	</div>	
</div>	
@endif -->	

			 
<script type="text/javascript">
$(document).ready(function() { 
	
	$('.editor').summernote();
	$('.tips').tooltip();	
	$(".select2").select2({ width:"100%" , maximumSelectionLength:3 ,dropdownParent: $('#mmb-modal-content')});	
    $('.date').datetimepicker({format: 'yyyy-mm-dd', autoclose:true , minView:2 , startView:2 , todayBtn:true }); 
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
	});			

});

function showRequest()
{
	$('.ajaxLoading').show();		
}  

</script>		 