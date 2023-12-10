@include('layouts.modern.header')

<style>
    div div div div p{
        margin-bottom: 10px !important;
    }
</style>

<div class="main-wraper padd-90 bg-grey-2 tabs-page">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="second-title">
                    <h2>{{ Lang::get('core.bookingform') }}</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="simple-tab type-2 tab-wrapper">
                    <div class="tab-nav-wrapper">
                        <div class="nav-tab  clearfix">
                            <div class="nav-tab-item active" id="traveller_tab">
                                {{ Lang::get('core.travellerdetail') }}
                            </div>
                            <div class="nav-tab-item" onclick="bookingCheck();passData();addMahram();" id="mahram_tab">
                                {{ Lang::get('core.tourdetails') }}
                            </div>
                            <div class="nav-tab-item" onclick="passportCheck();passData();" id="passport_tab">
                                {{ Lang::get('core.passportdetails') }}
                            </div>
                            <div class="nav-tab-item" onclick="summaryCheck();passData();copyForms();calculateroom();" id="summary_tab">
                                {{ Lang::get('core.summary') }}
                            </div>
                        </div>
                    </div>
                    <div class="tabs-content tabs-wrap-style clearfix">
                        <form role="form" id="booking_form" action="/bookPackage/setSession" method="post">
                            {{csrf_field()}}
                            <input type="hidden" value="{{ $affiliate }}" name="affiliate">
                            <input type="hidden" value="{{ app('request')->input('tourdateID') }}" name="tourdateID">
                            <input type="hidden" value="{{ app('request')->input('tourID') }}" name="tourID">
                            @if(Auth::check())
                                <input type="hidden" value="{{ $user_id }}" name="userID">
                            @endif
                            <input type="hidden" value="2" name="type">
                            {{--generate booking now with random characters--}}
                            <?php
                            $bookingno1 = substr(str_shuffle(str_repeat("ABCDEFGHJKLMNPQRSTUVWYZ", 4)), 0, 4);
                            $bookingno2 = substr(str_shuffle(str_repeat("123456789", 6)), 0, 6);
                            ?>
                            <input type="hidden" name="bookingsID" value="{{$bookingno1.$bookingno2}}">
                            <div class="tab-info active">
                                <div class="acc-body travel-form" id="travel-form">
                                    <h5>{{ Lang::get('core.travellerdetail') }}</h5>
                                    <div class="row">
                                        <fieldset class="fieldset1">
                                            <legend class="legend1">{{ Lang::get('core.traveller') }} 1 ({{ Lang::get('core.primarycontact') }})</legend>
                                            <div class="col-md-6">
                                                <div class="form-group" id="name">
                                                    <label for="Name & Surname" class=" control-label col-md-4 text-left"> {{ Lang::get('core.namesurname') }} <span class="asterix"> * </span></label>
                                                    @if(Auth::check())
                                                        <div>
                                                            <input  type='text' name='nameandsurname[]' id='nameandsurname1' value='{{$user['first_name']}}' required class='form-control required' />
                                                        </div>
                                                    @else
                                                        <div>
                                                            <input  type='text' name='nameandsurname[]' id='nameandsurname1' value='' required class='form-control required' />
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="form-group" id="email">
                                                    <label for="Email" class=" control-label col-md-4 text-left"> {{ Lang::get('core.email') }} <span class="asterix"> * </span></label>
                                                    @if(Auth::check())
                                                        <div>
                                                            <input  type='email' name='email[]' id='email1' value='{{$user['email']}}' required class='form-control required' />
                                                        </div>
                                                    @else
                                                        <div>
                                                            <input  type='email' name='email[]' id='email1' value='' required class='form-control required' />
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="form-group" >
                                                    <label for="Phone" class=" control-label col-md-4 text-left"> {{ Lang::get('core.phone') }} <span class="asterix"> * </span></label>
                                                    <div>
                                                        <input  type='text' name='phone[]' id='phone1' required class='form-control required' placeholder="ex:0123456789" />
                                                    </div>
                                                </div>
                                                <div class="form-group" >
                                                    <label for="NRIC" class=" control-label col-md-4 text-left"> {{ Lang::get('core.nric') }} <span class="asterix"> * </span></label>
                                                    <div>
                                                        <input  type='text' name='NRIC[]' id='nric1' required class='form-control required' placeholder="ex:987654321098" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group" >
                                                    <label for="Address" class=" control-label col-md-4 text-left"> {{ Lang::get('core.address') }}</label>
                                                    <div>
                                                        <textarea name='address[]' rows='5' id='address1' class='form-control '></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group" >
                                                    <label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get('core.country') }} </label>
                                                    <div>
                                                        <select name='countryID[]' class="form-control" id="countryID1">
                                                            <option value="">{{ Lang::get('core.choose_one') }}</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{$country->countryID}}">{{$country->country_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <button type="button" onclick="addTraveller();" class="c-button bg-dr-blue hv-dr-blue-o b-40 fl list-hidden"><i class="fa fa-user-plus" style="font-size: 20px" aria-hidden="true"></i></button>
                                    <span><input type="button" value="{{ Lang::get('core.btn_continue') }}" onclick="continueTraveller();" class="c-button bg-dr-blue hv-dr-blue-o b-40 fl list-hidden"></span>
                                </div>
                            </div>
                            <div class="tab-info" id="tabinfomahram">
                                <div class="acc-body mahram-detail" id="mahram-detail">
                                    <h5>{{ Lang::get('core.tourdetails') }}</h5>
                                    <div class="row">
                                    </div>
                                    <div class="row">

                                        <fieldset class="fieldset1">
                                            <legend class="legend1" id="mahram1"></legend>
                                            <div class="col-md-12">
                                                <div class="col-md-12">
                                                    <label for="Room Name" class=" control-label col-md-12 text-left"> {{ Lang::get('core.roomtypes') }} <span class="asterix"> * </span></label>
                                                    <span>We will fill up the same room first before getting another room. Ex: if (2 person room) selected for 3 people, we will give 2 (2 person room)</span>
                                                    <div>
                                                        <select name='room[]' class="form-control mahramname" id="room1">
                                                            <<option value="0" @if (old('room1') == '0') selected="selected" @endif>{{ Lang::get('core.choose_one') }}</option>
                                                            @if($roomprice->cost_single!=0 || $roomprice->cost_single!=NULL)
                                                                <option value="1" @if (old('room1') == '1') selected="selected" @endif>{{ Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_single }}</option>
                                                            @else
                                                                <option disabled>{{ Lang::get('core.roomtype_1') }} not available</option>
                                                            @endif
                                                            @if($roomprice->cost_double!=0 ||$roomprice->cost_double!=NULL)
                                                                <option value="2" @if (old('room1') == '2') selected="selected" @endif>{{ Lang::get('core.roomtype_2') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_double }}</option>
                                                            @else
                                                                <option disabled>{{ Lang::get('core.roomtype_2') }} not available</option>
                                                            @endif
                                                            @if($roomprice->cost_triple!=0 || $roomprice->cost_triple!=NULL)
                                                                <option value="3" @if (old('room1') == '3') selected="selected" @endif>{{ Lang::get('core.roomtype_3') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_triple }}</option>
                                                            @else
                                                                <option disabled>{{ Lang::get('core.roomtype_3') }} not available</option>
                                                            @endif
                                                            @if($roomprice->cost_quad!=0|| $roomprice->cost_quad!=NULL)
                                                                <option value="4" @if (old('room1') == '4') selected="selected" @endif>{{ Lang::get('core.roomtype_4') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_quad }}</option>
                                                            @else
                                                                <option disabled>{{ Lang::get('core.roomtype_4') }} not available</option>
                                                            @endif

                                                        </select>
                                                    </div>
                                                </div>
                                                {{-- <div class="form-group col-md-6">
                                                    <label for="Mahram Name" class=" control-label col-md-4 text-left"> {{ Lang::get('core.namesurname') }} <span class="asterix"> * </span></label>
                                                    <div>
                                                        <select name='mahram_name[]' class="form-control mahramname" id="mahram_name1">
                                                            <option>{{ Lang::get('core.choose_one') }}</option>

                                                        </select>
                                                    </div>
                                                </div> --}}

                                                <input type="hidden" name="roomtype1" id="roomtype1" value="0">
                                                <input type="hidden" name="roomtype2" id="roomtype2" value="0">
                                                <input type="hidden" name="roomtype3" id="roomtype3" value="0">
                                                <input type="hidden" name="roomtype4" id="roomtype4" value="0">

                                                {{-- <div class="form-group col-md-6">
                                                    <label for="mahram_relation" class=" control-label col-md-5 text-left"> {{ Lang::get('core.mahram_relation') }} <span class="asterix"> * </span></label>
                                                    <div>
                                                        <select class="form-control required" required name="mahram_relation" id="mahram_relation1">
                                                            <option value="">{{ Lang::get('core.please_select') }}</option>
                                                            <option value="1">{{ Lang::get('core.mahram_relation_1') }}</option>
                                                            <option value="2">{{ Lang::get('core.mahram_relation_2') }}</option>
                                                            <option value="3">{{ Lang::get('core.mahram_relation_3') }}</option>
                                                            <option value="4">{{ Lang::get('core.mahram_relation_4') }}</option>
                                                            <option value="5">{{ Lang::get('core.mahram_relation_5') }}</option>
                                                            <option value="6">{{ Lang::get('core.mahram_relation_6') }}</option>
                                                            <option value="7">{{ Lang::get('core.mahram_relation_7') }}</option>
                                                            <option value="8">{{ Lang::get('core.mahram_not_required') }}</option>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <button type="button" onclick="continueMahram();" class="c-button bg-dr-blue hv-dr-blue-o b-40 fl list-hidden">{{ Lang::get('core.btn_continue') }}</button>
                                </div>
                                <div>
                                    <button type="button" onclick="backMahram();" class="c-button bg-dr-blue hv-dr-blue-o b-40 fl list-hidden">{{ Lang::get('core.btn_back') }}</button>
                                </div>
                            </div>
                            <div class="tab-info">
                                <div class="acc-body passport-detail" id="passport-detail">
                                    <h5>{{ Lang::get('core.passportdetails') }}</h5>
                                    <div class="row">
                                        <fieldset class="fieldset1">
                                            <legend class="legend1" id="passport1"></legend>

                                            <div class="col-md-6">

                                                <div class="form-group" id="name">
                                                    <label for="passportno" class=" control-label col-md-4 text-left"> {{ Lang::get('core.passportno') }} <span class="asterix"> * </span></label>
                                                    <div>
                                                        <input  type='text' name='passportno[]' id='passportno1' required class='form-control required' />
                                                    </div>
                                                </div>                                        <div class="form-group" >
                                                    <label for="Issue Date" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dateofissue') }} <span class="asterix"> * </span></label>
                                                    <div>
                                                        <input  type='date' name='issuedate[]' id='issuedate1' required class='form-control required' placeholder="ex:0123456789" />
                                                    </div>
                                                </div>
                                                <div class="form-group" >
                                                    <label for="Issue Date" class=" control-label col-md-4 text-left"> {{ Lang::get('core.passportcountry') }} <span class="asterix"> * </span></label>
                                                    <div>
                                                        <select name='country[]' class="form-control" id="country1">
                                                            <option value="">{{ Lang::get('core.choose_one') }}</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{$country->countryID}}">{{$country->country_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="Date of Birth" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dateofbirth') }} <span class="asterix"> * </span></label>
                                                    <div>
                                                        <input  type='date' name='dob[]' id='dob1' required class='form-control required' />
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="Expired Date" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dateofexpiry') }} <span class="asterix"> * </span></label>
                                                    <div>
                                                        <input  type='date' name='expdate[]' id='expdate1' required class='form-control required' />
                                                    </div>
                                                </div>

                                            </div>
                                        </fieldset>

                                    </div>

                                </div>
                                <div class="pull-right">
                                    <button type="button" onclick="continuePassport();" class="c-button bg-dr-blue hv-dr-blue-o b-40 fl list-hidden">{{ Lang::get('core.btn_continue') }}</button>
                                </div>
                                <div>
                                    <button type="button" onclick="backPassport();" class="c-button bg-dr-blue hv-dr-blue-o b-40 fl list-hidden">{{ Lang::get('core.btn_back') }}</button>
                                </div>
                            </div>
                            <div class="tab-info">
                                <div class="acc-body booking-summary" id="booking-summary">
                                    <h5>{{ Lang::get('core.summary') }}</h5>
                                    <div class="row">

                                        <!-- travellers summary -->

                                    </div>
                                    <div class="col-md-8 col-sm-12 summary-detail">
                                        <div class="row">
                                            <button type="button" class="collapsiblesummary" id="summary_fullname1"></button>
                                            <div class="content">
                                                <h5>{{ Lang::get('core.travellerdetail') }}</h5>
                                                <p style="color: #333;">
                                                    {{ Lang::get('core.email') }}: <b id="summary_email1"></b><br>
                                                    {{ Lang::get('core.phone') }}: <b id="summary_phoneno1"></b><br>
                                                    {{ Lang::get('core.address') }}: <b id="summary_addresstext1"></b><br>
                                                    {{ Lang::get('core.country') }}: <b id="summary_countryID1"></b><br>
                                                </p>
                                                <h5>{{ Lang::get('core.tourdetails') }}</h5>
                                                <p style="color: #333;">
                                                    {{ Lang::get('core.namesurname') }}: <b id="summary_mahram_names1"></b><br>
                                                    {{ Lang::get('core.mahram_relation') }}: <b id="summary_mahram_relations1"></b><br>
                                                </p>
                                                <h5>{{ Lang::get('core.passportdetails') }}</h5>
                                                <p style="color: #333;">
                                                    {{ Lang::get('core.passportno') }}: <b id="summary_passportno1"></b><br>
                                                    {{ Lang::get('core.dateofbirth') }}: <b id="summary_dob1"></b><br>
                                                    {{ Lang::get('core.dateofissue') }}: <b id="summary_issuedate1"></b><br>
                                                    {{ Lang::get('core.dateofexpiry') }}: <b id="summary_expdate1"></b><br>
                                                    {{ Lang::get('core.passportcountry') }}: <b id="summary_country1"></b>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- package summary -->

                                    <div class="col-md-4 col-sm-12">
                                        <div class="right-sidebar">
                                            <div class="detail-block bg-dr-blue">
                                                <h4 class="color-white">package details</h4>
                                                <div class="details-desc">
                                                    {{--@foreach($tourdatedetail as $tour)--}}
                                                    <p class="color-grey-9">Tour Name:  <span class="color-white">{{ $detail['tour_name'] }}</span></p>
                                                    <p class="color-grey-9">Category: <span class="color-white">{{ $category->tourcategoryname }}</span></p>
                                                    <p class="color-grey-9">Date: <span class="color-white">{{ $tourdatedetail['start'] }} TO {{ $tourdatedetail['end'] }}</span></p>
                                                    <p class="color-grey-9">Duration: <span class="color-white">{{ $detail['total_days'] }} DAYS AND {{ $detail['total_nights'] }} NIGHT</span></p>
                                                    <p class="color-grey-9">Transit: <span class="color-white">{{ $detail['transit'] }}</span></p>
                                                    <p class="color-grey-9">Baggage Limit: <span class="color-white">{{ $detail['baggage_limit'] }}KG</span></p>
                                                    <input type="hidden" name="totaldeposit" id="totaldeposit">
                                                    {{--@endforeach--}}
                                                </div>
                                            </div>
                                            <div class="detail-block bg-dr-blue">
                                                <div class="details-desc">
                                                    <h4 class="color-white">payment details</h4>
                                                    <p class="color-grey-9">Number of Traveller: <span id="total_jemaah1" class="color-white"></span></p>
                                                    <p class="color-grey-9">Deposit per Traveller: <span class="color-white">{{CURRENCY_SYMBOLS}}{{ $tourdatedetail['cost_deposit'] }}</span></p>
                                                    <p class="color-grey-9">Total Deposit: <span class="color-white" id="total_deposit1"></span></p>
                                                    <input type="hidden" name="balance" id="balance">
                                                    <p class="color-grey-9">Total Price: <span class="color-white" id="total_price1"></span></p>
                                                </div>

                                                <div class="details-btn">
                                                    <button class="c-button b-40 bg-white hv-transparent" type="button" onclick="submitButton();"><span>BOOK AND PAY DEPOSIT</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" onclick="backSummary();" class="c-button bg-dr-blue hv-dr-blue-o b-40 fl list-hidden">Back</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var num = 1;
    //var total_price = '<?php echo $tourdatedetail['cost_price'];?>'*num;
    //document.getElementById('total_price1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_price;
    document.getElementById('total_jemaah1').innerHTML=num;

    function addTraveller() {
        num++;
        var total_price = '<?php echo $tourdatedetail['cost_price'];?>'*num;
        var total_deposit = '<?php echo $tourdatedetail['cost_deposit'];?>'*num;
        //document.getElementById('total_price1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_price;
        document.getElementById('total_deposit1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_deposit;
        document.getElementById('totaldeposit').value = total_deposit;
        document.getElementById('total_jemaah1').innerHTML=num;
        $( ".travel-form" ).append( '<div id="detail_traveller_'+num+'" class="row"><fieldset class="fieldset1">\n' +
            '                                        <legend class="legend1"><button type="button" onclick="deleteTraveller(\'traveller_'+num+'\');"><i class="fa fa-window-close" aria-hidden="true"></i></button> {{ Lang::get('core.jemaah') }} '+num+'</legend><div class="col-md-6"><div class="form-group" id="name'+num+'"><label for="Name & Surname" class=" control-label col-md-4 text-left"> {{ Lang::get("core.namesurname") }} <span class="asterix"> * </span></label><div><input  type="text" name="nameandsurname[]" id="nameandsurname'+num+'" required class="form-control required" /></div></div><div class="form-group"><label for="Email" class=" control-label col-md-4 text-left"> {{ Lang::get("core.email") }} <span class="asterix"> * </span></label><div><input  type="email" name="email[]" id="email'+num+'" required class="form-control required" /></div></div><div class="form-group" ><label for="Phone" class=" control-label col-md-4 text-left"> {{ Lang::get("core.phone") }} <span class="asterix"> * </span></label><div><input  type="text" name="phone[]"" id="phone'+num+'" required class="form-control required" placeholder="ex:0123456789" /></div></div><div class="form-group" ><label for="NRIC" class=" control-label col-md-4 text-left"> {{ Lang::get("core.nric") }} <span class="asterix"> * </span></label><div><input  type="text" name="NRIC[]"" id="nric'+num+'" required class="form-control required" placeholder="ex:987654321098" /></div></div></div><div class="col-md-6"><div class="form-group" ><label for="Address" class=" control-label col-md-4 text-left"> {{ Lang::get("core.address") }}</label><div><textarea name="address[]"" rows="5" id="address'+num+'" class="form-control"></textarea></div></div><div class="form-group" ><label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get("core.country") }} </label><div><select name="countryID[]"" class="form-control" id="countryID'+num+'"><option value="">{{ Lang::get("core.choose_one") }}</option> @foreach ($countries as $country) <option value="{{$country->countryID}}">{{$country->country_name}}</option> @endforeach </select></div></div></div></fieldset></div>' );

        $(".mahram-detail").append('<div id="mahram_traveller_'+num+'" class="row"><fieldset class="fieldset1"><legend class="legend1" id="mahram'+num+'"></legend><div class="col-md-12">\n' +
            '                                        <div class="col-md-12">\n' +
            '                                                    <label for="Room Name" class=" control-label col-md-4 text-left"> {{ Lang::get('core.roomtypes') }} <span class="asterix"> * </span></label>\n' +
            '                                                    <div>\n' +
            '                                                        <select name="room[]" class="form-control mahramname" id="room'+num+'">\n' +
            '                                                            <option value="0">{{ Lang::get('core.choose_one') }}</option>\n' +
            '                                                            <?php if ($roomprice->cost_single!=0 || $roomprice->cost_single!=NULL){?><option value="1" @if (old("room'+num+'") == '1') selected="selected" @endif>{{ Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_single }}</option><?php } else {?><option disabled>{{ Lang::get('core.roomtype_1') }} not available</option><?php }?>\n' +
            '                                                            <?php if ($roomprice->cost_double!=0 || $roomprice->cost_double!=NULL){?><option value="2" @if (old("room'+num+'") == '2') selected="selected" @endif>{{ Lang::get('core.roomtype_2') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_double }}</option><?php } else {?><option disabled>{{ Lang::get('core.roomtype_1') }} not available</option><?php }?>\n' +
            '                                                            <?php if ($roomprice->cost_triple!=0 || $roomprice->cost_triple!=NULL){?><option value="3" @if (old("room'+num+'") == '3') selected="selected" @endif>{{ Lang::get('core.roomtype_3') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_triple }}</option><?php } else {?><option disabled>{{ Lang::get('core.roomtype_1') }} not available</option><?php }?>\n' +
            '                                                            <?php if ($roomprice->cost_quad!=0 || $roomprice->cost_quad!=NULL){?><option value="4" @if (old("room'+num+'") == '4') selected="selected" @endif>{{ Lang::get('core.roomtype_4') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_quad }}</option><?php } else {?><option disabled>{{ Lang::get('core.roomtype_1') }} not available</option><?php }?>\n' +
            '\n' +
            '                                                        </select>\n' +
            '                                                    </div>\n' +
            '                                                </div>' +
            '                                    </div></fieldset></div>');

        $(".passport-detail").append('<div id="passport_traveller_'+num+'" class="row"><fieldset class="fieldset1"><legend class="legend1" id="passport'+num+'"></legend><div class="col-md-6"><div class="form-group"><label for="passportno" class=" control-label col-md-4 text-left"> {{ Lang::get("core.passportno") }} <span class="asterix"> * </span></label><div><input  type="text" name="passportno[]" id="passportno'+num+'" required class="form-control required" /></div></div><div class="form-group"><label for="Issue Date" class=" control-label col-md-4 text-left"> {{ Lang::get("core.dateofissue") }} <span class="asterix"> * </span></label><div><input  type="date" name="issuedate[]" id="issuedate'+num+'" required class="form-control required" placeholder="ex:0123456789" /></div></div><div class="form-group" ><label for="Issue Date" class=" control-label col-md-4 text-left"> {{ Lang::get("core.passportcountry") }} <span class="asterix"> * </span></label><div><select name="country[]" class="form-control" id="country'+num+'"><option value="">{{ Lang::get("core.choose_one") }}</option>@foreach ($countries as $country)<option value="{{$country->countryID}}">{{$country->country_name}}</option>@endforeach</select></div></div></div><div class="col-md-6"><div class="form-group"><label for="Date of Birth" class=" control-label col-md-4 text-left"> {{ Lang::get("core.dateofbirth") }} <span class="asterix"> * </span></label><div><input  type="date" name="dob[]" id="dob'+num+'" required class="form-control required" /></div></div><div class="form-group" ><label for="Expired Date" class=" control-label col-md-4 text-left"> {{ Lang::get("core.dateofexpiry") }} <span class="asterix"> * </span></label><div><input  type="date" name="expdate[]" id="expdate'+num+'" required class="form-control required" /></div></div></div></legend></div></div>');

        $(".summary-detail").append('<div class="row" id="summary_traveller_'+num+'"><button type="button" class="collapsiblesummary" id="summary_fullname'+num+'"></button><div class="content"><h5>Traveller Detail</h5><p>Email: <b id="summary_email'+num+'"></b></p><p>Phone number: <b id="summary_phoneno'+num+'"></b></p><p>Address: <b id="summary_addresstext'+num+'"></b></p><p>Country: <b id="summary_countryID'+num+'"></b></p><h5>Mahram Detail</h5><p>Mahram: <b id="summary_mahram_names'+num+'"></b></p><p>Mahram Relation: <b id="summary_mahram_relations'+num+'"></b></p><h5>Passport Detail</h5><p>No.: <b id="summary_passportno'+num+'"></b></p><p>Date of Birth: <b id="summary_dob'+num+'"></b></p><p>Issued Date: <b id="summary_issuedate'+num+'"></b></p><p>Expired Date: <b id="summary_expdate'+num+'"></b></p><p>Country of Issue: <b id="summary_country'+num+'"></b></p></div>');

        summaryCollapse("summary_fullname"+num);
    }

    function deleteTraveller(id) {
        num--;
        var total_price = '<?php echo $tourdatedetail['cost_price'];?>'*num;
        var total_deposit = '<?php echo $tourdatedetail['cost_deposit'];?>'*num;
        //document.getElementById('total_price1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_price;
        document.getElementById('total_deposit1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_deposit;
        document.getElementById('totaldeposit').value = total_deposit;
        document.getElementById('total_jemaah1').innerHTML=num;
        $( "#summary_"+id).remove();
        $( "#passport_"+id ).remove();
        $( "#mahram_"+id ).remove();
        $( "#detail_"+id ).remove();
    }

    function continueTraveller() {
        document.getElementById('mahram_tab').click();
    }

    function passData() {
        for (var i = 1; i <= num; i++) {
            if (document.getElementById('nameandsurname'+i) != null) {
                document.getElementById('mahram'+i).innerHTML = document.getElementById('nameandsurname'+i).value;
                document.getElementById('passport'+i).innerHTML = document.getElementById('nameandsurname'+i).value;
            }
        }
    }

    function validationJemaah() {
        var nameandsurnames = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("nameandsurname"+i).value!==""&&document.getElementById("nameandsurname"+i).value!==null) {
                boolean = true;
            }
            nameandsurnames.push(boolean);

        }

        var nameBoolean = true;
        for (var i = 0; i < nameandsurnames.length; i++) {
            nameBoolean = nameBoolean&&nameandsurnames[i];
        }
        
        var emails = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("email"+i).value!==""&&document.getElementById("email"+i).value!==null) {
                boolean = true;
            }
            emails.push(boolean);
        }

        var emailBoolean = true;
        for (var i = 0; i < emails.length; i++) {
            emailBoolean = emailBoolean&&emails[i];
        }

        var phones = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("phone"+i).value!==""&&document.getElementById("phone"+i).value!==null) {
                boolean = true;
            }
            phones.push(boolean);
        }

        var phoneBoolean = true;
        for (var i = 0; i < phones.length; i++) {
            phoneBoolean = phoneBoolean&&phones[i];
        }

        // var addresss = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("address"+i).value!==""&&document.getElementById("address"+i).value!==null) {
        //         boolean = true;
        //     }
        //     addresss.push(boolean);
        // }

        var addressBoolean = true;
        // for (var i = 0; i < addresss.length; i++) {
        //     addressBoolean = addressBoolean&&addresss[i];
        // }

        // var countryIDs = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("countryID"+i).value!==""&&document.getElementById("countryID"+i).value!==null) {
        //         boolean = true;
        //     }
        //     countryIDs.push(boolean);
        // }

        var countryIDBoolean = true;
        // for (var i = 0; i < countryIDs.length; i++) {
        //     countryIDBoolean = countryIDBoolean&&countryIDs[i];
        // }

        var nric = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("nric"+i).value!==""&&document.getElementById("nric"+i).value!==null) {
                boolean = true;
            }
            nric.push(boolean);
        }

        var nricBoolean = true;
        for (var i = 0; i < nric.length; i++) {
            nricBoolean = nricBoolean&&nric[i];
        }

        return nameBoolean&&emailBoolean&&phoneBoolean&&addressBoolean&&countryIDBoolean&&nricBoolean;

    }

    function validationBooking() {
        var rooms = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("room"+i).value!=="0"&&document.getElementById("room"+i).value!==null) {
                boolean = true;
            }
            rooms.push(boolean);
        }

        var roomBoolean = true;
        for (var i = 0; i < rooms.length; i++) {
            roomBoolean = roomBoolean&&rooms[i];
        }

        // var mahram_relations = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("mahram_relation"+i).value!==""&&document.getElementById("mahram_relation"+i).value!==null) {
        //         boolean = true;
        //     }
        //     mahram_relations.push(boolean);
        // }

        var mahram_relationBoolean = true;
        // for (var i = 0; i < mahram_relations.length; i++) {
        //     mahram_relationBoolean = mahram_relationBoolean&&mahram_relations[i];
        // }

        return roomBoolean&&mahram_relationBoolean;
    }

    function validationPassport() {
        var passportnos = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("passportno"+i).value!==""&&document.getElementById("passportno"+i).value!==null) {
                boolean = true;
            }
            passportnos.push(boolean);
        }

        var passportnoBoolean = true;
        for (var i = 0; i < passportnos.length; i++) {
            passportnoBoolean = passportnoBoolean&&passportnos[i];
        }

        var issuedates = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("issuedate"+i).value!==""&&document.getElementById("issuedate"+i).value!==null) {
                boolean = true;
            }
            issuedates.push(boolean);
        }

        var issuedateBoolean = true;
        for (var i = 0; i < issuedates.length; i++) {
            issuedateBoolean = issuedateBoolean&&issuedates[i];
        }

        var countrys = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("country"+i).value!=""&&document.getElementById("country"+i).value!==null) {
                boolean = true;
            }
            countrys.push(boolean);
        }

        var countryBoolean = true;
        for (var i = 0; i < countrys.length; i++) {
            countryBoolean = countryBoolean&&countrys[i];
        }

        var dobs = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("dob"+i).value!==""&&document.getElementById("dob"+i).value!==null) {
                boolean = true;
            }
            dobs.push(boolean);
        }

        var dobBoolean = true;
        for (var i = 0; i < dobs.length; i++) {
            dobBoolean = dobBoolean&&dobs[i];
        }

        var expdates = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("expdate"+i).value!==""&&document.getElementById("expdate"+i).value!==null) {
                boolean = true;
            }
            expdates.push(boolean);
        }

        var expdateBoolean = true;
        for (var i = 0; i < expdates.length; i++) {
            expdateBoolean = expdateBoolean&&expdates[i];
        }

        return passportnoBoolean&&issuedateBoolean&&countryBoolean&&dobBoolean&&expdateBoolean;

    }

    function bookingCheck() {
        if(!validationJemaah()){
            alert("Please fill in all the required information!");
            event.stopImmediatePropagation();
        }
    }

    function passportCheck() {
        var boolean = validationJemaah() && validationBooking();
        if(!boolean){
            alert("Please fill in all the required information!");
            event.stopImmediatePropagation();
        }
    }

    function summaryCheck() {
        var boolean = validationJemaah() && validationPassport() && validationBooking();
        if(!boolean){
            alert("Please fill in all the required information!");
            event.stopImmediatePropagation();
        }
    }

    function continueMahram() {
        document.getElementById('passport_tab').click();
    }

    function continuePassport() {
        document.getElementById('summary_tab').click();
    }

    function backMahram() {
        document.getElementById('traveller_tab').click();
    }

    function backPassport() {
        document.getElementById('mahram_tab').click();
    }

    function backSummary() {
        document.getElementById('passport_tab').click();
    }


