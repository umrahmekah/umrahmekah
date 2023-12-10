<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no" />
    <meta name="keywords" content="{{ $pageMetakey }}" />
    <meta name="description" content="{{ $pageMetadesc }}" />


    <link rel="apple-touch-icon" sizes="57x57" href="{{ URL::asset('assets/favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ URL::asset('assets/favicon//apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ URL::asset('assets/favicon//apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ URL::asset('assets/favicon//apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ URL::asset('assets/favicon//apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ URL::asset('assets/favicon//apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ URL::asset('assets/favicon//apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ URL::asset('assets/favicon//apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('assets/favicon//apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ URL::asset('assets/favicon//android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('assets/favicon//favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ URL::asset('assets/favicon//favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('assets/favicon//favicon-16x16.png')}}">
    <link rel="manifest" href="{{ URL::asset('assets/favicon//manifest.json')}}">
    <link href="{{ URL::asset('assets/theme/modern/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/theme/modern/css/jquery-ui.structure.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/theme/modern/css/jquery-ui.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ URL::asset('assets/theme/modern/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <title>{{ CNF_COMNAME }} | {{ $pageTitle }}</title>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', '{{ CNF_ANALYTICS }}', 'auto');
      ga('send', 'pageview');
    </script>
  </head>

<body data-color="theme-{{ CNF_TEMPCOLOR }}">

<div class="loading pink">
  <div class="loading-center">
    <div class="loading-center-absolute">
      <div class="object object_four"></div>
      <div class="object object_three"></div>
      <div class="object object_two"></div>
      <div class="object object_one"></div>
    </div>
  </div>
</div>



<header class="color-1 hovered menu-3">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="nav">
            <a href="{{ url() }}" class="logo">
              @if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
                <img src="{{ URL::asset('/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" style="max-height:60px; max-width:200px" />
              @else
                <h3><span class="color-blue">{{ CNF_COMNAME }}</span></h3>
              @endif
            </a>
            <div class="nav-menu-icon">
          <a href="#"><i></i></a>
        </div>
          <nav class="menu">
          <ul>
          <li class="type-1">
            <a href="{{ url() }}">home</a>
          </li>
          @include('layouts/modern/menu')

          </ul>
       </nav>
       </div>
         </div>
      </div>
   </div>
  </header>

<!-- Top Header -->
@if ($homepage==1 && file_exists(public_path().'/assets/videos/intro.mp4'))
@endif
@if (!empty($banners))
<?php $banner_id =0;  ?>
<!-- Slider Banner-->
<div class="top-baner">
  <div class="arrows">
    <div class="swiper-container main-slider-6" data-autoplay="3000" data-loop="1" data-speed="1000" data-center="0" data-slides-per-view="1">
      <div class="swiper-wrapper">

        @foreach ($banners as $banner)
        <?php
          if(file_exists(public_path().'/uploads/images/'.$banner->owner_id.'/'.$banner->image))
            $banner_url = url('/uploads/images/'.$banner->owner_id.'/'.$banner->image);
          else
            $banner_url = "";
        ?>
        <div class="swiper-slide active" data-val="{{$banner_id++}}">
            <div class="clip">
              <div class="bg bg-bg-chrome act" style="background-image:url({{$banner_url}})"></div>
            </div>
            <div class="vertical-align">
              <div class="container">
                <div class="row">
                  <div class="col-xs-12 col-sm-10 main-title">
                  <?php echo $banner->content ;?>
                  @if (!empty($banner->link_button))
                  <a href="{{$banner->link}}" class="c-button bg-aqua hv-transparent delay-2"><img src="img/loc_icon.png" alt=""><span>{{$banner->link_button}}</span></a>
                  @endif

                  </div>
                </div>
              </div>
            </div>
        </div>
        @endforeach

      </div>
      <div class="pagination @if ($banner_id>1)  pagination-center @else pagination-hidden @endif poin-style-1"></div>
        <div class="arrow-wrapp arr-s-4">
        <div class="cont-1170">
          <div class="swiper-arrow-left sw-arrow"><span class="fa fa-angle-left"></span></div>
          <div class="swiper-arrow-right sw-arrow"><span class="fa fa-angle-right"></span></div>
        </div>
      </div>
    </div>
  </div>
