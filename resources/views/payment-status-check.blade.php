@extends('layouts.app')

@section('title', 'Payment Status Check')

@section('content')
<section class="py-5" style="background: radial-gradient(circle at top, #0d0f16, #000); min-height: 100vh; color: #fff;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 p-4 text-center" style="background: rgba(0,0,0,0.9); border-radius: 16px;">

          <h3 class="mb-3">Payment Status Check</h3>
          <p class="text-muted mb-4">Click the button below to check your payment status</p>

          <div id="status-result" class="mb-4"></div>

          <button id="check-status-btn" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow mb-3">
            Check Payment Status
          </button>

          <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm">Back to Home</a>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.getElementById('check-status-btn').addEventListener('click', function() {
    const btn = this;
    const result = document.getElementById('status-result');
    
    btn.disabled = true;
    btn.textContent = 'Checking...';
    
    // First sync all payments, then check status
    fetch('/sync-payments', {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(syncData => {
        console.log('Sync result:', syncData);
        
        // Now check payment status
        return fetch('/check-payment-status', {
            headers: { 'Accept': 'application/json' }
        });
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            result.innerHTML = '<div class="alert alert-success text-white"> Payment Successful! Redirecting...</div>';
            setTimeout(() => window.location.href = data.redirect, 2000);
        } else if (data.status === 'failed') {
            result.innerHTML = '<div class="alert alert-danger text-white"> Payment Failed</div>';
            setTimeout(() => window.location.href = data.redirect, 2000);
        } else if (data.status === 'pending') {
            result.innerHTML = '<div class="alert alert-warning text-white"> Payment Pending (BTCPay Status: ' + data.btcpay_status + ')</div>';
        } else {
            result.innerHTML = '<div class="alert alert-danger text-white"> ' + (data.message || 'Error checking status') + '</div>';
        }
    })
    .catch(error => {
        result.innerHTML = '<div class="alert alert-danger text-white"> Network Error</div>';
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Check Payment Status';
    });
});
</script>
@endsection
