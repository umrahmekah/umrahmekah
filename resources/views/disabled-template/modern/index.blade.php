@include('layouts.modern.header')        

<!-- CONTENT -->
<div class="wrap-padding">
  <div class="container">
     <div class="row">
      <div class="col-xs-12 col-md-8">
        @include($pages)  
      </div> 
     </div>
    </div>
</div>


<!-- PACKAGE-ITEM -->
@if (!empty($packages))
<?php 
  $packages_count = count($packages); 
  if($packages_count == 1){
    $sm_slides = 1;
    $lg_slides = 1;
  }
  elseif ($packages_count == 2){
    $sm_slides = 2;
    $lg_slides = 2;
  }
  else{
    $sm_slides = 2;
    $lg_slides = 3;
  }

?>
<div class="main-wraper bg-grey-2 padd-90">
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        <div class="second-title">
          <h4 class="subtitle color-dr-blue-2">{{ Lang::get('core.packages') }}</h4>
          <h2>{{ Lang::get('core.our_featured_packages') }}</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="top-baner arrows">
      <div class="swiper-container" data-autoplay="0" data-loop="0" data-speed="1000" data-slides-per-view="responsive" data-mob-slides="1" data-xs-slides="{{$sm_slides}}" data-sm-slides="{{$sm_slides}}" data-md-slides="{{$lg_slides}}" data-lg-slides="{{$lg_slides}}" data-add-slides="{{$lg_slides}}">
        <div class="swiper-wrapper">
          @foreach ($packages as $package)
          <?php 
            $package_img = asset('mmb/images/tour-noimage.jpg');
            if(!empty($package->tourimage)){
              if(file_exists(public_path().'/uploads/images/'.$package->owner_id.'/'.$package->tourimage))
                $package_img = asset('uploads/images/'.$package->owner_id.'/'.$package->tourimage);  
            }

            $min_price = 0;
            if($package->cost_single > 0 )
              $min_price = $package->cost_single;
            if($package->cost_double > 0 && $package->cost_double < $min_price)
              $min_price = $package->cost_double ;
            if($package->cost_triple > 0 && $package->cost_triple < $min_price)
              $min_price = $package->cost_triple ;
            if($package->cost_quad > 0 && $package->cost_quad < $min_price)
              $min_price = $package->cost_quad ;

          ?>
          <div class="swiper-slide">
            <div class="tour-item style-5">
              <div class="radius-top">
                <img src="{{$package_img}}" alt="{{$package->tour_name}}">
              </div>
              <div class="tour-desc bg-dr-blue-2">
                <h4><a class="tour-title color-white" href="{{ url() }}/package?view={{$package->tourID}}">{{$package->tour_name}}</a></h4>
                <div class="tour-text color-white-light">{{$package->total_days}} {{ Lang::get('core.days') }}, {{$package->total_nights}} {{ Lang::get('core.nights') }}</div>
                <div class="tour-person color-white">@if ($min_price > 0) {{ Lang::get('core.from') }} <span>{{CURRENCY_SYMBOLS}} {{ $min_price }}</span>@endif  </div>           
                <a href="{{ url() }}/package?view={{$package->tourID}}" class="c-button b-40 bg-white hv-white-o"><span>{{ Lang::get('core.view_package') }}</span></a>
              </div>
            </div>          
          </div>
           @endforeach
                                 
        </div>
        <div class="pagination poin-style-2"></div>  
      </div>
      <div class="swiper-arrow-left offers-arrow color-3"><span class="fa fa-angle-left"></span></div>
      <div class="swiper-arrow-right offers-arrow color-3"><span class="fa fa-angle-right"></span></div>
    </div>  
      <div style="text-align: center; padding-top: 25px">
        <a href="{{ url() }}/package" class="c-button b-50 bg-green hv-green-o ">{{ Lang::get('core.view_all_packages') }}</a>
      </div>   
    </div>
  </div>
</div>
@endif  

<!-- TESTIMONIALS -->
@if (!empty($testimonials))
<div class="main-wraper bg-blue padd-90"  ">

  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-sm-offset-2">
        <div class="second-title">
          <h4 class="subtitle color-white underline">{{ Lang::get('core.testimonials') }}</h4>
          <h2 class="color-white">{{ Lang::get('core.what_our_clients_say') }}</h2>
        </div>
      </div>
    </div>
    <div class="arrows">
      <div class="swiper-container testi-slider swiper-swiper-unique-id-4 initialized" data-autoplay="0" data-loop="0" data-speed="900" data-center="0" data-slides-per-view="1" id="swiper-unique-id-4">
        <div class="swiper-wrapper" style="width: 2280px; height: 188px;">
          @foreach($testimonials as $testimonial)
          <?php 
          $testimonial_img = asset('mmb/images/testimonial-noimage.jpg');
          if(!empty($testimonial->image)){
            if(file_exists(public_path().'/uploads/images/'.$testimonial->owner_id.'/'.$testimonial->image))
              $testimonial_img = asset('uploads/images/'.$testimonial->owner_id.'/'.$testimonial->image);  
          }
          $testimonialtour_img = asset('mmb/images/tour-noimage.jpg');
          if(!empty($testimonial->tourimage)){
            if(file_exists(public_path().'/uploads/images/'.$testimonial->owner_id.'/'.$testimonial->tourimage))
              $testimonialtour_img = asset('uploads/images/'.$testimonial->owner_id.'/'.$testimonial->tourimage);  
          }
          ?>
          <div class="swiper-slide" data-val="1" style="width: 1140px; height: 188px;">
            <div class="container">
              <div class="row">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                  <div class="sg-testimonals">
                      <img class="sg-image" style="height: 100px !important" src="{{$testimonial_img}}" alt="{{$testimonial->namesurname}}">
                      <h3 class="color-white" style="font-weight: normal;"><strong>{{$testimonial->namesurname}}, </strong><font size="3">{{$testimonial->city}}</font></h3>
                      <p class="f-14 color-white-light">“{{$testimonial->testimonial}}”</p>
                  </div>              
                </div>
              </div>
            </div>
          </div>
          @endforeach
        </div>    
        <div class="pagination pagination-hidden poin-style-1 pagination-swiper-unique-id-4"><span class="swiper-pagination-switch swiper-visible-switch swiper-active-switch"></span><span class="swiper-pagination-switch"></span></div>
          <div class="arrow-wrapp arr-s-5">
          <div class="cont-1170">
            <div class="swiper-arrow-left sw-arrow"><span class="fa fa-angle-left"></span></div>
            <div class="swiper-arrow-right sw-arrow"><span class="fa fa-angle-right"></span></div>
          </div>
        </div>      
      </div>
    </div>      
  </div>

  </div>
@endif

@include('layouts.modern.footer')          