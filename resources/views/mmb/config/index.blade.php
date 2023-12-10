@extends('layouts.app')
@section('content')
    <section class="content-header">
      <h1>{{ Lang::get('core.generalsettings') }}</h1>
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
		 {!! Form::open(array('url'=>'core/config/save/', 'class'=>'form-horizontal row', 'files' => true)) !!}

		 <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_comname') }} </label>
			<div class="col-md-6">
			<input name="name" type="text" id="name" class="form-control input-sm" value="{{ CNF_COMNAME }}" />  
			 </div> 
		  </div>  
            
            <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.address') }} </label>
<div class="col-md-6">
            <textarea rows="3" class="form-control input-sm" name="address">{{ CNF_ADDRESS }}</textarea>
			 </div> 
		  </div>
            
            <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.tel') }} </label>
<div class="col-md-6">
			<input name="telephone" type="text" id="telephone" class="form-control input-sm" value="{{ CNF_TEL }}" />  
			 </div> 
		  </div>
            
            <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_emailsys') }} </label>
<div class="col-md-6">
			<input name="email" type="text" id="email" class="form-control input-sm" value="{{ CNF_EMAIL }}" /> 
			 </div> 
		  </div>
          <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4"><i class="fa fa-facebook-official fa-2x" aria-hidden="true"></i> </label>
<div class="col-md-6">
			<input name="facebook" type="text" id="facebook" class="form-control input-sm" placeholder="https://www.facebook.com/yourcompanyname" value="{{ CNF_FACEBOOK }}" /> 
			 </div> 
		  </div>
          <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4"><i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i>
</label>
<div class="col-md-6">
			<input name="twitter" type="text" id="twitter" class="form-control input-sm" placeholder="https://www.twitter.com/yourcompanyname" value="{{ CNF_TWITTER }}" /> 
			 </div> 
		  </div>
          <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4"><i class="fa fa-instagram fa-2x" aria-hidden="true"></i>
 </label>
<div class="col-md-6">
			<input name="instagram" type="text" id="instagram" class="form-control input-sm" placeholder="https://www.instagram.com/yourcompanyname" value="{{ CNF_INSTAGRAM }}" /> 
			 </div> 
		  </div>

            <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4"><i class="fa fa-tripadvisor fa-2x" aria-hidden="true"></i></label>
<div class="col-md-6">
			<input name="tripdavisor" type="text" id="tripdavisor" class="form-control input-sm" placeholder="https://www.tripadvisor.com/....." value="{{ CNF_TRIPADVISOR }}" /> 
			 </div> 
		  </div>		     
		   <div class="form-group">
		    <label  class=" control-label col-md-4">{{ Lang::get('core.logo') }}</label>
<div class="col-md-6">
    <div class="btn btn-primary btn-file"><i class="fa fa-picture-o fa-lg"></i>  {{ Lang::get('core.fr_backendlogo') }} 
				<input type="file" name="logo">
				
						 </div>
				<p> <i>{{ Lang::get('core.imagedimension') }} 400px * 50px </i> </p>
				<div>
				 	@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
                    
				 	<img src="{{ asset('/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" alt="{{ CNF_COMNAME }}" width="200" />
				 	@else
					<img src="{{ asset('mmb/images/logo.png')}}" alt="{{ CNF_COMNAME }}" width="200" />
					@endif	
				</div>				
			 </div> 
		  </div> 
      <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.sitetagline') }}</label>
<div class="col-md-6">
			<input name="tagline" type="text" id="tagline" class="form-control input-sm"  value="{{ CNF_TAGLINE }}" /> 
			 </div> 
		  </div> 
      <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.description') }}</label>
<div class="col-md-6">
            <textarea rows="3" class="form-control input-sm" name="description">{{ CNF_DESCRIPTION }}</textarea>
			 </div> 
		  </div>
      
      		   <div class="form-group">
		    <label  class=" control-label col-md-4">{{ Lang::get('core.headerimage') }}</label>
<div class="col-md-6">
    <div class="btn btn-primary btn-file"><i class="fa fa-picture-o fa-lg"></i>  {{ Lang::get('core.headerimage') }} 
				<input type="file" name="headerimage">
				
						 </div>
				<p> <i>{{ Lang::get('core.imagedimension') }} 1200px * 450px </i> </p>
				<div>
				 	@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_HEADERIMAGE) && CNF_HEADERIMAGE !='')
                    
				 	<img src="{{ asset('/uploads/images/'.CNF_OWNER.'/'.CNF_HEADERIMAGE)}}" alt="{{ CNF_HEADERIMAGE }}" width="200"/>
				 	@else
					<img src="{{ asset('uploads/images/header.jpg')}}" alt="{{ CNF_HEADERIMAGE }}" width="200" />
					@endif	
				</div>				
			 </div> 
		  </div> 

      <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">&nbsp;</label>
<div class="col-md-6">
				<button class="btn btn-primary" type="submit">{{ Lang::get('core.sb_savechanges') }} </button>
			 </div> 
		  </div>

		</div>  
		 {!! Form::close() !!}
	</div>
	</div>	 
</div>



                  			<div style="clear: both;"></div>





@stop