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
 {!! Form::open(array('url'=>'core/config/login/', 'class'=>'form-horizontal')) !!}		

 		<div class="box-header with-border">
			<h3 class="box-title">{{ Lang::get('core.displaysetting') }}</h3>
		</div>

		@if(Session::get('gid') =='1') 
 		  <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.developmentmode') }} </label>
<div class="col-md-6">
				<div class="checkbox">
					<input name="mode" type="checkbox" id="mode" value="1"
					@if (defined('CNF_MODE') &&  CNF_MODE =='production') checked @endif
					  />  {{ Lang::get('core.production') }}
				</div>
				<small> If you need to debug mode , please unchecked this option </small>	
			 </div> 
		  </div>
		@endif
        <div class="form-group">
			<label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.maintenancemode') }}</label>	
			<div class="col-md-6">
				<label class="checkbox">
				<input type="checkbox" name="maintenance" value="ON"  @if(CNF_MAINTENANCE =='ON') checked @endif class="minimal-red"  /> 
				</label>			
			</div>
		</div>
        <div class="form-group ">
			<label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.fr_allowfrontend') }} </label>	
			<div class="col-md-6">
				<label class="checkbox">
				<input type="checkbox" name="front" value="false" @if(CNF_FRONT =='true') checked @endif class="minimal-red"  /> 
				</label>			
			</div>
		</div>
        <div class="form-group ">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_fronttemplate') }}</label>
			<div class="col-md-3">
				<select class="select2" name="theme">
					@foreach(SiteHelpers::themeOption() as $t)
						<option value="{{  $t['folder'] }}"
						@if(CNF_THEME ==$t['folder']) selected @endif
						>{{  $t['name'] }}</option>
					@endforeach
				</select>
			 </div> 
		</div>
		<div class="form-group ">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_bookingform') }}</label>
			<div class="col-md-3">
				<select class="select2" name="booking_form">
					<option value="1" @if(CNF_BOOKINGFORM == 1) selected @endif>Full form</option>
					<option value="2" @if(CNF_BOOKINGFORM == 2) selected @endif>Simple form</option>
				</select>
			 </div> 
		</div>
		<div class="form-group">
			<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.tempcolor') }} </label>	
			<div class="col-md-6">
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="red-dark" @if(CNF_TEMPCOLOR =='red-dark') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#a10f2b" aria-hidden="true"></i></div>
	            <div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="red" @if(CNF_TEMPCOLOR =='red') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#BE3E1D" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="pink" @if(CNF_TEMPCOLOR =='pink') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#CC164D" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="purple" @if(CNF_TEMPCOLOR =='purple') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#b771b0" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="indigo" @if(CNF_TEMPCOLOR =='indogo') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#3b5998" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="blue-dark" @if(CNF_TEMPCOLOR =='blue-dark') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#34495e" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="blue" @if(CNF_TEMPCOLOR =='blue') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#00ADBB" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="cyan-dark" @if(CNF_TEMPCOLOR =='cyan-dark') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#00BCD4" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="teal" @if(CNF_TEMPCOLOR =='teal') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#67eaca" aria-hidden="true"></i></div>
	            <div class="col-xs-4 template-color">
					<input type="radio" name="template_color" style="top:-16px" value="green" @if(CNF_TEMPCOLOR =='green') checked @endif class="minimal-red"  /> 
	                <i class="fa fa-toggle-on fa-4x" style="color:#55A79A" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="green-bright" @if(CNF_TEMPCOLOR =='green-bright') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#2ECC71" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="lime" @if(CNF_TEMPCOLOR =='lime') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#b1dc44" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="yellow" @if(CNF_TEMPCOLOR =='yellow') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#FFC107" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="orange" @if(CNF_TEMPCOLOR =='orange') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#e67e22" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="brown" @if(CNF_TEMPCOLOR =='brown') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#91633c" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="olive" @if(CNF_TEMPCOLOR =='olive') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:olive" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="slate" @if(CNF_TEMPCOLOR =='slate') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#5D6D7E" aria-hidden="true"></i></div>
				<div class="col-xs-4 template-color">
					<input type="radio" name="template_color" value="grey" @if(CNF_TEMPCOLOR =='grey') checked @endif class="minimal-red"  /> 
					<i class="fa fa-toggle-on fa-4x" style="color:#212121" aria-hidden="true"></i></div>
				
          	</div>	
		</div>		
        <div class="form-group">
			<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.showhelp') }}</label>	
			<div class="col-md-6">
				<label class="checkbox">
				<input type="checkbox" name="show_help" value="ON"  @if(CNF_SHOWHELP =='ON') checked @endif class="minimal-red"  /> 
				</label>			
			</div>
		</div>

 		<div class="box-header with-border">
			<h3 class="box-title">{{ Lang::get('core.homepagesetting') }}</h3>
		</div>
		<div class="form-group">
			<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.showtour') }}</label>	
			<div class="col-md-6">
				<label class="checkbox">
				<input type="checkbox" name="show_tour" value="ON"  @if(CNF_SHOWTOUR =='ON') checked @endif class="minimal-red"  /> 
				</label>			
			</div>
		</div>
		<div class="form-group">
			<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.showtestimonial') }}</label>	
			<div class="col-md-6">
				<label class="checkbox">
				<input type="checkbox" name="show_testimonial" value="ON"  @if(CNF_SHOWTESTIMONIAL =='ON') checked @endif class="minimal-red"  /> 
				</label>			
			</div>
		</div>

 		<div class="box-header with-border">
			<h3 class="box-title">{{ Lang::get('core.accountsetting') }}</h3>
		</div>
        <div class="form-group">
			<label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.fr_allowregistration') }} </label>	
			<div class="col-md-6">
				<label class="checkbox">
				<input type="checkbox" name="registration" value="true"  @if(CNF_REGIST =='true') checked @endif class="minimal-red"  /> 
				</label>			
			</div>
		</div>
		@if(Session::get('gid') =='1')
        <div class="form-group">
			<label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.fr_registrationdefault') }}  </label>
			<div class="col-md-3">
				<div >
					<select class="form-control" name="group">
						@foreach($groups as $group)
						<option value="{{ $group->group_id }}"
						 @if(CNF_GROUP == $group->group_id ) selected @endif
						>{{ $group->name }}</option>
						@endforeach
					</select>
				</div>				
			</div>	
		 </div> 
		@endif
        <div class="form-group">
			<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_registration') }} </label>	
			<div class="col-md-6">
				<label class="radio">
				<input type="radio" name="activation" value="auto" @if(CNF_ACTIVATION =='auto') checked @endif class="minimal-red"  /> 
				{{ Lang::get('core.fr_registrationauto') }}
				</label>
				
				<label class="radio">
				<input type="radio" name="activation" value="manual" @if(CNF_ACTIVATION =='manual') checked @endif class="minimal-red"  /> 
				{{ Lang::get('core.fr_registrationmanual') }}
				</label>

				<label class="radio">
				<input type="radio" name="activation" value="confirmation" @if(CNF_ACTIVATION =='confirmation') checked @endif class="minimal-red"  />
				{{ Lang::get('core.fr_registrationemail') }}
				</label>								
			</div>	
		</div>        
 		<div class="form-group">
			<label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.captcha') }} </label>	
			<div class="col-md-6">
				<label class="checkbox">
				<input type="checkbox" name="captcha" value="false" @if(CNF_RECAPTCHA =='true') checked @endif class="minimal-red"  /> 
				</label>	
			</div>
		</div>

 		<div class="box-header with-border">
			<h3 class="box-title">{{ Lang::get('core.localisationsetting') }}</h3>
		</div>  
        <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.fr_multilanguage') }} <br />  </label>
			<div class="col-md-6">
				<div class="checkbox">
					<input name="multi_language" type="checkbox" id="multi_language" value="1" class="minimal-red" 
					@if(CNF_MULTILANG ==1) checked @endif
					  />  {{ Lang::get('core.fr_enable') }} 
				</div>	
			</div> 
		</div>	
		<div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_mainlanguage') }} </label>
			<div class="col-md-3">
				<select class="select2" name="default_language">
				@foreach(SiteHelpers::langOption() as $lang)
					<option value="{{  $lang['folder'] }}"
					@if(CNF_LANG ==$lang['folder']) selected @endif
					>{{  $lang['name'] }}</option>
				@endforeach
				</select>
			 </div> 
		  </div>  
		<div class="form-group " >
			<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.currency') }} </label>
			<div class="col-md-3">
				<select name='default_currency' rows='5' id='default_currency' class='select2 '   ></select>
			</div>
		</div>		
		<div class="form-group">
		    <label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.fr_dateformat') }} </label>
			<div class="col-md-6">
				<select class="form-control" name="date">
					<?php $dates = array(
							'Y-m-d'=>' ( Y-m-d ) '. \Lang::get('core.fr_mexample').' : '.date('Y-m-d'),
							'Y/m/d'=>' ( Y/m/d ) '. \Lang::get('core.fr_mexample').' : '.date('Y/m/d'),
							'd-m-y'=>' ( D-M-Y ) '. \Lang::get('core.fr_mexample').' : '.date('d-m-y'),
							'd/m/y'=>' ( D/M/Y ) '. \Lang::get('core.fr_mexample').' : '.date('d/m/y'),
							'm-d-y'=>' ( m-d-Y ) '. \Lang::get('core.fr_mexample').' : '.date('m-d-Y'),
							'm/d/y'=>' ( m/d/Y ) '. \Lang::get('core.fr_mexample').' : '.date('m/d/Y'),
							'd M Y'=>' ( d M Y ) '. \Lang::get('core.fr_mexample').' : '.date('d M Y'),
							'M d Y'=>' ( M d Y ) '. \Lang::get('core.fr_mexample').' : '.date('M d Y'),
						  );
					foreach($dates as $key=>$val) {?>
						<option value="{{  $key }}" @if(defined('CNF_DATE') && CNF_DATE ==$key) selected @endif >{{  $val }}</option>
					<?php } ?>
				</select>
			</div> 
		</div>

 		<div class="box-header with-border">
			<h3 class="box-title">{{ Lang::get('core.serversetting') }}</h3>
		</div>

            
		@if(Session::get('gid') =='1')
        <div class="form-group">
			<label for="ipt" class=" control-label col-md-4">  {{ Lang::get('core.fr_emailsys') }}  </label>	
