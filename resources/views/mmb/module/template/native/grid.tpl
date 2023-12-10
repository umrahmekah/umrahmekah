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

		<div class="box-header-tools pull-left" >
			@if($access['is_add'] ==1)
	   		<a href="{{ url('{class}/update?return='.$return) }}" class="tips btn btn-success btn-circle btn-sm"  title="{{ Lang::get('core.btn_create') }}">
			<i class=" fa fa-plus "></i></a>
			<a href="javascript://ajax" class="btn btn-sm btn-info copy btn-circle" title="Copy" ><i class="fa  fa-file-o"></i> </a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="MmbDelete();" class="tips btn btn-danger btn-sm btn-circle" title="{{ Lang::get('core.btn_remove') }}">
			<i class="fa fa-trash-o"></i> </a>
			@endif 
			<a href="{{ url( '{class}/search?return='.$return) }}" class="btn btn-warning btn-sm btn-circle" onclick="MmbModal(this.href,'Advance Search'); return false;" title="{{ Lang::get('core.btn_search') }}"><i class="fa  fa-search"></i> </a>
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


	</div>
	<div class="box-body "> 	
	   



	 {!! (isset($search_map) ? $search_map : '') !!}
	
	 {!! Form::open(array('url'=>'{class}/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
	 <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-bordered table-hover " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>
				<th width="50" style="width: 50px;">{{ Lang::get('core.btn_action') }}</th>
				
				@foreach ($tableGrid as $t)
					@if($t['view'] =='1')				
						<?php $limited = isset($t['limited']) ? $t['limited'] :''; 
						if(SiteHelpers::filterColumn($limited ))
						{
							$addClass='class="tbl-sorting" ';
							if($insort ==$t['field'])
							{
								$dir_order = ($inorder =='desc' ? 'sort-desc' : 'sort-asc'); 
								$addClass='class="tbl-sorting '.$dir_order.'" ';
							}
							echo '<th align="'.$t['align'].'" '.$addClass.' width="'.$t['width'].'">'.\SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())).'</th>';				
						} 
						?>
					@endif
				@endforeach
				
			  </tr>
        </thead>

        <tbody>        						
            @foreach ($rowData as $row)
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->{key} }}" />  </td>
					<td>
					 	<div class="dropdown">
						  <button class="btn btn-success btn-sm btn-outline  btn-circle dropdown-toggle" type="button" data-toggle="dropdown"> <i class="fa fa-arrow-down"></i>
						  <span class="caret"></span></button>
						  <ul class="dropdown-menu">
						 	@if($access['is_detail'] ==1)
							<li><a href="{{ url('{class}/show/'.$row->{key}.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i> {{ Lang::get('core.btn_view') }} </a></li>
							@endif
							@if($access['is_edit'] ==1)
							<li><a  href="{{ url('{class}/update/'.$row->{key}.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit "></i> {{ Lang::get('core.btn_edit') }} </a></li>
							@endif
						  </ul>
						</div>

					</td>														
				 @foreach ($tableGrid as $field)
					 @if($field['view'] =='1')
					 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
					 	@if(SiteHelpers::filterColumn($limited ))
					 	 <?php $addClass= ($insort ==$field['field'] ? 'class="tbl-sorting-active" ' : ''); ?>
						 <td align="{{ $field['align'] }}" width=" {{ $field['width'] }}"  {!! $addClass !!} >					 
						 	{!! SiteHelpers::formatRows($row->{$field['field']},$field ,$row ) !!}						 
						 </td>
						@endif	
					 @endif					 
				 @endforeach			 
                </tr>
				
            @endforeach
              
        </tbody>
      
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	@include('footer')
	</div>
</div>		  
</div>	
<script>
$(document).ready(function(){


	$('.copy').click(function() {
		var total = $('input[class="ids"]:checkbox:checked').length;
		if(confirm('are u sure Copy selected rows ?'))
		{
				$('#MmbTable').attr('action','{{ url("{class}/copy")}}');
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