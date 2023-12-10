@push('scripts')
<script>
    // to assign event to the first traveller
    summaryCollapse("summary_fullname1");

    var num = 1;
    var no_of_person = {!! (!empty($no_of_person)) ? $no_of_person : 0 !!};

    //var total_price = '<?php echo $tourdatedetail['cost_price'];?>'*num;
    //document.getElementById('total_price1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_price;
    document.getElementById('total_jemaah1').innerHTML=num;

    // to add traveller if 'no_of_person' available
    if (no_of_person > 1) {
        for (var index_person = 1 ; index_person < no_of_person ; index_person++) {
            addTraveller();
        }
    }

    function addTraveller() {
        num++;
        var total_price = '<?php echo $tourdatedetail['cost_price'];?>'*num;
        var total_deposit = '<?php echo $tourdatedetail['cost_deposit'];?>'*num;
        //document.getElementById('total_price1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_price;
        document.getElementById('total_deposit1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_deposit;
        document.getElementById('totaldeposit').value = total_deposit;
        document.getElementById('total_jemaah1').innerHTML=num;

        $('div.payment__icon').removeClass('payment__icon-complete');
        $('#jemaah-detail-tab div.payment__icon').addClass('payment__icon-active');
        
        $( ".travel-form" ).append( '<h6 class="mt-4 traveller'+num+'">\n' +
            '                           <button class="traveller'+num+'" type="button" onclick="deleteTraveller('+num+');">\n' +
            '                               <i class="fa fa-window-close" aria-hidden="true"></i>\n' +
            '                           </button> \n' +
            '                           <span id="traveller_name">{{ Lang::get('core.jemaah') }} '+num+'</span>\n' +
            '                        </h6>\n' +
            '                        <hr class="traveller'+num+'">\n' +
            '                        <div class="row traveller'+num+'">\n' +
            '                           <div class="col-md-6 col-md-offset-6">\n' +
            '                               <select class="form-control" name="traveller_type" id="traveller_type'+num+'" onchange="changeTravellerType(this.value, this.id)">\n' +
            '                                   <option value="1">Adult</option>\n' +
            '                                   <option value="2">Child</option>\n' +
            '                                   <option value="3">Infant</option>\n' +
            '                               </select>\n' +
            '                           </div>\n' +
            '                       </div>\n' +
            '                        <div id="detail_traveller_'+num+'" class="row traveller'+num+'">\n' +
            '                           <div class="col-md-6">\n' +
            '                               <div class="row">\n' +
            '                                   <div class="col-md-6" id="name'+num+'">\n' +
            '                                       <div><input  type="text" name="nameandsurname[]" id="nameandsurname'+num+'" required class="form-control required" placeholder="{{ Lang::get("core.firstname") }} *"/></div>\n' +
            '                                   </div>\n' +
            '                                   <div class="col-md-6" id="lastname'+num+'">\n' +
            '                                       <div><input  type="text" name="last_name[]" id="last_name'+num+'" required class="form-control required" placeholder="{{ Lang::get("core.lastname") }} *"/></div>\n' +
            '                                   </div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group">\n' +
            '                                   <div><input  type="email" name="email[]" id="email'+num+'" class="form-control" placeholder="{{ Lang::get("core.email") }}"/></div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group" >\n' +
            '                                   <div><input  type="text" name="phone[]"" id="phone'+num+'" required class="form-control required" placeholder="{{ Lang::get("core.phone") }} * ex:0123456789" /></div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group" >\n' +
            '                                   <div><input  type="text" name="NRIC[]"" id="nric'+num+'" maxlength="20" required class="form-control required" placeholder="{{ Lang::get("core.nric") }} * ex:987654321098" /></div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group">\n' +
            '                                   <div>\n' +
            '                                       <select name="nationality[]"" class="form-control" id="nationality'+num+'">\n' +
            '                                       <option value="">{{ Lang::get("core.choose_option", ["name" => Lang::get("core.nationality")]) }}</option>\n' +
            '                                           @foreach ($countries as $country)\n' +
            '                                               <option value="{{$country->countryID}}">{{$country->country_name}}</option>\n' +
            '                                           @endforeach\n' +
            '                                       </select>\n' +
            '                                   </div>\n' +
            '                               </div>\n' +
            '                           </div>\n' +
            '                           <div class="col-md-6">\n' +
            '                               <div class="form-group">\n' +
            '                                   <div>\n' +
            '                                       <select required name="gender[]" class="form-control" id="gender'+num+'">\n' +
            '                                           <option value="">{{ Lang::get("core.choose_option", ["name" => Lang::get("core.gender")]) }} *</option>\n' +
            '                                           <option value="M">{{ Lang::get("core.male") }}</option>\n' +
            '                                           <option value="F">{{ Lang::get("core.female") }}</option>\n' +
            '                                       </select>\n' +
            '                                   </div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group" >\n' +
            '                                   <div><textarea name="address[]"" rows="5" id="address'+num+'" class="form-control" placeholder="{{ Lang::get("core.address") }}"></textarea></div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group">\n' +
            '                                   <div>\n' +
            '                                       <select name="countryID[]"" class="form-control" id="countryID'+num+'">\n' +
            '                                       <option value="">{{ Lang::get("core.choose_option", ["name" => Lang::get("core.country")]) }}</option>\n' +
            '                                           @foreach ($countries as $country)\n' +
            '                                               <option value="{{$country->countryID}}">{{$country->country_name}}</option>\n' +
            '                                           @endforeach\n' +
            '                                       </select>\n' +
            '                                   </div>\n' +
            '                               </div>\n' +
            '                           </div>\n' +
            '                         </div>' );

        $(".mahram-detail").append('<h6 class="legend1 mt-4 traveller'+num+'" id="mahram'+num+'"></h6><hr class="traveller'+num+'"><div id="mahram_traveller_'+num+'" class="row traveller'+num+'">\n' +
            '                           <div class="col-md-12">\n' +
            '                               <div>\n' +
            '                                   <select name="room[]" class="form-control" id="room'+num+'">\n' +
            '                                       <option value="0">{{ Lang::get("core.choose_option", ["name" => "Room"]) }} *</option>\n' +
            '                                       <?php if ($roomprice->cost_single!=0 || $roomprice->cost_single!=NULL){?><option value="1" @if (old("room'+num+'") == '1') selected="selected" @endif>{{ Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_single }}</option><?php } ?>\n' +
            '                                       <?php if ($roomprice->cost_double!=0 || $roomprice->cost_double!=NULL){?><option value="2" @if (old("room'+num+'") == '2') selected="selected" @endif>{{ Lang::get('core.roomtype_2') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_double }}</option><?php } ?>\n' +
            '                                       <?php if ($roomprice->cost_triple!=0 || $roomprice->cost_triple!=NULL){?><option value="3" @if (old("room'+num+'") == '3') selected="selected" @endif>{{ Lang::get('core.roomtype_3') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_triple }}</option><?php } ?>\n' +
            '                                       <?php if ($roomprice->cost_quad!=0 || $roomprice->cost_quad!=NULL){?><option value="4" @if (old("room'+num+'") == '4') selected="selected" @endif>{{ Lang::get('core.roomtype_4') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_quad }}</option><?php } ?>\n' +
            '                                       <?php if ($roomprice->cost_quint!=0 || $roomprice->cost_quint!=NULL){?><option value="5" @if (old("room'+num+'") == '5') selected="selected" @endif>{{ Lang::get('core.roomtype_5') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_quint }}</option><?php } ?>\n' +
            '                                       <?php if ($roomprice->cost_sext!=0 || $roomprice->cost_sext!=NULL){?><option value="6" @if (old("room'+num+'") == '6') selected="selected" @endif>{{ Lang::get('core.roomtype_6') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_sext }}</option><?php } ?>\n' +
            
            '                                   </select>\n' +
            '                               </div>\n' +
            '                           </div>\n' +
            '                           @if(!$is_bound)\n' +
            '                           <div class="form-group col-md-6">\n' +
            '                               <div>\n' +
            '                                   <select name="mahram_name[]" class="form-control mahramname" id="mahram_name'+num+'">\n' +
            '                                       <option value="0">{{ Lang::get("core.mahramsurname") }} *</option>\n' +
            '                                   </select>\n' +
            '                               </div>\n' +
            '                           </div>\n' +
            '                           <div class="form-group col-md-6">\n' +
            '                               <div>\n' +
            '                                   <select class="form-control required" required name="mahram_relation" id="mahram_relation'+num+'">\n' +
            '                                       <option value="">{{ Lang::get("core.mahram_relation") }} *</option>\n' +
            '                                       <option value="1">{{ Lang::get("core.mahram_relation_1") }}</option>\n' +
            '                                       <option value="2">{{ Lang::get("core.mahram_relation_2") }}</option>\n' +
            '                                       <option value="3">{{ Lang::get("core.mahram_relation_3") }}</option>\n' +
            '                                       <option value="4">{{ Lang::get("core.mahram_relation_4") }}</option>\n' +
            '                                       <option value="5">{{ Lang::get("core.mahram_relation_5") }}</option>\n' +
            '                                       <option value="6">{{ Lang::get("core.mahram_relation_6") }}</option>\n' +
            '                                       <option value="7">{{ Lang::get("core.mahram_relation_7") }}</option>\n' +
            '                                       <option value="8">{{ Lang::get("core.mahram_not_required") }}</option>\n' +
            '                                   </select>\n' +
            '                               </div>\n' +
            '                           </div>@endif\n' +
            '                        </div>');

        $(".passport-detail").append('<h6 class="legend1 mt-4 traveller'+num+'" id="passport'+num+'"></h6><hr class="traveller'+num+'">\n' +
            '                           <div id="passport_traveller_'+num+'" class="row traveller'+num+'">\n' +
            '                               <div class="col-md-6 form-group">\n' +
            '                                   <div><input placeholder="{{ Lang::get("core.passportno") }}" maxlength="20" type="text" name="passportno[]" id="passportno'+num+'" class="form-control" /></div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group col-md-6">\n' +
            '                                   <div><input placeholder="{{ Lang::get("core.dateofbirth") }} &nbsp;" type="date" name="dob[]" id="dob'+num+'" class="form-control" /></div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group col-md-6">\n' +
            '                                   <div><input placeholder="{{ Lang::get("core.dateofissue") }} &nbsp;" type="date" name="issuedate[]" id="issuedate'+num+'" class="form-control" /></div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group col-md-6">\n' +
            '                                   <div><input placeholder="{{ Lang::get("core.dateofexpiry") }} &nbsp;" type="date" name="expdate[]" id="expdate'+num+'" class="form-control" /></div>\n' +
            '                               </div>\n' +
            '                               <div class="form-group col-md-6">\n' +
            '                                   <div><input placeholder="{{ Lang::get("core.place_made") }} &nbsp;" type="text" name="place_made[]" id="place_made'+num+'" class="form-control" /></div>\n' +
            '                               </div>\n' +
            
            '                               <div class="form-group col-md-6">\n' +
            '                                   <div>\n' +
            '                                       <select name="country[]" class="form-control" id="country'+num+'">\n' +
            '                                           <option value="">{{ Lang::get("core.choose_option", ["name" => Lang::get("core.passportcountry")]) }}</option>\n' +
            '                                           @foreach ($countries as $country)\n' +
            '                                               <option value="{{$country->countryID}}">{{$country->country_name}}</option>\n' +
            '                                           @endforeach\n' +
            '                                       </select>\n' +
            '                                   </div>\n' +
            '                               </div>\n' +
            '                           </div>');

        $(".summary-detail").append('<h6 class="legend1 mt-4 traveller'+num+'" id="summary_fullname'+num+'"></h6><hr class="traveller'+num+'">\n' +
            '                       <div class="row content traveller'+num+'">\n' +
            '                           <div class="col-xs-12 col-sm-12 col-md-6">\n' +
            '                               <div class="form">\n' +
            '                                   <h6>\n' +
            '                                       <i class="fas fa-users"></i>\n' +
            '                                       <span class="m-2">{{ Lang::get('core.jemaahdetail') }}</span>\n' +
            '                                   </h6>\n' +
            '                                   <div class="form__container">\n' +
            '                                       <div class="">\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.lastname') }}: </span>\n' +
            '                                               <span id="summary_lastname'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.gender') }}: </span>\n' +
            '                                               <span id="summary_gender'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.email') }}: </span>\n' +
            '                                               <span id="summary_email'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.nric') }}: </span>\n' +
            '                                               <span id="summary_nric'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.phone') }}: </span>\n' +
            '                                               <span id="summary_phoneno'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.address') }}: </span>\n' +
            '                                               <span id="summary_addresstext'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.country') }}: </span>\n' +
            '                                               <span id="summary_countryID'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                       </div>\n' +
            '                                   </div>\n' +
            '                               </div>\n' +
            '                           </div>\n' +
            '                           <div class="col-xs-12 col-sm-12 col-md-6">\n' +
            '                               <div class="form">\n' +
            '                                   <h6>\n' +
            '                                       <i class="far fa-file-alt"></i>\n' +
            '                                       <span class="m-2">{{ Lang::get('core.mahram_detail') }}</span>\n' +
            '                                   </h6>\n' +
            '                                   <div class="form__container">\n' +
            '                                       <div class="">\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">Room: </span>\n' +
            '                                               <span id="summary_room'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           @if(!$is_bound)\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.mahramsurname') }}: </span>\n' +
            '                                               <span id="summary_mahram_names'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.mahram_relation') }}: </span>\n' +
            '                                               <span id="summary_mahram_relations'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           @endif\n' +
            '                                       </div>\n' +
            '                                   </div>\n' +
            '                               </div>\n' +
            '                           </div>\n' +
            '                           <div class="col-xs-12 col-sm-12 col-md-6 mt-4">\n' +
            '                               <div class="form">\n' +
            '                                   <h6>\n' +
            '                                       <i class="far fa-address-card"></i>\n' +
            '                                       <span class="m-2">{{ Lang::get('core.passportdetails') }}</span>\n' +
            '                                   </h6>\n' +
            '                                   <div class="form__container">\n' +
            '                                       <div class="">\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.passportno') }}: </span>\n' +
            '                                               <span id="summary_passportno'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.dateofbirth') }}: </span>\n' +
            '                                               <span id="summary_dob'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.dateofissue') }}: </span>\n' +
            '                                               <span id="summary_issuedate'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.dateofexpiry') }}: </span>\n' +
            '                                               <span id="summary_expdate'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                           <div class="payment__table">\n' +
            '                                               <span class="title">{{ Lang::get('core.passportcountry') }}: </span>\n' +
            '                                               <span id="summary_country'+num+'"></span>\n' +
            '                                           </div>\n' +
            '                                       </div>\n' +
            '                                   </div>\n' +
            '                               </div>\n' +
            '                           </div>\n' +
            '                       </div>');

        summaryCollapse("summary_fullname"+num);
    }

    function changeTravellerType(value, id) {
        id = id.replace(/[^0-9\.]+/g, "");

        if(value == 1){
            $("#email"+id).attr('disabled', false).val('');
            $("#phone"+id).attr('disabled', false).val('');

            const room_option_string = 
                "<option value='0'>{{ Lang::get('core.choose_option', ['name' => 'Room']) }} *</option>');"+
                "@if ($roomprice->cost_single!=0 || $roomprice->cost_single!=NULL) <option value='1' @if (old('room"+num+"') == '1') selected @endif>{{ Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_single }}</option> @else <option disabled>{{ Lang::get('core.roomtype_1') }} not available</option> @endif"+
                "@if ($roomprice->cost_double!=0 || $roomprice->cost_double!=NULL) <option value='2' @if (old('room"+num+"') == '2') selected @endif>{{ Lang::get('core.roomtype_2') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_double }}</option> @else <option disabled>{{ Lang::get('core.roomtype_2') }} not available</option> @endif"+
                "@if ($roomprice->cost_triple!=0 || $roomprice->cost_triple!=NULL) <option value='3' @if (old('room"+num+"') == '3') selected @endif>{{ Lang::get('core.roomtype_3') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_triple }}</option> @else <option disabled>{{ Lang::get('core.roomtype_3') }} not available</option> @endif"+
                "@if ($roomprice->cost_quad!=0 || $roomprice->cost_quad!=NULL) <option value='4' @if (old('room"+num+"') == '4') selected @endif>{{ Lang::get('core.roomtype_4') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_quad }}</option> @else <option disabled>{{ Lang::get('core.roomtype_4') }} not available</option> @endif"+
                "@if ($roomprice->cost_quint!=0 || $roomprice->cost_quint!=NULL) <option value='5' @if (old('room"+num+"') == '5') selected @endif>{{ Lang::get('core.roomtype_5') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_quint }}</option> @else <option disabled>{{ Lang::get('core.roomtype_5') }} not available</option> @endif"+
                "@if ($roomprice->cost_sext!=0 || $roomprice->cost_sext!=NULL) <option value='6' @if (old('room"+num+"') == '6') selected @endif>{{ Lang::get('core.roomtype_6') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_sext }}</option> @else <option disabled>{{ Lang::get('core.roomtype_6') }} not available</option> @endif";

            const room_option = document.getElementById("room"+id);
            room_option.innerHTML = room_option_string;
        } else {
            $("#email"+id).attr('disabled', true).val('Not Required');
            $("#phone"+id).attr('disabled', true).val('Not Required');

            if(value == 2){
                const room_option_string = 
                    "<option value='0'>Please Select</option>"+
                    "@if ($roomprice->cost_child!=0 || $roomprice->cost_child!=NULL) <option value='7' @if (old('room"+num+"') == '7') selected @endif>{{ Lang::get('core.roomtype_7') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_child }}</option> @endif"+
                    "@if ($roomprice->cost_child_wo_bed!=0 || $roomprice->cost_child_wo_bed!=NULL) <option value='8' @if (old('room"+num+"') == '8') selected @endif>{{ Lang::get('core.roomtype_8') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_child_wo_bed }}</option> @endif";

                const room_option = document.getElementById("room"+id);
                room_option.innerHTML = room_option_string;
            } else if(value == 3) {
                const room_option_string = 
                    "@if ($roomprice->cost_infant_wo_bed!=0 || $roomprice->cost_infant_wo_bed!=NULL) <option value='9' @if (old('room"+num+"') == '9') selected @endif>{{ Lang::get('core.roomtype_9') }} {{CURRENCY_SYMBOLS}}{{ $roomprice->cost_infant_wo_bed }}</option> @endif";

                const room_option = document.getElementById("room"+id);
                room_option.innerHTML = room_option_string;
            }
        }
    }

    function deleteTraveller(id) {
        var is_last = (id == num);

        num--;
        var total_price = '<?php echo $tourdatedetail['cost_price'];?>'*num;
        var total_deposit = '<?php echo $tourdatedetail['cost_deposit'];?>'*num;
        //document.getElementById('total_price1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_price;
        document.getElementById('total_deposit1').innerHTML = '{{CURRENCY_SYMBOLS}} '+total_deposit;
        document.getElementById('totaldeposit').value = total_deposit;
        document.getElementById('total_jemaah1').innerHTML=num;

        $( ".traveller" + id).remove();

        if (!is_last) {
            rearrangePosition(id);
        }
    }

    function rearrangePosition(index) {
        for (var i = index ; i < num + 1 ; i++) {
            $("#traveller_type" + (i + 1)).attr('id', "traveller_type" + i);
            $("#nameandsurname" + (i + 1)).attr('id', "nameandsurname" + i);
            $("#last_name" + (i + 1)).attr('id', "last_name" + i);
            $("#gender" + (i + 1)).attr('id', "gender" + i);
            $("#email" + (i + 1)).attr('id', "email" + i);
            $("#nric" + (i + 1)).attr('id', "nric" + i);
            $("#nationality" + (i + 1)).attr('id', "nationality" + i);
            $("#phone" + (i + 1)).attr('id', "phone" + i);
            $("#address" + (i + 1)).attr('id', "address" + i);
            $("#countryID" + (i + 1)).attr('id', "countryID" + i);
            $("#room" + (i + 1)).attr('id', "room" + i);
            $("#mahram_name" + (i + 1)).attr('id', "mahram_name" + i);
            $("#mahram_relation" + (i + 1)).attr('id', "mahram_relation" + i);
            $("#passportno" + (i + 1)).attr('id', "passportno" + i);
            $("#dob" + (i + 1)).attr('id', "dob" + i);
            $("#issuedate" + (i + 1)).attr('id', "issuedate" + i);
            $("#expdate" + (i + 1)).attr('id', "expdate" + i);
            $("#place_made" + (i + 1)).attr('id', "place_made" + i);
            $("#country" + (i + 1)).attr('id', "country" + i);
            $("#mahram" + (i + 1)).attr('id', "mahram" + i);
            $("#passport" + (i + 1)).attr('id', "passport" + i);
            $("#detail_traveller_" + (i + 1)).attr('id', "detail_traveller_" + i);
            $("#mahram_traveller_" + (i + 1)).attr('id', "detail_traveller_" + i);
            $("#passport_traveller_" + (i + 1)).attr('id', "detail_traveller_" + i);
            $("#summary_fullname" + (i + 1)).attr('id', "summary_fullname" + i);
            $("#summary_lastname" + (i + 1)).attr('id', "summary_lastname" + i);
            $("#summary_gender" + (i + 1)).attr('id', "summary_gender" + i);
            $("#summary_email" + (i + 1)).attr('id', "summary_email" + i);
            $("#summary_nric" + (i + 1)).attr('id', "summary_nric" + i);
            $("#summary_phoneno" + (i + 1)).attr('id', "summary_phoneno" + i);
            $("#summary_addresstext" + (i + 1)).attr('id', "summary_addresstext" + i);
            $("#summary_countryID" + (i + 1)).attr('id', "summary_countryID" + i);
            $("#summary_room" + (i + 1)).attr('id', "summary_room" + i);
            $("#summary_mahram_names" + (i + 1)).attr('id', "summary_mahram_names" + i);
            $("#summary_mahram_relations" + (i + 1)).attr('id', "summary_mahram_relations" + i);
            $("#summary_passportno" + (i + 1)).attr('id', "summary_passportno" + i);
            $("#summary_dob" + (i + 1)).attr('id', "summary_dob" + i);
            $("#summary_issuedate" + (i + 1)).attr('id', "summary_issuedate" + i);
            $("#summary_expdate" + (i + 1)).attr('id', "summary_expdate" + i);
            $("#summary_country" + (i + 1)).attr('id', "summary_country" + i);

            // ################### DO NOT CHANGE THIS ARRANGEMENT BELOW ###################
            $(".traveller" + (i + 1)).each(function () {
                $(this).removeClass("traveller" + (i + 1));
                $(this).addClass("traveller" + i);
            });

            $('#travel-form h6.traveller' + i + ' span#traveller_name').html('{{ Lang::get('core.jemaah') }} ' + i);
            $('#travel-form h6.traveller' + i + ' button.traveller' + i).attr('onclick', 'deleteTraveller(' + i + ');');
            // ################### DO NOT CHANGE THIS ARRANGEMENT ABOVE ###################
        }
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
            if (document.getElementById("nameandsurname"+i)&&document.getElementById("nameandsurname"+i).value!==""&&document.getElementById("nameandsurname"+i).value!==null) {
                boolean = true;
            }
            nameandsurnames.push(boolean);

        }

        var nameBoolean = true;
        for (var i = 0; i < nameandsurnames.length; i++) {
            nameBoolean = nameBoolean&&nameandsurnames[i];
        }

        var lastnames = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("last_name"+i)&&document.getElementById("last_name"+i).value!==""&&document.getElementById("last_name"+i).value!==null) {
                boolean = true;
            }
            lastnames.push(boolean);

        }

        var lastnameBoolean = true;
        for (var i = 0; i < lastnames.length; i++) {
            lastnameBoolean = lastnameBoolean&&lastnames[i];
        }
        
        var emails = [];
        for (var i = 1; i <= 1; i++) {
            var boolean = false;
            if (document.getElementById("email"+i)&&document.getElementById("email"+i).value!==""&&document.getElementById("email"+i).value!==null) {
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
            if (document.getElementById("phone"+i)&&document.getElementById("phone"+i).value!==""&&document.getElementById("phone"+i).value!==null) {
                boolean = true;
            }
            phones.push(boolean);
        }

        var phoneBoolean = true;
        for (var i = 0; i < phones.length; i++) {
            phoneBoolean = phoneBoolean&&phones[i];
        }

        var genders = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("gender"+i)&&document.getElementById("gender"+i).value!=="0"&&document.getElementById("gender"+i).value!==null) {
                boolean = true;
            }
            genders.push(boolean);
        }

        var genderBoolean = true;
        for (var i = 0; i < genders.length; i++) {
            genderBoolean = genderBoolean&&genders[i];
        }

        // var addresss = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("address"+i)&&document.getElementById("address"+i).value!==""&&document.getElementById("address"+i).value!==null) {
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
        //     if (document.getElementById("countryID"+i)&&document.getElementById("countryID"+i).value!==""&&document.getElementById("countryID"+i).value!==null) {
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
            if (document.getElementById("nric"+i)&&document.getElementById("nric"+i).value!==""&&document.getElementById("nric"+i).value!==null) {
                boolean = true;
            }
            nric.push(boolean);
        }

        var nricBoolean = true;
        for (var i = 0; i < nric.length; i++) {
            nricBoolean = nricBoolean&&nric[i];
        }

        return nameBoolean&&emailBoolean&&phoneBoolean&&addressBoolean&&countryIDBoolean&&nricBoolean&&lastnameBoolean&&genderBoolean;

    }

    function validationBooking() {
        var rooms = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("room"+i)&&document.getElementById("room"+i).value!=="0"&&document.getElementById("room"+i).value!==null) {
                boolean = true;
            }
            rooms.push(boolean);
        }

        var roomBoolean = true;
        for (var i = 0; i < rooms.length; i++) {
            roomBoolean = roomBoolean&&rooms[i];
        }

        @if(!$is_bound)
        var mahram_names = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("mahram_name"+i)&&document.getElementById("mahram_name"+i).value!=="0"&&document.getElementById("mahram_name"+i).value!==null) {
                boolean = true;
            }
            mahram_names.push(boolean);
        }

        var mahramNameBoolean = true;
        for (var i = 0; i < mahram_names.length; i++) {
            mahramNameBoolean = mahramNameBoolean&&mahram_names[i];
        }

        var mahram_relations = [];
        for (var i = 1; i <= num; i++) {
            var boolean = false;
            if (document.getElementById("mahram_relation"+i)&&document.getElementById("mahram_relation"+i).value!==""&&document.getElementById("mahram_relation"+i).value!==null) {
                boolean = true;
            }
            mahram_relations.push(boolean);
        }

        var mahram_relationBoolean = true;
        for (var i = 0; i < mahram_relations.length; i++) {
            mahram_relationBoolean = mahram_relationBoolean&&mahram_relations[i];
        }
        @endif

        @if(!$is_bound)
        return roomBoolean&&mahramNameBoolean&&mahram_relationBoolean;
        @else
        return roomBoolean;
        @endif
    }

    function validationPassport() {
        // var passportnos = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("passportno"+i)&&document.getElementById("passportno"+i).value!==""&&document.getElementById("passportno"+i).value!==null) {
        //         boolean = true;
        //     }
        //     passportnos.push(boolean);
        // }

        // var passportnoBoolean = true;
        // for (var i = 0; i < passportnos.length; i++) {
        //     passportnoBoolean = passportnoBoolean&&passportnos[i];
        // }

        // var issuedates = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("issuedate"+i)&&document.getElementById("issuedate"+i).value!==""&&document.getElementById("issuedate"+i).value!==null) {
        //         boolean = true;
        //     }
        //     issuedates.push(boolean);
        // }

        // var issuedateBoolean = true;
        // for (var i = 0; i < issuedates.length; i++) {
        //     issuedateBoolean = issuedateBoolean&&issuedates[i];
        // }

        // var countrys = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("country"+i)&&document.getElementById("country"+i).value!=""&&document.getElementById("country"+i).value!==null) {
        //         boolean = true;
        //     }
        //     countrys.push(boolean);
        // }

        // var countryBoolean = true;
        // for (var i = 0; i < countrys.length; i++) {
        //     countryBoolean = countryBoolean&&countrys[i];
        // }

        // var dobs = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("dob"+i)&&document.getElementById("dob"+i).value!==""&&document.getElementById("dob"+i).value!==null) {
        //         boolean = true;
        //     }
        //     dobs.push(boolean);
        // }

        // var dobBoolean = true;
        // for (var i = 0; i < dobs.length; i++) {
        //     dobBoolean = dobBoolean&&dobs[i];
        // }

        // var expdates = [];
        // for (var i = 1; i <= num; i++) {
        //     var boolean = false;
        //     if (document.getElementById("expdate"+i)&&document.getElementById("expdate"+i).value!==""&&document.getElementById("expdate"+i).value!==null) {
        //         boolean = true;
        //     }
        //     expdates.push(boolean);
        // }

        // var expdateBoolean = true;
        // for (var i = 0; i < expdates.length; i++) {
        //     expdateBoolean = expdateBoolean&&expdates[i];
        // }

        // return passportnoBoolean&&issuedateBoolean&&countryBoolean&&dobBoolean&&expdateBoolean;

        return true;

    }

    function bookingCheck() {
        if(!validationJemaah()){
            alert("Please fill in all the required information!");
            event.stopImmediatePropagation();

            return false;
        }

        return true;
    }

    function passportCheck() {
        var boolean = validationJemaah() && validationBooking();
        if(!boolean){
            alert("Please fill in all the required information!");
            event.stopImmediatePropagation();

            return false;
        }

        return true;
    }

    function summaryCheck() {
        var boolean = validationJemaah() && validationPassport() && validationBooking();
        if(!boolean){
            alert("Please fill in all the required information!");
            event.stopImmediatePropagation();

            return false;
        }

        return true;
    }

    function togglePaymentStep(active_detail) {
        $('.tab-pane').filter(':not(' + active_detail + ')').hide();
        $('.tab-pane').filter(':not(' + active_detail + ')').css('opacity', 0);

        $(active_detail).show();
        $(active_detail).css('opacity', 1);

        $('div.payment__icon').removeClass('payment__icon-active');

        if (!($(active_detail + '-tab div.payment__icon').hasClass('payment__icon-complete'))) {
            $(active_detail + '-tab div.payment__icon').addClass('payment__icon-active');
        }

        togglePaymentDetail(active_detail);
    }

    function togglePaymentDetail(active_detail) {
        if (active_detail == '#booking-summary') {
            $('.payment__detail').removeClass('hidden');
        } else {
            $('.payment__detail').addClass('hidden');
        }
    }
    
    function continueTraveller() {
        // document.getElementById('booking-detail-tab').click();

        var valid = bookingCheck();

        if (valid) {
            passData();
            addMahram();

            $('#jemaah-detail-tab div.payment__icon').addClass('payment__icon-complete');

            togglePaymentStep('#booking-detail');
        }
    }

    function continueMahram() {
        // document.getElementById('passport-detail-tab').click();

        var valid = passportCheck();

        if (valid) {
            passData();

            $('#booking-detail-tab div.payment__icon').addClass('payment__icon-complete');

            togglePaymentStep('#passport-detail');
        }
    }

    function continuePassport() {
        // document.getElementById('booking-summary-tab').click();

        var valid = summaryCheck();

        if (valid) {
            passData();
            copyForms();
            calculateroom();

            $('#passport-detail-tab div.payment__icon').addClass('payment__icon-complete');

            togglePaymentStep('#booking-summary');
        }
    }

    function backMahram() {
        // document.getElementById('jemaah-detail-tab').click();

        $('div.payment__icon').removeClass('payment__icon-active');

        togglePaymentStep('#jemaah-detail');
    }

    function backPassport() {
        // document.getElementById('booking-detail-tab').click();

        $('div.payment__icon').removeClass('payment__icon-active');

        togglePaymentStep('#booking-detail');
    }

    function backSummary() {
        // document.getElementById('passport-detail').click();

        $('div.payment__icon').removeClass('payment__icon-active');

        togglePaymentStep('#passport-detail');
    }

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

            if (document.getElementById("last_name"+i) != null) {
                document.getElementById("summary_lastname"+i).innerHTML = document.getElementById("last_name"+i).value;
            }

            if (document.getElementById("gender"+i) != null) {
                document.getElementById("summary_gender"+i).innerHTML = getGenderDesc(document.getElementById("gender"+i).value);
            }

            if (document.getElementById("email"+i) != null) {
                document.getElementById("summary_email"+i).innerHTML = document.getElementById("email"+i).value;
            }

            if (document.getElementById("nric"+i) != null) {
                document.getElementById("summary_nric"+i).innerHTML = document.getElementById("nric"+i).value;
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

            if (document.getElementById("room"+i) != null) {
                document.getElementById('summary_room'+i).innerHTML = getRoomName(document.getElementById("room"+i).value);
            }

            if (document.getElementById("mahram_name"+i) != null) {
                document.getElementById('summary_mahram_names'+i).innerHTML = document.getElementById("mahram_name"+i).value;
            }

            if (document.getElementById("mahram_relation"+i) != null) {
                document.getElementById('summary_mahram_relations'+i).innerHTML = getMahramRelationName(document.getElementById("mahram_relation"+i).value);
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

    function getGenderDesc (gender) {
        if (gender == 'M') {
            return '{{ Lang::get('core.male') }}';
        } else if (gender == 'F') {
            return '{{ Lang::get('core.female') }}';
        }
    }

    function getRoomName (type) {
        if (type == 1) {
            return '{{ Lang::get('core.roomtype_1') }}';
        } else if (type == 2) {
            return '{{ Lang::get('core.roomtype_2') }}';
        } else if (type == 3) {
            return '{{ Lang::get('core.roomtype_3') }}';
        } else if (type == 4) {
            return '{{ Lang::get('core.roomtype_4') }}';
        } else if (type == 5) {
            return '{{ Lang::get('core.roomtype_5') }}';
        } else if (type == 6) {
            return '{{ Lang::get('core.roomtype_6') }}';
        } else if (type == 7) {
            return '{{ Lang::get('core.roomtype_7') }}';
        } else if (type == 8) {
            return '{{ Lang::get('core.roomtype_8') }}';
        } else if (type == 9) {
            return '{{ Lang::get('core.roomtype_9') }}';
        }
    }

    function getMahramRelationName (type) {
        if (type == 1) {
            return '{{ Lang::get('core.mahram_relation_1') }}';
        } else if (type == 2) {
            return '{{ Lang::get('core.mahram_relation_2') }}';
        } else if (type == 3) {
            return '{{ Lang::get('core.mahram_relation_3') }}';
        } else if (type == 4) {
            return '{{ Lang::get('core.mahram_relation_4') }}';
        } else if (type == 5) {
            return '{{ Lang::get('core.mahram_relation_5') }}';
        } else if (type == 6) {
            return '{{ Lang::get('core.mahram_relation_6') }}';
        } else if (type == 7) {
            return '{{ Lang::get('core.mahram_relation_7') }}';
        } else if (type == 8) {
            return '{{ Lang::get('core.mahram_not_required') }}';
        }
    }

    function addMahram(){
        $('.mahramname').each(function () {
            var mahram_selected = $(this).val();

            $(this).empty().append('<option value="0">{{ Lang::get("core.mahramsurname") }} *</option>');

            for (var index_mahram = 1; index_mahram <= num; index_mahram++){
                var newopt = $("#nameandsurname" + index_mahram).val();
                $('<option>').val(newopt).text(newopt).appendTo($(this));

                if (mahram_selected == newopt) {
                    $(this).val(newopt);
                }
            }
        });
    }

    function summaryCollapse(id) {
        var coll = document.getElementById(id);
        if(null != coll) {
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
    }

    function submitButton(){
        @if(Auth::check())
            document.forms["booking_form"].submit();
        @else
            $(document).on("submit", "form#booking_form", function(e){
                e.preventDefault();
                e.stopPropagation();
                return false;
            });

            var data = {treveller_password:document.getElementById("email1").value};
            axios.post("/bookpackage/checkcredential",data)
                .catch(function(error){
                    console.log(error);
                }).then(function(data){
                    if(data){
                        toggleSignInModal(data);
                    } else{
                        if (confirm("You don't have an account. If you proceed, we will create an account for you.")) {
                            document.forms["booking_form"].submit();
                        }
                    }
                }).catch(function(e){
                    console.log(e);
                });
        @endif
    }

    function toggleSignInModal(flag) {
        if (flag) {
            // Show Modal
            $('#signInModal').modal('show');

            $("#signin-email").val($('#email1').val());
        } else {
            // Hide Modal
            $('#signInModal').modal('hide');

            $("#signin-email").val('');
            $("#signin-password").val('');
        }
    }

    function toggleTerms() {
        $('#termsModal').modal('show');
    }

    function toggleError() {
        $('#errorModal').modal('show');
    }

    @if(Session::has('failed'))
        toggleError();
    @endif

    function agreeTerms(value) {
        let button = document.getElementById("proceedButton");
        if (value) {
            button.disabled = false;
        }else{
            button.disabled = true;
        }
    }

    function proceedSignIn(){
        var signin_email = $("#signin-email").val();
        var signin_password = $("#signin-password").val();
        var credential = {email:signin_email, password:signin_password};

        if(signin_password != "") {
            axios.post("/bookpackage/checklogin", credential)
                .catch(e=>{
                    console.log(e);
                }).then(data=>{
                    console.log(data);
                    if(data){
                        // Hide modal
                        toggleSignInModal(false);

                        document.forms["booking_form"].submit();
                    } else {
                        alert("Login Failed. Please Check Your Credentials");
                    }
                }).catch(e=>{
                    console.log(e);
                });
        }
    }

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
@endpush