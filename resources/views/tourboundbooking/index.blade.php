@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}

    <section class="content-header">
      <h1> {{ Lang::get('core.bookings') }}</h1>
    </section>

  <div class="content ">
<!-- @include('mmb.bookingmenu') -->
<div class="col-md-12 box box-primary">
	<div class="box-header with-border">
        		@include( 'mmb/toolbarmain')
	</div>
	<div class="box-body">

	 {!! Form::open(array('url'=>'tourboundbooking/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
	 <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-striped table-bordered " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>
				<th >{{ Lang::get('core.namesurname') }}</th>
				<th >{{ Lang::get('core.bookingno') }}</th>
				<th style="display:none">{{ Lang::get('core.tour') }}</th>
				<th style="display:none">{{ Lang::get('core.hotel') }}</th>
				<th style="display:none">{{ Lang::get('core.flight') }}</th>
				<th style="display:none">{{ Lang::get('core.car') }}</th>
				<th style="display:none">{{ Lang::get('core.extra') }}</th>
				{{--<th >{{ Lang::get('core.updated') }}</th>--}}
				<th>{{ Lang::get('core.agents') }}</th>
				<th >{{ Lang::get('core.created') }}</th>
				<th width="75">{{ Lang::get('core.btn_action') }}</th>
			  </tr>
        </thead>

        <tbody>
            @foreach ($rowData as $row)
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids minimal-red" name="ids[]" value="{{ $row->bookingsID }}" />  </td>
                    <td><a href="{{ url('travellers/show/'.$row->travellerID)}}" target="_blank" >{{ SiteHelpers::formatLookUp($row->travellerID,'travellerID','1:travellers:travellerID:nameandsurname') }}</a></td>
                    <td>
                    		@if($access['is_detail'] ==1)
							<a href="{{ url('tourboundbooking/show/'.$row->bookingsID.'?bn='.$row->bookingno.'&return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}">{{$row->bookingno}}</a>
							@else
								{{$row->bookingno}}
							@endif
                    </td>
                    <td style="display:none">{!! SiteHelpers::Tour($row->tour) !!}</td>
                    <td style="display:none">{!! SiteHelpers::Hotel($row->hotel) !!}</td>
                    <td style="display:none">{!! SiteHelpers::Flight($row->flight) !!}</td>
                    <td style="display:none">{!! SiteHelpers::Car($row->car) !!}</td>
                    <td style="display:none">{!! SiteHelpers::extraservices($row->extraservices) !!}</td>
                    {{--<td>{{ SiteHelpers::TarihFormat($row->updated_at)}}</td>--}}
                    <td>{{$row->agent}}</td>
                    <td>{{ SiteHelpers::TarihFormat($row->created_at)}}</td>
                    <td>
						 	@if($access['is_detail'] ==1)
							<a href="{{ url('tourboundbooking/show/'.$row->bookingsID.'?bn='.$row->bookingno.'&return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-eye fa-2x"></i> </a>
							@endif
							{{--@if($access['is_edit'] ==1)
							<a  href="{{ url('tourboundbooking/update/'.$row->bookingsID.'?bn='.$row->bookingno.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil fa-2x"></i> </a>
							@endif--}}
                            <a  href="{{ url('tourboundbooking/show/'.$row->bookingsID.'?pdf=true') }}" target="_blank" class="tips text-red" title="PDF"><i class="fa fa-file-pdf-o fa-2x"></i> </a>

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
        <div class="clr clear"></div>

<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#MmbTable').attr('action','{{ url("tourboundbooking/multisearch")}}');
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
    
    $('[data-toggle=confirmation-delete]').confirmation({
    rootSelector: '[data-toggle=confirmation-delete]',
    container: 'body',
    onConfirm: function(leo) {
    var total = $('input[class="ids"]:checkbox:checked').length;
	$('#MmbTable').submit();

    }
    });

    $('[data-toggle=confirmation-copy]').confirmation({
    rootSelector: '[data-toggle=confirmation-copy]',
    container: 'body',
    onConfirm: function(copy) {
    var total = $('input[class="ids"]:checkbox:checked').length;
    $('#MmbTable').attr('action','{{ url("tourboundbooking/copy")}}');
    $('#MmbTable').submit();
    }
    });


	$('.copy').click(function() {
		var total = $('input[class="ids"]:checkbox:checked').length;
		if(confirm('{{ Lang::get('core.rusureyouwanttocopythis') }}'))
		{
				$('#MmbTable').attr('action','{{ url("tourboundbooking/copy")}}');
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

<script>
	if ({{ Auth::User()->group_id }} == 2 || {{ Auth::User()->group_id }} == 4) {
		updateBookingNotification();
	}

	function updateBookingNotification() {
		axios.get('/booktour/updatebookingnotification')
		.then(response => {
			return response.data;
		}).catch(e => {
			console.log(e);
		}).then(data => {
			document.getElementById("notification_number").removeAttribute("data-count");
		}).catch(e => {
			console.log(e);
		});
	}
</script>

@stop
