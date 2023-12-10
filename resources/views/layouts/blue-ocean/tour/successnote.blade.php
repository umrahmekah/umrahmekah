@extends('layouts.blue-ocean.default')        

@section('content')

<script>
    redirectTime = "5000";
    redirectURL = "{{ url('dashboard') }}";
    setTimeout("location.href = redirectURL;",redirectTime);
</script>

<!-- DETAIL WRAPPER -->

    <div class="container mt-5 mb-5">
        <div class="detail-header">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <h1 align="center">{{ Lang::get('core.thankyou') }}</h1>
                    @if(Auth::guest())
                        <h3 align="center">{{ Lang::get('core.managenewuser') }}</h3>
                    @endif
                    @if($_GET)
                        @if($_GET['billplz']['paid']==='false')
                            <h3 align="center">{{ Lang::get('core.canceledorder') }}</h3>
                        @else
                            <h3 align="center">{{ Lang::get('core.managenote') }}</h3>
                        @endif
                    @else
                        <h3 align="center">{{ Lang::get('core.managenote') }}</h3>
                    @endif
                    <div>
                        <p align="center">
                            {{ Lang::get('core.supportnote1') }}<b>{{$email_owner->email}}</b>{{ Lang::get('core.supportnote2') }}<b>{{$email_owner->telephone}}</b>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection