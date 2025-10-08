@extends('layouts.app')

<<<<<<< HEAD
@section('content')
    <section class="py-5"
        style="background: linear-gradient(135deg, #e0f7f1, #f5fff8); min-height: 100vh; display: flex; align-items: center;">
        <div class="container text-center">
            <div class="card shadow-lg p-5 rounded-4 border-0"
                style="max-width: 650px; margin: auto; background: rgba(255,255,255,0.9); backdrop-filter: blur(12px);">
=======
@section('title', 'Payment Successful')

<!-- thankyou -->

@section('content')
    <section class="py-5"
        style="background: radial-gradient(circle at top, #0d0f16, #000); min-height: 100vh; display: flex; align-items: center; color: #fff;">
        <div class="container text-center">
            <div class="card shadow-lg p-5 rounded-4 border-0"
                style="max-width: 650px; margin: auto; background: rgba(0,0,0,0.9); backdrop-filter: blur(12px);">
>>>>>>> 23c30d7 (Escort project)

                {{-- Success Icon --}}
                <div class="mb-4">
                    <i class="fa-solid fa-circle-check text-success"
                        style="font-size: 80px; animation: pulse 1.5s infinite;"></i>
                </div>

                {{-- Title --}}
<<<<<<< HEAD
                <h2 class="fw-bold text-success mb-3" style="font-size: 2rem;">Payment Successful 🎉</h2>

                {{-- Message --}}
                <p class="text-muted fs-5">
=======
                <h2 class="fw-bold text-success mb-3" style="font-size: 2rem;">Payment Successful </h2>

                {{-- Message --}}
                <p class="payment-message">
>>>>>>> 23c30d7 (Escort project)
                    Thank you! Your payment has been completed successfully.
                </p>

                {{-- Session Message --}}
                @if(session('success'))
                    <p class="text-success fw-bold fs-6 mt-3">{{ session('success') }}</p>
                @endif

<<<<<<< HEAD
=======
                {{-- Subscription Details --}}
                @if(isset($subscription))
                    <p class="text-muted fs-5 mt-3">
                        <strong>Subscription Details:</strong><br>
                        Plan: {{ $subscription->plan->name }}<br>
                        Valid Until: {{ $subscription->end_date->format('d/m/Y') }}<br>
                        Invoice ID: {{ $subscription->invoice_id }}
                    </p>
                @endif

>>>>>>> 23c30d7 (Escort project)
                {{-- Smart Button --}}
                @php
                    $isFan = Auth::guard('fan')->check();
                    $isWeb = Auth::check();

                    $primaryUrl = $isFan ? route('fan.dashboard')
                        : ($isWeb ? route('dashboard') : route('home'));

                    $primaryText = ($isFan || $isWeb) ? 'Go to Dashboard' : 'Back to Home';
                @endphp

                <div class="mt-4">
                    <a href="{{ $primaryUrl }}" class="btn btn-success rounded-pill px-5 py-2 fw-bold shadow-lg"
                        style="transition: all 0.3s ease;">
                        {{ $primaryText }}
                    </a>
                </div>

                {{-- Auto Redirect Notice --}}
<<<<<<< HEAD
                <p class="text-muted small mt-4">
                    You will be redirected automatically in <span id="secs" class="fw-bold text-dark">30</span>s…
=======
                <p class="redirect-notice small mt-4">
                    You will be redirected automatically in <span id="secs" class="fw-bold text-light">30</span>s…
>>>>>>> 23c30d7 (Escort project)
                </p>
            </div>
        </div>
    </section>

    {{-- Custom Animations --}}
    <style>
        @keyframes pulse {
<<<<<<< HEAD
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
=======
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
>>>>>>> 23c30d7 (Escort project)
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.6);
            transform: translateY(-2px);
        }
<<<<<<< HEAD
    </style>

    {{-- Confetti CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    {{-- Auto Redirect + Confetti Script --}}
    <script>
        // Auto Redirect
=======

        .payment-message, .redirect-notice {
            color: #ffffff !important;
        }

        .payment-message { font-size: 1.25rem; }
        .redirect-notice { font-size: 1rem; }
    </style>

    {{-- Latest Confetti CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>

    {{-- Auto Redirect + Confetti Script --}}
    <script>
>>>>>>> 23c30d7 (Escort project)
        let s = 30, el = document.getElementById('secs');
        let t = setInterval(() => {
            s--; el.textContent = s;
            if (s === 0) {
                window.location.href = "{{ $primaryUrl }}";
                clearInterval(t);
            }
        }, 1000);

<<<<<<< HEAD
        // Confetti on page load
        function launchConfetti() {
            var duration = 3 * 1000;
            var end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 5,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 }
                });
                confetti({
                    particleCount: 5,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 }
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());
        }

=======
        function launchConfetti() {
            var duration = 3 * 1000;
            var end = Date.now() + duration;
            (function frame() {
                confetti({ particleCount: 5, angle: 60, spread: 55, origin: { x: 0 } });
                confetti({ particleCount: 5, angle: 120, spread: 55, origin: { x: 1 } });
                if (Date.now() < end) requestAnimationFrame(frame);
            }());
        }
>>>>>>> 23c30d7 (Escort project)
        window.onload = launchConfetti;
    </script>
@endsection