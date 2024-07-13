<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Webhook;

class StripeController extends Controller
{
    public function stripeWebhook(Request $request): JsonResponse
    {
        try {
            Log::info("Stripe webhook request", ["body" => $request->toArray(), "headers" => $request->headers]);
            $stripeClient = new StripeClient(env("STRIPE_SECRET"));
            $endpoint_secret = 'whsec_828e9427c5457a2f318199dc6ccb3a3751519ad64d3a717aa5c2524df78a1570';
            $payload = @file_get_contents('php://input');
            dd($payload);
            $sig_header = $request->headers->get("HTTP_STRIPE_SIGNATURE");
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            $eventData = null;
            switch ($event->type) {
                case "payment_intent.succeeded":
                    $paymentIntent = $event->data->object;
                    $eventData = $paymentIntent;
                    break;
                default:
                    $eventData = [];
                    break;
            }
            Log::info("STRIPE_EVENT_DATA", $eventData);
            return response()->json($eventData, 200);
        } catch (\Throwable $e) {
            Log::error("STRIPE_ERROR" . $e->getMessage());
            return response()->json(["message" => "Internal Server Error"]);
        }
    }
}
