<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
@endsection
<div class="breadcrumb">
    <div class="d-flex align-items-center w-100">
    <img src="{{asset('assets/images/avatar/'.Auth::user()->avatar)}}" alt="employee avatar" class="h-100px w-100px mr-4" style="border-radius: 6.5rem; width: 100px;" onerror="this.src='{{ asset('assets/images/avatar/no_avatar.jpeg') }}'">
      <h1 class="">{{ __('translate.Hi')}} {{ Auth::user()->username }}</h1>
      {{-- @dump(auth()->user());  --}}
    </div>
</div>
<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-12">
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
<div class="row" id="section_Dashboard">
    <!-- ICON BG -->
    <div class="col-lg-3 col-md-6 col-sm-6">
        <a href="/employees">
            <div class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Engineering"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">{{ __('translate.Employees') }}</p>
                        <p class="text-{{  $setting->theme_color  }} text-24 line-height-1 mb-2">{{ $count_employee }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <a href="/employee/my_requests">
            <div class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Boy"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">{{ __('translate.My_Request') }}</p>
                        {{-- <p class="text-{{  $setting->theme_color  }} text-24 line-height-1 mb-2">{{ $count_clients }}</p> --}}
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <a href="/tasks">
            <div class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                <div class="card-body text-center">    
                    <i class="i-Dropbox"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">{{ __('translate.Tasks') }}</p>
                        <p class="text-{{  $setting->theme_color  }} text-24 line-height-1 mb-2">{{ $count_task }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6">
        <a href="/leave">
            <div class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Check-2"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">{{ __('translate.Actions') }}</p>
                        <p class="text-{{  $setting->theme_color  }} text-24 line-height-1 mb-2">{{ $count_project }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

   

</div>

<div class="row">
    @php
    // $permissions = DB::table('permissions')->get();
    @endphp
    {{-- @dump($permissions) --}}
    <div class="col-lg-8 col-md-12">
        <div class="card mb-5" style="height: 95%">
            <div class="card-body">
                <div class="row mb-3 ">
                    <div class="col-12">
                        <h4 class="h4">{{ __('translate.Absent')}}</h4>
                        <div class="d-flex mx-1 p-4" style="overflow-x: scroll; display: flex;">
                            @foreach($not_clock_in as $notpresent)
                            <div class="mr-4" style="border-radius: 100%; overflow: hidden; min-height: 49px; min-width: 49px; max-width: 50px; max-height: 50px;">
                                <img src="{{ asset('assets/images/avatar/'. $notpresent['avatar'] ) }}" style="height: 49px; width: 49px;" alt="avatar" title="{{ $notpresent['username'] }}" onerror="this.src='{{ asset('assets/images/avatar/no_avatar.jpeg') }}'">
                            </div>
                            @endforeach                        
                       </div>
                    </div>
                </div>
                <div class="row">
                     <div class="col-12">
                         <h4 class="h4">{{ __('translate.Work from home') }}</h4>
                         <div class="d-flex mx-1 p-4" style="overflow-x: scroll; display: flex;">
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

    {{-- <div class="col-lg-4 col-sm-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.Projects_by_Status') }}</div>
                <div id="echartProject"></div>
            </div>
        </div>
    </div> --}}
    <div class="col-lg-4 col-sm-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.Tasks') }}</div>
                <div id="echartTask"></div>
            </div>
        </div>
    </div>



</div>

<div class="row">

    <div class="col-12">
        <div class="card o-hidden mb-4">
            <div class="card-header d-flex align-items-center border-0">
                <h3 class="w-50 float-left card-title m-0">{{ __('translate.Latest_Employees') }}</h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="ul-contact-list" class="table text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th scope="col">{{ __('translate.Firstname') }}</th>
                                <th scope="col">{{ __('translate.Lastname') }}</th>
                                <th scope="col">{{ __('translate.Phone') }}</th>
                                <th scope="col">{{ __('translate.Company') }}</th>
                                <th scope="col">{{ __('translate.Department') }}</th>
                                <th scope="col">{{ __('translate.Designation') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($latest_employees as $latest_employee)
                                <tr>
                                    <td></td>
                                    <td>{{ $latest_employee->firstname }}</td>
                                    <td>{{ $latest_employee->lastname }}</td>
                                    <td>{{ $latest_employee->phone }}</td>
                                    <td>{{ $latest_employee->company->name }}</td>
                                    <td>{{ $latest_employee->department->department }}</td>
                                    <td>{{ $latest_employee->designation->designation }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="col-lg-4 col-sm-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.Tasks_by_Status') }}</div>
                <div id="echartTask"></div>
            </div>
        </div>
    </div> --}}


</div>

<div class="row">

    {{-- <div class="col-lg-8 col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.Employee_count_by_department') }}</div>
                <div id="echartBar"></div>
            </div>
        </div>
    </div> --}}


    {{-- <div class="col-lg-4 col-sm-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.Expense_vs_Deposit') }}</div>
                <div id="echartPie"></div>
            </div>
        </div>
    </div> --}}

    
</div>

@endsection

@section('page-js')
<script src="{{ asset('assets/js/vendor/echarts.min.js') }}"></script>
<script src="{{ asset('assets/js/echart.options.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables.script.js') }}"></script>
<script>
    // Chart Employee count by department
    let echartElemBar = document.getElementById('echartBar');
    if (echartElemBar) {
        let echartBar = echarts.init(echartElemBar);
        var dark_heading = "#c2c6dc";
        echartBar.setOption({

            formatter: function(params) {
                return `${params.value} employee`;
            },
            grid: {
                left: '8px',
                right: '8px',
                bottom: '0',
                containLabel: true
            },

            tooltip: {
                show: true,
                backgroundColor: 'rgba(0, 0, 0, .8)'
            },
            xAxis: [{
                type: 'category',
                data: @json($department_name),
                axisTick: {
                    alignWithLabel: true
                },
                axisLabel: {
                    color: dark_heading,
                    interval: 0,
                    rotate: 30
                },
                axisLine: {
                    show: true,
                    color: dark_heading,

                    lineStyle: {
                        color: dark_heading,
                    }
                },

                splitLine: {
                    show: false
                },

            }],

            yAxis: [{
                type: 'value',
                axisLabel: {
                    // formatter: '{value}

                },
                min: 0,
                // max: 100,
                interval: 5,
                axisLine: {
                    show: false
                },
                splitLine: {
                    show: true,
                    interval: 'auto'
                }
            }],
            series: [{
                    name: 'Department',
                    data: @json($total_employee),
                    label: {
                        show: false,
                        color: '#639',
                        fontSize: 9,
                    },
                    type: 'bar',
                    color: '#7569b3',
                    barGap: 0,
                    smooth: true,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowOffsetY: -2,
                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                        }
                    }
                },

            ]
        });
        $(window).on('resize', function() {
            setTimeout(() => {
                echartBar.resize();
            }, 500);
        });
    }
    // Chart expense vs deposit
    let echartElemPie = document.getElementById('echartPie');
    if (echartElemPie) {
        let echartPie = echarts.init(echartElemPie);
        echartPie.setOption({
            color: ["#7982B9", "#7569b3"],
            tooltip: {
                show: true,
                backgroundColor: 'rgba(0, 0, 0, .8)'
            },
            series: [{
                // name: 'Expense vs Deposit',
                type: 'pie',
                radius: '60%',
                center: ['50%', '50%'],
                data: [{
                        value: @json($expense_amount),
                        name: 'Expense'
                    },
                    {
                        value: @json($deposit_amount),
                        name: 'Deposit'
                    },
                ],
                label: {
                        fontSize: 9, 
                    },
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }]
        });
        $(window).on('resize', function() {
            setTimeout(() => {
                echartPie.resize();
            }, 500);
        });
    }

    // Chart This Week Expense & Deposit

    let echartElemExpense = document.getElementById('echart_Bar_Expense');
    if (echartElemExpense) {
        let echart_Bar_Expense = echarts.init(echartElemExpense);
        var dark_heading = "#c2c6dc";
        echart_Bar_Expense.setOption({

            legend: {
                borderRadius: 0,
                orient: "horizontal",
                x: "right",
                data: ["Expenses", "Deposits"]
            },
            grid: {
                left: '8px',
                right: '8px',
                bottom: '0',
                containLabel: true
            },

            tooltip: {
                show: true,
                backgroundColor: 'rgba(0, 0, 0, .8)'
            },
            xAxis: [{
                type: "category",
                data: @json($days),
                axisTick: {
                    alignWithLabel: true
                },
                splitLine: {
                    show: false
                },
                axisLabel: {
                    color: dark_heading,
                    interval: 0,
                    rotate: 30
                },
                axisLine: {
                    show: true,
                    color: dark_heading,

                    lineStyle: {
                        color: dark_heading
                    }
                }
            }],

            yAxis: [{
                type: "value",

                axisLabel: {
                    color: dark_heading
                    // formatter: "${value}"
                },
                axisLine: {
                    show: false,
                    color: dark_heading,

                    lineStyle: {
                        color: dark_heading
                    }
                },
                min: 0,
                splitLine: {
                    show: true,
                    interval: "auto"
                }
            }],

            series: [{
                    name: 'Expenses',
                    data: @json($expenses_data),
                    label: {
                        show: false,
                        color: "#8B5CF6"
                    },
                    type: 'bar',
                    color: "#A78BFA",
                    barGap: 0,
                    smooth: true,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowOffsetY: -2,
                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                        }
                    }
                },

                {
                    name: 'Deposits',
                    data: @json($deposits_data),
                    label: {
                        show: false,
                        color: "#0168c1",
                        fontSize: 9, 
                    },
                    type: 'bar',
                    color: "#DDD6FE",
                    barGap: 0,
                    smooth: true,
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowOffsetY: -2,
                            shadowColor: 'rgba(0, 0, 0, 0.3)'
                        }
                    }
                },


            ]
        });
        $(window).on('resize', function() {
            setTimeout(() => {
                echart_Bar_Expense.resize();
            }, 500);
        });
    }


    // Chart Project by status
    let echartElemProject = document.getElementById('echartProject');
    if (echartElemProject) {
        let echartProject = echarts.init(echartElemProject);
        echartProject.setOption({
            color: ["#6D28D9", "#8B5CF6", "#A78BFA", "#C4B5FD", "#7C3AED"],
            // color: ["#003f5c", "#58508d", "#bc5090", "#ff6361", "#ffa600"],

            tooltip: {
                show: true,
                backgroundColor: 'rgba(0, 0, 0, .8)'
            },

            series: [{
                    type: 'pie',
                    radius: '60%',
                    center: ['50%', '50%'],
                    data: [
                        @foreach ($project_status as $key => $value)
                            {
                            value:@json($value) , name:@json($key),
                            },
                        @endforeach

                    ],
                    label: {
                        fontSize: 9, 
                    },
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }

            ]
        });
        $(window).on('resize', function() {
            setTimeout(() => {
                echartProject.resize();
            }, 500);
        });
    }

    // Chart Task by status
    let echartElemTask = document.getElementById('echartTask');
    if (echartElemTask) {
        let echartTask = echarts.init(echartElemTask);
        echartTask.setOption({
            // color: ["#6D28D9", "#8B5CF6", "#A78BFA", "#C4B5FD", "#7C3AED"],
            color: ["#003f5c", "#58508d", "#bc5090", "#ff6361", "#ffa600"],

            tooltip: {
                show: true,
                backgroundColor: 'rgba(0, 0, 0, .8)'
            },

            series: [{
                    type: 'pie',
                    radius: '60%',
                    center: ['50%', '50%'],
                    data: [
                        @foreach ($task_status as $key => $value)
                            {
                            value:@json($value) , name:@json(__('translate.'. $key)),
                            },
                        @endforeach

                    ],
                    label: {
                        fontSize: 9, 
                    },
                    itemStyle: {
                        emphasis: {
                            shadowBlur: 10,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.5)'
                        }
                    }
                }

            ]
        });
        $(window).on('resize', function() {
            setTimeout(() => {
                echartTask.resize();
            }, 500);
        });
    }
