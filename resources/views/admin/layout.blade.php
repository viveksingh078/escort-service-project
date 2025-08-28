
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title', 'Admin Dashboard')</title>
  <!-- base:css -->

  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <link rel="stylesheet" href="{{ asset('dash/vendors/typicons/typicons.css') }}">
  <link rel="stylesheet" href="{{ asset('dash/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('dash/css/vertical-layout-light/style.css') }}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{ asset(get_option('favicon')) }}" />
  {{-- jQuery CDN --}}
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

  {{-- Toast Plugin CSS and JS --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-toast-plugin@1.3.2/dist/jquery.toast.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery-toast-plugin@1.3.2/dist/jquery.toast.min.js"></script>

  {{-- Your Custom Toast Functions --}}
  <script src="{{ asset('dash/js/toastDemo.js') }}"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


</head>
<body>


  <div class="container-scroller">

      @include('admin.header')
      <!-- partial -->

      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial -->
      
      <div class="main-panel">
      <div class="content-wrapper">
           @yield('content')
      </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
         @include('admin.footer')
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

