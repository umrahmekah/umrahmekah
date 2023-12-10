<div class="col-xs-12 col-sm-12 col-md-3">
	<div class="tour" style="margin: 20px 0px;">
		<a href="{{ url('/package?view=' . $package->tourID) }}" class="tour__container" 
			style="background-image: url(&quot;{{ $package_img }}&quot;); height: 200px;">
			{{-- @if($package->minPrice > 0)
			<div class="tour__discount">
				<span>25% Off</span>
			</div>
			@endif --}}
		</a>
		<div class="tour__content" style="height: 98px;">
			<div class="tour__detail">
				<h3 style="font-size: .8rem">{{ $package->tour_name }}</h3>
			</div>
			@if($package->minPrice > 0)
			<div class="tour__pricing">
				<p class="text-right" style="font-size: 13px;">{{ Lang::get('core.from') }}</p>
				{{-- <p class="tour__pricing-discount text-right">{{CURRENCY_SYMBOLS}}{{ $min_price }}</p> --}}
				<p class="tour__pricing-value text-right" style="font-size: 13px;">
	              <span style="font-size: 13px;">{{CURRENCY_SYMBOLS}}{{ number_format($package->minPrice, 2, '.', ',') }}</span>
	          	</p>
			</div>
			@endif
		</div>
		@if($package->type == 1)
		<a href="{{ url('/package?view=' . $package->tourID) }}" class="btn btn-primary" style="margin-bottom: 20px;">{{ Lang::get('core.view_package') }}</a>
		@else
		<a href="{{ url('/package-bound?view=' . $package->tourID) }}" class="btn btn-primary" style="margin-bottom: 20px;">{{ Lang::get('core.view_package') }}</a>
		@endif
	</div>
</div>