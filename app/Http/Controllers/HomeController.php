<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
=======

>>>>>>> 23c30d7 (Escort project)
use Illuminate\Http\Request;
use App\Models\EscortCategory;
use App\Models\User;
use App\Models\Billing;
use App\Models\Ads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
<<<<<<< HEAD
=======
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\CountryFlag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\SubscriptionPlans;
use App\Services\SubscriptionService;

>>>>>>> 23c30d7 (Escort project)

class HomeController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        $categories = EscortCategory::withCount('escorts')->get();
=======
        // Fetch top 16 categories with escorts count
        $categories = EscortCategory::withCount('escorts')
            ->orderBy('escorts_count', 'desc')
            ->limit(16)
            ->get();

>>>>>>> 23c30d7 (Escort project)
        $escorts = User::where('role', 'escort')
            ->with('usermeta')
            ->latest()
            ->paginate(9);

<<<<<<< HEAD
         // Topbar Ads
        $topbarAds = Ads::where('position', 'topbar')->latest()->get();

        // Sidebar Ads
        $sidebarAds = Ads::where('position', 'sidebar')->latest()->get();


        return view('homepage', compact('categories', 'escorts', 'topbarAds', 'sidebarAds'));

=======
        $topCategories = EscortCategory::withCount('escorts')
            ->orderBy('escorts_count', 'desc')
            ->limit(8)
            ->get();

        $countries = Country::all();

        // Fetch top 16 countries with iso2, including those with no users
        $popularCountries = Country::leftJoin('country_flags', 'countries.id', '=', 'country_flags.country_id')
            ->select('countries.id', 'countries.name', 'countries.iso2', DB::raw('COALESCE(COUNT(DISTINCT usermeta.user_id), 0) as escorts_count'))
            ->leftJoin('usermeta', function ($join) {
                $join->on('countries.id', '=', 'usermeta.meta_value')
                    ->where('usermeta.meta_key', 'country');
            })
            ->whereNotNull('countries.iso2')
            ->groupBy('countries.id', 'countries.name', 'countries.iso2')
            ->orderByDesc('escorts_count')
            ->limit(16)
            ->get();

        $topbarAds = Ads::where('position', 'topbar')->latest()->get();
        $sidebarAds = Ads::where('position', 'sidebar')->latest()->get();

        return view('homepage', compact('categories', 'topCategories', 'escorts', 'topbarAds', 'sidebarAds', 'countries', 'popularCountries'));
>>>>>>> 23c30d7 (Escort project)
    }

    public function loadMoreEscorts(Request $request)
    {
        if ($request->ajax()) {
            $page = $request->get('page', 2);
<<<<<<< HEAD
            $escorts = User::where('role', 'escort')
                ->with('usermeta')
=======
            $countryId = $request->input('country_id', null);
            $stateId = $request->input('state_id', null);
            $cityId = $request->input('city_id', null);

            $escorts = User::where('role', 'escort')
                ->with('usermeta')
                ->when($countryId, function ($query) use ($countryId) {
                    return $query->whereHas('usermeta', function ($q) use ($countryId) {
                        $q->where('meta_key', 'country')->where('meta_value', $countryId);
                    });
                })
                ->when($stateId, function ($query) use ($stateId) {
                    return $query->whereHas('usermeta', function ($q) use ($stateId) {
                        $q->where('meta_key', 'state')->where('meta_value', $stateId);
                    });
                })
                ->when($cityId, function ($query) use ($cityId) {
                    return $query->whereHas('usermeta', function ($q) use ($cityId) {
                        $q->where('meta_key', 'city')->where('meta_value', $cityId);
                    });
                })
                ->latest()
>>>>>>> 23c30d7 (Escort project)
                ->paginate(9, ['*'], 'page', $page);

            if ($escorts->count() > 0) {
                $html = '';

                foreach ($escorts as $escort) {
                    $photo = $escort->usermeta->where('meta_key', 'profile_picture')->first()->meta_value ?? 'default.jpg';
                    $city = $escort->usermeta->where('meta_key', 'city')->first()->meta_value ?? '';
                    $location = $escort->usermeta->where('meta_key', 'country_code')->first()->meta_value ?? 'N/A';
                    $price = $escort->usermeta->where('meta_key', 'subscription_price')->first()->meta_value ?? 'N/A';
                    $package = $escort->usermeta->where('meta_key', 'package')->first()->meta_value ?? 'Basic Package';

<<<<<<< HEAD
                    $html .= '<div class="col-md-4 col-6 mb-4 escort-card-item">
=======
                    $html .= '<div class="col-md-4 col-6 mb-4 escort-card-item" data-country="' . ($escort->usermeta->where('meta_key', 'country_id')->first()->meta_value ?? '') . '">
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD
                    'totalPages' => $escorts->lastPage()
=======
                    'totalPages' => $escorts->lastPage(),
>>>>>>> 23c30d7 (Escort project)
                ]);
            }
        }

