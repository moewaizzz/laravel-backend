<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe;


class StripeController extends Controller
{
    public function index(){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
              # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
              'price' => '{{PRICE_ID}}',
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '?success=true',
            'cancel_url' => $YOUR_DOMAIN . '?canceled=true',
          ]); 

}

}
