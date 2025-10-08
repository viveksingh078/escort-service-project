<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Billing;

$billing = Billing::where('invoice_id', 'CnXsH2aJ5WHyG8WTdYDAKQ')->first();

if ($billing) {
    echo "✅ Database Status Check:\n";
    echo "Invoice ID: " . $billing->invoice_id . "\n";
    echo "Status: " . $billing->status . "\n";
    echo "Start Date: " . $billing->start_date . "\n";
    echo "End Date: " . $billing->end_date . "\n";
    echo "Updated At: " . $billing->updated_at . "\n";
} else {
    echo "❌ Billing record not found\n";
}
