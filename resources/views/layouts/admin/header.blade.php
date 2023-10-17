<!-- BEGIN: Header-->
@php
$user_profile     = (Auth::user()->profile != null) ? asset('storage/store/users/' . Auth::user()->profile) : asset('admin/images/user-icon.png');
@endphp
<header class="page-topbar" id="header">
        <div class="navbar navbar-fixed">
            <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-light-blue-cyan">
                <div class="nav-wrapper">
                    <ul class="left">
                        <li>
                            <h1 class="logo-wrapper"><a class="brand-logo darken-1" href="{{ url('dashboard/') }}"><img src="{{ asset('admin/images/logo/materialize-logo.png') }}" alt="materialize logo"><span class="logo-text hide-on-med-and-down">Ministry of Environment, Republic of Iraq</span></a></h1>
                        </li>
                    </ul>
                    <ul class="navbar-list right">
                        <li class="dropdown-language"><a class="waves-effect waves-block waves-light translation-button" href="javascript:void(0);" data-target="translation-dropdown"><span class="flag-icon flag-icon-gb"></span></a></li>
                        <li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);"><i class="material-icons">settings_overscan</i></a></li>
                        <li class="hide-on-large-only"><a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search </i></a></li>
                        <li><a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown"><i class="material-icons">notifications_none</i></a></li>
                        <li><a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown"><span class="avatar-status avatar-online"><img src="{{auth()->user()->profile_url}}" alt="Admin"><i></i></span></a></li>
                    </ul>
                    <!-- translation-button-->
                    <ul class="dropdown-content" id="translation-dropdown">
                    <li class="dropdown-item"><a class="grey-text text-darken-1 active" href="#" data-language="en"><i class="flag-icon flag-icon-{{ Config::get('languages')[App::getLocale()]['flag-icon']}}"></i> {{ Config::get('languages')[App::getLocale()]['display'] }}</a></li>

                    @foreach (Config::get('languages') as $lang => $language)
                        @if ($lang != App::getLocale())
                            <a class="dropdown-item" href=""> </a>
                            <li class="dropdown-item"><a class="grey-text text-darken-1 active" href="{{ route('lang.switch', $lang) }}" data-language="en"><i class="flag-icon flag-icon-{{$language['flag-icon']}}"></i> {{$language['display']}}</a></li>
                        @endif
                    @endforeach

                        <!-- <li class="dropdown-item"><a class="grey-text text-darken-1 active" href="#!" data-language="en"><i class="flag-icon flag-icon-gb"></i> English</a></li>
                        <li class="dropdown-item"><a class="grey-text text-darken-1" href="#!" data-language="fr"><i class="flag-icon"></i> Arabic</a></li> -->
                    </ul>
                    <!-- notifications-dropdown-->
                    <ul class="dropdown-content" id="notifications-dropdown">
                        <!-- <li>
                            <h6>NOTIFICATIONS<span class="new badge">2</span></h6>
                        </li>
                        <li class="divider"></li>
                         <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle cyan small">add_shopping_cart</span> A new document uploaded!</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">2 hours ago</time>
                        </li>
                        <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle red small">stars</span> New project submitted. </a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">3 days ago</time>
                        </li> -->
                        <!--<li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle teal small">settings</span> Settings updated</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">4 days ago</time>
                        </li>
                        <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle deep-orange small">today</span> Director meeting started</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">6 days ago</time>
                        </li>
                        <li><a class="black-text" href="#!"><span class="material-icons icon-bg-circle amber small">trending_up</span> Generate monthly report</a>
                            <time class="media-meta grey-text darken-2" datetime="2015-06-12T20:50:48+08:00">1 week ago</time>
                        </li> -->
                    </ul>
                    <!-- profile-dropdown-->
                    <ul class="dropdown-content" id="profile-dropdown">
                        <li><a class="grey-text text-darken-1" href="{{ url(ROUTE_PREFIX.'/profile') }}"><i class="material-icons">person_outline</i> Profile</a></li>
                        <li class="divider"></li>
                        <li><a class="grey-text text-darken-1" href="javascript:" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="material-icons">keyboard_tab</i> Logout</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </ul>
                </div>
                <nav class="display-none search-sm">
                    <div class="nav-wrapper">
                        <form id="navbarForm">
                            <div class="input-field search-input-sm">
                                <input class="search-box-sm" type="search" required="" id="search" placeholder="Explore Materialize" data-search="template-list">
                                <label class="label-icon" for="search"><i class="material-icons search-sm-icon">search</i></label><i class="material-icons search-sm-close">close</i>
                                <ul class="search-list collection search-list-sm display-none"></ul>
                            </div>
                        </form>
                    </div>
                </nav>
            </nav>
            <!-- BEGIN: Horizontal nav start-->
            <nav class="white hide-on-med-and-down" id="horizontal-nav">
                <div class="nav-wrapper">
                    <ul class="right hide-on-med-and-down" id="ul-horizontal-nav" data-menu="menu-navigation">
                        <li>
                            <a class="@if (Request::is(ROUTE_PREFIX.'/dashboard*')) active @endif" href="{{ url(ROUTE_PREFIX.'/dashboard') }}"> <i class="material-icons">dashboard</i><span><span class="dropdown-title" data-i18n="Dashboard">{{__('locale.Dashboard')}}</span></span></a>
                        </li>
                        <li>
                            <a class="@if (Request::is(ROUTE_PREFIX.'/users*') ||  Request::is(ROUTE_PREFIX.'/users/create*')) active @endif" href="{{ url(ROUTE_PREFIX.'/users') }}"> <i class="material-icons">person</i><span>{{__('locale.Users')}}</span></span></a>
                        </li>
                        <li><a class="dropdown-menu @if (Request::is(ROUTE_PREFIX.'/designations*') ||  Request::is(ROUTE_PREFIX.'/project-types*')) active @endif" href="Javascript:void(0)" data-target="SettingsDropdown"><i class="material-icons">settings</i><span><span class="dropdown-title" data-i18n="Dashboard">{{__('locale.Masters')}}</span><i class="material-icons right">keyboard_arrow_down</i></span></a>
                            <ul class="dropdown-content dropdown-horizontal-list" id="SettingsDropdown">
                                <li data-menu=""><a class="@if (Request::is(ROUTE_PREFIX.'/designations*')) active @endif" href="{{ url(ROUTE_PREFIX.'/designations') }}"><span data-i18n="Modern">{{__('locale.Designations')}}</span></a></li>
                                <li data-menu=""><a class="@if (Request::is(ROUTE_PREFIX.'/project-types*')) active @endif" href="{{ url(ROUTE_PREFIX.'/project-types') }}"><span data-i18n="eCommerce">{{__('locale.Project Types')}}</span></a></li>
                                <li data-menu=""><a class="@if (Request::is(ROUTE_PREFIX.'/departments*')) active @endif" href="{{ url(ROUTE_PREFIX.'/departments') }}"><span data-i18n="eCommerce">{{__('locale.Departments')}}</span></a></li>
                                <li data-menu=""><a class="@if (Request::is(ROUTE_PREFIX.'/settings*')) active @endif" href="{{ url(ROUTE_PREFIX.'/settings') }}"><span data-i18n="eCommerce">{{__('locale.Settings')}}</span></a></li>
                            </ul>
                        </li>
                        <li>
                            <a class="@if (Request::is(ROUTE_PREFIX.'/roles*') ||  Request::is(ROUTE_PREFIX.'/roles/create*')) active @endif" href="{{ url(ROUTE_PREFIX.'/roles') }}"> <i class="material-icons">lock</i><span>{{__('locale.Roles')}} & {{__('locale.Permissions')}}</span></span></a>
                        </li>
                        <!-- <li>
                            <a class="@if (Request::is(ROUTE_PREFIX.'/roles*') ||  Request::is(ROUTE_PREFIX.'/roles/create*')) active @endif" href="javascript:"> <i class="material-icons">vpn_key</i><span>{{__('locale.Permissions')}}</span></span></a>
                        </li> -->
                    </ul>
                </div>
                <!-- END: Horizontal nav start-->
            </nav>
        </div>
    </header>
    <!-- END: Header-->
    <!-- <ul class="display-none" id="default-search-main">
        <li class="auto-suggestion-title"><a class="collection-item" href="#">
                <h6 class="search-title">FILES</h6>
            </a></li>
        <li class="auto-suggestion"><a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar"><img src="{{ asset('admin/images/icon/pdf-image.png') }}" width="24" height="30" alt="sample image"></div>
                        <div class="member-info display-flex flex-column"><span class="black-text">Two new item submitted</span><small class="grey-text">Marketing Manager</small></div>
                    </div>
                    <div class="status"><small class="grey-text">17kb</small></div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar"><img src="{{ asset('admin/images/icon/doc-image.png') }}" width="24" height="30" alt="sample image"></div>
                        <div class="member-info display-flex flex-column"><span class="black-text">52 Doc file Generator</span><small class="grey-text">FontEnd Developer</small></div>
                    </div>
                    <div class="status"><small class="grey-text">550kb</small></div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar"><img src="{{ asset('admin/images/icon/xls-image.png') }}" width="24" height="30" alt="sample image"></div>
                        <div class="member-info display-flex flex-column"><span class="black-text">25 Xls File Uploaded</span><small class="grey-text">Digital Marketing Manager</small></div>
                    </div>
                    <div class="status"><small class="grey-text">20kb</small></div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar"><img src="{{ asset('admin/images/icon/jpg-image.png') }}" width="24" height="30" alt="sample image"></div>
                        <div class="member-info display-flex flex-column"><span class="black-text">Anna Strong</span><small class="grey-text">Web Designer</small></div>
                    </div>
                    <div class="status"><small class="grey-text">37kb</small></div>
                </div>
            </a></li>
        <li class="auto-suggestion-title"><a class="collection-item" href="#">
                <h6 class="search-title">MEMBERS</h6>
            </a></li>
        <li class="auto-suggestion"><a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar"><img class="circle" src="{{ asset('admin/images/avatar/avatar-7.png') }}" width="30" alt="sample image"></div>
                        <div class="member-info display-flex flex-column"><span class="black-text">John Doe</span><small class="grey-text">UI designer</small></div>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar"><img class="circle" src="{{ asset('admin/images/avatar/avatar-8.png') }}" width="30" alt="sample image"></div>
                        <div class="member-info display-flex flex-column"><span class="black-text">Michal Clark</span><small class="grey-text">FontEnd Developer</small></div>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar"><img class="circle" src="{{ asset('admin/images/avatar/avatar-10.png') }}" width="30" alt="sample image"></div>
                        <div class="member-info display-flex flex-column"><span class="black-text">Milena Gibson</span><small class="grey-text">Digital Marketing</small></div>
                    </div>
                </div>
            </a></li>
        <li class="auto-suggestion"><a class="collection-item" href="#">
                <div class="display-flex">
                    <div class="display-flex align-item-center flex-grow-1">
                        <div class="avatar"><img class="circle" src="{{ asset('admin/images/avatar/avatar-12.png') }}" width="30" alt="sample image"></div>
                        <div class="member-info display-flex flex-column"><span class="black-text">Anna Strong</span><small class="grey-text">Web Designer</small></div>
                    </div>
                </div>
            </a></li>
    </ul>
    <ul class="display-none" id="page-search-title">
        <li class="auto-suggestion-title"><a class="collection-item" href="#"><h6 class="search-title">PAGES</h6> </a></li>
    </ul>
    <ul class="display-none" id="search-not-found">
        <li class="auto-suggestion"><a class="collection-item display-flex align-items-center" href="#"><span class="material-icons">error_outline</span><span class="member-info">No results found.</span></a></li>
    </ul> -->