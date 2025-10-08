<?php
// Manual database update for testing
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Billing;

// Update the latest invoice status to active
$billing = Billing::where('invoice_id', 'X8jK4Jnfbh3q7uGTTYiHV')->first();

if ($billing) {
    $billing->update([
        'status' => 'active',
        'start_date' => now(),
        'end_date' => now()->addDays(30), // 1 month
    ]);
    
    echo "✅ Billing updated successfully!\n";
    echo "Invoice ID: " . $billing->invoice_id . "\n";
    echo "Status: " . $billing->status . "\n";
    echo "Start Date: " . $billing->start_date . "\n";
    echo "End Date: " . $billing->end_date . "\n";
} else {
    echo "❌ Billing record not found for invoice: CnXsH2aJ5WHyG8WTdYDAKQ\n";
}
