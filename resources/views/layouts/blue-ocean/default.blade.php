<!DOCTYPE html>
<html>
@include('layouts.blue-ocean.head')
<body data-color="theme-{{ CNF_TEMPCOLOR }}">
	{{ Session::put('previous_path', Request::path()) }}
	@include('layouts.blue-ocean.header')
	@include('layouts.blue-ocean.components.loader')
	<div id="default-layout">
		@yield('content')
	</div>
	@include('layouts.blue-ocean.footer')
	<script type="text/javascript" src="{{ asset('assets/js/app.js') }}"></script>
	@stack('scripts')
</body>
</html>

