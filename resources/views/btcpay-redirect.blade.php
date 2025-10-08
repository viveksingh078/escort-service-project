<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Redirect</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #000;
            color: #fff;
            text-align: center;
            padding: 50px;
        }
        .spinner {
            border: 4px solid #333;
            border-top: 4px solid #fff;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <h2>Payment Successful!</h2>
    <div class="spinner"></div>
    <p>Redirecting to activation page...</p>
    
    <script>
        // Check if we came from BTCPay (check URL or referrer)
        const urlParams = new URLSearchParams(window.location.search);
        const fromBTCPay = document.referrer.includes('btcpayserver.org') || 
                          urlParams.get('from') === 'btcpay';
        
        // Check for payment status indicators in URL
        const paymentFailed = window.location.hash.includes('failed') ||
                             window.location.hash.includes('expired') ||
                             urlParams.get('status') === 'failed' ||
                             urlParams.get('status') === 'expired';
                             
        const paymentSuccess = window.location.hash.includes('paid') ||
                              window.location.hash.includes('success') ||
                              urlParams.get('status') === 'paid' ||
                              urlParams.get('status') === 'success';
        
        if (fromBTCPay) {
            if (paymentFailed) {
                // Redirect to failed page
                document.querySelector('p').textContent = 'Payment failed. Redirecting...';
                setTimeout(function() {
                    window.location.href = '{{ route("payment.failed") }}';
                }, 2000);
            } else if (paymentSuccess) {
                // Redirect to processing page
                setTimeout(function() {
                    window.location.href = '{{ route("payment.processing") }}';
                }, 2000);
            } else {
                // Default to processing page (will handle status check)
                setTimeout(function() {
                    window.location.href = '{{ route("payment.processing") }}';
                }, 3000);
            }
        } else {
            // If not from BTCPay, redirect to home
            setTimeout(function() {
                window.location.href = '{{ route("home") }}';
            }, 3000);
        }
        
        // Check localStorage for payment status
        const storedStatus = localStorage.getItem('btcpay_payment_status');
        if (storedStatus === 'success') {
            localStorage.removeItem('btcpay_payment_status');
            setTimeout(function() {
                window.location.href = '{{ route("payment.processing") }}';
            }, 1000);
        } else if (storedStatus === 'failed') {
            localStorage.removeItem('btcpay_payment_status');
            setTimeout(function() {
                window.location.href = '{{ route("payment.failed") }}';
            }, 1000);
        }
    </script>
</body>
</html>
