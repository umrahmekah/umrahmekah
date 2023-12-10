@extends('layouts.app') @section('content')
<style>
    .ibox{
        border-radius: 2px;
        background: linear-gradient(180deg, #1b8447 35%, #26A65B 0%);
    }
    .ibox-content{
        margin: 5px;
        padding: 1px;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<div class="content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox bg-green">
                    <div class="ibox-content">
                        <h5>{{Lang::get('core.visitorcounts')}}</h5>
                        <h2>0{{//$analyticsData[0]}}</h2>
                        <!-- <div class="stat-percent font-bold text-navy">98% <i class="fa fa-bolt"></i></div> -->
                        <!-- <small>Total income</small> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox bg-green">
                    <div class="ibox-content">
                        <h5>{{Lang::get('core.registeredconversion')}}</h5>
                        <h2>{{number_format((float)$analyticsData[1], 2, '.', '')}}%</h2>
                        <!-- <div id="sparkline2"><canvas style="display: inline-block; width: 207.25px; height: 60px; vertical-align: top;" width="207" height="60"></canvas></div> -->
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox bg-green">
                    <div class="ibox-content">
                        <h5>{{Lang::get('core.newbookingconversion')}}</h5>
                        <h2>{{number_format((float)$analyticsData[2], 2, '.', '')}}%</h2>
                        <div class="text-center">
                            <!-- <div id="sparkline6"><canvas style="display: inline-block; width: 140px; height: 140px; vertical-align: top;" width="140" height="140"></canvas></div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox bg-green">
                    <div class="ibox-content">
                        <h5>{{Lang::get('core.returningbookingconversion')}}</h5>
                        <h2>{{number_format((float)$analyticsData[3], 2, '.', '')}}%</h2>
                        <div class="text-center">
                            <!-- <div id="sparkline6"><canvas style="display: inline-block; width: 140px; height: 140px; vertical-align: top;" width="140" height="140"></canvas></div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{Lang::get('core.monthlybookingreport')}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-center">
                                    <strong></strong>
                                </p>
                                <div class="chart">
                                    <canvas id="monthlyBooking" width="400" height="130"></canvas>

                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
<div style="clear:both"></div>	

<script>
var ctx = document.getElementById("monthlyBooking").getContext('2d');
var monthlyBooking = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [@foreach ($graph as $dat) 
                "{!! $dat->monthNum !!}",
                @endforeach],
        datasets: [{
            label: '{{Lang::get('core.numberofbookings')}}',
            data: [@foreach ($graph as $dat) 
                {!! $dat->totalbook !!},
                @endforeach],
            backgroundColor: [
                'rgba(255, 206, 86, 0.5)',
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(75, 192, 192, 0.5)',
                'rgba(153, 102, 255, 0.5)',
                'rgba(255, 159, 64, 0.5)'
            ],
            borderColor: [
                'rgba(255, 206, 86, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 3
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    userCallback: function(label, index, labels) {
                        // when the floored value is the same as the value we have a whole number
                        if (Math.floor(label) === label) {
                            return label;
                        }
                    }
                }
            }]
        }
    }
});

$(document).ready(function() {

            var sparklineCharts = function(){
                 $("#sparkline2").sparkline([24, 43, 43, 55, 44, 62, 44, 72], {
                     type: 'line',
                     width: '100%',
                     height: '60',
                     lineColor: '#1ab394',
                     fillColor: "#ffffff"
                 });

                 $("#sparkline6").sparkline([5, 3], {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#1ab394', '#F5F5F5']
                 });
            };

            var sparkResize;

            $(window).resize(function(e) {
                clearTimeout(sparkResize);
                sparkResize = setTimeout(sparklineCharts, 500);
            });

            sparklineCharts();


        });
</script>
@stop
