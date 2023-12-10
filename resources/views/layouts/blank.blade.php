<?php $mmbconfig  = config('mmb');?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $mmbconfig['cnf_appname'] }}</title>

    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('assets/favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('assets/favicon//apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('assets/favicon//apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/favicon//apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('assets/favicon//apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/favicon//apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('assets/favicon//apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('assets/favicon//apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon//apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('assets/favicon//android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon//favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('assets/favicon//favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon//favicon-16x16.png')}}">
    <link rel="manifest" href="{{ asset('assets/favicon//manifest.json')}}">

<!-- Bootstrap Core CSS -->
    <link href="{{ asset('')}}assets/template/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- This is a Custom CSS -->
    <link href="{{ asset('')}}assets/template/css/style.css" rel="stylesheet">
    <!-- Legacy  Custom CSS for old mmb layout -->
    <link href="{{ asset('')}}assets/template/css/legacy.css" rel="stylesheet">

    
	<link href="{{ asset('mmb/css/sximo5.css')}}" rel="stylesheet">
	<script src="{{ asset('adminlte')}}/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="{{ asset('adminlte')}}/bootstrap/js/bootstrap.min.js"></script>
	@if(session('themes') !='')
	<link href="{{ asset('')}}assets/template/css/colors/{{ session('themes')}}.css" id="theme" rel="stylesheet">
	@else
	<link href="{{ asset('')}}assets/template/css/colors/gray.css" id="theme" rel="stylesheet">
	@endif
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>    
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('')}}assets/template/bootstrap/dist/js/bootstrap.min.js"></script>
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->	

	
	
  	</head>

	<body onload="window.print()" data-color="theme-{{ CNF_TEMPCOLOR }}">
		{!! $html !!}
	
		<script type="text/javascript">
			$(function(){
				$('.box-header').hide();
			})
		</script>
	</body>

</html>