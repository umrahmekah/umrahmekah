@if(CNF_MULTILANG ==1)    
	<?php 
		$flag ='en';
		$langname = 'English'; 
		foreach(SiteHelpers::langOption() as $lang)
		{
			if($lang['folder'] == $pageLang or $lang['folder'] == CNF_LANG) {
				$flag = $lang['folder'];
				$langname = $lang['name']; 
			}
		}
	?>
	<div class="dropdown">
	  <button class="btn btn-default bg-transparent border-transparentas dropdown-toggle" type="button" 
	  	id="lang-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<img class="flag-lang" src="{{ URL::asset('mmb/images/flags/'.$flag.'.png') }}" width="16" height="11" alt="lang" /> 
		<span class="text-black">{{ $langname }}</span>
	  </button>
	  <div class="dropdown-menu" aria-labelledby="lang-dropdown">
	  	 @foreach(SiteHelpers::langOption() as $lang)
        <a href="{{ url('home/lang/'.$lang['folder'])}}" class="dropdown-item lang-{{$lang['folder']}}">
        	<img class="flag-lang" src="{{ URL::asset('mmb/images/flags/' . $lang['folder'] . '.png') }}" width="16" height="11" alt="lang" /> 
        	{{  $lang['name'] }}
        </a>
        @endforeach
	  </div>
	</div>
@endif