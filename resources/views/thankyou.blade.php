@extends('layouts.app')

@section('content')
    <section class="py-5">
        <div class="container text-center">
            <div class="card shadow-lg p-5 rounded-4" style="max-width: 600px; margin: auto;">

                {{-- Success Icon --}}
                <div class="mb-4">
                    <i class="fa-solid fa-circle-check text-success" style="font-size: 70px;"></i>
                </div>

                {{-- Title --}}
                <h2 class="fw-bold text-success">Payment Successful 🎉</h2>

                {{-- Message --}}
                <p class="text-muted fs-5">
                    "Thank you! Your payment has been completed successfully."
                </p>

                {{-- Session Message --}}
                @if(session('success'))
                    <p class="text-success fw-bold">{{ session('success') }}</p>
                @endif

                {{-- Smart Button with Dropdown --}}
                @php
                    $isFan = Auth::guard('fan')->check();
                    $isWeb = Auth::check();

                    $primaryUrl = $isFan ? route('fan.dashboard')
                        : ($isWeb ? route('dashboard') : route('home'));

                    $primaryText = ($isFan || $isWeb) ? 'Go to Dashboard' : 'Back to Home';
                  @endphp

                <div class="btn-group mt-4">
                    <!-- Default button -->
                    <a href="{{ $primaryUrl }}" class="btn btn-success rounded-pill px-4 fw-bold">
                        {{ $primaryText }}
                    </a>

                    <!-- Dropdown toggle
                                        <button type="button" class="btn btn-success rounded-pill dropdown-toggle dropdown-toggle-split"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="visually-hidden">Toggle</span>
                                        </button>

                                        Dropdown menu -->
                    <!-- <ul class="dropdown-menu dropdown-menu-end">
                                            @if($isFan || $isWeb)
                                                <li>
                                                    <a class="dropdown-item" href="{{ $isFan ? route('fan.dashboard') : route('dashboard') }}">
                                                        Go to Dashboard
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                            @endif
                                            <li><a class="dropdown-item" href="{{ route('home') }}">Go to Homepage</a></li>
                                        </ul> -->
                </div>

                {{-- Auto Redirect Notice --}}
                <p class="text-muted small mt-3">
                    Auto redirect in <span id="secs">300</span>s…
                </p>
            </div>
        </div>
    </section>

    {{-- Auto Redirect Script --}}
    <script>
        let s = 300, el = document.getElementById('secs');
        let t = setInterval(() => {
            s--; el.textContent = s;
            if (s === 0) {
                window.location.href = "{{ $primaryUrl }}";
                clearInterval(t);
            }
        }, 1000);
    </script>
@endsection