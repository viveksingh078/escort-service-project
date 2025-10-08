@extends('layouts.app')
@section('title', 'Listing Profile')
@section('content')


  <?php

  // echo "<pre>";
  // print_r($data);
  // echo "</pre>";

                                       ?>

  <link rel="stylesheet" href="{{ asset('css/single-profile-style.css') }}">

  <style>
    .blurred {
      filter: blur(5px);
      pointer-events: none;
      /* Click na ho direct */
    }

    .blur-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 20px;
      cursor: pointer;
      z-index: 10;
    }

    .details-blur {
      filter: blur(3px);
    }

    .position-relative {
      position: relative !important;
    }

    /* Ensure overlay works */
  </style>

  <?php
  // Check if user has paid for this escort
  use App\Models\Billing;
  use Illuminate\Support\Facades\Auth;

  $hasPaid = false;
  if (Auth::check() && Auth::user()->role == 'fan') {
    $hasPaid = Billing::where('fan_id', Auth::id())
      ->where('escort_id', $data['id'])  // Assume $data['id'] is escort ID
      ->where('status', 'paid')
      ->exists();
  }
                                      ?>

  <div class="container-fluid py-5 single-profile-page">
    <div class="container py-5">
      <div class="row">
        <div class="col-sm-12 col-lg-4 col-md-4">

          <div class="profile-card">
            <div class="card bg-white border-0 shadow-none">
              <div class="card-header p-0 m-0">
                <img src="{{ asset("storage/{$data['profile_picture']}") }}" class="profile-card-img card-img" alt="">
              </div>
              <div class="card-body py-5 position-relative">

                <div class="{{ !$hasPaid ? 'details-blur' : '' }}">

                  @if(!empty($data['age']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Age</h5>
                      <h5 class="card-title text-uppercase m-0">{{ $data['age'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['first_name']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Name</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['first_name'] }} {{ $data['last_name'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['Gender']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Gender</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['Gender'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['height']))
                    @php
                      // Assume height is stored in centimeters (e.g., 175) or as string like "5.9"
                      $height = $data['height'];

                      // If height is in centimeters
                      if (is_numeric($height) && $height > 100) {
                        $feet = floor($height / 30.48);
                        $inches = round(($height / 2.54) - ($feet * 12));
                        $formattedHeight = $feet . ',' . $inches;
                      } else {
                        // If already given in feet.inches (like 5.9)
                        $parts = explode('.', $height);
                        $formattedHeight = $parts[0] . ',' . ($parts[1] ?? '0');
                      }
                    @endphp

                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Height</h5>
                      <h5 class="card-title text-uppercase m-0">{{ $formattedHeight }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif


                  @if(!empty($data['dress']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Dress</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['dress'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['weight']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Weight</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['weight'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['bust']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Bust</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['bust'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['waist']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Waist</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['waist'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['eyes']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Eyes</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['eyes'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['hair']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Hair</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['hair'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['hips']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Hips</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['hips'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['shoe']))
                    <div class="d-flex justify-content-between align-items-center">
                      <h5 class="card-title text-uppercase m-0">Shoe</h5>
                      <h5 class="card-title text-capitalize m-0">{{ $data['shoe'] }}</h5>
                    </div>
                    <hr class="text-white">
                  @endif

                  @if(!empty($data['about_me']))
                    <p class="p-0 m-0 py-4">
                      {{ $data['about_me'] }}
                    </p>
                  @endif

                  <div class="social-links">
                    <a href="{{ $data['facebook_url'] ?? '#' }}" class="social-link">
                      <i class="fab fa-facebook pro-social-icon"></i>
                    </a>
                    <a href="{{ $data['twitter_url'] ?? '#' }}" class="social-link">
                      <i class="fab fa-twitter pro-social-icon"></i>
                    </a>
                    <a href="{{ $data['linkedin_url'] ?? '#' }}" class="social-link">
                      <i class="fab fa-linkedin pro-social-icon"></i>
                    </a>
                    <a href="{{ $data['youtube_url'] ?? '#' }}" class="social-link">
                      <i class="fab fa-youtube pro-social-icon"></i>
                    </a>
                  </div>

                </div>

                @if(!$hasPaid)
                  <a class="blur-overlay text-decoration-none text-white"
                    href="{{ Auth::guard('fan')->check() ? route('choose.plan', ['escort_id' => $data['id']]) : route('fan.register') }}">

                  </a>
                @endif

              </div>
            </div>
          </div>

        </div>

        <div class="col-sm-12 col-lg-8 col-md-8 profile-card-right">

          <div class="profile-card-right-inner">

            <div class=" grid-margin stretch-card">
              <div class="card shadow-none bg-transparent">
                <div class="card-body position-relative">
                  {{-- NAME --}}
                  @if(!empty($data['name']))
                    <h4 class="card-title p-0 m-0 text-lowercase {{ !$hasPaid ? 'details-blur' : '' }}">
                      <i class="fa-solid fa-user text-white mr-2"></i>
                      {{ $data['name'] }}
                    </h4>
                  @endif

                  @if(!$hasPaid && !empty($data['name']))
                    <a class="blur-overlay text-decoration-none text-white"
                      href="{{ Auth::guard('fan')->check() ? route('choose.plan', ['escort_id' => $data['id']]) : route('fan.register') }}"></a>
                  @endif

                  {{-- PHONE + WHATSAPP --}}
                  @if(!empty($data['country_code']) && !empty($data['phone_number']))
                    <div class="d-flex my-3 align-items-center {{ !$hasPaid ? 'details-blur' : '' }}">
                      <i class="fa-solid fa-phone text-white mr-2"></i>
                      <p class="text-white m-0">+{{ $data['country_code'] }} {{ $data['phone_number'] }}</p>

                      <span class="mx-2"></span> {{-- spacing between phone and WhatsApp --}}

                      <i class="fa-brands fa-whatsapp text-white mr-2"></i>
                      <p class="text-white m-0">+{{ $data['country_code'] }} {{ $data['phone_number'] }}</p>
                    </div>
                  @endif

                  @if(!$hasPaid && !empty($data['country_code']))
                    <a class="blur-overlay text-decoration-none text-white"
                      href="{{ Auth::guard('fan')->check() ? route('choose.plan', ['escort_id' => $data['id']]) : route('fan.register') }}">
                    </a>
                  @endif


                  {{-- EMAIL --}}
                  @if(!empty($data['email']))
                    <div class="d-flex my-3 align-items-center {{ !$hasPaid ? 'details-blur' : '' }}">
                      <i class="fa-solid fa-envelope text-white mr-2"></i>
                      <p class="text-white m-0">{{ $data['email'] }}</p>
                    </div>
                  @endif

                  @if(!$hasPaid && !empty($data['email']))
                    <a class="blur-overlay text-decoration-none text-white"
                      href="{{ Auth::guard('fan')->check() ? route('choose.plan', ['escort_id' => $data['id']]) : route('fan.register') }}"></a>
                  @endif

                  {{-- LOCATION --}}
                  @if(!empty($data['country']) || !empty($data['state']) || !empty($data['city']))
                    <div class="d-flex my-3 align-items-center {{ !$hasPaid ? 'details-blur' : '' }}">
                      <i class="fa-solid fa-location-dot text-white mr-2"></i>
                      <p class="text-white m-0">
                        {{ $data['country'] ?? '' }}
                        @if(!empty($data['state'])), {{ $data['state'] }}@endif
                        @if(!empty($data['city'])), {{ $data['city'] }}@endif
                      </p>
                    </div>
                  @endif

                  @if(!$hasPaid && (!empty($data['country']) || !empty($data['state']) || !empty($data['city'])))
                    <a class="blur-overlay text-decoration-none text-white"
                      href="{{ Auth::guard('fan')->check() ? route('choose.plan', ['escort_id' => $data['id']]) : route('fan.register') }}"></a>
                  @endif

                  <hr class="text-white ">
                </div>
              </div>
            </div>

            {{-- ================== Photos ================== --}}
            <div class=" grid-margin stretch-card">
              <div class="card shadow-none bg-transparent">
                <div class="card-body">
                  <h4 class="card-title">Photos</h4>
                  <hr class="text-white">
                  <div class="row">
                    @foreach(array_slice($photos, 0, 7) as $photo)
                      <div class="col-sm-6 col-md-3 col-lg-3 mb-4 position-relative">
                        <a data-fancybox="gallery" href="{{ asset($photo['file_path']) }}"
                          data-caption="{{ $photo['title'] ?? '' }}">
                          <img src="{{ asset($photo['file_path']) }}" alt="{{ $photo['title'] ?? '' }}"
                            class="img-fluid rounded shadow {{ !$hasPaid ? 'blurred' : '' }}"
                            style="height:140px; object-fit:cover;">
                        </a>
                        @if(!$hasPaid)
                          <a class="blur-overlay text-decoration-none text-white"
                            href="{{ Auth::guard('fan')->check() ? route('choose.plan', ['escort_id' => $data['id']]) : route('fan.register') }}"></a>
                        @endif
                      </div>
                    @endforeach
                    @if(count($photos) > 7)
                      {{-- + More Photos Card --}}
                      <div
                        class="col-sm-6 col-md-3 col-lg-3 mb-4 d-flex align-items-center justify-content-center position-relative">
                        <div
                          class="more-box bg-light rounded shadow text-center w-100 h-100 d-flex align-items-center justify-content-center"
                          style="height:140px; cursor:pointer;" id="viewMorePhotos">
                          <span class="fw-bold">+ {{ count($photos) - 7 }} More</span>
                        </div>
                        @if(!$hasPaid)
                          <a class="blur-overlay text-decoration-none text-white"
                            href="{{ Auth::guard('fan')->check() ? route('choose.plan', ['escort_id' => $data['id']]) : route('fan.register') }}"></a>
                        @endif
                      </div>
                    @endif
                  </div>
                  {{-- Hidden extra photos --}}
                  @foreach(array_slice($photos, 7) as $photo)
                    <a data-fancybox="gallery" href="{{ asset($photo['file_path']) }}"
                      data-caption="{{ $photo['title'] ?? '' }}" class="d-none">
                      <img src="{{ asset($photo['file_path']) }}" alt="{{ $photo['title'] ?? '' }}">
                    </a>
                  @endforeach

                </div>
              </div>
            </div>
            {{-- ================== Videos ================== --}}
            <div class="grid-margin stretch-card">
              <div class="card bg-transparent shadow-none">
                <div class="card-body">
                  <h4 class="card-title">Videos</h4>
                  <hr class="text-white">
                  <div class="row">
                    @foreach(array_slice($videos, 0, 7) as $video)
                      <div class="col-sm-6 col-md-3 col-lg-3 mb-4 position-relative">
                        <a data-fancybox="videos" href="{{ asset($video['file_path']) }}"
                          data-caption="{{ $video['title'] ?? '' }}" data-type="video">
                          <img
                            src="{{ $video['thumbnail_path'] ? asset($video['thumbnail_path']) : asset('default-video-thumbnail.jpg') }}"
                            alt="{{ $video['title'] ?? '' }}"
                            class="img-fluid rounded shadow {{ !$hasPaid ? 'blurred' : '' }}"
                            style="height:140px; object-fit:cover;">
                          {{-- Play icon overlay --}}
                          <span class="position-absolute top-50 start-50 translate-middle text-white"
                            style="font-size:35px; pointer-events:none;">
                            <i class="fas fa-play-circle text-dark" style="font-size: 35px;"></i>
                          </span>
                        </a>
                        @if(!$hasPaid)
                          <a class="blur-overlay text-decoration-none text-white"
                            href="{{ Auth::guard('fan')->check() ? route('choose.plan', ['escort_id' => $data['id']]) : route('fan.register') }}"></a>
                        @endif
                      </div>
                    @endforeach
                    @if(count($videos) > 7)
                      {{-- + More Videos Card --}}
                      <div
                        class="col-sm-6 col-md-3 col-lg-3 mb-4 d-flex align-items-center justify-content-center position-relative">
                        <div
                          class="more-box bg-light rounded shadow text-center w-100 h-100 d-flex align-items-center justify-content-center"
                          style="height:140px; cursor:pointer;" id="viewMoreVideos">
                          <span class="fw-bold">+ {{ count($videos) - 7 }} More</span>
                        </div>
                        @if(!$hasPaid)
                          <div class="blur-overlay" data-bs-toggle="modal" data-bs-target="#paymentModal">

                          </div>
                        @endif
                      </div>
                    @endif
                  </div>
                  {{-- Hidden extra videos --}}
                  @foreach(array_slice($videos, 7) as $video)
                    <a data-fancybox="videos" href="{{ asset($video['file_path']) }}"
                      data-caption="{{ $video['title'] ?? '' }}" data-type="video" class="d-none">
                      <img
                        src="{{ $video['thumbnail_path'] ? asset($video['thumbnail_path']) : asset('default-video-thumbnail.jpg') }}"
                        alt="{{ $video['title'] ?? '' }}">
                    </a>
                  @endforeach
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>



  {{-- ================== Fancybox Scripts ================== --}}
  <script>
    Fancybox.bind('[data-fancybox="gallery"]', {
      Toolbar: { display: ["close"] }
    });
    Fancybox.bind('[data-fancybox="videos"]', {
      Toolbar: { display: ["close", "play"] },
      Video: { autoplay: false, controls: true }
    });
    // View More Photos
    document.getElementById("viewMorePhotos")?.addEventListener("click", function () {
      Fancybox.show(document.querySelectorAll('[data-fancybox="gallery"]'), {
        startIndex: 7
      });
    });
    // View More Videos
    document.getElementById("viewMoreVideos")?.addEventListener("click", function () {
      Fancybox.show(document.querySelectorAll('[data-fancybox="videos"]'), {
        startIndex: 7
      });
    });
  </script>


  <!-- Payment Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title" id="paymentModalLabel">
            Payment for Your Chosen Escort: {{ $data['name'] }}
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('billing') }}" method="POST">
            @csrf
            <input type="hidden" name="escort_id" value="{{ $data['id'] }}">

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="zip_code" class="form-label">Zip Code</label>
                <input type="text" class="form-control" id="zip_code" name="zip_code" required>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="country" class="form-label">Country</label>
                <input type="text" class="form-control" id="country" name="country" required>
              </div>
            </div>

            <button type="submit" class="btn btn-pink w-100 rounded-pill">Continue to Checkout</button>
          </form>
        </div>
      </div>
    </div>
  </div>


@endsection