</script>

<script>

    function countryName(id,i){
        var country=[];
        axios.get("country_name/"+id)
            .then(response => {
            return response.data;
    }).catch(e => {
            console.log(e);
    }).then(data => {
            country=data;
        document.getElementById(i).innerHTML = country['country_name'];
    }).catch(e => {
            console.log(e);
    });
    }

    function copyForms() {

        for (var i = 1; i <= num; i++) {
            if (document.getElementById("nameandsurname"+i) != null) {
                document.getElementById("summary_fullname"+i).innerHTML = document.getElementById("nameandsurname"+i).value;
            }

            if (document.getElementById("email"+i) != null) {
                document.getElementById("summary_email"+i).innerHTML = document.getElementById("email"+i).value;
            }

            if (document.getElementById("phone"+i) != null) {
                document.getElementById("summary_phoneno"+i).innerHTML = document.getElementById("phone"+i).value;
            }

            if (document.getElementById("address"+i) != null) {
                document.getElementById("summary_addresstext"+i).innerHTML = document.getElementById("address"+i).value;
            }

            if (document.getElementById("countryID"+i) != null) {
                countryName(document.getElementById("countryID"+i).value,"summary_countryID"+i);
            }

            if (document.getElementById("mahram_name"+i) != null) {
                document.getElementById('summary_mahram_names'+i).innerHTML = document.getElementById("mahram_name"+i).value;
            }

            if (document.getElementById("mahram_relation"+i) != null) {
                document.getElementById('summary_mahram_relations'+i).innerHTML = document.getElementById("mahram_relation"+i).value;
            }

            if (document.getElementById("passportno"+i) != null) {
                document.getElementById('summary_passportno'+i).innerHTML = document.getElementById("passportno"+i).value;
            }

            if (document.getElementById("dob"+i) != null) {
                document.getElementById('summary_dob'+i).innerHTML = document.getElementById("dob"+i).value;
            }

            if (document.getElementById("issuedate"+i) != null) {
                document.getElementById('summary_issuedate'+i).innerHTML = document.getElementById("issuedate"+i).value;
            }

            if (document.getElementById("expdate"+i) != null) {
                document.getElementById('summary_expdate'+i).innerHTML = document.getElementById("expdate"+i).value;
            }

            if (document.getElementById("country"+i) != null) {
                countryName(document.getElementById("country"+i).value,'summary_country'+i);
            }
        }
    }
