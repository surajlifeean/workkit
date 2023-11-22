<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    

    public function authenticate(Request $request)
    {
        // dd($request);
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
       
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            
            $user_Auth = Auth::User();
            // \Log::info('User Role ID: ' . $user_Auth->role_users_id);

                if($user_Auth->role_users_id == 1){
                    return redirect('/dashboard/admin');
                }elseif($user_Auth->role_users_id == 3){
                    return redirect('/dashboard/client');
                }elseif($user_Auth->role_users_id == 4){
                    return redirect('/dashboard/hr');
                }elseif($user_Auth->role_users_id == 2){
                    return redirect('/dashboard/employee');
                }elseif($user_Auth->role_users_id > 4 ){
                    return redirect('/dashboard/others');
                }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
