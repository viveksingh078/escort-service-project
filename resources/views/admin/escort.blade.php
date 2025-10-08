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
<<<<<<< HEAD
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
=======

      <form id="escortForm" action="{{ route('escort.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Step 1: Basic Registration -->
        <div id="step1" class="step">
          <h4>Escort Register</h4>
          <div class="form-group">
            <label>Display Name</label>
            <input type="text" name="display_name"
              class="form-control rounded-pill @error('display_name') is-invalid @enderror"
              value="{{ old('display_name') }}" placeholder="Enter your display name">
>>>>>>> 23c30d7 (Escort project)
            @error('display_name')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
<<<<<<< HEAD
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
=======
          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control rounded-pill @error('username') is-invalid @enderror"
              value="{{ old('username') }}" placeholder="Enter your username">
            @error('username')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control rounded-pill @error('email') is-invalid @enderror"
              value="{{ old('email') }}" placeholder="Enter your email address">
            @error('email')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password"
              class="form-control rounded-pill @error('password') is-invalid @enderror" placeholder="Enter your password">
            @error('password')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation"
              class="form-control rounded-pill @error('password_confirmation') is-invalid @enderror"
              placeholder="Confirm your password">
            @error('password_confirmation')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-check">
            <input type="checkbox" name="terms" class="form-check-input @error('terms') is-invalid @enderror" {{ old('terms') ? 'checked' : '' }}>
            <label class="form-check-label">Confirm Our Terms and Conditions</label>
            @error('terms')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-check">
            <input type="checkbox" name="over_18" class="form-check-input @error('over_18') is-invalid @enderror" {{ old('over_18') ? 'checked' : '' }}>
            <label class="form-check-label">I confirm that I am over 18 years old</label>
            @error('over_18')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <button type="button" class="btn btn-primary" onclick="nextStep(1)">Continue </button>
        </div>

        <!-- Step 2: All Remaining Details -->
        <div id="step2" class="step" style="display: none;">
          <h5 class="h3">Personal Details</h5>
          <p class="small">
            You must use your full, real name exactly as displayed on your passport, driving license or government issued
            Identity Card. This is used for admin purposes only and is not shown publicly.
          </p>
          <hr class="p-0 my-2">

          <div class="row mt-3">
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="display_name" class="form-label fw-bold small">Display Name</label>
              <input type="text" class="form-control rounded-pill text-capitalize" id="display_name"
                value="{{ old('display_name') }}" readonly>
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="username" class="form-label fw-bold small">Username</label>
              <input type="text" class="form-control rounded-pill" id="username" value="{{ old('username') }}" readonly>
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="user_email" class="form-label fw-bold small">User Email</label>
              <input type="text" class="form-control rounded-pill" id="user_email" value="{{ old('email') }}" readonly>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="first_name" class="form-label fw-bold small">First Name</label>
              <input type="text" class="form-control custom-input @error('first_name') is-invalid @enderror"
                name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder="Enter your first name">
              @error('first_name')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="last_name" class="form-label fw-bold small">Last Name</label>
              <input type="text" class="form-control custom-input @error('last_name') is-invalid @enderror"
                name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder="Enter your last name">
              @error('last_name')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="dob" class="form-label fw-bold small">Date of Birth</label>
              <input type="date" class="form-control custom-input @error('dob') is-invalid @enderror" name="dob" id="dob"
                value="{{ old('dob') }}">
              <input type="hidden" class="form-control" name="age" id="age" value="{{ old('age') }}">
              @error('dob')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="country_code" class="form-label fw-bold small">Country Code (Mobile)</label>
              <input type="number" class="form-control custom-input @error('country_code') is-invalid @enderror"
                name="country_code" id="country_code" value="{{ old('country_code') }}" placeholder="Country code">
              @error('country_code')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="phone_number" class="form-label fw-bold small">Phone Number</label>
              <input type="text" class="form-control custom-input @error('phone_number') is-invalid @enderror"
                name="phone_number" id="phone_number" value="{{ old('phone_number') }}"
                placeholder="Enter your phone number">
              @error('phone_number')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="whatsapp_number" class="form-label fw-bold small">Whatsapp Number</label>
              <input type="text" class="form-control custom-input @error('whatsapp_number') is-invalid @enderror"
                name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}"
                placeholder="Enter your whatsapp number">
              @error('whatsapp_number')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="profile_picture" class="form-label fw-bold small">Profile Picture</label>
              <input type="file" class="form-control custom-input @error('profile_picture') is-invalid @enderror"
                name="profile_picture" id="profile_picture" accept="image/*">
              @error('profile_picture')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="profile_banner" class="form-label fw-bold small">Profile Banner</label>
              <input type="file" class="form-control custom-input @error('profile_banner') is-invalid @enderror"
                name="profile_banner" id="profile_banner" accept="image/*">
              @error('profile_banner')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Profile Category -->
          <h5 class="h3 mt-4">Profile Category</h5>
          <div class="category-buttons">
            @foreach($categories as $cat)
              <label class="btn btn-outline-secondary category-label">
                <input type="radio" name="category_id" value="{{ $cat->id }}" class="category-radio" {{ old('category_id') == $cat->id ? 'checked' : '' }}> {{ $cat->name }}
              </label>
            @endforeach