</script>
<script>
    function addMahram(){
        var mahramname=document.getElementsByClassName("mahramname");
        for (var i = 1; i <= num; i++) {
            // document.getElementById("mahram_name"+i).innerHTML="";
            // for (var j = 1; j<=num; j++){
            //     var newopt = document.getElementById("nameandsurname"+j).value;
            //     $('<option>').val(newopt).text(newopt).appendTo(document.getElementById("mahram_name"+i));

            // }
        }
    }
</script>

<script>

    function summaryCollapse(id) {
        var coll = document.getElementById(id);
        coll.addEventListener("click",function(){
            this.classList.toggle("activesummary");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    }

    // to assign event to the first traveller
    summaryCollapse("summary_fullname1");

</script>

<script>
    function submitButton(){
        @if(Auth::check())
            document.forms["booking_form"].submit();
                @else
        var data = {treveller_password:document.getElementById("email1").value};
        axios.post("/bookpackage/checkcredential",data)
            .catch(function(error){
                console.log(error);
            }).then(function(response){
            return response.data;
        }).catch(function(e){
            console.log(e);
        }).then(function(data){
            if(data){
                var pass = prompt("You have an account. Please login detail.\nEmail : "+document.getElementById("email1").value+"\nPassword : ");
                var cred = {email:document.getElementById("email1").value,password:pass};
                if(pass != "")
                    axios.post("/bookpackage/checklogin",cred)
                        .catch(e=>{
                        console.log(e);
            }).then(response=>{
                    return response.data;
            }).catch(e=>{
                    console.log(e);
            }).then(data=>{
                    console.log(data);
                if(data){
                    document.forms["booking_form"].submit();
                }else{
                    alert("Login Failed. Please Check Your Credentials");
                }
            }).catch(e=>{
                    console.log(e);
            });
            }else{
                if (confirm("You don't have an account. Please click Ok if you want us to create an account for you.")) {
                    document.forms["booking_form"].submit();
                }
            }
        }).catch(function(e){
            console.log(e);
        });
        @endif
    }
</script>

<script>
    function calculateroom(){

        var room1=0;
        var room2=0;
        var room3=0;
        var room4=0;

        for (var i = 1; i <= num; i++) {
            var room = document.getElementById('room'+i).value;
            //alert(room);
            if(room === "1"){
                ++room1;
            } else if(room === "2"){
                ++room2;
            } else if(room === "3"){
                ++room3;
            } else if(room === "4") {
                ++room4;
            }

        }

        var totalroom1 = Math.ceil(room1);
        document.getElementById("roomtype1").value = totalroom1;
        var totalroom2 = Math.ceil(room2/2);
        document.getElementById("roomtype2").value = totalroom2;
        var totalroom3 = Math.ceil(room3/3);
        document.getElementById("roomtype3").value = totalroom3;
        var totalroom4 = Math.ceil(room4/4);
        document.getElementById("roomtype4").value = totalroom4;

        var price1 = room1*<?php echo $tourdatedetail['cost_single']; ?>;
        var price2 = room2*<?php echo $tourdatedetail['cost_double']; ?>;
        var price3 = room3*<?php echo $tourdatedetail['cost_triple']; ?>;
        var price4 = room4*<?php echo $tourdatedetail['cost_quad']; ?>;
        var totalprice = +price1 + +price2 + +price3 + +price4;

        document.getElementById('total_price1').innerHTML = '{{CURRENCY_SYMBOLS}} '+totalprice;
        document.getElementById('balance').value = totalprice;

        var total_deposit = '<?php echo $tourdatedetail['cost_deposit'];?>'*num;

        document.getElementById('total_deposit1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_deposit;
        document.getElementById('totaldeposit').value = total_deposit;

    }
</script>

@include('layouts.modern.footer')
