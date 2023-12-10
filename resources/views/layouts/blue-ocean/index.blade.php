@extends('layouts.blue-ocean.default')        

@section('content')
	@if(!Request::is('/')) 
		@include('layouts.blue-ocean.template.page')
	@else 
		@include('layouts.blue-ocean.landing-page.welcome')

		@if(config('templates.blue-ocean.settings.section-two.enabled'))
			@include('layouts.blue-ocean.landing-page.feature')
		@endif

		@if(CNF_SHOWTOUR)
			@include('layouts.blue-ocean.landing-page.package')
		@endif

		@if(CNF_SHOWTESTIMONIAL)
			@include('layouts.blue-ocean.landing-page.testimonial')
		@endif

		@if(config('templates.blue-ocean.settings.section-six.enabled'))
			@include('layouts.blue-ocean.landing-page.promotion')
		@endif

		@if(config('templates.blue-ocean.settings.section-five.enabled'))
			@include('layouts.blue-ocean.landing-page.browse-activity')
		@endif

		@if(config('templates.blue-ocean.settings.section-seven.enabled'))
			@include('layouts.blue-ocean.landing-page.why-us')
		@endif
	@endif
@endsection

@push('scripts')
	<script>
		$(function(){
			const navbar = $('#navbar_id');
			const feature = $('#feature_id');
			const slider = document.getElementById('slider_id');

			let nav_height = (parseFloat(navbar.height()/ $(window).height()) * 100);
			let fea_height = 0;
			if (feature.length) {
				fea_height = (parseFloat(feature.height()/ $(window).height()) * 100);
			}
			let sli_height = (100 - nav_height - fea_height).toFixed(2);

			slider.innerHTML = ".slider {height: "+sli_height+"vh;width: 100%;background-size: cover;background-position: center center;background-repeat: no-repeat;}";

			if (+sli_height <= 25) {
				let buttons = document.getElementsByName('banner_button');
				for(button of buttons){
					button.style.display = 'none';
				}
			}
		});
	</script>
@endpush