>>>>>>> 23c30d7 (Escort project)
            @error('category_id')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
<<<<<<< HEAD
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
=======

          <!-- About Profile -->
          <h5 class="mt-4 h3">About Profile</h5>
          <p class="small">All these details what you add here are going to related to your public profile.</p>
          <hr class="p-0 my-2">
          <div class="row mt-3">
            <div class="col-sm-12 col-lg-12 col-md-12 mb-4">
              <label for="about_me" class="form-label fw-bold small">About Me</label>
              <textarea class="form-control custom-textarea @error('about_me') is-invalid @enderror" id="about_me"
                name="about_me" rows="3">{{ old('about_me') }}</textarea>
              @error('about_me')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Physical Details -->
          <h5 class="mt-4 h3">Physical Details</h5>
          <p class="small">Add your physical details. These will appear on your public profile.</p>
          <hr class="p-0 my-2">
          <div class="row mt-3">
            <!-- Height -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="height" class="form-label fw-bold small">Height</label>
              <select id="height" name="height" class="form-select custom-input @error('height') is-invalid @enderror">
                <option value="">Select Height</option>
                @for($i = 140; $i <= 210; $i++)
                  <option value="{{ $i }}" {{ old('height') == $i ? 'selected' : '' }}>
                    {{ $i }} cm
                  </option>
                @endfor
              </select>
              @error('height')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Dress -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="dress" class="form-label fw-bold small">Dress</label>
              <select id="dress" name="dress" class="form-select custom-input @error('dress') is-invalid @enderror">
                <option value="">Select Dress Size</option>
                @for($i = 1; $i <= 20; $i++)
                  <option value="{{ $i }}" {{ old('dress') == $i ? 'selected' : '' }}>
                    {{ $i }}
                  </option>
                @endfor
              </select>
              @error('dress')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Weight -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="weight" class="form-label fw-bold small">Weight</label>
              <select id="weight" name="weight" class="form-select custom-input @error('weight') is-invalid @enderror">
                <option value="">Select Weight</option>
                @for($i = 40; $i <= 120; $i++)
                  <option value="{{ $i }}" {{ old('weight') == $i ? 'selected' : '' }}>
                    {{ $i }} kg
                  </option>
                @endfor
              </select>
              @error('weight')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Bust -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="bust" class="form-label fw-bold small">Bust</label>
              <select id="bust" name="bust" class="form-select custom-input @error('bust') is-invalid @enderror">
                <option value="">Select Bust Size</option>
                @for($i = 70; $i <= 120; $i++)
                  <option value="{{ $i }}" {{ old('bust') == $i ? 'selected' : '' }}>
                    {{ $i }} cm
                  </option>
                @endfor
              </select>
              @error('bust')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Waist -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="waist" class="form-label fw-bold small">Waist</label>
              <select id="waist" name="waist" class="form-select custom-input @error('waist') is-invalid @enderror">
                <option value="">Select Waist Size</option>
                @for($i = 40; $i <= 100; $i++)
                  <option value="{{ $i }}" {{ old('waist') == $i ? 'selected' : '' }}>
                    {{ $i }} cm
                  </option>
                @endfor
              </select>
              @error('waist')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Eyes -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="eyes" class="form-label fw-bold small">Eyes</label>
              <select id="eyes" name="eyes" class="form-select custom-input @error('eyes') is-invalid @enderror">
                <option value="">Select Eye Color</option>
                <option value="gray" {{ old('eyes') == 'gray' ? 'selected' : '' }}>Gray</option>
                <option value="blue" {{ old('eyes') == 'blue' ? 'selected' : '' }}>Blue</option>
                <option value="green" {{ old('eyes') == 'green' ? 'selected' : '' }}>Green</option>
                <option value="hazel" {{ old('eyes') == 'hazel' ? 'selected' : '' }}>Hazel</option>
                <option value="brown" {{ old('eyes') == 'brown' ? 'selected' : '' }}>Brown</option>
                <option value="black" {{ old('eyes') == 'black' ? 'selected' : '' }}>Black</option>
              </select>
              @error('eyes')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Hair -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="hair" class="form-label fw-bold small">Hair</label>
              <select id="hair" name="hair" class="form-select custom-input @error('hair') is-invalid @enderror">
                <option value="">Select Hair Color</option>
                <option value="blond" {{ old('hair') == 'blond' ? 'selected' : '' }}>Blond</option>
                <option value="black" {{ old('hair') == 'black' ? 'selected' : '' }}>Black</option>
                <option value="brown" {{ old('hair') == 'brown' ? 'selected' : '' }}>Brown</option>
                <option value="red" {{ old('hair') == 'red' ? 'selected' : '' }}>Red</option>
                <option value="auburn" {{ old('hair') == 'auburn' ? 'selected' : '' }}>Auburn</option>
                <option value="gray" {{ old('hair') == 'gray' ? 'selected' : '' }}>Gray</option>
                <option value="other" {{ old('hair') == 'other' ? 'selected' : '' }}>Other</option>
              </select>
              @error('hair')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Hips -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="hips" class="form-label fw-bold small">Hips</label>
              <select id="hips" name="hips" class="form-select custom-input @error('hips') is-invalid @enderror">
                <option value="">Select Hip Size</option>
                @for($i = 70; $i <= 120; $i++)
                  <option value="{{ $i }}" {{ old('hips') == $i ? 'selected' : '' }}>
                    {{ $i }} cm
                  </option>
                @endfor
              </select>
              @error('hips')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Shoe -->
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="shoe" class="form-label fw-bold small">Shoe</label>
              <select id="shoe" name="shoe" class="form-select custom-input @error('shoe') is-invalid @enderror">
                <option value="">Select Shoe Size</option>
                @for($i = 4; $i <= 12; $i += 0.5)
                  <option value="{{ $i }}" {{ old('shoe') == $i ? 'selected' : '' }}>
                    {{ $i }}
                  </option>
                @endfor
              </select>
              @error('shoe')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>

          <!-- Social Profiles -->
          <h5 class="mt-4 h3">Social Profiles</h5>
          <p class="small">Connect your social media accounts to make your profile more engaging and trustworthy. Visitors
            will be able to see your social links on your public profile.</p>
          <hr class="p-0 my-2">
          <div class="row mt-3">
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="facebook_url" class="form-label fw-bold small">
                <i class="fab fa-facebook text-primary"></i> Facebook
              </label>
              <input type="url" class="form-control custom-input @error('facebook_url') is-invalid @enderror"
                name="facebook_url" id="facebook_url" value="{{ old('facebook_url') }}"
                placeholder="Enter your Facebook URL">
              @error('facebook_url')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="twitter_url" class="form-label fw-bold small">
                <i class="fab fa-twitter text-info"></i> Twitter
              </label>
              <input type="url" class="form-control custom-input @error('twitter_url') is-invalid @enderror"
                name="twitter_url" id="twitter_url" value="{{ old('twitter_url') }}" placeholder="Enter your Twitter URL">
              @error('twitter_url')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="linkedin_url" class="form-label fw-bold small">
                <i class="fab fa-linkedin text-primary"></i> LinkedIn
              </label>
              <input type="url" class="form-control custom-input @error('linkedin_url') is-invalid @enderror"
                name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url') }}"
                placeholder="Enter your LinkedIn URL">
              @error('linkedin_url')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
              <label for="youtube_url" class="form-label fw-bold small">
                <i class="fab fa-youtube text-danger"></i> YouTube
              </label>
              <input type="url" class="form-control custom-input @error('youtube_url') is-invalid @enderror"
                name="youtube_url" id="youtube_url" value="{{ old('youtube_url') }}" placeholder="Enter your YouTube URL">
              @error('youtube_url')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Address Details -->
          <h5 class="mt-4 h3">Address Details</h5>
          <p class="small">Please select your country, state, and city. This will help in localizing your profile.</p>
          <hr class="p-0 my-2">
          <div class="row mt-3">
            <!-- Country -->
            <div class="col-sm-12 col-lg-4 col-md-4 mb-4">
              <label for="country" class="form-label fw-bold small">Country</label>
              <select id="country" name="country" class="form-select custom-input @error('country') is-invalid @enderror">
                <option value="">Loading countries...</option>
              </select>
              @error('country')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <!-- State -->
            <div class="col-sm-12 col-lg-4 col-md-4 mb-4">
              <label for="state" class="form-label fw-bold small">State</label>
              <select id="state" name="state" class="form-select custom-input @error('state') is-invalid @enderror">
                <option value="">Select Country First</option>
              </select>
              @error('state')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
            <!-- City -->
            <div class="col-sm-12 col-lg-4 col-md-4 mb-4">
              <label for="city" class="form-label fw-bold small">City</label>
              <select id="city" name="city" class="form-select custom-input @error('city') is-invalid @enderror">
                <option value="">Select State First</option>
              </select>
              @error('city')
                <p class="invalid-feedback">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <!-- Payment Settings -->
          <h5 class="h3 mt-4">Payment Settings</h5>
          <p class="small">In this section you need to add your monthly subscription.</p>
          <div class="range-slider">
            <label for="subscription_price">Subscription Prices</label>
            <input type="range" name="subscription_price" id="subscription_price"
              class="range-slider__range @error('subscription_price') is-invalid @enderror" min="0" max="100"
              value="{{ old('subscription_price', 50) }}" step="1">
            <span class="range-slider__value">{{ old('subscription_price', 50) }}</span>
