<?php $plugins = \Nwidart\Modules\Facades\Module::allEnabled(); ?>
<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <ul class="navigation-left accordion mt-3" id="accordionExample">
            {{-- <li class="">
                <a class="link_btn {{ request()->is('dashboard/admin') ? 'active_link' : '' }}" href="/dashboard/admin">
                    <i class="nav-icon i-Bar-Chart"></i>
                    <span class="">{{ __('translate.Dashboard') }}</span>                   
                </a>
            </li> --}}
            @if (auth()->user()->role_users_id == 1)
            @can('Dashboard_view')
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/admin') ? 'active_link' : '' }}" href="/dashboard/admin">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="">{{ __('translate.Dashboard') }}</span>                   
                    </a>
                </li>
            @endcan
            @elseif(auth()->user()->role_users_id == 3)
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/client') ? 'active_link' : '' }}" href="/dashboard/client">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="nav-text">{{ __('translate.Dashboard') }}</span>
                    </a>
                </li>
            @elseif(auth()->user()->role_users_id == 2)
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/employee') ? 'active_link' : '' }}" href="/dashboard/employee">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="nav-text">{{ __('translate.Dashboard') }}</span>
                    </a>
                </li>
            @elseif(auth()->user()->role_users_id == 4)
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/hr') ? 'active_link' : '' }}" href="/dashboard/hr">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="nav-text">{{ __('translate.Dashboard') }}</span>
                    </a>
                </li>
            @endif
            
            @if (auth()->user()->role_users_id == 2 || auth()->user()->role_users_id == 4)
                <li class="">
                    <a class="link_btn {{ request()->is('employee/my_requests') ? 'active_link' : '' }}" href="/employee/my_requests">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="nav-text">{{ __('translate.My_Request') }}</span>
                    </a>
                </li>
            @endif

            @if (auth()->user()->role_users_id == 3)
                 <li class="">
                     <a class="link_btn {{ request()->is('client_projects') ? 'active_link' : '' }}" href="/client_projects">
                         <i class="nav-icon i-Dropbox"></i>
                         <span class="nav-text">{{ __('translate.Projects') }}</span>
                     </a>
                 </li>
     
                 <li class="">
                     <a class="link_btn {{ request()->is('client_tasks') ? 'active' : '' }}" href="/client_tasks">
                         <i class="nav-icon i-Check"></i>
                         <span class="nav-text">{{ __('translate.Tasks') }}</span>
                     </a>
                 </li>
            @endif

            @if (auth()->user()->can('office_shift_view') || auth()->user()->can('event_view') || auth()->user()->can('holiday_view') || auth()->user()->can('award_view') || auth()->user()->can('complaint_view') || auth()->user()->can('travel_view'))
                <li class="" data-item="hr">
                    <a class="link_btn {{ request()->is('hr/*') ? 'active_link' : '' }}" href="#"
                        aria-expanded="true" data-toggle="collapse" data-target="#collapse_hr"
                        aria-expanded="true" aria-controls="collapse_hr">
                        <div class="d-flex align-items-center justify-content-start">
                            <i class="nav-icon i-Library"></i>
                            <span class="">{{ __('translate.Hr_Management') }}</span>
                            <i class="nav-icon i-Arrow-Right"></i>
                        </div>
                        
                    </a>
                    <ul id="collapse_hr" class="submenu list-unstyled collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <li>
                            dhhdhh
                        </li>
                    </ul>
                </li>
            @endif

            <li class="">
                <a href="javascript:void(0)" class="link_btn "
                     aria-expanded="true" data-toggle="collapse" data-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                     <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span>dashboard</span>
                        <i class="nav-icon i-Arrow-Right"></i>
                     </div>
                </a>
                <ul id="collapse1" class="submenu list-unstyled collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <li class="">
                        
                    </li>
                </ul>
            </li>
           
        </ul>
    </div>
</div>

<script>
    const link_btns = document.querySelectorAll('.link_btn');

    link_btns.forEach(link_btn => {
      link_btn.addEventListener('click', () => {
        const right_arrow = link_btn.querySelector('div i:last-child');
            right_arrow.classList.toggle('i_toggle_position');
      });
    });

</script>