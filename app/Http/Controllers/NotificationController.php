<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Notification;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Http;

class NotificationController extends Controller
{
    public function index($id)
    {
        if ( auth()->user()->role_users_id == 1 ) {
            $notifications = Notification::where('is_superadmin', 1)
            ->select('title', 'message', 'created_at', 'user_id', 'is_superadmin')
            ->get();
            // dd($notifications);
            return view('notifications.index', compact('notifications'));
        } else {
            try {
                if (Auth::check()) {
    
                    $notifications = Notification::where('leave_id', $id)->get();
    
                    return response()->json($notifications);
                } else {
                    return response()->json(['error' => 'User is not authenticated'], 401);
                }
            } catch (ModelNotFoundException $e) {
                return response()->json(['error' => 'Record not found'], 404);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Internal Server Error'], 500);
            }
        }  
    }
    public function store_from_superadmin(Request $request){
        // return response()->json('fghj'); 
      Notification::create([
        'title' => $request->title,
        'message' => $request->message,
        'is_superadmin' => $request->is_superadmin,
        'user_id' => 0
      ]);

      return response()->json(['code' => 200]);
    }

    public function store(Request $request, $id)
    {
        $comp_id = Employee::where('id', auth()->user()->id)->select('company_id')->first();
        $notifications = new Notification();
        $notifications->title = $request->title;
        $notifications->message = $request->message;
        $notifications->user_id = auth()->user()->id;
        $notifications->receiver_role_user_id = 1;
        $notifications->leave_id = $id;
        $notifications->company_id = $comp_id->company_id;
        $notifications->save();

        return response()->json($notifications, 200);
    }


    public function get_notifications()
    {
        $user_auth = auth()->user();
        if ($user_auth->role_users_id == 1) {
            $notifications = Notification::where('is_superadmin', 1)
            ->select('title', 'message', 'created_at')
            ->where('is_seen', 0)
            ->where('user_id', '<>', $user_auth->id)
            ->get();
            return response()->json($notifications);
        }else{
            $get_all_notifications = null;
            $comp_id = Employee::where('id', auth()->user()->id)->select('company_id')->first();
    
            
            if ($user_auth->role_users_id == 4 || $user_auth->role_users_id == 1) {
                $get_all_notifications = Notification::where('is_seen', 0)
                    ->where('user_id', '<>', $user_auth->id)
                    ->where('company_id', $comp_id->company_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } elseif ($user_auth->role_users_id == 2) {
                $get_all_notifications = Notification::where('is_seen', 0)
                    ->where('user_id', '<>', $user_auth->id)
                    ->where('company_id', $comp_id->company_id)
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
            return response()->json($get_all_notifications);
        }
        
    }

    public function notifications_seen($id)
    {
        if (auth()->user()->role_users_id == 1) {
            $notifications = Notification::where('user_id', '<>', auth()->user()->id)
                ->where('is_superadmin', 1)
                ->update(['is_seen' => 1]);
                return response()->json($notifications);
            } else {
            $comp_id = Employee::where('id', auth()->user()->id)->select('company_id')->first();
            $notifications = Notification::where('company_id', $comp_id->company_id)
                ->where('leave_id', $id)
                ->where('user_id', '<>', auth()->user()->id)
                ->update(['is_seen' => 1]);
            return response()->json($notifications);
        }
        

    }

    public function send_notification(Request $request){

        if (auth()->user()->role_users_id == 1) {
            $request->validate([
                'title' => 'required|string',
                'message' => 'required|string',
            ]);

            $notifications = new Notification();
            $notifications->title = $request->title;
            $notifications->message = $request->message;
            $notifications->user_id = auth()->user()->id;
            $notifications->is_superadmin = 1;
            $notifications->save();
            $url = config('app.superadmin_url') . '/api/get_clients_notifications';
                
            $post = Http::post($url, [
                'title' => $notifications->title,
                'message' => $notifications->message,
                'company_id' => auth()->user()->client_id
            ]);
            
            return response()->json(['code' => 200, 'status' => 'Success']);
        }else {
            return response()->json(['code' => 403]);
        }
    } 
}
