<?php
// Sync all pending payments with BTCPay server

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Billing;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

$btcpayUrl = rtrim(config('btcpay.host'), '/');
$storeId = config('btcpay.store_id');
$apiKey = config('btcpay.api_key');

// Get all pending billings with invoice_id
$pendingBillings = Billing::where('status', 'pending')
    ->whereNotNull('invoice_id')
    ->get();

echo "🔍 Found " . $pendingBillings->count() . " pending payments to check...\n\n";

foreach ($pendingBillings as $billing) {
    echo "Checking Invoice: " . $billing->invoice_id . "\n";
    
    // Check status from BTCPay
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $apiKey,
        'Content-Type' => 'application/json',
    ])->get("{$btcpayUrl}/api/v1/stores/{$storeId}/invoices/{$billing->invoice_id}");
    
    if ($response->successful()) {
        $invoice = $response->json();
        $status = $invoice['status'] ?? 'Unknown';
        
        echo "BTCPay Status: " . $status . "\n";
        
        if (in_array($status, ['Settled', 'Complete', 'Confirmed'])) {
            $billing->update([
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addDays($billing->plan->duration_days ?? 30),
            ]);
            echo "✅ Updated to ACTIVE\n";
        } elseif (in_array($status, ['Expired', 'Invalid'])) {
            $billing->update(['status' => 'failed']);
            echo "❌ Updated to FAILED\n";
        } else {
            echo "⏳ Still pending (" . $status . ")\n";
        }
    } else {
        echo "❌ Failed to check BTCPay status\n";
    }
    
    echo "---\n";
}

echo "\n✅ Payment sync completed!\n";
