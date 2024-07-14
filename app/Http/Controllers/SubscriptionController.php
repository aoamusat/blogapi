<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        try {
            $request->validate([
                'payment_method' => 'required|string',
            ]);

            $user = Auth::user();
            $paymentMethod = $request->payment_method;

            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $priceId = env("STRIPE_PRICE_ID");

            $user->newSubscription('default', $priceId)->create($paymentMethod);

            return response()->json(['message' => 'Subscription successful'], 200);
        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "Subscription failed. Internal server error"]);
        }
    }

    public function checkSubscription()
    {
        $user = Auth::user();

        return response()->json([
            'subscribed' => $user->subscribed('default')
        ]);
    }
}
