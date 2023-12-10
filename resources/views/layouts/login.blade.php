<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-117911895-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-117911895-1');
</script>

<title>Oomrah</title>
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
<link href="https://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" type='text/css'>
<link rel="stylesheet" href="{{ asset('mmb')}}/css/bootstrap.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('mmb')}}/css/Grace.css">
<script src="{{ asset('mmb')}}/js/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="{{ asset('mmb/js/parsley.js') }}"></script>			
<script src="{{ asset('mmb')}}/js/bootstrap.js"></script>
<script type="text/javascript" src="{{ asset('mmb/js/form.js') }}"></script>	
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->		
  	</head>
	<body class="hold-transition login-page" data-color="theme-{{ CNF_TEMPCOLOR }}">
		@yield('content')	
	</body>

</html>