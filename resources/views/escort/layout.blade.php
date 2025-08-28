
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title', 'Escort Dashboard')</title>
  <!-- base:css -->

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="{{ asset('css/escort-dashboard.css') }}">

  <link rel="stylesheet" href="{{ asset('dash/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('dash/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('dash/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset(get_option('favicon')) }}" />

    {{-- jQuery CDN --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.min.js"></script>
</head>
<body>


  <div class="container-scroller">

      @include('escort.header')
      <!-- partial -->

      <!-- partial:partials/_sidebar.html -->
      @include('escort.sidebar')
      <!-- partial -->
      
      <div class="main-panel">
      <div class="content-wrapper py-4">
           @yield('content')
      </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
         @include('escort.footer')
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->


  @php
      $showEmailVerifyPopup = false;
      if (Auth::guard('escort')->user()->email_verified != 1) {
          $showEmailVerifyPopup = true;
      }
  @endphp

    @if ($showEmailVerifyPopup)

    <div class="modal fade" id="emailVerifyModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="emailVerifyModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content border-0">
          <div class="modal-header d-flex justify-content-between align-items-center">
            <h5 class="modal-title p-0 m-0 fw-light" id="emailVerifyModalLabel">Email Verification Required</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin: -33px -16px -28px auto !important;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body pt-0 mt-0">
            <p>Please check your email for a verification link.</p>

              @if (session('success'))
                  <div class="alert alert-success alert-sm mt-2 mb-1 py-1 px-2">
                      {{ session('success') }}
                  </div>
              @endif

              @if (session('error'))
                  <div class="alert alert-danger alert-sm mt-2 mb-1 py-1 px-2">
                      {{ session('error') }}
                  </div>
              @endif


              <form method="POST" action="{{ route('resend.verification', ['email' => Crypt::encryptString(Auth::guard('escort')->user()->email)]) }}">
                  @csrf
                  <div class="d-flex justify-content-between mt-4">
                      <button type="submit" class="btn btn-sm btn-primary">Send Again</button>
                  </div>
              </form>

          </div>
        </div>
      </div>
    </div>

    <!-- Show Modal on Page Load -->
    <script>
    jQuery(document).ready(function(){
        jQuery('#emailVerifyModal').modal('show');
    });
    </script>

  @endif

  <!-- base:js -->
  <script src="{{ asset('dash/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="{{ asset('dash/vendors/chart.js/Chart.min.js') }}"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('dash/js/off-canvas.js') }}"></script>
  <script src="{{ asset('dash/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('dash/js/template.js') }}"></script>
  <script src="{{ asset('dash/js/settings.js') }}"></script>
  <script src="{{ asset('dash/js/todolist.js') }}"></script>
  <!-- endinject -->
  <!-- End custom js for this page-->
</body>

</html>

