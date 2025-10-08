<?php
// Test the payment status API directly

$url = 'http://127.0.0.1:8000/check-payment-status';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "🔍 Payment Status API Test:\n";
echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";

if ($httpCode == 200) {
    $data = json_decode($response, true);
    if ($data) {
        echo "\n📊 Parsed Response:\n";
        echo "Status: " . ($data['status'] ?? 'unknown') . "\n";
        if (isset($data['redirect'])) {
            echo "Redirect URL: " . $data['redirect'] . "\n";
        }
        if (isset($data['btcpay_status'])) {
            echo "BTCPay Status: " . $data['btcpay_status'] . "\n";
        }
    }
} else {
    echo "❌ API call failed\n";
}
