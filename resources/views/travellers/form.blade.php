@extends('layouts.app')

@section('content')
	<section class="content-header">
		<h1> {{ Lang::get('core.travellers') }}</h1>
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

				{!! Form::open(array('url'=>'travellers/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
					<div>{!! Form::hidden('travellerID', $row['travellerID']) !!}
						<div class="form-group  " >
							<label for="Name & Surname" class=" control-label col-md-4 text-left"> {{ Lang::get('core.firstname') }} <span class="asterix"> * </span></label>
							<div class="col-md-5">
								<input  type='text' name='nameandsurname' id='nameandsurname' value='{{ $row['nameandsurname'] }}' required class='form-control required' />
							</div>
						</div>
                        <div class="form-group  " >
                            <label for="Last Name" class=" control-label col-md-4 text-left"> {{ Lang::get('core.lastname') }} <span class="asterix"> * </span></label>
                            <div class="col-md-5">
                                <input  type='text' name='last_name' id='last_name' value='{{ $row['last_name'] }}' required class='form-control required' />
                            </div>
                        </div>
                        <div class="form-group  " >
                            <label for="Gender" class=" control-label col-md-4 text-left"> {{ Lang::get('core.gender') }} <span class="asterix"> * </span></label>
                            <div class="col-md-5">
                                <input type="radio" name="gender" id="gender" value="M" required @if ($row['gender'] === 'M')
                                    checked 
                                @endif>{{ Lang::get('core.male') }} <input type="radio" name="gender" id="gender" value="F" @if ($row['gender'] === 'F')
                                    checked 
                                @endif>{{ Lang::get('core.female') }}
                            </div>
                        </div>
						<div class="form-group  " >
							<label for="Email" class=" control-label col-md-4 text-left"> {{ Lang::get('core.email') }} <span class="asterix"> * </span></label>
							<div class="col-md-5">
								<input  type='text' name='email' id='email' value='{{ $row['email'] }}' required class='form-control ' />
							</div>
						</div>
						<div class="form-group  " >
							<label for="Phone" class=" control-label col-md-4 text-left"> {{ Lang::get('core.phone') }} </label>
							<div class="col-md-5">
								<input  type='text' name='phone' id='phone' value='{{ $row['phone'] }}' class='form-control ' />
							</div>
						</div>
                        <div class="form-group  " >
                            <label for="Phone" class=" control-label col-md-4 text-left"> {{ Lang::get('core.nric') }} </label>
                            <div class="col-md-5">
                                <input  type='text' name='nric' id='nric' value='{{ $traveller->NRIC }}' class='form-control ' />
                            </div>
                        </div>
						<div class="form-group  " >
							<label for="Address" class=" control-label col-md-4 text-left"> {{ Lang::get('core.address') }} </label>
							<div class="col-md-5">
								<textarea name='address' rows='2' id='address' class='form-control '>{{ $row['address'] }}</textarea>
							</div>
						</div>
						<div class="form-group  " >
							<label for="City" class=" control-label col-md-4 text-left"> {{ Lang::get('core.city') }} </label>
							<div class="col-md-4">
								<input  type='text' name='city' id='city' value='{{ $row['city'] }}'class='form-control ' />
							</div>
						</div>
						<div class="form-group  " >
							<label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get('core.country') }} </label>
							<div class="col-md-4">
								<select name='countryID' rows='5' id='countryID' class='select2 '   ></select>
							</div>
						</div>
                        <div class="form-group  " >
                            <label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get('core.nationality') }} </label>
                            <div class="col-md-4">
                                <select name='nationality' rows='5' id='nationality' class='select2 '   ></select>
                            </div>
                        </div>
						<div class="form-group  " >
							<label for="Profile Picture" class=" control-label col-md-4 text-left"> {{ Lang::get('core.profilepicture') }} </label>
							<div class="col-md-5">
								<div class="btn btn-primary btn-file"><i class="fa fa-camera fa-2x"></i>
									<input  type='file' name='image' id='image' @if($row['image'] =='') class='required' @endif style='width:150px !important;'  /></div>
								<div >
									@if(file_exists('./uploads/images/'.CNF_OWNER.'/'.$row['image']) && $row['image'] !='')
										<span class="pull-left removeMultiFiles "  url="/uploads/images/<?php echo CNF_OWNER;?>/{{$row['image']}}">
										<i class="fa fa-trash-o fa-2x text-red " data-toggle="confirmation" data-title="{{Lang::get('core.rusure')}}" data-content="{{ Lang::get('core.youwanttodeletethis') }}" title="{{ Lang::get('core.deletethisimage') }}" ></i></span>                            {!! SiteHelpers::showUploadedFile($row['image'],'/uploads/images/'.CNF_OWNER.'/') !!}
									@endif

								</div>
							</div>
						</div>
					</div>
					<fieldset><legend>{{ Lang::get('core.passportdetails') }}</legend>
						<div>
							<div class="form-group  " >
                                <label for="Passport No" class=" control-label col-md-4 text-left"> {{ Lang::get('core.passportno') }} </label>
                                <div class="col-md-4">
                                    <input  type='text' name='passportno' id='passportno' value='{{ $row['passportno'] }}'
                                class='form-control ' />
							    </div>
							</div>
                            <div class="form-group  " >
                                <label for="Date of Birth" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dateofbirth') }} </label>
                                <div class="col-md-5">

                                    <div class="input-group m-b" style="width:150px !important;">
                                        {!! Form::text('dateofbirth', $row['dateofbirth'],array('class'=>'form-control date')) !!}
                                        <span class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Date of Issue" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dateofissue') }} </label>
                                <div class="col-md-5">

                                    <div class="input-group m-b" style="width:150px !important;">
                                        {!! Form::text('passportissue', $row['passportissue'],array('class'=>'form-control date')) !!}
                                        <span class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></span>
                                    </div>
                                    </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Date of Expiry" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dateofexpiry') }}</label>
                                <div class="col-md-5">

                                    <div class="input-group m-b" style="width:150px !important;">
                                        {!! Form::text('passportexpiry', $row['passportexpiry'],array('class'=>'form-control date')) !!}
                                        <span class="input-group-addon"><i class="fa fa-calendar fa-lg"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Country of Issue" class=" control-label col-md-4 text-left"> {{ Lang::get('core.countryofissue') }} <span class="asterix"> * </span></label>
                                <div class="col-md-4">
                                    <select name='passportcountry' rows='5' id='passportcountry' class='select2 ' required  ></select>
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Passport No" class=" control-label col-md-4 text-left"> {{ Lang::get('core.passportplaceissue') }} </label>
                                <div class="col-md-4">
                                    <input  type='text' name='passport_place_made' id='passport_place_made' value='{{ $row['passport_place_made'] }}'
                                class='form-control ' />
                                </div>
                            </div>
                        </div>
					</fieldset>
                {{--add document upload field--}}
                {{--{!! Form::open(array('url'=>'travellerdetail/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}--}}
                    <fieldset>
                        <legend>{{ Lang::get('core.mahram_detail') }}</legend>
                        @if(!empty($row->travellerID))
                            <div class="form-group  " >
                                <label for="Relation" class=" control-label col-md-4 text-left"> {{ lang::get('core.relation') }} </label>
                                <div class="col-md-6">

                                    <?php $relation = explode(',',$row['relation']);
                                    $relation_opt = array(
                                        '0' => lang::get('core.is_mahram'),
                                        '1' => lang::get('core.mahram_relation_1'),
                                        '2' => lang::get('core.mahram_relation_2'),
                                        '3' => lang::get('core.mahram_relation_3'),
                                        '4' => lang::get('core.mahram_relation_4'),
                                        '5' => lang::get('core.mahram_relation_5'),
                                        '6' => lang::get('core.mahram_relation_6'),
                                        '7' => lang::get('core.mahram_relation_7')); ?>
                                    <select name='mahram_relation' rows='5'   class='select2 '  >
                                        <?php
                                        foreach($relation_opt as $key=>$val)
                                        {
                                            echo "<option  value ='$key' ".($row['mahram_relation'] == $key ? " selected='selected' " : '' ).">$val</option>";
                                        }
                                        ?></select>
                                </div>
                                <div class="col-md-2">

                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="mahram_id" class=" control-label col-md-4 text-left"> {{ lang::get('core.mahram') }} </label>
                                <div class="col-md-6">
                                    <select name='mahram_id' id='mahram_id' class='select2 '>

                                        <option value="0"> {{ lang::get('core.is_mahram') }}</option>
                                        @foreach($mahram_list as $mah)
                                        <option value="{{ $mah->travellerID }}" @if($row->mahram_id == $mah->travellerID ) selected @endif> {{ $mah->nameandsurname }}</option>

                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">

                                </div>
                            </div>

                        @else
                            <h4>{{ lang::get('core.reminderaftersave') }}</h4>
                        @endif
                    </fieldset>
                {{--{!! Form::close !!}--}}
                    <fieldset><legend>{{ Lang::get('core.emergencydetails') }}</legend>
                        <div>
                            <div class="form-group  " >
                                <label for="Emergency Contact Name" class=" control-label col-md-4 text-left"> {{ Lang::get('core.emergencycontact') }} </label>
                                <div class="col-md-5">
                                    <input  type='text' name='emergencycontactname' id='emergencycontactname' value='{{ $row['emergencycontactname'] }}'
                                    class='form-control ' />
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Emergency Contact Email" class=" control-label col-md-4 text-left"> {{ Lang::get('core.emcontactemail') }} </label>
                                <div class="col-md-5">
                                    <input  type='text' name='emergencycontactemail' id='emergencycontactemail' value='{{ $row['emergencycontactemail'] }}'
                                    class='form-control ' />
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Emergency Contact Phone" class=" control-label col-md-4 text-left"> {{ Lang::get('core.emphone') }} </label>
                                <div class="col-md-5">
                                    <input  type='text' name='emergencycontanphone' id='emergencycontanphone' value='{{ $row['emergencycontanphone'] }}'
                                    class='form-control ' />
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Insurance Company" class=" control-label col-md-4 text-left"> {{ Lang::get('core.insurancecompany') }} </label>
                                <div class="col-md-5">
                                    <input  type='text' name='insurancecompany' id='insurancecompany' value='{{ $row['insurancecompany'] }}'
                                    class='form-control ' />
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Insurance Policy No" class=" control-label col-md-4 text-left"> {{ Lang::get('core.insurancepolicyno') }} </label>
                                <div class="col-md-5">
                                    <input  type='text' name='insurancepolicyno' id='insurancepolicyno' value='{{ $row['insurancepolicyno'] }}'
                                    class='form-control ' />
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Insurance Company Phone" class=" control-label col-md-4 text-left"> {{ Lang::get('core.insurancecompanyphone') }} </label>
                                <div class="col-md-5">
                                    <input  type='text' name='insurancecompanyphone' id='insurancecompanyphone' value='{{ $row['insurancecompanyphone'] }}'
                                    class='form-control ' />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset><legend>{{ Lang::get('core.specialrequest') }}</legend>
                        <div>
                            <div class="form-group  " >
                                <label for="Bed Configuration" class=" control-label col-md-4 text-left"> {{ Lang::get('core.bedconfiguration') }} </label>
                                <div class="col-md-5">

                                    <label class='radio radio-inline'>
                                    <input type='radio' name='bedconfiguration' value ='1'  @if($row['bedconfiguration'] == '1') checked="checked" @endif > {{ Lang::get('core.single') }} </label>
                                    <label class='radio radio-inline'>
                                    <input type='radio' name='bedconfiguration' value ='2'  @if($row['bedconfiguration'] == '2') checked="checked" @endif > {{ Lang::get('core.twin') }} </label>
                                    <label class='radio radio-inline'>
                                    <input type='radio' name='bedconfiguration' value ='3'  @if($row['bedconfiguration'] == '3') checked="checked" @endif > {{ Lang::get('core.double') }} </label>
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Dietary Requirements" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dietaryreq') }} </label>
                                <div class="col-md-5">
                                    <textarea name='dietaryrequirements' rows='5' id='dietaryrequirements' class='form-control '>{{ $row['dietaryrequirements'] }}</textarea>
                                </div>
                            </div>
                            <div class="form-group  " >
                                <label for="Interests" class=" control-label col-md-4 text-left"> {{ Lang::get('core.interests') }} </label>
                                <div class="col-md-5">
                                    <input  type='text' name='interests' id='interests' value='{{ $row['interests'] }}'
                                    class='form-control ' />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>{{ Lang::get('core.status') }}</legend>
                        <div>
                            <div class="form-group  " >
                                <div class="col-md-12 text-center ">
                                    <label class='radio radio-inline'>
                                    <input type='radio' name='status' value ='0' required @if($row['status'] == '0') checked="checked" @endif > {{ Lang::get('core.fr_minactive') }} </label>
                                    <label class='radio radio-inline'>
                                    <input type='radio' name='status' value ='1' required @if($row['status'] == '1') checked="checked" @endif > {{ Lang::get('core.fr_mactive') }} </label>
                                </div>
                            </div>
                        </div>
				    </fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 text-right">&nbsp;</label>
                        <div class="col-sm-8">
                            <button type="submit" name="apply" class="btn btn-info btn-sm" > {{ Lang::get('core.sb_apply') }}</button>
                            <button type="submit" name="submit" class="btn btn-primary btn-sm" > {{ Lang::get('core.sb_save') }}</button>
                            <button type="submit" name="applynew" class="btn btn-primary btn-sm" > {{ Lang::get('core.sb_save_add') }}</button>
                            <button type="button" onclick="location.href='{{ URL::to('travellers?return='.$return) }}' " class="btn btn-danger btn-sm ">  {{ Lang::get('core.sb_cancel') }} </button>
                        </div>
                    </div>

				{!! Form::close() !!}
			</div>
		</div>
	</div>

    <div style="clear:both"></div>


    <script type="text/javascript">
	$(document).ready(function() {

    $('[data-toggle=confirmation]').confirmation({
    rootSelector: '[data-toggle=confirmation]',
    container: 'body'
    });


		$("#countryID").jCombo("{!! url('travellers/comboselect?filter=def_country:countryID:country_name') !!}",
		{  selected_value : '{{ $row["countryID"] }}' });

        $("#nationality").jCombo("{!! url('travellers/comboselect?filter=def_country:countryID:country_name') !!}",
        {  selected_value : '{{ $row["nationality"] }}' });

		$("#passportcountry").jCombo("{!! url('travellers/comboselect?filter=def_country:countryID:country_name') !!}",
		{  selected_value : '{{ $row["passportcountry"] }}' });


		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("travellers/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();
			return false;
		});

        $(".date").datetimepicker({
        format: 'yyyy-mm-dd',
        autoclose:true ,
        minView:2 ,
        startView:4
        });



	});
</script>


@stop