{{--*/ $menus = SiteHelpers::menus('top') /*--}}
<ul class="navbar-nav ml-auto">
	@foreach ($menus as $menu)
		@if($menu['module'] != 'separator')
			@include('layouts.blue-ocean.components.menu-item', [
				'has_child' => (count($menu['childs']) > 0) ? true : false,
				'menus' => $menu['childs'],
				'url' => $menu['menu_type'] == 'external' ? url($menu['url']) : url($menu['module']),
				'icon' => $menu['menu_icons'],
				'label' => (CNF_MULTILANG == 1 && isset($menu['menu_lang']['title'][Session::get('lang')]) 
					&& $menu['menu_lang']['title'][Session::get('lang')] != '') 
					? $menu['menu_lang']['title'][Session::get('lang')] 
					: $menu['menu_name'],
			])
		@endif 
	@endforeach
	@if(auth()->user())
		@include('layouts.blue-ocean.components.menu-item', [
			'url' => url('/dashboard'),
			'icon' => '',
			'label' => Lang::get('core.dashboard'),
		])
	@else 
		@include('layouts.blue-ocean.components.menu-item', [
			'url' => url('/user/login'),
			'icon' => '',
			'label' => Lang::get('core.login'),
		])
		{{-- @include('layouts.blue-ocean.components.menu-item', [
			'url' => url('/user/register'),
			'icon' => '',
			'label' => Lang::get('core.register'),
		]) --}}
	@endif
</ul>
<div class="mb-4">
	@include('layouts.blue-ocean.components.menu-lang')
</div>