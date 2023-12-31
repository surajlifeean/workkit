<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.employee')
@section('main-content')

<div class="breadcrumb">
    <h1>{{ __('translate.Dashboard') }}</h1>

</div>
<div class="breadcrumb">
     <div class="d-flex align-items-center w-100">
     <img src="{{asset('assets/images/avatar/'.Auth::user()->avatar)}}" alt="employee avatar" class=" mr-4" style="border-radius: 10.5rem; width: 120px; height: 120px;" onerror="this.src='{{ asset('assets/images/avatar/no_avatar.jpeg') }}'">
       <h1 class="text-secondary">{{ __('translate.Hi')}} {{ Auth::user()->username }}</h1>
       {{-- @dump(auth()->user());  --}}
     </div>
</div>

<div class="separator-breadcrumb border-top"></div>

<div id="section_Dashboard_employee">
    <div class="row">
                <div class="col-lg-8 col-md-12 col-sm-12">
                     <div class="card o-hidden mb-4">
                        <div class="card-header  border-0">
                            @if(!$punch_in)
                            <span class="float-left card-title m-0">{{ __('translate.No_Shift_Today') }}</span>
                            @else
                            <span class="clock_in float-left card-title m-0">{{str_replace(['AM', 'PM', 'am', 'pm'], '', $punch_in)}} - {{str_replace(['AM', 'PM', 'am', 'pm'], '', $punch_out)}}

                            @isset($employee_attendance->clock_in_out)
                                @if($employee_attendance->clock_in_out != 0)
                                ({{ __('translate.Punch_In') }}: {{ date('H:i', strtotime($employee_attendance->clock_in) + (new DateTimeZone($setting->timezone))->getOffset(new DateTime()) ) }})
                                {{-- @else
                                 ({{ __('translate.Punch_Out') }}: {{ date('h:i A', strtotime($employee_attendance->clock_out)) }}) --}}
                                @endif
                            @endisset
                            </span>
                            @endif
                           
                            
                            </span>
                            <form method="post" action="{{route('attendance_by_employee.post',$employee->id)}}"
                                accept-charset="utf-8">
                                @csrf
                                <input type="hidden" value="{{$punch_in}}" id="punch_in" name="office_punch_in">
                                <input type="hidden" value="{{$punch_out}}" id="punch_out" name="office_punch_out">
                                <input type="hidden" value="" id="in_out" name="in_out_value">
                                @if(!$employee_attendance || $employee_attendance->clock_in_out == 0)
                                <button type="submit"
                                    class="btn btn-{{$setting->theme_color}} btn-rounded btn-md m-1 text-right float-right"><i
                                        class="i-Arrow-UpinCircle text-white mr-2"></i>
                                    {{ __('translate.Punch_In') }}</button>
                                @else
                                <button type="submit"
                                    class="btn btn-danger btn-rounded btn-md m-1 text-right float-right"><i
                                        class="i-Arrow-DowninCircle text-white mr-2"></i>
                                    {{ __('translate.Punch_Out') }}</button>
                                @endif
                            </form>
                        </div>

                    </div>
                </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
            <!-- ICON BG -->
           
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                   
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title">{{ __('translate.Leave_taken_vs_remaining') }}</div>
                            <div id="echart_leave"></div>
                        </div>
                    </div>
                </div>
               {{-- <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Dropbox"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">{{ __('translate.Projects') }}</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{$count_projects}}</p>
                            </div>
                        </div>
                    </div>
                </div> --}}

               

               {{-- <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Gift-Box"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">{{ __('translate.Awards') }}</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{$count_awards}}</p>
                            </div>
                        </div>
                    </div>
                </div> --}}

                
            </div>
        </div>

        <div class="col-lg-6 col-sm-12">
            <div class="card" style="height: 93%;">
              <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <h4 class="h4">{{__('translate.Absent')}}</h4>
                        <div class="d-flex mx-1 py-3" style="overflow-x: scroll; display: flex;">
                        @foreach($not_clock_in as $notpresent)
                            <div class="mr-4" style="border-radius: 100%; overflow: hidden; min-height: 49px; min-width: 49px; max-width: 50px; max-height: 50px;">
                                <img src="{{ asset('assets/images/avatar/'. $notpresent['avatar'] ) }}" style="height: inherit; width: inherit;" alt="avatar" title="{{ $notpresent['username'] }}" onerror="this.src='{{ asset('assets/images/avatar/no_avatar.jpeg') }}'">
                            </div>
                        @endforeach                        
                       </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-12">
                         <h4 class="h4">{{ __('translate.Work from home')}}</h4>
                         <div class="d-flex mx-1 py-3" style="overflow-x: scroll; display: flex;">
                            @foreach($work_from_home as $wfh)
                                <div class="mr-4" style="border-radius: 100%; overflow: hidden; min-height: 49px;min-width: 49px;max-width: 50px;max-height: 50px;">
                                    <img src="{{ asset('assets/images/avatar/'. $wfh->avatar ) }}" style="height: inherit; width: inherit;" alt="avatar" title="{{ $wfh->username }}" onerror="this.src='{{ asset('assets/images/avatar/no_avatar.jpeg') }}'">
                                </div>
                            @endforeach
                        </div>
                        
                     </div>
                </div>
              </div>
            </div>
        </div>


    </div>

    <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-6">
                    <a href="/employee/my_requests" class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Check"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">{{ __('translate.Tasks') }}</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{$count_tasks}}</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <a href="/core/announcements" class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                        <div class="card-body text-center">
                            <i class="i-Letter-Open"></i>
                            <div class="content">
                                <p class="text-muted mt-2 mb-0">{{ __('translate.Announcements') }}</p>
                                <p class="text-primary text-24 line-height-1 mb-2">{{$count_announcement}}</p>
                            </div>
                        </div>
                    </a>
                </div>

    </div>


    {{-- <div class="row">

        <div class="col-lg-6 col-sm-12">
            <div class="card o-hidden mb-4">
                <div class="card-header d-flex align-items-center border-0">
                    <h3 class="float-left card-title m-0">{{ __('translate.Latest_Assigned_Projects') }}</h3>
                </div>

                <div class="">
                    <div class="table-responsive">
                        <table id="user_table" class="table text-center">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('translate.Project') }}</th>
                                    <th scope="col">{{ __('translate.Client') }}</th>
                                    <th scope="col">{{ __('translate.Status') }}</th>
                                    <th scope="col">{{ __('translate.Progress') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latest_projects as $latest_project)
                                <tr>
                                    <td>{{$latest_project->title}}</td>
                                    <td>{{$latest_project->client->username}}</td>
                                    <td>
                                        @if($latest_project->status == 'completed')
                                        <span class="badge badge-success m-2">{{ __('translate.Completed') }}</span>
                                        @elseif($latest_project->status == 'not_started')
                                        <span class="badge badge-warning m-2">{{ __('translate.Not_Started') }}</span>
                                        @elseif($latest_project->status == 'progress')
                                        <span class="badge badge-primary m-2">{{ __('translate.In_Progress') }}</span>
                                        @elseif($latest_project->status == 'cancelled')
                                        <span class="badge badge-danger m-2">{{ __('translate.Cancelled') }}</span>
                                        @elseif($latest_project->status == 'hold')
                                        <span class="badge badge-secondary m-2">{{ __('translate.On_Hold') }}</span>
                                        @endif
                                    </td>
                                    <td>{{$latest_project->project_progress}} %</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-12">
            <div class="card o-hidden mb-4">
                <div class="card-header d-flex align-items-center border-0">
                    <h3 class="float-left card-title m-0">{{ __('translate.Latest_Tasks') }}</h3>
                </div>

                <div class="">
                    <div class="table-responsive">
                        <table id="user_table" class="table  text-center">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('translate.Task') }}</th>
                                    <th scope="col">{{ __('translate.Project') }}</th>
                                    <th scope="col">{{ __('translate.Status') }}</th>
                                    <th scope="col">{{ __('translate.Progress') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latest_tasks as $latest_task)
                                <tr>
                                    <td>{{$latest_task->title}}</td>
                                    <td>{{$latest_task->project->title}}</td>
                                    <td>
                                        @if($latest_task->status == 'completed')
                                        <span class="badge badge-success m-2">{{ __('translate.Completed') }}</span>
                                        @elseif($latest_task->status == 'not_started')
                                        <span class="badge badge-warning m-2">{{ __('translate.Not_Started') }}</span>
                                        @elseif($latest_task->status == 'progress')
                                        <span class="badge badge-primary m-2">{{ __('translate.In_Progress') }}</span>
                                        @elseif($latest_task->status == 'cancelled')
                                        <span class="badge badge-danger m-2">{{ __('translate.Cancelled') }}</span>
                                        @elseif($latest_task->status == 'hold')
                                        <span class="badge badge-secondary m-2">{{ __('translate.On_Hold') }}</span>
                                        @endif
                                    </td>
                                    <td>{{$latest_task->task_progress}} %</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div> --}}

</div>
@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
<script src="{{asset('assets/js/echart.options.min.js')}}"></script>

<script>
    let echartElemleave = document.getElementById('echart_leave');
        if (echartElemleave) {
            let echart_leave = echarts.init(echartElemleave);
            echart_leave.setOption({
                ...echartOptions.defaultOptions,
                ... {
                    legend: {
                        show: true,
                        bottom: 0,
                    },
                    series: [{
                        type: 'pie',
                        ...echartOptions.pieRing,
        
                        label: echartOptions.pieLabelCenterHover,
                        data: [{
                            name: "{{ __('translate.Taken') }}",
                            value: @json($total_leave_taken),
                            itemStyle: {
                                color: '#663399',
                            }
                        }, {
                            name: "{{ __('translate.Remaining') }}",
                            value: @json($total_leave_remaining),
                            itemStyle: {
                                color: '#ced4da',
                            }
                        }]
                    }]
                }
            });
            $(window).on('resize', function() {
                setTimeout(() => {
                    echart_leave.resize();
                }, 500);
            });
        }
        
        
</script>

@endsection