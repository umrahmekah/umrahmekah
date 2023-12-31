  <?php $sidebar = SiteHelpers::menus('sidebar') ;?>
  <aside class="main-sidebar" style="margin-top: 10px">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
         <a href="{{ url('user/profile')}}">{!! SiteHelpers::avatar( 40 ) !!} </a>
        </div>
        <div class="pull-left info">
          <p>{{ Session::get('fid')}}</p>
          <p style="font-weight: 400; font-size: .8em">{{ Session::get('eid')}}</p>
        </div>
      </div>
  <ul class="sidebar-menu"> 
    @foreach ($sidebar as $menu)
      @if($menu['module'] =='separator')
        <li class="header"> {{$menu['menu_name']}} </li>        
      @else
          <li class="treeview @if(Request::segment(1) == $menu['module']) active @endif">
          <a 
            @if($menu['menu_type'] =='external')
              href="{{ $menu['url'] }}" 
            @else
              href="{{ URL::to($menu['module'])}}" 
            @endif
          >
            <i class="{{$menu['menu_icons']}}"></i> 
            <span>
              @if(CNF_MULTILANG ==1 && isset($menu['menu_lang']['title'][Session::get('lang')]))
                {{ $menu['menu_lang']['title'][Session::get('lang')] }}
              @else
                {{$menu['menu_name']}}
              @endif              
            </span>
            @if(count($menu['childs']) > 0 )
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            @endif
          </a>
          <!--- LEVEL II -->
            @if(count($menu['childs']) > 0 )
              <ul class="treeview-menu">
               @foreach ($menu['childs'] as $menu2)
                <li @if(Request::segment(1) == $menu2['module']) class="active" @endif >
                  <a 
                    @if($menu2['menu_type'] =='external')
                      href="{{ $menu2['url']}}" 
                    @else
                      href="{{ url($menu2['module'])}}"  
                    @endif                  
                  >                
                  <i class="{{$menu2['menu_icons']}}"></i>
                  @if(CNF_MULTILANG ==1 && isset($menu2['menu_lang']['title'][Session::get('lang')]))
                    {{ $menu2['menu_lang']['title'][Session::get('lang')] }}
                  @else
                    {{$menu2['menu_name']}}
                  @endif
                   @if(count($menu2['childs']) > 0 )
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                   @endif 
                </a>
                  <!-- LEVEL III -->
                    @if(count($menu2['childs']) > 0)
                    <ul class="treeview-menu">
                       @foreach ($menu2['childs'] as $menu3)
                            <li  @if(Request::segment(1) == $menu3['module']) class="active" @endif>
                                <a 
                                  @if($menu3['menu_type'] =='external')
                                    href="{{ $menu3['url']}}" 
                                  @else
                                    href="{{ url($menu3['module'])}}"  
                                  @endif                  
                                >                
                                <i class="{{$menu3['menu_icons']}}"></i>
                                @if(CNF_MULTILANG ==1 && isset($menu3['menu_lang']['title'][Session::get('lang')]))
                                  {{ $menu3['menu_lang']['title'][Session::get('lang')] }}
                                @else
                                  {{$menu3['menu_name']}}
                                @endif
                              </a>
                           </li> 
                        @endforeach  
                    </ul>  
                     @endif 
                  <!-- END LEVEL III -->
                </li>
                @endforeach 
              </ul>
            @endif 
            <!-- END LEVEL II -->
          </li>
          @endif 
        @endforeach 
    </ul>   
    </section>
  </aside>