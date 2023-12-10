<div id="travel-form" class="travel-form mt-3 ml-1">
    <h5>
        <i class="fas fa-users"></i>
        <span class="m-2">{{ Lang::get('core.jemaahdetail') }}</span>
    </h5>

    <h6 class="mt-4 traveller1">{{ Lang::get('core.jemaah') }} 1 ({{ Lang::get('core.primarycontact') }})</h6>
    <hr>

    <div class="row">
        <div class="col-md-6">

            <div class="row">
                <div class="col-md-6" id="name">
                    @if(Auth::check())
                    <div>
                        <input type='text' name='nameandsurname[]' id='nameandsurname1' placeholder="{{ Lang::get('core.firstname') }} *" value='{{$user['first_name']}}'
                            required class='form-control required' />
                    </div>
                    @else
                    <div>
                        <input type='text' name='nameandsurname[]' id='nameandsurname1' placeholder="{{ Lang::get('core.firstname') }} *" value='' required class='form-control required' />
                    </div>
                    @endif
                </div>
                <div class="col-md-6" id="last_name">
                    @if(Auth::check())
                    <div>
                        <input type='text' name='last_name[]' id='last_name1' placeholder="{{ Lang::get('core.lastname') }} *" value='{{$user['last_name']}}'
                            required class='form-control required' />
                    </div>
                    @else
                    <div>
                        <input type='text' name='last_name[]' id='last_name1' placeholder="{{ Lang::get('core.lastname') }} *" value='' required class='form-control required' />
                    </div>
                    @endif
                </div>
            </div>
            <div class="form-group" id="email">
                @if(Auth::check())
                <div>
                    <input type='email' name='email[]' id='email1' placeholder="{{ Lang::get('core.email') }} *" value='{{$user['email']}}' required class='form-control required' />
                </div>
                @else
                <div>
                    <input type='email' name='email[]' id='email1' placeholder="{{ Lang::get('core.email') }} *" value='' required class='form-control required' />
                </div>
                @endif
            </div>
            <div class="form-group">
                <div>
                    <input type='text' name='phone[]' id='phone1' required class='form-control required' placeholder="{{ Lang::get('core.phone') }} * ex:0123456789" />
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type='text' name='NRIC[]' id='nric1' maxlength="20" required class='form-control required' placeholder="{{ Lang::get('core.nric') }} * ex:987654321098" />
                </div>
            </div>
            <div class="form-group">
                <div>
                    <select name='nationality[]' class="form-control" id="nationality1">
                        <option value="">{{ Lang::get('core.choose_option', ['name' => Lang::get('core.nationality')]) }}</option>
                        @foreach ($countries as $country)
                        <option value="{{$country->countryID}}">{{$country->country_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <div>
                    <select required name='gender[]' class="form-control" id="gender1">
                        <option value="">{{ Lang::get('core.choose_option', ['name' => Lang::get('core.gender')]) }} *</option>
                        <option value="M">{{ Lang::get("core.male") }}</option>
                        <option value="F">{{ Lang::get("core.female") }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <textarea name='address[]' rows='5' id='address1' class='form-control ' placeholder="{{ Lang::get('core.address') }}"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <select name='countryID[]' class="form-control" id="countryID1">
                        <option value="">{{ Lang::get('core.choose_option', ['name' => Lang::get('core.country')]) }}</option>
                        @foreach ($countries as $country)
                        <option value="{{$country->countryID}}">{{$country->country_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="float-right">
    <button type="button" onclick="addTraveller();" class="btn btn-default">
        <i class="fa fa-user-plus" style="font-size: 20px" aria-hidden="true"></i>
    </button>
    <span>
        <input type="button" class="btn btn-primary" value="{{ Lang::get('core.btn_continue') }}" onclick="continueTraveller();" class="btn btn-default">
    </span>
</div>