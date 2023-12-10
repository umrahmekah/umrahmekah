<?php usort($tableGrid, "SiteHelpers::_sort"); ?> <div class="col-md-12">
<div class="box box-primary">
	<div class="box-header with-border">
		
		@include( 'mmb/toolbar')
	</div>
	<div class="box-body"> 	

	 {!! (isset($search_map) ? $search_map : '') !!}
	 
	 <?php echo Form::open(array('url'=>'suppliers/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable'  ,'data-parsley-validate'=>'' )) ;?>
<div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">	
	@if(count($rowData)>=1)
    <table class="table table-bordered table-striped " class="display compact" id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th width="20"> No </th>
				<th width="30"> <input type="checkbox" class="checkall" /></th>		
				@if($setting['view-method']=='expand')<th width="50" style="width: 50px;">  </th> @endif			
				<th><?php echo Lang::get('core.suppliername') ;?></th>
                <th><?php echo Lang::get('core.email') ;?></th>
                <th><?php echo Lang::get('core.suppliertype') ;?></th>
				<th><?php echo Lang::get('core.phone') ;?></th>
				<th width="100"><?php echo Lang::get('core.btn_action') ;?></th>
				<th><?php echo Lang::get('core.status') ;?></th>
			  </tr>
        </thead>

        <tbody>
        	@if($access['is_add'] =='1' && $setting['inline']=='true')
			<tr id="form-0" >
				<td> # </td>
				<td> </td>
				@if($setting['view-method']=='expand') <td> </td> @endif
				<td >
					<button onclick="saved('form-0')" class="btn btn-success btn-xs" type="button"><i class="fa fa-play-circle"></i></button>
				</td>
				@foreach ($tableGrid as $t)
					@if($t['view'] =='1')
					<?php $limited = isset($t['limited']) ? $t['limited'] :''; ?>
						@if(SiteHelpers::filterColumn($limited ))
						<td data-form="{{ $t['field'] }}" data-form-type="{{ AjaxHelpers::inlineFormType($t['field'],$tableForm)}}">
							{!! SiteHelpers::transForm($t['field'] , $tableForm) !!}								
						</td>
						@endif
					@endif
				@endforeach

			  </tr>	 
			  @endif        
			
           		<?php foreach ($rowData as $row) : 
           			  $id = $row->supplierID;
           		?>
                <tr class="editable" id="form-{{ $row->supplierID }}">
					<td class="number"> <?php echo ++$i;?>  </td>
					<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->supplierID ;?>" />  </td>					
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->supplierID }}" data-url="{{ url('suppliers/show/'.$id) }}"><i class="fa fa-plus-square " ></i></a></td>								
					@endif					
					<td>{{ $row->name }}</td>
					<td>{{ $row->email }}</td>
					<td>{{ $row->supplier->type->supplier_type }}</td>
					<td>{{ $row->phone }}</td>
					<td data-values="action" data-key="<?php echo $row->supplierID ;?>"  >
					 	{{-- <div class=" action " > --}}
					 		<a   class="tips" href="{{ url('suppliers/show2/'.$row->supplierID) }}" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-eye fa-2x"></i> </a>
					 		<a href="{{url('suppliers/update/' . $id)}}" onclick="MmbModal(this.href,'{{Lang::get('core.edit')}}'); return false;" class="tips" title="{{Lang::get('core.btn_edit')}}"><i class="fa fa-pencil fa-2x"></i></a>
					 	{{-- </div> --}}
						{{-- {!! AjaxHelpers::buttonAction('suppliers',$access,$id ,$setting) !!} --}}
						{{-- {!! AjaxHelpers::buttonActionInline($row->supplierID,'supplierID') !!}	 --}}
						
					</td>	
					<td>{!! $row->supplier->statusLabel !!}</td>		
                </tr>
                @if($setting['view-method']=='expand')
                <tr style="display:none" class="expanded" id="row-{{ $row->supplierID }}">
                	<td class="number"></td>
                	<td></td>
                	<td></td>
                	<td colspan="{{ $colspan}}" class="data"></td>
                	<td></td>
                </tr>
                @endif				
            <?php endforeach;?>
              
        </tbody>
      
    </table>
	@else

	<div style="margin:100px 0; text-align:center;">
	
		<p> {{ Lang::get('core.norecord') }} </p>
	</div>
	
	@endif		
	
	</div>
	<?php echo Form::close() ;?>
<!--
        @include('ajaxfooter')
-->
	
	</div>
</div>	
	
	</div>	 	                  			<div style="clear: both;"></div>  	@if($setting['inline'] =='true') @include('mmb.module.utility.inlinegrid') @endif
<script>
$(document).ready(function() {
	$('.tips').tooltip();	
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
	
	$('#{{ $pageModule }}Paginate .pagination li a').click(function() {
		var url = $(this).attr('href');
		reloadData('#{{ $pageModule }}',url);		
		return false ;
	});

	<?php if($setting['view-method'] =='expand') :
			echo AjaxHelpers::htmlExpandGrid();
		endif;
	 ?>	
});		
</script>	
<style>
.table th { text-align: none !important;  }
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>