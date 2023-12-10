<div class="bg-blue" style="padding: 20px 10%;">
	<div class="container">
		<h3 class="font-medium font-weight-light text-white">{{ Lang::get('core.why_us') }}</h3>
		<div class="about__features bg-blue">
			@foreach(config('templates.blue-ocean.settings.section-seven.reasons') as $reason)
				<div class="about__feature">
					<div class="about__feature-img">
						<img src="{{ $reason['icon'] }}">
					</div>
					<div class="about__feature-text">
						<h4>{{ $reason['title'] }}</h4>
						<p>{{ $reason['message'] }}</p>
					</div>
				</div>
			@endforeach
		</div>
	</div>
</div>