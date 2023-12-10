@extends('layouts.app') @section('content')

  <style>
    .m-t-md {
    margin-top: 20px;
  }

  .list-unstyled {
    padding-left: 0;
    list-style: none;
  }

  dl, ol, ul {
    margin-top: 0;
    margin-bottom: 1rem;
  }

  *, ::after, ::before {
    box-sizing: border-box;
  }

  h3, h4 {
    color: black;
  }

  .btn {
    font-size: 10px;
    padding:0px 22px;
    -moz-border-radius:28px;
    -webkit-border-radius:28px;
  }

  .badge {
    font-size: 10px;
  }

  ul {
    display: block;
    list-style-type: disc;
    margin-block-start: 1em;
    margin-block-end: 1em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    padding-inline-start: 40px;
  }

  .lazur-bg, .bg-info {
    background-color: #B2DDFF/*#23c6c8*/ !important;
    color: #ffffff;
  }

  .m-r-xs {
    margin-right: 5px;
  }

  /*.fa {
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  } */

  .widget {
    border-radius: 10px;
    padding: 5px 20px 5px 20px;
    margin-bottom: 10px;
    margin-top: 10px;
  }

  li {
    text-align: -webkit-match-parent;
    font-size: 12px; 
    color: black;
    padding-bottom: 3px;
    padding-top: 3px;
  }
s
  .list-unstyled {
    padding-left: 0;
    list-style: none;
  }

  ul {
    list-style-type: disc;
  }

hr{
    padding: 0px;
    margin: 0px;
    border-color: grey;    
  }

.progress {

  border-radius: 10px;
  background-color: white;
}

.progress-bar {
  border-radius: 10px;
  display: block;
  color: black;
}

</style>
  <!-- content starts here -->
  <section class="content-header">
    <h1>{{ Lang::get('core.dashboard') }}</h1>
     
  </section>
    <div class="content">
    <div class="row">
      <div class="box box-primary">
        <div class="box-header with-border">
          @foreach($toursB as $key =>  $tourdate)
            @if($key == 0 || $key%3 == 0)
            <div class="row">
            @endif
              <div class="col-md-4">
                <div class="box-body">
                  <div class="widget lazur-bg p-xl">
                    <a href="{{ url('tourdates/show/'.$tourdate->tourdateID.'') }}" class="tips" title="{{ Lang::get('core.btn_view') }}">
                      <h4 align="justify">
                        <span class="fa fa-calendar m-r-xs pull-left"></span><label>&nbsp {{ Lang::get('core.departuredate') }}:</label> &nbsp; {{ \Carbon::parse($tourdate->start)->format('d M Y') }}
                      </h4>
                      <ul class="list-unstyled m-t-md"><hr>
                        <li >
                          <span class="fa fa-sun-o m-r-xs"></span> {{ Lang::get('core.daycount') }}: <span style="float:right;">{{ $diff = Carbon\Carbon::parse($tourdate->start)->diffForHumans() }}</span>
                        </li>
                        <hr>
                        <li>
                          <span class="fa fa-ticket m-r-xs"></span> {{ Lang::get('core.code') }}: <span style="float:right;">{{ $tourdate->tour_code }}</span>
                        </li>
                        <hr>
                        <li>
                          <span class="fa fa-bed m-r-xs"></span> {{ Lang::get('core.occupancy') }} (%): <span style="float:right;">{{ number_format($tourdate->occupancy, 2) }}</span>
                        </li>
                        <hr>
                        <li>
                          <span class="fa fa-usd m-r-xs"></span>&nbsp {{ Lang::get('core.cash') }} ({{CURRENCY_SYMBOLS}}): <span style="float:right;">{{ number_format($tourdate->totalPayments, 2) }}</span>
                        </li>
                        <hr>
                        <li>
                          <span class="glyphicon glyphicon-tasks m-r-xs"></span> {{ Lang::get('core.taskcomplete') }}:
                          @if($tourdate->tasks->count() == 0)
                            <div>
                              <div style="padding-bottom: 20px">
                                <center><b>{{ Lang::get('core.no_task_recorded') }}</b></center>
                                <hr>
                              </div> 
                            </div>
                          @else
                            <div class="progress">
                              <div class="progress-bar progress-bar-success progress-bar-striped active" id="boot" name="progress" role="progressbar" style="width:{{ $tourdate->task_percentage }}%">
                                {{ $tourdate->taskFraction }}
                              </div>
                            </div> 
                          @endif
                        </li>
                      </ul>
                    </a>
                  </div>
                </div>
              </div>
            @if($key%3 == 2 || $toursB->keys()->last() == $key)
            </div>
            @endif
          @endforeach
          
        </div>
        <div class="text-center">{!! $toursB->render() !!}</div>
      </div>
   </div>
  </div>
  
  <div style="clear:both"></div>

@stop
