<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
<<<<<<< HEAD
    <title>{{ config('app.name', 'Laravel') }}</title>
=======
    <title>@yield('title', 'Escort')</title>

    <link rel="stylesheet" href="{{ asset('dash/vendors/typicons/typicons.css') }}">
    <link rel="stylesheet" href="{{ asset('dash/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('dash/css/vertical-layout-light/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/file-uploader-single.css') }}">
>>>>>>> 23c30d7 (Escort project)
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Vite Assets (Laravel 9+) -->
<<<<<<< HEAD
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
=======
   @vite(['resources/sass/app.scss', 'resources/js/app.js']) 
>>>>>>> 23c30d7 (Escort project)
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome/css/all.css') }}" />
    <!-- Owl Carousel & Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front-style.css') }}">
    <link rel="icon" type="image/png" href="{{ asset(get_option('favicon')) }}">
    <!-- Custom Script -->
    <script src="{{ asset('js/custum.js') }}" defer></script>
     {{-- jQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<<<<<<< HEAD
=======
      <!-- Fancybox JS & CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"/>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
>>>>>>> 23c30d7 (Escort project)
  </head>
  <body>
    <div id="app" class="bg-white">
      <header>
        <div id="header-top" class="container-xl py-3">
          <div class="px-1 py-1 bg-white row align-items-center">
            <div class="pt-1 col-lg-2 align-items-center d-flex">
              <div class="px-1 py-1 bg-white col-23 align-items-center">
                <a href="{{ route('home'); }}"><p
                  class="text-black fs-4 fw-semibold font-family-Inter m-0 px-3 py-2 d-none d-lg-block"
                  >
                  <img src="{{ asset(get_option('logo')) }}" alt="logo" height="">
                </p>
              </a>
            </div>
          </div>
          <div class="bg-white rounded-4 col-lg-7 row">
            <form>
              <div class="input-group position-relative">
                <input
                type="text"
                class="form-control rounded-4 pe-5 br-25 h-45"
                placeholder="Search for escorts, services, etc."
                />
                <button
                class="btn btn-outline-secondary border-0 rounded-4 position-absolute end-0 zindex-99 search_top_center"
                type="submit"
                >
                <i class="fa-solid fa-magnifying-glass"></i>
                </button>
              </div>
            </form>
          </div>
          
          @if(Auth::guard('fan')->check())
          {{-- <a class="w-100 br-25 text-center btn btn-primary bg-main border-0 text-white fs-6 fw-normal font-family-Inter text-uppercase m-0 px-3 py-2" href="{{ route('fan.logout') }}">Logout</a> --}}
          @elseif(Auth::guard('escort')->check())
          {{-- <a class="w-100 br-25 text-center btn btn-primary bg-main border-0 text-white fs-6 fw-normal font-family-Inter text-uppercase m-0 px-3 py-2" href="{{ route('escort.logout') }}">Logout</a> --}}
          @else
          <div class="px-1 py-1 col-6 col-lg-1 justify-content-center align-items-center d-flex mt-2 mt-lg-0">
            <a href="{{ route('login') }}"
<<<<<<< HEAD
              class="w-100 br-25 text-center btn btn-primary bg-main border-0 text-white fs-6 fw-normal font-family-Inter text-uppercase m-0 px-3 py-2">
=======
              class="w-100 br-25 text-center btn category-btn fw-semibold  bg-main border-0 text-white fs-6 fw-normal font-family-Inter text-uppercase m-0 px-3 py-2">
>>>>>>> 23c30d7 (Escort project)
              Login
            </a>
          </div>
          @endif
          <div
            class="pe-1 py-px rounded-4 col-6 col-lg-2 align-items-center justify-content-end d-flex mt-2 mt-lg-0"
            >
            <div class="py-1 justify-content-center align-items-center w-100">
              <a
<<<<<<< HEAD
                class="btn-hover text-center btn btn-outline-dark br-25 text-black fs-6 badge-pill fw-normal font-family-Inter col-12 m-0 px-3 py-2"
=======
                class="btn-hover text-center btn ads1-btn fw-semibold  br-25 text-white fs-6 badge-pill fw-normal font-family-Inter col-12 m-0 px-3 py-2"
>>>>>>> 23c30d7 (Escort project)
                >
                Post Your Ad
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="header_bottom">
<<<<<<< HEAD
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
=======
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-0 bottom-nav">
>>>>>>> 23c30d7 (Escort project)
          <div class="container-fluid">
            <a class="navbar-brand" href="#">
              <img
              src="{{ asset(get_option('logo')) }}"
              alt="Logo"
              width="30"
              height="24"
              class="d-inline-block align-text-top d-lg-none"
              />
            </a>
            <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarScroll"
            aria-controls="navbarScroll"
            aria-expanded="false"
            aria-label="Toggle navigation"
            >
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
              <ul
                class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll"
                style="--bs-scroll-height: 200px"
                >
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="#"
                  >ESCORTS</a
                  >
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#">ESCORT INBOUND</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#">SM/FETISH/BDSM</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#">TRANSSEXUALS</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#">BROTHELS</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link text-white" href="#">ESCORT AGENCIES</a>
                </li>
              </ul>
              <div class="d-flex">
                <ul class="d-flex list-unstyled mb-0 gap-4">
                  <li>
                    <a href="{{ route('support') }}" class="text-white">
                      <span><i class="fa-solid fa-envelope text-main me-2"></i></span>
                       Support
                    </a>
                  </li>
                  @if(Auth::guard('fan')->check())
                  <li>
                    <a href="{{ route('fan.dashboard') }}" class="text-white">
                      <span><i class="fa-solid fa-user-tie text-main me-2"></i></span>
                      My Account
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('fan.logout') }}" class="text-white">
                      <span><i class="fa-solid fa-right-from-bracket text-main me-2"></i></span>
                    Logout</a>
                  </li>
                  @elseif(Auth::guard('escort')->check())
                  <li>
                    <a href="{{ route('escort.dashboard') }}" class="text-white">
                      <span><i class="fa-solid fa-user-tie text-main me-2"></i></span>
                      My Account
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('escort.logout') }}" class="text-white">
                      <span><i class="fa-solid fa-right-from-bracket text-main me-2"></i></span>
                    Logout</a>
                  </li>
                  @else
                  <li>
                    <a href="{{ route('register') }}" class="text-white">
                      <span><i class="fa-solid fa-user-tie text-main me-2"></i></span>
                      signup
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('login') }}" class="text-white">
                    <span><i class="fa-solid fa-right-from-bracket text-main me-2"></i></span
                    >login
                  </a>
                </li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </nav>
    </div>
  </header>
  <!-- MAIN CONTENT -->
  <main class="main">
    @yield('content')
  </main>
  <!-- FOOTER -->
  <footer class="bg-dark py-5">
    <div class="container">
      <p class="text-white text-center mb-3">
        <a href="#" class="text-white">Home</a> |
        <a href="#" class="text-white">About Us</a> |
        <a href="#" class="text-white">Contact Us</a> |
        <a href="#" class="text-white">Privacy</a> |
        <a href="#" class="text-white">Terms</a>
      </p>
      <p class="text-white text-center text-light mb-3">
        This website is an advertising and information resource. We are not an escort agency. We do not control third-party content or services linked from this site. All advertised individuals were at least 18 years old at the time of photography.
      </p>
      <p class="text-center text-white">©2025 Escort Website</p>
    </div>
  </footer>
</div>
<<<<<<< HEAD
<!-- JS Scripts -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
=======

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

<!-- JS Scripts -->
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('js/file-uploader-single.js') }}"></script>
>>>>>>> 23c30d7 (Escort project)
<script>
$(document).ready(function () {
$(".owl-carousel").owlCarousel({
loop: true,
margin: 40,
responsiveClass: true,
dots: false,
responsive: {
0: { items: 1, nav: true },
600: { items: 4, nav: false },
1000: { items: 3, nav: true }
}
});
});
</script>
</body>
</html>