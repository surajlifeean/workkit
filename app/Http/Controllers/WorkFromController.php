<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;

class WorkFromController extends Controller
{
    public function index(){
        $employee = Employee::where('id', auth()->user()->id)->select('username', 'id')->first();
        return view('attendance.home_office', compact('employee'));
    }

    public function getSelfAttendence(){

    }
}
