@extends('layouts.blue-ocean.default')        

@section('content')

<!-- DETAIL WRAPPER -->

    <div class="container mt-5 mb-5">
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-12">

                    <h1 align="center">
                        {{ ucwords(Lang::get('core.failedtransaction')) }}
                    </h1>
                    @if(Auth::guest())
                        <h3 align="center">{{ Lang::get('core.managenewuser') }}</h3>
                    @endif
                    <h3 align="center">
                        @if(request()->get('id'))
                            @if($invoice) 
                                {{ Lang::get('core.transactiontimeout') }}
                            @else
                                {{ Lang::get('core.donthavetransaction') }}
                            @endif
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