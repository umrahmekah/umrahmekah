@extends('layouts.app')

@section('content')
  <section class="content-header">
      <h1> {!! Lang::get('core.moduleconfiguration') !!}</h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
        <li><a href="{{ url('mmb/module') }}"> Module</a></li>
        <li class="active">SQL  </li>
         <li class="active">{{ $row->module_title }}</li>
      </ol>
  </section>   

  <div class="content">

 

  @if(Session::has('message'))
       {{ Session::get('message') }}
  @endif
<div class="box box-primary">
 <div class="box-header with-border"> <h4> {{ $row->module_title }}<small>  : {{ Lang::get('core.modulesqlconfiguration') }} </small></h4></div>
 <div class="box-body ">

  @include('mmb.module.tab',array('active'=>'sql','type'=>  $type ))
  
 {!! Form::open(array('url'=>'mmb/module/savesql/'.$module_name, 'class'=>'form-vertical ' ,'id'=>'SQL' , 'parsley-validate'=>'','novalidate'=>' ')) !!}
 <div class="infobox infobox-info fade in">
  <button type="button" class="close" data-dismiss="alert"> x </button>  
  <p> {!! Lang::get('core.modulesqlnotice') !!} </p> 
</div>  


<div class="form-group">
<label for="ipt" class=" control-label">SQL SELECT & JOIN</label>
  <textarea name="sql_select" rows="5" id="sql_select" class="tab_behave form-control"  placeholder="SQL Select & Join Statement" >{{ $sql_select }}</textarea>
</div>  

<div class="form-group">
<label for="ipt" class=" control-label">SQL WHERE CONDITIONAL</label>
  <textarea name="sql_where" rows="2" id="sql_where" class="form-control" placeholder="SQL Where Statement" >{{ $sql_where }}</textarea>
</div> 

<div class="infobox infobox-danger fade in">
  <button type="button" class="close" data-dismiss="alert"> x </button>  
  <p> {!! Lang::get('core.modulesqlwarning') !!}   </p>  
</div>  
    
  

<div class="form-group">
<label for="ipt" class=" control-label">SQL GROUP</label>
 <textarea name="sql_group" rows="2" id="sql_group" class="form-control"   placeholder="SQL Grouping Statement" >{{ $sql_group }}</textarea>

</div> 
<div class="form-group">
<label for="ipt" class=" control-label"></label>
<button type="submit" class="btn btn-primary"> {{ Lang::get('core.savesql') }} </button>
</div>  

 <input type="hidden" name="module_id" value="{{ $row->module_id }}" />
 <input type="hidden" name="module_name" value="{{ $row->module_name }}" />
 
 {!! Form::close() !!}
 </div>
 </div>
</div>  
  
</div>  
<script type="text/javascript">
  $(document).ready(function(){

    <?php echo MmbHelpers::sjForm('SQL'); ?>

  })
</script> 
@stop