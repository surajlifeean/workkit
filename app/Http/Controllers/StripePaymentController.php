<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stripe;
use Illuminate\View\View;


class StripePaymentController extends Controller
{

    public function stripeCheckout(Request $request)
    {
        
        // $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $stripe = new \Stripe\StripeClient(['api_key' => config('services.stripe.secret')]);

        // dd($request);
        // 'payment_method_types' => ['link','card'],

        $request->session()->put('checkout_parameters', $request->all());

        $redirectUrl = route('stripe.checkout.success') . '?session_id={CHECKOUT_SESSION_ID}';

        $response = $stripe->checkout->sessions->create([
            'success_url' => $redirectUrl,
            'cancel_url' => route('stripe.checkout.cancel'),
            // 'customer_email' => 'demo@gmail.com',

            'payment_method_types' => ['card'],

            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => $request->product,
                        ],
                        'unit_amount' => ( $request->currency == 'XAF' || $request->currency == 'XOF' ? $request->price : 100 * $request->price ),
                        'currency' => $request->currency,
                    ],
                    'quantity' => 1
                ],
            ],

            'mode' => 'payment',
            'allow_promotion_codes' => true,
        ]);
        // dd('uiuyy');
        return redirect($response['url']);
    }

    public function stripeCheckoutSuccess(Request $request)
    {
        $checkoutParameters = $request->session()->get('checkout_parameters');
        // dd($checkoutParameters);
        $stripe = new \Stripe\StripeClient(['api_key' => config('services.stripe.secret')]);

        $response = $stripe->checkout->sessions->retrieve($request->session_id);
        $url = config('app.superadmin_url') . '/api/subscription-plans';

        // dd($response);
        $post = Http::post($url, [
            'company_id' => auth()->user()->client_id,
            'subscription_plan_data' => $response,
            'is_offer_price' => $checkoutParameters['is_offer_price'],
            'subs_plan_id' => $checkoutParameters['plan_id'],
        ]);

        if($post->status() === 200){
            $request->session()->forget('checkout_parameters');
        }

        return redirect()->route('subscription.index')
            ->with('success', 'Payment successful.');
    }

    public function stripeCheckoutCancel()
    {
        return redirect()->route('subscription.index')
            ->with('error', 'Payment Cancelled.');
    }
}
