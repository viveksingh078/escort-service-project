<?php
// Manual webhook test - simulate BTCPay webhook call

$invoiceId = 'CnXsH2aJ5WHyG8WTdYDAKQ'; // Latest invoice ID from database
$webhookUrl = 'http://127.0.0.1:8000/btcpay-webhook';

// Simulate BTCPay webhook data
$webhookData = [
    'id' => $invoiceId,
    'status' => 'settled', // or 'confirmed'
    'type' => 'InvoicePaymentSettled'
];

// Create signature for webhook security
$payload = json_encode($webhookData);
$secret = 'AqSKoxM18FV2WGft81p88Q'; // Your webhook secret
$signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

// Send POST request to webhook
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $webhookUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'User-Agent: BTCPay-Server',
    'BTCPay-Sig: ' . $signature
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "✅ Webhook Test Results:\n";
echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

if ($httpCode == 200) {
    echo "✅ Webhook successful! Check database for status update.\n";
} else {
    echo "❌ Webhook failed. Check Laravel logs.\n";
}
