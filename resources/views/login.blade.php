@extends('layouts.app')
@section('content')

  <link rel="stylesheet" href="{{ asset('css/user-login-style.css') }}">

    <div class="container-fluid login-page p-0 m-0">
      <div class="container py-4">

            <!-- Background spheres -->
      <div class="sphere sphere-1">
        <img src="{{ asset('images/login-Image_1.png') }}" alt="">
      </div>
      <div class="sphere sphere-2">
        <img src="{{ asset('images/login-Image_2.png') }}" alt="">
      </div>
      <div class="sphere sphere-3">
        <img src="{{ asset('images/login-Image_3.png') }}" alt="">
      </div>


        <div class="row">
          <div class="col-sm-12 col-lg-6 offset-lg-3 col-md-6 offset-md-3">
            
            <!-- Login Card -->
            <div class="d-flex justify-content-center align-items-center">
              <div class="glass-card">
                <div class="text-center mb-4">
                  <h2 class="title mb-2">Welcome Back</h2>
                  <p class="text-muted fw-bold small">Sign in to your account</p>
                    @if(Session::has('success'))
                    <p class="small text-success">{{ Session::get('success') }}</p>
                    @endif
                    @if(Session::has('error'))
                    <p class="small text-danger">{{ Session::get('error') }}</p>
                    @endif
                </div>
                <form action="{{ route('authenticate') }}" method="post" >
                  @csrf
                  <div class="form-group mb-3">
                    <label for="email" class="fw-bold mb-1 text-muted small">Email</label>
                    <input type="text" name="email" value="{{old('email')}}" class="form-control input-field @error('email') is-invalid @enderror" id="email" placeholder="Enter your email">
                     @error('email')
                       <p class="invalid-feedback">{{$message}}</p>
                     @enderror
                  </div>
                  <div class="form-group mb-1 show-password">
                    <label for="password" class="fw-bold mb-1 text-muted small">Password</label>
                      <input type="password" name="password" class="form-control input-field @error('password') is-invalid @enderror" id="password" value="" placeholder="Password">
                      <button class="show-password-btn">
                        <i class="fas fa-eye show-password-icon"></i>
                      </button>
                    @error('password')
                      <p class="invalid-feedback">{{$message}}</p>
                    @enderror
                  </div>
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                      
                    </div>
                    <a href="#" class="text-muted fw-normal text-decoration-none small">Forgot password?</a>
                  </div>
                  <button type="submit" class="btn btn-login w-100">LOGIN</button>
                </form>
                <div class="text-center mt-4">
                  <p class="text-muted fw-bolder small">Don't have an account? <a href="{{ route('register')}}" class="font-weight-bold">Sign up</a></p>
                </div>
              </div>
            </div>
            
          </div>
        </div>
      </div>
    </div>


      <script>

      document.addEventListener("DOMContentLoaded", function () {
        const toggleBtn = document.querySelector(".show-password-btn");
        const passwordInput = document.getElementById("password");
        const icon = toggleBtn.querySelector("i");

        toggleBtn.addEventListener("click", function (e) {
          e.preventDefault();
          const type = passwordInput.getAttribute("type");
          if (type === "password") {
            passwordInput.setAttribute("type", "text");
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
          } else {
            passwordInput.setAttribute("type", "password");
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
          }
        });
      });


      document.addEventListener('DOMContentLoaded', function () {

        const inputs = document.querySelectorAll('.input-field');

        inputs.forEach(input => {
          input.addEventListener('focus', function () {
            const icon = this.parentElement.querySelector('i');
            if (icon) icon.style.transform = 'scale(1.2)';
          });

          input.addEventListener('blur', function () {
            const icon = this.parentElement.querySelector('i');
            if (icon) icon.style.transform = 'scale(1)';
          });
        });

        const card = document.querySelector('.glass-card');
        let floatValue = 0, floatDirection = 1;

        function floatCard() {
          floatValue += 0.02 * floatDirection;
          if (floatValue > 3) floatDirection = -1;
          if (floatValue < -3) floatDirection = 1;
          card.style.transform = `translateY(${floatValue}px)`;
          requestAnimationFrame(floatCard);
        }
        floatCard();

        const spheres = document.querySelectorAll('.sphere');
        spheres.forEach(sphere => {
          sphere.addEventListener('mouseenter', () => {
            sphere.style.transform = 'scale(1.1)';
            sphere.style.opacity = '1';
          });
          sphere.addEventListener('mouseleave', () => {
            sphere.style.transform = 'scale(1)';
            sphere.style.opacity = '0.8';
          });
        });
      });
    </script>
    
@endsection