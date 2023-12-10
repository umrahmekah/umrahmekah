@extends('layouts.blue-ocean.default')        

@section('content')

<!-- DETAIL WRAPPER -->

    <div class="container mt-5 mb-5">
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-12">

                    <h1 align="center">
                        @if($invoice->invoicePaymentMethod->payment_status == 'completed')
                            {{ Lang::get('core.thankyou') }}
                        @elseif($invoice->invoicePaymentMethod->payment_status == 'awaiting')
                            {{ Lang::get('core.awaitingpayment') }}
                        @elseif($invoice->invoicePaymentMethod->payment_status == 'failed') 
                            {{ Lang::get('core.failed') }}
                        @endif
                    </h1>
                    <h3 align="center">
                        @if($invoice->invoicePaymentMethod->payment_status == 'failed')
                            {{ Lang::get('core.failedtransaction') }}
                        @else
                            @if($invoice->pay_status == 'Paid')
                                {{ Lang::get('core.managenote') }}
                            @else
                                {{ $invoice->invoicePaymentMethod->payment_status_message }}
                            @endif
                        @endif
                    </h3>
                    <div>
                        <p align="center">
                            {{ Lang::get('core.supportnote1') }}<b>{{$owner->email}}</b>{{ Lang::get('core.supportnote2') }}<b>{{$owner->telephone}}</b>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection