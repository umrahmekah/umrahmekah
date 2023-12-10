@extends('layouts.app')

@section('content')
  <section class="content-header">
      <h1> Module <small>Configuration</small></h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
        <li><a href="{{ url('mmb/module') }}"> Module</a></li>
        <li class="active">JSON  </li>
         <li class="active">{{ $row->module_title }}</li>
      </ol>
  </section>   

  <div class="content">

 

  @if(Session::has('message'))
       {{ Session::get('message') }}
  @endif
<div class="box box-primary">
 <div class="box-header with-border"> <h4> {{ $row->module_title }}<small>  : MySQL Editor ( Edit SQL Statement ) </small></h4></div>
 <div class="box-body ">

  @include('mmb.module.tab',array('active'=>'json','type'=>  $type ))
  
 {!! Form::open(array('url'=>'mmb/module/savejson/'.$module_name, 'class'=>'form-vertical ' ,'id'=>'JSON' , 'parsley-validate'=>'','novalidate'=>' ')) !!}


<div class="form-group">
<label for="ipt" class=" control-label">JSON Encoded</label>
  <textarea name="json_encoded" rows="15" id="json_encoded" class="tab_behave form-control"  placeholder="" >{{ $json_encoded }}</textarea>
</div>  

<div class="form-group">
<label for="ipt" class=" control-label">JSON Decoded</label>
  <textarea name="json_decoded" rows="15" id="json_decoded" class="tab_behave form-control"  placeholder="" >{{ $json_decoded }}</textarea>
</div>  

<div class="form-group hidden">
<label for="ipt" class=" control-label"></label>
<button type="submit" class="btn btn-primary"> Save SQL </button>
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