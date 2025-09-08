@extends('admin.layout')
@section('title', 'Add Escort')
@section('content')
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <div class="container-fluid p-0 m-0 py-4">
    <div class="container py-5 px-5 bg-white">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="pb-1">Add Escort</h5>
        <a href="{{ route('admin.escort.manage') }}" class="btn btn-sm btn-primary">Back to Manage</a>
      </div>
      <hr class="p-0 m-0">

      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.escort.store') }}" method="POST" enctype="multipart/form-data" id="addEscortForm">
        @csrf

        <!-- Basic Information -->
        <h5>Basic Information</h5>
        <div class="row mt-3">
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Display Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
              value="{{ old('name') }}" required>
            @error('name')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Username</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" name="username"
              value="{{ old('username') }}">
            @error('username')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
              value="{{ old('email') }}" required>
            @error('email')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
            @error('password')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Confirm Password</label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
              name="password_confirmation" required>
            @error('password_confirmation')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <!-- Personal Details -->
        <h5 class="mt-4">Personal Details</h5>
        <p class="small">Use your real name for admin purposes only.</p>
        <hr class="p-0 my-2">
        <div class="row mt-3">
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">First Name</label>
            <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name"
              value="{{ old('first_name') }}" required>
            @error('first_name')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Last Name</label>
            <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name"
              value="{{ old('last_name') }}" required>
            @error('last_name')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Country Code</label>
            <input type="text" class="form-control @error('country_code') is-invalid @enderror" name="country_code"
              value="{{ old('country_code') }}">
            @error('country_code')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Phone Number</label>
            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number"
              value="{{ old('phone_number') }}">
            @error('phone_number')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Date of Birth</label>
            <input type="date" class="form-control @error('dob') is-invalid @enderror" name="dob"
              value="{{ old('dob') }}">
            @error('dob')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Profile Picture</label>
            <input type="file" class="form-control @error('profile_picture') is-invalid @enderror" name="profile_picture">
            @error('profile_picture')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <!-- Profile Setup -->
        <h5 class="mt-4">Profile Setup</h5>
        <p class="small">Details for your public profile.</p>
        <hr class="p-0 my-2">
        <div class="row mt-3">
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Display Name</label>
            <input type="text" class="form-control @error('display_name') is-invalid @enderror" name="display_name"
              value="{{ old('display_name') }}">
            @error('display_name')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Age to Display</label>
            <input type="number" class="form-control @error('age') is-invalid @enderror" name="age"
              value="{{ old('age') }}" readonly>
            @error('age')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Public Country Code</label>
            <input type="text" class="form-control @error('public_country_code') is-invalid @enderror"
              name="public_country_code" value="{{ old('public_country_code') }}">
            @error('public_country_code')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Profile Category</label>
            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id">
              <option value="">Select Category</option>
              <option value="1" {{ old('category_id') == 1 ? 'selected' : '' }}>Category 1</option>
              <option value="2" {{ old('category_id') == 2 ? 'selected' : '' }}>Category 2</option>
            </select>
            @error('category_id')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-12 mb-4">
            <label class="form-label fw-bold small">Introduction</label>
            <textarea class="form-control @error('introduction') is-invalid @enderror" name="introduction"
              rows="3">{{ old('introduction') }}</textarea>
            @error('introduction')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <!-- Payment Settings -->
        <h5 class="mt-4">Payment Settings</h5>
        <p class="small">Set your subscription price.</p>
        <hr class="p-0 my-2">
        <div class="row mt-3">
          <div class="col-md-6 mb-4">
            <label class="form-label fw-bold small">Subscription Price (0-100)</label>
            <input type="range" class="form-control-range @error('subscription_price') is-invalid @enderror"
              name="subscription_price" min="0" max="100" value="{{ old('subscription_price', 50) }}">
            <span class="ml-2">{{ old('subscription_price', 50) }}</span>
            @error('subscription_price')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="col-md-6 mb-4">
            <div class="form-check">
              <input class="form-check-input @error('digital_services_only') is-invalid @enderror" type="checkbox"
                name="digital_services_only" id="digital_services_only">
              <label class="form-check-label" for="digital_services_only">Confirm for digital services only</label>
              @error('digital_services_only')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
          </div>
          <div class="col-md-6 mb-4">
            <div class="form-check">
              <input class="form-check-input @error('promote_profile') is-invalid @enderror" type="checkbox"
                name="promote_profile" id="promote_profile">
              <label class="form-check-label" for="promote_profile">Promote my profile</label>
              @error('promote_profile')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        <div class="row mt-3">
          <div class="d-flex justify-content-end">
            <a href="{{ route('admin.escort.manage') }}" class="btn btn-sm btn-secondary mt-3 mx-1">Cancel</a>
            <button type="submit" class="btn btn-sm btn-primary mt-3">Add Escort</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    jQuery(document).ready(function ($) {
      $('#addEscortForm').submit(function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
          url: "{{ route('admin.escort.store') }}",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          success: function (res) {
            if (res.success) {
              alert(res.message);
              if (res.redirect) {
                window.location.href = res.redirect;
              }
            }
          },
          error: function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
              alert(Object.values(xhr.responseJSON.errors).join('\n'));
            }
          }
        });
      });

      // DOB and Age calculation
      const dobInput = document.querySelector('input[name="dob"]');
      const ageInput = document.querySelector('input[name="age"]');
      dobInput.addEventListener('change', function () {
        const dob = new Date(this.value);
        if (dob instanceof Date && !isNaN(dob)) {
          const today = new Date();
          let age = today.getFullYear() - dob.getFullYear();
          const m = today.getMonth() - dob.getMonth();
          if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
          }
          ageInput.value = age;
        } else {
          ageInput.value = '';
        }
      });

      // Set max date to 18 years ago
      const today = new Date();
      const eighteenYearsAgo = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
      dobInput.max = eighteenYearsAgo.toISOString().split('T')[0];

      // Subscription price slider
      const rangeInput = document.querySelector('input[name="subscription_price"]');
      const rangeValue = rangeInput.nextElementSibling;
      rangeValue.textContent = rangeInput.value;
      rangeInput.addEventListener('input', function () {
        rangeValue.textContent = this.value;
      });
    });
  </script>
@endsection