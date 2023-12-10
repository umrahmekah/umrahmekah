@extends('layouts.app')

@section('content')
	{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
	<section class="content-header">
		<h1> {{ $pageTitle }} </h1>
	</section>

	<div class="content">

		<div class="box box-primary">
			<div class="box-header with-border">
				@include( 'mmb/toolbarmain')
			</div>
			<div class="box-body ">

				{!! (isset($search_map) ? $search_map : '') !!}
				@if(count($rowData)>0)

				{!! Form::open(array('url'=>'credittotals/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}


					<div class="row">
						@foreach($rowData as $row)

							<div class="column">
								<div class="col-xs-3"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->id }}" /></div>
								<div class="col-md-7">
									@if($access['is_edit'] ==1)
									<a href="{{ url('credittotals/show/'.$row->id.'?return='.$return)}}">{{ $row->name }}<p style="align-content: center">
											{{ Lang::get('core.total_credit_left') }}{{ $row->total_credit }}</p></a>
									@else
										<p style="align-content: center">
											{{ Lang::get('core.total_credit_left') }}{{ $row->total_credit }}</p>
									@endif
								</div>
							</div>
						@endforeach
					</div>
				{!! Form::close() !!}
				@else
					<h5 style="text-align: center">{{ Lang::get('core.no_data') }}</h5>
				@endif
			</div>
		</div>
	</div>
	<script>
        $(document).ready(function(){
            $('.do-quick-search').click(function(){
                $('#MmbTable').attr('action','{{ url("credittotals/multisearch")}}');
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
                    $('#MmbTable').attr('action','{{ url("credittotals/copy")}}');
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