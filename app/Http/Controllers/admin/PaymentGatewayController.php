<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class PaymentGatewayController extends Controller
{
    public function btcPayPage()
    {
        return view('admin.payment-btcpay');
    }

    public function createInvoice()
    {
        $btcpayConfig = Config::get('btcpay');
        $btcpayUrl = rtrim($btcpayConfig['url'], '/');
        $storeId = $btcpayConfig['store_id'];
        $apiKey = $btcpayConfig['api_key'];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post("$btcpayUrl/api/v1/stores/$storeId/invoices", [
                    "amount" => 20,
                    "currency" => "USD",
                    "metadata" => [
                        "orderId" => uniqid(),
                        "customerEmail" => "test@example.com",
                    ],
                    "checkout" => [
                        "redirectURL" => route('payment.success'),
                    ]
                ]);


        if ($response->successful()) {
            $invoice = $response->json();
            if (isset($invoice['checkoutLink'])) {
                return redirect()->away($invoice['checkoutLink']);
            } else {
                Log::error("No checkoutLink found:", $invoice);
                return redirect()->back()->withErrors(['error' => 'No checkout link received.']);
            }
        } else {
            Log::error("API Failed: " . $response->status() . " - " . $response->body());
            return redirect()->back()->withErrors(['error' => 'API failed: ' . $response->body()]);
        }
    }

    public function paymentSuccess()
    {
        return view('admin.payment-success')->with('message', '✅ Payment Successful! BTC Pay se confirm ho gaya.');
    }

    public function paymentCancel()
    {
        return view('admin.payment-cancel')->with('message', '❌ Payment Cancelled!');
    }
}
