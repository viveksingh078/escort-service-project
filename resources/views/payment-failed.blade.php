@extends('layouts.app')

@section('content')
    <section class="d-flex align-items-center justify-content-center"
        style="min-height: 100vh; background: linear-gradient(135deg, #ffe5e5, #fff5f5);">
        <div class="card shadow-lg border-0 text-center p-5"
            style="max-width: 520px; border-radius: 20px; background: rgba(255,255,255,0.92); backdrop-filter: blur(10px);">

            <!-- Red Failed Icon -->
            <div class="mb-4">
                <i class="fa-solid fa-circle-xmark text-danger" style="font-size: 80px; animation: shake 1s infinite;"></i>
            </div>

            <!-- Heading -->
            <p class="text-danger fw-bold mb-3" style="font-size: 1.25rem;">
                Payment Failed
            </p>

            <!-- Message -->
            <p class="text-muted fs-5 mb-4">
                Oops! Your payment could not be processed or the invoice has expired.<br>
                Please try again or contact support if the issue persists.
            </p>


            <!-- Return Button -->
            <a href="{{ route('home') }}" class="btn btn-danger btn-lg px-5 fw-bold shadow-lg"
                style="border-radius: 30px; transition: all 0.3s;">
                Return to Homepage
            </a>

            <!-- Auto Redirect Notice -->
            <p class="text-muted small mt-4">
                Redirecting in <span id="secs" class="fw-bold text-dark">10</span>s…
            </p>
        </div>
    </section>

    <!-- Animations -->
    <style>
        @keyframes shake {
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
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #dc3545, #fd5d6c);
            box-shadow: 0 0 15px rgba(220, 53, 69, 0.6);
            transform: translateY(-2px);
        }
    </style>

    <!-- Auto Redirect Script -->
    <script>
        let s = 10, el = document.getElementById('secs');
        let t = setInterval(() => {
            s--; el.textContent = s;
            if (s === 0) {
                window.location.href = "{{ route('home') }}";
                clearInterval(t);
            }
        }, 1000);
    </script>
@endsection