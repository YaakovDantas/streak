<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Refill;
use App\Models\UserRefillBalance;
use Illuminate\Http\Request;

class WebhookStripeController extends Controller
{
    public function __invoke(Request $request)
    {
        $transactionId = $request->input('data.object.id');
        $payment = Payment::where('transaction_id', $transactionId)->first();

        if ($payment && $payment->status === 'pending') {
            $payment->update(['status' => 'paid']);
            $this->addPaidRefill($payment->user_id);
        }

        return response()->json(['ok' => true]);
    }

    private function addPaidRefill($userId)
    {
        return UserRefillBalance::firstOrCreate(
            ['user_id' => $userId],
            ['free' => 0, 'ad' => 0, 'paid' => 1]
        );
        Refill::create([
            'user_id' => $userId,
            'type' => 'paid',
            'refilled_for' => now()->toDateString(),
        ]);
    }
}
