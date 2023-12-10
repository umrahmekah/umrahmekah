@extends('layouts.app')
@section('content')
<section class="content-header">
	<h1>{{ Lang::get('core.sitesettings') }}</h1>
</section>
<div class="content">
	@if(Session::has('message'))
		{{ Session::get('message') }}   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		
	@include('mmb.config.tab')	
	<div class="col-md-9">
		<div class="box box-primary">
			<div class="box-body"> 
				{!! Form::open(array('url'=>'core/config/payment-integration/', 'class'=>'form-horizontal')) !!}		
				<div class="box-header with-border">
					<h3 class="box-title">{{ Lang::get('core.payment_integration') }}</h3>
				</div>
				<div class="form-group" style="padding-top: 10px;">
					<label for="ipt" class=" control-label col-md-4">
						{{ Lang::get('core.choose_payment_type') }}
					</label>
					<div class="col-md-6">
						<select class="form-control" name="payment_gateway_id" id="payment-gateway-type">
							<option value="0"{{ CNF_PAYMENT_GATEWAY_ID == 0 ? ' selected' : '' }}>Manual</option>
							@foreach($payments as $payment)
								<option value="{{ $payment->id }}"{{ CNF_PAYMENT_GATEWAY_ID == $payment->id ? ' selected' : '' }}>{{ $payment->name }}</option>
							@endforeach
						</select>
					</div> 
				</div>  
				<div class="payment_elements{{ CNF_PAYMENT_GATEWAY_ID !== 1 ? ' hidden' : '' }}" id="payment-type-id-1">
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.billplz_api_key') }}</label>
						<div class="col-md-6">
							<input name="billplz_api_key" type="text" id="billplz_api_key" class="form-control input-sm input-payment-type input-payment-type-id-1" value="{{ CNF_BILLPLZAPIKEY }}" />  
						</div> 
					</div>        
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.billplz_signature_key') }}</label>
						<div class="col-md-6">
							<input name="billplz_signature_key" type="text" id="billplz_signature_key" class="form-control input-sm input-payment-type input-payment-type-id-1" value="{{ CNF_BILLPLZSIGNATUREKEY }}" />  
						</div> 
					</div>  
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.billplz_collection_id') }}</label>
						<div class="col-md-6">
							<input name="billplz_collection_id" type="text" id="billplz_collection_id" class="form-control input-sm input-payment-type input-payment-type-id-1" value="{{ CNF_BILLPLZCOLLECTIONID }}" />  
						</div> 
					</div>
				</div>  
				<div class="payment_elements{{ CNF_PAYMENT_GATEWAY_ID !== 2 ? ' hidden' : '' }}" id="payment-type-id-2">
					<hr>
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bca_va][channel]" type="text" id="bayarind_channel_bca_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="BCA Virtual Account" readonly />  
						</div> 
					</div> 
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Merchant Name</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bca_va][merchant_name]" type="text" id="bayarind_merchant_name_bca_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bca_va->merchant_name)) ? $payment_config->bca_va->merchant_name : '' }}" />  
						</div> 
					</div>      
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel ID</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bca_va][channel_id]" type="text" id="bayarind_channel_id_bca_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bca_va->channel_id)) ? $payment_config->bca_va->channel_id : '' }}" />  
						</div> 
					</div>        
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Company Code</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bca_va][company_code]" type="text" id="bayarind_company_code_bca_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bca_va->company_code)) ? $payment_config->bca_va->company_code : '' }}" />  
						</div> 
					</div>  
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Secret Key</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bca_va][secret_key]" type="text" id="bayarind_secret_key_bca_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bca_va->secret_key)) ? $payment_config->bca_va->secret_key : '' }}" />  
						</div> 
					</div>
					<div class="form-group">
						<input name="payment_gateway_data[2][bca_va][payment_instruction]" type="hidden" id="bayarind_payment_instruction_bca_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bca_va->payment_instruction)) ? $payment_config->bca_va->payment_instruction : '' }}" />  
					</div>
					<hr>
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][permata_va][channel]" type="text" id="bayarind_channel_permata_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="Permata Virtual Account" readonly />  
						</div> 
					</div> 
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Merchant Name</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][permata_va][merchant_name]" type="text" id="bayarind_merchant_name_permata_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->permata_va->merchant_name)) ? $payment_config->permata_va->merchant_name : '' }}" />  
						</div> 
					</div>      
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel ID</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][permata_va][channel_id]" type="text" id="bayarind_channel_id_permata_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->permata_va->channel_id)) ? $payment_config->permata_va->channel_id : '' }}" />  
						</div> 
					</div>        
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">BIN</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][permata_va][bin]" type="text" id="bayarind_bin_permata_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->permata_va->bin)) ? $payment_config->permata_va->bin : '' }}" />  
						</div> 
					</div>  
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Secret Key</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][permata_va][secret_key]" type="text" id="bayarind_secret_key_permata_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->permata_va->secret_key)) ? $payment_config->permata_va->secret_key : '' }}" />  
						</div> 
					</div>
					<div class="form-group">
						<input name="payment_gateway_data[2][permata_va][payment_instruction]" type="hidden" id="bayarind_payment_instruction_permata_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->permata_va->payment_instruction)) ? $payment_config->permata_va->payment_instruction : '' }}" />  
					</div>
					<hr>
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][mandiri_va][channel]" type="text" id="bayarind_channel_mandiri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="Mandiri Virtual Account" readonly />  
						</div> 
					</div> 
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Merchant Name</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][mandiri_va][merchant_name]" type="text" id="bayarind_merchant_name_mandiri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->mandiri_va->merchant_name)) ? $payment_config->mandiri_va->merchant_name : '' }}" />  
						</div> 
					</div>      
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel ID</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][mandiri_va][channel_id]" type="text" id="bayarind_channel_id_mandiri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->mandiri_va->channel_id)) ? $payment_config->mandiri_va->channel_id : '' }}" />  
						</div> 
					</div>   
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Company Code</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][mandiri_va][company_code]" type="text" id="bayarind_company_code_mandiri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->mandiri_va->company_code)) ? $payment_config->mandiri_va->company_code : '' }}" />  
						</div> 
					</div>       
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">BIN</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][mandiri_va][bin]" type="text" id="bayarind_bin_mandiri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->mandiri_va->bin)) ? $payment_config->mandiri_va->bin : '' }}" />  
						</div> 
					</div>  
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Secret Key</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][mandiri_va][secret_key]" type="text" id="bayarind_secret_key_mandiri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->mandiri_va->secret_key)) ? $payment_config->mandiri_va->secret_key : '' }}" />  
						</div> 
					</div>
					<div class="form-group">
						<input name="payment_gateway_data[2][mandiri_va][payment_instruction]" type="hidden" id="bayarind_payment_instruction_mandiri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->mandiri_va->payment_instruction)) ? $payment_config->mandiri_va->payment_instruction : '' }}" />  
					</div>
					<hr>
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bni_va][channel]" type="text" id="bayarind_channel_bni_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="BNI Virtual Account" readonly />  
						</div> 
					</div> 
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Merchant Name</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bni_va][merchant_name]" type="text" id="bayarind_merchant_name_bni_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bni_va->merchant_name)) ? $payment_config->bni_va->merchant_name : '' }}" />  
						</div> 
					</div>      
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel ID</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bni_va][channel_id]" type="text" id="bayarind_channel_id_bni_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bni_va->channel_id)) ? $payment_config->bni_va->channel_id : '' }}" />  
						</div> 
					</div>        
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">BIN</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bni_va][bin]" type="text" id="bayarind_bin_bni_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bni_va->bin)) ? $payment_config->bni_va->bin : '' }}" />  
						</div> 
					</div>  
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Secret Key</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bni_va][secret_key]" type="text" id="bayarind_secret_key_bni_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bni_va->secret_key)) ? $payment_config->bni_va->secret_key : '' }}" />  
						</div> 
					</div>
					<div class="form-group">
						<input name="payment_gateway_data[2][bni_va][payment_instruction]" type="hidden" id="bayarind_payment_instruction_bni_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bni_va->payment_instruction)) ? $payment_config->bni_va->payment_instruction : '' }}" />  
					</div>
					<hr>
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bri_va][channel]" type="text" id="bayarind_channel_bri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="BRI Virtual Account" readonly />  
						</div> 
					</div> 
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Merchant Name</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bri_va][merchant_name]" type="text" id="bayarind_merchant_name_bri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bri_va->merchant_name)) ? $payment_config->bri_va->merchant_name : '' }}" />  
						</div> 
					</div>      
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel ID</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bri_va][channel_id]" type="text" id="bayarind_channel_id_bri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bri_va->channel_id)) ? $payment_config->bri_va->channel_id : '' }}" />  
						</div> 
					</div>        
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">BIN</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bri_va][bin]" type="text" id="bayarind_bin_bri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bri_va->bin)) ? $payment_config->bri_va->bin : '' }}" />  
						</div> 
					</div>  
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Secret Key</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bri_va][secret_key]" type="text" id="bayarind_secret_key_bri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bri_va->secret_key)) ? $payment_config->bri_va->secret_key : '' }}" />  
						</div> 
					</div>
					<div class="form-group">
						<input name="payment_gateway_data[2][bri_va][payment_instruction]" type="hidden" id="bayarind_payment_instruction_bri_va" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bri_va->payment_instruction)) ? $payment_config->bri_va->payment_instruction : '' }}" />  
					</div>
					<hr>
					<input name="payment_gateway_data[2][bca_klikpay][callback]" type="hidden" id="bayarind_callback_bca_klikpay" class="form-control input-sm input-payment-type input-payment-type-id-2" value="bookpackage/payment-complete" readonly />  
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bca_klikpay][channel]" type="text" id="bayarind_channel_bca_klikpay" class="form-control input-sm input-payment-type input-payment-type-id-2" value="BCA KlikPay" readonly />  
						</div> 
					</div> 
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Merchant Name</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bca_klikpay][merchant_name]" type="text" id="bayarind_merchant_name_bca_klikpay" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bca_klikpay->merchant_name)) ? $payment_config->bca_klikpay->merchant_name : '' }}" />  
						</div> 
					</div>      
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel ID</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bca_klikpay][channel_id]" type="text" id="bayarind_channel_id_bca_klikpay" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bca_klikpay->channel_id)) ? $payment_config->bca_klikpay->channel_id : '' }}" />  
						</div> 
					</div>        
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Secret Key</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][bca_klikpay][secret_key]" type="text" id="bayarind_secret_key_bca_klikpay" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bca_klikpay->secret_key)) ? $payment_config->bca_klikpay->secret_key : '' }}" />  
						</div> 
					</div>
					<div class="form-group">
						<input name="payment_gateway_data[2][bca_klikpay][payment_instruction]" type="hidden" id="bayarind_payment_instruction_bca_klikpay" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->bca_klikpay->payment_instruction)) ? $payment_config->bca_klikpay->payment_instruction : '' }}" />  
					</div>
					<hr>
					<input name="payment_gateway_data[2][cimb_click][callback]" type="hidden" id="bayarind_callback_cimb_click" class="form-control input-sm input-payment-type input-payment-type-id-2" value="bookpackage/payment-complete" readonly />  
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][cimb_click][channel]" type="text" id="bayarind_channel_cimb_click" class="form-control input-sm input-payment-type input-payment-type-id-2" value="CIMB Click" readonly />  
						</div> 
					</div> 
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Merchant Name</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][cimb_click][merchant_name]" type="text" id="bayarind_merchant_name_cimb_click" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->cimb_click->merchant_name)) ? $payment_config->cimb_click->merchant_name : '' }}" />  
						</div> 
					</div>      
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Channel ID</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][cimb_click][channel_id]" type="text" id="bayarind_channel_id_cimb_click" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->cimb_click->channel_id)) ? $payment_config->cimb_click->channel_id : '' }}" />  
						</div> 
					</div>        
					<div class="form-group">
						<label for="ipt" class=" control-label col-md-4">Secret Key</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][cimb_click][secret_key]" type="text" id="bayarind_secret_key_cimb_click" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->cimb_click->secret_key)) ? $payment_config->cimb_click->secret_key : '' }}" />  
						</div> 
					</div>
					<div class="form-group">
						<input name="payment_gateway_data[2][cimb_click][payment_instruction]" type="hidden" id="bayarind_payment_instruction_cimb_click" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->cimb_click->payment_instruction)) ? $payment_config->cimb_click->payment_instruction : '' }}" />  
					</div>
					<hr>
					<input name="payment_gateway_data[2][gpn][audience]" type="hidden" id="gpn-audience" class="form-control input-sm input-payment-type input-payment-type-id-2" value="bookpackage/sales" readonly />  
					<div class="form-group">
						<label for="gpn-channel" class=" control-label col-md-4">Channel</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][gpn][channel]" type="text" id="gpn-channel-" class="form-control input-sm input-payment-type input-payment-type-id-2" value="GPN (Gerbang Pembayaran Nasional)" readonly />  
						</div> 
					</div> 
					<div class="form-group">
						<label for="gpn-man" class=" control-label col-md-4">MAN</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][gpn][man]" type="text" id="gpn-man" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->gpn->man)) ? $payment_config->gpn->man : '' }}" />  
						</div> 
					</div>      
					<div class="form-group">
						<label for="gpn-secret-key" class=" control-label col-md-4">Secret Key</label>
						<div class="col-md-6">
							<input name="payment_gateway_data[2][gpn][secret_key]" type="text" id="gpn-secret-key" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->gpn->secret_key)) ? $payment_config->gpn->secret_key : '' }}" />  
						</div> 
					</div>        
					<div class="form-group">
						<input name="payment_gateway_data[2][gpn][payment_instruction]" type="hidden" id="gpn-payment-instruction" class="form-control input-sm input-payment-type input-payment-type-id-2" value="{{ (CNF_PAYMENT_GATEWAY_ID == 2 && $payment_config && !empty($payment_config->gpn->payment_instruction)) ? $payment_config->gpn->payment_instruction : '' }}" />  
					</div>
				</div>      
				<div class="form-group">
					<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
					<div class="col-md-8">
						<button class="btn btn-primary" type="submit"> {{ Lang::get('core.sb_savechanges') }}</button>
					</div> 
				</div>
				{!! Form::close() !!}
			</div>	
		</div>
		
	</div>
</div>
<div style="clear: both;"></div>
<script type="text/javascript">
	(function($) {
		'use strict';

		$(document).ready(function() {
			$('#payment-gateway-type').on('change', function() {
				$('.payment_elements').addClass('hidden');
				$('#payment-type-id-' + $(this).val()).removeClass('hidden');
				$('.input-payment-type').removeAttr('required');
				$('.input-payment-type-id-' + $(this).val()).attr('required', true);
			});
		});	

	})(jQuery);
</script>
@stop




