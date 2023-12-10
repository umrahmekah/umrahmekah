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
	   		<a href="/tasks" class="tips" title="{{ Lang::get('core.btn_back') }}"><i class="fa  fa-arrow-left fa-2x"></i></a>
        </div>
            
    	</div>
    	<div class="box-body" >
            <div class="row">
            <div class="col-md-3">
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>{{ Lang::get('core.status') }}</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fa fa-check-circle-o fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            @if($task->status == 0)<h2 class="text-blue">Ongoing</h2>@endif
                            @if($task->status == 2)<h2 class="text-green">Completed</h2>@endif
                        </div>
                    </div>
                </div> 
        
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Due Date</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fa fa-calendar fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h2 class="text-green">{{$task->due_date}}</h2>
                        </div>
                    </div>
                </div>
                
                <div class="hpanel">
                    <div class="panel-body">
                        <div class="stats-title pull-left">
                            <h4>Created By</h4>
                        </div>
                        <div class="stats-icon pull-right">
                            <i class="fa fa-pencil-square-o fa-4x"></i>
                        </div>
                        <div class="m-t-xl">
                            <h2 class="text-green">{{$created_by->fullName}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="panel panel-success">
                    <div class="panel-heading"><h3>{{$task->task_name}}</h3></div>
                    <div class="panel-body">
                       
                             <table width="100%" style="margin:2%">
                            <tr>
                                <th width="20%"></th>
                                <th ></th>
                                <th></th>
                            </tr>
                            <tr>
                                <td><h5><b>Description</b></h5></td>
                                <td><b>: </b></td>
                                <td>{{$task->description}}</td>
                            </tr>
                            <tr>
                                <td><h5><b>Assigned to</b></h5></td>
                                <td><b>: </b></td>
                                <td>{{$assigned_to->fullName}}</td>
                            </tr>
                            <tr>
                                <td><h5><b>Assigned by</b></h5></td>
                                <td><b>: </b></td>
                                <td>{{$assigned_by->fullName}}</td>
                            </tr>
                            <tr>
                                <td><h5><b>Last updated</b></h5></td>
                                <td><b>: </b></td>
                                <td>{{$task->updated_at}}</td>
                            </tr>
                            <tr>
                                <td><h5><b>Created at</b></h5></td>
                                <td><b>: </b></td>
                                <td>{{$task->created_at}}</td>
                            </tr>
                            <tr>
                                <td><h5><b>Owner</b></h5></td>
                                <td><b>: </b></td>
                                <td>{{$owner->name}}</td>
                            </tr>
                            <tr>
                                <td><h5><b>Departure Date</b></h5></td>
                                <td><b>: </b></td>
                                    @foreach($tourdates as $tourdate)
                                      @if($tourdate->tourdateID == $task->tour_date_id)
                                <td><a href="{{ url('tourdates/show/'.$tourdate->tourdateID.'?return='.$return)}}" class="tips" title="{{ Lang::get('core.btn_view') }}">{{$tourdate->tour_code}} </a>
                                       @foreach($tours as $tour) 
                                        @if($departureDate->tourID == $tour->tourID)
                                        {{ $tour->tour_name }} 
                                        @endif 
                                       @endforeach</td>
                                     @endif
                                    @endforeach
                            </tr>
                        </table>
                        
                    </div>
                </div>

                    <table>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        <tr>
                            <td>
                            {!! Form::open(array('url'=>'tasks/delete/'.$task->id, 'class'=>'form-horizontal')) !!}
                            <button type="submit" name="" class="btn btn-danger btn-sm" >Delete</button> 
                            {!! Form::close() !!}
                            </td> 
                            <td>
                            <a href="{{ url('tasks/duplicate/'.$task->id) }}" class="btn btn-info btn-sm" style="color: white;">Duplicate</a> 
                            </td> 
                        @if($task->status != 2)
                            <td>
                            {!! Form::open(array('url'=>'tasks/edit/'.$task->id, 'class'=>'form-horizontal')) !!}
                            <button type="submit" name="" class="btn btn-info btn-sm" >Edit</button> 
                            {!! Form::close() !!}
                            </td>
                        @endif   
                        
                        @if($task->status == 0)
                            <td>
                            {!! Form::open(array('url'=>'tasks/completestatus/'.$task->id, 'class'=>'form-horizontal', 'method' => 'GET')) !!}
                            <button type="submit" name="" class="btn btn-success btn-sm" >Mark Completed</button> 
                            {!! Form::close() !!}
                            </td>
                        @endif
                        </tr>
                    </table>
            </div>
            
            </div>
    	</div>
    </div>	
</div>

@stop
  