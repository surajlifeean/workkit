<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClaimController extends Controller
{
    public function index()
    {
        $claims = Claim::all();
        return view('claims.index', compact('claims'));
    }

    public function store(Request $request)
    {
     
        // if (auth()->user()->role_users_id == 2) {
            request()->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'attachment' => 'nullable|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
            ]);
            
            $filename = null;
            if ($request->hasFile('attachment')) {


                $image = $request->file('attachment');
                $filename = time() . '.' . $image->extension();
                $image->move(public_path('/assets/images/claims'), $filename);
            } else {
                $filename = 'no_image.png';
            }
        
            $claim = new Claim();
            $claim->title = $request->input('title');
            $claim->description = $request->input('description');
            $claim->employee_id = auth()->user()->id;
            // $claim->status = $request->input('status');
            $claim->attachment = $filename;
            $claim->save();

            // dd($claim);
            return response()->json(['success' => true, 'isvalid' => true]);
        // }
    }

    public function destroy($id)
    {

        Claim::whereId($id)->update([
            'deleted_at' => Carbon::now(),
        ]);

        return response()->json(['success' => true]);
    }
}
