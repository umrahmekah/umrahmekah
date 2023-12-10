@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>
            Module Management
        </h1>
    </section>

    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <ul class="nav nav-tabs" style="margin-bottom:10px;">
                    <li class="dropdown pull-left active">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-superpowers fa-lg text-green" aria-hidden="true"></i>
                        {{ Lang::get('core.modulelist') }}
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                        <?php $md = DB::table('tb_module')->where('module_type','!=','core')->orderBy('module_title','ASC')->get();
                            foreach($md as $m) { ?>
                                <li><a href="{{ url('mmb/module/permission/'.$m->module_name)}}"> {{ $m->module_title}}</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
                <div class="row">
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <a href="module/create">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-plus"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ Lang::get('core.btn_create') }} {{ Lang::get('core.t_module') }}</span>
                                    <span class="info-box-number">Data</span>

                                    <div class="progress">
                                        <div class="progress-bar" style="width: 70%"></div>
                                    </div>
                                    <span class="progress-description">
                                    {{ Lang::get('core.fr_createmodule') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <div class="info-box bg-green" onclick="$('.unziped').toggle()">
                            <span class="info-box-icon"><i class="fa fa-download"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">{{ Lang::get('core.btn_install') }} {{ Lang::get('core.t_module') }}</span>
                                <span class="info-box-number">Data</span>

                                <div class="progress">
                                    <div class="progress-bar" style="width: 70%"></div>
                                    </div>
                                <span class="progress-description">
                                {{ Lang::get('core.fr_installmodule') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-xs-12">
                        <a href="tables">
                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-database "></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">{{ Lang::get('core.btn_database') }} </span>
                                    <span class="info-box-number">Data</span>

                                    <div class="progress">
                                        <div class="progress-bar" style="width: 70%"></div>
                                    </div>
                                    <span class="progress-description">
                                    {{ Lang::get('core.fr_managedatabase') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>


                @if(Session::has('message'))
                    {{ Session::get('message') }}
                @endif
                <div class="white-bg p-sm m-b unziped" style=" border:solid 1px #ddd; display:none; padding: 10px 5px 30px">
                    {!! Form::open(array('url'=>'mmb/module/install/', 'class'=>'breadcrumb-search','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
                        <h3>{{ Lang::get('core.uploadmoduletoinstall') }} </h3>
                        <p>  <input type="file" name="installer" required style="float:left;">  <button type="submit" class="btn btn-primary btn-xs" style="float:left;"  ><i class="icon-upload"></i> {{ Lang::get('core.btn_install') }}</button></p>
                    </form>
                    <div class="clr"></div>
                </div>

                <ul class="nav nav-tabs" style="margin-bottom:10px;">
                    <li @if($type =='addon') class="active" @endif><a href="{{ URL::to('mmb/module')}}"> {{ Lang::get('core.tab_installed') }}  </a></li>
                    <li @if($type =='core') class="active" @endif><a href="{{ URL::to('mmb/module?t=core')}}"> {{ Lang::get('core.tab_core') }}</a></li>
                </ul>

                @if($type =='core')

                    <div class="infobox infobox-info fade in">
                        <button type="button" class="close" data-dismiss="alert"> x </button>
                        <p>{!! Lang::get('core.rebuildnotice') !!}</p>
                    </div>

                @endif

                <div class="table-responsive"  style="min-height:400px; padding-bottom: 200px;">


                    {!! Form::open(array('url'=>'mmb/module/package#', 'class'=>'form-horizontal' ,'ID' =>'MmbTable' )) !!}

                    @if(count($rowData) >=1)
                        <table class="table table-hover  ">
                            <thead>
                            <tr>
                                <th>{{ Lang::get('core.action') }}</th>
                                <th><input type="checkbox" class="checkall" /></th>
                                <th>{{ Lang::get('core.t_module') }}</th>
                                <th>{{ Lang::get('core.type') }}</th>

                                <th>{{ Lang::get('core.controller') }}</th>
                                <th>{{ Lang::get('core.database') }}</th>
                                <th>PRI</th>
                                <th>{{ Lang::get('core.created') }}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($rowData as $row)
                                <tr>
                                    <td>
                                        <div class="btn-group ">
                                            <button class="btn btn-success btn-sm  btn-outline dropdown-toggle" data-toggle="dropdown">
                                                <I class="ti-align-justify"></I> <span class="caret"></span>
                                            </button>
                                            <ul  class="dropdown-menu icons-right " style="z-index: 999999">
                                                @if($type != 'core')
                                                    <li><a href="{{ URL::to($row->module_name)}}"> {{ Lang::get('core.btn_view') }} {{ Lang::get('core.t_module') }} </a></li>
                                                    <li><a href="{{ URL::to('mmb/module/duplicate/'.$row->module_id)}}" onclick="MmbModal(this.href,'Duplicate/Clone Module'); return false;" > {{ Lang::get('core.duplicateclone') }} </a></li>
                                                @endif
                                                <li><a href="{{ URL::to('mmb/module/config/'.$row->module_name)}}"> {{ Lang::get('core.btn_edit') }}</a></li>

                                                @if($type != 'core')
                                                    <li><a href="javascript://ajax" onclick="MmbConfirmDelete('{{ URL::to('mmb/module/destroy/'.$row->module_id)}}')"> {{ Lang::get('core.btn_remove') }}</a></li>
                                                    <li class="divider"></li>
                                                    <li><a href="{{ URL::to('mmb/module/rebuild/'.$row->module_id)}}"> {{ Lang::get('core.rebuildallcodes') }}</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                    <td>

                                        <input type="checkbox" class="ids" name="id[]" value="{{ $row->module_id }}" /> </td>
                                    <td>{{ $row->module_title }} </td>
                                    <td>{{ $row->module_type }} </td>
                                    <td>{{ $row->module_name }} </td>

                                    <td>{{ $row->module_db }} </td>
                                    <td>{{ $row->module_db_key }} </td>
                                    <td>{{ $row->module_created }} </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        {!! Form::close() !!}
                </div>
                @else

                    <p class="text-center" style="padding:50px 0;">{{ Lang::get('core.norecord') }}
                        <br /><br />
                        <a href="{{ URL::to('mmb/module/create')}}" class="btn btn-success "><i class="fa fa-plus"></i> {{ Lang::get('core.fr_createmodule') }} </a>
                    </p>
                @endif
            </div>

        </div>
        </div>
    </div>
<style type="text/css">
	.info-box {cursor: pointer;}
    .dropdown-menu {
    max-height: 300px;
    overflow-y: auto;
    overflow-x: hidden;
}
</style>
  <script language='javascript' >
  jQuery(document).ready(function($){
    $('.post_url').click(function(e){
      e.preventDefault();
      if( ( $('.ids',$('#MmbTable')).is(':checked') )==false ){
        alert( $(this).attr('data-title') + " not selected");
        return false;
      }
      $('#MmbTable').attr({'action' : $(this).attr('href') }).submit();
    });


  })
  </script>

@stop