<!DOCTYPE html>
<html lang="en">
  <head>
    <title>{{ CNF_COMNAME }} | {{ $pageTitle }}</title>
    <meta name="keywords" content="{{ $pageMetakey }}" />
    <meta name="description" content="{{ $pageMetadesc }}" />
    <meta name="Author" content="Oomrah" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
    <link href="{{ URL::asset('assets/css/theme-style.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/colour-'.CNF_TEMPCOLOR.'.css')}}" rel="stylesheet">
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

    <link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Rambla:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Calligraffitti' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,700' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <!-- Optional: ICON SETS -->
    <!-- Iconset: Font Awesome 4.7.0 via CDN -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Iconset: ionicons - http://ionicons.com/ -->
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <!-- Iconset: Linearicons - https://linearicons.com/free -->
    <link href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css" rel="stylesheet">
<script src="{{ URL::asset('mmb/js/jquery-2.2.3.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('mmb/js/jCombo.js') }}"></script>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', '{{ config('laravel-analytics.view_id') }}', 'auto');
ga('send', 'pageview');
</script>
  </head>
  <body class="page page-backstretch navbar-layout-default" >    
    <div id="header">
      <!--Branding & Navigation Region-->
      
      <!--Hidden Header Region-->
      <div class="header-hidden collapse">
        <div class="header-hidden-inner container">
          <div class="row">
            <div class="col-sm-6">
              <h3>Oomrah</h3>
              <p>Travel made easy</p>
              <a href="#" class="btn btn-sm btn-primary">Find out more</a> 
            </div>
            <div class="col-sm-6">
              <h3>
                Contact Us
              </h3>
              <address>
                <p>
                  <abbr title="Phone"><i class="fa fa-phone"></i></abbr>
                  {{ CNF_TEL }}
                </p>
                <p>
                  <abbr title="Email"><i class="fa fa-envelope"></i></abbr>
                  {{ CNF_EMAIL }}
                </p>
                <p>
                  <abbr title="Address"><i class="fa fa-home"></i></abbr>
                  {{ CNF_ADDRESS }}.
                </p>
              </address>
            </div>
          </div>
        </div>
      </div>
      
      <div class="header-upper">
        <div id="header-hidden-link" style="display:none !important">
          <a href="#" title="" class="show-hide" data-toggle="show-hide" data-target=".header-hidden"><i></i>Open</a>
        </div>
        <div class="header-inner container">
          <div class="header-block-flex order-1 mr-auto">
            <nav class="nav nav-sm header-block-flex">
            @if(Auth::check())
            <div class="dropdown dropdowns-no-carets">
              <a class="nav-link text-xs p-1 dropdown-toggle" data-toggle="dropdown" href="#"> {{ Lang::get('core.m_myaccount') }}</a>
              <div class="dropdown-menu dropdown-menu-mini dropdown-menu-primary">
                <a href="{{ url('dashboard') }}" class="dropdown-item" >Dashboard</a>
                <a href="{{ url('user/profile?view=frontend') }}" class="dropdown-item">{{ Lang::get('core.m_profile') }}</a>
                <a href="{{ url('user/logout') }}" class="dropdown-item"> {{ Lang::get('core.m_logout') }}</a>
              </div>
            </div>
            @else
              @if(CNF_REGIST =='true')
                <a class="nav-link text-xs text-uppercase d-none- d-md-block" href="{{ url('user/register') }}" >{{ Lang::get('core.signup') }}</a> 
              @endif 
                <a class="nav-link text-xs text-uppercase d-none- d-md-block" href="{{ url('user/profile?view=frontend') }}" >{{ Lang::get('core.signin') }} </a> 

            @endif
            </nav>
            <div class="header-divider header-divider-sm"></div>
            <!--language menu-->
            @if(CNF_MULTILANG ==1)    
              <?php 
              $flag ='en';
              $langname = 'English'; 
              foreach(SiteHelpers::langOption() as $lang):
                if($lang['folder'] == $pageLang or $lang['folder'] == CNF_LANG) {
                  $flag = $lang['folder'];
                  $langname = $lang['name']; 
                }
              endforeach;?>
            <div class="dropdown dropdowns-no-carets">
              <a href="#" class="nav-link text-xs p-1 dropdown-toggle" data-toggle="dropdown" ><img class="flag-lang" src="{{ URL::asset('mmb/images/flags/'.$flag.'.png') }}" width="16" height="11" alt="lang" /> {{ $langname }} <span class="caret"></span></a>
              <div class="dropdown-menu dropdown-menu-mini dropdown-menu-primary">
                @foreach(SiteHelpers::langOption() as $lang)
                <a href="{{ url('home/lang/'.$lang['folder'])}}" class="dropdown-item lang-{{$lang['folder']}}"><img class="flag-lang" src="{{ URL::asset('mmb/images/flags/'.$lang['folder'].'.png') }}" width="16" height="11" alt="lang" /> {{  $lang['name'] }}</a>
                @endforeach
                
              </div>
            </div>
            @endif

          </div>
          <div class="nav nav-icons header-block order-12">
       @if (CNF_FACEBOOK !='')   <a href="{{ CNF_FACEBOOK }}" class="nav-link" target="_blank"><i class="fa fa-facebook-official"></i><span class="sr-only">Facebook</span></a>@endif
       @if (CNF_TWITTER !='')    <a href="{{ CNF_TWITTER }}" class="nav-link" target="_blank"><i class="fa fa-twitter"></i><span class="sr-only">Twitter</span></a>@endif
       @if (CNF_INSTAGRAM !='')  <a href="{{ CNF_INSTAGRAM }}" class="nav-link" target="_blank"><i class="fa fa-instagram"></i><span class="sr-only">Instagram</span></a>@endif 
       @if (CNF_TRIPADVISOR !='')<a href="{{ CNF_TRIPADVISOR }}" class="nav-link" target="_blank"><i class="fa fa-tripadvisor"></i><span class="sr-only">Tripadvisor</span></a>@endif
          </div>
        </div>
      </div>
      <div data-toggle="sticky">
        <div class="header-search collapse" id="search">
          <form class="search-form container">
            <input type="text" name="search" class="form-control search" value="" placeholder="Search">
            <button type="button" class="btn btn-link"><span class="sr-only">Search </span><i class="fa fa-search fa-flip-horizontal search-icon"></i></button>
            <button type="button" class="btn btn-link close-btn" data-toggle="search-form-close"><span class="sr-only">Close </span><i class="fa fa-times search-icon"></i></button>
          </form>
        </div>
        
        <div class="header">
          <div class="header-inner container">
            <div class="header-brand">
              <a class="header-brand-text" href="{{ url() }}" title="Home">
                <a href="{{ url() }}">@if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
        <img src="{{ URL::asset('/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" style="height:70px" />
        @else
                  <h1>
                  <span>{{ CNF_COMNAME }}</span>
                </h1>
        @endif </a>
              </a>
            </div>
            <!-- other header content -->
            <div class="header-block order-12">
              
              <!--Search trigger 
              <a href="#search" class="btn btn-icon btn-link header-btn float-right order-11" data-toggle="search-form" data-target=".header-search"><i class="fa fa-search fa-flip-horizontal search-icon"></i></a>-->
            <a href="#top" class="btn btn-link btn-icon header-btn float-right d-lg-none" data-toggle="jpanel-menu" data-target=".navbar-main" data-direction="right"> <i class="fa fa-bars"></i> </a>
            </div>
            
            <div class="navbar navbar-expand-md">
              <div class="navbar-main collapse">
                <ul class="nav navbar-nav float-lg-right dropdown-effect-fade">
                @include('layouts/leo/menu')
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
      <div id="highlighted">
          @if ($homepage==1 && file_exists(public_path().'/assets/videos/intro.mp4')) 
      <div class="header" style="height:400px;display:none;" data-bg-video="{{ URL::asset('assets/videos/intro.mp4')}}" data-settings='{"posterType":"none", "muted":true }'>
          <h2 class="display-4 text-white text-center" data-animate="fadeIn" data-animate-delay="0.2" style="height:180px;">
          <span class="font-weight-bold">{{ $pageTitle }}!</span>
        </h2>
          <h2 class="font-weight-normal text-white text-center" data-animate="fadeIn" data-animate-delay="0.35" data-typed='["{{ CNF_TAGLINE }}"]' ></h2>

          </div>

          <div class="bg-white overlay- overlay-primary overlay-op-2 text-center px-3 py-5 py-lg-10 flex-valign" data-bg-img="@if(file_exists('./uploads/images/'.CNF_OWNER.'/'.$pageImage)  && $pageImage !=''){{ URL::asset('uploads/images/'.CNF_OWNER.'/'.$pageImage) }}@else{{ URL::asset('/uploads/images/'.CNF_OWNER.'/header.jpg') }}@endif" data-css='{"background-position": "center bottom","background-attachment": "fixed"}'  data-offset="#header">
          </div>

          @else
          <div class="bg-white overlay- overlay-primary overlay-op-2 text-center px-3 py-5 py-lg-10- flex-valign" data-bg-img="@if(file_exists('./uploads/images/'.CNF_OWNER.'/'.$pageImage)  && $pageImage !=''){{ URL::asset('uploads/images/'.CNF_OWNER.'/'.$pageImage) }}@else{{ URL::asset('/uploads/images/'.CNF_OWNER.'/header.jpg') }}@endif" data-css='{"background-position": "center bottom","background-attachment": "fixed"}'  data-offset="#header">
            <h2 class="font-weight-normal text-white text-center" data-animate="fadeIn" data-animate-delay="0.35" data-typed='["{{ $pageTitle }}"]' data-typed-settings='{"cursor":false}'></h2>
           </div>
         @endif
      </div>      