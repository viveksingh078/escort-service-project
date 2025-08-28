@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('css/user-register-style.css') }}">

<div class="container-fluid p-0 m-0 escort-register-page">
    <div class="container py-5">
        <div class="row">
            <div class="col-12 mx-auto">
                <div class="escort-register-inner shadow-sm p-5">

                    <h2>Escort Register</h2>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('escort.register.step1') }}" method="POST">
                        @csrf

                        <div class="row mt-3">

                        	 <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small">Display Name</label>
                                <input type="text" class="form-control custom-input @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" placeholder="Enter your display name">
                                @error('name')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small">Username</label>
                                <input type="text" class="form-control custom-input @error('username') is-invalid @enderror" 
                                       name="username" value="{{ old('username') }}" placeholder="Enter your username">
                                @error('username')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small">Email Address</label>
                                <input type="email" class="form-control custom-input @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" placeholder="Enter your email address">
                                @error('email')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4 pass-col">
                                <label class="form-label fw-bold small">Password</label>
                                <input type="password" class="form-control custom-input @error('password') is-invalid @enderror" 
                                       name="password" placeholder="Enter your password">
                                <i class="fa-solid fa-eye pass-view-btn" style="cursor: pointer;"></i>
                                @error('password')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4 pass-col">
                                <label class="form-label fw-bold small">Confirm Password</label>
                                <input type="password" class="form-control custom-input @error('password_confirmation') is-invalid @enderror" 
                                       name="password_confirmation" placeholder="Confirm your password">
                                <i class="fa-solid fa-eye pass-view-btn" style="cursor: pointer;"></i>
                                @error('password_confirmation')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="form-check mb-2">
                                <input class="form-check-input custom-input-check shadow-none" type="checkbox" 
                                       id="terms" name="terms" >
                                <label class="form-check-label" for="terms">
                                    Confirm our <a href="#">Terms and Conditions</a>
                                </label>
                                @error('terms')
                                    <p class="text-danger small">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input custom-input-check shadow-none" type="checkbox" 
                                       id="over18" name="over18"  >
                                <label class="form-check-label" for="over18">
                                    I confirm that I am over 18 years old
                                </label>
                                @error('over18')
                                    <p class="text-danger small">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div>
                                <button class="btn custom-btn" type="submit">
                                    Continue 
                                    <i class="fa-solid fa-chevron-right custom-btn-icon"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
  document.querySelectorAll('.pass-col').forEach(function(container) {
    const input = container.querySelector('input');
    const toggleBtn = container.querySelector('.pass-view-btn');

    toggleBtn.addEventListener('click', function () {
      if (input.type === 'password') {
        input.type = 'text';
        toggleBtn.classList.remove('fa-eye');
        toggleBtn.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        toggleBtn.classList.remove('fa-eye-slash');
        toggleBtn.classList.add('fa-eye');
      }
    });
  });
</script>

@endsection