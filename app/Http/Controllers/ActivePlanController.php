<?php

namespace App\Http\Controllers;

use App\Models\ActivePlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivePlanController extends Controller
{
    public function index()
    {
    }

    public function store(Request $request)
    {
        $active_plan = ActivePlan::firstOrNew(['plan_request_id' => $request->plan_request_id]);

       
            $active_plan->subs_plan_id = $request->input('subs_plan_id');
            $active_plan->plan_request_id = $request->input('plan_request_id');
            $active_plan->total_users = $request->input('total_users');
            $active_plan->status = $request->input('status');
            
            $active_plan->start_date = is_array($request->input('start_date')) ? $request->input('start_date')['date'] : $request->input('start_date') ?? null;
            $active_plan->end_date = is_array($request->input('end_date')) ? $request->input('end_date')['date'] : $request->input('end_date') ?? null;

            $active_plan->save();

        return response()->json(['message' => 'Successfully created']);
    }
}
