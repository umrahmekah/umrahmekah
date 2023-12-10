<div class="acc-body passport-detail  mt-3 ml-1 traveller1" id="passport-detail">
    <h5>
        <i class="far fa-address-card"></i>
        <span class="m-2">{{ Lang::get('core.passportdetails') }}</span>
    </h5>
    
    <h6 class="mt-4 legend1" id="passport1"></h6>
    <hr>

    <div class="row">
        <div class="form-group col-md-6" id="name">
            <div>
                <label>{{ Lang::get('core.passportno') }}</label>
                <input placeholder="{{ Lang::get('core.passportno') }}" style="text-transform:uppercase" maxlength="20" type='text' name='passportno[]' id='passportno1' class='form-control' />
            </div>
        </div>

        <div class="form-group col-md-6">
            <div>
                <label>{{ Lang::get('core.dateofbirth') }}</label>
                <input placeholder="{{ Lang::get('core.dateofbirth') }} &nbsp;" type='date' name='dob[]' id='dob1' class='form-control' />
            </div>
        </div>

        <div class="form-group col-md-6">
            <div>
                <label>{{ Lang::get('core.dateofissue') }}</label>
                <input placeholder="{{ Lang::get('core.dateofissue') }} &nbsp;" type='date' name='issuedate[]' id='issuedate1' class='form-control' />
            </div>
        </div>

        <div class="form-group col-md-6">
            <div>
                <label>{{ Lang::get('core.dateofexpiry') }}</label>
                <input placeholder="{{ Lang::get('core.dateofexpiry' )}} &nbsp;" type='date' name='expdate[]' id='expdate1' class='form-control' />
            </div>
        </div>

        <div class="form-group col-md-6">
            <div>
                <label>{{ Lang::get('core.place_made') }}</label>
                <input placeholder="{{ Lang::get('core.place_made' )}} &nbsp;" type='text' name='place_made[]' id='place_made1' class='form-control' />
            </div>
        </div>

        <div class="form-group col-md-6">
            <div>
                <label>{{ Lang::get('core.country') }}</label>
                <select name='country[]' class="form-control" id="country1">
                    <option value="">{{ Lang::get('core.choose_option', ['name' => Lang::get('core.passportcountry')]) }} </option>
                    @foreach ($countries as $country)
                        <option value="{{$country->countryID}}">{{$country->country_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="float-right">
    <button type="button" onclick="backPassport();" class="btn btn-default">{{ Lang::get('core.btn_back') }}</button>
    <button type="button" onclick="continuePassport();" class="btn btn-primary">{{ Lang::get('core.btn_continue') }}</button>
</div>