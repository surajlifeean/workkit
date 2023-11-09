<?php
$setting = DB::table('settings')->where('deleted_at', '=', null)->first();
$leave_type = DB::table('leave_types')->where('deleted_at', '=', null)->get();
?>
@extends('layouts.master')
@section('main-content')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datepicker.min.css')}}">

<style>
    .mainbody_table::-webkit-scrollbar {
        display: none;
    }

    .hidden_divs {
        display: none;
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 0.5rem;
        top: 0;
        z-index: 1;
    }

    td {
        cursor: pointer;
        height: 1rem;
    }

    td:hover .hidden_divs {
        display: inline;
    }

    span {
        color: #000 !important;
    }

    /* .bg-success{
        background-color: #4caf50;
    }
    .bg-blue{
        background-color: #003473;
    }
    .bg-secondary{
        background-color: #52495a;
    }
    .bg-teal{
        background-color: #20c997;
    }

    .bg-warning{
        background-color: #ffc107;
    } */
</style>
@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Daily_Attendance') }}</h1>
    <ul>
        <li><a href="/daily_attendance">{{ __('translate.Attendance') }}</a></li>
        <li>{{ __('translate.Daily_Attendance') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_daily_attendance">
    <div class="col-md-12">


        <div class="card text-left p-2">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 text-right">
                        <button class="btn btn-{{ $setting->theme_color}} mb-2 ml-auto" data-toggle="modal" data-target="#myModal">{{ __('translate.Customize') }}</button>

                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-md-3 d-flex my-1 my-sm-0">
                        <h5 class="mr-auto">{{ __('translate.'. $monthName ) }}, {{ $currentYear }} </h5>
                        <select id="recordsPerPage" class="form-control" style="width: 80px;">
                            <option value="10">10</option>
                            <option value="1">1</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-3 my-sm-0 my-1">
                        <input type="text" class="form-control" placeholder="{{ __('translate.Search Employee..')}}" id="employeeSearch">
                    </div>

                    <div class="col-12 col-sm-6 d-flex col-lg-3 my-1 my-sm-0">
                        <form action="{{ route('daily_attendance') }}" class="d-flex" method="GET">
                            @csrf
                            @method('GET')
                            <input type="month" class="form-control mr-2" name="year_month">
                            <button class="btn btn-{{ $setting->theme_color}} mx-2">{{__('translate.Submit')}}</button>
      
                        </form>


                    </div>

                </div>
                <div class="row my-2">

                </div>
                <div class="row">
                    <div class="col-3  px-0">
                        <table class="" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center px-auto p-1 border border-light"><button id="left-button" class="btn btn-light px-1 py-0"><</button><span class="mx-3">{{ __("translate.Employee") }}</span><button id="right-button" class="btn btn-light px-1 py-0">></button></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee_attendances as $employee_attendance)
                                <tr class="employeeRow employess_search employeeRow1">
                                    <td class="text-left px-auto p-1 border border-light ">{{ $employee_attendance->firstname }} {{ $employee_attendance->lastname }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-9 mainbody_table pl-0" id="mainbody_table" style="overflow-x: scroll;">
                        <table class="">
                            <thead>
                                <tr>
                                    @for($i = 1; $i <= $numberOfDays; $i++) <th class="border border-light p-1 px-3 h-full">{{ $i }}</th>
                                        @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee_attendances as $employee_attendance)
                                <tr class="employeeRow employess_search employeeRow2">
                                    <td hidden>{{ $employee_attendance->firstname }} {{ $employee_attendance->lastname }}</td>
                                    @for($i = 1; $i <= $numberOfDays; $i++) @php $present=false; $non_working_day=false; $wfh=false; $holiday=false; $im=null; $is_leave=false; $leave_title=null; if($i < 10) { $im='0' .$i; } else { $im=$i; } $now=\Carbon\Carbon::now(); $graterThenNow=\Carbon\Carbon::parse($currentYm.'-'.$im)->gt($now);
                                        $dayOfWeek = \Carbon\Carbon::parse($currentYm.'-'.$im)->format('l');
                                        @endphp

                                        @if($employee_attendance['attendance_dates'])
                                        @foreach ($employee_attendance['attendance_dates'] as $date)

                                        @if ( $date == $currentYm.'-'.$im)

                                        @php
                                        $present = true;
                                        @endphp
                                        @endif
                                        @endforeach
                                        @endif

                                        @if($employee_attendance['non_working_day'])

                                        @foreach ($employee_attendance['non_working_day'] as $date)
                                        @if ( $dayOfWeek == ucwords($date))
                                        @php
                                        $non_working_day = true;
                                        @endphp
                                        @endif
                                        @endforeach
                                        @endif

                                        @if($employee_attendance['work_from_home_dates'])
                                        @foreach ($employee_attendance['work_from_home_dates'] as $date)

                                        @if ( $date == $currentYm.'-'.$im)
                                        @php
                                        $wfh = true;
                                        @endphp
                                        @endif
                                        @endforeach
                                        @endif

                                        @if($employee_attendance['holidays'])
                                        @foreach ($employee_attendance['holidays'] as $date)

                                        @if ( $date == $currentYm.'-'.$im)
                                        @php
                                        $holiday = true;
                                        @endphp
                                        @endif
                                        @endforeach
                                        @endif

                                        @if($employee_attendance['leaves'])
                                        @php
                                        $currentDate = \Carbon\Carbon::now();
                                        $targetDate = \Carbon\Carbon::parse($currentYm.'-'.$im);
                                        @endphp

                                        @foreach($employee_attendance['leaves'] as $leave)
                                        @php
                                        $startDate = \Carbon\Carbon::parse($leave->start_date);
                                        $endDate = \Carbon\Carbon::parse($leave->end_date);
                                        @endphp

                                        @if ($targetDate->between($startDate, $endDate) || $targetDate->eq($startDate) || $targetDate->eq($endDate))

                                        @php
                                        $is_leave = true;
                                        $leave_title = $leave->title;
                                        @endphp

                                        @endif
                                        @endforeach

                                        @endif

                                        @if ($present)
                                        @if($wfh)
                                        <td class="border border-light p-1 px-auto text-center bg-blue wfh position-relative" style="color: transparent;">
                                            <div class="position-absolute hidden_divs">
                                                <span>{{ __('translate.' .$dayOfWeek) }}</span>
                                                <span>{{ __('translate.Work from home')}}</span>
                                            </div>
                                        </td>
                                        @else
                                        <td class="border border-light p-1 px-auto text-center bg-success present position-relative">
                                            <div class="position-absolute hidden_divs">
                                                <span>{{ __('translate.' .$dayOfWeek) }}</span>
                                                <span>{{__('translate.Present')}}</span>

                                            </div>
                                        </td>
                                        @endif
                                        @elseif($non_working_day)
                                        <td class="border border-light p-1 px-auto text-center bg-secondary non_working_day position-relative" style="color: transparent;">
                                            <div class="position-absolute hidden_divs">
                                                <span>{{ __('translate.' .$dayOfWeek) }}</span>
                                                <span>{{ __('translate.Week ends')}}</span>

                                            </div>
                                        </td>
                                        @elseif ($wfh && $graterThenNow)
                                        <td class="border border-light p-1 px-auto text-center bg-blue wfh position-relative" style="color: transparent;">-
                                            <div class="position-absolute hidden_divs">
                                                <span>{{ __('translate.' .$dayOfWeek) }}</span>
                                                <span>{{ __('translate.Work from home')}}</span>
                                            </div>
                                        </td>
                                        @elseif($holiday)
                                        <td class="border border-light p-1 px-auto text-center bg-teal holiday position-relative" style="color: transparent;">
                                            <div class="position-absolute hidden_divs">
                                                <span>{{ __('translate.' .$dayOfWeek) }}</span>
                                                <span>{{ __('translate.Holiday') }}</span>

                                            </div>
                                        </td>
                                        @elseif($is_leave)
                                        <td class="border border-light p-1 px-auto text-center bg-warning leave position-relative {{ $leave_title }}" style="color: transparent;">
                                            <div class="position-absolute hidden_divs">
                                                <span>{{ __('translate.' .$dayOfWeek) }}</span>
                                                <span>{{ __('translate.Leave') }}</span>
                                                <span>{{ $leave_title }}</span>
                                            </div>
                                        </td>
                                        @else
                                        <td class="border border-light p-1 px-auto text-center bg-white absent h-full absent" style="color: transparent;">
                                            -
                                        </td>
                                        @endif

                                        @endfor
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
        </div>


    </div>
</div>
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{ __('translate.Customize') }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 d-flex align-items-center">
                        <lebel class="mr-1">
                            All
                        </lebel><br>
                        <select name="all_inp" id="all_inp" class="form-control">
                            <option value="present">{{ __('translate.Present')}}</option>
                            <option value="absent">{{ __('translate.Absent')}}</option>
                            <option value="wfh">{{ __('translate.Work from home')}}</option>
                            <option value="holiday">{{ __('translate.Holiday')}}</option>
                            <option value="non_working_day">{{ __('translate.Week ends')}}</option>
                        </select>
                        <input type="color" id="colorPicker_allinp" name="colorPicker" class="" style="width: 25px;">
                        <button class="btn btn-{{ $setting->theme_color }} mx-2" id="all_input">{{__('translate.Submit')}}</button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 d-flex align-items-center">
                        <lebel class="mr-1">
                            {{ __('translate.Leave') }}
                        </lebel><br>
                        <select name="leaves_inp" id="leaves_inp" class="form-control">
                            @foreach($leave_type as $lt)
                            <option value="{{$lt->title}}">{{$lt->title}}</option>
                            @endforeach
                        </select>
                        <input type="color" id="colorPicker_leaves" name="colorPicker" class="" style="width: 25px;">
                        <button class="btn btn-{{ $setting->theme_color}} mx-2" id="leaves_input">{{__('translate.Submit')}}</button>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>


<script>
    // console.log(first)

    $(document).ready(function() {
        $("#employeeSearch").on("input", function() {
            var searchTerm = $(this).val().toLowerCase();

            $(".employeeRow").each(function() {
                var employeeName = $(this).children().first().text().toLowerCase();

                if (employeeName.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });


        $('#recordsPerPage').change(function() {
            var selectedValue = $(this).val();
            var tableRows1 = $('.employeeRow1');
            var tableRows2 = $('.employeeRow2');

            if (selectedValue === 'all') {
                tableRows1.show();
                tableRows2.show();
            } else {
                // Show only selected number of rows
                tableRows1.hide().slice(0, selectedValue).show();
                tableRows2.hide().slice(0, selectedValue).show();
            }
        });
        $('#recordsPerPage').val('25').change();

        $('#all_input').on('click', function() {
            let selectedColor = $('#colorPicker_allinp').val();
            let selectedClass = $('#all_inp').val();
            // selectedColor += '!important';
            // Apply the background color to elements with the selected class
            $('.' + selectedClass).attr('style', 'background-color: ' + selectedColor + ' !important');
        });

        $('#leaves_input').on('click', function() {
            let selectedColor = $('#colorPicker_leaves').val();
            let selectedLeaveType = $('#leaves_inp').val();

            $('.leave.' + selectedLeaveType).attr('style', 'background-color: ' + selectedColor + ' !important');
        });
    });

    const rightBtn = document.querySelector('#right-button');
    const leftBtn = document.querySelector('#left-button');

    rightBtn.addEventListener("click", function(event) {
        console.log(event)
        const conent = document.querySelector('#mainbody_table');
        conent.scrollLeft += 300;
        event.preventDefault();
    });

    leftBtn.addEventListener("click", function(event) {
        console.log(event)
        const conent = document.querySelector('#mainbody_table');
        conent.scrollLeft -= 300;
        event.preventDefault();
    });
</script>
@endsection