</div>

@else

<!-- Image Header -->
<div class="inner-banner style-6">
  <img class="center-image" src="@if(file_exists('./uploads/images/'.CNF_OWNER.'/'.$pageImage)  && $pageImage !=''){{ URL::asset('uploads/images/'.CNF_OWNER.'/'.$pageImage) }}@else{{ URL::asset('/uploads/images/'.CNF_OWNER.'/header.jpg') }}@endif" alt="">
  <div class="vertical-align">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <h2 class="color-white">{{ $pageTitle }}</h2>
          </div>
      </div>
    </div>
  </div>
</div>
@endif


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
    <div class="row arrows">
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
                <div class="tour-text color-white-light">{{$package->total_days}}  {{ Lang::get('core.days') }} {{$package->total_nights}}  {{ Lang::get('core.nights') }}</div>
                <div class="tour-person color-white">@if ($min_price > 0) {{ Lang::get('core.from') }} <span>{{CURRENCY_SYMBOLS}}  {{ $min_price }}</span>@endif  </div>
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
@endif

<!-- FOOTER -->
<footer class="bg-dark type-2">
      <div class="container">
        <div class="row">
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="footer-block">
              <div class="f_text color-grey-7">
                <h4>{{ CNF_COMNAME }}</h4>
                <h6>{{ CNF_TAGLINE }}</h6>
                {{ CNF_DESCRIPTION }}
              </div>
              <div class="footer-share">
                @if (CNF_FACEBOOK !='')
                  <a href="{{ CNF_FACEBOOK }}" target="_blank"><span class="fa fa-facebook"></span></a>
                @endif
                @if (CNF_TWITTER !='')
                  <a href="{{ CNF_TWITTER }}"  target="_blank"><span class="fa fa-twitter"></span></a>
                @endif
                @if (CNF_INSTAGRAM !='')
                  <a href="{{ CNF_INSTAGRAM }} " target="_blank"><span class="fa fa-instagram"></span></a>
                @endif
                @if (CNF_TRIPADVISOR !='')
                  <a href="{{ CNF_TRIPADVISOR }}" target="_blank"><span class="fa fa-tripadvisor"></span></a>
                @endif
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-sm-6 no-padding">
           <div class="footer-block">
           </div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
             <div class="footer-block"></div>
          </div>
          <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="footer-block">
              <h6>{{ Lang::get('core.contact_us') }}</h6>
                <div class="contact-info">
                  <div class="contact-line color-grey-3"><i class="fa fa-map-marker"></i><span><a href="https://www.google.com.my/maps/place/{{ urlencode(CNF_ADDRESS) }}" target="_blank">{{ CNF_ADDRESS }}</a></span></div>
                  <div class="contact-line color-grey-3"><i class="fa fa-phone"></i><a href="tel:{{ CNF_TEL }}">{{ CNF_TEL }}</a></div>
                  <div class="contact-line color-grey-3"><i class="fa fa-envelope-o"></i><a href="mailto:{{ CNF_EMAIL }}">{{ CNF_EMAIL }}</a></div>
                  <div class="contact-line color-grey-3"><i class="fa fa-globe"></i><a href="https://{{ CNF_DOMAIN }}" target="_blank">{{ CNF_DOMAIN }}</a></div>
                </div>
             </div>
          </div>
        </div>
      </div>
      <div class="footer-link bg-black">
        <div class="container">
          <div class="row">
            <div class="col-md-12">
                <div class="copyright">
            <span>&copy; {{ date('Y')}} {{ CNF_COMNAME }}.</strong> {{Lang::get('core.allrights')}}.</span>
          </div>
              <ul>

            <li><a class="link-aqua" href="https://oomrah.com/privacy">{{ Lang::get('core.privacy_policy') }}</a></li>
            <li><a class="link-aqua" href="https://oomrah.com/term"> {{ Lang::get('core.tandc') }}</a></li>
          </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
<script src="{{ URL::asset('assets/theme/modern/js/jquery-2.1.4.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/jquery-ui.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/idangerous.swiper.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/jquery.viewportchecker.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/isotope.pkgd.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/jquery.circliful.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/jquery.mousewheel.min.js')}}"></script>
<script src="{{ URL::asset('assets/theme/modern/js/all.js')}}"></script>
</body>
</html>