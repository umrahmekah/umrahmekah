@extends('layouts.app')

@section('content')
<head>
<style>
.btn {
  background-color: darkgreen;
  border: none;
  color: white;
  padding: 2px 4px;
  font-size: 12px;
  cursor: pointer;
}

/* Darker background on mouse-over */
.btn:hover {
color: white;
  background-color: green;
}
</style>
</head>

<div class="content"> 

    <div class="row">
        <div class="col-md-6">
            <h3>{{ Lang::get('core.tasks') }}</h3>
        </div>
    </div>
  <!--  <div class="box-header with-border">
		<div class="col-lg-3 col-xs-6">
        <div class="hpanel">
               <div class="panel-body">
                    <div class="stats-title pull-left">
                            <h4>Ongoing Tasks</h4>
                    </div>
                    <div class="stats-icon pull-right">
                            <i class="fa fa-history fa-4x"></i>
                    </div>
                    <div class="m-t-xl">
                        <h1 class="text-green">{{$ongoing_task}}</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
        <div class="hpanel">
               <div class="panel-body">
                    <div class="stats-title pull-left">
                            <h4>Completed Task</h4>
                    </div>
                    <div class="stats-icon pull-right">
                            <i class="fa fa-check-circle-o fa-4x"></i>
                    </div>
                    <div class="m-t-xl">
                        <h1 class="text-green">{{$completed_task}}</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
        <div class="hpanel">
               <div class="panel-body">
                    <div class="stats-title pull-left">
                            <h4>Cancelled Task</h4>
                    </div>
                    <div class="stats-icon pull-right">
                            <i class="fa fa-ban fa-4x"></i>
                    </div>
                    <div class="m-t-xl">
                        <h1 class="text-green"></h1>
                    </div>
                </div>
            </div>
        </div>
	</div> !-->
    
    <div class="box box-primary">
    	<div class="box-header with-border">
            
            <div class="box-header-tools pull-left" >
        
	   		  <a href="{{ url('tasks/create/')}}" class="tips text-green"  title="{{ Lang::get('core.btn_create') }} ">
			  <i class="fa fa-plus-square-o fa-2x"></i></a>
                
                <a href="#"  id="massDuplicate" class="tips text-blue" title="{{ Lang::get('core.btn_copy') }}">
                    <i class="fa fa-copy fa-2x" data-toggle="confirmation" data-title="{{Lang::get('core.rusure')}}"  data-content="{{ Lang::get('core.rusuredelete') }}" ></i>
                </a>
                <a href="#"  id="massDelete" class="tips text-red" title="{{ Lang::get('core.btn_remove') }}">
					<i class="fa fa-trash-o fa-2x delete_all" data-toggle="confirmation" data-title="{{Lang::get('core.rusure')}}"  data-content="{{ Lang::get('core.rusuredelete') }}" ></i>
                </a>
                
            </div>

            
    	</div>
    	<div class="box-body" >
            {!! Form::open(array('url'=>'tasks/massdelete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
     <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
    <table class="table table-striped table-bordered " id="{{ $pageModule }}Table">
        <thead>
			<tr>
				<th align="center" class="number" style="vertical-align: top"> No </th>
				<th align="center" style="vertical-align: top"> <input type="checkbox" class="checkall" id="master"></th>
				<th align="center" style="vertical-align: top">{{ Lang::get('core.task') }}</th>	
                <th align="center" style="vertical-align: top">{{ Lang::get('core.tourcodes') }}</th>	
				<th align="center" style="vertical-align: top">{{ Lang::get('core.duedate') }}</th>	
				<th align="center" style="vertical-align: top">{{ Lang::get('core.assigned_to') }}</th>	
				<th align="center" style="vertical-align: top">{{ Lang::get('core.assigned_by') }}</th>	
				<th align="center" style="vertical-align: top">{{ Lang::get('core.status') }}</th>	
				<th align="center" style="vertical-align: top">{{ Lang::get('core.btn_action') }}</th>
			  </tr>
        </thead>

        <tbody> @foreach($tasks as $task)
                <tr>
                    
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="checkbox" name="ids[]" value="{{$task->id}}"></td>
                    <td><a href="{{ url('tasks/show/'.$task->id.'')}}" class="tips" title="{{ Lang::get('core.btn_view') }}">{{$task->task_name}} </a></td>
                    @foreach($tourdates as $tourdate)
                    @if($tourdate->tourdateID == $task->tour_date_id)
                    <td><a href="{{ url('tourdates/show/'.$tourdate->tourdateID.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}">{{$tourdate->tour_code}} </a></td>
                    @endif
                    @endforeach
                    <td>{{$task->due_date}}</td>
                    @foreach($users as $user)
                    @if($user->id == $task->assigned_id)<td>{{$user->username}}</td>@endif
                    @endforeach
                     @foreach($users as $user)
                    @if($user->id == $task->assigner_id)<td>{{$user->username}}</td>@endif
                    @endforeach
                    @if($task->status == 0)<td width="90">
                    <span class="label label-block label-info label-sm">Ongoing</span>
                    </td>@endif
                    @if($task->status == 2)<td width="90">
                    <span class="label label-block label-success label-sm">Completed</span>
                    </td>@endif
                    <td> 
                        @if($task->status == 0)
                        <a  href="{{ url('tasks/completestatus/'.$task->id) }}" class="btn" ><i class="fa fa-check-circle-o fa-2x"></i></a>
                        @endif
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
		$('#MmbTable').attr('action','{{ url("tasks/multisearch")}}');
		$('#MmbTable').submit();
	});

	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
	});
	$('.checkall').on('ifChecked',function(){
		$('input[type="checkbox"]').iCheck('check');
	});
	$('.checkall').on('ifUnchecked',function(){
		$('input[type="checkbox"]').iCheck('uncheck');
	});	
    
    $('#massDelete').click(function() {
		var total = $('input[class="ids"]:checkbox:checked').length;
		if(confirm('Confirm delete?'))
		{
				$('#MmbTable').attr('action','{{ url("tasks/massdelete")}}');
				$('#MmbTable').submit();
		}
	})

    $('#massDuplicate').click(function() {
        var total = $('input[class="ids"]:checkbox:checked').length;
        if(confirm('Confirm duplicate?'))
        {
                $('#MmbTable').attr('action','{{ url("tasks/massduplicate")}}');
                $('#MmbTable').submit();
        }
    })
    
    // $('#massDelete').on('click', function (event) {
    //     var id = $(this).attr('data-id');
    //     if ($(this).text() === '+') {
    //         $.ajax({
    //             url: '/Tasks/massDelete',
    //             type: 'GET',
    //             dataType: 'json',
    //             data: '{"id":"' + id + '"}',
    //             contentType: 'application/json; charset=utf-8',
    //             success: function (data) {
    //                 $('.element_wrapper [data-id="' + id + '"]').html(data);
    //             },
    //             error: function (XMLHttpRequest, textStatus, errorThrown) {
    //                 alert("responseText=" + XMLHttpRequest.responseText + "\n textStatus=" + textStatus + "\n errorThrown=" + errorThrown);
    //             }
    //         });
    //         $(this).html('-');
    //     }
    //     else $(this).html('+');
    // });
    

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

@stop