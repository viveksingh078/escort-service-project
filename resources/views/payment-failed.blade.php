@extends('layouts.app')

<<<<<<< HEAD
@section('content')
    <section class="d-flex align-items-center justify-content-center"
        style="min-height: 100vh; background: linear-gradient(135deg, #ffe5e5, #fff5f5);">
        <div class="card shadow-lg border-0 text-center p-5"
            style="max-width: 520px; border-radius: 20px; background: rgba(255,255,255,0.92); backdrop-filter: blur(10px);">
=======
@section('title', 'Payment Failed')

<!-- paymentFailed -->

@section('content')
    <section class="d-flex align-items-center justify-content-center"
        style="min-height: 100vh; background: radial-gradient(circle at top, #0d0f16, #000); color: #fff;">
        <div class="card shadow-lg border-0 text-center p-5"
            style="max-width: 520px; border-radius: 20px; background: rgba(0,0,0,0.9); backdrop-filter: blur(10px);">
>>>>>>> 23c30d7 (Escort project)

            <!-- Red Failed Icon -->
            <div class="mb-4">
                <i class="fa-solid fa-circle-xmark text-danger" style="font-size: 80px; animation: shake 1s infinite;"></i>
            </div>

            <!-- Heading -->
<<<<<<< HEAD
            <p class="text-danger fw-bold mb-3" style="font-size: 1.25rem;">
=======
            <p class="payment-failed-text fw-bold mb-3" style="font-size: 1.25rem;">
>>>>>>> 23c30d7 (Escort project)
                Payment Failed
            </p>

            <!-- Message -->
<<<<<<< HEAD
            <p class="text-muted fs-5 mb-4">
=======
            <p class="payment-message fs-5 mb-4">
>>>>>>> 23c30d7 (Escort project)
                Oops! Your payment could not be processed or the invoice has expired.<br>
                Please try again or contact support if the issue persists.
            </p>

<<<<<<< HEAD
=======
            <!-- Error Message if Passed -->
            @if(session('error'))
                <p class="text-danger fw-bold fs-6 mt-3">{{ session('error') }}</p>
            @endif

            <!-- Retry Button if Escort ID Available -->
            @if(isset($escortId))
                <a href="{{ route('choose.plan', ['escort_id' => $escortId]) }}" class="btn btn-warning rounded-pill px-5 py-2 fw-bold shadow-lg me-2"
                    style="transition: all 0.3s;">
                    Retry Payment
                </a>
            @endif
>>>>>>> 23c30d7 (Escort project)

            <!-- Return Button -->
            <a href="{{ route('home') }}" class="btn btn-danger btn-lg px-5 fw-bold shadow-lg"
                style="border-radius: 30px; transition: all 0.3s;">
                Return to Homepage
            </a>

            <!-- Auto Redirect Notice -->
<<<<<<< HEAD
            <p class="text-muted small mt-4">
                Redirecting in <span id="secs" class="fw-bold text-dark">10</span>s…
=======
            <p class="redirect-notice small mt-4">
                Redirecting in <span id="secs" class="fw-bold text-light">30</span>s…
>>>>>>> 23c30d7 (Escort project)
            </p>
        </div>
    </section>

    <!-- Animations -->
    <style>
        @keyframes shake {
<<<<<<< HEAD
            0% {
                transform: translateX(0);
            }

            20% {
                transform: translateX(-5px);
            }

            40% {
                transform: translateX(5px);
            }

            60% {
                transform: translateX(-5px);
            }

            80% {
                transform: translateX(5px);
            }

            100% {
                transform: translateX(0);
            }
=======
            0% { transform: translateX(0); }
            20% { transform: translateX(-5px); }
            40% { transform: translateX(5px); }
            60% { transform: translateX(-5px); }
            80% { transform: translateX(5px); }
            100% { transform: translateX(0); }
>>>>>>> 23c30d7 (Escort project)
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc3545, #fd5d6c);
            box-shadow: 0 0 15px rgba(220, 53, 69, 0.6);
            transform: translateY(-2px);
        }
<<<<<<< HEAD
=======

        .btn-warning:hover {
            background: linear-gradient(135deg, #ffc107, #ff9800);
            box-shadow: 0 0 15px rgba(255, 193, 7, 0.6);
            transform: translateY(-2px);
        }

        .payment-failed-text, .payment-message, .redirect-notice {
            color: #ffffff !important;
        }

        .payment-message { font-size: 1.25rem; }
        .redirect-notice { font-size: 1rem; }
>>>>>>> 23c30d7 (Escort project)
    </style>

    <!-- Auto Redirect Script -->
    <script>
<<<<<<< HEAD
        let s = 10, el = document.getElementById('secs');
=======
        let s = 30, el = document.getElementById('secs');
>>>>>>> 23c30d7 (Escort project)
        let t = setInterval(() => {
            s--; el.textContent = s;
            if (s === 0) {
                window.location.href = "{{ route('home') }}";
                clearInterval(t);
            }
        }, 1000);
    </script>
@endsection