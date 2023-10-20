<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubscriptionController extends Controller
{
   public function index(){
      // $url = env('SUPERADMIN_URL') .'/api/subscription-plans';

      $url = config('app.superadmin_url') . '/api/subscription-plans';

      $response = Http::get($url);
      // dd($response);
      $data = $response->json();
      // dd($data);
      $data = $data['data'];
    return view('subscription.subscription', compact('data'));
   }
   
}
