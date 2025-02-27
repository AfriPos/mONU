<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\invoiceModel;
use App\Models\paymentModel;
use Illuminate\Http\Request;
use App\Services\PayPalService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    protected $paypalService;

    public function __construct(PayPalService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    // Create a PayPal Order
    public function createOrder(Request $request)
    {
        $amount = floatval($request->amount); // Ensure it's a valid float

        if ($amount <= 0) {
            return response()->json(['error' => 'Amount must be greater than zero'], 400);
        }

        $order = $this->paypalService->createOrder($amount);

        return response()->json($order);
    }


    // Capture PayPal Order (Complete Payment)
    public function captureOrder($orderId)
    {
        $result = $this->paypalService->captureOrder($orderId);

        if ($result['status'] === "COMPLETED") {
            // Get the amount paid from PayPal
            $amountPaid = floatval($result['purchase_units'][0]['payments']['captures'][0]['amount']['value']);

            // Get organization ID
            $credit = Credit::first();

            // Add any remaining balance to the new amount paid
            $totalAmount = $amountPaid + $credit->account_balance;

            // Calculate how many full credits can be purchased
            $credits = floor($totalAmount / 0.77);

            // Calculate the new remaining balance
            $remainingBalance = round($totalAmount - ($credits * 0.77), 2);

            // Create invoice record
            $invoice = invoiceModel::create([
                'invoice_number' => 'INV-' . time(),
                'amount' => $amountPaid,
                'status' => 'paid',
            ]);

            // Create payment record
            $payment = paymentModel::create([
                'invoice_id' => $invoice->id,
                'amount' => $amountPaid,
                'payment_method' => 'paypal',
                'transaction_id' => $orderId,
                'status' => 'completed',
                'payment_date' => now(),
            ]);

            // Update credits and balance_remaining
            Credit::first()->update([
                'balance' => DB::raw("balance + {$credits}"),
                'account_balance' => $remainingBalance
            ]);

            Log::info('Payment successful', [
                'amountPaid' => $amountPaid,
                'credits' => $credits,
                'remainingBalance' => $remainingBalance
            ]);
        }

        return response()->json($result);
    }
}
