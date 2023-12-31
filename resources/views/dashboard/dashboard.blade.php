<?php 
 $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); 
 $currentLanguage = app()->getLocale();
 $lang = ($currentLanguage ?? '') === 'en' ? 'en' : (($currentLanguage === 'fr') ? 'fr' : 'ar'); 
?>
@extends('layouts.master')
@section('main-content')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/datatables.min.css') }}">
    <style>
        @media (min-width: 992px) {
            .col-lg-2 {
                flex: 0 0 20%;
                max-width: 20%;
            }
    
   
        }
    </style>
@endsection

<div class="row" id="section_Dashboard">
    <!-- ICON BG -->
    <div class="col-lg-2 col-md-6 col-sm-6">
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

    <div class="col-lg-2 col-md-6 col-sm-6">
        <a href="/clients">
            <div class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Boy"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">{{ __('translate.Clients') }}</p>
                        <p class="text-{{  $setting->theme_color  }} text-24 line-height-1 mb-2">{{ $count_clients }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-6 col-sm-6">
        <a href="/projects">
            <div class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Dropbox"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">{{ __('translate.Projects') }}</p>
                        <p class="text-{{  $setting->theme_color  }} text-24 line-height-1 mb-2">{{ $count_project }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-6 col-sm-6">
        <a href="/tasks">
            <div class="card card-icon-bg card-icon-bg-{{  $setting->theme_color  }} o-hidden mb-4">
                <div class="card-body text-center">
                    <i class="i-Check"></i>
                    <div class="content">
                        <p class="text-muted mt-2 mb-0">{{ __('translate.Tasks') }}</p>
                        <p class="text-{{  $setting->theme_color  }} text-24 line-height-1 mb-2">{{ $count_task }}</p>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-lg-2 col-md-6 col-sm-6">
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
    <div class="col-lg-8 col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.This Week Expense') }}</div>
                <div id="echart_Bar_Expense"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-sm-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.Projects_by_Status') }}</div>
                <div id="echartProject"></div>
            </div>
        </div>
    </div>



</div>

<div class="row">

    <div class="col-lg-8 col-sm-12">
        <div class="card o-hidden mb-4">
            <div class="card-header d-flex align-items-center border-0">
                <h3 class="w-50 float-left card-title m-0">{{ __('translate.Latest_Employees') }}</h3>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="ul-contact-list" class="table text-center">
                        <thead>
                            <tr>
                                <th></th>
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
                                    <th></th>
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

    <div class="col-lg-4 col-sm-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.Tasks_by_Status') }}</div>
                <div id="echartTask"></div>
            </div>
        </div>
    </div>


</div>

<div class="row">

    <div class="col-lg-8 col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.Employee_count_by_department') }}</div>
                <div id="echartBar"></div>
            </div>
        </div>
    </div>


    <div class="col-lg-4 col-sm-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-title">{{ __('translate.Expense') }}</div>
                <div id="echartPie"></div>
            </div>
        </div>
    </div>

    
</div>
@endsection

@section('page-js')
<script src="{{ asset('assets/js/vendor/echarts.min.js') }}"></script>
<script src="{{ asset('assets/js/echart.options.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables.script.js') }}"></script>
<script>
    // Chart Employee count by department
    // bg-primary
    let lang = @json($lang);
    let expenses = lang === 'en' ? 'Expenses' : (lang === 'fr' ? 'Dépenses' : 'مصروف')
    // Get the element by ID
   
    let echartElemBar = document.getElementById('echartBar');
    if (echartElemBar) {
        let echartBar = echarts.init(echartElemBar);
        var dark_heading = "#c2c6dc";
        const barColors = ['#6633d1', '#f7b84a'];
        
        echartBar.setOption({

            formatter: function(params) {
                return `${params.value} {{__('translate.Employees')}}`;
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
                    rotate: 30,
                    itemStyle: {
                        color: function(params) {
                        return barColors[params.dataIndex % barColors.length];
                        },
                    }
                },
                axisLine: {
                    show: true,
                    color: dark_heading,

                    lineStyle: {
                        color: function(params) {
                        return barColors[params.dataIndex % barColors.length];
                        },
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
                    barGap: 0,
                    smooth: true,
                    itemStyle: {
                        color: function(params) {
                        return barColors[params.dataIndex % barColors.length];
                        },
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
                        name: expenses
                    },
                    // {
                    //     value: @json($deposit_amount),
                    //     name: 'Deposit'
                    // },
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
                data: [expenses, "Deposits"]
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

            series: [
                {
                    name: expenses,
                    data: @json($expenses_data),
                    label: {
                        show: false,
                        color: '#f44336',
                        fontSize: 9, 
                    },
                    type: 'bar',
                    color: '#f44336',
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

                //           

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
                            value:@json($value) , name:@json( __('translate.'. $key) ),
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
                            value:@json($value) , name:@json( __('translate.'. $key) ),
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
