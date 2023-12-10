@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
    <section class="content-header">
      <h1> {{ Lang::get('core.travelagents') }}</h1>
    </section>

  <div class="content"> 	
<div class="box box-primary">
	<div class="box-header with-border">
        		@include( 'mmb/toolbarmain')
	</div>
    
<!-- 	<div class="box-header with-border">

		<div class="box-header-tools pull-left" >
			@if($access['is_add'] ==1)
	   		<a href="{{ url('travelagents/update?return='.$return) }}" class="tips btn btn-success btn-circle btn-sm"  title="{{ Lang::get('core.btn_create') }}">
			<i class=" fa fa-plus "></i></a>
			<a href="javascript://ajax" class="btn btn-sm btn-info copy btn-circle" title="Copy" ><i class="fa  fa-file-o"></i> </a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="MmbDelete();" class="tips btn btn-danger btn-sm btn-circle" title="{{ Lang::get('core.btn_remove') }}">
			<i class="fa fa-trash-o"></i> </a>
			@endif 
			<a href="{{ url( 'travelagents/search?return='.$return) }}" class="btn btn-warning btn-sm btn-circle" onclick="MmbModal(this.href,'Advance Search'); return false;" title="{{ Lang::get('core.btn_search') }}"><i class="fa  fa-search"></i> </a>
			<a href="{{ url($pageModule) }}" class=" tips btn btn-default btn-sm btn-circle"  title="{{ Lang::get('core.btn_clearsearch') }}" ><i class="fa fa-repeat"></i>  </a>

		</div>

		<div class="box-header-tools pull-right" >

		@if($access['is_add'] ==1)
		<a href="{{ URL::to($pageModule .'/import?return='.$return) }}" onclick="MmbModal(this.href, 'Import CSV'); return false;" class="tips btn btn-xs btn-warning btn-circle" title="Import CSV">
		<i class="fa  fa-arrow-up"></i></a>
		@endif

		@if($access['is_excel'] ==1)
		<a href="{{ url( $pageModule .'/export/excel?return='.$return) }}" class="tips  btn btn-primary btn-xs btn-circle"  title="Excel"><i class="fa  fa-arrow-down"></i> </a>

		
		@endif

		@if(Session::get('gid') ==1)
			<a href="{{ url('mmb/module/config/'.$pageModule) }}" class="tips btn btn-success btn-sm btn-circle" title=" {{ Lang::get('core.btn_config') }}" ><i class="icon-options-vertical"></i></a>
		@endif 
		</div>


	</div> -->
	<div class="box-body "> 	
	   
<!-- 	 {!! (isset($search_map) ? $search_map : '') !!} -->
	
	 {!! Form::open(array('url'=>'travelagents/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
	 <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-bordered table-hover " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>
				<th>{{ Lang::get('core.agentname') }}</th>
				<th>{{ Lang::get('core.email') }}</th>
				<th>{{ Lang::get('core.phone') }}</th>
				{{-- <th>affiliatelink</th> --}}
				<th>{{ Lang::get('core.total_sales') }}</th>
				<th>{{ Lang::get('core.total_commission') }}</th>
				<th width="50" style="width: 50px;">{{ Lang::get('core.btn_action') }}</th>
				
			  </tr>
        </thead>

        <tbody>        						
            @foreach ($rowData as $row)
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->travelagentID }}" />  </td>
					<td>{{ $row->legalname }}</td>
					<td>{{ $row->email }}</td>
					<td>{{ $row->phone }}</td>
					{{-- <td>{{  CNF_DOMAIN }}/package?affiliate={{ $row->affiliatelink }}</td> --}}
					<td>{{ $row->total_sales }}</td>
					<td>{{ $row->total_commission }}</td>
					<td>

						 	@if($access['is_detail'] ==1)
							<a href="{{ url('travelagents/show/'.$row->travelagentID.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-eye fa-2x"></i> </a>
							@endif
							@if($access['is_edit'] ==1)
							<a  href="{{ url('travelagents/update/'.$row->travelagentID.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil fa-2x"></i> </a>
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

	$('.do-quick-search').click(function(){
		$('#MmbTable').attr('action','{{ url("travelagents/multisearch")}}');
		$('#MmbTable').submit();
	});

	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
	});
	$('#{{ $pageModule }}Table .checkall').on('ifChecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
	});
	$('#{{ $pageModule }}Table .checkall').on('ifUnchecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('uncheck');
	});	
    
	$('.copy').click(function() {
		var total = $('input[class="ids"]:checkbox:checked').length;
		if(confirm('{{ Lang::get('core.rusureyouwanttocopythis') }}'))
		{
				$('#MmbTable').attr('action','{{ url("travelagents/copy")}}');
				$('#MmbTable').submit();
		}
	})

});
</script>
<style>
.table th , th { text-align: none !important;  }
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>

<script>
  $(function () {
    $('#{{ $pageModule }}Table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
      "autoWidth": true,
      "language": datatableLang.{{ config('app.locale') }}
    });
  });
</script>

@stop