@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<div class="row">
    <div class="col-md-12" style="padding: 10px; padding-bottom: 0px;">
        <a href="{{ url('report/agentlist/')}}" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.agent_list') }}"><i class="fa fa-list fa-lg text-blue"></i> {{ Lang::get('core.agent_list') }}</a>
        <a href="{{ url('report?pdf="true"')}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.packages_report_pdf') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.packages_report_pdf') }}</a>
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
        padding-top: 5px;
        padding-bottom: 5px;
        
    }
    td{
        cursor: pointer;
    }
    tr:hover{
        background-color: #bcbcbc;
    }
    tr:first-child:hover {
    background-color: white;
    }
</style>

<div class="content"> 

    <div class="box box-primary">
    	<div class="box-header with-border">
            <div class="row">
                <div class="col-md-6">
                    <h3>{{ Lang::get('core.package_sales_and_collection_report') }}</h3>
                    <span>{{ \Carbon::today()->startOfYear()->format('d M Y') }} - {{ \Carbon::today()->format('d M Y') }}</span>
                </div>
            </div>
            <div class="row">
                        <div class="col-md-6" style="height: 250px; width: 930px;"> 
            
                            <canvas id="booking" style="max-height: 250px max-width: 930px" ></canvas>
                        
                        </div>
                <div class="col-md-6"></div>
            </div>
    	</div>
    	<div class="box-body" >


            
    		<div class="row">
                <div class="col-md-10">
                    <table border="1">
                        <tr>
                            <th>{{ Lang::get('core.package_name') }}</th>
                            <th>{{ Lang::get('core.sales') }} ({{CURRENCY_SYMBOLS}})</th>
                            <th>{{ Lang::get('core.cash_collection') }} ({{CURRENCY_SYMBOLS}})</th>
                            <th>{{ Lang::get('core.occupancy') }} (%)</th>
                        </tr>
                        @foreach($tours as $tour)
                            <tr class="hoverTable">
                                <td onclick="window.location = '{{ url('/report/package/'.$tour->tourID) }}';">{{ $tour->tour_name }}</td>
                                <td onclick="window.location = '{{ url('/report/package/'.$tour->tourID) }}';">{{CURRENCY_SYMBOLS}} {{ number_format($tour->ytdSales, 2) }}</td>
                                <td onclick="window.location = '{{ url('/report/package/'.$tour->tourID) }}';">{{CURRENCY_SYMBOLS}} {{ number_format($tour->ytdPayments, 2) }}</td>
                                <td onclick="window.location = '{{ url('/report/package/'.$tour->tourID) }}';">{{ number_format($tour->ytdOccupancy, 2) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>


    	</div>
    </div>	
</div>

<!-- <script>
var ctx = document.getElementById('booking');
var booking = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [@foreach ($tours as $tour) "{{ $tour->tour_name }}", @endforeach],
        datasets: [{
            label: 'Sales ({{CURRENCY_SYMBOLS}})',
            data: [@foreach ($tours as $tour) "{{ $tour->ytdSales }}", @endforeach],
            backgroundColor: [@foreach ($tours as $tour) 'rgba(255, 99, 132, 0.2)', @endforeach],
            borderColor: [@foreach ($tours as $tour) 'rgba(255, 99, 132, 1)', @endforeach],
            borderWidth: 1
        }],
        
        labels: [@foreach ($tours as $tour) "{{ $tour->tour_name }}", @endforeach],
        datasets: [{
            label: 'Sales ({{CURRENCY_SYMBOLS}})',
            data: [@foreach ($tours as $tour) "{{ $tour->ytdSales }}", @endforeach],
            backgroundColor: [@foreach ($tours as $tour) 'rgba(255, 99, 132, 0.2)', @endforeach],
            borderColor: [@foreach ($tours as $tour) 'rgba(255, 99, 132, 1)', @endforeach],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero: true,
                    autoSkip: false,
                    autoSkipPadding: 0
                }
            }]
        }, 
        maintainAspectRatio: false
    }
});
</script> !-->

<script>

var ctx = document.getElementById("booking").getContext("2d");

var data = {
  labels: [@foreach ($tours as $tour) "{{ $tour->tour_name }}", @endforeach],
  datasets: [{
    label: "Sales({{CURRENCY_SYMBOLS}})",
    backgroundColor: [@foreach ($tours as $tour) 'rgba(255, 99, 132, 0.5)', @endforeach],
    data: [@foreach ($tours as $tour) "{{ $tour->ytdSales }}", @endforeach]
  }, {
    label: "Cash Collection({{CURRENCY_SYMBOLS}})",
    backgroundColor: [@foreach ($tours as $tour) 'rgba(54, 162, 235, 0.5)', @endforeach],
    data: [@foreach ($tours as $tour) "{{ $tour->ytdPayments }}", @endforeach]
  }]
};

var myBarChart = new Chart(ctx, {
  type: 'bar',
  data: data,
  options: {
    barValueSpacing: 20,
    scales: {
      yAxes: [{
        ticks: {
          min: 0,
        }
      }],
        xAxes: [{
        ticks: {
            autoSkip: false,
          min: 0,
        }
      }]
    },
      maintainAspectRatio: false
  }
});

</script>

	  
@stop