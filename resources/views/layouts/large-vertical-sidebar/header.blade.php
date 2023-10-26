@php
    $setting = App\Models\Setting::where('id', 1)->first();
@endphp
<style>
    :root{
        --blue: #003473;
        --indigo: #3F51B5;
        --purple: #663399;
        --pink: #CB3066;
        --red: #f44336;
        --orange: #e97d23;
        --yellow: #ffc107;
        --green: #4caf50;
        --teal: #20c997;
        --cyan: #9c27b0;
        --white: #fff;
        --gray: #70657b;
        --gray-dark: #52495a;
        --primary: #663399;
        --secondary: #52495a;
        --success: #4caf50;
        --info: #003473;
        --warning: #ffc107;
        --danger: #f44336;
        --light: #bbb;
        --dark: #47404f;
    }
    .child_links span:hover, .child_links i:hover{
       color: var(--{{ $setting->theme_color }});
    }
    
</style>
<div class="d-flex position-fixed flex-column header-body" style="width: 100%;">

    <div class="main-header">

        <div class="logo">
            <img src="{{ asset('assets/images/' . ($setting->is_dark_mode == 0 ? $setting->logo : $setting->dark_logo)) }}" alt="">
        </div>

        <div class="margin_auto"></div>

        <div class="header-part-right">
            @php
                $currentLanguage = app()->getLocale();
            @endphp
            <!-- Full screen toggle -->
            <i class="i-Full-Screen header-icon text-white d-none d-sm-inline-block" data-fullscreen></i>
            <!-- Grid menu Dropdown -->
            <div class="dropdown widget_dropdown">
                <img src="{{ $currentLanguage === 'en' ? asset('assets/flags/gb.svg') : ($currentLanguage === 'fr' ? asset('assets/flags/fr.svg') : asset('assets/flags/sa.svg')) }}" alt="lang_img" 
                role="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 21px;width: 28px;border-radius: 0.3rem;margin-right: 14px;">
                <!-- <i class="i-Globe text-success header-icon" ></i> -->
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
            <!-- sun -->
            <svg id="sun" class="sun cursor-pointer " xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-sun light-mode">
                <circle cx="12" cy="12" r="5"></circle>
                <line x1="12" y1="1" x2="12" y2="3"></line>
                <line x1="12" y1="21" x2="12" y2="23"></line>
                <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                <line x1="1" y1="12" x2="3" y2="12"></line>
                <line x1="21" y1="12" x2="23" y2="12"></line>
                <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
            </svg>
            <!-- moon -->
            <svg id="moon" class="moon cursor-pointer " xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-moon dark-mode">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
            </svg>
            
            <!-- Notification bell -->
            <div class="dropdown">
                <div id="notification-count" class="bg-success"></div>

                <!-- @dump($currentLanguage) -->
                <i class="i-Bell text-white header-icon"  role="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="margin-right: -8px;font-weight: 900;"></i>
                <div class="dropdown-menu dropdown-menu-right p-0" aria-labelledby="dropdownMenuButton2" style=" overflow: auto; border-radius: 1.5rem;">
                    <div class="card " style="box-shadow: none !important; width: 20rem !important; height: 30rem !important;">
                        <div class="card-body bg-transparent" id="notification_box">

                        </div>
                    </div>
                </div>
            </div>
            <!-- User avatar dropdown -->
            <div class="dropdown">
                <div class="user col align-self-end position-relative">
                    <img src="{{asset('assets/images/avatar/'.Auth::user()->avatar)}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <div class="bg-success position-absolute active_div"></div>
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
        @php 
        $currentUrl = url()->current();
        $path = parse_url($currentUrl, PHP_URL_PATH);
        $firstPart = substr($path, strrpos($path, '/'));
        $lastPart = substr($path, strrpos($path, '/') + 1);

        $capitalizedLastPart = ucfirst($lastPart);
        @endphp
        <!-- @dump($capitalizedLastPart, $firstPart) -->
        <a class="m-0">{{ __('translate.Dashboard') }} / <a> {{ __('translate.Home') }}</a></a>

        <div class="mr-auto"></div>
    </div>
</div>
<script>
    let isDark = {{  $setting->is_dark_mode }};
    if (isDark == 1) {
        document.body.classList.add('dark-theme');
    }

    document.getElementById('moon').addEventListener('click', function() {
        document.body.classList.toggle('dark-theme');
    });
    
    document.getElementById('sun').addEventListener('click', function() {
        document.body.classList.toggle('dark-theme');
    });
</script>
<!-- header top menu end -->