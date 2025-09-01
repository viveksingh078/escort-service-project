@extends('layouts.app')
@section('content')

  <link rel="stylesheet" href="{{ asset('css/user-register-style.css') }}">

  <div class="container-fluid p-0 m-0 escort-register-page">
    <div class="container py-5">
      <div class="row">
        <div class="col-12 mx-auto">
          <div class="escort-register-inner shadow-sm p-5">
            <h5>Personal Details</h5>
            <p class="small">
              You must use your full, real name exactly as displayed on your passport, driving license or government
              issued Identity Card. This is used for admin purposes only and is not shown publicly.
            </p>
            <hr class="p-0 my-2">

            @if(session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('escort.register.step2.post', [$userid]) }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              <input type="hidden" name="userid" id="userid" value="{{ $userid }}">
              <div class="row mt-3">
                <div class="col-md-6 mb-4">
                  <label for="first_name" class="form-label fw-bold small">First Name</label>
                  <input type="text" class="form-control custom-input @error('first_name') is-invalid @enderror"
                    name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name">
                  @error('first_name')
                    <p class="invalid-feedback">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-6 mb-4">
                  <label for="last_name" class="form-label fw-bold small">Last Name</label>
                  <input type="text" class="form-control custom-input @error('last_name') is-invalid @enderror"
                    name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name">
                  @error('last_name')
                    <p class="invalid-feedback">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-2 mb-4">
                  <label for="personal_country_code" class="form-label fw-bold small">Country Code</label>
                  <input type="string" class="form-control custom-input @error('country_code') is-invalid @enderror"
                    name="country_code" id="personal_country_code" value="{{ old('country_code') }}"
                    placeholder="Country code">
                  @error('country_code')
                    <p class="invalid-feedback">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-4 mb-4">
                  <label for="phone_number" class="form-label fw-bold small">Phone Number</label>
                  <input type="text" class="form-control custom-input @error('phone_number') is-invalid @enderror"
                    name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                    placeholder="Enter your phone number">
                  @error('phone_number')
                    <p class="invalid-feedback">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-6 mb-4">
                  <label for="dob" class="form-label fw-bold small">Date of Birth</label>
                  <input type="date" class="form-control custom-input @error('dob') is-invalid @enderror" name="dob"
                    id="dob" value="{{ old('dob') }}">
                  @error('dob')
                    <p class="invalid-feedback">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <!-- ///// Profile Picture ///// -->
              <div class="row mt-3">
                <div class="col-md-6 mb-4">
                  <label for="profile_picture" class="form-label fw-bold small">Profile Picture</label>

                  {{-- Agar profile picture pehle se hai toh dikhado --}}
                  @php
                    $profilePic = isset($escort)
                      ? $escort->usermeta->where('meta_key', 'profile_picture')->first()->meta_value ?? null
                      : null;
                  @endphp

                  @if($profilePic)
                    <div class="mb-2">
                      <img src="{{ asset('storage/' . $profilePic) }}" alt="Profile Picture" class="img-thumbnail"
                        width="150">
                    </div>
                  @endif

                  <input type="file" class="form-control custom-input @error('profile_picture') is-invalid @enderror"
                    name="profile_picture" id="profile_picture" accept="image/*">

                  @error('profile_picture')
                    <p class="invalid-feedback">{{ $message }}</p>
                  @enderror

                  <p class="small mt-1">Upload a clear profile picture. This will be shown on your public profile.</p>
                </div>
              </div>


              <!-- <h5 class="mt-4">Verify your identity</h5>
        <p class="small">We need to verify your ID before you can become an Escort.</p>
        <hr class="p-0 my-2">
        <div class="row mt-3">
        <div class="col-md-3 mb-4">
        <label for="photo_id_doc" class="form-label fw-bold small">Photo of your ID document</label>
        <input type="file" class="form-control custom-input @error('photo_id_doc') is-invalid @enderror"
        name="photo_id_doc" id="photo_id_doc" accept="image/*">
        @error('photo_id_doc')
        <p class="invalid-feedback">{{ $message }}</p>
        @enderror
        </div>
        <div class="col-md-3 mb-4"></div>
        <div class="col-md-3 mb-4">
        <label for="photo_id" class="form-label fw-bold small">Photo of you holding your ID</label>
        <input type="file" class="form-control custom-input @error('photo_id') is-invalid @enderror"
        name="photo_id" id="photo_id" accept="image/*">
        @error('photo_id')
        <p class="invalid-feedback">{{ $message }}</p>
        @enderror
        </div>
        <div class="col-md-3 mb-4"></div>
        <div class="col-md-12 mb-4">
        <p class="small">For your ID verification, we only accept an in-date government issued photo ID. We
        prefer a passport. The image must be clear and visible for verification. For your photo verification
        you should take a photo clearly showing your face, holding the ID document provided.</p>
        </div>
        </div> -->

              <h5 class="mt-4">Setup your profile</h5>
              <p class="small">All these details what you add here are going to related to your public profile.</p>
              <hr class="p-0 my-2">
              <div class="row mt-3">
                <div class="col-md-4 mb-4">
                  <label for="display_name" class="form-label fw-bold small">Display Name</label>
                  <input type="text" class="form-control custom-input @error('display_name') is-invalid @enderror"
                    name="display_name" id="display_name" value="{{ old('display_name') }}"
                    placeholder="Enter your display name">
                  @error('display_name')
                    <p class="invalid-feedback">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-4 mb-4">
                  <label for="age" class="form-label fw-bold small">Age to display</label>
                  <input type="number" class="form-control custom-input @error('age') is-invalid @enderror" name="age"
                    id="age" value="{{ old('age') }}" placeholder="Enter your age" readonly>
                  @error('age')
                    <p class="invalid-feedback">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-4 mb-4">
                  <label for="public_country_code" class="form-label fw-bold small">Country Code (for profile)</label>
                  <input type="string"
                    class="form-control custom-input @error('public_country_code') is-invalid @enderror"
                    name="public_country_code" id="public_country_code" value="{{ old('public_country_code') }}"
                    placeholder="Public profile country code">
                  @error('public_country_code')
                    <p class="invalid-feedback">{{ $message }}</p>
                  @enderror
                </div>
                <div class="col-md-12 mb-4">
                  <label class="form-label fw-bold small">Profile Category</label>
                  <div class="d-flex gap-2 flex-wrap" id="profile_category_group">
                    @foreach($categories as $index => $category)
                      <label class="btn btn-outline-dark category-label mb-2" for="category_id_{{ $category->id }}">
                        <input type="radio" id="category_id_{{ $category->id }}" name="category_id"
                          value="{{ $category->id }}" class="category-radio d-none" {{ old('category_id', $index === 0 ? $category->id : '') == $category->id ? 'checked' : '' }}>
                        {{ $category->name }}
                      </label>
                    @endforeach
                    @error('category_id')
                      <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                  </div>
                </div>
                <div class="col-md-12 mb-4">
                  <label for="introduction"
                    class="form-label fw-bold small @error('introduction') is-invalid @enderror">Your Introduction</label>
                  <textarea class="form-control custom-textarea " id="introduction" name="introduction"
                    rows="3">{{ old('introduction') }}</textarea>
                  @error('introduction')
                    <p class="text-danger small">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <h5 class="mt-4">Payment Settings</h5>
              <p class="small">In this section you need to add your monthly subscription.</p>
              <hr class="p-0 my-2">
              <div class="row mt-3 mb-5">
                @php
                  $price = old('subscription_price', 50); // Default value 50 if nothing submitted before
                @endphp
                <div class="col-md-12">
                  <label for="subscription_price" class="fw-bold small">
                    Subscription Prices
                    <span type="button" class="info_icon info_icon3"></span>
                  </label>
                  <div class="range-slider">
                    <input class="range-slider__range" type="range" id="subscription_price" name="subscription_price"
                      value="{{ $price }}" min="0" max="100">
                    <span class="range-slider__value">{{ $price }}</span>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <div class="form-check mb-2">
                  <input class="form-check-input custom-input-check shadow-none" type="checkbox" id="terms" name="terms">
                  <label class="form-check-label" for="terms">
                    I confirm that i am registering on the platform in order to generate income on an ongoing basis and
                    will be liable and responsible for my own tax reporting obligations.
                  </label>
                  @error('terms')
                    <p class="text-danger small">{{ $message }}</p>
                  @enderror
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input custom-input-check shadow-none" type="checkbox"
                    id="digital_services_only" name="digital_services_only">
                  <label class="form-check-label" for="digital_services_only">
                    I confirm that I will only use the service to receive funds for digital services and I am aware that
                    any attempt to use the service for physical services or in-person escort bookings will cause immediate
                    account termination and the instant refund of ALL client funds held by us.
                  </label>
                  @error('digital_services_only')
                    <p class="text-danger small">{{ $message }}</p>
                  @enderror
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input custom-input-check shadow-none" type="checkbox" id="promote_profile"
                    name="promote_profile">
                  <label class="form-check-label" for="promote_profile">
                    Yes, I want my profile to be promoted
                  </label>
                  @error('promote_profile')
                    <p class="text-danger small">{{ $message }}</p>
                  @enderror
                </div>
              </div>
              <div class="row mt-3">
                <div>
                  <button class="btn custom-btn" type="submit">
                    Submit
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
    document.addEventListener('DOMContentLoaded', function () {
      const radios = document.querySelectorAll('.category-radio');

      radios.forEach(radio => {
        radio.addEventListener('change', function () {
          // Remove 'active' class from all labels
          document.querySelectorAll('.category-label').forEach(label => {
            label.classList.remove('active');
          });

          // Add 'active' class to the selected one
          if (this.checked) {
            this.closest('label').classList.add('active');
          }
        });

        // Set default active state if one is pre-checked (e.g., after form validation error)
        if (radio.checked) {
          radio.closest('label').classList.add('active');
        }
      });
    });

    document.addEventListener('DOMContentLoaded', function () {
      const rangeInput = document.querySelector('.range-slider__range');
      const rangeValue = document.querySelector('.range-slider__value');

      // Initial value sync
      rangeValue.textContent = rangeInput.value;

      // On input change
      rangeInput.addEventListener('input', function () {
        rangeValue.textContent = this.value;
      });
    });


    document.addEventListener('DOMContentLoaded', function () {
      const dobInput = document.getElementById('dob');
      const ageInput = document.getElementById('age');

      // Set max date to today minus 18 years
      const today = new Date();
      const eighteenYearsAgo = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
      dobInput.max = eighteenYearsAgo.toISOString().split('T')[0];

      dobInput.addEventListener('change', function () {
        const dob = new Date(this.value);
        if (isValidDate(dob)) {
          const age = calculateAge(dob);
          ageInput.value = age;
        } else {
          ageInput.value = '';
        }
      });

      function calculateAge(dob) {
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
          age--;
        }
        return age;
      }

      function isValidDate(d) {
        return d instanceof Date && !isNaN(d);
      }
    });

  </script>


@endsection