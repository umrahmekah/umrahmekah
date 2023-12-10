@extends('layouts.blue-ocean.default')        

@section('content')

<!-- DETAIL WRAPPER -->

    <div class="container mt-5 mb-5">
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-12">

                    <h1 align="center">
                        @if(request()->get('id'))
                            @if($invoice->invoicePaymentMethod->payment_status == 'success')
                                {{ Lang::get('core.thankyou') }}
                            @elseif($invoice->invoicePaymentMethod->payment_status == 'awaiting')
                                {{ Lang::get('core.awaitingpayment') }}
                            @else
                                {{ Lang::get('failedtransaction') }}
                            @endif
                        @else
                            {{ Lang::get('failedtransaction') }}
                        @endif
                    </h1>
                    @if(Auth::guest())
                        <h3 align="center">{{ Lang::get('core.managenewuser') }}</h3>
                    @endif
                    <h3 align="center">
                        @if(request()->get('id'))
                            @if($invoice->pay_status == 'Paid')
                                {{ Lang::get('core.managenote') }}
                            @else
                                {{ $invoice->invoicePaymentMethod->payment_status_message }}
                            @endif

                            {{-- @if($invoice->booking->bookTour->status == '0')
                                Transaction has been canceled
                            @else
                                @if($invoice->pay_status == 'Paid')
                                    {{ Lang::get('core.managenote') }}
                                @elseif($invoice->pay_status == 'Awaiting Payment')
                                    Awaiting payment
                                @elseif($invoice->pay_status)
                                    Payment has been partially paid
                                @endif
                            @endif --}}
                            
                        @else
                            {{ Lang::get('core.donthavetransaction') }}
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