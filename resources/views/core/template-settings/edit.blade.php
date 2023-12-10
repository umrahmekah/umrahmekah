@extends('layouts.app')
@section('content')
    <section class="content-header">
      <h1>{{ Lang::get('core.m_template') }}</h1>
    </section>

  	<div class="content">
  		@include('components.message')
  		@include('components.error', ['errors' => $errors])
		@include('mmb.config.tab')	
		<div class="col-md-9">
			<div class="box box-primary">
				<div class="box-body"> 

					@include('core.template-settings.partials.' . str_slug(CNF_THEME, '-'))
					
			 	</div>
		 	</div>
		</div>
	</div>
	<div style="clear: both;"></div>
@endsection





