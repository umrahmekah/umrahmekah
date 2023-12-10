<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no" />
    <meta name="keywords" content="{{ $pageMetakey }}" />
    <meta name="description" content="{{ $pageMetadesc }}" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('laravel-analytics.view_id') }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '{{ config('laravel-analytics.view_id') }}');
    </script>

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
    <link href="{{ URL::asset('assets/theme/modern/css/button.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/theme/modern/css/jquery-ui.structure.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/theme/modern/css/jquery-ui.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="{{ URL::asset('assets/theme/modern/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/theme/modern/css/stepwizard.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/theme/modern/css/collapse.css')}}" rel="stylesheet" type="text/css"/>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="{{ URL::asset('assets/theme/modern/css/bookingform.css')}}" rel="stylesheet" id="bootstrap-css">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/lightbox.css') }}" >
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/css/package-form.css') }}" >
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    {{--<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>--}}
    {{--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>--}}

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

{{ Session::put('previous_path', Request::path()) }}

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
                                {{--<a href="{{ url() }}">home</a>--}}
                                <a href="{{ url() }}"><i class="fa fa-home" style="font-size:1.5em"></i></a>
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

                    <?php 
                      $bannerMax = 4;
                      $bannerImg = rand(1, $bannerMax);
                    ?>
                    @foreach ($banners as $banner)
                        <?php

                        if(file_exists(public_path().'/uploads/images/'.$banner->owner_id.'/'.$banner->image))
                            $banner_url = url('/uploads/images/'.$banner->owner_id.'/'.$banner->image);
                        else{

                            $banner_url = "https://static.oomrah.com/uploads/images/banner-".fmod(++$bannerImg,$bannerMax).".jpg";
                        }
                        ?>
                        <div class="swiper-slide active" data-val="{{$banner_id++}}">
                            <div class="clip">
                                <div class="bg bg-bg-chrome act" style="background-image:url({{$banner_url}})"></div>
                            </div>
                            <div class="vertical-align">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 main-title">
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
        <?php 
            $headerMax = 4;
            $headerImg = rand(0, $headerMax);
        ?>
        <img class="center-image" src="@if(file_exists('./uploads/images/'.CNF_OWNER.'/'.$pageImage)  && $pageImage !=''){{ URL::asset('uploads/images/'.CNF_OWNER.'/'.$pageImage) }}@elseif(file_exists('./uploads/images/'.CNF_OWNER.'/header.jpg')){{ URL::asset('/uploads/images/'.CNF_OWNER.'/header.jpg') }}@else{{ 'https://static.oomrah.com/uploads/images/header-'.$headerImg.'.jpg' }}@endif" alt="">
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
