@include('layouts.modern.header')
<script>
    redirectTime = "5000";
    redirectURL = "{{ url('dashboard') }}";
    setTimeout("location.href = redirectURL;",redirectTime);

</script>
@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
    <img style="height:70px" src="{{ asset('uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" />
@else
    <img style="height:70px" src="{{ asset('mmb/images/logo.png')}}" />
@endif
<hr>
<div></div>
<h3 align="center">{{ Lang::get('core.thankyoupay') }}</h3>
<h4 align="center">{{ Lang::get('core.amountpaid') }} : {{ $email_data["amount_paid"] }}</h4>

<p>{{ Lang::get('core.bookingdetail') }}</p>
<p><b>{{ Lang::get('core.bookingno') }} : {{ $email_data["bookingno"] }}</b></p>
<p><b>{{ Lang::get('core.amountpaid') }} : {{ $email_data["amount_paid"] }}</b></p>
<p><b>{{ Lang::get('core.paydate') }} : {{ $email_data['paid_at'] }}</b></p>

<p>{{ Lang::get('core.bookingloginnote') }}</p>
<div style="background-color:{{ $email_data['color'] }}; width:100%">
    <p style="color: white;">{{ Lang::get('core.supportnote1') }}{{ $email_data['supportemail'] }}{{ Lang::get('core.supportnote2') }}{{ $email_data['supportphone'] }}</p>
</div>
<h2>{{ Lang::get('core.thankyou') }}</h2>

@include('layouts.modern.footer')