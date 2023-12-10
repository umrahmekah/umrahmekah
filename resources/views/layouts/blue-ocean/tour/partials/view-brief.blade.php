  <div class="bg-white3 d-none d-md-block" style="padding: 0px 10%;">
	<div class="container" style="padding: 0;">
		<div class="package__summary" style="padding-top: 20px; padding-bottom: 10px;">
			<div class="container" style="padding: 0;">
				<div class="row">
					<div class="col-xs-12 col-sm-6 mb-3">
						<span>
							<i class="far fa-clock"></i> {{ $package->total_days }} {{ Lang::get('core.days') }} - {{ $package->total_nights}} {{ Lang::get('core.nights') }}
						</span>
					</div>
					{{-- <div class="col-xs-12 col-sm-6 mb-3">
						<span>
							<i class="far fa-calendar-alt"></i> {{ Lang::get('core.category') }}: {{ SiteHelpers::formatLookUp($row->tourcategoriesID,'tourcategoriesID','1:def_tour_categories:tourcategoriesID:tourcategoryname') }}
						</span>
					</div> --}}
					<div class="col-xs-12 col-sm-6 mb-3">
						<span>
							<i class="fas fa-plane-departure"></i> {{ Lang::get('core.sector')}}: {{ $package->sector }}
						</span>
					</div>
					<div class="col-xs-12 col-sm-6 mb-3">
						<span>
							<i class="fas fa-plane-arrival"></i>{{ Lang::get('core.flight') }}: @if($package->flights != null){{ $package->flights->name }}@endif
						</span>
					</div>
					<div class="col-xs-12 col-sm-6 mb-3">
						<span>
							<i class="fas fa-users"></i>{{ Lang::get('core.transit') }}: {{ $package->transit }}
						</span>
					</div>
					<div class="col-xs-12 col-sm-6 mb-3">
						<span>
							<i class="fas fa-briefcase"></i> {{ Lang::get('core.baggage_limit') }}: {{ $package->baggage_limit }} kg
						</span>
					</div>
					<div class="col-xs-12 col-sm-6 mb-3">
						<span>
							<i class="fas fa-briefcase"></i> {{ Lang::get('core.capacity')}}: {{@$seat_available_total}} {{ Lang::get('core.spot_left')}}
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

