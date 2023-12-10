<div class="package__section">
	<div class="package__single border-0">
		<h2>
			<i class="far fa-images"></i> Photos
		</h2>
	</div>
	<div class="package__single pt-0">
		<div id="gallery" class="carousel slide" data-ride="carousel">
			<div class="carousel-indicators">
				@foreach(explode(',', $row->gallery) as $key => $image) 
    				<li data-target="#carouselExampleIndicators" data-slide-to="0" class="{{ 0 == $key ? 'active' : '' }}"></li>
				@endforeach
			</div>	
			<div class="carousel-inner">
				@foreach(explode(',', $row->gallery) as $key => $image) 
				    <div class="carousel-item {{ 0 == $key ? 'active' : '' }}">
				      <img src="{{ asset('uploads/images/' . CNF_OWNER . '/' . $image) }}" 
				      	onerror="this.src='{{ $package_image }}'"
				      	class="d-block w-100" alt="{{ $image }}">
				    </div>
			    @endforeach
			</div>
		</div>
	</div>
</div>