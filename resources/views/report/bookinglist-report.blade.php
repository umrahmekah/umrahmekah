@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<div class="row">
    <div class="col-md-12" style="padding: 10px; padding-bottom: 0px;">
        <a href="{{ url('report') }}" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-left fa-2x"></i></a>
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
</style>

<div class="content"> 

    <div class="box box-primary">
    	<div class="box-header with-border">
            <div class="row">
                <div class="col-md-6">
                    <h3>Booking List Report</h3>
                    <h4>{{$tour_c->tour_code}}</h4>
                </div>
                <div class="col-md-6">
                </div>
            </div>
    	</div>
    	<div class="box-body" >

    		<div class="row">
                <div class="col-md-10">
                    <table border="1">
                        <tr>
                            <th>Booking No</th>
                            <th>Primary Contact Name</th>
                            <th>Primary Contact Phone</th>
                            <th>Sales ({{CURRENCY_SYMBOLS}})</th>
                            <th>Cash Collection ({{CURRENCY_SYMBOLS}})</th>
                        </tr>
                        @foreach($tour_c->booktours as $booktour)
                            <tr>
                                <td>{{ $booktour->booking->bookingno }}</td>
                                <td>@if($booktour->booking->traveller){{ $booktour->booking->traveller->fullname }}@endif</td>
                                <td>@if($booktour->booking->traveller){{ $booktour->booking->traveller->phone }}@endif</td>
                                
                                @if($booktour->booking->invoice && $booktour->booking->invoice->InvTotal > 0)
                                <td> {{CURRENCY_SYMBOLS}} {{number_format($booktour->booking->invoice->InvTotal, 2)}} </td>
                                    @else
                                <td align="center"> - </td>
                                @endif 
                                
                                @if($booktour->booking->invoice && $booktour->booking->invoice->totalPaid > 0)
                                <td> {{CURRENCY_SYMBOLS}} {{number_format($booktour->booking->invoice->totalPaid, 2)}} </td>
                                    @else
                                <td align="center"> - </td>
                                    
                                    @endif 
                                
                                <!--<td>{{CURRENCY_SYMBOLS}} @if($booktour->booking->invoice){{number_format($booktour->booking->invoice->totalPaid, 2) }}@endif</td> !-->
                                
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>


    	</div>
    </div>	
</div>

	  
@stop