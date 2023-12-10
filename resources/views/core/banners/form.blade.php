@extends('layouts.app')
@section('content')
    <section class="content-header">
      <h1>{{ Lang::get('core.banners') }}</h1>
    </section>
<div class="content">
<div class="box box-primary ">
	<div class="box-header with-border"> 
		<div class="box-header-tools pull-left" >
			<a href="{{ url($pageModule.'?return='.$return) }}" class="tips"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left fa-2x"></i></a> 
		</div>
	</div>
	<div class="box-body"> 	
		<ul class="parsley-error-list">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>	
		 {!! Form::open(array('url'=>'core/banners/save?return='.$return, 'class'=>'form-vertical','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
			<div class="col-md-9">
				{!! Form::hidden('bannerID', $row['bannerID']) !!}
				<div class="form-group  " >
					<label > {{ Lang::get('core.title') }} </label>									
					  {!! Form::text('title', $row['title'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
				</div>  					
				<div class="form-group  " >
					<textarea name='content' rows='5' id='content' class='form-control editor'  
   >{{ $row['content'] }}</textarea> 						
				</div>
				<div class="form-group  " >
					<label > {{ Lang::get('core.link') }} </label>									
					  {!! Form::text('link', $row['link'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
				</div>
				<div class="form-group  " >
					<label > {{ Lang::get('core.link_button') }} </label>									
					  {!! Form::text('link_button', $row['link_button'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
				</div> 

                <div class="form-group  " >
					<label > {{ Lang::get('core.headerimage') }}</label><br><br>
                    <div class="btn btn-primary btn-file"><i class="fa fa-camera fa-lg"></i>  {{ 	Lang::get('core.headerimage') }} 
						<input type="file" name="image"></input> 	
            		</div>
                	<div>
	                @if(file_exists('./uploads/images/'.CNF_OWNER.'/'.$row['image']) && $row['image'] !='')
	               		<span class="pull-left removeMultiFiles "  url="/uploads/images/".CNF_OWNER."/{{$row['image']}}">
							<i class="fa fa-trash-o fa-2x text-red " 
		                       data-toggle="confirmation" 
		                       data-title="{{Lang::get('core.rusure')}}" 
		                       data-content="{{ Lang::get('core.youwanttodeletethis') }}" >
		                    </i></span>    
	                {!! SiteHelpers::showUploadedFile($row['image'],'/uploads/images/'.CNF_OWNER.'/') !!}
	                @endif
	            	</div>
                </div>

			</div>
			<div class="col-md-3" id="sidebar">
            	<div class="theiaStickySidebar">
	                <div class="form-group  " >
						<label> {{ Lang::get('core.position_name') }} <span class="asterix"> * </span></label>
						<div class="">
							<input  type='text' name='position_name' id='position_name' value='{{(empty($row['position_name']))?'home':$row['position_name']}}' required class='form-control ' /> 
						 </div> 
					</div>

	                <div class="form-group  " >
						<label> {{ Lang::get('core.sort') }} <span class="asterix"> * </span></label>
						<div class="">
							<input  type='text' name='sort' id='sort' value='{{ $row['sort'] }}' required class='form-control ' /> 
						 </div> 
					</div>

					<div class="form-group  " >
						<label> {{ Lang::get('core.status') }}:  </label>
						<div class="">					
						  <input  type='radio' name='status'  value="enable" required class="minimal-red" 
						  @if( $row['status'] != 'disable')  	checked	  @endif				  
						   /> 
						  <label>{{ Lang::get('core.fr_enable') }}</label>
						</div> 
						<div class="">					
						  <input  type='radio' name='status'  value="disable" required class="minimal-red" 
						   @if( $row['status'] == 'disable')  	checked	  @endif				  
						   /> 
						  <label>{{ Lang::get('core.disabled') }}</label>
						</div> 					 
					</div>			
									   				
					<div class="form-group">
						<button type="submit" name="apply" class="btn btn-info btn-sm btn-flat" >{{ Lang::get('core.sb_apply') }}</button>
						<button type="submit" name="submit" class="btn btn-primary btn-sm btn-flat" >{{ Lang::get('core.sb_save') }}</button>
						<button type="button" onclick="location.href='{{ URL::to('core/banners?return='.$return) }}' " class="btn btn-danger btn-sm btn-flat">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>	
				</div>
			</div>
		 {!! Form::close() !!}	
	</div>
</div>		 
</div>		 
<div style="clear:both;"></div> 
	<script type="text/javascript">
       jQuery('#sidebar').theiaStickySidebar({
			additionalMarginTop: 60
		});
	</script>
	<script>
		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("core/banners/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});
    
	    $('[data-toggle=confirmation]').confirmation({
		    rootSelector: '[data-toggle=confirmation]',
		    container: 'body'
	    });


    	$("input[name='sort']").TouchSpin();
		
    </script>

@stop