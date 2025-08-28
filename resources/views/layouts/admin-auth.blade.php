<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Escort') }}</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">

    <!-- App CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome/css/all.css') }}">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">

    <!-- Custom Frontend Style -->
    <link rel="stylesheet" href="{{ asset('css/front-style.css') }}">


    <link rel="shortcut icon" href="{{ asset(get_option('favicon')) }}" />

    <!-- Content Security & Security Headers -->
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' 'unsafe-inline' https:; style-src 'self' 'unsafe-inline' https:; img-src 'self' data: https:;">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
  </head>

  <body>
    <div id="app">
      <main>
        @yield('content')
      </main>

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
            This website is an advertising and information resource only. We are not an escort agency. 
            We take no responsibility for the content or actions of third-party websites or individuals. 
            All models listed were at least 18 years old when photographed.
          </p>
          <p class="text-center text-white">©2025 Escort Website</p>
        </div>
      </footer>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/custum.js') }}" defer></script>

    <script>
      $(document).ready(function () {
        $(".owl-carousel").owlCarousel({
          loop: true,
          margin: 40,
          responsiveClass: true,
          dots: false,
          responsive: {
            0: {
              items: 1,
              nav: true
            },
            600: {
              items: 4,
              nav: false
            },
            1000: {
              items: 3,
              nav: true
            }
          }
        });
      });
    </script>
  </body>
</html>
