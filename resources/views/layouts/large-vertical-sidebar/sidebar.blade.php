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
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="">{{ __('translate.Dashboard') }}</span> 
                        </div>                  
                    </a>
                </li>
            @endcan
            @elseif(auth()->user()->role_users_id == 3)
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/client') ? 'active_link' : '' }}" href="/dashboard/client">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="nav-text">{{ __('translate.Dashboard') }}</span>
                        </div>
                    </a>
                </li>
            @elseif(auth()->user()->role_users_id == 2)
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/employee') ? 'active_link' : '' }}" href="/dashboard/employee">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="nav-text">{{ __('translate.Dashboard') }}</span>
                        </div>
                    </a>
                </li>
            @elseif(auth()->user()->role_users_id == 4)
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/hr') ? 'active_link' : '' }}" href="/dashboard/hr">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="nav-text">{{ __('translate.Dashboard') }}</span>
                        </div>
                    </a>
                </li>
            @endif
            
            @if (auth()->user()->role_users_id == 2 || auth()->user()->role_users_id == 4)
                <li class="">
                    <a class="link_btn {{ request()->is('employee/my_requests') ? 'active_link' : '' }}" href="/employee/my_requests">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="nav-text">{{ __('translate.My_Request') }}</span>
                        </div>
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
                     <a class="link_btn {{ request()->is('client_tasks') ? 'active_link' : '' }}" href="/client_tasks">
                         <i class="nav-icon i-Check"></i>
                         <span class="nav-text">{{ __('translate.Tasks') }}</span>
                     </a>
                 </li>
            @endif

            @if (auth()->user()->can('office_shift_view') || auth()->user()->can('event_view') || auth()->user()->can('holiday_view') || auth()->user()->can('award_view') || auth()->user()->can('complaint_view') || auth()->user()->can('travel_view'))
                <li class="" data-item="hr">
                    <a class="link_btn {{  ( request()->is('hr/*') || request()->is('core/*') || request()->is('users')) ? 'active_link' : '' }}" href="#"
                        aria-expanded="true" data-toggle="collapse" data-target="#collapse_hr"
                        aria-expanded="true" aria-controls="collapse_hr">
                        <div class="d-flex align-items-center justify-content-start">
                            <i class="nav-icon i-Library"></i>
                            <span class="">{{ __('translate.Hr_Management') }}</span>
                            <i class="nav-icon i-Arrow-Right"></i>
                        </div>
                        
                    </a>
                    <ul id="collapse_hr" class="submenu list-unstyled collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        @if (auth()->user()->role_users_id == 1)
            
                        @can('company_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'company.index' ? 'open' : '' }}"
                                    href="{{ route('company.index') }}">
                                    <i class=" i-Management"></i>
                                    <span class="item-name">{{ __('translate.Company') }}</span>
                                </a>
                            </li>
                        @endcan
                
                        @can('department_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'departments.index' ? 'open' : '' }}"
                                    href="{{ route('departments.index') }}">
                                    <i class="nav-icon i-Shop"></i>
                                    <span class="item-name">{{ __('translate.Departments') }}</span>
                                </a>
                            </li>
                        @endcan
                
                        @can('designation_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'designations.index' ? 'open' : '' }}"
                                    href="{{ route('designations.index') }}">
                                    <i class="nav-icon i-Shutter"></i>
                                    <span class="item-name">{{ __('translate.Designations') }}</span>
                                </a>
                            </li>
                        @endcan

                        @can('user_view')
                           <li class="child_links">
                               <a class="" href="/users">
                                   <i class="nav-icon i-Business-Mens"></i>
                                   <span class="item-name">{{ __('translate.User_Controller') }}</span>
                               </a>
                               
                           </li>
                        @endcan
     
                        @endif

                        @can('office_shift_view')
                            <li class="child_links">
                                <a href="{{ route('office_shift.index') }}"
                                    class="{{ Route::currentRouteName() == 'office_shift.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Clock"></i>
                                    <span class="item-name">{{ __('translate.Office_Shift') }}</span>
                                </a>
                            </li>
                        @endcan

                        @can('event_view')
                            <li class="child_links">
                                <a href="{{ route('event.index') }}"
                                    class="{{ Route::currentRouteName() == 'event.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Clock-4"></i>
                                    <span class="item-name">{{ __('translate.Events') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('holiday_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'holiday.index' ? 'open' : '' }}"
                                    href="{{ route('holiday.index') }}">
                                    <i class="nav-icon i-Christmas-Bell"></i>
                                    <span class="item-name">{{ __('translate.Holidays') }}</span>
                                </a>
                            </li>
                        @endcan

                        @if (auth()->user()->can('award_view') || auth()->user()->can('award_type'))
                            <li class="child_links" id="accordionExample1">
                                <a 
                                aria-expanded="true" data-toggle="collapse" data-target="#collapse_awards"
                                aria-expanded="true" aria-controls="collapse_awards">
                                    <i class="nav-icon i-Gift-Box"></i>
                                    <span class="item-name">{{ __('translate.Awards') }}</span>
                                    <i class="ml-2 i-Arrow-Down"></i>
                                </a>
                                <ul id="collapse_awards" class="collapse submenu list-unstyled" aria-labelledby="headingOne" data-parent="#accordionExample1">
                                    @can('award_view')
                                        <li class="child_links"><a class="{{ Route::currentRouteName() == 'award.index' ? 'open' : '' }}"
                                                href="{{ route('award.index') }}">{{ __('translate.Award_List') }}</a></li>
                                    @endcan
                                    @can('award_type')
                                        <li class="child_links"><a class="{{ Route::currentRouteName() == 'award_type.index' ? 'open' : '' }}"
                                                href="{{ route('award_type.index') }}">{{ __('translate.Award_Type') }}</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            @if (auth()->user()->can('company_view') || auth()->user()->can('department_view') || auth()->user()->can('designation_view') || auth()->user()->can('policy_view') || auth()->user()->can('announcement_view'))
             @if (auth()->user()->role_users_id != 1)
                <li class=" " data-item="core">
                    <a class="link_btn {{ request()->is('core/*') ? 'active_link' : '' }}" href="#"
                        aria-expanded="true" data-toggle="collapse" data-target="#collapse_core"
                        aria-expanded="true" aria-controls="collapse_core"
                        >
                        <div class="d-flex align-items-center justify-content-start">
                            <i class="nav-icon i-Management"></i>
                            @if(auth()->user()->role_users_id == 2 || auth()->user()->role_users_id == 4)
                            <span class="">{{ __('translate.Communication') }}</span>
                            @else
                            <span class="">{{ __('translate.Company_Management') }}</span>
                            @endif
                            <i class="nav-icon i-Arrow-Right"></i>
                        </div>
                    </a>
                    <ul id="collapse_core" class="submenu list-unstyled collapse" 
                     aria-labelledby="headingOne" data-parent="#accordionExample"
                    >
                        @can('company_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'company.index' ? 'open' : '' }}"
                                    href="{{ route('company.index') }}">
                                    <i class="nav-icon i-Management"></i>
                                    <span class="item-name">{{ __('translate.Company') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('department_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'departments.index' ? 'open' : '' }}"
                                    href="{{ route('departments.index') }}">
                                    <i class="nav-icon i-Shop"></i>
                                    <span class="item-name">{{ __('translate.Departments') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('designation_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'designations.index' ? 'open' : '' }}"
                                    href="{{ route('designations.index') }}">
                                    <i class="nav-icon i-Shutter"></i>
                                    <span class="item-name">{{ __('translate.Designations') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('policy_view')
                            <li class="child_links">
                                <a href="{{ route('policies.index') }}"
                                    class="{{ Route::currentRouteName() == 'policies.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Danger"></i>
                                    <span class="item-name">{{ __('translate.Policies') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('announcement_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'announcements.index' ? 'open' : '' }}"
                                    href="{{ route('announcements.index') }}">
                                    <i class="nav-icon i-Letter-Open"></i>
                                    <span class="item-name">{{ __('translate.Announcements') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('comp_docs_link_view')
                             <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'comp_docs_links.index' ? 'open' : '' }}"
                                     href="{{ route('comp_docs_links.index') }}">
                                     <i class="nav-icon i-Letter-Open"></i>
                                     <span class="item-name">{{ __('translate.Docs_&_Links') }}</span>
                                 </a>
                             </li>
                        @endcan
            
                    </ul>
                </li>
             @endif
            @endif

            @can('user_view')
               @if (auth()->user()->role_users_id != 1)
                <li class="">
                    <a class="link_btn {{ request()->is('users') ? 'active_link' : '' }}" href="/users">
                      <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Business-Mens"></i>
                        <span class="">{{ __('translate.User_Controller') }}</span>
                      </div>
                    </a>
                    
                </li>
               @endif
            @endcan

            @if (auth()->user()->can('employee_view') || auth()->user()->can('employee_add') )
                <li class="" data-item="employees">
                    <a class="link_btn {{ request()->is('employees') || request()->is('employees/*') ? 'active_link' : '' }}" href="#"
                        aria-expanded="true" data-toggle="collapse" data-target="#collapse_employees"
                        aria-expanded="true" aria-controls="collapse_employees"
                        >
                        <div class="d-flex align-items-center justify-content-start">  
                          <i class="nav-icon i-Engineering"></i>
                          <span class="nav-text">{{ __('translate.Employees') }}</span>
                          <i class="nav-icon i-Arrow-Right"></i>
                        </div>
                    </a>
                    <ul id="collapse_employees"
                        class="submenu list-unstyled collapse" 
                        aria-labelledby="headingOne" data-parent="#accordionExample" data-parent="employees"
                        >
                        @can('employee_add')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'employees.create' ? 'open' : '' }}"
                                    href="{{ route('employees.create') }}">
                                    <i class="nav-icon i-Add-User"></i>
                                    <span class="item-name">{{ __('translate.Create_Employee') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('employee_view')
                            <li class="child_links">
                                <a href="{{ route('employees.index') }}"
                                    class="{{ Route::currentRouteName() == 'employees.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Business-Mens"></i>
                                    <span class="item-name">{{ __('translate.Employee_List') }}</span>
                                </a>
                            </li>
                        @endcan
            
                    </ul>
                </li>

            @endif

            @can('project_view')
                <li class="">
                    <a class="link_btn {{ request()->is('projects') ? 'active_link' : '' }}" href="/projects">
                      <div class="d-flex align-items-center justify-content-start">  
                        <i class="nav-icon i-Dropbox"></i>
                        <span class="">{{ __('translate.Projects') }}</span>
                      </div>
                    </a>
                </li>
            @endcan

            @can('task_view')
                <li class=" ">
                    <a class="link_btn {{ request()->is('tasks') ? 'active_link' : '' }}" href="/tasks">
                        <div class="d-flex align-items-center justify-content-start">  
                        <i class="nav-icon i-Dropbox"></i>
                        <span class="nav-text">{{ __('translate.Team_Goal') }}</span></div>
                    </a>
                </li>
            @endcan

            @if( auth()->user()->role_users_id == 4 )
                <li class="" data-item="actions">
                    <a class="link_btn {{ request()->is('leave') ||  request()->is('accounting/expense')  || request()->is('tasks') ? 'active_link' : '' }}" href="/tasks"
                        aria-expanded="true" data-toggle="collapse" data-target="#collapse_tasks"
                        aria-expanded="true" aria-controls="collapse_tasks"
                        >
                        <div class="d-flex align-items-center justify-content-start">  
                        <i class="nav-icon i-Check"></i>
                        <span class="nav-text">{{ __('translate.Actions') }}</span>
                        <i class="nav-icon i-Arrow-Right"></i>
                        </div>
                    </a>
                    <ul id="collapse_tasks" class="submenu list-unstyled collapse" 
                        aria-labelledby="headingOne" data-parent="#accordionExample" data-parent="actions">
                        @can('leave_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'leave.index' ? 'open' : '' }}"
                                    href="{{ route('leave.index') }}">
                                    <i class="nav-icon i-Wallet"></i>
                                    <span class="item-name">{{ __('translate.Manage_leaves') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('expense_view')
                            <li class="child_links">
                                <a href="{{ route('expense.index') }}"
                                    class="{{ Route::currentRouteName() == 'expense.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Money-Bag"></i>
                                    <span class="item-name">{{ __('translate.Manage_Expenses') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif

            @can('client_view')
                <li class="">
                    <a class="link_btn {{ request()->is('clients') ? 'active_link' : '' }}" href="/clients">
                        <div class="d-flex align-items-center justify-content-start">  
                        <i class="nav-icon i-Boy"></i>
                        <span class="nav-text">{{ __('translate.Clients') }}</span></div>
                    </a>
                </li>
            @endcan

            @can('attendance_view')
                <li class=" "
                    data-item="attendances">
                    <a class="link_btn {{ request()->is('daily_attendance') || request()->is('attendances/*') ? 'active' : '' }}" href="#"
                        aria-expanded="true" data-toggle="collapse" data-target="#collapse_attendances"
                        aria-expanded="true" aria-controls="collapse_attendances"
                        >
                        <div class="d-flex align-items-center justify-content-start">   
                          <i class="nav-icon i-Clock"></i>
                          <span class="nav-text">{{ __('translate.Attendance') }}</span>
                          <i class="nav-icon i-Arrow-Right"></i>
                        </div>
                    </a>
                    <ul id="collapse_attendances" data-parent="attendances"
                        class="submenu list-unstyled collapse" 
                        aria-labelledby="headingOne" data-parent="#accordionExample"
                        >
                        @can('attendance_view')
                            <li class="nav-item">
                                <a class="{{ Route::currentRouteName() == 'daily_attendance' ? 'open' : '' }}"
                                    href="{{ route('daily_attendance') }}">
                                    <i class="nav-icon i-Clock"></i>
                                    <span class="item-name">{{ __('translate.Daily_Attendance') }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('attendances.index') }}"
                                    class="{{ Route::currentRouteName() == 'attendances.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Clock-4"></i>
                                    <span class="item-name">{{ __('translate.Attendances') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('Home_Office_View')
                        <li class="nav-item">
                            <a href="{{ route('work_from.index') }}"
                                class="{{ Route::currentRouteName() == 'work_from.index' ? 'open' : '' }}">
                                <i class="nav-icon i-Home-4"></i>
                                <span class="item-name">{{ __('translate.Home_Office') }}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
            @endcan
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