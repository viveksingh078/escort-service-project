@extends('layouts.app')

@section('title', 'Payment Processing')

@section('content')
<section class="py-5" style="background: radial-gradient(circle at top, #0d0f16, #000); min-height: 100vh; color: #fff;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 p-4 text-center" style="background: rgba(0,0,0,0.9); border-radius: 16px;">

          <div class="mb-4">
            <i class="fa-solid fa-spinner fa-spin text-primary" style="font-size: 60px;"></i>
          </div>

          <h3 class="mb-3">Processing Your Payment</h3>
          <p class="text-muted mb-4">Please wait while we confirm your payment...</p>

          <div id="status-message" class="mb-4">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>

          <p class="text-muted small">
            This page will automatically redirect you once payment is confirmed.
          </p>

        </div>
      </div>
    </div>
  </div>
</section>

<script>
let checkCount = 0;
const maxChecks = 60; // 5 minutes (60 checks * 5 seconds)

function checkPaymentStatus() {
    fetch('/check-payment-status', {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        checkCount++;
        
        if (data.status === 'success') {
            document.getElementById('status-message').innerHTML = 
                '<div class="alert alert-success text-white">✅ Payment Successful! Redirecting...</div>';
            setTimeout(() => window.location.href = data.redirect, 1000);
        } else if (data.status === 'failed') {
            document.getElementById('status-message').innerHTML = 
                '<div class="alert alert-danger text-white">❌ Payment Failed. Redirecting...</div>';
            setTimeout(() => window.location.href = data.redirect, 2000);
        } else if (checkCount >= maxChecks) {
            // Timeout after 5 minutes
            document.getElementById('status-message').innerHTML = 
                '<div class="alert alert-warning text-white">⏰ Payment timeout. Redirecting...</div>';
            setTimeout(() => window.location.href = '/payment-failed', 2000);
        } else {
            // Continue checking
            setTimeout(checkPaymentStatus, 5000); // Check every 5 seconds
        }
    })
    .catch(error => {
        console.error('Status check error:', error);
        if (checkCount < maxChecks) {
            setTimeout(checkPaymentStatus, 5000);
        }
    });
}

// Start checking immediately and sync first
function checkPaymentStatusWithSync() {
    // First sync all payments
    fetch('/sync-payments', {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(syncData => {
        console.log('Auto sync result:', syncData);
        
        // Now check payment status
        return fetch('/check-payment-status', {
            headers: { 'Accept': 'application/json' }
        });
    })
    .then(response => response.json())
    .then(data => {
        checkCount++;
        
        if (data.status === 'success') {
            document.getElementById('status-message').innerHTML = 
                '<div class="alert alert-success text-white">✅ Payment Successful! Redirecting...</div>';
            setTimeout(() => window.location.href = data.redirect, 1000);
        } else if (data.status === 'failed') {
            document.getElementById('status-message').innerHTML = 
                '<div class="alert alert-danger text-white">❌ Payment Failed. Redirecting...</div>';
            setTimeout(() => window.location.href = data.redirect, 2000);
        } else if (checkCount >= maxChecks) {
            // Timeout after 5 minutes
            document.getElementById('status-message').innerHTML = 
                '<div class="alert alert-warning text-white">⏰ Payment timeout. Redirecting...</div>';
            setTimeout(() => window.location.href = '/payment-failed', 2000);
        } else {
            // Continue checking
            setTimeout(checkPaymentStatusWithSync, 10000); // Check every 10 seconds
        }
    })
    .catch(error => {
        console.error('Status check error:', error);
        if (checkCount < maxChecks) {
            setTimeout(checkPaymentStatusWithSync, 10000);
        }
    });
}

// Start checking after 5 seconds
setTimeout(checkPaymentStatusWithSync, 5000);
</script>
@endsection
