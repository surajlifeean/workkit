<div class="d-flex position-fixed flex-column header-body" style="width: 100%;">

    <div class="main-header">

        <div class="logo">
            <img src="{{asset('assets/images/logo.png')}}" alt="">
        </div>

        {{-- <div class="menu-toggle">
            <div></div>
            <div></div>
            <div></div>
        </div> --}}

        <div class="margin_auto"></div>

        <div class="header-part-right">
            <div class="dropdown">
                <div id="notification-count" class="text-white"></div>
                <i class="i-Bell text-success header-icon" role="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton2" style="width: 20rem !important; height: 30rem !important; overflow: auto;">
                    <div class="card " style="box-shadow: none !important;">
                        <div class="card-body bg-transparent" id="notification_box">

                        </div>
                    </div>
                </div>
            </div>
            <!-- Full screen toggle -->
            <i class="i-Full-Screen header-icon text-success d-none d-sm-inline-block" data-fullscreen></i>
            <!-- Grid menu Dropdown -->
            <div class="dropdown widget_dropdown">
                <i class="i-Globe text-success header-icon" role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <div class="menu-icon-grid">
                        <a href="{{route('language.switch','en')}}">
                            <img class="flag-icon" src="{{asset('assets/flags/gb.svg')}}">English
                        </a>
                        <a href="{{route('language.switch','fr')}}">
                            <img class="flag-icon" src="{{asset('assets/flags/fr.svg')}}">Frensh
                        </a>
                        <a href="{{route('language.switch','ar')}}">
                            <img class="flag-icon" src="{{asset('assets/flags/sa.svg')}}">Arabic
                        </a>
                    </div>
                </div>
            </div>

            <!-- User avatar dropdown -->
            <div class="dropdown">
                <div class="user col align-self-end">
                    <img src="{{asset('assets/images/avatar/'.Auth::user()->avatar)}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <div class="dropdown-header">
                            <i class="i-Lock-User mr-1"></i> {{ Auth::user()->username }}
                        </div>
                        @if(auth()->user()->role_users_id == 1)
                        <a class="dropdown-item" href="{{route('profile.index')}}">{{ __('translate.Profile') }}</a>
                        @elseif(auth()->user()->role_users_id == 3)
                        <a class="dropdown-item" href="{{route('client_profile')}}">{{ __('translate.Profile') }}</a>
                        @else
                        <a class="dropdown-item" href="{{route('employee_profile')}}">{{ __('translate.Profile') }}</a>
                        @endif
                        @can('settings')
                        <a class="dropdown-item" href="{{route('system_settings.index')}}">{{ __('translate.System_Settings') }}</a>
                        @endcan

                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="d-flex align-items-center justify-content-between sub-header">
        <div class="menu-toggle">
            <div></div>
            <div></div>
            <div></div>
        </div>

        <a class="m-0">{{ __('translate.Dashboard') }} / <a> {{ __('translate.Home') }}</a></a>

        <div class="mr-auto"></div>
    </div>
</div>

<!-- header top menu end -->