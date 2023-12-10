<input type="hidden" id="tour-id" value="">
<div class="layout__float" style="top: -150px; max-width: 350px; right: 0px;">
	<div class="booking mt-0">
		<div class="booking__head" style="max-width: 350px;">
			<div class="booking__header p-3 text-center text-white font-weight-semibold">
				{{ Lang::get('core.special_offer') }}
			</div>
			@if($isMobile == 0)
			<div class="booking__total">
				<i class="fas fa-tag text-white @if($isMobile == 0) ml-5 @endif "></i>
				<p class="booking__price mr-3 mb-0">From 
					@if($isMobile == 0) <span>{{CURRENCY_SYMBOLS}} {{ moneyFormat($min_price) }}</span> @endif
				</p>
				<span class="booking__price-final">
					{{CURRENCY_SYMBOLS}} {{ moneyFormat($min_price) }}
					<i class="fas fa-info-circle booking__tooltip mr-5"></i>
				</span>
			</div>
			@endif
		</div>
		<div class="booking__form">
			<div class="booking__section">
				<div class="booking__form__input">
					<i class="fas fa-calendar"></i>
					<div class="input input__dropdown">
						<select class="form-control" id="tour-date" onchange="tourDateChange(this.value)">
							<option value="">{{ Lang::get('core.choose_option', ['name' => Lang::get('core.date')]) }}</option>
							@foreach($tdate as $td)
							<option value="{{ $td['tourdateID'] }}">{{ SiteHelpers::TarihFormat($td['start']) }} - {{
								SiteHelpers::TarihFormat($td['end']) }}</option>
							@endforeach
						</select>
						<i class="fas fa-chevron-down"></i>
					</div>
				</div>
				<div class="booking__timeline">
					<span></span>
					<span class="booking__timeline-shade"></span>
				</div>
				<div class="booking__form__input">
					<i class="fas fa-bed"></i>
					<div class="input input__dropdown">
						<select class="form-control" id="tour-roomtype" onchange="tourRoomTypeChange(this.value);">
						</select>
						<i class="fas fa-chevron-down"></i>
					</div>
				</div>
				<div class="booking__timeline">
					<span></span>
					<span class="booking__timeline-shade"></span>
				</div>
				<div class="booking__form__input">
					<i class="fas fa-users"></i>
					<div class="input input__dropdown">
						<select class="form-control" id="tour-person" onchange="tourPersonChange(this.value);">
						</select>
						<i class="fas fa-chevron-down"></i>
					</div>
				</div>
			</div>
			<div class="booking__section" style="display: none;">
				<div class="booking__timeline">
					<span></span>
					<span class="booking__timeline-shade"></span>
				</div>
				<div class="booking__form__input">
					<i class="fas fa-check-circle"></i>
					<div class="input">
						<div>
							<a href="#" id="btn-proceed-booking" class="btn btn-primary">
								{{ Lang::get('core.proceed_booking') }}
							</a>
							{{-- @if(Auth::check())
							@else								
								<button type="button" data-toggle="modal" data-target="#registerUserModal" class="btn btn-primary">
									{{ Lang::get('core.proceed_booking') }}
								</button>
							@endif --}}
							<div id="registerUserModal" tabindex="-1" role="dialog" aria-labelledby="registerUserModalLabel" aria-hidden="true"
							 data-backdrop="false" class="modal fade">
								<div role="document" class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h5 id="registerUserModalLabel" class="modal-title text-center text">{{ Lang::get('core.proceed_booking') }}</h5>
											<button type="button" data-dismiss="modal" aria-label="Close" class="close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="container py-4">
												<div class="row">
													<div class="col-xs-12 col-sm-12 col-md-6 p-4">
														<form class="mb-3">
															<h3 class="font-uppercase font-normal font-weight-bold text-black3">Already a member?</h3>
															<div class="form-group">
																<label>Username</label>
																<input type="text" class="form-control">
															</div>
															<div class="form-group">
																<label>Password</label>
																<input type="password" class="form-control">
															</div>
															<button type="submit" class="btn btn-blue">sign in!</button>
														</form>
														<a href="#" class="link"> Forget Password?</a>
													</div>
													<div class="col-xs-12 col-sm-12 col-md-6 p-4">
														<h3 class="font-uppercase font-normal font-weight-bold text-black3 text-center">dont'have an account?
															create one.</h3>
														<p class="text-center font-small">When you book with an account, you will be able to track your payment
															status, track the confirmation and you can also rate the tour after you finished the tour.</p>
														<a href="#" class="btn btn-blue btn-block">sign up</a>
														<p class="text-center text-uppercase font-weight-bold my-3 text-black3 font-small mt-5">or continue as
															guest</p>
														<a href="/payment" class="btn btn-blue btn-block">continue as guest</a>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('layouts.blue-ocean.tour.partials.extra-info')
</div>



<script>
	console.log('{{ $isMobile }}');
</script>