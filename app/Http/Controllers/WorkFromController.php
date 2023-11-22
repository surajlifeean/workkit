<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\OfficeShift;
use App\Models\User;
use App\Models\WorkFrom;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WorkFromController extends Controller
{
    public function index()
    {
        $employee_id = auth()->user()->id;
        $employee = Employee::where('id', $employee_id)->select('username', 'id', 'office_shift_id', 'company_id')->first();
        $work_from_home = null;
        $officeShift = OfficeShift::first();

        $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endDate = Carbon::now()->endOfWeek()->format('Y-m-d');

        if ($employee) {
            $work_from_home = WorkFrom::where('employee_id', $employee_id)
                ->where('company_id', $employee->company_id)
                ->whereBetween('work_from_home_date', [$startDate, $endDate])
                ->orderBy('work_from_home_date', 'asc')
                ->pluck('work_from_home_date');

            $get_halfdays_times = WorkFrom::whereIn('work_from_home_date', $work_from_home)
                ->select('work_from_home_date', 'work_from_home_time')->get();

            $officeShift = OfficeShift::where('id', $employee->office_shift_id)->first();

            foreach ($get_halfdays_times as $get_halfdays_time) {
                if ($get_halfdays_time->work_from_home_time != null) {
                    $dayOfWeekString = strtolower(date('l', strtotime($get_halfdays_time->work_from_home_date)));
                    $officeShift[$dayOfWeekString . "_in"] = date('H:i', strtotime($get_halfdays_time->work_from_home_time));
                    $officeShift[$dayOfWeekString . "_out"] = date('H:i', strtotime($get_halfdays_time->work_from_home_time . ' +4 hours'));
                }
            }
        } else {
            // if its admin 
            $work_from_home = WorkFrom::where('employee_id', $employee_id)
                ->where('company_id', 0)
                ->whereBetween('work_from_home_date', [$startDate, $endDate])
                ->orderBy('work_from_home_date', 'asc')
                ->pluck('work_from_home_date');

            $get_halfdays_times = WorkFrom::whereIn('work_from_home_date', $work_from_home)
                ->select('work_from_home_date', 'work_from_home_time')->get();

            if(auth()->user()->office_shift_id){
                $officeShift = OfficeShift::where('id', auth()->user()->office_shift_id)->first();
            }

            foreach ($get_halfdays_times as $get_halfdays_time) {
                if ($get_halfdays_time->work_from_home_time != null) {
                    $dayOfWeekString = strtolower(date('l', strtotime($get_halfdays_time->work_from_home_date)));
                    $officeShift[$dayOfWeekString . "_in"] = date('H:i', strtotime($get_halfdays_time->work_from_home_time));
                    $officeShift[$dayOfWeekString . "_out"] = date('H:i', strtotime($get_halfdays_time->work_from_home_time . ' +4 hours'));
                }
            }
        }


        $otherEmployeesQuery = Employee::where('employees.id', '<>', auth()->user()->id)
            ->leftJoin('designations', 'designations.id', '=', 'employees.designation_id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->select('employees.username', 'employees.id as employee_id', 'employees.office_shift_id', 'designations.designation', 'departments.department');

        if ($employee && $employee->company_id) {
            $otherEmployeesQuery->where('employees.company_id', '=', $employee->company_id);
        }
        $otherEmployees = $otherEmployeesQuery->get();



        foreach ($otherEmployees as $value) {
            $wfh = WorkFrom::where('employee_id', $value->employee_id)
                ->whereBetween('work_from_home_date', [$startDate, $endDate])
                ->pluck('work_from_home_date');

            $shift = OfficeShift::where('id', $value->office_shift_id)->first();

            if ($wfh) {
                $value['from_home'] = $wfh;
            } else {
                $value['from_home'] = null;
            }
            $wfhomes = WorkFrom::whereIn('work_from_home_date', $wfh)->select('work_from_home_time', 'work_from_home_date')->get();
            foreach ($wfhomes as $wfhome) {
                if ($shift) {
                    $value['shifts'] = $shift;
                    if ($wfhome->work_from_home_time != null) {
                        $dayOfWeekString = strtolower(date('l', strtotime($wfhome->work_from_home_date)));
                        $value['shifts'][$dayOfWeekString . "_in"] = date('H:i', strtotime($wfhome->work_from_home_time));
                        $value['shifts'][$dayOfWeekString . "_out"] = date('H:i', strtotime($wfhome->work_from_home_time . ' +4 hours'));
                    }
                } else {
                    $value['shifts'] = null;
                }
            }
        }
        // adding admin records
        $distinctEmployeeIds = WorkFrom::where('company_id', 0)->where('employee_id', '<>', auth()->user()->id)
            ->whereBetween('work_from_home_date', [$startDate, $endDate])
            ->distinct('employee_id')->pluck('employee_id');
        // dd($distinctEmployeeIds);
        $data = Employee::select('id')->first();

        foreach ($distinctEmployeeIds as $id) {

            $current_user = User::where('id', $id)->first();

            $shifts = null;
            $wfh = WorkFrom::where('employee_id', $id)
                ->whereBetween('work_from_home_date', [$startDate, $endDate])
                ->pluck('work_from_home_date');

            $shift = OfficeShift::where('id', $value->office_shift_id)->first();


            if (!$current_user->office_shift_id) {
                $shifts = OfficeShift::first();
            } else {
                $shifts = OfficeShift::where('id', $current_user->office_shift_id)->first();
            }

            $data['username'] = $current_user->username;
            $data['employee_id'] = $id;
            $data['office_shift_id'] = $current_user->office_shift_id ? $current_user->office_shift_id : $shifts->id;

            if ($wfh) {
                $data['from_home'] = $wfh;
            } else {
                $data['from_home'] = null;
            }

            $data['shifts'] = $shifts;

            $otherEmployees[] = $data;
        }
        $otherEmployees = $otherEmployees->sortBy('employee_id')->values()->all();

        //   dd($otherEmployees);
        return view('attendance.home_office', compact('employee', 'work_from_home', 'officeShift', 'otherEmployees'));
    }


    public function workFromHome(Request $request)
    {
        // dd($request);
        $employee_id = auth()->user()->id;
        $comp_id = Employee::where('id', $employee_id)->select('company_id')->first();
        if ($request->type == 'From Office') {
            $check_home = WorkFrom::where('work_from_home_date', $request->work_from_home_date)
                ->where('employee_id', $employee_id)
                ->first();
            if (!$check_home) {
                $new = WorkFrom::create([
                    'work_from_home_date' => $request->work_from_home_date,
                    'employee_id' => $employee_id,
                    'company_id'  => $comp_id && $comp_id->company_id ? $comp_id->company_id : '',
                    'work_from_home_time' => $request->day_type == 'halfDay' ? $request->time : null,
                ]);
                return response()->json(['200' => $new]);
            }
            return response()->json(['200' => 'found']);
        } else {
            $deleted = WorkFrom::where('work_from_home_date', $request->work_from_home_date)
                ->where('employee_id', $employee_id)
                ->delete();

            if ($deleted) {
                return response()->json(['200' => 'From Office']);
            } else {
                return response()->json(['200' => 'Record not found']);
            }
        }
    }

    public function othersWorkFromHome()
    {
    }
}
