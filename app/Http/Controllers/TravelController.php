<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use App\Models\Travel;
use App\Models\ArrangementType;
use App\Models\Company;
use App\Models\Employee;
use App\Models\ExpenseCategory;
use App\Models\Notification;
use Carbon\Carbon;

class TravelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_auth = auth()->user();
        // dd('gh');
        if ($user_auth->can('travel_view') || $user_auth->role_users_id == 4) {

            $travels = Travel::with('company:id,name', 'employee:id,username', 'expenseCategory:id,title')
                ->where('deleted_at', '=', null)
                ->orderBy('id', 'desc')
                ->get();
            // dd($travels);
            $accounts = Account::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id','account_name']);

            $exp_types = ExpenseCategory::get();
            // dd($travels);
            return view('hr.travel.travel_list', compact('travels', 'exp_types', 'accounts'));
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_auth = auth()->user();
        // if ($user_auth->can('travel_add')){

        $companies   = Company::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'name']);
        $arrangement_types = ArrangementType::where('deleted_at', '=', null)->orderBy('id', 'desc')->get();

        return response()->json([
            'companies'         => $companies,
            'arrangement_types' => $arrangement_types,
        ]);

        // }
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
        // dd($request);
        $user_auth = auth()->user();
        if ($user_auth->can('travel_add') || $user_auth->role_users_id == 4 || $user_auth->role_users_id == 2) {
            // dd($request);
            $this->validate($request, [
                'company_id'         => 'required',
                'employee_id'         => 'required',
                'arrangement_type_id' => 'nullable',
                'expected_budget'     => 'required|numeric',
                'actual_budget'       => 'required|numeric',
                'start_date'          => 'required',
                'end_date'            => 'required',
                'visit_purpose'       => 'required|string|max:255',
                'visit_place'         => 'required|string|max:255',
                'travel_mode'         => 'required|string|max:255',
                'status'            => 'required',
            ]);
            $filename = null;
            if ($request->hasFile('attachment')) {

                $image = $request->file('attachment');
                $filename = time() . '.' . $image->extension();
                $image->move(public_path('/assets/images/expenses'), $filename);
            }
            
            Travel::create([
                'company_id'         => $request['company_id'],
                'employee_id'         => $request['employee_id'],
                // 'expense_category_id' => $request['expense_category_id'],
                // 'arrangement_type_id' => $request['arrangement_type_id'],
                'expected_budget'     => $request['expected_budget'],
                'actual_budget'       => $request['actual_budget'],
                'start_date'          => $request['start_date'],
                'end_date'            => $request['end_date'],
                'visit_purpose'       => $request['visit_purpose'],
                'visit_place'         => $request['visit_place'],
                'travel_mode'         => $request['travel_mode'],
                'description'         => $request['description'],
                'status'            => $request['status'],
                'attachment' => $filename,
                'expense_category_id' => $request['expense_category_id']
            ]);
            $comp_id = Employee::where('id', $user_auth->id)->pluck('company_id');

            if($user_auth->role_users_id != 1){
                $new_notification = new Notification();
                $new_notification->title = 'New employee expense';
                $new_notification->message = 'New employee expense request available';
                $new_notification->user_id = $user_auth->id;
                $new_notification->is_seen = 0;
    
                $new_notification->company_id = $comp_id;
                $new_notification->save();
            }
         
            // dd($new_notification);

            return response()->json(['success' => true]);
            // dd('hit');
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
        if ($user_auth->can('travel_edit')) {

            $companies   = Company::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'name']);
            $arrangement_types = ArrangementType::where('deleted_at', '=', null)->orderBy('id', 'desc')->get();

            return response()->json([
                'companies'         => $companies,
                // 'arrangement_types' => $arrangement_types,
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
        // dd($request);
        $user_auth = auth()->user();
        if ($user_auth->can('travel_edit') || $user_auth->role_users_id == 4 || $user_auth->role_users_id == 2) {
         
            $this->validate($request, [
                'company_id'         => 'required',
                'employee_id'         => 'required',
                // 'arrangement_type_id' => 'required',
                'expected_budget'     => 'required|numeric',
                'actual_budget'       => 'required|numeric',
                'start_date'          => 'required',
                'end_date'            => 'required',
                'visit_purpose'       => 'required|string|max:255',
                'visit_place'         => 'required|string|max:255',
                'travel_mode'         => 'required|string|max:255',
                'status'              => 'required',

            ]);
            //    dd($request);
            $travel = Travel::findOrFail($id);

            $Current_attachment = $travel->attachment;
            $filename = null;
            if ($request->attachment != 'null' && $request->hasFile('attachment')) {
                if ($request->attachment != $Current_attachment) {

                    $image = $request->file('attachment');
                    $filename = time() . '.' . $image->extension();
                    $image->move(public_path('/assets/images/expenses'), $filename);
                    $path = public_path() . '/assets/images/expenses';
                    $attachment = $path . '/' . $Current_attachment;
                    if (file_exists($attachment)) {
                        @unlink($attachment);
                    }
                } else {
                    $filename = $Current_attachment;
                }
            } else {
                $filename = $Current_attachment;
            }

            Travel::whereId($id)->update([
                'company_id'         => $request['company_id'],
                'employee_id'         => $request['employee_id'],
                'arrangement_type_id' => $request['arrangement_type_id'],
                'expected_budget'     => $request['expected_budget'],
                'actual_budget'       => $request['actual_budget'],
                'start_date'          => $request['start_date'],
                'end_date'            => $request['end_date'],
                'visit_purpose'       => $request['visit_purpose'],
                'visit_place'         => $request['visit_place'],
                'travel_mode'         => $request['travel_mode'],
                'description'         => $request['description'],
                'status'            => $request['status'],
                'attachment' => $filename,
                'expense_category_id' => $request['expense_category_id']
            ]);

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
        if ($user_auth->can('travel_delete') || $user_auth->role_users_id == 4 || $user_auth->role_users_id == 2) {

            Travel::whereId($id)->update([
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
        if ($user_auth->can('travel_delete') || $user_auth->role_users_id == 4 || $user_auth->role_users_id == 2) {
            $selectedIds = $request->selectedIds;

            foreach ($selectedIds as $travel_id) {
                Travel::whereId($travel_id)->update([
                    'deleted_at' => Carbon::now(),
                ]);
            }
            return response()->json(['success' => true]);
        }
        return abort('403', __('You are not authorized'));
    }
}
