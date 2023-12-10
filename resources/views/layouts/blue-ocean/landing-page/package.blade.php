<!-- PACKAGE-ITEM -->
@if (!empty($packages))
	<?php 
		$packages_count = count($packages); 
		if($packages_count == 1)
		{
			$sm_slides = 1;
			$lg_slides = 1;
		}
		elseif ($packages_count == 2)
		{
			$sm_slides = 1;
			$lg_slides = 2;
		}
		else
		{
			$sm_slides = 1;
			$lg_slides = 4;
		}
		$count = 1;
		$count_total = 1;
		$num = 0;
		if($isMobile){
			$slides = $sm_slides;
		}else{
			$slides = $lg_slides;
		}
	?>
	{{-- <div class="home__tour py-100 " style="padding-right:10%; padding-left: 10%; background-color: #f1f1f1; background-image: url('{{ asset('assets/img/packagebg.jpg') }}');"> --}}
	<div class="home__tour py-100 " style="padding-right:10%; padding-left: 10%; padding-top: 5%; padding-bottom: 5%; background-color: #f1f1f1; background-image: none;" id="page">
		<div class="container">
			<h3 style="color: black; text-align: center;">{{ Lang::get('core.package_title') }}</h3>
			<div id="carousel-package" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators" style="bottom: -30px;">
			  		@foreach($packages as $key => $value)
			  			@if($key % $slides == 0)
							<li data-target="#carousel-package" data-slide-to="{{ $num++ }}" class="{{ $key == 0 ? 'active' : '' }}"></li>
						@endif
			  		@endforeach
		  		</ol>
	  			<div class="carousel-inner">
					@foreach($packages as $key => $package)
						<?php 
							$package_img = asset('mmb/images/tour-noimage.jpg');
							if(!empty($package->landing_image)){
							  	if(file_exists(public_path().'/uploads/images/'.$package->owner_id.'/'.$package->landing_image)) {
									$package_img = asset('uploads/images/'.$package->owner_id.'/'.$package->landing_image);
								}
							}



							$package_img .= '?auto=yes&bg=777&fg=555&text=' . $package->tour_name;
						?>
						@if($key % $slides == 0 || $key == 0)
		    				<div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
		    					<div class="row justify-content-center">
		    			@endif

						@include('layouts.blue-ocean.landing-page.package-detail')

						@if($count == $slides || $packages_count == $count_total)
								</div>
		    				</div>
		    				<?php $count = 0; ?>
		    			@endif

		    			<?php $count++ ?> 
		    			<?php $count_total++ ?> 
					@endforeach
				</div>
			</div>
		</div>
	</div>
@endif