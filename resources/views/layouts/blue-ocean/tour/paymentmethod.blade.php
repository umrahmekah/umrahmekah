@extends('layouts.blue-ocean.default')        

@section('content')
<!-- DETAIL WRAPPER -->
    <div class="container mt-5 mb-5">
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <h1 align="center">{{ Lang::get('core.paymentmethods') }}</h1>
                    @if(session('payment_method_error'))
                    	<div class="alert alert-danger mt-5">
	                    	{{ session('payment_method_error') }}
	                    </div>
                   	@endif

                   	@if(!session('bookings'))
                   		<div class="alert alert-danger mt-5">{{ Lang::get('core.donthavetransaction') }}</div>
                   	@endif

                   	@if(session('bookings'))
	                    <form action="{{ route('bookpackage.paymentmethodprocess') }}" method="POST">
	                    	{{-- <input type="hidden" name="payment_details" value="{{ $payment_details }}"> --}}
		                    <div class="payment-method mt-5">
		                    	<div class="row align-items-center h-100">
		                    		@foreach($payment_methods as $key => $payment) 
		                    			@if($payment->secret_key)
		                    				<div class="col-lg-4 mb-4">
				                    			<div class="card">
							                    	<div class="card-body">
							                    		<div class="row">
							                    			<div class="col-lg-2">
							                    				<div class="form-check">
							                    					<input class="form-check-input" type="radio" name="payment_method" value="{{ $key }}" id="{{ $key }}">
							                    				</div>
							                    			</div>
							                    			<div class="col-lg-9">
							                    				<label class="form-check-label" for="{{ $key }}">
						                    						{{ $payment->channel }}
						                    					</label>
							                    			</div>
							                    		</div>
							                    	</div>
							                    </div>
				                    		</div>
		                    			@endif
		                    		@endforeach
		                    	</div>
		                    </div>
		                    <div class="payment-method-footer">
		                    	<div class="float-right">
		                    		<button class="btn btn-primary">{{ Lang::get('core.next') }}</button>
		                    	</div>
		                    </div>
	                    </form>
	               	@endif
                </div>
            </div>
        </div>
    </div>
@endsection