<div class="acc-body mahram-detail mt-3 ml-1" id="mahram-detail">
    <h5>
        <i class="fas fa-file-alt"></i>
        <span class="m-2">{{ Lang::get('core.mahram_detail') }}</span>
    </h5>

    <h6 class="mt-4 legend1 traveller1" id="mahram1"></h6>
    <hr>

    <div class="row">
        <div class="col-md-12">
            <span class="text-justify small">{{ Lang::get('core.form_booking_2_description') }}</span>
            <div>
                <select name='room[]' class="form-control" id="room1">
                    <option value="0" @if (old('room1')=='0' ) selected="selected" @endif>{{
                        Lang::get('core.choose_option', ['name' => 'Room']) }} *</option>
                    @if($roomprice->cost_single!=0 ||
                    $roomprice->cost_single!=NULL)
                        @if($roomprice->discount)
                            <option value="1" @if (old('room1')=='1' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->discount_price[0] }}</option>
                        @else
                            <option value="1" @if (old('room1')=='1' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->cost_single }}</option>
                        @endif
                    @endif
                    @if($roomprice->cost_double!=0
                    ||$roomprice->cost_double!=NULL)
                        @if($roomprice->discount)
                            <option value="1" @if (old('room1')=='1' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->discount_price[1] }}</option>
                        @else
                            <option value="2" @if (old('room1')=='2' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_2') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->cost_double }}</option>
                        @endif
                    @endif
                    @if($roomprice->cost_triple!=0 ||
                    $roomprice->cost_triple!=NULL)
                        @if($roomprice->discount)
                            <option value="1" @if (old('room1')=='1' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->discount_price[2] }}</option>
                        @else
                            <option value="3" @if (old('room1')=='3' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_3') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->cost_triple }}</option>
                        @endif
                    @endif
                    @if($roomprice->cost_quad!=0||
                    $roomprice->cost_quad!=NULL)
                        @if($roomprice->discount)
                            <option value="1" @if (old('room1')=='1' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->discount_price[3] }}</option>
                        @else
                            <option value="4" @if (old('room1')=='4' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_4') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->cost_quad }}</option>
                        @endif
                    @endif
                    @if($roomprice->cost_quint!=0||
                    $roomprice->cost_quint!=NULL)
                        @if($roomprice->discount)
                            <option value="1" @if (old('room1')=='1' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->discount_price[4] }}</option>
                        @else
                            <option value="5" @if (old('room1')=='5' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_5') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->cost_quint }}</option>
                        @endif
                    @endif
                    @if($roomprice->cost_sext!=0||
                    $roomprice->cost_sext!=NULL)
                        @if($roomprice->discount)
                            <option value="1" @if (old('room1')=='1' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_1') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->discount_price[5] }}</option>
                        @else
                            <option value="6" @if (old('room1')=='6' ) selected="selected" @endif>{{
                                Lang::get('core.roomtype_6') }} {{CURRENCY_SYMBOLS}}{{
                                $roomprice->cost_sext }}</option>
                        @endif
                    @endif
                    

                </select>
            </div>
        </div>

        @if(!$is_bound)
        <div class="form-group col-md-6">
            <div>
                <select name='mahram_name[]' class="form-control mahramname" id="mahram_name1">
                    <option value="0">{{ Lang::get('core.mahramsurname') }} *</option>
                </select>
            </div>
        </div>

        <div class="form-group col-md-6">
            <div>
                <select class="form-control required" required name="mahram_relation" id="mahram_relation1">
                    <option value="">{{ Lang::get('core.mahram_relation') }} *</option>
                    <option value="1">{{
                        Lang::get('core.mahram_relation_1') }}</option>
                    <option value="2">{{
                        Lang::get('core.mahram_relation_2') }}</option>
                    <option value="3">{{
                        Lang::get('core.mahram_relation_3') }}</option>
                    <option value="4">{{
                        Lang::get('core.mahram_relation_4') }}</option>
                    <option value="5">{{
                        Lang::get('core.mahram_relation_5') }}</option>
                    <option value="6">{{
                        Lang::get('core.mahram_relation_6') }}</option>
                    <option value="7">{{
                        Lang::get('core.mahram_relation_7') }}</option>
                    <option value="8">{{
                        Lang::get('core.mahram_not_required') }}</option>
                </select>
            </div>
        </div>
        @endif

        <input type="hidden" name="roomtype1" id="roomtype1" value="0">
        <input type="hidden" name="roomtype2" id="roomtype2" value="0">
        <input type="hidden" name="roomtype3" id="roomtype3" value="0">
        <input type="hidden" name="roomtype4" id="roomtype4" value="0">

    </div>
</div>
<div class="float-right">
    <button type="button" onclick="backMahram();" class="btn btn-default">{{ Lang::get('core.btn_back') }}</button>
    <button type="button" onclick="continueMahram();" class="btn btn-primary">{{ Lang::get('core.btn_continue') }}</button>
</div>