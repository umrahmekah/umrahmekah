<?php usort($tableGrid, "SiteHelpers::_sort"); ?>
<div class="box box-primary">
	<div class="box-header with-border">
		
		@include( 'mmb/toolbar')
	</div>
	<div class="box-body"> 	

	 {!! (isset($search_map) ? $search_map : '') !!}
	 
	 <?php echo Form::open(array('url'=>'testimonials/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable'  ,'data-parsley-validate'=>'' )) ;?>
<div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">	
	@if(count($rowData)>=1)
    <table class="table table-bordered table-striped " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th width="20"> No </th>
				<th width="30"> <input type="checkbox" class="checkall" /></th>		
				@if($setting['view-method']=='expand')<th width="50" style="width: 50px;">  </th> @endif		
				<th width="50"><?php echo Lang::get('core.btn_action') ;?></th>	
                <th><?php echo Lang::get('core.namesurname') ;?></th>	
				<th><?php echo Lang::get('core.email') ;?></th>					
                <th><?php echo Lang::get('core.country') ;?></th>				
                <th><?php echo Lang::get('core.tourdate') ;?></th>		
				<th><?php echo Lang::get('core.tourname') ;?></th>	
				<th width="30"><?php echo Lang::get('core.status') ;?></th>					
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
           			  $id = $row->testimonialID;
           		?>
                <tr class="editable" id="form-{{ $row->testimonialID }}">
					<td class="number"> <?php echo ++$i;?>  </td>
					<td ><input type="checkbox" class="ids" name="ids[]" value="<?php echo $row->testimonialID ;?>" />  </td>					
					@if($setting['view-method']=='expand')
					<td><a href="javascript:void(0)" class="expandable" rel="#row-{{ $row->testimonialID }}" data-url="{{ url('testimonials/show/'.$id) }}"><i class="fa fa-plus-square fa-2x" ></i></a></td>								
					@endif		
				 <td data-values="action" data-key="<?php echo $row->testimonialID ;?>"  >
					{!! AjaxHelpers::buttonAction('testimonials',$access,$id ,$setting) !!}
					{!! AjaxHelpers::buttonActionInline($row->testimonialID,'testimonialID') !!}	
						
				</td>						
					 <?php foreach ($tableGrid as $field) :
					 	if($field['view'] =='1') : 
							$value = SiteHelpers::formatRows($row->{$field['field']}, $field , $row);
						 	?>
						 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
						 	@if(SiteHelpers::filterColumn($limited ))
								 <td align="<?php echo $field['align'];?>" data-values="{{ $row->{$field['field']} }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">					 
									{!! $value !!}							 
								 </td>
							@endif	
						 <?php endif;					 
						endforeach; 
					  ?>			 
                </tr>
                @if($setting['view-method']=='expand')
                <tr style="display:none" class="expanded" id="row-{{ $row->testimonialID }}">
                	<td class="number"></td>
                	<td colspan="8" class="data"></td>
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
        @include('ajaxfooter')	
	</div>
</div>	
	
	@if($setting['inline'] =='true') @include('mmb.module.utility.inlinegrid') @endif
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

