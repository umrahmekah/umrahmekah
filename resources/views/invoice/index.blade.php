@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
    <section class="content-header">
      <h1>{{Lang::get('core.invoices')}}</h1>
    </section>

  <div class="content">

<div class="box box-primary">
	<div class="box-header with-border">
		  @include( 'mmb/toolbarmain')
	</div>
	<div class="box-body">

		<div class="row">
			<div class="col-md-3">
				<label>Filter by tourdate <button class="btn btn-primary btn-sm" onclick="clearTourdate()">CLear</button></label>
				<select class="form-control" onchange="changeTourdate(this.value)">
					<option selected hidden disabled>Select tourdate</option>
					@foreach($tourdates as $td)
						<option value="{{$td->tourdateID}}" @if(session()->get('tourdatefilter') == $td->tourdateID) selected @endif>{{ $td->tour_code }} ({{\Carbon::parse($td->start)->format('d/m/Y')}} - {{\Carbon::parse($td->end)->format('d/m/Y')}})</option>
					@endforeach
				</select>
			</div>
		</div>

		<hr>

	 {!! Form::open(array('url'=>'invoice/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
	 <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-striped table-bordered " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>
				<th>{{ Lang::get('core.invoiceno') }}</th>	
				<th>{{ Lang::get('core.bookingno') }}</th>	
				<th>{{ Lang::get('core.total') }}</th>	
				<th>{{ Lang::get('core.traveller') }}</th>	
				<th>{{ Lang::get('core.issuedate') }}</th>	
				<th width="30">{{Lang::get('core.duedate') }}</th>	
				<th width="30">{{Lang::get('core.status') }}</th>	
				<th width="50" style="width: 50px;">{{ Lang::get('core.btn_action') }}</th>
			  </tr>
        </thead>

        <tbody>
            @foreach ($rowData as $row)
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids minimal-red" name="ids[]" value="{{ $row->invoiceID }}" />  </td>
                    <td><a href="{{ url('invoice/show/'.$row->invoiceID.'?return='.$return) }}">{{ $row->invoiceID }}</a>
                    </td>
                    <td><a @if($row->booking) href="{{ url('createbooking/show/'.$row->bookingID.'?return='.$return) }}" @endif target="_blank"> @if($row->booking) {{ $row->booking->bookingno }} @else No Booking Found @endif</a>
                    </td>
                    <td>{{ $row->InvTotal}} {{ SiteHelpers::formatLookUp($row->currency,'currencyID','1:def_currency:currencyID:currency_sym') }}
                    </td>
                    <?php $temptraveller = \DB::table('travellers')->where('travellerID', $row->travellerID)->first() ?>
                    <td>@if($temptraveller)<a href="{{ url('travellers/show/'.$row->travellerID.'?return='.$return) }}" target="_blank">{{ $temptraveller->nameandsurname.' '.$temptraveller->last_name }}</a>@endif
                    </td>
                    <td>{{ SiteHelpers::TarihFormat($row->DateIssued)}}
                    </td>
                    <td>{!! InvoiceStatus::paymentstatus($row->DueDate) !!}
                    </td>
                    <td><?php   
    $payment = DB::table('invoice_payments')->where('invoiceID', $row->invoiceID )->sum('amount');
    $Total = $row->InvTotal ;
                        ?>{!! InvoiceStatus::Payments($payment , $Total) !!}</td>
					<td width="100">

						 	@if($access['is_detail'] ==1)
							<a href="{{ url('invoice/show/'.$row->invoiceID.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-eye fa-2x"></i> </a>
							@endif
							@if($access['is_edit'] ==1)
							<a  href="{{ url('invoice/update/'.$row->invoiceID.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil fa-2x"></i> </a>
							@endif
							<a  href="{{ url('invoice/show/'.$row->invoiceID.'?pdf=true') }}" target="_blank" class="tips text-red" title="PDF"><i class="fa fa-file-pdf-o fa-2x"></i> </a>

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
		$('#MmbTable').attr('action','{{ url("invoice/multisearch")}}');
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
				$('#MmbTable').attr('action','{{ url("invoice/copy")}}');
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
      "autoWidth": true,
      "language": datatable.{{ config('app.locale') }}
    });
  });

  function changeTourdate(value) {
  	axios.get('/tourdates/tourdatefilter/'+value)
    .then(response => {
    	location.reload();
    }).catch(e => {
    	console.log(e.response.data);
    });
  }

  function clearTourdate() {
  	axios.get('/tourdates/tourdatefilterclear')
    .then(response => {
    	location.reload();
    }).catch(e => {
    	console.log(e.response.data);
    });
  }
</script>

@stop
