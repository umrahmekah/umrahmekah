@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
    <section class="content-header">
      <h1> {{ $pageTitle }}</h1>
    </section>

  <div class="content"> 	

<div class="box box-primary">

	<div class="box-header with-border">
		@include( 'mmb/toolbarmain')
	</div>

	<div class="box-body ">
	
		{!! Form::open(array('url'=>'owners/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
		<div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
			<table class="table table-bordered table-hover " id="{{ $pageModule }}Table">
				<thead>
					<tr>
						<th class="number"> No </th>
						<th> <input type="checkbox" class="checkall" /></th>
						<th width="50" style="width: 50px;">{{ Lang::get('core.btn_action') }}</th>
						<th>{{ Lang::get('core.name') }}</th>
						<th>{{ Lang::get('core.domain') }}</th>
						<th>{{ Lang::get('core.subdomain') }}</th>
						<th>{{ Lang::get('core.email') }}</th>


					  </tr>
				</thead>

				<tbody>
					@foreach ($rowData as $row)
						<tr>
							<td width="30"> {{ ++$i }} </td>
							<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" />  </td>
							<td>

								@if($access['is_detail'] ==1)
									<a href="{{ url('owners/show/'.$row->id.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-eye fa-2x"></i> </a>
								@endif
								@if($access['is_edit'] ==1)
									<a  href="{{ url('owners/update/'.$row->id.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil fa-2x"></i> </a>
								@endif

							</td>
							@if($access['is_view']==1)
								<td>{{$row->name}}</td>
								<td>{{$row->domain}}</td>
								<td>{{$row->subdomain}}</td>
								<td>{{$row->email}}</td>
							@endif
						</tr>

					@endforeach

				</tbody>

			</table>
			<input type="hidden" name="md" value="" />
		</div>
		{!! Form::close() !!}
		{{--@include('footer')--}}
	</div>
</div>		  
</div>	
<script>
$(document).ready(function(){
    $('.do-quick-search').click(function(){
        $('#MmbTable').attr('action','{{ url("owners/multisearch")}}');
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
			$('#MmbTable').attr('action','{{ url("owners/copy")}}');
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