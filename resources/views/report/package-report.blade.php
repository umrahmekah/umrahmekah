@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<div class="row">
    <div class="col-md-12" style="padding: 10px; padding-bottom: 0px;">
        <a href="{{ url('report') }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
        <a href="{{ url('/report/package/'.$tour->tourID.'?pdf="true"')}}" target="_blank" class="btn btn-xs btn-default tips" title="{{ Lang::get('core.package_report_pdf') }}"><i class="fa fa-file-pdf-o fa-lg text-red"></i> {{ Lang::get('core.package_report_pdf') }}</a>
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
    td:hover{
        background-color: #bcbcbc;
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
                    <h3>Package Sales & Collection Report</h3>
                    <h4>{{ $tour->tour_name }}</h4>
                </div>
            </div>
            
            <div class="col-md-6" style="height: 250px; width: 930px;"> 
            
                    <canvas id="booking" style="max-height: 250px max-width: 930px" ></canvas>
               </div>
            
    	</div>
    	<div class="box-body" >

    		<div class="row">
                <div class="col-md-10">
                    <table border="1">
                        <tr>
                            <th>Code</th>
                            <th>Date</th>
                            <th>Sales ({{CURRENCY_SYMBOLS}})</th>
                            <th>Cash Collection ({{CURRENCY_SYMBOLS}})</th>
                            <th>Occupancy(%)</th>
                        </tr>
                        @foreach($tour->tourdates as $tourdate)
                            <tr>
                                <td onclick="window.location = '{{ url('/report/booking/'.$tourdate->tour_code) }}';">{{ $tourdate->tour_code }}</td>
                                <td onclick="window.location = '{{ url('/report/booking/'.$tourdate->tour_code) }}';">{{ \Carbon::parse($tourdate->start)->format('d M Y') }} - {{ \Carbon::parse($tourdate->end)->format('d M Y') }}</td>
                                <td onclick="window.location = '{{ url('/report/booking/'.$tourdate->tour_code) }}';">{{CURRENCY_SYMBOLS}} {{ number_format($tourdate->totalSales) }}</td>
                                <td onclick="window.location = '{{ url('/report/booking/'.$tourdate->tour_code) }}';">{{CURRENCY_SYMBOLS}} {{ number_format($tourdate->totalPayments) }}</td>
                                <td onclick="window.location = '{{ url('/report/booking/'.$tourdate->tour_code) }}';">{{ number_format($tourdate->occupancy, 2) }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>


    	</div>
    </div>	
</div>

<!--<script>
var ctx = document.getElementById('booking');
var booking = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [@foreach ($tour->tourdates as $tourdate) "{{ $tourdate->tour_code }}", @endforeach],
        datasets: [{
            label: 'Sales ({{CURRENCY_SYMBOLS}})',
            data: [@foreach ($tour->tourdates as $tourdate) "{{ $tourdate->totalSales }}", @endforeach],
            backgroundColor: [@foreach ($tour->tourdates as $tourdate) 'rgba(255, 99, 132, 0.2)', @endforeach],
            borderColor:[@foreach ($tour->tourdates as $tourdate) 'rgba(255, 99, 132, 1)', @endforeach],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
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
  labels:[@foreach($tour->tourdates as $tourdate) "{{ $tourdate->tour_code }}", @endforeach],
  datasets: [{
    label: "Sales({{CURRENCY_SYMBOLS}})",
    backgroundColor: [@foreach($tour->tourdates as $tourdate) 'rgba(255, 99, 132, 0.5)', @endforeach],
    data: [@foreach($tour->tourdates as $tourdate) "{{ $tourdate->totalSales }}", @endforeach]
  }, {
    label: "Cash Collection({{CURRENCY_SYMBOLS}})",
    backgroundColor:[@foreach($tour->tourdates as $tourdate) 'rgba(54, 162, 235, 0.5)', @endforeach],
    data:[@foreach($tour->tourdates as $tourdate) "{{ $tourdate->totalPayments }}", @endforeach]
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