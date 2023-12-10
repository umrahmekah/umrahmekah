<li class="nav-item @if(isset($has_child) && true == $has_child) dropdown active @endif">
    <a href="{{ $url }}" 
    	class="nav-link @if(isset($has_child) && true == $has_child) dropdown-toggle @endif" style="color: black;" 
    	@if(isset($has_child) && true == $has_child) 
    		id="{{$menu['menu_name']}}-drop" 
    		data-toggle="dropdown" 
    		data-hover="dropdown"
    	@endif>
    	@if(isset($icon)) <i class="{{ $icon }}"></i> @endif {{ $label }}
    </a>
</li>
@if(isset($has_child) && true == $has_child)
	<div class="dropdown-menu">
		@foreach ($menus as $menu)
			@if($menu['module'] != 'separator')
				@include('layouts.blue-ocean.components.menu-item', [
					'has_child' => count($menu['childs']) > 0 ? true : false,
					'menus' => $menu['childs'],
					'url' => $menu['menu_type'] == 'external' ? url($menu['url']) : url($menu['module']),
					'icon' => $menu['menu_icons'],
					'label' => (CNF_MULTILANG ==1 && isset($menu['menu_lang']['title'][Session::get('lang')]) && $menu['menu_lang']['title'][Session::get('lang')]!= '') ? $menu['menu_lang']['title'][Session::get('lang')] : $menu['menu_name'],
				])
			@endif
		@endforeach
	</div>
@endif