</script>


<script>
    $('#ul-contact-list').DataTable( {
            "processing": true, // for show progress bar
            select: {
                style: 'multi',
                selector: '.select-checkbox',
                items: 'row',
            },
            columnDefs: [
                {
                    targets: 0,
                    className: 'select-checkbox'
                },
                {
                    targets: [0],
                    orderable: false
                }
            ],
        
            dom: "<'row'<'col-sm-12 col-md-7'lB><'col-sm-12 col-md-5 p-0'f>>rtip",
            oLanguage:
                { 
                sLengthMenu: "_MENU_", 
                sSearch: '',
                sSearchPlaceholder: "{{ __('translate.Search...') }}",
                "oPaginate": {
                    "sNext": "{{ __('translate.Next') }}",
                    "sPrevious": "{{ __('translate.Previous') }}",
                    "sLast": "{{ __('translate.Last') }}",
                },
                "sZeroRecords": "{{__('translate.No matching records found')}}",
                "sInfo": "{{__('translate.Showing _START_ to _END_ of _TOTAL_ entries')}}",
                "sInfoFiltered": "{{ __('translate.filtered from _MAX_ total entries') }}",
                "sInfoEmpty": "{{ __('translate.Showing 0 to 0 of 0 entries')}}",
            },
            buttons: [
                {
                    extend: 'collection',
                    text: 'EXPORT',
                    buttons: [
                        'csv',
                        'excel', 
                        'pdf', 
                        {
                            extend: 'print',
                            text: "{{ __('translate.print') }}",
                        },  
                    ]
                }]
        });

</script>
@endsection
