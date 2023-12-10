<ul class="d-flex ul-none mb-0" style="padding-left: 0px;">
	@if(!empty(CNF_TWITTER))
		@include('layouts.blue-ocean.components.social-media-link-item', [
	        'link' => CNF_TWITTER,
	        'icon' => 'fab fa-twitter',
	    ])
	@endif

	@if(!empty(CNF_INSTAGRAM))
    @include('layouts.blue-ocean.components.social-media-link-item', [
        'link' => CNF_INSTAGRAM,
        'icon' => 'fab fa-instagram',
    ])
    @endif

    @if(!empty(CNF_FACEBOOK))
    @include('layouts.blue-ocean.components.social-media-link-item', [
        'link' => CNF_FACEBOOK,
        'icon' => 'fab fa-facebook-f',
    ])
    @endif
    
    @if(!empty(CNF_TRIPADVISOR))
    @include('layouts.blue-ocean.components.social-media-link-item', [
        'link' => CNF_TRIPADVISOR,
        'icon' => 'fab fa-tripadvisor',
    ])
    @endif
</ul>