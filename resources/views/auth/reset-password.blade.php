@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/user-login-style.css') }}">

    <div class="container-fluid login-page p-0 m-0">
        <div class="container py-4">
            <div class="d-flex justify-content-center align-items-center">
                <div class="glass-card">
                    <div class="text-center mb-4">
                        <h2 class="title mb-2">Reset Password</h2>
                        <p class="text-muted fw-bold small">Enter new password below</p>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="form-group mb-3">
                            <label class="fw-bold mb-1 text-muted small">New Password</label>
                            <input type="password" name="password"
                                class="form-control input-field @error('password') is-invalid @enderror"
                                placeholder="Enter your new password">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="fw-bold mb-1 text-muted small">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control input-field @error('password_confirmation') is-invalid @enderror"
                                placeholder="Confirm new password">
                            @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-login w-100">Reset Password</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection