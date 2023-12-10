@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<div class="row">
    <div class="col-md-12" style="padding: 10px; padding-bottom: 0px;">
        <a href="{{ url('report') }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
        <a href="{{ url('report/agentdetail')}}" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.agent_detailed_list') }}"><i class="fa fa-list fa-lg text-blue"></i> {{ Lang::get('core.agent_detailed_list') }}</a>
        <a href="{{ url('report/agentlist?pdf="true"')}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.agent_report_pdf') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.agent_report_pdf') }}</a>
    </div>
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
                <div class="col-md-6">
                    <h3>{{ Lang::get('core.package_sales_and_collection_report') }}</h3>
                    <h4>{{ Lang::get('core.agent') }}</h4>
                </div>
                <div class="col-md-6">
                    <canvas id="booking" style="max-height: 250px; max-width: 500px;"></canvas>
                </div>
            </div>
    	</div>
    	<div class="box-body" >

    		<div class="row">
                <div class="col-md-12">
                    <table border="1">
                        <tr>
                            <th>{{ Lang::get('core.name') }}</th>
                            <th>{{ Lang::get('core.sales') }}</th>
                            <th>{{ Lang::get('core.cash_collection') }}</th>
                        </tr>
                        @foreach($agents as $agent)
                            <tr>
                                <td>{{ $agent->agency_name }}</td>
                                <td>{{ number_format($agent->totalSales) }}</td>
                                <td>{{ number_format($agent->totalPayments) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>


    	</div>
    </div>	
</div>

<script>
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
</script>
	  
@stop