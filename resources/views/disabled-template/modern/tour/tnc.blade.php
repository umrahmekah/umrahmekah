@include('layouts.modern.header')

<!-- DETAIL WRAPPER -->
<div class="list-wrapper bg-grey-2">
    <div class="container">
        <ul class="list-breadcrumb clearfix">
            <li><a class="color-grey link-dr-blue" href="{{ url('') }}">{{ Lang::get('core.home') }}</a> /</li>
            <li><a class="color-grey link-dr-blue" href="{{ url('package') }}">{{ Lang::get('core.packages') }}</a> /</li>
            <li><a class="color-grey link-dr-blue" href="#" onclick="window.history.back();">{{ $tour }}</a> /</li>
            <li>{{ $tandc->title }}</li>
        </ul>
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
</div>


@include('layouts.modern.footer')  
