{{-- hide on screens smaller than md --}}
<div class="package__cover cover d-none d-md-block" 
    style="background-image: url(&quot;{!! $package_image  !!}&quot;); height: 50vh;">
    <div class="package__title" style=" padding: 0px 10%;">
        <div>
            <div class="container position-relative" style="padding: 0px;">
                <div class="">
                    <div class="container" style="padding: 0px;">
                        <h1>{{ $package->tour_name }}</h1>
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
                <div class="booking__head-sticky" style="max-width: 350px; right: 0;">
                    <div class="booking__header p-3 text-center text-white font-weight-semibold">
                        {{ Lang::get('core.special_offer') }}
                    </div>
                    <div class="booking__total" style="margin-bottom: -18px;">
                        <i class="fas fa-tag text-white"></i>
                        <p class="booking__price mr-3 mb-0">
                            {{ Lang::get('core.from') }} {{-- <span>{{CURRENCY_SYMBOLS}} {{ moneyFormat($min_price) }}</span> --}}
                        </p>
                        <span class="booking__price-final">
                            @if($min_price) {{CURRENCY_SYMBOLS}} {{ moneyFormat($min_price) }} @else {{ Lang::get('core.notavailable') }} @endif
                            <i class="fas fa-info-circle booking__tooltip text-white"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
          

<div class="package__cover cover d-md-none" 
    style="background-image: url(&quot;{!! $package_image  !!}&quot;); height: 50vh;">
    <div class="package__title" style="top: 35vh !important;">
        <div class="ml-3 mr-3">
            <h1>
            {{ $package->tour_name }}
            </h1>
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
</div>