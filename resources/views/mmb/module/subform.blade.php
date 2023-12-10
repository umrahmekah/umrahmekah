@extends('layouts.app')

@section('content')

    <section class="content-header">
      <h1> Module <small>Configuration</small></h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('dashboard') }}"> Home</a></li>
        <li><a href="{{ url('mmb/module') }}"> Module</a></li>
        <li class="active">Sub Form</li>
         <li class="active">{{ $row->module_title }}</li>
      </ol>
    </section>

   <div class="content">

  
  
@if(Session::has('message'))
       {{ Session::get('message') }}
@endif



<ul>
  @foreach($errors->all() as $error)
    <li>{{ $error }}</li>
  @endforeach
</ul> 
<div class="box box-primary">
 <div class="box-header with-border"><h4> {{ $row->module_title }}  <small> :  Extend form ( Setting Child Form ) </small> </h4></div>
  <div class="box-body">  

    @include('mmb.module.tab',array('active'=>'subform'))

<ul class="nav nav-tabs" style="margin-bottom:10px;">
    <li  ><a href="{{ URL::to('mmb/module/form/'.$module_name)}}">Form Configuration </a></li>
    <li class="active" ><a href="{{ URL::to('mmb/module/subform/'.$module_name)}}">Sub Form </a></li>
  <li ><a href="{{ URL::to('mmb/module/formdesign/'.$module_name)}}">Form Layout</a></li>
</ul>    
  
    {!! Form::open(array('url'=>'mmb/module/savesubform/'.$module_name, 'class'=>'form-horizontal  ','id'=>'fSubf')) !!}

        <input  type='text' name='master' id='master'  value='{{ $row->module_name }}'  style="display:none;" /> 
        <input  type='text' name='module_id' id='module_id'  value='{{ $row->module_id }}'  style="display:none;" />

         <div class="form-group">
          <label for="ipt" class=" control-label col-md-4"> Subform Title <code>*</code></label>
          <div class="col-md-8">
            {!! Form::text('title', (isset($subform['title']) ? $subform['title']: null ),array('class'=>'form-control input-sm', 'placeholder'=>'' ,'required'=>'true')) !!} 
          </div> 
        </div>   

        <div class="form-group">
          <label for="ipt" class=" control-label col-md-4">Master Form Key <code>*</code></label>
        <div class="col-md-8">

              <select name="master_key" id="master_key" required="true" class="form-control input-sm"> 
              <?php foreach($fields as $field) {?>
                        <option value="<?php echo $field['field'];?>" <?php if(isset($subform['master_key']) && $subform['master_key'] == $field['field']) echo 'selected';?>><?php echo $field['field'];?></option>   
              <?php } ?>      
                    </select>   
         </div> 
        </div>  

        <div class="form-group">
          <label for="ipt" class=" control-label col-md-4"> Take <b>FORM</b> from Module </label>
        <div class="col-md-8">
              <select name="module" id="module" required="true" class="form-control input-sm">
              <option value="">-- Select Module --</option> 
              <?php foreach($modules as $module) {?>
                  <option value="<?php echo $module['module_name'];?>" <?php if(isset($subform['module']) && $subform['module'] == $module['module_name']) echo 'selected';?> ><?php echo $module['module_title'];?></option>
              <?php } ?>
                    </select>
         </div> 
        </div>  

         <div class="form-group">
          <label for="ipt" class=" control-label col-md-4">Sub Form Database <code>*</code></label>
        <div class="col-md-8">
          <select name="table" id="table" required="true" class="form-control input-sm">       
                    </select> 
         </div> 
        </div>       

         <div class="form-group">
          <label for="ipt" class=" control-label col-md-4">Sub Form Relation Key <code>*</code></label>
        <div class="col-md-8">
          <select name="key" id="key" required="true" class="form-control input-sm">
          </select> 
         </div> 
        </div>     

         <div class="form-group">
          <label for="ipt" class=" control-label col-md-4"></label>
        <div class="col-md-8">
          <button name="submit" type="submit" class="btn btn-primary"><i class="icon-bubble-check"></i> Save Master Detail </button>
          @if(isset($subform['master_key']))
          <a href="{{ url('mmb/module/subformremove/'.$module_name) }}" class="btn btn-danger"><i class="icon-cancel-circle2 "></i> Remove </a>
          @endif
         </div> 
        </div> 
      
     {!! Form::close() !!}
    </div>
  </div>
</div>    

 <script>
$(document).ready(function(){   
    $("#table").jCombo("{{ url('mmb/module/combotable') }}",
    {selected_value : "{{ (isset($subform['table']) ? $subform['table']: null ) }}" }); 
    $("#key").jCombo("{{ url('mmb/module/combotablefield') }}?table=",
    { parent  :  "#table" , selected_value : "{{ (isset($subform['key']) ? $subform['key']: null ) }}"}); 
});
</script> 

<script type="text/javascript">
  $(document).ready(function(){

    <?php echo MmbHelpers::sjForm('fSubf'); ?>

  })
</script>

@stop     