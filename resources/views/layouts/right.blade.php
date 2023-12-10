<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <div class="tab-content">
        <div class="tab-pane active" id="control-sidebar-settings-tab">
            <ul class="control-sidebar-menu">
                @if(Session::get('gid') =='1')
                    <li>
                        <a href="{{ url('owners?return=')}}">
                            <i class="menu-icon fa fa-plus fa-2x bg-gray-light" ></i>
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_add_domain') }}</h4>
                                <p>{{ Lang::get('core.m_add_detail') }}</p>
                            </div>
                        </a>
                    </li>
                @endif
                @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li>
                    <a href="{{ url('core/config')}}">
                        <i class="menu-icon fa fa-info fa-2x bg-gray-light" ></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_setting') }}</h4>
                            <p>{{ Lang::get('core.dash_setting') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li>
                    <a href="{{ url('core/config/security')}}">
                        <i class="menu-icon fa fa-desktop fa-2x bg-gray-light" ></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.t_loginsecurity') }}</h4>
                            <p>{{ Lang::get('core.t_loginsecuritysmall') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1')
                    <li>
                        <a href="{{ url('/creditpackage')}}">
                            <i class="menu-icon fa fa-ticket fa-2x bg-gray-light" ></i>
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">{{ Lang::get('core.credit_package') }}</h4>
                                <p>{{ Lang::get('core.credit_package') }}</p>
                            </div>
                        </a>
                    </li>
                @endif
                @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <?php $credit = SiteHelpers::GetCreditTotal() ;?>
                <li>
                    <a href="{{ url('core/credit')}}">
                        <i class="menu-icon fa fa-ticket fa-2x bg-gray-light" ></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_credits') }} ({{$credit}})</h4>
                            <p>{{ Lang::get('core.dash_credits') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li>
                    <a href="{{ url('core/users')}}">
                        <i class="menu-icon fa fa-user fa-2x bg-gray-light" ></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_users') }}<span class="pull-right-container">
                             <?php $us = SiteHelpers::ResumeUserStatus() ;?>
                              <small class="label pull-right bg-red tips" title="{{Lang::get('core.pending')}}" id="s_banned"> {{ $us->s_banned}}</small>
                              <small class="label pull-right bg-olive tips" title="{{Lang::get('core.fr_minactive')}}"  id="s_inactive">{{ $us->s_inactive}}</small>
                              <small class="label pull-right bg-green tips" title="{{Lang::get('core.fr_mactive')}}"  id="s_active">{{ $us->s_active}}</small>
                            </span></h4>
                            <p>{{ Lang::get('core.dash_users') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1')
                <li>
                    <a href="{{ url('core/groups')}}">
                        <i class="menu-icon fa fa-users fa-2x bg-gray-light" ></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_groups') }}</h4>
                            <p>{{ Lang::get('core.dash_usergroup') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1')
                    <li>
                        <a href="{{ url('mmb/module')}}">
                            <i class="menu-icon fa fa-folder fa-2x bg-gray-light" ></i>
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_config') }}</h4>
                                <p>{{ Lang::get('core.dash_module') }}</p>
                            </div>
                        </a>
                    </li>
                @endif
                @if(Session::get('gid') =='1')
                    <li>
                        <a href="{{ url('core/config/payment-integration') }}">
                        {{-- <a href="{{ url('paymentgateways') }}"> --}}
                            <i class="menu-icon fa fa-credit-card fa-2x bg-gray-light" ></i>
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">{{ Lang::get('core.paymentgateway') }}</h4>
                                <p>{{ Lang::get('core.m_paymentgateway') }}</p>
                            </div>
                        </a>
                    </li>
                @endif
                @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li>
                    <a href="{{ url('core/menu')}}">
                        <i class="menu-icon fa fa-bars fa-2x bg-gray-light" ></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_menu') }}</h4>

                            <p>{{ Lang::get('core.m_menu2') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1')
                <li>
                    <a href="{{ url('core/users/blast')}}">
                        <i class="menu-icon fa fa-at fa-2x bg-gray-light" ></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_blastemail') }}</h4>

                            <p>{{ Lang::get('core.t_blastemailsmall') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1')
                <li>
                    <a href="{{ url('core/config/email')}}">
                        <i class="menu-icon fa fa-envelope fa-2x bg-gray-light" ></i>

                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.t_emailtemplate') }}</h4>
                            <p>{{ Lang::get('core.t_emailtemplatesmall') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1')
                <li>
                    <a href="{{ url('core/config/translation')}}">
                        <i class="menu-icon fa fa-language fa-2x bg-gray-light"  ></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.tab_translation') }}</h4>
                            <p>{{ Lang::get('core.tab_translationsmall') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1' || Session::get('gid') =='2')
                <li>
                    <a href="{{ url('log')}}">
                        <i class="menu-icon fa fa-code fa-2x bg-gray-light" ></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_logs') }}</h4>
                            <p>{{ Lang::get('core.dash_logs') }}</p>
                        </div>
                    </a>
                </li>
                @endif
                @if(Session::get('gid') =='1')
                <li>
                    <a href="{{ url('core/config/clearlog')}}" class="clearCache">
                        <i class="menu-icon fa fa-refresh fa-2x bg-gray-light" ></i>
                        <div class="menu-info">
                            <h4 class="control-sidebar-subheading">{{ Lang::get('core.m_clearcache') }}</h4>
                            <p>{{ Lang::get('core.dash_clearcache') }}</p>
                        </div>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</aside>
<div class="control-sidebar-bg"></div>
