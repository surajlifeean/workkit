<?php

namespace App\Http\Controllers;

use App\Models\ActivePlan;
use Illuminate\Http\Request;

class ActivePlanController extends Controller
{
    public function index()
    {
    }

    public function store(Request $request)
    {
        $dataToFill = [
            'plan_id' => $request->input('plan_id'),
            'plan_request_id' => $request->input('plan_request_id'),
            'total_users' => $request->input('total_users'),
            'status' => $request->input('status'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
        ];

        $active_plan = ActivePlan::firstOrNew(['plan_request_id' => $dataToFill['plan_request_id']]);

        $active_plan->fill($dataToFill);
        $active_plan->save();

        return response()->json(['message' => 'Successfully created']);
    }
}
