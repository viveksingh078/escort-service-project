@extends('layouts.app')

@section('title', 'Processing Payment')

@section('content')
<section class="py-5" style="background: radial-gradient(circle at top, #0d0f16, #000); min-height: 100vh; color: #fff;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card shadow-lg border-0 p-4 text-center" style="background: rgba(0,0,0,0.9); border-radius: 16px;">

          <div class="mb-4">
            <i class="fa-solid fa-spinner fa-spin text-success" style="font-size: 60px;"></i>
          </div>

          <h3 class="mb-3">Payment Successful!</h3>
          <p class="text-muted mb-4">Processing your subscription activation...</p>

          <div id="status-message" class="mb-4">
            <div class="alert alert-info text-white">
              <i class="fa-solid fa-clock me-2"></i>
              Syncing payment with our servers...
            </div>
          </div>

          <div class="progress mb-4">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                 role="progressbar" style="width: 0%" id="progress-bar"></div>
          </div>

          <p class="text-muted small">
            This process usually takes 30-60 seconds. Please wait...
          </p>

        </div>
      </div>
    </div>
  </div>
</section>

<script>
let progress = 0;
let checkCount = 0;
const maxChecks = 30; // 5 minutes max

function updateProgress() {
    progress += 10;
    if (progress > 90) progress = 90;
    document.getElementById('progress-bar').style.width = progress + '%';
}

function processPayment() {
    updateProgress();
    
    // First sync all payments
    fetch('/sync-payments', {
        headers: { 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(syncData => {
        console.log('Auto sync result:', syncData);
        updateProgress();
        
        // Now check payment status
        return fetch('/check-payment-status', {
            headers: { 'Accept': 'application/json' }
        });
    })
    .then(response => response.json())
    .then(data => {
        checkCount++;
        updateProgress();
        
        if (data.status === 'success') {
            document.getElementById('progress-bar').style.width = '100%';
            document.getElementById('status-message').innerHTML = 
                '<div class="alert alert-success text-white"><i class="fa-solid fa-check-circle me-2"></i>Subscription Activated Successfully!</div>';
            setTimeout(() => window.location.href = data.redirect, 2000);
        } else if (data.status === 'failed') {
            document.getElementById('progress-bar').style.width = '100%';
            document.getElementById('progress-bar').classList.remove('bg-success');
            document.getElementById('progress-bar').classList.add('bg-danger');
            document.getElementById('status-message').innerHTML = 
                '<div class="alert alert-danger text-white"><i class="fa-solid fa-times-circle me-2"></i>Payment Failed or Expired</div>';
            setTimeout(() => window.location.href = data.redirect, 3000);
        } else if (checkCount >= maxChecks) {
            document.getElementById('status-message').innerHTML = 
                '<div class="alert alert-warning text-white"><i class="fa-solid fa-clock me-2"></i>Processing timeout. Please contact support.</div>';
        } else {
            // Continue checking
            setTimeout(processPayment, 10000); // Check every 10 seconds
        }
    })
    .catch(error => {
        console.error('Processing error:', error);
        if (checkCount < maxChecks) {
            setTimeout(processPayment, 10000);
        }
    });
}

// Start processing after 2 seconds
setTimeout(processPayment, 2000);
</script>
@endsection
