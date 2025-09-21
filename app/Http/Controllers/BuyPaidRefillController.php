<?php

namespace App\Http\Controllers\Refill;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class BuyPaidRefillController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        $provider = $user->country === 'BR' ? 'abacatepay' : 'stripe';
        $currency = $provider === 'abacatepay' ? 'BRL' : 'USD';
        $amount = 2.00;

        $payment = Payment::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
        ]);

        if ($provider === 'stripe') {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower($currency),
                        'product_data' => ['name' => 'Paid Refill'],
                        'unit_amount' => $amount * 100,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('dashboard') . '?payment_success=1',
                'cancel_url' => route('dashboard') . '?payment_canceled=1',
            ]);

            $payment->transaction_id = $session->id;
            $payment->save();

            return redirect($session->url);
        }

        // AbacatePay fictício
        $checkoutUrl = "https://abacatepay.com/checkout?amount={$amount}&currency={$currency}&ref={$payment->id}";
        $payment->transaction_id = uniqid('abp_');
        $payment->save();

        return redirect($checkoutUrl);
    }
}
