@push('scripts')
	<script type="text/javascript">
		function subcribeNewsletter()
		{
			$.post('{{ route('api.subscribe-newsletter') }}', {email: $('#newsletter-email').val() }, function(data, textStatus, xhr) {
				if(data.message) {
					alert(data.message);	
				}
			});
		}
	</script>
@endpush

<div class="home__cta" style="padding-right: 10%; padding-left: 10%;">
	<div class="container">
		<div class="row">
			@if(true == config('templates.blue-ocean.settings.section-five.activities.enabled'))
				<div class="col-xs-12 col-sm-12 col-md-6">
					<h3>
						<i class="fas fa-sliders-h"></i> {{ Lang::get('core.browse_tours_activity') }}
					</h3>
					<div class="home__cta__list">
						<ul class="ul-none">
							@foreach (config('templates.blue-ocean.settings.section-five.activities.first') as $activity)
								<li>
									<a href="{{ $activity['url'] }}">{{ $activity['label'] }}</a>
								</li>
							@endforeach
						</ul>
						<ul class="ul-none">
							@foreach (config('templates.blue-ocean.settings.section-five.activities.second') as $activity)
								<li>
									<a href="{{ $activity['url'] }}">{{ $activity['label'] }}</a>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			@endif
			@if(true == config('templates.blue-ocean.settings.section-five.newsletter.enabled'))
			<div class="col-xs-12 col-sm-12 col-md-6">
				<h3>
					<i class="far fa-envelope"></i> {{ config('templates.blue-ocean.settings.section-five.newsletter.labels.title') }}
				</h3>
				<p>{{ config('templates.blue-ocean.settings.section-five.newsletter.labels.message') }}</p>
				<div class="home__newsletter">
					<input id="newsletter-email" type="email" placeholder="{{ config('templates.blue-ocean.settings.section-five.newsletter.labels.placeholder') }}" class="form-control">
						<button onclick="subcribeNewsletter()" class="btn btn-primary">{{ config('templates.blue-ocean.settings.section-five.newsletter.labels.button') }}</button>
					</div>
					<div class="list-inline">
						<ul class="ul-none">
							@foreach (config('templates.blue-ocean.settings.section-five.newsletter.socials') as $social)
								@if(!empty($social['url']) && '#' != $social['url'])
									<li class="list-inline-item">
										<a href="{{ $social['url'] }}" target="_blank">
											<i class="{{ $social['icon'] }}"></i>
										</a>
									</li>
								@endif
							@endforeach
						</ul>
					</div>
				</div>
				@endif
			</div>
		</div>
	</div>