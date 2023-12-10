
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
			<table class="table table-bordered table-striped " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th width="20"> No </th>
				<th >{{Lang::get('core.paymentdate')}}</th>	
				<th >{{Lang::get('core.referencenumber')}}</th>	
                <th >{{Lang::get('core.amount')}}</th>	
                <th >{{Lang::get('core.receipt')}}</th>	

			  </tr>
        </thead>

        <tbody>
        	@foreach($payments as $payment)
              <tr>
              	<td>{{$payment->invoicePaymentID}}</td>
              	<td>{{$payment->payment_date}}</td>
              	<td>{{$payment->payment_prove}}</td>
              	<td>{{$symbol}} {{number_format($payment->amount)}}</td>
              	<td><a href="/invoice/receiptmail?payment_id={{$payment->invoicePaymentID}}"><i class="fa fa-fw fa-2x fa-envelope text-green tips" title data-original-title="Send receipt to e-mail"></i></a></td>
              </tr>
            @endforeach
        </tbody>
      
    </table>


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