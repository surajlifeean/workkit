<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class NotificationController extends Controller
{
    public function index($id){
        try {
            if (Auth::check()) {
                $user_id = auth()->user()->id;
                $notifications = Notification::where(function($query) use ($user_id) {
                    $query->where('user_id', $user_id)
                          ->orWhere('receiver_user_id', $user_id);
                })
                ->where('leave_id', $id)
                ->get();
    
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

    public function store(Request $request, $id){
        $notifications = new Notification();
        $notifications->title = $request->title;
        $notifications->message = $request->message;
        $notifications->user_id = auth()->user()->id;
        $notifications->receiver_role_user_id = 1;
        $notifications->leave_id = $id;
        $notifications->save();
    
        return response()->json($notifications, 200); 
    }
    

    public function sendMessage($id, $reciver_id){

    }
}
