@include('layouts.modern.header')

<div class="container" style="width: 100% !important;">

    <div class="stepwizard">
        <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step">
                <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
                <p>{{ Lang::get('core.jemaahdetail') }}</p>
            </div>
            {{--<div class="stepwizard-step">--}}
                {{--<a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>--}}
                {{--<p>{{ Lang::get('core.mahram_detail') }}</p>--}}
            {{--</div>--}}
            <div class="stepwizard-step">
                <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
                <p>{{ Lang::get('core.passportdetails') }}</p>
            </div>
        </div>
    </div>

    <form role="form" action="/bookPackage/setSession" method="post">
        <div class="row setup-content" id="step-1">
            <div class="col-xs-12">
                <div class="col-md-12 field_wrapper" >

                    {{csrf_field()}}

                    <input type="hidden" value="{{ app('request')->input('tourdateID') }}" name="tourdateID">
                    <input type="hidden" value="{{ app('request')->input('tourID') }}" name="tourID">

                    {{--generate booking now with random characters--}}
                    <?php
                    $bookingno1 = substr(str_shuffle(str_repeat('ABCDEFGHJKLMNPQRSTUVWYZ', 4)), 0, 4);
                    $bookingno2 = substr(str_shuffle(str_repeat('123456789', 6)), 0, 6);
                    ?>
                    <input type="hidden" name="bookingsID" value="{{$bookingno1.$bookingno2}}">


                    <h3> {{ Lang::get('core.jemaahdetail') }} 1</h3><br>
                    <div class="form-group col-md-4" id="name">
                        <label for="Name & Surname" class=" control-label col-md-4 text-left"> {{ Lang::get('core.namesurname') }} <span class="asterix"> * </span></label>
                        @if(Auth::check())
                            <div>
                                <input  type='text' name='nameandsurname[]' id='nameandsurname' value='{{$user['first_name']}}' required class='form-control required' />
                            </div>
                        @else
                            <div>
                                <input  type='text' name='nameandsurname[]' id='nameandsurname' value='' required class='form-control required' />
                            </div>
                        @endif
                    </div>
                    <div class="form-group col-md-4" id="email">
                        <label for="Email" class=" control-label col-md-4 text-left"> {{ Lang::get('core.email') }} <span class="asterix"> * </span></label>
                        @if(Auth::check())
                            <div>
                                <input  type='email' name='email[]' id='email' value='{{$user['email']}}' required class='form-control required' />
                            </div>
                        @else
                            <div>
                                <input  type='email' name='email[]' id='email' value='' required class='form-control required' />
                            </div>
                        @endif
                    </div>
                    <div class="form-group  col-md-4" >
                        <label for="Phone" class=" control-label col-md-4 text-left"> {{ Lang::get('core.phone') }} <span class="asterix"> * </span></label>
                        <div>
                            <input  type='text' name='phone[]' id='phone' value='' required class='form-control required' placeholder="ex:0123456789" />
                        </div>
                    </div>
                    <div class="form-group  col-md-4" >
                        <label for="Address" class=" control-label col-md-4 text-left"> {{ Lang::get('core.address') }}</label>
                        <div>
                            <textarea name='address[]' rows='5' id='address' class='form-control '></textarea>
                        </div>
                    </div>
                    <div class="form-group col-md-4" id="city">
                        <label for="City" class=" control-label col-md-4 text-left"> {{ Lang::get('core.city') }} </label>
                        <div>
                            <input  type='text' name='city[]' id='city' class='form-control ' />
                        </div>
                    </div>
                    <div class="form-group col-md-4 " >
                        <label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get('core.country') }} </label>
                        <div>
                            <select name='countryID[]' class="form-control" id="countryID">
                                <option>{{ Lang::get('core.choose_one') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{$country->countryID}}">{{$country->country_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @if(Auth::check())
                        <div class="form-group col-md-4" hidden>
                            <label for="Country" class=" control-label col-md-6 text-left"> {{ Lang::get('core.account') }} </label>
                            <select name='acc' class="form-control">
                                <option value="1">{{ Lang::get('core.yes') }}</option>
                                <option value="0" disabled>{{ Lang::get('core.no') }}</option>
                            </select>
                        </div>
                    @else
                        <div class="form-group col-md-4">
                            <label for="Country" class=" control-label col-md-6 text-left"> {{ Lang::get('core.account') }} </label>
                            <select name='acc' class="form-control">
                                <option value="1">{{ Lang::get('core.yes') }}</option>
                                <option value="0">{{ Lang::get('core.no') }}</option>
                            </select>
                        </div>
                    @endif
                    <div class="form-group col-md-4">
                        <div>
                            <label for="Country" class=" control-label col-md-6 text-left"> {{ Lang::get('core.more') }} </label><br>
                            <a href="javascript:void(0);" class="add_button" title="Add field"><button class="btn btn-primary" type="button">{{ Lang::get('core.add_jemaah') }}</button></a>
                        </div>
                    </div>

                </div>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button">{{ Lang::get('core.next') }}</button>
            </div>
        </div>
        {{--<div class="row setup-content" id="step-2">--}}
            {{--<div class="col-xs-12">--}}
                {{--<div class="col-md-12">--}}
                    {{--<h3> {{ Lang::get('core.mahram_detail') }}</h3>--}}
                    {{--<div class="form-group col-md-6">--}}
                        {{--<label class="control-label">{{ Lang::get('core.mahram') }}</label>--}}
                        {{--<select class="form-control required" required id="mahram_name" name="mahram_name">--}}
                            {{--<option>{{ Lang::get('core.choose_one') }}</option>--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<div class="form-group col-md-6">--}}
                        {{--<label class="control-label">{{ Lang::get('core.mahram_relation') }}</label>--}}
                        {{--<select class="form-control required" required name="mahram_relation">--}}
                            {{--<option>{{ Lang::get('core.mahram_relation_1') }}</option>--}}
                            {{--<option>{{ Lang::get('core.mahram_relation_2') }}</option>--}}
                            {{--<option>{{ Lang::get('core.mahram_relation_3') }}</option>--}}
                            {{--<option>{{ Lang::get('core.mahram_relation_4') }}</option>--}}
                            {{--<option>{{ Lang::get('core.mahram_relation_5') }}</option>--}}
                            {{--<option>{{ Lang::get('core.mahram_relation_6') }}</option>--}}
                            {{--<option>{{ Lang::get('core.mahram_relation_7') }}</option>--}}
                        {{--</select>--}}
                    {{--</div>--}}
                    {{--<button class="btn btn-primary prevBtn btn-lg pull-left" type="button">{{ Lang::get('core.previous') }}</button>--}}
                    {{--<button class="btn btn-primary nextBtn btn-lg pull-right" type="button">{{ Lang::get('core.next') }}</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="row setup-content" id="step-2">
            <div class="col-xs-12">
                <div class="col-md-12 field_wrapper_2">
                    <h3> {{ Lang::get('core.passportdetails') }}</h3>

                    <fieldset>
                        <h3> </h3><br>
                        <div class="form-group col-md-4" id="name">
                            <label for="Passportno" class=" control-label col-md-5 text-left"> {{ Lang::get('core.passportno') }} <span class="asterix"> * </span></label>
                            <div >
                                <input  type='text' name='passportno[]' id='passportno' value='' required class='form-control required' />
                            </div>
                        </div>
                        <div class="form-group col-md-4 " id="email">
                            <label for="Dob" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dateofbirth') }} <span class="asterix"> * </span></label>
                            <div>
                                <input  type='date' name='dob[]' id='dob' value='' required class='form-control required' />
                            </div>
                        </div>
                        <div class="form-group  col-md-4" >
                            <label for="Issuedate" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dateofissue') }} <span class="asterix"> * </span></label>
                            <div>
                                <input  type='date' name='issuedate[]' id='issuedate' value='' required class='form-control required' />
                            </div>
                        </div>
                        <div class="form-group col-md-4" >
                            <label for="Expdate" class=" control-label col-md-4 text-left"> {{ Lang::get('core.dateofexpiry') }} <span class="asterix"> * </span></label>
                            <div>
                                <input  type='date' name='expdate[]' rows='2' id='expdate' class='form-control '>
                            </div>
                        </div>
                        <div class="form-group  col-md-4" >
                            <label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get('core.passportcountry') }} </label>
                            <div>
                                <select name='country[]' class="form-control" id="country">
                                    <option>{{ Lang::get('core.choose_one') }}</option>
                                    @foreach ($countries as $country)
                                        <option value="{{$country->countryID}}">{{$country->country_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </fieldset>


                </div>
                <button class="btn btn-primary prevBtn btn-lg pull-left" type="button">Previous</button>
                <button class="btn btn-success btn-lg pull-right" type="submit">Submit</button>
            </div>
        </div>

    </form>

</div>
@include('layouts.modern.footer')

<script>

    $(document).ready(function () {
        var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn'),
            allPrevBtn = $('.prevBtn'),
            allNextBtn2 = $('.nextBtn2'),
            allNextBtn3 = $('.nxtBtn3');

        allWells.hide();

        navListItems.click(function (e) {
            e.preventDefault();
            var $target = $($(this).attr('href')),
                $item = $(this);

            if (!$item.hasClass('disabled')) {
                navListItems.removeClass('btn-primary').addClass('btn-default');
                $item.addClass('btn-primary');
                allWells.hide();
                $target.show();
                $target.find('input:eq(0)').focus();
            }
        });

        allPrevBtn.click(function(){
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                prevStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().prev().children("a");

            prevStepWizard.removeAttr('disabled').trigger('click');
        });

        allNextBtn.click(function(){
            var curStep = $(this).closest(".setup-content"),
                curStepBtn = curStep.attr("id"),
                nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
                curInputs = curStep.find("input[type='text'],input[type='url']"),
                isValid = true;

            $(".form-group").removeClass("has-error");
            for(var i=0; i<curInputs.length; i++){
                if (!curInputs[i].validity.valid){
                    isValid = false;
                    $(curInputs[i]).closest(".form-group").addClass("has-error");
                }
            }

            if (isValid)
                nextStepWizard.removeAttr('disabled').trigger('click');
        });

        allNextBtn2.click(function(){
            //alert('test');
            var x = document.getElementById("mahram_name");
            console.log('a');
            var option = document.createElement("option");
            //var name = document.getElementById("nameandsurname").value;
            option.text = document.getElementById("nameandsurname").value;
            x.remove(option);
            x.add(option);
        })

//        allNextBtn3.click(function(){
//            document.getElementById("mahram_name1").value= document.getElementById("mahram_name").value;
//        })

        $('div.setup-panel div a.btn-primary').trigger('click');
    });
</script>

<script type="text/javascript">
    $(document).ready(function(){
        var maxField = 20; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var wrapper2 = $('.field_wrapper_2');
        var fieldHTML = '<div><h3> {{ Lang::get("core.jemaahdetail") }} </h3><br>' +
            '<div class="form-group col-md-4" id="name">\n' +
            '    <label for="Name & Surname" class=" control-label col-md-4 text-left"> {{ Lang::get("core.namesurname") }} <span class="asterix"> * </span></label>\n' +
            '    <div>\n' +
            '        <input  type="text" name="nameandsurname[]" id="nameandsurname" value="" required class="form-control required" />\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="form-group col-md-4" id="email">\n' +
            '    <label for="Email" class=" control-label col-md-4 text-left"> {{ Lang::get("core.email") }} <span class="asterix"> * </span></label>\n' +
            '    <div>\n' +
            '        <input  type="text" name="email[]" id="email" value="" required class="form-control required" />\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="form-group  col-md-4" >\n' +
            '    <label for="Phone" class=" control-label col-md-4 text-left"> {{ Lang::get("core.phone") }} <span class="asterix"> * </span></label>\n' +
            '    <div>\n' +
            '        <input  type="text" name="phone[]" id="phone" value="" required class="form-control required" />\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="form-group  col-md-4" >\n' +
            '    <label for="Address" class=" control-label col-md-4 text-left"> {{ Lang::get("core.address") }} </label>\n' +
            '    <div>\n' +
            '        <textarea name="address[]" rows="3" id="address" class="form-control "></textarea>\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="form-group col-md-4" id="city">\n' +
            '    <label for="City" class=" control-label col-md-4 text-left"> {{ Lang::get("core.city") }} </label>\n' +
            '    <div>\n' +
            '        <input  type="text" name="city[]" id="city" class="form-control " />\n' +
            '    </div>\n' +
            '</div>' +
            '<div class="form-group col-md-4 " >\n' +
            '                        <label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get("core.country") }} </label>\n' +
            '                        <div>\n' +
            '                            <select name="countryID[]" class="form-control">\n' +
            '                                <option>Please choose one</option>\n' +
            '                                   @foreach ($countries as $country)\n' +
            '                                    <option value="{{$country->countryID }}">{{$country->country_name}}</option>\n' +
            '                                @endforeach' +
            '                            </select>\n' +
            '                        </div>\n' +
            '                    </div>' +
            '<a href="javascript:void(0);" class="remove_button" title="Remove field"><button class="btn btn-primary pull-right" type="button">{{ Lang::get("core.remove_jemaah") }}</button></a></div>'; //New input field html
        var fieldHTML2 = '<div><h3> {{ Lang::get("core.passportdetails") }}</h3>\n' +
            '\n' +
            '                    <fieldset>\n' +
            '                        <h3> </h3><br>\n' +
            '                        <div class="form-group col-md-4" id="name">\n' +
            '                            <label for="Passportno" class=" control-label col-md-5 text-left"> {{ Lang::get("core.passportno") }} <span class="asterix"> * </span></label>\n' +
            '                            <div >\n' +
            '                                <input  type="text" name="passportno[]" id="passportno" value="" required class="form-control required" />\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                        <div class="form-group col-md-4 " id="email">\n' +
            '                            <label for="Dob" class=" control-label col-md-4 text-left"> {{ Lang::get("core.dateofbirth") }} <span class="asterix"> * </span></label>\n' +
            '                            <div>\n' +
            '                                <input  type="date" name="dob[]" id="dob" value="" required class="form-control required" />\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                        <div class="form-group  col-md-4" >\n' +
            '                            <label for="Issuedate" class=" control-label col-md-4 text-left"> {{ Lang::get("core.dateofissue") }} <span class="asterix"> * </span></label>\n' +
            '                            <div>\n' +
            '                                <input  type="date" name="issuedate[]" id="issuedate" value="" required class="form-control required" />\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                        <div class="form-group col-md-4" >\n' +
            '                            <label for="Expdate" class=" control-label col-md-4 text-left"> {{ Lang::get("core.dateofexpiry") }} <span class="asterix"> * </span></label>\n' +
            '                            <div>\n' +
            '                                <input  type="date" name="expdate[]" rows="2" id="expdate" class="form-control ">\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                        <div class="form-group  col-md-4" >\n' +
            '                            <label for="Country" class=" control-label col-md-4 text-left"> {{ Lang::get("core.passportcountry") }} </label>\n' +
            '                            <div>\n' +
            '                                <select name="country[]" class="form-control" id="country">\n' +
            '                                    <option>{{ Lang::get("core.choose_one") }}</option>\n' +
            '                                    @foreach ($countries as $country)\n' +
            '                                        <option value="{{$country->countryID}}">{{$country->country_name}}</option>\n' +
            '                                    @endforeach\n' +
            '                                </select>\n' +
            '                            </div>\n' +
            '                        </div>\n' +
            '                    </fieldset></div>';
        var x = 1; //Initial field counter is 1
        $(addButton).click(function(){ //Once add button is clicked
            if(x < maxField){ //Check maximum number of input fields
                x++; //Increment field counter
                //alert(x);
                $(wrapper).append(fieldHTML); // Add field html
                $(wrapper2).append(fieldHTML2);
            }
        });
        $(wrapper).on('click', '.remove_button', function(e){ //Once remove button is clicked
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
</script>

{{--<script>--}}
    {{--function test() {--}}
        {{--var formData = $('form').serializeArray();--}}
        {{--axios.post('/bookPackage/ajaxsession', formData)--}}
        {{--.then(response => {--}}
            {{--console.log(response.data);--}}
            {{--return response.data;--}}
        {{--}).catch(e => {--}}
            {{--console.log(e);--}}
        {{--})--}}
        {{--.then(data => {--}}
            {{--window.location.href = "/user/login";--}}
        {{--}).catch(e => {--}}
            {{--console.log(e);--}}
        {{--});--}}
    {{--}--}}
{{--</script>--}}