>>>>>>> 23c30d7 (Escort project)
            @error('subscription_price')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
<<<<<<< HEAD
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
=======
          <div class="form-check mb-2">
            <input type="checkbox" name="confirm_income" id="confirm_income"
              class="form-check-input @error('confirm_income') is-invalid @enderror" value="1" {{ old('confirm_income') ? 'checked' : '' }}>
            <label for="confirm_income">I confirm that I am registering on the platform in order to generate income on
              an ongoing basis and will be liable and responsible for my own tax reporting obligations.</label>
            @error('confirm_income')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-check mb-2">
            <input type="checkbox" name="digital_services_only" id="digital_services_only"
              class="form-check-input @error('digital_services_only') is-invalid @enderror" value="1" {{ old('digital_services_only') ? 'checked' : '' }}>
            <label for="digital_services_only">I confirm that I will only use the service to receive funds for digital
              services and I am aware that any attempt to use the service for physical services or in-person escort
              bookings will cause immediate account termination and the instant refund of ALL client funds held by
              us.</label>
            @error('digital_services_only')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-check mb-2">
            <input type="checkbox" name="promote_profile" id="promote_profile"
              class="form-check-input @error('promote_profile') is-invalid @enderror" value="1" {{ old('promote_profile') ? 'checked' : '' }}>
            <label for="promote_profile">Yes, I want my profile to be promoted</label>
            @error('promote_profile')
              <p class="invalid-feedback">{{ $message }}</p>
            @enderror
          </div>

          <button type="button" class="btn btn-secondary" onclick="prevStep(2)">Back</button>
          <button type="submit" class="btn btn-primary">Submit</button>
