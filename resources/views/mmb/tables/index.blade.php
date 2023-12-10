@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <div class="row bg-title">
        <!-- .page title -->
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">{{ $pageTitle }} <small> {{ $pageNote }} </small> </h4> 
        </div>
        <!-- /.page title -->
        <!-- .breadcrumb -->
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12"> 
	      <ol class="breadcrumb">
	        <li><a href="{{ url('dashboard') }}"> Home</a></li>
	        <li  class="active"> Database </li>
	      </ol>
        </div>
        <!-- /.breadcrumb -->
    </div>

    <div class="row">
        <div class="white-box">

        <div class="ajaxLoading"></div>

			<a href="{{ url('mmb/tables/tableconfig/')}}" class="btn btn-sm btn-success linkConfig tips" title="New Table "><i class="fa fa-plus"></i> </a>
			<a href="{{ url('mmb/tables/mysqleditor/')}}" class="btn btn-sm btn-info linkConfig tips" title="MySQL Editor"><i class="fa fa-pencil"></i>  </a>

		<div class="row">
			<div class="col-md-3">
				{!! Form::open(array('url'=>'mmb/tables/tableremove/', 'class'=>'form-horizontal','id'=>'removeTable' )) !!}
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								
								<th width="30"> <input type="checkbox" class="checkall i-checks-all " /></th>
								<th> Table Name </th>
								<th width="50"> Action </th>
							</tr>
						</thead>
						<tbody>
						@foreach($tables as $table)
							<tr>
								<td><input type="checkbox" class="ids  i-checks" name="id[]" value="{{ $table }}" /> </td>
								<td><a href="{{ URL::TO('mmb/tables/tableconfig/'.$table)}}" class="linkConfig" > {{ $table }}</a></td>
								<td>
								<a href="javascript:void(0)" onclick="droptable()" class="btn btn-xs btn-danger"><i class="fa fa-trash-o"></i></a>
								</td>
							</tr>
						@endforeach
						</tbody>
					
					</table>
				
				</div>
				{!! Form::close() !!}		
			</div>
			<div class="col-md-9">
				
				<div class="tableconfig" style="background:#fff; padding:10px; min-height:300px; border:solid 1px #ddd;">

				</div>

			</div>

		</div>
			
	 	</div>
    </div>


</div>



  <script type="text/javascript" src="{{ asset('mmb/js/simpleclone.js') }}"></script>

<script type="text/javascript">
$(document).ready(function(){

	$('.linkConfig').click(function(){
		$('.ajaxLoading').show();
		var url =  $(this).attr('href');
		$.get( url , function( data ) {
			$( ".tableconfig" ).html( data );
			$('.ajaxLoading').hide();
			
			
		});
		return false;
	});
});

function droptable()
{
	if(confirm('are you sure remove selected table(s) ?'))
	{
		$('#removeTable').submit();
	} else {
		return false;
	}
}

</script>
@endsection