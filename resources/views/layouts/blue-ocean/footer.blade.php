<div class="footer" style="padding-right: 10%; padding-left: 10%;">
  	<div class="container">
		<div class="row">
		  	<div class="col-sm-12 col-md-4 footer__single">
				{{-- @include('layouts.blue-ocean.components.icon') --}}
				<p> {{ CNF_TAGLINE }} </p>
				<p> {{ CNF_DESCRIPTION }} </p>
			</div>
			<div class="d-none d-sm-block col-md-4 footer__single">
			</div>
			{{-- @if(true == template_configurations()['settings']['footer']['top_destination']['enabled'])
				<div class="col-sm-12 col-md-4 footer__single">
					<h3>Top destinations</h3>

					@foreach (top_destinations() as $destination)
						<div class="footer__tours">
							<a href="#" class="footer__tour" 
								style="background-image: url(&quot;{{ template_configurations()['settings']['footer']['top_destination']['background_image'] }}&quot;);">
						  		<span>{{ $destination->tour_name }} ({{ $destination->tour_count }})</span>
							</a>
						</div>  
					@endforeach
				</div>
			@endif --}}
			<div class="col-sm-12 col-md-4 footer__single">
				<h3>{{ Lang::get('core.contact_us') }}</h3>
				<p>{{ CNF_ADDRESS }}</p>
				<p> Phone : {{ CNF_TEL }}</p>
				<p> <a href="mailto:{{ CNF_EMAIL }}">{{ CNF_EMAIL }}</a></p>
				<p> <a href="https://{{ CNF_DOMAIN }}" target="_blank">{{ CNF_DOMAIN }}</a></p>
				<div class="ml-auto">
					@include('layouts.blue-ocean.components.social-media-link')
				</div>
			</div>
	  	</div>
	</div>
	<div class="text-center pt-1 pb-1 text-muted" style="background-color: #2d2d2d">
		<span style="font-size: 11px;">&copy; {{ date('Y')}} {{ CNF_COMNAME }}.</strong> {{ Lang::get('core.allrights') }}.</span>
	</div>
</div>