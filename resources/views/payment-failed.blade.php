@extends('layouts.app')

@section('content')
    <section class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background: #f8f9fa;">
        <div class="card shadow-lg border-0 text-center p-5" style="max-width: 500px; border-radius: 20px;">

            <!-- Red Failed Icon -->
            <div class="mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor"
                    class="text-danger bi bi-x-circle-fill" viewBox="0 0 16 16">
                    <path
                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                </svg>
            </div>

            <!-- Heading -->
            <h2 class="text-danger mb-3">Payment Failed</h2>
            <p class="text-muted mb-4">
                Oops! Your payment could not be processed or the invoice has expired.<br>
                Please try again or contact support if the issue persists.
            </p>

            <!-- Return Button -->
            <a href="{{ route('home') }}" class="btn btn-danger btn-lg px-4" style="border-radius: 30px;">
                Return to Homepage
            </a>

        </div>
    </section>
@endsection