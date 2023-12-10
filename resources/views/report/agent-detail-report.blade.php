@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<div class="row">
    <form method="get" target="_blank">
        <div class="col-md-12" style="padding: 10px; padding-bottom: 0px;">
            <a href="{{ url('report/agentlist') }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
            <input type="hidden" name="pdf" value="true">
            @if(request('agent_ids'))
                @foreach(request('agent_ids') as $id)
                    <input type="hidden" name="agent_ids[]" value="{{$id}}">
                @endforeach
            @endif
            <button type="submit" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.agent_detail_pdf') }}" style="margin-bottom: 10px;"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.agent_detail_pdf') }}</button>
        </div>
    </form>
</div>

<style>
    table{
        width: 100%;
    }
    /*table, th, td{
        border-style: solid;
        border: 1px;
    }*/
    th, td{
        padding-left: 5px;
        padding-right: 5px;
    }
</style>

<div class="content"> 

    <div class="box box-primary">
    	<div class="box-header with-border">
            <div class="row">
                <div class="col-md-12" style="margin-left: 5px;">
                    <form method="get">
                        <label>{{ Lang::get('core.agent') }} &nbsp</label>
                        <select id="agent_ids" name="agent_ids[]" multiple class="form-control" >
                            @foreach($agents as $agent)
                                <option value="{{ $agent->travelagentID }}" @if(request('agent_ids') && in_array($agent->travelagentID, request('agent_ids'))) selected @endif>{{ $agent->agency_name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary btn-sm">{{ Lang::get('core.search') }}</button>
                    </form>
                </div>
            </div>
    	</div>
        <div class="box-body with-border">
            <div class="row">
                <div class="col-md-6">
                    <h3>{{ Lang::get('core.package_sales_and_collection_report') }}</h3>
                    <h4>{{ Lang::get('core.agentdetails') }}</h4>
                </div>
                {{-- <div class="col-md-6">
                    <canvas id="booking" style="max-height: 250px; max-width: 500px;"></canvas>
                </div> --}}
            </div>
        </div>
    	<div class="box-body" >

    		<div class="row">
                <div class="col-md-12">
                    @foreach($results as $agent)
                        <h5>{{ $agent->agency_name }}</h5>
                        <table border="1">
                            <tr>
                                <th>{{ Lang::get('core.date') }}</th>
                                <th>{{ Lang::get('core.booking') }} #</th>
                                <th>{{ Lang::get('core.package') }}</th>
                                <th>{{ Lang::get('core.total_sales') }}</th>
                                <th>{{ Lang::get('core.payment_status') }}</th>
                                <th>{{ Lang::get('core.commission') }}</th>
                            </tr>
                            @foreach($agent->bookings as $booking)
                                <tr>
                                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                                    <td>{{ $booking->bookingno }}</td>
                                    <td>@if($booking->bookTour) {{ $booking->bookTour->tour->tour_name }} @else No Tour @endif</td>
                                    <td>@if($booking->invoice) {{ number_format($booking->invoice->InvTotal) }} @else No Invoice @endif</td>
                                    <td>@if($booking->invoice) {{ $booking->invoice->payStatus }} @else No Invoice @endif</td>
                                    <td>{{ number_format($booking->commissions) }}</td>
                                </tr>
                            @endforeach
                            {{-- @foreach($agents as $agent)
                                <tr>
                                    <td>{{ $agent->agency_name }}</td>
                                    <td>{{ number_format($agent->totalSales) }}</td>
                                    <td>{{ number_format($agent->totalPayments) }}</td>
                                </tr>
                            @endforeach --}}
                        </table>
                        <hr>
                    @endforeach
                </div>
            </div>


    	</div>
    </div>	
</div>

{{-- <script>
    var ctx = document.getElementById("booking");
    var booking = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [@foreach ($agents as $agent) "{{ $agent->agency_name }}", @endforeach],
            datasets: [{
                label: "Sales",
                data: [@foreach ($agents as $agent) "{{ $agent->totalSales }}", @endforeach],
                backgroundColor: [
                    @foreach ($agents as $agent)
                        "#" + (Math.random().toString(16) + "000000").slice(2, 8),
                    @endforeach
                ],

            }],
        },
        options: {
            legend: {
                @if($agents->count() > 10)
                    display: false,
                @endif
                position: 'bottom'
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<script>
$(document).ready(function(){
    $('#agent_ids').multiselect({
        nonSelectedText: '{{ Lang::get('core.select_agent') }}',
        enableFiltering: true,
        enableCaseInsensitiveFiltering: true,
        // buttonWidth:'100%',
        // enableClickableOptGroups: true,
        enableCollapsibleOptGroups: true,
        includeSelectAllOption: true
    });

});
</script>
	  
@stop