@extends('layouts.app')
@section('content')

<<<<<<< HEAD
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
              <!-- /////  Profile Picture ///// -->

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
=======
<?php

$display_name = $userid->name;
$user_name = $userid->username;
$user_email = $userid->email;
$user_id = $userid->id;

?>

<link rel="stylesheet" href="{{ asset('css/user-register-style.css') }}">
<div class="container-fluid p-0 m-0 escort-register-page">
  <div class="container py-5">
    <div class="row">
      <div class="col-12 mx-auto">
        <div class="escort-register-inner shadow-sm p-5">
          <h5 class="h3">Personal Details</h5>
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
              <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                <label for="display_name" class="form-label fw-bold small">Display Name</label>
                <input type="text" class="form-control rounded-pill text-capitalize"
                name="display_name" id="display_name" value="{{ $display_name }}" readonly="">
              </div>
              <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                <label for="username" class="form-label fw-bold small">Username</label>
                <input type="text" class="form-control rounded-pill"
                name="username" id="username" value="{{ $user_name }}" readonly="">
              </div>
              <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                <label for="user_email" class="form-label fw-bold small">User Email</label>
                <input type="text" class="form-control rounded-pill"
                name="user_email" id="user_email" value="{{ $user_email }}" readonly="">
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
                <input type="date" class="form-control custom-input @error('dob') is-invalid @enderror" name="dob"
                id="dob" value="{{ old('dob') }}">
                <input type="hidden" class="form-contro is-invalid" name="age" id="age" value="{{ old('age') }}">
                @error('dob')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
              </div>
              <div class="col-sm-12 col-lg-3 col-md-3 mb-4">
                <label for="country_code" class="form-label fw-bold small">Country Code (Mobile)</label>
                <input type="number" class="form-control custom-input @error('country_code') is-invalid @enderror"
                name="country_code" id="country_code" value="{{ old('country_code') }}"
                placeholder="Country code">
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


              <div class="col-sm-12 col-md-12 mb-4">
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
            </div>
            <!-- !!!!!!! About profile !!!!!!! -->
            <h5 class="mt-4 h3">About profile</h5>
            <p class="small">All these details what you add here are going to related to your public profile.</p>
            <hr class="p-0 my-2">
            <div class="row mt-3">
              <div class="col-sm-12 col-lg-12 col-md-12 mb-4">
                <label for="about_me" class="form-label fw-bold small">About Me</label>
                <textarea 
                    class="form-control custom-textarea @error('about_me') is-invalid @enderror" 
                    id="about_me" 
                    name="about_me"
                    rows="3">{{ old('about_me') }}</textarea>
                
                @error('about_me')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

              
            </div>
            
            <!-- !!!!!!! Physical Details !!!!!!! -->
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
                      @for($i=40; $i<=120; $i++)
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
                    <option value="gray"  {{ old('eyes') == 'gray' ? 'selected' : '' }}>Gray</option>
                    <option value="blue"  {{ old('eyes') == 'blue' ? 'selected' : '' }}>Blue</option>
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
                      <option value="blond"  {{ old('hair') == 'blond' ? 'selected' : '' }}>Blond</option>
                      <option value="black"  {{ old('hair') == 'black' ? 'selected' : '' }}>Black</option>
                      <option value="brown"  {{ old('hair') == 'brown' ? 'selected' : '' }}>Brown</option>
                      <option value="red"    {{ old('hair') == 'red' ? 'selected' : '' }}>Red</option>
                      <option value="auburn" {{ old('hair') == 'auburn' ? 'selected' : '' }}>Auburn</option>
                      <option value="gray"   {{ old('hair') == 'gray' ? 'selected' : '' }}>Gray</option>
                      <option value="other"  {{ old('hair') == 'other' ? 'selected' : '' }}>Other</option>
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
            <!-- !!!!!!! Social Profiles !!!!!!! -->
            <h5 class="mt-4 h3">Social Profiles</h5>
            <p class="small">Connect your social media accounts to make your profile more engaging and trustworthy. Visitors will be able to see your social links on your public profile.</p>
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
                name="twitter_url" id="twitter_url" value="{{ old('twitter_url') }}"
                placeholder="Enter your Twitter URL">
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
                name="youtube_url" id="youtube_url" value="{{ old('youtube_url') }}"
                placeholder="Enter your YouTube URL">
                @error('youtube_url')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <!-- !!!!!!! Address Details !!!!!!! -->
            <h5 class="mt-4 h3">Address Details</h5>
            <p class="small">Please select your country, state, and city. This will help in localizing your profile.</p>
            <hr class="p-0 my-2">
            <div class="row mt-3">
              
              <!-- Country -->
            <div class="col-sm-12 col-lg-4 col-md-4 mb-4">
                <label for="country" class="form-label fw-bold small">Country</label>
                <select id="country" name="country" 
                        class="form-select custom-input @error('country') is-invalid @enderror">
                    <option value="">Loading countries...</option>
                </select>
                @error('country')
                    <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>

            <!-- State -->
            <div class="col-sm-12 col-lg-4 col-md-4 mb-4">
                <label for="state" class="form-label fw-bold small">State</label>
                <select id="state" name="state" 
                        class="form-select custom-input @error('state') is-invalid @enderror">
                    <option value="">Select Country First</option>
                </select>
                @error('state')
                    <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>

            <!-- City -->
            <div class="col-sm-12 col-lg-4 col-md-4 mb-4">
                <label for="city" class="form-label fw-bold small">City</label>
                <select id="city" name="city" 
                        class="form-select custom-input @error('city') is-invalid @enderror">
                    <option value="">Select State First</option>
                </select>
                @error('city')
                    <p class="invalid-feedback">{{ $message }}</p>
                @enderror
            </div>

            </div>
            <!--!!!!!!!  Payment Settings !!!!!!! -->
            <h5 class="mt-4 h3">Payment Settings</h5>
            <p class="small">In this section you need to add your monthly subscription.</p>
            <hr class="p-0 my-2">
            <div class="row mt-3">
              @php
              $price = old('subscription_price', 50); // Default value 50 if nothing submitted before
              @endphp
              <div class="col-sm-12 col-lg-12 col-md-12 mb-5">
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
              <div class="col-sm-12 col-lg-12 col-md-12 mb-4">
                <div class="form-check mb-2">
                  <input 
                      class="form-check-input custom-input-check shadow-none @error('terms') is-invalid @enderror" 
                      type="checkbox" 
                      id="terms" 
                      name="terms" 
                      value="1"
                      {{ old('terms') ? 'checked' : '' }}
                  >
                  <label class="form-check-label mx-0" for="terms">
                      I confirm that I am registering on the platform in order to generate income on an ongoing basis and
                      will be liable and responsible for my own tax reporting obligations.
                  </label>
                  @error('terms')
                      <p class="text-danger small">{{ $message }}</p>
                  @enderror
              </div>

              <div class="form-check mb-2">
                <input 
                    class="form-check-input custom-input-check shadow-none @error('digital_services_only') is-invalid @enderror" 
                    type="checkbox"
                    id="digital_services_only" 
                    name="digital_services_only" 
                    value="1"
                    {{ old('digital_services_only') ? 'checked' : '' }}
                >
                <label class="form-check-label mx-0" for="digital_services_only">
                    I confirm that I will only use the service to receive funds for digital services and I am aware that
                    any attempt to use the service for physical services or in-person escort bookings will cause immediate
                    account termination and the instant refund of ALL client funds held by us.
                </label>
                @error('digital_services_only')
                    <p class="text-danger small">{{ $message }}</p>
                @enderror
            </div>

              <div class="form-check mb-2">
                  <input 
                      class="form-check-input custom-input-check shadow-none @error('promote_profile') is-invalid @enderror" 
                      type="checkbox" 
                      id="promote_profile" 
                      name="promote_profile" 
                      value="1"
                      {{ old('promote_profile') ? 'checked' : '' }}
                  >
                  <label class="form-check-label mx-0" for="promote_profile">
                      Yes, I want my profile to be promoted
                  </label>
                  @error('promote_profile')
                      <p class="text-danger small">{{ $message }}</p>
                  @enderror
              </div>

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
>>>>>>> 23c30d7 (Escort project)
        </div>
      </div>
    </div>
  </div>
<<<<<<< HEAD

=======
</div>
>>>>>>> 23c30d7 (Escort project)

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

<<<<<<< HEAD
=======
<script>
$(document).ready(function () {
    // Load Countries on page load
    $.getJSON("/get-countries", function (data) {
        let options = '<option value="">Select Country</option>';
        $.each(data, function (key, country) {
            options += `<option value="${country.id}">${country.name}</option>`;
        });
        $("#country").html(options);
    });

    // Load States when Country changes
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

    // Load Cities when State changes
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
</script>

>>>>>>> 23c30d7 (Escort project)

@endsection