@extends('layouts.app')
@section('content')
    <section class="content-header">
      <h1>{{ Lang::get('core.sitesettings') }}</h1>
    </section>
  <div class="content">
	@if(Session::has('message'))
           {{ Session::get('message') }}   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		
@include('mmb.config.tab')	
<div class="col-md-9">
<div class="box box-primary">
	<div class="box-body"> 
 {!! Form::open(array('url'=>'core/config/billplz/', 'class'=>'form-horizontal')) !!}		

 		<div class="box-header with-border">
			<h3 class="box-title">{{ Lang::get('core.billplz_configuration') }}</h3>
		</div>
            
		 <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.billplz_api_key') }}</label>
			<div class="col-md-6">
			<input required name="billplz_api_key" type="text" id="billplz_api_key" class="form-control input-sm" value="{{ CNF_BILLPLZAPIKEY }}" />  
			 </div> 
		 </div>        
		 <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.billplz_signature_key') }}</label>
			<div class="col-md-6">
			<input required name="billplz_signature_key" type="text" id="billplz_signature_key" class="form-control input-sm" value="{{ CNF_BILLPLZSIGNATUREKEY }}" />  
			 </div> 
		 </div>  
		 <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.billplz_collection_id') }}</label>
			<div class="col-md-6">
			<input required name="billplz_collection_id" type="text" id="billplz_collection_id" class="form-control input-sm" value="{{ CNF_BILLPLZCOLLECTIONID }}" />  
			 </div> 
		 </div>  
        <div class="form-group">
		<label for="ipt" class=" control-label col-md-4">&nbsp;</label>
		<div class="col-md-8">
			<button class="btn btn-primary" type="submit"> {{ Lang::get('core.sb_savechanges') }}</button>
		 </div> 
	  </div>
    </div>	
	 </div>
 {!! Form::close() !!}
</div>
</div>
                  			<div style="clear: both;"></div>

@stop




