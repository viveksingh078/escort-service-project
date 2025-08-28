<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title', 'Fan Dashboard')</title>
  <!-- base:css -->

  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="{{ asset('dash/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('dash/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('dash/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset(get_option('favicon')) }}" />
</head>
<body>


  <div class="container-scroller">

      @include('fan.header')
      <!-- partial -->

      <!-- partial:partials/_sidebar.html -->
      @include('fan.sidebar')
      <!-- partial -->
      
      <div class="main-panel">
      <div class="content-wrapper">
           @yield('content')
      </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
         @include('fan.footer')
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

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
  <!-- Custom js for this page-->
  <script src="js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

