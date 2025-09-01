@extends('layouts.app')

@section('content')
    <section class="py-5"
        style="background: linear-gradient(135deg, #e0f7f1, #f5fff8); min-height: 100vh; display: flex; align-items: center;">
        <div class="container text-center">
            <div class="card shadow-lg p-5 rounded-4 border-0"
                style="max-width: 650px; margin: auto; background: rgba(255,255,255,0.9); backdrop-filter: blur(12px);">

                {{-- Success Icon --}}
                <div class="mb-4">
                    <i class="fa-solid fa-circle-check text-success"
                        style="font-size: 80px; animation: pulse 1.5s infinite;"></i>
                </div>

                {{-- Title --}}
                <h2 class="fw-bold text-success mb-3" style="font-size: 2rem;">Payment Successful 🎉</h2>

                {{-- Message --}}
                <p class="text-muted fs-5">
                    Thank you! Your payment has been completed successfully.
                </p>

                {{-- Session Message --}}
                @if(session('success'))
                    <p class="text-success fw-bold fs-6 mt-3">{{ session('success') }}</p>
                @endif

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
                <p class="text-muted small mt-4">
                    You will be redirected automatically in <span id="secs" class="fw-bold text-dark">300</span>s…
                </p>
            </div>
        </div>
    </section>

    {{-- Custom Animations --}}
    <style>
        @keyframes pulse {
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
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #28a745, #20c997);
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.6);
            transform: translateY(-2px);
        }
    </style>

    {{-- Confetti CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    {{-- Auto Redirect + Confetti Script --}}
    <script>
        // Auto Redirect
        let s = 300, el = document.getElementById('secs');
        let t = setInterval(() => {
            s--; el.textContent = s;
            if (s === 0) {
                window.location.href = "{{ $primaryUrl }}";
                clearInterval(t);
            }
        }, 1000);

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

        window.onload = launchConfetti;
    </script>
@endsection