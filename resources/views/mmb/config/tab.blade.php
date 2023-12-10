<div class="col-md-3" id="sidebar">
 <div class="theiaStickySidebar">
    <div class="box box-solid">
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
            @if(Session::get('gid') =='1'|| Session::get('gid') =='2')
                <li @if($active=='' ) class="active" @endif><a href="{{ URL::to('core/config/')}}"><i class="fa fa-info fa-fw fa-2x"></i> {{ Lang::get('core.t_generalsetting') }}</a></li>
            @endif
            @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li @if($active=='security' ) class="active" @endif><a href="{{ URL::to('core/config/security')}}"><i class="fa fa-desktop fa-fw fa-2x"></i> {{ Lang::get('core.sitesettings') }}</a></li>
            @endif
            {{-- @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li @if($active=='billplz' ) class="active" @endif><a href="{{ URL::to('core/config/billplz')}}"><i class="fa fa-money fa-fw fa-2x"></i> {{ Lang::get('core.billplz_integration') }}</a></li>
            @endif --}}

            @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li @if($active=='payment-integration' ) class="active" @endif><a href="{{ URL::to('core/config/payment-integration')}}"><i class="fa fa-money fa-fw fa-2x"></i> {{ Lang::get('core.payment_integration') }}</a></li>
            @endif

            @if((Session::get('gid') =='1' || Session::get('gid') =='2') && has_template_settings())
                <li @if($active=='template-settings' ) class="active" @endif>
                    <a href="{{ URL::to('core/template-settings')}}">
                        <i class="fa fa-eye fa-fw fa-2x"></i> {{ Lang::get('core.m_template') }}
                    </a>
                </li>
            @endif

            @if(Session::get('gid') =='1'|| Session::get('gid') =='2')
                <li @if($active=='credit' ) class="active" @endif><a href="{{ URL::to('core/credit/')}}"><i class="fa fa-ticket fa-fw fa-2x"></i> {{ Lang::get('core.m_credits') }}</a></li>
            @endif
            @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li @if($active=='users' ) class="active" @endif><a href="{{ URL::to('core/users/')}}"><i class="fa fa-user fa-fw fa-2x"></i> {{ Lang::get('core.m_users') }}</a></li>
            @endif
            @if(Session::get('gid') =='1')
                <li @if($active=='groups' ) class="active" @endif><a href="{{ URL::to('core/groups/')}}"><i class="fa fa-users fa-fw fa-2x"></i> {{ Lang::get('core.m_groups') }}</a></li>
            @endif
            @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li @if($active=='menu' ) class="active" @endif><a href="{{ URL::to('core/menu')}}"><i class="fa fa-bars fa-fw fa-2x"></i> {{ Lang::get('core.m_menu') }}</a></li>
            @endif
            @if(Session::get('gid') =='1')
                <li @if($active=='blast' ) class="active" @endif><a href="{{ URL::to('core/users/blast')}}"><i class="fa fa-at fa-fw fa-2x"></i> {{ Lang::get('core.m_blastemail') }}</a></li>
            @endif
            @if(Session::get('gid') =='1')
                <li @if($active=='email' ) class="active" @endif><a href="{{ URL::to('core/config/email')}}"><i class="fa fa-envelope fa-fw fa-2x"></i> {{ Lang::get('core.t_emailtemplate') }}</a></li>
            @endif
            @if(Session::get('gid') =='1')
                <li @if($active=='translation' ) class="active" @endif><a href="{{ URL::to('core/config/translation')}}"><i class="fa fa-language fa-fw fa-2x"></i> {{ Lang::get('core.tab_translation') }}</a></li>
            @endif
            @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li @if($active=='userlogs' ) class="active" @endif><a href="{{ URL::to('log')}}"><i class="fa fa-code fa-fw fa-2x"></i> {{ Lang::get('core.m_logs') }}</a></li>
            @endif
            @if(Session::get('gid') =='1')
                <li @if($active=='log' ) class="active" @endif><a href="{{ URL::to('core/config/clearlog')}}" class="clearCache"><i class="fa fa-refresh fa-fw fa-2x"></i> {{ Lang::get('core.m_clearcache') }}</a></li>
            @endif
            </ul>
        </div>
    </div>
</div>
</div>
	<script>
		jQuery('#sidebar').theiaStickySidebar({
			additionalMarginTop: 70
		});
	</script>
