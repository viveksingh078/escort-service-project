@extends('layouts.app')

@section('title', 'Payment Instructions')

@section('content')
<section class="py-5" style="background: radial-gradient(circle at top, #0d0f16, #000); min-height: 100vh; color: #fff;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-lg border-0 p-5 text-center" style="background: rgba(0,0,0,0.9); border-radius: 16px;">

          <div class="mb-4">
            <i class="fa-solid fa-info-circle text-info" style="font-size: 60px;"></i>
          </div>

          <h3 class="text-white mb-4">Payment Instructions</h3>
          
          <div class="alert alert-success text-start mb-4" style="background-color: rgba(40, 167, 69, 0.2); color: #ffffff !important;">
            <h5 class="text-white">Payment Completed Successfully!</h5>
            <p class="mb-2 text-white">Your payment has been processed. The system will automatically:</p>
            <ol class="mb-0">
          <li class="text-white"><strong>Process your payment</strong> with our secure system</li>
          <li class="text-white"><strong>Enable your subscription</strong> within 1-2 minutes</li>
          <li class="text-white"><strong>Direct you</strong> to the thank you page automatically</li>
            </ol>
          </div>

          <div class="mb-4">
            <a href="{{ route('payment.complete') }}" class="btn btn-success btn-lg rounded-pill px-5 py-3 fw-bold shadow">
              Complete & Activate Subscription
            </a>
          </div>

          <div class="text-white small">
            <p class="mb-2 text-white">
              Payment confirmation usually takes 1-2 minutes
            </p>
            <p class="mb-0 text-white">
              Having issues? <a href="{{ route('home') }}" class="text-info">Contact Support</a>
            </p>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<script>
// Auto-redirect to payment complete page after 3 seconds
setTimeout(function() {
    window.location.href = '{{ route("payment.complete") }}';
}, 9000);
</script>
@endsection