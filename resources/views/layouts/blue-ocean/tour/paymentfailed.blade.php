@extends('layouts.blue-ocean.default')        

@section('content')
<!-- DETAIL WRAPPER -->
    <div class="container mt-5 mb-5">
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <h1 align="center">{{ ucwords(Lang::get('core.failedtransaction')) }}</h1>
                    <div class="text-center mt-3 mb-5">
                        @if(isset($response->insertMessage))
                            {{ $response->insertMessage }}
                        @else
                            {{ $response }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection