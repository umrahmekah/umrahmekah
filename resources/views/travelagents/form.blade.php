@extends('layouts.app')

@section('content')
	<section class="content-header">
		<h1> {{ Lang::get('core.travelagents') }}</h1>
	</section>

	<div class="content">
		<div class="box box-primary">
			<div class="box-header with-border">
				<div class="box-header-tools pull-left" >
					<a href="{{ url($pageModule.'?return='.$return) }}" class="tips"  title="{{ Lang::get('core.btn_back') }}" ><i class="fa  fa-arrow-left fa-2x"></i></a>
				</div>
			</div>
			<div class="box-body">
				<ul class="parsley-error-list">
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>

				{!! Form::open(array('url'=>'travelagents/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
				<div class="col-md-12">
					<fieldset><legend> {{ Lang::get('core.agentdetails') }}</legend>
						{!! Form::hidden('travelagentID', $row['travelagentID']) !!}
						<div class="form-group  " >
							<label for="Legalname" class=" control-label col-md-4 text-left"> {{ Lang::get('core.fullname') }} <span class="asterix"> * </span></label>
							<div class="col-md-6">
								<input required  type='text' name='legalname' id='legalname' value='{{ $row['legalname'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						{{-- this is used <div class="form-group  " >
							<label for="Agency Licence Code" class=" control-label col-md-4 text-left"> Agency Licence Code </label>
							<div class="col-md-6">
								<input  type='text' name='agency_licence_code' id='agency_licence_code' value='{{ $row['agency_licence_code'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div> --}}
						{{-- <div class="form-group  " >
							<label for="Agency Code" class=" control-label col-md-4 text-left"> Agency Code </label>
							<div class="col-md-6">
								<input  type='text' name='agency_code' id='agency_code' value='{{ $row['agency_code'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div> --}}
						<div class="form-group  " >
							<label for="Email" class=" control-label col-md-4 text-left"> {{ Lang::get('core.email') }} <span class="asterix"> * </span></label>
							<div class="col-md-6">
								<input  type='text' name='email' id='email' value='{{ $row['email'] }}'
										required     class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Mobilephone" class=" control-label col-md-4 text-left"> {{ Lang::get('core.mobilePhone') }} <span class="asterix"> * </span></label>
							<div class="col-md-6">
								<input required  type='text' name='mobilephone' id='mobilephone' value='{{ $row['mobilephone'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						{{-- <div class="form-group  " >
							<label for="Website" class=" control-label col-md-4 text-left"> Website </label>
							<div class="col-md-6">
								<input  type='text' name='website' id='website' value='{{ $row['website'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div> --}}
						<div class="form-group  " style="display: none;">
							<label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get('core.country') }} <span class="asterix"> * </span></label>
							<div class="col-md-6">
								<select name='countryID' rows='5' id='countryID' class='select2 ' required   ></select>
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Address" class=" control-label col-md-4 text-left"> {{ Lang::get('core.address') }} </label>
							<div class="col-md-6">
										  {{-- <textarea name='address' rows='5' id='address' class='form-control '
										  >{{ $row['address'] }}</textarea> --}}
									<input  type='text' name='address' id='address' value='{{ $row['address'] }}' 
										  	class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						{{-- address line 2 use website column in table --}}
						<div class="form-group  " >
							<label for="Website" class=" control-label col-md-4 text-left"></label>
							<div class="col-md-6">
								<input  type='text' name='website' id='website' value='{{ $row['website'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						{{-- postcode use phone column in table --}}
						<div class="form-group  " >
							<label for="Phone" class=" control-label col-md-4 text-left"> {{ Lang::get('core.postcode') }} </label>
							<div class="col-md-6">
								<input  type='text' name='phone' id='phone' value='{{ $row['phone'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						{{-- city use agency licence code column in table --}}
						<div class="form-group  " >
							<label for="Agency Licence Code" class=" control-label col-md-4 text-left"> {{ Lang::get('core.f_city') }} </label>
							<div class="col-md-6">
								<input  type='text' name='agency_licence_code' id='agency_licence_code' value='{{ $row['agency_licence_code'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="City" class=" control-label col-md-4 text-left"> {{ Lang::get('core.state') }} <span class="asterix"> * </span></label>
							<div class="col-md-6">
								<select name='cityID' rows='5' id='cityID' class='select2 ' required   ></select>
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Agent Logo" class=" control-label col-md-4 text-left"> {{ Lang::get('core.agentlogo') }} </label>
							<div class="col-md-6">
								<input  type='file' name='agent_logo' id='agent_logo' @if($row['agent_logo'] =='') class='required' @endif style='width:150px !important;'  />
								<div >
									{!! SiteHelpers::showUploadedFile($row['agent_logo'],'/uploads/images/') !!}

								</div>

							</div>
							<div class="col-md-2">

							</div>
						</div>
						{{-- <div class="form-group  " >
							<label for="Personincontact" class=" control-label col-md-4 text-left"> Person in Contact </label>
							<div class="col-md-6">
								<input  type='text' name='personincontact' id='personincontact' value='{{ $row['personincontact'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div> --}}
						{{-- this is used <div class="form-group  " >
							<label for="Phone" class=" control-label col-md-4 text-left"> Phone </label>
							<div class="col-md-6">
								<input  type='text' name='phone' id='phone' value='{{ $row['phone'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div> --}}
						{{-- <div class="form-group  " >
							<label for="Fax" class=" control-label col-md-4 text-left"> Fax </label>
							<div class="col-md-6">
								<input  type='text' name='fax' id='fax' value='{{ $row['fax'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div> --}}
						<div class="form-group  " >
							<label for="Bankname" class=" control-label col-md-4 text-left"> {{ Lang::get('core.bankname') }} </label>
							<div class="col-md-6">
								<input  type='text' name='bankname' id='bankname' value='{{ $row['bankname'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Ibancode" class=" control-label col-md-4 text-left"> {{ Lang::get('core.banknumber') }} </label>
							<div class="col-md-6">
								<input  type='text' name='ibancode' id='ibancode' value='{{ $row['ibancode'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						{{-- <div class="form-group  " >
							<label for="Holder Name" class=" control-label col-md-4 text-left"> Holder Name </label>
							<div class="col-md-6">
								<input  type='text' name='holder_name' id='holder_name' value='{{ $row['holder_name'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div> --}}
						{{-- <div class="form-group  " >
							<label for="Vatno" class=" control-label col-md-4 text-left"> Vat No </label>
							<div class="col-md-6">
								<input  type='text' name='vatno' id='vatno' value='{{ $row['vatno'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div> --}}
						@if(in_array(Auth::user()->group_id, [1,2]))
						<div class="form-group  " >
							<label for="Commissionrate" class=" control-label col-md-4 text-left"> {{ Lang::get('core.commisionrate') }} <span class="asterix"> * </span></label>
							<div class="col-md-6">
								<input  required type='text' name='commissionrate' id='commissionrate' value='{{ $row['commissionrate'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Commissionrate" class=" control-label col-md-4 text-left"> {{ Lang::get('core.affiliatelink') }} <span class="asterix"> * </span></label>
							<div class="col-md-6">
								<input  required type='text' name='affiliatelink' id='affiliatelink' value='{{ $row['affiliatelink'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						@else
						<input  type='hidden' name='affiliatelink' id='affiliatelink' value='{{ $row['affiliatelink'] }}'
										class='form-control ' />
						@endif
						<div class="form-group  " >
							<label for="Agency Name" class=" control-label col-md-4 text-left"> {{ Lang::get('core.agencyname') }} </label>
							<div class="col-md-6">
								<input  type='text' name='agency_name' id='agency_name' value='{{ $row['agency_name'] }}'
										class='form-control ' />
							</div>
							<div class="col-md-2">

							</div>
						</div>
						<div class="form-group  " >
							<label for="Status" class=" control-label col-md-4 text-left"> {{ Lang::get('core.status') }} <span class="asterix"> * </span></label>
							<div class="col-md-6">

								<label class='radio radio-inline'>
									<input type='radio' name='status' value ='0' required @if($row['status'] == '0') checked="checked" @endif > {{ Lang::get('core.passive') }} </label>
								<label class='radio radio-inline'>
									<input type='radio' name='status' value ='1' required @if($row['status'] == '1') checked="checked" @endif > {{ Lang::get('core.active') }} </label>
							</div>
							<div class="col-md-2">

							</div>
						</div> </fieldset>
				</div>




				<div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">
						<button type="submit" name="apply" class="btn btn-info btn-sm" > {{ Lang::get('core.sb_apply') }}</button>
						<button type="submit" name="submit" class="btn btn-primary btn-sm" > {{ Lang::get('core.sb_save') }}</button>
						<button type="submit" name="applynew" class="btn btn-primary btn-sm" > {{ Lang::get('core.sb_save_add') }}</button>
						<button type="button" onclick="location.href='{{ URL::to('travelagents?return='.$return) }}' " class="btn btn-danger btn-sm ">  {{ Lang::get('core.sb_cancel') }} </button>
					</div>
				</div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>

	<script type="text/javascript">
        $(document).ready(function() {


            $("#countryID").jCombo("{!! url('travelagents/comboselect?filter=def_country:countryID:country_name') !!}",
                {  selected_value : '129' });

            $("#cityID").jCombo("{!! url('travelagents/comboselect?filter=def_city:cityID:city_name') !!}&parent=countryID:",
                {  parent: '#countryID', selected_value : '{{ $row["cityID"] }}' });


            $('.removeMultiFiles').on('click',function(){
                var removeUrl = '{{ url("travelagents/removefiles?file=")}}'+$(this).attr('url');
                $(this).parent().remove();
                $.get(removeUrl,function(response){});
                $(this).parent('div').empty();
                return false;
            });

        });
	</script>
@stop