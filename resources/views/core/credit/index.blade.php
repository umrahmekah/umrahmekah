@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
    <section class="content-header">
      <h1>{{ Lang::get('core.log') }}</h1>
    </section>
  <div class="content">
@include('mmb.config.tab')	
<div class="col-md-9">

<div class="box box-primary">
	<div class="box-header with-border hidden">
		  @include( 'mmb/toolbarmain')
	</div>
	<div class="box-body">
	<div class="box-header with-border">
		<h3 class="box-title">{{ Lang::get('core.m_credits') }}</h3>
	</div>
	<div class="form-horizontal">
	    <div class="form-group">
	    	<div class="well" style="background-color: #77dd77;">
	    		<?php $credit = SiteHelpers::GetCreditTotal() ;?>
                <h3>
                    {{ Lang::get('core.m_credits_balance') }} {{$credit}} {{ Lang::get('core.m_credits') }}
                </h3>
            </div>
		</div>
	</div>

	<form method="post" class="form-horizontal" action="/core/credit/topup">

		{{ csrf_field() }}

		<div class="form-group"><label class="col-sm-2 control-label">{{ Lang::get('core.want_to_topup') }}</label>

	        <div class="col-sm-10"><select class="form-control m-b" name="package_id" id="package_id">
	        	<option>{{ Lang::get('core.select_topup') }}</option>
	        	@foreach($creditPackage as $package)
	            <option value="{{$package['id']}}">{{CURRENCY_SYMBOLS}}{{$package['amount']}} {{ Lang::get('core.for') }} {{$package['credit']}} {{ Lang::get('core.m_credits') }}</option>
	            @endforeach
	        </select>
	        </div>
	    </div>

	    <div class="form-group">
            <div class="col-sm-4 col-sm-offset-2">
                <button class="btn btn-primary" type="submit">{{ Lang::get('core.pay_with_online_banking') }}</button>
            </div>
        </div>

	</form>

	<div class="box-header with-border">
		<h3 class="box-title">{{ Lang::get('core.m_credits_transaction') }}</h3>
	</div>

	 {!! Form::open(array('url'=>'log/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
	 <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-striped table-bordered " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>
				<!-- <th><?php echo Lang::get('core.ip') ;?></th>	
				<th><?php echo Lang::get('core.username') ;?></th>	
				<th><?php echo Lang::get('core.module') ;?></th>	
				<th><?php echo Lang::get('core.task') ;?></th>	
				<th><?php echo Lang::get('core.note') ;?></th>	
				<th><?php echo Lang::get('core.logdate') ;?></th> -->
				<th>ID</th>
				<th>User ID</th>
				<th>Owner ID</th>
				<th>Transaction ID</th>
				<th>Paid Amount</th>
				<th>Credit</th>
				<th>Transaction Date</th>
				<th>Transaction Time</th>
			  </tr>
        </thead>

        <tbody>
            @foreach ($rowData as $row)

            @if($row->status == 'paid')
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids minimal-red" name="ids[]" value="{{ $row->id }}" />  </td>
				 @foreach ($tableGrid as $field)
					 @if($field['view'] =='1')
					 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
					 	@if(SiteHelpers::filterColumn($limited ))
						 <td align="{{ $field['align'] }}" width=" {{ $field['width'] }}">
						 	{!! SiteHelpers::formatRows($row->{$field['field']},$field ,$row ) !!}
						 </td>
						@endif
					 @endif
				 @endforeach
                </tr>
            @endif
            @endforeach

        </tbody>

    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	</div>
</div>
</div>
</div>
                  			<div style="clear: both;"></div>

<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#MmbTable').attr('action','{{ url("core/credit/multisearch")}}');
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
				$('#MmbTable').attr('action','{{ url("core/credit/copy")}}');
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
