<?php

return [
    'host' => env('BTCPAY_HOST', 'https://btcpay.coincharge.io'), // Changed from 'url' to 'host' for consistency
    'api_key' => env('BTCPAY_API_KEY'), // Remove default for security
    'store_id' => env('BTCPAY_STORE_ID'), // Remove default for security
    'webhook_secret' => env('BTCPAY_WEBHOOK_SECRET'), // Added for webhook
];