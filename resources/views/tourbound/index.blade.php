@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
    <section class="content-header">
      <h1> {{ $pageTitle }} <small> {{ $pageNote }} </small></h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
        <li  class="active"> {{ $pageTitle }} </li>
      </ol>
    </section>

  <div class="content"> 	

<div class="box box-primary">
	<div class="box-header with-border">
		@include( 'mmb/toolbarmain')
	</div>
	<div class="box-body ">
        
            {!! Form::open(array('url'=>'tours/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
            <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
                <table class="table table-striped table-filter" id="{{ $pageModule }}Table">
                    <thead>
                        <tr data-status="arama">
                        <th colspan="3"><div class="input-group pull-left"> 
                    <span class="input-group-addon">{{ Lang::get('core.btn_search') }}</span>
                    <input id="filter" type="text" class="form-control">
                </div></th>
                        <th colspan="5"> <div class="pull-right">
							<div class="btn-group">
							    <button type="button" class="btn btn-default btn-filter" data-target="all">All</button>
								<button type="button" class="btn btn-success btn-filter" data-target="1">{{ Lang::get('core.daily') }}</button>
								<button type="button" class="btn btn-warning btn-filter" data-target="2">{{ Lang::get('core.onrequest') }}</button>
								<button type="button" class="btn btn-info    btn-filter"  data-target="3">{{ Lang::get('core.setdate') }}</button>
								
							</div>
				</div></th>
                        </tr>
                        <tr data-status="title">
                            <th> <input type="checkbox" class="checkall" /></th>
				<th >{{ Lang::get('core.tourname') }}</th>
				<th >{{ Lang::get('core.tourcategory') }}</th>
				<th >{{ Lang::get('core.tourduration') }}</th>
				<th width="50">{{ Lang::get('core.departs') }}</th>
				<th width="50">{{ Lang::get('core.views') }}</th>
				<th width="50">{{ Lang::get('core.status') }}</th>
                <th>{{ Lang::get('core.btn_action') }}</th>
                        </tr>
                    </thead>

                    <tbody class="searchable">
                        @foreach ($rowData as $row)
                        <?php $tour_dates=\DB::table('tour_date')->where('tourID', $row->tourID )->where('status','=','1' )->count('tourdateID'); ?>

                        <tr data-status="{{$row->departs}}">
                            <td width="50"><input type="checkbox" class="ids minimal-red" name="ids[]" value="{{ $row->tourID }}" /> </td>
                            <td><a href="{{ url('tourbound/show/'.$row->tourID.'?return='.$return)}}">{{ $row->tour_name }}</a></td>
                            <td>{{ SiteHelpers::formatLookUp($row->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }}</td>
                            <td>{{ $row->total_days }} {{ Lang::get('core.days') }} - {{ $row->total_nights }} {{ Lang::get('core.nights') }}</td>
                            <td width="90">{!! SiteHelpers::departs($row->departs) !!}</td>
                            <td width="80"class="text-center">{{ $row->views }}</td>
                            <td width="70">{!! GeneralStatus::Status($row->status) !!}</td>
                            <td width="100">
                                @if($access['is_detail'] ==1)
                                <a href="{{ url('tourbound/show/'.$row->tourID.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}"><i class="fa fa-search fa-2x"></i> </a> 
                                @endif 
                                @if($access['is_edit'] ==1)
                                <a href="{{ url('tourbound/update/'.$row->tourID.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil fa-2x"></i> </a> 
                                @endif 
                                @if($access['is_detail'] ==1) 
                                @if($tour_dates !=0)
                                <a href="{{ url('tourbounddates?search=tourID:=:'.$row->tourID.'?return='.$return)}}" class="tips" title="{{ $tour_dates }} {{ Lang::get('core.departures') }}"><i class="fa fa-bus fa-2x text-blue"></i> </a> 
                                @endif 
                                @if($tour_dates ==0)
                                <a href="{{ url('tourbounddates/update?return=')}}" class="tips text-red" title="{{ Lang::get('core.adddeparturedate') }}"><i class="fa fa-plus-square fa-2x"></i> </a> 
                                @endif 
                                @endif
                            </td>
                        </tr>

                        @endforeach

                    </tbody>

                </table>
                <input type="hidden" name="md" value="" />
            </div>
            {!! Form::close() !!}
        </div>
</div>		  
</div>	
<script>
$(document).ready(function(){


	$('.copy').click(function() {
		var total = $('input[class="ids"]:checkbox:checked').length;
		if(confirm('are u sure Copy selected rows ?'))
		{
				$('#MmbTable').attr('action','{{ url("tourbound/copy")}}');
				$('#MmbTable').submit();// do the rest here
		}
	})	
	
});	
</script>	
<style>
.table th , th { text-align: none !important;  }
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>	
@stop