{{-- hide on screens smaller than md --}}
<div class="package__cover cover" style="background-image: url(&quot;{!! $package_image  !!}&quot;); height: 50vh;">
    <div class="package__title" style="top: 28vh;">
        <div>
            <div class="container">
                <div class="container">
                    <h1>{{ $detail->tour_name }}</h1>
                    {{-- <div class="package__title__rating mb-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>(2 Reviews)</span>
                    </div> --}}
                </div>
            </div>

            <div class="payment__bg">
                <div class="container">
                    <div class="payment__steps">
                        <a class="nav-link active payment__step xy-center" id="jemaah-detail-tab" data-toggle="tab"
                            href="#jemaah-detail" role="tab" aria-controls="jemaah-detail" aria-selected="true" onclick="backMahram();">
                            <div class="payment__icon__container">
                                <div class="payment__icon payment__icon-active xy-center text-center"><i class="fas fa-check"></i><span>1</span></div>
                            </div>
                            <span> {{ Lang::get('core.jemaahdetail') }}</span>
                        </a>
                        <a class="nav-link payment__step xy-center" id="booking-detail-tab" data-toggle="tab" href="#booking-detail"
                            role="tab" aria-controls="booking-detail" aria-selected="false" onclick="continueTraveller();">
                            <div class="payment__icon__container">
                                <div class="payment__icon xy-center text-center"><i class="fas fa-check"></i><span>2</span></div>
                            </div>
                            <span> {{ Lang::get('core.mahram_detail') }}</span>
                        </a>
                        <a class="nav-link payment__step xy-center" id="booking-summary-tab" data-toggle="tab" href="#booking-summary"
                            role="tab" aria-controls="booking-summary" aria-selected="false" onclick="continuePassport();">
                            <div class="payment__icon__container">
                                <div class="payment__icon xy-center text-center"><i class="fas fa-check"></i><span>3</span></div>
                            </div>
                            <span> {{ Lang::get('core.summary') }}</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>