<div class="col-md-6">
					
					<label class="radio">
					<input type="radio" name="mail" value="phpmail" class="minimal-red"  @if(defined('CNF_MAIL') && CNF_MAIL =='phpmail') checked @endif /> 
					PHP MAIL System
					</label>
					
					<label class="radio">
					<input type="radio" name="mail" value="swift" class="minimal-red"  @if(defined('CNF_MAIL') && CNF_MAIL =='swift') checked @endif /> 
					SWIFT Mailer <a href="javascript:void(0)" onclick="MmbModal('{{ url('core/template/swift') }}','SwiftMailer Settings')"> ( Configuration Required) </a> 
					</label>			
			</div>
		</div>	
		@endif
        <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.metakey') }} </label>
<div class="col-md-6">
				<textarea class="form-control input-sm" placeholder="{{ Lang::get('core.keywords') }}" name="meta_keyword">{{ CNF_METAKEY }}</textarea>
			 </div> 
		  </div> 

		   <div class="form-group">
		    <label  class=" control-label col-md-4">{{ Lang::get('core.metadescription') }}</label>
<div class="col-md-6">
				<textarea class="form-control input-sm" placeholder="{{ Lang::get('core.sitedescription') }}" name="meta_description">{{ CNF_METADESC }}</textarea>
			 </div> 
		  </div>
  <div class="form-group hidden">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.googleapicalendar') }}</label>
			<div class="col-md-6">
			<input name="cnf_apikey" type="text" id="cnf_apikey" placeholder="AIzaSyA8D5123adQpT390j46leZbo7aw3J6SBFs" class="form-control input-sm" value="{{ CNF_APIKEY }}" />  
			 </div> 
		  </div>  
            
		 <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.googlecalendarid') }}</label>
			<div class="col-md-6">
			<input name="google_calendar" type="text" id="google_calendar" placeholder="en.malaysia#holiday@group.v.calendar.google.com" class="form-control input-sm" value="{{ CNF_CALENDARID }}" />  
			 </div> 
		  </div>        
		 <div class="form-group">
		    <label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.googleanalytics') }}</label>
			<div class="col-md-6">
			<input name="google_analytics" type="text" id="google_analytics" placeholder="UA-XXXXX-X" class="form-control input-sm" value="{{ CNF_ANALYTICS }}" />  
			 </div> 
		  </div>  
		@if(Session::get('gid') =='1')        
        <div class="form-group">
			<label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.fr_restrictip') }}<p><small><i>
								
								{{ Lang::get('core.fr_restrictipsmall') }}  <br />
								{{ Lang::get('core.fr_restrictipexam') }} : <code> 192.116.134.21 , 194.111.606.21 </code>
							</i></small></p> </label>	
<div class="col-md-6">
							<textarea rows="3" class="form-control" name="restrict_ip">{{ CNF_RESTRICIP }}</textarea>
			</div>
		</div>
		@endif
		@if(Session::get('gid') =='1')	  
        <div class="form-group">
			<label for="ipt" class=" control-label col-md-4"> {{ Lang::get('core.fr_allowip') }}	
							<p><small><i>
								
								{{ Lang::get('core.fr_allowipsmall') }}  <br />
								{{ Lang::get('core.fr_allowipexam') }} : <code> 192.116.134.21 , 194.111.606.21 </code>
							</i></small></p></label>	
<div class="col-md-6">
							<textarea rows="3" class="form-control" name="allow_ip">{{ CNF_ALLOWIP }}</textarea>
    						<p> {{ Lang::get('core.fr_ipnote') }} </p>

			</div>
		</div>
		@endif
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

 	<script type="text/javascript">
        $(document).ready(function() {
            $("#default_currency").jCombo("{!! url('currency/comboselect?filter=def_currency:currencyID:currency_sym|symbol&limit=WHERE:status:=:1') !!}",
                {  selected_value : '{{ CNF_CURRENCY }}' });
        });
	</script>

@stop




