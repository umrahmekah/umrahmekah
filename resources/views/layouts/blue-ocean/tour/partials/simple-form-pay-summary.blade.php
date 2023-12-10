<div class="payment__container">
    <div class="payment__summary">
        {{csrf_field()}}
        <input type="hidden" value="{{ $affiliate }}" name="affiliate">
        <input type="hidden" value="{{ app('request')->input('tourdateID') }}" name="tourdateID">
        <input type="hidden" value="{{ app('request')->input('tourID') }}" name="tourID">
        <input type="hidden" value="1" name="type">

        @if(Auth::check())
        <input type="hidden" value="{{ $user_id }}" name="userID">
        @endif

        <?php
            // generate booking now with random characters
            $bookingno1 = substr(str_shuffle(str_repeat("ABCDEFGHJKLMNPQRSTUVWYZ", 2)), 0, 2);
            $bookingno2 = substr(str_shuffle(str_repeat("123456789", 4)), 0, 4);
        ?>
        <input type="hidden" name="bookingsID" value="{{$bookingno1.$bookingno2}}">

        <!-- package summary -->
        <h5 class="text-uppercase mb-4">package details</h5>
        <div class="details-desc">
            <p><span class="text-default"><i class="fas fa-tree mr-4"></i>Umrah Name: </span>{{ $detail['tour_name'] }}</p>
            <p><span class="text-default"><i class="fas fa-tags mr-3"></i>Category: </span>{{ $detail->category->tourcategoryname }}</p>
            <p><span class="text-default"><i class="far fa-calendar-alt mr-4"></i>Date: </span>{{ $tourdatedetail['start'] }} TO {{$tourdatedetail['end'] }}</p>
            <p><span class="text-default"><i class="far fa-clock mr-4"></i>Duration: </span>{{ $detail['total_days'] }} DAYS AND {{$detail['total_nights'] }} NIGHT</p>
            <p><span class="text-default"><i class="fas fa-shipping-fast mr-3"></i>Transit: </span>{{ $detail['transit'] }}</p>
            <p><span class="text-default"><i class="fas fa-briefcase mr-4"></i>Baggage Limit: </span>{{ $detail['baggage_limit']}}KG</p>
            <input type="hidden" name="totaldeposit" id="totaldeposit">
        </div>
    </div>
    <div class="payment__price payment__detail hidden" style="background: white;">

        <!-- payment summary -->
        <h5 class="text-uppercase mb-4">payment details</h5>
        <div>
            <p>
                <span class="text-default"><i class="fas fa-users mr-3"></i>Number of Jemaah: </span>
                <span class="font-weight-bold text-default" id="total_jemaah1">                    
            </p>
            <p>
                <span class="text-default"><i class="far fa-money-bill-alt mr-3"></i>Deposit per Jemaah: </span>
                <span class="font-weight-bold text-default">{{CURRENCY_SYMBOLS}}{{$tourdatedetail->cost_deposit }}
            </p>
            {{-- <p>
                <span class=text-default"><i class="fas fa-money-bill-alt mr-3"></i>Total Deposit: </span>
                <span class="font-weight-bold text-default" id="total_deposit1">
            </p> --}}
        </div>
        <div class="payment__price-total xy-center">
            <span><i class="fas fa-tag"></i>Total Deposit</span>
            <span class="font-weight-bold font-medium text-dark">
                <span class="text-default" id="total_deposit1"></span>
            </span>
        </div>
        <input type="hidden" name="balance" id="balance">
    </div>
    <button class="payment__button payment__detail hidden btn btn-primary btn-block" onclick="toggleTerms();">
        <span>@if(CNF_BILLPLZAPIKEY) BOOK AND PAY DEPOSIT @else BOOK AND PAY LATER @endif</span>
    </button>
</div>

<div id="signInModal" tabindex="-1" role="dialog" aria-labelledby="signInModalLabel" aria-hidden="true"
    data-backdrop="false" class="modal fade">
    <div role="document" class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="signInModalLabel" class="modal-title text-center text">{{ Lang::get('core.signin') }}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container py-4">
                    <div class="col-xs-12 col-sm-12 p-4">
                        <form class="mb-3">
                            <h3 class="font font-normal font-weight-bold text-black3">You have an account. Please login.</h3>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="text" disabled value="" id="signin-email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" id="signin-password" class="form-control">
                            </div>
                            <button type="button" class="btn btn-blue" onclick="proceedSignIn();">sign in!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true"
    data-backdrop="false" class="modal fade">
    <div role="document" class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="termsModalLabel" class="modal-title text-center text">{{ Lang::get('core.tandc') }}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container py-4">
                    <div class="col-xs-12 col-sm-12 p-4" style="background-color: #e2e2e2; max-height: 350px; overflow-y: scroll;">
                        {!! $detail->tandc->tandc !!}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <input type="checkbox" name="agree" id="agree" onchange="agreeTerms(this.checked)"> I have read the Terms And Condition above and agree.
                        <button type="button" id="proceedButton" class="btn btn-primary" onclick="submitButton();" disabled>Proceed</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>