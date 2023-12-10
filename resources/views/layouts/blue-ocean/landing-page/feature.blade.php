{{-- <style>
	.about__feature{
		display:-webkit-box;
		display:-ms-flexbox;
		display:flex;
		padding:30px 5px;
		-webkit-box-flex:1;
		-ms-flex:1 1;flex:1 1
	}
	.about__feature-img{
		-webkit-box-flex:1;
		-ms-flex:1 1 10%;
		flex:1 1 10%;
		padding:0 20px
	}
	.about__feature-text{
		-webkit-box-flex:3;
		-ms-flex:3 3 70%;
		flex:3 3 70%
	}
</style> --}}
<div class="about__features bg-blue" style="padding: 0px 10%;" id="feature_id">
	<div class="container">
		<div class="row" style="padding:35px 5px; padding-bottom: 20px">
			@foreach (config('templates.blue-ocean.settings.section-two.features') as $key => $feature)
				{{-- @if($key != 3) --}}
					<div class="col-md-3" style="padding-bottom: 0px; padding-right: 0px; padding-left: 0px;">
						<div class="row">
							<div class="col-md-2 col-sm-6" style="width: auto;">
								{{-- <img height="40px" width="40px" src="{{ $feature['icon'] }}"> --}}
								<i height="40px" width="40px" class="fa fa-plane fa-2x" @if ($isMobile)
									style="font-size: 1.5em;" 
								@endif></i>
							</div>
							<div class="col-md-10 col-sm-6" style="width: auto;">
								<h6>{{ $feature['title'] }}</h6>
								<p style="color: #fff; font-size: 12px;">{{ $feature['message'] }}</p>
							</div>
						</div>
					</div>
				{{-- @endif --}}
			@endforeach
		</div>
	</div>
</div>