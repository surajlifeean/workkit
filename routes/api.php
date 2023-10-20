<?php

// use App\Http\Controllers\ActivePlanController;

// use App\Http\Controllers\ActivePlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("receive_notification_superadmin", "NotificationController@store_from_superadmin");

Route::resource('active-plans', 'ActivePlanController');

// Route::get("users/get_users_data", "UserController@Get_users_data");
