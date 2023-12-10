@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
    <section class="content-header">
      <h1> {{ Lang::get('core.travellers') }} </h1>
    </section>

  <div class="content">          
<div class="box box-primary">
	<div class="box-header with-border">
        		@include( 'mmb/toolbarmain')
	</div>
    
	<div class="box-body">
		<div class="row">
			<div class="col-sm-6"></div>
			<div class="col-sm-6">
				<form method="GET">
					<div class="pull-right">
						<label>{{ Lang::get('core.search') }}: </label> &nbsp;
						<input type="search" name="search" value="{{request('search')}}" class="form-control" style="max-width: 200px; display: inline;">
						<button type="submit" class="btn btn-primary btn-sm">{{ Lang::get('core.search') }}</button>
					</div>
				</form>
			</div>
		</div>
	 {!! Form::open(array('url'=>'travellers/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
	 <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-striped table-bordered " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>
				<th>{{Lang::get('core.namesurname')}}</th>	
				<th>{{Lang::get('core.email')}}</th>	
				<th>{{Lang::get('core.country')}}</th>	
				<th>{{Lang::get('core.city')}}</th>	
				<th width="30">{{Lang::get('core.status')}}</th>	
				<th width="50" style="width: 50px;">{{ Lang::get('core.btn_action') }}</th>
			  </tr>
        </thead>

        <tbody>
            @foreach ($travellers as $row)
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids minimal-red" name="ids[]" value="{{ $row->travellerID }}" />  </td>
					
                    <td><a href="{{ url('travellers/show/'.$row->travellerID.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}">{{$row->nameandsurname}} {{$row->last_name}}</a></td>
                    <td><a href="mailto:{{$row->email}}" >{{$row->email}}</a></td>
                    <td>{{ SiteHelpers::formatLookUp($row->countryID,'countryID','1:def_country:countryID:country_name') }}</td>
                    <td>{{$row->city}}</td>
                    <td> {!! GeneralStatus::Status($row->status) !!}</td>
                    <td>

						 	@if($access['is_detail'] ==1)
							<a href="{{ url('travellers/show/'.$row->travellerID.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-eye fa-2x"></i> </a>
							@endif
							@if($access['is_edit'] ==1)
							<a  href="{{ url('travellers/update/'.$row->travellerID.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil fa-2x"></i> </a>
							@endif

					</td>
                </tr>

            @endforeach

        </tbody>

    </table>
    <div class="pull-right">
    	{!! $travellers->appends(['search' => request('search')])->render() !!}
    </div>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	</div>
</div>
</div>

<div class="modal inmodal" id="{{$pageModule}}_import" tabindex="-1" role="dialog">
    <div class="modal-dialog">
    	<div class="modal-content animated bounceInRight">
    		<form method="POST" enctype="multipart/form-data" action="/{{$pageModule}}/savefromcsv">
    			{{csrf_field()}}
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
	                <h4 class="modal-title">{{ Lang::get('core.traveller_list') }}</h4>
	                <small class="font-bold">{{ Lang::get('core.upload_traveller_list_csv') }}</small>
	            </div>
	            <div class="modal-body">
	                <div class="form-group">
	                	<label>{{ Lang::get('core.upload_traveller_csv_file') }}</label> 
	                	<div class=""><input required type="file" name="traveller_lists" id="flight_matching"></div>
	                </div>
	                <p>{!! Lang::get('core.download_traveller_csv_file', [
	                	'linkopen' => '<a href="/travellers/downloadtemplate">',
	                	'linkclose' => '</a>'
	                ]) !!}</p>
	                <p>{{ Lang::get('core.traveller_upload_file_csv_notice') }} <br><b style="color: red;">{!! Lang::get('core.traveller_upload_file_csv_notice2') !!}</b></p>
	                <p>{{ Lang::get('core.note') }}:</p>
	                <ul>
	                	<li>{{ Lang::get('core.traveller_upload_file_csv_list_1') }}</li>
	                	<li>{{ Lang::get('core.traveller_upload_file_csv_list_2') }}</li>
	                	<li>{{ Lang::get('core.traveller_upload_file_csv_list_3') }}</li>
	                	<li>{{ Lang::get('core.traveller_upload_file_csv_list_4') }}</li>
	                	<li>{{ Lang::get('core.traveller_upload_file_csv_list_5') }}</li>
	                </ul>
	            </div>
	            <div class="modal-footer">
	                <button type="button" class="btn btn-white" data-dismiss="modal">{{ Lang::get('core.close') }}</button>
	                <button type="submit" class="btn btn-primary">{{ Lang::get('core.uploadfile') }}</button>
	            </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#MmbTable').attr('action','{{ url("travellers/multisearch")}}');
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
				$('#MmbTable').attr('action','{{ url("travellers/copy")}}');
				$('#MmbTable').submit();
		}
	});

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
      "paging": false,
      "lengthChange": true,
      "searching": false,
      "ordering": false,
      "info": false,
      "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
      "autoWidth": true
    });
  });
</script>

@stop
