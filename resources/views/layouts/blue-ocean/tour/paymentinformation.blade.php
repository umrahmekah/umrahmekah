@extends('layouts.blue-ocean.default')        

@section('content')
<!-- DETAIL WRAPPER -->
    <div class="container mt-5 mb-5">
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <h1 align="center">{{ Lang::get('core.paymentdetails') }}</h1>
                    <div class="table-responsive mt-5">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 20%">{{ Lang::get('core.productcode') }}</th>
                                    <th style="width: 35%">{{ Lang::get('core.product') }}</th>
                                    <th style="width: 10%" class="text-center">{{ Lang::get('core.qty') }}</th>
                                    <th style="width: 20%" class="text-right">{{ Lang::get('core.price') }}</th>
                                    <th style="width: 20%" class="text-right">{{ Lang::get('core.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td style="width: 20%">{{ $item->Code }}</td>
                                        <td style="width: 35%">{{ $item->Items }}</td>
                                        <td style="width: 10%" class="text-center">{{ $item->Qty }}</td>
                                        <td style="width: 20%" class="text-right">{{ CURRENCY_SYMBOLS }} {{ $item->Amount}}</td>
                                        <td style="width: 20%" class="text-right">{{ CURRENCY_SYMBOLS }} {{ $item->Qty * $item->Amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tr>
                                <th colspan="4" class="text-right">{{ Lang::get('core.subtotal') }}</th>
                                <td class="text-right">{{ CURRENCY_SYMBOLS }} {{ $invoice->Subtotal }}</td>
                            </tr>
                            @if($invoice->discount != 0) 
                                <tr>
                                    <th colspan="4" class="text-right">{{ Lang::get('core.discount_per_pax') }}</th>
                                    <td class="text-right">{{ CURRENCY_SYMBOLS }} {{ $invoice->discount }}</td>
                                </tr>
                            @endif
                            @if($invoice->tax != 0)
                                <tr>
                                    <th colspan="4" class="text-right">{{ Lang::get('core.tax') }} ( {{ $row->tax }} % )</th>
                                    <td class="text-right">{{ CURRENCY_SYMBOLS }} {{ ( $invoice->Subtotal - $invoice->discount ) * ($invoice->tax / 100) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th colspan="4" class="text-right">{{ Lang::get('core.total') }}</th>
                                <td class="text-right">{{ CURRENCY_SYMBOLS }} {{ $invoice->InvTotal }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="table-responsive mt-5">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="2">{{ Lang::get('core.paymentinformation') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 30%">{{ Lang::get('core.paymentmethod') }}</td>
                                    <td class="text-right">{{ $payment_method->channel }}</td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">{{ Lang::get('core.virtualaccountname') }}</td>
                                    <td class="text-right">{{ $payment_method->account_name }}</td>
                                </tr>
                                <tr>
                                    <td style="width: 30%">{{ Lang::get('core.virtualaccountnumber') }}</td>
                                    <td class="text-right">{{ $payment_method->account_number }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection