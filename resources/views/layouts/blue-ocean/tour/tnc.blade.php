@extends('layouts.blue-ocean.default')        

@section('content')
    <div class="container mt-5 mb-5">
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <h2 class="detail-title color-dark-2">{{ $tandc->title }}</h2>
                </div>
            </div>
        </div>
        <div class="container">
        	{!! $tandc->tandc !!}
        </div>
    </div>
@endsection