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
                    
                        <h5>{{Lang::get('core.totalbooking')}}</h5>
                        <h2>{{$totalbookings}}</h2> 
                        <!-- <div class="stat-percent font-bold text-navy">98% <i class="fa fa-bolt"></i></div> -->
                        <!-- <small>{{Lang::get('core.totalincomes')}}</small> -->
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
                        {{-- <h3 class="box-title">{{Lang::get('core.monthlybookingreport')}}</h3> --}}
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class="box-title">Report for {{ $currentHijri->format('Y') }}H Session</h3>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control" onchange="changeYear(this.value)">
                                    <option value="{{ \HijriDate::today()->year }}">{{ \HijriDate::today()->year }}H</option>
                                    <option value="{{ \HijriDate::today()->year - 1 }}" @if($currentHijri->year == (\HijriDate::today()->year - 1)) selected @endif>{{ \HijriDate::today()->year - 1 }}H</option>
                                    <option value="{{ \HijriDate::today()->year - 2 }}" @if($currentHijri->year == (\HijriDate::today()->year - 2)) selected @endif>{{ \HijriDate::today()->year - 2 }}H</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row" style="margin-bottom: 10px;">
                            <!-- Monthly booking chart !-->
                            <div class="col-md-6">
                                <p class="text-center">
                                    <strong>Total Booking</strong>
                                </p>
                                <div class="chart">
                                    <canvas id="monthlyBooking" width="400" height="130"></canvas>
                                </div>
                            </div>
                            <!-- Gender chart canvas !-->
                            <div class="col-md-6">
                                <p class="text-center">
                                    <strong>Booking by Gender</strong>
                                </p>
                                <div class="chart">
                                    <canvas id="genderStat" width="400" height="130"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Age bar chart !-->
                            <div class="col-md-6">
                                 <h1></h1>
                                <p class="text-center">
                                    <strong>Booking by Age</strong>
                                </p>
                                <div class="chart">
                                    <p style="margin-top: 5px;"></p>
                                    <canvas id="ageStat" width="400" height="130"></canvas>
                                </div>
                            </div>
                            <!-- Age bar chart ends !-->
                            <!-- Source bar chart !-->
                            <div class="col-md-6">
                                 <h1></h1>
                                <p class="text-center">
                                    <strong>Booking by source</strong>
                                </p>
                                <div class="chart">
                                    <p style="margin-top: 5px;"></p>
                                    <canvas id="sourceStat" width="400" height="130"></canvas>
                                </div>
                            </div>
                            {{-- source barchart ends --}}
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
        labels: [@foreach ($bookingMonths as $key => $months) 
                "{!! $key !!}",
                @endforeach],
        datasets: [{
            label: '{{Lang::get('core.numberofbookings')}}',
            data: [@foreach ($bookingMonths as $key => $months) 
                {!! $months !!},
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
            }],
            xAxes: [{
                stacked: false,
                beginAtZero: true,
                scaleLabel: {
                    labelString: 'Month'
                },
                ticks: {
                    stepSize: 1,
                    min: 0,
                    autoSkip: false
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

<!-- Age bar chart Chart !-->
<script>
var ctx = document.getElementById('ageStat').getContext('2d');
var ageStat = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [ "Below 2", "2 - 12", "13 - 17", "18 - 25", "26 - 31", "32 - 36", "37 - 41", "42 - 46", "47 - 51", "52 - 56", "Above 61" ],
        datasets: [{
            label: 'Number for each age',
            data: [ @foreach($ages as $age)
                   {{$age}},
                   @endforeach ],
            backgroundColor: [ @foreach($ages as $age)
                   'rgba(255, 99, 132, 0.2)',
                   @endforeach ],
            borderColor: [ @foreach($ages as $age)'rgba(255, 99, 132, 1)', @endforeach ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    callback: function(value) {if (value % 1 === 0) {return value;}}
                }
            }]
        }
    }
});
</script>

<!-- Gender doughnut chart !-->
<script>
    var ctx = document.getElementById("genderStat");
    var genderStat = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ["Male", "Female"],
            datasets: [{
                data: [{{$male_count}}, {{$female_count}}],
                backgroundColor: ["#a3c7c9","#FF69B4"],

            }],
        },
        options: {
            legend: {
                    display: true,
                position: 'bottom'
            },
            responsive: true,
            maintainAspectRatio: false,
        }
    });
</script> 

<!-- Source bar chart !-->
<script>
var ctx = document.getElementById('sourceStat').getContext('2d');
var ageStat = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [ @foreach ($sourceType as $type)
            "{{$type}}",
        @endforeach ],
        datasets: [{
            label: 'Number for each source',
            data: [ @foreach($source as $key => $sr)
            <?php if($key == 0){ continue; } ?>
                   {{$sr}},
                   @endforeach ],
            backgroundColor: [ @foreach($source as $sr)
                   'rgb(201, 209, 232)',
                   @endforeach ],
            borderColor: [ @foreach($source as $sr)'rgb(139, 162, 198)', @endforeach ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    callback: function(value) {if (value % 1 === 0) {return value;}}
                }
            }],
            xAxes: [{
                ticks: {
                    fontSize: 10
                }
            }]
        }
    }
});
</script>

<script>
    function changeYear(year) {
        window.location.href = "{{ url('analytic?year=') }}"+year;
    }
</script>


@stop
