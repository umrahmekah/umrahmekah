@push('styles')
	<style type="text/css" id="slider_id">
		.slider {
		    height: 77vh;
		    width: 100%;
		    background-size: cover;
		    background-position: center center;
		    background-repeat: no-repeat;
		}
		
	</style>
@endpush
<?php $num = 0; ?>
<div id="carouselbanner" class="carousel slide d-none d-md-block" data-ride="carousel">
	<ol class="carousel-indicators">
		@if (empty($banners))
			@foreach (config('templates.blue-ocean.settings.section-one.photos') as $key => $value)
				<li data-target="#carouselbanner" 
					data-slide-to="{{ $num++ }}" @if(0 == $key) 
					class="active" @endif></li>
			@endforeach
		@else 
			@foreach ($banners as $key => $value)
				<li data-target="#carouselbanner" 
					data-slide-to="{{ $num++ }}" @if(0 == $key) 
					class="active" @endif></li>
			@endforeach
		@endif
    
    {{-- <li data-target="#carouselExampleIndicators" data-slide-to="1"></li> --}}
  </ol>
  <div class="carousel-inner">
  	@if(empty($banners))
	  	@foreach (config('templates.blue-ocean.settings.section-one.photos') as $key => $photo)
		<div class="carousel-item @if(0 == $key) active @endif slider" style="background-image: url('{{ $photo['url'] }}')">
			<div class="carousel-caption text-left mb-5" style="left: 5% !important; right: 5% !important;">
				<h3>{{ $photo['heading-one'] }}</h3>
				<h1>{{ $photo['heading-second'] }}</h1>
				{{-- <p class="text-white">Italy, Rome, Venice, Milan</p> --}}
				<a href="{{ $photo['learn-more-link'] }}" class="btn btn-primary btn-md" name="banner_button">Learn More</a>
			</div>
	    </div>
	  	@endforeach
  	@else 
  		<?php 
  			$bannerMax = 4;
            $bannerImg = rand(1, $bannerMax);
        ?>
		@foreach ($banners as $key => $banner)
		<?php 
			if(file_exists(public_path().'/uploads/images/'.$banner->owner_id.'/'.$banner->image)) {
                $banner_url = url('/uploads/images/'.$banner->owner_id.'/'.$banner->image);
			} else {
                $banner_url = "https://static.oomrah.com/uploads/images/banner-".fmod(++$bannerImg,$bannerMax).".jpg";
            }
			$photo = [
				'url' => $banner_url,
				'heading-one' => $banner->title,
				'heading-second' => null,
				'learn-more-link' => $banner->link ?? '#',
				'learn-more-label' => $banner->link_button ?? Lang::get('core.learn_more'),
			];
		?>
		<div class="carousel-item @if(0 == $key) active @endif slider" style="background-image: url('{{ $photo['url'] }}')">
			<div class="carousel-caption text-left mb-5" style="left: 10% !important; right: 10% !important;">
				<div class="container">
					<h3>{{ $photo['heading-one'] }}</h3>
					<h1>{{ $photo['heading-second'] }}</h1>
					{{-- <p class="text-white">Italy, Rome, Venice, Milan</p> --}}
					<a href="{{ $photo['learn-more-link'] }}" class="btn btn-primary btn-md" name="banner_button">{{ $photo['learn-more-label'] }}</a>
				</div>
			</div>
	    </div>
	  	@endforeach
  	@endif
  </div>
</div>
