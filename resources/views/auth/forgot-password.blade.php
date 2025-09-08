@extends('layouts.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/user-login-style.css') }}">

<div class="container-fluid login-page p-0 m-0">
  <div class="container py-4">
    <div class="d-flex justify-content-center align-items-center">
      <div class="glass-card">
        <div class="text-center mb-4">
          <h2 class="title mb-2">Forgot Password</h2>
          <p class="text-muted fw-bold small">Enter your email to receive reset link</p>

          @if (session('success'))
            <p class="small text-success">{{ session('success') }}</p>
          @endif
        </div>

    <form action="{{ route('password.email') }}" method="POST" novalidate>
  @csrf

  <div class="form-group mb-3">
    <label class="fw-bold mb-1 text-muted small">Email</label>
    <input
      type="email"
      name="email"
      class="form-control input-field @error('email') is-invalid @enderror"
      placeholder="Enter your email"
      value="{{ old('email') }}"
    >
        @error('email')
            <p class="invalid-feedback">{{ $message }}</p>
        @enderror
  </div>

  <button type="submit" class="btn btn-login w-100">Send Reset Link</button>
</form>


        <div class="text-center mt-3">
          <a href="{{ route('login') }}" class="small text-muted">Back to Login</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
