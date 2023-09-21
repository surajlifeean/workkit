<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use App\Models\OfficeShift;
use App\Models\WorkFrom;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WorkFromController extends Controller
{
    public function index()
    {
        $employee_id = auth()->user()->id;
        $employee = Employee::where('id', $employee_id)->select('username', 'id', 'office_shift_id')->first();

        $startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endDate = Carbon::now()->endOfWeek()->format('Y-m-d');
        $officeShift = OfficeShift::where('id', $employee->office_shift_id)->first();

        $work_from_home = WorkFrom::where('employee_id', $employee_id)
            ->whereBetween('work_from_home_date', [$startDate, $endDate])
            ->orderBy('work_from_home_date', 'asc')
            ->pluck('work_from_home_date');

        $otherEmployees = Employee::
            select('username', 'id as employee_id', 'office_shift_id')
            ->get();
            

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

           if ($shift) {
             $value['shifts'] = $shift;
           } else {
            $value['shifts'] = null;
           }
           
        }
    //    return $otherEmployees;
        return view('attendance.home_office', compact('employee', 'work_from_home', 'officeShift', 'otherEmployees'));
    }


    public function workFromHome(Request $request)
    {
        $employee_id = auth()->user()->id;

        if ($request->type == 'From Office') {
            $check_home = WorkFrom::where('work_from_home_date', $request->work_from_home_date)
                ->where('employee_id', $employee_id)
                ->first();
            if (!$check_home) {
                $new = WorkFrom::create([
                    'work_from_home_date' => $request->work_from_home_date,
                    'employee_id' => $employee_id,
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
