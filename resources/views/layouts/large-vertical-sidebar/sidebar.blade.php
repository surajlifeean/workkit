<?php $plugins = \Nwidart\Modules\Facades\Module::allEnabled(); ?>
<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <ul class="navigation-left accordion mt-3" id="accordionExample">
            {{--------------------------------------------Dashboard----------------------------------------------}}
            @if (auth()->user()->role_users_id == 1)
            {{-- @can('Dashboard_view') --}}
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/admin') ? 'active_link' : '' }}" href="/dashboard/admin">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="">{{ __('translate.Dashboard') }}</span> 
                        </div>                  
                    </a>
                </li>
            {{-- @endcan --}}
            @elseif(auth()->user()->role_users_id == 3)
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/client') ? 'active_link' : '' }}" href="/dashboard/client">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span>{{ __('translate.Dashboard') }}</span>
                        </div>
                    </a>
                </li>
            @elseif(auth()->user()->role_users_id == 2)
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/employee') ? 'active_link' : '' }}" href="/dashboard/employee">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span>{{ __('translate.Dashboard') }}</span>
                        </div>
                    </a>
                </li>
            @elseif(auth()->user()->role_users_id == 4)
                <li class="">
                    <a class="link_btn {{ request()->is('dashboard/hr') ? 'active_link' : '' }}" href="/dashboard/hr">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span>{{ __('translate.Dashboard') }}</span>
                        </div>
                    </a>
                </li>
            @endif
            {{----------------------------------------- Notification ----------------------------------------------}}
            @if (auth()->user()->role_users_id == 1)
                <li class="">
                    <a class="link_btn {{ request()->is('get_messages/*') ? 'active_link' : '' }}" href="/get_messages/0">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bell"></i>
                        <span>{{ __('translate.Notifications') }}</span>
                        </div>
                    </a>
                </li>
            @endif
            {{---------------------------------------- My requests ------------------------------------------------}}
            @if (auth()->user()->role_users_id == 2 || auth()->user()->role_users_id == 4)
                <li class="">
                    <a class="link_btn {{ request()->is('employee/my_requests') ? 'active_link' : '' }}" href="/employee/my_requests">
                        <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span>{{ __('translate.My_Request') }}</span>
                        </div>
                    </a>
                </li>
            @endif
            {{--------------------------------------------------  client ------------------------------------------------}}
            @if (auth()->user()->role_users_id == 3)
                 <li class="">
                     <a class="link_btn {{ request()->is('client_projects') ? 'active_link' : '' }}" href="/client_projects">
                         <i class="nav-icon i-Dropbox"></i>
                         <span>{{ __('translate.Projects') }}</span>
                     </a>
                 </li>
     
                 <li class="">
                     <a class="link_btn {{ request()->is('client_tasks') ? 'active_link' : '' }}" href="/client_tasks">
                         <i class="nav-icon i-Check"></i>
                         <span>{{ __('translate.Tasks') }}</span>
                     </a>
                 </li>
            @endif
            {{----------------------------------------------------------- core -----------------------------------------}}
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

                        {{-- @can('user_view')
                           <li class="child_links">
                               <a class="" href="/users">
                                   <i class="nav-icon i-Business-Mens"></i>
                                   <span class="item-name">{{ __('translate.User_Controller') }}</span>
                               </a>
                               
                           </li>
                        @endcan --}}
     
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
            {{------------------------------------------------ Department -------------------------------------------}}
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
            {{--------------------------------------------- Users -------------------------------------------------}}
            @can('user_view')
               {{-- @if (auth()->user()->role_users_id != 1) --}}
                <li class="">
                    <a class="link_btn {{ request()->is('users') ? 'active_link' : '' }}" href="/users">
                      <div class="d-flex align-items-center justify-content-start">
                        <i class="nav-icon i-Business-Mens"></i>
                        <span class="">{{ __('translate.User_Controller') }}</span>
                      </div>
                    </a>
                    
                </li>
               {{-- @endif --}}
            @endcan
            {{---------------------------------------------- Employees -------------------------------------------}}
            @if (auth()->user()->can('employee_view') || auth()->user()->can('employee_add') )
                <li class="" data-item="employees">
                    <a class="link_btn {{ request()->is('employees') || request()->is('employees/*') ? 'active_link' : '' }}" href="#"
                        aria-expanded="true" data-toggle="collapse" data-target="#collapse_employees"
                        aria-expanded="true" aria-controls="collapse_employees"
                        >
                        <div class="d-flex align-items-center justify-content-start">  
                          <i class="nav-icon i-Engineering"></i>
                          <span>{{ __('translate.Employees') }}</span>
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
            {{------------------------------------------ Projects -------------------------------------------------}}
            @can('project_view')
                <li class="">
                    <a class="link_btn {{ request()->is('projects') ? 'active_link' : '' }}" href="/projects">
                      <div class="d-flex align-items-center justify-content-start">  
                        <i class="nav-icon i-Box-Open"></i>
                        <span class="">{{ __('translate.Projects') }}</span>
                      </div>
                    </a>
                </li>
            @endcan
            {{------------------------------------------- Team Goal ------------------------------------------------}}
            @can('task_view')
                <li class=" ">
                    <a class="link_btn {{ request()->is('tasks') ? 'active_link' : '' }}" href="/tasks">
                        <div class="d-flex align-items-center justify-content-start">  
                        <i class="nav-icon i-Dropbox"></i>
                        <span>{{ __('translate.Team_Goal') }}</span></div>
                    </a>
                </li>
            @endcan
            {{---------------------------------------------- Actions ----------------------------------------------}}
            @if( auth()->user()->role_users_id == 4 )
                <li class="" data-item="actions">
                    <a class="link_btn {{ request()->is('leave') ||  request()->is('accounting/expense')  || request()->is('tasks') ? 'active_link' : '' }}" href="/tasks"
                        aria-expanded="true" data-toggle="collapse" data-target="#collapse_tasks"
                        aria-expanded="true" aria-controls="collapse_tasks"
                        >
                        <div class="d-flex align-items-center justify-content-start">  
                        <i class="nav-icon i-Check"></i>
                        <span>{{ __('translate.Actions') }}</span>
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
                        <span>{{ __('translate.Clients') }}</span></div>
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
                          <span>{{ __('translate.Attendance') }}</span>
                          <i class="nav-icon i-Arrow-Right"></i>
                        </div>
                    </a>
                    <ul id="collapse_attendances" 
                        class="submenu list-unstyled collapse" 
                        aria-labelledby="headingOne" data-parent="#accordionExample"
                        >
                        @can('attendance_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'daily_attendance' ? 'open' : '' }}"
                                    href="{{ route('daily_attendance') }}">
                                    <i class="nav-icon i-Clock"></i>
                                    <span class="item-name">{{ __('translate.Daily_Attendance') }}</span>
                                </a>
                            </li>
                            <li class="child_links">
                                <a href="{{ route('attendances.index') }}"
                                    class="{{ Route::currentRouteName() == 'attendances.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Clock-4"></i>
                                    <span class="item-name">{{ __('translate.Attendances') }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('Home_Office_View')
                        <li class="child_links">
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
            {{---------------------------------------------Accounting-----------------------------------------}}
            @if(auth()->user()->role_users_id != 4)
            @if (auth()->user()->can('account_view') ||auth()->user()->can('deposit_view') ||auth()->user()->can('expense_view') ||auth()->user()->can('expense_category') ||auth()->user()->can('deposit_category') ||auth()->user()->can('payment_method'))
                <li class="" data-item="accounting">
                    <a class="link_btn {{ request()->is('accounting') || request()->is('accounting/*') ? 'active_link' : '' }}" href="#"
                    aria-expanded="true" data-toggle="collapse" data-target="#collapse_accounting"
                    aria-expanded="true" aria-controls="collapse_accounting"
                    >
                    <div class="d-flex align-items-center justify-content-start">   
                         <i class="nav-icon i-Pen-6"></i>
                         <span class="nav-text">{{ __('translate.Accounting') }}</span>
                         <i class="nav-icon i-Arrow-Right"></i>
                    </div>
                    </a>
                    <ul id="collapse_accounting" 
                        class="submenu list-unstyled collapse" 
                        aria-labelledby="headingOne" data-parent="#accordionExample">

                        @can('account_view')
                            <li class="child_links">
                                <a href="{{ route('account.index') }}"
                                    class="{{ Route::currentRouteName() == 'account.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Financial"></i>
                                    <span class="item-name">{{ __('translate.Account') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('deposit_view')
                            <li class="child_links">
                                <a href="{{ route('deposit.index') }}"
                                    class="{{ Route::currentRouteName() == 'deposit.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Money-2"></i>
                                    <span class="item-name">{{ __('translate.Deposit') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('expense_view')
                            <li class="child_links">
                                <a href="{{ route('expense.index') }}"
                                    class="{{ Route::currentRouteName() == 'expense.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Money-Bag"></i>
                                    <span class="item-name">{{ __('translate.Expense') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @if (auth()->user()->can('expense_category') || auth()->user()->can('deposit_category') || auth()->user()->can('payment_method'))
                            <li class="child_links" id="accordionExample3">
                                <a class=""
                                aria-expanded="true" data-toggle="collapse" data-target="#collapse_accounting_settings"
                                aria-controls="collapse_accounting_settings"
                                >
                                    <i class="nav-icon i-Gear"></i>
                                    <span class="item-name">{{ __('translate.Account_Settings') }}</span>
                                    <i class="dd-arrow i-Arrow-Down"></i>
                                </a>
                                <ul id="collapse_accounting_settings" 
                                class="collapse submenu list-unstyled" 
                                aria-labelledby="headingOne" data-parent="#accordionExample3"
                                >
                                    @can('expense_category')
                                        <li class="child_links"><a class="{{ Route::currentRouteName() == 'expense_category.index' ? 'open' : '' }}"
                                                href="{{ route('expense_category.index') }}">{{ __('translate.Expense_Category') }}</a>
                                        </li>
                                    @endcan
            
                                    @can('deposit_category')
                                        <li class="child_links"><a class="{{ Route::currentRouteName() == 'deposit_category.index' ? 'open' : '' }}"
                                                href="{{ route('deposit_category.index') }}">{{ __('translate.Deposit_Category') }}</a>
                                        </li>
                                    @endcan
                                    @can('payment_method')
                                        <li class="child_links"><a class="{{ Route::currentRouteName() == 'payment_methods.index' ? 'open' : '' }}"
                                                href="{{ route('payment_methods.index') }}">{{ __('translate.Payment_Methods') }}</a>
                                        </li>
                                    @endcan
                                </ul>
                            </li>
                        @endif
                    </ul>       
                </li>
            @endif
            @endif 
            {{------------------------------------------------leave------------------------------------------------}}
            @if(auth()->user()->role_users_id != 4)
                @if (auth()->user()->can('leave_view') ||auth()->user()->can('leave_type'))
                    <li class="" data-item="leave">
                        <a class="link_btn {{ request()->is('leave') || request()->is('leave_type') ? 'active_link' : '' }}" href="#"
                          aria-expanded="true" data-toggle="collapse" data-target="#collapse_leave"
                          aria-controls="collapse_leave"
                          >
                          <div class="d-flex align-items-center justify-content-start">   
                            <i class="nav-icon i-Calendar"></i>
                            <span class="nav-text">{{ __('translate.Leave_Request') }}</span>
                            <i class="nav-icon i-Arrow-Right"></i>
                          </div>
                        </a>
                            <ul id="collapse_leave" class="submenu list-unstyled collapse" 
                                aria-labelledby="headingOne" data-parent="#accordionExample"
                               >
                                @can('leave_view')
                                    <li class="child_links">
                                        <a class="{{ Route::currentRouteName() == 'leave.index' ? 'open' : '' }}"
                                            href="{{ route('leave.index') }}">
                                            <i class="nav-icon i-Wallet"></i>
                                            <span class="item-name">{{ __('translate.Request_leave') }}</span>
                                        </a>
                                    </li>
                                @endcan
                    
                                @can('leave_type')
                                    <li class="child_links">
                                        <a class="{{ Route::currentRouteName() == 'leave_type.index' ? 'open' : '' }}"
                                            href="{{ route('leave_type.index') }}">
                                            <i class="nav-icon i-Wallet"></i>
                                            <span class="item-name">{{ __('translate.Leave_Type') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>

                            
                    </li>
                @endif
            @endif
            <!-- ---------------------------------------------------Tranings--------------------------------------------------- -->

            @if (auth()->user()->can('training_view') || auth()->user()->can('trainer') || auth()->user()->can('training_skills'))
                <li class=" " data-item="training">
                    <a class="link_btn {{ request()->is('trainings') || request()->is('trainers') || request()->is('training_skills') ? 'active_link' : '' }}" href="#"
                        aria-expanded="true" data-toggle="collapse" data-target="#collapse_training"
                          aria-controls="collapse_training"
                    >
                    <div class="d-flex align-items-center justify-content-start">   
                        <i class="nav-icon i-Windows-Microsoft"></i>
                        <span class="nav-text">{{ __('translate.Training') }}</span>
                        <i class="nav-icon i-Arrow-Right"></i>
                    </div>
                    </a>
                    <ul class="submenu list-unstyled collapse" id="collapse_training"
                    aria-labelledby="headingOne" data-parent="#accordionExample"
                    >
                        @can('training_view')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'trainings.index' ? 'open' : '' }}"
                                    href="{{ route('trainings.index') }}">
                                    <i class="nav-icon i-Windows-Microsoft"></i>
                                    <span class="item-name">{{ __('translate.Training_List') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('trainer')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'trainers.index' ? 'open' : '' }}"
                                    href="{{ route('trainers.index') }}">
                                    <i class="nav-icon i-Business-Mens"></i>
                                    <span class="item-name">{{ __('translate.Trainers') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('training_skills')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'training_skills.index' ? 'open' : '' }}"
                                    href="{{ route('training_skills.index') }}">
                                    <i class="nav-icon i-Wallet"></i>
                                    <span class="item-name">{{ __('translate.Training_Skills') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
            <!--  -->
            @foreach ($plugins as $item)
                @if (View::exists(strtolower($item) . '::layouts.large-vertical-sidebar.sidebar'))
                    @include(strtolower($item) . '::layouts.large-vertical-sidebar.sidebar')
                @endif
            @endforeach
            <!------------------------------------------------------ Settings ------------------------------------------------------>
            @if (auth()->user()->can('settings') || auth()->user()->can('group_permission') || auth()->user()->can('currency') || auth()->user()->can('backup') || auth()->user()->can('module_settings'))
                <li class="" data-item="settings">
                    <a class="link_btn {{ request()->is('settings/*') ? 'active_link' : '' }}" href="#"
                    aria-expanded="true" data-toggle="collapse" data-target="#collapse_settings"
                    aria-controls="collapse_settings"
                    >
                    <div class="d-flex align-items-center justify-content-start">   
                        <i class="nav-icon i-Security-Settings"></i>
                        <span class="nav-text">{{ __('translate.Settings') }}</span>
                        <i class="nav-icon i-Arrow-Right"></i>
                    </div>
                    </a>

                    <ul class="submenu list-unstyled collapse" id="collapse_settings"
                    aria-labelledby="headingOne" data-parent="#accordionExample"
                    >
                        @can('settings')
                       
            
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'update_settings.index' ? 'open' : '' }}"
                                    href="{{ route('update_settings.index') }}">
                                    <i class="nav-icon i-Data"></i>
                                    <span class="item-name">{{ __('translate.Update_Log') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        {{-- @can('module_settings')
                        <li class="child_links ">
                            <a class="{{ Route::currentRouteName()=='module_settings.index' ? 'open' : '' }}"
                        href="{{route('module_settings.index')}}">
                        <i class="nav-icon i-Clock-3"></i>
                        <span class="item-name">{{ __('translate.Module_settings') }}</span>
                        </a>
                        </li>
                        @endcan --}}
            
                        @can('group_permission')
                            <li class="child_links">
                                <a href="{{ route('permissions.index') }}"
                                    class="{{ Route::currentRouteName() == 'permissions.index' ? 'open' : '' }}">
                                    <i class="nav-icon i-Lock-2"></i>
                                    <span class="item-name">{{ __('translate.Permissions') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('currency')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'currency.index' ? 'open' : '' }}"
                                    href="{{ route('currency.index') }}">
                                    <i class="nav-icon i-Dollar-Sign"></i>
                                    <span class="item-name">{{ __('translate.Currency') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('backup')
                            <li class="child_links">
                                <a class="{{ Route::currentRouteName() == 'backup.index' ? 'open' : '' }}"
                                    href="{{ route('backup.index') }}">
                                    <i class="nav-icon i-Download"></i>
                                    <span class="item-name">{{ __('translate.Backup') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>

                </li>
            @endif
            {{------------------------------------------------------System setup-----------------------------------------}}
            @if (auth()->user()->role_users_id == 1)
            <li class="">
                <a class="link_btn {{ request()->is('business_settings') || request()->is('system_settings') ? 'active_link' : '' }}" href="/business_settings"
                    aria-expanded="true" data-toggle="collapse" data-target="#collapse_system_setup"
                    aria-controls="collapse_system_setup">
                    <div class="d-flex align-items-center justify-content-start">  
                        <i class="nav-icon i-Data-Settings"></i>
                        <span>{{ __('translate.System_Setup') }}</span>
                        <i class="nav-icon i-Arrow-Right"></i>
                    </div>
                </a>
                <ul id="collapse_system_setup" class="submenu list-unstyled collapse"
                    aria-labelledby="headingOne" data-parent="#accordionExample">
                    <li class="child_links">
                        <a class="{{ Route::currentRouteName() == 'business_settings.index' ? 'open' : '' }}"
                            href="{{ route('business_settings') }}">
                            <i class="nav-icon i-Data"></i>
                            <span class="item-name">{{ __('translate.Business_settings') }}</span>
                        </a>
                    </li>
                    <li class="child_links ">
                        <a class="{{ Route::currentRouteName() == 'system_settings.index' ? 'open' : '' }}"
                            href="{{ route('system_settings.index') }}">
                            <i class="nav-icon i-Gear"></i>
                            <span class="item-name">{{ __('translate.System_Settings') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            {{------------------------------------------------------Subscription-------------------------------------}}
            @if (auth()->user()->role_users_id == 1)
                <li class="">
                    <a class="link_btn {{ request()->is('subscription') ? 'active_link' : '' }}" href="/subscription">
                        <div class="d-flex align-items-center justify-content-start">  
                            <i class="nav-icon i-Money-Bag"></i>
                            <span>{{ __('translate.Subscription') }}</span>
                        </div>
                    </a>
                </li>
            @endif
            <!-- -----------------------------------------------------Reports----------------------------------------------------- -->
            @if (auth()->user()->can('attendance_report') || auth()->user()->can('employee_report') || auth()->user()->can('project_report') || auth()->user()->can('task_report') || auth()->user()->can('expense_report') || auth()->user()->can('deposit_report'))
                <li class="" data-item="report">
                    <a class="link_btn {{ request()->is('report/*') ? 'active' : '' }}" href="#"
                    aria-expanded="true" data-toggle="collapse" data-target="#collapse_report"
                          aria-controls="collapse_report"
                    >
                    <div class="d-flex align-items-center justify-content-start"> 
                        <i class="nav-icon i-Line-Chart"></i>
                        <span class="nav-text">{{ __('translate.Reports') }}</span>
                        <i class="nav-icon i-Arrow-Right"></i>
                    </div>
                    </a>
                    <ul 
                      class="submenu list-unstyled collapse" id="collapse_report"
                      aria-labelledby="headingOne" data-parent="#accordionExample"
                    >
                        @can('attendance_report')
                            <li class="child_links ">
                                <a class="{{ Route::currentRouteName() == 'attendance_report_index' ? 'open' : '' }}"
                                    href="{{ route('attendance_report_index') }}">
                                    <i class="nav-icon i-Wallet"></i>
                                    <span class="item-name">{{ __('translate.Attendance_Report') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('employee_report')
                            <li class="child_links ">
                                <a class="{{ Route::currentRouteName() == 'employee_report_index' ? 'open' : '' }}"
                                    href="{{ route('employee_report_index') }}">
                                    <i class="nav-icon i-Wallet"></i>
                                    <span class="item-name">{{ __('translate.Employee_Report') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('project_report')
                            <li class="child_links ">
                                <a class="{{ Route::currentRouteName() == 'project_report_index' ? 'open' : '' }}"
                                    href="{{ route('project_report_index') }}">
                                    <i class="nav-icon i-Wallet"></i>
                                    <span class="item-name">{{ __('translate.Project_Report') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('task_report')
                            <li class="child_links ">
                                <a class="{{ Route::currentRouteName() == 'task_report_index' ? 'open' : '' }}"
                                    href="{{ route('task_report_index') }}">
                                    <i class="nav-icon i-Wallet"></i>
                                    <span class="item-name">{{ __('translate.Task_Report') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('expense_report')
                            <li class="child_links ">
                                <a class="{{ Route::currentRouteName() == 'expense_report_index' ? 'open' : '' }}"
                                    href="{{ route('expense_report_index') }}">
                                    <i class="nav-icon i-Wallet"></i>
                                    <span class="item-name">{{ __('translate.Expense_Report') }}</span>
                                </a>
                            </li>
                        @endcan
            
                        @can('deposit_report')
                            <li class="child_links mb-5">
                                <a class="{{ Route::currentRouteName() == 'deposit_report_index' ? 'open' : '' }}"
                                    href="{{ route('deposit_report_index') }}">
                                    <i class="nav-icon i-Wallet"></i>
                                    <span class="item-name">{{ __('translate.Deposit_Report') }}</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</div>

<script>
   const link_btns = document.querySelectorAll('.link_btn');

    link_btns.forEach(link_btn => {
        link_btn.addEventListener('click', (e) => {
            link_btns.forEach(other_btn => {
                if (other_btn !== link_btn) {
                    const right_arrow = other_btn.querySelector('div i:last-child');
    
                    if (right_arrow) {
                        other_btn.classList.remove('link_btn_colapsed');
                        right_arrow.classList.remove('i_toggle_position');
                    }
                }
            });
    
            const right_arrow = link_btn.querySelector('div i:last-child');
            right_arrow.classList.toggle('i_toggle_position');
    
            var targetSelector = e.currentTarget.getAttribute('data-target');
            var targetElement = document.querySelector(targetSelector);
    
            if (targetElement && targetElement.classList.contains('collapse') && targetElement.classList.contains('show')) {
                console.log('The collapse is currently shown.');
                link_btn.classList.remove('link_btn_colapsed');
            } else {
                console.log('The collapse is not shown.');
                link_btn.classList.toggle('link_btn_colapsed');
            }
        });
    });


</script>