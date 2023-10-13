<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubscriptionController extends Controller
{
   public function index(){
      $response = Http::get('http://localhost/Alsol/workkitsuperadmin/api/subscription-plans');
      // dd($response);
      $data = $response->json();
      // dd($data);
      $data = $data['data'];
    return view('subscription.subscription', compact('data'));
   }
   
}
