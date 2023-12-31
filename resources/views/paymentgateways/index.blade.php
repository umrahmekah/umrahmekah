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
	   



	 {!! (isset($search_map) ? $search_map : '') !!}
	
	 {!! Form::open(array('url'=>'paymentgateways/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
	 <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-bordered table-hover " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>
				<th width="50" style="width: 50px;">{{ Lang::get('core.btn_action') }}</th>
				<th>{{ Lang::get('core.gateway_name') }}</th>
				<th>{{ Lang::get('core.gateway_key') }}</th>
				
			  </tr>
        </thead>

        <tbody>        						
            @foreach ($rowData as $row)
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" />  </td>
					<td>

						@if($access['is_detail'] ==1)
							<a href="{{ url('paymentgateways/show/'.$row->id.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-eye fa-2x"></i> </a>
						@endif
						@if($access['is_edit'] ==1)
							<a  href="{{ url('paymentgateways/update/'.$row->id.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil fa-2x"></i> </a>
						@endif

					</td>														
					<td>{{ $row->gateway_name }}</td>
					<td>{{ $row->gateway_api_key }}</td>
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
        $('#MmbTable').attr('action','{{ url("paymentgateways/multisearch")}}');
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
		if(confirm('are u sure Copy selected rows ?'))
		{
				$('#MmbTable').attr('action','{{ url("paymentgateways/copy")}}');
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

<script>
    $(function () {
        $('#{{ $pageModule }}Table').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
            "autoWidth": true
        });
    });
</script>
@stop