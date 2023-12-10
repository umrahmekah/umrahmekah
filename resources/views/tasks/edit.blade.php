@extends('layouts.app')

@section('content')

<div class="content"> 

    <div class="row">
        <div class="col-md-6">
            <h3>Tasks</h3>
        </div>
    </div>
    <div class="box box-primary">
        
      <div class="box-header with-border">
          <div class="box-header-tools pull-left" >
              <a href="/tasks" class="tips"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left fa-2x"></i></a>
          </div>
     </div> 
        
        <div class="box-body">
          <fieldset><legend>Edit Task</legend>
            <div class="col-md-12">
                {!! Form::open(array('url'=> $url, 'class'=>'form-horizontal')) !!}
                    {{ csrf_field() }}
            <div class="form-group  " >
                <div class="row">
                <label class="control-label col-md-4 text-right"> Task Name </label>
                <div class="col-md-5">
                    <input  type='text' value="{{$task->task_name}}"  name='task_name' id='task_name' class='form-control ' />
                </div>
            </div>
            </div>
            
            <div class="form-group">
                <div class="row">
                <label for="" class="control-label col-md-4 text-right"> Description </label>
                <div class="col-md-5">
                        <textarea name='description' id='description' class='form-control ' value="{{$task->description}}">{{$task->description}}</textarea>
                </div>
                </div>
            </div>
                
            <div class="form-group  " >
                <div class="row">
                <label for="" class="control-label col-md-4 text-right"> Assign User </label>
                <div class="col-md-4">
                         <select name='assigned_id' id='assigned_id' class='select2 ' >
                            <option>--Please Select--</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" @if($user->id == $task->assigned_id) selected @endif>{{ $user->username }}</option>
                             @endforeach
                         </select>
                </div>
                </div>
            </div>
                
            <div class="form-group  " >
                <div class="row">
                <label for="" class="control-label col-md-4 text-right"> Due Date </label>
                <div class="col-md-5">
                    <div class="input-group m-b" style="width:150px !important;">
                         <input class="form-control date" value="{{$task->due_date}}" name="due_date" type="text" selected>
                        <span class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></span>
                     </div>
                </div>
                </div>
            </div>
                
            <div class="form-group  " >
                <div class="row">
                <label for="" class="control-label col-md-4 text-right"> Departure Date </label>
                <div class="col-md-4">
                         <select name='tourdateID' rows='5' id='tourdateID' class='select2 '   >
                                @foreach($tours as $tour)
                                    @if($task->tour_date_id == $tour->tourID)
                                <option value="{{$task->tour_date_id}}"><b>{{$task->tour_date_id}}</b> - {{$tour->tour_name}}</option>
                                    @endif
                                @endforeach
                             @foreach($tourdates as $tourdate)
                                @foreach($tours as $tour)
                                    @if($tourdate->tourID == $tour->tourID)
                                <option value="{{$tourdate->tourdateID}}"><b>{{$tourdate->tour_code}}</b> - {{$tour->tour_name}}</option>
                                    @endif
                                @endforeach
                             @endforeach
                        
                         </select>
                </div>
                </div>
            </div>
                
            </div> 
            </fieldset>
            <div style="margin-top:2%">
                <label class="col-sm-4 text-right">&nbsp;</label>
                    <div class="col-sm-8">
                        <button href="/tasks" type="button" onclick="goBack()" class="btn btn-danger btn-sm "> Cancel </button>
                        <button type="submit" name="submit" class="btn btn-primary btn-sm" > Save </button>
                    </div>
                </div>
              </fieldset>
            {!! Form::close() !!}
        </div>  
        </div>
</div>


<script type="text/javascript">
	$(document).ready(function() {

    $('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    container: 'body'
    });

        $(".date").datetimepicker({
        format: 'yyyy-mm-dd',
        autoclose:true ,
        minView:2 ,
        startView:4
        });



	});
    function goBack() {
  window.history.back()
}
</script>

@stop