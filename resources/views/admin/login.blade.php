<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
      <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="shortcut icon" href="{{ asset(get_option('favicon')) }}" />
  </head>

  <style>

    @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');


    .admin-form{
      background: #E8555A;
      background: radial-gradient(circle, rgba(232, 85, 90, 1) 0%, rgba(23, 23, 23, 1) 100%);
      font-family: "Montserrat", sans-serif;
      min-height:100vh;
      display:flex;
      justify-content:center;
      align-items:center;
    }

    .admin-form input{
      border: 1px solid #FF6CEC;
      border-radius: 50px;
      font-size: 14px;
      height: 50px;
    }

    .admin-form button {
       border: 1px solid #FF6CEC;
       background: linear-gradient(217deg, rgba(255, 108, 236, 1) 0%, rgba(133, 54, 122, 1) 100%);
       transition: background 1s ease; /* slow and smooth transition */
       border-radius: 50px;
    }

    .admin-form button:hover {
       border: 1px solid #FF6CEC;
       background: linear-gradient(217deg, rgba(255, 108, 236, 1) 0%, rgba(133, 54, 122, 1) 100%);
       box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
    }

  .pass-show {
    position: relative;
  }

  .pass-icon{
    position:absolute;
    right: 15px;
    top: 17px;
  }


  </style>
  <body>

    <div class="container-fluid p-0 m-0 admin-form">
      <div class="container py-5">

        <div class="row">
          <div class="col-sm-12 col-lg-6 offset-lg-3 col-md-6 offset-md-3">

            <form action="{{ route('admin.authenticate') }}" method="post" class="bg-white py-5 px-5 shadow-sm mx-5 rounded">
                @csrf

              <h5 class="text-center mb-0">Admin Login</h5>

              <div class="row">
                <div class="col-12">
                  @if(Session::has('success'))
                  <div class="alert alert-success mt-4">{{ Session::get('success') }}</div>
                  @endif
                  @if(Session::has('error'))
                  <div class="alert alert-danger mt-4">{{ Session::get('error') }}</div>
                  @endif
                </div>
              </div>

              <hr class="mb-4">

              <div class="form-group mt-4">
                <input type="text" name="login" id="login" class="form-control form-control-lg shadow-none @error('login') is-invalid @enderror" placeholder=" Email or Username" value="{{old('login')}}">
                @error('login')
                 <p class="invalid-feedback">{{$message}}</p>
                @enderror
              </div>

              <div class="form-group mt-4 pass-show">
                <input type="text"  name="password" id="password" class="form-control form-control-lg shadow-none @error('password') is-invalid @enderror" placeholder=" Enter Password">
                  <i class="fas fa-eye pass-icon" ></i>
                @error('password')
                 <p class="invalid-feedback">{{$message}}</p>
                @enderror
              </div>

              <div class="form-group mt-4">
                <button class="btn btn-primary btn-lg btn-block w-100" type="submit">Login</button>
              </div>
              
            </form>
            
          </div>
        </div>
        
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const passGroup = document.querySelector('.pass-show');
        const passInput = passGroup.querySelector('input');
        const passIcon = passGroup.querySelector('.pass-icon');

        passIcon.addEventListener('click', function () {
          const isPassword = passInput.type === 'password';
          passInput.type = isPassword ? 'text' : 'password';
          passIcon.classList.toggle('fa-eye');
          passIcon.classList.toggle('fa-eye-slash');
        });
      });
    </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  </body>
</html>