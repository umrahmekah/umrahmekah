<div class="acc-body booking-summary mt-3 ml-1" id="booking-summary">
    <h5>
        <i class="fas fa-clipboard-list"></i>
        <span class="m-2">{{ Lang::get('core.summary') }}</span>
    </h5>

    <div class="summary-detail">
        {{-- <div class="payment__card">
            <h6 class="text-center font-weight-bold">Please select your preferred additional services.</h6>
            <div class="payment__card__checkbox xy-center">
                <div class="input"><input type="checkbox" aria-label="Checkbox for following text input"></div><span
                    class="payment__card__checkbox-title text-left">Cleaning fee</span><span class="payment__card__checkbox-value text-blue text-right">$9
                    / Room</span>
            </div>
            <div class="payment__card__checkbox xy-center">
                <div class="input"><input type="checkbox" aria-label="Checkbox for following text input"></div><span
                    class="payment__card__checkbox-title text-left">Tip for tour guide</span><span class="payment__card__checkbox-value text-blue text-right">$20
                    / Person</span>
            </div>
            <div class="payment__card__checkbox xy-center">
                <div class="input"><input type="checkbox" aria-label="Checkbox for following text input"></div><span
                    class="payment__card__checkbox-title text-left">Entrance Ticket</span><span class="payment__card__checkbox-value text-blue text-right">$15
                    / Person</span>
            </div>
            <div class="payment__card__checkbox xy-center">
                <div class="input"><input type="checkbox" aria-label="Checkbox for following text input"></div><span
                    class="payment__card__checkbox-title text-left">Lunch Meal</span><span class="payment__card__checkbox-value text-blue text-right">$12
                    / Person</span>
            </div>
        </div> --}}
        <h6 class="mt-4" id="summary_fullname1"></h6>
        <hr>
        <div class="row content">
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form">
                    <h6>
                        <i class="fas fa-users"></i>
                        <span class="m-2">{{ Lang::get('core.jemaahdetail') }}</span>
                    </h6>
                    <div class="form__container">
                        <div class="">
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.lastname') }}: </span>
                                <span id="summary_lastname1"></span>
                            </div>
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.gender') }}: </span>
                                <span id="summary_gender1"></span>
                            </div>
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.email') }}: </span>
                                <span id="summary_email1"></span>
                            </div>
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.nric') }}: </span>
                                <span id="summary_nric1"></span>
                            </div>
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.phone') }}: </span>
                                <span id="summary_phoneno1"></span>
                            </div>
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.address') }}: </span>
                                <span id="summary_addresstext1"></span>
                            </div>
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.country') }}: </span>
                                <span id="summary_countryID1"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <div class="form">
                    <h6>
                        <i class="far fa-file-alt"></i>
                        <span class="m-2">{{ Lang::get('core.mahram_detail') }}</span>
                    </h6>
                    <div class="form__container">
                        <div class="">
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.adult_number') }}: </span>
                                <span id="summary_adult_number"></span>
                            </div>
                            @if(!$is_bound)
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.child_number') }}: </span>
                                <span id="summary_child_number"></span>
                            </div>
                            <div class="payment__table">
                                <span class="title">{{ Lang::get('core.infant_number') }}: </span>
                                <span id="summary_infant_number"></span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!---->
</div>
<div  class="float-right mt-4">
    <button type="button" onclick="backSummary();" class="btn btn-default">{{ Lang::get('core.btn_back') }}</button>
</div>