<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\Leave;
use Carbon\Carbon;
use DateTime;
use Exception;
// use DB;
use App\utils\helpers;
use DataTables;
use Illuminate\Support\Facades\DB;

class AttendancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_auth = auth()->user();
        if ($user_auth->can('attendance_view')) {
            if ($user_auth->role_users_id == 1) {
                $attendances = Attendance::where('deleted_at', '=', null)->orderBy('id', 'desc')->get();
                return view('attendance.attendance_list', compact('attendances'));
            } else {
                $attendances = Attendance::where('deleted_at', '=', null)
                    ->where('employee_id', '=', $user_auth->id)->orderBy('id', 'desc')->get();
                    // dd($attendances);
                return view('attendance.attendance_list', compact('attendances'));
            }
        }
        return abort('403', __('You are not authorized'));
    }




    // public function daily_attendance(Request $request)
    // {
    //     $user_auth = auth()->user();
    //     $day_now = Carbon::now()->format('Y-m-d');
    //     $day_in_now = strtolower(Carbon::now()->format('l')) . '_in';

    //     if ($request->ajax()) {
    // 	    if ($user_auth->can('attendance_view')){
    //             if ($user_auth->role_users_id == 1){
    //                 $employee = Employee::with(['office_shift','attendance' => function ($query) use ($day_now)
    //                 {
    //                     $query->where('date' , $day_now);
    //                 },
    //                 'office_shift',
    //                 'company:id,name',
    //                 'leave' => function ($query) use ($day_now)
    //                 {
    //                     $query->where('start_date' ,'<=', $day_now)->where('end_date' ,'>=', $day_now);
    //                 }]
    //                 )
    //                 ->select('id','company_id','username','office_shift_id')
    //                 ->where('joining_date' ,'<=', $day_now)
    //                 ->where('leaving_date' , NULL)
    //                 ->where('deleted_at' , NULL)
    //                 ->get();

    //             }else{

    //                 $employee = Employee::with(['office_shift','attendance' => function ($query) use ($day_now)
    //                 {
    //                     $query->where('date' , $day_now);
    //                 },
    //                 'office_shift',
    //                 'company:id,name',
    //                 'leave' => function ($query) use ($day_now)
    //                 {
    //                     $query->where('start_date' ,'<=', $day_now)->where('end_date' ,'>=', $day_now);
    //                 }]
    //                 )
    //                 ->select('id','company_id','username','office_shift_id')
    //                 ->where('id' ,'=', $user_auth->id)
    //                 ->where('joining_date' ,'<=', $day_now)
    //                 ->where('leaving_date' , NULL)
    //                 ->where('deleted_at' , NULL)
    //                 ->get();
    //             }

    //             $holidays = Holiday::select('id','company_id','start_date','end_date')
    //             ->where('start_date' ,'<=', $day_now)
    //             ->where('end_date' ,'>=', $day_now)
    //             ->where('deleted_at' , NULL)
    //             ->first();

    //         return datatables()->of($employee)
    //             ->setRowId(function($employee)
    //             {
    //                 return $employee->id;
    //             })
    //             ->addColumn('username' , function($employee)
    //             {
    //                 return $employee->username;
    //             })
    //             ->addColumn('company' , function($employee)
    //             {
    //                 return $employee->company->name;
    //             })
    //             ->addColumn('date' , function($employee) use($day_now)
    //             {
    //                 if($employee->attendance->isEmpty()){
    //                     return Carbon::parse($day_now)->format('Y-m-d');
    //                 }else{
    //                     $attendace_row = $employee->attendance->first();
    //                     return $attendace_row->date;
    //                 }
    //             })
    //             ->addColumn('status' , function($employee) use($holidays , $day_in_now)
    //             {
    //                 if($employee->attendance->isEmpty()){

    //                     if(is_null($employee->office_shift->$day_in_now ?? null || ($employee->office_shift->day_in_now == '')))
    //                     {
    //                         return 'Off Day' ;
    //                     }
    //                     if($holidays)
    //                     {
    //                         if($employee->company_id == $holidays->company_id)
    //                         {
    //                             return 'Holiday';
    //                         }
    //                     }
    //                     if($employee->leave->isEmpty())
    //                     {
    //                         return 'Absent';
    //                     }

    //                 return 'On leave';
    //                 }else
    //                 {
    //                     $attendace_row = $employee->attendance->first();
    //                     return $attendace_row->status;
    //                 }
    //                 })
    //             ->addColumn('clock_in' , function($employee)
    //             {
    //                 if($employee->attendance->isEmpty())
    //                 {
    //                     return '---';
    //                 }else
    //                 {
    //                     $attendace_row = $employee->attendance->first();
    //                     return $attendace_row->clock_in;
    //                 }
    //             })
    //             ->addColumn('clock_out' , function($employee)
    //             {
    //                 if($employee->attendance->isEmpty())
    //                 {
    //                     return '---';
    //                 }else
    //                 {
    //                     $attendace_row = $employee->attendance->last();
    //                     return $attendace_row->clock_out;
    //                 }
    //             })
    //             ->addColumn('late_time' , function($employee)
    //             {
    //                 if($employee->attendance->isEmpty())
    //                 {
    //                     return '---';
    //                 }else
    //                 {
    //                     $attendace_row = $employee->attendance->first();
    //                     return $attendace_row->late_time;
    //                 }
    //             })
    //             ->addColumn('depart_early' , function($employee)
    //             {
    //                 if($employee->attendance->isEmpty())
    //                 {
    //                     return '---';
    //                 }else
    //                 {
    //                     $attendace_row = $employee->attendance->first();
    //                     return $attendace_row->depart_early;
    //                 }
    //             })
    //             ->addColumn('overtime' , function($employee)
    //             {
    //                 if($employee->attendance->isEmpty())
    //                 {
    //                     return '---';
    //                 }else
    //                 {
    //                     $total = 0;
    //                     foreach($employee->attendance as $attendance_row)
    //                     {
    //                         sscanf($attendance_row->overtime, '%d:%d' , $hour , $min);
    //                         $total += $hour *60 + $min;
    //                     }
    //                     if($h = floor($total / 60))
    //                     {
    //                         $total %= 60;
    //                     }
    //                     return sprintf('%02d:%02d', $h, $total);
    //                 }
    //             })


    //             ->addColumn('total_work' , function($employee)
    //             {
    //                 if($employee->attendance->isEmpty())
    //                 {
    //                      return '---';
    //                 }else
    //                     {
    //                     $total = 0;
    //                     foreach($employee->attendance as $attendance_row)
    //                         {
    //                             sscanf($attendance_row->total_work, '%d:%d' , $hour , $min);
    //                             $total += $hour * 60 + $min;
    //                         }
    //                     if($h = floor($total / 60))
    //                     {
    //                          $total %= 60;
    //                     }
    //                     return sprintf('%02d:%02d', $h, $total);
    //                 }
    //             })

    //             ->addColumn('total_rest' , function($employee)
    //             {
    //                 if($employee->attendance->isEmpty())
    //                 {
    //                     return '---';
    //                 }else
    //                 {
    //                     $total = 0;
    //                     foreach($employee->attendance as $attendance_row)
    //                     {
    //                         sscanf($attendance_row->total_rest, '%d:%d' , $hour , $min);
    //                         $total += $hour * 60 + $min;
    //                     }
    //                     if($h = floor($total / 60))
    //                     {
    //                         $total %= 60;
    //                     }
    //                     return sprintf('%02d:%02d', $h, $total);
    //                 }
    //             })
    //         ->rawColumns(['action'])
    //         ->make(true);


    //         }else{
    //             return abort('403', __('You are not authorized'));
    //         }
    //     }
    //     return view('attendance.attendance_daily');
    // }

    public function daily_attendance(Request $request)
    {

        $currentMonth = Carbon::now();

        if ($request->has('year_month') && !empty($request->year_month)) {

            $currentMonth = Carbon::parse($request->year_month);
        }
        $monthName = $currentMonth->format('F');
        $numberOfDays = $currentMonth->daysInMonth;

        $employee_attendances = Employee::select([
            'employees.id as employee_id',
            'employees.firstname',
            'employees.lastname',
            'employees.company_id',

            DB::raw('GROUP_CONCAT(attendances.date) as attendance_dates'),
            DB::raw(
                'CONCAT(
                     IF(monday_in IS NULL AND monday_out IS NULL, "monday,", ""),
                     IF(tuesday_in IS NULL AND tuesday_out IS NULL, "tuesday,", ""),
                     IF(wednesday_in IS NULL AND wednesday_out IS NULL, "wednesday,", ""),
                     IF(thursday_in IS NULL AND thursday_out IS NULL, "thursday,", ""),
                     IF(friday_in IS NULL AND friday_out IS NULL, "friday,", ""),
                     IF(saturday_in IS NULL AND saturday_out IS NULL, "saturday,", ""),
                     IF(sunday_in IS NULL AND sunday_out IS NULL, "sunday,", "")
                 ) AS non_working_day'
            ),
            DB::raw('GROUP_CONCAT(work_from.work_from_home_date) as work_from_home_dates')
        ])
            ->leftJoin('attendances', function ($join) use ($currentMonth) {
                $join->on('employees.id', '=', 'attendances.employee_id')
                    ->whereMonth('attendances.date', '=', $currentMonth->month)
                    ->whereDate('attendances.date', '<=', now());
            })
            ->leftJoin('office_shifts', 'employees.office_shift_id', '=', 'office_shifts.id')
            ->leftJoin('work_from', function ($join) use ($currentMonth) {
                $join->on('employees.id', '=', 'work_from.employee_id')
                    ->whereMonth('work_from.work_from_home_date', '=', $currentMonth->month);
                // ->whereDate('work_from.work_from_home_date', '<=', now()); // Include only dates lower or equal to today
            })
            ->groupBy('employees.id')
            ->get(); // Use first() instead of get() to retrieve a single employee

        // Convert the attendance_dates and work_from_home_dates to arrays
        $holidays = null;
        if ($employee_attendances) {
            foreach ($employee_attendances as $employee_attendance) {
                if ($employee_attendance->attendance_dates) {
                    $employee_attendance->attendance_dates = explode(',', $employee_attendance->attendance_dates);
                }
                if ($employee_attendance->work_from_home_dates) {
                    $employee_attendance->work_from_home_dates = explode(',', $employee_attendance->work_from_home_dates);
                }
                if ($employee_attendance->non_working_day) {
                    $employee_attendance->non_working_day = explode(',', rtrim($employee_attendance->non_working_day, ','));
                }

                $holidays = Holiday::where('company_id', $employee_attendance->company_id)
                    ->where(function ($query) use ($currentMonth) {
                        $query->whereMonth('start_date', '=', $currentMonth->month)
                            ->orWhereMonth('end_date', '=', $currentMonth->month);
                    })
                    ->select('start_date', 'end_date')
                    ->get();

                $holidaysArray = $holidays->flatMap(function ($holiday) {
                    $startDate = \Carbon\Carbon::parse($holiday->start_date);
                    $endDate = \Carbon\Carbon::parse($holiday->end_date);
                    $currentDate = now();

                    $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

                    // if ($currentDate >= $endDate) {
                    //     $period->add($currentDate);
                    // }
                    return collect($period)->map(function ($date) {
                        return $date->toDateString();
                    })->toArray();
                });
                //filter unique
                $holidaysArray = array_unique($holidaysArray->all());
                //
                $employee_attendance['holidays'] = $holidaysArray;

                $leaves = Leave::leftJoin('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
                    ->where('leaves.employee_id', $employee_attendance->employee_id)
                    ->where('leaves.status', 'approved')
                    ->select('leaves.start_date', 'leaves.end_date', 'leaves.half_day', 'leave_types.title')
                    ->get() ?? null;

                $employee_attendance['leaves'] = $leaves;
            }
        }
        $currentYm = $currentMonth->format('Y-m');
        $currentYear = $currentMonth->year;

        // dd($employee_attendances, $currentYm);
        return view('attendance.attendance_daily', compact('numberOfDays', 'monthName', 'employee_attendances', 'currentYm', 'currentYear'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_auth = auth()->user();
        if ($user_auth->can('attendance_add')) {

            $companies = Company::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'name']);
            return response()->json([
                'companies'       => $companies,
            ]);
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('attendance_add')) {

            $this->validate($request, [
                'company_id'      => 'required',
                'employee_id'      => 'required',
                'date'           => 'required',
                'clock_in'      => 'required',
                'clock_out'      => 'required',
            ]);


            $employee_id  = $request->employee_id;
            $date  = $request->date;
            $company_id  = $request->company_id;
            $clock_in  = $request->clock_in;
            $clock_out  = $request->clock_out;

            try {
                $clock_in  = new DateTime($clock_in);
                $clock_out  = new DateTime($clock_out);
            } catch (Exception $e) {
                return $e;
            }


            $employee = Employee::with('office_shift')->findOrFail($employee_id);

            $day_now = Carbon::parse($request->date)->format('l');
            $day_in_now = strtolower($day_now) . '_in';
            $day_out_now = strtolower($day_now) . '_out';

            $shift_in = $employee->office_shift->$day_in_now;
            $shift_out = $employee->office_shift->$day_out_now;

            if ($shift_in == null) {
                $data['employee_id'] = $employee_id;
                $data['company_id'] = $company_id;
                $data['date'] = $date;
                $data['clock_in'] = $clock_in->format('H:i');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['status'] = 'present';

                $work_duration = $clock_in->diff($clock_out)->format('%H:%I');
                $data['total_work'] = $work_duration;
                $data['depart_early'] = '00:00';
                $data['late_time'] = '00:00';
                $data['overtime'] = '00:00';
                $data['clock_in_out'] = 0;
            }

            try {
                $shift_in  = new DateTime(substr($shift_in, 0, -2));
                $shift_out  = new DateTime(substr($shift_out, 0, -2));
            } catch (Exception $e) {
                return $e;
            }

            $data['employee_id'] = $employee_id;
            $data['date'] = $date;

            if ($clock_in > $shift_in) {
                $time_diff = $shift_in->diff($clock_in)->format('%H:%I');
                $data['clock_in'] = $clock_in->format('H:i');
                $data['late_time'] = $time_diff;
            } else {
                $data['clock_in'] = $shift_in->format('H:i');
                $data['late_time'] = '00:00';
            }


            if ($clock_out < $shift_out) {
                $time_diff = $shift_out->diff($clock_out)->format('%H:%I');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['depart_early'] = $time_diff;
            } elseif ($clock_out > $shift_out) {
                $time_diff = $shift_out->diff($clock_out)->format('%H:%I');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['overtime'] = $time_diff;
                $data['depart_early'] = '00:00';
            } else {
                $data['clock_out'] = $shift_out->format('H:i');
                $data['overtime'] = '00:00';
                $data['depart_early'] = '00:00';
            }

            $data['status'] = 'present';
            $work_duration = $clock_in->diff($clock_out)->format('%H:%I');
            $data['total_work'] = $work_duration;
            $data['clock_in_out'] = 0;
            $data['company_id'] = $company_id;

            $data['clock_in_ip'] = '';
            $data['clock_out_ip'] = '';

            Attendance::create($data);

            return response()->json(['success' => true]);
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('attendance_edit')) {

            $companies = Company::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'name']);
            return response()->json([
                'companies'       => $companies,
            ]);
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('attendance_edit')) {

            $this->validate($request, [
                'company_id'      => 'required',
                'employee_id'      => 'required',
                'date'           => 'required',
                'clock_in'      => 'required',
                'clock_out'      => 'required',
            ]);

            $employee_id  = $request->employee_id;
            $date  = $request->date;
            $clock_in  = $request->clock_in;
            $clock_out  = $request->clock_out;

            try {
                $clock_in  = new DateTime($clock_in);
                $clock_out  = new DateTime($clock_out);
            } catch (Exception $e) {
                return $e;
            }

            $day_now = Carbon::parse($request->date)->format('l');

            $employee = Employee::with('office_shift')->findOrFail($employee_id);

            $day_in_now = strtolower($day_now) . '_in';
            $day_out_now = strtolower($day_now) . '_out';

            $shift_in = $employee->office_shift->$day_in_now;
            $shift_out = $employee->office_shift->$day_out_now;

            if ($shift_in == null) {
                $data['employee_id'] = $employee_id;
                $data['date'] = $date;
                $data['clock_in'] = $clock_in->format('H:i');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['status'] = 'present';

                $work_duration = $clock_in->diff($clock_out)->format('%H:%I');
                $data['total_work'] = $work_duration;
                $data['depart_early'] = '00:00';
                $data['late_time'] = '00:00';
                $data['overtime'] = '00:00';
                $data['clock_in_out'] = 0;

                return $data;
            }

            try {
                $shift_in  = new DateTime($shift_in);
                $shift_out  = new DateTime($shift_out);
            } catch (Exception $e) {
                return $e;
            }

            $data['employee_id'] = $employee_id;
            $data['date'] = $date;

            if ($clock_in > $shift_in) {
                $time_diff = $shift_in->diff($clock_in)->format('%H:%I');
                $data['clock_in'] = $clock_in->format('H:i');
                $data['late_time'] = $time_diff;
            } else {
                $data['clock_in'] = $shift_in->format('H:i');
                $data['late_time'] = '00:00';
            }


            if ($clock_out < $shift_out) {
                $time_diff = $shift_out->diff($clock_out)->format('%H:%I');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['depart_early'] = $time_diff;
            } elseif ($clock_out > $shift_out) {
                $time_diff = $shift_out->diff($clock_out)->format('%H:%I');
                $data['clock_out'] = $clock_out->format('H:i');
                $data['overtime'] = $time_diff;
                $data['depart_early'] = '00:00';
            } else {
                $data['clock_out'] = $shift_out->format('H:i');
                $data['overtime'] = '00:00';
                $data['depart_early'] = '00:00';
            }

            $data['status'] = 'present';
            $work_duration = $clock_in->diff($clock_out)->format('%H:%I');
            $data['total_work'] = $work_duration;
            $data['clock_in_out'] = 0;


            Attendance::find($id)->update($data);

            return response()->json(['success' => true]);
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('attendance_delete')) {

            Attendance::whereId($id)->update([
                'deleted_at' => Carbon::now(),
            ]);

            return response()->json(['success' => true]);
        }
        return abort('403', __('You are not authorized'));
    }

    //-------------- Delete by selection  ---------------\\

    public function delete_by_selection(Request $request)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('attendance_delete')) {
            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $attendance_id) {
                Attendance::whereId($attendance_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
            return response()->json(['success' => true]);
        }
        return abort('403', __('You are not authorized'));
    }
}
