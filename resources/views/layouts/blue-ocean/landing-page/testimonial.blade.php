<!-- TESTIMONIALS -->
@push('styles')
	<style>
		.carousel-testimony {
			margin: 0 auto;
			padding: 0 70px;
		}
		.carousel-testimony .item {
			color: #999;
			overflow: hidden;
		    min-height: 120px;
			font-size: 13px;
		}
		.carousel-testimony .media {
			position: relative;
			padding: 0 0 0 20px;
		}
		.carousel-testimony .media img {
			width: 75px;
			height: 75px;
			display: block;
			border-radius: 50%;
		}
		.carousel-testimony .testimonial-wrapper {
			padding: 0 10px;
		}
		.carousel-testimony .testimonial {
		    color: #808080;
		    position: relative;
		    padding: 15px;
		    background: #f1f1f1;
		    border: 1px solid #efefef;
		    border-radius: 3px;
			margin-bottom: 15px;
		}
		.carousel-testimony .testimonial::after {
			content: "";
			width: 15px;
			height: 15px;
			display: block;
			background: #f1f1f1;
			border: 1px solid #efefef;
			border-width: 0 0 1px 1px;
			position: absolute;
			bottom: -8px;
			left: 46px;
			transform: rotateZ(-46deg);
		}
		.carousel-testimony .star-rating li {
			padding: 0 2px;
		}
		.carousel-testimony .star-rating i {
			font-size: 16px;
			color: #ffdc12;
		}
		.carousel-testimony .overview {
			padding: 3px 0 0 15px;
		}
		.carousel-testimony .overview .details {
			padding: 5px 0 8px;
		}
		.carousel-testimony .overview b {
			text-transform: uppercase;
			color: #1abc9c;
		}
		.carousel-testimony .carousel-indicators {
			bottom: -50px;
		}
		.carousel-indicators li, .carousel-indicators li.active {
			width: 18px;
		    height: 18px;
			border-radius: 50%;
			margin: 1px 2px;
		}
		.carousel-indicators li {	
		    background: #e2e2e2;
		    border: 4px solid #fff;
		}
		.carousel-indicators li.active {
			color: #fff;
		    background: #1abc9c;
		    border: 5px double;    
		}
		.carousel-indicators {
			position:absolute;
			right:0;
			bottom:0px;
			left:0;
			z-index:15;
			display:-webkit-box;
			display:-ms-flexbox;
			display:flex;
			-webkit-box-pack:center;
			-ms-flex-pack:center;
			justify-content:center;
			padding-left:0;
			margin-right:15%;
			margin-left:15%;
			list-style:none
		}
	</style>
@endpush
<?php 
	if ($isMobile) {
		$slides = 1;
	}else{
		$slides = 2;
	}
?>
@if (!empty($testimonials))
	<div class="pt-3" style="padding-left: 10%; padding-right: 10%; padding-bottom: 50px;">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<h3 style="margin-top: 10px; margin-bottom: 20px; text-align: center;">{{ Lang::get('core.testimonial_title') }}</h3>
					<div id="carouseltestimony" class="carousel carousel-testimony slide" data-ride="carousel" style="padding: 0px;">
						{{-- Carousel indicators --}}
						<ol class="carousel-indicators">
							@foreach($testimonials as $key => $testimonial)
							@if($key%$slides == 0)
								<li data-target="#carouseltestimony" data-slide-to="{{$key/$slides ?? 0}}" @if($key == 0) class="active" @endif></li>
							@endif
							@endforeach
						</ol>   
						{{-- Wrapper for carousel items --}}
						<div class="carousel-inner">
							<?php $last = end($testimonials)->testimonialID; ?>
							@foreach($testimonials as $key => $testimonial)
							<?php 
								$testimonial_img = asset('mmb/images/testimonial-noimage.jpg');
								if(!empty($testimonial->image)){
									if(file_exists(public_path().'/uploads/images/'.$testimonial->owner_id.'/'.$testimonial->image)) {
										$testimonial_img = asset('uploads/images/'.$testimonial->owner_id.'/'.$testimonial->image);  
									}
								}

								$testimonialtour_img = asset('mmb/images/tour-noimage.jpg');
								if(!empty($testimonial->tourimage)){
									if(file_exists(public_path().'/uploads/images/'.$testimonial->owner_id.'/'.$testimonial->tourimage)) {
										$testimonialtour_img = asset('uploads/images/'.$testimonial->owner_id.'/'.$testimonial->tourimage);  
									}
								}
							?>
							@if($key%$slides == 0)
							<div class="item carousel-item @if($key == 0) active @endif">
								<div class="row">
							@endif
									<div class="col-sm-6" style="margin: 10px 0px;">
										<div class="testimonial-wrapper">
											<div class="testimonial">{{ $testimonial->testimonial }}</div>
											<div class="media">
												<div class="media-left d-flex mr-3">
													<img src="{{ $testimonial_img }}" alt="">										
												</div>
												<div class="media-body">
													<div class="overview">
														<div class="name"><b>{{ $testimonial->namesurname }}</b></div>
														<div class="details">{{ $testimonial->tour_name }}</div>
														<div class="star-rating">
															{{ \Carbon::parse($testimonial->tour_date)->format('d/m/Y') }}
														</div>
													</div>										
												</div>
											</div>
										</div>								
									</div>
							@if($key%$slides != 0 || $testimonial->testimonialID == $last || $slides == 1)
								</div>			
							</div>
							@endif
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endif