>>>>>>> 23c30d7 (Escort project)
        </div>
      </form>
    </div>
  </div>

  <script>
<<<<<<< HEAD
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
=======
    function nextStep(current) {
      let isValid = true;
      if (current === 1) {
        const displayName = document.querySelector('input[name="display_name"]').value;
        const username = document.querySelector('input[name="username"]').value;
        const email = document.querySelector('input[name="email"]').value;
        const password = document.querySelector('input[name="password"]').value;
        const confirmPassword = document.querySelector('input[name="password_confirmation"]').value;
        const terms = document.querySelector('input[name="terms"]').checked;
        const over18 = document.querySelector('input[name="over_18"]').checked;

        if (!displayName || !username || !email || !password || !confirmPassword || !terms || !over18 || password !== confirmPassword) {
          isValid = false;
          alert('Please fill all fields, ensure passwords match, and accept terms.');
        }
      }

      if (isValid) {
        document.getElementById('step' + current).style.display = 'none';
        document.getElementById('step' + (current + 1)).style.display = 'block';
        if (current === 1) {
          document.getElementById('display_name').value = document.querySelector('input[name="display_name"]').value;
          document.getElementById('username').value = document.querySelector('input[name="username"]').value;
          document.getElementById('user_email').value = document.querySelector('input[name="email"]').value;
        }
      }
    }

    function prevStep(current) {
      document.getElementById('step' + current).style.display = 'none';
      document.getElementById('step' + (current - 1)).style.display = 'block';
    }

    // Category active class
    document.addEventListener('DOMContentLoaded', function () {
      const radios = document.querySelectorAll('.category-radio');
      radios.forEach(radio => {
        radio.addEventListener('change', function () {
          document.querySelectorAll('.category-label').forEach(label => {
            label.classList.remove('active');
          });
          if (this.checked) {
            this.closest('label').classList.add('active');
          }
        });
        if (radio.checked) {
          radio.closest('label').classList.add('active');
        }
      });
    });

    // Range slider
    document.addEventListener('DOMContentLoaded', function () {
      const rangeInput = document.querySelector('.range-slider__range');
      const rangeValue = document.querySelector('.range-slider__value');
      rangeValue.textContent = rangeInput.value;
      rangeInput.addEventListener('input', function () {
        rangeValue.textContent = this.value;
      });
    });

    // DOB age calculate
    document.addEventListener('DOMContentLoaded', function () {
      const dobInput = document.getElementById('dob');
      const ageInput = document.getElementById('age');
      const today = new Date();
      const eighteenYearsAgo = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
      dobInput.max = eighteenYearsAgo.toISOString().split('T')[0];

      dobInput.addEventListener('change', function () {
        const dob = new Date(this.value);
        if (!isNaN(dob)) {
          const age = calculateAge(dob);
>>>>>>> 23c30d7 (Escort project)
          ageInput.value = age;
        } else {
          ageInput.value = '';
        }
      });

<<<<<<< HEAD
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
=======
      function calculateAge(dob) {
        const today = new Date();
        let age = today.getFullYear() - dob.getFullYear();
        const m = today.getMonth() - dob.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
          age--;
        }
        return age;
      }
    });

    // Country State City AJAX
    $(document).ready(function () {
      $.getJSON("/get-countries", function (data) {
        let options = '<option value="">Select Country</option>';
        $.each(data, function (key, country) {
          options += `<option value="${country.id}">${country.name}</option>`;
        });
        $("#country").html(options);
      });

      $("#country").on("change", function () {
        let countryId = $(this).val();
        $("#state").html('<option value="">Loading...</option>');
        $("#city").html('<option value="">Select State First</option>');

        if (countryId) {
          $.getJSON("/get-states/" + countryId, function (data) {
            let options = '<option value="">Select State</option>';
            $.each(data, function (key, state) {
              options += `<option value="${state.id}">${state.name}</option>`;
            });
            $("#state").html(options);
          });
        }
      });

      $("#state").on("change", function () {
        let stateId = $(this).val();
        $("#city").html('<option value="">Loading...</option>');

        if (stateId) {
          $.getJSON("/get-cities/" + stateId, function (data) {
            let options = '<option value="">Select City</option>';
            $.each(data, function (key, city) {
              options += `<option value="${city.id}">${city.name}</option>`;
            });
            $("#city").html(options);
          });
        }
      });
    });

    // Form submit via AJAX
    // Form submit via AJAX (existing script ke end mein replace kar)
    document.getElementById('escortForm').addEventListener('submit', function (e) {
      e.preventDefault();
      let formData = new FormData(this);

      // Clear previous errors
      document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
      document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

      fetch('{{ route('escort.store') }}', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Escort added successfully!');
            window.location.href = '{{ route('admin.escort.manage') }}';
          } else {
            // Show errors next to fields
            if (data.errors) {
              Object.keys(data.errors).forEach(field => {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                  input.classList.add('is-invalid');
                  input.closest('.form-group, .col-sm-12').insertAdjacentHTML('beforeend', `<p class="invalid-feedback d-block">${data.errors[field][0]}</p>`);
                }
              });
            } else {
              alert('Error: Something went wrong');
            }
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error submitting form. Check console.');
        });
    });
  </script>

  <link rel="stylesheet" href="{{ asset('css/user-register-style.css') }}">
>>>>>>> 23c30d7 (Escort project)
@endsection