<?php 
$reviews  = \DB::table('testimonials')->where('owner_id', '=' ,CNF_OWNER)->where('status', '=', '0')->count();
$support  = \DB::table('tbl_tickets')->where('owner_id', '=' ,CNF_OWNER)->where('status', '=', 'New')->count();
$comments = \DB::table('tb_comments')->where('owner_id', '=' ,CNF_OWNER)->where('approved', '=', '0')->count();
$notifications = \DB::table('book_tour')->where('owner_id', '=' ,CNF_OWNER)->where('admin_read', '=', '0')->count();
?>
<header class="main-header">
    <a href="{{ url('')}}" class="logo">
        <span class="logo-mini">
        <img src="{{ asset('mmb/images/logo-sm.png') }}" height="40px" />
      </span>
        <span class="logo-lg">
          @if(file_exists(public_path().'/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO) && CNF_LOGO !='')
            <img src="{{ URL::asset('/uploads/images/'.CNF_OWNER.'/'.CNF_LOGO)}}" style="height:50px" />
          @else
            <img src="{{ asset('mmb/images/logo-md.png')}}" height="50px" />
          @endif            
      </span>
    </a>
    <nav class="navbar navbar-static-top">
    <div class="header-link pull-left"><i class="fa fa-bars fa-lg" data-toggle="offcanvas"></i></div>
@if(Session::get('gid') == 1 || Session::get('gid') == 2)       
    <div class="hidden header-link tips" title="Dashboard" data-placement="bottom"><a href="{{ url('dashboard')}}"><i class="fa fa-dashboard fa-lg"></i></a></div>
    <div class="hidden header-link tips" title="{{ Lang::get('core.calendar1') }}" data-placement="bottom"><a href="{{ url('calendar')}}"><i class="fa fa-calendar fa-lg"></i></a></div>
@endif    
    <div class="header-link pull-right"><a href="{{ url('user/logout')}}" class="tips" title="{{ Lang::get('core.m_logout') }}"  data-placement="bottom" ><i class="fa fa-power-off fa-lg" aria-hidden="true"></i></a></div>
@if(Session::get('gid') == 1 || Session::get('gid') == 2)        
    <div class="header-link pull-right"><a href="#" data-toggle="control-sidebar" class="tips" title="{{ Lang::get('core.dash_i_setting') }}"  data-placement="bottom" ><i class="fa fa-gear fa-lg" aria-hidden="true"></i></a></div>
@endif
                @if(CNF_MULTILANG ==1)
                <div class="header-link pull-right">

                    <?php 
          $flag ='en';
          $langname = 'English'; 
          foreach(SiteHelpers::langOption() as $lang):
            if($lang['folder'] == Session::get('lang') or $lang['folder'] == 'CNF_LANG') {
              $flag = $lang['folder'];
              $langname = $lang['name']; 
            }
          endforeach;?>
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img class="flag-lang hidden-xs" src="{{ asset('mmb/images/flags/'.$flag.'.png') }}" width="16" height="12" alt="lang" /> {{ strtoupper($flag) }}
                        <span class="hidden-xs">
             <i class="fa fa-caret-down"></i>
            </span>
                    </a>
                    <ul class="dropdown-menu icons-right animated flipInX">
                        <li class="header"> {{ Lang::get('core.m_sel_lang') }} </li>
                        @foreach(SiteHelpers::langOption() as $lang)
                        <li>
                            <a href="{{ URL::to('home/lang/'.$lang['folder'])}}"><img class="flag-lang" src="{{ asset('mmb/images/flags/'. $lang['folder'].'.png')}}" width="16" height="11" alt="lang" /> {{ $lang['name'] }}</a>
                        </li>
                        @endforeach
                    </ul>
                            </div>

                    @endif
        
        @if(Session::get('gid') == 1 || Session::get('gid') == 2)
        <!-- <div class="header-link tips pull-right" title="{{ Lang::get('core.support') }}" data-placement="bottom">
            <a href="{{ url('support')}}">
              <i class="fa fa-life-ring fa-lg" aria-hidden="true"></i>
              <span class="label label-danger">@if ($support!='0') {{$support }} @endif</span>
            </a>
        </div> -->
        <!-- <div class="header-link tips pull-right" title="{{ Lang::get('core.testimonials') }}" data-placement="bottom">
            <a href="{{ url('testimonials')}}">
              <i class="fa fa-smile-o fa-lg"  aria-hidden="true"></i>
              <span class="label label-danger">@if ($reviews!='0') {{$reviews }} @endif</span>
            </a>
        </div> -->
        <!-- <div class="header-link tips pull-right" title="{{ Lang::get('core.blogcomments') }}" data-placement="bottom">
            <a href="{{ url('commentscheck')}}">
              <i class="fa fa-comment fa-lg" aria-hidden="true"></i>
                <span class="label label-danger">@if ($comments!='0') {{$comments }} @endif</span>
            </a>
        </div> -->
        <div class="header-link tips pull-right " title="{{ Lang::get('core.static_content') }}" data-placement="bottom">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true">
                        <i class="fa fa-list-alt fa-lg"></i>
                    </a>

                    <div class="dropdown-menu hdropdown bigmenu animated flipInX">
                        <table>
                            <tbody>
                            <tr>
                                <td>
                                    <a href="{{ url('core/pages')}}">
                                        <i class="fa fa-newspaper-o fa-lg fa-fw text-gray"></i>
                                        <h5>{{ Lang::get('core.page') }}</h5>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url('core/posts')}}">
                                        <i class="fa fa-rss-square fa-lg fa-fw text-gray"></i>
                                        <h5>{{ Lang::get('core.blog') }}</h5>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url('core/banners')}}">
                                        <i class="fa fa-bookmark fa-lg fa-fw text-gray"></i>
                                        <h5>{{ Lang::get('core.banners') }}</h5>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ url('testimonials')}}">
                                        <i class="fa fa-smile-o fa-lg fa-fw text-gray"></i>
                                        <h5>{{ Lang::get('core.testimonials') }}</h5>
                                    </a>
                                </td>
                                @if(Session::get('gid') == 1)
                                <td>
                                    <a href="{{ url('faqs')}}">
                                        <i class="fa fa-question-circle fa-lg fa-fw text-red"></i>
                                        <h5>{{ Lang::get('core.faq') }}</h5>
                                    </a>
                                </td>
                                @endif
                                @if(Session::get('gid') == 1)
                                <td>
                                    <a href="{{ url('core/forms')}}">
                                        <i class="fa fa-list fa-lg fa-fw text-red"></i>
                                        <h5>{{ Lang::get('core.form') }}</h5>
                                    </a>
                                </td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    </div>
            
        </div>

        @if( Auth::User()->group_id == 2 || Auth::User()->group_id == 4 )

        <div class="header-link tips pull-right " title="" data-placement="bottom">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="true">
                        <span id="notification_number" class="fa-stack has-badge" @if($notifications!='0')data-count="{{$notifications}}" @endif >
                          <i class="fa fa-bell fa-lg fa-stack-1x" style="line-height: 1.1em"></i>
                        </span>
                    </a>

                    <ul class="dropdown-menu dropdown-alerts" style="right: 0px;">

                        <li class="divider"></li>
                        
                        @foreach( App\Models\Booktour::where('owner_id',CNF_OWNER)->orderBy('created_at','desc')->get()->slice(0,5) as $notification)

                        <?php  
                            $tour = App\Models\Tours::find($notification->tourID);
                            $user = App\User::find($notification->entry_by);
                        ?>

                        @if($user != null && $tour != null)

                        <li>
                                <div>

                                    <p style="padding-right: 15px;padding-left: 15px">{{ $user->first_name }} {{ Lang::get('core.enter_booking') }} {{ $tour->tour_name }} {{ Lang::get('core.tour') }}</p>
               
                                </div>
                        </li>

                        <li class="divider"></li>

                        @endif

                        @endforeach

                        <li>
                            <a href="/createbooking" style="text-align: center;">
                                <div>
                                    <p style="text-decoration: underline">{{ Lang::get('core.view_all') }}</p>
                                </div>
                            </a>
                        </li>
                        
                    </ul>
            
        </div>

        @endif

        <div class="header-link tips pull-right credit-top" title="Credit" data-placement="bottom">
            <a href="/core/credit">
                <?php 
                $credittotals = \DB::table('credittotals')->where('owner_id', '=' ,CNF_OWNER)->get();
                if ($credittotals == null) {
                    $total_credit = 0;
                }else{
                    $total_credit = reset($credittotals)->total_credit;
                }
                echo Lang::get('core.m_credits').": ".$total_credit." "; 
                ?>
              <i class="fa fa-ticket fa-lg" aria-hidden="true"></i>
                <span class="label label-danger">@if ($comments!='0') {{$comments }} @endif</span>
            </a>
        </div>
        
        @endif




    </nav>
</header>
