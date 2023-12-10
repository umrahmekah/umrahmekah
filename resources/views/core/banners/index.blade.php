@extends('layouts.app')
@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}

    <section class="content-header">
      <h1>{{ Lang::get('core.banners') }}
      </h1>
    </section>
  <div class="content">          
<div class="box box-primary">
	<div class="box-header with-border">
        		@include( 'mmb/toolbarmain')
	</div>

				<div class="box-body "> 	


				 {!! (isset($search_map) ? $search_map : '') !!}
				
				 {!! Form::open(array('url'=>'core/banners/delete?return='.$return, 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
				 <div class="table-responsive" style="min-height:300px;  padding-bottom:60px;">
			    <table class="table table-hover ">
			        <thead>
						<tr>
							<th class="number"><span> No </span> </th>
							<th> <input type="checkbox" class="checkall" /></th>
							<th>{{ Lang::get('core.btn_action') }}</th>
							<th width="50">{{ Lang::get('core.fr_mposition') }}</th>
							<th>{{ Lang::get('core.title') }}</th>
							<th>{{ Lang::get('core.link') }}</th>
							<th>{{ Lang::get('core.link_button') }}</th>
							<th>{{ Lang::get('core.sort') }}</th>
							<th>{{ Lang::get('core.status') }}</th>
							<th>{{ Lang::get('core.date') }}</th>
				            <th width="90" >{{ Lang::get('core.headerimage') }}</th>				
							
						  </tr>
			        </thead>

			        <tbody>        						
			            @foreach ($rowData as $row)
			                <tr>
								<td width="30"> {{ ++$i }} </td>
								<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->bannerID }}" />  </td>	
								<td>
										@if($access['is_edit'] ==1)
				<a  href="{{ URL::to('core/banners/update/'.$row->bannerID.'?return='.$return) }}" class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-pencil fa-2x "></i></a>
										@endif

								</td>
                                <td>{{ $row->position_name }}</td>
                                <td>{{ $row->title }}</td>
                                <td><a href='{{ $row->link }}' target="_blank">{{ $row->link }}</a></td>
                                <td>{{ $row->link_button }}</td>
                                <td>{{ $row->sort }}</td>
                                <td>{!! $row->status == 'enable' ? '<i class="text-success fa fa-check-circle fa-2x"></i>' : '<i class="text-danger fa fa-times-circle fa-2x"></i>'  !!}</td>
                                <td>{{ SiteHelpers::TarihFormat($row->updated_at) }}</td>
                                <td> {!! SiteHelpers::showUploadedFile($row->image,'/uploads/images/'.CNF_OWNER.'/') !!} </td>

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
	</div>  
</div>	


@stop