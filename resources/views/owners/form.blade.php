@extends('layouts.app')

@section('content')

	<section class="content-header">
		<h1> {{ $pageTitle }}</h1>
	</section>

	<div class="content">

		<div class="box box-primary">
			<div class="box-header-tools pull-left" >
				<a href="{{ url($pageModule.'?return='.$return) }}" class="tips"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left fa-2x"></i></a>
			</div>
			<div class="box-body">

				<ul class="parsley-error-list">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>

				{!! Form::open(array('url'=>'owners/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
				<div class="col-md-12">
					<fieldset><legend> Owners</legend>
						{!! Form::hidden('id', $row['id']) !!}
						{!! Form::hidden('current_domain', $row['domain']) !!}
						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_comname') }} </label>
							<div class="col-md-6">
								<input name="name" type="text" id="name" class="form-control input-sm" value="{{ $row['name'] }}" required />
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.domain') }} </label>
							<div class="col-md-6">
								<input name="domain" type="text" id="domain" class="form-control input-sm" value="{{ $row['domain'] }}" required/>
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.subdomain') }} </label>
							<div class="col-md-6">
								<input name="subdomain" type="text" id="subdomain" class="form-control input-sm" value="{{ $row['subdomain'] }}" required/>
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.address') }} </label>
							<div class="col-md-6">
								<textarea rows="3" class="form-control input-sm" name="address">{{ $row['address'] }}</textarea>
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.tel') }} </label>
							<div class="col-md-6">
								<input name="telephone" type="text" id="telephone" class="form-control input-sm" value="{{ $row['telephone'] }}" />
							</div>
						</div>

						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.fr_emailsys') }} </label>
							<div class="col-md-6">
								<input name="email" type="text" id="email" class="form-control input-sm" value="{{ $row['email'] }}" />
							</div>
						</div>

					@if($row['id'] =='')
						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.defaultpassword') }} </label>
							<div class="col-md-6">
							<input name="password" type="text" id="password" class="form-control input-sm" value="" required/> 
							</div>
						</div>
					@endif
			 
						<div class="form-group">
							<label for="ipt" class=" control-label col-md-4">{{ Lang::get('core.tempcolor') }} </label>
							<div class="col-md-6">
								<label class="radio">
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="green" @if(CNF_TEMPCOLOR =='green') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#55A79A" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="red" @if(CNF_TEMPCOLOR =='red') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#BE3E1D" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="blue" @if(CNF_TEMPCOLOR =='blue') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#00ADBB" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="purple" @if(CNF_TEMPCOLOR =='purple') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#b771b0" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="pink" @if(CNF_TEMPCOLOR =='pink') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#CC164D" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="orange" @if(CNF_TEMPCOLOR =='orange') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#e67e22" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="lime" @if(CNF_TEMPCOLOR =='lime') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#b1dc44" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="blue-dark" @if(CNF_TEMPCOLOR =='blue-dark') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#34495e" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="red-dark" @if(CNF_TEMPCOLOR =='red-dark') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#a10f2b" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="brown" @if(CNF_TEMPCOLOR =='brown') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#91633c" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="cyan-dark" @if(CNF_TEMPCOLOR =='cyan-dark') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#008b8b" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="yellow" @if(CNF_TEMPCOLOR =='yellow') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#FFC107" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="slate" @if(CNF_TEMPCOLOR =='slate') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#5D6D7E" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="olive" @if(CNF_TEMPCOLOR =='olive') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:olive" aria-hidden="true"></i></div>
									<div class="col-xs-4 hidden">
										<input type="radio" name="template_color" value="teal" @if(CNF_TEMPCOLOR =='teal') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:teal" aria-hidden="true"></i></div>
									<div class="col-xs-4">
										<input type="radio" name="template_color" value="green-bright" @if(CNF_TEMPCOLOR =='green-bright') checked @endif class="minimal-red"  />
										<i class="fa fa-toggle-on fa-4x" style="color:#2ECC71" aria-hidden="true"></i></div>
								</label>

							</div>
						</div>
					</fieldset>
				</div>

				<div style="clear:both"></div>


				<div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">
						<button type="submit" name="apply" class="btn btn-info btn-sm" > {{ Lang::get('core.sb_apply') }}</button>
						<button type="submit" name="submit" class="btn btn-primary btn-sm" > {{ Lang::get('core.sb_save') }}</button>
						<button type="button" onclick="location.href='{{ URL::to('owners?return='.$return) }}' " class="btn btn-danger btn-sm">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>

				</div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>

	<script type="text/javascript">
        $(document).ready(function() {


            $("#group").jCombo("{!! url('owners/comboselect?filter=tb_groups:group_id:name') !!}",
                {  selected_value : '{{ $row["group"] }}' });


            $('.removeMultiFiles').on('click',function(){
                var removeUrl = '{{ url("owners/removefiles?file=")}}'+$(this).attr('url');
                $(this).parent().remove();
                $.get(removeUrl,function(response){});
                $(this).parent('div').empty();
                return false;
            });

        });
	</script>
@stop