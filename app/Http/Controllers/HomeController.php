<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EscortCategory;
use App\Models\User;
use App\Models\Billing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = EscortCategory::withCount('escorts')->get();
        $escorts = User::where('role', 'escort')
            ->with('usermeta')
            ->paginate(9);
        return view('homepage', compact('categories', 'escorts'));
    }

    public function loadMoreEscorts(Request $request)
    {
        if ($request->ajax()) {
            $page = $request->get('page', 2);
            $escorts = User::where('role', 'escort')
                ->with('usermeta')
                ->paginate(9, ['*'], 'page', $page);

            if ($escorts->count() > 0) {
                $html = '';

                foreach ($escorts as $escort) {
                    $photo = $escort->usermeta->where('meta_key', 'profile_picture')->first()->meta_value ?? 'default.jpg';
                    $city = $escort->usermeta->where('meta_key', 'city')->first()->meta_value ?? '';
                    $location = $escort->usermeta->where('meta_key', 'country_code')->first()->meta_value ?? 'N/A';
                    $price = $escort->usermeta->where('meta_key', 'subscription_price')->first()->meta_value ?? 'N/A';
                    $package = $escort->usermeta->where('meta_key', 'package')->first()->meta_value ?? 'Basic Package';

                    $html .= '<div class="col-md-4 col-6 mb-4 escort-card-item">
                        <div class="card position-relative overflow-hidden escort-card" 
                             data-escort-id="' . $escort->id . '" 
                             data-name="' . $escort->name . '" 
                             data-price="$' . $price . '" 
                             data-package="' . $package . '">
                            <div class="py-3">
                                <h6 class="text-center text-main">' . $escort->name . '</h6>
                            </div>
                            <div class="image_container position-relative">
                                <img src="' . asset('storage/' . $photo) . '" alt="' . $escort->name . ' Photo" class="img-fluid" />
                                <div class="tags">
                                    <span class="tag-1 position-relative">
                                        <img src="' . asset('images/VIP.png') . '" alt="VIP Tag" />
                                    </span>
                                    <span class="tag-2 position-relative">
                                        <img src="' . asset('images/Independent.png') . '" alt="Independent Tag" />
                                    </span>
                                    <span class="tag-3 position-relative">
                                        <img src="' . asset('images/video.png') . '" alt="Video Tag" />
                                    </span>
                                </div>
                            </div>
                            <div class="image_footer py-2">
                                <p class="text-center">Escort ' . $city . '</p>
                            </div>
                            <div class="onOverlay position-absolute d-flex flex-column justify-content-center align-items-center">
                                <div class="text-white fs-6 fw-normal font-family-Inter text-uppercase m-0 px-3 py-2">
                                    <p class="m-0 text-center">' . strtoupper($escort->name) . '</p>
                                    <h4 class="m-0 text-center mt-1">$' . $price . '</h4>
                                </div>
                                <div class="col-8 mx-auto">
                                    <p class="text-center text-white">
                                        <span class="me-2"><i class="fa-solid fa-location-dot"></i></span>
                                        ' . $location . '
                                    </p>
                                    <p class="text-center text-white mt-2">
                                        <span class="me-2"><i class="fa-solid fa-circle text-success"></i></span>
                                        Available
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>';
                }

                return response()->json([
                    'success' => true,
                    'html' => $html,
                    'hasMorePages' => $escorts->hasMorePages(),
                    'currentPage' => $escorts->currentPage(),
                    'totalPages' => $escorts->lastPage()
                ]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function processBilling(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'zip_code' => 'required|string',
                'country' => 'required|string',
                'escort_id' => 'required|integer',
            ]);

            $billing = Billing::create([
                'user_id' => auth('fan')->id(),
                'escort_id' => $request->escort_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'status' => 'pending',
            ]);

            $escort = User::find($request->escort_id);
            $amount = (float) ($escort->usermeta->where('meta_key', 'subscription_price')->first()->meta_value ?? 0);

            $btcpayUrl = rtrim(config('btcpay.host'), '/');
            $storeId = config('btcpay.store_id');
            $apiKey = config('btcpay.api_key');

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$btcpayUrl}/api/v1/stores/{$storeId}/invoices", [
                        // Interpret subscription_price as BTC amount per your requirement (e.g., 0.001)
                        'amount' => (float) $amount,
                        'currency' => 'BTC',
                        'metadata' => [
                            'orderId' => (string) $billing->id,
                            'customerEmail' => auth('fan')->user()->email,
                        ],
                        'checkout' => [
                            'redirectURL' => route('payment.result') . '?invoiceId={InvoiceId}',
                            'redirectAutomatically' => true,
                        ],

                        'notificationUrl' => route('btcpay.webhook'),
                    ]);

            if ($response->successful()) {
                $invoice = $response->json();
                $billing->update(['invoice_id' => $invoice['id'] ?? null]);
                $redirectUrl = $invoice['checkoutLink'] ?? null;
                if ($redirectUrl) {
                    return response()->json(['success' => true, 'message' => 'Redirecting to payment', 'url' => $redirectUrl]);
                }
                return response()->json(['success' => false, 'message' => 'No checkout link received from BTCPay'], 400);
            }

            return response()->json(['success' => false, 'message' => 'Invoice creation failed: ' . $response->body()], 400);
        } catch (\Exception $e) {
            \Log::error('Billing Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }

    public function showPaymentPage($invoice_id)
    {
        // Optional: If you still want to render a local payment page using invoice details,
        // you can fetch invoice via BTCPay API. Otherwise you can skip this route.
        $btcpayUrl = rtrim(config('btcpay.host'), '/');
        $storeId = config('btcpay.store_id');
        $apiKey = config('btcpay.api_key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->get("{$btcpayUrl}/api/v1/stores/{$storeId}/invoices/{$invoice_id}");

        $invoice = (object) ($response->successful() ? $response->json() : []);
        $paymentMethods = $invoice->paymentMethods ?? [];

        $qrImages = [];
        $details = [];

        foreach ($paymentMethods as $method => $pmDetails) {
            if (isset($pmDetails->paymentLink)) {
                $qrCode = QrCode::create($pmDetails->paymentLink)
                    ->setSize(250)
                    ->setMargin(10);
                $writer = new PngWriter();
                $qrImages[$method] = base64_encode($writer->write($qrCode)->getString());

                $details[$method] = [
                    'amount' => $pmDetails->amount,
                    'destination' => $pmDetails->destination,
                    'paymentLink' => $pmDetails->paymentLink,
                ];
            }
        }

        return view('payment', compact('invoice', 'qrImages', 'details'));
    }

    public function handleWebhook(Request $request)
    {
        $secret = env('BTCPAY_WEBHOOK_SECRET');
        $signature = $request->header('BTCPay-Sig');
        $expectedSig = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);

        if ($signature !== $expectedSig) {
            return response('Invalid signature', 403);
        }

        $payload = json_decode($request->getContent(), true);
        if ($payload['type'] === 'InvoiceSettled') {
            $billing = Billing::where('invoice_id', $payload['invoiceId'])->first();
            if ($billing) {
                $billing->update(['status' => 'paid']);
            }
        }

        return response('OK', 200);
    }

    public function paymentResult(Request $request)
    {
        $invoiceId = $request->query('invoiceId');
        if (!$invoiceId) {
            return redirect()->route('payment.failed'); // agar id missing hai
        }

        $btcpayUrl = rtrim(config('btcpay.host'), '/');
        $storeId = config('btcpay.store_id');
        $apiKey = config('btcpay.api_key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->get("{$btcpayUrl}/api/v1/stores/{$storeId}/invoices/{$invoiceId}");

        if (!$response->successful()) {
            return redirect()->route('payment.failed');
        }

        $invoice = $response->json();
        $status = $invoice['status'] ?? null;

        if ($status === 'Settled') {
            return redirect()->route('payment.success');
        } else {
            return redirect()->route('payment.failed');
        }
    }

}