<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Credit;
use App\Models\Organization;

class MpesaController extends Controller
{
    // Initiate STK Push
    public function stkPush(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'amount' => 'required|integer|min:1',
            'organization_id' => 'required|exists:organizations,id',
        ]);

        $phone = $this->formatPhoneNumber($request->phone);
        $amount = $request->amount;
        $organizationId = $request->organization_id;

        $accessToken = $this->getAccessToken();
        $timestamp = date('YmdHis');
        $password = base64_encode(env('MPESA_SHORTCODE') . env('MPESA_PASSKEY') . $timestamp);

        $response = Http::withToken($accessToken)->post('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', [
            'BusinessShortCode' => env('MPESA_SHORTCODE'),
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => env('MPESA_SHORTCODE'),
            'PhoneNumber' => $phone,
            'CallBackURL' => env('MPESA_CALLBACK_URL'),
            'AccountReference' => 'Credit Purchase',
            'TransactionDesc' => 'Buying system credits',
        ]);

        return response()->json($response->json());
    }

    // Handle M-Pesa Callback
    public function callback(Request $request)
    {
        $data = $request->all();
        \Log::info('M-Pesa Callback: ', $data);

        if (isset($data['Body']['stkCallback']['ResultCode']) && $data['Body']['stkCallback']['ResultCode'] == 0) {
            $amount = $data['Body']['stkCallback']['CallbackMetadata']['Item'][0]['Value'];
            $phone = $data['Body']['stkCallback']['CallbackMetadata']['Item'][4]['Value'];

            // Fetch the organization
            $organization = Organization::whereHas('users', function ($query) use ($phone) {
                $query->where('phone', $phone);
            })->first();

            if ($organization) {
                $organization->credit->increment('balance', $amount);
            }
        }
    }

    // Get M-Pesa Access Token
    private function getAccessToken()
    {
        $response = Http::withBasicAuth(env('MPESA_CONSUMER_KEY'), env('MPESA_CONSUMER_SECRET'))
            ->get('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');

        return $response->json()['access_token'];
    }

    // Format Phone Number to 2547XXXXXXXX
    private function formatPhoneNumber($phone)
    {
        if (substr($phone, 0, 1) == "0") {
            return "254" . substr($phone, 1);
        }
        return $phone;
    }
}
