{{--*/ $menus = SiteHelpers::menus('top') /*--}}	
			@foreach ($menus as $menu)
		      @if($menu['module'] =='separator')
		      @else
		      	<li class="type-1">
		      		<a  @if($menu['menu_type'] =='external')  href="{{ URL::to($menu['url'])}}" 
						@else href="{{ URL::to($menu['module'])}}" 
						@endif>
				 		<i class="{{$menu['menu_icons']}}"></i> 
						
						@if(CNF_MULTILANG ==1 && isset($menu['menu_lang']['title'][Session::get('lang')]) && $menu['menu_lang']['title'][Session::get('lang')]!='')
							{{ $menu['menu_lang']['title'][Session::get('lang')] }}
						@else
							{{$menu['menu_name']}}
						@endif	

						@if(count($menu['childs'])> 0 )
							<span class="fa fa-angle-down"></span>
						@endif

					</a> 

					@if(count($menu['childs']) > 0)
                    <ul class="dropmenu">
							@foreach ($menu['childs'] as $menu2)
                           @if(count($menu2['childs']) > 0) <ul class="dropmenu">
> @endif

							 	@if($menu2['module'] =='separator')
						      	@else
									 	<a class="dropdown-item" @if(count($menu2['childs']) > 0)
id="{{$menu2['menu_name']}}-drop" data-toggle="dropdown" data-hover="dropdown" data-close-others="false" @endif
											@if($menu2['menu_type'] =='external')
												href="{{ URL::to($menu2['url'])}}" 
											@else
												href="{{ URL::to($menu2['module'])}}" 
											@endif
														
										>
											<i class="{{$menu2['menu_icons']}}"></i> 
												@if(CNF_MULTILANG ==1 && isset($menu2['menu_lang']['title'][Session::get('lang')]))
													{{ $menu2['menu_lang']['title'][Session::get('lang')] }}
												@else
													{{$menu2['menu_name']}}
												@endif
											
										</a> 
										@if(count($menu2['childs']) > 0)
                                        <div class="dropdown-menu" role="menu" aria-labelledby="{{$menu2['menu_name']}}-drop"> 
											@foreach($menu2['childs'] as $menu3)
													<a 
														@if($menu3['menu_type'] =='external')
															href="{{ URL::to($menu3['url'])}}" 
														@else
															href="{{ URL::to($menu3['module'])}}" 
														@endif										
													class="dropdown-item"
													>
														@if(CNF_MULTILANG ==1 && isset($menu3['menu_lang']['title'][Session::get('lang')]))
															{{ $menu3['menu_lang']['title'][Session::get('lang')] }}
														@else
															{{$menu3['menu_name']}}
														@endif
												</a>
											@endforeach
                                        </div>
										@endif							
										
									@endif	
                                						@if(count($menu2['childs']) > 0)</ul>@endif

							@endforeach
				    </ul>
					@endif

				</li>


				@endif	
			@endforeach	

        <!--account menu-->
		@if(Auth::check())
		<li class="type-1"><a href="{{ url('user/profile?view=frontend') }}">{{ Lang::get('core.m_myaccount') }}<span class="fa fa-angle-down"></span></a>
			<ul class="dropmenu">
				<li><a href="{{ url('dashboard') }}">{{ Lang::get('core.m_dashboard') }}</a></li>
                <li><a href="{{ url('user/profile?view=frontend') }}">{{ Lang::get('core.m_profile') }}</a></li>
                <li><a href="{{ url('user/logout') }}">{{ Lang::get('core.m_logout') }}</a></li>
			</ul>
		</li>
		@else
		<li class="type-1"><a href="{{ url('user/profile?view=frontend') }}">{{ Lang::get('core.m_signup_signin') }}<span class="fa fa-angle-down"></span></a>
			<ul class="dropmenu">
				<li><a href="{{ url('user/profile?view=frontend') }}">{{ Lang::get('core.signin') }}</a></li>
				@if(CNF_REGIST =='true')
				<li><a href="{{ url('user/register') }}">{{ Lang::get('core.signup') }}</a></li>
				@endif 
			</ul>
		</li>		
		@endif

        <!--language menu-->
        @if(CNF_MULTILANG ==1)    
          <?php 
          $flag ='my';
          $langname = 'Bahasa'; 
          foreach(SiteHelpers::langOption() as $lang):
            //if($lang['folder'] == $pageLang or $lang['folder'] == CNF_LANG) {
          	if( $lang['folder'] == $pageLang  ) {
              $flag = $lang['folder'];
              $langname = $lang['name']; 
            }
          endforeach;?>
		<li class="type-1"><a href="#"><img class="flag-lang" src="{{ URL::asset('mmb/images/flags/'.$flag.'.png') }}" width="16" height="11" alt="lang" /> {{ $flag }}<span class="fa fa-angle-down"></span></a>
			<ul class="dropmenu">
				@foreach(SiteHelpers::langOption() as $lang)
				<li><a href="{{ url('home/lang/'.$lang['folder'])}}"><img class="flag-lang" src="{{ URL::asset('mmb/images/flags/'.$lang['folder'].'.png') }}" width="16" height="11" alt="lang" /> {{  $lang['name'] }}</a></li>
				@endforeach
			</ul>
		</li>
        @endif