<<<<<<< HEAD
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
=======
        return response()->json(['success' => false, 'html' => '', 'hasMorePages' => false]);
    }

    public function createInvoice(Request $request)
    {
        $validated = $request->validate([
            'escort_id' => 'required|exists:users,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'country' => 'required|string',
        ]);

        $escort = User::findOrFail($validated['escort_id']);
        $price = $escort->usermeta->where('meta_key', 'subscription_price')->first()->meta_value ?? 50;

>>>>>>> 23c30d7 (Escort project)
        $btcpayUrl = rtrim(config('btcpay.host'), '/');
        $storeId = config('btcpay.store_id');
        $apiKey = config('btcpay.api_key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
<<<<<<< HEAD
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

=======
        ])->post("{$btcpayUrl}/api/v1/stores/{$storeId}/invoices", [
                    'amount' => $price,
                    'currency' => 'USD',
                    'buyerName' => $validated['first_name'] . ' ' . $validated['last_name'],
                    'buyerAddress1' => $validated['address'],
                    'buyerCity' => $validated['city'],
                    'buyerState' => $validated['state'],
                    'buyerZip' => $validated['zip_code'],
                    'buyerCountry' => $validated['country'],
                ]);

        if ($response->successful()) {
            $invoice = $response->json();
            $billing = Billing::create([
                'user_id' => Auth::id(),
                'fan_id' => Auth::id(),
                'escort_id' => $validated['escort_id'],
                'invoice_id' => $invoice['id'],
                'amount' => $price,
                'status' => 'pending',
            ]);

            return response()->json(['success' => true, 'invoiceUrl' => $invoice['checkoutLink']]);
        }

        return response()->json(['success' => false, 'message' => 'Failed to create invoice'], 500);
    }

    public function processBilling(Request $request)
    {
        $validated = $request->validate([
            'escort_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:subscription_plans,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'country' => 'required|string',
        ]);

        $userId = Auth::guard('fan')->id();
        $subscriptionService = new SubscriptionService();

        $result = $subscriptionService->processSubscriptionChange(
            $userId,
            $validated['plan_id'],
            $validated['escort_id'],
            $validated
        );

        $billing = $result['billing'];
        $amount = (float) ($billing->amount ?? 0);

        if ($amount < 0) {
            $amount = 0.00;
        }

        if ($result['type'] === 'downgrade' && $amount == 0) {
            return back()->with('success', $result['message']);
        }

        $btcpayUrl = rtrim(config('btcpay.host'), '/');
        $storeId = config('btcpay.store_id');
        $apiKey = config('btcpay.api_key');

        try {
            $payload = [
                'amount' => round((float) $amount, 2),
                'currency' => 'USD',
                'buyerName' => $validated['first_name'] . ' ' . $validated['last_name'],
                'buyerAddress1' => $validated['address'],
                'buyerCity' => $validated['city'],
                'buyerState' => $validated['state'],
                'buyerZip' => $validated['zip_code'],
                'buyerCountry' => $validated['country'],
                'paymentMethods' => ['BTC-LightningNetwork'],
                'expirationTime' => now()->addMinutes(5)->toIso8601String(),
                'redirectURL' => route('btcpay.redirect'), // Smart redirect page
                'notificationURL' => route('btcpay.webhook'), // Webhook URL for status updates
                'checkout' => [
                    'redirectAutomatically' => true,
                    'redirectURL' => route('btcpay.redirect'),
                    'defaultLanguage' => 'en'
                ],
                'metadata' => [
                    'customScript' => url('/btcpay-auto-redirect.js'),
                    'redirectUrl' => route('payment.processing')
                ]
            ];

            Log::info('BTCPay create invoice request', [
                'store_id' => $storeId,
                'payload' => $payload,
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post("{$btcpayUrl}/api/v1/stores/{$storeId}/invoices", $payload);

            Log::info('BTCPay create invoice response', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            if ($response->successful()) {
                $invoice = $response->json();
                $invoiceId = $invoice['id'] ?? null;
                $checkout = $invoice['checkoutLink'] ?? null;

                if (!$invoiceId || !$checkout) {
                    Log::warning('BTCPay invoice missing fields', ['invoice' => $invoice]);
                    return redirect()->route('payment.failed', ['escort_id' => $validated['escort_id']])
                        ->withErrors(['message' => 'Invoice bana, par checkout link missing hai.']);
                }

                $billing->update([
                    'invoice_id' => $invoiceId,
                    'expires_at' => now()->addMinutes(5),
                ]);

                // Store invoice ID in session for later status check
                session(['current_invoice_id' => $invoiceId]);

                // Redirect to BTCPay checkout
                return redirect($checkout);
            }

            return redirect()->route('payment.failed', ['escort_id' => $validated['escort_id']])
                ->withErrors(['message' => 'Invoice create nahi hui. ' . ($response->json()['message'] ?? 'BTCPay error')]);
        } catch (\Throwable $e) {
            Log::error('BTCPay invoice exception', ['error' => $e->getMessage()]);
            return redirect()->route('payment.failed', ['escort_id' => $validated['escort_id']])
                ->withErrors(['message' => 'Exception: ' . $e->getMessage()]);
        }
    }

    public function choosePlan($escortId)
    {
        $escort = User::findOrFail($escortId);
        $plans = SubscriptionPlans::orderBy('price')->get();

        // Get current subscription status
        $subscriptionService = new SubscriptionService();
        $currentSubscription = $subscriptionService->getSubscriptionStatus(
            Auth::guard('fan')->id(),
            $escortId
        );

        return view('choose-plan', compact('escort', 'plans', 'currentSubscription'));
    }

    public function showPaymentPage($invoiceId)
    {
>>>>>>> 23c30d7 (Escort project)
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
<<<<<<< HEAD
        $status = $invoice['status'] ?? null;

        if ($status === 'Settled') {
            return redirect()->route('payment.success');
        } else {
            return redirect()->route('payment.failed');
        }
    }

    public function userSupport(){
        return view('support');
    }

=======

        $pmDetails = $invoice['checkoutLink'] ?? null;
        $expiresAt = $invoice['expirationTime'] ?? null; // ISO8601

        $qrImages = [];

        return view('payment', compact('invoice', 'qrImages', 'pmDetails', 'expiresAt'));
    }


    public function handleWebhook(Request $request)
    {
        // Log all webhook data for debugging
        Log::info('BTCPay Webhook received', [
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'raw_body' => $request->getContent(),
            'time' => now()->toDateTimeString()
        ]);

        // Verify webhook signature if secret is configured
        $webhookSecret = config('btcpay.webhook_secret');
        if ($webhookSecret) {
            $signature = $request->header('BTCPay-Sig');
            $payload = $request->getContent();
            $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $webhookSecret);
            
            if (!hash_equals($expectedSignature, $signature ?? '')) {
                Log::warning('Webhook signature verification failed', [
                    'expected' => $expectedSignature,
                    'received' => $signature
                ]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
            }
            Log::info('✅ Webhook signature verified');
        }

        // BTCPay sends different formats, handle both
        $invoiceId = $request->input('id') ?? $request->input('invoiceId');
        $status = $request->input('status') ?? $request->input('type');

        if (!$invoiceId) {
            Log::warning('Webhook missing invoice ID', ['request_data' => $request->all()]);
            return response()->json(['status' => 'error', 'message' => 'Missing invoice ID'], 400);
        }

        $billing = Billing::where('invoice_id', $invoiceId)->first();

        if (!$billing) {
            Log::warning('No billing found for invoice', ['invoice_id' => $invoiceId]);
            return response()->json(['status' => 'error', 'message' => 'Billing not found'], 404);
        }

        Log::info('Processing webhook', ['invoice_id' => $invoiceId, 'status' => $status, 'billing_id' => $billing->id]);

        // Handle different BTCPay status formats
        $successStatuses = ['confirmed', 'settled', 'complete', 'Settled', 'Complete', 'Confirmed'];
        $failedStatuses = ['expired', 'invalid', 'Expired', 'Invalid'];

        if (in_array($status, $successStatuses)) {
            $billing->update([
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($billing->plan->duration_days ?? 30),
            ]);
            Log::info('✅ Payment successful - Database updated', ['invoice_id' => $invoiceId, 'billing_id' => $billing->id]);
        } elseif (in_array($status, $failedStatuses)) {
            $billing->update(['status' => 'failed']);
            Log::info('❌ Payment failed/expired - Database updated', ['invoice_id' => $invoiceId, 'status' => $status]);
        } else {
            Log::info('⏳ Payment status pending', ['invoice_id' => $invoiceId, 'status' => $status]);
        }

        return response()->json(['status' => 'ok', 'message' => 'Webhook processed']);
    }

    private function activateSubscription($billing)
    {
        $subscriptionService = new SubscriptionService();

        // Set expiry date if not set
        if (!$billing->expires_at) {
            $billing->update([
                'expires_at' => now()->addDays($billing->plan->duration_days)
            ]);
        }

        // If it's a downgrade, schedule it for later
        if ($billing->subscription_type === 'downgrade' && $billing->starts_at) {
            // Schedule downgrade for later
            return;
        }

        // For upgrades, renewals, and new subscriptions, activate immediately
        if (in_array($billing->subscription_type, ['upgrade', 'renewal', 'new'])) {
            // Cancel any existing active subscriptions for this user-escort pair
            Billing::where('user_id', $billing->user_id)
                ->where('escort_id', $billing->escort_id)
                ->where('id', '!=', $billing->id)
                ->where('status', 'paid')
                ->update(['status' => 'cancelled']);
        }
    }

    public function paymentResult(Request $request)
    {
        $invoiceId = $request->query('invoiceId');
        if (!$invoiceId) {
            Log::warning('Payment result called without invoice ID');
            return redirect()->route('payment.failed');
        }

        $btcpayUrl = rtrim(config('btcpay.host'), '/');
        $storeId = config('btcpay.store_id');
        $apiKey = config('btcpay.api_key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->get("{$btcpayUrl}/api/v1/stores/{$storeId}/invoices/{$invoiceId}");

        if (!$response->successful()) {
            Log::error('Failed to fetch invoice from BTCPay', ['invoice_id' => $invoiceId]);
            return redirect()->route('payment.failed');
        }

        $invoice = $response->json();
        $status = $invoice['status'] ?? null;

        Log::info('Payment result check', ['invoice_id' => $invoiceId, 'status' => $status]);

        // BTCPay status mapping:
        // New -> Payment not started
        // Processing -> Payment in progress  
        // Settled -> Payment completed successfully
        // Invalid -> Payment failed
        // Expired -> Payment timed out
        
        if (in_array($status, ['Settled', 'Complete', 'Confirmed'])) {
            // Update billing status in database
            $billing = Billing::where('invoice_id', $invoiceId)->first();
            if ($billing) {
                $billing->update([
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addDays($billing->plan->duration_days ?? 30),
                ]);
            }
            
            Log::info('Payment successful, redirecting to thankyou', ['invoice_id' => $invoiceId]);
            return redirect()->route('payment.success');
        } elseif (in_array($status, ['Invalid', 'Expired'])) {
            // Update billing status as failed
            $billing = Billing::where('invoice_id', $invoiceId)->first();
            if ($billing) {
                $billing->update(['status' => 'failed']);
            }
            
            Log::info('Payment failed/expired, redirecting to failed page', ['invoice_id' => $invoiceId, 'status' => $status]);
            return redirect()->route('payment.failed');
        } else {
            // Status is New or Processing - payment still pending
            Log::info('Payment still pending', ['invoice_id' => $invoiceId, 'status' => $status]);
            return redirect()->route('payment.failed')->with('error', 'Payment is still processing or was cancelled.');
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        // Try to get invoice ID from session first, then from latest billing record
        $invoiceId = session('current_invoice_id');
        
        if (!$invoiceId) {
            // Get the latest invoice from database
            $latestBilling = Billing::orderBy('created_at', 'desc')->first();
            if ($latestBilling && $latestBilling->invoice_id) {
                $invoiceId = $latestBilling->invoice_id;
                Log::info('Using latest invoice from database', ['invoice_id' => $invoiceId]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'No invoice found in session or database']);
            }
        }

        $btcpayUrl = rtrim(config('btcpay.host'), '/');
        $storeId = config('btcpay.store_id');
        $apiKey = config('btcpay.api_key');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->get("{$btcpayUrl}/api/v1/stores/{$storeId}/invoices/{$invoiceId}");

        if (!$response->successful()) {
            return response()->json(['status' => 'error', 'message' => 'Failed to check status']);
        }

        $invoice = $response->json();
        $status = $invoice['status'] ?? null;

        Log::info('Manual payment status check', ['invoice_id' => $invoiceId, 'status' => $status]);

        if (in_array($status, ['Settled', 'Complete', 'Confirmed'])) {
            // Update billing status
            $billing = Billing::where('invoice_id', $invoiceId)->first();
            if ($billing) {
                $billing->update([
                    'status' => 'active',
                    'start_date' => now(),
                    'end_date' => now()->addDays($billing->plan->duration_days ?? 30),
                ]);
                
                // Clear session
                session()->forget('current_invoice_id');
                
                return response()->json(['status' => 'success', 'redirect' => route('payment.success')]);
            }
        } elseif (in_array($status, ['Invalid', 'Expired'])) {
            $billing = Billing::where('invoice_id', $invoiceId)->first();
            if ($billing) {
                $billing->update(['status' => 'failed']);
            }
            
            session()->forget('current_invoice_id');
            return response()->json(['status' => 'failed', 'redirect' => route('payment.failed')]);
        }

        return response()->json(['status' => 'pending', 'btcpay_status' => $status]);
    }

    public function syncPayments()
    {
        $btcpayUrl = rtrim(config('btcpay.host'), '/');
        $storeId = config('btcpay.store_id');
        $apiKey = config('btcpay.api_key');

        // Get all pending billings with invoice_id
        $pendingBillings = Billing::where('status', 'pending')
            ->whereNotNull('invoice_id')
            ->get();

        $updated = 0;
        $failed = 0;

        foreach ($pendingBillings as $billing) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$btcpayUrl}/api/v1/stores/{$storeId}/invoices/{$billing->invoice_id}");
            
            if ($response->successful()) {
                $invoice = $response->json();
                $status = $invoice['status'] ?? 'Unknown';
                
                if (in_array($status, ['Settled', 'Complete', 'Confirmed'])) {
                    $billing->update([
                        'status' => 'active',
                        'start_date' => now(),
                        'end_date' => now()->addDays($billing->plan->duration_days ?? 30),
                    ]);
                    $updated++;
                } elseif (in_array($status, ['Expired', 'Invalid'])) {
                    $billing->update(['status' => 'failed']);
                    $failed++;
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => "Sync completed: {$updated} activated, {$failed} failed",
            'total_checked' => $pendingBillings->count()
        ]);
    }

    public function userSupport()
    {
        return view('support');
    }

    public function loadEscortsByCategory(Request $request)
    {
        $categoryId = $request->query('category_id');
        $countryId = $request->query('country_id');
        $stateId = $request->query('state_id');
        $cityId = $request->query('city_id');
        $page = $request->query('page', 1);

        $escorts = User::where('role', 'escort')
            ->whereHas('usermeta', function ($query) use ($categoryId) {
                $query->where('meta_key', 'category_id')->where('meta_value', $categoryId);
            })
            ->with('usermeta')
            ->when($countryId, function ($query) use ($countryId) {
                return $query->whereHas('usermeta', function ($q) use ($countryId) {
                    $q->where('meta_key', 'country')->where('meta_value', $countryId);
                });
            })
            ->when($stateId, function ($query) use ($stateId) {
                return $query->whereHas('usermeta', function ($q) use ($stateId) {
                    $q->where('meta_key', 'state')->where('meta_value', $stateId);
                });
            })
            ->when($cityId, function ($query) use ($cityId) {
                return $query->whereHas('usermeta', function ($q) use ($cityId) {
                    $q->where('meta_key', 'city')->where('meta_value', $cityId);
                });
            })
            ->latest()
            ->paginate(9, ['*'], 'page', $page);

        $html = view('partials.escorts', compact('escorts'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'hasMorePages' => $escorts->hasMorePages()
        ]);
    }

    public function filterEscorts(Request $request)
    {
        $countryId = $request->input('country_id');
        $stateId = $request->input('state_id');
        $cityId = $request->input('city_id');
        $categories = $request->input('categories') ? explode(',', $request->input('categories')) : [];
        $page = $request->input('page', 1);

        $escorts = User::where('role', 'escort')
            ->with('usermeta')
            ->when($countryId, function ($query) use ($countryId) {
                return $query->whereHas('usermeta', function ($q) use ($countryId) {
                    $q->where('meta_key', 'country')->where('meta_value', $countryId);
                });
            })
            ->when($stateId, function ($query) use ($stateId) {
                return $query->whereHas('usermeta', function ($q) use ($stateId) {
                    $q->where('meta_key', 'state')->where('meta_value', $stateId);
                });
            })
            ->when($cityId, function ($query) use ($cityId) {
                return $query->whereHas('usermeta', function ($q) use ($cityId) {
                    $q->where('meta_key', 'city')->where('meta_value', $cityId);
                });
            })
            ->when(!empty($categories), function ($query) use ($categories) {
                return $query->whereHas('usermeta', function ($q) use ($categories) {
                    $q->where('meta_key', 'category_id')->whereIn('meta_value', $categories);
                });
            })
            ->latest()
            ->paginate(9, ['*'], 'page', $page);

        $html = view('partials.escorts', compact('escorts'))->render();
        return response()->json([
            'success' => true,
            'html' => $html,
            'hasMorePages' => $escorts->hasMorePages()
        ]);
    }

    public function showCategory($id)
    {
        $category = EscortCategory::findOrFail($id);
        $escorts = User::where('role', 'escort')
            ->whereHas('usermeta', function ($query) use ($id) {
                $query->where('meta_key', 'category_id')->where('meta_value', $id);
            })
            ->with('usermeta')
            ->paginate(12);
        $topbarAds = Ads::where('position', 'topbar')->latest()->get(); // Add this line
        return view('category', compact('category', 'escorts', 'topbarAds')); // Update compact
    }

    public function showCountry($id)
    {
        $country = Country::findOrFail($id);
        $escorts = User::where('role', 'escort')
            ->whereHas('usermeta', function ($query) use ($id) {
                $query->where('meta_key', 'country')->where('meta_value', $id);
            })
            ->with('usermeta')
            ->paginate(12);
        $topbarAds = Ads::where('position', 'topbar')->latest()->get();

        // Fetch categories with escorts count
        $categories = EscortCategory::withCount('escorts')
            ->orderBy('escorts_count', 'desc')
            ->limit(16)
            ->get();

        // Fetch popular countries
        $popularCountries = Country::leftJoin('country_flags', 'countries.id', '=', 'country_flags.country_id')
            ->select('countries.id', 'countries.name', 'countries.iso2', DB::raw('COALESCE(COUNT(DISTINCT usermeta.user_id), 0) as escorts_count'))
            ->leftJoin('usermeta', function ($join) {
                $join->on('countries.id', '=', 'usermeta.meta_value')
                    ->where('usermeta.meta_key', 'country');
            })
            ->whereNotNull('countries.iso2')
            ->groupBy('countries.id', 'countries.name', 'countries.iso2')
            ->orderByDesc('escorts_count')
            ->limit(16)
            ->get();

        return view('country', compact('country', 'escorts', 'topbarAds', 'categories', 'popularCountries'));
    }

    public function paymentSuccess($invoice_id = null)
    {
        $subscription = null;
        if ($invoice_id) {
            $subscription = Billing::where('invoice_id', $invoice_id)->with('plan')->first(); // Assume Billing model has plan relation
        }
        return view('thankyou', compact('subscription'))->with('success', 'Subscription activated successfully!');
    }

    public function debugBilling()
    {
        $userId = Auth::guard('fan')->id();
        $billings = Billing::where('fan_id', $userId)->with('plan', 'escort')->get();

        return response()->json([
            'user_id' => $userId,
            'total_billings' => $billings->count(),
            'billings' => $billings->map(function($billing) {
                return [
                    'id' => $billing->id,
                    'escort_id' => $billing->escort_id,
                    'plan_id' => $billing->plan_id,
                    'plan_name' => $billing->plan->name ?? 'N/A',
                    'amount' => $billing->amount,
                    'status' => $billing->status,
                    'invoice_id' => $billing->invoice_id,
                    'expires_at' => $billing->expires_at,
                    'created_at' => $billing->created_at,
                ];
            })
        ]);
    }

    public function downgradeSubscription(Request $request)
    {
        $validated = $request->validate([
            'new_plan_id' => 'required|exists:subscription_plans,id',
            'current_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $userId = Auth::guard('fan')->id();
        $newPlan = SubscriptionPlans::findOrFail($validated['new_plan_id']);
        $currentPlan = SubscriptionPlans::findOrFail($validated['current_plan_id']);

        // Verify that new plan is actually a downgrade
        if ($newPlan->price >= $currentPlan->price) {
            return back()->with('error', 'This is not a valid downgrade plan.');
        }

        // Find current active subscription
        $currentSubscription = Billing::where('fan_id', $userId)
            ->where('plan_id', $currentPlan->id)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$currentSubscription) {
            return back()->with('error', 'No active subscription found for downgrade.');
        }

        // Cancel current subscription
        $currentSubscription->update(['status' => 'cancelled']);

        // Create new subscription with immediate activation
        $billing = Billing::create([
            'user_id' => Auth::id(),
            'fan_id' => $userId,
            'escort_id' => $currentSubscription->escort_id,
            'plan_id' => $newPlan->id,
            'amount' => 0, // Free downgrade
            'status' => 'active',
            'subscription_type' => 'downgrade',
            'previous_plan_id' => $currentPlan->id,
            'expires_at' => now()->addDays($newPlan->duration_days),
            'start_date' => now(),
            'end_date' => now()->addDays($newPlan->duration_days),
            'first_name' => $currentSubscription->first_name,
            'last_name' => $currentSubscription->last_name,
            'address' => $currentSubscription->address,
            'city' => $currentSubscription->city,
            'state' => $currentSubscription->state,
            'zip_code' => $currentSubscription->zip_code,
            'country' => $currentSubscription->country,
        ]);

        return redirect()->route('choose.plan', $currentSubscription->escort_id)
            ->with('success', "Successfully downgraded to {$newPlan->name}! Your new subscription is now active.");
    }


>>>>>>> 23c30d7 (Escort project)
}